@echo off
echo ========================================
echo Fix: Opsi Lainnya Setting Key
echo 8 Februari 2026
echo ========================================
echo.
echo FIX: Setting key mismatch untuk Opsi Lainnya
echo - Code mencari: daftar_ulang_madrasah_payment_enable_opsi_lainnya
echo - Database punya: daftar_ulang_madrasah_enable_opsi_lainnya
echo - Updated code untuk gunakan setting key yang benar
echo.
echo IMPACT: "Opsi Lainnya" sekarang muncul saat setting diaktifkan
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
echo CHANGE APPLIED:
echo.
echo Line 820 - Setting key updated:
echo   FROM: 'daftar_ulang_madrasah_payment_enable_opsi_lainnya'
echo   TO:   'daftar_ulang_madrasah_enable_opsi_lainnya'
echo.
echo RESULT:
echo   - Code sekarang pakai setting key yang benar ✅
echo   - "Opsi Lainnya" muncul saat setting = 1 ✅
echo   - User bisa input nominal custom ✅
echo.
echo ========================================
echo 🧪 TESTING REQUIRED
echo ========================================
echo.
echo URL: https://pmbm.mtsn2kotamalang.sch.id/pendaftar/daftarulang?activeTab=madrasah
echo.
echo Test Steps:
echo 1. Hard refresh: Ctrl + Shift + R
echo 2. Select paket dari dropdown
echo 3. Select opsi (radio button)
echo 4. In "Konfirmasi Nominal", verify DUA opsi muncul:
echo    - ● Bayar Penuh (GREEN)
echo    - ○ Opsi Lainnya (YELLOW) ✅ Should appear now!
echo 5. Click "Opsi Lainnya"
echo 6. Verify manual input field muncul
echo 7. Enter custom amount (e.g., 250000)
echo 8. Should accept the value
echo.
echo ========================================
echo 📝 RELATED FIXES
echo ========================================
echo.
echo This session (8 Feb 2026):
echo 1. ✅ Fix undefined variable $paketMadrasah
echo 2. ✅ Match Step 2 styling dengan Step 1
echo 3. ✅ Fix orphaned div tags (footer broken)
echo 4. ✅ Fix Opsi Lainnya setting key (THIS FIX)
echo.
echo All changes in 1 file: daftar-ulang.blade.php
echo.
pause
