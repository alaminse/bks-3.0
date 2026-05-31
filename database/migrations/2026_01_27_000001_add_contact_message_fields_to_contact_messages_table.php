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
        Schema::table('contact_messages', function (Blueprint $table) {
            // Add columns for tracking message status
            $table->boolean('is_read')->default(false)->after('message');
            $table->timestamp('read_at')->nullable()->after('is_read');
            $table->unsignedBigInteger('read_by')->nullable()->after('read_at');

            // Add columns for reply functionality
            $table->text('reply_message')->nullable()->after('read_by');
            $table->timestamp('replied_at')->nullable()->after('reply_message');
            $table->unsignedBigInteger('replied_by')->nullable()->after('replied_at');

            // Add foreign keys if users table exists
            $table->foreign('read_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('replied_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropForeign(['read_by']);
            $table->dropForeign(['replied_by']);

            $table->dropColumn([
                'is_read',
                'read_at',
                'read_by',
                'reply_message',
                'replied_at',
                'replied_by',
            ]);
        });
    }
};
