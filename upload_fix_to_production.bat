@echo off
REM Upload and execute fix script to production
REM Date: 30 Januari 2026

echo ================================================
echo Upload Fix Script to Production
echo ================================================
echo.

REM Check if fix script exists
if not exist "fix_livewire_assets.sh" (
    echo ERROR: fix_livewire_assets.sh not found!
    echo Please make sure you're in the project root directory.
    pause
    exit /b 1
)

echo Step 1: Uploading fix script to production...
scp fix_livewire_assets.sh mtsnkot4@103.30.244.77:/home/mtsnkot4/
if errorlevel 1 (
    echo ERROR: Failed to upload script
    pause
    exit /b 1
)
echo Upload successful!
echo.

echo Step 2: Making script executable and running...
echo.
echo ================================================
echo EXECUTING FIX ON PRODUCTION SERVER
echo ================================================
echo.

ssh mtsnkot4@103.30.244.77 "chmod +x /home/mtsnkot4/fix_livewire_assets.sh && /home/mtsnkot4/fix_livewire_assets.sh"

if errorlevel 1 (
    echo.
    echo ERROR: Fix script failed on production
    echo Please check the output above for details
    pause
    exit /b 1
)

echo.
echo ================================================
echo FIX COMPLETED SUCCESSFULLY!
echo ================================================
echo.
echo Next steps:
echo 1. Open browser (Incognito/Private mode)
echo 2. Clear browser cache (Ctrl+Shift+Delete)
echo 3. Visit: https://pmbm.mtsn2kotamalang.sch.id
echo 4. Try login - should work now!
echo.
pause
