@echo off
echo ============================================================
echo FIX ERROR 500 - Upload Helper Files
echo ============================================================
echo.
echo ROOT CAUSE: format_pengumuman() function not found
echo FIX: Upload Helpers files + regenerate autoload
echo.

echo [1/2] Uploading helpers.php...
scp app/Helpers/helpers.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Helpers/
if %errorlevel% neq 0 (
    echo.
    echo ❌ SSH not available. Please upload manually via cPanel:
    echo.
    echo File: C:\ppdbmtsn2km-laravel\app\Helpers\helpers.php
    echo To: /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Helpers/
    echo.
    pause
    exit /b 1
)
echo ✓ helpers.php uploaded
echo.

echo [2/2] Uploading TextFormatter.php...
scp app/Helpers/TextFormatter.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Helpers/
if %errorlevel% neq 0 (
    echo ❌ Failed to upload TextFormatter.php
    pause
    exit /b 1
)
echo ✓ TextFormatter.php uploaded
echo.

echo ============================================================
echo ✅ FILES UPLOADED!
echo ============================================================
echo.
echo CRITICAL NEXT STEPS:
echo.
echo 1. Regenerate autoload (WAJIB!):
echo    ssh mtsnkot4@103.30.244.77
echo    cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
echo    composer dump-autoload
echo.
echo 2. Clear caches:
echo    php artisan view:clear
echo    php artisan optimize:clear
echo.
echo 3. Test Dashboard:
echo    Login sebagai pendaftar
echo    Dashboard harus load tanpa error 500
echo.
echo See: FIX_ERROR_500_DASHBOARD.md for detailed guide
echo.
pause
