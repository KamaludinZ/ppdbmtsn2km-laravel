@echo off
echo ========================================
echo UPLOAD FIX CATATAN STYLING
echo ========================================
echo.

REM Upload detail-pendaftar.blade.php yang sudah diperbaiki
echo [1/1] Uploading detail-pendaftar.blade.php (Admin View)...
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

echo.
echo ========================================
echo UPLOAD COMPLETED!
echo ========================================
echo.
echo Files uploaded:
echo - resources/views/livewire/admin/detail-pendaftar.blade.php
echo.
echo Styling Changes: CATATAN BOX
echo.
echo [SEBELUM] - Putih Tidak Terlihat:
echo - Background: Semi-transparan putih (bg-white bg-opacity-20)
echo - Text: Putih (text-white) - TIDAK TERLIHAT!
echo - Border: Putih semi-transparan
echo - Button: Semi-transparan putih
echo - Max width: 320px (max-w-xs)
echo.
echo [SESUDAH] - Jelas dan Proper:
echo - Background: Kuning muda solid (bg-yellow-50)
echo - Text: Hitam gelap (text-gray-800) - JELAS TERLIHAT!
echo - Border: Kuning terang 2px (border-yellow-400)
echo - Button: Gradient biru solid (from-blue-500 to-blue-600)
echo - Max width: 448px (max-w-md)
echo - Icon: Warning icon kuning untuk catatan
echo - Icon: Info icon abu-abu untuk kosong
echo - Shadow: Lebih tegas (shadow-lg)
echo.
echo Visual Improvements:
echo 1. Kontras Tinggi:
echo    - Background kuning + text hitam = sangat jelas
echo 2. Icon Menarik:
echo    - Warning icon kuning untuk alert
echo    - Info icon abu-abu untuk info
echo 3. Button Menonjol:
echo    - Gradient biru dengan hover effect
echo    - Shadow untuk depth
echo 4. Border Jelas:
echo    - Kuning 2px solid untuk emphasis
echo 5. Layout Lebih Luas:
echo    - Max-width diperbesar untuk readability
echo.
echo Please test:
echo 1. Login sebagai admin
echo 2. Akses /admin/pendaftar/{id}
echo 3. Set status ke perbaiki_data/lengkapi_data/data_ditolak
echo 4. Lihat catatan box di header
echo 5. Verifikasi:
echo    a. Background kuning muda terlihat jelas
echo    b. Text hitam mudah dibaca
echo    c. Border kuning menonjol
echo    d. Button biru solid terlihat jelas
echo    e. Icon warning/info matching dengan tema
echo.
pause
