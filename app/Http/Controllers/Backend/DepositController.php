<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use Illuminate\Support\Facades\Auth;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DepositController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->middleware(['auth', 'admin']);
        // $this->middleware('permission:deposit-list|deposit-create|deposit-edit|deposit-delete', ['only' => ['index','store']]);
        // $this->middleware('permission:deposit-create', ['only' => ['create','store']]);
        // $this->middleware('permission:deposit-edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:deposit-delete', ['only' => ['destroy']]);
        // $this->middleware('permission:deposit-assign', ['only' => ['assignPage', 'assignToPackage', 'removeFromPackage', 'assignEdit']]);
    }

    /**
     * Admin: সব Deposits দেখান
     */
    public function index(Request $request)
    {
        $query = Deposit::with('user')->latest();

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->has('payment_method') && $request->payment_method !== 'all') {
            $query->where('payment_method', $request->payment_method);
        }

        // Search by reference or transaction ID
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhere('transaction_id', 'like', "%{$search}%")
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

        $deposits = $query->paginate(20);

        $statsQuery = Deposit::whereHas('user', function($q) {
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

        return view('backend.deposits.index', compact('deposits', 'stats'));
    }

    /**
     * Admin: Deposit approve করুন
     */
    // public function approve($id)
    // {
    //     try {
    //         $deposit = Deposit::findOrFail($id);

    //         if ($deposit->status !== 'pending') {
    //             return back()->with('error', 'This deposit has already been processed.');
    //         }

    //         // Wallet এ টাকা জমা করুন
    //         app(WalletService::class)->credit(
    //             userId: $deposit->user_id,
    //             amount: $deposit->amount,
    //             type: 'deposit',
    //             referenceType: 'Deposit',
    //             referenceId: $deposit->id,
    //             description: "Deposit approved - {$deposit->reference_number}"
    //         );

    //         // Deposit status update
    //         $deposit->update([
    //             'status' => 'approved',
    //             'approved_at' => now(),
    //             'approved_by' => Auth::id()
    //         ]);

    //         // Optional: Send notification to user
    //         // event(new DepositApproved($deposit));

    //         return back()->with('success', "Deposit {$deposit->reference_number} approved successfully. {$deposit->amount} USDT credited to user wallet.");

    //     } catch (\Exception $e) {
    //         return back()->with('error', 'Failed to approve deposit: ' . $e->getMessage());
    //     }
    // }
    public function approve($id)
{
    try {
        $deposit = Deposit::findOrFail($id);

        if ($deposit->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Already processed.'
            ], 422);
        }

        app(WalletService::class)->credit(
            userId: $deposit->user_id,
            amount: $deposit->amount,
            type: 'deposit',
            referenceType: 'Deposit',
            referenceId: $deposit->id,
            description: "Deposit approved - {$deposit->reference_number}"
        );

        $deposit->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Deposit approved.'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Admin: Deposit reject করুন
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'reject_reason' => 'required|string|min:10|max:500'
        ], [
            'reject_reason.required' => 'Please provide a reason for rejection',
            'reject_reason.min' => 'Reason must be at least 10 characters'
        ]);

        try {
            $deposit = Deposit::findOrFail($id);

            if ($deposit->status !== 'pending') {
                return back()->with('error', 'This deposit has already been processed.');
            }

            $deposit->update([
                'status' => 'rejected',
                'reject_reason' => $request->reject_reason,
                'approved_at' => now(),
                'approved_by' => Auth::id()
            ]);

            // Optional: Send notification to user
            // event(new DepositRejected($deposit));

            return back()->with('success', "Deposit {$deposit->reference_number} rejected.");

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reject deposit: ' . $e->getMessage());
        }
    }

    /**
     * Admin: View deposit details
     */
    public function show($id)
    {
        $deposit = Deposit::with(['user', 'approver'])->findOrFail($id);

        return view('backend.deposits.show', compact('deposit'));
    }

    /**
     * Delete deposit (Admin only - use carefully)
     */
    public function destroy($id)
    {
        try {
            $deposit = Deposit::findOrFail($id);

            // Only allow deleting rejected or very old pending deposits
            if ($deposit->status === 'approved') {
                return back()->with('error', 'Cannot delete approved deposits.');
            }

            $deposit->delete();

            return back()->with('success', 'Deposit deleted successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete deposit: ' . $e->getMessage());
        }
    }
}
