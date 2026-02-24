@echo off
color 0C
echo ================================================================
echo CRITICAL FIX - ERROR 500 TES PSIKOLOGI - 12 FEB 2026
echo REMOVE "BELUM UPLOAD" FILTER
echo ================================================================
color 0F
echo.
color 0E
echo MASALAH:
color 0F
echo - Error 500 saat akses /admin/tes-psikologi
echo - Error 500 saat akses /panitia/tes-psikologi
echo - Halaman tidak bisa diakses sama sekali
echo.
color 0C
echo ROOT CAUSE:
color 0F
echo Filter "Belum Upload" menyebabkan view switch antara 2 model:
echo   - Pendaftar model (untuk filter belum_upload)
echo   - TesPsikologiPendaftar model (untuk filter lainnya)
echo.
echo Property mismatch menyebabkan error 500!
echo.
color 0A
echo SOLUSI - REMOVE FILTER ENTIRELY:
color 0F
echo 1. Hapus option "Belum Upload" dari dropdown filter
echo 2. Simplify header structure (no conditional)
echo 3. Simplify tbody structure (no conditional)
echo 4. Simplify survey column (direct property access)
echo 5. Simplify extended columns conditional
echo.
echo Struktur final:
echo   * Single static header (12 columns)
echo   * Single tbody structure (TesPsikologiPendaftar model)
echo   * Direct property access ($data->pendaftar_id)
echo   * Simple conditional (@if showExtendedColumns)
echo.
echo File yang akan di-upload:
echo   1. resources/views/livewire/admin/partials/tes-psikologi-data.blade.php
echo.
color 0E
echo URGENT FIX!
color 0F
echo Halaman Tes Psikologi tidak bisa diakses sama sekali.
echo Upload SEGERA!
echo.
pause

echo.
color 0E
echo Uploading critical fix ke production...
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
echo TESTING STEPS - SEGERA TEST!
color 0F
echo.
echo 1. TEST AKSES HALAMAN ADMIN:
echo    - Login sebagai Admin
echo    - Buka menu Tes Psikologi ^> Data Psikologi
echo    - Verifikasi: Halaman load tanpa error 500
echo.
echo 2. TEST AKSES HALAMAN PANITIA:
echo    - Login sebagai Panitia (panitia_tes_psikologi)
echo    - Buka menu Tes Psikologi
echo    - Verifikasi: Halaman load tanpa error 500
echo.
echo 3. TEST FILTER "SEMUA STATUS":
echo    - Filter: "Semua Status"
echo    - Verifikasi: Tampil semua data
echo    - Verifikasi: 12 kolom + extended columns
echo.
echo 4. TEST FILTER "MENUNGGU VERIFIKASI":
echo    - Filter: "Menunggu Verifikasi"
echo    - Verifikasi: Tampil data dengan status menunggu
echo    - Verifikasi: Semua kolom tampil normal
echo.
echo 5. TEST FILTER LAINNYA:
echo    - Test filter "Disetujui"
echo    - Test filter "Ditolak"
echo    - Test filter "Perlu Perbaikan"
echo    - Verifikasi: Semua filter berfungsi normal
echo.
echo 6. TEST WHATSAPP CLICKABLE:
echo    - Klik nomor di kolom "Ortu WhatsApp"
echo    - Verifikasi: Buka WhatsApp chat
echo.
echo 7. TEST SORTING:
echo    - Klik header "Waktu Transfer"
echo    - Verifikasi: Data ter-sort
echo.
color 0A
echo Expected Result:
echo   - Halaman load tanpa error 500
echo   - Filter dropdown ada 5 options (tanpa "Belum Upload")
echo   - Semua filter berfungsi normal
echo   - WhatsApp clickable works
echo   - Sorting works
echo   - Survey modal works
color 0F
echo.
echo Dokumentasi: FIX_ERROR_500_TES_PSIKOLOGI_REMOVE_BELUM_UPLOAD_FILTER_12_FEB_2026.md
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
echo UPLOAD MANUAL VIA CPANEL - URGENT!
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
echo 6. LANGSUNG TEST!
echo    Tidak perlu clear cache karena hanya view.
echo.

:end
pause
