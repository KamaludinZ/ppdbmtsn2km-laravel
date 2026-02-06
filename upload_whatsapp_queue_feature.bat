@echo off
echo ===================================================
echo Upload WhatsApp Queue System ke Production
echo ===================================================
echo.

REM Konfigurasi SSH
set SSH_USER=mtsnkot4
set SSH_HOST=103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo [1/7] Upload Migration...
scp database/migrations/2026_02_03_210000_create_message_queue_table.php %SSH_USER%@%SSH_HOST%:%REMOTE_PATH%/database/migrations/
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Gagal upload migration!
    pause
    exit /b 1
)

echo [2/7] Upload Model MessageQueue...
scp app/Models/MessageQueue.php %SSH_USER%@%SSH_HOST%:%REMOTE_PATH%/app/Models/
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Gagal upload model!
    pause
    exit /b 1
)

echo [3/7] Upload WhatsAppQueueService...
scp app/Services/WhatsAppQueueService.php %SSH_USER%@%SSH_HOST%:%REMOTE_PATH%/app/Services/
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Gagal upload service!
    pause
    exit /b 1
)

echo [4/7] Upload Command ProcessWhatsAppQueue...
scp app/Console/Commands/ProcessWhatsAppQueue.php %SSH_USER%@%SSH_HOST%:%REMOTE_PATH%/app/Console/Commands/
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Gagal upload command!
    pause
    exit /b 1
)

echo [5/7] Upload PengaturanPesan Controller...
scp app/Livewire/Admin/PengaturanPesan.php %SSH_USER%@%SSH_HOST%:%REMOTE_PATH%/app/Livewire/Admin/
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Gagal upload controller!
    pause
    exit /b 1
)

echo [6/7] Upload Views...
scp resources/views/livewire/admin/pengaturan-pesan.blade.php %SSH_USER%@%SSH_HOST%:%REMOTE_PATH%/resources/views/livewire/admin/
scp resources/views/livewire/admin/partials/history-email-tab.blade.php %SSH_USER%@%SSH_HOST%:%REMOTE_PATH%/resources/views/livewire/admin/partials/
scp resources/views/livewire/admin/partials/history-whatsapp-tab.blade.php %SSH_USER%@%SSH_HOST%:%REMOTE_PATH%/resources/views/livewire/admin/partials/
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Gagal upload views!
    pause
    exit /b 1
)

echo [7/7] Upload Scheduler Configuration...
scp routes/console.php %SSH_USER%@%SSH_HOST%:%REMOTE_PATH%/routes/
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Gagal upload scheduler config!
    pause
    exit /b 1
)

echo.
echo ===================================================
echo Upload Selesai!
echo ===================================================
echo.
echo LANGKAH SELANJUTNYA DI SERVER:
echo.
echo 1. SSH ke server:
echo    ssh %SSH_USER%@%SSH_HOST%
echo.
echo 2. Masuk ke folder project:
echo    cd %REMOTE_PATH%
echo.
echo 3. Run migration:
echo    php artisan migrate
echo.
echo 4. Clear cache:
echo    php artisan optimize:clear
echo    php artisan view:clear
echo    php artisan config:clear
echo.
echo 5. Test command:
echo    php artisan whatsapp:process-queue
echo.
echo 6. Cek scheduler:
echo    php artisan schedule:list
echo.
echo 7. Setup .env (jika belum):
echo    nano .env
echo    Tambahkan:
echo    WHATSAPP_API_URL=https://api.whatsapp.example.com/send
echo    WHATSAPP_API_KEY=your_api_key_here
echo.
echo 8. Pastikan cron job sudah running:
echo    crontab -l
echo    Harus ada:
echo    * * * * * cd %REMOTE_PATH% ^&^& php artisan schedule:run ^>^> /dev/null 2^>^&1
echo.
echo ===================================================
echo.
pause
