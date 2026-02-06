@echo off
echo ========================================
echo Upload Fix Pendaftar Tab Orangtua
echo ========================================
echo.
echo MASALAH YANG DIPERBAIKI:
echo.
echo Tab Orangtua di /pendaftar/formulir tidak bisa masuk edit mode
echo Klik "Edit Data" tidak berfungsi
echo.
echo ROOT CAUSE:
echo Property name mismatch setelah fix sebelumnya
echo - Expected: editModeOrangtua (lowercase t)
echo - Actual: editModeOrangTua (CAPITAL T)
echo.

set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo ========================================
echo UPLOAD FILES...
echo ========================================
echo.

echo [1/3] Upload Pendaftar Controller...
scp "app/Livewire/Pendaftar/Formulir.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload controller
    pause
    exit /b 1
)
echo [OK] Pendaftar controller uploaded

echo.
echo [2/3] Upload Tab Template (backward compatibility)...
scp "resources/views/tab_templates/tab-data-orangtua.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/tab_templates/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload template
    pause
    exit /b 1
)
echo [OK] Tab template uploaded

echo.
echo [3/3] Clear cache...
ssh %SERVER% "cd %REMOTE_PATH% && php artisan optimize:clear"
if %errorlevel% neq 0 (
    echo ERROR: Gagal clear cache
    pause
    exit /b 1
)
echo [OK] Cache cleared

echo.
echo ========================================
echo UPLOAD BERHASIL!
echo ========================================
echo.
echo YANG SUDAH DIPERBAIKI:
echo.
echo 1. PROPERTY NAME (Pendaftar/Formulir.php):
echo    Line 41:   editModeOrangTua → editModeOrangtua ✓
echo    Line 1321: editModeOrangTua → editModeOrangtua ✓
echo.
echo 2. TEMPLATE COMPATIBILITY (tab-data-orangtua.blade.php):
echo    Support both: editModeOrangtua AND editModeOrangTua ✓
echo.
echo WHY THIS FIX WORKS:
echo ===================
echo ucfirst('orangtua') = 'Orangtua' (lowercase t)
echo 'editMode' + 'Orangtua' = 'editModeOrangtua'
echo.
echo Property MUST match ucfirst() result:
echo ✓ editModeOrangtua (CORRECT)
echo ✗ editModeOrangTua (WRONG - causes mismatch)
echo.
echo ========================================
echo TESTING CHECKLIST
echo ========================================
echo.
echo URL: https://pmbm.mtsn2kotamalang.sch.id/pendaftar/formulir?activeTab=orangtua
echo.
echo TEST:
echo [ ] Klik "Edit Data" → Masuk edit mode ✓
echo [ ] Form fields jadi editable ✓
echo [ ] Button berubah jadi "Batal" ✓
echo [ ] Edit data → Simpan → Data tersimpan ✓
echo [ ] Keluar dari edit mode setelah simpan ✓
echo [ ] Flash message tampil ✓
echo.
echo EXPECTED BEHAVIOR:
echo 1. Klik "Edit Data" → Masuk edit mode (WORKS NOW)
echo 2. Form fields enabled
echo 3. Edit data orang tua
echo 4. Klik "Simpan" → Data tersimpan
echo 5. Keluar dari edit mode
echo 6. Flash message "Data orang tua berhasil diperbarui"
echo.
echo Hard refresh browser: Ctrl + Shift + R
echo.
pause
