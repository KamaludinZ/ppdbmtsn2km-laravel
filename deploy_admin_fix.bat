@echo off
echo ========================================
echo   DEPLOY ADMIN FIX TO PRODUCTION
echo ========================================
echo.

set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo [Step 1/4] Uploading emergency script...
scp emergency_admin_reset.php %SERVER%:%REMOTE_PATH%/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload script
    pause
    exit /b 1
)
echo OK - Script uploaded

echo.
echo [Step 2/4] Running emergency reset on server...
ssh %SERVER% "cd %REMOTE_PATH% && php emergency_admin_reset.php"
if %errorlevel% neq 0 (
    echo ERROR: Failed to run script
    pause
    exit /b 1
)

echo.
echo [Step 3/4] Deleting emergency script from server...
ssh %SERVER% "cd %REMOTE_PATH% && rm -f emergency_admin_reset.php"
echo OK - Script deleted

echo.
echo [Step 4/4] Clearing server caches...
ssh %SERVER% "cd %REMOTE_PATH% && php artisan cache:clear && php artisan config:clear && php artisan view:clear"
echo OK - Caches cleared

echo.
echo ========================================
echo   DEPLOYMENT COMPLETE!
echo ========================================
echo.
echo Admin credentials have been reset:
echo   Email:    admin@mtsn2kotamalang.sch.id
echo   Password: Admin123!@#
echo.
echo Login URL: https://pmbm.mtsn2kotamalang.sch.id/login/admin
echo.
pause
