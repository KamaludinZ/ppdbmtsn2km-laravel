@echo off
echo Testing in PRODUCTION mode...
set APP_ENV=production
set APP_DEBUG=false
php artisan serve --port=8001