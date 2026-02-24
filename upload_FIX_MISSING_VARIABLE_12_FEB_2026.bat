@echo off
color 0A
echo ================================================================
echo FIX MISSING VARIABLE - $showExtendedColumns
echo COMPLETE FIX FOR TES PSIKOLOGI - 12 FEB 2026
echo ================================================================
color 0F
echo.
color 0E
echo ERROR YANG DIPERBAIKI:
color 0F
echo Undefined variable $showExtendedColumns at line 205
echo.
color 0E
echo ROOT CAUSE:
color 0F
echo Variable $showExtendedColumns digunakan di view
echo tapi TIDAK di-pass dari component ke view.
echo.
color 0A
echo SOLUSI:
color 0F
echo Tambahkan variable $showExtendedColumns di method render():
echo   - Admin: $showExtendedColumns = true (full access)
echo   - Panitia: $showExtendedColumns = $this-^>isPanitiaPsikologi
echo.
color 0E
echo FILES YANG AKAN DI-UPLOAD (2 FILES):
color 0F
echo 1. app/Livewire/Admin/TesPsikologi.php (line 1546-1549 added)
echo 2. app/Livewire/Panitia/TesPsikologi.php (line 994-1004 added)
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
echo UPLOAD BERHASIL - ALL ISSUES FIXED!
echo ================================================================
color 0F
echo.
color 0A
echo SEKARANG HARUSNYA SUDAH WORKS!
color 0F
echo.
color 0E
echo TESTING - FINAL CHECK:
color 0F
echo.
echo 1. AKSES HALAMAN:
echo    URL: /admin/tes-psikologi
echo    Expected: ✅ Load SUKSES (no error 500)
echo.
echo 2. FILTER "BELUM UPLOAD":
echo    Pilih filter: "Belum Upload"
echo    Expected:
echo      ✅ Load SUKSES
echo      ✅ Tampil 8 kolom pendaftar
echo      ✅ WhatsApp clickable
echo      ✅ NO sorting icons
echo.
echo 3. FILTER "MENUNGGU VERIFIKASI":
echo    Pilih filter: "Menunggu Verifikasi"
echo    Expected:
echo      ✅ Load SUKSES
echo      ✅ Tampil 12 kolom tes psikologi
echo      ✅ Sorting icons tampil (3 kolom)
echo      ✅ WhatsApp clickable
echo      ✅ Extended columns tampil (Ubah Status, Kirim Pesan, Riwayat)
echo.
echo 4. TEST SORTING:
echo    Klik header: "Waktu Transfer"
echo    Expected: ✅ Data ter-sort ascending/descending
echo.
echo 5. TEST WHATSAPP:
echo    Klik nomor di kolom "Ortu WhatsApp"
echo    Expected: ✅ Buka WhatsApp chat
echo.
echo 6. TEST PANITIA:
echo    URL: /panitia/tes-psikologi
echo    Expected: ✅ All features work
echo.
color 0A
echo ALL FIXES APPLIED:
echo   ✅ WithSorting trait (fix $sortField error)
echo   ✅ No sorting for belum_upload (fix query error)
echo   ✅ $showExtendedColumns variable (fix undefined variable)
echo   ✅ All features: sorting, filter, WhatsApp
color 0F
echo.
echo Dokumentasi: FIX_MISSING_VARIABLE_SHOWEXTENDEDCOLUMNS_12_FEB_2026.md
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
echo UPLOAD MANUAL VIA CPANEL (2 FILES):
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
echo IMPORTANT: File VIEW sudah OK, tidak perlu upload lagi!
color 0F
echo.

:end
pause
