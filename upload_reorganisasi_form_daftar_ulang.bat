@echo off
echo ============================================
echo REORGANISASI FORM DAFTAR ULANG MADRASAH
echo ============================================
echo.
echo PERUBAHAN YANG AKAN DI-UPLOAD:
echo [1] Surat Pernyataan dipindahkan ke paling atas
echo [2] Payment fields (Opsi + Bank) digabung dengan Upload Bukti
echo [3] Tambah field "Nama Pengirim" di bawah Bank
echo [4] Tambah divider antara payment info dan upload
echo.
echo FILE YANG AKAN DI-UPLOAD:
echo - resources/views/livewire/pendaftar/daftar-ulang.blade.php
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
echo CLEAR VIEW CACHE
echo ========================================
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear"
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
echo STRUKTUR FORM BARU:
echo ==================
echo.
echo TAB MADRASAH - NEW LAYOUT:
echo.
echo 1. SURAT PERNYATAAN (paling atas)
echo    - Download Template
echo    - Upload Surat Pernyataan
echo.
echo 2. PILIH NOMINAL BIAYA
echo    - Pilih Nominal
echo    - Konfirmasi Nominal
echo.
echo 3. UPLOAD BUKTI PEMBAYARAN (merged)
echo    - Pilih Opsi Pembayaran
echo    - Daftar Bank/Metode Pembayaran
echo    - Nama Pengirim (NEW)
echo    - [Divider]
echo    - Upload Bukti Pembayaran
echo.
echo 4. Tombol Simpan Data
echo.
echo ============================================
echo TESTING CHECKLIST
echo ============================================
echo.
echo TEST 1: Verifikasi Urutan Section
echo ==================================
echo 1. Login sebagai Pendaftar (status lulus)
echo 2. Buka /pendaftar/daftarulang?activeTab=madrasah
echo 3. VERIFY urutan section:
echo    [x] Section 1: Surat Pernyataan (paling atas)
echo    [x] Section 2: Pilih Nominal Biaya
echo    [x] Section 3: Upload Bukti Pembayaran (dengan payment fields)
echo.
echo TEST 2: Verifikasi Field Nama Pengirim
echo ======================================
echo 1. Scroll ke section "Upload Bukti Pembayaran"
echo 2. VERIFY field Nama Pengirim:
echo    [x] Ada setelah "Daftar Bank/Metode Pembayaran"
echo    [x] Label: "Nama Pengirim" dengan asterisk merah
echo    [x] Placeholder: "Masukkan nama pengirim sesuai bukti transfer"
echo    [x] Helper text muncul di bawah input
echo    [x] Input field dapat diisi
echo.
echo TEST 3: Verifikasi Visual Grouping
echo ==================================
echo 1. Check section "Upload Bukti Pembayaran"
echo 2. VERIFY urutan field dalam section:
echo    [x] Pilih Opsi Pembayaran (dropdown)
echo    [x] Daftar Bank/Metode Pembayaran (dropdown)
echo    [x] Nama Pengirim (input text)
echo    [x] --- Divider line (border-t) ---
echo    [x] Upload Bukti Pembayaran (file input)
echo.
echo TEST 4: Test Form Submission
echo ============================
echo 1. Isi semua field yang required:
echo    - Upload Surat Pernyataan
echo    - Pilih Nominal Biaya
echo    - Konfirmasi Nominal
echo    - Pilih Opsi Pembayaran
echo    - Pilih Bank
echo    - Isi Nama Pengirim (contoh: "Budi Santoso")
echo    - Upload Bukti Pembayaran
echo.
echo 2. Klik tombol "Simpan Data"
echo 3. VERIFY:
echo    [x] Loading indicator muncul
echo    [x] Success message muncul
echo    [x] Data tersimpan
echo.
echo 4. CHECK DATABASE:
echo    SELECT nama_pengirim, opsi_pembayaran, metode_pembayaran
echo    FROM daftar_ulang
echo    WHERE pendaftar_id = [test_id];
echo.
echo    [x] nama_pengirim = "Budi Santoso"
echo    [x] opsi_pembayaran = [selected option]
echo    [x] metode_pembayaran = [selected bank]
echo.
echo TEST 5: Validation Test
echo =======================
echo 1. Try submit TANPA mengisi Nama Pengirim
echo 2. VERIFY:
echo    [x] Error message muncul di bawah field Nama Pengirim
echo    [x] Text error berwarna merah
echo    [x] Form tidak submit
echo.
echo TEST 6: Responsive Test
echo =======================
echo 1. Test di berbagai ukuran layar:
echo    - Desktop (^>1024px)
echo    - Tablet (768px - 1024px)
echo    - Mobile (^<768px)
echo.
echo 2. VERIFY:
echo    [x] Semua section responsive
echo    [x] Field stack dengan baik
echo    [x] Divider tetap visible
echo    [x] Tidak ada horizontal overflow
echo.
echo ============================================
echo VISUAL COMPARISON
echo ============================================
echo.
echo BEFORE:
echo -------
echo Container 1: Pilih Nominal
echo - Pilih Nominal Biaya
echo - Konfirmasi Nominal
echo - Opsi Pembayaran
echo - Bank Selection
echo.
echo Container 2: Upload Bukti (terpisah)
echo - Upload only
echo.
echo Container 3: Surat Pernyataan (di bawah)
echo.
echo AFTER:
echo ------
echo Container 1: Surat Pernyataan (dipindah ke atas)
echo - Download Template
echo - Upload Surat
echo.
echo Container 2: Pilih Nominal (streamlined)
echo - Pilih Nominal Biaya
echo - Konfirmasi Nominal
echo.
echo Container 3: Upload Bukti (merged)
echo - Opsi Pembayaran
echo - Bank Selection
echo - Nama Pengirim (NEW)
echo - [Divider]
echo - Upload Bukti
echo.
echo ============================================
echo BENEFITS
echo ============================================
echo [+] Better user flow (surat dulu, baru nominal, baru payment)
echo [+] Related fields grouped together
echo [+] Nama Pengirim helps admin verification
echo [+] Clearer visual hierarchy
echo [+] Reduced form confusion
echo.
echo NOTES:
echo - Field nama_pengirim sudah ada di database
echo - Wire model "namaPengirim" sudah ada di Livewire component
echo - No database migration needed
echo - Safe to deploy
echo.
pause
