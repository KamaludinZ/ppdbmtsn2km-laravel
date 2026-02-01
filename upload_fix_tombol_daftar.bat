@echo off
echo ========================================
echo Upload Fix Tombol Daftar Sekarang
echo ========================================
echo.

REM Upload file yang diperbaiki
echo [1/3] Uploading register.blade.php...
scp resources/views/livewire/auth/register.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/auth/

echo.
echo [2/3] Clearing cache on server...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear && php artisan cache:clear && echo 'Cache cleared successfully'"

echo.
echo [3/3] Upload complete!
echo.
echo ========================================
echo TESTING CHECKLIST:
echo ========================================
echo 1. Buka: https://pmbm.mtsn2kotamalang.sch.id/daftar
echo 2. Isi form sampai Step 3 - Data Diri
echo 3. Klik tombol "Daftar Sekarang"
echo 4. Verifikasi modal konfirmasi muncul
echo 5. Test proses pendaftaran
echo ========================================
echo.
pause
