<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing records - clear any url_prefix types
        DB::table('site_google_properties')
            ->whereNotNull('gsc_site_url')
            ->where('gsc_property_type', 'url_prefix')
            ->update([
                    'gsc_site_url' => null,
                    'gsc_property_type' => null,
                    'gsc_connected' => false,
                ]);

        // Add new gsc_domain column
        Schema::table('site_google_properties', function (Blueprint $table) {
            $table->string('gsc_domain')->nullable()->after('gsc_connected');
        });

        // Copy data from gsc_site_url to gsc_domain
        DB::table('site_google_properties')
            ->whereNotNull('gsc_site_url')
            ->update([
                    'gsc_domain' => DB::raw('gsc_site_url'),
                ]);

        // Drop old columns
        Schema::table('site_google_properties', function (Blueprint $table) {
            $table->dropColumn(['gsc_site_url', 'gsc_property_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_google_properties', function (Blueprint $table) {
            $table->string('gsc_site_url')->nullable()->after('gsc_connected');
            $table->enum('gsc_property_type', ['domain', 'url_prefix'])->nullable()->after('gsc_site_url');
        });

        // Copy data back
        DB::table('site_google_properties')
            ->whereNotNull('gsc_domain')
            ->update([
                    'gsc_site_url' => DB::raw('gsc_domain'),
                    'gsc_property_type' => 'domain',
                ]);

        Schema::table('site_google_properties', function (Blueprint $table) {
            $table->dropColumn('gsc_domain');
        });
    }
};
