<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyProfit;
use App\Models\ProfitDistribution;
use App\Models\PartnerShare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CompanyProfitController extends Controller
{
    protected $walletService;

    function __construct(\App\Services\WalletService $walletService)
    {
        $this->middleware('permission:profit-list|profit-create|profit-edit|profit-delete', ['only' => ['index','show']]);
        $this->middleware('permission:profit-create', ['only' => ['create','store']]);
        $this->middleware('permission:profit-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:profit-delete', ['only' => ['destroy']]);
        $this->middleware('permission:profit-distribute', ['only' => ['distribute']]);

        $this->walletService = $walletService;
    }

    public function index()
    {
        $data = CompanyProfit::with(['company', 'creator'])
            ->latest()
            ->paginate(10);

        return view('backend.profits.index', compact('data'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function create()
    {
        $companies = Company::where('status', 'active')->get();
        return view('backend.profits.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'profit_amount' => 'required|numeric|min:0',
            'profit_date' => 'required|date',
            'profit_type' => 'required|in:monthly,quarterly,yearly,other',
            'description' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::id();
        $data['distribution_status'] = 'pending';

        CompanyProfit::create($data);

        return redirect()->route('backend.profits.index')
            ->with('success', 'Profit added successfully.');
    }

    public function show(CompanyProfit $profit)
    {
        $profit->load(['company', 'creator', 'distributions.user']);
        return view('backend.profits.show', compact('profit'));
    }

    public function edit(CompanyProfit $profit)
    {
        if ($profit->isDistributed()) {
            return redirect()->route('backend.profits.index')
                ->with('error', 'Cannot edit already distributed profit.');
        }

        $companies = Company::where('status', 'active')->get();
        return view('backend.profits.edit', compact('profit', 'companies'));
    }

    public function update(Request $request, CompanyProfit $profit)
    {
        if ($profit->isDistributed()) {
            return redirect()->route('backend.profits.index')
                ->with('error', 'Cannot update already distributed profit.');
        }

        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'profit_amount' => 'required|numeric|min:0',
            'profit_date' => 'required|date',
            'profit_type' => 'required|in:monthly,quarterly,yearly,other',
            'description' => 'nullable|string',
        ]);

        $profit->update($request->all());

        return redirect()->route('backend.profits.index')
            ->with('success', 'Profit updated successfully.');
    }

    public function destroy(CompanyProfit $profit)
    {
        if ($profit->isDistributed()) {
            return redirect()->route('backend.profits.index')
                ->with('error', 'Cannot delete already distributed profit.');
        }

        $profit->delete();

        return redirect()->route('backend.profits.index')
            ->with('success', 'Profit deleted successfully.');
    }

    /**
     * Distribute profit to all active partners
     */
    public function distribute(CompanyProfit $profit)
    {
        if ($profit->isDistributed()) {
            return redirect()->route('backend.profits.index')
                ->with('error', 'Profit already distributed.');
        }

        try {
            DB::beginTransaction();

            // Get all active partners with their shares
            $partnerShares = PartnerShare::where('company_id', $profit->company_id)
                ->where('status', 'active')
                ->with('user')
                ->get();

            if ($partnerShares->isEmpty()) {
                return redirect()->route('backend.profits.index')
                    ->with('error', 'No active partners found for this company.');
            }

            $distributionCount = 0;
            $totalDistributed = 0;

            foreach ($partnerShares as $share) {
                // Calculate profit for this partner
                $partnerProfit = ($profit->profit_amount * $share->share_percentage) / 100;

                // Create distribution record
                $distribution = ProfitDistribution::create([
                    'company_profit_id' => $profit->id,
                    'user_id' => $share->user_id,
                    'company_id' => $profit->company_id,
                    'share_percentage' => $share->share_percentage,
                    'profit_amount' => $partnerProfit,
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);

                // ✅ Credit to partner's wallet
                $wallet = $this->walletService->credit(
                    userId: $share->user_id,
                    amount: $partnerProfit,
                    type: 'profit_distribution',
                    referenceType: 'ProfitDistribution',
                    referenceId: $distribution->id,
                    description: "Profit from {$profit->company->name} - {$profit->profit_type} ({$share->share_percentage}%)"
                );

                if (!$wallet) {
                    DB::rollBack();
                    return redirect()->route('backend.profits.index')
                        ->with('error', 'Profit distribution failed for user: ' . $share->user->name);
                }

                // ✅ Ensure partner role
                if (!$share->user->hasRole('partner')) {
                    $share->user->assignRole('partner');
                }

                $distributionCount++;
                $totalDistributed += $partnerProfit;

                \Log::info('Profit distributed to partner', [
                    'company' => $profit->company->name,
                    'user_id' => $share->user_id,
                    'user_name' => $share->user->name,
                    'share_percentage' => $share->share_percentage,
                    'profit_amount' => $partnerProfit,
                ]);
            }

            // Update profit status
            $profit->update([
                'distribution_status' => 'distributed',
                'distributed_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('backend.profits.index')
                ->with('success', "Profit of ৳" . number_format($profit->profit_amount, 2) . " distributed successfully to {$distributionCount} partners. Total distributed: ৳" . number_format($totalDistributed, 2));

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Profit distribution failed', [
                'error' => $e->getMessage(),
                'profit_id' => $profit->id ?? null,
                'company_id' => $profit->company_id ?? null,
            ]);

            return redirect()->route('backend.profits.index')
                ->with('error', 'Error distributing profit: ' . $e->getMessage());
        }
    }

    /**
     * Show profit distribution details for a specific partner
     */
    public function partnerDistributions($userId)
    {
        $user = \App\Models\User::findOrFail($userId);

        $distributions = ProfitDistribution::where('user_id', $userId)
            ->with(['company', 'companyProfit'])
            ->latest()
            ->paginate(15);

        $totalProfit = ProfitDistribution::where('user_id', $userId)->sum('profit_amount');

        return view('backend.profits.partner-distributions', compact('user', 'distributions', 'totalProfit'))
            ->with('i', (request()->input('page', 1) - 1) * 15);
    }
}
