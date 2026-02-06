@echo off
echo ========================================
echo Upload Fix Tab Alamat - Cascade Dropdowns
echo ========================================
echo.
echo PERBAIKAN:
echo [1] Fix foreign keys di cascade dropdowns (province_id, regency_id, district_id)
echo [2] Tambah method populateCascadeDropdowns() untuk load data existing
echo [3] Update toggleEdit() untuk populate dropdowns saat masuk edit mode
echo [4] Update semua updated* methods untuk populate child dropdowns
echo.

set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo [1/3] Upload Admin DetailPendaftar Controller...
scp "app/Livewire/Admin/DetailPendaftar.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Admin/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload Admin controller
    pause
    exit /b 1
)
echo [OK] Admin controller uploaded

echo.
echo [2/3] Clear ALL caches...
ssh %SERVER% "cd %REMOTE_PATH% && php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear && php artisan optimize:clear"
if %errorlevel% neq 0 (
    echo ERROR: Gagal clear cache
    pause
    exit /b 1
)
echo [OK] All caches cleared

echo.
echo [3/3] Delete compiled views...
ssh %SERVER% "cd %REMOTE_PATH% && rm -rf storage/framework/views/*.php"
echo [OK] Compiled views deleted

echo.
echo ========================================
echo UPLOAD BERHASIL!
echo ========================================
echo.
echo YANG SUDAH DIPERBAIKI:
echo ✓ Tab Alamat dropdown kabupaten/kota, kecamatan, kelurahan sekarang menampilkan opsi saat edit mode
echo ✓ Foreign keys diperbaiki (province_id, regency_id, district_id)
echo ✓ Dropdown lists di-populate otomatis saat masuk edit mode
echo ✓ Dropdown children ter-update saat parent selection berubah
echo.
echo TESTING:
echo 1. Admin Tab Alamat: https://pmbm.mtsn2kotamalang.sch.id/admin/pendaftar/27?activeTab=alamat
echo    - Klik "Edit Data"
echo    - Pastikan dropdown sudah terisi dengan data yang ada
echo    - Ubah provinsi → pastikan kabupaten ter-update
echo    - Ubah kabupaten → pastikan kecamatan ter-update
echo    - Ubah kecamatan → pastikan kelurahan ter-update
echo.
echo 2. Panitia Tab Alamat: https://pmbm.mtsn2kotamalang.sch.id/panitia-pendaftaran/pendaftar/27?activeTab=alamat
echo    - Sama seperti admin testing
echo.
echo Hard refresh browser: Ctrl + Shift + R
echo.
pause
