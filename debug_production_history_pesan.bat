@echo off
echo ============================================================
echo DEBUG PRODUCTION - History Pesan Error 500
echo ============================================================
echo.

echo [1] Checking if message_queue table exists...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan tinker --execute='echo (Schema::hasTable(\"message_queue\") ? \"TABLE EXISTS\" : \"TABLE NOT FOUND\");'"
echo.

echo [2] Checking if MessageQueue model file exists...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && ls -la app/Models/MessageQueue.php"
echo.

echo [3] Checking WhatsAppQueueService file...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && ls -la app/Services/WhatsAppQueueService.php"
echo.

echo [4] Checking last 50 lines of error log...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && tail -50 storage/logs/laravel.log | grep -A 10 'Exception\|Error'"
echo.

echo [5] Checking pending migrations...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan migrate:status | grep -A 5 'message_queue'"
echo.

echo ============================================================
pause
