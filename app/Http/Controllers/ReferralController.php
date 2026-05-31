<?php

namespace App\Http\Controllers;

use App\Models\ReferralEarning;
use App\Models\ReferralSetting;
use App\Services\ReferralService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    protected $referralService;

    public function __construct(ReferralService $referralService)
    {
        $this->middleware('auth');
        $this->referralService = $referralService;
    }

    /**
     * Referral dashboard
     */
    public function index()
    {
        $user  = Auth::user();
        $stats = $this->referralService->getReferralStats($user->id);

        $referrals = $user->referrals()
            ->with('referred')
            ->latest()
            ->paginate(10);

        // Earnings grouped by generation
        $earnings = ReferralEarning::where('referrer_id', $user->id)
            ->with(['referred'])
            ->latest()
            ->paginate(10);

        // Active generation settings (for UI display)
        $generationSettings = ReferralSetting::orderBy('generation')->get();

        // Build the user's downline tree (up to max active generation)
        $downlineTree = $this->referralService->getRecentReferrals($user->id, 20);

        return view('frontend.referrals.index', compact(
            'user', 'stats', 'referrals', 'earnings',
            'generationSettings', 'downlineTree'
        ));
    }

    /**
     * Return referral link as JSON (for copy button)
     */
    public function getReferralLink()
    {
        return response()->json([
            'success' => true,
            'link'    => Auth::user()->getReferralLink(),
            'code'    => Auth::user()->referral_code,
        ]);
    }

    /**
     * Copy referral link (returns JSON for AJAX or redirects back)
     */
    public function copyLink(Request $request)
    {
        $user = Auth::user();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Referral link copied!',
                'link'    => $user->getReferralLink(),
                'code'    => $user->referral_code,
            ]);
        }

        return back()->with('success', 'Referral link copied!');
    }
}
