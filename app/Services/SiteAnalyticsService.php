<?php

namespace App\Services;

use App\Models\Site;
use App\Models\User;
use App\Services\Google\GoogleAnalyticsService;
use App\Services\Google\GoogleSearchConsoleService;


class SiteAnalyticsService
{
    public function __construct(
        protected GoogleAnalyticsService $ga4Service,
        protected GoogleSearchConsoleService $gscService
    ) {
    }

    public function getSiteMetrics(User $user, Site $site, string $period): array
    {
        $dates = $this->calculateDates($period);
        $startDate = $dates['start'];
        $endDate = $dates['end'];

        $response = [
            'period' => $period,
            'date_range' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
            'ga4' => null,
            'gsc' => null,
            'gsc_queries' => null,
            'daily' => [],
            'cached_at' => now()->toIso8601String(),
        ];

        // Fetch GA4 Data
        if ($site->hasGa4() && $user->googleAccount) {
            $response['ga4'] = $this->ga4Service->fetchGa4Summary(
                $user,
                $site->ga4_property_id,
                $startDate,
                $endDate
            );

            // Daily Data
            $ga4Daily = $this->ga4Service->fetchGa4Daily(
                $user,
                $site->ga4_property_id,
                $startDate,
                $endDate
            );
            $this->mergeDailyData($response['daily'], $ga4Daily, 'ga');
        }

        // Fetch GSC Data
        if ($site->hasGsc() && $user->googleAccount) {
            $response['gsc'] = $this->gscService->fetchGscPerformance(
                $user,
                $site->gsc_site_url,
                $startDate,
                $endDate
            );

            // Daily Data
            $gscDaily = $this->gscService->fetchGscDaily(
                $user,
                $site->gsc_site_url,
                $startDate,
                $endDate
            );
            $this->mergeDailyData($response['daily'], $gscDaily, 'gsc');

            // Fetch top queries
            $response['gsc_queries'] = $this->gscService->fetchGscQueries(
                $user,
                $site->gsc_site_url,
                $startDate,
                $endDate,
                20
            );
        }

        // Sort daily data by date
        $response['daily'] = array_values($response['daily']);
        usort($response['daily'], fn($a, $b) => strcmp($a['date'], $b['date']));

        return $response;
    }

    private function calculateDates(string $period): array
    {
        // GSC data has a 24-48 hour delay
        // We offset by 1 day for shorter periods to show more recent available data

        $end = now();
        $start = now();

        switch ($period) {
            case 'today':
                // Show yesterday's data since today is often incomplete
                $start = now()->subDay();
                $end = now()->subDay();
                break;
            case 'yesterday':
                $start = now()->subDays(2);
                $end = now()->subDays(2);
                break;
            case '2d':
                $start = now()->subDays(2);
                $end = now()->subDay();
                break;
            case '3d':
                $start = now()->subDays(3);
                $end = now()->subDay();
                break;
            case '7d':
                // Last 7 days ending yesterday
                $start = now()->subDays(7);
                $end = now()->subDay();
                break;
            case '28d':
                $start = now()->subDays(28);
                $end = now()->subDay();
                break;
            case '30d':
                $start = now()->subDays(30);
                $end = now()->subDay();
                break;
            case '90d':
                $start = now()->subDays(90);
                $end = now()->subDay();
                break;
            default:
                // Default to 7 days
                $start = now()->subDays(7);
                $end = now()->subDay();
                break;
        }

        return [
            'start' => $start->format('Y-m-d'),
            'end' => $end->format('Y-m-d'),
        ];
    }

    private function mergeDailyData(array &$target, ?array $source, string $prefix)
    {
        if (!$source)
            return;

        foreach ($source as $day) {
            $date = $day['date'];
            if (!isset($target[$date])) {
                $target[$date] = ['date' => $date];
            }

            foreach ($day as $key => $val) {
                if ($key !== 'date') {
                    $target[$date]["{$prefix}_{$key}"] = $val;
                }
            }
        }
    }
}
