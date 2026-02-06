@echo off
echo ========================================
echo   CRITICAL FIX: PostgreSQL UNSIGNED Error
echo ========================================
echo.
echo Error: type "unsigned" does not exist
echo Fix: Change UNSIGNED to INTEGER in SQL query
echo.
echo Tanggal: %date% %time%
echo.

echo [1/1] Uploading JalurPendaftaran.php (Fix UNSIGNED -^> INTEGER)...
scp app/Models/JalurPendaftaran.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload file
    pause
    exit /b 1
)
echo OK - File uploaded
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
echo.
echo ========================================
echo   Test Registration:
echo ========================================
echo.
echo 1. Monitor log:
echo    tail -f storage/logs/laravel.log ^| grep -i "unsigned\|Registration"
echo.
echo 2. Test via tinker:
echo    php artisan tinker
echo    ^> $jalur = \App\Models\JalurPendaftaran::first();
echo    ^> $nomor = $jalur-^>generateNomorPendaftaran();
echo    ^> echo $nomor;
echo    # Harus output nomor baru tanpa error
echo.
echo 3. Test registration via browser:
echo    - Buka /daftar
echo    - Pilih jalur
echo    - Submit
echo    - Harus sukses tanpa error "unsigned"
echo.
echo ========================================
echo   Expected Result:
echo ========================================
echo.
echo BEFORE: SQLSTATE[42704]: type "unsigned" does not exist
echo AFTER:  Registration sukses, nomor pendaftaran generated
echo.

pause
