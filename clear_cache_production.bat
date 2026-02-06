@echo off
echo ========================================
echo CLEAR ALL CACHE PRODUCTION - AGGRESSIVE
echo ========================================
echo.

set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo [1/8] Clear Application Cache...
ssh %SERVER% "cd %REMOTE_PATH% && php artisan cache:clear"

echo [2/8] Clear Config Cache...
ssh %SERVER% "cd %REMOTE_PATH% && php artisan config:clear"

echo [3/8] Clear Route Cache...
ssh %SERVER% "cd %REMOTE_PATH% && php artisan route:clear"

echo [4/8] Clear View Cache...
ssh %SERVER% "cd %REMOTE_PATH% && php artisan view:clear"

echo [5/8] Clear Compiled Cache...
ssh %SERVER% "cd %REMOTE_PATH% && php artisan optimize:clear"

echo [6/8] Delete Compiled Views Manually...
ssh %SERVER% "cd %REMOTE_PATH% && rm -rf storage/framework/views/*.php"

echo [7/8] Delete Livewire Cache...
ssh %SERVER% "cd %REMOTE_PATH% && rm -rf storage/framework/cache/livewire-tmp/*"

echo [8/8] Rebuild Optimize...
ssh %SERVER% "cd %REMOTE_PATH% && php artisan optimize"

echo.
echo ========================================
echo CACHE CLEARED SUCCESSFULLY!
echo ========================================
echo.
echo Silakan refresh browser dengan hard reload (Ctrl+Shift+R)
echo.
pause
