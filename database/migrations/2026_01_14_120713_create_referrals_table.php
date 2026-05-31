<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->cascadeOnDelete(); // Who referred
            $table->foreignId('referred_id')->constrained('users')->cascadeOnDelete(); // Who was referred
            $table->string('referral_code', 20)->nullable(); // Code used
            $table->enum('status', ['pending', 'active', 'inactive', 'completed'])->default('pending');
            $table->timestamp('activated_at')->nullable(); // When referred user became active
            $table->timestamp('completed_at')->nullable(); // When referral requirements met
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index('referrer_id');
            $table->index('referred_id');
            $table->index('referral_code');
            $table->index('status');
            $table->unique(['referrer_id', 'referred_id']); // Prevent duplicate referrals
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
