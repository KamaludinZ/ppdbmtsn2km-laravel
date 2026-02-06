@echo off
echo ========================================
echo UPLOAD ALL FIXES - 6 FEBRUARI 2026
echo ========================================
echo.
echo Total Files: 7
echo Fix #1: Catatan Display (4 files)
echo Fix #2: Survey Array Fix (3 files)
echo.
echo Target: pmbm.mtsn2kotamalang.sch.id
echo ========================================
echo.

REM Define variables
set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo [1/7] Uploading Dashboard.php (Catatan Display)...
scp "app/Livewire/Pendaftar/Dashboard.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload Dashboard.php
    pause
    exit /b 1
)
echo OK - Dashboard.php uploaded
echo.

echo [2/7] Uploading dashboard.blade.php (Catatan Display)...
scp "resources/views/livewire/pendaftar/dashboard.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload dashboard.blade.php
    pause
    exit /b 1
)
echo OK - dashboard.blade.php uploaded
echo.

echo [3/7] Uploading status.blade.php (Catatan Display)...
scp "resources/views/livewire/pendaftar/status.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload status.blade.php
    pause
    exit /b 1
)
echo OK - status.blade.php uploaded
echo.

echo [4/7] Uploading status-alert.blade.php (Catatan Display)...
scp "resources/views/components/status-alert.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/components/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload status-alert.blade.php
    pause
    exit /b 1
)
echo OK - status-alert.blade.php uploaded
echo.

echo [5/7] Uploading TesPsikologi.php Pendaftar (Survey Fix)...
scp "app/Livewire/Pendaftar/TesPsikologi.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload TesPsikologi.php
    pause
    exit /b 1
)
echo OK - TesPsikologi.php (Pendaftar) uploaded
echo.

echo [6/7] Uploading Formulir.php (Survey Fix)...
scp "app/Livewire/Pendaftar/Formulir.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload Formulir.php
    pause
    exit /b 1
)
echo OK - Formulir.php uploaded
echo.

echo [7/7] Uploading TesPsikologi.php Admin (Survey Fix)...
scp "app/Livewire/Admin/TesPsikologi.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Admin/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload TesPsikologi.php (Admin)
    pause
    exit /b 1
)
echo OK - TesPsikologi.php (Admin) uploaded
echo.

echo ========================================
echo All files uploaded successfully!
echo ========================================
echo.
echo Now clearing caches...
echo.

REM Clear caches
ssh %SERVER% "cd %REMOTE_PATH% && php artisan view:clear"
if %errorlevel% neq 0 (
    echo WARNING: Failed to clear view cache
) else (
    echo OK - View cache cleared
)

ssh %SERVER% "cd %REMOTE_PATH% && php artisan config:clear"
if %errorlevel% neq 0 (
    echo WARNING: Failed to clear config cache
) else (
    echo OK - Config cache cleared
)

ssh %SERVER% "cd %REMOTE_PATH% && php artisan cache:clear"
if %errorlevel% neq 0 (
    echo WARNING: Failed to clear application cache
) else (
    echo OK - Application cache cleared
)

ssh %SERVER% "cd %REMOTE_PATH% && php artisan optimize:clear"
if %errorlevel% neq 0 (
    echo WARNING: Failed to clear optimize cache
) else (
    echo OK - Optimize cache cleared
)

echo.
echo ========================================
echo DEPLOYMENT COMPLETE!
echo ========================================
echo.
echo Next Steps:
echo 1. Test catatan display: /pendaftar/dashboard
echo 2. Test catatan display: /pendaftar/status
echo 3. Test progress step 2 for data_ditolak
echo 4. Test survey submission with checkbox
echo 5. Test admin edit survey with checkbox
echo.
echo Verification:
echo - Access /pendaftar/dashboard (should see catatan if status requires)
echo - Submit tes psikologi with checkbox (should NOT crash)
echo - Check database: survey_answers.jawaban should be JSON for checkbox
echo.
echo Monitor logs:
echo ssh %SERVER% "cd %REMOTE_PATH% && tail -100 storage/logs/laravel.log"
echo.
pause
