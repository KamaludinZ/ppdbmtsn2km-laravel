@echo off
echo ============================================
echo UPLOAD FIX KODE POS ERROR 500
echo ============================================
echo.
echo File yang akan diupload:
echo - app/Livewire/Pendaftar/Formulir.php
echo.
pause

echo.
echo [1/2] Upload Formulir.php...
scp app/Livewire/Pendaftar/Formulir.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if %errorlevel% neq 0 (
    echo GAGAL upload Formulir.php
    pause
    exit /b 1
)
echo BERHASIL upload Formulir.php

echo.
echo [2/2] Clear cache production...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan cache:clear && php artisan view:clear"
if %errorlevel% neq 0 (
    echo WARNING: Gagal clear cache, silakan manual
) else (
    echo BERHASIL clear cache
)

echo.
echo ============================================
echo UPLOAD SELESAI!
echo ============================================
echo.
echo CATATAN:
echo - Error 500 di field kode pos sudah diperbaiki
echo - Sekarang jika query postal_codes gagal, field tetap kosong
echo - User bisa input kode pos manual
echo - Error akan dicatat di log untuk monitoring
echo.
pause
