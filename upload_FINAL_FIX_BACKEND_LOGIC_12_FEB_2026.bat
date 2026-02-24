@echo off
color 0C
echo ================================================================
echo FINAL FIX - BACKEND LOGIC ERROR - TES PSIKOLOGI
echo FIX SORTING ISSUE UNTUK FILTER BELUM UPLOAD
echo ================================================================
color 0F
echo.
color 0E
echo ROOT CAUSE DITEMUKAN:
color 0F
echo Filter "belum_upload" menggunakan model Pendaftar
echo Tapi method applySorting() mencoba sort by kolom:
echo   - waktu_transfer (TIDAK ADA di Pendaftar)
echo   - nama_pengirim (TIDAK ADA di Pendaftar)
echo   - status_verifikasi (TIDAK ADA di Pendaftar)
echo.
echo Result: ERROR karena kolom tidak exist!
echo.
color 0A
echo SOLUSI:
color 0F
echo Untuk filter "belum_upload", TIDAK apply sorting.
echo Hanya order by created_at DESC (pendaftar terbaru dulu).
echo.
color 0E
echo FILES YANG AKAN DI-UPLOAD (2 FILES BACKEND):
color 0F
echo 1. app/Livewire/Admin/TesPsikologi.php (line 1318-1320 fixed)
echo 2. app/Livewire/Panitia/TesPsikologi.php (line 804-806 fixed)
echo.
color 0C
echo CRITICAL: File VIEW TIDAK PERLU DI-UPLOAD LAGI!
color 0F
echo View sudah benar, hanya backend logic yang salah.
echo.
pause

echo.
color 0E
echo [STEP 1/2] Uploading Admin/TesPsikologi.php...
color 0F
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Admin\TesPsikologi.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/

if %errorlevel% neq 0 (
    color 0C
    echo GAGAL upload Admin/TesPsikologi.php!
    color 0F
    goto manual_upload
)
color 0A
echo SUCCESS: Admin/TesPsikologi.php uploaded!
color 0F
echo.

echo [STEP 2/2] Uploading Panitia/TesPsikologi.php...
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Panitia\TesPsikologi.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/

if %errorlevel% neq 0 (
    color 0C
    echo GAGAL upload Panitia/TesPsikologi.php!
    color 0F
    goto manual_upload
)
color 0A
echo SUCCESS: Panitia/TesPsikologi.php uploaded!
color 0F

echo.
color 0A
echo ================================================================
echo UPLOAD BERHASIL - BACKEND LOGIC FIXED!
echo ================================================================
color 0F
echo.
echo SEGERA TEST SEKARANG!
echo.
color 0E
echo TESTING PRIORITY - FILTER "BELUM UPLOAD":
color 0F
echo.
echo 1. TEST AKSES HALAMAN:
echo    URL: /admin/tes-psikologi
echo    Expected: Load tanpa error
echo.
echo 2. TEST FILTER "BELUM UPLOAD":
echo    Pilih filter: "Belum Upload"
echo    Expected:
echo      - Load SUKSES (no error)
echo      - Tampil 8 kolom dari Pendaftar
echo      - Data ter-sort by created_at DESC (terbaru dulu)
echo      - WhatsApp clickable
echo.
echo 3. TEST FILTER "MENUNGGU VERIFIKASI":
echo    Pilih filter: "Menunggu Verifikasi"
echo    Expected:
echo      - Load SUKSES
echo      - Tampil 12 kolom dari Tes Psikologi
echo      - Sorting icons tampil di 3 kolom
echo      - Klik header "Waktu Transfer" - data ter-sort
echo.
echo 4. TEST WHATSAPP:
echo    Klik nomor di kolom "Ortu WhatsApp"
echo    Test di filter "Belum Upload" DAN filter lain
echo    Expected: Buka WhatsApp chat
echo.
echo 5. TEST PANITIA:
echo    URL: /panitia/tes-psikologi
echo    Test all filters
echo    Expected: All work normal
echo.
color 0A
echo EXPECTED RESULT:
echo - No error 500
echo - Filter "Belum Upload" works (tanpa sorting icons)
echo - Filter lain works (dengan sorting icons)
echo - WhatsApp clickable works (semua filter)
echo - All features intact
color 0F
echo.
echo Dokumentasi: FINAL_FIX_BACKEND_LOGIC_12_FEB_2026.md
echo.
goto end

:manual_upload
color 0C
echo.
echo ================================================================
echo UPLOAD GAGAL - SSH CONNECTION REFUSED
echo ================================================================
color 0F
echo.
color 0E
echo UPLOAD MANUAL VIA CPANEL (2 FILES BACKEND):
color 0F
echo.
echo FILE 1: Admin TesPsikologi Component
echo   Local: C:\ppdbmtsn2km-laravel\app\Livewire\Admin\TesPsikologi.php
echo   Production: app/Livewire/Admin/
echo   REPLACE file lama
echo.
echo FILE 2: Panitia TesPsikologi Component
echo   Local: C:\ppdbmtsn2km-laravel\app\Livewire\Panitia\TesPsikologi.php
echo   Production: app/Livewire/Panitia/
echo   REPLACE file lama
echo.
color 0C
echo PENTING: File VIEW sudah benar, TIDAK perlu upload lagi!
color 0F
echo.

:end
pause
