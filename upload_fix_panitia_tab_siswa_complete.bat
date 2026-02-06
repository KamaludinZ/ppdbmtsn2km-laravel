@echo off
echo ========================================
echo Upload Fix Panitia Tab Siswa - COMPLETE
echo ========================================
echo.
echo MASALAH YANG DIPERBAIKI:
echo.
echo [1] ERROR 500 saat klik "Edit Data" di Tab Siswa
echo [2] Missing 28 properties (EMIS fields)
echo [3] Validation only 7 fields (now 30+ fields)
echo [4] Save only 7 fields (now 30+ fields)
echo.

set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo ROOT CAUSE:
echo ============
echo Tab Siswa view has 30+ fields with wire:model
echo Panitia controller only had 8 properties
echo Missing 28 properties caused Livewire error 500
echo.
echo SOLUTION:
echo =========
echo Added 28 missing properties
echo Updated saveDataPribadi() to validate all fields
echo Updated saveDataPribadi() to save all fields
echo Matches Admin controller behavior now
echo.

echo ========================================
echo UPLOAD FILE...
echo ========================================
echo.

echo [1/2] Upload Panitia Controller...
scp "app/Livewire/Panitia/DetailPendaftar.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Panitia/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload controller
    pause
    exit /b 1
)
echo [OK] Panitia controller uploaded

echo.
echo [2/2] Clear cache...
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
echo 1. ADDED PROPERTIES (28 properties):
echo    ✓ nik, nama_panggilan, kewarganegaraan
echo    ✓ negara_asal, nomor_kitas
echo    ✓ nomor_kk, nama_kepala_keluarga
echo    ✓ anak_ke, jumlah_saudara, jumlah_saudara_kandung
echo    ✓ nomor_telepon_rumah, email_pribadi
echo    ✓ tidak_punya_hp, pra_sekolah_tk, pra_sekolah_paud
echo    ✓ imunisasi_* (6 fields)
echo    ✓ hobi, cita_cita, yang_membiayai_sekolah
echo    ✓ nomor_kip, berkebutuhan_khusus, jenis_kebutuhan_khusus
echo.
echo 2. LOADING LOGIC (loadDataToProperties):
echo    ✓ Load all 28 properties with null coalescing
echo    ✓ Boolean fields default to false
echo    ✓ Text fields default to empty string
echo    ✓ Array fields default to empty array
echo.
echo 3. VALIDATION (saveDataPribadi):
echo    ✓ Validate 30+ fields (was 7)
echo    ✓ Proper regex for names
echo    ✓ NIK 16 digits, NISN 10 digits
echo    ✓ Conditional validation (WNI/WNA)
echo    ✓ Phone number validation
echo.
echo 4. SAVE LOGIC (saveDataPribadi):
echo    ✓ Save all 30+ fields (was 7)
echo    ✓ Proper null handling for booleans
echo    ✓ Exit edit mode after save
echo.
echo ========================================
echo TESTING CHECKLIST
echo ========================================
echo.
echo URL: https://pmbm.mtsn2kotamalang.sch.id/panitia-pendaftaran/pendaftar/167?activeTab=siswa
echo.
echo PRIORITY TEST:
echo [ ] Klik "Edit Data" → TIDAK ERROR 500 ✓
echo [ ] Form masuk edit mode ✓
echo [ ] Semua field tampil ✓
echo [ ] Isi data → Simpan → Data tersimpan ✓
echo [ ] Keluar dari edit mode ✓
echo [ ] Flash message tampil ✓
echo.
echo VALIDATION TEST:
echo [ ] Field required → Error jika kosong ✓
echo [ ] NISN 10 digit validation ✓
echo [ ] NIK 16 digit validation ✓
echo [ ] WNA → Negara asal required ✓
echo [ ] WNI → NIK required ✓
echo.
echo EXPECTED BEHAVIOR:
echo 1. Klik "Edit Data" → Masuk edit mode (NO ERROR 500)
echo 2. Form tampil semua field EMIS
echo 3. Validation bekerja untuk semua field
echo 4. Klik "Simpan" → Data tersimpan SEMUA field
echo 5. Keluar dari edit mode
echo 6. Flash message "Data siswa berhasil diperbarui"
echo.
echo USER INSIGHT:
echo "apakah mungkin sama kasusnya seperti di admin yang masih
echo  membaca waktu tempuh yang padahal ada di tab alamat"
echo.
echo ✓ User benar! Pattern sama: View punya field, controller tidak
echo ✓ Solution: Tambah missing properties ke controller
echo.
echo Hard refresh browser: Ctrl + Shift + R
echo.
pause
