@echo off
echo ========================================
echo Upload Fitur Mode Opsi Seragam (Radio/Checkbox)
echo Tanggal: 21 Februari 2026
echo ========================================
echo.

REM Upload file-file yang diubah
echo [1/6] Uploading Admin Daftar Ulang Blade View (settings selection_mode + display kolom)...
timeout /t 60 /nobreak >nul
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/

echo.
echo [2/6] Uploading Pendaftar Daftar Ulang Blade View (checkbox/radio display)...
timeout /t 60 /nobreak >nul
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\pendaftar\daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/

echo.
echo [3/6] Uploading Pendaftar Daftar Ulang Component (logic multi-select)...
timeout /t 60 /nobreak >nul
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Pendaftar\DaftarUlang.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/

echo.
echo [4/6] Uploading Invoice Seragam PDF (multi-select display)...
timeout /t 60 /nobreak >nul
scp "C:\ppdbmtsn2km-laravel\resources\views\pdf\invoice-daftar-ulang-seragam.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/pdf/

echo.
echo [5/6] Uploading Dokumentasi...
timeout /t 30 /nobreak >nul
scp "C:\ppdbmtsn2km-laravel\FITUR_MODE_OPSI_SERAGAM_RADIO_CHECKBOX_21_FEB_2026.md" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/

echo.
echo [6/6] Running cache clear di server...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"

echo.
echo ========================================
echo Upload Selesai!
echo ========================================
echo.
echo File yang diupload:
echo 1. resources/views/livewire/admin/daftar-ulang.blade.php
echo 2. resources/views/livewire/pendaftar/daftar-ulang.blade.php
echo 3. app/Livewire/Pendaftar/DaftarUlang.php
echo 4. resources/views/pdf/invoice-daftar-ulang-seragam.blade.php
echo 5. FITUR_MODE_OPSI_SERAGAM_RADIO_CHECKBOX_21_FEB_2026.md
echo.
echo Silakan test fitur:
echo 1. Login sebagai admin
echo 2. Buka /admin/daftar-ulang?activeTab=settings
echo 3. Edit paket seragam dan pilih mode (radio/checkbox)
echo 4. Login sebagai pendaftar
echo 5. Test pilihan opsi di /pendaftar/daftarulang?activeTab=seragam
echo.
pause
