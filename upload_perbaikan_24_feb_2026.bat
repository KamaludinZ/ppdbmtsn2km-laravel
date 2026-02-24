@echo off
echo ========================================
echo UPLOAD PERBAIKAN 24 FEBRUARI 2026
echo ========================================
echo.

echo [1/3] Uploading AdminLogin.php...
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Auth\AdminLogin.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Auth/
if errorlevel 1 (
    echo ERROR: Failed to upload AdminLogin.php
    pause
    exit /b 1
)
echo SUCCESS: AdminLogin.php uploaded
echo.

echo [2/3] Uploading PanitiaSeragam/DaftarUlang.php...
scp "C:\ppdbmtsn2km-laravel\app\Livewire\PanitiaSeragam\DaftarUlang.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/PanitiaSeragam/
if errorlevel 1 (
    echo ERROR: Failed to upload DaftarUlang.php
    pause
    exit /b 1
)
echo SUCCESS: DaftarUlang.php uploaded
echo.

echo [3/3] Uploading panitia-seragam/daftar-ulang.blade.php...
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\panitia-seragam\daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia-seragam/
if errorlevel 1 (
    echo ERROR: Failed to upload daftar-ulang.blade.php
    pause
    exit /b 1
)
echo SUCCESS: daftar-ulang.blade.php uploaded
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
echo 1. Login panitia: panitia@test.com, panitia-seragam@test.com, panitia-psikologi@test.com
echo 2. Halaman /panitia-seragam/daftar-ulang?activeTab=seragam
echo 3. Modal popup dengan backdrop blur
echo 4. Sortable headers (klik header untuk sort)
echo 5. Toggle Aksi Opsi berfungsi
echo.
pause
