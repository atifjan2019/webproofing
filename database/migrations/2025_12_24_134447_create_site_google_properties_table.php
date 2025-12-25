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
        Schema::create('site_google_properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->onDelete('cascade');

            // GA4 Property
            $table->string('ga4_property_id')->nullable(); // e.g., properties/123456789
            $table->string('ga4_property_name')->nullable();
            $table->boolean('ga4_connected')->default(false);

            // Google Search Console Property
            $table->string('gsc_site_url')->nullable(); // e.g., sc-domain:example.com or https://example.com/
            $table->enum('gsc_property_type', ['domain', 'url_prefix'])->nullable();
            $table->boolean('gsc_connected')->default(false);

            $table->timestamps();

            $table->unique('site_id'); // One property record per site
            $table->index('ga4_property_id');
            $table->index('gsc_site_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_google_properties');
    }
};
