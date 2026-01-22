@echo off
echo =====================================
echo PPDB MTsN 2 - Production Preparation
echo =====================================
echo.

echo [1] Building production assets with Vite...
call npm run build

echo.
echo [2] Creating production CSS file...
copy public\css\app-production.css public\css\app.css /Y

echo.
echo [3] Optimizing Laravel...
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo.
echo [4] Setting production environment...
echo ASSET_MODE=mixed > .env.production.local

echo.
echo =====================================
echo Production preparation complete!
echo =====================================
echo.
echo Files to upload:
echo - All files EXCEPT:
echo   - node_modules/
echo   - .env (use .env.production on server)
echo   - storage/logs/*
echo   - public/hot
echo.
echo Make sure public/css/app.css contains production CSS!
echo.
pause