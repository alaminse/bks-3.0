<?php

use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('total_value', 15, 2)->default(0);
            $table->decimal('total_shares_issued', 15, 2)->default(0);
            $table->decimal('available_shares', 15, 2)->default(0);
            $table->decimal('share_price', 10, 2)->default(0);
            $table->string('logo')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
