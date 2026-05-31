<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Models\Deposit;
use App\Models\User;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:admin-dashboard', ['only' => ['index']]);
    }

    public function index()
    {
        // Get all users with isDemo attribute
        $users = User::with(['withdrawals', 'deposits'])->get();

        // Filter out demo users for calculations
        $realUsers = $users->filter(function($user) {
            return !($user->isDemo ?? false);
        });

        // Calculate statistics excluding demo users
        $totalUsers = $realUsers->count();
        $activeUsers = $realUsers->where('status', 'active')->count();

        // Calculate total deposits excluding demo users
        $totalDeposits = $realUsers->reduce(function($carry, $user) {
            return $carry + $user->deposits->where('status', 'approved')->sum('amount');
        }, 0);

        // Calculate total withdrawals excluding demo users
        $totalWithdrawals = $realUsers->reduce(function($carry, $user) {
            return $carry + $user->withdrawals->where('status', 'approved')->sum('amount');
        }, 0);

        // Calculate net revenue
        $netRevenue = $totalDeposits - $totalWithdrawals;

        // Get approved counts
        $approvedDeposits = Deposit::where('status', 'approved')->count();
        $approvedWithdrawals = Withdrawal::where('status', 'approved')->count();

        // Get pending withdrawals
        $pendingWithdrawals = Withdrawal::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get pending deposits
        $pendingDeposits = Deposit::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get recent users
        $recentUsers = User::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Add isDemo attribute to users if not present
        $recentUsers->each(function($user) {
            $user->isDemo = $user->isDemo ?? false;
            $user->total_investment = $user->deposits->where('status', 'approved')->sum('amount');
        });

        return view('backend.dashboard', compact(
            'totalUsers',
            'activeUsers',
            'totalDeposits',
            'totalWithdrawals',
            'netRevenue',
            'approvedDeposits',
            'approvedWithdrawals',
            'pendingWithdrawals',
            'pendingDeposits',
            'recentUsers'
        ));
    }

    public function updateUserStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->status = $request->status;
        $user->save();

        return response()->json(['success' => true]);
    }
}
