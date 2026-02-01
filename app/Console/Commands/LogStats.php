<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

/**
 * Command untuk melihat statistik log files
 * Run: php artisan log:stats
 */
class LogStats extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'log:stats';

    /**
     * The console command description.
     */
    protected $description = 'Display log files statistics';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $logsPath = storage_path('logs');
        $files = File::glob($logsPath . '/*.log*');

        if (empty($files)) {
            $this->warn('No log files found.');
            return Command::SUCCESS;
        }

        $stats = [];
        $totalSize = 0;

        foreach ($files as $file) {
            $size = File::size($file);
            $totalSize += $size;
            $modified = Carbon::createFromTimestamp(File::lastModified($file));

            $stats[] = [
                'File' => basename($file),
                'Size' => $this->formatBytes($size),
                'Modified' => $modified->format('Y-m-d H:i:s'),
                'Age' => $modified->diffForHumans(),
            ];
        }

        // Sort by size (descending)
        usort($stats, function ($a, $b) {
            return $this->parseSize($b['Size']) <=> $this->parseSize($a['Size']);
        });

        $this->info('Log Files Statistics:');
        $this->table(
            ['File', 'Size', 'Modified', 'Age'],
            $stats
        );

        $this->newLine();
        $this->info("Total Files: " . count($files));
        $this->info("Total Size: " . $this->formatBytes($totalSize));

        // Disk space warning
        $diskFreeSpace = disk_free_space(storage_path());
        $diskTotalSpace = disk_total_space(storage_path());
        $diskUsedPercentage = (($diskTotalSpace - $diskFreeSpace) / $diskTotalSpace) * 100;

        $this->newLine();
        $this->info("Disk Space:");
        $this->line("  Free: " . $this->formatBytes($diskFreeSpace));
        $this->line("  Used: " . number_format($diskUsedPercentage, 2) . "%");

        if ($diskUsedPercentage > 90) {
            $this->error("⚠️ Warning: Disk space is running low!");
            $this->warn("Consider running: php artisan log:cleanup");
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

    /**
     * Parse size string back to bytes for sorting
     */
    protected function parseSize(string $size): float
    {
        $units = ['B' => 1, 'KB' => 1024, 'MB' => 1048576, 'GB' => 1073741824];
        preg_match('/^([\d.]+)\s*(\w+)$/', $size, $matches);

        if (isset($matches[1], $matches[2], $units[$matches[2]])) {
            return (float) $matches[1] * $units[$matches[2]];
        }

        return 0;
    }
}
