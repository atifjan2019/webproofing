<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Site;
use App\Services\ScreenshotService;
use Illuminate\Support\Facades\Log;

class CaptureSiteScreenshot implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Site $site;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 180;

    /**
     * Create a new job instance.
     */
    public function __construct(Site $site)
    {
        $this->site = $site;
    }

    /**
     * Execute the job.
     */
    public function handle(ScreenshotService $screenshotService): void
    {
        set_time_limit(180);

        try {
            $screenshotService->captureAndSave($this->site);
        } catch (\Exception $e) {
            Log::error('Background screenshot capture failed for site ' . $this->site->id . ': ' . $e->getMessage());
        }
    }
}
