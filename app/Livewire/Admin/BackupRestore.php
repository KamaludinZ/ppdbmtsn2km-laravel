<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use ZipArchive;

class BackupRestore extends Component
{
    public $backups = [];
    public $selectedBackup = null;
    public $backupType = 'full'; // 'full', 'database', 'files'
    public $restoreType = 'full';
    public $isRestoring = false;
    
    public function mount()
    {
        $this->loadBackups();
    }

    public function render()
    {
        return view('livewire.admin.backup-restore', [
            'backups' => $this->backups,
        ])->layout('components.layouts.dashboard', [
            'role' => 'admin',
            'pageTitle' => 'Backup & Restore',
            'pageDescription' => 'Kelola backup dan restore data aplikasi'
        ]);
    }

    public function createBackup()
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "backup_{$timestamp}.zip";
        $backupPath = storage_path("app/backups/{$filename}");

        // Create backups directory if it doesn't exist
        if (!Storage::exists('backups')) {
            Storage::makeDirectory('backups');
        }

        $zip = new ZipArchive();
        if ($zip->open($backupPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            // Add database dump
            if ($this->backupType === 'full' || $this->backupType === 'database') {
                $dbDumpPath = storage_path("app/backups/db_dump_{$timestamp}.sql");

                try {
                    // Get database configuration
                    $connection = config('database.default');
                    $driver = config("database.connections.{$connection}.driver");
                    $database = config("database.connections.{$connection}.database");
                    $username = config("database.connections.{$connection}.username");
                    $password = config("database.connections.{$connection}.password");
                    $host = config("database.connections.{$connection}.host");
                    $port = config("database.connections.{$connection}.port");

                    // Create database dump based on driver
                    if ($driver === 'pgsql') {
                        // PostgreSQL dump
                        putenv("PGPASSWORD={$password}");
                        $command = "pg_dump -h {$host} -p {$port} -U {$username} -F p {$database}";
                        $result = shell_exec($command . ' > ' . $dbDumpPath . ' 2>&1');
                        putenv("PGPASSWORD");
                    } elseif ($driver === 'mysql') {
                        // MySQL dump
                        $command = "mysqldump --host={$host} --user={$username} --password={$password} {$database}";
                        $result = shell_exec($command . ' > ' . $dbDumpPath . ' 2>&1');
                    }

                    if (file_exists($dbDumpPath) && filesize($dbDumpPath) > 0) {
                        $zip->addFile($dbDumpPath, 'database.sql');
                    } else {
                        session()->flash('error', 'Gagal membuat database dump. Pastikan pg_dump/mysqldump terinstall.');
                        $zip->close();
                        @unlink($backupPath);
                        return;
                    }
                } catch (\Exception $e) {
                    session()->flash('error', 'Error creating database dump: ' . $e->getMessage());
                    $zip->close();
                    @unlink($backupPath);
                    return;
                }
            }

            // Add uploaded files
            if ($this->backupType === 'full' || $this->backupType === 'files') {
                if (is_dir(public_path('uploads'))) {
                    $this->addFilesToZip($zip, public_path('uploads'), 'uploads');
                }
                if (is_dir(storage_path('app/public'))) {
                    $this->addFilesToZip($zip, storage_path('app/public'), 'storage/public');
                }
            }

            $zip->close();

            // Clean up temporary database dump
            if (isset($dbDumpPath) && file_exists($dbDumpPath)) {
                @unlink($dbDumpPath);
            }

            $this->loadBackups();
            session()->flash('message', 'Backup berhasil dibuat.');
        } else {
            session()->flash('error', 'Gagal membuat backup.');
        }
    }

    private function addFilesToZip($zip, $dir, $zipDir)
    {
        $files = File::allFiles($dir);
        foreach ($files as $file) {
            $localPath = $file->getRelativePathname();
            $zip->addFile($file->getPathname(), $zipDir . '/' . $localPath);
        }
    }

    public function downloadBackup($filename)
    {
        $path = storage_path("app/backups/{$filename}");
        
        if (file_exists($path)) {
            return response()->download($path);
        }
        
        session()->flash('error', 'File backup tidak ditemukan.');
    }

    public function deleteBackup($filename)
    {
        $path = storage_path("app/backups/{$filename}");
        
        if (file_exists($path)) {
            unlink($path);
            $this->loadBackups();
            session()->flash('message', 'Backup berhasil dihapus.');
        } else {
            session()->flash('error', 'File backup tidak ditemukan.');
        }
    }

    public function restoreBackup()
    {
        if (!$this->selectedBackup) {
            session()->flash('error', 'Silakan pilih file backup terlebih dahulu.');
            return;
        }
        
        $this->isRestoring = true;
        
        $backupPath = storage_path("app/backups/{$this->selectedBackup}");
        
        if (!file_exists($backupPath)) {
            session()->flash('error', 'File backup tidak ditemukan.');
            $this->isRestoring = false;
            return;
        }
        
        $zip = new ZipArchive();
        if ($zip->open($backupPath) === TRUE) {
            // Extract to temporary directory
            $tempDir = storage_path('app/temp_restore');
            if (!File::isDirectory($tempDir)) {
                File::makeDirectory($tempDir, 0755, true);
            }
            
            $zip->extractTo($tempDir);
            $zip->close();
            
            try {
                // Restore database if needed
                if ($this->restoreType === 'full' || $this->restoreType === 'database') {
                    $dbDumpPath = $tempDir . '/database.sql';
                    if (file_exists($dbDumpPath)) {
                        $connection = config('database.default');
                        $driver = config("database.connections.{$connection}.driver");
                        $database = config("database.connections.{$connection}.database");
                        $username = config("database.connections.{$connection}.username");
                        $password = config("database.connections.{$connection}.password");
                        $host = config("database.connections.{$connection}.host");
                        $port = config("database.connections.{$connection}.port");

                        if ($driver === 'pgsql') {
                            // PostgreSQL restore
                            putenv("PGPASSWORD={$password}");
                            $command = "psql -h {$host} -p {$port} -U {$username} -d {$database} < {$dbDumpPath}";
                            shell_exec($command . ' 2>&1');
                            putenv("PGPASSWORD");
                        } elseif ($driver === 'mysql') {
                            // MySQL restore
                            $command = "mysql --host={$host} --user={$username} --password={$password} {$database} < {$dbDumpPath}";
                            shell_exec($command . ' 2>&1');
                        }
                    }
                }
                
                // Restore files if needed
                if ($this->restoreType === 'full' || $this->restoreType === 'files') {
                    $sourceDir = $tempDir . '/uploads';
                    $destDir = public_path('uploads');
                    
                    if (is_dir($sourceDir)) {
                        // Remove existing uploads directory
                        if (is_dir($destDir)) {
                            File::deleteDirectory($destDir);
                        }
                        
                        // Copy restored files
                        File::copyDirectory($sourceDir, $destDir);
                    }
                }
                
                // Clean up temporary directory
                File::deleteDirectory($tempDir);
                
                session()->flash('message', 'Restore berhasil dilakukan.');
            } catch (\Exception $e) {
                session()->flash('error', 'Gagal melakukan restore: ' . $e->getMessage());
            }
            
            $this->isRestoring = false;
        } else {
            session()->flash('error', 'Gagal membuka file backup.');
            $this->isRestoring = false;
        }
    }

    private function loadBackups()
    {
        $this->backups = [];
        
        if (Storage::exists('backups')) {
            $files = Storage::files('backups');
            
            foreach ($files as $file) {
                $filename = basename($file);
                $path = storage_path("app/{$file}");
                
                $this->backups[] = [
                    'filename' => $filename,
                    'size' => $this->formatBytes(filesize($path)),
                    'created_at' => date('d M Y H:i:s', filemtime($path)),
                    'path' => $path
                ];
            }
            
            // Sort by creation date (newest first)
            usort($this->backups, function ($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });
        }
    }

    private function formatBytes($size, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }

        return round($size, $precision) . ' ' . $units[$i];
    }

    public function refreshBackups()
    {
        $this->loadBackups();
        session()->flash('message', 'Daftar backup berhasil diperbarui.');
    }
}