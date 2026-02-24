@echo off
echo ========================================
echo UPLOAD: Fitur Pengaturan Alert Ukuran Seragam
echo Tanggal: 22 Februari 2026
echo ========================================
echo.

echo [1/4] Uploading Admin DaftarUlang.php...
timeout /t 1 /nobreak >nul
scp app/Livewire/Admin/DaftarUlang.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload Admin DaftarUlang.php
    pause
    exit /b 1
)
echo SUCCESS: Admin DaftarUlang.php uploaded
echo.

echo [2/4] Uploading Pendaftar DaftarUlang.php...
timeout /t 1 /nobreak >nul
scp app/Livewire/Pendaftar/DaftarUlang.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload Pendaftar DaftarUlang.php
    pause
    exit /b 1
)
echo SUCCESS: Pendaftar DaftarUlang.php uploaded
echo.

echo [3/4] Uploading Admin daftar-ulang.blade.php...
timeout /t 1 /nobreak >nul
scp resources/views/livewire/admin/daftar-ulang.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload Admin daftar-ulang.blade.php
    pause
    exit /b 1
)
echo SUCCESS: Admin daftar-ulang.blade.php uploaded
echo.

echo [4/4] Uploading Pendaftar daftar-ulang.blade.php...
timeout /t 1 /nobreak >nul
scp resources/views/livewire/pendaftar/daftar-ulang.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload Pendaftar daftar-ulang.blade.php
    pause
    exit /b 1
)
echo SUCCESS: Pendaftar daftar-ulang.blade.php uploaded
echo.

echo ========================================
echo Clearing production cache...
echo ========================================
timeout /t 1 /nobreak >nul
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
if %errorlevel% neq 0 (
    echo WARNING: Failed to clear cache
    echo Please clear cache manually via SSH
) else (
    echo SUCCESS: Cache cleared
)
echo.

echo ========================================
echo UPLOAD COMPLETE!
echo ========================================
echo.
echo Files uploaded:
echo   [+] app/Livewire/Admin/DaftarUlang.php
echo   [+] app/Livewire/Pendaftar/DaftarUlang.php
echo   [+] resources/views/livewire/admin/daftar-ulang.blade.php
echo   [+] resources/views/livewire/pendaftar/daftar-ulang.blade.php
echo.
echo Next steps:
echo   1. Login ke /admin/daftar-ulang?activeTab=settings
echo   2. Scroll ke "Pengaturan Alert Perbedaan Ukuran Seragam Harian"
echo   3. Test enable/disable checkbox
echo   4. Test kedua mode perhitungan
echo   5. Verify alert muncul dengan benar untuk pendaftar
echo.
echo Documentation: FITUR_PENGATURAN_ALERT_UKURAN_SERAGAM_22_FEB_2026.md
echo.
pause
