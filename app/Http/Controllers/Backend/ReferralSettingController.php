<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ReferralEarning;
use App\Models\ReferralSetting;
use App\Models\Referral;
use Illuminate\Http\Request;

class ReferralSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    // ─── Settings page ────────────────────────────────────────────────

    public function index()
    {
        $settings = ReferralSetting::orderBy('generation')->get();

        $stats = [
            'total_referrals'    => Referral::count(),
            'active_referrals'   => Referral::where('status', 'active')->count(),
            'total_commissions'  => ReferralEarning::where('status', 'paid')->sum('amount'),
            'this_month'         => ReferralEarning::where('status', 'paid')
                                        ->whereMonth('created_at', now()->month)
                                        ->sum('amount'),
        ];

        // Earnings per generation for chart
        $earningsByGen = ReferralEarning::where('status', 'paid')
            ->selectRaw('generation, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('generation')
            ->orderBy('generation')
            ->get();

        return view('backend.referrals.settings', compact('settings', 'stats', 'earningsByGen'));
    }

    /**
     * Update all generation settings at once
     */
    public function update(Request $request)
    {
        $request->validate([
            'generations'                     => 'required|array|min:1|max:10',
            'generations.*.generation'        => 'required|integer|min:1|max:10',
            'generations.*.commission_rate'   => 'required|numeric|min:0|max:100',
            'generations.*.is_active'         => 'nullable|boolean',
            'generations.*.label'             => 'nullable|string|max:100',
        ]);

        foreach ($request->generations as $data) {
            ReferralSetting::updateOrCreate(
                ['generation' => $data['generation']],
                [
                    'commission_rate' => $data['commission_rate'],
                    'is_active'       => isset($data['is_active']) ? (bool)$data['is_active'] : false,
                    'label'           => $data['label'] ?? "Level {$data['generation']}",
                ]
            );
        }

        return back()->with('success', 'Referral commission settings updated successfully!');
    }

    /**
     * Add a new generation level
     */
    public function addGeneration(Request $request)
    {
        $maxExisting = ReferralSetting::max('generation') ?? 0;
        $next        = $maxExisting + 1;

        if ($next > 10) {
            return back()->with('error', 'Maximum 10 generation levels allowed.');
        }

        ReferralSetting::create([
            'generation'      => $next,
            'commission_rate' => 0,
            'is_active'       => false,
            'label'           => "Level {$next}",
        ]);

        return back()->with('success', "Level {$next} added. Set its commission rate and activate it.");
    }

    /**
     * Delete a generation level (only the highest can be deleted)
     */
    public function deleteGeneration(ReferralSetting $referralSetting)
    {
        $max = ReferralSetting::max('generation');

        if ($referralSetting->generation !== $max) {
            return back()->with('error', 'Only the highest generation level can be deleted.');
        }

        if ($referralSetting->generation === 1) {
            return back()->with('error', 'Cannot delete Level 1 (direct referral).');
        }

        $referralSetting->delete();

        return back()->with('success', "Level {$referralSetting->generation} removed.");
    }

    // ─── Earnings list ────────────────────────────────────────────────

    public function earnings(Request $request)
    {
        $query = ReferralEarning::with(['referrer', 'referred'])
            ->latest();

        if ($request->filled('generation')) {
            $query->where('generation', $request->generation);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('referrer', fn($q) =>
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
            );
        }

        $earnings  = $query->paginate(20);
        $settings  = ReferralSetting::orderBy('generation')->get();
        $maxGen    = ReferralSetting::max('generation') ?? 3;

        return view('backend.referrals.earnings', compact('earnings', 'settings', 'maxGen'));
    }
}
