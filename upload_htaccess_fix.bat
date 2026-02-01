@echo off
REM Upload fixed .htaccess to production
REM Date: 30 Januari 2026

echo ================================================
echo Upload Fixed .htaccess to Production
echo ================================================
echo.

REM Check if .htaccess exists
if not exist "public\.htaccess" (
    echo ERROR: public\.htaccess not found!
    pause
    exit /b 1
)

echo Step 1: Creating backup of production .htaccess...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && cp public/.htaccess public/.htaccess.backup-error500-$(date +%%Y%%m%%d-%%H%%M%%S)"
if errorlevel 1 (
    echo WARNING: Backup failed, but continuing...
)
echo.

echo Step 2: Uploading fixed .htaccess...
scp public\.htaccess mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/public/.htaccess
if errorlevel 1 (
    echo ERROR: Failed to upload .htaccess
    pause
    exit /b 1
)
echo Upload successful!
echo.

echo Step 3: Testing .htaccess syntax...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && curl -I -s https://pmbm.mtsn2kotamalang.sch.id/vendor/livewire/livewire.min.js | head -1"
echo.

echo ================================================
echo .htaccess UPLOADED!
echo ================================================
echo.
echo Please test:
echo 1. https://pmbm.mtsn2kotamalang.sch.id/vendor/livewire/livewire.min.js
echo    Should return 200 OK (not 404 or 500)
echo.
echo 2. https://pmbm.mtsn2kotamalang.sch.id/login/admin
echo    Should load without errors
echo.
pause
