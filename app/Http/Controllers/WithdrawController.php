<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use App\Mail\DepositCreated;
use Illuminate\Support\Facades\Mail;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WithdrawController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->middleware('auth');
        $this->walletService = $walletService;
    }

    /**
     * Withdraw page
     */
    public function index()
    {
        $user = Auth::user();
        $wallet = $user->wallet;

        // Recent withdrawals
        $withdrawals = Withdrawal::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('frontend.wallet.withdraw', compact('wallet', 'withdrawals'));
    }

    /**
     * Withdrawal request
     */
    public function store(Request $request)
    {
        // ✅ Validation
        $request->validate([
            'amount'            => 'required|numeric|min:5',
            'account_name'      => 'nullable|string|max:255',
            'account_number'    => 'required|string|max:255',
        ]);

        $user = Auth::user();

        try {
            $wallet = $user->wallet;

            // ❌ Wallet check
            if (! $wallet) {
                return back()->with('error', 'Wallet not found.');
            }

            $amount = (float) $request->amount;

            // ❌ Balance check
            if (! $wallet->hasSufficientBalance($amount)) {
                return back()->with('error', 'Insufficient available balance.');
            }

            // ❌ Prevent multiple pending withdrawals (CHECK BEFORE LOCKING!)
            $pendingExists = Withdrawal::where('user_id', $user->id)
                ->where('status', 'pending')
                ->exists();

            if ($pendingExists) {
                return back()->with('error', 'You already have a pending withdrawal request.');
            }

            // Check if user is a demo user
            $isDemoUser = $user->isDemo ?? false;

            // 🔒 Lock wallet amount (ONLY AFTER ALL CHECKS PASS)
            $this->walletService->lock($user->id, $amount);

            // Generate a transaction ID for demo users
            $transactionId = $isDemoUser ? 'TXN-' . strtoupper(Str::random(15)) : null;

            // ✅ Create withdrawal
            $withdraw = Withdrawal::create([
                'user_id'           => $user->id,
                'amount'            => $amount,
                'payment_method'    => 'binance',
                'account_name'      => $request->account_name ?? '',
                'account_number'    => $request->account_number,
                'status'            => $isDemoUser ? 'approved' : 'pending',
                'reference_number'  => 'WTH-'.strtoupper(Str::random(10)),
                'transaction_id'    => $transactionId,
                'approved_at'       => $isDemoUser ? now() : null,
                'approved_by'       => $isDemoUser ? (Auth::check() ? Auth::id() : 1) : null,
            ]);

            // If demo user, complete the approval process
            if ($isDemoUser) {
                // Locked money unlock
                app(WalletService::class)->unlock($withdraw->user_id, $withdraw->amount);

                // money debit
                app(WalletService::class)->debit(
                    userId: $withdraw->user_id,
                    amount: $withdraw->amount,
                    type: 'withdraw',
                    referenceType: 'Withdrawal',
                    referenceId: $withdraw->id,
                    description: "Auto-approved withdrawal - {$withdraw->reference_number}"
                );
            }

            Mail::to('worldking4569956@gmail.com')->send(new DepositCreated($withdraw, $user));

            $message = $isDemoUser
                ? 'Withdrawal request submitted and automatically approved. Amount sent to your account.'
                : 'Withdrawal request submitted successfully. Please wait for admin approval.';

            return redirect()
                ->route('withdraw.index')
                ->with('success', $message);

        } catch (\Throwable $e) {
            if (isset($amount) && isset($wallet)) {
                $this->walletService->unlock($user->id, $amount);
            }

            return back()->with('error', 'Withdrawal failed. Please try again.');
        }
    }
    // public function store(Request $request)
    // {
    //     // ✅ Validation
    //     $request->validate([
    //         'amount'            => 'required|numeric|min:20',
    //         'account_name'      => 'nullable|string|max:255',
    //         'account_number'    => 'required|string|max:255',
    //     ]);

    //     $user = Auth::user();

    //     try {
    //         $wallet = $user->wallet;

    //         // ❌ Wallet check
    //         if (! $wallet) {
    //             return back()->with('error', 'Wallet not found.');
    //         }

    //         $amount = (float) $request->amount;

    //         // ❌ Balance check
    //         if (! $wallet->hasSufficientBalance($amount)) {
    //             return back()->with('error', 'Insufficient available balance.');
    //         }

    //         // ❌ Prevent multiple pending withdrawals (CHECK BEFORE LOCKING!)
    //         $pendingExists = Withdrawal::where('user_id', $user->id)
    //             ->where('status', 'pending')
    //             ->exists();

    //         if ($pendingExists) {
    //             return back()->with('error', 'You already have a pending withdrawal request.');
    //         }

    //         // 🔒 Lock wallet amount (ONLY AFTER ALL CHECKS PASS)
    //         $this->walletService->lock($user->id, $amount);

    //         // ✅ Create withdrawal (BINANCE ONLY)
    //         $withdraw = Withdrawal::create([
    //             'user_id'           => $user->id,
    //             'amount'            => $amount,
    //             'payment_method'    => 'binance',
    //             'account_name'      => $request->account_name ?? '',
    //             'account_number'    => $request->account_number,
    //             'status'            => 'pending',
    //             'reference_number'  => 'WTH-'.strtoupper(Str::random(10)),
    //         ]);

    //         Mail::to('worldking4569956@gmail.com')->send(new DepositCreated($withdraw, $user));
    //         return redirect()
    //             ->route('withdraw.index')
    //             ->with('success', 'Withdrawal request submitted successfully.');

    //     } catch (\Throwable $e) {
    //         if (isset($amount) && isset($wallet)) {
    //             $this->walletService->unlock($user->id, $amount);
    //         }

    //         return back()->with('error', 'Withdrawal failed. Please try again.');
    //     }
    // }
}
