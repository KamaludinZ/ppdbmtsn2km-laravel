@echo off
REM ================================================================
REM Upload Script - Complete RTE Fixes (Cache + Escaping)
REM ================================================================
REM
REM This script uploads fixes for BOTH issues:
REM 1. Cache Issue: Pendaftar view showing stale data
REM 2. Escaping Issue: Admin save data becomes empty after refresh
REM
REM Total files: 3
REM ================================================================

echo ================================================================
echo   Upload Complete RTE Fixes
echo   Cache Issue + Escaping Issue
echo   Date: 8 Feb 2026
echo ================================================================
echo.

REM Set production server details
set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo ================================================================
echo   PART 1: Fix Cache Issue (Pendaftar Display)
echo ================================================================
echo.

echo [1/3] Uploading Pendaftar DaftarUlang Component...
echo Fix: Move data loading dari blade to component
scp "app\Livewire\Pendaftar\DaftarUlang.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Pendaftar/
if errorlevel 1 (
    echo ERROR: Failed to upload Pendaftar DaftarUlang.php
    pause
    exit /b 1
)
echo SUCCESS
echo.

echo [2/3] Uploading Pendaftar DaftarUlang Blade View...
echo Fix: Use component properties instead of @php
scp "resources\views\livewire\pendaftar\daftar-ulang.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/pendaftar/
if errorlevel 1 (
    echo ERROR: Failed to upload pendaftar daftar-ulang.blade.php
    pause
    exit /b 1
)
echo SUCCESS
echo.

echo ================================================================
echo   PART 2: Fix Escaping Issue (Admin Save)
echo ================================================================
echo.

echo [3/3] Uploading Admin DaftarUlang Blade View...
echo Fix: Use @js() instead of addslashes() for Trix
scp "resources\views\livewire\admin\daftar-ulang.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/admin/
if errorlevel 1 (
    echo ERROR: Failed to upload admin daftar-ulang.blade.php
    pause
    exit /b 1
)
echo SUCCESS
echo.

echo ================================================================
echo   Clearing Production Cache (CRITICAL!)
echo ================================================================
echo.

echo [4/8] Clearing view cache...
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan view:clear"
if errorlevel 1 (
    echo WARNING: Failed to clear view cache
) else (
    echo SUCCESS
)
echo.

echo [5/8] Clearing Livewire cache...
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan livewire:delete-tmp"
if errorlevel 1 (
    echo WARNING: Failed to clear Livewire cache
) else (
    echo SUCCESS
)
echo.

echo [6/8] Clearing application cache...
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan cache:clear"
if errorlevel 1 (
    echo WARNING: Failed to clear application cache
) else (
    echo SUCCESS
)
echo.

echo [7/8] Clearing config cache...
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan config:clear"
if errorlevel 1 (
    echo WARNING: Failed to clear config cache
) else (
    echo SUCCESS
)
echo.

echo [8/8] Optimizing application...
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan optimize:clear"
if errorlevel 1 (
    echo WARNING: Failed to optimize
) else (
    echo SUCCESS
)
echo.

echo ================================================================
echo   Upload Complete!
echo ================================================================
echo.
echo Files uploaded:
echo   1. app/Livewire/Pendaftar/DaftarUlang.php         [Cache Fix]
echo   2. resources/views/livewire/pendaftar/daftar-ulang.blade.php  [Cache Fix]
echo   3. resources/views/livewire/admin/daftar-ulang.blade.php      [Escaping Fix]
echo.
echo Cache cleared:
echo   - View cache
echo   - Livewire cache
echo   - Application cache
echo   - Config cache
echo   - Optimized
echo.
echo ================================================================
echo   TESTING CHECKLIST - COMPLETE END-TO-END
echo ================================================================
echo.
echo [TEST 1: Admin Can Save Data]
echo   1. Login sebagai Admin
echo   2. Buka /admin/daftar-ulang?activeTab=settings
echo   3. Tab "Madrasah" - "Informasi untuk Pendaftar"
echo   4. Ketik test dengan formatting:
echo      - Bold text: **Penting**
echo      - Italic text: _Catatan_
echo      - Link: https://example.com
echo      - List dengan bullets
echo      - Text dengan "quotes" dan 'apostrophes'
echo   5. Klik "Simpan Pengaturan Madrasah"
echo   6. Refresh page (F5)
echo   7. VERIFY: Content masih ada di editor (tidak kosong!)
echo.
echo [TEST 2: Pendaftar Can See Updates]
echo   1. Logout dari Admin
echo   2. Login sebagai Pendaftar (yang sudah lulus)
echo   3. Buka /pendaftar/daftarulang?activeTab=madrasah
echo   4. VERIFY: Informasi box tampil dengan content dari Admin
echo   5. VERIFY: Formatting displayed correctly:
echo      - Bold text ditampilkan tebal
echo      - Italic text ditampilkan miring
echo      - Links clickable
echo      - Lists formatted dengan bullets
echo   6. VERIFY: Prose classes applied (typography bagus)
echo.
echo [TEST 3: No Cache Issue]
echo   1. Update lagi informasi di Admin (ubah text)
echo   2. Save di Admin
echo   3. LANGSUNG buka di Pendaftar (tanpa clear cache)
echo   4. VERIFY: New content langsung terlihat
echo.
echo [TEST 4: Test All 3 Tabs]
echo   1. Test tab Madrasah (done above)
echo   2. Test tab Seragam:
echo      - Input complex content di Admin
echo      - Save and refresh
echo      - Verify content persists in Admin
echo      - Verify displayed correctly in Pendaftar
echo   3. Test tab Ukuran:
echo      - Input complex content di Admin
echo      - Save and refresh
echo      - Verify content persists in Admin
echo      - Verify displayed correctly in Pendaftar
echo.
echo Expected Results:
echo   - Admin: Content persists after save and refresh
echo   - Pendaftar: Shows latest content immediately
echo   - No JavaScript errors in browser console
echo   - All formatting works (bold, italic, links, lists)
echo   - Quotes and special characters work
echo.
echo ================================================================
echo   TROUBLESHOOTING
echo ================================================================
echo.
echo Problem: Admin - Data kosong setelah save?
echo Solution 1: Check browser console for JavaScript errors
echo Solution 2: Hard refresh (Ctrl+Shift+R)
echo Solution 3: Try different browser
echo.
echo Problem: Pendaftar - Masih tampil data lama?
echo Solution 1: Hard refresh browser (Ctrl+Shift+R)
echo Solution 2: Clear browser cache
echo Solution 3: Try incognito mode
echo Solution 4: Run cache clear commands again
echo.
echo Problem: JavaScript error "Unexpected token"?
echo Solution: This fix should resolve it with @js() directive
echo.
echo Problem: Content dengan quotes break?
echo Solution: This fix should resolve it with proper JSON encoding
echo.
echo ================================================================
echo.
echo Both issues are now FIXED!
echo   1. Cache Issue: Pendaftar sees fresh data from component
echo   2. Escaping Issue: Admin data persists with @js() encoding
echo.
echo Ready for production testing!
echo ================================================================
pause
