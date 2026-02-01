<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

/**
 * Command untuk membersihkan log files lama
 * Run: php artisan log:cleanup
 */
class LogCleanup extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'log:cleanup
                            {--days=30 : Number of days to keep logs}
                            {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     */
    protected $description = 'Clean up old log files';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = (int) $this->option('days');
        $dryRun = $this->option('dry-run');
        $logsPath = storage_path('logs');

        $this->info("Cleaning up log files older than {$days} days...");

        if ($dryRun) {
            $this->warn("DRY RUN MODE - No files will be deleted");
        }

        $cutoffDate = Carbon::now()->subDays($days);
        $deletedCount = 0;
        $totalSize = 0;

        $files = File::glob($logsPath . '/*.log*');

        foreach ($files as $file) {
            $fileTime = Carbon::createFromTimestamp(File::lastModified($file));

            if ($fileTime->lt($cutoffDate)) {
                $size = File::size($file);
                $totalSize += $size;

                $this->line("  - " . basename($file) . " (" . $this->formatBytes($size) . ")");

                if (!$dryRun) {
                    File::delete($file);
                }

                $deletedCount++;
            }
        }

        if ($deletedCount > 0) {
            $this->info("\nTotal files to delete: {$deletedCount}");
            $this->info("Total size to free: " . $this->formatBytes($totalSize));

            if (!$dryRun) {
                $this->info("✓ Log cleanup completed successfully!");
            } else {
                $this->warn("Run without --dry-run to actually delete files");
            }
        } else {
            $this->info("No log files to clean up.");
        }

        return Command::SUCCESS;
    }

    /**
     * Format bytes to human readable format
     */
    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
