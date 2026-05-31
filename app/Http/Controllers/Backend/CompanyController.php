<?php

namespace App\Http\Controllers\Backend;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:company-list|company-create|company-edit|company-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:company-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:company-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:company-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $data = Company::latest()->paginate(10);

        return view('backend.companies.index', compact('data'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function create()
    {
        return view('backend.companies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_value' => 'required|numeric|min:0',
            'share_price' => 'required|numeric|min:0',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();

        // Calculate available shares
        $data['available_shares'] = $request->total_value / $request->share_price;
        $data['total_shares_issued'] = 0;

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('companies', 'public');
        }

        Company::create($data);

        return redirect()->route('backend.companies.index')
            ->with('success', 'Company created successfully.');
    }

    public function show(Company $company)
    {
        return view('backend.companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        return view('backend.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_value' => 'required|numeric|min:0',
            'share_price' => 'required|numeric|min:0',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();

        // Recalculate available shares
        $data['available_shares'] = ($request->total_value / $request->share_price) - $company->total_shares_issued;

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            $data['logo'] = $request->file('logo')->store('companies', 'public');
        }

        $company->update($data);

        return redirect()->route('backend.companies.index')
            ->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company)
    {
        // Delete logo if exists
        if ($company->logo) {
            Storage::disk('public')->delete($company->logo);
        }

        $company->delete();

        return redirect()->route('backend.companies.index')
            ->with('success', 'Company deleted successfully.');
    }

    public function updateSharePrice(Request $request, Company $company)
    {
        $request->validate([
            'new_share_price' => 'required|numeric|min:0.01',
            'reason' => 'required|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $oldPrice = $company->share_price;
            $newPrice = $request->new_share_price;
            $changePercentage = (($newPrice - $oldPrice) / $oldPrice) * 100;

            // Update share price
            $company->share_price = $newPrice;
            $company->save();

            // Log price change history
            \App\Models\SharePriceHistory::create([
                'company_id' => $company->id,
                'old_price' => $oldPrice,
                'new_price' => $newPrice,
                'change_percentage' => $changePercentage,
                'reason' => $request->reason,
                'changed_by' => Auth::id(),
            ]);

            DB::commit();

            $changeText = $changePercentage >= 0 ? 'increased' : 'decreased';

            return redirect()->route('backend.companies.show', $company->id)
                ->with('success', "Share price {$changeText} from ৳" . number_format($oldPrice, 2) . " to ৳" . number_format($newPrice, 2) . " (" . abs(round($changePercentage, 2)) . "%)");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update share price: ' . $e->getMessage());
        }
    }

    public function sharePriceHistory(Company $company)
    {
        $histories = \App\Models\SharePriceHistory::where('company_id', $company->id)
            ->with('changedBy')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('backend.companies.price-history', compact('company', 'histories'));
    }
}
