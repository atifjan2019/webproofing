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
        Schema::create('site_pagespeed_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->string('strategy')->default('mobile'); // mobile or desktop

            // Scores (0-100)
            $table->integer('performance_score')->nullable();
            $table->integer('seo_score')->nullable();
            $table->integer('accessibility_score')->nullable();
            $table->integer('best_practices_score')->nullable();

            // Core Web Vitals (Stored as strings to preserve units like "0.5 s")
            $table->string('lcp')->nullable(); // Largest Contentful Paint
            $table->string('fcp')->nullable(); // First Contentful Paint
            $table->string('cls')->nullable(); // Cumulative Layout Shift
            $table->string('tbt')->nullable(); // Total Blocking Time
            $table->string('speed_index')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_pagespeed_metrics');
    }
};
