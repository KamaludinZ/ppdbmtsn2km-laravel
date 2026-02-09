@echo off
REM ================================================================
REM Upload Script - Fix KIP Field Save Issue
REM ================================================================
REM
REM Problem: KIP field tidak bisa menyimpan di Tab Data Siswa
REM Root Cause: nomor_kip missing dari validation dan $dataToUpdate
REM Solution: Add nomor_kip to saveDataSiswa() in Admin & Panitia
REM
REM Total files: 2 (Admin + Panitia)
REM ================================================================

echo ================================================================
echo   Fix KIP Field Save Issue
echo   Date: 9 Feb 2026
echo ================================================================
echo.
echo Problem: KIP field tidak bisa menyimpan saat edit
echo Location: Tab Data Siswa di Admin/Panitia detail pendaftar
echo Solution: Add nomor_kip to validation and save method
echo.

REM Set production server details
set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo ================================================================
echo   Uploading Fixed Files
echo ================================================================
echo.

echo [1/2] Uploading Admin DetailPendaftar Component...
echo File: app\Livewire\Admin\DetailPendaftar.php
echo Changes: Add nomor_kip validation + add to $dataToUpdate array
echo.
timeout /t 2 /nobreak >nul
scp "app\Livewire\Admin\DetailPendaftar.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Admin/
if errorlevel 1 (
    echo ERROR: Failed to upload Admin DetailPendaftar.php
    pause
    exit /b 1
)
echo SUCCESS: Admin component uploaded
echo.

echo [2/2] Uploading Panitia DetailPendaftar Component...
echo File: app\Livewire\Panitia\DetailPendaftar.php
echo Changes: Add nomor_kip validation + add to $dataToUpdate array
echo.
timeout /t 2 /nobreak >nul
scp "app\Livewire\Panitia\DetailPendaftar.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Panitia/
if errorlevel 1 (
    echo ERROR: Failed to upload Panitia DetailPendaftar.php
    pause
    exit /b 1
)
echo SUCCESS: Panitia component uploaded
echo.

echo ================================================================
echo   Clearing Production Cache (CRITICAL!)
echo ================================================================
echo.

echo [3/7] Clearing view cache...
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan view:clear"
if errorlevel 1 (
    echo WARNING: Failed to clear view cache
) else (
    echo SUCCESS: View cache cleared
)
echo.

echo [4/7] Clearing Livewire cache...
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan livewire:delete-tmp"
if errorlevel 1 (
    echo WARNING: Failed to clear Livewire cache
) else (
    echo SUCCESS: Livewire cache cleared
)
echo.

echo [5/7] Clearing application cache...
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan cache:clear"
if errorlevel 1 (
    echo WARNING: Failed to clear application cache
) else (
    echo SUCCESS: Application cache cleared
)
echo.

echo [6/7] Clearing config cache...
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan config:clear"
if errorlevel 1 (
    echo WARNING: Failed to clear config cache
) else (
    echo SUCCESS: Config cache cleared
)
echo.

echo [7/7] Optimizing application...
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
echo   - app/Livewire/Admin/DetailPendaftar.php
echo   - app/Livewire/Panitia/DetailPendaftar.php
echo.
echo Cache cleared:
echo   - View cache
echo   - Livewire cache
echo   - Application cache
echo   - Config cache
echo   - Optimized
echo.
echo ================================================================
echo   What Changed?
echo ================================================================
echo.
echo BOTH Admin and Panitia DetailPendaftar components:
echo.
echo [CHANGE 1] Added validation rule:
echo   'nomor_kip' =^> 'nullable^|string^|max:25^|regex:/^^[A-Za-z0-9]+$/'
echo.
echo [CHANGE 2] Added custom error messages:
echo   'nomor_kip.max' =^> 'Nomor KIP maksimal 25 karakter'
echo   'nomor_kip.regex' =^> 'Nomor KIP hanya boleh berisi huruf dan angka'
echo.
echo [CHANGE 3] Added to $dataToUpdate array:
echo   'nomor_kip' =^> $this-^>nomor_kip
echo.
echo Line numbers:
echo   - Line 1296: Validation rule added
echo   - Line 1340: Added to $dataToUpdate array
echo.
echo ================================================================
echo   TESTING CHECKLIST
echo ================================================================
echo.
echo [TEST 1: Save Empty KIP - Admin]
echo   1. Login sebagai Admin
echo   2. Buka /admin/pendaftar/27 (atau ID lain)
echo   3. Click tab "Data Siswa"
echo   4. Click "Edit Data"
echo   5. Scroll to Section 9: "Kartu Indonesia Pintar (KIP)"
echo   6. Leave "Nomor KIP" field EMPTY
echo   7. Click "Simpan"
echo   8. Verify: Success message, exit edit mode
echo   9. Refresh page (F5)
echo   10. Verify: Field still empty (saved correctly as null)
echo.
echo [TEST 2: Save Valid KIP Number - Admin]
echo   1. Admin: Edit Data Siswa for pendaftar
echo   2. Input Nomor KIP: "KIP123456789"
echo   3. Click "Simpan"
echo   4. Verify: Success message
echo   5. Click "Edit Data" again
echo   6. Verify: Field shows "KIP123456789"
echo   7. Refresh page (F5)
echo   8. Verify: Value persists after reload
echo   9. Check database:
echo      ssh %SERVER% "cd %REMOTE_PATH% ^&^& php artisan tinker"
echo      App\Models\Pendaftar::find(27)-^>nomor_kip
echo   10. Expected output: "KIP123456789"
echo.
echo [TEST 3: Validation - Max Length]
echo   1. Edit Data Siswa
echo   2. Input Nomor KIP: "ABCDEFGHIJKLMNOPQRSTUVWXYZ" (26 chars - too long)
echo   3. Click "Simpan"
echo   4. Verify: ERROR shown
echo   5. Error message: "Nomor KIP maksimal 25 karakter"
echo   6. Data NOT saved
echo   7. Change to 25 chars exactly: "ABCDEFGHIJKLMNOPQRSTUVWXY"
echo   8. Click "Simpan"
echo   9. Verify: SUCCESS
echo.
echo [TEST 4: Validation - Invalid Characters]
echo   1. Edit Data Siswa
echo   2. Input Nomor KIP: "KIP-2024-001" (contains hyphens)
echo   3. Click "Simpan"
echo   4. Verify: ERROR shown
echo   5. Error message: "Nomor KIP hanya boleh berisi huruf dan angka"
echo   6. Fix: Change to "KIP2024001" (no hyphens)
echo   7. Click "Simpan"
echo   8. Verify: SUCCESS
echo.
echo [TEST 5: Save from Panitia Interface]
echo   1. Logout, login sebagai Panitia
echo   2. Buka /panitia/pendaftar/27
echo   3. Repeat Test 1-4 from Panitia interface
echo   4. Verify: All tests pass (same behavior as Admin)
echo.
echo [TEST 6: KIP Affects Upload Dokumen]
echo   1. Admin: Set pendaftar KIP to "KIP999", save
echo   2. Login as that Pendaftar
echo   3. Go to /pendaftar/dokumen
echo   4. Verify: "Upload KIP" field is VISIBLE and ENABLED
echo   5. Admin: Clear KIP (set to empty), save
echo   6. Pendaftar: Refresh /pendaftar/dokumen
echo   7. Verify: "Upload KIP" field is now HIDDEN/DISABLED
echo.
echo [TEST 7: Multiple Edit Cycles]
echo   1. Admin: Set KIP to "TEST001", save
echo   2. Verify saved: "TEST001"
echo   3. Edit again, change to "TEST002", save
echo   4. Verify saved: "TEST002"
echo   5. Edit again, clear (empty), save
echo   6. Verify saved: null (empty)
echo   7. Edit again, set to "FINAL123", save
echo   8. Verify saved: "FINAL123"
echo   All cycles should work - no lingering old values
echo.
echo Expected Results:
echo   - KIP field saves correctly in Admin interface
echo   - KIP field saves correctly in Panitia interface
echo   - Empty values save as null (optional field)
echo   - Non-empty values persist across page reloads
echo   - Validation enforces max 25 chars
echo   - Validation enforces alphanumeric only
echo   - Upload KIP field activation works based on nomor_kip
echo.
echo ================================================================
echo   TROUBLESHOOTING
echo ================================================================
echo.
echo Problem: KIP field still tidak save?
echo Solution 1: Hard refresh browser (Ctrl+Shift+F5)
echo Solution 2: Clear Livewire cache again:
echo            ssh %SERVER% "cd %REMOTE_PATH% ^&^& php artisan livewire:delete-tmp"
echo Solution 3: Check component loaded:
echo            ssh %SERVER% "cd %REMOTE_PATH% ^&^& grep nomor_kip app/Livewire/Admin/DetailPendaftar.php"
echo            Should show validation rule and $dataToUpdate line
echo.
echo Problem: Validation error tidak muncul?
echo Solution 1: Check browser console for JavaScript errors
echo Solution 2: Verify Livewire working (check other fields)
echo Solution 3: Clear browser cache completely
echo.
echo Problem: Old value masih muncul setelah save?
echo Solution 1: Check database directly:
echo            ssh %SERVER% "cd %REMOTE_PATH% ^&^& php artisan tinker"
echo            $p = App\Models\Pendaftar::find(27)
echo            $p-^>nomor_kip
echo            If correct in DB but wrong in UI: cache issue
echo Solution 2: Clear all caches (run this script again)
echo.
echo Problem: Validation terlalu ketat?
echo Note: Validation matches UI constraints:
echo   - Max 25 chars (same as input maxlength="25")
echo   - Alphanumeric only (same as UI oninput filter)
echo   This is intentional and correct.
echo.
echo ================================================================
echo.
echo KIP Field FIX complete!
echo.
echo Before Fix:
echo   - KIP field in UI: YES
echo   - Field loads data: YES
echo   - Field saves data: NO (BUG)
echo   - User confused: YES
echo.
echo After Fix:
echo   - KIP field in UI: YES
echo   - Field loads data: YES
echo   - Field saves data: YES (FIXED)
echo   - Validation works: YES
echo   - User happy: YES
echo.
echo Root Cause:
echo   Field was missing from:
echo   1. Validation rules in saveDataSiswa()
echo   2. $dataToUpdate array in saveDataSiswa()
echo.
echo Solution:
echo   Added nomor_kip to both places in BOTH components:
echo   - app/Livewire/Admin/DetailPendaftar.php
echo   - app/Livewire/Panitia/DetailPendaftar.php
echo.
echo Impact:
echo   - KIP field now saves correctly
echo   - Upload KIP feature now works
echo   - Validation prevents invalid data
echo   - Admin and Panitia both fixed
echo.
echo ================================================================
pause
