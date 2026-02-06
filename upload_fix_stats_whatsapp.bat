@echo off
echo ============================================================
echo FIX STATS OVERVIEW - HISTORY WHATSAPP
echo ============================================================
echo.
echo Masalah: Stats overview di tab history-whatsapp tidak berfungsi
echo Solusi: Perbaiki fungsi getQueueStatistics() untuk menghitung dari database
echo.
echo Stats yang akan berfungsi:
echo - Total Pesan (semua WhatsApp di riwayat_pesan)
echo - Antrian (dari message_queue dengan status queued/processing)
echo - Terkirim (dari riwayat_pesan dengan status terkirim)
echo - Gagal (dari riwayat_pesan dengan status gagal)
echo - Hari Ini (terkirim hari ini)
echo.
echo ============================================================
pause

echo.
echo [1/1] Uploading PengaturanPesan.php...
scp app/Livewire/Admin/PengaturanPesan.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
if errorlevel 1 (
    echo ERROR: Gagal upload PengaturanPesan.php
    pause
    exit /b 1
)
echo SUCCESS: PengaturanPesan.php uploaded

echo.
echo ============================================================
echo LANGKAH SELANJUTNYA DI PRODUCTION:
echo ============================================================
echo.
echo 1. Clear cache:
echo    ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
echo.
echo 2. Test Stats Overview:
echo    - Buka /admin/pengaturan-pesan?activeTab=history-whatsapp
echo    - Stats overview harus menampilkan angka yang benar:
echo      * Total Pesan: jumlah semua history WhatsApp
echo      * Antrian: jumlah pesan di queue
echo      * Terkirim: jumlah pesan terkirim
echo      * Gagal: jumlah pesan gagal
echo      * Hari Ini: jumlah terkirim hari ini
echo.
echo 3. Test dengan kirim pesan:
echo    - Kirim test WhatsApp dari /admin/settings
echo    - Refresh tab history-whatsapp
echo    - Stats harus terupdate otomatis
echo.
echo ============================================================
echo UPLOAD SELESAI!
echo ============================================================
pause
