<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run()
    {
        $packages = [
            [
                'name'           => 'Quick Entry',
                'slug'           => 'quick-entry',
                'description'    => 'Start earning from day one',
                'price'          => 10.00,
                'daily_tasks'    => 3,
                'daily_earning'  => 0.50,
                'duration_days'  => 0, // 0 = unlimited
                'features'       => "Basic task access\nEmail support\nDaily withdrawal",
                'is_active'      => true,
                'sort_order'     => 1,
            ],
            [
                'name'           => 'Starter',
                'slug'           => 'starter',
                'description'    => 'Perfect for beginners',
                'price'          => 50.00,
                'daily_tasks'    => 5,
                'daily_earning'  => 2.50,
                'duration_days'  => 0,
                'features'       => "Basic task access\nEmail support\nDaily withdrawal\nReferral bonus",
                'is_active'      => true,
                'sort_order'     => 2,
            ],
            [
                'name'           => 'Professional',
                'slug'           => 'professional',
                'description'    => 'Most popular choice',
                'price'          => 150.00,
                'daily_tasks'    => 10,
                'daily_earning'  => 7.50,
                'duration_days'  => 0,
                'features'       => "All Starter features\nPriority task queue\nPriority support\nInstant withdrawal",
                'is_active'      => true,
                'sort_order'     => 3,
            ],
            [
                'name'           => 'Premium',
                'slug'           => 'premium',
                'description'    => 'Maximum earnings potential',
                'price'          => 300.00,
                'daily_tasks'    => 20,
                'daily_earning'  => 15.00,
                'duration_days'  => 0,
                'features'       => "All Professional features\nVIP task access\nDedicated support\nNo withdrawal fees\nHigher referral bonuses",
                'is_active'      => true,
                'sort_order'     => 4,
            ],
            [
                'name'           => 'Enterprise',
                'slug'           => 'enterprise',
                'description'    => 'For serious earners',
                'price'          => 500.00,
                'daily_tasks'    => 30,
                'daily_earning'  => 25.00,
                'duration_days'  => 0,
                'features'       => "All Premium features\nUnlimited withdrawals\nPersonal account manager\nCustom task options\nHighest referral rates",
                'is_active'      => true,
                'sort_order'     => 5,
            ],
            [
                'name'           => 'Ultimate',
                'slug'           => 'ultimate',
                'description'    => 'The highest earning package',
                'price'          => 1000.00,
                'daily_tasks'    => 50,
                'daily_earning'  => 50.00,
                'duration_days'  => 0,
                'features'       => "All Enterprise features\nMaximum daily tasks\nLifetime priority support\nZero fees on all transactions\nExclusive rewards & bonuses",
                'is_active'      => true,
                'sort_order'     => 6,
            ],
        ];

        foreach ($packages as $package) {
            Package::updateOrCreate(
                ['slug' => $package['slug']],
                $package
            );
        }
    }
}
