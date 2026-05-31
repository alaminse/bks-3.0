<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // MySQL ENUM column এ নতুন values যোগ করা
        DB::statement("
            ALTER TABLE wallet_transactions
            MODIFY COLUMN type ENUM(
                'deposit',
                'package',
                'task',
                'withdraw',
                'referral',
                'adjustment',
                'investment',
                'refund',
                'profit_distribution'
            ) NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback করলে আগের অবস্থায় ফিরে যাবে
        DB::statement("
            ALTER TABLE wallet_transactions
            MODIFY COLUMN type ENUM(
                'deposit',
                'package',
                'task',
                'withdraw',
                'referral',
                'adjustment'
            ) NOT NULL
        ");
    }
};
