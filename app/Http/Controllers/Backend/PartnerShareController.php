<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\Company;
use App\Models\PartnerShare;
use Illuminate\Http\Request;
use App\Services\WalletService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class PartnerShareController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->middleware('auth');
        $this->walletService = $walletService;
        $this->middleware('permission:partner-share-list|partner-share-create|partner-share-edit|partner-share-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:partner-share-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:partner-share-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:partner-share-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $query = PartnerShare::with(['user', 'company']);

        // Filter by company
        if ($request->has('company_id') && $request->company_id != '') {
            $query->where('company_id', $request->company_id);
        }

        // Filter by user
        if ($request->has('user_id') && $request->user_id != '') {
            $query->where('user_id', $request->user_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $data = $query->latest()->paginate(10);
        $companies = Company::where('status', 'active')->get();
        $partners = User::role('partner')->get();

        return view('backend.partner-shares.index', compact('data', 'companies', 'partners'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function create()
    {
        $companies = Company::where('status', 'active')->get();
        $partners = User::role('partner')->with('wallet')->get();

        return view('backend.partner-shares.create', compact('companies', 'partners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'company_id' => 'required|exists:companies,id',
            'invested_amount' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $company = Company::findOrFail($request->company_id);
            $user = User::findOrFail($request->user_id);

            // ✅ Convert to float for proper comparison
            $userBalance = floatval(str_replace(',', '', $user->wallet_balance));
            $investedAmount = floatval($request->invested_amount);

            // ✅ Check sufficient balance
            if ($userBalance < $investedAmount) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'User does not have sufficient balance. Available: ৳'.number_format($userBalance, 2).', Required: ৳'.number_format($investedAmount, 2));
            }

            // Calculate share quantity
            $shareQuantity = $investedAmount / $company->share_price;

            // Check if enough shares available
            if ($shareQuantity > $company->available_shares) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Not enough shares available. Available: '.number_format($company->available_shares, 2).' shares, Required: '.number_format($shareQuantity, 2).' shares');
            }

            // Calculate total company value including this investment
            $totalInvested = PartnerShare::where('company_id', $company->id)
                ->where('status', 'active')
                ->sum('invested_amount') + $investedAmount;

            $sharePercentage = ($investedAmount / $totalInvested) * 100;

            // Update all existing partners' share percentages
            $existingShares = PartnerShare::where('company_id', $company->id)
                ->where('status', 'active')
                ->get();

            foreach ($existingShares as $share) {
                $share->share_percentage = ($share->invested_amount / $totalInvested) * 100;
                $share->save();
            }

            // Create new partner share
            $partnerShare = PartnerShare::create([
                'user_id' => $request->user_id,
                'company_id' => $request->company_id,
                'invested_amount' => $investedAmount, // ✅ Use cleaned amount
                'share_quantity' => $shareQuantity,
                'share_percentage' => $sharePercentage,
                'purchase_date' => $request->purchase_date,
                'status' => 'active',
            ]);

            // ✅ Debit from wallet (এটাই যথেষ্ট - দুইবার deduct করার দরকার নেই)
            $wallet = $this->walletService->debit(
                userId: $user->id,
                amount: $investedAmount, // ✅ সঠিক amount
                type: 'investment', // ✅ সঠিক type
                referenceType: 'PartnerShare', // ✅ সঠিক reference
                referenceId: $partnerShare->id, // ✅ Partner share ID
                description: "Investment in {$company->name} - {$shareQuantity} shares"
            );

            // Check if debit was successful
            if (! $wallet) {
                DB::rollBack();

                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Insufficient wallet balance or transaction failed.');
            }

            // ✅ Update company shares
            $company->total_shares_issued += $shareQuantity;
            $company->available_shares -= $shareQuantity;
            $company->save();

            DB::commit();

            return redirect()->route('backend.partner-shares.index')
                ->with('success', 'Partner share created successfully. User: '.$user->name.', Investment: ৳'.number_format($investedAmount, 2).', Shares: '.number_format($shareQuantity, 2));

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating partner share: '.$e->getMessage());
        }
    }

    public function show(PartnerShare $partnerShare)
    {
        $partnerShare->load(['user', 'company']);

        return view('backend.partner-shares.show', compact('partnerShare'));
    }

    public function edit(PartnerShare $partnerShare)
    {
        $companies = Company::where('status', 'active')->get();
        $partners = User::role('partner')->get();
        $currentCompany = $partnerShare->company;

        return view('backend.partner-shares.edit', compact('partnerShare', 'companies', 'partners', 'currentCompany'));
    }

    public function update(Request $request, PartnerShare $partnerShare)
    {
        $request->validate([
            'status' => 'required|in:active,sold,transferred',
            'sold_price' => 'nullable|required_if:status,sold|numeric|min:0',
            'transfer_to_user_id' => 'nullable|required_if:status,transferred|exists:users,id',
        ]);

        try {
            DB::beginTransaction();

            $oldStatus = $partnerShare->status;
            $newStatus = $request->status;
            $company = $partnerShare->company;
            $user = $partnerShare->user;

            if ($oldStatus != $newStatus) {

                // CASE 1: SOLD
                if ($newStatus == 'sold') {
                    $soldPrice = floatval($request->sold_price);

                    $wallet = $this->walletService->credit(
                        userId: $user->id,
                        amount: $soldPrice,
                        type: 'refund',
                        referenceType: 'PartnerShare',
                        referenceId: $partnerShare->id,
                        description: "Sold {$partnerShare->share_quantity} shares of {$company->name} at ৳".number_format($soldPrice, 2)
                    );

                    if (! $wallet) {
                        DB::rollBack();

                        return redirect()->back()->with('error', 'Wallet transaction failed.');
                    }

                    $company->total_shares_issued -= $partnerShare->share_quantity;
                    $company->available_shares += $partnerShare->share_quantity;
                    $company->save();

                    $partnerShare->status = 'sold';
                    $partnerShare->sold_price = $soldPrice;
                    $partnerShare->sold_at = now();
                    $partnerShare->save();

                    $this->recalculateSharePercentages($company->id, $partnerShare->id);

                    DB::commit();

                    return redirect()->route('backend.partner-shares.index')
                        ->with('success', 'Share sold successfully. ৳'.number_format($soldPrice, 2).' credited to '.$user->name);
                }

                // CASE 2: TRANSFERRED
                elseif ($newStatus == 'transferred') {
                    $newOwnerId = $request->transfer_to_user_id;
                    $newOwner = User::findOrFail($newOwnerId);

                    if ($user->id == $newOwnerId) {
                        return redirect()->back()->with('error', 'Cannot transfer to the same user.');
                    }

                    $partnerShare->status = 'transferred';
                    $partnerShare->transferred_to = $newOwnerId;
                    $partnerShare->transferred_at = now();
                    $partnerShare->save();

                    $newShare = PartnerShare::create([
                        'user_id' => $newOwnerId,
                        'company_id' => $company->id,
                        'invested_amount' => $partnerShare->invested_amount,
                        'share_quantity' => $partnerShare->share_quantity,
                        'share_percentage' => $partnerShare->share_percentage,
                        'purchase_date' => now(),
                        'status' => 'active',
                        'transferred_from' => $user->id,
                    ]);

                    if (! $newOwner->hasRole('partner')) {
                        $newOwner->assignRole('partner');
                    }

                    $this->recalculateSharePercentages($company->id, $partnerShare->id);

                    DB::commit();

                    return redirect()->route('backend.partner-shares.index')
                        ->with('success', 'Share transferred from '.$user->name.' to '.$newOwner->name);
                }

                // CASE 3: ACTIVE
                elseif ($newStatus == 'active' && ($oldStatus == 'sold' || $oldStatus == 'transferred')) {

                    if ($oldStatus == 'sold') {
                        $company->total_shares_issued += $partnerShare->share_quantity;
                        $company->available_shares -= $partnerShare->share_quantity;
                        $company->save();

                        if ($partnerShare->sold_price > 0) {
                            $this->walletService->debit(
                                userId: $user->id,
                                amount: $partnerShare->sold_price,
                                type: 'adjustment',
                                referenceType: 'PartnerShare',
                                referenceId: $partnerShare->id,
                                description: "Reversed sale of {$company->name} shares"
                            );
                        }
                    }

                    $partnerShare->status = 'active';
                    $partnerShare->save();

                    $this->recalculateSharePercentages($company->id);

                    DB::commit();

                    return redirect()->route('backend.partner-shares.index')
                        ->with('success', 'Partner share reactivated successfully.');
                }
            }

            $partnerShare->update(['status' => $request->status]);
            DB::commit();

            return redirect()->route('backend.partner-shares.index')
                ->with('success', 'Partner share updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Partner share update failed', [
                'error' => $e->getMessage(),
                'share_id' => $partnerShare->id,
            ]);

            return redirect()->back()->with('error', 'Error: '.$e->getMessage());
        }
    }

    private function recalculateSharePercentages($companyId, $excludeShareId = null)
    {
        $query = PartnerShare::where('company_id', $companyId)->where('status', 'active');

        if ($excludeShareId) {
            $query->where('id', '!=', $excludeShareId);
        }

        $totalInvested = $query->sum('invested_amount');

        if ($totalInvested > 0) {
            $activeShares = $query->get();
            foreach ($activeShares as $share) {
                $share->share_percentage = ($share->invested_amount / $totalInvested) * 100;
                $share->save();
            }
        }
    }

    public function destroy(PartnerShare $partnerShare)
    {
        try {
            DB::beginTransaction();

            $user = $partnerShare->user;
            $company = $partnerShare->company;

            // ✅ Refund করুন wallet এ (যদি wallet system ব্যবহার করেন)
            $wallet = $this->walletService->credit(
                userId: $user->id,
                amount: $partnerShare->invested_amount,
                type: 'refund',
                referenceType: 'PartnerShare',
                referenceId: $partnerShare->id,
                description: "Refund from {$company->name} investment"
            );

            if (! $wallet) {
                DB::rollBack();

                return redirect()->back()
                    ->with('error', 'Refund transaction failed.');
            }

            // Update company shares
            $company->total_shares_issued -= $partnerShare->share_quantity;
            $company->available_shares += $partnerShare->share_quantity;
            $company->save();

            // Recalculate percentages for remaining partners
            $totalInvested = PartnerShare::where('company_id', $company->id)
                ->where('status', 'active')
                ->where('id', '!=', $partnerShare->id)
                ->sum('invested_amount');

            if ($totalInvested > 0) {
                $remainingShares = PartnerShare::where('company_id', $company->id)
                    ->where('status', 'active')
                    ->where('id', '!=', $partnerShare->id)
                    ->get();

                foreach ($remainingShares as $share) {
                    $share->share_percentage = ($share->invested_amount / $totalInvested) * 100;
                    $share->save();
                }
            }

            $partnerShare->delete();

            DB::commit();

            return redirect()->route('backend.partner-shares.index')
                ->with('success', 'Partner share deleted and amount refunded successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error deleting partner share: '.$e->getMessage());
        }
    }
}
