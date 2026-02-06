@echo off
echo ========================================
echo   FIX: 404 Bukti Tes Psikologi
echo ========================================
echo.
echo Error: Bukti tes psikologi 404 saat diakses admin/panitia
echo Fix: Secure file serving dengan authorization
echo.
echo Tanggal: %date% %time%
echo.

echo [1/4] Uploading SecureFileController.php (Added viewBuktiBayar method)...
scp app/Http/Controllers/SecureFileController.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Http/Controllers/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload SecureFileController.php
    pause
    exit /b 1
)
echo OK - SecureFileController.php uploaded
echo.

echo [2/4] Uploading web.php (Added view.bukti-bayar route)...
scp routes/web.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/routes/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload web.php
    pause
    exit /b 1
)
echo OK - web.php uploaded
echo.

echo [3/4] Uploading admin view (tes-psikologi-data.blade.php)...
scp resources/views/livewire/admin/partials/tes-psikologi-data.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload tes-psikologi-data.blade.php
    pause
    exit /b 1
)
echo OK - Admin view uploaded (panitia juga otomatis fix karena pakai view yang sama)
echo.

echo [4/4] Uploading pendaftar view (tes-psikologi.blade.php)...
scp resources/views/livewire/pendaftar/tes-psikologi.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload tes-psikologi.blade.php
    pause
    exit /b 1
)
echo OK - Pendaftar view uploaded
echo.

echo ========================================
echo   Upload Selesai!
echo ========================================
echo.
echo PENTING: Jalankan perintah berikut di server:
echo.
echo ssh mtsnkot4@103.30.244.77
echo cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
echo php artisan route:clear
echo php artisan view:clear
echo php artisan cache:clear
echo.
echo ========================================
echo   Testing Checklist:
echo ========================================
echo.
echo 1. Test sebagai Admin:
echo    - Buka Tes Psikologi ^> Data Psikologi
echo    - Klik "Lihat" pada bukti transfer
echo    - Harus muncul gambar (tidak 404)
echo.
echo 2. Test sebagai Panitia:
echo    - Buka Tes Psikologi
echo    - Klik "Lihat" pada bukti transfer
echo    - Harus muncul gambar (tidak 404)
echo.
echo 3. Test sebagai Pendaftar:
echo    - Buka menu Tes Psikologi
echo    - Gambar bukti harus muncul (tidak 404)
echo    - Klik "Lihat Bukti" (jika disetujui)
echo    - Harus muncul gambar (tidak 404)
echo.
echo 4. Test Authorization:
echo    - Login sebagai Pendaftar A
echo    - Coba akses: /view/bukti-bayar/{id_pendaftar_B}/psikologi
echo    - Harus 403 Forbidden (tidak boleh akses)
echo.
echo ========================================
echo   What's Fixed:
echo ========================================
echo.
echo BEFORE:
echo - Bukti psikologi 404 karena file di private storage
echo - URL: /storage/bukti_bayar/27/bukti_psikologi-...jpg
echo - File di: storage/app/private/ (tidak bisa diakses via symlink)
echo.
echo AFTER:
echo - File served via secure route dengan authorization
echo - URL: /view/bukti-bayar/27/psikologi
echo - Authorization: Admin/Panitia ^= all, Pendaftar ^= own only
echo - File tetap aman di private storage
echo.
echo ========================================
echo   Security Benefits:
echo ========================================
echo.
echo [+] File tidak bisa diakses langsung via URL public
echo [+] Authorization check sebelum serve file
echo [+] Admin/Panitia bisa akses semua file
echo [+] Pendaftar hanya bisa akses file mereka sendiri
echo [+] Unauthorized users mendapat 403 Forbidden
echo.

pause
