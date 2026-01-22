@echo off
echo ========================================
echo   FIX DASHBOARD ERROR 500
echo ========================================
echo.

set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo [Step 1/4] Backing up current Dashboard.php...
ssh %SERVER% "cd %REMOTE_PATH% && cp app/Livewire/Admin/Dashboard.php app/Livewire/Admin/Dashboard.php.backup-$(date +%%Y%%m%%d-%%H%%M%%S)"
if %errorlevel% neq 0 (
    echo ERROR: Failed to backup
    pause
    exit /b 1
)
echo OK - Backup created

echo.
echo [Step 2/4] Uploading fixed Dashboard.php...
scp app\Livewire\Admin\Dashboard_FIXED.php %SERVER%:%REMOTE_PATH%/app/Livewire/Admin/Dashboard.php
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload
    pause
    exit /b 1
)
echo OK - File uploaded

echo.
echo [Step 3/4] Clearing caches...
ssh %SERVER% "cd %REMOTE_PATH% && php artisan cache:clear && php artisan config:clear && php artisan view:clear"
echo OK - Caches cleared

echo.
echo [Step 4/4] Testing dashboard...
echo Please open: https://pmbm.mtsn2kotamalang.sch.id/admin/dashboard
echo.

echo ========================================
echo   FIX COMPLETE!
echo ========================================
echo.
echo What was fixed:
echo - Added try-catch error handling
echo - Simplified EMIS stats calculation
echo - Fixed relationship name (jalur to jalurPendaftaran)
echo - Added logging for debugging
echo.
echo If still error:
echo 1. Check logs: tail storage/logs/laravel.log
echo 2. Run: php artisan optimize:clear
echo.
pause
