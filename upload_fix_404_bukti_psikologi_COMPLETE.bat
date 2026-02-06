@echo off
echo ========================================
echo   COMPLETE FIX: 404 Bukti Tes Psikologi
echo ========================================
echo.
echo Fix untuk masalah:
echo - Bukti psikologi 404 saat diakses admin/panitia
echo - Gambar tidak muncul setelah upload di halaman pendaftar
echo.
echo Tanggal: %date% %time%
echo.
pause

echo.
echo ========================================
echo   STEP 1: Upload Controller
echo ========================================
echo [1/5] Uploading SecureFileController.php...
scp app/Http/Controllers/SecureFileController.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Http/Controllers/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload SecureFileController.php
    pause
    exit /b 1
)
echo OK - Controller uploaded
echo.

echo ========================================
echo   STEP 2: Upload Routes
echo ========================================
echo [2/5] Uploading web.php...
scp routes/web.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/routes/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload web.php
    pause
    exit /b 1
)
echo OK - Routes uploaded
echo.

echo ========================================
echo   STEP 3: Upload Admin View
echo ========================================
echo [3/5] Uploading tes-psikologi-data.blade.php...
scp resources/views/livewire/admin/partials/tes-psikologi-data.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload admin view
    pause
    exit /b 1
)
echo OK - Admin view uploaded (panitia auto-fix)
echo.

echo ========================================
echo   STEP 4: Upload Pendaftar View
echo ========================================
echo [4/5] Uploading tes-psikologi.blade.php (WITH ERROR HANDLING)...
scp resources/views/livewire/pendaftar/tes-psikologi.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload pendaftar view
    pause
    exit /b 1
)
echo OK - Pendaftar view uploaded (with error handling)
echo.

echo ========================================
echo   STEP 5: Clear All Cache
echo ========================================
echo [5/5] Clearing cache di production...
echo.
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan route:clear && echo 'Route cache cleared' && php artisan view:clear && echo 'View cache cleared' && php artisan cache:clear && echo 'App cache cleared' && php artisan config:clear && echo 'Config cache cleared'"
if %errorlevel% neq 0 (
    echo.
    echo WARNING: Gagal clear cache via SSH
    echo Silakan jalankan manual:
    echo.
    echo ssh mtsnkot4@103.30.244.77
    echo cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
    echo php artisan route:clear
    echo php artisan view:clear
    echo php artisan cache:clear
    echo php artisan config:clear
    echo.
    pause
) else (
    echo OK - All cache cleared
    echo.
)

echo.
echo ========================================
echo   Upload Selesai!
echo ========================================
echo.
echo Files uploaded:
echo [+] SecureFileController.php (viewBuktiBayar method)
echo [+] web.php (view.bukti-bayar route)
echo [+] tes-psikologi-data.blade.php (admin/panitia)
echo [+] tes-psikologi.blade.php (pendaftar with error handling)
echo.
echo Cache cleared:
echo [+] Route cache
echo [+] View cache
echo [+] App cache
echo [+] Config cache
echo.
pause

echo.
echo ========================================
echo   TESTING MANUAL (PENTING!)
echo ========================================
echo.
echo 1. Test sebagai PENDAFTAR yang sudah upload bukti:
echo    - Login ke /pendaftar/login
echo    - Buka menu "Tes Psikologi"
echo    - Refresh halaman dengan Ctrl+Shift+R (hard refresh)
echo    - Gambar bukti HARUS muncul
echo    - Jika muncul pesan "Gagal memuat gambar" = ada masalah
echo.
echo 2. Test sebagai ADMIN:
echo    - Login ke /admin/login
echo    - Buka "Tes Psikologi" -^> Tab "Data Psikologi"
echo    - Klik tombol "Lihat" pada baris pendaftar yang ada bukti
echo    - Gambar HARUS muncul di tab baru
echo.
echo 3. Test sebagai PANITIA:
echo    - Login ke /panitia/login
echo    - Buka menu "Tes Psikologi"
echo    - Klik tombol "Lihat" pada bukti transfer
echo    - Gambar HARUS muncul di tab baru
echo.
pause

echo.
echo ========================================
echo   TROUBLESHOOTING (Jika masih error)
echo ========================================
echo.
echo Jika gambar masih tidak muncul:
echo.
echo 1. Check route di server:
echo    ssh mtsnkot4@103.30.244.77
echo    cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
echo    php artisan route:list ^| grep bukti-bayar
echo    # Harus ada: GET ^| view/bukti-bayar/{pendaftar}/{type}
echo.
echo 2. Check file exists:
echo    ls -la app/Http/Controllers/SecureFileController.php
echo    grep -n "viewBuktiBayar" app/Http/Controllers/SecureFileController.php
echo    # Harus ada method viewBuktiBayar
echo.
echo 3. Check view updated:
echo    grep -n "route.*view.bukti-bayar" resources/views/livewire/pendaftar/tes-psikologi.blade.php
echo    # Harus ada baris dengan route view.bukti-bayar
echo.
echo 4. Test route langsung (ganti 27 dengan ID pendaftar real):
echo    curl -I https://pmbm.mtsn2kotamalang.sch.id/view/bukti-bayar/27/psikologi
echo    # Harus return 200 OK atau 302 redirect (jika belum login)
echo.
echo 5. Check log error:
echo    tail -50 storage/logs/laravel.log
echo    # Cari error terkait bukti-bayar atau SecureFileController
echo.
pause

echo.
echo ========================================
echo   QUICK VERIFICATION COMMANDS
echo ========================================
echo.
echo Jalankan di server untuk verify:
echo.
echo # 1. Check route registered
echo php artisan route:list --name=view.bukti-bayar
echo.
echo # 2. Check controller method exists
echo grep -A 20 "function viewBuktiBayar" app/Http/Controllers/SecureFileController.php
echo.
echo # 3. Check view updated
echo grep "view.bukti-bayar.*psikologi" resources/views/livewire/pendaftar/tes-psikologi.blade.php
echo.
echo # 4. Clear cache again (just to be sure)
echo php artisan optimize:clear
echo.
pause

echo.
echo ========================================
echo   EXPECTED RESULTS
echo ========================================
echo.
echo BEFORE:
echo - URL: /storage/bukti_bayar/27/bukti_psikologi-...jpg
echo - Status: 404 Not Found
echo - Pendaftar: Gambar tidak muncul
echo - Admin/Panitia: 404 saat klik Lihat
echo.
echo AFTER:
echo - URL: /view/bukti-bayar/27/psikologi
echo - Status: 200 OK (atau 302 jika belum login)
echo - Pendaftar: Gambar muncul di halaman
echo - Admin/Panitia: Gambar muncul saat klik Lihat
echo - Error handling: Jika gagal muncul pesan error
echo.
echo ========================================
echo.
pause
