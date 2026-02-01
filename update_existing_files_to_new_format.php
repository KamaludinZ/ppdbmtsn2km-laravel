<?php
/**
 * Update Existing Files to New Naming Format
 *
 * PERINGATAN: Script ini akan:
 * 1. Rename file fisik di storage
 * 2. Update database dengan nama file baru
 *
 * Backup database dan storage sebelum menjalankan!
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Pendaftar;
use App\Models\Dokumen;
use App\Services\FileNamingService;
use Illuminate\Support\Facades\Storage;

echo "===========================================\n";
echo "UPDATE FILES TO NEW NAMING FORMAT\n";
echo "===========================================\n\n";

echo "⚠️  PERINGATAN: Script ini akan rename file fisik dan update database!\n";
echo "Pastikan Anda sudah backup database dan storage folder.\n\n";

echo "Tekan ENTER untuk melanjutkan, atau CTRL+C untuk batalkan...\n";
// $handle = fopen("php://stdin", "r");
// $line = fgets($handle);
// fclose($handle);

echo "\nMemulai update...\n\n";

$updated = 0;
$skipped = 0;
$errors = 0;

// ===================================
// 1. Update Foto Pendaftar
// ===================================
echo "1. Updating Foto Pendaftar...\n";
$pendaftars = Pendaftar::whereNotNull('foto')->get();
foreach ($pendaftars as $pendaftar) {
    $oldPath = $pendaftar->foto;

    if (empty($oldPath) || !Storage::disk('public')->exists($oldPath)) {
        $skipped++;
        continue;
    }

    // Get extension
    $ext = pathinfo($oldPath, PATHINFO_EXTENSION);

    // Generate new filename
    $newFilename = FileNamingService::generateImageFileName(
        'foto_profil',
        $pendaftar->nomor_pendaftaran,
        $ext,
        $pendaftar->nama_lengkap
    );

    $newPath = 'photos/' . $newFilename;

    // Skip if already in new format (check if nama pendaftar is in path)
    $namaClean = str_replace([' ', '-'], ['_', ''], $pendaftar->nama_lengkap);
    if (strpos($oldPath, substr($namaClean, 0, 15)) !== false) {
        echo "  ⏭️  SKIP: {$pendaftar->nomor_pendaftaran} - already new format (contains nama)\n";
        $skipped++;
        continue;
    }

    try {
        // Rename file
        if (Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->move($oldPath, $newPath);

            // Update database
            $pendaftar->foto = $newPath;
            $pendaftar->save();

            echo "  ✅ {$pendaftar->nomor_pendaftaran}: {$oldPath} → {$newPath}\n";
            $updated++;
        }
    } catch (\Exception $e) {
        echo "  ❌ ERROR {$pendaftar->nomor_pendaftaran}: {$e->getMessage()}\n";
        $errors++;
    }
}

// ===================================
// 2. Update Dokumen Persyaratan
// ===================================
echo "\n2. Updating Dokumen Persyaratan...\n";
$dokumens = Dokumen::with('pendaftar')->get();
foreach ($dokumens as $dokumen) {
    $oldPath = $dokumen->path_file;

    if (empty($oldPath) || !Storage::disk('private')->exists($oldPath)) {
        $skipped++;
        continue;
    }

    $pendaftar = $dokumen->pendaftar;
    if (!$pendaftar) {
        $skipped++;
        continue;
    }

    // Get extension
    $ext = pathinfo($oldPath, PATHINFO_EXTENSION);

    // Generate new filename
    $jenisClean = FileNamingService::cleanJenisDokumen($dokumen->jenis_dokumen);
    $newFilename = FileNamingService::generateFileName(
        $jenisClean,
        $pendaftar->nomor_pendaftaran,
        $ext,
        $pendaftar->nama_lengkap
    );

    $newPath = 'dokumen/' . $pendaftar->id . '/' . $newFilename;

    // Skip if already in new format (check if nama pendaftar is in path)
    $namaClean = str_replace([' ', '-'], ['_', ''], $pendaftar->nama_lengkap);
    if (strpos($oldPath, substr($namaClean, 0, 15)) !== false) {
        echo "  ⏭️  SKIP: {$pendaftar->nomor_pendaftaran} - already new format (contains nama)\n";
        $skipped++;
        continue;
    }

    try {
        // Rename file
        if (Storage::disk('private')->exists($oldPath)) {
            Storage::disk('private')->move($oldPath, $newPath);

            // Update database
            $dokumen->path_file = $newPath;
            $dokumen->nama_file = $newFilename;
            $dokumen->save();

            echo "  ✅ {$pendaftar->nomor_pendaftaran} [{$dokumen->jenis_dokumen}]: {$newFilename}\n";
            $updated++;
        }
    } catch (\Exception $e) {
        echo "  ❌ ERROR {$pendaftar->nomor_pendaftaran}: {$e->getMessage()}\n";
        $errors++;
    }
}

// ===================================
// 3. Update Bukti Tes Psikologi
// ===================================
echo "\n3. Updating Bukti Tes Psikologi...\n";
$tesPsikologis = \App\Models\TesPsikologiPendaftar::with('pendaftar')
    ->whereNotNull('bukti_transfer')
    ->get();

foreach ($tesPsikologis as $tesPs) {
    $oldPath = $tesPs->bukti_transfer;

    if (empty($oldPath)) {
        $skipped++;
        continue;
    }

    // Check which disk the file is in (bisa di public atau private)
    $isInPublic = Storage::disk('public')->exists($oldPath);
    $isInPrivate = Storage::disk('private')->exists($oldPath);

    if (!$isInPublic && !$isInPrivate) {
        echo "  ⏭️  SKIP {$tesPs->id}: File tidak ditemukan di public maupun private disk\n";
        $skipped++;
        continue;
    }

    $pendaftar = $tesPs->pendaftar;
    if (!$pendaftar) {
        $skipped++;
        continue;
    }

    // Get extension
    $ext = pathinfo($oldPath, PATHINFO_EXTENSION);

    // Generate new filename
    $newFilename = FileNamingService::generateBuktiBayarFileName(
        'psikologi',
        $pendaftar->nomor_pendaftaran,
        $ext,
        $pendaftar->nama_lengkap
    );

    $newPath = 'bukti_bayar/' . $pendaftar->id . '/' . $newFilename;

    // Skip if already in new format (check if nama pendaftar is in path)
    $namaClean = str_replace([' ', '-'], ['_', ''], $pendaftar->nama_lengkap);
    if (strpos($oldPath, substr($namaClean, 0, 15)) !== false) {
        echo "  ⏭️  SKIP: {$pendaftar->nomor_pendaftaran} - already new format (contains nama)\n";
        $skipped++;
        continue;
    }

    try {
        // Move file from public OR private disk to private disk with new name
        if ($isInPublic) {
            // File di public disk, perlu pindah ke private disk
            $fileContent = Storage::disk('public')->get($oldPath);
            Storage::disk('private')->put($newPath, $fileContent);

            // Hapus file lama dari public disk
            Storage::disk('public')->delete($oldPath);

            echo "  ✅ {$pendaftar->nomor_pendaftaran}: {$oldPath} (public) → {$newPath} (private)\n";
        } else {
            // File sudah di private disk, cukup rename
            Storage::disk('private')->move($oldPath, $newPath);
            echo "  ✅ {$pendaftar->nomor_pendaftaran}: {$oldPath} → {$newPath}\n";
        }

        // Update database
        $tesPs->bukti_transfer = $newPath;
        $tesPs->save();

        $updated++;
    } catch (\Exception $e) {
        echo "  ❌ ERROR {$pendaftar->nomor_pendaftaran}: {$e->getMessage()}\n";
        $errors++;
    }
}

// ===================================
// 4. Update Bukti Daftar Ulang
// ===================================
echo "\n4. Updating Bukti Daftar Ulang Madrasah...\n";
$daftarUlangs = \App\Models\DaftarUlang::with('pendaftar')
    ->whereNotNull('bukti_pembayaran_madrasah')
    ->get();

foreach ($daftarUlangs as $du) {
    $oldPath = $du->bukti_pembayaran_madrasah;

    if (empty($oldPath)) {
        $skipped++;
        continue;
    }

    // Check which disk the file is in
    $isInPublic = Storage::disk('public')->exists($oldPath);
    $isInPrivate = Storage::disk('private')->exists($oldPath);

    if (!$isInPublic && !$isInPrivate) {
        echo "  ⏭️  SKIP {$du->id}: File tidak ditemukan di public maupun private disk\n";
        $skipped++;
        continue;
    }

    $pendaftar = $du->pendaftar;
    if (!$pendaftar) {
        $skipped++;
        continue;
    }

    // Get extension
    $ext = pathinfo($oldPath, PATHINFO_EXTENSION);

    // Generate new filename
    $newFilename = FileNamingService::generateBuktiBayarFileName(
        'daftar_ulang',
        $pendaftar->nomor_pendaftaran,
        $ext,
        $pendaftar->nama_lengkap
    );

    $newPath = 'bukti_bayar/' . $pendaftar->id . '/' . $newFilename;

    // Skip if already in new format (check if nama pendaftar is in path)
    $namaClean = str_replace([' ', '-'], ['_', ''], $pendaftar->nama_lengkap);
    if (strpos($oldPath, substr($namaClean, 0, 15)) !== false) {
        echo "  ⏭️  SKIP: {$pendaftar->nomor_pendaftaran} - already new format (contains nama)\n";
        $skipped++;
        continue;
    }

    try {
        // Move file from public OR private disk to private disk with new name
        if ($isInPublic) {
            // File di public disk, perlu pindah ke private disk
            $fileContent = Storage::disk('public')->get($oldPath);
            Storage::disk('private')->put($newPath, $fileContent);

            // Hapus file lama dari public disk
            Storage::disk('public')->delete($oldPath);

            echo "  ✅ {$pendaftar->nomor_pendaftaran}: {$oldPath} (public) → {$newPath} (private)\n";
        } else {
            // File sudah di private disk, cukup rename
            Storage::disk('private')->move($oldPath, $newPath);
            echo "  ✅ {$pendaftar->nomor_pendaftaran}: {$oldPath} → {$newPath}\n";
        }

        // Update database
        $du->bukti_pembayaran_madrasah = $newPath;
        $du->save();

        $updated++;
    } catch (\Exception $e) {
        echo "  ❌ ERROR {$pendaftar->nomor_pendaftaran}: {$e->getMessage()}\n";
        $errors++;
    }
}

// ===================================
// 5. Update Bukti Daftar Ulang Seragam
// ===================================
echo "\n5. Updating Bukti Daftar Ulang Seragam...\n";
$seragams = \App\Models\Seragam::with('pendaftar')
    ->whereNotNull('bukti_pembayaran')
    ->get();

foreach ($seragams as $seragam) {
    $oldPath = $seragam->bukti_pembayaran;

    if (empty($oldPath)) {
        $skipped++;
        continue;
    }

    // Check which disk the file is in
    $isInPublic = Storage::disk('public')->exists($oldPath);
    $isInPrivate = Storage::disk('private')->exists($oldPath);

    if (!$isInPublic && !$isInPrivate) {
        echo "  ⏭️  SKIP {$seragam->id}: File tidak ditemukan di public maupun private disk\n";
        $skipped++;
        continue;
    }

    $pendaftar = $seragam->pendaftar;
    if (!$pendaftar) {
        $skipped++;
        continue;
    }

    // Get extension
    $ext = pathinfo($oldPath, PATHINFO_EXTENSION);

    // Generate new filename
    $newFilename = FileNamingService::generateBuktiBayarFileName(
        'seragam',
        $pendaftar->nomor_pendaftaran,
        $ext,
        $pendaftar->nama_lengkap
    );

    $newPath = 'bukti_bayar/' . $pendaftar->id . '/' . $newFilename;

    // Skip if already in new format (check if nama pendaftar is in path)
    $namaClean = str_replace([' ', '-'], ['_', ''], $pendaftar->nama_lengkap);
    if (strpos($oldPath, substr($namaClean, 0, 15)) !== false) {
        echo "  ⏭️  SKIP: {$pendaftar->nomor_pendaftaran} - already new format (contains nama)\n";
        $skipped++;
        continue;
    }

    try {
        // Move file from public OR private disk to private disk with new name
        if ($isInPublic) {
            // File di public disk, perlu pindah ke private disk
            $fileContent = Storage::disk('public')->get($oldPath);
            Storage::disk('private')->put($newPath, $fileContent);

            // Hapus file lama dari public disk
            Storage::disk('public')->delete($oldPath);

            echo "  ✅ {$pendaftar->nomor_pendaftaran}: {$oldPath} (public) → {$newPath} (private)\n";
        } else {
            // File sudah di private disk, cukup rename
            Storage::disk('private')->move($oldPath, $newPath);
            echo "  ✅ {$pendaftar->nomor_pendaftaran}: {$oldPath} → {$newPath}\n";
        }

        // Update database
        $seragam->bukti_pembayaran = $newPath;
        $seragam->save();

        $updated++;
    } catch (\Exception $e) {
        echo "  ❌ ERROR {$pendaftar->nomor_pendaftaran}: {$e->getMessage()}\n";
        $errors++;
    }
}

echo "\n===========================================\n";
echo "SUMMARY\n";
echo "===========================================\n";
echo "✅ Updated: $updated files\n";
echo "⏭️  Skipped: $skipped files\n";
echo "❌ Errors: $errors files\n";

if ($errors === 0) {
    echo "\n🎉 Migration completed successfully!\n";
} else {
    echo "\n⚠️  Some files had errors. Please check the log above.\n";
}

echo "\n===========================================\n";
echo "NEXT STEPS:\n";
echo "===========================================\n";
echo "1. Test export Excel untuk verify nama file baru\n";
echo "2. Upload file baru akan otomatis gunakan format baru\n";
echo "3. Verify file dapat di-download dengan benar\n";
echo "===========================================\n";
