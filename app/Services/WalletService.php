<?php

namespace App\Services;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Exception;

class WalletService
{
    /**
     * (Credit)
     *
     * @param int $userId -  user  wallet
     * @param float $amount - how much
     * @param string $type - which type (deposit, task, referral)
     * @param string|null $referenceType - for what (Task, Package)
     * @param int|null $referenceId - ID
     * @param string|null $description - description
     */
    public function credit(
        int $userId,
        float $amount,
        string $type,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?string $description = null
    ): Wallet {
        // money negative error
        if ($amount < 0) {
            throw new Exception("Amount must be greater than 0");
        }

        // Database transaction
        return DB::transaction(function () use ($userId, $amount, $type, $referenceType, $referenceId, $description) {

            // Add new record in Wallet
            $wallet = Wallet::firstOrCreate(
                ['user_id' => $userId],
                ['balance' => 0, 'locked_balance' => 0]
            );

            // Balance add
            $wallet->increment('balance', $amount);
            $wallet->refresh(); // new balance

            // Transaction record
            WalletTransaction::create([
                'wallet_id'         => $wallet->id,
                'type'              => $type,
                'amount'            => $amount,
                'direction'         => 'credit',
                'reference_type'    => $referenceType,
                'reference_id'      => $referenceId,
                'description'       => $description ?? "Credited $amount to wallet"
            ]);

            return $wallet;
        });
    }

    /**
     * Money (Debit)
     *
     * @throws Exception if balance low
     */
    public function debit(
        int $userId,
        float $amount,
        string $type,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?string $description = null
    ): Wallet {
        if ($amount <= 0) {
            throw new Exception("Amount must be greater than 0");
        }

        return DB::transaction(function () use ($userId, $amount, $type, $referenceType, $referenceId, $description) {

            // Wallet find and lock (same time can not request multiple)
            $wallet = Wallet::where('user_id', $userId)
                ->lockForUpdate()
                ->first();

            // If not enough found Wallet error
            if (!$wallet) {
                throw new Exception("Wallet not found");
            }

            if (!$wallet->hasSufficientBalance($amount)) {
                throw new Exception("Insufficient balance. Available: {$wallet->available_balance}");
            }

            // Balance debut
            $wallet->decrement('balance', $amount);
            $wallet->refresh();

            // Transaction record
            WalletTransaction::create([
                'wallet_id'         => $wallet->id,
                'type'              => $type,
                'amount'            => $amount,
                'direction'         => 'debit',
                'reference_type'    => $referenceType,
                'reference_id'      => $referenceId,
                'description'       => $description ?? "Debited $amount from wallet"
            ]);

            return $wallet;
        });
    }

    /**
     * money lock (like: withdraw pending)
     */
    public function lock(int $userId, float $amount): Wallet
    {
        return DB::transaction(function () use ($userId, $amount) {
            $wallet = Wallet::where('user_id', $userId)
                ->lockForUpdate()
                ->firstOrFail();

            if (!$wallet->hasSufficientBalance($amount)) {
                throw new Exception("Insufficient balance to lock");
            }

            $wallet->increment('locked_balance', $amount);

            return $wallet->refresh();
        });
    }

    /**
     * Lock to release money
     */
    public function unlock(int $userId, float $amount): Wallet
    {
        return DB::transaction(function () use ($userId, $amount) {
            $wallet = Wallet::where('user_id', $userId)
                ->lockForUpdate()
                ->firstOrFail();

            $wallet->decrement('locked_balance', $amount);

            return $wallet->refresh();
        });
    }

    /**
     * Wallet balance
     */
    public function getBalance(int $userId): array
    {
        $wallet = Wallet::where('user_id', $userId)->first();

        if (!$wallet) {
            return [
                'balance'           => 0,
                'locked_balance'    => 0,
                'available_balance' => 0
            ];
        }

        return [
            'balance'           => $wallet->balance,
            'locked_balance'    => $wallet->locked_balance,
            'available_balance' => $wallet->available_balance
        ];
    }

    /**
     * Transaction history
     */
    public function getTransactions(int $userId, int $limit = 20)
    {
        $wallet = Wallet::where('user_id', $userId)->first();

        if (!$wallet) {
            return collect();
        }

        return WalletTransaction::where('wallet_id', $wallet->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
