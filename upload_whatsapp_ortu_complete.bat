@echo off
echo ========================================
echo   Upload WhatsApp Ortu dan Pengumuman
echo ========================================
echo.
echo Tanggal: %date% %time%
echo.

echo [1/6] Uploading Auth/Register.php...
scp app/Livewire/Auth/Register.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Auth/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload Register.php
    pause
    exit /b 1
)
echo OK - Register.php uploaded

echo.
echo [2/6] Uploading Admin/Pendaftar.php...
scp app/Livewire/Admin/Pendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload Admin/Pendaftar.php
    pause
    exit /b 1
)
echo OK - Admin/Pendaftar.php uploaded

echo.
echo [3/6] Uploading Panitia/Pendaftar.php...
scp app/Livewire/Panitia/Pendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload Panitia/Pendaftar.php
    pause
    exit /b 1
)
echo OK - Panitia/Pendaftar.php uploaded

echo.
echo [4/6] Uploading Admin/pendaftar.blade.php...
scp resources/views/livewire/admin/pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload admin pendaftar view
    pause
    exit /b 1
)
echo OK - Admin pendaftar view uploaded

echo.
echo [5/6] Uploading Panitia/pendaftar.blade.php...
scp resources/views/livewire/panitia/pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload panitia pendaftar view
    pause
    exit /b 1
)
echo OK - Panitia pendaftar view uploaded

echo.
echo [6/6] Uploading Pendaftar/dashboard.blade.php...
scp resources/views/livewire/pendaftar/dashboard.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload pendaftar dashboard view
    pause
    exit /b 1
)
echo OK - Pendaftar dashboard view uploaded

echo.
echo ========================================
echo   Upload Selesai!
echo ========================================
echo.
echo Langkah selanjutnya:
echo.
echo 1. Clear cache di server:
echo    ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear && php artisan cache:clear"
echo.
echo 2. Test di production:
echo    a. Test registrasi baru (cek WhatsApp ke orang tua)
echo    b. Test kirim WhatsApp manual dari Admin
echo    c. Test edit nomor ortu WhatsApp (Admin dan Panitia)
echo    d. Test tampilan pengumuman di dashboard pendaftar
echo.
echo 3. Monitor log error:
echo    ssh mtsnkot4@103.30.244.77 "tail -100 /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/storage/logs/laravel.log"
echo.

pause
