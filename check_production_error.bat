@echo off
echo ========================================
echo Check Error Log Production
echo ========================================
echo.

set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo Mengambil 100 baris terakhir dari log...
echo.
ssh %SERVER% "cd %REMOTE_PATH% && tail -100 storage/logs/laravel.log"

echo.
echo ========================================
echo Selesai
echo ========================================
pause
