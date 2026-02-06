@echo off
echo ========================================
echo   Upload Fix Email dan WhatsApp Ortu
echo ========================================
echo.
echo Tanggal: %date% %time%
echo.

echo [1/3] Uploading Admin Pendaftar View...
scp resources/views/livewire/admin/pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload admin pendaftar view
    pause
    exit /b 1
)
echo OK - Admin Pendaftar View uploaded

echo.
echo [2/3] Uploading Panitia Pendaftar View...
scp resources/views/livewire/panitia/pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload panitia pendaftar view
    pause
    exit /b 1
)
echo OK - Panitia Pendaftar View uploaded

echo.
echo [3/3] Uploading Admin Pendaftar Controller...
scp app/Livewire/Admin/Pendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload admin pendaftar controller
    pause
    exit /b 1
)
echo OK - Admin Pendaftar Controller uploaded

echo.
echo ========================================
echo   Upload Selesai!
echo ========================================
echo.
echo Langkah selanjutnya:
echo 1. Clear cache di server dengan perintah:
echo    ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear && php artisan cache:clear"
echo.
echo 2. Test di production:
echo    - Login sebagai Admin
echo    - Buka halaman Data Pendaftar
echo    - Cek kolom "Email" (bukan "Kontak")
echo    - Test tombol "Kirim WhatsApp"
echo.
echo 3. Monitor log error:
echo    ssh mtsnkot4@103.30.244.77 "tail -100 /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/storage/logs/laravel.log"
echo.

pause
