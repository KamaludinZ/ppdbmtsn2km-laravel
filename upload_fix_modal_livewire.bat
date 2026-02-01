@echo off
echo ========================================
echo Upload Fix Modal Livewire Production
echo ========================================
echo.

echo [STEP 1/5] Uploading Register.php (Controller)...
scp app/Livewire/Auth/Register.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Auth/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload Register.php
    pause
    exit /b 1
)
echo SUCCESS: Register.php uploaded

echo.
echo [STEP 2/5] Uploading register.blade.php (View)...
scp resources/views/livewire/auth/register.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/auth/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload register.blade.php
    pause
    exit /b 1
)
echo SUCCESS: register.blade.php uploaded

echo.
echo [STEP 3/5] Clearing cache on server...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear && php artisan cache:clear && php artisan config:clear && echo 'All caches cleared'"
if %errorlevel% neq 0 (
    echo ERROR: Failed to clear cache
    pause
    exit /b 1
)
echo SUCCESS: Cache cleared

echo.
echo [STEP 4/5] Setting proper permissions...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && chmod -R 775 storage bootstrap/cache && echo 'Permissions set'"
echo.

echo [STEP 5/5] Upload complete!
echo.
echo ========================================
echo TESTING INSTRUCTIONS:
echo ========================================
echo.
echo 1. Buka browser dan buka Console (F12 - Console tab)
echo    URL: https://pmbm.mtsn2kotamalang.sch.id/daftar
echo.
echo 2. Perhatikan console logs saat halaman load:
echo    - "Page loaded"
echo    - "Livewire available: true"
echo    - "Alpine available: true"
echo    - "Button found: <button>"
echo.
echo 3. Isi form sampai Step 3 - Data Diri
echo.
echo 4. Klik tombol "Daftar Sekarang"
echo.
echo 5. Perhatikan console logs:
echo    - "handleConfirmSubmit called"
echo    - "Livewire loaded, attempting to call confirmSubmit"
echo    - "Button clicked (via addEventListener)"
echo.
echo 6. Cek apakah modal muncul
echo.
echo 7. Jika modal tidak muncul, cek server logs:
echo    ssh mtsnkot4@103.30.244.77
echo    cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
echo    tail -100 storage/logs/laravel.log ^| grep -i confirmsubmit
echo.
echo ========================================
echo EXPECTED RESULTS:
echo ========================================
echo - Console logs muncul (tidak ada error)
echo - Modal konfirmasi muncul
echo - Server logs tercatat
echo.
echo Jika masih error, lihat dokumentasi lengkap di:
echo FIX_MODAL_LIVEWIRE_ERROR_PRODUCTION.md
echo ========================================
echo.
pause
