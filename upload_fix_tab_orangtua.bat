@echo off
echo ========================================
echo Upload Fix Tab Orangtua - Edit Mode
echo ========================================
echo.
echo MASALAH YANG DIPERBAIKI:
echo.
echo [1] Klik "Edit Data" tidak masuk edit mode
echo [2] Property name mismatch: editModeOrangTua vs editModeOrangtua
echo.

set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo [1/3] Upload Tab Orangtua View...
scp "resources/views/tab_templates/tab-data-orangtua.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/tab_templates/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload view
    pause
    exit /b 1
)
echo [OK] Tab orangtua view uploaded

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
echo 1. PROPERTY NAME FIXED:
echo    ✓ View sekarang menggunakan: $editModeOrangtua (lowercase t)
echo    ✓ Sesuai dengan controller property
echo    ✓ toggleEdit('orangtua') sekarang berfungsi
echo.
echo 2. EDIT MODE BEHAVIOR:
echo    ✓ Klik "Edit Data" → Masuk edit mode
echo    ✓ Klik "Batal" → Keluar edit mode
echo    ✓ Klik "Simpan" → Keluar edit mode otomatis
echo.
echo ========================================
echo TESTING CHECKLIST
echo ========================================
echo.
echo URL: https://pmbm.mtsn2kotamalang.sch.id/admin/pendaftar/27?activeTab=orangtua
echo.
echo TEST 1 - ENTER EDIT MODE:
echo [ ] Klik tombol "Edit Data"
echo [ ] EXPECTED: Form masuk edit mode (input fields aktif)
echo [ ] EXPECTED: Tombol "Simpan" dan "Batal" muncul
echo [ ] EXPECTED: Tombol "Edit Data" hilang
echo.
echo TEST 2 - EXIT EDIT MODE (CANCEL):
echo [ ] Klik tombol "Batal"
echo [ ] EXPECTED: Form keluar edit mode (read-only)
echo [ ] EXPECTED: Tombol "Edit Data" muncul kembali
echo.
echo TEST 3 - EXIT EDIT MODE (SAVE):
echo [ ] Klik "Edit Data" lagi
echo [ ] Ubah data orangtua (nama ayah/ibu)
echo [ ] Klik "Simpan"
echo [ ] EXPECTED: Notif "Data orang tua berhasil diperbarui"
echo [ ] EXPECTED: Keluar dari edit mode otomatis
echo [ ] EXPECTED: Data tersimpan dengan benar
echo.
echo Hard refresh browser: Ctrl + Shift + R
echo.
pause
