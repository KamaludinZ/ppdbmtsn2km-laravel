@echo off
echo ========================================
echo Upload WhatsApp Queue Logic Update
echo 4 Februari 2026
echo ========================================
echo.

echo Step 1: Upload WhatsAppQueueService.php...
scp "app/Services/WhatsAppQueueService.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Services/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload WhatsAppQueueService.php
    pause
    exit /b 1
)
echo OK - WhatsAppQueueService.php uploaded
echo.

echo Step 2: Upload console.php...
scp "routes/console.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/routes/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload console.php
    pause
    exit /b 1
)
echo OK - console.php uploaded
echo.

echo Step 3: Upload dokumentasi...
scp "WHATSAPP_QUEUE_SYSTEM_UPDATED.md" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/
scp "UPDATE_WHATSAPP_QUEUE_LOGIC_4_FEB_2026.md" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/
echo OK - Dokumentasi uploaded
echo.

echo Step 4: Clear cache di production...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan cache:clear"
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan config:clear"
echo OK - Cache cleared
echo.

echo Step 5: Test WhatsApp queue service...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan tinker --execute=\"echo 'Testing WhatsApp Queue Service...\n'; \$service = new \App\Services\WhatsAppQueueService(); echo 'Service loaded successfully!\n';\""
echo.

echo Step 6: Cek scheduler list...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan schedule:list | grep -i whatsapp"
echo.

echo ========================================
echo Upload Selesai!
echo ========================================
echo.
echo Langkah selanjutnya:
echo 1. Cek log: tail -f storage/logs/whatsapp-queue.log
echo 2. Monitor antrian: php artisan tinker
echo    MessageQueue::where('status', 'queued')->count();
echo 3. Test manual reschedule:
echo    php artisan tinker
echo    $queueService = new \App\Services\WhatsAppQueueService();
echo    $count = $queueService->rescheduleOverdueMessages();
echo.
pause
