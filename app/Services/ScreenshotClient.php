<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ScreenshotClient
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = rtrim(env('SCREENSHOT_API_BASE'), '/');
        $this->token = env('SCREENSHOT_API_TOKEN');
    }

    /**
     * Take a screenshot of the given URL.
     *
     * @param int|string $siteId
     * @param string $url
     * @param bool $fullPage
     * @return array
     */
    public function takeScreenshot($siteId, $url, $fullPage = true)
    {
        $endpoint = $this->baseUrl . '/screenshot';

        try {
            $response = Http::withToken($this->token)
                ->timeout(60) // Generous timeout for screenshots
                ->post($endpoint, [
                    'site_id' => $siteId,
                    'url' => $url,
                    'fullPage' => $fullPage,
                ]);

            $data = $response->json();

            if ($response->successful() && isset($data['ok']) && $data['ok']) {
                $path = $data['path'] ?? '';
                // Strip "/app/" from the path to get the filename
                $filename = str_replace('/app/', '', $path);

                return [
                    'success' => true,
                    'filename' => $filename,
                    'load_time_ms' => $data['load_time_ms'] ?? null,
                    'data' => $data,
                ];
            }

            return [
                'success' => false,
                'error' => $data['error'] ?? 'Unknown error',
                'status' => $response->status(),
            ];

        } catch (\Exception $e) {
            Log::error('Screenshot service error: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
