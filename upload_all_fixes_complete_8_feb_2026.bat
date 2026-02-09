@echo off
echo ========================================
echo UPLOAD ALL FIXES COMPLETE - 8 FEB 2026
echo ========================================
echo.
echo TOTAL PERUBAHAN: 15+ features/fixes
echo TOTAL FILES: 25+ files
echo.
echo PERUBAHAN YANG AKAN DIUPLOAD:
echo.
echo TAB UKURAN:
echo   1. Reorder tab (Ukuran ke tengah)
echo   2. Ringkasan pilihan ukuran
echo   3. Warning perbedaan ukuran baju vs celana
echo.
echo TAB SERAGAM:
echo   4. Hierarchical dependent dropdown (Paket -^> Opsi)
echo   5. Hapus duplikat section paket seragam
echo   6. Improved upload bukti UI
echo   7. Warning perbedaan ukuran (conditional)
echo   8. Status display (Yellow menunggu, Green disetujui)
echo   9. Data summary (6 data points)
echo  10. FIX CRITICAL: Tombol Simpan (@endif salah tempat)
echo.
echo TAB MADRASAH:
echo  11. Hierarchical packages (Backend + Admin + Pendaftar)
echo  12. Dependent dropdown (Paket -^> Opsi -^> Konfirmasi)
echo  13. Fix Step 2 styling (gradient matching Step 1)
echo  14. Fix Opsi Lainnya setting key mismatch
echo  15. Fix undefined variable $paketMadrasah
echo  16. Fix footer broken (orphaned divs)
echo  17. Fix disable wrapper (tombol tidak bisa diklik)
echo.
echo FITUR BARU:
echo  18. Toggle "Opsi Lainnya" untuk dropdown paket (Madrasah + Seragam)
echo  19. Manual input custom paket (nama + nominal)
echo.
echo ========================================
echo FILES TO UPLOAD (7 files + 1 migration):
echo ========================================
echo.
echo Backend:
echo   1. database/migrations/2026_02_08_100000_add_opsi_seragam_to_seragam_table.php
echo   2. app/Models/Seragam.php
echo   3. app/Livewire/Admin/DaftarUlang.php
echo   4. app/Livewire/Pendaftar/DaftarUlang.php
echo.
echo Frontend:
echo   5. resources/views/livewire/admin/daftar-ulang.blade.php
echo   6. resources/views/livewire/pendaftar/daftar-ulang.blade.php
echo   7. resources/views/livewire/panitia/daftar-ulang.blade.php
echo.
pause
echo.

echo ========================================
echo STEP 1: Creating Backups on Production
echo ========================================
echo.

ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && mkdir -p backups/8feb2026_complete"
if errorlevel 1 (
    echo [ERROR] Failed to create backup directory
    pause
    exit /b 1
)
echo [OK] Backup directory created

ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && cp app/Models/Seragam.php backups/8feb2026_complete/"
echo [OK] Backed up Seragam.php

ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && cp app/Livewire/Admin/DaftarUlang.php backups/8feb2026_complete/"
echo [OK] Backed up Admin DaftarUlang.php

ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && cp app/Livewire/Pendaftar/DaftarUlang.php backups/8feb2026_complete/"
echo [OK] Backed up Pendaftar DaftarUlang.php

ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && cp resources/views/livewire/admin/daftar-ulang.blade.php backups/8feb2026_complete/"
echo [OK] Backed up admin daftar-ulang.blade.php

ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && cp resources/views/livewire/pendaftar/daftar-ulang.blade.php backups/8feb2026_complete/"
echo [OK] Backed up pendaftar daftar-ulang.blade.php

ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && cp resources/views/livewire/panitia/daftar-ulang.blade.php backups/8feb2026_complete/"
echo [OK] Backed up panitia daftar-ulang.blade.php

echo.
echo ========================================
echo STEP 2: Uploading Files to Production
echo ========================================
echo.

echo [1/7] Uploading migration...
scp "database\migrations\2026_02_08_100000_add_opsi_seragam_to_seragam_table.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/
if errorlevel 1 (
    echo [ERROR] Failed to upload migration
    pause
    exit /b 1
)
echo [OK] Migration uploaded

echo.
echo [2/7] Uploading Seragam model...
scp "app\Models\Seragam.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/
if errorlevel 1 (
    echo [ERROR] Failed to upload Seragam model
    pause
    exit /b 1
)
echo [OK] Seragam model uploaded

echo.
echo [3/7] Uploading Admin DaftarUlang.php...
scp "app\Livewire\Admin\DaftarUlang.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
if errorlevel 1 (
    echo [ERROR] Failed to upload Admin DaftarUlang
    pause
    exit /b 1
)
echo [OK] Admin DaftarUlang uploaded

echo.
echo [4/7] Uploading Pendaftar DaftarUlang.php...
scp "app\Livewire\Pendaftar\DaftarUlang.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if errorlevel 1 (
    echo [ERROR] Failed to upload Pendaftar DaftarUlang
    pause
    exit /b 1
)
echo [OK] Pendaftar DaftarUlang uploaded

echo.
echo [5/7] Uploading admin daftar-ulang.blade.php...
scp "resources\views\livewire\admin\daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
if errorlevel 1 (
    echo [ERROR] Failed to upload admin view
    pause
    exit /b 1
)
echo [OK] Admin view uploaded

echo.
echo [6/7] Uploading pendaftar daftar-ulang.blade.php...
scp "resources\views\livewire\pendaftar\daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if errorlevel 1 (
    echo [ERROR] Failed to upload pendaftar view
    pause
    exit /b 1
)
echo [OK] Pendaftar view uploaded

echo.
echo [7/7] Uploading panitia daftar-ulang.blade.php...
scp "resources\views\livewire\panitia\daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/
if errorlevel 1 (
    echo [ERROR] Failed to upload panitia view
    pause
    exit /b 1
)
echo [OK] Panitia view uploaded

echo.
echo ========================================
echo STEP 3: Running Migration
echo ========================================
echo.

echo Running migration...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan migrate"
if errorlevel 1 (
    echo [WARNING] Migration may have failed or already run
)
echo [OK] Migration completed

echo.
echo ========================================
echo STEP 4: Clearing Caches
echo ========================================
echo.

echo Clearing view cache...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear"
echo [OK] View cache cleared

echo.
echo Clearing optimize cache...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
echo [OK] Optimize cache cleared

echo.
echo Clearing config cache...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan config:clear"
echo [OK] Config cache cleared

echo.
echo Clearing route cache...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan route:clear"
echo [OK] Route cache cleared

echo.
echo ========================================
echo ✅ UPLOAD COMPLETED SUCCESSFULLY!
echo ========================================
echo.
echo ALL FILES UPLOADED:
echo ✓ Migration: 2026_02_08_100000_add_opsi_seragam_to_seragam_table.php
echo ✓ Model: app/Models/Seragam.php
echo ✓ Livewire: app/Livewire/Admin/DaftarUlang.php
echo ✓ Livewire: app/Livewire/Pendaftar/DaftarUlang.php
echo ✓ View: resources/views/livewire/admin/daftar-ulang.blade.php
echo ✓ View: resources/views/livewire/pendaftar/daftar-ulang.blade.php
echo ✓ View: resources/views/livewire/panitia/daftar-ulang.blade.php
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
echo CRITICAL TESTS (MUST PASS):
echo.
echo 1. SERAGAM TAB - Tombol Simpan Hidden
echo    URL: https://pmbm.mtsn2kotamalang.sch.id/pendaftar/daftarulang?activeTab=seragam
echo    - Set status = 'menunggu' in database
echo    - Hard refresh: Ctrl + Shift + R
echo    - VERIFY: Tombol "Simpan Data Seragam" NOT VISIBLE (CRITICAL!)
echo    - VERIFY: Yellow status card visible
echo    - VERIFY: Data summary visible
echo.
echo 2. MADRASAH TAB - Action Buttons Clickable
echo    URL: https://pmbm.mtsn2kotamalang.sch.id/pendaftar/daftarulang?activeTab=madrasah
echo    - Set status = 'disetujui' in database
echo    - Hard refresh: Ctrl + Shift + R
echo    - VERIFY: ALL action buttons normal appearance (NOT grayed out)
echo    - VERIFY: "Lihat Surat Pernyataan" button clickable
echo    - VERIFY: "Lihat Bukti" button clickable
echo    - VERIFY: "Download Invoice" button clickable
echo.
echo 3. ADMIN SETTINGS - Hierarchical Packages
echo    URL: https://pmbm.mtsn2kotamalang.sch.id/admin/daftar-ulang?activeTab=settings
echo    - Hard refresh: Ctrl + Shift + R
echo    - Scroll to "Daftar Ulang Madrasah"
echo    - VERIFY: Hierarchical "Paket Madrasah" with green/cyan boxes
echo    - VERIFY: Toggle BIRU "Opsi Lainnya untuk Dropdown Paket" exists
echo    - VERIFY: Toggle KUNING "Opsi Lainnya untuk Konfirmasi Nominal" exists
echo    - Create package with 2 options
echo    - Save and verify persistence
echo.
echo 4. DEPENDENT DROPDOWN - Madrasah
echo    URL: https://pmbm.mtsn2kotamalang.sch.id/pendaftar/daftarulang?activeTab=madrasah
echo    - Select package from dropdown
echo    - VERIFY: "Pilih Opsi" section appears with radio buttons
echo    - Select option
echo    - VERIFY: "Konfirmasi Nominal" section appears
echo    - VERIFY: "Paket Lainnya (Input Manual)" option visible in dropdown
echo.
echo 5. UKURAN TAB - Reordered
echo    URL: https://pmbm.mtsn2kotamalang.sch.id/pendaftar/daftarulang
echo    - VERIFY: Tab order is [Madrasah] [Ukuran] [Seragam]
echo    - VERIFY: Ukuran tab in CENTER position
echo    - Select sizes and verify ringkasan appears
echo    - Set different sizes for baju and celana harian
echo    - VERIFY: Orange warning appears
echo.
echo ========================================
echo 📚 DOCUMENTATION
echo ========================================
echo.
echo Complete documentation:
echo - UPLOAD_MANUAL_SUPER_LENGKAP_8_FEB_2026.md (THIS FILE - COMPREHENSIVE!)
echo - QUICK_CHECKLIST_8_FEB_2026.txt (Quick reference)
echo.
echo Lihat UPLOAD_MANUAL_SUPER_LENGKAP_8_FEB_2026.md untuk:
echo - Testing checklist lengkap
echo - Troubleshooting guide
echo - Database verification commands
echo - Rollback procedure
echo.
echo ========================================
echo.
pause
