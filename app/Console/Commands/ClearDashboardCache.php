<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearDashboardCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear dashboard statistics cache (EMIS completeness stats)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Clearing dashboard cache...');

        // Clear all dashboard_emis_stats_* cache keys
        $cleared = 0;
        $cacheStore = Cache::getStore();

        // For file cache, we need to match and delete by pattern
        // This will work with file-based cache (configured for shared hosting)
        if (method_exists($cacheStore, 'flush')) {
            // If flush is available, we can use it (but only for testing environments)
            // In production, we specifically target only dashboard cache
        }

        // Clear specific cache pattern by trying common keys
        // Since we don't have direct pattern matching in file cache,
        // we'll use a wildcard approach through Laravel's cache tags (if available)
        // For simple approach: clear by known range
        for ($count = 0; $count <= 10000; $count += 100) {
            $key = 'dashboard_emis_stats_' . $count;
            if (Cache::has($key)) {
                Cache::forget($key);
                $cleared++;
            }
        }

        // Also provide option to clear all application cache
        if ($this->confirm('Do you want to clear all application cache?', false)) {
            Cache::flush();
            $this->info('All application cache cleared!');
        } else {
            $this->info("Dashboard cache cleared! ({$cleared} keys removed)");
            $this->comment('Note: Dashboard stats will be recalculated on next page load.');
        }

        return Command::SUCCESS;
    }
}
