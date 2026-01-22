@echo off
echo Testing in DEVELOPMENT mode...
set APP_ENV=local
set APP_DEBUG=true
php artisan serve --port=8002