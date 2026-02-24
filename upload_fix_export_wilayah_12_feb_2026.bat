@echo off
echo ========================================
echo FIX EXPORT WILAYAH KOSONG - 12 FEB 2026
echo ========================================
echo.
echo File yang akan di-upload:
echo - app/Exports/PendaftarExport.php
echo.
echo Perubahan:
echo 1. Tambah trim() di loadRegionCache()
echo 2. Tambah trim() dan fallback query di getRegionName()
echo 3. Kolom provinsi/kabupaten/kecamatan/kelurahan sekarang muncul
echo.
echo Solusi untuk:
echo - Whitespace/trailing space di ID wilayah
echo - Cache miss (fallback langsung query ke DB)
echo.
pause

echo.
echo Uploading file ke production...
echo.

scp "C:\ppdbmtsn2km-laravel\app\Exports\PendaftarExport.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/

if %errorlevel% == 0 (
    echo.
    echo ========================================
    echo UPLOAD BERHASIL!
    echo ========================================
    echo.
    echo File berhasil di-upload ke production.
    echo.
    echo NEXT STEPS:
    echo 1. Export data pendaftar lengkap
    echo 2. Verifikasi kolom wilayah MUNCUL dengan NAMA (bukan ID)
    echo 3. Cek provinsi/kabupaten/kecamatan/kelurahan di:
    echo    - Alamat Ayah
    echo    - Alamat Ibu
    echo    - Alamat Wali
    echo    - Alamat Siswa
    echo.
    echo Expected: "Jawa Timur", "Kota Malang", dll
    echo NOT: "35", "3573", atau (kosong)
    echo.
    echo Dokumentasi: FIX_EXPORT_WILAYAH_KOSONG_12_FEB_2026.md
    echo.
) else (
    echo.
    echo ========================================
    echo UPLOAD GAGAL!
    echo ========================================
    echo.
    echo Silakan upload manual via cPanel File Manager:
    echo 1. Login ke cPanel
    echo 2. Buka File Manager
    echo 3. Upload ke: app/Exports/PendaftarExport.php
    echo.
)

pause
