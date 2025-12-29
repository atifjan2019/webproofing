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

    public function getSiteMetrics(User $user, Site $site, string $period, array $gscFilters = [], array $types = ['all']): array
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
            'gsc_pages' => null,
            'gsc_countries' => null,
            'daily' => [],
            'cached_at' => now()->toIso8601String(),
        ];

        // Fetch GA4 Data
        if ($site->hasGa4() && $user->googleAccount && (in_array('all', $types) || in_array('ga4', $types))) {
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

            // Overall GSC Stats & Daily
            if (in_array('all', $types) || in_array('gsc', $types)) {
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
            }

            // Fetch top queries WITH filters
            if (in_array('all', $types) || in_array('queries', $types)) {
                // Calculate comparison dates if not already done (for queries too)
                if (!isset($compareStartDate)) {
                    $start = \Carbon\Carbon::parse($startDate);
                    $end = \Carbon\Carbon::parse($endDate);
                    $days = $start->diffInDays($end) + 1;

                    $compareEndDate = $start->copy()->subDay()->format('Y-m-d');
                    $compareStartDate = $start->copy()->subDays($days)->format('Y-m-d');
                }

                $response['gsc_queries'] = $this->gscService->fetchGscQueries(
                    $user,
                    $site->gsc_site_url,
                    $startDate,
                    $endDate,
                    5000,
                    $gscFilters,
                    $compareStartDate,
                    $compareEndDate
                );
            }

            // Fetch top pages with comparison data
            if (in_array('all', $types) || in_array('pages', $types)) {
                // Calculate comparison dates (same duration, immediately preceding)
                $start = \Carbon\Carbon::parse($startDate);
                $end = \Carbon\Carbon::parse($endDate);
                $days = $start->diffInDays($end) + 1;

                $compareEndDate = $start->copy()->subDay()->format('Y-m-d');
                $compareStartDate = $start->copy()->subDays($days)->format('Y-m-d');

                $response['gsc_pages'] = $this->gscService->fetchGscPages(
                    $user,
                    $site->gsc_site_url,
                    $startDate,
                    $endDate,
                    20,
                    $compareStartDate, // Pass comparison start
                    $compareEndDate    // Pass comparison end
                );
            }

            // Fetch top countries
            if (in_array('all', $types) || in_array('countries', $types)) {
                $response['gsc_countries'] = $this->gscService->fetchGscCountries(
                    $user,
                    $site->gsc_site_url,
                    $startDate,
                    $endDate,
                    10
                );
            }
        }

        // Sort daily data by date
        $response['daily'] = array_values($response['daily']);
        usort($response['daily'], fn($a, $b) => strcmp($a['date'], $b['date']));

        return $response;
    }

    private function calculateDates(string $period): array
    {
        // Show all available data including today
        // Note: Today's GSC data may be incomplete or zero due to processing delays

        $end = now();
        $start = now();

        switch ($period) {
            case '24h':
                // "Last 24 hours" in GSC crosses days, so we need yesterday + today
                $start = now()->subDay();
                $end = now();
                break;
            case 'today':
                // Just today
                $start = now();
                $end = now();
                break;
            case 'yesterday':
                $start = now()->subDay();
                $end = now()->subDay();
                break;
            case '2d':
                $start = now()->subDay();
                $end = now();
                break;
            case '3d':
                $start = now()->subDays(2);
                $end = now();
                break;
            case '7d':
                $start = now()->subDays(6);
                $end = now();
                break;
            case '28d':
                $start = now()->subDays(27);
                $end = now();
                break;
            case '30d':
                $start = now()->subDays(29);
                $end = now();
                break;
            case '90d':
                $start = now()->subDays(89);
                $end = now();
                break;
            default:
                // Default to 7 days
                $start = now()->subDays(6);
                $end = now();
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
