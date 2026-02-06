@echo off
echo ========================================
echo Upload Fix Nama Lengkap - Remove Uppercase
echo ========================================
echo.
echo MASALAH YANG DIPERBAIKI:
echo.
echo [1] Field "Nama Lengkap" tampil HURUF BESAR SEMUA di mode edit
echo [2] Tidak sesuai data asli (proper case / title case)
echo [3] Membingungkan user karena display vs database berbeda
echo.

set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo [1/3] Upload Tab Siswa View...
scp "resources/views/tab_templates/tab-data-siswa.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/tab_templates/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload view
    pause
    exit /b 1
)
echo [OK] Tab siswa view uploaded

echo.
echo [2/3] Clear view cache...
ssh %SERVER% "cd %REMOTE_PATH% && php artisan view:clear"
if %errorlevel% neq 0 (
    echo ERROR: Gagal clear cache
    pause
    exit /b 1
)
echo [OK] View cache cleared

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
echo 1. UPPERCASE CLASS REMOVED:
echo    ✓ Field nama lengkap tidak lagi tampil UPPERCASE
echo    ✓ Tampil sesuai data asli (proper case / title case)
echo.
echo 2. PLACEHOLDER IMPROVED:
echo    ✓ Before: "NAMA LENGKAP"
echo    ✓ After:  "Nama Lengkap" (title case)
echo.
echo 3. HELPER TEXT IMPROVED:
echo    ✓ Before: "Isi dengan huruf kapital sesuai akta kelahiran"
echo    ✓ After:  "Isi sesuai akta kelahiran / ijazah"
echo.
echo ========================================
echo TESTING CHECKLIST
echo ========================================
echo.
echo URL: https://pmbm.mtsn2kotamalang.sch.id/admin/pendaftar/27?activeTab=siswa
echo.
echo TEST 1 - PROPER CASE DISPLAY:
echo [ ] Klik "Edit Data" di Tab Siswa
echo [ ] Lihat field "Nama Lengkap"
echo [ ] EXPECTED: Tampil proper case (Ahmad Rizki Maulana)
echo [ ] EXPECTED: BUKAN uppercase (AHMAD RIZKI MAULANA)
echo.
echo TEST 2 - INPUT NEW NAME:
echo [ ] Ketik nama baru: "Muhammad Fahmi Al Farizi"
echo [ ] EXPECTED: Tampil proper case (bukan uppercase)
echo [ ] EXPECTED: Placeholder: "Nama Lengkap"
echo.
echo TEST 3 - SAVE PRESERVES CASE:
echo [ ] Edit nama ke proper case
echo [ ] Klik "Simpan"
echo [ ] EXPECTED: Data tersimpan proper case
echo [ ] EXPECTED: View mode tampil sama (proper case)
echo.
echo EXAMPLE:
echo Database: "Ahmad Rizki Maulana"
echo Display:  "Ahmad Rizki Maulana" (proper case) ✓
echo NOT:      "AHMAD RIZKI MAULANA" (uppercase)   ✗
echo.
echo Hard refresh browser: Ctrl + Shift + R
echo.
pause
