@echo off
echo ========================================
echo   Upload Fix: Validation Messages
echo   dan Duplicate Key Error
echo ========================================
echo.
echo Tanggal: %date% %time%
echo.

echo [1/2] Uploading Formulir.php (Validation Messages Fix)...
scp app/Livewire/Pendaftar/Formulir.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload Formulir.php
    pause
    exit /b 1
)
echo OK - Formulir.php uploaded
echo.

echo [2/2] Uploading JalurPendaftaran.php (Duplicate Key Fix)...
scp app/Models/JalurPendaftaran.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload JalurPendaftaran.php
    pause
    exit /b 1
)
echo OK - JalurPendaftaran.php uploaded
echo.

echo ========================================
echo   Upload Selesai!
echo ========================================
echo.
echo PENTING: Jalankan perintah berikut di server:
echo.
echo ssh mtsnkot4@103.30.244.77
echo cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
echo php artisan cache:clear
echo php artisan config:clear
echo php artisan view:clear
echo.
echo ========================================
echo   Test Validation Messages:
echo ========================================
echo.
echo 1. Login sebagai Pendaftar
echo 2. Buka Formulir Pendaftaran
echo 3. Kosongkan field wajib -^> Save
echo 4. Error harus muncul dalam Bahasa Indonesia
echo    (BUKAN "validation.required")
echo.
echo ========================================
echo   Test Duplicate Key Fix:
echo ========================================
echo.
echo 1. Monitor log real-time:
echo    tail -f storage/logs/laravel.log
echo.
echo 2. Biarkan multiple users register bersamaan
echo.
echo 3. Pastikan tidak ada error:
echo    "duplicate key value violates unique constraint"
echo.
echo ========================================
echo   Monitoring Production:
echo ========================================
echo.
echo # Cek error duplicate key
echo ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && tail -100 storage/logs/laravel.log | grep -i duplicate"
echo.
echo # Cek validation errors
echo ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && tail -100 storage/logs/laravel.log | grep -i validation"
echo.
echo # Test generate nomor pendaftaran
echo php artisan tinker
echo ^> $jalur = \App\Models\JalurPendaftaran::first();
echo ^> $jalur-^>generateNomorPendaftaran();
echo.

pause
