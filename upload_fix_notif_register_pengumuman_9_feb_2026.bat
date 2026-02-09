@echo off
echo ================================================
echo UPLOAD FIX NOTIF REGISTER DAN PENGUMUMAN
echo 9 Februari 2026
echo ================================================
echo.

REM Upload file Register.php dengan pesan error yang diperbaiki
echo [1/2] Upload Register.php...
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Auth\Register.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Auth/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload Register.php
    pause
    exit /b 1
)
echo SUCCESS: Register.php uploaded
echo.

REM Upload file pengumuman.blade.php dengan TextFormatter
echo [2/2] Upload pengumuman.blade.php...
scp "C:\ppdbmtsn2km-laravel\resources\views\public\pengumuman.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/public/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload pengumuman.blade.php
    pause
    exit /b 1
)
echo SUCCESS: pengumuman.blade.php uploaded
echo.

echo ================================================
echo CLEAR CACHE DI SERVER
echo ================================================
echo.

ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
if %errorlevel% neq 0 (
    echo WARNING: Gagal clear cache, tapi file sudah terupload
) else (
    echo SUCCESS: Cache cleared
)

echo.
echo ================================================
echo UPLOAD SELESAI!
echo ================================================
echo.
echo File yang diupload:
echo 1. app/Livewire/Auth/Register.php
echo 2. resources/views/public/pengumuman.blade.php
echo.
echo Silahkan test di:
echo - /daftar - coba register dengan email/NISN yang sudah terdaftar
echo - /daftar - coba upload file rapor lebih dari 2MB atau bukan PDF
echo - /pengumuman - cek tampilan konten pengumuman
echo.
pause
