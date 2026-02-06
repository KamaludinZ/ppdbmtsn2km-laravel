@echo off
echo ========================================
echo UPLOAD: Add Nama Pengirim Field
echo Tes Psikologi Payment Form + Export
echo ========================================
echo.
echo Tanggal: 5 Februari 2026
echo Total Files: 8
echo Migration: YES
echo.
echo ========================================
echo.

REM Change to project directory
cd /d C:\ppdbmtsn2km-laravel

echo [1/8] Uploading migration file...
scp database/migrations/2026_02_05_100000_add_nama_pengirim_to_tes_psikologi_pendaftar_table.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload migration file
    pause
    exit /b 1
)
echo SUCCESS: Migration uploaded
echo.

echo [2/8] Uploading Model...
scp app/Models/TesPsikologiPendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload model
    pause
    exit /b 1
)
echo SUCCESS: Model uploaded
echo.

echo [3/8] Uploading Livewire Controller...
scp app/Livewire/Pendaftar/TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload controller
    pause
    exit /b 1
)
echo SUCCESS: Controller uploaded
echo.

echo [4/8] Uploading Upload Form View...
scp resources/views/livewire/pendaftar/tes-psikologi-form.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload form view
    pause
    exit /b 1
)
echo SUCCESS: Form view uploaded
echo.

echo [5/8] Uploading Admin/Panitia Table View...
scp resources/views/livewire/admin/partials/tes-psikologi-data.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload table view
    pause
    exit /b 1
)
echo SUCCESS: Table view uploaded
echo.

echo [6/8] Uploading Invoice PDF...
scp resources/views/pdf/invoice-psikologi.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/pdf/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload invoice PDF
    pause
    exit /b 1
)
echo SUCCESS: Invoice PDF uploaded
echo.

echo [7/9] Uploading Pendaftar Main View...
scp resources/views/livewire/pendaftar/tes-psikologi.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload pendaftar view
    pause
    exit /b 1
)
echo SUCCESS: Pendaftar view uploaded
echo.

echo [8/9] Uploading Export Class...
scp app/Exports/TesPsikologiExport.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload export class
    pause
    exit /b 1
)
echo SUCCESS: Export class uploaded
echo.

echo ========================================
echo [9/9] Running Migration and Clear Cache...
echo ========================================
echo.

ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan migrate --force"
if %errorlevel% neq 0 (
    echo ERROR: Migration failed
    pause
    exit /b 1
)
echo SUCCESS: Migration completed
echo.

echo Clearing cache...
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
echo Summary:
echo - Migration: Added nama_pengirim column
echo - Model: Updated fillable array
echo - Controller: Added property, validation, save/reset
echo - Views: Added input field and display columns
echo - Invoice: Added nama pengirim to payment info
echo - Export: Added nama pengirim column to Excel
echo.
echo Next Steps:
echo 1. Test upload form: /pendaftar/tes-psikologi
echo 2. Test admin table: /admin/tes-psikologi?mainTab=data
echo 3. Test panitia table: /panitia/tes-psikologi?mainTab=data
echo 4. Test invoice PDF download
echo 5. Test Excel export (Export Excel button)
echo.
echo Documentation: ADD_NAMA_PENGIRIM_TES_PSIKOLOGI_COMPLETE.md
echo.
echo ========================================
pause
