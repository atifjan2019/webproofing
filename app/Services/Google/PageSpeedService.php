<?php

namespace App\Services\Google;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PageSpeedService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed';

    public function __construct()
    {
        $this->apiKey = config('google.pagespeed_api_key');
    }

    /**
     * Run PageSpeed test for a URL.
     * 
     * @param string $url The URL to test
     * @param string $strategy 'mobile' or 'desktop'
     */
    public function analyze(string $url, string $strategy = 'mobile'): ?array
    {
        if (!$this->apiKey) {
            Log::error('PageSpeed API key not configured');
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Referer' => config('app.url'),
            ])->get($this->baseUrl, [
                        'url' => $url,
                        'key' => $this->apiKey,
                        'strategy' => $strategy,
                        'category' => ['PERFORMANCE', 'SEO', 'ACCESSIBILITY', 'BEST_PRACTICES'],
                    ]);

            if (!$response->successful()) {
                Log::error('PageSpeed API failed', [
                    'url' => $url,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }

            return $this->formatResult($response->json());

        } catch (\Exception $e) {
            Log::error('PageSpeed exception', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    protected function formatResult(array $data): array
    {
        $lighthouse = $data['lighthouseResult'] ?? [];
        $categories = $lighthouse['categories'] ?? [];
        $audits = $lighthouse['audits'] ?? []; // For detailed metrics

        return [
            'scores' => [
                'performance' => ($categories['performance']['score'] ?? 0) * 100,
                'accessibility' => ($categories['accessibility']['score'] ?? 0) * 100,
                'best_practices' => ($categories['best-practices']['score'] ?? 0) * 100,
                'seo' => ($categories['seo']['score'] ?? 0) * 100,
            ],
            'metrics' => [
                'fcp' => $audits['first-contentful-paint']['displayValue'] ?? 'N/A', // First Contentful Paint
                'lcp' => $audits['largest-contentful-paint']['displayValue'] ?? 'N/A', // Largest Contentful Paint
                'tbt' => $audits['total-blocking-time']['displayValue'] ?? 'N/A', // Total Blocking Time
                'cls' => $audits['cumulative-layout-shift']['displayValue'] ?? 'N/A', // Cumulative Layout Shift
                'speed_index' => $audits['speed-index']['displayValue'] ?? 'N/A',
            ],
            'screenshot' => $lighthouse['audits']['final-screenshot']['details']['data'] ?? null, // Base64 image
            'loading_experience' => $data['loadingExperience']['overall_category'] ?? 'UNKNOWN',
        ];
    }
}
