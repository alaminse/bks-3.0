<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partner_shares', function (Blueprint $table) {
            // For sold status
            $table->decimal('sold_price', 15, 2)->nullable()->after('status');
            $table->timestamp('sold_at')->nullable()->after('sold_price');

            // For transferred status
            $table->foreignId('transferred_to')->nullable()->after('sold_at')->constrained('users')->onDelete('set null');
            $table->foreignId('transferred_from')->nullable()->after('transferred_to')->constrained('users')->onDelete('set null');
            $table->timestamp('transferred_at')->nullable()->after('transferred_from');
        });
    }

    public function down(): void
    {
        Schema::table('partner_shares', function (Blueprint $table) {
            $table->dropColumn(['sold_price', 'sold_at', 'transferred_to', 'transferred_from', 'transferred_at']);
        });
    }
};
