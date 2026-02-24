@echo off
echo ========================================
echo UPLOAD FIX PAKET LAINNYA DAN EXCEL EXPORT
echo 21 FEBRUARI 2026
echo ========================================
echo.
echo Files yang akan di-upload:
echo 1. resources/views/livewire/pendaftar/daftar-ulang.blade.php
echo 2. app/Livewire/Pendaftar/DaftarUlang.php
echo 3. app/Exports/DaftarUlangMadrasahSheet.php
echo 4. app/Exports/DaftarUlangSeragamSheet.php
echo 5. app/Exports/DaftarUlangUkuranSheet.php
echo.
echo Fixes:
echo - Tab Madrasah: Paket Lainnya display dan save
echo - Tab Seragam: Paket Lainnya display dan save
echo - Excel Export: Parse JSON, add columns, multi-select
echo.
pause
echo.
echo [1/5] Uploading daftar-ulang.blade.php...
scp "resources/views/livewire/pendaftar/daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if errorlevel 1 (
    echo ERROR: Failed to upload daftar-ulang.blade.php
    pause
    exit /b 1
)
echo SUCCESS: daftar-ulang.blade.php uploaded
echo.

echo [2/5] Uploading DaftarUlang.php...
scp "app/Livewire/Pendaftar/DaftarUlang.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if errorlevel 1 (
    echo ERROR: Failed to upload DaftarUlang.php
    pause
    exit /b 1
)
echo SUCCESS: DaftarUlang.php uploaded
echo.

echo [3/5] Uploading DaftarUlangMadrasahSheet.php...
scp "app/Exports/DaftarUlangMadrasahSheet.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/
if errorlevel 1 (
    echo ERROR: Failed to upload DaftarUlangMadrasahSheet.php
    pause
    exit /b 1
)
echo SUCCESS: DaftarUlangMadrasahSheet.php uploaded
echo.

echo [4/5] Uploading DaftarUlangSeragamSheet.php...
scp "app/Exports/DaftarUlangSeragamSheet.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/
if errorlevel 1 (
    echo ERROR: Failed to upload DaftarUlangSeragamSheet.php
    pause
    exit /b 1
)
echo SUCCESS: DaftarUlangSeragamSheet.php uploaded
echo.

echo [5/5] Uploading DaftarUlangUkuranSheet.php...
scp "app/Exports/DaftarUlangUkuranSheet.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/
if errorlevel 1 (
    echo ERROR: Failed to upload DaftarUlangUkuranSheet.php
    pause
    exit /b 1
)
echo SUCCESS: DaftarUlangUkuranSheet.php uploaded
echo.

echo ========================================
echo ALL FILES UPLOADED SUCCESSFULLY!
echo ========================================
echo.
echo Clearing cache on production...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear && php artisan view:clear && php artisan config:clear && php artisan cache:clear"
if errorlevel 1 (
    echo WARNING: Cache clear failed, please clear manually
) else (
    echo SUCCESS: Cache cleared
)
echo.

echo ========================================
echo DEPLOYMENT COMPLETE!
echo ========================================
echo.
echo Next steps:
echo 1. Test Tab Madrasah - Paket Lainnya
echo 2. Test Tab Seragam - Paket Lainnya
echo 3. Test Export Excel Madrasah
echo 4. Test Export Excel Seragam
echo 5. Test Export Excel Ukuran
echo.
echo Detailed testing checklist: RANGKUMAN_PERBAIKAN_21_FEB_2026_FINAL.md
echo.
pause
