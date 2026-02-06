@echo off
echo Clearing Laravel cache...
php artisan view:clear
php artisan optimize:clear
echo.
echo Cache cleared!
pause
