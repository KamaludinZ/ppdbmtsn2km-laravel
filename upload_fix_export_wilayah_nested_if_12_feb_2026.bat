@echo off
echo ================================================================
echo FIX EXPORT WILAYAH - HAPUS NESTED IF - 12 FEB 2026
echo ================================================================
echo.
echo File yang akan di-upload:
echo - app/Exports/PendaftarExport.php
echo.
echo ROOT CAUSE DITEMUKAN:
echo Nested if yang terlalu strict menghalangi data wilayah muncul!
echo.
echo PERUBAHAN:
echo 1. Alamat Ayah: Hapus if(!ayah_tinggal_luar_negeri)
echo 2. Alamat Ibu: Hapus if(!ibu_sama_dengan_ayah) dan if(!ibu_tinggal_luar_negeri)
echo 3. Alamat Wali: Hapus if(!wali_sama_dengan_ayah) dan if(!wali_tinggal_luar_negeri)
echo.
echo SEKARANG: Data wilayah SELALU ditampilkan jika ada di database
echo.
pause

echo.
echo Uploading file ke production...
echo.

scp "C:\ppdbmtsn2km-laravel\app\Exports\PendaftarExport.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/

if %errorlevel% == 0 (
    echo.
    echo ================================================================
    echo UPLOAD BERHASIL!
    echo ================================================================
    echo.
    echo File berhasil di-upload ke production.
    echo.
    echo NEXT STEPS - TEST:
    echo 1. Export data pendaftar lengkap
    echo 2. Buka file Excel
    echo 3. Cari pendaftar yang punya data alamat
    echo 4. Verifikasi kolom wilayah MUNCUL:
    echo.
    echo    Expected:
    echo    - Provinsi Ayah: Jawa Timur
    echo    - Kabupaten Ayah: Kota Malang
    echo    - Kecamatan Ayah: Lowokwaru
    echo    - Kelurahan Ayah: Tunggulwulung
    echo.
    echo    NOT:
    echo    - Provinsi Ayah: (kosong)
    echo    - Kabupaten Ayah: (kosong)
    echo.
    echo 5. Cek juga untuk Ibu, Wali, dan Siswa
    echo.
    echo Dokumentasi: FIX_EXPORT_WILAYAH_NESTED_IF_12_FEB_2026.md
    echo.
    echo JIKA MASIH KOSONG:
    echo - Kemungkinan data di database memang NULL/kosong
    echo - Upload debug_wilayah_export.php untuk cek data mentah
    echo - Jalankan: upload_debug_wilayah.bat
    echo.
) else (
    echo.
    echo ================================================================
    echo UPLOAD GAGAL!
    echo ================================================================
    echo.
    echo Upload manual via cPanel File Manager:
    echo 1. Login ke cPanel
    echo 2. Buka File Manager
    echo 3. Upload ke: app/Exports/PendaftarExport.php
    echo.
)

pause
