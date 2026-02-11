@echo off
echo ========================================
echo Upload Fix: Ubah Status Logic
echo ========================================
echo.

echo Uploading Admin DetailPendaftar.php...
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Admin\DetailPendaftar.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/

echo.
echo Uploading Panitia DetailPendaftar.php...
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Panitia\DetailPendaftar.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/

echo.
echo ========================================
echo Upload Complete!
echo ========================================
echo.
echo NEXT STEPS:
echo 1. SSH ke server production
echo 2. Jalankan: php artisan optimize:clear
echo.
echo TEST INSTRUCTIONS:
echo 1. Buka /admin/pendaftar/[id] atau /panitia/pendaftar/[id]
echo 2. Lihat header info di atas, ada tombol "Ubah Status"
echo.
echo KONDISI TOMBOL AKTIF:
echo - TIDAK ada dokumen dengan status "Menunggu Verifikasi"
echo - Dokumen dengan status "Disetujui", "Perbaiki Data", atau "Data Ditolak" = TOMBOL AKTIF
echo.
echo KONDISI TOMBOL DISABLED:
echo - ADA dokumen dengan status "Menunggu Verifikasi" = TOMBOL DISABLED
echo.
pause
