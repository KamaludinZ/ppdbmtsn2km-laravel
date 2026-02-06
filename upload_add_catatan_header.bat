@echo off
echo ========================================
echo UPLOAD ADD CATATAN HEADER
echo ========================================
echo.

REM Upload detail-pendaftar.blade.php yang sudah diperbaiki
echo [1/1] Uploading detail-pendaftar.blade.php (Admin)...
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\detail-pendaftar.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload detail-pendaftar.blade.php
    pause
    exit /b 1
)

echo.
echo ========================================
echo CLEARING CACHE
echo ========================================
echo.

REM Clear all caches
echo Clearing view cache...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear"

echo Clearing config cache...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan config:clear"

echo Clearing application cache...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan cache:clear"

echo.
echo ========================================
echo UPLOAD COMPLETED!
echo ========================================
echo.
echo Files uploaded:
echo - resources/views/livewire/admin/detail-pendaftar.blade.php
echo.
echo Feature Added:
echo.
echo [CATATAN VERIFIKASI DI HEADER]
echo.
echo 1. Lokasi: Header Info Detail Pendaftar (/admin/pendaftar/{id})
echo 2. Tampil Hanya Jika Status:
echo    - lengkapi_data
echo    - perbaiki_data
echo    - data_ditolak
echo.
echo 3. Konten Catatan:
echo    - Jika catatan_verifikasi ada isi: Tampilkan isi catatan
echo    - Jika catatan_verifikasi kosong: "Catatan belum di isi"
echo.
echo 4. Tampilan:
echo    - Icon chat bubble
echo    - Label: "Catatan untuk Pendaftar"
echo    - Box dengan background semi-transparan
echo    - Teks putih (jika ada isi) atau teks abu-abu italic (jika kosong)
echo.
echo Please test:
echo 1. Login sebagai admin
echo 2. Akses /admin/pendaftar/{id} dengan berbagai status:
echo    a. Status lengkapi_data = Catatan muncul
echo    b. Status perbaiki_data = Catatan muncul
echo    c. Status data_ditolak = Catatan muncul
echo    d. Status menunggu_verifikasi = Catatan TIDAK muncul
echo    e. Status data_disetujui = Catatan TIDAK muncul
echo.
echo 3. Test dengan catatan kosong vs berisi
echo.
pause
