@echo off
echo ========================================
echo Upload FIX FINAL - Window Confirm Solution
echo ========================================
echo.
echo Solusi ini menggunakan window.confirm() native JavaScript
echo - Tidak perlu Alpine.js
echo - Tidak perlu modal kompleks
echo - Pasti bekerja di semua browser
echo.
echo ========================================

echo.
echo [1/4] Uploading Register.php (Controller)...
scp app/Livewire/Auth/Register.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Auth/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload Register.php
    pause
    exit /b 1
)
echo SUCCESS: Register.php uploaded

echo.
echo [2/4] Uploading register.blade.php (View)...
scp resources/views/livewire/auth/register.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/auth/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload register.blade.php
    pause
    exit /b 1
)
echo SUCCESS: register.blade.php uploaded

echo.
echo [3/4] Clearing cache on server...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear && php artisan cache:clear && php artisan config:clear && echo '✅ All caches cleared'"
if %errorlevel% neq 0 (
    echo ERROR: Failed to clear cache
    pause
    exit /b 1
)
echo SUCCESS: Cache cleared

echo.
echo [4/4] Upload complete!
echo.
echo ========================================
echo PERUBAHAN YANG DILAKUKAN:
echo ========================================
echo.
echo ✅ Tombol "Daftar Sekarang" menggunakan window.confirm()
echo ✅ Hapus modal confirmation yang kompleks
echo ✅ Hapus JavaScript functions yang tidak terpakai
echo ✅ Hapus method confirmSubmit dan cancelSubmit
echo ✅ Logic NSM/NPSN TETAP BERFUNGSI (tidak berubah)
echo.
echo ========================================
echo TESTING INSTRUCTIONS:
echo ========================================
echo.
echo 1. Buka: https://pmbm.mtsn2kotamalang.sch.id/daftar
echo.
echo 2. Isi form sampai Step 3 - Data Diri
echo.
echo 3. Klik tombol "Daftar Sekarang"
echo.
echo 4. HARUS MUNCUL DIALOG KONFIRMASI:
echo    ┌─────────────────────────────────────────┐
echo    │  Yakin ingin mendaftar?                 │
echo    │                                         │
echo    │  Data pendaftaran Anda sudah sesuai...  │
echo    │                                         │
echo    │         [ OK ]      [ Batal ]           │
echo    └─────────────────────────────────────────┘
echo.
echo 5. Klik OK - Proses pendaftaran
echo    Klik Batal - Batalkan pendaftaran
echo.
echo ========================================
echo TEST LOGIC NSM/NPSN:
echo ========================================
echo.
echo 1. Di Step 1 - Info Jalur:
echo    - Pilih "Madrasah" - Input NSM (12 digit)
echo    - Jika NSM sudah pernah ada, nama sekolah auto-fill ✅
echo.
echo 2. Atau pilih "Umum" - Input NPSN (8 digit)
echo    - Jika NPSN sudah pernah ada, nama sekolah auto-fill ✅
echo.
echo ========================================
echo EXPECTED RESULTS:
echo ========================================
echo.
echo ✅ Browser confirm dialog muncul
echo ✅ Tidak ada error di console
echo ✅ Proses pendaftaran berjalan normal
echo ✅ Logic NSM/NPSN autocomplete tetap berfungsi
echo.
echo ========================================
echo.
pause
