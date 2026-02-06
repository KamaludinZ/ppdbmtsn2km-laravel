@echo off
echo ============================================================
echo COMPLETE UPLOAD - Fix Error 500 History Pesan
echo ============================================================
echo.
echo This will upload ALL required files for History Pesan feature:
echo - Services (WhatsAppQueueService.php)
echo - Models (MessageQueue.php)
echo - Controllers (PengaturanPesan.php)
echo - Views (history-email-tab, history-whatsapp-tab)
echo - Migration (message_queue table)
echo.
echo Press Ctrl+C to cancel, or
pause
echo.

echo ============================================================
echo STEP 1: UPLOADING FILES
echo ============================================================
echo.

echo [1/6] Uploading WhatsAppQueueService...
scp app/Services/WhatsAppQueueService.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Services/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload WhatsAppQueueService.php
    pause
    exit /b 1
)
echo OK!

echo [2/6] Uploading MessageQueue model...
scp app/Models/MessageQueue.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload MessageQueue.php
    pause
    exit /b 1
)
echo OK!

echo [3/6] Uploading PengaturanPesan controller...
scp app/Livewire/Admin/PengaturanPesan.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload PengaturanPesan.php
    pause
    exit /b 1
)
echo OK!

echo [4/6] Uploading history-email-tab view...
scp resources/views/livewire/admin/partials/history-email-tab.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload history-email-tab.blade.php
    pause
    exit /b 1
)
echo OK!

echo [5/6] Uploading history-whatsapp-tab view...
scp resources/views/livewire/admin/partials/history-whatsapp-tab.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload history-whatsapp-tab.blade.php
    pause
    exit /b 1
)
echo OK!

echo [6/6] Uploading migration file...
scp database/migrations/2026_02_03_210000_create_message_queue_table.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload migration
    pause
    exit /b 1
)
echo OK!

echo.
echo ============================================================
echo STEP 2: CONFIGURING PRODUCTION
echo ============================================================
echo.

echo Setting file permissions...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && chmod 644 app/Services/WhatsAppQueueService.php && chmod 644 app/Models/MessageQueue.php && chmod 644 app/Livewire/Admin/PengaturanPesan.php && chmod 644 resources/views/livewire/admin/partials/history-email-tab.blade.php && chmod 644 resources/views/livewire/admin/partials/history-whatsapp-tab.blade.php && chmod 644 database/migrations/2026_02_03_210000_create_message_queue_table.php"
echo OK!
echo.

echo Running migration...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan migrate --force"
echo OK!
echo.

echo Clearing all caches...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear && php artisan view:clear"
echo OK!
echo.

echo Running composer dump-autoload...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && composer dump-autoload --optimize"
echo OK!
echo.

echo ============================================================
echo SUCCESS! ALL FILES UPLOADED AND CONFIGURED
echo ============================================================
echo.
echo Files uploaded:
echo [+] app/Services/WhatsAppQueueService.php
echo [+] app/Models/MessageQueue.php
echo [+] app/Livewire/Admin/PengaturanPesan.php
echo [+] resources/views/livewire/admin/partials/history-email-tab.blade.php
echo [+] resources/views/livewire/admin/partials/history-whatsapp-tab.blade.php
echo [+] database/migrations/2026_02_03_210000_create_message_queue_table.php
echo.
echo Actions completed:
echo [+] Set file permissions (chmod 644)
echo [+] Ran database migration
echo [+] Cleared all Laravel caches
echo [+] Ran composer dump-autoload
echo.
echo ============================================================
echo TESTING INSTRUCTIONS:
echo ============================================================
echo.
echo 1. Clear your browser cache (Ctrl+Shift+Del)
echo 2. Go to: /admin/pengaturan-pesan
echo 3. Click tab "History Email" - should show 5 stats cards
echo 4. Click tab "History WhatsApp" - should show queue with features
echo 5. Verify NO error 500 appears
echo.
echo If still error, check logs:
echo ssh mtsnkot4@103.30.244.77
echo cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
echo tail -100 storage/logs/laravel.log
echo.
echo ============================================================
pause
