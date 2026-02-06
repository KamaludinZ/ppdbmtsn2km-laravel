@echo off
echo ========================================
echo UPLOAD CATATAN PENDAFTAR COMPLETE
echo ========================================
echo.

REM Upload file yang sudah diperbaiki
echo [1/4] Uploading Dashboard.php (Pendaftar Livewire)...
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Pendaftar\Dashboard.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload Dashboard.php
    pause
    exit /b 1
)

echo [2/4] Uploading dashboard.blade.php (Pendaftar View)...
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\pendaftar\dashboard.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload dashboard.blade.php
    pause
    exit /b 1
)

echo [3/4] Uploading status.blade.php (Pendaftar View)...
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\pendaftar\status.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload status.blade.php
    pause
    exit /b 1
)

echo [4/4] Uploading status-alert.blade.php (Component)...
scp "C:\ppdbmtsn2km-laravel\resources\views\components\status-alert.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/components/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload status-alert.blade.php
    pause
    exit /b 1
)

echo.
echo ========================================
echo CLEARING CACHE
echo ========================================
echo.

REM Clear all caches
echo Clearing view cache...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear"

echo Clearing config cache...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan config:clear"

echo Clearing application cache...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan cache:clear"

echo.
echo ========================================
echo UPLOAD COMPLETED!
echo ========================================
echo.
echo Files uploaded:
echo - app/Livewire/Pendaftar/Dashboard.php
echo - resources/views/livewire/pendaftar/dashboard.blade.php
echo - resources/views/livewire/pendaftar/status.blade.php
echo - resources/views/components/status-alert.blade.php
echo.
echo ========================================
echo CHANGES SUMMARY
echo ========================================
echo.
echo [1] DASHBOARD.PHP - Progress Logic Fix:
echo    - Step 2 (Formulir) now shows "Ditolak" when status = data_ditolak
echo    - Previously showed "Menunggu" incorrectly
echo.
echo [2] DASHBOARD.BLADE.PHP - Catatan Display:
echo    - Added catatan box ABOVE progress pendaftaran
echo    - Shows for status: lengkapi_data, perbaiki_data, data_ditolak
echo    - Yellow/orange gradient with warning icon
echo    - Displays catatan_verifikasi from admin/panitia
echo.
echo [3] STATUS.BLADE.PHP - Catatan Display:
echo    - Added catatan box BELOW status card
echo    - Same design as dashboard
echo    - Shows for same status values
echo.
echo [4] STATUS-ALERT.BLADE.PHP - Component Update:
echo    - Added support for: lengkapi_data, perbaiki_data, data_ditolak
echo    - Now shows alert in /pendaftar/formulir header
echo    - Added config for new status values
echo.
echo ========================================
echo TESTING CHECKLIST
echo ========================================
echo.
echo Test 1: Dashboard Progress - Formulir Ditolak
echo 1. Set pendaftar status_pendaftaran = 'data_ditolak'
echo 2. Access /pendaftar/dashboard
echo 3. Check Progress Step 2 (Formulir) shows "Ditolak" (red)
echo.
echo Test 2: Dashboard - Catatan Display
echo 1. Set status = lengkapi_data/perbaiki_data/data_ditolak
echo 2. Fill catatan_verifikasi in database
echo 3. Access /pendaftar/dashboard
echo 4. Verify yellow/orange box appears ABOVE progress
echo 5. Verify catatan text is displayed clearly
echo.
echo Test 3: Status Page - Catatan Display
echo 1. Same status as Test 2
echo 2. Access /pendaftar/status
echo 3. Verify catatan box appears BELOW status card
echo.
echo Test 4: Formulir Page - Status Alert
echo 1. Same status as Test 2
echo 2. Access /pendaftar/formulir
echo 3. Verify status-alert component shows in header
echo 4. Verify catatan is displayed in alert
echo.
echo Test 5: Empty Catatan
echo 1. Set catatan_verifikasi = NULL or empty
echo 2. Access all 3 pages
echo 3. Verify catatan box does NOT appear
echo.
pause
