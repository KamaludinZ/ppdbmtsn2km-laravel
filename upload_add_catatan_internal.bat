@echo off
echo ========================================
echo UPLOAD FITUR CATATAN INTERNAL
echo ========================================
echo.
echo Fitur: Catatan Internal untuk Admin/Panitia
echo Tanggal: 5 Februari 2026
echo.

REM Upload migrations
echo [1/6] Uploading migrations...
scp database/migrations/2026_02_05_130000_add_catatan_internal_to_daftar_ulang_table.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/
scp database/migrations/2026_02_05_130001_add_catatan_internal_to_seragam_table.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload migrations
    pause
    exit /b 1
)
echo Migrations uploaded successfully!
echo.

REM Upload models
echo [2/6] Uploading models...
scp app/Models/DaftarUlang.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/
scp app/Models/Seragam.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload models
    pause
    exit /b 1
)
echo Models uploaded successfully!
echo.

REM Upload Admin controller
echo [3/6] Uploading Admin DaftarUlang controller...
scp app/Livewire/Admin/DaftarUlang.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload Admin controller
    pause
    exit /b 1
)
echo Admin controller uploaded successfully!
echo.

REM Upload Panitia controller
echo [4/6] Uploading Panitia DaftarUlang controller...
scp app/Livewire/Panitia/DaftarUlang.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload Panitia controller
    pause
    exit /b 1
)
echo Panitia controller uploaded successfully!
echo.

REM Upload Admin view
echo [5/7] Uploading Admin DaftarUlang view...
scp resources/views/livewire/admin/daftar-ulang.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload Admin view
    pause
    exit /b 1
)
echo Admin view uploaded successfully!
echo.

REM Upload Panitia view
echo [6/7] Uploading Panitia DaftarUlang view...
scp resources/views/livewire/panitia/daftar-ulang.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload Panitia view
    pause
    exit /b 1
)
echo Panitia view uploaded successfully!
echo.

REM Run migrations on production
echo [7/7] Running migrations on production...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan migrate --force"
if %errorlevel% neq 0 (
    echo ERROR: Failed to run migrations
    pause
    exit /b 1
)
echo Migrations completed successfully!
echo.

REM Clear cache
echo Clearing production cache...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
if %errorlevel% neq 0 (
    echo WARNING: Failed to clear cache
)
echo Cache cleared!
echo.

echo ========================================
echo UPLOAD COMPLETED SUCCESSFULLY!
echo ========================================
echo.
echo Files uploaded:
echo - 2 migrations (catatan_internal columns)
echo - 2 models (DaftarUlang, Seragam)
echo - 2 controllers (Admin, Panitia)
echo - 2 views (Admin, Panitia)
echo.
echo TESTING CHECKLIST:
echo [ ] Test add catatan internal di Admin - Madrasah
echo [ ] Test add catatan internal di Admin - Seragam
echo [ ] Test add catatan internal di Admin - Ukuran
echo [ ] Test add catatan internal di Panitia - Madrasah
echo [ ] Verify catatan_internal TIDAK tampil di /pendaftar/daftarulang
echo.
pause
