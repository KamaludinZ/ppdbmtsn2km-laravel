@echo off
echo ============================================================
echo UPLOAD FIX - HISTORY EMAIL DAN WHATSAPP
echo ============================================================
echo.
echo File yang akan diupload:
echo - app/Livewire/Auth/Register.php (UPDATED)
echo - app/Livewire/Auth/RegisterFixed.php (UPDATED)
echo - app/Livewire/Auth/RegisterOptimized.php (UPDATED)
echo - database/migrations/2026_01_23_102510_create_riwayat_pesan_table.php (EXISTING)
echo.
echo ============================================================
echo PASTIKAN:
echo 1. Tabel riwayat_pesan sudah ada di production
echo 2. Jika belum ada, jalankan migration dulu
echo ============================================================
echo.
pause

echo.
echo [1/4] Uploading Register.php...
scp app/Livewire/Auth/Register.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Auth/
if errorlevel 1 (
    echo ERROR: Gagal upload Register.php
    pause
    exit /b 1
)
echo SUCCESS: Register.php uploaded

echo.
echo [2/4] Uploading RegisterFixed.php...
scp app/Livewire/Auth/RegisterFixed.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Auth/
if errorlevel 1 (
    echo ERROR: Gagal upload RegisterFixed.php
    pause
    exit /b 1
)
echo SUCCESS: RegisterFixed.php uploaded

echo.
echo [3/4] Uploading RegisterOptimized.php...
scp app/Livewire/Auth/RegisterOptimized.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Auth/
if errorlevel 1 (
    echo ERROR: Gagal upload RegisterOptimized.php
    pause
    exit /b 1
)
echo SUCCESS: RegisterOptimized.php uploaded

echo.
echo [4/4] Uploading migration file...
scp database/migrations/2026_01_23_102510_create_riwayat_pesan_table.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/
if errorlevel 1 (
    echo ERROR: Gagal upload migration file
    pause
    exit /b 1
)
echo SUCCESS: Migration file uploaded

echo.
echo ============================================================
echo LANGKAH SELANJUTNYA DI PRODUCTION:
echo ============================================================
echo.
echo 1. Cek apakah tabel riwayat_pesan sudah ada:
echo    ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan migrate:status"
echo.
echo 2. Jika tabel belum ada, jalankan migration:
echo    ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan migrate"
echo.
echo 3. Clear cache:
echo    ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
echo.
echo 4. Test registrasi baru dan cek history muncul di:
echo    - /admin/pengaturan-pesan?activeTab=history-email
echo    - /admin/pengaturan-pesan?activeTab=history-whatsapp
echo.
echo ============================================================
echo UPLOAD SELESAI!
echo ============================================================
pause
