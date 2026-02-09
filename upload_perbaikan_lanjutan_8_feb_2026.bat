@echo off
echo ========================================
echo Upload Perbaikan Lanjutan - 8 Feb 2026
echo ========================================
echo.
echo Files yang akan diupload:
echo 1. Fix RTE Pengumuman (Admin & Panitia)
echo 2. Fix Stats Data Disetujui (Admin & Panitia Kelulusan)
echo 3. Fix Stats Perlu Perbaikan (Admin & Panitia DaftarUlang)
echo 4. Sync Panitia Madrasah Settings UI
echo 5. Sync Panitia-Seragam Settings UI
echo.
echo Total: 8 files
echo.
pause

echo.
echo [1/8] Uploading Admin Pengumuman view...
scp "resources/views/livewire/admin/pengumuman.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload admin pengumuman view
    pause
    exit /b 1
)

echo.
echo [2/8] Uploading Panitia Pengumuman view...
scp "resources/views/livewire/panitia/pengumuman.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload panitia pengumuman view
    pause
    exit /b 1
)

echo.
echo [3/8] Uploading Admin Kelulusan component...
scp "app/Livewire/Admin/Kelulusan.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload admin kelulusan component
    pause
    exit /b 1
)

echo.
echo [4/8] Uploading Panitia Kelulusan component...
scp "app/Livewire/Panitia/Kelulusan.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload panitia kelulusan component
    pause
    exit /b 1
)

echo.
echo [5/8] Uploading Admin DaftarUlang component...
scp "app/Livewire/Admin/DaftarUlang.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload admin daftarulang component
    pause
    exit /b 1
)

echo.
echo [6/8] Uploading Panitia DaftarUlang component...
scp "app/Livewire/Panitia/DaftarUlang.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload panitia daftarulang component
    pause
    exit /b 1
)

echo.
echo [7/8] Uploading Panitia Settings Madrasah tab...
scp "resources/views/livewire/panitia/partials/settings-madrasah-tab.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/partials/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload panitia settings madrasah tab
    pause
    exit /b 1
)

echo.
echo [8/8] Uploading Panitia-Seragam Settings tab...
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
echo All 8 files have been uploaded to production.
echo Cache has been cleared.
echo.
echo Please verify the following features:
echo - RTE Pengumuman di Admin dan Panitia
echo - Stats Data Disetujui di /admin/kelulusan
echo - Stats Perlu Perbaikan di /admin/daftar-ulang
echo - Tampilan Settings di /panitia-daftarulang/daftar-ulang
echo - Tampilan Settings di /panitia-seragam/daftar-ulang
echo.
pause
