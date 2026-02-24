@echo off
echo ========================================
echo Upload Perbaikan Fitur Mode Opsi Seragam
echo Tanggal: 21 Februari 2026
echo ========================================
echo.
echo PERBAIKAN:
echo 1. Reset opsi ketika ganti paket
echo 2. Fix konfirmasi nominal sum untuk checkbox
echo.

REM Upload file yang diperbaiki
echo [1/3] Uploading Pendaftar DaftarUlang Component (method updatedPaketSeragam)...
timeout /t 60 /nobreak >nul
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Pendaftar\DaftarUlang.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/

echo.
echo [2/3] Uploading Pendaftar Daftar Ulang Blade View (fix detection dan konfirmasi)...
timeout /t 60 /nobreak >nul
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\pendaftar\daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/

echo.
echo [3/3] Uploading Dokumentasi Perbaikan...
timeout /t 30 /nobreak >nul
scp "C:\ppdbmtsn2km-laravel\PERBAIKAN_FITUR_MODE_OPSI_SERAGAM_21_FEB_2026.md" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/

echo.
echo Running cache clear di server...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"

echo.
echo ========================================
echo Upload Perbaikan Selesai!
echo ========================================
echo.
echo File yang diupload:
echo 1. app/Livewire/Pendaftar/DaftarUlang.php
echo 2. resources/views/livewire/pendaftar/daftar-ulang.blade.php
echo 3. PERBAIKAN_FITUR_MODE_OPSI_SERAGAM_21_FEB_2026.md
echo.
echo TESTING CHECKLIST:
echo.
echo Test 1: Ganti Paket
echo - Login sebagai pendaftar
echo - Pilih paket A (checkbox) dan centang beberapa opsi
echo - Ganti ke paket B (radio)
echo - Verifikasi: opsi otomatis di-reset
echo.
echo Test 2: Konfirmasi Nominal Checkbox
echo - Pilih paket dengan mode checkbox
echo - Centang 2-3 opsi berbeda
echo - Verifikasi: konfirmasi nominal menampilkan TOTAL SUM
echo - Verifikasi: ada rincian detail per item
echo.
pause
