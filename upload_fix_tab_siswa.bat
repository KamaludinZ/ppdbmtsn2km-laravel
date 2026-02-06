@echo off
echo ========================================
echo Upload Fix Tab Siswa - Validation Error Summary
echo ========================================
echo.
echo MASALAH YANG DIPERBAIKI:
echo.
echo [1] Data tidak bisa disimpan karena validation error tidak terlihat
echo [2] User tidak tahu field mana yang error
echo [3] Error messages tersebar di form yang panjang
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
echo 1. VALIDATION ERROR SUMMARY:
echo    ✓ Error summary box di bagian atas form (red background)
echo    ✓ Menampilkan jumlah total errors
echo    ✓ List semua error messages sekaligus
echo    ✓ Instruksi scroll untuk perbaikan field
echo.
echo 2. USER EXPERIENCE:
echo    ✓ User langsung tahu apa yang salah
echo    ✓ Tidak perlu hunting error messages
echo    ✓ Proses perbaikan data lebih cepat
echo.
echo 3. SAVE BEHAVIOR:
echo    ✓ Validation error → Tetap di edit mode + tampilkan errors
echo    ✓ Validation pass → Simpan data + keluar edit mode
echo    ✓ Flash message: "Data siswa berhasil diperbarui"
echo.
echo ========================================
echo TESTING CHECKLIST
echo ========================================
echo.
echo URL: https://pmbm.mtsn2kotamalang.sch.id/admin/pendaftar/27?activeTab=siswa
echo.
echo TEST 1 - VALIDATION ERROR VISIBLE:
echo [ ] Klik "Edit Data"
echo [ ] Hapus value di NISN atau NIK
echo [ ] Klik "Simpan"
echo [ ] EXPECTED: Error summary box muncul di atas (red)
echo [ ] EXPECTED: List semua error messages
echo [ ] EXPECTED: Form tetap di edit mode
echo.
echo TEST 2 - SAVE SUCCESS:
echo [ ] Perbaiki field yang error
echo [ ] Ubah data lain (nama panggilan, dll)
echo [ ] Klik "Simpan"
echo [ ] EXPECTED: Notif "Data siswa berhasil diperbarui"
echo [ ] EXPECTED: Keluar dari edit mode otomatis
echo [ ] EXPECTED: Data tersimpan dengan benar
echo.
echo COMMON VALIDATION ERRORS:
echo - NISN: Harus 10 digit angka
echo - NIK: Harus 16 digit angka (wajib untuk WNI)
echo - Nomor KK: Harus 16 digit angka
echo - Nama: Hanya huruf dan karakter khusus
echo - Anak Ke: Harus angka 1-99
echo - Hobi, Cita-cita, Yang Membiayai: Harus dipilih
echo.
echo Hard refresh browser: Ctrl + Shift + R
echo.
pause
