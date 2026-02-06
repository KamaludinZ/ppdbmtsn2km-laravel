@echo off
echo ========================================
echo FIX: UPLOAD BUTTON DAN TOMBOL LIHAT BUKTI
echo ========================================
echo.
echo Perubahan:
echo - Batalkan auto-upload, gunakan tombol Upload terpisah
echo - Tambahkan tombol "Lihat Bukti" yang prominent
echo - Terapkan untuk Tab Madrasah dan Tab Seragam
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
echo Tab Madrasah:
echo  - Tombol Upload Surat Pernyataan (biru)
echo  - Tombol Upload Bukti Pembayaran (emerald)
echo  - Tombol Lihat Bukti (prominent) untuk file yang sudah terupload
echo.
echo Tab Seragam:
echo  - Tombol Upload Bukti Pembayaran (biru)
echo  - Tombol Lihat Bukti (prominent) untuk file yang sudah terupload
echo.
echo NEW METHODS:
echo  - uploadSuratPernyataan^(^)
echo  - uploadBuktiPembayaran^(^)
echo  - uploadBuktiSeragam^(^)
echo.
echo TESTING CHECKLIST:
echo [ ] Test upload Surat Pernyataan ^> tombol muncul ^> loading ^> Lihat Bukti muncul
echo [ ] Test upload Bukti Pembayaran ^> tombol muncul ^> loading ^> Lihat Bukti muncul
echo [ ] Test upload Bukti Seragam ^> tombol muncul ^> loading ^> Lihat Bukti muncul
echo [ ] Test klik "Lihat Bukti" ^> file terbuka di tab baru
echo [ ] Verify loading indicator berfungsi
echo [ ] Test validasi file type dan size
echo.
pause
