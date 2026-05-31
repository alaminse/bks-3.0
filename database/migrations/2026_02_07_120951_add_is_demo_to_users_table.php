<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add the 'isDemo' column as a boolean.
            // It will default to false, meaning new users are not demo accounts by default.
            // We place it after the 'email' column for organizational purposes.
            $table->boolean('isDemo')->default(false)->after('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // To rollback, we simply drop the 'isDemo' column.
            $table->dropColumn('isDemo');
        });
    }
};
