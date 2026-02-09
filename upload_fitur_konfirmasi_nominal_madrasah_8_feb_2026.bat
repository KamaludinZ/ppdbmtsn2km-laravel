@echo off
echo ========================================
echo Fitur: Konfirmasi Nominal Madrasah
echo 8 Februari 2026
echo ========================================
echo.
echo FITUR: Konfirmasi Nominal yang Dibayarkan sama seperti Seragam
echo - Menampilkan "Bayar Penuh" dengan nominal yang dipilih
echo - Menampilkan "Opsi Lainnya" untuk input manual (jika diaktifkan)
echo.
echo ========================================
echo.

echo [INFO] Uploading file to production server...
echo.

echo [1/2] Uploading daftar-ulang.blade.php...
scp "resources\views\livewire\pendaftar\daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/

if errorlevel 1 (
    echo [ERROR] Failed to upload daftar-ulang.blade.php
    pause
    exit /b 1
)

echo [SUCCESS] File uploaded successfully!
echo.

echo [2/2] Running artisan optimize:clear...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"

if errorlevel 1 (
    echo [ERROR] Failed to run optimize:clear
    pause
    exit /b 1
)

echo.
echo ========================================
echo ✅ FITUR DEPLOYED SUCCESSFULLY!
echo ========================================
echo.
echo CHANGES APPLIED:
echo.
echo 1. ✅ Changed "Konfirmasi Nominal yang Dibayarkan" di Madrasah tab
echo    - Sekarang sama seperti Seragam tab
echo.
echo 2. ✅ Menampilkan "Bayar Penuh" option
echo    - Otomatis isi nominal yang dipilih di step sebelumnya
echo    - Warna hijau (emerald)
echo.
echo 3. ✅ Menampilkan "Opsi Lainnya" option (jika diaktifkan)
echo    - Untuk input manual (angsuran/potongan)
echo    - Warna kuning (yellow)
echo    - Manual input field muncul saat dipilih
echo.
echo FLOW SEKARANG:
echo Step 1: Pilih Nominal Biaya
echo         User pilih nominal (e.g., "Nominal A - Rp 1.000.000")
echo         Tampil: "Nominal yang dipilih: Rp 1.000.000"
echo.
echo Step 2: Konfirmasi Nominal yang Dibayarkan
echo         Option 1: [●] Bayar Penuh
echo                   Nominal A
echo                   Rp 1.000.000
echo.
echo         Option 2: [ ] Opsi Lainnya (Bayar Tidak Penuh)
echo                   Isi nominal secara manual (untuk angsuran/potongan)
echo                   [Input manual jika dipilih]
echo.
echo ========================================
echo 🧪 TESTING REQUIRED
echo ========================================
echo.
echo Please verify at:
echo https://pmbm.mtsn2kotamalang.sch.id/pendaftar/daftarulang?activeTab=madrasah
echo.
echo Test Flow:
echo 1. Hard refresh browser: Ctrl + Shift + R
echo 2. Pilih nominal di "Pilih Nominal Biaya"
echo 3. Cek bagian "Konfirmasi Nominal yang Dibayarkan" muncul
echo 4. Verify opsi "Bayar Penuh" tampil dengan nominal yang dipilih
echo 5. Verify opsi "Opsi Lainnya" tampil (jika setting enabled)
echo 6. Klik "Opsi Lainnya" → Manual input field muncul
echo.
echo SETTING UNTUK OPSI LAINNYA (Optional):
echo Key: daftar_ulang_madrasah_payment_enable_opsi_lainnya
echo Value: true (untuk enable) / false (untuk disable)
echo.
echo Default: false (hanya "Bayar Penuh" yang tampil)
echo.
pause
