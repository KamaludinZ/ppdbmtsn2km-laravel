@echo off
echo ========================================
echo Upload Fix Panitia - ALL TABS Exit Edit Mode
echo ========================================
echo.
echo MASALAH YANG DIPERBAIKI:
echo.
echo Semua tab di Panitia tidak keluar dari edit mode setelah simpan
echo.
echo TABS FIXED (8 tabs):
echo [1] Tab Siswa (Data Pribadi)
echo [2] Tab Orangtua
echo [3] Tab Alamat
echo [4] Tab Sekolah
echo [5] Tab Rapor
echo [6] Tab Prestasi
echo [7] Tab Keahlian
echo [8] Tab Tahfidz
echo.

set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo [1/2] Upload Panitia Controller...
scp "app/Livewire/Panitia/DetailPendaftar.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Panitia/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload controller
    pause
    exit /b 1
)
echo [OK] Panitia controller uploaded

echo.
echo [2/2] Clear cache...
ssh %SERVER% "cd %REMOTE_PATH% && php artisan optimize:clear"
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
echo YANG SUDAH DIPERBAIKI (8 METHODS):
echo.
echo 1. saveDataPribadi() → editModeSiswa = false ✓
echo 2. saveDataOrangTua() → editModeOrangtua = false ✓
echo 3. saveDataAlamat() → editModeAlamat = false ✓
echo 4. saveDataSekolah() → editModeSekolah = false ✓
echo 5. saveDataRapor() → editModeRapor = false ✓
echo 6. saveDataPrestasi() → editModeDataPrestasi = false ✓
echo 7. saveDataKeahlian() → editModeDataKeahlian = false ✓
echo 8. saveDataTahfidz() → editModeDataTahfidz = false ✓
echo.
echo ========================================
echo TESTING CHECKLIST
echo ========================================
echo.
echo URL: https://pmbm.mtsn2kotamalang.sch.id/panitia-pendaftaran/pendaftar/167
echo Login: User dengan role panitia_pendaftaran
echo.
echo TEST EACH TAB:
echo.
echo [ ] Tab Siswa: Simpan → Keluar edit mode ✓
echo [ ] Tab Orangtua: Simpan → Keluar edit mode ✓
echo [ ] Tab Alamat: Simpan → Keluar edit mode ✓
echo [ ] Tab Sekolah: Simpan → Keluar edit mode ✓
echo [ ] Tab Rapor: Simpan → Keluar edit mode ✓
echo [ ] Tab Prestasi: Simpan → Keluar edit mode ✓
echo [ ] Tab Keahlian: Simpan → Keluar edit mode ✓
echo [ ] Tab Tahfidz: Simpan → Keluar edit mode ✓
echo.
echo EXPECTED BEHAVIOR SETIAP TAB:
echo 1. Klik "Edit Data" → Masuk edit mode
echo 2. Ubah data
echo 3. Klik "Simpan" → KELUAR dari edit mode ✓
echo 4. Flash message "Data berhasil diperbarui" tampil ✓
echo 5. Form kembali ke view mode (read-only) ✓
echo.
echo NOTE:
echo - Fix berlaku untuk route /panitia/ dan /panitia-pendaftaran/
echo - Kedua route menggunakan controller yang sama
echo.
pause
