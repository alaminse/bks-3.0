<?php

namespace App\Services;

use App\Models\User;
use App\Models\Referral;
use App\Models\ReferralEarning;
use App\Models\ReferralSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReferralService
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    // ═══════════════════════════════════════════════════════════════
    // CREATE REFERRAL ON REGISTRATION
    // ═══════════════════════════════════════════════════════════════

    public function createReferral($newUserId, $referralCode)
    {
        $referrer = User::where('referral_code', $referralCode)->first();

        if (!$referrer) {
            Log::warning("Invalid referral code: {$referralCode}");
            return null;
        }

        $existing = Referral::where('referrer_id', $referrer->id)
            ->where('referred_id', $newUserId)
            ->first();

        if ($existing) return $existing;

        $referral = Referral::create([
            'referrer_id'   => $referrer->id,
            'referred_id'   => $newUserId,
            'referral_code' => $referralCode,
            'status'        => 'pending',
        ]);

        $referrer->incrementReferralCount();

        User::where('id', $newUserId)->update(['referred_by' => $referrer->id]);

        Log::info("Referral created: User {$referrer->id} referred User {$newUserId}");

        return $referral;
    }

    // ═══════════════════════════════════════════════════════════════
    // PACKAGE PURCHASE COMMISSION (multi-generation)
    // ═══════════════════════════════════════════════════════════════

    public function processPackageCommission($packagePurchaseId, $packageAmount, $buyerId)
    {
        return $this->processCommission(
            triggerUserId:  $buyerId,
            amount:         $packageAmount,
            type:           'package',
            referenceType:  'PackagePurchase',
            referenceId:    $packagePurchaseId,
            description:    "package purchase ($" . number_format($packageAmount, 2) . ")"
        );
    }

    // ═══════════════════════════════════════════════════════════════
    // TASK COMPLETION COMMISSION (multi-generation)
    // ═══════════════════════════════════════════════════════════════

    public function processTaskCommission($submissionId, $taskReward, $userId)
    {
        return $this->processCommission(
            triggerUserId:  $userId,
            amount:         $taskReward,
            type:           'task',
            referenceType:  'UserTaskSubmission',
            referenceId:    $submissionId,
            description:    "task completion (reward: $" . number_format($taskReward, 2) . ")"
        );
    }

    // ═══════════════════════════════════════════════════════════════
    // CORE MULTI-GENERATION ENGINE
    // ═══════════════════════════════════════════════════════════════

    private function processCommission(
        int    $triggerUserId,
        float  $amount,
        string $type,
        string $referenceType,
        int    $referenceId,
        string $description
    ): array {
        DB::beginTransaction();

        try {
            $ancestors = $this->getAncestorChain($triggerUserId);

            if (empty($ancestors)) {
                DB::commit();
                return ['success' => false, 'message' => 'No referrer found for this user'];
            }

            $maxGen    = ReferralSetting::maxActiveGeneration();
            $results   = [];
            $totalPaid = 0;

            foreach ($ancestors as $generation => $ancestor) {
                if ($generation > $maxGen) break;

                $rate = ReferralSetting::rateForGeneration($generation);

                if ($rate <= 0) continue;

                $commission = round(($amount * $rate) / 100, 2);

                // Find referral record
                $directChildId = $generation === 1
                    ? $triggerUserId
                    : ($ancestors[$generation - 1]->id ?? null);

                $referral = $directChildId
                    ? Referral::where('referrer_id', $ancestor->id)
                               ->where('referred_id', $directChildId)
                               ->first()
                    : null;

                // Activate referral if still pending (Level 1 only)
                if ($referral && $generation === 1 && $referral->status === 'pending') {
                    $referral->activate();
                }

                // Create earning record
                $earning = ReferralEarning::create([
                    'referral_id'     => $referral?->id,
                    'referrer_id'     => $ancestor->id,
                    'referred_id'     => $triggerUserId,
                    'type'            => $type,
                    'generation'      => $generation,
                    'amount'          => $commission,
                    'commission_rate' => $rate,
                    'status'          => 'approved',
                    'reference_type'  => $referenceType,
                    'reference_id'    => $referenceId,
                    'description'     => "Level {$generation} commission from {$description}",
                ]);

                // Credit wallet
                $wallet = $this->walletService->credit(
                    userId:        $ancestor->id,
                    amount:        $commission,
                    type:          'referral',
                    referenceType: 'ReferralEarning',
                    referenceId:   $earning->id,
                    description:   "Level {$generation} referral — user #{$triggerUserId} {$description}"
                );

                // Link wallet transaction
                $tx = $wallet->transactions()
                    ->where('type', 'referral')
                    ->where('reference_type', 'ReferralEarning')
                    ->where('reference_id', $earning->id)
                    ->latest()
                    ->first();

                $earning->update([
                    'wallet_transaction_id' => $tx?->id,
                    'paid_at'               => now(),
                    'status'                => 'paid',
                ]);

                // Update ancestor's total referral earnings
                $ancestor->addToReferralEarnings($commission);

                $totalPaid += $commission;
                $results[]  = [
                    'generation' => $generation,
                    'user_id'    => $ancestor->id,
                    'commission' => $commission,
                    'rate'       => $rate,
                ];

                Log::info("Referral commission Gen{$generation}: \${$commission} → User {$ancestor->id} (trigger: {$type} #{$referenceId})");
            }

            DB::commit();

            return [
                'success'     => true,
                'message'     => 'Multi-generation commissions processed',
                'total_paid'  => $totalPaid,
                'generations' => $results,
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Commission failed ({$type}): " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // ═══════════════════════════════════════════════════════════════
    // WALK UP ANCESTOR CHAIN
    // Returns [1 => User(direct referrer), 2 => User(their referrer), ...]
    // ═══════════════════════════════════════════════════════════════

    public function getAncestorChain(int $userId): array
    {
        $maxGen    = ReferralSetting::maxActiveGeneration();
        $ancestors = [];
        $currentId = $userId;

        for ($gen = 1; $gen <= $maxGen; $gen++) {
            $user = User::find($currentId);
            if (!$user || !$user->referred_by) break;

            $parent = User::find($user->referred_by);
            if (!$parent) break;

            $ancestors[$gen] = $parent;
            $currentId       = $parent->id;
        }

        return $ancestors;
    }

    // ═══════════════════════════════════════════════════════════════
    // STATS
    // ═══════════════════════════════════════════════════════════════

    public function getReferralStats($userId)
    {
        $user          = User::find($userId);
        $walletBalance = $this->walletService->getBalance($userId);

        $earningsByGeneration = ReferralEarning::where('referrer_id', $userId)
            ->where('status', 'paid')
            ->selectRaw('generation, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('generation')
            ->orderBy('generation')
            ->get()
            ->keyBy('generation');

        return [
            'total_referrals'        => $user->total_referrals,
            'active_referrals'       => $user->getActiveReferralsCount(),
            'pending_referrals'      => $user->referrals()->where('status', 'pending')->count(),
            'total_earnings'         => $user->total_referral_earnings,
            'approved_earnings'      => $user->getTotalApprovedEarnings(),
            'pending_earnings'       => $user->getPendingEarnings(),
            'this_month_earnings'    => ReferralEarning::where('referrer_id', $userId)
                                            ->where('status', 'paid')
                                            ->whereMonth('created_at', now()->month)
                                            ->sum('amount'),
            'earnings_by_generation' => $earningsByGeneration,
            'wallet_balance'         => $walletBalance['balance'],
            'available_balance'      => $walletBalance['available_balance'],
            'locked_balance'         => $walletBalance['locked_balance'],
            'referral_link'          => $user->getReferralLink(),
            'referral_code'          => $user->referral_code,
        ];
    }

    public function getRecentReferrals($userId, $limit = 10)
    {
        return Referral::where('referrer_id', $userId)
            ->with(['referred', 'earnings'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getRecentEarnings($userId, $limit = 10)
    {
        return ReferralEarning::where('referrer_id', $userId)
            ->with(['referred', 'referral'])
            ->latest()
            ->limit($limit)
            ->get();
    }
}
