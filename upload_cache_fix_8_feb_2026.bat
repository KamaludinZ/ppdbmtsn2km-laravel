@echo off
REM ================================================================
REM Upload Script - Cache Fix untuk RTE Display di Pendaftar
REM ================================================================
REM
REM This script fixes the cache issue where Pendaftar view
REM still shows old content after Admin updates RTE settings.
REM
REM Root Cause: Data was loaded in blade @php block (gets cached)
REM Solution: Move data loading to Livewire component mount()
REM
REM Files: 2
REM ================================================================

echo ================================================================
echo   Upload Cache Fix - RTE Display di Pendaftar
echo   Date: 8 Feb 2026
echo ================================================================
echo.

REM Set production server details
set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo [1/2] Uploading Pendaftar DaftarUlang Component...
echo File: app\Livewire\Pendaftar\DaftarUlang.php
scp "app\Livewire\Pendaftar\DaftarUlang.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Pendaftar/
if errorlevel 1 (
    echo ERROR: Failed to upload Pendaftar DaftarUlang.php
    pause
    exit /b 1
)
echo SUCCESS: Component uploaded
echo.

echo [2/2] Uploading Pendaftar DaftarUlang Blade View...
echo File: resources\views\livewire\pendaftar\daftar-ulang.blade.php
scp "resources\views\livewire\pendaftar\daftar-ulang.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/pendaftar/
if errorlevel 1 (
    echo ERROR: Failed to upload pendaftar daftar-ulang.blade.php
    pause
    exit /b 1
)
echo SUCCESS: Blade view uploaded
echo.

echo ================================================================
echo   Clearing Production Cache (CRITICAL!)
echo ================================================================
echo.
echo This step is CRITICAL to ensure old cached views are removed.
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
echo   1. app/Livewire/Pendaftar/DaftarUlang.php
echo   2. resources/views/livewire/pendaftar/daftar-ulang.blade.php
echo.
echo Cache cleared:
echo   - View cache
echo   - Livewire cache
echo   - Application cache
echo   - Config cache
echo   - Optimized
echo.
echo ================================================================
echo   TESTING CHECKLIST
echo ================================================================
echo.
echo [STEP 1: Update di Admin]
echo   1. Login sebagai Admin
echo   2. Buka /admin/daftar-ulang?activeTab=settings
echo   3. Pilih tab "Madrasah"
echo   4. Update "Informasi untuk Pendaftar" dengan RTE:
echo      - Add bold text
echo      - Add list
echo      - Add heading
echo      - Add link
echo   5. Klik "Simpan Pengaturan Madrasah"
echo   6. Verify success message
echo.
echo [STEP 2: Check di Pendaftar]
echo   1. Logout dari Admin
echo   2. Login sebagai Pendaftar (yang sudah lulus)
echo   3. Buka /pendaftar/daftarulang?activeTab=madrasah
echo   4. Verify informasi box tampil dengan:
echo      - Text baru (BUKAN text lama!)
echo      - Bold formatting works
echo      - Lists formatted properly
echo      - Headings displayed correctly
echo      - Links clickable
echo      - Prose classes applied
echo.
echo [STEP 3: Test Cache Behavior]
echo   1. Update informasi lagi di Admin (ubah text)
echo   2. LANGSUNG buka di Pendaftar (tanpa clear cache)
echo   3. Should see NEW content immediately
echo   4. Refresh multiple times
echo   5. Content should stay up-to-date
echo.
echo [STEP 4: Test Seragam dan Ukuran]
echo   1. Test juga update di tab Seragam
echo   2. Test juga update di tab Ukuran
echo   3. Verify semua tab works correctly
echo.
echo Expected Result:
echo   - Admin updates IMMEDIATELY visible di Pendaftar
echo   - NO stale data
echo   - NO need to clear cache after each update
echo   - RTE formatting displays correctly
echo.
echo ================================================================
echo   TROUBLESHOOTING
echo ================================================================
echo.
echo Problem: Still showing old content?
echo Solution 1: Hard refresh browser (Ctrl+Shift+R)
echo Solution 2: Clear browser cache
echo Solution 3: Try different browser / incognito mode
echo Solution 4: Run cache clear commands again
echo.
echo Problem: RTE formatting not displayed?
echo Solution: Check prose classes are applied in HTML inspector
echo.
echo Problem: Box not showing at all?
echo Solution: Check Admin has saved non-empty content
echo.
echo ================================================================
pause
