@echo off
echo ========================================
echo   Upload Pengumuman Formatting
echo ========================================
echo.
echo Tanggal: %date% %time%
echo.

echo [1/5] Uploading composer.json...
scp composer.json mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload composer.json
    pause
    exit /b 1
)
echo OK - composer.json uploaded

echo.
echo [2/5] Uploading TextFormatter.php...
scp app/Helpers/TextFormatter.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Helpers/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload TextFormatter.php
    pause
    exit /b 1
)
echo OK - TextFormatter.php uploaded

echo.
echo [3/5] Uploading helpers.php...
scp app/Helpers/helpers.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Helpers/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload helpers.php
    pause
    exit /b 1
)
echo OK - helpers.php uploaded

echo.
echo [4/5] Uploading Admin pengumuman view...
scp resources/views/livewire/admin/pengumuman.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload pengumuman view
    pause
    exit /b 1
)
echo OK - Pengumuman view uploaded

echo.
echo [5/5] Uploading Pendaftar dashboard view...
scp resources/views/livewire/pendaftar/dashboard.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload dashboard view
    pause
    exit /b 1
)
echo OK - Dashboard view uploaded

echo.
echo ========================================
echo   Upload Selesai!
echo ========================================
echo.
echo PENTING: Jalankan perintah berikut di server:
echo.
echo ssh mtsnkot4@103.30.244.77
echo cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
echo composer dump-autoload
echo php artisan view:clear
echo php artisan cache:clear
echo.
echo Verifikasi helper:
echo php artisan tinker
echo ^> format_pengumuman('*test* _italic_');
echo.
echo Test di browser:
echo 1. Login Admin -^> Pengumuman -^> Tambah
echo 2. Test format: *bold* _italic_ https://example.com
echo 3. Login Pendaftar -^> Dashboard -^> Cek pengumuman
echo.

pause
