@echo off
color 0A
echo ================================================================
echo UPLOAD FILTER VERIFIKASI PSIKOLOGI - Admin Pendaftar
echo 12 FEBRUARI 2026
echo ================================================================
color 0F
echo.
color 0E
echo FITUR BARU:
color 0F
echo   - Tambah filter dropdown "Verif Psikologi" di /admin/pendaftar
echo   - 6 opsi filter: Semua, Belum Upload, Menunggu, Disetujui, Ditolak, Perbaiki
echo   - Query dengan whereHas dan whereDoesntHave
echo   - UI grid 5 kolom (dari 4 kolom)
echo.
color 0E
echo FILES YANG AKAN DI-UPLOAD (2 FILES):
color 0F
echo   1. app/Livewire/Admin/Pendaftar.php (backend component)
echo   2. resources/views/livewire/admin/pendaftar.blade.php (frontend view)
echo.
pause

echo.
color 0E
echo [STEP 1/2] Uploading Admin Pendaftar Component...
color 0F
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Admin\Pendaftar.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/

if %errorlevel% neq 0 (
    color 0C
    echo GAGAL upload Pendaftar.php!
    color 0F
    goto manual_upload
)
color 0A
echo SUCCESS: Pendaftar.php uploaded!
color 0F
echo.

echo [STEP 2/2] Uploading Admin Pendaftar View...
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\pendaftar.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/

if %errorlevel% neq 0 (
    color 0C
    echo GAGAL upload pendaftar.blade.php!
    color 0F
    goto manual_upload
)
color 0A
echo SUCCESS: pendaftar.blade.php uploaded!
color 0F

echo.
color 0A
echo ================================================================
echo UPLOAD BERHASIL - FILTER VERIF PSIKOLOGI READY!
echo ================================================================
color 0F
echo.
color 0A
echo FITUR SUDAH AKTIF DI PRODUCTION!
color 0F
echo.
color 0E
echo TESTING CHECKLIST:
color 0F
echo.
echo 1. LOAD HALAMAN:
echo    URL: /admin/pendaftar
echo    Expected: ^Load sukses (no error)
echo.
echo 2. FILTER GRID:
echo    Expected: ^Ada 5 filter dalam 1 row
echo    - Kelengkapan EMIS
echo    - Jalur Pendaftaran
echo    - Status
echo    - Verif Psikologi (NEW!)
echo    - Per Halaman
echo.
echo 3. DROPDOWN OPTIONS:
echo    Pilih "Verif Psikologi"
echo    Expected: ^6 opsi
echo    - Semua Status
echo    - Belum Upload
echo    - Menunggu Verifikasi
echo    - Disetujui
echo    - Ditolak
echo    - Perlu Perbaikan
echo.
echo 4. TEST FILTER:
echo    Pilih "Belum Upload"
echo    Expected: ^Tampilkan pendaftar yang belum upload psikologi
echo.
echo 5. KOMBINASI FILTER:
echo    Pilih Jalur + Verif Psikologi
echo    Expected: ^Keduanya bekerja bersamaan
echo.
color 0A
echo CATATAN:
echo   - Tidak perlu clear cache
echo   - Langsung test setelah upload
echo   - Filter kombinasi dengan filter lain works
color 0F
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
echo FILE 1: Admin Pendaftar Component
echo   Local: C:\ppdbmtsn2km-laravel\app\Livewire\Admin\Pendaftar.php
echo   Production: app/Livewire/Admin/Pendaftar.php
echo   Fitur: Filter logic + property + queryString
echo.
echo FILE 2: Admin Pendaftar View
echo   Local: C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\pendaftar.blade.php
echo   Production: resources/views/livewire/admin/pendaftar.blade.php
echo   Fitur: Dropdown filter baru + grid 5 kolom
echo.
color 0A
echo PENTING:
color 0F
echo   - Upload 2 files untuk fitur lengkap
echo   - Tidak perlu clear cache
echo   - Test setelah upload
echo.

:end
pause
