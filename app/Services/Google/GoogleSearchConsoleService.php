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
    /**
     * Fetch top search queries from GSC with optional comparison.
     */
    public function fetchGscQueries(User $user, string $siteUrl, string $startDate, string $endDate, int $limit = 20, array $filters = [], ?string $compareStartDate = null, ?string $compareEndDate = null): ?array
    {
        $accessToken = $this->tokenManager->getValidAccessToken($user);

        if (!$accessToken) {
            return null;
        }

        try {
            $encodedSiteUrl = urlencode($siteUrl);

            // 1. Fetch Current Period Data
            $requestBody = [
                'startDate' => $startDate,
                'endDate' => $endDate,
                'dimensions' => ['query'],
                'rowLimit' => $limit,
                'dataState' => 'all', // Include fresh/preliminary data
            ];

            if (!empty($filters)) {
                $requestBody['dimensionFilterGroups'] = [
                    [
                        'filters' => array_map(function ($f) {
                            return [
                                'dimension' => $f['dimension'] ?? 'query',
                                'operator' => $f['operator'] ?? 'includingRegex',
                                'expression' => $f['expression'],
                            ];
                        }, $filters)
                    ]
                ];
            }

            $response = Http::withToken($accessToken)
                ->post(config('google.gsc_api_base') . "/sites/{$encodedSiteUrl}/searchAnalytics/query", $requestBody);

            if (!$response->successful()) {
                Log::error('Failed to fetch GSC queries', [
                    'user_id' => $user->id,
                    'site_url' => $siteUrl,
                    'status' => $response->status(),
                ]);
                return null;
            }

            $currentData = $response->json()['rows'] ?? [];

            // 2. Fetch Comparison Period Data (if requested)
            $compareDataMap = [];
            if ($compareStartDate && $compareEndDate) {
                // Fetch more rows to increase match rate
                $compareRequestBody = [
                    'startDate' => $compareStartDate,
                    'endDate' => $compareEndDate,
                    'dimensions' => ['query'],
                    'rowLimit' => $limit * 2,
                    'dataState' => 'all',
                ];

                // Apply same filters to comparison request
                if (!empty($filters)) {
                    $compareRequestBody['dimensionFilterGroups'] = $requestBody['dimensionFilterGroups'];
                }

                $compareResponse = Http::withToken($accessToken)
                    ->post(config('google.gsc_api_base') . "/sites/{$encodedSiteUrl}/searchAnalytics/query", $compareRequestBody);

                if ($compareResponse->successful()) {
                    $compareRows = $compareResponse->json()['rows'] ?? [];
                    foreach ($compareRows as $row) {
                        $term = $row['keys'][0] ?? null;
                        if ($term) {
                            $compareDataMap[$term] = [
                                'clicks' => (int) ($row['clicks'] ?? 0),
                                'impressions' => (int) ($row['impressions'] ?? 0),
                            ];
                        }
                    }
                }
            }

            // 3. Merge and Format Data
            $queries = [];
            foreach ($currentData as $row) {
                $query = $row['keys'][0] ?? null;

                if ($query) {
                    $clicks = (int) ($row['clicks'] ?? 0);
                    $impressions = (int) ($row['impressions'] ?? 0);

                    $growthData = [];
                    if ($compareStartDate && $compareEndDate) {
                        // Even if map is empty, we must indicate we TRIED comparison
                        $prevClicks = $compareDataMap[$query]['clicks'] ?? 0;
                        $prevImpressions = $compareDataMap[$query]['impressions'] ?? 0;

                        // Clicks Growth
                        if ($prevClicks > 0) {
                            $growthData['clicks_growth'] = round((($clicks - $prevClicks) / $prevClicks) * 100);
                        } else {
                            $growthData['clicks_growth'] = $clicks > 0 ? 100 : 0;
                        }

                        // Impressions Growth
                        if ($prevImpressions > 0) {
                            $growthData['impressions_growth'] = round((($impressions - $prevImpressions) / $prevImpressions) * 100);
                        } else {
                            $growthData['impressions_growth'] = $impressions > 0 ? 100 : 0;
                        }

                        $growthData['prev_clicks'] = $prevClicks;
                    }

                    $queries[] = array_merge([
                        'query' => $query,
                        'clicks' => $clicks,
                        'impressions' => $impressions,
                        'ctr' => (float) ($row['ctr'] ?? 0),
                        'position' => round((float) ($row['position'] ?? 0), 1),
                    ], $growthData);
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
    /**
     * Fetch top pages from GSC.
     */
    /**
     * Fetch top pages from GSC with optional comparison data.
     */
    public function fetchGscPages(User $user, string $siteUrl, string $startDate, string $endDate, int $limit = 20, ?string $compareStartDate = null, ?string $compareEndDate = null): ?array
    {
        $accessToken = $this->tokenManager->getValidAccessToken($user);

        if (!$accessToken) {
            return null;
        }

        try {
            $encodedSiteUrl = urlencode($siteUrl);

            // 1. Fetch Current Period Data
            $requestBody = [
                'startDate' => $startDate,
                'endDate' => $endDate,
                'dimensions' => ['page'],
                'rowLimit' => $limit,
                'dataState' => 'all',
            ];

            $response = Http::withToken($accessToken)
                ->post(config('google.gsc_api_base') . "/sites/{$encodedSiteUrl}/searchAnalytics/query", $requestBody);

            if (!$response->successful()) {
                Log::error('Failed to fetch GSC pages', [
                    'user_id' => $user->id,
                    'site_url' => $siteUrl,
                    'status' => $response->status(),
                ]);
                return null;
            }

            $currentData = $response->json()['rows'] ?? [];

            // 2. Fetch Comparison Period Data (if requested)
            $compareDataMap = [];
            if ($compareStartDate && $compareEndDate) {
                // We fetch more rows for comparison to ensure we find matches for the top current pages
                $compareRequestBody = [
                    'startDate' => $compareStartDate,
                    'endDate' => $compareEndDate,
                    'dimensions' => ['page'],
                    'rowLimit' => $limit * 2, // Fetch more to increase hit rate
                    'dataState' => 'all',
                ];

                $compareResponse = Http::withToken($accessToken)
                    ->post(config('google.gsc_api_base') . "/sites/{$encodedSiteUrl}/searchAnalytics/query", $compareRequestBody);

                if ($compareResponse->successful()) {
                    $compareRows = $compareResponse->json()['rows'] ?? [];
                    foreach ($compareRows as $row) {
                        $page = $row['keys'][0] ?? null;
                        if ($page) {
                            $compareDataMap[$page] = [
                                'clicks' => (int) ($row['clicks'] ?? 0),
                                'impressions' => (int) ($row['impressions'] ?? 0),
                            ];
                        }
                    }
                }
            }

            // 3. Merge and Format Data
            $pages = [];
            foreach ($currentData as $row) {
                $page = $row['keys'][0] ?? null;

                if ($page) {
                    $clicks = (int) ($row['clicks'] ?? 0);
                    $impressions = (int) ($row['impressions'] ?? 0);

                    $growthData = [];
                    if (!empty($compareDataMap)) {
                        $prevClicks = $compareDataMap[$page]['clicks'] ?? 0;
                        $prevImpressions = $compareDataMap[$page]['impressions'] ?? 0;

                        // Clicks Growth
                        if ($prevClicks > 0) {
                            $growthData['clicks_growth'] = round((($clicks - $prevClicks) / $prevClicks) * 100);
                        } else {
                            $growthData['clicks_growth'] = $clicks > 0 ? 100 : 0; // New page or huge growth
                        }

                        // Impressions Growth
                        if ($prevImpressions > 0) {
                            $growthData['impressions_growth'] = round((($impressions - $prevImpressions) / $prevImpressions) * 100);
                        } else {
                            $growthData['impressions_growth'] = $impressions > 0 ? 100 : 0;
                        }

                        $growthData['prev_clicks'] = $prevClicks;
                    }

                    $pages[] = array_merge([
                        'page' => $page,
                        'clicks' => $clicks,
                        'impressions' => $impressions,
                        'ctr' => (float) ($row['ctr'] ?? 0),
                        'position' => round((float) ($row['position'] ?? 0), 1),
                    ], $growthData);
                }
            }

            return $pages;

        } catch (\Exception $e) {
            Log::error('Exception while fetching GSC pages', [
                'user_id' => $user->id,
                'site_url' => $siteUrl,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Fetch top countries from GSC.
     */
    public function fetchGscCountries(User $user, string $siteUrl, string $startDate, string $endDate, int $limit = 10): ?array
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
                    'dimensions' => ['country'],
                    'rowLimit' => $limit,
                    'dataState' => 'all',
                ]);

            if (!$response->successful()) {
                Log::error('Failed to fetch GSC countries', [
                    'user_id' => $user->id,
                    'site_url' => $siteUrl,
                    'status' => $response->status(),
                ]);
                return null;
            }

            $data = $response->json();
            $countries = [];

            foreach ($data['rows'] ?? [] as $row) {
                $countryCode = $row['keys'][0] ?? null; // GSC returns 'usa', 'gbr', etc. or ISO codes? usually ISO-3 lower or ISO-2.
                // Actually GSC API returns ISO 3166-1 alpha-3 code (e.g. USA, GBR) usually. 
                // Let's just return what it gives for now.

                if ($countryCode) {
                    $countries[] = [
                        'country' => $countryCode,
                        'clicks' => (int) ($row['clicks'] ?? 0),
                        'impressions' => (int) ($row['impressions'] ?? 0),
                        'ctr' => (float) ($row['ctr'] ?? 0),
                    ];
                }
            }

            return $countries;
        } catch (\Exception $e) {
            Log::error('Exception while fetching GSC countries', [
                'user_id' => $user->id,
                'site_url' => $siteUrl,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}
