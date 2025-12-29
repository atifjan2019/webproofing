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
            // Manually build query string for repeated 'category' parameters
            $categories = ['performance', 'accessibility', 'best-practices', 'seo'];
            $queryParams = [
                'url' => $url,
                'key' => $this->apiKey,
                'strategy' => $strategy,
            ];

            $queryString = http_build_query($queryParams);

            // Append categories without array brackets
            foreach ($categories as $cat) {
                $queryString .= '&category=' . $cat;
            }

            $response = Http::timeout(150)
                ->withHeaders([
                    'Referer' => config('app.url'),
                ])->get($this->baseUrl . '?' . $queryString);

            if (!$response->successful()) {
                $errorData = $response->json();
                $errorMessage = $errorData['error']['message'] ?? 'Unknown Google API Error';

                Log::error('PageSpeed API failed', [
                    'url' => $url,
                    'status' => $response->status(),
                    'error' => $errorMessage,
                    'full_body' => $response->body(),
                ]);

                throw new \Exception($errorMessage);
            }

            $data = $response->json();

            // Log for debugging
            $receivedCategories = array_keys($data['lighthouseResult']['categories'] ?? []);
            Log::info('PageSpeed categories received', ['list' => $receivedCategories]);

            return $this->formatResult($data);

        } catch (\Exception $e) {
            Log::error('PageSpeed exception', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);
            throw $e;
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
