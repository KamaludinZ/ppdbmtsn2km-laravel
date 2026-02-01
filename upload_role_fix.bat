@echo off
REM Upload Role Saving Fix to Production
REM Date: 30 Januari 2026
REM Critical: Fix panitia role saving as 'pendaftar'

echo ================================================
echo Upload Role Saving Fix to Production
echo ================================================
echo.

echo CRITICAL FIX: Panitia role tersimpan sebagai 'pendaftar'
echo This fix will:
echo 1. Upload fixed Panitia.php (explicit role assignment)
echo 2. Upload fix script for existing wrong data
echo 3. Clear caches
echo 4. Run fix script to correct existing data
echo.
pause

REM Check files exist
if not exist "app\Livewire\Admin\Panitia.php" (
    echo ERROR: Panitia.php not found!
    pause
    exit /b 1
)

if not exist "fix_wrong_role_panitia.php" (
    echo ERROR: fix_wrong_role_panitia.php not found!
    pause
    exit /b 1
)

echo Step 1: Uploading fixed Panitia.php...
scp app\Livewire\Admin\Panitia.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/Panitia.php
if errorlevel 1 (
    echo ERROR: Failed to upload Panitia.php
    pause
    exit /b 1
)
echo Upload successful!
echo.

echo Step 2: Uploading fix script...
scp fix_wrong_role_panitia.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/
if errorlevel 1 (
    echo ERROR: Failed to upload fix script
    pause
    exit /b 1
)
echo Upload successful!
echo.

echo Step 3: Clearing caches...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
echo.

echo ================================================
echo FIX UPLOADED SUCCESSFULLY!
echo ================================================
echo.
echo IMPORTANT: Next Steps - Fix Existing Wrong Data
echo ================================================
echo.
echo The fix script needs to be run INTERACTIVELY on the server.
echo.
echo Please run these commands:
echo.
echo   ssh mtsnkot4@103.30.244.77
echo   cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
echo   php fix_wrong_role_panitia.php
echo.
echo The script will:
echo 1. Find all users with role 'pendaftar' but no pendaftar profile
echo 2. Ask you what role to set (1-6)
echo 3. Convert all wrong users to the chosen role
echo.
echo Recommended: Choose option 1 (panitia_pendaftaran)
echo.
echo ================================================
echo After fixing, verify in browser:
echo ================================================
echo.
echo 1. Go to: https://pmbm.mtsn2kotamalang.sch.id/admin/panitia
echo 2. Clear all filters
echo 3. All panitia should now appear!
echo 4. Try adding new panitia - role should save correctly
echo.
echo ================================================
pause
