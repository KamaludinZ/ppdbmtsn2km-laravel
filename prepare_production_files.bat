@echo off
echo ========================================
echo  PREPARE FILES FOR PRODUCTION UPLOAD
echo ========================================
echo.

REM Create production folder
set PROD_FOLDER=production_upload_%date:~-4,4%%date:~-7,2%%date:~-10,2%_%time:~0,2%%time:~3,2%
set PROD_FOLDER=%PROD_FOLDER: =0%
mkdir "%PROD_FOLDER%"

echo Creating folder structure...

REM Create directory structure
mkdir "%PROD_FOLDER%\database\migrations"
mkdir "%PROD_FOLDER%\app\Models"
mkdir "%PROD_FOLDER%\app\Livewire\Admin"
mkdir "%PROD_FOLDER%\app\Livewire\Auth"
mkdir "%PROD_FOLDER%\resources\views\livewire\admin"
mkdir "%PROD_FOLDER%\routes"

echo.
echo Copying files...
echo.

REM Copy Migration
echo [1/10] Copying migration...
copy "database\migrations\2026_01_23_053343_add_tampil_di_daftar_to_jalur_pendaftaran_table.php" "%PROD_FOLDER%\database\migrations\" >nul

REM Copy Model
echo [2/10] Copying JalurPendaftaran model...
copy "app\Models\JalurPendaftaran.php" "%PROD_FOLDER%\app\Models\" >nul

REM Copy Livewire Admin
echo [3/10] Copying Admin Jalur component...
copy "app\Livewire\Admin\Jalur.php" "%PROD_FOLDER%\app\Livewire\Admin\" >nul

REM Copy Livewire Auth
echo [4/10] Copying Register.php...
copy "app\Livewire\Auth\Register.php" "%PROD_FOLDER%\app\Livewire\Auth\" >nul

echo [5/10] Copying RegisterFixed.php...
copy "app\Livewire\Auth\RegisterFixed.php" "%PROD_FOLDER%\app\Livewire\Auth\" >nul

echo [6/10] Copying RegisterOptimized.php...
copy "app\Livewire\Auth\RegisterOptimized.php" "%PROD_FOLDER%\app\Livewire\Auth\" >nul

REM Copy View
echo [7/10] Copying jalur.blade.php view...
copy "resources\views\livewire\admin\jalur.blade.php" "%PROD_FOLDER%\resources\views\livewire\admin\" >nul

REM Copy optional test files
echo [8/10] Copying test_jalur.php route (optional)...
copy "routes\test_jalur.php" "%PROD_FOLDER%\routes\" >nul

echo [9/10] Copying check scripts (optional)...
copy "check_jalur_status.php" "%PROD_FOLDER%\" >nul
copy "test_daftar_jalur.php" "%PROD_FOLDER%\" >nul

REM Copy documentation
echo [10/10] Copying documentation...
copy "UPLOAD_TO_PRODUCTION.md" "%PROD_FOLDER%\" >nul
copy "FIX_STATUS_DAFTAR_FINAL.md" "%PROD_FOLDER%\" >nul
copy "FITUR_STATUS_DAFTAR_JALUR.md" "%PROD_FOLDER%\" >nul

echo.
echo ========================================
echo  FILES READY!
echo ========================================
echo.
echo All files copied to: %PROD_FOLDER%
echo.
echo Next steps:
echo 1. Review files in %PROD_FOLDER%
echo 2. Upload to production server via FTP/SFTP
echo 3. SSH to server and run: php artisan migrate
echo 4. Clear cache: php artisan optimize:clear
echo.
echo See UPLOAD_TO_PRODUCTION.md for detailed instructions
echo.
pause
