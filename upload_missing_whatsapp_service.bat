@echo off
echo ============================================================
echo UPLOAD MISSING FILE - WhatsAppQueueService
echo ============================================================
echo.

echo [1/4] Uploading WhatsAppQueueService.php...
scp app/Services/WhatsAppQueueService.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Services/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload WhatsAppQueueService.php
    pause
    exit /b 1
)
echo WhatsAppQueueService.php uploaded successfully!
echo.

echo [2/4] Uploading MessageQueue.php model (just in case)...
scp app/Models/MessageQueue.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/
if %errorlevel% neq 0 (
    echo WARNING: Failed to upload MessageQueue.php (might already exist)
)
echo MessageQueue.php uploaded!
echo.

echo [3/4] Setting permissions and clearing caches...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && chmod 644 app/Services/WhatsAppQueueService.php && chmod 644 app/Models/MessageQueue.php && php artisan optimize:clear && composer dump-autoload"
echo.

echo [4/4] Running migration (just in case)...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan migrate --force"
echo.

echo ============================================================
echo UPLOAD COMPLETE!
echo ============================================================
echo.
echo Files uploaded:
echo [+] app/Services/WhatsAppQueueService.php
echo [+] app/Models/MessageQueue.php
echo.
echo Actions executed:
echo [+] Set file permissions
echo [+] Cleared all caches
echo [+] Ran composer dump-autoload
echo [+] Ran migrations
echo.
echo NOW TEST IN BROWSER:
echo 1. Clear browser cache (Ctrl+Shift+Del)
echo 2. Go to /admin/pengaturan-pesan
echo 3. Click "History WhatsApp" tab
echo 4. Should work without error 500!
echo.
pause
