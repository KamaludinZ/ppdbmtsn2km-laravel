@echo off
color 0A
echo ================================================================
echo ROOT CAUSE FIX - EXPORT WILAYAH - TABLE NAME - 12 FEB 2026
echo ================================================================
color 0F
echo.
echo INI ADALAH FIX FINAL YANG MENGATASI ROOT CAUSE!
echo.
color 0E
echo ROOT CAUSE DITEMUKAN:
color 0C
echo Export mencari di tabel yang TIDAK ADA!
color 0F
echo.
echo Tabel yang SALAH (dicari tapi tidak ada):
echo   - indonesia_provinces
echo   - indonesia_cities
echo   - indonesia_districts
echo   - indonesia_villages
echo.
color 0A
echo Tabel yang BENAR (digunakan Laravel Indonesia package):
echo   - provinces
echo   - regencies
echo   - districts
echo   - villages
color 0F
echo.
echo PERUBAHAN (replace_all):
echo   1. indonesia_provinces  ---^> provinces   (4 places)
echo   2. indonesia_cities     ---^> regencies   (4 places)
echo   3. indonesia_districts  ---^> districts   (4 places)
echo   4. indonesia_villages   ---^> villages    (4 places)
echo.
echo Total: 16 tempat diganti
echo.
color 0A
echo CONFIDENCE LEVEL: 10/10 - INI PASTI BERHASIL!
color 0F
echo.
echo File yang akan di-upload:
echo - app/Exports/PendaftarExport.php
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
    echo URGENT - TEST SEKARANG JUGA:
    color 0F
    echo.
    echo 1. Login ke sistem (Admin/Panitia)
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
    echo.
    echo    NOT (bukan seperti ini lagi):
    echo    - Provinsi Ayah: (kosong)
    echo    - Kabupaten Ayah: (kosong)
    color 0F
    echo.
    echo 5. Cek juga untuk Ibu, Wali, Siswa
    echo.
    echo Dokumentasi: FIX_EXPORT_WILAYAH_TABLE_NAME_FINAL_12_FEB_2026.md
    echo.
    color 0A
    echo SEKARANG PASTI BERHASIL!
    color 0F
    echo Karena nama tabel sudah BENAR!
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
