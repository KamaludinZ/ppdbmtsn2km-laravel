@echo off
echo ========================================
echo UPLOAD DEBUG SCRIPT WILAYAH
echo ========================================
echo.
echo File yang akan di-upload:
echo - debug_wilayah_export.php
echo.
echo Setelah upload, akses di browser:
echo https://pmbm.mtsn2kotamalang.sch.id/debug_wilayah_export.php
echo.
pause

echo.
echo Uploading debug script...
echo.

scp "C:\ppdbmtsn2km-laravel\debug_wilayah_export.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/

if %errorlevel% == 0 (
    echo.
    echo ========================================
    echo UPLOAD BERHASIL!
    echo ========================================
    echo.
    echo Akses di browser:
    echo https://pmbm.mtsn2kotamalang.sch.id/debug_wilayah_export.php
    echo.
    echo Script akan menampilkan:
    echo 1. Sample data pendaftar (ayah_provinsi, ibu_provinsi, dll)
    echo 2. Isi tabel indonesia_provinces
    echo 3. Test lookup provinsi
    echo 4. Struktur tabel
    echo.
    echo Screenshot hasil dan kirim ke saya!
    echo.
) else (
    echo.
    echo ========================================
    echo UPLOAD GAGAL!
    echo ========================================
    echo.
    echo Upload manual via cPanel File Manager ke root folder
    echo.
)

pause
