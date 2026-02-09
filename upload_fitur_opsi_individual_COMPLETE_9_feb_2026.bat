@echo off
REM ================================================================
REM Upload Script - Fitur Opsi Individual COMPLETE (9 Feb 2026)
REM ================================================================
REM
REM Feature: Individual toggles + Pendaftar view logic
REM Complete implementation: Admin toggle + Pendaftar display
REM
REM STEP 5 COMPLETE:
REM   - Admin can toggle opsi lainnya per-pendaftar
REM   - Pendaftar sees opsi lainnya if global OR individual is enabled
REM
REM Total files: 6 (2 models + 3 components + 1 migration)
REM Migration: MUST run manually via SSH
REM ================================================================

echo ================================================================
echo   Fitur Opsi Individual - COMPLETE IMPLEMENTATION
echo   Date: 9 Feb 2026
echo ================================================================
echo.
echo Feature: Individual Opsi Lainnya Toggles
echo Status: STEP 1-5 COMPLETE
echo.
echo What's included:
echo   STEP 1-4: Admin UI with reliable toggles
echo   STEP 5: Pendaftar view logic (NEW!)
echo.
echo Logic: Global Setting OR Individual Setting
echo   IF global ON: Everyone sees opsi lainnya
echo   IF global OFF + individual ON: Only that person sees it
echo   IF both OFF: Nobody sees it
echo.

REM Set production server details
set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo ================================================================
echo   STEP 1: Upload Models
echo ================================================================
echo.

echo [1/6] Uploading DaftarUlang Model...
echo File: app\Models\DaftarUlang.php
timeout /t 2 /nobreak >nul
scp "app\Models\DaftarUlang.php" %SERVER%:%REMOTE_PATH%/app/Models/
if errorlevel 1 (
    echo ERROR: Failed to upload DaftarUlang.php
    pause
    exit /b 1
)
echo SUCCESS: DaftarUlang model uploaded
echo.

echo [2/6] Uploading Seragam Model...
echo File: app\Models\Seragam.php
timeout /t 2 /nobreak >nul
scp "app\Models\Seragam.php" %SERVER%:%REMOTE_PATH%/app/Models/
if errorlevel 1 (
    echo ERROR: Failed to upload Seragam.php
    pause
    exit /b 1
)
echo SUCCESS: Seragam model uploaded
echo.

echo ================================================================
echo   STEP 2: Upload Admin Components
echo ================================================================
echo.

echo [3/6] Uploading Admin DaftarUlang Component...
echo File: app\Livewire\Admin\DaftarUlang.php
echo Changes: Toggle methods for individual settings
timeout /t 2 /nobreak >nul
scp "app\Livewire\Admin\DaftarUlang.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Admin/
if errorlevel 1 (
    echo ERROR: Failed to upload Admin DaftarUlang component
    pause
    exit /b 1
)
echo SUCCESS: Admin DaftarUlang component uploaded
echo.

echo [4/6] Uploading Admin DaftarUlang View (RELIABLE VERSION)...
echo File: resources\views\livewire\admin\daftar-ulang.blade.php
echo Changes: "Aksi Opsi" column with reliable toggles
timeout /t 2 /nobreak >nul
scp "resources\views\livewire\admin\daftar-ulang.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/admin/
if errorlevel 1 (
    echo ERROR: Failed to upload Admin view
    pause
    exit /b 1
)
echo SUCCESS: Admin DaftarUlang view uploaded
echo.

echo ================================================================
echo   STEP 3: Upload Pendaftar Components (STEP 5 - NEW!)
echo ================================================================
echo.

echo [5/6] Uploading Pendaftar DaftarUlang Component...
echo File: app\Livewire\Pendaftar\DaftarUlang.php
echo Changes: Global OR Individual logic + Computed properties
echo   - Checks global setting OR individual setting
echo   - 4 computed properties for fine-grained control
echo   - Null-safe with try-catch blocks
timeout /t 2 /nobreak >nul
scp "app\Livewire\Pendaftar\DaftarUlang.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Pendaftar/
if errorlevel 1 (
    echo ERROR: Failed to upload Pendaftar DaftarUlang component
    pause
    exit /b 1
)
echo SUCCESS: Pendaftar DaftarUlang component uploaded
echo.

echo ================================================================
echo   STEP 4: Upload Migration File
echo ================================================================
echo.

echo [6/6] Uploading Migration...
echo File: database\migrations\2026_02_09_100000_add_individual_opsi_lainnya_to_daftar_ulang_and_seragam.php
timeout /t 2 /nobreak >nul
scp "database\migrations\2026_02_09_100000_add_individual_opsi_lainnya_to_daftar_ulang_and_seragam.php" %SERVER%:%REMOTE_PATH%/database/migrations/
if errorlevel 1 (
    echo ERROR: Failed to upload migration file
    pause
    exit /b 1
)
echo SUCCESS: Migration file uploaded
echo.

echo ================================================================
echo   STEP 5: Run Migration (MANUAL STEP)
echo ================================================================
echo.

echo IMPORTANT: You MUST run migration manually via SSH!
echo.
echo Run these commands:
echo   ssh %SERVER%
echo   cd %REMOTE_PATH%
echo   php artisan migrate
echo.
echo Expected output:
echo   Migrating: 2026_02_09_100000_add_individual_opsi_lainnya_to_daftar_ulang_and_seragam
echo   Migrated:  2026_02_09_100000_add_individual_opsi_lainnya_to_daftar_ulang_and_seragam
echo.
pause
echo.

echo ================================================================
echo   STEP 6: Clearing Production Cache
echo ================================================================
echo.

echo [1/4] Clearing view cache...
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan view:clear"
if errorlevel 1 (
    echo WARNING: Failed to clear view cache
) else (
    echo SUCCESS: View cache cleared
)
echo.

echo [2/4] Clearing application cache...
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan cache:clear"
if errorlevel 1 (
    echo WARNING: Failed to clear application cache
) else (
    echo SUCCESS: Application cache cleared
)
echo.

echo [3/4] Clearing config cache...
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan config:clear"
if errorlevel 1 (
    echo WARNING: Failed to clear config cache
) else (
    echo SUCCESS: Config cache cleared
)
echo.

echo [4/4] Optimizing application...
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan optimize:clear"
if errorlevel 1 (
    echo WARNING: Failed to optimize
) else (
    echo SUCCESS: Application optimized
)
echo.

echo ================================================================
echo   Upload Complete!
echo ================================================================
echo.
echo Files uploaded:
echo   1. app/Models/DaftarUlang.php
echo   2. app/Models/Seragam.php
echo   3. app/Livewire/Admin/DaftarUlang.php
echo   4. resources/views/livewire/admin/daftar-ulang.blade.php
echo   5. app/Livewire/Pendaftar/DaftarUlang.php (STEP 5 - NEW!)
echo   6. database/migrations/2026_02_09_100000_add_individual_opsi_lainnya_to_daftar_ulang_and_seragam.php
echo.
echo Cache cleared:
echo   - View cache
echo   - Application cache
echo   - Config cache
echo   - Optimized
echo.
echo ================================================================
echo   COMPLETE IMPLEMENTATION SUMMARY
echo ================================================================
echo.
echo STEP 1-4 (Admin Side):
echo   [x] Database migration (4 new columns)
echo   [x] Model updates (fillable + casts)
echo   [x] Backend toggle methods
echo   [x] Frontend "Aksi Opsi" column with reliable toggles
echo.
echo STEP 5 (Pendaftar Side - NEW!):
echo   [x] Component logic: Global OR Individual
echo   [x] Computed properties for fine-grained control
echo   [x] Null-safe with try-catch blocks
echo   [x] Backward compatible
echo.
echo ================================================================
echo   HOW IT WORKS
echo ================================================================
echo.
echo ADMIN:
echo   1. Go to /admin/daftar-ulang?activeTab=madrasah
echo   2. See "Aksi Opsi" column with 2 toggles per row
echo   3. Click toggle to enable for specific pendaftar
echo   4. Toggle state saves to database
echo.
echo PENDAFTAR (After STEP 5):
echo   1. Login as pendaftar
echo   2. Go to /pendaftar/daftar-ulang?activeTab=madrasah
echo   3. IF global ON OR individual toggle ON:
echo      → Sees "Opsi Lainnya" in dropdown/konfirmasi
echo   4. IF both OFF:
echo      → Does NOT see "Opsi Lainnya"
echo.
echo Logic: Global Setting OR Individual Setting
echo   Global ON + Individual OFF = Opsi Lainnya shown
echo   Global OFF + Individual ON = Opsi Lainnya shown
echo   Global OFF + Individual OFF = Opsi Lainnya hidden
echo   Global ON + Individual ON = Opsi Lainnya shown
echo.
echo ================================================================
echo   TESTING CHECKLIST
echo ================================================================
echo.
echo [TEST 1: Admin Toggle]
echo   1. Admin → /admin/daftar-ulang?activeTab=madrasah
echo   2. Find pendaftar (e.g., row 1)
echo   3. Click "Dropdown Opsi Lainnya" toggle → ON
echo   4. Verify: Toggle turns blue
echo   5. Verify: Success notification
echo   6. Refresh page → Toggle still ON
echo.
echo [TEST 2: Pendaftar View - Individual ON]
echo   1. Ensure global setting is OFF
echo   2. Enable individual toggle for pendaftar A (from Test 1)
echo   3. Login as pendaftar A
echo   4. Go to /pendaftar/daftar-ulang?activeTab=madrasah
echo   5. Verify: "Opsi Lainnya" appears in dropdown paket
echo   6. Test selecting "Opsi Lainnya"
echo   7. Verify: Can input custom amount
echo.
echo [TEST 3: Pendaftar View - Individual OFF]
echo   1. Login as pendaftar B (toggle OFF)
echo   2. Go to /pendaftar/daftar-ulang?activeTab=madrasah
echo   3. Verify: "Opsi Lainnya" does NOT appear
echo.
echo [TEST 4: Global Setting Priority]
echo   1. Admin → /admin/daftar-ulang?activeTab=settings
echo   2. Enable global setting for Madrasah
echo   3. Login as any pendaftar
echo   4. Verify: ALL pendaftar see "Opsi Lainnya"
echo   5. Disable global setting
echo   6. Verify: Only pendaftar with individual toggle ON see it
echo.
echo [TEST 5: Seragam Tab]
echo   1. Repeat Test 1-4 for Seragam tab
echo   2. Verify: Same behavior for Seragam
echo.
echo [TEST 6: Konfirmasi Nominal]
echo   1. Enable "Konfirmasi Opsi Lainnya" toggle
echo   2. Login as that pendaftar
echo   3. Go to konfirmasi nominal step
echo   4. Verify: "Opsi Lainnya" appears in nominal options
echo.
echo ================================================================
echo   TROUBLESHOOTING
echo ================================================================
echo.
echo Problem: Toggle works but pendaftar doesn't see opsi lainnya
echo Solution 1: Clear browser cache (Ctrl+Shift+F5)
echo Solution 2: Check database - verify opsi_lainnya_*_individual = true
echo Solution 3: Check Laravel logs for errors
echo Solution 4: Verify migration ran successfully
echo.
echo Problem: Error when clicking toggle
echo Solution 1: Verify migration ran (columns exist)
echo Solution 2: Check Laravel logs:
echo            ssh %SERVER% "cd %REMOTE_PATH% ^&^& tail -50 storage/logs/laravel.log"
echo.
echo Problem: Opsi lainnya appears for everyone (not just toggled users)
echo Solution: Check if global setting is ON:
echo           Admin → /admin/daftar-ulang?activeTab=settings
echo           Disable global setting if you want per-user control
echo.
echo ================================================================
echo.
echo Fitur Opsi Individual COMPLETE (Steps 1-5) UPLOAD SUCCESS!
echo.
echo Next Steps:
echo   1. Run migration: php artisan migrate
echo   2. Test Admin toggles
echo   3. Test Pendaftar view (STEP 5)
echo   4. Verify global OR individual logic works
echo.
echo Documentation: FITUR_AKSI_OPSI_INDIVIDUAL_COMPLETE_9_FEB_2026.md
echo.
echo ================================================================
pause
