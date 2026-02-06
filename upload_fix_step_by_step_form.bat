@echo off
echo ========================================
echo FIX: STEP-BY-STEP FORM IMPLEMENTATION
echo ========================================
echo.
echo Perubahan:
echo - Implementasi tampilan bertahap (progressive disclosure)
echo - Step 1: Upload Surat Pernyataan (selalu ditampilkan)
echo - Step 2: Pilih Nominal Biaya (muncul setelah Step 1)
echo - Step 3: Konfirmasi Nominal (muncul setelah Step 2)
echo - Step 4: Opsi Pembayaran + Bank + Nama + Upload Bukti (muncul setelah Step 3)
echo - Fix: Upload button tidak mengubah status
echo.
echo Tanggal: 5 Februari 2026
echo.

REM Upload View
echo [1/3] Uploading Pendaftar DaftarUlang view...
scp resources/views/livewire/pendaftar/daftar-ulang.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload view
    pause
    exit /b 1
)
echo View uploaded successfully!
echo.

REM Upload Controller
echo [2/3] Uploading Pendaftar DaftarUlang controller...
scp app/Livewire/Pendaftar/DaftarUlang.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload controller
    pause
    exit /b 1
)
echo Controller uploaded successfully!
echo.

REM Clear cache
echo [3/3] Clearing production cache...
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
if %errorlevel% neq 0 (
    echo WARNING: Failed to clear cache
)
echo Cache cleared!
echo.

echo ========================================
echo UPLOAD COMPLETED SUCCESSFULLY!
echo ========================================
echo.
echo Files uploaded:
echo - 1 view (Pendaftar DaftarUlang)
echo - 1 controller (Pendaftar DaftarUlang)
echo.
echo NEW FEATURES:
echo.
echo STEP-BY-STEP FORM:
echo  Step 1: Upload Surat Pernyataan (selalu muncul)
echo  Step 2: Pilih Nominal Biaya (muncul setelah Step 1)
echo  Step 3: Konfirmasi Nominal (muncul setelah Step 2)
echo  Step 4: Detail Pembayaran (muncul setelah Step 3)
echo.
echo VISUAL INDICATORS:
echo  - Badge dengan nomor step (1, 2, 3, 4)
echo  - Warna berbeda untuk setiap step
echo  - Clear visual hierarchy
echo.
echo BUG FIXES:
echo  - Upload button tidak mengubah status
echo  - Status hanya berubah saat klik "Simpan Data"
echo  - uploadBuktiPembayaran^(^) - removed status update
echo  - uploadBuktiSeragam^(^) - removed status update
echo.
echo TESTING CHECKLIST:
echo [ ] Step 1 selalu muncul di awal
echo [ ] Step 2 hanya muncul setelah Surat Pernyataan diupload
echo [ ] Step 3 hanya muncul setelah nominal dipilih
echo [ ] Step 4 hanya muncul setelah konfirmasi nominal
echo [ ] Upload button tidak mengubah status
echo [ ] Status berubah "menunggu" hanya setelah klik "Simpan Data"
echo [ ] Step indicators (badges 1-4) ditampilkan dengan benar
echo [ ] Progressive disclosure berfungsi dengan baik
echo.
pause
