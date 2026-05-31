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
        Schema::create('user_task_submissions', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('user_package_id');
            $table->unsignedBigInteger('task_id');

            // Proof data
            $table->string('proof')->nullable()->comment('Screenshot or proof file path');
            $table->text('proof_text')->nullable()->comment('Text proof or notes');

            // Status and reward
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->decimal('reward_amount', 10, 2);

            // Rejection info
            $table->text('rejection_reason')->nullable();

            // Timestamps
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('approved_at')->nullable();

            // Approver
            $table->unsignedBigInteger('approved_by')->nullable();

            $table->softDeletes();
            // Foreign key constraints
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('user_package_id')
                  ->references('id')
                  ->on('user_packages')
                  ->onDelete('cascade');

            $table->foreign('task_id')
                  ->references('id')
                  ->on('tasks')
                  ->onDelete('cascade');

            $table->foreign('approved_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');

            // Indexes for performance
            $table->index(['user_id', 'status']);
            $table->index(['user_package_id', 'submitted_at']);
            $table->index('status');
            $table->index(['task_id', 'submitted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_task_submissions');
    }
};
