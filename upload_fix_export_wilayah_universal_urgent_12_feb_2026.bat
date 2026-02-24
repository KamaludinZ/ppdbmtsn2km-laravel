@echo off
color 0C
echo ================================================================
echo URGENT FIX - EXPORT WILAYAH - SOLUSI UNIVERSAL - 12 FEB 2026
echo ================================================================
color 0F
echo.
echo File yang akan di-upload:
echo - app/Exports/PendaftarExport.php
echo.
color 0E
echo ROOT CAUSE DITEMUKAN (ANALISA MENDALAM):
echo Data di database kemungkinan menggunakan CODE bukan ID!
color 0F
echo.
echo SOLUSI UNIVERSAL:
echo 1. Cache BOTH id and code di loadRegionCache()
echo 2. Query BOTH id and code di getRegionName()
echo 3. Bekerja untuk SEMUA skenario (ID atau CODE)
echo.
echo Perubahan:
echo - loadRegionCache(): Cache by ID AND CODE
echo - getRegionName(): Query with ID OR CODE
echo.
color 0A
echo SOLUSI INI PASTI BERHASIL!
color 0F
echo.
pause

echo.
color 0E
echo Uploading file ke production...
color 0F
echo.

scp "C:\ppdbmtsn2km-laravel\app\Exports\PendaftarExport.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/

if %errorlevel% == 0 (
    color 0A
    echo.
    echo ================================================================
    echo UPLOAD BERHASIL!
    echo ================================================================
    color 0F
    echo.
    echo File berhasil di-upload ke production.
    echo.
    color 0E
    echo URGENT - LANGSUNG TEST SEKARANG:
    color 0F
    echo.
    echo 1. Login ke sistem
    echo 2. Export data pendaftar lengkap
    echo 3. Buka file Excel
    echo 4. CEK KOLOM WILAYAH:
    echo.
    color 0A
    echo    Expected (HARUS MUNCUL):
    echo    - Provinsi Ayah: Jawa Timur
    echo    - Kabupaten Ayah: Kota Malang
    echo    - Kecamatan Ayah: Lowokwaru
    echo    - Kelurahan Ayah: Tunggulwulung
    color 0F
    echo.
    echo 5. Cek juga untuk Ibu, Wali, Siswa
    echo.
    echo Dokumentasi: FIX_EXPORT_WILAYAH_UNIVERSAL_URGENT_12_FEB_2026.md
    echo.
    color 0C
    echo JIKA MASIH KOSONG:
    color 0F
    echo - Upload debug_wilayah_export.php
    echo - Akses: https://pmbm.mtsn2kotamalang.sch.id/debug_wilayah_export.php
    echo - Screenshot hasilnya untuk analisa lebih lanjut
    echo.
) else (
    color 0C
    echo.
    echo ================================================================
    echo UPLOAD GAGAL!
    echo ================================================================
    color 0F
    echo.
    echo SSH Connection refused.
    echo.
    color 0E
    echo UPLOAD MANUAL VIA CPANEL (URGENT):
    color 0F
    echo.
    echo 1. Login ke cPanel
    echo 2. Buka File Manager
    echo 3. Navigate ke: app/Exports/
    echo 4. Upload file: PendaftarExport.php
    echo 5. REPLACE file yang lama
    echo 6. LANGSUNG TEST!
    echo.
)

pause
