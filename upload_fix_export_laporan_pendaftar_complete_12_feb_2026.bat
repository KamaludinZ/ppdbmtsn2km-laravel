@echo off
echo ===============================================================
echo FIX EXPORT LAPORAN PENDAFTAR LENGKAP - COMPLETE - 12 FEB 2026
echo ===============================================================
echo.
echo File yang akan di-upload:
echo - app/Exports/PendaftarExport.php
echo.
echo Perubahan LENGKAP:
echo 1. Fix NIK dan Nomor KK digit terakhir jadi 0 (tab character)
echo 2. Fix data bergeser dari kolom (array_fill count)
echo 3. Fix kolom provinsi/kabupaten/kecamatan/kelurahan (tampil nama)
echo 4. Fix kondisi status tempat tinggal siswa (selain ayah/ibu)
echo 5. Fix jarak/transportasi/waktu tempuh selalu muncul
echo 6. Tambah kolom SURVEI UMUM (dynamic)
echo 7. Tambah kolom KEAHLIAN (max 3 records, 6 fields each)
echo 8. Tambah kolom TAHFIDZ (1 record, 3 fields)
echo.
echo Kolom Baru:
echo - Keahlian 1-3: 18 kolom total
echo - Tahfidz: 3 kolom
echo - Survei Umum: Dynamic
echo - Survei Tes Psikologi: Dynamic (sudah ada, diubah label)
echo.
pause

echo.
echo Uploading file ke production...
echo.

scp "C:\ppdbmtsn2km-laravel\app\Exports\PendaftarExport.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/

if %errorlevel% == 0 (
    echo.
    echo ===============================================================
    echo UPLOAD BERHASIL!
    echo ===============================================================
    echo.
    echo File berhasil di-upload ke production.
    echo.
    echo NEXT STEPS - TEST CHECKLIST:
    echo [ ] 1. Export data pendaftar lengkap
    echo [ ] 2. Verifikasi NIK dan Nomor KK lengkap 16 digit
    echo [ ] 3. Verifikasi kolom tidak bergeser
    echo [ ] 4. Verifikasi provinsi/kabupaten tampil NAMA bukan ID
    echo [ ] 5. Verifikasi alamat siswa muncul saat status bukan ayah/ibu
    echo [ ] 6. Verifikasi jarak/transportasi/waktu tempuh selalu ada
    echo [ ] 7. Verifikasi ada kolom SURVEI UMUM
    echo [ ] 8. Verifikasi ada kolom SURVEI TES PSIKOLOGI
    echo [ ] 9. Verifikasi ada kolom KEAHLIAN 1-3
    echo [ ] 10. Verifikasi ada kolom TAHFIDZ
    echo.
    echo Dokumentasi: FIX_EXPORT_LAPORAN_PENDAFTAR_COMPLETE_12_FEB_2026.md
    echo.
) else (
    echo.
    echo ===============================================================
    echo UPLOAD GAGAL!
    echo ===============================================================
    echo.
    echo Silakan check koneksi SSH dan coba lagi.
    echo Atau upload manual via cPanel File Manager:
    echo 1. Login ke cPanel
    echo 2. Buka File Manager
    echo 3. Upload file ke: app/Exports/PendaftarExport.php
    echo.
)

pause
