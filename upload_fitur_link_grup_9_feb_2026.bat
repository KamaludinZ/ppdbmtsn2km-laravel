@echo off
REM ================================================================
REM Upload Script - Fitur Link Grup WhatsApp/Telegram
REM ================================================================
REM
REM Fitur ini menambahkan link grup yang dapat diatur Admin
REM dan ditampilkan sebagai tombol di dashboard & kartu tes pendaftar
REM
REM Total files: 5 (3 backend + 3 frontend)
REM ================================================================

echo ================================================================
echo   Upload Fitur Link Grup WhatsApp/Telegram
echo   Date: 9 Feb 2026
echo ================================================================
echo.

REM Set production server details
set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo ================================================================
echo   PART 1: Upload Backend Files (3 files)
echo ================================================================
echo.

echo [1/5] Uploading Admin PengaturanKartu Component...
echo File: app\Livewire\Admin\PengaturanKartu.php
echo Changes: Add link grup properties, mount(), and saveLinkGrup()
scp "app\Livewire\Admin\PengaturanKartu.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Admin/
if errorlevel 1 (
    echo ERROR: Failed to upload PengaturanKartu.php
    pause
    exit /b 1
)
echo SUCCESS
echo.

echo [2/5] Uploading Pendaftar Dashboard Component...
echo File: app\Livewire\Pendaftar\Dashboard.php
echo Changes: Add link grup properties and load settings in mount()
scp "app\Livewire\Pendaftar\Dashboard.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Pendaftar/
if errorlevel 1 (
    echo ERROR: Failed to upload Dashboard.php
    pause
    exit /b 1
)
echo SUCCESS
echo.

echo [3/5] Uploading Pendaftar KartuTesSeleksi Component...
echo File: app\Livewire\Pendaftar\KartuTesSeleksi.php
echo Changes: Add link grup properties and load settings in mount()
scp "app\Livewire\Pendaftar\KartuTesSeleksi.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Pendaftar/
if errorlevel 1 (
    echo ERROR: Failed to upload KartuTesSeleksi.php
    pause
    exit /b 1
)
echo SUCCESS
echo.

echo ================================================================
echo   PART 2: Upload Frontend Files (3 files)
echo ================================================================
echo.

echo [4/5] Uploading Admin Pengaturan Kartu Blade View...
echo File: resources\views\livewire\admin\pengaturan-kartu.blade.php
echo Changes: Add "Link Grup" tab with toggle, URL input, preview
timeout /t 2 /nobreak >nul
scp "resources\views\livewire\admin\pengaturan-kartu.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/admin/
if errorlevel 1 (
    echo ERROR: Failed to upload pengaturan-kartu.blade.php
    pause
    exit /b 1
)
echo SUCCESS
echo.

echo [5/5] Uploading Pendaftar Dashboard Blade View...
echo File: resources\views\livewire\pendaftar\dashboard.blade.php
echo Changes: Add "Bergabung ke Grup" button next to "Cetak Kartu" button
timeout /t 2 /nobreak >nul
scp "resources\views\livewire\pendaftar\dashboard.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/pendaftar/
if errorlevel 1 (
    echo ERROR: Failed to upload dashboard.blade.php
    pause
    exit /b 1
)
echo SUCCESS
echo.

echo [6/5] Uploading Pendaftar Kartu Tes Seleksi Blade View...
echo File: resources\views\livewire\pendaftar\kartu-tes-seleksi.blade.php
echo Changes: Add "Bergabung ke Grup" button next to "Cetak Kartu" button
timeout /t 2 /nobreak >nul
scp "resources\views\livewire\pendaftar\kartu-tes-seleksi.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/livewire/pendaftar/
if errorlevel 1 (
    echo ERROR: Failed to upload kartu-tes-seleksi.blade.php
    pause
    exit /b 1
)
echo SUCCESS
echo.

echo ================================================================
echo   Clearing Production Cache (CRITICAL!)
echo ================================================================
echo.

echo [7/11] Clearing view cache...
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan view:clear"
if errorlevel 1 (
    echo WARNING: Failed to clear view cache
) else (
    echo SUCCESS
)
echo.

echo [8/11] Clearing Livewire cache...
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan livewire:delete-tmp"
if errorlevel 1 (
    echo WARNING: Failed to clear Livewire cache
) else (
    echo SUCCESS
)
echo.

echo [9/11] Clearing application cache...
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan cache:clear"
if errorlevel 1 (
    echo WARNING: Failed to clear application cache
) else (
    echo SUCCESS
)
echo.

echo [10/11] Clearing config cache...
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan config:clear"
if errorlevel 1 (
    echo WARNING: Failed to clear config cache
) else (
    echo SUCCESS
)
echo.

echo [11/11] Optimizing application...
timeout /t 2 /nobreak >nul
ssh %SERVER% "cd %REMOTE_PATH% && php artisan optimize:clear"
if errorlevel 1 (
    echo WARNING: Failed to optimize
) else (
    echo SUCCESS
)
echo.

echo ================================================================
echo   Upload Complete!
echo ================================================================
echo.
echo Files uploaded (6 files):
echo.
echo [BACKEND - 3 files]
echo   1. app/Livewire/Admin/PengaturanKartu.php
echo   2. app/Livewire/Pendaftar/Dashboard.php
echo   3. app/Livewire/Pendaftar/KartuTesSeleksi.php
echo.
echo [FRONTEND - 3 files]
echo   4. resources/views/livewire/admin/pengaturan-kartu.blade.php
echo   5. resources/views/livewire/pendaftar/dashboard.blade.php
echo   6. resources/views/livewire/pendaftar/kartu-tes-seleksi.blade.php
echo.
echo Cache cleared:
echo   - View cache
echo   - Livewire cache
echo   - Application cache
echo   - Config cache
echo   - Optimized
echo.
echo ================================================================
echo   TESTING CHECKLIST
echo ================================================================
echo.
echo [TEST 1: Configure Link Grup]
echo   1. Login sebagai Admin
echo   2. Buka /admin/pengaturan-kartu?activeTab=link-grup
echo   3. Verify tab "Link Grup" tampil
echo   4. Toggle switch ON
echo   5. Input URL: https://chat.whatsapp.com/ABC123
echo   6. Klik "Simpan Link Grup"
echo   7. Verify success message
echo   8. Refresh page - verify data persist
echo   9. Verify preview link tampil
echo.
echo [TEST 2: Button di Dashboard Pendaftar]
echo   1. Logout dari Admin
echo   2. Login sebagai Pendaftar (status: data_disetujui)
echo   3. Buka /pendaftar/dashboard
echo   4. Scroll to "Petunjuk Cetak Kartu Ujian" section
echo   5. Verify 2 buttons tampil:
echo      - "Cetak Kartu Ujian" (biru)
echo      - "Bergabung ke Grup" (hijau)
echo   6. Click "Bergabung ke Grup"
echo   7. Verify:
echo      - Opens in new tab
echo      - URL = https://chat.whatsapp.com/ABC123
echo.
echo [TEST 3: Button di Kartu Tes Seleksi]
echo   1. Masih sebagai Pendaftar
echo   2. Buka /pendaftar/kartu-tes-seleksi
echo   3. Verify 2 buttons di top-right:
echo      - "Cetak Kartu" (biru)
echo      - "Bergabung ke Grup" (hijau)
echo   4. Click "Bergabung ke Grup"
echo   5. Verify opens WhatsApp group in new tab
echo.
echo [TEST 4: Toggle OFF = Button Hidden]
echo   1. Login sebagai Admin
echo   2. Buka /admin/pengaturan-kartu?activeTab=link-grup
echo   3. Toggle switch OFF
echo   4. Klik "Simpan Link Grup"
echo   5. Logout, login sebagai Pendaftar
echo   6. Check dashboard dan kartu tes seleksi
echo   7. Verify: NO "Bergabung ke Grup" button
echo   8. Verify: ONLY "Cetak Kartu" buttons tampil
echo.
echo [TEST 5: Print Behavior]
echo   1. Login sebagai Pendaftar
echo   2. Buka /pendaftar/kartu-tes-seleksi
echo   3. Click "Cetak Kartu" button (print preview)
echo   4. Verify: Both buttons (Cetak ^& Bergabung) HIDDEN in print
echo   5. Verify: Only kartu content visible for printing
echo.
echo [TEST 6: URL Validation]
echo   1. Admin: Enable toggle
echo   2. Input invalid URL: "not-a-url"
echo   3. Click save
echo   4. Verify error: "Link grup harus berupa URL yang valid"
echo   5. Input valid URL: https://t.me/testgroup
echo   6. Verify success
echo.
echo [TEST 7: Telegram Link]
echo   1. Admin: Input https://t.me/ppdbtest2026
echo   2. Save
echo   3. Pendaftar: Click "Bergabung ke Grup"
echo   4. Verify: Opens Telegram group page
echo.
echo [TEST 8: Empty URL]
echo   1. Admin: Enable toggle but leave URL empty
echo   2. Save
echo   3. Pendaftar: Check dashboard
echo   4. Verify: NO button appears (both conditions needed)
echo.
echo Expected Results:
echo   - Admin dapat set link grup dengan toggle ON/OFF
echo   - URL validation works (must be valid URL)
echo   - Preview link tampil dan clickable
echo   - Button preview accurate
echo   - Pendaftar sees button ONLY if enabled AND URL not empty
echo   - Button opens in new tab with correct URL
echo   - Print mode hides buttons
echo   - Works for WhatsApp and Telegram links
echo.
echo ================================================================
echo   TROUBLESHOOTING
echo ================================================================
echo.
echo Problem: Button tidak muncul di dashboard?
echo Solution 1: Check Admin toggle is ON
echo Solution 2: Check URL is not empty
echo Solution 3: Hard refresh (Ctrl+Shift+R)
echo Solution 4: Clear browser cache
echo.
echo Problem: Link tidak buka di tab baru?
echo Solution: Check browser popup blocker settings
echo.
echo Problem: Error saat save "URL not valid"?
echo Solution: Ensure URL starts with https:// or http://
echo.
echo Problem: Toggle tidak berfungsi?
echo Solution 1: Check JavaScript console for errors
echo Solution 2: Hard refresh page
echo Solution 3: Try different browser
echo.
echo Problem: Button masih muncul setelah toggle OFF?
echo Solution 1: Clear Livewire cache again
echo Solution 2: Hard refresh browser
echo Solution 3: Check setting in database:
echo            ssh %SERVER% "cd %REMOTE_PATH% && php artisan tinker"
echo            App\Models\Setting::get('link_grup_enabled')
echo.
echo ================================================================
echo.
echo Fitur Link Grup READY untuk production!
echo.
echo New Database Settings (automatically created on first save):
echo   - link_grup_url (string) = WhatsApp/Telegram group link
echo   - link_grup_enabled (boolean) = Enable/disable button visibility
echo.
echo Access:
echo   - Admin: /admin/pengaturan-kartu?activeTab=link-grup
echo   - Pendaftar (dashboard): /pendaftar/dashboard
echo   - Pendaftar (kartu): /pendaftar/kartu-tes-seleksi
echo.
echo Features:
echo   - Enable/disable toggle switch
echo   - URL validation (format + max 500 chars)
echo   - Real-time preview of link
echo   - Visual preview of buttons
echo   - Conditional display (only if enabled AND URL exists)
echo   - Opens in new tab (safe external link)
echo   - Print-friendly (hidden in print mode)
echo   - Works with WhatsApp ^& Telegram
echo.
echo ================================================================
pause
