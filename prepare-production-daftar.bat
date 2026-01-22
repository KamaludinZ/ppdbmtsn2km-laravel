@echo off
echo ========================================
echo PREPARING /DAFTAR FOR PRODUCTION
echo ========================================
echo.

echo [1/6] Clearing all caches...
php artisan view:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear

echo.
echo [2/6] Optimizing for production...
php artisan optimize

echo.
echo [3/6] Caching configuration...
php artisan config:cache

echo.
echo [4/6] Caching routes...
php artisan route:cache

echo.
echo [5/6] Caching views...
php artisan view:cache

echo.
echo [6/6] Running composer autoload optimization...
composer dump-autoload --optimize

echo.
echo ========================================
echo PRODUCTION PREPARATION COMPLETE!
echo ========================================
echo.
echo IMPORTANT REMINDERS:
echo 1. Set APP_ENV=production in .env
echo 2. Set APP_DEBUG=false in .env
echo 3. Ensure database credentials are correct
echo 4. Test the /daftar page thoroughly
echo.
pause