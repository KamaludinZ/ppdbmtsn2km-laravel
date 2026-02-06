@echo off
echo ========================================
echo FIX: STATUS ALERT CATATAN DISPLAY
echo ========================================
echo.
echo Perubahan:
echo - Catatan sekarang ditampilkan untuk semua user (termasuk pendaftar)
echo - Label berbeda untuk admin/panitia vs pendaftar
echo - Tambahan whitespace-pre-line untuk formatting
echo.
echo File yang akan diupload:
echo [1] resources/views/components/status-alert.blade.php
echo.
pause

echo.
echo [1/2] Uploading status-alert component...
scp resources/views/components/status-alert.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/components/

if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Upload failed!
    pause
    exit /b 1
)

echo.
echo [2/2] Clearing production cache...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear"

if %ERRORLEVEL% NEQ 0 (
    echo WARNING: Cache clear failed, but file was uploaded
    pause
    exit /b 1
)

echo.
echo ========================================
echo UPLOAD COMPLETED SUCCESSFULLY!
echo ========================================
echo.
echo Silakan test:
echo 1. Login sebagai pendaftar dengan status "perbaiki"
echo 2. Buka halaman daftar ulang
echo 3. Verify catatan dari admin muncul dengan label: "Catatan dari Admin/Panitia:"
echo.
pause
