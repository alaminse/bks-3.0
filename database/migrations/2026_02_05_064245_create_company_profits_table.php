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
        Schema::create('company_profits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->decimal('profit_amount', 15, 2);
            $table->date('profit_date');
            $table->enum('profit_type', ['monthly', 'quarterly', 'yearly', 'other'])->default('monthly');
            $table->text('description')->nullable();
            $table->enum('distribution_status', ['pending', 'distributed'])->default('pending');
            $table->timestamp('distributed_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            // Indexes for better performance
            $table->index('company_id');
            $table->index('distribution_status');
            $table->index('profit_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_profits');
    }
};
