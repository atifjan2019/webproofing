<?php

namespace App\Services;

use App\Models\Site;
use App\Models\SiteScreenshot;
use Illuminate\Support\Facades\Log;

class ScreenshotService
{
    protected ScreenshotClient $client;

    public function __construct(ScreenshotClient $client)
    {
        $this->client = $client;
    }

    /**
     * Capture a screenshot for a site and save it to the database.
     *
     * @param Site $site
     * @param string $deviceType
     * @return array Result with 'success', 'screenshot' (model), or 'error'
     */
    public function captureAndSave(Site $site, string $deviceType = 'desktop'): array
    {
        $url = 'https://' . $site->domain;

        try {
            // Call the external screenshot API
            $result = $this->client->takeScreenshot($site->id, $url, true);

            if ($result['success']) {
                $screenshot = SiteScreenshot::create([
                    'site_id' => $site->id,
                    'url' => $url,
                    'image_path' => $result['filename'],
                    'device_type' => $deviceType,
                    'status' => 'captured',
                    'captured_at' => now(),
                    'load_time_ms' => $result['load_time_ms'] ?? 0,
                    'width' => 1200, // Default for now, or extract from API if available
                    'height' => 800,
                ]);

                return [
                    'success' => true,
                    'screenshot' => $screenshot
                ];
            } else {
                SiteScreenshot::create([
                    'site_id' => $site->id,
                    'url' => $url,
                    'status' => 'failed',
                    'error_message' => $result['error'] ?? 'Unknown error',
                    'captured_at' => now(),
                ]);

                return [
                    'success' => false,
                    'error' => $result['error'] ?? 'Unknown API error'
                ];
            }
        } catch (\Exception $e) {
            Log::error("Screenshot capture failed for site {$site->id}: " . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
