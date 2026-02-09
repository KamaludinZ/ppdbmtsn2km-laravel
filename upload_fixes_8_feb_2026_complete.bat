@echo off
REM ================================================================
REM Upload Script - Complete Fixes 8 Feb 2026
REM ================================================================
REM
REM This script uploads all fixes made on 8 Feb 2026:
REM 1. URL Tab Support di Panitia & PanitiaSeragam DaftarUlang
REM 2. Stats Overview Fix (Admin pattern)
REM 3. Rich Text Editor (RTE) implementation (10 fields)
REM 4. Paket Seragam Display Fix (JSON parsing)
REM 5. Prose classes for RTE display in Pendaftar view
REM
REM Total files: 11
REM ================================================================

echo ================================================================
echo   Upload Fixes - 8 Feb 2026
echo   URL Tabs + Stats + RTE + Paket Seragam Display
echo ================================================================
echo.

REM Set production server details
set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo [1/10] Uploading Panitia DaftarUlang (URL + Stats)...
scp "app\Livewire\Panitia\DaftarUlang.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Panitia/
if errorlevel 1 (
    echo ERROR: Failed to upload Panitia DaftarUlang.php
    pause
    exit /b 1
)

echo [2/10] Uploading PanitiaSeragam DaftarUlang (URL + Stats)...
scp "app\Livewire\PanitiaSeragam\DaftarUlang.php" %SERVER%:%REMOTE_PATH%/app/Livewire/PanitiaSeragam/
if errorlevel 1 (
    echo ERROR: Failed to upload PanitiaSeragam DaftarUlang.php
    pause
    exit /b 1
)

echo [3/10] Uploading Admin DaftarUlang blade (3 RTE fields)...
scp "resources\views\livewire\admin\daftar-ulang.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/admin/
if errorlevel 1 (
    echo ERROR: Failed to upload admin daftar-ulang.blade.php
    pause
    exit /b 1
)

echo [4/10] Uploading Panitia Settings Madrasah Tab (1 RTE field)...
scp "resources\views\livewire\panitia\partials\settings-madrasah-tab.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/panitia/partials/
if errorlevel 1 (
    echo ERROR: Failed to upload settings-madrasah-tab.blade.php
    pause
    exit /b 1
)

echo [5/10] Uploading PanitiaSeragam Settings Tab (2 RTE fields)...
scp "resources\views\livewire\panitia-seragam\partials\settings-tab.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/panitia-seragam/partials/
if errorlevel 1 (
    echo ERROR: Failed to upload panitia-seragam settings-tab.blade.php
    pause
    exit /b 1
)

echo [6/10] Uploading Admin Tes Psikologi Settings (2 RTE fields)...
scp "resources\views\livewire\admin\partials\tes-psikologi-settings.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/admin/partials/
if errorlevel 1 (
    echo ERROR: Failed to upload tes-psikologi-settings.blade.php
    pause
    exit /b 1
)

echo [7/10] Uploading Admin Tes Psikologi Survey (1 RTE field)...
scp "resources\views\livewire\admin\partials\tes-psikologi-survey.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/admin/partials/
if errorlevel 1 (
    echo ERROR: Failed to upload admin tes-psikologi-survey.blade.php
    pause
    exit /b 1
)

echo [8/10] Uploading Panitia Tes Psikologi Survey (1 RTE field)...
scp "resources\views\livewire\panitia\partials\tes-psikologi-survey.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/panitia/partials/
if errorlevel 1 (
    echo ERROR: Failed to upload panitia tes-psikologi-survey.blade.php
    pause
    exit /b 1
)

echo [9/10] Uploading Admin DetailPendaftar blade (Paket Seragam display)...
scp "resources\views\livewire\admin\detail-pendaftar.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/admin/
if errorlevel 1 (
    echo ERROR: Failed to upload admin detail-pendaftar.blade.php
    pause
    exit /b 1
)

echo [10/11] Uploading Panitia DetailPendaftar blade (Paket Seragam display)...
scp "resources\views\livewire\panitia\detail-pendaftar.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/panitia/
if errorlevel 1 (
    echo ERROR: Failed to upload panitia detail-pendaftar.blade.php
    pause
    exit /b 1
)

echo [11/11] Uploading Pendaftar DaftarUlang blade (RTE prose classes)...
scp "resources\views\livewire\pendaftar\daftar-ulang.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/pendaftar/
if errorlevel 1 (
    echo ERROR: Failed to upload pendaftar daftar-ulang.blade.php
    pause
    exit /b 1
)

echo.
echo ================================================================
echo   Clearing Production Cache...
echo ================================================================
echo.

echo [12/13] Clearing Livewire cache...
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan livewire:delete-tmp"
if errorlevel 1 (
    echo WARNING: Failed to clear Livewire cache
)

echo [13/13] Clearing application cache...
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan optimize:clear"
if errorlevel 1 (
    echo WARNING: Failed to clear application cache
)

echo.
echo ================================================================
echo   Upload Complete!
echo ================================================================
echo.
echo Semua file berhasil diupload:
echo   - Panitia DaftarUlang (URL + Stats)
echo   - PanitiaSeragam DaftarUlang (URL + Stats)
echo   - Admin DaftarUlang blade (3 RTE)
echo   - Panitia Settings Madrasah (1 RTE)
echo   - PanitiaSeragam Settings (2 RTE)
echo   - Admin Tes Psikologi Settings (2 RTE)
echo   - Admin Tes Psikologi Survey (1 RTE)
echo   - Panitia Tes Psikologi Survey (1 RTE)
echo   - Admin DetailPendaftar blade (Paket Seragam display)
echo   - Panitia DetailPendaftar blade (Paket Seragam display)
echo   - Pendaftar DaftarUlang blade (RTE prose classes)
echo.
echo Total: 11 files uploaded + 2 cache clear commands
echo.
echo TESTING CHECKLIST:
echo.
echo [URL Tabs]
echo   1. Test /panitia/daftar-ulang dengan tab switching
echo   2. Copy URL dan buka di tab baru
echo   3. Test /panitia-seragam/daftar-ulang dengan tab switching
echo.
echo [Stats Overview]
echo   1. Login sebagai Panitia
echo   2. Check stats di /panitia/daftar-ulang
echo   3. Apply filter, verify stats TIDAK berubah
echo   4. Compare dengan Admin stats
echo.
echo [Rich Text Editor]
echo   1. Test di /admin/daftar-ulang (3 tabs settings)
echo   2. Test di /panitia/daftar-ulang settings madrasah
echo   3. Test di /panitia-seragam/daftar-ulang settings (2 tabs)
echo   4. Test di /admin/tes-psikologi tab Settings
echo   5. Test di /admin/tes-psikologi tab Survey
echo   6. Test di /panitia/tes-psikologi tab Survey
echo.
echo Verify RTE toolbar works:
echo   - Bold, Italic, Underline
echo   - Links
echo   - Bulleted/Numbered lists
echo   - Headings
echo   - Formatting saved correctly
echo.
echo [Paket Seragam Display]
echo   1. Login sebagai Admin
echo   2. Buka /admin/pendaftar/[ID]?activeTab=daftarulang
echo   3. Scroll ke bagian "Daftar Ulang Seragam"
echo   4. Verify "Paket Seragam" displayed as formatted card:
echo      - Nama Paket (e.g. PAKET LENGKAP)
echo      - Deskripsi (e.g. SERAGAM+ATRIBUT+BUKU)
echo      - Opsi Paket dengan nama dan harga formatted
echo   5. Test juga di /panitia/pendaftar/[ID]?activeTab=daftarulang
echo   6. Verify NO raw JSON displayed
echo.
echo [RTE Display in Pendaftar View]
echo   1. Login sebagai Pendaftar
echo   2. Buka /pendaftar/daftarulang
echo   3. Verify informasi boxes displayed properly:
echo      - Madrasah Info box (indigo) - prose formatting
echo      - Seragam Info box (cyan) - prose formatting
echo      - Ukuran Info box (purple) - prose formatting
echo   4. Check formatting:
echo      - Bold text displayed correctly
echo      - Italic text displayed correctly
echo      - Links clickable
echo      - Lists formatted properly
echo      - Headings displayed with proper sizing
echo   5. Verify NO raw HTML tags visible
echo   6. Verify text is readable and properly styled
echo.
echo ================================================================
pause
