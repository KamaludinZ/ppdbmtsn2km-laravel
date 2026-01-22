@echo off
echo ========================================
echo   UPLOAD: Remove EMIS from Panitia
echo ========================================
echo.

set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo [Step 1/4] Backing up original files...
ssh %SERVER% "cd %REMOTE_PATH% && cp app/Livewire/Panitia/Dashboard.php app/Livewire/Panitia/Dashboard.php.backup-$(date +%%Y%%m%%d-%%H%%M%%S) && cp resources/views/livewire/panitia/dashboard.blade.php resources/views/livewire/panitia/dashboard.blade.php.backup-$(date +%%Y%%m%%d-%%H%%M%%S) && cp resources/views/components/layouts/dashboard.blade.php resources/views/components/layouts/dashboard.blade.php.backup-$(date +%%Y%%m%%d-%%H%%M%%S)"
if %errorlevel% neq 0 (
    echo ERROR: Failed to backup files
    pause
    exit /b 1
)
echo OK - Backups created

echo.
echo [Step 2/4] Uploading modified files...
scp app\Livewire\Panitia\Dashboard.php %SERVER%:%REMOTE_PATH%/app/Livewire/Panitia/Dashboard.php
scp resources\views\livewire\panitia\dashboard.blade.php %SERVER%:%REMOTE_PATH%/resources/views/livewire/panitia/dashboard.blade.php
scp resources\views\components\layouts\dashboard.blade.php %SERVER%:%REMOTE_PATH%/resources/views/components/layouts/dashboard.blade.php
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload files
    pause
    exit /b 1
)
echo OK - Files uploaded

echo.
echo [Step 3/4] Clearing caches...
ssh %SERVER% "cd %REMOTE_PATH% && php artisan view:clear && php artisan cache:clear"
echo OK - Caches cleared

echo.
echo [Step 4/4] Testing...
echo Please test:
echo 1. Login as panitia: https://pmbm.mtsn2kotamalang.sch.id/login/admin
echo 2. Visit: https://pmbm.mtsn2kotamalang.sch.id/panitia/dashboard
echo 3. Verify EMIS section is NOT visible
echo 4. Verify "Input EMIS" menu is NOT visible
echo.

echo ========================================
echo   UPLOAD COMPLETE!
echo ========================================
echo.
echo Changes made:
echo - Removed EMIS properties and methods from Panitia Dashboard controller
echo - Removed EMIS section from panitia dashboard view
echo - Removed "Input EMIS" menu item from panitia navigation
echo.
echo What was removed:
echo - Kelengkapan Data EMIS Kemenag 4.0 section
echo - Input EMIS menu item (panitia only)
echo.
pause
