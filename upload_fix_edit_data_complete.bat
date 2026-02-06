@echo off
echo ========================================
echo Upload Fix Edit Data + Tab Survey ke Production
echo ========================================
echo.
echo PERBAIKAN:
echo [1] Edit Data berfungsi di semua tab (Admin dan Panitia)
echo [2] Tab Survey dipindah setelah "Pendidikan Lain"
echo.

set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo [1/6] Upload Admin Controller...
scp "app/Livewire/Admin/DetailPendaftar.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Admin/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload Admin controller
    pause
    exit /b 1
)
echo [OK] Admin controller uploaded

echo [2/6] Upload Panitia Controller...
scp "app/Livewire/Panitia/DetailPendaftar.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Panitia/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload Panitia controller
    pause
    exit /b 1
)
echo [OK] Panitia controller uploaded

echo [3/6] Upload Admin View...
scp "resources/views/livewire/admin/detail-pendaftar.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/admin/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload Admin view
    pause
    exit /b 1
)
echo [OK] Admin view uploaded

echo [4/6] Upload Panitia View...
scp "resources/views/livewire/panitia/detail-pendaftar.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/panitia/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload Panitia view
    pause
    exit /b 1
)
echo [OK] Panitia view uploaded

echo [5/6] Clear ALL caches...
ssh %SERVER% "cd %REMOTE_PATH% && php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear && php artisan optimize:clear"
if %errorlevel% neq 0 (
    echo ERROR: Gagal clear cache
    pause
    exit /b 1
)
echo [OK] All caches cleared

echo [6/6] Delete compiled views manually...
ssh %SERVER% "cd %REMOTE_PATH% && rm -rf storage/framework/views/*.php"
echo [OK] Compiled views deleted

echo.
echo ========================================
echo UPLOAD BERHASIL!
echo ========================================
echo.
echo YANG SUDAH DIPERBAIKI:
echo ✓ Edit Data berfungsi di semua tab (Admin dan Panitia)
echo ✓ Tab Survey dipindah setelah "Pendidikan Lain"
echo ✓ Edit Nilai Rapor tetap berfungsi
echo.
echo FILE YANG DIUPLOAD:
echo - app/Livewire/Admin/DetailPendaftar.php
echo - app/Livewire/Panitia/DetailPendaftar.php
echo - resources/views/livewire/admin/detail-pendaftar.blade.php
echo - resources/views/livewire/panitia/detail-pendaftar.blade.php
echo.
echo URUTAN TAB BARU:
echo 1. Data Siswa
echo 2. Data Orang Tua
echo 3. Data Alamat
echo 4. Data Prestasi
echo 5. Keahlian
echo 6. Tahfidz
echo 7. Beasiswa dan Bantuan
echo 8. Pendidikan Lain
echo 9. Survey (DIPINDAH KE SINI)
echo 10. Verifikasi Dokumen
echo 11. Daftar Ulang
echo.
echo Silakan test di:
echo - https://pmbm.mtsn2kotamalang.sch.id/admin/pendaftar/13
echo - https://pmbm.mtsn2kotamalang.sch.id/panitia-pendaftaran/pendaftar/13
echo.
echo Hard refresh browser: Ctrl + Shift + R
echo.
pause
