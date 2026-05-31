<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->middleware('auth');
        $this->middleware('admin'); // Only admin can access
        $this->walletService = $walletService;
    }

    /**
     * Admin: all Withdrawals
     */
    public function index(Request $request)
    {
        $query = Withdrawal::with('user')->latest();

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->has('payment_method') && $request->payment_method !== 'all') {
            $query->where('payment_method', $request->payment_method);
        }

        // Search by reference, account, or user
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhere('account_number', 'like', "%{$search}%")
                  ->orWhere('account_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $withdrawals = $query->paginate(20);
        $statsQuery = Withdrawal::whereHas('user', function($q) {
            $q->where('isDemo', false);
        });

        $stats = [
            // Use clone to avoid modifying the base query for each stat
            'pending_count' => (clone $statsQuery)->where('status', 'pending')->count(),
            'pending_amount' => (clone $statsQuery)->where('status', 'pending')->sum('amount'),
            'approved_today' => (clone $statsQuery)->where('status', 'approved')
                ->whereDate('approved_at', today())
                ->count(),
            'total_approved' => (clone $statsQuery)->where('status', 'approved')->sum('amount'),
        ];

        return view('backend.withdrawal.index', compact('withdrawals', 'stats'));
    }

    /**
     * Admin: Withdrawal details
     */
    public function show($id)
    {
        $withdrawal = Withdrawal::with(['user', 'approver'])->findOrFail($id);

        return view('backend.withdrawal.show', compact('withdrawal'));
    }

    /**
     * Admin: Withdrawal approve
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'transaction_id' => 'required|string|min:10|max:255',
        ], [
            'transaction_id.required' => 'Transaction ID is required',
            'transaction_id.min' => 'Transaction ID must be at least 10 characters'
        ]);

        try {
            $withdrawal = Withdrawal::findOrFail($id);

            if ($withdrawal->status !== 'pending') {
                return back()->with('error', 'This withdrawal has already been processed.');
            }

            // Locked money unlock
            app(WalletService::class)->unlock($withdrawal->user_id, $withdrawal->amount);

            // money debit
            app(WalletService::class)->debit(
                userId: $withdrawal->user_id,
                amount: $withdrawal->amount,
                type: 'withdraw',
                referenceType: 'Withdrawal',
                referenceId: $withdrawal->id,
                description: "Withdrawal approved - {$withdrawal->reference_number}"
            );

            // Withdrawal status update
            $withdrawal->update([
                'status' => 'approved',
                'transaction_id' => $request->transaction_id,
                'approved_at' => now(),
                'approved_by' => Auth::id(),
            ]);

            // Optional: Send notification
            // event(new WithdrawalApproved($withdrawal));

            return back()->with('success', "Withdrawal {$withdrawal->reference_number} approved successfully. {$withdrawal->amount} USDT sent to user.");

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to approve withdrawal: '.$e->getMessage());
        }
    }

    /**
     * Admin: Withdrawal reject করুন
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'reject_reason' => 'required|string|min:10|max:500',
        ], [
            'reject_reason.required' => 'Please provide a reason for rejection',
            'reject_reason.min' => 'Reason must be at least 10 characters'
        ]);

        try {
            $withdrawal = Withdrawal::findOrFail($id);

            if ($withdrawal->status !== 'pending') {
                return back()->with('error', 'This withdrawal has already been processed.');
            }

            // Locked টাকা unlock করুন (reject করায় টাকা ফেরত)
            app(WalletService::class)->unlock($withdrawal->user_id, $withdrawal->amount);

            $withdrawal->update([
                'status' => 'rejected',
                'reject_reason' => $request->reject_reason,
                'approved_at' => now(),
                'approved_by' => Auth::id(),
            ]);

            // Optional: Send notification
            // event(new WithdrawalRejected($withdrawal));

            return back()->with('success', "Withdrawal {$withdrawal->reference_number} rejected. Amount unlocked in user wallet.");

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reject withdrawal: '.$e->getMessage());
        }
    }

    /**
     * Delete withdrawal (Use carefully)
     */
    public function destroy($id)
    {
        try {
            $withdrawal = Withdrawal::findOrFail($id);

            // Only allow deleting rejected or very old pending
            if ($withdrawal->status === 'approved') {
                return back()->with('error', 'Cannot delete approved withdrawals.');
            }

            // If pending, unlock the amount first
            if ($withdrawal->status === 'pending') {
                app(WalletService::class)->unlock($withdrawal->user_id, $withdrawal->amount);
            }

            $withdrawal->delete();

            return back()->with('success', 'Withdrawal deleted successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete withdrawal: '.$e->getMessage());
        }
    }
}
