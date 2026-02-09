@echo off
echo ============================================
echo FIX SURAT PERNYATAAN REQUIRED FIRST
echo ============================================
echo.
echo PERUBAHAN YANG AKAN DI-UPLOAD:
echo [1] Tombol "Simpan Surat Pernyataan" setelah pilih file
echo [2] Form berikutnya HIDDEN jika belum upload surat pernyataan
echo [3] Pesan info untuk user dengan step-by-step instructions
echo [4] Backend method saveSuratPernyataan() untuk save terpisah
echo.
echo FILES YANG AKAN DI-UPLOAD:
echo - resources/views/livewire/pendaftar/daftar-ulang.blade.php
echo - app/Livewire/Pendaftar/DaftarUlang.php
echo.
pause

echo.
echo ========================================
echo UPLOAD DAFTAR ULANG VIEW
echo ========================================
scp resources/views/livewire/pendaftar/daftar-ulang.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if %errorlevel% neq 0 (
    echo GAGAL upload daftar-ulang view
    pause
    exit /b 1
)
echo BERHASIL upload daftar-ulang view

echo.
echo ========================================
echo UPLOAD DAFTAR ULANG LIVEWIRE COMPONENT
echo ========================================
scp app/Livewire/Pendaftar/DaftarUlang.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if %errorlevel% neq 0 (
    echo GAGAL upload DaftarUlang component
    pause
    exit /b 1
)
echo BERHASIL upload DaftarUlang component

echo.
echo ========================================
echo CLEAR CACHE
echo ========================================
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear && php artisan optimize:clear"
if %errorlevel% neq 0 (
    echo WARNING: Gagal clear cache
) else (
    echo BERHASIL clear cache
)

echo.
echo ============================================
echo UPLOAD SELESAI!
echo ============================================
echo.
echo WORKFLOW BARU:
echo ==============
echo.
echo STATE 1: BELUM UPLOAD SURAT PERNYATAAN
echo =======================================
echo [x] Section "Surat Pernyataan" visible (paling atas)
echo [x] Download Template button visible
echo [x] Upload area visible
echo [ ] Tombol "Simpan Surat Pernyataan" NOT visible (belum pilih file)
echo.
echo [x] Yellow warning box visible dengan instruksi:
echo     1. Download template Surat Pernyataan
echo     2. Isi dan tandatangani
echo     3. Scan atau foto dalam PDF
echo     4. Upload surat pernyataan
echo     5. Klik "Simpan Surat Pernyataan"
echo.
echo [ ] Form "Pilih Nominal Biaya" NOT visible (hidden)
echo [ ] Form "Upload Bukti Pembayaran" NOT visible (hidden)
echo [ ] Tombol "Simpan Data" NOT visible (hidden)
echo.
echo STATE 2: FILE DIPILIH (BELUM DISIMPAN)
echo =======================================
echo [x] File name displayed: "File terpilih: surat_pernyataan.pdf"
echo [x] Tombol "Simpan Surat Pernyataan" NOW visible (blue button)
echo [x] Yellow warning box still visible
echo [ ] Form berikutnya still HIDDEN
echo.
echo STATE 3: SURAT PERNYATAAN TERSIMPAN
echo ====================================
echo [x] Green badge: "Surat pernyataan telah diupload [Lihat]"
echo [x] Upload area hidden
echo [x] Yellow warning box DISAPPEARED
echo [x] Form "Pilih Nominal Biaya" NOW visible
echo [x] Form "Upload Bukti Pembayaran" NOW visible
echo [x] All payment fields visible (Opsi, Bank, Nama Pengirim)
echo [x] Tombol "Simpan Data" NOW visible
echo.
echo ============================================
echo TESTING CHECKLIST
echo ============================================
echo.
echo TEST 1: Initial Load (Belum Upload)
echo ====================================
echo 1. Login sebagai Pendaftar (status lulus)
echo 2. Buka /pendaftar/daftarulang?activeTab=madrasah
echo 3. VERIFY:
echo    [x] Section Surat Pernyataan visible at top
echo    [x] Download Template button visible
echo    [ ] Tombol "Simpan Surat Pernyataan" NOT visible
echo    [x] Yellow warning box visible
echo    [ ] Form "Pilih Nominal" NOT visible
echo    [ ] Form "Upload Bukti" NOT visible
echo    [ ] Tombol "Simpan Data" NOT visible
echo.
echo TEST 2: File Selected (Belum Disimpan)
echo ======================================
echo 1. Click upload area, select PDF file
echo 2. VERIFY:
echo    [x] File name displayed
echo    [x] Tombol "Simpan Surat Pernyataan" NOW visible (blue)
echo    [x] Warning box still visible
echo    [ ] Form berikutnya still HIDDEN
echo.
echo TEST 3: Save Surat Pernyataan
echo ==============================
echo 1. With file selected, click "Simpan Surat Pernyataan"
echo 2. VERIFY loading state:
echo    [x] Button shows spinner
echo    [x] Text: "Menyimpan..."
echo    [x] Button disabled
echo 3. After success:
echo    [x] Success message: "Surat pernyataan berhasil disimpan!"
echo    [x] Green badge appears
echo    [x] Upload area hidden
echo.
echo TEST 4: Form Unlock After Save
echo ===============================
echo 1. After surat pernyataan saved
echo 2. VERIFY:
echo    [ ] Yellow warning box DISAPPEARED
echo    [x] Form "Pilih Nominal Biaya" NOW visible
echo    [x] Form "Upload Bukti Pembayaran" NOW visible
echo    [x] Nama Pengirim field visible
echo    [x] Tombol "Simpan Data" NOW visible
echo.
echo TEST 5: Complete Form Submission
echo ================================
echo 1. Surat pernyataan already uploaded
echo 2. Fill all fields:
echo    - Select nominal biaya
echo    - Confirm nominal
echo    - Select opsi pembayaran
echo    - Select bank
echo    - Enter nama pengirim
echo    - Upload bukti pembayaran
echo 3. Click "Simpan Data"
echo 4. VERIFY:
echo    [x] Success message appears
echo    [x] All data saved to database
echo.
echo TEST 6: Validation Test
echo =======================
echo 1. Try to upload non-PDF file (e.g., .jpg)
echo 2. Click "Simpan Surat Pernyataan"
echo 3. VERIFY:
echo    [x] Error message: "File harus berformat PDF"
echo    [x] Form not saved
echo    [x] Warning box still visible
echo.
echo TEST 7: Re-upload Test
echo ======================
echo 1. Surat pernyataan already uploaded
echo 2. Upload different PDF file
echo 3. Click "Simpan Surat Pernyataan" again
echo 4. VERIFY:
echo    [x] Old file replaced with new file
echo    [x] Success message appears
echo    [x] Form berikutnya remains visible
echo.
echo DATABASE VERIFICATION:
echo =====================
echo Run SQL query:
echo.
echo SELECT
echo     surat_pernyataan_path,      -- Should have path
echo     nominal_biaya,               -- Should have value
echo     opsi_pembayaran,             -- Should have value
echo     metode_pembayaran,           -- Should have value
echo     nama_pengirim,               -- Should have value
echo     bukti_pembayaran_madrasah,   -- Should have path
echo     status
echo FROM daftar_ulang
echo WHERE pendaftar_id = [test_pendaftar_id];
echo.
echo BACKEND METHOD ADDED:
echo ====================
echo Method: saveSuratPernyataan()
echo Location: app/Livewire/Pendaftar/DaftarUlang.php
echo Line: 331-376
echo.
echo Features:
echo - Validates PDF file (max 2MB)
echo - Creates DaftarUlang record if not exists
echo - Deletes old file if re-uploading
echo - Stores file in 'surat-pernyataan' folder
echo - Updates database with file path
echo - Shows success message
echo - Refreshes component to show unlocked forms
echo.
pause
