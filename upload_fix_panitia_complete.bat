@echo off
echo ========================================
echo Upload Fix Panitia - COMPLETE (All Issues)
echo ========================================
echo.
echo MASALAH YANG DIPERBAIKI:
echo.
echo [1] ERROR 500 saat klik "Edit Data" di Tab Siswa
echo [2] Semua tab tidak keluar dari edit mode setelah simpan
echo [3] Loading state di semua tombol Tab Dokumen
echo [4] Tab templates shared (Orangtua, Siswa, Survey)
echo.

set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo FIXES APPLIED:
echo ===============
echo.
echo PANITIA CONTROLLER:
echo - Error 500 fix: Null-safe loading (34 properties)
echo - Exit edit mode: 8 tabs fixed
echo - Tab Siswa, Orangtua, Alamat, Sekolah
echo - Tab Rapor, Prestasi, Keahlian, Tahfidz
echo.
echo PANITIA VIEW:
echo - Loading state per button (Tab Dokumen)
echo.
echo TAB TEMPLATES (Shared):
echo - Tab Orangtua: Property name fix
echo - Tab Siswa: Error summary + proper case
echo - Tab Survey: Read-only untuk Panitia
echo.

echo ========================================
echo UPLOAD FILES...
echo ========================================
echo.

echo [1/4] Upload Panitia Controller...
scp "app/Livewire/Panitia/DetailPendaftar.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Panitia/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload controller
    pause
    exit /b 1
)
echo [OK] Panitia controller uploaded

echo.
echo [2/4] Upload Panitia View...
scp "resources/views/livewire/panitia/detail-pendaftar.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/panitia/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload view
    pause
    exit /b 1
)
echo [OK] Panitia view uploaded

echo.
echo [3/4] Upload Tab Templates...
scp "resources/views/tab_templates/tab-data-orangtua.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/tab_templates/
scp "resources/views/tab_templates/tab-data-siswa.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/tab_templates/
scp "resources/views/tab_templates/tab-survey.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/tab_templates/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload tab templates
    pause
    exit /b 1
)
echo [OK] Tab templates uploaded

echo.
echo [4/4] Clear cache...
ssh %SERVER% "cd %REMOTE_PATH% && php artisan view:clear && php artisan optimize:clear && rm -rf storage/framework/views/*.php"
if %errorlevel% neq 0 (
    echo ERROR: Gagal clear cache
    pause
    exit /b 1
)
echo [OK] Cache cleared

echo.
echo ========================================
echo UPLOAD BERHASIL!
echo ========================================
echo.
echo YANG SUDAH DIPERBAIKI:
echo.
echo 1. ERROR 500 FIXED:
echo    ✓ loadDataToProperties() null-safe (34 properties)
echo    ✓ Tanggal lahir safe type checking
echo    ✓ Semua field handle null value
echo.
echo 2. EXIT EDIT MODE (8 tabs):
echo    ✓ Tab Siswa → editModeSiswa = false
echo    ✓ Tab Orangtua → editModeOrangtua = false
echo    ✓ Tab Alamat → editModeAlamat = false
echo    ✓ Tab Sekolah → editModeSekolah = false
echo    ✓ Tab Rapor → editModeRapor = false
echo    ✓ Tab Prestasi → editModeDataPrestasi = false
echo    ✓ Tab Keahlian → editModeDataKeahlian = false
echo    ✓ Tab Tahfidz → editModeDataTahfidz = false
echo.
echo 3. LOADING STATE:
echo    ✓ Tab Dokumen: Loading per button
echo    ✓ wire:target spesifik dengan doc key
echo.
echo 4. TAB TEMPLATES:
echo    ✓ Tab Orangtua: Property name fix
echo    ✓ Tab Siswa: Error summary + uppercase fix
echo    ✓ Tab Survey: Badge "Mode Lihat" untuk Panitia
echo.
echo ========================================
echo TESTING CHECKLIST
echo ========================================
echo.
echo URL: https://pmbm.mtsn2kotamalang.sch.id/panitia-pendaftaran/pendaftar/167
echo.
echo PRIORITY TEST:
echo [ ] Tab Siswa: Klik "Edit Data" → TIDAK ERROR 500 ✓
echo [ ] Tab Siswa: Simpan → Keluar edit mode ✓
echo.
echo ALL TABS TEST:
echo [ ] Tab Siswa: Edit & Save → OK ✓
echo [ ] Tab Orangtua: Edit & Save → OK ✓
echo [ ] Tab Alamat: Edit & Save → OK ✓
echo [ ] Tab Sekolah: Edit & Save → OK ✓
echo [ ] Tab Rapor: Edit & Save → OK ✓
echo [ ] Tab Prestasi: Edit & Save → OK ✓
echo [ ] Tab Keahlian: Edit & Save → OK ✓
echo [ ] Tab Tahfidz: Edit & Save → OK ✓
echo [ ] Tab Dokumen: Loading per button ✓
echo [ ] Tab Survey: Badge "Mode Lihat" ✓
echo.
echo EXPECTED BEHAVIOR:
echo 1. Klik "Edit Data" → Masuk edit mode (NO ERROR)
echo 2. Form tampil semua field (handle null)
echo 3. Klik "Simpan" → Keluar dari edit mode
echo 4. Flash message "Data berhasil diperbarui"
echo.
echo NOTE:
echo - Fix berlaku untuk /panitia/ dan /panitia-pendaftaran/
echo - Tab templates shared dengan Admin
echo.
echo Hard refresh browser: Ctrl + Shift + R
echo.
pause
