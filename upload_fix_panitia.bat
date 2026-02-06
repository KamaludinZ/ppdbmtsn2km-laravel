@echo off
echo ========================================
echo Upload Fix Panitia - Same as Admin
echo ========================================
echo.
echo MASALAH YANG DIPERBAIKI:
echo.
echo [1] Tab Prestasi - Tidak keluar dari edit mode setelah simpan
echo [2] Tab Dokumen - Loading state di semua tombol
echo.
echo Plus fixes dari shared tab templates:
echo [3] Tab Orangtua - Tidak bisa masuk edit mode
echo [4] Tab Siswa - Error summary ^& Uppercase nama lengkap
echo [5] Tab Survey - Error MethodNotFoundException
echo.

set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo [1/3] Upload Panitia Controller...
scp "app/Livewire/Panitia/DetailPendaftar.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Panitia/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload controller
    pause
    exit /b 1
)
echo [OK] Panitia controller uploaded

echo.
echo [2/3] Upload Panitia View...
scp "resources/views/livewire/panitia/detail-pendaftar.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/panitia/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload view
    pause
    exit /b 1
)
echo [OK] Panitia view uploaded

echo.
echo [3/3] Clear cache...
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
echo 1. TAB PRESTASI:
echo    ✓ Klik "Simpan" → Keluar dari edit mode
echo    ✓ Property: editModeDataPrestasi = false
echo.
echo 2. TAB DOKUMEN:
echo    ✓ Loading state per button (specific wire:target)
echo    ✓ Klik "Simpan Status" KK → Hanya button KK loading
echo    ✓ Button lain tetap normal
echo.
echo 3. SHARED TAB TEMPLATES (otomatis berlaku):
echo    ✓ Tab Orangtua: Bisa masuk edit mode
echo    ✓ Tab Siswa: Error summary ^& proper case nama
echo    ✓ Tab Survey: Read-only untuk Panitia
echo.
echo ========================================
echo TESTING CHECKLIST
echo ========================================
echo.
echo URL: https://pmbm.mtsn2kotamalang.sch.id/panitia/pendaftar/27
echo Login as: Panitia
echo.
echo TEST 1 - TAB PRESTASI:
echo [ ] Klik "Edit Data" → Masuk edit mode
echo [ ] Klik "Simpan" → KELUAR dari edit mode ✓
echo.
echo TEST 2 - TAB ORANGTUA:
echo [ ] Klik "Edit Data" → MASUK edit mode ✓
echo [ ] Klik "Simpan" → Keluar dari edit mode
echo.
echo TEST 3 - TAB SISWA:
echo [ ] Ada error → Summary box tampil ✓
echo [ ] Nama lengkap tampil proper case ✓
echo.
echo TEST 4 - TAB SURVEY:
echo [ ] Badge "Mode Lihat" tampil ✓
echo [ ] TIDAK ada tombol "Edit Data" ✓
echo [ ] TIDAK ada error saat buka tab ✓
echo.
echo TEST 5 - TAB DOKUMEN:
echo [ ] Klik "Simpan Status" satu dokumen
echo [ ] Hanya button itu yang loading ✓
echo [ ] Button lain tetap normal ✓
echo.
echo TOTAL FIXES: 7 issues
echo FILES MODIFIED: 2 Panitia files + 3 shared templates
echo.
echo Hard refresh browser: Ctrl + Shift + R
echo.
pause
