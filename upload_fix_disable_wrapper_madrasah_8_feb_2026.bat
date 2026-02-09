@echo off
echo ========================================
echo Fix: Remove Disable Wrapper Madrasah
echo 8 Februari 2026
echo ========================================
echo.
echo BUG: Action buttons (Lihat Surat Pernyataan, Lihat Bukti, Download Invoice)
echo      terlihat grayed out dan tidak bisa diklik saat status disetujui
echo FIX: Remove redundant disable wrapper - gunakan progressive disclosure only
echo.
echo ========================================
echo.

echo [INFO] Uploading file to production server...
echo.

echo [1/2] Uploading daftar-ulang.blade.php...
scp "resources\views\livewire\pendaftar\daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/

if errorlevel 1 (
    echo [ERROR] Failed to upload daftar-ulang.blade.php
    pause
    exit /b 1
)

echo [SUCCESS] File uploaded successfully!
echo.

echo [2/2] Running artisan optimize:clear...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"

if errorlevel 1 (
    echo [ERROR] Failed to run optimize:clear
    pause
    exit /b 1
)

echo.
echo ========================================
echo ✅ FIX DEPLOYED SUCCESSFULLY!
echo ========================================
echo.
echo ROOT CAUSE:
echo - Wrapper div with pointer-events-none opacity-60 at line 373
echo - Wrapper contained EVERYTHING (status display + form)
echo - Action buttons affected by disable CSS
echo.
echo FIX APPLIED:
echo - Removed opening wrapper div (line 373)
echo - Removed closing wrapper div (line 1194)
echo - Action buttons now free from disable effect
echo.
echo EXPECTED BEHAVIOR NOW:
echo ✅ Status 'disetujui' → Action buttons FULLY FUNCTIONAL
echo ✅ Lihat Surat Pernyataan → Normal appearance, clickable
echo ✅ Lihat Bukti Pembayaran → Normal appearance, clickable
echo ✅ Download Invoice → Normal appearance, clickable
echo ✅ Hover effects working on all buttons
echo ✅ No graying out (opacity-60) on buttons
echo.
echo ========================================
echo 🧪 TESTING REQUIRED
echo ========================================
echo.
echo Please verify at:
echo https://pmbm.mtsn2kotamalang.sch.id/pendaftar/daftarulang?activeTab=madrasah
echo.
echo Test with status = 'disetujui':
echo 1. Hard refresh browser: Ctrl + Shift + R
echo 2. Verify all action buttons have normal appearance (not grayed)
echo 3. Verify hover effects working (scale, color change)
echo 4. Verify buttons are clickable
echo 5. Test clicking each button:
echo    - Lihat Surat Pernyataan → Opens PDF in new tab
echo    - Lihat Bukti Pembayaran → Opens image in new tab
echo    - Download Invoice → Downloads PDF file
echo.
echo If buttons still grayed out:
echo - Clear browser cache completely
echo - Try different browser
echo - Check database: status must be exactly 'disetujui'
echo.
pause
