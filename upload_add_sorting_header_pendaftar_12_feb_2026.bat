@echo off
color 0A
echo ================================================================
echo ADD SORTING HEADER - ADMIN ^& PANITIA PENDAFTAR - 12 FEB 2026
echo ================================================================
color 0F
echo.
echo FITUR BARU: Sorting pada Header Kolom
echo.
echo ROOT CAUSE:
color 0C
echo - Trait WithSorting KOSONG (tidak ada implementasi)
color 0F
echo - View Admin sudah pakai sortable-header tapi tidak bekerja
echo - View Panitia masih pakai header biasa
echo.
color 0A
echo SOLUSI:
color 0F
echo 1. Isi trait WithSorting dengan implementasi lengkap
echo 2. Update view Panitia dengan sortable-header
echo.
echo File yang akan di-upload:
echo   1. app/WithSorting.php
echo   2. resources/views/livewire/panitia/pendaftar.blade.php
echo.
color 0E
echo FITUR YANG DITAMBAHKAN:
color 0F
echo - Klik header kolom untuk sorting
echo - Toggle asc/desc saat klik ulang
echo - Icon arrow up/down
echo - Highlight kolom yang di-sort
echo - Sorting preserved di URL
echo - Reset pagination otomatis
echo.
echo Kolom yang bisa di-sort:
echo   - Nomor Pendaftaran
echo   - Nama Lengkap
echo   - Jenis Kelamin
echo   - Asal Kab/Kota
echo   - Jenis Sekolah, NSM/NPSN, Asal Sekolah
echo   - Nama Ayah, Nama Ibu
echo   - Status Pendaftaran, Status Tes
echo   - Persetujuan Daftar Ulang
echo   - Diperbarui, Tanggal Daftar
echo.
pause

echo.
color 0E
echo Uploading files ke production...
color 0F
echo.

echo [1/2] Uploading WithSorting.php...
scp "C:\ppdbmtsn2km-laravel\app\WithSorting.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/

if %errorlevel% neq 0 (
    color 0C
    echo GAGAL upload WithSorting.php!
    color 0F
    goto manual_upload
)

echo [2/2] Uploading panitia/pendaftar.blade.php...
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\panitia\pendaftar.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/

if %errorlevel% neq 0 (
    color 0C
    echo GAGAL upload pendaftar.blade.php!
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
echo 1. TEST DI ADMIN:
echo    - Login sebagai Admin
echo    - Buka menu Pendaftar
echo    - Klik header kolom "Nomor Pendaftaran"
echo    - Verifikasi data ter-sort ascending
echo    - Verifikasi icon arrow UP muncul
echo    - Klik lagi untuk toggle descending
echo.
echo 2. TEST DI PANITIA:
echo    - Login sebagai Panitia
echo    - Buka menu Pendaftar
echo    - Test sorting kolom yang sama
echo.
echo 3. TEST KOLOM LAIN:
echo    - Nama Lengkap
echo    - Jenis Kelamin
echo    - Status Pendaftaran
echo    - Tanggal Daftar
echo.
color 0A
echo Expected Result:
echo   - Klik header langsung sort data
echo   - Icon arrow up/down muncul
echo   - Header highlight dengan warna hijau
echo   - Toggle asc/desc saat klik ulang
color 0F
echo.
echo Dokumentasi: ADD_SORTING_HEADER_PENDAFTAR_12_FEB_2026.md
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
echo    - Navigate ke: app/
echo    - Upload: WithSorting.php
echo    - REPLACE file yang lama
echo.
echo 4. Upload file kedua:
echo    - Navigate ke: resources/views/livewire/panitia/
echo    - Upload: pendaftar.blade.php
echo    - REPLACE file yang lama
echo.
echo 5. LANGSUNG TEST!
echo.

:end
pause
