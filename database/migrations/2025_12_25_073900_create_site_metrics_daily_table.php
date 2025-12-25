<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('site_metrics_daily', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->onDelete('cascade');
            $table->date('date');

            // GA4 Metrics
            $table->integer('ga_users')->nullable();
            $table->integer('ga_sessions')->nullable();
            $table->integer('ga_pageviews')->nullable();
            $table->integer('ga_engaged_sessions')->nullable();
            $table->decimal('ga_engagement_rate', 5, 4)->nullable();
            $table->integer('ga_event_count')->nullable();

            // GSC Metrics
            $table->integer('gsc_clicks')->nullable();
            $table->integer('gsc_impressions')->nullable();
            $table->decimal('gsc_ctr', 5, 4)->nullable();
            $table->decimal('gsc_position', 5, 2)->nullable();

            $table->timestamps();

            $table->unique(['site_id', 'date']);
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_metrics_daily');
    }
};
