@echo off
echo ========================================
echo UPLOAD KOLOM CATATAN ADMIN PENDAFTAR
echo 24 FEBRUARI 2026 - WITH FIX
echo ========================================
echo.
echo INCLUDES FIX: Column order alignment
echo ========================================
echo.

echo [1/2] Uploading Pendaftar.php Livewire component...
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Admin\Pendaftar.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
if errorlevel 1 (
    echo ERROR: Failed to upload Pendaftar.php
    pause
    exit /b 1
)
echo SUCCESS: Pendaftar.php uploaded
echo.

echo [2/2] Uploading pendaftar.blade.php view...
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\pendaftar.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
if errorlevel 1 (
    echo ERROR: Failed to upload pendaftar.blade.php
    pause
    exit /b 1
)
echo SUCCESS: pendaftar.blade.php uploaded
echo.

echo ========================================
echo Clearing cache on production...
echo ========================================
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear && php artisan cache:clear && php artisan config:clear"
echo.

echo ========================================
echo UPLOAD COMPLETE!
echo ========================================
echo.
echo All files have been uploaded successfully.
echo Cache has been cleared on production.
echo.
echo Please test the following:
echo 1. Buka https://pmbm.mtsn2kotamalang.sch.id/admin/pendaftar
echo 2. Check kolom "Catatan" muncul di tabel
echo 3. Click tombol edit (icon pensil) di kolom catatan
echo 4. Modal muncul dengan blur background
echo 5. Edit catatan dan simpan
echo 6. Verify catatan tersimpan dan muncul di tabel
echo 7. Test truncate untuk catatan panjang (^>50 karakter)
echo 8. Test character counter (max 1000 karakter)
echo 9. Test click outside modal untuk close
echo 10. Test column visibility toggle (Filter Kolom)
echo.
pause
