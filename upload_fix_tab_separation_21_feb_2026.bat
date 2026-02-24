@echo off
echo ========================================
echo UPLOAD FIX: Tab Separation Seragam-Ukuran
echo 21 Februari 2026
echo ========================================
echo.

echo [1/4] Uploading Migration...
timeout /t 2 /nobreak >nul
scp database/migrations/2026_02_21_210000_add_belum_diisi_to_seragam_status_enum.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Migration upload failed!
    pause
    exit /b 1
)
echo Migration uploaded successfully!
echo.

echo [2/4] Uploading Updated DaftarUlang.php...
timeout /t 2 /nobreak >nul
scp app/Livewire/Pendaftar/DaftarUlang.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: DaftarUlang.php upload failed!
    pause
    exit /b 1
)
echo DaftarUlang.php uploaded successfully!
echo.

echo [3/4] Uploading Documentation...
timeout /t 2 /nobreak >nul
scp FIX_TAB_SEPARATION_SERAGAM_UKURAN_21_FEB_2026.md mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/
if %ERRORLEVEL% NEQ 0 (
    echo WARNING: Documentation upload failed (non-critical)
)
echo Documentation uploaded!
echo.

echo [4/4] Running Migration on Production...
timeout /t 2 /nobreak >nul
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan migrate --force"
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Migration execution failed!
    echo Please check production logs!
    pause
    exit /b 1
)
echo Migration executed successfully!
echo.

echo ========================================
echo UPLOAD COMPLETE!
echo ========================================
echo.
echo Files uploaded:
echo - Migration: 2026_02_21_210000_add_belum_diisi_to_seragam_status_enum.php
echo - DaftarUlang.php (updated)
echo - Documentation (markdown)
echo.
echo Migration executed on production!
echo.
echo Next: Test di production
echo - Login sebagai pendaftar
echo - Submit Tab Ukuran saja
echo - Verifikasi Tab Seragam masih "belum_diisi"
echo.
pause
