@echo off
echo ========================================
echo Upload Fix Edit Nilai Rapor LENGKAP ke Production
echo ========================================
echo.
echo PERBAIKAN:
echo [1] Tambah method toggleEdit() di Panitia (fix error 500 klik Edit Data)
echo [2] Method saveNilaiRapor() sudah ada di Admin dan Panitia
echo [3] Method toggleEditMode() sudah ada di Admin dan Panitia
echo.

set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo [1/4] Upload Panitia DetailPendaftar Controller (FIXED - dengan toggleEdit)...
scp "app/Livewire/Panitia/DetailPendaftar.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Panitia/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload Panitia controller
    pause
    exit /b 1
)
echo [OK] Panitia controller uploaded

echo [2/4] Upload Admin DetailPendaftar Controller...
scp "app/Livewire/Admin/DetailPendaftar.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Admin/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload Admin controller
    pause
    exit /b 1
)
echo [OK] Admin controller uploaded

echo [3/4] Upload Panitia DetailPendaftar View...
scp "resources/views/livewire/panitia/detail-pendaftar.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/panitia/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload Panitia view
    pause
    exit /b 1
)
echo [OK] Panitia view uploaded

echo [4/4] Upload Admin DetailPendaftar View...
scp "resources/views/livewire/admin/detail-pendaftar.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/admin/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload Admin view
    pause
    exit /b 1
)
echo [OK] Admin view uploaded

echo.
echo [5/5] Clear cache di production...
ssh %SERVER% "cd %REMOTE_PATH% && php artisan view:clear && php artisan cache:clear && php artisan optimize:clear"
if %errorlevel% neq 0 (
    echo ERROR: Gagal clear cache
    pause
    exit /b 1
)
echo [OK] Cache cleared

echo.
echo ========================================
echo Upload BERHASIL!
echo ========================================
echo.
echo YANG SUDAH DIPERBAIKI:
echo ✓ Error 500 di Panitia (toggleEdit method not found) - FIXED
echo ✓ Tombol Edit Nilai Rapor - ADDED
echo ✓ Modal Edit dengan 3 kolom + rata-rata - ADDED
echo ✓ Simpan nilai rapor - WORKING
echo.
echo FILE YANG DIUPLOAD:
echo - app/Livewire/Panitia/DetailPendaftar.php (+ method toggleEdit)
echo - app/Livewire/Admin/DetailPendaftar.php
echo - resources/views/livewire/panitia/detail-pendaftar.blade.php
echo - resources/views/livewire/admin/detail-pendaftar.blade.php
echo.
echo Silakan test di:
echo - https://pmbm.mtsn2kotamalang.sch.id/panitia/pendaftar/[id]
echo - https://pmbm.mtsn2kotamalang.sch.id/admin/pendaftar/[id]
echo.
pause
