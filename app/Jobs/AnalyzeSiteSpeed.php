<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Site;
use App\Services\Google\PageSpeedService;
use Illuminate\Support\Facades\Log;

class AnalyzeSiteSpeed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Site $site;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 300;

    public function __construct(Site $site)
    {
        $this->site = $site;
    }

    public function handle(PageSpeedService $pageSpeedService): void
    {
        set_time_limit(300);

        $strategies = ['mobile', 'desktop'];
        $url = $this->site->domain;
        if (!str_starts_with($url, 'http')) {
            $url = 'https://' . $url;
        }

        foreach ($strategies as $strategy) {
            try {
                $result = $pageSpeedService->analyze($url, $strategy);

                if ($result) {
                    $this->site->pagespeedMetrics()->updateOrCreate(
                        ['strategy' => $strategy],
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
                }
            } catch (\Exception $e) {
                Log::error("Background speed analysis failed for site {$this->site->id} ({$strategy}): " . $e->getMessage());
            }
        }
    }
}
