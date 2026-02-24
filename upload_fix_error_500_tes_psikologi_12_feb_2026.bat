@echo off
color 0C
echo ================================================================
echo CRITICAL FIX - ERROR 500 TES PSIKOLOGI - 12 FEB 2026
echo ================================================================
color 0F
echo.
color 0E
echo MASALAH:
color 0F
echo - Error 500 saat akses /admin/tes-psikologi
echo - Error 500 saat akses /panitia/tes-psikologi
echo - Error saat pilih filter "Belum Upload"
echo.
color 0C
echo ROOT CAUSE (3 ISSUES):
color 0F
echo 1. Kode kolom opsi/bank/waktu berada DI LUAR conditional
echo 2. Extended columns tampil untuk filter "belum_upload"
echo 3. Survey mengakses $data-^>pendaftar_id (tidak ada di Pendaftar)
echo.
color 0A
echo SOLUSI (3 FIXES):
color 0F
echo 1. Pindahkan kode kolom tes psikologi ke dalam blok @else
echo 2. Tambah conditional: ^&^& $filterStatus !== 'belum_upload'
echo 3. Survey pakai: $pendaftarId (conditional variable)
echo.
echo Struktur final:
echo   * Line 132-161: Kolom untuk filter "belum_upload"
echo   * Line 162-256: Kolom untuk filter lainnya + @endif
echo   * Line 257+: Kolom yang selalu tampil
echo.
echo File yang akan di-upload:
echo   1. resources/views/livewire/admin/partials/tes-psikologi-data.blade.php
echo.
color 0E
echo URGENT FIX!
color 0F
echo Halaman Tes Psikologi tidak bisa diakses sama sekali.
echo Upload segera!
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
echo 1. TEST AKSES HALAMAN:
echo    - Login sebagai Admin
echo    - Buka menu Tes Psikologi ^> Data Psikologi
echo    - Verifikasi: Halaman load tanpa error 500
echo.
echo 2. TEST FILTER "BELUM UPLOAD":
echo    - Pilih filter "Belum Upload"
echo    - Verifikasi: Halaman load normal
echo    - Verifikasi: Tampil 8 kolom (Nomor, NISN, Nama, dll)
echo.
echo 3. TEST FILTER "MENUNGGU VERIFIKASI":
echo    - Pilih filter "Menunggu Verifikasi"
echo    - Verifikasi: Halaman load normal
echo    - Verifikasi: Tampil 12 kolom (termasuk Opsi, Bank, Waktu)
echo.
echo 4. TEST DI PANITIA:
echo    - Login sebagai Panitia (panitia_tes_psikologi)
echo    - Buka menu Tes Psikologi
echo    - Test filter "Belum Upload"
echo    - Verifikasi: Tidak ada error 500
echo.
color 0A
echo Expected Result:
echo   - Halaman load tanpa error
echo   - Filter "Belum Upload" tampil 8 kolom
echo   - Filter lainnya tampil 12 kolom
echo   - Kolom WhatsApp Ortu tetap tampil
echo   - Works di Admin DAN Panitia
color 0F
echo.
echo Dokumentasi: FIX_ERROR_500_TES_PSIKOLOGI_12_FEB_2026.md
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
