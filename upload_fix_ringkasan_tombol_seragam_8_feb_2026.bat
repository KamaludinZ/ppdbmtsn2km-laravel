@echo off
echo ========================================
echo Upload Fix: Ringkasan Data + Tombol Simpan Seragam
echo 8 Februari 2026
echo ========================================
echo.

echo [INFO] Uploading file to production server...
echo.

echo [1/2] Uploading daftar-ulang.blade.php...
scp "resources\views\livewire\pendaftar\daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/

if errorlevel 1 (
    echo [ERROR] Failed to upload daftar-ulang.blade.php
    pause
    exit /b 1
)

echo [SUCCESS] File uploaded successfully!
echo.

echo [2/2] Running artisan optimize:clear...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"

if errorlevel 1 (
    echo [ERROR] Failed to run optimize:clear
    pause
    exit /b 1
)

echo.
echo ========================================
echo ✅ DEPLOYMENT COMPLETED SUCCESSFULLY!
echo ========================================
echo.
echo Changes deployed:
echo 1. ✅ Added "Data yang Telah Dikirim" section
echo    - Displays: Paket, Opsi, Nominal, Bank, Nama Pengirim, Waktu
echo    - 2-column responsive grid layout
echo    - Indonesian date format with WIB
echo.
echo 2. ✅ Tombol Simpan structure verified
echo    - Already hidden when status = menunggu/disetujui
echo    - Conditional block structure is correct
echo.
echo Please verify at:
echo https://pmbm.mtsn2kotamalang.sch.id/pendaftar/daftarulang?activeTab=seragam
echo.
echo IMPORTANT: Clear browser cache!
echo - Press Ctrl + Shift + R (hard refresh)
echo.
pause
