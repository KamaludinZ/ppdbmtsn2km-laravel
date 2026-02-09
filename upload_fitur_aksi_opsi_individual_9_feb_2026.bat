@echo off
REM ================================================================
REM Upload Script - Fitur Aksi Opsi Individual (9 Feb 2026)
REM ================================================================
REM
REM Feature: Individual toggles for Opsi Lainnya per-pendaftar
REM Purpose: Admin can enable opsi lainnya for specific pendaftar
REM Location: /admin/daftar-ulang tabs Madrasah and Seragam
REM
REM Total files: 4 (2 models + 2 components)
REM Migration: MUST run manually via SSH
REM ================================================================

echo ================================================================
echo   Fitur Aksi Opsi Individual - Upload Script
echo   Date: 9 Feb 2026
echo ================================================================
echo.
echo Feature: Individual toggles for Opsi Lainnya
echo Location: /admin/daftar-ulang?activeTab=madrasah/seragam
echo Changes: New column "Aksi Opsi" with 2 toggle switches per row
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

echo [4/4] Uploading DaftarUlang View...
echo File: resources\views\livewire\admin\daftar-ulang.blade.php
echo Changes: Added "Aksi Opsi" column with toggle switches
echo   - Madrasah tab: 2 toggles per row (lines 1539-1578)
echo   - Seragam tab: 2 toggles per row (lines 1762-1801)
echo.
timeout /t 2 /nobreak >nul
scp "resources\views\livewire\admin\daftar-ulang.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/admin/
if errorlevel 1 (
    echo ERROR: Failed to upload daftar-ulang.blade.php
    pause
    exit /b 1
)
echo SUCCESS: DaftarUlang view uploaded
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

echo [2/5] Clearing Livewire cache...
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan livewire:delete-tmp"
if errorlevel 1 (
    echo WARNING: Failed to clear Livewire cache
) else (
    echo SUCCESS: Livewire cache cleared
)
echo.

echo [3/5] Clearing application cache...
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan cache:clear"
if errorlevel 1 (
    echo WARNING: Failed to clear application cache
) else (
    echo SUCCESS: Application cache cleared
)
echo.

echo [4/5] Clearing config cache...
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan config:clear"
if errorlevel 1 (
    echo WARNING: Failed to clear config cache
) else (
    echo SUCCESS: Config cache cleared
)
echo.

echo [5/5] Optimizing application...
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
echo   5. database/migrations/2026_02_09_100000_add_individual_opsi_lainnya_to_daftar_ulang_and_seragam.php
echo.
echo Cache cleared:
echo   - View cache
echo   - Livewire cache
echo   - Application cache
echo   - Config cache
echo   - Optimized
echo.
echo ================================================================
echo   What Was Implemented
echo ================================================================
echo.
echo Feature: Individual Opsi Lainnya Toggles
echo Location: /admin/daftar-ulang?activeTab=madrasah/seragam
echo.
echo New Column: "Aksi Opsi"
echo   Contains 2 toggle switches per row:
echo   1. Dropdown Opsi Lainnya (Blue toggle)
echo   2. Konfirmasi Opsi Lainnya (Emerald toggle)
echo.
echo Purpose:
echo   Admin can enable opsi lainnya for SPECIFIC pendaftar
echo   even if global setting is OFF.
echo.
echo Logic:
echo   IF global_setting == ON:
echo     Opsi lainnya for ALL pendaftar (toggles ignored)
echo   ELSE:
echo     IF individual_toggle == ON:
echo       Opsi lainnya for THIS pendaftar only
echo     ELSE:
echo       No opsi lainnya
echo.
echo ================================================================
echo   TESTING CHECKLIST
echo ================================================================
echo.
echo [TEST 1: Column Visibility]
echo   1. Login as Admin
echo   2. Go to /admin/daftar-ulang?activeTab=madrasah
echo   3. Verify: "Aksi Opsi" column visible after "Catatan" column
echo   4. Verify: 2 toggle switches in each row
echo   5. Switch to ?activeTab=seragam
echo   6. Verify: Same column appears
echo.
echo [TEST 2: Toggle Dropdown - Madrasah]
echo   1. Go to /admin/daftar-ulang?activeTab=madrasah
echo   2. Find a pendaftar (e.g., row 1)
echo   3. Click "Dropdown Opsi Lainnya" toggle (blue) to ON
echo   4. Verify: Toggle turns blue
echo   5. Verify: Text changes to "Aktif untuk orang ini"
echo   6. Verify: Notification shows success message
echo   7. Refresh page (F5)
echo   8. Verify: Toggle still ON (blue)
echo   9. Check database:
echo      ssh %SERVER% "cd %REMOTE_PATH% ^&^& php artisan tinker"
echo      App\Models\DaftarUlang::where('pendaftar_id', X)-^>first()-^>opsi_lainnya_dropdown_individual
echo   10. Expected: true
echo.
echo [TEST 3: Toggle Konfirmasi - Madrasah]
echo   1. Find a different pendaftar
echo   2. Click "Konfirmasi Opsi Lainnya" toggle (emerald) to ON
echo   3. Verify: Toggle turns emerald/green
echo   4. Verify: Text changes to "Aktif untuk orang ini"
echo   5. Verify: Success notification
echo   6. Refresh page
echo   7. Verify: Toggle still ON
echo   8. Check database:
echo      DaftarUlang::where('pendaftar_id', X)-^>first()-^>opsi_lainnya_konfirmasi_individual
echo   9. Expected: true
echo.
echo [TEST 4: Toggle OFF]
echo   1. Click a toggle that is ON to turn it OFF
echo   2. Verify: Toggle turns gray
echo   3. Verify: Text changes to "Tidak aktif"
echo   4. Verify: Success notification
echo   5. Refresh page
echo   6. Verify: Toggle still OFF
echo   7. Check database: Expected false
echo.
echo [TEST 5: Toggle Seragam]
echo   1. Go to /admin/daftar-ulang?activeTab=seragam
echo   2. Repeat Test 2-4 for seragam tab
echo   3. Verify: Toggles work for seragam table
echo   4. Check database: seragam table columns
echo.
echo [TEST 6: Multiple Pendaftar]
echo   1. Enable Dropdown toggle for pendaftar A
echo   2. Enable Konfirmasi toggle for pendaftar B
echo   3. Enable BOTH toggles for pendaftar C
echo   4. Leave BOTH toggles OFF for pendaftar D
echo   5. Refresh page
echo   6. Verify: Each pendaftar has correct toggle states
echo.
echo [TEST 7: Independent Toggles]
echo   1. For same pendaftar:
echo   2. Enable Dropdown toggle → ON
echo   3. Leave Konfirmasi toggle → OFF
echo   4. Verify: They work independently
echo   5. Toggle Konfirmasi → ON
echo   6. Toggle Dropdown → OFF
echo   7. Verify: Only Konfirmasi is ON
echo.
echo Expected Results:
echo   - Kolom "Aksi Opsi" visible in Madrasah and Seragam tabs
echo   - 2 toggle switches per row (blue and emerald)
echo   - Toggles change color when clicked (ON/OFF)
echo   - Text indicator shows current state
echo   - Changes persist after page reload
echo   - Database updates correctly
echo   - Success notifications display
echo   - Each pendaftar has independent toggles
echo.
echo ================================================================
echo   IMPORTANT NOTES
echo ================================================================
echo.
echo 1. MIGRATION REQUIRED
echo    Run: php artisan migrate
echo    This adds 4 new columns to database
echo.
echo 2. GLOBAL SETTING HAS PRIORITY
echo    When global setting is ON, individual toggles are IGNORED
echo    This is by design to avoid confusion
echo.
echo 3. STEP 5 NOT YET IMPLEMENTED
echo    Current implementation (Steps 1-4):
echo      - UI toggles in Admin panel
echo      - Database updates
echo      - State persistence
echo
echo    Future work (Step 5):
echo      - Update Pendaftar views to check global OR individual
echo      - Display opsi lainnya based on combined logic
echo
echo    For now: Test toggle UI and database updates
echo.
echo 4. USE CASES
echo    Scenario 1: Opsi lainnya for everyone
echo      Solution: Enable global setting in Settings tab
echo
echo    Scenario 2: Opsi lainnya for specific users only
echo      Solution: Disable global setting, enable individual toggles
echo
echo    Scenario 3: Exception for one user
echo      NOT supported: Cannot disable for one when global is ON
echo      Workaround: Contact pendaftar directly
echo.
echo ================================================================
echo   TROUBLESHOOTING
echo ================================================================
echo.
echo Problem: Toggle doesn't change color
echo Solution 1: Hard refresh browser (Ctrl+Shift+F5)
echo Solution 2: Clear Livewire cache:
echo            ssh %SERVER% "cd %REMOTE_PATH% ^&^& php artisan livewire:delete-tmp"
echo Solution 3: Check browser console for errors
echo.
echo Problem: Changes don't persist
echo Solution 1: Check database connection
echo Solution 2: Verify migration ran successfully:
echo            ssh %SERVER% "cd %REMOTE_PATH% ^&^& php artisan migrate:status"
echo Solution 3: Check error logs:
echo            ssh %SERVER% "cd %REMOTE_PATH% ^&^& tail -50 storage/logs/laravel.log"
echo.
echo Problem: Column not appearing
echo Solution 1: Clear view cache:
echo            ssh %SERVER% "cd %REMOTE_PATH% ^&^& php artisan view:clear"
echo Solution 2: Check file uploaded correctly:
echo            ssh %SERVER% "ls -la %REMOTE_PATH%/resources/views/livewire/admin/daftar-ulang.blade.php"
echo Solution 3: Hard refresh browser (Ctrl+Shift+F5)
echo.
echo ================================================================
echo.
echo Fitur Aksi Opsi Individual UPLOAD COMPLETE!
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
