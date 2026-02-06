@echo off
echo ========================================
echo FIX: SYNTAX ERROR - INDENTATION
echo ========================================
echo.
echo Perubahan:
echo - Perbaikan indentasi line 1923 dari 4 spaces ke 8 spaces
echo - Menutup struktur @if-@else dengan benar
echo - Menyelesaikan ParseError "unexpected token endif"
echo.
echo File yang akan diupload:
echo [1] resources/views/livewire/pendaftar/daftar-ulang.blade.php
echo.
pause

echo.
echo [1/2] Uploading daftar-ulang blade file...
scp resources/views/livewire/pendaftar/daftar-ulang.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/

if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Upload failed!
    pause
    exit /b 1
)

echo.
echo [2/2] Clearing production cache...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear && php artisan optimize:clear"

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
echo 1. Akses /pendaftar/daftarulang
echo 2. Verify tidak ada ParseError
echo 3. Test semua tab (Madrasah, Seragam, Ukuran)
echo 4. Verify footer tidak broken
echo 5. Test switch antar tab
echo.
pause
