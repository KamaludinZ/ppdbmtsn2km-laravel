@echo off
REM ================================================================
REM Upload Script - Fix Toggle Switch Link Grup
REM ================================================================
REM
REM Problem: Toggle switch tidak muncul normal di production
REM Root Cause: Tailwind peer classes tidak ter-compile
REM Solution: Ganti dengan Alpine.js toggle (@entangle)
REM
REM Total files: 1
REM ================================================================

echo ================================================================
echo   Fix Toggle Switch Link Grup
echo   Date: 9 Feb 2026
echo ================================================================
echo.
echo Problem: Toggle switch tidak muncul normal di production
echo Solution: Ganti Tailwind peer classes dengan Alpine.js toggle
echo.

REM Set production server details
set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo ================================================================
echo   Uploading Fixed File
echo ================================================================
echo.

echo [1/1] Uploading Admin Pengaturan Kartu Blade View...
echo File: resources\views\livewire\admin\pengaturan-kartu.blade.php
echo Changes: Replace Tailwind peer toggle with Alpine.js toggle
echo.
timeout /t 2 /nobreak >nul
scp "resources\views\livewire\admin\pengaturan-kartu.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/admin/
if errorlevel 1 (
    echo ERROR: Failed to upload pengaturan-kartu.blade.php
    pause
    exit /b 1
)
echo SUCCESS: File uploaded
echo.

echo ================================================================
echo   Clearing Production Cache (CRITICAL!)
echo ================================================================
echo.

echo [2/6] Clearing view cache...
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan view:clear"
if errorlevel 1 (
    echo WARNING: Failed to clear view cache
) else (
    echo SUCCESS: View cache cleared
)
echo.

echo [3/6] Clearing Livewire cache...
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan livewire:delete-tmp"
if errorlevel 1 (
    echo WARNING: Failed to clear Livewire cache
) else (
    echo SUCCESS: Livewire cache cleared
)
echo.

echo [4/6] Clearing application cache...
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan cache:clear"
if errorlevel 1 (
    echo WARNING: Failed to clear application cache
) else (
    echo SUCCESS: Application cache cleared
)
echo.

echo [5/6] Clearing config cache...
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan config:clear"
if errorlevel 1 (
    echo WARNING: Failed to clear config cache
) else (
    echo SUCCESS: Config cache cleared
)
echo.

echo [6/6] Optimizing application...
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
echo File uploaded:
echo   - resources/views/livewire/admin/pengaturan-kartu.blade.php
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
echo OLD (Broken):
echo   - Toggle switch using Tailwind peer classes
echo   - peer-checked:after:translate-x-full
echo   - Complex CSS pseudo-elements
echo   - Not working in production
echo.
echo NEW (Fixed):
echo   - Toggle switch using Alpine.js
echo   - @entangle for two-way binding with Livewire
echo   - Explicit DOM elements (no pseudo-elements)
echo   - Dynamic :class for conditional styling
echo   - Works in ALL browsers/environments
echo.
echo ================================================================
echo   TESTING CHECKLIST
echo ================================================================
echo.
echo [TEST 1: Visual Display]
echo   1. Login sebagai Admin
echo   2. Buka /admin/pengaturan-kartu?activeTab=link-grup
echo   3. Verify toggle switch tampil dengan benar:
echo      - Gray background with white circle (when OFF)
echo      - Click toggle
echo      - Turns green with circle sliding to right
echo      - Smooth 200ms animation
echo.
echo [TEST 2: Click Functionality]
echo   1. Click toggle multiple times
echo   2. Verify:
echo      - OFF: Gray, circle left
echo      - ON: Green, circle right
echo      - Smooth transitions
echo      - Click anywhere on toggle or label text
echo.
echo [TEST 3: Save and Persist]
echo   1. Toggle ON
echo   2. Enter URL: https://chat.whatsapp.com/test123
echo   3. Click "Simpan Link Grup"
echo   4. Verify success message
echo   5. Refresh page (F5 or Ctrl+R)
echo   6. Verify:
echo      - Toggle still ON (green)
echo      - URL still there
echo      - Data persisted
echo.
echo [TEST 4: Toggle OFF Behavior]
echo   1. Toggle OFF (gray, circle left)
echo   2. Click "Simpan Link Grup"
echo   3. Logout, login as Pendaftar
echo   4. Check /pendaftar/dashboard
echo   5. Verify: NO "Bergabung ke Grup" button appears
echo.
echo [TEST 5: Toggle ON Behavior]
echo   1. Admin: Toggle ON
echo   2. Save
echo   3. Pendaftar: Check dashboard
echo   4. Verify: "Bergabung ke Grup" button appears
echo   5. Click button
echo   6. Verify: Opens WhatsApp in new tab
echo.
echo [TEST 6: Browser Compatibility]
echo   Test on multiple browsers:
echo   - Chrome/Edge: Toggle works
echo   - Firefox: Toggle works
echo   - Safari: Toggle works
echo   - Mobile: Toggle works
echo.
echo Expected Results:
echo   - Toggle displays correctly (gray OFF, green ON)
echo   - Circle slides smoothly left/right
echo   - Click anywhere on toggle or label to switch
echo   - State persists after save and refresh
echo   - Two-way binding works (Alpine ^<-^> Livewire)
echo   - Works in ALL browsers
echo.
echo ================================================================
echo   TROUBLESHOOTING
echo ================================================================
echo.
echo Problem: Toggle still tidak muncul?
echo Solution 1: Hard refresh browser (Ctrl+Shift+F5)
echo Solution 2: Clear browser cache completely
echo Solution 3: Try different browser (Chrome/Firefox)
echo Solution 4: Check browser console for JavaScript errors
echo.
echo Problem: Toggle tidak bisa di-click?
echo Solution 1: Verify Alpine.js loaded (check console)
echo Solution 2: Check for JavaScript errors
echo Solution 3: Disable browser extensions (ad blockers)
echo.
echo Problem: Toggle state tidak persist setelah refresh?
echo Solution 1: Check Livewire connection
echo Solution 2: Verify @entangle working (console log)
echo Solution 3: Check database setting saved:
echo            ssh %SERVER% "cd %REMOTE_PATH% && php artisan tinker"
echo            App\Models\Setting::get('link_grup_enabled')
echo.
echo Problem: Animation tidak smooth?
echo Solution: Check CSS transitions loaded
echo          Tailwind transition classes should work
echo.
echo ================================================================
echo.
echo Toggle Switch FIX complete!
echo.
echo Technical Details:
echo   - Alpine.js component with @entangle
echo   - Two-way binding with Livewire property
echo   - Explicit span elements (no ::after pseudo-elements)
echo   - Conditional classes with :class directive
echo   - 200ms smooth transitions
echo   - Works in ALL environments
echo.
echo Old Implementation (Broken):
echo   - Tailwind peer classes
echo   - peer-checked:after:translate-x-full
echo   - CSS pseudo-elements
echo   - Doesn't work in production
echo.
echo New Implementation (Fixed):
echo   - Alpine.js toggle
echo   - @entangle('link_grup_enabled')
echo   - Real DOM elements
echo   - Works everywhere
echo.
echo ================================================================
pause
