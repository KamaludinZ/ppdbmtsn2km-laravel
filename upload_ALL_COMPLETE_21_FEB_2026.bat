@echo off
echo ========================================
echo UPLOAD COMPLETE - 21 FEBRUARI 2026
echo (INCLUDING DATABASE MIGRATIONS!)
echo ========================================
echo.
echo Files to upload:
echo.
echo PHP FILES (5):
echo 1. resources/views/livewire/pendaftar/daftar-ulang.blade.php
echo 2. app/Livewire/Pendaftar/DaftarUlang.php
echo 3. app/Exports/DaftarUlangMadrasahSheet.php
echo 4. app/Exports/DaftarUlangSeragamSheet.php
echo 5. app/Exports/DaftarUlangUkuranSheet.php
echo.
echo MIGRATION FILES (3):
echo 6. database/migrations/2026_02_09_100000_add_individual_opsi_lainnya_to_daftar_ulang_and_seragam.php
echo 7. database/migrations/2026_02_21_100000_change_paket_seragam_to_text.php
echo 8. database/migrations/2026_02_21_210000_add_belum_diisi_to_seragam_status_enum.php
echo.
echo AFTER UPLOAD, YOU MUST:
echo - Run migrations: php artisan migrate
echo - Clear cache: php artisan optimize:clear
echo.
pause
echo.

echo ========================================
echo STEP 1: Uploading PHP Files (5 files)
echo ========================================
echo.

echo [1/5] Uploading daftar-ulang.blade.php...
scp "resources/views/livewire/pendaftar/daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if errorlevel 1 (
    echo ERROR: Failed to upload daftar-ulang.blade.php
    pause
    exit /b 1
)
echo SUCCESS: daftar-ulang.blade.php uploaded
echo.

echo [2/5] Uploading DaftarUlang.php...
scp "app/Livewire/Pendaftar/DaftarUlang.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if errorlevel 1 (
    echo ERROR: Failed to upload DaftarUlang.php
    pause
    exit /b 1
)
echo SUCCESS: DaftarUlang.php uploaded
echo.

echo [3/5] Uploading DaftarUlangMadrasahSheet.php...
scp "app/Exports/DaftarUlangMadrasahSheet.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/
if errorlevel 1 (
    echo ERROR: Failed to upload DaftarUlangMadrasahSheet.php
    pause
    exit /b 1
)
echo SUCCESS: DaftarUlangMadrasahSheet.php uploaded
echo.

echo [4/5] Uploading DaftarUlangSeragamSheet.php...
scp "app/Exports/DaftarUlangSeragamSheet.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/
if errorlevel 1 (
    echo ERROR: Failed to upload DaftarUlangSeragamSheet.php
    pause
    exit /b 1
)
echo SUCCESS: DaftarUlangSeragamSheet.php uploaded
echo.

echo [5/5] Uploading DaftarUlangUkuranSheet.php...
scp "app/Exports/DaftarUlangUkuranSheet.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/
if errorlevel 1 (
    echo ERROR: Failed to upload DaftarUlangUkuranSheet.php
    pause
    exit /b 1
)
echo SUCCESS: DaftarUlangUkuranSheet.php uploaded
echo.

echo ========================================
echo STEP 2: Uploading Migration Files (3 files)
echo ========================================
echo.

echo [1/3] Uploading add_individual_opsi_lainnya...
scp "database/migrations/2026_02_09_100000_add_individual_opsi_lainnya_to_daftar_ulang_and_seragam.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/
if errorlevel 1 (
    echo ERROR: Failed to upload migration 1
    pause
    exit /b 1
)
echo SUCCESS: Migration 1 uploaded
echo.

echo [2/3] Uploading change_paket_seragam_to_text...
scp "database/migrations/2026_02_21_100000_change_paket_seragam_to_text.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/
if errorlevel 1 (
    echo ERROR: Failed to upload migration 2
    pause
    exit /b 1
)
echo SUCCESS: Migration 2 uploaded
echo.

echo [3/3] Uploading add_belum_diisi_to_seragam_status_enum...
scp "database/migrations/2026_02_21_210000_add_belum_diisi_to_seragam_status_enum.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/
if errorlevel 1 (
    echo ERROR: Failed to upload migration 3
    pause
    exit /b 1
)
echo SUCCESS: Migration 3 uploaded
echo.

echo ========================================
echo ALL FILES UPLOADED SUCCESSFULLY!
echo ========================================
echo.
echo ========================================
echo CRITICAL: YOU MUST RUN MIGRATIONS NOW!
echo ========================================
echo.
echo Opening SSH connection to run migrations...
echo.
pause
echo.

ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan migrate && php artisan optimize:clear && php artisan view:clear && php artisan config:clear && php artisan cache:clear"

if errorlevel 1 (
    echo.
    echo ========================================
    echo WARNING: Migration or cache clear failed!
    echo ========================================
    echo.
    echo Please run manually via SSH:
    echo 1. ssh mtsnkot4@103.30.244.77
    echo 2. cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
    echo 3. php artisan migrate
    echo 4. php artisan optimize:clear
    echo.
    pause
    exit /b 1
) else (
    echo.
    echo ========================================
    echo DEPLOYMENT COMPLETE!
    echo ========================================
    echo.
    echo Migrations ran successfully
    echo Cache cleared successfully
    echo.
)

echo ========================================
echo NEXT STEPS - TESTING
echo ========================================
echo.
echo Please test the following:
echo.
echo 1. Tab Madrasah - Paket Lainnya
echo    URL: /pendaftar/daftarulang?activeTab=madrasah
echo    - Pilih "Paket Lainnya (Input Manual)"
echo    - Section konfirmasi harus MUNCUL
echo    - Data harus bisa DISIMPAN
echo.
echo 2. Tab Seragam - Paket Lainnya
echo    URL: /pendaftar/daftarulang?activeTab=seragam
echo    - Sama seperti Tab Madrasah
echo.
echo 3. Export Excel Madrasah
echo    - Check kolom: "Opsi yang Dipilih", "Nominal Opsi"
echo.
echo 4. Export Excel Seragam
echo    - Check "Paket Seragam" readable (bukan JSON)
echo    - Multi-select dengan " + " separator
echo.
echo 5. Export Excel Ukuran
echo    - Check "Paket Seragam" readable
echo.
echo Full testing checklist: RANGKUMAN_FINAL_LENGKAP_21_FEB_2026.md
echo.
pause
