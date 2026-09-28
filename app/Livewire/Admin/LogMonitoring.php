<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class LogMonitoring extends Component
{
    use WithPagination;

    public $selectedLog = 'laravel';
    public $logContent = '';
    public $searchTerm = '';
    public $selectedLevel = 'all';
    public $selectedDate = '';
    public $autoRefresh = false;
    public $refreshInterval = 5; // seconds

    protected $queryString = [
        'selectedLog',
        'searchTerm',
        'selectedLevel',
        'selectedDate'
    ];

    public function mount()
    {
        $this->loadLogContent();
    }

    public function updatedSelectedLog()
    {
        $this->loadLogContent();
    }

    public function updatedSearchTerm()
    {
        $this->loadLogContent();
    }

    public function updatedSelectedLevel()
    {
        $this->loadLogContent();
    }

    public function updatedSelectedDate()
    {
        $this->loadLogContent();
    }

    public function refresh()
    {
        $this->loadLogContent();
        $this->dispatch('log-refreshed');
    }

    public function clearLog()
    {
        $logPath = $this->getLogPath();

        if (File::exists($logPath)) {
            File::put($logPath, '');
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Log file cleared successfully!'
            ]);
            $this->loadLogContent();
        }
    }

    public function downloadLog()
    {
        $logPath = $this->getLogPath();

        if (File::exists($logPath)) {
            return response()->download($logPath);
        }
    }

    public function deleteLog()
    {
        $logPath = $this->getLogPath();

        if (File::exists($logPath)) {
            File::delete($logPath);
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Log file deleted successfully!'
            ]);
            $this->selectedLog = 'laravel';
            $this->loadLogContent();
        }
    }

    protected function loadLogContent()
    {
        $logPath = $this->getLogPath();

        if (!File::exists($logPath)) {
            $this->logContent = "Log file not found: " . basename($logPath);
            return;
        }

        // Baca maksimal 5 MB terakhir saja agar log besar tidak menghabiskan memori server
        $content = $this->bacaEkor($logPath, 5 * 1024 * 1024);

        // Filter by date if selected
        if ($this->selectedDate) {
            $lines = explode("\n", $content);
            $filteredLines = array_filter($lines, function ($line) {
                return str_contains($line, $this->selectedDate);
            });
            $content = implode("\n", $filteredLines);
        }

        // Filter by level
        if ($this->selectedLevel !== 'all') {
            $lines = explode("\n", $content);
            $filteredLines = array_filter($lines, function ($line) {
                return stripos($line, '.' . strtoupper($this->selectedLevel) . ':') !== false;
            });
            $content = implode("\n", $filteredLines);
        }

        // Filter by search term
        if ($this->searchTerm) {
            $lines = explode("\n", $content);
            $filteredLines = array_filter($lines, function ($line) {
                return stripos($line, $this->searchTerm) !== false;
            });
            $content = implode("\n", $filteredLines);
        }

        // Get last 500 lines for performance
        $lines = explode("\n", $content);
        $lines = array_slice($lines, -500);
        $this->logContent = implode("\n", $lines);
    }

    /**
     * Ambil bagian akhir file (maks $maxBytes) tanpa memuat seluruh file ke memori.
     */
    protected function bacaEkor(string $path, int $maxBytes): string
    {
        $size = File::size($path);
        if ($size <= $maxBytes) {
            return File::get($path);
        }

        $handle = fopen($path, 'rb');
        fseek($handle, -$maxBytes, SEEK_END);
        $content = (string) fread($handle, $maxBytes);
        fclose($handle);

        return substr($content, (int) strpos($content, "\n") + 1); // buang baris yang terpotong
    }

    protected function getLogPath()
    {
        $logsPath = storage_path('logs');

        // Nama log & tanggal dibatasi agar tidak bisa keluar dari folder logs (../)
        if (!preg_match('/^[A-Za-z0-9_\-]+$/', (string) $this->selectedLog)) {
            $this->selectedLog = 'laravel';
        }
        if ($this->selectedDate && !preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $this->selectedDate)) {
            $this->selectedDate = '';
        }

        $logFile = $this->selectedLog . '.log';

        // Check if specific date log exists
        if ($this->selectedDate && $this->selectedLog !== 'laravel') {
            $dateLogFile = $this->selectedLog . '-' . $this->selectedDate . '.log';
            if (File::exists($logsPath . '/' . $dateLogFile)) {
                return $logsPath . '/' . $dateLogFile;
            }
        }

        return $logsPath . '/' . $logFile;
    }

    public function getAvailableLogs()
    {
        $logsPath = storage_path('logs');
        $files = File::glob($logsPath . '/*.log');

        $logs = [];
        foreach ($files as $file) {
            $filename = basename($file);
            // Extract base log name (without date suffix)
            $baseName = preg_replace('/-\d{4}-\d{2}-\d{2}\.log$/', '', $filename);
            $baseName = str_replace('.log', '', $baseName);

            if (!in_array($baseName, $logs)) {
                $logs[] = $baseName;
            }
        }

        return $logs;
    }

    public function getLogStats()
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

        return [
            'total_files' => $totalFiles,
            'total_size' => $this->formatBytes($totalSize),
            'disk_free' => $this->formatBytes($diskFreeSpace),
            'disk_used_percentage' => round($diskUsedPercentage, 2),
        ];
    }

    public function getRecentErrors()
    {
        $logsPath = storage_path('logs');
        $logFile = $logsPath . '/laravel.log';

        if (!File::exists($logFile)) {
            return [];
        }

        $content = $this->bacaEkor($logFile, 512 * 1024);
        $lines = explode("\n", $content);
        $errors = [];

        // Get last 100 lines and filter errors
        $recentLines = array_slice($lines, -100);

        foreach ($recentLines as $line) {
            if (preg_match('/\.(ERROR|CRITICAL|EMERGENCY):/', $line)) {
                $errors[] = $line;
            }
        }

        return array_slice($errors, -10); // Return last 10 errors
    }

    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    public function render()
    {
        return view('livewire.admin.log-monitoring', [
            'availableLogs' => $this->getAvailableLogs(),
            'logStats' => $this->getLogStats(),
            'recentErrors' => $this->getRecentErrors(),
        ])->layout('components.layouts.dashboard');
    }
}
