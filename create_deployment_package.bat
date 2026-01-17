@echo off
REM Quick Deployment Package Creator for PPDB MTsN 2 Kota Malang
REM Creates a production-ready package for shared hosting

echo ===========================================
echo Quick Deployment Package Creator
echo ===========================================

REM Create deployment package using PowerShell
set DEPLOY_PACKAGE=ppdb_production_quick_%date:~-4,4%%date:~-10,2%%date:~-7,2%_%time:~0,2%%time:~3,2%%time:~6,2%.zip

echo Creating deployment package: %DEPLOY_PACKAGE%

REM Create deployment package using PowerShell with specific exclusions
powershell -command "Compress-Archive -Path @('app', 'bootstrap', 'config', 'database', 'public', 'resources', 'routes', 'storage', 'vendor', 'artisan', 'composer.json', 'composer.lock', 'package.json', 'package-lock.json') -DestinationPath '%DEPLOY_PACKAGE%' -CompressionLevel Optimal -Force"

if %errorlevel% equ 0 (
    echo.
    echo Deployment package created successfully: %DEPLOY_PACKAGE%
    
    REM Get file size
    for %%A in (%DEPLOY_PACKAGE%) do set SIZE=%%~zA
    set /a SIZE_MB=%SIZE%/1024/1024
    echo Package size: %SIZE% bytes (%SIZE_MB% MB)
    
    echo.
    echo Package contents:
    powershell -command "Expand-Archive -LiteralPath '%DEPLOY_PACKAGE%' -DestinationPath 'temp_extract' -Force; Get-ChildItem -Path 'temp_extract' -Recurse | Select-Object -First 20 | Format-Table -AutoSize; Remove-Item -Path 'temp_extract' -Recurse -Force"
    
    echo.
    echo Deployment package is ready for upload to shared hosting!
    echo.
    echo Next steps:
    echo 1. Upload %DEPLOY_PACKAGE% to your shared hosting
    echo 2. Extract to your subdomain directory
    echo 3. Create .env file with production settings
    echo 4. Run: php artisan key:generate
    echo 5. Run: php artisan migrate --force
    echo 6. Set proper permissions for storage directory
) else (
    echo.
    echo ERROR: Failed to create deployment package.
    echo Please check if all required directories exist.
)

echo.
echo Process completed.
pause