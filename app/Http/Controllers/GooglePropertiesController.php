<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\SiteMetricsDaily;
use App\Services\Google\GoogleAnalyticsService;
use App\Services\Google\GoogleSearchConsoleService;
use App\Services\SiteAnalyticsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class GooglePropertiesController extends Controller
{
    protected GoogleAnalyticsService $ga4Service;
    protected GoogleSearchConsoleService $gscService;

    public function __construct(
        GoogleAnalyticsService $ga4Service,
        GoogleSearchConsoleService $gscService
    ) {
        $this->middleware('auth');
        $this->ga4Service = $ga4Service;
        $this->gscService = $gscService;
    }

    /**
     * List available GA4 properties.
     */
    public function listGa4Properties()
    {
        $user = Auth::user();

        if (!$user->googleAccount) {
            return response()->json([
                'error' => 'No Google account connected',
            ], 400);
        }

        $properties = $this->ga4Service->listProperties($user);

        if ($properties === null) {
            return response()->json([
                'error' => 'Failed to fetch GA4 properties. Please reconnect your Google account.',
            ], 500);
        }

        return response()->json([
            'properties' => $properties,
        ]);
    }

    /**
     * Select GA4 property for a site.
     */
    public function selectGa4Property(Request $request, Site $site)
    {
        if ($site->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'property_id' => 'required|string',
            'property_name' => 'nullable|string',
        ]);

        $site->update([
            'ga4_property_id' => $request->property_id,
            'ga4_property_name' => $request->property_name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'GA4 property selected successfully.',
        ]);
    }

    /**
     * List available GSC properties.
     */
    public function listGscProperties()
    {
        $user = Auth::user();

        if (!$user->googleAccount) {
            return response()->json([
                'error' => 'No Google account connected',
            ], 400);
        }

        $properties = $this->gscService->listProperties($user);

        if ($properties === null) {
            return response()->json([
                'error' => 'Failed to fetch Search Console properties. Please reconnect your Google account.',
            ], 500);
        }

        return response()->json([
            'properties' => $properties,
        ]);
    }

    /**
     * Select GSC property for a site.
     */
    public function selectGscProperty(Request $request, Site $site)
    {
        if ($site->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'site_url' => 'required|string',
        ]);

        $site->update([
            'gsc_site_url' => $request->site_url,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Search Console property selected successfully.',
        ]);
    }

    /**
     * Get site metrics with filtering and cache support.
     */
    public function getSiteMetrics(Request $request, Site $site, SiteAnalyticsService $analyticsService)
    {
        if ($site->user_id !== Auth::id()) {
            abort(403);
        }

        $user = Auth::user();

        // If user has disconnected Google account, return disconnected state immediately
        // This prevents showing stale cached data
        if (!$user->googleAccount) {
            return response()->json([
                'ga4' => null,
                'gsc' => null,
                'daily' => [],
                'connected' => false,
                'error' => 'No Google account connected'
            ]);
        }

        $period = $request->input('period', '30d');
        $refresh = $request->boolean('refresh', false);
        $gscRegex = $request->input('gsc_regex');

        $type = $request->input('type', 'all');
        $types = ($type === 'all') ? ['all'] : [$type];

        $gscFilters = [];
        if ($gscRegex) {
            $gscFilters[] = [
                'dimension' => 'query',
                'operator' => 'includingRegex',
                'expression' => $gscRegex
            ];
        }

        // Cache key includes date to ensure daily freshness and filters to separate results
        $dateKey = now()->format('Y-m-d');
        $filterHash = $gscRegex ? '_' . md5($gscRegex) : '';
        $typeHash = ($type !== 'all') ? '_' . $type : '';

        $cacheKey = "site_analytics_v3_{$site->id}_{$period}_{$dateKey}{$filterHash}{$typeHash}";

        if ($refresh) {
            Cache::forget($cacheKey);
        }

        // Dynamic TTL: 'today' needs frequent updates, historical data cached for 12 hours (2x/day)
        $ttl = ($period === 'today') ? now()->addMinutes(30) : now()->addHours(12);

        $response = Cache::remember($cacheKey, $ttl, function () use ($user, $site, $period, $analyticsService, $gscFilters, $types) {
            $data = $analyticsService->getSiteMetrics($user, $site, $period, $gscFilters, $types);
            $data['cached_at'] = now()->toISOString();
            return $data;
        });

        return response()->json($response);
    }

    /**
     * Store daily metrics snapshot.
     */
    public function storeDailySnapshot(Site $site, string $date, array $ga4Data = null, array $gscData = null)
    {
        SiteMetricsDaily::updateOrCreate(
            [
                'site_id' => $site->id,
                'date' => $date,
            ],
            [
                'ga_users' => $ga4Data['users'] ?? null,
                'ga_sessions' => $ga4Data['sessions'] ?? null,
                'ga_pageviews' => $ga4Data['pageviews'] ?? null,
                'ga_engaged_sessions' => $ga4Data['engaged_sessions'] ?? null,
                'ga_engagement_rate' => $ga4Data['engagement_rate'] ?? null,
                'ga_event_count' => $ga4Data['event_count'] ?? null,
                'gsc_clicks' => $gscData['clicks'] ?? null,
                'gsc_impressions' => $gscData['impressions'] ?? null,
                'gsc_ctr' => $gscData['ctr'] ?? null,
                'gsc_position' => $gscData['position'] ?? null,
            ]
        );
    }
}
