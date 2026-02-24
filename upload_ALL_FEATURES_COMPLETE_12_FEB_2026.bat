@echo off
color 0A
echo ================================================================
echo UPLOAD ALL FEATURES - TES PSIKOLOGI - 12 FEB 2026
echo SORTING + FILTER BELUM UPLOAD + WHATSAPP CLICKABLE
echo ================================================================
color 0F
echo.
color 0E
echo FITUR YANG DI-RESTORE:
color 0F
echo 1. Sorting (3 kolom sortable: Waktu Transfer, Nama Pengirim, Status)
echo 2. Filter "Belum Upload" (dengan conditional 8 vs 12 kolom)
echo 3. Kolom WhatsApp Clickable (untuk semua filter, tanpa tombol edit)
echo 4. Conditional property access (fix error 500)
echo 5. All extended features
echo.
color 0E
echo FILES YANG AKAN DI-UPLOAD:
color 0F
echo 1. app/Livewire/Admin/TesPsikologi.php (WITH WithSorting trait)
echo 2. app/Livewire/Panitia/TesPsikologi.php (WITH WithSorting trait)
echo 3. resources/views/livewire/admin/partials/tes-psikologi-data.blade.php
echo.
color 0C
echo PENTING: Component HARUS di-upload PERTAMA sebelum view!
color 0F
echo Karena view menggunakan $sortField dan $sortDirection dari component.
echo.
pause

echo.
color 0E
echo [STEP 1/3] Uploading Admin/TesPsikologi.php component...
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

echo [STEP 2/3] Uploading Panitia/TesPsikologi.php component...
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

echo [STEP 3/3] Uploading tes-psikologi-data.blade.php view...
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\partials\tes-psikologi-data.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/

if %errorlevel% neq 0 (
    color 0C
    echo GAGAL upload tes-psikologi-data.blade.php!
    color 0F
    goto manual_upload
)
color 0A
echo SUCCESS: tes-psikologi-data.blade.php uploaded!
color 0F

echo.
color 0A
echo ================================================================
echo UPLOAD BERHASIL - SEMUA FILE UPLOADED!
echo ================================================================
color 0F
echo.
echo SEGERA TEST SEKARANG!
echo.
color 0E
echo TESTING CHECKLIST - PRIORITY TINGGI:
color 0F
echo.
echo 1. TEST AKSES HALAMAN:
echo    URL: /admin/tes-psikologi
echo    Expected: Load tanpa error 500
echo.
echo 2. TEST FILTER DROPDOWN:
echo    Check options: Semua, Belum Upload, Menunggu, Disetujui, Ditolak, Perbaiki
echo    Pilih: "Belum Upload"
echo    Expected: Tampil 8 kolom (Pendaftar)
echo.
echo 3. TEST FILTER LAINNYA:
echo    Pilih: "Menunggu Verifikasi"
echo    Expected: Tampil 12 kolom (Tes Psikologi)
echo.
echo 4. TEST SORTING (filter selain "Belum Upload"):
echo    Klik header: "Waktu Transfer"
echo    Expected: Data ter-sort ascending/descending
echo.
echo 5. TEST WHATSAPP CLICKABLE:
echo    Klik nomor di kolom "Ortu WhatsApp"
echo    Expected: Buka WhatsApp chat
echo    Test di filter "Belum Upload" DAN filter lainnya
echo.
echo 6. TEST PANITIA ACCESS:
echo    URL: /panitia/tes-psikologi
echo    Expected: Load normal, all features work
echo.
echo 7. TEST EXTENDED COLUMNS (filter selain "Belum Upload"):
echo    Check columns: Ubah Status, Kirim Pesan, Riwayat
echo    Expected: All buttons work
echo.
echo 8. TEST SURVEY MODAL:
echo    Klik "Lihat" di kolom Status Survey
echo    Expected: Modal muncul
echo.
color 0A
echo EXPECTED RESULT:
echo - No error 500
echo - Filter "Belum Upload" works (8 kolom)
echo - Filter lainnya works (12 kolom + sorting)
echo - WhatsApp clickable works (semua filter)
echo - Sorting works (3 kolom sortable)
echo - All features intact
color 0F
echo.
echo Dokumentasi: ALL_FEATURES_COMPLETE_TES_PSIKOLOGI_12_FEB_2026.md
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
echo UPLOAD MANUAL VIA CPANEL (3 FILES):
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
echo FILE 3: View File
echo   Local: C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\partials\tes-psikologi-data.blade.php
echo   Production: resources/views/livewire/admin/partials/
echo   REPLACE file lama
echo.
color 0C
echo CRITICAL: Upload FILE 1 dan FILE 2 DULU sebelum FILE 3!
color 0F
echo.

:end
pause
