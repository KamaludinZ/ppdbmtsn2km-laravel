@echo off
echo ========================================
echo Upload Fix: User-Friendly Duplicate Message
echo ========================================
echo.

echo Uploading Register.php...
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Auth\Register.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Auth/

echo.
echo ========================================
echo Upload Complete!
echo ========================================
echo.
echo NEXT STEPS:
echo 1. SSH ke server production
echo 2. Jalankan: php artisan optimize:clear
echo.
echo TEST INSTRUCTIONS:
echo 1. Coba daftar dengan email yang sudah ada
echo 2. Pastikan pesan error user-friendly muncul:
echo    "Email ini sudah terdaftar di sistem kami..."
echo.
echo 3. Coba daftar dengan NISN yang sudah ada
echo 4. Pastikan pesan error user-friendly muncul:
echo    "NISN ini sudah terdaftar di sistem kami..."
echo.
pause
