@echo off
echo ========================================
echo UPLOAD FIX ALPINE.JS SYNTAX ERROR
echo ========================================
echo.
echo Fix untuk error di /panitia-psikologi/laporan:
echo - Uncaught SyntaxError: Unexpected token 'if'
echo.
echo Perbaikan:
echo - Wrap if statement dalam IIFE (Immediately Invoked Function Expression)
echo - Menghindari syntax error Alpine.js saat menggunakan if di x-init
echo.

echo [1/2] Upload file yang diperbaiki...
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\panitia\laporan.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/
if %errorlevel% neq 0 (
    echo ERROR: Upload gagal!
    pause
    exit /b 1
)
echo ✓ Upload berhasil
echo.

echo [2/2] Clear cache di production...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear && php artisan cache:clear && echo '✅ Cache cleared'"
if %errorlevel% neq 0 (
    echo WARNING: Clear cache gagal
) else (
    echo ✓ Cache cleared
)
echo.

echo ========================================
echo SELESAI
echo ========================================
echo.
echo Test di:
echo https://pmbm.mtsn2kotamalang.sch.id/panitia-psikologi/laporan
echo.
echo Error di console seharusnya sudah hilang!
echo.
pause
