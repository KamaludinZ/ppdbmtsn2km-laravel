@echo off
echo =========================================
echo FIX EXPORT LAPORAN PENDAFTAR - 12 FEB 2026
echo =========================================
echo.
echo File yang akan di-upload:
echo - app/Exports/PendaftarExport.php
echo.
echo Perubahan:
echo 1. Fix NIK dan Nomor KK digit terakhir jadi 0
echo 2. Fix data bergeser dari kolom
echo.
pause

echo.
echo Uploading file ke production...
echo.

scp "C:\ppdbmtsn2km-laravel\app\Exports\PendaftarExport.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/

if %errorlevel% == 0 (
    echo.
    echo =========================================
    echo UPLOAD BERHASIL!
    echo =========================================
    echo.
    echo File berhasil di-upload ke production.
    echo.
    echo NEXT STEPS:
    echo 1. Test export laporan data pendaftar lengkap
    echo 2. Verifikasi NIK dan Nomor KK lengkap 16 digit
    echo 3. Verifikasi kolom tidak bergeser
    echo.
    echo Dokumentasi: FIX_EXPORT_LAPORAN_PENDAFTAR_12_FEB_2026.md
    echo.
) else (
    echo.
    echo =========================================
    echo UPLOAD GAGAL!
    echo =========================================
    echo.
    echo Silakan check koneksi SSH dan coba lagi.
    echo.
)

pause
