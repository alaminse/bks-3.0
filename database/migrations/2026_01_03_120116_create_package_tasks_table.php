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
        Schema::create('package_tasks', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->unsignedBigInteger('package_id');
            $table->unsignedBigInteger('task_id');

            // Task reward and order
            $table->decimal('reward_amount', 10, 2);
            $table->integer('sort_order')->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('package_id')
                  ->references('id')
                  ->on('packages')
                  ->onDelete('cascade');

            $table->foreign('task_id')
                  ->references('id')
                  ->on('tasks')
                  ->onDelete('cascade');

            // Unique constraint: একই package এ একই task একবারই
            $table->unique(['package_id', 'task_id']);

            // Indexes
            $table->index('package_id');
            $table->index('task_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_tasks');
    }
};
