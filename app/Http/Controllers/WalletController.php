<?php

namespace App\Http\Controllers;

use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->middleware('auth');
        $this->walletService = $walletService;
    }

    /**
     * Wallet Dashboard
     */
    public function index()
    {
        $userId = Auth::id();
        // Wallet data
        $wallet = Auth::user()?->wallet;

        // Total earned calculation (credit transaction sum)

        $totalEarned = $wallet ? $wallet->transactions()
            ->where('direction', 'credit')
            ->whereIn('type', ['task', 'referral'])
            ->sum('amount') : 0;

        // Recent transactions (last 10)
        $transactions = $wallet ? $wallet->transactions()
            ->orderBy('created_at', 'desc')
            ->paginate(10) : collect();

        return view('frontend.wallet.index', compact('wallet', 'totalEarned', 'transactions'));
    }

    /**
     * all Transactions
     */
    public function transactions(Request $request)
    {
        $wallet = Auth::user()?->wallet;

        if (! $wallet) {
            return view('frontend.wallet.transactions', [
                'wallet' => null,
                'transactions' => collect(), // empty result
            ]);
        }

        $query = $wallet->transactions(); // ALWAYS query builder

        // Filter by type
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Filter by direction
        if ($request->filled('direction') && $request->direction !== 'all') {
            $query->where('direction', $request->direction);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query
            ->orderByDesc('created_at')
            ->paginate(20);
    

        // Count per type from ALL transactions (unfiltered)
        $typeCounts = $wallet->transactions()
            ->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        return view('frontend.wallet.transactions', compact('wallet', 'transactions'));
    }

    /**
     * Balance check
     */
    public function balance()
    {
        $balance = $this->walletService->getBalance(Auth::id());

        return response()->json([
            'success' => true,
            'data' => $balance,
        ]);
    }
}
