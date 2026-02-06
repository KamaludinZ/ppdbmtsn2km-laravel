@echo off
echo ========================================
echo UPLOAD FIX DASHBOARD CETAK KARTU
echo ========================================
echo.

REM Upload file helpers dan dashboard yang sudah diperbaiki
echo [1/5] Uploading helpers.php...
scp "C:\ppdbmtsn2km-laravel\app\Helpers\helpers.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Helpers/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload helpers.php
    pause
    exit /b 1
)

echo [2/5] Uploading TextFormatter.php...
scp "C:\ppdbmtsn2km-laravel\app\Helpers\TextFormatter.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Helpers/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload TextFormatter.php
    pause
    exit /b 1
)

echo [3/5] Uploading Dashboard.php (Livewire)...
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Pendaftar\Dashboard.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload Dashboard.php
    pause
    exit /b 1
)

echo [4/5] Uploading dashboard.blade.php (View)...
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\pendaftar\dashboard.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload dashboard.blade.php
    pause
    exit /b 1
)

echo [5/5] Uploading composer.json...
scp "C:\ppdbmtsn2km-laravel\composer.json" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload composer.json
    pause
    exit /b 1
)

echo.
echo ========================================
echo RUNNING COMPOSER AND CACHE COMMANDS
echo ========================================
echo.

REM Run composer dump-autoload di production
echo Running composer dump-autoload...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && composer dump-autoload"
if %errorlevel% neq 0 (
    echo WARNING: composer dump-autoload failed, but continuing...
)

REM Clear all caches
echo Clearing view cache...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear"

echo Clearing config cache...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan config:clear"

echo Clearing route cache...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan route:clear"

echo Clearing application cache...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan cache:clear"

echo Optimizing application...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"

echo.
echo ========================================
echo UPLOAD COMPLETED!
echo ========================================
echo.
echo Files uploaded:
echo - app/Helpers/helpers.php
echo - app/Helpers/TextFormatter.php
echo - app/Livewire/Pendaftar/Dashboard.php
echo - resources/views/livewire/pendaftar/dashboard.blade.php
echo - composer.json
echo.
echo Fixed Issues:
echo 1. Error "Call to undefined function format_pengumuman()" - FIXED
echo 2. Info cetak kartu hanya muncul jika verifikasi selesai - FIXED
echo.
echo Cache cleared and autoload regenerated.
echo.
echo Please test:
echo 1. Access /pendaftar/dashboard as pendaftar
echo 2. Check if no error 500
echo 3. Verify cetak kartu info only shows when verifikasi is complete
echo.
pause
