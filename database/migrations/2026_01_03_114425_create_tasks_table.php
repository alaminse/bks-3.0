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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('task_type', ['visit', 'youtube', 'install', 'survey', 'custom'])->default('custom');
            $table->string('task_url')->nullable()->comment('URL for visit/youtube tasks');
            $table->boolean('auto_verify')->nullable()->default(true)->comment('Auto verify task without admin approval');
            $table->integer('required_duration')->nullable()->comment('Required duration in seconds for auto verify');
            $table->enum('status', ['active', 'disabled'])->default('active');
            $table->integer('estimated_time')->nullable()->comment('Estimated time in minutes');
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('task_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
