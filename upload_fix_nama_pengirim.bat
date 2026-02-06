@echo off
echo ========================================
echo UPLOAD FIX NAMA PENGIRIM - DAFTAR ULANG
echo ========================================
echo.

REM Upload migrations
echo [1/7] Uploading migrations...
scp database/migrations/2026_02_05_120000_add_nama_pengirim_to_daftar_ulang_table.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/
scp database/migrations/2026_02_05_120001_add_nama_pengirim_to_seragam_table.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload migrations
    pause
    exit /b 1
)
echo Migrations uploaded successfully!
echo.

REM Upload models
echo [2/7] Uploading models...
scp app/Models/DaftarUlang.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/
scp app/Models/Seragam.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload models
    pause
    exit /b 1
)
echo Models uploaded successfully!
echo.

REM Upload controller
echo [3/7] Uploading controller...
scp app/Livewire/Pendaftar/DaftarUlang.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload controller
    pause
    exit /b 1
)
echo Controller uploaded successfully!
echo.

REM Upload view
echo [4/7] Uploading view...
scp resources/views/livewire/pendaftar/daftar-ulang.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload view
    pause
    exit /b 1
)
echo View uploaded successfully!
echo.

REM Upload invoices
echo [5/7] Uploading invoice PDFs...
scp resources/views/pdf/invoice-daftar-ulang-madrasah.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/pdf/
scp resources/views/pdf/invoice-daftar-ulang-seragam.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/pdf/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload invoices
    pause
    exit /b 1
)
echo Invoices uploaded successfully!
echo.

REM Upload exports
echo [6/7] Uploading export classes...
scp app/Exports/DaftarUlangMadrasahSheet.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/
scp app/Exports/DaftarUlangSeragamSheet.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload exports
    pause
    exit /b 1
)
echo Exports uploaded successfully!
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
echo Semua file telah diupload dan migrasi berhasil dijalankan.
echo.
pause
