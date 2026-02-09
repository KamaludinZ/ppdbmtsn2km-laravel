@echo off
echo ============================================
echo FIX ROUTE KARTU TES SELEKSI DI DASHBOARD
echo ============================================
echo.
echo PERBAIKAN:
echo - Route /pendaftar/kartu-ujian diganti menjadi /pendaftar/kartu-tes-seleksi
echo - 2 lokasi diperbaiki:
echo   [1] Tombol "Cetak Kartu Ujian" di bagian Data Terverifikasi
echo   [2] Link "Cetak Kartu" di menu Aksi Cepat
echo.
pause

echo.
echo ========================================
echo UPLOAD DASHBOARD VIEW
echo ========================================
scp resources/views/livewire/pendaftar/dashboard.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if %errorlevel% neq 0 (
    echo GAGAL upload dashboard view
    pause
    exit /b 1
)
echo BERHASIL upload dashboard view

echo.
echo ========================================
echo CLEAR VIEW CACHE
echo ========================================
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear"
if %errorlevel% neq 0 (
    echo WARNING: Gagal clear cache
) else (
    echo BERHASIL clear cache
)

echo.
echo ============================================
echo UPLOAD SELESAI!
echo ============================================
echo.
echo TESTING CHECKLIST:
echo.
echo 1. Login sebagai Pendaftar dengan status "lulus"
echo 2. Buka /pendaftar/dashboard
echo 3. Scroll ke bagian "Data Terverifikasi - Siap Ujian!"
echo 4. Klik tombol "Cetak Kartu Ujian"
echo    [x] URL harus: /pendaftar/kartu-tes-seleksi
echo    [x] Bukan: /pendaftar/kartu-ujian (old)
echo.
echo 5. Scroll ke bagian "Aksi Cepat"
echo 6. Klik link "Cetak Kartu"
echo    [x] URL harus: /pendaftar/kartu-tes-seleksi
echo    [x] Bukan: /pendaftar/kartu-ujian (old)
echo.
echo NOTE:
echo - Semua link kartu ujian sekarang sudah konsisten
echo - Route baru: /pendaftar/kartu-tes-seleksi
echo - Route lama /pendaftar/kartu-ujian sudah tidak digunakan lagi
echo.
pause
