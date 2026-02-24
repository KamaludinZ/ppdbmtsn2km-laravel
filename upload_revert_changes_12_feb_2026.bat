@echo off
color 0E
echo ================================================================
echo REVERT CHANGES - TES PSIKOLOGI - 12 FEB 2026
echo NON-AKTIFKAN WHATSAPP COLUMN DAN FILTER BELUM UPLOAD
echo ================================================================
color 0F
echo.
color 0E
echo PERUBAHAN YANG DI-NON-AKTIFKAN:
color 0F
echo 1. Kolom "Ortu WhatsApp" di menu Tes Psikologi
echo 2. Filter "Belum Upload" di dropdown
echo.
color 0A
echo PERUBAHAN YANG TETAP ADA:
color 0F
echo 1. Sorting (Waktu Transfer, Nama Pengirim, Status Psikologi)
echo 2. Filter status (5 options)
echo 3. Search
echo 4. Survey modal
echo 5. Extended columns (Ubah Status, Kirim Pesan, Riwayat)
echo 6. All core features
echo.
echo File yang akan di-upload:
echo   1. resources/views/livewire/admin/partials/tes-psikologi-data.blade.php
echo.
echo Struktur final:
echo   * 12 kolom tes psikologi (NO WhatsApp)
echo   * 5 filter options (NO "Belum Upload")
echo   * Sorting works
echo   * All core features intact
echo.
pause

echo.
color 0E
echo Uploading revert changes ke production...
color 0F
echo.

echo [1/1] Uploading tes-psikologi-data.blade.php...
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
echo File berhasil di-upload ke production.
echo.
color 0E
echo TESTING STEPS:
color 0F
echo.
echo 1. TEST AKSES HALAMAN:
echo    - Login sebagai Admin
echo    - Buka menu Tes Psikologi ^> Data Psikologi
echo    - URL: /admin/tes-psikologi
echo    - Verifikasi: Halaman load normal
echo.
echo 2. TEST FILTER DROPDOWN:
echo    - Check options available
echo    - Verifikasi: 5 options (NO "Belum Upload")
echo      * Semua Status
echo      * Menunggu Verifikasi
echo      * Disetujui
echo      * Ditolak
echo      * Perlu Perbaikan
echo.
echo 3. TEST TABLE COLUMNS:
echo    - Check header columns
echo    - Verifikasi: 12 kolom (NO "Ortu WhatsApp")
echo    - Verifikasi: Sorting icons ada di 3 kolom:
echo      * Waktu Transfer
echo      * Nama Pengirim
echo      * Status Psikologi
echo.
echo 4. TEST SORTING:
echo    - Klik header "Waktu Transfer"
echo    - Verifikasi: Data ter-sort
echo    - Klik lagi untuk reverse
echo.
echo 5. TEST FILTER "MENUNGGU":
echo    - Pilih filter "Menunggu Verifikasi"
echo    - Verifikasi: Tampil data dengan status menunggu
echo.
echo 6. TEST SURVEY MODAL:
echo    - Klik "Lihat" di kolom Status Survey (if active)
echo    - Verifikasi: Modal muncul
echo.
echo 7. TEST PANITIA ACCESS:
echo    - Login sebagai Panitia (panitia_tes_psikologi)
echo    - Buka menu Tes Psikologi
echo    - Verifikasi: Load normal, shared view
echo.
color 0A
echo Expected Result:
echo   - Halaman load normal
echo   - Filter dropdown: 5 options (NO "Belum Upload")
echo   - Table: 12 kolom (NO "Ortu WhatsApp")
echo   - Sorting works (3 kolom sortable)
echo   - All filters work
echo   - Survey modal works
echo   - Extended columns works
color 0F
echo.
color 0E
echo NOTE:
color 0F
echo Kolom "Ortu WhatsApp" yang clickable MASIH ADA di:
echo   - /admin/pendaftar
echo   - /panitia/pendaftar
echo Jadi fitur WhatsApp clickable tidak hilang!
echo.
echo Dokumentasi: REVERT_CHANGES_TES_PSIKOLOGI_12_FEB_2026.md
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
echo 3. Navigate ke:
echo    resources/views/livewire/admin/partials/
echo.
echo 4. Upload file:
echo    tes-psikologi-data.blade.php
echo.
echo 5. REPLACE file yang lama
echo.
echo 6. TEST sesuai steps di atas
echo.

:end
pause
