@echo off
color 0A
echo ================================================================
echo ADD WHATSAPP CLICKABLE - KOLOM ORTU WHATSAPP - 12 FEB 2026
echo ================================================================
color 0F
echo.
echo FITUR BARU:
echo   1. Nomor WhatsApp Ortu jadi clickable link
echo   2. Klik langsung buka chat WhatsApp
echo   3. Kolom baru di Tes Psikologi (sebelah kiri Kirim Pesan)
echo.
color 0E
echo LOKASI PERUBAHAN:
color 0F
echo - /admin/pendaftar: Kolom Ortu WhatsApp jadi clickable
echo - /panitia/pendaftar: Kolom Ortu WhatsApp jadi clickable
echo - /admin/tes-psikologi: Tambah kolom Ortu WhatsApp baru
echo - /panitia/tes-psikologi: Tambah kolom Ortu WhatsApp baru
echo.
color 0A
echo SOLUSI:
color 0F
echo 1. Format nomor ke Indonesia (62xxx)
echo 2. Generate WhatsApp URL: https://wa.me/62xxx
echo 3. Tambah icon WhatsApp SVG
echo 4. Hover effect (underline + darker green)
echo 5. Open in new tab
echo.
echo File yang akan di-upload:
echo   1. resources/views/livewire/admin/pendaftar.blade.php
echo   2. resources/views/livewire/panitia/pendaftar.blade.php
echo   3. resources/views/livewire/admin/partials/tes-psikologi-data.blade.php
echo.
color 0E
echo FITUR:
color 0F
echo - Klik nomor langsung buka WhatsApp
echo - Format otomatis (0812... → 628123...)
echo - WhatsApp icon hijau
echo - Hover: darker green + underline
echo - Buka di tab baru
echo - Support filter "Belum Upload" di Tes Psikologi
echo.
pause

echo.
color 0E
echo Uploading files ke production...
color 0F
echo.

echo [1/3] Uploading admin/pendaftar.blade.php...
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\pendaftar.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/

if %errorlevel% neq 0 (
    color 0C
    echo GAGAL upload admin/pendaftar.blade.php!
    color 0F
    goto manual_upload
)

echo [2/3] Uploading panitia/pendaftar.blade.php...
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\panitia\pendaftar.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/

if %errorlevel% neq 0 (
    color 0C
    echo GAGAL upload panitia/pendaftar.blade.php!
    color 0F
    goto manual_upload
)

echo [3/3] Uploading tes-psikologi-data.blade.php...
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\partials\tes-psikologi-data.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/

if %errorlevel% neq 0 (
    color 0C
    echo GAGAL upload tes-psikologi-data.blade.php!
    color 0F
    goto manual_upload
)

color 0A
echo.
echo ================================================================
echo UPLOAD BERHASIL!
echo ================================================================
color 0F
echo.
echo Files berhasil di-upload ke production.
echo.
color 0E
echo TESTING STEPS:
color 0F
echo.
echo 1. TEST DI ADMIN PENDAFTAR:
echo    - Login sebagai Admin
echo    - Buka menu Pendaftar
echo    - Scroll ke kolom "Ortu WhatsApp"
echo    - Hover nomor (verifikasi: underline + darker green)
echo    - Klik nomor
echo    - Verifikasi: Buka WhatsApp Web/App di tab baru
echo.
echo 2. TEST DI PANITIA PENDAFTAR:
echo    - Login sebagai Panitia
echo    - Buka menu Pendaftar
echo    - Test sama seperti Admin
echo.
echo 3. TEST DI ADMIN TES PSIKOLOGI:
echo    - Login sebagai Admin
echo    - Buka menu Tes Psikologi ^> Data Psikologi
echo    - Scroll horizontal ke kanan
echo    - Verifikasi: Kolom "Ortu WhatsApp" SEBELAH KIRI "Ubah Status"
echo    - Klik nomor WhatsApp
echo    - Verifikasi: Buka chat WhatsApp
echo.
echo 4. TEST FILTER BELUM UPLOAD:
echo    - Di menu Tes Psikologi
echo    - Pilih filter "Belum Upload"
echo    - Verifikasi: Kolom Ortu WhatsApp tetap tampil
echo    - Klik nomor
echo    - Verifikasi: Buka WhatsApp
echo.
echo 5. TEST EDGE CASES:
echo    - Nomor dengan format: 0812-3456-7890
echo    - Nomor dengan format: 812 345 6789
echo    - Nomor dengan format: +62 812 345 6789
echo    - Nomor kosong: Tampil "-" (gray, tidak clickable)
echo.
color 0A
echo Expected Result:
echo   - Nomor tampil dengan icon WhatsApp hijau
echo   - Klik langsung buka chat
echo   - Format otomatis ke 62xxx
echo   - Hover: underline + darker green
echo   - Buka di tab baru
echo   - Kolom baru di Tes Psikologi (kiri Kirim Pesan)
color 0F
echo.
echo Dokumentasi: ADD_WHATSAPP_CLICKABLE_ORTU_12_FEB_2026.md
echo.
goto end

:manual_upload
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
echo UPLOAD MANUAL VIA CPANEL:
color 0F
echo.
echo 1. Login ke cPanel
echo 2. Buka File Manager
echo.
echo 3. Upload file pertama:
echo    - Navigate ke: resources/views/livewire/admin/
echo    - Upload: pendaftar.blade.php
echo    - REPLACE file yang lama
echo.
echo 4. Upload file kedua:
echo    - Navigate ke: resources/views/livewire/panitia/
echo    - Upload: pendaftar.blade.php
echo    - REPLACE file yang lama
echo.
echo 5. Upload file ketiga:
echo    - Navigate ke: resources/views/livewire/admin/partials/
echo    - Upload: tes-psikologi-data.blade.php
echo    - REPLACE file yang lama
echo.
echo 6. LANGSUNG TEST!
echo    Tidak perlu clear cache karena hanya view yang berubah.
echo.

:end
pause
