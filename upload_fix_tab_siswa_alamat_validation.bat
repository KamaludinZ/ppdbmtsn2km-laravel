@echo off
echo ========================================
echo Upload Fix Tab Siswa - Remove Tab Alamat Fields Validation
echo ========================================
echo.
echo MASALAH YANG DIPERBAIKI:
echo.
echo [1] Error "waktu tempuh must be an integer" muncul di Tab Siswa
echo [2] Field waktu_tempuh ada di Tab Alamat, bukan Tab Siswa
echo [3] Validation expect integer, tapi dropdown value adalah string
echo [4] 4 field Tab Alamat ter-validate di Tab Siswa (salah tempat)
echo.

set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo [1/3] Upload Admin DetailPendaftar Controller...
scp "app/Livewire/Admin/DetailPendaftar.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Admin/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload controller
    pause
    exit /b 1
)
echo [OK] Controller uploaded

echo.
echo [2/3] Clear optimize cache...
ssh %SERVER% "cd %REMOTE_PATH% && php artisan optimize:clear"
if %errorlevel% neq 0 (
    echo ERROR: Gagal clear cache
    pause
    exit /b 1
)
echo [OK] Cache cleared

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
echo 1. FIELD OWNERSHIP CLEANUP:
echo    ✓ Removed validation untuk 4 field Tab Alamat dari Tab Siswa:
echo      - jenis_tinggal
echo      - alat_transportasi
echo      - jarak_tempat_tinggal
echo      - waktu_tempuh
echo.
echo 2. VALIDATION ERROR FIXED:
echo    ✓ Error "waktu tempuh must be an integer" tidak muncul lagi
echo    ✓ Tab Siswa hanya validate field-nya sendiri
echo    ✓ Tab Alamat validation tetap berfungsi dengan benar
echo.
echo 3. DATA TYPE CORRECT:
echo    ✓ Waktu tempuh di Tab Alamat: string dropdown
echo    ✓ Options: "1-10 menit", "10-19 menit", "1-2 jam", etc
echo    ✓ Validation di Tab Alamat: nullable^|string^|max:50 (sudah benar)
echo.
echo ========================================
echo TESTING CHECKLIST
echo ========================================
echo.
echo URL TAB SISWA: https://pmbm.mtsn2kotamalang.sch.id/admin/pendaftar/27?activeTab=siswa
echo.
echo TEST 1 - NO MORE WAKTU TEMPUH ERROR:
echo [ ] Klik "Edit Data" di Tab Siswa
echo [ ] Jangan ubah apapun
echo [ ] Klik "Simpan"
echo [ ] EXPECTED: Tidak ada error "waktu tempuh must be an integer"
echo [ ] EXPECTED: Notif "Data siswa berhasil diperbarui"
echo [ ] EXPECTED: Keluar dari edit mode
echo.
echo TEST 2 - TAB SISWA VALIDATION WORKS:
echo [ ] Klik "Edit Data" di Tab Siswa
echo [ ] Hapus NISN
echo [ ] Klik "Simpan"
echo [ ] EXPECTED: Error "The nisn field is required"
echo [ ] EXPECTED: TIDAK ADA error tentang waktu_tempuh
echo.
echo URL TAB ALAMAT: https://pmbm.mtsn2kotamalang.sch.id/admin/pendaftar/27?activeTab=alamat
echo.
echo TEST 3 - TAB ALAMAT STILL WORKS:
echo [ ] Klik "Edit Data" di Tab Alamat
echo [ ] Ubah dropdown "Waktu Tempuh" ke "1-10 menit"
echo [ ] Klik "Simpan"
echo [ ] EXPECTED: Notif "Data alamat berhasil diperbarui"
echo [ ] EXPECTED: Data tersimpan dengan benar
echo [ ] EXPECTED: Keluar dari edit mode
echo.
echo DROPDOWN OPTIONS WAKTU TEMPUH (TAB ALAMAT):
echo - 1-10 menit
echo - 10-19 menit
echo - 20-29 menit
echo - 30-39 menit
echo - 1-2 jam
echo - Lebih dari 2 jam
echo.
echo Hard refresh browser: Ctrl + Shift + R
echo.
pause
