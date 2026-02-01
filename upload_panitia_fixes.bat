@echo off
echo ================================================
echo UPLOAD PANITIA FIXES - 28 JANUARI 2026
echo ================================================
echo.
echo This script will upload the following fixes:
echo 1. Panitia detail-pendaftar.blade.php (TypeError + Display sync)
echo 2. Beasiswa template (Proactive number_format fix)
echo.
echo Press any key to start upload...
pause >nul

echo.
echo [1/2] Uploading panitia detail-pendaftar.blade.php...
scp resources/views/livewire/panitia/detail-pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/

if %errorlevel% neq 0 (
    echo ❌ Failed to upload detail-pendaftar.blade.php
    echo Check SSH connection and try again.
    pause
    exit /b 1
)

echo ✅ detail-pendaftar.blade.php uploaded successfully
echo.

echo [2/2] Uploading tab-beasiswa.blade.php...
scp resources/views/tab_templates/tab-beasiswa.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/tab_templates/

if %errorlevel% neq 0 (
    echo ❌ Failed to upload tab-beasiswa.blade.php
    echo Check SSH connection and try again.
    pause
    exit /b 1
)

echo ✅ tab-beasiswa.blade.php uploaded successfully
echo.
echo ================================================
echo ✅ ALL FILES UPLOADED SUCCESSFULLY
echo ================================================
echo.
echo Next steps:
echo 1. SSH to production: ssh mtsnkot4@103.30.244.77
echo 2. Go to project: cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
echo 3. Clear cache: php artisan view:clear
echo 4. Clear optimize: php artisan optimize:clear
echo 5. Test page: https://pmbm.mtsn2kotamalang.sch.id/panitia/pendaftar/27
echo.
echo Would you like to SSH now and clear cache? (Y/N)
set /p clear_cache=

if /i "%clear_cache%"=="Y" (
    echo.
    echo Connecting to production server...
    ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear && php artisan optimize:clear && echo '✅ Cache cleared successfully!'"

    if %errorlevel% neq 0 (
        echo ❌ Failed to clear cache
        echo Please SSH manually and run:
        echo   php artisan view:clear
        echo   php artisan optimize:clear
    ) else (
        echo.
        echo ================================================
        echo ✅ DEPLOYMENT COMPLETE!
        echo ================================================
        echo.
        echo Test the page now:
        echo https://pmbm.mtsn2kotamalang.sch.id/panitia/pendaftar/27
    )
)

echo.
pause
