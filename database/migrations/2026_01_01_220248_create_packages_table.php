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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 18, 2);
            $table->integer('daily_tasks')->comment('Daily task limit');
            $table->decimal('daily_earning', 18, 2)->comment('Daily earning limit');
            $table->decimal('per_task_earning', 18, 2)->default(0)->comment('Per Task Earning');
            $table->integer('duration_days')->default(30)->comment('Package validity in days');
            $table->json('features')->nullable()->comment('Package features list');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('is_active');
            $table->index('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
