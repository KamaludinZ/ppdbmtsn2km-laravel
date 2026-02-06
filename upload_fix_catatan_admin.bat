@echo off
echo ========================================
echo UPLOAD FIX - CATATAN ADMIN (2 FILES)
echo ========================================
echo.
echo Files yang terlewat untuk fitur catatan:
echo 1. Admin DetailPendaftar.php
echo 2. Admin detail-pendaftar.blade.php
echo.
echo Target: pmbm.mtsn2kotamalang.sch.id
echo ========================================
echo.

REM Define variables
set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo [1/2] Uploading Admin DetailPendaftar.php...
scp "app/Livewire/Admin/DetailPendaftar.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Admin/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload DetailPendaftar.php
    echo.
    echo Kemungkinan penyebab:
    echo - SSH connection timeout
    echo - File terlalu besar (~80-100KB)
    echo - Network issue
    echo.
    echo Coba lagi atau upload manual via cPanel.
    pause
    exit /b 1
)
echo OK - DetailPendaftar.php uploaded (~80-100KB)
echo.

echo [2/2] Uploading Admin detail-pendaftar.blade.php...
scp "resources/views/livewire/admin/detail-pendaftar.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/admin/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload detail-pendaftar.blade.php
    echo.
    echo Kemungkinan penyebab:
    echo - SSH connection timeout
    echo - File terlalu besar (~100-120KB)
    echo - Network issue
    echo.
    echo Coba lagi atau upload manual via cPanel.
    pause
    exit /b 1
)
echo OK - detail-pendaftar.blade.php uploaded (~100-120KB)
echo.

echo ========================================
echo All files uploaded successfully!
echo ========================================
echo.
echo Now clearing caches...
echo.

REM Clear caches
ssh %SERVER% "cd %REMOTE_PATH% && php artisan view:clear"
if %errorlevel% neq 0 (
    echo WARNING: Failed to clear view cache
) else (
    echo OK - View cache cleared
)

ssh %SERVER% "cd %REMOTE_PATH% && php artisan config:clear"
if %errorlevel% neq 0 (
    echo WARNING: Failed to clear config cache
) else (
    echo OK - Config cache cleared
)

ssh %SERVER% "cd %REMOTE_PATH% && php artisan cache:clear"
if %errorlevel% neq 0 (
    echo WARNING: Failed to clear application cache
) else (
    echo OK - Application cache cleared
)

ssh %SERVER% "cd %REMOTE_PATH% && php artisan optimize:clear"
if %errorlevel% neq 0 (
    echo WARNING: Failed to clear optimize cache
) else (
    echo OK - Optimize cache cleared
)

echo.
echo ========================================
echo DEPLOYMENT COMPLETE!
echo ========================================
echo.
echo Next Steps - TESTING:
echo.
echo 1. TEST ADMIN:
echo    - Login sebagai admin
echo    - Access /admin/pendaftar
echo    - Klik salah satu pendaftar
echo    - Cek Header Info: harus ada "Catatan untuk Pendaftar"
echo    - Klik "Update Status": harus ada textarea "Catatan Verifikasi"
echo.
echo 2. INPUT CATATAN:
echo    - Isi catatan: "Mohon lengkapi data orang tua"
echo    - Pilih status: "Perbaiki Data"
echo    - Klik Simpan
echo.
echo 3. TEST PENDAFTAR:
echo    - Login sebagai pendaftar yang tadi diupdate
echo    - Access /pendaftar/dashboard
echo    - Catatan harus muncul di atas Progress Pendaftaran
echo    - Text harus JELAS TERLIHAT (dark gray on light yellow)
echo.
echo 4. TEST STATUS PAGE:
echo    - Access /pendaftar/status
echo    - Catatan harus muncul di bawah Status Card
echo.
echo ========================================
echo.
echo Expected Results:
echo [+] Admin header info: Catatan section visible
echo [+] Admin modal: Textarea catatan visible
echo [+] Pendaftar dashboard: Catatan box muncul (high contrast)
echo [+] Pendaftar status: Catatan box muncul (high contrast)
echo.
echo If catatan masih belum muncul di pendaftar:
echo - Pastikan status = perbaiki_data/lengkapi_data/data_ditolak
echo - Pastikan catatan_verifikasi tidak kosong
echo - Clear browser cache (Ctrl+Shift+R)
echo.
pause
