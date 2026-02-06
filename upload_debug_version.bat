@echo off
echo ============================================================
echo UPLOAD DEBUG VERSION - Survey Overwrite Investigation
echo ============================================================
echo.
echo This version includes extensive logging to help us understand
echo why the overwrite bug still occurs after the fix.
echo.

echo [1/1] Uploading TesPsikologi.php (DEBUG VERSION with logging)...
scp app/Livewire/Pendaftar/TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload TesPsikologi.php
    pause
    exit /b 1
)
echo OK - TesPsikologi.php uploaded

echo.
echo ============================================================
echo UPLOAD SELESAI!
echo ============================================================
echo.
echo NEXT STEPS:
echo.
echo 1. Clear production cache:
echo    ssh mtsnkot4@103.30.244.77
echo    cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
echo    php artisan view:clear
echo    php artisan optimize:clear
echo.
echo 2. Test submit survey sebagai pendaftar (JPNA-26020002 atau yang baru)
echo    - Jawab beberapa pertanyaan dengan jawaban berbeda
echo    - Klik "Selesai & Simpan"
echo.
echo 3. Lihat log file untuk analisa:
echo    tail -100 storage/logs/laravel.log
echo.
echo 4. Copy hasil log dan kirim ke saya untuk analisa
echo.
pause
