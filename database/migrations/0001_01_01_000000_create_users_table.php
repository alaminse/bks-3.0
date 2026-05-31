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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // BIGINT PK
            $table->string('name', 150);
            $table->string('last_name')->nullable();
            $table->string('email', 150)->unique();
            $table->string('avatar')->nullable();
            $table->string('referral_code', 20)->unique()->nullable();
            $table->foreignId('referred_by')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('total_referrals')->default(0);
            $table->decimal('total_referral_earnings', 18, 8)->default(0);
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('password', 255);
            $table->enum('status', ['active', 'suspended', 'banned'])->default('active');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('last_login_ip', 50)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index('referral_code');
            $table->index('referred_by');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
