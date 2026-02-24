@echo off
echo ========================================
echo UPLOAD CEK PENDAFTARAN 24 FEBRUARI 2026
echo ========================================
echo.

echo [1/2] Uploading CekPendaftaran.php...
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Public\CekPendaftaran.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Public/
if errorlevel 1 (
    echo ERROR: Failed to upload CekPendaftaran.php
    pause
    exit /b 1
)
echo SUCCESS: CekPendaftaran.php uploaded
echo.

echo [2/2] Uploading cek-pendaftaran.blade.php...
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\public\cek-pendaftaran.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/public/
if errorlevel 1 (
    echo ERROR: Failed to upload cek-pendaftaran.blade.php
    pause
    exit /b 1
)
echo SUCCESS: cek-pendaftaran.blade.php uploaded
echo.

echo ========================================
echo Clearing cache on production...
echo ========================================
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear && php artisan cache:clear && php artisan config:clear"
echo.

echo ========================================
echo UPLOAD COMPLETE!
echo ========================================
echo.
echo All files have been uploaded successfully.
echo Cache has been cleared on production.
echo.
echo Please test the following:
echo 1. Buka https://pmbm.mtsn2kotamalang.sch.id/cek-pendaftaran
echo 2. Search dengan nomor pendaftaran atau NISN
echo 3. Cek 3 status cards utama tampil dengan benar
echo 4. Jika status lulus:
echo    - Status Verif Pendaftaran: "Data Disetujui" (hijau)
echo    - Status Kelulusan: "LULUS" (hijau)
echo    - Section daftar ulang muncul
echo 5. Test dengan berbagai status (draft, menunggu, lulus, tidak lulus)
echo.
pause
