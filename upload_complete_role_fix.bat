@echo off
REM Upload COMPLETE Role Fix (Store + Update) to Production
REM Date: 30 Januari 2026
REM Critical: Fix both CREATE and EDIT role issues

echo ================================================
echo Upload COMPLETE Role Fix to Production
echo ================================================
echo.
echo CRITICAL FIX - Both CREATE and EDIT:
echo 1. CREATE panitia - role tersimpan sebagai 'pendaftar'
echo 2. EDIT panitia - ubah role tidak berhasil
echo.
echo This fix includes:
echo - Method store(): Explicit role assignment
echo - Method update(): Explicit role assignment
echo - Both methods now correctly save role
echo.
pause

REM Check file exists
if not exist "app\Livewire\Admin\Panitia.php" (
    echo ERROR: Panitia.php not found!
    pause
    exit /b 1
)

echo Step 1: Uploading COMPLETE fixed Panitia.php...
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
    echo WARNING: Failed to upload fix script
)
echo.

echo Step 3: Uploading check script...
scp check_hidden_panitia.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/
if errorlevel 1 (
    echo WARNING: Failed to upload check script
)
echo.

echo Step 4: Clearing caches...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
echo.

echo ================================================
echo COMPLETE FIX UPLOADED SUCCESSFULLY!
echo ================================================
echo.
echo IMPORTANT: Fix Existing Wrong Data
echo ================================================
echo.
echo Run these commands on server:
echo.
echo   ssh mtsnkot4@103.30.244.77
echo   cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
echo   php fix_wrong_role_panitia.php
echo.
echo Choose appropriate role for each wrong user.
echo Recommended: Option 1 (panitia_pendaftaran)
echo.
echo ================================================
echo TESTING CHECKLIST
echo ================================================
echo.
echo After fixing data, test ALL operations:
echo.
echo 1. CREATE New Panitia:
echo    - Try each role: Panitia Pendaftaran, Daftar Ulang, Tes Psikologi, Seragam
echo    - Check DB: role should match selected role
echo.
echo 2. EDIT Existing Panitia:
echo    - Change role from Panitia Pendaftaran to Daftar Ulang
echo    - Check DB: role should be updated successfully
echo.
echo 3. Verify All Panitia Visible:
echo    - URL: https://pmbm.mtsn2kotamalang.sch.id/admin/panitia
echo    - Clear filters, all panitia should appear
echo.
echo 4. Check Database (phpPgAdmin):
echo    - No users with role 'pendaftar' without pendaftar profile
echo    - All panitia have correct role values
echo.
echo ================================================
pause
