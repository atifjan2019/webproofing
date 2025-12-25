<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->string('ga4_property_id')->nullable()->after('status');
            $table->string('ga4_property_name')->nullable()->after('ga4_property_id');
            $table->string('gsc_site_url')->nullable()->after('ga4_property_name');
        });
    }

    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn(['ga4_property_id', 'ga4_property_name', 'gsc_site_url']);
        });
    }
};
