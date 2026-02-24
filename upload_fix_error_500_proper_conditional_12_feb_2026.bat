@echo off
color 0A
echo ================================================================
echo FIX ERROR 500 TES PSIKOLOGI - PROPER CONDITIONAL
echo KEEP FILTER "BELUM UPLOAD" + FIX ALL PROPERTY ACCESS
echo ================================================================
color 0F
echo.
color 0E
echo USER REQUEST:
color 0F
echo Filter "Belum Upload" TETAP ADA dan berfungsi dengan benar!
echo Kolom "Ortu WhatsApp" tiru dari /admin/pendaftar (tanpa tombol edit)
echo.
color 0C
echo MASALAH SEBELUMNYA:
color 0F
echo - Error 500 saat akses /admin/tes-psikologi
echo - Error 500 saat akses /panitia/tes-psikologi
echo - Error 500 saat pilih filter "Belum Upload"
echo.
color 0A
echo SOLUSI PROPER:
color 0F
echo 1. KEEP filter "Belum Upload" di dropdown
echo 2. Conditional header (8 kolom vs 12 kolom)
echo 3. Conditional body ($data vs $data-^>pendaftar)
echo 4. Conditional survey (pendaftar_id)
echo 5. Conditional WhatsApp (telepon_ortu)
echo 6. Conditional extended columns (hide untuk "Belum Upload")
echo.
echo Struktur final:
echo   * Filter "Belum Upload": 8 kolom + Survey + WhatsApp
echo   * Filter lainnya: 12 kolom + Survey + WhatsApp + Extended
echo.
echo File yang akan di-upload:
echo   1. resources/views/livewire/admin/partials/tes-psikologi-data.blade.php
echo.
color 0E
echo CRITICAL FIX!
color 0F
echo Upload segera untuk restore functionality!
echo.
pause

echo.
color 0E
echo Uploading fix ke production...
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
echo    - URL: /admin/tes-psikologi
echo    - Verifikasi: Halaman load tanpa error 500
echo.
echo 2. TEST FILTER "BELUM UPLOAD":
echo    - Pilih filter "Belum Upload"
echo    - Verifikasi: Load normal
echo    - Verifikasi: Tampil 8 kolom pendaftar
echo    - Verifikasi: Kolom WhatsApp clickable
echo    - Verifikasi: TIDAK ada kolom extended (Ubah Status, dll)
echo.
echo 3. TEST FILTER "SEMUA STATUS":
echo    - Pilih filter "Semua Status"
echo    - Verifikasi: Load normal
echo    - Verifikasi: Tampil 12 kolom tes psikologi
echo    - Verifikasi: Extended columns tampil
echo.
echo 4. TEST FILTER "MENUNGGU VERIFIKASI":
echo    - Pilih filter "Menunggu Verifikasi"
echo    - Verifikasi: Load normal
echo    - Verifikasi: Tampil data dengan status menunggu
echo.
echo 5. TEST WHATSAPP CLICKABLE:
echo    - Klik nomor di kolom "Ortu WhatsApp"
echo    - Verifikasi: Buka WhatsApp chat
echo    - Test di filter "Belum Upload" DAN filter lainnya
echo.
echo 6. TEST SORTING (filter selain "Belum Upload"):
echo    - Klik header "Waktu Transfer"
echo    - Verifikasi: Data ter-sort
echo.
echo 7. TEST SURVEY MODAL:
echo    - Klik "Lihat" di kolom Status Survey
echo    - Verifikasi: Modal muncul dengan jawaban
echo.
echo 8. TEST DI PANITIA:
echo    - Login sebagai Panitia (panitia_tes_psikologi)
echo    - Buka menu Tes Psikologi
echo    - Test semua filter
echo    - Verifikasi: Tidak ada error 500
echo.
color 0A
echo Expected Result:
echo   - Halaman load tanpa error 500
echo   - Filter "Belum Upload" berfungsi normal
echo   - Filter lainnya berfungsi normal
echo   - WhatsApp clickable works di semua filter
echo   - Sorting works
echo   - Survey modal works
echo   - Extended columns works (except untuk "Belum Upload")
color 0F
echo.
echo Dokumentasi: FIX_ERROR_500_TES_PSIKOLOGI_PROPER_CONDITIONAL_12_FEB_2026.md
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
