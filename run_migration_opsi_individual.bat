@echo off
REM ================================================================
REM Run Migration - Individual Opsi Lainnya (9 Feb 2026)
REM ================================================================

set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo ================================================================
echo   Run Migration for Individual Opsi Lainnya
echo ================================================================
echo.
echo Migration file: 2026_02_09_100000_add_individual_opsi_lainnya_to_daftar_ulang_and_seragam.php
echo.

echo ================================================================
echo   STEP 1: Check if migration file exists
echo ================================================================
echo.
ssh %SERVER% "ls -la %REMOTE_PATH%/database/migrations/2026_02_09_100000_add_individual_opsi_lainnya_to_daftar_ulang_and_seragam.php"
if errorlevel 1 (
    echo.
    echo ERROR: Migration file not found!
    echo Uploading migration file first...
    echo.
    scp "database\migrations\2026_02_09_100000_add_individual_opsi_lainnya_to_daftar_ulang_and_seragam.php" %SERVER%:%REMOTE_PATH%/database/migrations/
    if errorlevel 1 (
        echo ERROR: Failed to upload migration file
        pause
        exit /b 1
    )
    echo SUCCESS: Migration file uploaded
) else (
    echo SUCCESS: Migration file exists
)
echo.

echo ================================================================
echo   STEP 2: Run Migration
echo ================================================================
echo.
echo Running: php artisan migrate
echo.
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan migrate"
if errorlevel 1 (
    echo.
    echo ERROR: Migration failed!
    echo.
    echo Troubleshooting:
    echo 1. Check database connection
    echo 2. Check if migration already ran: php artisan migrate:status
    echo 3. Check logs: tail -50 storage/logs/laravel.log
    echo.
    pause
    exit /b 1
)
echo.
echo SUCCESS: Migration completed!
echo.

echo ================================================================
echo   STEP 3: Verify Columns Created
echo ================================================================
echo.
echo Checking if columns exist in database...
echo.
timeout /t 2 /nobreak >nul

echo [1/4] Checking daftar_ulang.opsi_lainnya_dropdown_individual...
ssh %SERVER% "cd %REMOTE_PATH% && psql -U mtsnkot4 -d postgresppdb -c \"SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'daftar_ulang' AND column_name = 'opsi_lainnya_dropdown_individual';\""
echo.

echo [2/4] Checking daftar_ulang.opsi_lainnya_konfirmasi_individual...
ssh %SERVER% "cd %REMOTE_PATH% && psql -U mtsnkot4 -d postgresppdb -c \"SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'daftar_ulang' AND column_name = 'opsi_lainnya_konfirmasi_individual';\""
echo.

echo [3/4] Checking seragam.opsi_lainnya_dropdown_individual...
ssh %SERVER% "cd %REMOTE_PATH% && psql -U mtsnkot4 -d postgresppdb -c \"SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'seragam' AND column_name = 'opsi_lainnya_dropdown_individual';\""
echo.

echo [4/4] Checking seragam.opsi_lainnya_konfirmasi_individual...
ssh %SERVER% "cd %REMOTE_PATH% && psql -U mtsnkot4 -d postgresppdb -c \"SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'seragam' AND column_name = 'opsi_lainnya_konfirmasi_individual';\""
echo.

echo ================================================================
echo   STEP 4: Clear Caches
echo ================================================================
echo.

echo [1/3] Clearing application cache...
ssh %SERVER% "cd %REMOTE_PATH% && php artisan cache:clear"
echo.

echo [2/3] Clearing config cache...
ssh %SERVER% "cd %REMOTE_PATH% && php artisan config:clear"
echo.

echo [3/3] Optimizing...
ssh %SERVER% "cd %REMOTE_PATH% && php artisan optimize:clear"
echo.

echo ================================================================
echo   Migration Complete!
echo ================================================================
echo.
echo New columns added:
echo   - daftar_ulang.opsi_lainnya_dropdown_individual (boolean)
echo   - daftar_ulang.opsi_lainnya_konfirmasi_individual (boolean)
echo   - seragam.opsi_lainnya_dropdown_individual (boolean)
echo   - seragam.opsi_lainnya_konfirmasi_individual (boolean)
echo.
echo ================================================================
echo   Next Steps
echo ================================================================
echo.
echo 1. Go to: /admin/daftar-ulang?activeTab=madrasah
echo 2. Hard refresh browser (Ctrl + Shift + F5)
echo 3. Try clicking the toggles
echo 4. Should work now without errors!
echo.
echo If still error, check:
echo   - Browser console for JS errors
echo   - Laravel logs: ssh %SERVER% "cd %REMOTE_PATH% && tail -50 storage/logs/laravel.log"
echo.
echo ================================================================
pause
