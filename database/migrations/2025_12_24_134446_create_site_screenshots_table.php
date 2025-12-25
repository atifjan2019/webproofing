<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('site_screenshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->onDelete('cascade');
            $table->string('url'); // Page URL that was screenshotted
            $table->string('image_url')->nullable(); // Placeholder for actual image storage
            $table->string('image_path')->nullable(); // Local/cloud storage path
            $table->enum('device_type', ['desktop', 'mobile', 'tablet'])->default('desktop');
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->string('status')->default('pending'); // pending, captured, failed
            $table->text('error_message')->nullable();
            $table->timestamp('captured_at')->nullable();
            $table->timestamps();

            $table->index(['site_id', 'url']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_screenshots');
    }
};
