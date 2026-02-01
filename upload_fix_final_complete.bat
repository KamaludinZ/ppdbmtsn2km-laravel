@echo off
echo ========================================
echo Upload FIX FINAL - Modal + Auto Phase Update
echo ========================================
echo.
echo Perbaikan yang dilakukan:
echo 1. Background blur modal konfirmasi (bg-gray-900/75)
echo 2. Auto-update fase pendaftaran di settings
echo.
echo ========================================

echo.
echo [1/5] Uploading Register.php (Controller)...
scp app/Livewire/Auth/Register.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Auth/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload Register.php
    pause
    exit /b 1
)
echo SUCCESS: Register.php uploaded

echo.
echo [2/5] Uploading register.blade.php (View)...
scp resources/views/livewire/auth/register.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/auth/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload register.blade.php
    pause
    exit /b 1
)
echo SUCCESS: register.blade.php uploaded

echo.
echo [3/5] Uploading Settings.php (Auto Phase Update)...
scp app/Livewire/Admin/Settings.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload Settings.php
    pause
    exit /b 1
)
echo SUCCESS: Settings.php uploaded

echo.
echo [4/5] Clearing cache on server...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear && php artisan cache:clear && php artisan config:clear && echo '✅ All caches cleared'"
if %errorlevel% neq 0 (
    echo ERROR: Failed to clear cache
    pause
    exit /b 1
)
echo SUCCESS: Cache cleared

echo.
echo [5/5] Upload complete!
echo.
echo ========================================
echo PERUBAHAN YANG DILAKUKAN:
echo ========================================
echo.
echo ✅ FIX 1: Modal Konfirmasi Pendaftaran
echo    - Background blur: bg-gray-900/75 backdrop-blur-sm
echo    - Serasi dengan modal lainnya di aplikasi
echo    - Modal di paling depan (z-index 9999)
echo.
echo ✅ FIX 2: Auto-Update Fase Pendaftaran
echo    - Jika fase = "belum_dibuka"
echo    - Dan tanggal sekarang >= tanggal mulai pendaftaran
echo    - Maka fase otomatis berubah menjadi "dibuka"
echo    - Update dilakukan saat halaman settings dibuka
echo.
echo ✅ Logic NSM/NPSN tetap berfungsi (tidak berubah)
echo.
echo ========================================
echo TESTING INSTRUCTIONS:
echo ========================================
echo.
echo TEST 1: Modal Konfirmasi
echo 1. Buka: https://pmbm.mtsn2kotamalang.sch.id/daftar
echo 2. Isi form sampai Step 3 - Data Diri
echo 3. Klik tombol "Daftar Sekarang"
echo 4. Modal popup harus muncul dengan:
echo    - Background blur gelap transparan (seperti modal lain)
echo    - Modal di paling depan
echo 5. Klik "Ya, Daftar Sekarang" - Proses pendaftaran
echo 6. Klik "Batal" atau area gelap - Modal tertutup
echo.
echo TEST 2: Auto-Update Fase Pendaftaran
echo 1. Buka: https://pmbm.mtsn2kotamalang.sch.id/admin/settings
echo 2. Cek bagian "Pengaturan Umum"
echo 3. Jika fase = "belum_dibuka" dan tanggal mulai sudah lewat
echo 4. Refresh halaman atau buka lagi
echo 5. Fase pendaftaran harus otomatis berubah jadi "dibuka"
echo.
echo ========================================
echo EXPECTED RESULTS:
echo ========================================
echo.
echo ✅ Modal background blur transparan (bukan abu-abu solid)
echo ✅ Tidak ada error di console
echo ✅ Fase pendaftaran auto-update jika kondisi terpenuhi
echo ✅ Log auto-update muncul di storage/logs/laravel.log
echo.
echo ========================================
echo.
pause
