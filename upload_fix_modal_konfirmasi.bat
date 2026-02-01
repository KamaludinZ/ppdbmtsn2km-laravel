@echo off
echo ========================================
echo Upload FIX MODAL KONFIRMASI PENDAFTARAN
echo ========================================
echo.
echo Solusi ini menggunakan modal popup dengan:
echo - Background blur (backdrop-blur-sm)
echo - Popup di paling depan (z-index 9999)
echo - Serasi dengan modal lainnya di aplikasi
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
echo ✅ Tombol "Daftar Sekarang" menggunakan wire:click="openConfirmModal"
echo ✅ Modal konfirmasi dengan background blur (bg-black/30 backdrop-blur-sm)
echo ✅ Modal di paling depan (z-index 9999)
echo ✅ Serasi dengan modal lainnya di aplikasi (pattern yang sama)
echo ✅ Loading state pada tombol submit di modal
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
echo 4. HARUS MUNCUL MODAL POPUP dengan:
echo    ┌─────────────────────────────────────────┐
echo    │  Konfirmasi Pendaftaran                 │
echo    │  ─────────────────────────────────────  │
echo    │  Yakin ingin mendaftar?                 │
echo    │                                         │
echo    │  Data pendaftaran Anda sudah sesuai...  │
echo    │                                         │
echo    │  [ Batal ]  [ Ya, Daftar Sekarang ]    │
echo    └─────────────────────────────────────────┘
echo.
echo 5. Background harus blur dan gelap (backdrop-blur-sm)
echo.
echo 6. Klik "Ya, Daftar Sekarang" - Proses pendaftaran
echo    Klik "Batal" - Tutup modal
echo.
echo ========================================
echo EXPECTED RESULTS:
echo ========================================
echo.
echo ✅ Modal popup muncul dengan background blur
echo ✅ Tidak ada error di console
echo ✅ Proses pendaftaran berjalan normal
echo ✅ Logic NSM/NPSN autocomplete tetap berfungsi
echo ✅ Modal serasi dengan modal lainnya
echo.
echo ========================================
echo.
pause
