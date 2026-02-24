@echo off
echo ========================================
echo UPLOAD FINAL COMPLETE - 21 FEBRUARI 2026
echo (PAKET LAINNYA + LINK GRUP DAFTAR ULANG + MIGRATIONS)
echo ========================================
echo.
echo Total Files: 10 unique files
echo - 7 PHP files
echo - 3 Migration files
echo.
echo FEATURES:
echo 1. Paket Lainnya (Tab Madrasah + Seragam)
echo 2. Link Grup WhatsApp Daftar Ulang
echo.
echo Files to upload:
echo.
echo PAKET LAINNYA (5 files):
echo 1. resources/views/livewire/pendaftar/daftar-ulang.blade.php
echo 2. app/Livewire/Pendaftar/DaftarUlang.php
echo 3. app/Exports/DaftarUlangMadrasahSheet.php
echo 4. app/Exports/DaftarUlangSeragamSheet.php
echo 5. app/Exports/DaftarUlangUkuranSheet.php
echo.
echo MIGRATIONS (3 files):
echo 6. database/migrations/2026_02_09_100000_add_individual_opsi_lainnya_to_daftar_ulang_and_seragam.php
echo 7. database/migrations/2026_02_21_100000_change_paket_seragam_to_text.php
echo 8. database/migrations/2026_02_21_210000_add_belum_diisi_to_seragam_status_enum.php
echo.
echo LINK GRUP DAFTAR ULANG (2 files + 1 shared):
echo 9. app/Livewire/Admin/PengaturanKartu.php
echo 10. resources/views/livewire/admin/pengaturan-kartu.blade.php
echo #1 (daftar-ulang.blade.php also includes link grup button)
echo.
echo AFTER UPLOAD, YOU MUST:
echo - Run migrations: php artisan migrate
echo - Clear cache: php artisan optimize:clear
echo.
pause
echo.

echo ========================================
echo STEP 1: Uploading PAKET LAINNYA Files (5 files)
echo ========================================
echo.

echo [1/5] Uploading daftar-ulang.blade.php (PAKET LAINNYA + LINK GRUP)...
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
echo STEP 2: Uploading MIGRATION Files (3 files)
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
echo STEP 3: Uploading LINK GRUP DAFTAR ULANG Files (2 files)
echo ========================================
echo.

echo [1/2] Uploading PengaturanKartu.php...
scp "app/Livewire/Admin/PengaturanKartu.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
if errorlevel 1 (
    echo ERROR: Failed to upload PengaturanKartu.php
    pause
    exit /b 1
)
echo SUCCESS: PengaturanKartu.php uploaded
echo.

echo [2/2] Uploading pengaturan-kartu.blade.php...
scp "resources/views/livewire/admin/pengaturan-kartu.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
if errorlevel 1 (
    echo ERROR: Failed to upload pengaturan-kartu.blade.php
    pause
    exit /b 1
)
echo SUCCESS: pengaturan-kartu.blade.php uploaded
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
echo === PAKET LAINNYA ===
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
echo === LINK GRUP DAFTAR ULANG ===
echo.
echo 6. Admin Settings
echo    URL: /admin/pengaturan-kartu?activeTab=link-grup
echo    - Scroll ke bagian "Link Grup WhatsApp/Telegram - Khusus Daftar Ulang"
echo    - Aktifkan toggle
echo    - Isi URL: https://chat.whatsapp.com/TEST123
echo    - Simpan
echo    - Preview tombol harus MUNCUL
echo.
echo 7. Pendaftar - Tombol Bergabung
echo    URL: /pendaftar/daftarulang?activeTab=madrasah
echo    - Status madrasah harus = 'disetujui' (panitia approve dulu)
echo    - Tombol "Bergabung ke Grup WhatsApp" harus MUNCUL
echo    - Klik tombol - buka di tab baru
echo    - Tombol TIDAK muncul di Tab Seragam/Ukuran
echo.
echo Full documentation:
echo - ADD_LINK_GRUP_DAFTAR_ULANG_21_FEB_2026.md
echo - RANGKUMAN_FINAL_LENGKAP_21_FEB_2026.md
echo.
pause
