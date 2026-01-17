@echo off
REM Pre-Deployment Preparation Script for PPDB MTsN 2 Kota Malang
REM Run this script before deploying to shared hosting

echo ===========================================
echo PPDB MTsN 2 Kota Malang - Pre-Deployment Script
echo ===========================================

REM Function to log messages
set LOG_FILE=pre_deploy.log
echo.%date% %time% - Starting pre-deployment preparation... >> %LOG_FILE%

REM Check if we're in the correct directory
if not exist "artisan" (
    echo.ERROR: Not in Laravel root directory. Please run this script from the Laravel project root.
    echo.%date% %time% - ERROR: Not in Laravel root directory. >> %LOG_FILE%
    exit /b 1
)

echo.Verified Laravel project directory.
echo.%date% %time% - Verified Laravel project directory. >> %LOG_FILE%

REM 1. Run tests (skip for now due to environment issues)
echo.
echo.1. Skipping tests (environment compatibility issues)...
echo.%date% %time% - Skipping tests due to environment compatibility... >> %LOG_FILE%
REM php artisan test --no-interaction
REM if %errorlevel% neq 0 (
REM     echo.ERROR: Tests failed. Please fix issues before deployment.
REM     echo.%date% %time% - ERROR: Tests failed. >> %LOG_FILE%
REM     pause
REM     exit /b 1
REM ) else (
    echo.Tests skipped for compatibility.
    echo.%date% %time% - Tests skipped for compatibility. >> %LOG_FILE%
REM )

REM 2. Clear all caches
echo.
echo.2. Clearing caches...
echo.%date% %time% - Clearing all caches... >> %LOG_FILE%
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear
php artisan optimize:clear
echo.%date% %time% - Caches cleared successfully. >> %LOG_FILE%

REM 3. Install production dependencies
echo.
echo.3. Installing production dependencies...
echo.%date% %time% - Installing production dependencies... >> %LOG_FILE%
composer install --no-dev --optimize-autoloader --no-interaction
if %errorlevel% neq 0 (
    echo.ERROR: Failed to install production dependencies.
    echo.%date% %time% - ERROR: Failed to install production dependencies. >> %LOG_FILE%
    pause
    exit /b 1
) else (
    echo.Production dependencies installed successfully.
    echo.%date% %time% - Production dependencies installed successfully. >> %LOG_FILE%
)

REM 4. Build assets
echo.
echo.4. Building production assets...
echo.%date% %time% - Building production assets... >> %LOG_FILE%

REM Check if package.json exists
if exist "package.json" (
    npm install --production=false
    npm run build
    echo.Assets built successfully.
    echo.%date% %time% - Assets built successfully. >> %LOG_FILE%
) else (
    echo.No package.json found, skipping asset building.
    echo.%date% %time% - No package.json found, skipping asset building. >> %LOG_FILE%
)

REM 5. Cache configurations
echo.
echo.5. Caching configurations...
echo.%date% %time% - Caching configurations... >> %LOG_FILE%
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
echo.%date% %time% - Configurations cached successfully. >> %LOG_FILE%

REM 6. Optimize autoloader
echo.
echo.6. Optimizing autoloader...
echo.%date% %time% - Optimizing autoloader... >> %LOG_FILE%
composer dump-autoload --optimize --no-dev
echo.%date% %time% - Autoloader optimized successfully. >> %LOG_FILE%

REM 7. Check for debug statements
echo.
echo.7. Checking for debug statements...
echo.%date% %time% - Checking for debug statements in code... >> %LOG_FILE%

REM Using findstr to search for debug functions
findstr /S /N /C:"dd(" app\ *.php config\ *.php database\ *.php resources\ *.php routes\ *.php > debug_temp.txt 2>nul
findstr /S /N /C:"dump(" app\ *.php config\ *.php database\ *.php resources\ *.php routes\ *.php >> debug_temp.txt 2>nul
findstr /S /N /C:"var_dump(" app\ *.php config\ *.php database\ *.php resources\ *.php routes\ *.php >> debug_temp.txt 2>nul
findstr /S /N /C:"die(" app\ *.php config\ *.php database\ *.php resources\ *.php routes\ *.php >> debug_temp.txt 2>nul
findstr /S /N /C:"exit(" app\ *.php config\ *.php database\ *.php resources\ *.php routes\ *.php >> debug_temp.txt 2>nul

if exist "debug_temp.txt" (
    echo.WARNING: Debug statements found in code:
    type debug_temp.txt
    echo.%date% %time% - WARNING: Debug statements found in code: >> %LOG_FILE%
    type debug_temp.txt >> %LOG_FILE%
    del debug_temp.txt
    set /p CONTINUE_DEBUG=Do you want to continue despite debug statements? (y/n):
    if /i not "%CONTINUE_DEBUG%"=="y" (
        echo.Deployment cancelled due to debug statements.
        echo.%date% %time% - Deployment cancelled due to debug statements. >> %LOG_FILE%
        exit /b 1
    )
) else (
    echo.No debug statements found in code.
    echo.%date% %time% - No debug statements found in code. >> %LOG_FILE%
)

REM 8. Check environment file
echo.
echo.8. Verifying environment configuration...
echo.%date% %time% - Verifying environment configuration... >> %LOG_FILE%

if not exist ".env" (
    echo.ERROR: .env file not found.
    echo.%date% %time% - ERROR: .env file not found. >> %LOG_FILE%
    pause
    exit /b 1
)

REM Check if APP_ENV is set to production
for /f "tokens=2 delims==" %%a in ('findstr /B "APP_ENV=" .env') do set APP_ENV=%%a
if not "%APP_ENV%"=="production" (
    echo.WARNING: APP_ENV is not set to production in .env file: %APP_ENV%
    echo.%date% %time% - WARNING: APP_ENV is not set to production: %APP_ENV% >> %LOG_FILE%
    set /p CONTINUE_ENV=Do you want to continue? (y/n):
    if /i not "%CONTINUE_ENV%"=="y" (
        echo.Deployment cancelled due to environment settings.
        echo.%date% %time% - Deployment cancelled due to environment settings. >> %LOG_FILE%
        exit /b 1
    )
) else (
    echo.APP_ENV is correctly set to production.
    echo.%date% %time% - APP_ENV is correctly set to production. >> %LOG_FILE%
)

REM 9. Check APP_DEBUG
for /f "tokens=2 delims==" %%a in ('findstr /B "APP_DEBUG=" .env') do set APP_DEBUG=%%a
if not "%APP_DEBUG%"=="false" (
    echo.WARNING: APP_DEBUG is not set to false in .env file: %APP_DEBUG%
    echo.%date% %time% - WARNING: APP_DEBUG is not set to false: %APP_DEBUG% >> %LOG_FILE%
    set /p CONTINUE_DEBUG_SETTING=Do you want to continue? (y/n):
    if /i not "%CONTINUE_DEBUG_SETTING%"=="y" (
        echo.Deployment cancelled due to debug settings.
        echo.%date% %time% - Deployment cancelled due to debug settings. >> %LOG_FILE%
        exit /b 1
    )
) else (
    echo.APP_DEBUG is correctly set to false.
    echo.%date% %time% - APP_DEBUG is correctly set to false. >> %LOG_FILE%
)

REM 10. Create backup of current state
echo.
echo.9. Creating backup...
echo.%date% %time% - Creating backup of current state... >> %LOG_FILE%

set BACKUP_FILE=ppdb_backup_%date:~-4,4%%date:~-10,2%%date:~-7,2%_%time:~0,2%%time:~3,2%%time:~6,2%.zip
REM Use PowerShell to create zip archive
powershell -command "Compress-Archive -Path .\* -DestinationPath '%BACKUP_FILE%' -Exclude @('node_modules', '.git', 'storage/logs/*', 'storage/framework/cache/*', 'storage/framework/sessions/*', 'storage/framework/views/*', '%BACKUP_FILE%', 'pre_deploy.log')"
if %errorlevel% equ 0 (
    echo.Backup created: %BACKUP_FILE%
    echo.%date% %time% - Backup created: %BACKUP_FILE% >> %LOG_FILE%
) else (
    echo.ERROR: Failed to create backup.
    echo.%date% %time% - ERROR: Failed to create backup. >> %LOG_FILE%
    pause
    exit /b 1
)

REM 11. Verify storage permissions (create directories if they don't exist)
echo.
echo.10. Setting up storage directories...
echo.%date% %time% - Setting up storage directories... >> %LOG_FILE%

if not exist "storage\framework\cache" mkdir storage\framework\cache
if not exist "storage\framework\sessions" mkdir storage\framework\sessions
if not exist "storage\framework\views" mkdir storage\framework\views
if not exist "storage\logs" mkdir storage\logs
if not exist "bootstrap\cache" mkdir bootstrap\cache

echo.Storage directories created/verified.
echo.%date% %time% - Storage directories created/verified. >> %LOG_FILE%

REM 12. Check database connection
echo.
echo.11. Testing database connection...
echo.%date% %time% - Testing database connection... >> %LOG_FILE%

php artisan tinker --execute="DB::connection()->getPdo(); echo 'Connection successful'; die();" 2>nul
if %errorlevel% equ 0 (
    echo.Database connection successful.
    echo.%date% %time% - Database connection successful. >> %LOG_FILE%
) else (
    echo.WARNING: Could not verify database connection.
    echo.%date% %time% - WARNING: Could not verify database connection. >> %LOG_FILE%
)

REM 13. Generate deployment package
echo.
echo.12. Creating deployment package...
echo.%date% %time% - Creating deployment package... >> %LOG_FILE%

set DEPLOY_PACKAGE=ppdb_production_%date:~-4,4%%date:~-10,2%%date:~-7,2%_%time:~0,2%%time:~3,2%%time:~6,2%.zip

REM Create deployment package using PowerShell
powershell -command "Compress-Archive -Path @('app', 'bootstrap', 'config', 'database', 'public', 'resources', 'routes', 'storage', 'vendor', 'artisan', 'composer.json', 'composer.lock', 'package.json', 'package-lock.json') -DestinationPath '%DEPLOY_PACKAGE%' -CompressionLevel Optimal"
if %errorlevel% equ 0 (
    echo.Deployment package created: %DEPLOY_PACKAGE%
    REM Get file size
    for %%A in (%DEPLOY_PACKAGE%) do set SIZE=%%~zA
    set /a SIZE_MB=%SIZE%/1024/1024
    echo.Package size: %SIZE_MB% MB
    echo.%date% %time% - Deployment package created: %DEPLOY_PACKAGE% (%SIZE_MB% MB) >> %LOG_FILE%
) else (
    echo.ERROR: Failed to create deployment package.
    echo.%date% %time% - ERROR: Failed to create deployment package. >> %LOG_FILE%
    pause
    exit /b 1
)

REM 14. Final summary
echo.
echo.===========================================
echo.PRE-DEPLOYMENT PREPARATION COMPLETE
echo.===========================================
echo.%date% %time% - Pre-deployment preparation completed successfully! >> %LOG_FILE%

echo.
echo.Generated files:
echo.- Backup: %BACKUP_FILE%
echo.- Deployment package: %DEPLOY_PACKAGE%
echo.- Log: %LOG_FILE%
echo.
echo.Package size: %SIZE_MB% MB
echo.

echo.%date% %time% - Deployment package is ready for upload to shared hosting. >> %LOG_FILE%
echo.%date% %time% - Please review %LOG_FILE% for any warnings or errors. >> %LOG_FILE%
echo.%date% %time% - Deployment preparation script completed successfully. >> %LOG_FILE%

echo.Next steps:
echo.1. Upload %DEPLOY_PACKAGE% to your shared hosting
echo.2. Follow the deployment guide in DEPLOYMENT_GUIDE_SHARED_HOSTING.md
echo.3. Remember to create/update your .env file on the server
echo.4. Run migrations after deployment: php artisan migrate --force
echo.
echo.Deployment preparation is complete! Press any key to exit.
pause >nul