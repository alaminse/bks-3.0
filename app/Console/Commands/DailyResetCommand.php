<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserPackage;
use Carbon\Carbon;

class DailyResetCommand extends Command
{
    protected $signature = 'task:daily-reset';
    protected $description = 'Reset daily task limits and check package expiry';

    public function handle()
    {
        $this->info('Starting daily reset...');

        // 1. Check and expire packages
        $this->expirePackages();

        // 2. Daily earning records are auto-created when needed
        // No need to pre-create them

        $this->info('Daily reset completed successfully!');
    }

    /**
     * Expire packages that have reached their end date
     */
    protected function expirePackages()
    {
        $expiredCount = UserPackage::where('status', 'active')
            ->where('valid_until', '<', now())
            ->update(['status' => 'expired']);

        $this->info("Expired {$expiredCount} packages");
    }
}
