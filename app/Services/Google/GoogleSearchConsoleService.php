<?php

namespace App\Services\Google;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleSearchConsoleService
{
    protected GoogleTokenManager $tokenManager;

    public function __construct(GoogleTokenManager $tokenManager)
    {
        $this->tokenManager = $tokenManager;
    }

    /**
     * List all verified sites in Search Console.
     */
    public function listProperties(User $user): ?array
    {
        $accessToken = $this->tokenManager->getValidAccessToken($user);

        if (!$accessToken) {
            return null;
        }

        try {
            $response = Http::withToken($accessToken)
                ->get(config('google.gsc_api_base') . '/sites');

            if (!$response->successful()) {
                Log::error('Failed to list GSC properties', [
                    'user_id' => $user->id,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }

            $data = $response->json();
            $sites = [];

            foreach ($data['siteEntry'] ?? [] as $site) {
                $sites[] = [
                    'site_url' => $site['siteUrl'],
                    'permission_level' => $site['permissionLevel'] ?? 'unknown',
                ];
            }

            return $sites;
        } catch (\Exception $e) {
            Log::error('Exception while listing GSC properties', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Fetch Search Console performance data.
     *
     * @param string $siteUrl The site URL (e.g., "sc-domain:example.com" or "https://example.com/")
     * @param string $startDate Format: YYYY-MM-DD
     * @param string $endDate Format: YYYY-MM-DD
     * @return array|null
     */
    public function fetchGscPerformance(User $user, string $siteUrl, string $startDate, string $endDate): ?array
    {
        $accessToken = $this->tokenManager->getValidAccessToken($user);

        if (!$accessToken) {
            return null;
        }

        try {
            $encodedSiteUrl = urlencode($siteUrl);

            $response = Http::withToken($accessToken)
                ->timeout(30)
                ->post(config('google.gsc_api_base') . "/sites/{$encodedSiteUrl}/searchAnalytics/query", [
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'dimensions' => [], // No dimensions = aggregate totals
                    'dataState' => 'all', // Include fresh/preliminary data
                ]);

            if (!$response->successful()) {
                Log::error('Failed to fetch GSC performance', [
                    'user_id' => $user->id,
                    'site_url' => $siteUrl,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }

            $data = $response->json();

            // Debug logging
            Log::info('GSC API Response', [
                'user_id' => $user->id,
                'site_url' => $siteUrl,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'has_rows' => isset($data['rows']),
                'row_count' => count($data['rows'] ?? []),
                'raw_data' => $data,
            ]);

            $row = $data['rows'][0] ?? null;

            if (!$row) {
                Log::info('GSC: No rows returned', [
                    'site_url' => $siteUrl,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ]);
                return [
                    'clicks' => 0,
                    'impressions' => 0,
                    'ctr' => 0,
                    'position' => 0,
                ];
            }

            return [
                'clicks' => (int) ($row['clicks'] ?? 0),
                'impressions' => (int) ($row['impressions'] ?? 0),
                'ctr' => (float) ($row['ctr'] ?? 0),
                'position' => (float) ($row['position'] ?? 0),
            ];
        } catch (\Exception $e) {
            Log::error('Exception while fetching GSC performance', [
                'user_id' => $user->id,
                'site_url' => $siteUrl,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Fetch daily GSC performance data for charting.
     */
    public function fetchGscDaily(User $user, string $siteUrl, string $startDate, string $endDate): ?array
    {
        $accessToken = $this->tokenManager->getValidAccessToken($user);

        if (!$accessToken) {
            return null;
        }

        try {
            $encodedSiteUrl = urlencode($siteUrl);

            $response = Http::withToken($accessToken)
                ->timeout(30)
                ->post(config('google.gsc_api_base') . "/sites/{$encodedSiteUrl}/searchAnalytics/query", [
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'dimensions' => ['date'],
                    'dataState' => 'all', // Include fresh/preliminary data
                ]);

            if (!$response->successful()) {
                Log::error('Failed to fetch GSC daily data', [
                    'user_id' => $user->id,
                    'site_url' => $siteUrl,
                    'status' => $response->status(),
                ]);
                return null;
            }

            $data = $response->json();
            $dailyData = [];

            foreach ($data['rows'] ?? [] as $row) {
                $date = $row['keys'][0] ?? null;

                if ($date) {
                    $dailyData[] = [
                        'date' => $date,
                        'clicks' => (int) ($row['clicks'] ?? 0),
                        'impressions' => (int) ($row['impressions'] ?? 0),
                        'ctr' => (float) ($row['ctr'] ?? 0),
                        'position' => (float) ($row['position'] ?? 0),
                    ];
                }
            }

            // Sort by date
            usort($dailyData, fn($a, $b) => strcmp($a['date'], $b['date']));

            return $dailyData;
        } catch (\Exception $e) {
            Log::error('Exception while fetching GSC daily data', [
                'user_id' => $user->id,
                'site_url' => $siteUrl,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Fetch top search queries from GSC.
     */
    public function fetchGscQueries(User $user, string $siteUrl, string $startDate, string $endDate, int $limit = 20): ?array
    {
        $accessToken = $this->tokenManager->getValidAccessToken($user);

        if (!$accessToken) {
            return null;
        }

        try {
            $encodedSiteUrl = urlencode($siteUrl);

            $response = Http::withToken($accessToken)
                ->post(config('google.gsc_api_base') . "/sites/{$encodedSiteUrl}/searchAnalytics/query", [
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'dimensions' => ['query'],
                    'rowLimit' => $limit,
                    'dataState' => 'all', // Include fresh/preliminary data
                ]);

            if (!$response->successful()) {
                Log::error('Failed to fetch GSC queries', [
                    'user_id' => $user->id,
                    'site_url' => $siteUrl,
                    'status' => $response->status(),
                ]);
                return null;
            }

            $data = $response->json();
            $queries = [];

            foreach ($data['rows'] ?? [] as $row) {
                $query = $row['keys'][0] ?? null;

                if ($query) {
                    $queries[] = [
                        'query' => $query,
                        'clicks' => (int) ($row['clicks'] ?? 0),
                        'impressions' => (int) ($row['impressions'] ?? 0),
                        'ctr' => (float) ($row['ctr'] ?? 0),
                        'position' => round((float) ($row['position'] ?? 0), 1),
                    ];
                }
            }

            return $queries;
        } catch (\Exception $e) {
            Log::error('Exception while fetching GSC queries', [
                'user_id' => $user->id,
                'site_url' => $siteUrl,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}
