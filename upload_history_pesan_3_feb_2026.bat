@echo off
echo ============================================================
echo UPLOAD HISTORY PESAN ENHANCEMENT KE PRODUCTION
echo Tanggal: 3 Februari 2026
echo ============================================================
echo.

echo [1/4] Uploading Model Files...
scp app/Models/MessageQueue.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload MessageQueue.php
    pause
    exit /b 1
)
echo Model files uploaded successfully!
echo.

echo [2/4] Uploading Controller Files...
scp app/Livewire/Admin/Pendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload Admin/Pendaftar.php
    pause
    exit /b 1
)

scp app/Livewire/Panitia/Pendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload Panitia/Pendaftar.php
    pause
    exit /b 1
)

scp app/Livewire/Admin/PengaturanPesan.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload Admin/PengaturanPesan.php
    pause
    exit /b 1
)
echo Controller files uploaded successfully!
echo.

echo [3/4] Uploading View Files...
scp resources/views/livewire/admin/pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload admin/pendaftar.blade.php
    pause
    exit /b 1
)

scp resources/views/livewire/panitia/pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload panitia/pendaftar.blade.php
    pause
    exit /b 1
)

scp resources/views/livewire/admin/partials/history-email-tab.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload history-email-tab.blade.php
    pause
    exit /b 1
)

scp resources/views/livewire/admin/partials/history-whatsapp-tab.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload history-whatsapp-tab.blade.php
    pause
    exit /b 1
)
echo View files uploaded successfully!
echo.

echo [4/4] Uploading Migration File...
scp database/migrations/2026_02_03_210000_create_message_queue_table.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload migration file
    pause
    exit /b 1
)
echo Migration file uploaded successfully!
echo.

echo ============================================================
echo UPLOAD SELESAI!
echo ============================================================
echo.
echo File yang berhasil diupload:
echo [+] 1 Model file
echo [+] 3 Controller files
echo [+] 4 View files
echo [+] 1 Migration file
echo.
echo LANGKAH SELANJUTNYA:
echo 1. SSH ke production: ssh mtsnkot4@103.30.244.77
echo 2. cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
echo 3. Backup database:
echo    pg_dump -U mtsnkot4 -d mtsnkot4_ppdb ^> backup_$(date +%%Y%%m%%d_%%H%%M%%S).sql
echo 4. Jalankan migration: php artisan migrate --force
echo 5. Clear caches: php artisan optimize:clear
echo.
echo Buka file UPLOAD_TO_PRODUCTION_HISTORY_PESAN_3_FEB_2026.md
echo untuk panduan lengkap dan checklist verifikasi.
echo.
echo ============================================================
pause
