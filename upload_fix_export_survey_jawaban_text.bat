@echo off
echo ============================================================
echo  FIX: Ekspor Tes Psikologi - Jawaban Survey Tampil Teks
echo ============================================================
echo.
echo Mengupload file yang diperbaiki...
echo.

scp "app/Exports/TesPsikologiExport.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/TesPsikologiExport.php

if %ERRORLEVEL% EQU 0 (
    echo [OK] TesPsikologiExport.php berhasil diupload
) else (
    echo [ERROR] Gagal upload TesPsikologiExport.php
    pause
    exit /b 1
)

echo.
echo Membersihkan cache...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"

echo.
echo ============================================================
echo  SELESAI! Perubahan yang dilakukan:
echo  1. Eager load ditambah: question.options (mencegah N+1)
echo  2. $answer->jawaban diganti getJawabanFormatted()
echo     sehingga ID opsi dikonversi ke label teks yang benar
echo ============================================================
pause
