<?php

namespace App\Services\Google;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleAnalyticsService
{
    protected GoogleTokenManager $tokenManager;

    public function __construct(GoogleTokenManager $tokenManager)
    {
        $this->tokenManager = $tokenManager;
    }

    /**
     * List all GA4 properties accessible by the user.
     * Improved to handle viewer-only access and different account setups.
     */
    public function listProperties(User $user): ?array
    {
        $accessToken = $this->tokenManager->getValidAccessToken($user);

        if (!$accessToken) {
            Log::warning('GA4 listProperties: No valid access token', ['user_id' => $user->id]);
            return null;
        }

        try {
            // Try fetching via accountSummaries (works for most cases)
            $response = Http::withToken($accessToken)
                ->get(config('google.ga4_admin_api_base') . '/accountSummaries');

            Log::info('GA4 listProperties response', [
                'user_id' => $user->id,
                'status' => $response->status(),
                'has_body' => !empty($response->body()),
            ]);

            if (!$response->successful()) {
                Log::error('Failed to list GA4 properties', [
                    'user_id' => $user->id,
                    'status' => $response->status(),
                    'error_code' => $response->json('error.code'),
                    'error_message' => $response->json('error.message'),
                    'body' => substr($response->body(), 0, 500),
                ]);

                // Check for specific error types
                $errorCode = $response->json('error.code');
                if ($errorCode === 403) {
                    Log::warning('GA4: Permission denied - user may need to reconnect with correct scopes', [
                        'user_id' => $user->id
                    ]);
                }

                return null;
            }

            $data = $response->json();
            $properties = [];

            // Log the raw response for debugging
            Log::debug('GA4 accountSummaries data', [
                'user_id' => $user->id,
                'account_count' => count($data['accountSummaries'] ?? []),
                'raw_accounts' => array_map(fn($a) => $a['displayName'] ?? 'Unknown', $data['accountSummaries'] ?? []),
            ]);

            // Extract properties from account summaries
            foreach ($data['accountSummaries'] ?? [] as $account) {
                $accountName = $account['displayName'] ?? 'Unknown Account';

                foreach ($account['propertySummaries'] ?? [] as $property) {
                    // Property format is "properties/123456789"
                    $propertyId = str_replace('properties/', '', $property['property']);

                    $properties[] = [
                        'property_id' => $propertyId,
                        'property_name' => $property['displayName'] ?? 'Unknown Property',
                        'account_name' => $accountName,
                        'property_type' => $property['propertyType'] ?? 'PROPERTY_TYPE_UNSPECIFIED',
                    ];
                }
            }

            Log::info('GA4 properties found', [
                'user_id' => $user->id,
                'count' => count($properties),
                'properties' => array_map(fn($p) => $p['property_name'], $properties),
            ]);

            // If no properties found via accountSummaries, log helpful message
            if (empty($properties)) {
                Log::info('GA4: No properties found for user. Possible reasons: no GA4 properties, viewer access on different email, or Universal Analytics only.', [
                    'user_id' => $user->id,
                    'google_email' => $user->googleAccount?->email,
                ]);
            }

            return $properties;
        } catch (\Exception $e) {
            Log::error('Exception while listing GA4 properties', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    /**
     * Fetch GA4 summary metrics for a property.
     *
     * @param string $propertyId GA4 property ID (numbers only)
     * @param string $startDate Format: YYYY-MM-DD
     * @param string $endDate Format: YYYY-MM-DD
     * @return array|null
     */
    public function fetchGa4Summary(User $user, string $propertyId, string $startDate, string $endDate): ?array
    {
        $accessToken = $this->tokenManager->getValidAccessToken($user);

        if (!$accessToken) {
            Log::warning('GA4 fetchGa4Summary: No valid access token', ['user_id' => $user->id]);
            return null;
        }

        try {
            $response = Http::withToken($accessToken)
                ->post(config('google.ga4_api_base') . "/properties/{$propertyId}:runReport", [
                    'dateRanges' => [
                        ['startDate' => $startDate, 'endDate' => $endDate],
                    ],
                    'metrics' => [
                        ['name' => 'activeUsers'],
                        ['name' => 'sessions'],
                        ['name' => 'engagedSessions'],
                        ['name' => 'engagementRate'],
                        ['name' => 'screenPageViews'],
                        ['name' => 'eventCount'],
                    ],
                ]);

            if (!$response->successful()) {
                Log::error('Failed to fetch GA4 summary', [
                    'user_id' => $user->id,
                    'property_id' => $propertyId,
                    'date_range' => [$startDate, $endDate],
                    'status' => $response->status(),
                    'error' => $response->json('error.message'),
                    'body' => substr($response->body(), 0, 500),
                ]);
                return null;
            }

            $data = $response->json();

            // Parse the response
            $metrics = $data['rows'][0]['metricValues'] ?? [];

            if (empty($metrics)) {
                Log::info('GA4 summary: No data for date range', [
                    'user_id' => $user->id,
                    'property_id' => $propertyId,
                    'date_range' => [$startDate, $endDate],
                ]);
            }

            return [
                'users' => (int) ($metrics[0]['value'] ?? 0),
                'sessions' => (int) ($metrics[1]['value'] ?? 0),
                'engaged_sessions' => (int) ($metrics[2]['value'] ?? 0),
                'engagement_rate' => (float) ($metrics[3]['value'] ?? 0),
                'pageviews' => (int) ($metrics[4]['value'] ?? 0),
                'event_count' => (int) ($metrics[5]['value'] ?? 0),
            ];
        } catch (\Exception $e) {
            Log::error('Exception while fetching GA4 summary', [
                'user_id' => $user->id,
                'property_id' => $propertyId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Fetch daily GA4 metrics for charting.
     */
    public function fetchGa4Daily(User $user, string $propertyId, string $startDate, string $endDate): ?array
    {
        $accessToken = $this->tokenManager->getValidAccessToken($user);

        if (!$accessToken) {
            return null;
        }

        try {
            $response = Http::withToken($accessToken)
                ->post(config('google.ga4_api_base') . "/properties/{$propertyId}:runReport", [
                    'dateRanges' => [
                        ['startDate' => $startDate, 'endDate' => $endDate],
                    ],
                    'dimensions' => [
                        ['name' => 'date'],
                    ],
                    'metrics' => [
                        ['name' => 'activeUsers'],
                        ['name' => 'sessions'],
                        ['name' => 'engagedSessions'],
                        ['name' => 'engagementRate'],
                        ['name' => 'screenPageViews'],
                        ['name' => 'eventCount'],
                    ],
                    'orderBys' => [
                        ['dimension' => ['dimensionName' => 'date']],
                    ],
                ]);

            if (!$response->successful()) {
                Log::error('Failed to fetch GA4 daily metrics', [
                    'user_id' => $user->id,
                    'property_id' => $propertyId,
                    'status' => $response->status(),
                ]);
                return null;
            }

            $data = $response->json();
            $dailyData = [];

            foreach ($data['rows'] ?? [] as $row) {
                $date = $row['dimensionValues'][0]['value'] ?? null;
                $metrics = $row['metricValues'] ?? [];

                if ($date) {
                    // Convert YYYYMMDD to YYYY-MM-DD
                    $formattedDate = substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, 6, 2);

                    $dailyData[] = [
                        'date' => $formattedDate,
                        'users' => (int) ($metrics[0]['value'] ?? 0),
                        'sessions' => (int) ($metrics[1]['value'] ?? 0),
                        'engaged_sessions' => (int) ($metrics[2]['value'] ?? 0),
                        'engagement_rate' => (float) ($metrics[3]['value'] ?? 0),
                        'pageviews' => (int) ($metrics[4]['value'] ?? 0),
                        'event_count' => (int) ($metrics[5]['value'] ?? 0),
                    ];
                }
            }

            return $dailyData;
        } catch (\Exception $e) {
            Log::error('Exception while fetching GA4 daily metrics', [
                'user_id' => $user->id,
                'property_id' => $propertyId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}

