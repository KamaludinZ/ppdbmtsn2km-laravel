<?php

/**
 * Script untuk Update Status Pendaftaran
 *
 * Script ini mengubah status kelulusan (lulus, tidak_lulus, cadangan)
 * menjadi status pendaftaran yang valid (menunggu_verifikasi)
 *
 * Tanggal: 30 Januari 2026
 *
 * CARA PENGGUNAAN:
 * php update_status_pendaftaran.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Pendaftar;
use Illuminate\Support\Facades\DB;

echo "\n";
echo "========================================\n";
echo "Update Status Pendaftaran Script\n";
echo "========================================\n";
echo "\n";

// Check current status distribution
echo "📊 Status pendaftaran saat ini:\n";
echo "----------------------------------------\n";
$statusCounts = DB::table('pendaftars')
    ->select('status_pendaftaran', DB::raw('count(*) as total'))
    ->groupBy('status_pendaftaran')
    ->get();

foreach ($statusCounts as $status) {
    echo sprintf("%-25s: %d\n", $status->status_pendaftaran, $status->total);
}
echo "\n";

// Find pendaftar with old status (kelulusan)
$oldStatuses = ['lulus', 'tidak_lulus', 'cadangan'];
$pendaftarWithOldStatus = Pendaftar::whereIn('status_pendaftaran', $oldStatuses)->get();

if ($pendaftarWithOldStatus->isEmpty()) {
    echo "✅ Tidak ada pendaftar dengan status kelulusan lama.\n";
    echo "✅ Database sudah bersih!\n";
    echo "\n";
    exit(0);
}

echo "⚠️  Ditemukan {$pendaftarWithOldStatus->count()} pendaftar dengan status kelulusan lama:\n";
echo "----------------------------------------\n";

foreach ($oldStatuses as $oldStatus) {
    $count = $pendaftarWithOldStatus->where('status_pendaftaran', $oldStatus)->count();
    if ($count > 0) {
        echo sprintf("%-25s: %d\n", $oldStatus, $count);
    }
}
echo "\n";

// Ask for confirmation
echo "🔄 Status akan diubah menjadi: 'menunggu_verifikasi'\n";
echo "\n";
echo "Lanjutkan update? (yes/no): ";
$handle = fopen("php://stdin", "r");
$line = fgets($handle);
$confirmation = trim(strtolower($line));
fclose($handle);

if ($confirmation !== 'yes') {
    echo "\n";
    echo "❌ Update dibatalkan.\n";
    echo "\n";
    exit(0);
}

echo "\n";
echo "🔄 Memulai update...\n";
echo "----------------------------------------\n";

// Update status
$updated = 0;
$errors = 0;

foreach ($pendaftarWithOldStatus as $pendaftar) {
    try {
        $oldStatus = $pendaftar->status_pendaftaran;
        $pendaftar->status_pendaftaran = 'menunggu_verifikasi';
        $pendaftar->save();

        echo sprintf("✅ ID %d: %s → menunggu_verifikasi\n", $pendaftar->id, $oldStatus);
        $updated++;
    } catch (\Exception $e) {
        echo sprintf("❌ ID %d: Error - %s\n", $pendaftar->id, $e->getMessage());
        $errors++;
    }
}

echo "\n";
echo "========================================\n";
echo "Update Summary\n";
echo "========================================\n";
echo sprintf("✅ Berhasil diupdate: %d\n", $updated);
echo sprintf("❌ Gagal diupdate: %d\n", $errors);
echo "\n";

// Show new status distribution
echo "📊 Status pendaftaran setelah update:\n";
echo "----------------------------------------\n";
$newStatusCounts = DB::table('pendaftars')
    ->select('status_pendaftaran', DB::raw('count(*) as total'))
    ->groupBy('status_pendaftaran')
    ->get();

foreach ($newStatusCounts as $status) {
    echo sprintf("%-25s: %d\n", $status->status_pendaftaran, $status->total);
}
echo "\n";

echo "✅ Update selesai!\n";
echo "\n";
