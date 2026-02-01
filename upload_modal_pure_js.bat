@echo off
echo ========================================
echo Upload Modal Pure JavaScript Solution
echo ========================================
echo.

echo [1/3] Uploading register.blade.php...
scp resources/views/livewire/auth/register.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/auth/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload
    pause
    exit /b 1
)
echo SUCCESS: File uploaded

echo.
echo [2/3] Clearing cache...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear && php artisan cache:clear && echo 'Cache cleared'"

echo.
echo [3/3] Upload complete!
echo.
echo ========================================
echo TESTING INSTRUCTIONS:
echo ========================================
echo.
echo 1. Buka: https://pmbm.mtsn2kotamalang.sch.id/daftar
echo 2. Buka Console Browser (F12 - Console)
echo 3. Isi form sampai Step 3
echo 4. Klik tombol "Daftar Sekarang"
echo.
echo 5. Console harus muncul:
echo    handleConfirmSubmit called
echo    showConfirmationModal called
echo    Modal shown
echo.
echo 6. Modal harus muncul!
echo.
echo 7. Test modal manual di console:
echo    testModal()
echo.
echo ========================================
echo TROUBLESHOOTING:
echo ========================================
echo.
echo Jika modal tidak muncul:
echo 1. Run di console: document.getElementById('confirmationModal')
echo    (Harus return element, bukan null)
echo.
echo 2. Run di console: testModal()
echo    (Modal harus muncul)
echo.
echo 3. Cek element class:
echo    document.getElementById('confirmationModal').classList
echo    (Jika ada 'hidden', modal tersembunyi)
echo.
echo ========================================
echo.
pause
