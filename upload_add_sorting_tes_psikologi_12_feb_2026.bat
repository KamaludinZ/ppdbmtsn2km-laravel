@echo off
color 0A
echo ================================================================
echo ADD SORTING ^& FILTER BELUM UPLOAD - TES PSIKOLOGI - 12 FEB 2026
echo ================================================================
color 0F
echo.
echo FITUR BARU:
echo   1. Sorting pada Header Kolom
echo   2. Filter Status "Belum Upload"
echo.
color 0E
echo ROOT CAUSE SORTING:
color 0F
echo - Trait WithSorting sudah diisi (fixed sebelumnya)
echo - View sudah ada sortable-header component
echo - Tapi belum applied di component TesPsikologi
echo.
color 0E
echo ROOT CAUSE FILTER BELUM UPLOAD:
color 0F
echo - Tidak ada opsi untuk lihat pendaftar yang belum upload
echo - Admin/Panitia sulit follow-up pembayaran
echo - Perlu filter khusus dengan query berbeda
echo.
color 0A
echo SOLUSI:
color 0F
echo 1. Tambah trait WithSorting ke Admin/TesPsikologi
echo 2. Tambah trait WithSorting ke Panitia/TesPsikologi
echo 3. Tambah filter "Belum Upload" di dropdown
echo 4. Tambah conditional header (sortable vs static)
echo 5. Tambah conditional tbody (Pendaftar vs TesPsikologi)
echo.
echo File yang akan di-upload:
echo   1. app/Livewire/Admin/TesPsikologi.php
echo   2. app/Livewire/Panitia/TesPsikologi.php
echo   3. resources/views/livewire/admin/partials/tes-psikologi-data.blade.php
echo.
color 0E
echo FITUR YANG DITAMBAHKAN:
color 0F
echo.
echo SORTING:
echo - Klik header kolom untuk sorting
echo - Toggle asc/desc saat klik ulang
echo - Icon arrow up/down
echo - Highlight kolom yang di-sort
echo.
echo Kolom Sortable (Belum Upload):
echo   - Nomor Pendaftaran, NISN, Nama Pendaftar
echo   - Jenis Kelamin, Status Pendaftaran
echo.
echo Kolom Sortable (Status Lain):
echo   - Waktu Transfer, Nama Pengirim
echo   - Status Psikologi
echo.
echo FILTER BELUM UPLOAD:
echo - Menampilkan pendaftar yang sudah disetujui
echo - Tapi BELUM upload bukti pembayaran
echo - Berguna untuk follow-up dan reminder
echo.
pause

echo.
color 0E
echo Uploading files ke production...
color 0F
echo.

echo [1/3] Uploading Admin/TesPsikologi.php...
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Admin\TesPsikologi.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/

if %errorlevel% neq 0 (
    color 0C
    echo GAGAL upload Admin/TesPsikologi.php!
    color 0F
    goto manual_upload
)

echo [2/3] Uploading Panitia/TesPsikologi.php...
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Panitia\TesPsikologi.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/

if %errorlevel% neq 0 (
    color 0C
    echo GAGAL upload Panitia/TesPsikologi.php!
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
echo 1. TEST SORTING DI ADMIN:
echo    - Login sebagai Admin
echo    - Buka menu Tes Psikologi ^> Data Psikologi
echo    - Klik header "Waktu Transfer"
echo    - Verifikasi data ter-sort
echo    - Verifikasi icon arrow muncul
echo    - Klik lagi untuk toggle desc
echo.
echo 2. TEST FILTER BELUM UPLOAD:
echo    - Pilih filter "Belum Upload"
echo    - Verifikasi tampil pendaftar yang sudah disetujui
echo    - Verifikasi kolom header berubah (Email, No HP, Status)
echo    - Klik header "Nomor Pendaftaran"
echo    - Verifikasi sorting bekerja
echo.
echo 3. TEST DI PANITIA:
echo    - Login sebagai Panitia (panitia_tes_psikologi)
echo    - Buka menu Tes Psikologi
echo    - Test sorting sama seperti Admin
echo    - Test filter "Belum Upload"
echo.
echo 4. TEST DENGAN SEARCH:
echo    - Pilih filter "Belum Upload"
echo    - Search nama pendaftar
echo    - Klik sorting
echo    - Verifikasi sorting bekerja pada hasil search
echo.
color 0A
echo Expected Result:
echo   - Klik header langsung sort data
echo   - Icon arrow up/down muncul
echo   - Filter "Belum Upload" menampilkan data yang benar
echo   - Kolom header conditional sesuai filter
echo   - Sorting preserved di URL
color 0F
echo.
echo Dokumentasi: ADD_SORTING_TES_PSIKOLOGI_12_FEB_2026.md
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
echo    - Navigate ke: app/Livewire/Admin/
echo    - Upload: TesPsikologi.php
echo    - REPLACE file yang lama
echo.
echo 4. Upload file kedua:
echo    - Navigate ke: app/Livewire/Panitia/
echo    - Upload: TesPsikologi.php
echo    - REPLACE file yang lama
echo.
echo 5. Upload file ketiga:
echo    - Navigate ke: resources/views/livewire/admin/partials/
echo    - Upload: tes-psikologi-data.blade.php
echo    - REPLACE file yang lama
echo.
echo 6. Clear cache production:
echo    - Jalankan: php artisan optimize:clear
echo.
echo 7. LANGSUNG TEST!
echo.

:end
pause
