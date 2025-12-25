<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Use raw SQL for MariaDB compatibility
        if (Schema::hasColumn('google_accounts', 'google_id')) {
            DB::statement('ALTER TABLE google_accounts CHANGE google_id google_user_id VARCHAR(255) NULL');
        }
        if (Schema::hasColumn('google_accounts', 'token_expires_at')) {
            DB::statement('ALTER TABLE google_accounts CHANGE token_expires_at expires_at TIMESTAMP NULL');
        }

        Schema::table('google_accounts', function (Blueprint $table) {
            // Drop columns we don't need
            if (Schema::hasColumn('google_accounts', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('google_accounts', 'scopes')) {
                $table->dropColumn('scopes');
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('google_accounts', 'google_user_id')) {
            DB::statement('ALTER TABLE google_accounts CHANGE google_user_id google_id VARCHAR(255) NULL');
        }
        if (Schema::hasColumn('google_accounts', 'expires_at')) {
            DB::statement('ALTER TABLE google_accounts CHANGE expires_at token_expires_at TIMESTAMP NULL');
        }

        Schema::table('google_accounts', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->json('scopes')->nullable();
        });
    }
};
