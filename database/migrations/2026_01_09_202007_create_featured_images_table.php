<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * এই migration টেবিল তৈরি করবে featured images এর জন্য
     * যেখানে banner/slider images store হবে
     */
    public function up(): void
    {
        Schema::create('featured_images', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_path');
            $table->string('link_url')->nullable();
            $table->integer('order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            // Indexes for better query performance
            $table->index('status');
            $table->index('order');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * Migration rollback করলে এই টেবিল delete হবে
     */
    public function down(): void
    {
        Schema::dropIfExists('featured_images');
    }
};
