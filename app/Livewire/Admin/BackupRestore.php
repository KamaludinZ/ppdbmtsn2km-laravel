<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use ZipArchive;

class BackupRestore extends Component
{
    use WithFileUploads;

    public $backups = [];
    public $selectedBackup = null;
    public $backupType = 'full'; // 'full', 'database', 'files'
    public $restoreType = 'full';
    public $showDeleteConfirm = false;
    public $showRestoreConfirm = false;
    public $backupToDelete = null;

    // Upload backup file properties
    public $uploadedBackupFile = null;
    public $restoreSource = 'server'; // 'server' or 'upload'
    
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
        try {
            $timestamp = now()->format('Y-m-d_H-i-s');
            $filename = "backup_{$this->backupType}_{$timestamp}.zip";

            // Create backups directory if it doesn't exist
            $backupDir = storage_path('app/backups');
            if (!File::isDirectory($backupDir)) {
                File::makeDirectory($backupDir, 0755, true);
            }

            $backupPath = $backupDir . '/' . $filename;

            // Check if directory is writable
            if (!is_writable($backupDir)) {
                throw new \Exception('Directory backup tidak dapat ditulis. Periksa permission folder storage/app/backups');
            }

            $zip = new ZipArchive();
            $zipResult = $zip->open($backupPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            if ($zipResult === TRUE) {
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
                            throw new \Exception('Gagal membuat database dump. Pastikan pg_dump/mysqldump terinstall atau gunakan backup file saja.');
                        }
                    } catch (\Exception $e) {
                        $zip->close();
                        @unlink($backupPath);
                        throw new \Exception('Error creating database dump: ' . $e->getMessage());
                    }
                }

                // Add uploaded files
                if ($this->backupType === 'full' || $this->backupType === 'files') {
                    // Backup public uploads folder
                    if (is_dir(public_path('uploads'))) {
                        $this->addFilesToZip($zip, public_path('uploads'), 'uploads');
                    }

                    // Backup public storage (photos, etc)
                    if (is_dir(storage_path('app/public'))) {
                        $this->addFilesToZip($zip, storage_path('app/public'), 'storage/public');
                    }

                    // Backup private storage (dokumen sensitif, bukti bayar, etc)
                    if (is_dir(storage_path('app/private'))) {
                        $this->addFilesToZip($zip, storage_path('app/private'), 'storage/private');
                    }
                }

                $zip->close();

                // Clean up temporary database dump
                if (isset($dbDumpPath) && file_exists($dbDumpPath)) {
                    @unlink($dbDumpPath);
                }

                $this->loadBackups();
                session()->flash('message', 'Backup berhasil dibuat!');
            } else {
                // Provide detailed error message based on ZipArchive error code
                $errorMessages = [
                    ZipArchive::ER_EXISTS => 'File sudah ada',
                    ZipArchive::ER_INCONS => 'Zip archive tidak konsisten',
                    ZipArchive::ER_INVAL => 'Argumen tidak valid',
                    ZipArchive::ER_MEMORY => 'Malloc failure',
                    ZipArchive::ER_NOENT => 'File tidak ditemukan',
                    ZipArchive::ER_NOZIP => 'Bukan zip archive',
                    ZipArchive::ER_OPEN => 'Tidak dapat membuka file',
                    ZipArchive::ER_READ => 'Read error',
                    ZipArchive::ER_SEEK => 'Seek error',
                ];

                $errorMsg = $errorMessages[$zipResult] ?? 'Unknown error code: ' . $zipResult;
                throw new \Exception('Gagal membuat file backup ZIP: ' . $errorMsg . '. Pastikan folder storage/app/backups dapat ditulis dan PHP zip extension terinstall.');
            }
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
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

    public function confirmDelete($filename)
    {
        $this->backupToDelete = $filename;
        $this->showDeleteConfirm = true;
    }

    public function cancelDelete()
    {
        $this->backupToDelete = null;
        $this->showDeleteConfirm = false;
    }

    public function deleteBackup()
    {
        if (!$this->backupToDelete) {
            return;
        }

        // Security: Validate filename to prevent path traversal
        if (!$this->isValidBackupFilename($this->backupToDelete)) {
            session()->flash('error', 'Nama file backup tidak valid.');
            $this->backupToDelete = null;
            $this->showDeleteConfirm = false;
            return;
        }

        $path = storage_path("app/backups/{$this->backupToDelete}");

        // Security: Ensure the resolved path is still within backups directory
        $backupDir = realpath(storage_path('app/backups'));
        $filePath = realpath($path);

        if ($filePath === false || strpos($filePath, $backupDir) !== 0) {
            session()->flash('error', 'Akses file ditolak untuk alasan keamanan.');
            $this->backupToDelete = null;
            $this->showDeleteConfirm = false;
            return;
        }

        if (file_exists($path)) {
            unlink($path);
            $this->loadBackups();
            session()->flash('message', 'Backup berhasil dihapus.');
        } else {
            session()->flash('error', 'File backup tidak ditemukan.');
        }

        $this->backupToDelete = null;
        $this->showDeleteConfirm = false;
    }

    public function downloadBackup($filename)
    {
        // Security: Validate filename to prevent path traversal
        if (!$this->isValidBackupFilename($filename)) {
            session()->flash('error', 'Nama file backup tidak valid.');
            return;
        }

        // Redirect to download route instead of returning response
        // This is more compatible with Livewire
        return redirect()->route('admin.backup.download', ['filename' => $filename]);
    }

    public function confirmRestore()
    {
        // Validate based on restore source
        if ($this->restoreSource === 'server') {
            // Validate server backup selection
            if (!$this->selectedBackup) {
                session()->flash('error', 'Silakan pilih file backup terlebih dahulu.');
                return;
            }

            // Validate filename to prevent path traversal
            if (!$this->isValidBackupFilename($this->selectedBackup)) {
                session()->flash('error', 'Nama file backup tidak valid.');
                return;
            }
        } else {
            // Validate uploaded file
            if (!$this->uploadedBackupFile) {
                session()->flash('error', 'Silakan upload file backup terlebih dahulu.');
                return;
            }

            // Validate uploaded file extension
            $extension = $this->uploadedBackupFile->getClientOriginalExtension();
            if ($extension !== 'zip') {
                session()->flash('error', 'File harus berformat .zip');
                return;
            }

            // Validate file size (max 500MB)
            $maxSize = 500 * 1024 * 1024; // 500MB in bytes
            if ($this->uploadedBackupFile->getSize() > $maxSize) {
                session()->flash('error', 'Ukuran file maksimal 500MB.');
                return;
            }

            // Validate filename pattern (should start with backup_)
            $originalName = $this->uploadedBackupFile->getClientOriginalName();
            if (strpos($originalName, 'backup_') !== 0) {
                session()->flash('error', 'File backup harus dimulai dengan "backup_"');
                return;
            }
        }

        $this->showRestoreConfirm = true;
    }

    public function cancelRestore()
    {
        $this->showRestoreConfirm = false;
    }

    public function restoreBackup()
    {
        $this->showRestoreConfirm = false;

        // Determine backup path based on source
        $backupPath = null;
        $isTemporaryFile = false;

        if ($this->restoreSource === 'server') {
            // Restore from server backup
            if (!$this->selectedBackup) {
                session()->flash('error', 'Silakan pilih file backup terlebih dahulu.');
                return;
            }

            // Security: Validate filename to prevent path traversal
            if (!$this->isValidBackupFilename($this->selectedBackup)) {
                session()->flash('error', 'Nama file backup tidak valid.');
                return;
            }

            $backupPath = storage_path("app/backups/{$this->selectedBackup}");

            // Security: Ensure the resolved path is still within backups directory
            $backupDir = realpath(storage_path('app/backups'));
            $filePath = realpath($backupPath);

            if ($filePath === false || strpos($filePath, $backupDir) !== 0) {
                session()->flash('error', 'Akses file ditolak untuk alasan keamanan.');
                return;
            }

            if (!file_exists($backupPath)) {
                session()->flash('error', 'File backup tidak ditemukan.');
                return;
            }
        } else {
            // Restore from uploaded file
            if (!$this->uploadedBackupFile) {
                session()->flash('error', 'Silakan upload file backup terlebih dahulu.');
                return;
            }

            try {
                // Save uploaded file to temporary location
                $tempDir = storage_path('app/temp_upload');
                if (!File::isDirectory($tempDir)) {
                    File::makeDirectory($tempDir, 0755, true);
                }

                $tempFilename = 'uploaded_' . time() . '.zip';
                $backupPath = $tempDir . '/' . $tempFilename;

                // Move uploaded file to temporary location
                $this->uploadedBackupFile->storeAs('temp_upload', $tempFilename);
                $backupPath = storage_path("app/temp_upload/{$tempFilename}");

                $isTemporaryFile = true;
            } catch (\Exception $e) {
                session()->flash('error', 'Gagal menyimpan file upload: ' . $e->getMessage());
                return;
            }
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
                            $result = shell_exec($command . ' 2>&1');
                            putenv("PGPASSWORD");
                        } elseif ($driver === 'mysql') {
                            // MySQL restore
                            $command = "mysql --host={$host} --user={$username} --password={$password} {$database} < {$dbDumpPath}";
                            $result = shell_exec($command . ' 2>&1');
                        }
                    }
                }

                // Restore files if needed
                if ($this->restoreType === 'full' || $this->restoreType === 'files') {
                    // Restore public uploads
                    $sourceUploadsDir = $tempDir . '/uploads';
                    $destUploadsDir = public_path('uploads');

                    if (is_dir($sourceUploadsDir)) {
                        if (is_dir($destUploadsDir)) {
                            File::deleteDirectory($destUploadsDir);
                        }
                        File::copyDirectory($sourceUploadsDir, $destUploadsDir);
                    }

                    // Restore public storage
                    $sourcePublicDir = $tempDir . '/storage/public';
                    $destPublicDir = storage_path('app/public');

                    if (is_dir($sourcePublicDir)) {
                        if (is_dir($destPublicDir)) {
                            File::deleteDirectory($destPublicDir);
                        }
                        File::copyDirectory($sourcePublicDir, $destPublicDir);
                    }

                    // Restore private storage
                    $sourcePrivateDir = $tempDir . '/storage/private';
                    $destPrivateDir = storage_path('app/private');

                    if (is_dir($sourcePrivateDir)) {
                        if (is_dir($destPrivateDir)) {
                            File::deleteDirectory($destPrivateDir);
                        }
                        File::copyDirectory($sourcePrivateDir, $destPrivateDir);
                    }
                }

                // Clean up temporary directory
                File::deleteDirectory($tempDir);

                // Clean up temporary uploaded file if exists
                if ($isTemporaryFile && file_exists($backupPath)) {
                    @unlink($backupPath);
                    // Also clean up the temp_upload directory if empty
                    $tempUploadDir = storage_path('app/temp_upload');
                    if (is_dir($tempUploadDir) && count(scandir($tempUploadDir)) === 2) {
                        @rmdir($tempUploadDir);
                    }
                }

                // Reset uploaded file property
                $this->uploadedBackupFile = null;

                session()->flash('message', 'Restore berhasil dilakukan! Silakan refresh halaman.');
            } catch (\Exception $e) {
                // Clean up temporary uploaded file on error
                if ($isTemporaryFile && file_exists($backupPath)) {
                    @unlink($backupPath);
                }
                session()->flash('error', 'Gagal melakukan restore: ' . $e->getMessage());
            }
        } else {
            // Clean up temporary uploaded file if ZIP failed to open
            if ($isTemporaryFile && file_exists($backupPath)) {
                @unlink($backupPath);
            }
            session()->flash('error', 'Gagal membuka file backup.');
        }
    }

    private function loadBackups()
    {
        $this->backups = [];

        $backupDir = storage_path('app/backups');

        if (File::isDirectory($backupDir)) {
            $files = File::files($backupDir);

            foreach ($files as $file) {
                $filename = $file->getFilename();

                // Only include .zip files
                if (pathinfo($filename, PATHINFO_EXTENSION) === 'zip') {
                    $this->backups[] = [
                        'filename' => $filename,
                        'size' => $this->formatBytes($file->getSize()),
                        'created_at' => date('d M Y H:i:s', $file->getMTime()),
                        'path' => $file->getPathname()
                    ];
                }
            }

            // Sort by creation date (newest first)
            usort($this->backups, function ($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });
        }
    }

    /**
     * Validate backup filename to prevent path traversal attacks
     *
     * @param string $filename
     * @return bool
     */
    private function isValidBackupFilename($filename)
    {
        // Check for null or empty
        if (empty($filename)) {
            return false;
        }

        // Check for path traversal patterns
        if (strpos($filename, '..') !== false) {
            return false;
        }

        // Check for directory separators
        if (strpos($filename, '/') !== false || strpos($filename, '\\') !== false) {
            return false;
        }

        // Check for null bytes
        if (strpos($filename, "\0") !== false) {
            return false;
        }

        // Only allow alphanumeric, dash, underscore, dot
        if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $filename)) {
            return false;
        }

        // Must be a .zip file
        if (pathinfo($filename, PATHINFO_EXTENSION) !== 'zip') {
            return false;
        }

        // Must start with "backup_"
        if (strpos($filename, 'backup_') !== 0) {
            return false;
        }

        return true;
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