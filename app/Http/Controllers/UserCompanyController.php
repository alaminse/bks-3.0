<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\PartnerShare;
use Illuminate\Http\Request;
use App\Models\SharePriceHistory;
use App\Models\ProfitDistribution;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserCompanyController extends Controller
{
    protected $walletService;

    public function __construct(\App\Services\WalletService $walletService)
    {
        $this->middleware('auth');
        $this->walletService = $walletService;
    }

    /**
     * Show all available companies
     */
    public function index()
    {
        $companies = Company::where('status', 'active')
            ->where('available_shares', '>', 0)
            ->latest()
            ->paginate(12);

        return view('frontend.companies.index', compact('companies'));
    }

    /**
     * Show company details
     */
    public function show(Company $company)
    {
        $company->load(['partnerShares' => function($query) {
            $query->where('status', 'active')->with('user');
        }]);

        // Check if current user is already a partner
        $userShare = null;
        if (Auth::check()) {
            $userShare = PartnerShare::where('company_id', $company->id)
                ->where('user_id', Auth::id())
                ->where('status', 'active')
                ->first();
        }

        // Get price history
        $priceHistory = SharePriceHistory::where('company_id', $company->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('frontend.companies.show', compact('company', 'userShare', 'priceHistory'));
    }

    /**
     * Show investment form
     */
    public function invest(Company $company)
    {
        if ($company->status != 'active' || $company->available_shares <= 0) {
            return redirect()->route('companies.index')
                ->with('error', 'This company is not available for investment.');
        }

        $user = Auth::user();

        // Check if user already has investment
        $existingShare = PartnerShare::where('company_id', $company->id)
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        return view('frontend.companies.invest', compact('company', 'user', 'existingShare'));
    }

    /**
     * Process investment
     */
    public function processInvestment(Request $request, Company $company)
    {
        $request->validate([
            'invested_amount' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();

            // Convert to float
            $userBalance = floatval(str_replace(',', '', $user->wallet_balance));
            $investedAmount = floatval($request->invested_amount);

            // Check balance
            if ($userBalance < $investedAmount) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Insufficient balance. You have ৳' . number_format($userBalance, 2) . ' but need ৳' . number_format($investedAmount, 2) . '. Please recharge your account.');
            }

            // Calculate share quantity
            $shareQuantity = $investedAmount / $company->share_price;

            // Check shares availability
            if ($shareQuantity > $company->available_shares) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Not enough shares available. Maximum: ' . number_format($company->available_shares, 2) . ' shares (৳' . number_format($company->available_shares * $company->share_price, 2) . ')');
            }

            // Calculate percentages
            $totalInvested = PartnerShare::where('company_id', $company->id)
                ->where('status', 'active')
                ->sum('invested_amount') + $investedAmount;

            $sharePercentage = ($investedAmount / $totalInvested) * 100;

            // Update existing shares percentages
            $existingShares = PartnerShare::where('company_id', $company->id)
                ->where('status', 'active')
                ->get();

            foreach ($existingShares as $share) {
                $share->share_percentage = ($share->invested_amount / $totalInvested) * 100;
                $share->save();
            }

            // Create partner share
            $partnerShare = PartnerShare::create([
                'user_id' => $user->id,
                'company_id' => $company->id,
                'invested_amount' => $investedAmount,
                'share_quantity' => $shareQuantity,
                'share_percentage' => $sharePercentage,
                'purchase_date' => now(),
                'status' => 'active',
            ]);

            // Wallet debit
            $wallet = $this->walletService->debit(
                userId: $user->id,
                amount: $investedAmount,
                type: 'investment',
                referenceType: 'PartnerShare',
                referenceId: $partnerShare->id,
                description: "Investment in {$company->name} - " . number_format($shareQuantity, 2) . " shares"
            );

            if (!$wallet) {
                DB::rollBack();
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Transaction failed. Please try again.');
            }

            // Update company
            $company->total_shares_issued += $shareQuantity;
            $company->available_shares -= $shareQuantity;
            $company->save();

            // ✅ CRITICAL: Assign partner role if not already assigned
            if (!$user->hasRole('partner')) {
                $user->assignRole('partner');
            }

            DB::commit();

            return redirect()->route('companies.my-investments')
                ->with('success', 'Investment successful! You are now a partner of ' . $company->name . ' with ' . number_format($sharePercentage, 2) . '% ownership.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('User investment failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'company_id' => $company->id ?? null
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Investment failed: ' . $e->getMessage());
        }
    }

    /**
     * Show user's investments
     */
    public function myInvestments()
    {
        $user = Auth::user();

        // ✅ Check and assign partner role if user has active shares
        $hasActiveShares = PartnerShare::where('user_id', $user->id)
            ->where('status', 'active')
            ->exists();

        if ($hasActiveShares && !$user->hasRole('partner')) {
            $user->assignRole('partner');
        }

        $investments = PartnerShare::where('user_id', $user->id)
            ->with('company')
            ->latest()
            ->paginate(10);

        // Calculate totals
        $activeInvestments = $investments->where('status', 'active');
        $totalInvested = $activeInvestments->sum('invested_amount');
        $totalShares = $activeInvestments->sum('share_quantity');
        $currentValue = $activeInvestments->sum('current_value');
        $totalProfitLoss = $currentValue - $totalInvested;

        return view('frontend.companies.my-investments', compact(
            'investments',
            'totalInvested',
            'totalShares',
            'currentValue',
            'totalProfitLoss'
        ));
    }

    /**
     * Show profit history
     */
    public function profitHistory()
    {
        $user = Auth::user();

        // ✅ Check and assign partner role
        $hasDistributions = ProfitDistribution::where('user_id', $user->id)->exists();

        if ($hasDistributions && !$user->hasRole('partner')) {
            $user->assignRole('partner');
        }

        $profitDistributions = ProfitDistribution::where('user_id', $user->id)
            ->with(['company', 'companyProfit'])
            ->latest()
            ->paginate(15);

        $totalProfit = ProfitDistribution::where('user_id', $user->id)
            ->sum('profit_amount');

        // Statistics
        $monthlyProfit = ProfitDistribution::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('profit_amount');

        $yearlyProfit = ProfitDistribution::where('user_id', $user->id)
            ->whereYear('created_at', now()->year)
            ->sum('profit_amount');

        return view('frontend.companies.profit-history', compact(
            'profitDistributions',
            'totalProfit',
            'monthlyProfit',
            'yearlyProfit'
        ));
    }

    /**
     * Show user's portfolio dashboard
     */
    public function portfolio()
    {
        $user = Auth::user();

        // ✅ Check and assign partner role
        $hasActiveShares = PartnerShare::where('user_id', $user->id)
            ->where('status', 'active')
            ->exists();

        if ($hasActiveShares && !$user->hasRole('partner')) {
            $user->assignRole('partner');
        }

        // Get all investments
        $activeInvestments = PartnerShare::where('user_id', $user->id)
            ->where('status', 'active')
            ->with('company')
            ->get();

        // Calculate portfolio stats
        $totalInvested = $activeInvestments->sum('invested_amount');
        $currentValue = $activeInvestments->sum('current_value');
        $totalProfitLoss = $currentValue - $totalInvested;
        $totalProfitLossPercentage = $totalInvested > 0 ? ($totalProfitLoss / $totalInvested) * 100 : 0;

        // Total profit received
        // $totalProfitReceived = ProfitDistribution::where('user_id', $user->id)
        //     ->sum('profit_amount');

        // Company wise breakdown
        $companiesData = $activeInvestments->map(function($share) {
            return [
                'company' => $share->company,
                'investment' => $share->invested_amount,
                'current_value' => $share->current_value,
                'shares' => $share->share_quantity,
                'ownership' => $share->share_percentage,
                'profit_loss' => $share->unrealized_profit_loss,
                'profit_loss_percentage' => $share->unrealized_profit_loss_percentage,
            ];
        });

        $totalProfitReceived = 0;

        return view('frontend.companies.portfolio', compact(
            'totalInvested',
            'currentValue',
            'totalProfitLoss',
            'totalProfitLossPercentage',
            'totalProfitReceived',
            'companiesData'
        ));
    }
}
