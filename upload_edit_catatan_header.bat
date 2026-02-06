@echo off
echo ========================================
echo UPLOAD EDIT CATATAN HEADER
echo ========================================
echo.

REM Upload file yang sudah diperbaiki
echo [1/2] Uploading DetailPendaftar.php (Admin Livewire)...
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Admin\DetailPendaftar.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload DetailPendaftar.php
    pause
    exit /b 1
)

echo [2/2] Uploading detail-pendaftar.blade.php (Admin View)...
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\detail-pendaftar.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload detail-pendaftar.blade.php
    pause
    exit /b 1
)

echo.
echo ========================================
echo CLEARING CACHE
echo ========================================
echo.

REM Clear all caches
echo Clearing view cache...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear"

echo Clearing config cache...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan config:clear"

echo Clearing application cache...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan cache:clear"

echo.
echo ========================================
echo UPLOAD COMPLETED!
echo ========================================
echo.
echo Files uploaded:
echo - app/Livewire/Admin/DetailPendaftar.php
echo - resources/views/livewire/admin/detail-pendaftar.blade.php
echo.
echo Feature Added: EDIT CATATAN VERIFIKASI
echo.
echo [BACKEND CHANGES - DetailPendaftar.php]
echo 1. Added property: public $catatan_verifikasi
echo 2. Load catatan_verifikasi in loadPendaftar()
echo 3. Update catatan_verifikasi in updateStatusPendaftaran()
echo 4. Pass catatan_verifikasi to notification
echo.
echo [FRONTEND CHANGES - detail-pendaftar.blade.php]
echo 1. Show current catatan in header info
echo 2. Add "Edit Catatan" button in header
echo 3. Add modal for editing catatan
echo 4. Modal features:
echo    - Textarea with placeholder
echo    - Tips for writing good feedback
echo    - Save and Cancel buttons
echo    - Livewire wire:model binding
echo.
echo [TAMPILAN]
echo - Catatan tampil di header (jika status: lengkapi/perbaiki/ditolak)
echo - Button "Edit Catatan" dengan icon pensil
echo - Modal popup untuk edit
echo - Auto-save ketika submit form updateStatusPendaftaran
echo.
echo Please test:
echo 1. Login sebagai admin
echo 2. Akses /admin/pendaftar/{id}
echo 3. Set status ke lengkapi_data/perbaiki_data/data_ditolak
echo 4. Klik button "Edit Catatan"
echo 5. Isi catatan di modal
echo 6. Klik "Simpan Catatan"
echo 7. Cek catatan tersimpan dan muncul di header
echo.
pause
