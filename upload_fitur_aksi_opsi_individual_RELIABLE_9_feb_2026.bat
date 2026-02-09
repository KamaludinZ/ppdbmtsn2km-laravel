@echo off
REM ================================================================
REM Upload Script - Fitur Aksi Opsi Individual RELIABLE (9 Feb 2026)
REM ================================================================
REM
REM Feature: Individual toggles for Opsi Lainnya per-pendaftar
REM Purpose: Admin can enable opsi lainnya for specific pendaftar
REM Location: /admin/daftar-ulang tabs Madrasah and Seragam
REM
REM IMPROVEMENTS: Null safety checks, try-catch blocks, reliable display
REM Total files: 4 (2 models + 2 components)
REM Migration: MUST run manually via SSH
REM ================================================================

echo ================================================================
echo   Fitur Aksi Opsi Individual - RELIABLE VERSION
echo   Date: 9 Feb 2026
echo ================================================================
echo.
echo Feature: Individual toggles for Opsi Lainnya
echo Location: /admin/daftar-ulang?activeTab=madrasah/seragam
echo Changes: New column "Aksi Opsi" with 2 toggle switches per row
echo.
echo IMPROVEMENTS in this version:
echo   - Null safety checks with isset()
echo   - Try-catch blocks for graceful degradation
echo   - Reliable display even if migration not yet run
echo   - No errors if database columns don't exist
echo.

REM Set production server details
set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo ================================================================
echo   STEP 1: Upload Models
echo ================================================================
echo.

echo [1/4] Uploading DaftarUlang Model...
echo File: app\Models\DaftarUlang.php
echo Changes: Added opsi_lainnya_*_individual to fillable and casts
echo.
timeout /t 2 /nobreak >nul
scp "app\Models\DaftarUlang.php" %SERVER%:%REMOTE_PATH%/app/Models/
if errorlevel 1 (
    echo ERROR: Failed to upload DaftarUlang.php
    pause
    exit /b 1
)
echo SUCCESS: DaftarUlang model uploaded
echo.

echo [2/4] Uploading Seragam Model...
echo File: app\Models\Seragam.php
echo Changes: Added opsi_lainnya_*_individual to fillable and casts
echo.
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
echo   STEP 2: Upload Components
echo ================================================================
echo.

echo [3/4] Uploading Admin DaftarUlang Component...
echo File: app\Livewire\Admin\DaftarUlang.php
echo Changes: Added 2 toggle methods (lines 1032-1106)
echo   - toggleOpsiLainnyaDropdownIndividual()
echo   - toggleOpsiLainnyaKonfirmasiIndividual()
echo.
timeout /t 2 /nobreak >nul
scp "app\Livewire\Admin\DaftarUlang.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Admin/
if errorlevel 1 (
    echo ERROR: Failed to upload DaftarUlang component
    pause
    exit /b 1
)
echo SUCCESS: DaftarUlang component uploaded
echo.

echo [4/4] Uploading DaftarUlang View (RELIABLE VERSION)...
echo File: resources\views\livewire\admin\daftar-ulang.blade.php
echo Changes: Added "Aksi Opsi" column with toggle switches
echo   - Madrasah tab: 2 toggles per row (with null safety)
echo   - Seragam tab: 2 toggles per row (with null safety)
echo   - Try-catch blocks for graceful degradation
echo   - isset() checks to prevent errors
echo.
timeout /t 2 /nobreak >nul
scp "resources\views\livewire\admin\daftar-ulang.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/admin/
if errorlevel 1 (
    echo ERROR: Failed to upload daftar-ulang.blade.php
    pause
    exit /b 1
)
echo SUCCESS: DaftarUlang view uploaded (RELIABLE)
echo.

echo ================================================================
echo   STEP 3: Upload Migration File
echo ================================================================
echo.

echo [5/5] Uploading Migration...
echo File: database\migrations\2026_02_09_100000_add_individual_opsi_lainnya_to_daftar_ulang_and_seragam.php
echo Changes: Adds 4 new columns (2 per table)
echo.
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
echo   STEP 4: Run Migration (MANUAL STEP)
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
echo Columns added:
echo   - daftar_ulang.opsi_lainnya_dropdown_individual (boolean, default false)
echo   - daftar_ulang.opsi_lainnya_konfirmasi_individual (boolean, default false)
echo   - seragam.opsi_lainnya_dropdown_individual (boolean, default false)
echo   - seragam.opsi_lainnya_konfirmasi_individual (boolean, default false)
echo.
pause
echo.

echo ================================================================
echo   STEP 5: Clearing Production Cache
echo ================================================================
echo.

echo [1/5] Clearing view cache...
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan view:clear"
if errorlevel 1 (
    echo WARNING: Failed to clear view cache
) else (
    echo SUCCESS: View cache cleared
)
echo.

echo [2/5] Clearing application cache...
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan cache:clear"
if errorlevel 1 (
    echo WARNING: Failed to clear application cache
) else (
    echo SUCCESS: Application cache cleared
)
echo.

echo [3/5] Clearing config cache...
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan config:clear"
if errorlevel 1 (
    echo WARNING: Failed to clear config cache
) else (
    echo SUCCESS: Config cache cleared
)
echo.

echo [4/5] Optimizing application...
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
echo   4. resources/views/livewire/admin/daftar-ulang.blade.php (RELIABLE)
echo   5. database/migrations/2026_02_09_100000_add_individual_opsi_lainnya_to_daftar_ulang_and_seragam.php
echo.
echo Cache cleared:
echo   - View cache
echo   - Application cache
echo   - Config cache
echo   - Optimized
echo.
echo ================================================================
echo   What Makes This RELIABLE
echo ================================================================
echo.
echo 1. NULL SAFETY CHECKS
echo    - Uses isset() to check if property exists
echo    - Prevents errors if database column doesn't exist yet
echo.
echo 2. TRY-CATCH BLOCKS
echo    - Wraps property access in try-catch
echo    - Silently fails if column not found
echo    - Graceful degradation: shows toggle as OFF
echo.
echo 3. SAFE INITIALIZATION
echo    @php
echo        $dropdownEnabled = false;
echo        try {
echo            $dropdownEnabled = isset($item->daftarUlang->opsi_lainnya_dropdown_individual)
echo                ^&^& $item->daftarUlang->opsi_lainnya_dropdown_individual == true;
echo        } catch (\Exception $e) {
echo            // Silently fail if column doesn't exist
echo        }
echo    @endphp
echo.
echo 4. PRODUCTION-READY
echo    - Won't break page if migration not yet run
echo    - Toggle displays but defaults to OFF
echo    - Once migration runs, toggle state loads correctly
echo.
echo ================================================================
echo   TESTING CHECKLIST
echo ================================================================
echo.
echo [TEST 1: Before Migration - Display Works]
echo   1. Go to /admin/daftar-ulang?activeTab=madrasah
echo   2. Verify: "Aksi Opsi" column shows
echo   3. Verify: 2 toggles per row (both OFF/gray)
echo   4. Verify: NO ERRORS in browser console
echo   5. Verify: NO ERRORS in Laravel logs
echo   6. Page loads normally without breaking
echo.
echo [TEST 2: After Migration - Toggle Works]
echo   1. Run migration: php artisan migrate
echo   2. Refresh page (Ctrl+Shift+F5)
echo   3. Click toggle to ON
echo   4. Verify: Toggle turns blue/emerald
echo   5. Verify: Success notification
echo   6. Refresh page
echo   7. Verify: Toggle state persists
echo   8. Check database: value is true
echo.
echo [TEST 3: Both Tabs Work]
echo   1. Test Madrasah tab toggles
echo   2. Test Seragam tab toggles
echo   3. Both should work identically
echo.
echo [TEST 4: Reliability on Production]
echo   1. Upload files WITHOUT running migration
echo   2. Page should load normally (no errors)
echo   3. Toggles display but are inactive
echo   4. Run migration
echo   5. Hard refresh browser
echo   6. Toggles now work and save to database
echo.
echo ================================================================
echo   IMPORTANT NOTES
echo ================================================================
echo.
echo 1. SAFE TO UPLOAD BEFORE MIGRATION
echo    This version is safe to upload even if migration not yet run.
echo    Page will display correctly without errors.
echo.
echo 2. MIGRATION REQUIRED FOR FUNCTIONALITY
echo    Toggles will display but won't save until migration runs.
echo    After migration, toggles work fully.
echo.
echo 3. NO BREAKING CHANGES
echo    If something goes wrong with migration, page still works.
echo    Toggles just default to OFF state.
echo.
echo 4. RECOMMENDED DEPLOYMENT ORDER
echo    Option A (Safe):
echo      1. Upload all files first
echo      2. Test page loads correctly
echo      3. Run migration
echo      4. Test toggles work
echo.
echo    Option B (Standard):
echo      1. Run migration first
echo      2. Upload all files
echo      3. Clear cache
echo      4. Test everything
echo.
echo ================================================================
echo   TROUBLESHOOTING
echo ================================================================
echo.
echo Problem: Toggle doesn't change color
echo Solution 1: Hard refresh browser (Ctrl+Shift+F5)
echo Solution 2: Clear Laravel cache:
echo            ssh %SERVER% "cd %REMOTE_PATH% ^&^& php artisan cache:clear"
echo Solution 3: Check browser console for errors
echo.
echo Problem: Changes don't persist
echo Solution 1: Verify migration ran:
echo            ssh %SERVER% "cd %REMOTE_PATH% ^&^& php artisan migrate:status"
echo Solution 2: Check database columns exist:
echo            ssh %SERVER% "cd %REMOTE_PATH% ^&^& php artisan tinker"
echo            Schema::hasColumn('daftar_ulang', 'opsi_lainnya_dropdown_individual')
echo Solution 3: Check error logs:
echo            ssh %SERVER% "cd %REMOTE_PATH% ^&^& tail -50 storage/logs/laravel.log"
echo.
echo Problem: Column not appearing
echo Solution 1: Clear view cache:
echo            ssh %SERVER% "cd %REMOTE_PATH% ^&^& php artisan view:clear"
echo Solution 2: Check file uploaded:
echo            ssh %SERVER% "ls -la %REMOTE_PATH%/resources/views/livewire/admin/daftar-ulang.blade.php"
echo Solution 3: Hard refresh browser (Ctrl+Shift+F5)
echo.
echo Problem: Page shows error before migration
echo   This should NOT happen with RELIABLE version!
echo   If it does, check Laravel logs for specific error.
echo.
echo ================================================================
echo.
echo Fitur Aksi Opsi Individual (RELIABLE) UPLOAD COMPLETE!
echo.
echo Next Steps:
echo   1. Run migration: php artisan migrate
echo   2. Test toggles in Admin panel
echo   3. Verify database updates
echo   4. (Future) Implement Step 5 - Pendaftar view logic
echo.
echo Documentation: FITUR_AKSI_OPSI_INDIVIDUAL_9_FEB_2026.md
echo.
echo ================================================================
pause
