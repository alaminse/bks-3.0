<?php

namespace App\Http\Controllers;

use App\Models\DailyPackageEarning;
use App\Models\FeaturedImage;
use App\Models\Package;
use App\Models\UserPackage;
use App\Models\UserTaskSubmission;
use Illuminate\Support\Facades\Auth;

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
    }

    public function index()
    {
        $user = Auth::user();

        // Wallet stats
        $wallet = $user->wallet;
        $walletStats = [
            'balance' => $wallet->balance ?? 0,
            'available' => $wallet->available_balance ?? 0,
            'locked' => $wallet->locked_balance ?? 0,
        ];

        // Active packages
        $activePackages = UserPackage::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('valid_until', '>', now())
            ->with('package')
            ->get();

        // Today's stats
        $todayStats = [
            'earned' => UserTaskSubmission::where('user_id', $user->id)
                ->where('status', 'approved')
                ->whereDate('submitted_at', today())
                ->sum('reward_amount'),

            'tasks_completed' => UserTaskSubmission::where('user_id', $user->id)
                ->where('status', 'approved')
                ->whereDate('submitted_at', today())
                ->count(),
        ];

        // Total earnings (all time)
        $totalEarnings = UserTaskSubmission::where('user_id', $user->id)
            ->where('status', 'approved')
            ->sum('reward_amount');

        // Recent activities
        $recentActivities = UserTaskSubmission::where('user_id', $user->id)
            ->with(['task', 'userPackage.package'])
            ->latest('submitted_at')
            ->limit(5)
            ->get();

        // Available packages
        $availablePackages = Package::where('is_active', true)
            ->orderBy('price', 'asc')
            ->get();

        // Recent transactions
        $recentTransactions = $wallet
            ? $wallet->transactions()->latest()->limit(5)->get()
            : collect();

        // Featured banners
        $featuredBanners = FeaturedImage::active()
            ->ordered()
            ->get()
            ->map(fn ($image) => [
                'image' => $image->image_path,
                'title' => $image->title,
                'description' => $image->description,
                'link' => $image->link_url ?? route('packages.index'),
            ])
            ->toArray();

        // Announcements
        $announcements = [];

        // Tasks total
        $tasksTotal = $user->activePackage?->package->daily_tasks ?? 0;

        /*
        |--------------------------------------------------------------------------
        | Last 7 Days Earnings Chart (FIXED)
        |--------------------------------------------------------------------------
        | daily_package_earnings table does NOT have user_id
        | So we filter by user_package_id
        */

        $userPackageIds = UserPackage::where('user_id', $user->id)->pluck('id');

        $last7Days = DailyPackageEarning::whereIn('user_package_id', $userPackageIds)
            ->whereDate('earning_date', '>=', now()->subDays(6))
            ->selectRaw('earning_date as date, SUM(total_earned) as earned')
            ->groupBy('earning_date')
            ->orderBy('earning_date')
            ->get();

        $chartLabels = $last7Days->pluck('date')
            ->map(fn ($d) => \Carbon\Carbon::parse($d)->format('D'));

        $chartData = $last7Days->pluck('earned');

        return view('frontend.dashboard', compact(
            'user',
            'wallet',
            'walletStats',
            'activePackages',
            'todayStats',
            'totalEarnings',
            'recentActivities',
            'availablePackages',
            'recentTransactions',
            'featuredBanners',
            'announcements',
            'tasksTotal',
            'chartLabels',
            'chartData'
        ));
    }
}
