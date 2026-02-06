@echo off
echo ========================================
echo UPLOAD: Fix Kartu Tes Seleksi Access
echo Tambah Kondisi Upload Bukti Transfer
echo ========================================
echo.
echo Tanggal: 5 Februari 2026
echo Total Files: 2
echo Migration: NO
echo.
echo NEW ACCESS CONDITIONS:
echo 1. Status pendaftaran = data_disetujui
echo 2. Sudah upload bukti transfer tes psikologi
echo.
echo ========================================
echo.

REM Change to project directory
cd /d C:\ppdbmtsn2km-laravel

echo [1/2] Uploading Dashboard Layout...
scp resources/views/components/layouts/dashboard.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/components/layouts/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload dashboard layout
    pause
    exit /b 1
)
echo SUCCESS: Dashboard layout uploaded
echo.

echo [2/2] Uploading Kartu Tes Seleksi Controller...
scp app/Livewire/Pendaftar/KartuTesSeleksi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload controller
    pause
    exit /b 1
)
echo SUCCESS: Controller uploaded
echo.

echo ========================================
echo [3/3] Clearing Cache...
echo ========================================
echo.

ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear && php artisan view:clear"
if %errorlevel% neq 0 (
    echo WARNING: Cache clear may have failed
)
echo SUCCESS: Cache cleared
echo.

echo ========================================
echo UPLOAD COMPLETE!
echo ========================================
echo.
echo Summary of Changes:
echo.
echo FILE 1: resources/views/components/layouts/dashboard.blade.php
echo   - Updated canAccessKartuUjian condition
echo   - Added check for bukti transfer tes psikologi
echo   - Updated tooltip message
echo.
echo FILE 2: app/Livewire/Pendaftar/KartuTesSeleksi.php
echo   - Added access check in mount() method
echo   - Redirect to dashboard if conditions not met
echo   - Show error message to user
echo.
echo ACCESS CONDITIONS (BOTH REQUIRED):
echo   1. Status pendaftaran = data_disetujui ✅
echo   2. Sudah upload bukti transfer tes psikologi ✅
echo.
echo ========================================
echo TESTING CHECKLIST
echo ========================================
echo.
echo Test 1: Menu Disabled State
echo    a. Login sebagai pendaftar (status != data_disetujui)
echo    b. ✅ Menu "Kartu Tes Seleksi" should be grayed out/disabled
echo    c. ✅ Tooltip shows: "Menu ini dapat diakses setelah..."
echo.
echo Test 2: Menu Disabled (No Bukti Transfer)
echo    a. Login sebagai pendaftar (status = data_disetujui)
echo    b. BUT belum upload bukti transfer tes psikologi
echo    c. ✅ Menu "Kartu Tes Seleksi" should be grayed out/disabled
echo.
echo Test 3: Menu Enabled (All Conditions Met)
echo    a. Login sebagai pendaftar (status = data_disetujui)
echo    b. AND sudah upload bukti transfer tes psikologi
echo    c. ✅ Menu "Kartu Tes Seleksi" should be active/clickable
echo    d. ✅ Can access /pendaftar/kartu-tes-seleksi
echo.
echo Test 4: Direct URL Access (Blocked)
echo    a. Login sebagai pendaftar (conditions not met)
echo    b. Try direct URL: /pendaftar/kartu-tes-seleksi
echo    c. ✅ Should redirect to /pendaftar/dashboard
echo    d. ✅ Show error message: "Kartu Tes Seleksi hanya dapat..."
echo.
echo Documentation: FIX_KARTU_TES_SELEKSI_ACCESS_COMPLETE.md
echo.
echo ========================================
pause
