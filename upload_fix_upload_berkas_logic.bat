@echo off
echo ========================================
echo UPLOAD FIX UPLOAD BERKAS LOGIC
echo ========================================
echo.

REM Upload Dashboard.php yang sudah diperbaiki
echo [1/1] Uploading Dashboard.php (Livewire)...
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Pendaftar\Dashboard.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload Dashboard.php
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

echo Optimizing application...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"

echo.
echo ========================================
echo UPLOAD COMPLETED!
echo ========================================
echo.
echo Files uploaded:
echo - app/Livewire/Pendaftar/Dashboard.php
echo.
echo Fixed Logic for Upload Berkas (Step 3):
echo.
echo [SELESAI] Status "Selesai" jika:
echo   - Semua dokumen WAJIB sudah diupload DAN
echo   - Semua dokumen yang diupload (wajib + tidak wajib) sudah DISETUJUI
echo.
echo [PERBAIKI] Status "Perbaiki" jika:
echo   - Ada salah satu dokumen yang statusnya "tidak_sesuai" atau "perlu_perbaikan"
echo.
echo [MENUNGGU] Status "Menunggu" jika:
echo   - Semua dokumen WAJIB sudah diupload DAN
echo   - Ada salah satu dokumen yang statusnya "menunggu_verifikasi"
echo.
echo [LENGKAPI] Status "Lengkapi" jika:
echo   - Belum semua dokumen WAJIB diupload
echo.
echo New Methods Added:
echo   1. areAllUploadedDocumentsApproved() - Check if all uploaded docs approved
echo   2. hasAnyDocumentWaiting() - Check if any document is waiting
echo.
echo Please test:
echo 1. Login as pendaftar dengan berbagai kondisi dokumen
echo 2. Cek progress "Upload Berkas" dengan skenario:
echo    a. Semua dokumen disetujui (wajib + tidak wajib) = Selesai
echo    b. Ada dokumen yang menunggu = Menunggu
echo    c. Ada dokumen yang perlu diperbaiki = Perbaiki
echo    d. Belum lengkap = Lengkapi
echo.
pause
