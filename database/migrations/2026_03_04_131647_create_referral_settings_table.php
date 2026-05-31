<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── referral_settings: one row per generation level ──────────────
        Schema::create('referral_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('generation')       // 1 = direct, 2 = indirect, 3 = 3rd level…
                  ->unique()
                  ->comment('1 = direct referral, 2 = 2nd generation, etc.');
            $table->decimal('commission_rate', 5, 2)        // e.g. 10.00 = 10%
                  ->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('label')->nullable()             // "Direct", "Level 2", …
                  ->comment('Display label for admin UI');
            $table->timestamps();
        });

        // ── Seed default 3 generations ───────────────────────────────────
        DB::table('referral_settings')->insert([
            ['generation' => 1, 'commission_rate' => 10.00, 'is_active' => true, 'label' => 'Direct (Level 1)', 'created_at' => now(), 'updated_at' => now()],
            ['generation' => 2, 'commission_rate' => 5.00,  'is_active' => true, 'label' => 'Level 2',          'created_at' => now(), 'updated_at' => now()],
            ['generation' => 3, 'commission_rate' => 2.00,  'is_active' => false,'label' => 'Level 3',          'created_at' => now(), 'updated_at' => now()],
        ]);

        // ── Add generation column to referral_earnings ───────────────────
        Schema::table('referral_earnings', function (Blueprint $table) {
            $table->unsignedTinyInteger('generation')->default(1)->after('type')
                  ->comment('Which generation this earning belongs to');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_settings');

        Schema::table('referral_earnings', function (Blueprint $table) {
            $table->dropColumn('generation');
        });
    }
};
