<?php

namespace App\Jobs;

use App\Models\Site;
use App\Models\SitePagespeedMetric;
use App\Services\Google\PageSpeedService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RunPageSpeedAnalysis implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Site $site, public string $strategy = 'mobile')
    {
    }

    /**
     * Execute the job.
     */
    public function handle(PageSpeedService $pageSpeedService): void
    {
        Log::info("Running PageSpeed analysis for site: {$this->site->id} ({$this->site->domain})");

        $url = $this->site->domain;
        if (!str_starts_with($url, 'http')) {
            $url = 'https://' . $url;
        }

        $result = $pageSpeedService->analyze($url, $this->strategy);

        if (!$result) {
            Log::error("PageSpeed analysis failed for site: {$this->site->id}");
            return;
        }

        // Store Metrics
        SitePagespeedMetric::updateOrCreate(
            [
                'site_id' => $this->site->id,
                'strategy' => $this->strategy
            ],
            [
                'performance_score' => $result['scores']['performance'] ?? null,
                'seo_score' => $result['scores']['seo'] ?? null,
                'accessibility_score' => $result['scores']['accessibility'] ?? null,
                'best_practices_score' => $result['scores']['best_practices'] ?? null,
                'lcp' => $result['metrics']['lcp'] ?? null,
                'fcp' => $result['metrics']['fcp'] ?? null,
                'cls' => $result['metrics']['cls'] ?? null,
                'tbt' => $result['metrics']['tbt'] ?? null,
                'speed_index' => $result['metrics']['speed_index'] ?? null,
            ]
        );

        Log::info("PageSpeed analysis completed for site: {$this->site->id}");
    }
}
