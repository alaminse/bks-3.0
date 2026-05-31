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
        Schema::create('daily_package_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_package_id')->constrained()->cascadeOnDelete();
            $table->date('earning_date');
            $table->decimal('total_earned', 10, 2)->default(0);
            $table->integer('task_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            // একটি package এর একটি specific date এর জন্য একটাই record
            $table->unique(['user_package_id', 'earning_date']);
            $table->index('earning_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_package_earnings');
    }
};
