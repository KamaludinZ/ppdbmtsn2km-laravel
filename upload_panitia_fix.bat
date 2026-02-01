@echo off
REM Upload Panitia Fix to Production
REM Date: 30 Januari 2026

echo ================================================
echo Upload Panitia List Fix to Production
echo ================================================
echo.

REM Check if file exists
if not exist "app\Livewire\Admin\Panitia.php" (
    echo ERROR: app\Livewire\Admin\Panitia.php not found!
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

echo Step 2: Uploading check script...
scp check_hidden_panitia.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/
if errorlevel 1 (
    echo WARNING: Failed to upload check script (optional)
)
echo.

echo Step 3: Clearing caches on production...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
if errorlevel 1 (
    echo ERROR: Failed to clear cache
    pause
    exit /b 1
)
echo.

echo Step 4: Running data check...
echo.
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php check_hidden_panitia.php"
echo.

echo ================================================
echo FIX DEPLOYED SUCCESSFULLY!
echo ================================================
echo.
echo Next steps:
echo 1. Open browser: https://pmbm.mtsn2kotamalang.sch.id/admin/panitia
echo 2. Clear filters (pilih "Semua Role")
echo 3. Clear search box
echo 4. Go to page 1
echo 5. Refresh page (Ctrl+F5)
echo 6. All panitia should be visible now!
echo.
echo Test:
echo - Try adding new panitia - should appear immediately
echo - Try editing panitia - should update immediately
echo - Try deleting panitia - should remove immediately
echo.
pause
