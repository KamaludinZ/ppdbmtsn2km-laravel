@echo off
echo ============================================
echo UPLOAD FIX COMPLETE - COLUMN CODE ERROR
echo ============================================
echo.
echo MASALAH:
echo - Error 500 saat save data alamat di Admin dan Panitia
echo - Error "column code does not exist" di production
echo - Tabel wilayah di production tidak punya kolom 'code'
echo - Query menggunakan where('code', ...) yang tidak ada
echo.
echo SOLUSI:
echo - Ubah query dari where('code', ...) ke where('id', ...)
echo - Terapkan logic sama seperti di Pendaftar/Formulir.php
echo - Tambahkan try-catch untuk query postal_codes
echo - Auto-fill kode pos berdasarkan nama wilayah
echo.
echo FILE YANG AKAN DIUPLOAD (3 files):
echo 1. app/Livewire/Admin/DetailPendaftar.php
echo 2. app/Livewire/Panitia/DetailPendaftar.php
echo 3. app/Exports/PendaftarExport.php
echo.
echo LOKASI ERROR:
echo - updatedAyahKelurahan()
echo - updatedIbuKelurahan()
echo - updatedWaliKelurahan()
echo - updatedSiswaKelurahan()
echo - loadRegionCache() di Export
echo.
pause

echo.
echo [1/4] Upload Admin DetailPendaftar.php...
scp app/Livewire/Admin/DetailPendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
if %errorlevel% neq 0 (
    echo GAGAL upload Admin DetailPendaftar.php
    pause
    exit /b 1
)
echo BERHASIL upload Admin DetailPendaftar.php

echo.
echo [2/4] Upload Panitia DetailPendaftar.php...
scp app/Livewire/Panitia/DetailPendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/
if %errorlevel% neq 0 (
    echo GAGAL upload Panitia DetailPendaftar.php
    pause
    exit /b 1
)
echo BERHASIL upload Panitia DetailPendaftar.php

echo.
echo [3/4] Upload PendaftarExport.php...
scp app/Exports/PendaftarExport.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/
if %errorlevel% neq 0 (
    echo GAGAL upload PendaftarExport.php
    pause
    exit /b 1
)
echo BERHASIL upload PendaftarExport.php

echo.
echo [4/4] Clear cache production...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan cache:clear && php artisan view:clear"
if %errorlevel% neq 0 (
    echo WARNING: Gagal clear cache, silakan manual
) else (
    echo BERHASIL clear cache
)

echo.
echo ============================================
echo UPLOAD SELESAI!
echo ============================================
echo.
echo TESTING YANG HARUS DILAKUKAN:
echo.
echo 1. TEST SAVE ALAMAT DI ADMIN:
echo    - Login sebagai Admin
echo    - Buka detail pendaftar
echo    - Edit tab Alamat (Ayah/Ibu/Wali/Siswa)
echo    - Ubah dropdown wilayah
echo    - Klik Simpan
echo    - Pastikan TIDAK ERROR 500
echo.
echo 2. TEST SAVE ALAMAT DI PANITIA-PENDAFTARAN:
echo    - Login sebagai Panitia
echo    - Buka detail pendaftar
echo    - Edit tab Alamat
echo    - Ubah dropdown wilayah
echo    - Klik Simpan
echo    - Pastikan TIDAK ERROR 500
echo.
echo 3. TEST AUTO-FILL KODE POS:
echo    - Saat pilih kelurahan/desa
echo    - Kode pos otomatis terisi (jika ada data)
echo    - Jika tidak terisi, bisa input manual
echo.
echo 4. TEST EXPORT DATA PENDAFTAR:
echo    - Login sebagai Admin/Panitia
echo    - Menu Pendaftar
echo    - Klik Export Excel
echo    - Pastikan file ter-download tanpa error
echo    - Buka Excel, cek nama wilayah muncul
echo.
echo 5. CEK LOG ERROR:
echo    - Pastikan tidak ada error:
echo      "column code does not exist"
echo.
pause
