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
        Schema::create('user_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->decimal('purchase_price', 18, 2)->comment('Price at purchase time');
            $table->integer('daily_task_limit');
            $table->decimal('daily_earning_limit', 18, 2);
            $table->decimal('total_earning', 18, 2)->default(0)->comment('Total earned from this package');
            $table->integer('completed_tasks')->default(0)->comment('Total tasks completed');
            $table->enum('status', ['active', 'expired', 'completed'])->default('active');
            $table->timestamp('valid_until')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'package_id']);
            $table->index('valid_until');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_packages');
    }
};
