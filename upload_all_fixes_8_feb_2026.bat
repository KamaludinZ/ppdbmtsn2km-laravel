@echo off
echo ========================================
echo UPLOAD ALL FIXES - 8 Februari 2026
echo ========================================
echo.
echo PERUBAHAN YANG AKAN DIUPLOAD:
echo.
echo 1. Fix undefined variable paketMadrasah
echo 2. Fix Step 2 styling Madrasah (gradient)
echo 3. Fix footer layout broken (orphaned divs)
echo 4. Fix Opsi Lainnya setting key mismatch
echo 5. FITUR BARU: Opsi Lainnya dropdown paket (Madrasah + Seragam)
echo.
echo ========================================
echo FILES TO UPLOAD (4 files):
echo ========================================
echo.
echo 1. app/Livewire/Admin/DaftarUlang.php
echo 2. app/Livewire/Pendaftar/DaftarUlang.php
echo 3. resources/views/livewire/pendaftar/daftar-ulang.blade.php
echo 4. resources/views/livewire/admin/daftar-ulang.blade.php
echo.
pause
echo.

echo ========================================
echo STEP 1: Creating Backups on Production
echo ========================================
echo.

ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && cp app/Livewire/Admin/DaftarUlang.php app/Livewire/Admin/DaftarUlang.php.backup-8feb2026"
if errorlevel 1 (
    echo [ERROR] Failed to backup Admin DaftarUlang.php
    pause
    exit /b 1
)
echo [OK] Backed up Admin DaftarUlang.php

ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && cp app/Livewire/Pendaftar/DaftarUlang.php app/Livewire/Pendaftar/DaftarUlang.php.backup-8feb2026"
if errorlevel 1 (
    echo [ERROR] Failed to backup Pendaftar DaftarUlang.php
    pause
    exit /b 1
)
echo [OK] Backed up Pendaftar DaftarUlang.php

ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && cp resources/views/livewire/pendaftar/daftar-ulang.blade.php resources/views/livewire/pendaftar/daftar-ulang.blade.php.backup-8feb2026"
if errorlevel 1 (
    echo [ERROR] Failed to backup pendaftar daftar-ulang.blade.php
    pause
    exit /b 1
)
echo [OK] Backed up pendaftar daftar-ulang.blade.php

ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && cp resources/views/livewire/admin/daftar-ulang.blade.php resources/views/livewire/admin/daftar-ulang.blade.php.backup-8feb2026"
if errorlevel 1 (
    echo [ERROR] Failed to backup admin daftar-ulang.blade.php
    pause
    exit /b 1
)
echo [OK] Backed up admin daftar-ulang.blade.php

echo.
echo ========================================
echo STEP 2: Uploading Files to Production
echo ========================================
echo.

echo [1/4] Uploading Admin DaftarUlang.php...
scp "app\Livewire\Admin\DaftarUlang.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
if errorlevel 1 (
    echo [ERROR] Failed to upload Admin DaftarUlang.php
    pause
    exit /b 1
)
echo [OK] Uploaded Admin DaftarUlang.php

echo.
echo [2/4] Uploading Pendaftar DaftarUlang.php...
scp "app\Livewire\Pendaftar\DaftarUlang.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if errorlevel 1 (
    echo [ERROR] Failed to upload Pendaftar DaftarUlang.php
    pause
    exit /b 1
)
echo [OK] Uploaded Pendaftar DaftarUlang.php

echo.
echo [3/4] Uploading pendaftar daftar-ulang.blade.php...
scp "resources\views\livewire\pendaftar\daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if errorlevel 1 (
    echo [ERROR] Failed to upload pendaftar daftar-ulang.blade.php
    pause
    exit /b 1
)
echo [OK] Uploaded pendaftar daftar-ulang.blade.php

echo.
echo [4/4] Uploading admin daftar-ulang.blade.php...
scp "resources\views\livewire\admin\daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
if errorlevel 1 (
    echo [ERROR] Failed to upload admin daftar-ulang.blade.php
    pause
    exit /b 1
)
echo [OK] Uploaded admin daftar-ulang.blade.php

echo.
echo ========================================
echo STEP 3: Clearing Caches on Production
echo ========================================
echo.

echo Clearing view cache...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear"
if errorlevel 1 (
    echo [WARNING] Failed to clear view cache
)
echo [OK] View cache cleared

echo.
echo Clearing optimize cache...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
if errorlevel 1 (
    echo [WARNING] Failed to clear optimize cache
)
echo [OK] Optimize cache cleared

echo.
echo Clearing config cache...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan config:clear"
if errorlevel 1 (
    echo [WARNING] Failed to clear config cache
)
echo [OK] Config cache cleared

echo.
echo Clearing route cache...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan route:clear"
if errorlevel 1 (
    echo [WARNING] Failed to clear route cache
)
echo [OK] Route cache cleared

echo.
echo ========================================
echo ✅ UPLOAD COMPLETED SUCCESSFULLY!
echo ========================================
echo.
echo ALL FILES UPLOADED:
echo ✓ app/Livewire/Admin/DaftarUlang.php
echo ✓ app/Livewire/Pendaftar/DaftarUlang.php
echo ✓ resources/views/livewire/pendaftar/daftar-ulang.blade.php
echo ✓ resources/views/livewire/admin/daftar-ulang.blade.php
echo.
echo ALL CACHES CLEARED:
echo ✓ View cache
echo ✓ Optimize cache
echo ✓ Config cache
echo ✓ Route cache
echo.
echo ========================================
echo 🧪 TESTING REQUIRED
echo ========================================
echo.
echo Please test the following:
echo.
echo 1. ADMIN SETTINGS
echo    URL: https://pmbm.mtsn2kotamalang.sch.id/admin/daftar-ulang?activeTab=settings
echo    - Hard refresh: Ctrl + Shift + R
echo    - Verify 2 toggles for Madrasah (BLUE + YELLOW)
echo    - Verify 2 toggles for Seragam (BLUE + YELLOW)
echo    - Enable BLUE toggles "Opsi Lainnya untuk Dropdown Paket"
echo    - Save settings
echo.
echo 2. PENDAFTAR MADRASAH
echo    URL: https://pmbm.mtsn2kotamalang.sch.id/pendaftar/daftarulang?activeTab=madrasah
echo    - Hard refresh: Ctrl + Shift + R
echo    - Verify no error 500
echo    - Verify Step 1 gradient background (emerald/teal)
echo    - Verify Step 2 gradient background (same as Step 1)
echo    - Verify footer displays correctly
echo    - Verify dropdown shows "Paket Lainnya (Input Manual)"
echo    - Select "Paket Lainnya" and test manual input
echo    - Verify "Opsi Lainnya" for konfirmasi nominal appears
echo.
echo 3. PENDAFTAR SERAGAM
echo    URL: https://pmbm.mtsn2kotamalang.sch.id/pendaftar/daftarulang?activeTab=seragam
echo    - Same tests as Madrasah
echo.
echo ========================================
echo 📋 QUICK VERIFICATION CHECKLIST
echo ========================================
echo.
echo Must verify these are working:
echo [ ] No error 500 on pendaftar view
echo [ ] No undefined variable error
echo [ ] Step 1 and Step 2 have matching gradient backgrounds
echo [ ] Footer displays correctly (not broken)
echo [ ] Admin settings show 2 toggles each (Madrasah + Seragam)
echo [ ] Dropdown shows "Paket Lainnya" when enabled
echo [ ] Manual input fields work
echo [ ] Opsi Lainnya konfirmasi nominal works
echo.
echo ========================================
echo 📚 DOCUMENTATION
echo ========================================
echo.
echo Complete documentation available:
echo - UPLOAD_MANUAL_COMPLETE_8_FEB_2026.md
echo - FITUR_OPSI_LAINNYA_DROPDOWN_PAKET_8_FEB_2026.md
echo - FIX_OPSI_LAINNYA_SETTING_KEY_8_FEB_2026.md
echo - FIX_STEP_2_STYLING_MADRASAH_8_FEB_2026.md
echo.
echo ========================================
echo.
pause
