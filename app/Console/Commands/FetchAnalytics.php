<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\SiteAnalyticsService;
use Illuminate\Support\Facades\Cache;

class FetchAnalytics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analytics:fetch-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and cache daily analytics for all sites';

    /**
     * Execute the console command.
     */
    public function handle(SiteAnalyticsService $analyticsService)
    {
        $this->info('Starting analytics fetch...');

        $users = User::with(['sites', 'googleAccount'])->get();

        foreach ($users as $user) {
            if (!$user->googleAccount) {
                continue;
            }

            $this->info("Processing user: {$user->email}");

            foreach ($user->sites as $site) {
                // Skip if no properties configured
                if (!$site->hasGa4() && !$site->hasGsc()) {
                    continue;
                }

                $this->info("Fetching metrics for site: {$site->domain}");

                try {
                    // Fetch for default periods
                    $periods = ['30d', '90d'];

                    foreach ($periods as $period) {
                        // Measure time
                        $start = microtime(true);

                        $data = $analyticsService->getSiteMetrics($user, $site, $period);

                        // Cache it
                        $dateKey = now()->format('Y-m-d');
                        $cacheKey = "site_analytics_{$site->id}_{$period}_{$dateKey}";

                        // Determine cache duration
                        $ttl = ($period === 'today') ? now()->addMinutes(30) : now()->addHours(24);
                        Cache::put($cacheKey, $data, $ttl);

                        $duration = round(microtime(true) - $start, 2);
                        $this->info("Fetched {$period} in {$duration}s");
                    }

                    $duration = round(microtime(true) - $start, 2);
                    $this->info("Done in {$duration}s");

                } catch (\Exception $e) {
                    $this->error("Failed for site {$site->domain}: " . $e->getMessage());
                }
            }
        }

        $this->info('Analytics fetch completed.');
    }
}
