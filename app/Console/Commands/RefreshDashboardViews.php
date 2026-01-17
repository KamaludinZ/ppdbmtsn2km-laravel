<?php

namespace App\Console\Commands;

use App\Models\DashboardStats;
use App\Models\JalurStats;
use App\Models\PanitiaStats;
use App\Models\RecentPendaftar;
use Illuminate\Console\Command;

class RefreshDashboardViews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:refresh-views {--concurrent : Refresh concurrently (allows reads during refresh)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh all dashboard materialized views for better performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $concurrent = $this->option('concurrent');

        $this->info('Starting to refresh dashboard materialized views...');
        $this->newLine();

        // Refresh Dashboard Stats
        $this->refreshView('Dashboard Stats', function () use ($concurrent) {
            DashboardStats::refreshView($concurrent);
        });

        // Refresh Jalur Stats
        $this->refreshView('Jalur Stats', function () use ($concurrent) {
            JalurStats::refreshView($concurrent);
        });

        // Refresh Recent Pendaftar
        $this->refreshView('Recent Pendaftar', function () use ($concurrent) {
            RecentPendaftar::refreshView($concurrent);
        });

        // Refresh Panitia Stats
        $this->refreshView('Panitia Stats', function () use ($concurrent) {
            PanitiaStats::refreshView($concurrent);
        });

        $this->newLine();
        $this->info('All materialized views refreshed successfully!');

        return Command::SUCCESS;
    }

    /**
     * Refresh a single view with progress indication
     */
    private function refreshView(string $name, callable $callback)
    {
        $this->info("Refreshing {$name}...");

        $startTime = microtime(true);

        try {
            $callback();

            $duration = round((microtime(true) - $startTime) * 1000, 2);
            $this->comment("  ✓ {$name} refreshed in {$duration}ms");
        } catch (\Exception $e) {
            $this->error("  ✗ Failed to refresh {$name}: {$e->getMessage()}");
            $this->warn("  Stack trace: {$e->getTraceAsString()}");
        }
    }
}
