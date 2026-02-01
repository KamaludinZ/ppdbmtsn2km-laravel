<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Monitoring extends Component
{
    public $serverInfo = [];
    public $databaseInfo = [];
    public $applicationInfo = [];
    public $diskUsage = [];

    // Log monitoring properties
    public $logStats = [];
    public $recentErrors = [];
    public $selectedLogType = 'laravel';
    public $logContent = '';
    public $showLogViewer = false;

    public function mount()
    {
        $this->loadServerInfo();
        $this->loadDatabaseInfo();
        $this->loadApplicationInfo();
        $this->loadDiskUsage();
        $this->loadLogStats();
        $this->loadRecentErrors();
    }

    public function render()
    {
        return view('livewire.admin.monitoring', [
            'serverInfo' => $this->serverInfo,
            'databaseInfo' => $this->databaseInfo,
            'applicationInfo' => $this->applicationInfo,
            'diskUsage' => $this->diskUsage,
            'logStats' => $this->logStats,
            'recentErrors' => $this->recentErrors,
            'availableLogs' => $this->getAvailableLogs(),
        ])->layout('components.layouts.dashboard', [
            'role' => 'admin',
            'pageTitle' => 'Monitoring Sistem',
            'pageDescription' => 'Monitoring server, database, aplikasi, dan logs'
        ]);
    }

    private function loadServerInfo()
    {
        $this->serverInfo = [
            'os' => php_uname('s'),
            'version' => php_uname('v'),
            'architecture' => php_uname('m'),
            'hostname' => gethostname(),
            'ip_address' => $_SERVER['SERVER_ADDR'] ?? 'localhost',
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'php_version' => PHP_VERSION,
            'memory_usage' => round(memory_get_usage(true) / 1024 / 1024, 2) . ' MB',
            'max_execution_time' => ini_get('max_execution_time'),
            'max_input_vars' => ini_get('max_input_vars'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
        ];
    }

    private function loadDatabaseInfo()
    {
        try {
            $connection = DB::connection();
            $driverName = $connection->getDriverName();

            // Get table count based on database driver
            $tablesCount = 0;
            $connectionsActive = [];

            if ($driverName === 'pgsql') {
                // PostgreSQL queries
                $tables = DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
                $tablesCount = count($tables);
                $connectionsActive = DB::select("SELECT COUNT(*) as count FROM pg_stat_activity");
            } elseif ($driverName === 'mysql') {
                // MySQL queries
                $tablesCount = count(DB::select('SHOW TABLES'));
                $connectionsActive = DB::select('SHOW PROCESSLIST');
            }

            $this->databaseInfo = [
                'connection' => $driverName,
                'database' => $connection->getDatabaseName(),
                'host' => $connection->getConfig('host'),
                'port' => $connection->getConfig('port'),
                'tables_count' => $tablesCount,
                'connections_active' => is_array($connectionsActive) && isset($connectionsActive[0]) ?
                    (isset($connectionsActive[0]->count) ? $connectionsActive[0]->count : count($connectionsActive)) : 0,
            ];
        } catch (\Exception $e) {
            $this->databaseInfo = [
                'error' => 'Could not connect to database: ' . $e->getMessage()
            ];
        }
    }

    private function loadApplicationInfo()
    {
        $this->applicationInfo = [
            'app_name' => config('app.name'),
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug'),
            'app_url' => config('app.url'),
            'laravel_version' => app()->version(),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
            'fallback_locale' => config('app.fallback_locale'),
            'cipher' => config('app.cipher'),
            'key_present' => !empty(config('app.key')),
        ];
    }

    private function loadDiskUsage()
    {
        $totalSpace = disk_total_space(base_path());
        $freeSpace = disk_free_space(base_path());
        $usedSpace = $totalSpace - $freeSpace;
        
        $this->diskUsage = [
            'total' => $this->formatBytes($totalSpace),
            'used' => $this->formatBytes($usedSpace),
            'free' => $this->formatBytes($freeSpace),
            'percent_used' => round(($usedSpace / $totalSpace) * 100, 2),
        ];
    }

    private function formatBytes($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    public function refreshData()
    {
        $this->loadServerInfo();
        $this->loadDatabaseInfo();
        $this->loadApplicationInfo();
        $this->loadDiskUsage();
        $this->loadLogStats();
        $this->loadRecentErrors();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Data monitoring berhasil diperbarui.'
        ]);
    }

    // Log Monitoring Methods

    private function loadLogStats()
    {
        $logsPath = storage_path('logs');
        $files = File::glob($logsPath . '/*.log*');

        $totalSize = 0;
        $totalFiles = count($files);

        foreach ($files as $file) {
            $totalSize += File::size($file);
        }

        $diskFreeSpace = disk_free_space(storage_path());
        $diskTotalSpace = disk_total_space(storage_path());
        $diskUsedPercentage = (($diskTotalSpace - $diskFreeSpace) / $diskTotalSpace) * 100;

        $this->logStats = [
            'total_files' => $totalFiles,
            'total_size' => $this->formatBytes($totalSize),
            'total_size_bytes' => $totalSize,
            'disk_free' => $this->formatBytes($diskFreeSpace),
            'disk_used_percentage' => round($diskUsedPercentage, 2),
            'oldest_log' => $this->getOldestLogDate($files),
            'newest_log' => $this->getNewestLogDate($files),
        ];
    }

    private function loadRecentErrors()
    {
        $logsPath = storage_path('logs');
        $logFile = $logsPath . '/laravel.log';

        if (!File::exists($logFile)) {
            $this->recentErrors = [];
            return;
        }

        $content = File::get($logFile);
        $lines = explode("\n", $content);
        $errors = [];

        // Get last 200 lines and filter errors/critical
        $recentLines = array_slice($lines, -200);

        foreach ($recentLines as $line) {
            if (preg_match('/\.(ERROR|CRITICAL|EMERGENCY):/i', $line)) {
                $errors[] = [
                    'line' => Str::limit($line, 150),
                    'full' => $line,
                    'time' => $this->extractTimestamp($line),
                ];
            }
        }

        // Get last 5 errors
        $this->recentErrors = array_slice($errors, -5);
    }

    private function extractTimestamp($line)
    {
        if (preg_match('/\[(.*?)\]/', $line, $matches)) {
            try {
                return Carbon::parse($matches[1])->diffForHumans();
            } catch (\Exception $e) {
                return 'Unknown';
            }
        }
        return 'Unknown';
    }

    private function getOldestLogDate($files)
    {
        if (empty($files)) return 'N/A';

        $oldest = null;
        foreach ($files as $file) {
            $time = File::lastModified($file);
            if ($oldest === null || $time < $oldest) {
                $oldest = $time;
            }
        }

        return $oldest ? Carbon::createFromTimestamp($oldest)->format('Y-m-d') : 'N/A';
    }

    private function getNewestLogDate($files)
    {
        if (empty($files)) return 'N/A';

        $newest = null;
        foreach ($files as $file) {
            $time = File::lastModified($file);
            if ($newest === null || $time > $newest) {
                $newest = $time;
            }
        }

        return $newest ? Carbon::createFromTimestamp($newest)->format('Y-m-d') : 'N/A';
    }

    public function getAvailableLogs()
    {
        $logsPath = storage_path('logs');
        $files = File::glob($logsPath . '/*.log');

        $logs = [];
        foreach ($files as $file) {
            $filename = basename($file);
            $baseName = str_replace('.log', '', $filename);

            if (!in_array($baseName, $logs)) {
                $logs[] = $baseName;
            }
        }

        return $logs;
    }

    public function toggleLogViewer()
    {
        $this->showLogViewer = !$this->showLogViewer;

        if ($this->showLogViewer) {
            $this->loadLogContent();
        }
    }

    public function updatedSelectedLogType()
    {
        $this->loadLogContent();
    }

    public function loadLogContent()
    {
        $logPath = storage_path('logs/' . $this->selectedLogType . '.log');

        if (!File::exists($logPath)) {
            $this->logContent = "Log file not found: " . basename($logPath);
            return;
        }

        $content = File::get($logPath);

        // Get last 100 lines for performance
        $lines = explode("\n", $content);
        $lines = array_slice($lines, -100);
        $this->logContent = implode("\n", $lines);
    }

    public function viewFullLogs()
    {
        return redirect()->route('admin.logs');
    }

    public function runLogCleanup()
    {
        try {
            $output = Artisan::call('log:cleanup', ['--days' => 30]);
            $this->loadLogStats();
            $this->loadRecentErrors();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Log cleanup berhasil dijalankan! Log yang lebih dari 30 hari telah dihapus.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Gagal menjalankan log cleanup: ' . $e->getMessage()
            ]);
        }
    }
}