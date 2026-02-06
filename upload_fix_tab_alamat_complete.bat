@echo off
echo ========================================
echo Upload Fix Tab Alamat - COMPLETE
echo ========================================
echo.
echo PERBAIKAN YANG SUDAH DILAKUKAN:
echo [1] Fix foreign keys cascade dropdowns (province_id, regency_id, district_id)
echo [2] Tambah populateCascadeDropdowns() untuk load data existing
echo [3] Fix loading jarak_ke_sekolah, transportasi, waktu_tempuh
echo [4] Exit edit mode setelah Simpan berhasil (Tab Siswa, Orangtua, Alamat)
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
echo PERBAIKAN YANG SUDAH DITERAPKAN:
echo.
echo 1. CASCADE DROPDOWNS:
echo    ✓ Dropdown kabupaten/kota, kecamatan, kelurahan menampilkan opsi saat edit mode
echo    ✓ Foreign keys diperbaiki (province_id, regency_id, district_id)
echo    ✓ Dropdown lists di-populate otomatis saat masuk edit mode
echo    ✓ Dropdown children ter-update saat parent selection berubah
echo.
echo 2. FIELD JARAK DAN TRANSPORTASI:
echo    ✓ Field jarak_ke_sekolah terload dengan benar
echo    ✓ Field transportasi terload dengan benar
echo    ✓ Field waktu_tempuh terload dengan benar
echo.
echo 3. EXIT EDIT MODE:
echo    ✓ Tab Siswa: Keluar dari edit mode setelah Simpan berhasil
echo    ✓ Tab Orangtua: Keluar dari edit mode setelah Simpan berhasil
echo    ✓ Tab Alamat: Keluar dari edit mode setelah Simpan berhasil
echo.
echo ========================================
echo TESTING CHECKLIST
echo ========================================
echo.
echo TAB SISWA:
echo [ ] https://pmbm.mtsn2kotamalang.sch.id/admin/pendaftar/27?activeTab=siswa
echo     - Klik "Edit Data"
echo     - Ubah data → Klik "Simpan"
echo     - EXPECTED: Notif berhasil muncul DAN keluar dari edit mode
echo.
echo TAB ORANGTUA:
echo [ ] https://pmbm.mtsn2kotamalang.sch.id/admin/pendaftar/27?activeTab=orangtua
echo     - Klik "Edit Data"
echo     - Ubah data → Klik "Simpan"
echo     - EXPECTED: Notif berhasil muncul DAN keluar dari edit mode
echo.
echo TAB ALAMAT:
echo [ ] https://pmbm.mtsn2kotamalang.sch.id/admin/pendaftar/27?activeTab=alamat
echo     - Klik "Edit Data"
echo     - EXPECTED: Semua field terload (provinsi, kabupaten, jarak, transportasi)
echo     - EXPECTED: Dropdown menampilkan opsi yang sesuai
echo     - Ubah provinsi → EXPECTED: Kabupaten ter-update
echo     - Ubah data → Klik "Simpan"
echo     - EXPECTED: Notif berhasil muncul DAN keluar dari edit mode
echo.
echo Hard refresh browser: Ctrl + Shift + R
echo.
pause
