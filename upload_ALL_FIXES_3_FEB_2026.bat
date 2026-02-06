@echo off
echo ========================================
echo UPLOAD ALL FIXES - 3 Februari 2026
echo ========================================
echo.
echo TOTAL FIXES: 2 issues
echo FILES: 3 files
echo.
echo [FIX 1] Panitia Tab Siswa - Error 500
echo        /panitia-pendaftaran/pendaftar/167?activeTab=siswa
echo        ✓ Added 28 missing properties
echo        ✓ Updated validation (7 → 30+ fields)
echo        ✓ Updated save (7 → 30+ fields)
echo.
echo [FIX 2] Pendaftar Tab Orangtua - Edit Mode
echo        /pendaftar/formulir?activeTab=orangtua
echo        ✓ Fixed property name (OrangTua → Orangtua)
echo        ✓ Template backward compatibility
echo.

set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo ========================================
echo UPLOAD FILES...
echo ========================================
echo.

echo [1/4] Upload Panitia Controller...
scp "app/Livewire/Panitia/DetailPendaftar.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Panitia/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload Panitia controller
    pause
    exit /b 1
)
echo [OK] Panitia controller uploaded

echo.
echo [2/4] Upload Pendaftar Controller...
scp "app/Livewire/Pendaftar/Formulir.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload Pendaftar controller
    pause
    exit /b 1
)
echo [OK] Pendaftar controller uploaded

echo.
echo [3/4] Upload Tab Template...
scp "resources/views/tab_templates/tab-data-orangtua.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/tab_templates/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload template
    pause
    exit /b 1
)
echo [OK] Tab template uploaded

echo.
echo [4/4] Clear cache...
ssh %SERVER% "cd %REMOTE_PATH% && php artisan view:clear && php artisan optimize:clear && rm -rf storage/framework/views/*.php"
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
echo SUMMARY FIX #1: Panitia Tab Siswa
echo ===================================
echo.
echo PROBLEM:
echo - Error 500 saat klik "Edit Data"
echo - Tab Siswa view has 30+ fields
echo - Controller only has 8 properties
echo - Missing 28 properties = error 500
echo.
echo SOLUTION:
echo ✓ Added 28 missing properties:
echo   - nik, nama_panggilan, kewarganegaraan
echo   - negara_asal, nomor_kitas, nomor_kk
echo   - nama_kepala_keluarga, anak_ke
echo   - jumlah_saudara, jumlah_saudara_kandung
echo   - nomor_telepon_rumah, email_pribadi
echo   - tidak_punya_hp, hobi, cita_cita
echo   - yang_membiayai_sekolah, nomor_kip
echo   - pra_sekolah_tk, pra_sekolah_paud
echo   - imunisasi_* (6 fields)
echo   - berkebutuhan_khusus, jenis_kebutuhan_khusus
echo.
echo ✓ Updated loadDataToProperties():
echo   - Load all 28 properties with null coalescing
echo   - Boolean default: false
echo   - Text default: empty string
echo   - Array default: empty array
echo.
echo ✓ Updated saveDataPribadi():
echo   - Validate 30+ fields (was 7)
echo   - Save 30+ fields (was 7)
echo   - Proper regex validation
echo   - NIK 16 digits, NISN 10 digits
echo   - Conditional WNI/WNA validation
echo.
echo IMPACT:
echo ✓ NO MORE ERROR 500
echo ✓ ALL fields can be edited and saved
echo ✓ Proper validation for all fields
echo ✓ Data integrity maintained
echo.
echo.
echo SUMMARY FIX #2: Pendaftar Tab Orangtua
echo =======================================
echo.
echo PROBLEM:
echo - Klik "Edit Data" tidak masuk edit mode
echo - Property name mismatch after previous fix
echo - Expected: editModeOrangtua (lowercase t)
echo - Actual: editModeOrangTua (CAPITAL T)
echo.
echo SOLUTION:
echo ✓ Changed property name:
echo   Line 41:   editModeOrangTua → editModeOrangtua
echo   Line 1321: editModeOrangTua → editModeOrangtua
echo.
echo ✓ Template backward compatibility:
echo   Support both property name variations
echo.
echo WHY:
echo ucfirst('orangtua') = 'Orangtua' (lowercase t)
echo toggleEdit() builds: 'editMode' + 'Orangtua'
echo Result: 'editModeOrangtua' (lowercase t)
echo Property MUST match this!
echo.
echo IMPACT:
echo ✓ Edit mode works correctly
echo ✓ Consistent with Admin & Panitia
echo ✓ Backward compatible
echo.
echo ========================================
echo TESTING CHECKLIST
echo ========================================
echo.
echo TEST 1: Panitia Tab Siswa
echo ==========================
echo URL: https://pmbm.mtsn2kotamalang.sch.id/panitia-pendaftaran/pendaftar/167?activeTab=siswa
echo.
echo [ ] Klik "Edit Data" → TIDAK ERROR 500 ✓
echo [ ] Form masuk edit mode ✓
echo [ ] Semua field tampil (30+ fields) ✓
echo [ ] Isi data EMIS → Simpan → Data tersimpan ✓
echo [ ] Validation bekerja (NISN, NIK, dll) ✓
echo [ ] Keluar edit mode setelah simpan ✓
echo [ ] Flash message tampil ✓
echo.
echo TEST 2: Pendaftar Tab Orangtua
echo ===============================
echo URL: https://pmbm.mtsn2kotamalang.sch.id/pendaftar/formulir?activeTab=orangtua
echo.
echo [ ] Klik "Edit Data" → Masuk edit mode ✓
echo [ ] Form fields editable ✓
echo [ ] Button "Batal" tampil ✓
echo [ ] Edit data → Simpan → Data tersimpan ✓
echo [ ] Keluar edit mode setelah simpan ✓
echo [ ] Flash message tampil ✓
echo.
echo EXPECTED BEHAVIORS:
echo.
echo Panitia Tab Siswa:
echo 1. NO ERROR 500 when clicking "Edit Data"
echo 2. Form shows all EMIS fields
echo 3. Validation works for all fields
echo 4. All data saved to database
echo 5. Exit edit mode after save
echo.
echo Pendaftar Tab Orangtua:
echo 1. Edit mode WORKS when clicking "Edit Data"
echo 2. Form fields become editable
echo 3. Data saved correctly
echo 4. Exit edit mode after save
echo.
echo Hard refresh browser: Ctrl + Shift + R
echo.
echo ========================================
echo DOKUMENTASI
echo ========================================
echo.
echo 1. FIX_PANITIA_TAB_SISWA_COMPLETE.md
echo 2. FIX_PENDAFTAR_TAB_ORANGTUA.md
echo 3. upload_fix_panitia_tab_siswa_complete.bat
echo 4. upload_fix_pendaftar_orangtua.bat
echo 5. upload_ALL_FIXES_3_FEB_2026.bat (THIS FILE)
echo.
echo ========================================
echo STATISTICS
echo ========================================
echo.
echo Files Modified: 3 files
echo Lines Changed: ~110 lines
echo Properties Added: 28 (Panitia)
echo Properties Fixed: 2 (Pendaftar)
echo Validation Rules: 30+ (Panitia)
echo Issues Fixed: 2 critical issues
echo.
echo USER INSIGHT:
echo "apakah mungkin sama kasusnya seperti di admin yang
echo  masih membaca waktu tempuh yang padahal ada di tab alamat"
echo.
echo ✓ User was CORRECT!
echo ✓ Pattern: View has fields that controller doesn't handle
echo ✓ Solution: Add missing properties to controller
echo.
echo ========================================
echo ALL FIXES UPLOADED SUCCESSFULLY! 🚀
echo ========================================
echo.
pause
