@echo off
echo ========================================
echo Upload Perbaikan Stats & Settings - 8 Feb 2026
echo ========================================
echo.
echo Files yang akan diupload:
echo 1. Fix Stats Data Disetujui (Admin & Panitia Kelulusan)
echo 2. Panitia-Seragam Settings: READ-ONLY ^=^> EDITABLE
echo 3. Panitia-Seragam Component: Add existing image properties
echo.
echo Total: 4 files
echo.
pause

echo.
echo [1/3] Uploading Admin Kelulusan component (stats fix)...
scp "app/Livewire/Admin/Kelulusan.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload admin kelulusan component
    pause
    exit /b 1
)

echo.
echo [2/3] Uploading Panitia Kelulusan component (stats fix)...
scp "app/Livewire/Panitia/Kelulusan.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload panitia kelulusan component
    pause
    exit /b 1
)

echo.
echo [3/4] Uploading Panitia-Seragam Settings tab (EDITABLE)...
scp "resources/views/livewire/panitia-seragam/partials/settings-tab.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia-seragam/partials/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload panitia-seragam settings tab
    pause
    exit /b 1
)

echo.
echo [4/4] Uploading Panitia-Seragam Component (existing image properties)...
scp "app/Livewire/PanitiaSeragam/DaftarUlang.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/PanitiaSeragam/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload panitia-seragam component
    pause
    exit /b 1
)

echo.
echo ========================================
echo Clear cache on production server...
echo ========================================
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
if %errorlevel% neq 0 (
    echo WARNING: Failed to clear cache. Please clear manually.
)

echo.
echo ========================================
echo UPLOAD COMPLETED SUCCESSFULLY!
echo ========================================
echo.
echo All 4 files have been uploaded to production.
echo Cache has been cleared.
echo.
echo ========================================
echo WHAT CHANGED?
echo ========================================
echo.
echo 1. STATS "DATA DISETUJUI" FIX:
echo    - Stats sekarang include SEMUA status yang sudah diverifikasi:
echo      * data_disetujui
echo      * lulus
echo      * diterima
echo      * tidak_lulus
echo      * cadangan
echo      * ditolak
echo    - Stats akan match dengan filter di /admin/pendaftar
echo    - Diterapkan di Admin dan Panitia Kelulusan
echo.
echo 2. PANITIA-SERAGAM SETTINGS (EDITABLE):
echo    - Settings TIDAK LAGI READ-ONLY
echo    - Sekarang Panitia Seragam BISA EDIT settings
echo    - Full CRUD untuk Paket Seragam dengan opsi pembayaran
echo    - Full CRUD untuk Ukuran Seragam (6 jenis) dengan image upload
echo    - Synchronized dengan Admin settings
echo.
echo ========================================
echo TESTING INSTRUCTIONS:
echo ========================================
echo.
echo Test Stats (5 minutes):
echo 1. Login Admin -^> /admin/kelulusan
echo 2. Cek angka "Data Disetujui" di stats
echo 3. Bandingkan dengan /admin/pendaftar (filter: data_disetujui)
echo 4. Angka harus SAMA (include lulus, tidak_lulus, cadangan, dll)
echo 5. Filter by search/jalur -^> Stats TIDAK berubah
echo.
echo 6. Login Panitia -^> /panitia/kelulusan
echo 7. Cek angka "Data Disetujui" di stats
echo 8. Angka harus SAMA dengan Admin Kelulusan
echo.
echo Test Panitia-Seragam Settings (5 minutes):
echo 1. Login Panitia Seragam -^> /panitia-seragam/daftar-ulang
echo 2. Klik tab "Pengaturan"
echo 3. Verify tampilan settings:
echo    - Ada form input (bukan READ-ONLY display)
echo    - Ada textarea untuk Informasi
echo    - Bisa tambah/hapus paket seragam
echo    - Bisa tambah/hapus opsi pembayaran
echo    - Bisa tambah/hapus ukuran (Jas, Baju Olahraga, dll)
echo    - Ada tombol "Simpan Pengaturan Seragam"
echo    - Ada tombol "Simpan Pengaturan Ukuran"
echo 4. Test edit settings:
echo    - Ubah sesuatu
echo    - Klik "Simpan"
echo    - Verify tersimpan (refresh page)
echo.
echo ========================================
pause
