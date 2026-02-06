@echo off
echo ========================================
echo Upload Fix Exit Edit Mode - ALL TABS
echo ========================================
echo.
echo PERBAIKAN YANG SUDAH DILAKUKAN:
echo.
echo Tab Siswa       : saveDataSiswa() - Exit edit mode setelah simpan
echo Tab Orangtua    : saveDataOrangTua() - Exit edit mode setelah simpan
echo Tab Alamat      : saveDataAlamat() - Exit edit mode setelah simpan
echo Tab Prestasi    : saveDataPrestasi() + saveDataPrestasiTerstruktur() - Exit edit mode setelah simpan
echo Tab Beasiswa    : saveDataBeasiswa() + saveDataBeasiswaTerstruktur() - Exit edit mode setelah simpan
echo Tab Pendidikan  : saveDataPendidikan() + saveDataPendidikanLainTerstruktur() - Exit edit mode setelah simpan
echo Tab Keahlian    : saveDataKeahlian() - Exit edit mode setelah simpan
echo Tab Tahfidz     : saveDataTahfidz() - Exit edit mode setelah simpan
echo.
echo TOTAL: 11 methods diperbaiki
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
echo.
echo MASALAH:
echo   User melaporkan: "Tab Prestasi ketika selesai edit notif sudah berhasil
echo   tapi tidak keluar dari edit mode."
echo.
echo SOLUSI:
echo   Semua method save*() sekarang otomatis keluar dari edit mode setelah
echo   simpan berhasil. Form otomatis kembali ke mode view (read-only).
echo.
echo PATTERN FIX:
echo   1. Update database
echo   2. Reload data
echo   3. Set editMode flag = false (ADDED)
echo   4. Show success notification
echo.
echo ========================================
echo TESTING CHECKLIST
echo ========================================
echo.
echo TESTING LOKAL (SEBELUM UPLOAD INI):
echo [x] Tab Siswa - Exit edit mode: TESTED
echo [x] Tab Orangtua - Exit edit mode: TESTED
echo [x] Tab Alamat - Exit edit mode: TESTED
echo [ ] Tab Prestasi - Exit edit mode: NEEDS TESTING
echo [ ] Tab Beasiswa - Exit edit mode: NEEDS TESTING
echo [ ] Tab Pendidikan - Exit edit mode: NEEDS TESTING
echo [ ] Tab Keahlian - Exit edit mode: NEEDS TESTING
echo [ ] Tab Tahfidz - Exit edit mode: NEEDS TESTING
echo.
echo TESTING PRODUCTION (SETELAH UPLOAD INI):
echo.
echo URL BASE: https://pmbm.mtsn2kotamalang.sch.id/admin/pendaftar/27
echo.
echo Test setiap tab dengan flow:
echo   1. Klik "Edit Data"
echo   2. Ubah data
echo   3. Klik "Simpan"
echo   4. EXPECTED: Notif berhasil muncul DAN keluar dari edit mode
echo.
echo Tabs to test (append ?activeTab=xxx):
echo   - siswa
echo   - orangtua
echo   - alamat
echo   - prestasi (USER REPORTED ISSUE - MAIN FIX)
echo   - beasiswa
echo   - pendidikan
echo   - keahlian
echo   - tahfidz
echo.
echo Hard refresh browser: Ctrl + Shift + R
echo.
pause
