@echo off
echo ========================================
echo Upload Fix Edit Nilai Rapor - WITH VERIFICATION
echo ========================================
echo.

set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo INFO FILE LOKAL:
echo - Panitia DetailPendaftar.php: 929 baris
echo - Admin DetailPendaftar.php: 2577 baris
echo.

echo [STEP 1/6] Upload Panitia Controller...
scp "app/Livewire/Panitia/DetailPendaftar.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Panitia/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload Panitia controller
    pause
    exit /b 1
)
echo [OK] Panitia controller uploaded

echo [STEP 2/6] Upload Admin Controller...
scp "app/Livewire/Admin/DetailPendaftar.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Admin/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload Admin controller
    pause
    exit /b 1
)
echo [OK] Admin controller uploaded

echo [STEP 3/6] Upload Panitia View...
scp "resources/views/livewire/panitia/detail-pendaftar.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/panitia/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload Panitia view
    pause
    exit /b 1
)
echo [OK] Panitia view uploaded

echo [STEP 4/6] Upload Admin View...
scp "resources/views/livewire/admin/detail-pendaftar.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/admin/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload Admin view
    pause
    exit /b 1
)
echo [OK] Admin view uploaded

echo [STEP 5/6] Verifikasi file di production...
ssh %SERVER% "cd %REMOTE_PATH% && echo 'Baris Panitia:' && wc -l app/Livewire/Panitia/DetailPendaftar.php && echo 'Method toggleEdit:' && grep -n 'public function toggleEdit' app/Livewire/Panitia/DetailPendaftar.php | head -1"
echo.

echo [STEP 6/6] Clear ALL caches...
ssh %SERVER% "cd %REMOTE_PATH% && php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear && php artisan optimize:clear"
if %errorlevel% neq 0 (
    echo ERROR: Gagal clear cache
    pause
    exit /b 1
)
echo [OK] All caches cleared

echo.
echo ========================================
echo UPLOAD SELESAI\!
echo ========================================
echo.
echo Silakan test di production:
echo - https://pmbm.mtsn2kotamalang.sch.id/panitia/pendaftar/[id]
echo - https://pmbm.mtsn2kotamalang.sch.id/admin/pendaftar/[id]
echo.
pause
