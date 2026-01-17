<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class Monitoring extends Component
{
    public $serverInfo = [];
    public $databaseInfo = [];
    public $applicationInfo = [];
    public $diskUsage = [];

    public function mount()
    {
        $this->loadServerInfo();
        $this->loadDatabaseInfo();
        $this->loadApplicationInfo();
        $this->loadDiskUsage();
    }

    public function render()
    {
        return view('livewire.admin.monitoring', [
            'serverInfo' => $this->serverInfo,
            'databaseInfo' => $this->databaseInfo,
            'applicationInfo' => $this->applicationInfo,
            'diskUsage' => $this->diskUsage,
        ])->layout('components.layouts.dashboard', [
            'role' => 'admin',
            'pageTitle' => 'Monitoring Sistem',
            'pageDescription' => 'Monitoring server, database, dan aplikasi'
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
        
        session()->flash('message', 'Data monitoring berhasil diperbarui.');
    }
}