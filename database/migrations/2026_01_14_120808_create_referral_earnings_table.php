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
        Schema::create('referral_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referral_id')->constrained()->cascadeOnDelete();
            $table->foreignId('referrer_id')->constrained('users')->cascadeOnDelete(); // User who earned
            $table->foreignId('referred_id')->constrained('users')->cascadeOnDelete(); // User who triggered earning
            $table->enum('type', ['signup', 'deposit', 'task', 'package', 'commission'])->default('signup');
            $table->decimal('amount', 18, 8);
            $table->decimal('commission_rate', 5, 2)->nullable(); // Percentage if applicable
            $table->enum('status', ['pending', 'approved', 'paid', 'rejected'])->default('pending');
            $table->string('reference_type', 50)->nullable(); // What triggered earning (Task, Package, etc.)
            $table->bigInteger('reference_id')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('wallet_transaction_id')->nullable()->constrained('wallet_transactions')->nullOnDelete();
            $table->timestamp('paid_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index('referral_id');
            $table->index('referrer_id');
            $table->index('referred_id');
            $table->index('type');
            $table->index('status');
            $table->index(['reference_type', 'reference_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_earnings');
    }
};
