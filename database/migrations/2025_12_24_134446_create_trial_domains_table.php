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
        Schema::create('trial_domains', function (Blueprint $table) {
            $table->id();
            $table->string('domain')->unique(); // Normalized domain - only one trial per domain globally
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User who used the trial
            $table->foreignId('site_id')->constrained()->onDelete('cascade'); // Associated site
            $table->timestamp('trial_started_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable(); // 7 days from start
            $table->boolean('is_expired')->default(false);
            $table->timestamps();

            $table->index('domain');
            $table->index('trial_ends_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trial_domains');
    }
};
