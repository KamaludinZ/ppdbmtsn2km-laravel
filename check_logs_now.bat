@echo off
echo ============================================================
echo URGENT - Check Production Logs for Survey Save Process
echo ============================================================
echo.
echo Connecting to production...
echo.

ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && tail -100 storage/logs/laravel.log | grep -A 30 'survey\|Survey'"

pause
