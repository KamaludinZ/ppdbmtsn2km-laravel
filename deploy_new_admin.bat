@echo off
echo ========================================
echo   DEPLOY NEW ADMIN TO PRODUCTION
echo ========================================
echo.

set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo [Step 1/4] Uploading new admin creation script...
scp create_new_admin.php %SERVER%:%REMOTE_PATH%/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload script
    pause
    exit /b 1
)
echo OK - Script uploaded

echo.
echo [Step 2/4] Creating new admin on server...
ssh %SERVER% "cd %REMOTE_PATH% && php create_new_admin.php"
if %errorlevel% neq 0 (
    echo ERROR: Failed to run script
    pause
    exit /b 1
)

echo.
echo [Step 3/4] Deleting script from server...
ssh %SERVER% "cd %REMOTE_PATH% && rm -f create_new_admin.php"
echo OK - Script deleted

echo.
echo [Step 4/4] Final cache clear...
ssh %SERVER% "cd %REMOTE_PATH% && php artisan optimize:clear"
echo OK - All caches cleared

echo.
echo ========================================
echo   DEPLOYMENT COMPLETE!
echo ========================================
echo.
echo New admin account created:
echo   Email:    superadmin@mtsn2kotamalang.sch.id
echo   Password: SuperAdmin2026!@#
echo.
echo Login URL: https://pmbm.mtsn2kotamalang.sch.id/login/admin
echo.
echo IMPORTANT:
echo 1. Test login with new credentials
echo 2. Change password after first login
echo 3. You now have 2 admin accounts (backup)
echo.
pause
