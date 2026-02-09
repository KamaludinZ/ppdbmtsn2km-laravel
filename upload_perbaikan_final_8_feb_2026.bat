@echo off
echo ========================================
echo Upload Perbaikan Final - 8 Feb 2026
echo ========================================
echo.
echo Files yang akan diupload:
echo 1. Fix Stats Data Disetujui (Admin & Panitia Kelulusan)
echo 2. Ubah Panitia-Seragam Settings menjadi READ-ONLY
echo.
echo Total: 3 files
echo.
pause

echo.
echo [1/3] Uploading Admin Kelulusan component...
scp "app/Livewire/Admin/Kelulusan.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload admin kelulusan component
    pause
    exit /b 1
)

echo.
echo [2/3] Uploading Panitia Kelulusan component...
scp "app/Livewire/Panitia/Kelulusan.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload panitia kelulusan component
    pause
    exit /b 1
)

echo.
echo [3/3] Uploading Panitia-Seragam Settings tab (READ-ONLY)...
scp "resources/views/livewire/panitia-seragam/partials/settings-tab.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia-seragam/partials/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload panitia-seragam settings tab
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
echo All 3 files have been uploaded to production.
echo Cache has been cleared.
echo.
echo Please verify the following features:
echo - Stats Data Disetujui di /admin/kelulusan (harus tampil TOTAL tanpa filter)
echo - Stats Data Disetujui di /panitia/kelulusan (harus tampil TOTAL tanpa filter)
echo - Settings di /panitia-seragam/daftar-ulang?activeTab=settings (READ-ONLY)
echo.
echo Testing Instructions:
echo 1. Login Admin -> Kelulusan -> Cek stats "Data Disetujui"
echo 2. Filter by search/jalur -> Stats TIDAK berubah
echo 3. Login Panitia Seragam -> Daftar Ulang -> Tab Settings
echo 4. Verify: Info box muncul "Hanya Lihat"
echo 5. Verify: Data dari Admin muncul dengan benar
echo 6. Verify: Tidak ada input/button untuk edit
echo.
pause
