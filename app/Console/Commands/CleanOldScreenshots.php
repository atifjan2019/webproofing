<?php

namespace App\Console\Commands;

use App\Models\SiteScreenshot;
use Illuminate\Console\Command;

class CleanOldScreenshots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'screenshots:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete screenshots older than 7 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = SiteScreenshot::where('created_at', '<', now()->subDays(7))->delete();
        $this->info("Deleted {$count} old screenshots.");
    }
}
