<?php
/**
 * Script untuk membersihkan data testing duplicate
 * Upload ke root project di production, lalu jalankan: php cleanup_test_users.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Pendaftar;

echo "=== CLEANUP TEST USERS ===" . PHP_EOL . PHP_EOL;

// Email-email yang akan dibersihkan (ganti dengan email test Anda)
$testEmails = [
    'kamalzuhri01@gmail.com',
    'kamaludinszuhri01@gmail.com',
];

echo "Mencari user dengan email test..." . PHP_EOL;

foreach ($testEmails as $email) {
    $users = User::where('email', $email)->get();

    if ($users->count() === 0) {
        echo "✓ Email '{$email}' tidak ditemukan (sudah bersih)" . PHP_EOL;
        continue;
    }

    echo PHP_EOL . "Found {$users->count()} user(s) dengan email: {$email}" . PHP_EOL;

    foreach ($users as $user) {
        echo "  - User ID: {$user->id}, Name: {$user->name}, Role: {$user->role}, Created: {$user->created_at}" . PHP_EOL;

        // Hapus data pendaftar jika ada
        if ($user->pendaftar) {
            echo "    → Menghapus pendaftar: {$user->pendaftar->nama_lengkap} (NISN: {$user->pendaftar->nisn})" . PHP_EOL;

            // Hapus file rapor jika ada
            if ($user->pendaftar->file_rapor) {
                $filePath = storage_path('app/private/' . $user->pendaftar->file_rapor);
                if (file_exists($filePath)) {
                    unlink($filePath);
                    echo "    → File rapor dihapus: {$user->pendaftar->file_rapor}" . PHP_EOL;
                }
            }

            $user->pendaftar->delete();
        }

        // Hapus user
        echo "    → Menghapus user: {$user->email}" . PHP_EOL;
        $user->delete();
        echo "    ✓ User berhasil dihapus" . PHP_EOL;
    }
}

echo PHP_EOL . "=== CLEANUP SELESAI ===" . PHP_EOL;
echo "Silakan coba pendaftaran lagi dengan email yang bersih." . PHP_EOL;
