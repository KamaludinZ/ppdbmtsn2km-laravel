@echo off
echo ========================================
echo 🔴 CRITICAL FIX: Tombol Simpan Seragam
echo 8 Februari 2026
echo ========================================
echo.
echo BUG: Tombol "Simpan Data Seragam" masih terlihat saat status menunggu
echo FIX: Perbaiki struktur conditional block (@else)
echo.
echo ========================================
echo.

echo [INFO] Uploading file to production server...
echo.

echo [1/3] Uploading daftar-ulang.blade.php...
scp "resources\views\livewire\pendaftar\daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/

if errorlevel 1 (
    echo [ERROR] Failed to upload daftar-ulang.blade.php
    pause
    exit /b 1
)

echo [SUCCESS] File uploaded successfully!
echo.

echo [2/3] Clearing view cache...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear"

if errorlevel 1 (
    echo [ERROR] Failed to clear view cache
    pause
    exit /b 1
)

echo [SUCCESS] View cache cleared!
echo.

echo [3/3] Running artisan optimize:clear...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"

if errorlevel 1 (
    echo [ERROR] Failed to run optimize:clear
    pause
    exit /b 1
)

echo.
echo ========================================
echo ✅ CRITICAL FIX DEPLOYED SUCCESSFULLY!
echo ========================================
echo.
echo ROOT CAUSE:
echo - @endif at line 1974 closed @else block too early
echo - Tombol Simpan ended up OUTSIDE @else block
echo - Result: Button visible for ALL statuses (including menunggu)
echo.
echo FIX APPLIED:
echo - Moved Tombol Simpan INSIDE @else block
echo - Added proper @endif to close nested conditionals
echo - Removed unnecessary $isSeragamApproved logic
echo.
echo EXPECTED BEHAVIOR NOW:
echo ✅ Status 'menunggu' → Tombol Simpan HIDDEN
echo ✅ Status 'disetujui' → Tombol Simpan HIDDEN
echo ✅ Status 'ditolak' → Tombol Simpan VISIBLE
echo ✅ Status 'perbaiki' → Tombol Simpan VISIBLE
echo ✅ Status null → Tombol Simpan VISIBLE
echo.
echo ========================================
echo 🧪 TESTING REQUIRED
echo ========================================
echo.
echo Please verify at:
echo https://pmbm.mtsn2kotamalang.sch.id/pendaftar/daftarulang?activeTab=seragam
echo.
echo Test with status = 'menunggu':
echo 1. Hard refresh browser: Ctrl + Shift + R
echo 2. Verify tombol "Simpan Data Seragam" is NOT visible
echo 3. Verify yellow status card is visible
echo 4. Verify "Data yang Telah Dikirim" section is visible
echo.
echo If still visible:
echo - Clear browser cache completely
echo - Try different browser
echo - Check database: status_seragam must be exactly 'menunggu'
echo.
pause
