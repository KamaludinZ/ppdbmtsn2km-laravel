@echo off
echo ================================================
echo UPLOAD PANITIA DETAIL FIXES - 28 JANUARI 2026
echo ================================================
echo.
echo This script will upload:
echo 1. Panitia DetailPendaftar component (region methods fix)
echo 2. Tab data orangtua template (undefined variables fix)
echo.
echo Files to upload:
echo - app/Livewire/Panitia/DetailPendaftar.php
echo - resources/views/tab_templates/tab-data-orangtua.blade.php
echo.
echo Press any key to start upload...
pause >nul

echo.
echo ================================================
echo STEP 1: UPLOAD FILES
echo ================================================
echo.

echo [1/2] Uploading Panitia DetailPendaftar component...
scp app/Livewire/Panitia/DetailPendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/

if %errorlevel% neq 0 (
    echo ❌ Failed to upload DetailPendaftar.php
    echo Check SSH connection and try again.
    pause
    exit /b 1
)

echo ✅ DetailPendaftar.php uploaded successfully
echo.

echo [2/2] Uploading tab-data-orangtua template...
scp resources/views/tab_templates/tab-data-orangtua.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/tab_templates/

if %errorlevel% neq 0 (
    echo ❌ Failed to upload tab-data-orangtua.blade.php
    echo Check SSH connection and try again.
    pause
    exit /b 1
)

echo ✅ tab-data-orangtua.blade.php uploaded successfully
echo.

echo ================================================
echo ✅ ALL FILES UPLOADED SUCCESSFULLY
echo ================================================
echo.

echo ================================================
echo STEP 2: CLEAR CACHE
echo ================================================
echo.
echo Connecting to production server to clear cache...
echo.

ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear && php artisan optimize:clear && echo '✅ Cache cleared successfully!'"

if %errorlevel% neq 0 (
    echo ⚠️ Warning: Failed to clear cache automatically
    echo Please SSH manually and run:
    echo   cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
    echo   php artisan view:clear
    echo   php artisan optimize:clear
    pause
    exit /b 0
)

echo.
echo ================================================
echo STEP 3: VERIFY DEPLOYMENT
echo ================================================
echo.
echo Testing page load...
curl -I https://pmbm.mtsn2kotamalang.sch.id/panitia/pendaftar/27

echo.
echo ================================================
echo ✅ DEPLOYMENT COMPLETE!
echo ================================================
echo.
echo What was fixed:
echo ✅ Issue #3: Undefined variable in Data Orang Tua tab
echo ✅ Issue #4: Missing region methods in Data Alamat tab
echo.
echo Please test in browser:
echo 1. Login as panitia
echo 2. Go to: https://pmbm.mtsn2kotamalang.sch.id/panitia/pendaftar/27
echo 3. Test Data Orang Tua tab (should show parent data)
echo 4. Test Data Alamat tab (should show region names)
echo 5. Verify all other tabs still work
echo.
echo Expected results:
echo ✅ No "Undefined variable" errors
echo ✅ No "Method does not exist" errors
echo ✅ Parent data displays: Ayah, Ibu, Wali
echo ✅ Region names display: JAWA TIMUR, KOTA MALANG
echo ✅ Penghasilan formatted: Rp 5.000.000
echo.
echo If issues occur:
echo - Check Laravel logs: storage/logs/laravel.log
echo - Clear cache again
echo - Refer to: UPLOAD_ALL_FIXES_28_JAN_2026.md
echo.

pause
