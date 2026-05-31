<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Referral;
use App\Models\UserPackage;
use Illuminate\Http\Request;
use App\Services\WalletService;
use App\Services\ReferralService;
use App\Models\UserTaskSubmission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{
    protected $walletService;

    protected $referralService;

    public function __construct(WalletService $walletService, ReferralService $referralService)
    {
        $this->middleware('auth');
        $this->referralService = $referralService;
        $this->walletService = $walletService;
    }

    /**
     * All Packages (User View)
     */
    public function index()
    {
        $packages = Package::where('is_active', true)
            ->orderBy('price', 'asc')
            ->get();

        // User  active packages
        $userPackages = UserPackage::where('user_id', Auth::id())
            ->where('status', 'active')
            ->with('package')
            ->get();

        $wallet = Auth::user()->wallet;

        return view('frontend.packages.index', compact('packages', 'userPackages', 'wallet'));
    }

    /**
     * Package Details
     */
    public function show($slug)
    {
        $package = Package::whereSlug($slug)->firstOrFail();

        // Check: Package active
        if (! $package->is_active) {
            return redirect()->route('packages.index')
                ->with('error', 'This package is not available at the moment.');
        }

        $wallet = Auth::user()->wallet;

        // This package already active or not check
        $hasActivePackage = UserPackage::where('user_id', Auth::id())
            ->where('package_id', $package->id)
            ->where('status', 'active')
            ->exists();

        return view('frontend.packages.show', compact('package', 'wallet', 'hasActivePackage'));
    }

    /**
     * Package purchase
     */
    public function purchase(Request $request, $slug)
    {
        $package = Package::whereSlug($slug)->firstOrFail();
        $userId = Auth::id();

        try {

            DB::beginTransaction();
            // Check: this package check active or not
            $hasActive = UserPackage::where('user_id', $userId)
                ->where('package_id', $package->id)
                ->where('status', 'active')
                ->exists();

            if ($hasActive) {
                return back()->with('error', 'You already have an active subscription for this package.');
            }

            // Check: Package active
            if (! $package->is_active) {
                return back()->with('error', 'This package is not available.');
            }

            // Debit balance
            $wallet = $this->walletService->debit(
                userId: $userId,
                amount: $package->price,
                type: 'package',
                referenceType: 'Package',
                referenceId: $package->id,
                description: "Purchased {$package->name} package"
            );;

            // Check if debit was successful
            if (!$wallet) {
                DB::rollBack();
                return back()->with('error', 'Insufficient wallet balance.');
            }

            // User Package
            $packagePurchase = UserPackage::create([
                'user_id' => $userId,
                'package_id' => $package->id,
                'purchase_price' => $package->price,
                'daily_task_limit' => $package->daily_tasks,
                'daily_earning_limit' => $package->daily_earning,
                'total_earning' => 0,
                'completed_tasks' => 0,
                'status' => 'active',
                // 'valid_until' => now()->addDays($package->duration_days),
            ]);

            // $referral = Referral::where('referred_id', Auth::id())->first();

            // if ($referral) {
                $commissionAdd = $this->referralService->processPackageCommission(
                    $packagePurchase->id,
                    $package->price,
                    Auth::id()
                );
            // }

            DB::commit();
            return redirect()->route('packages.my')
                ->with('success', "Successfully purchased {$package->name} package!");

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Active Packages
     */
    public function myPackages()
    {
        $userId = Auth::id();

        $activePackages = UserPackage::where('user_id', $userId)
            ->where('status', 'active')
            ->with(['package', 'todaySubmissions'])
            ->orderBy('created_at', 'desc')
            ->get();

        $expiredPackages = UserPackage::where('user_id', $userId)
            ->whereIn('status', ['expired', 'completed'])
            ->with('package')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return view('frontend.packages.my-packages', compact('activePackages', 'expiredPackages'));
    }
}
