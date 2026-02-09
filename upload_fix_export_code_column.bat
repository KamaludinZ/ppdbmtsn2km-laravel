@echo off
echo ============================================
echo UPLOAD FIX EXPORT ERROR - COLUMN CODE
echo ============================================
echo.
echo MASALAH:
echo - Error "column code does not exist" di production
echo - Tabel wilayah di production tidak punya kolom 'code'
echo - PendaftarExport.php mencoba query kolom 'code'
echo.
echo SOLUSI:
echo - Ubah query dari 'code' ke 'id'
echo - Gunakan 'id' sebagai key cache
echo - Simplifikasi method getRegionName()
echo.
echo File yang akan diupload:
echo - app/Exports/PendaftarExport.php
echo.
pause

echo.
echo [1/2] Upload PendaftarExport.php...
scp app/Exports/PendaftarExport.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/
if %errorlevel% neq 0 (
    echo GAGAL upload PendaftarExport.php
    pause
    exit /b 1
)
echo BERHASIL upload PendaftarExport.php

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
echo - Error "column code does not exist" sudah diperbaiki
echo - Export data pendaftar sekarang menggunakan id bukan code
echo - Cache region menggunakan id sebagai key
echo - Silakan test export data pendaftar di production
echo.
pause
