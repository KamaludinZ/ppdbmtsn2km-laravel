@echo off
echo ========================================
echo FIX: REQUIRED FIELDS - PENDAFTAR DAFTAR ULANG
echo ========================================
echo.
echo Perubahan: Semua field di /pendaftar/daftarulang menjadi wajib diisi
echo Tanggal: 5 Februari 2026
echo.

REM Upload Pendaftar DaftarUlang controller
echo [1/2] Uploading Pendaftar DaftarUlang controller...
scp app/Livewire/Pendaftar/DaftarUlang.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
if %errorlevel% neq 0 (
    echo ERROR: Failed to upload Pendaftar DaftarUlang controller
    pause
    exit /b 1
)
echo Pendaftar DaftarUlang controller uploaded successfully!
echo.

REM Clear cache
echo [2/2] Clearing production cache...
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
echo - 1 controller (Pendaftar DaftarUlang)
echo.
echo PERUBAHAN VALIDASI:
echo.
echo Tab Madrasah (DU Madrasah):
echo  - opsiPembayaran: nullable ^> REQUIRED
echo  - bankPilihan: nullable ^> REQUIRED
echo  - buktiPembayaran: nullable ^> REQUIRED
echo  - namaPengirim: nullable ^> REQUIRED
echo  - suratPernyataan: nullable ^> REQUIRED
echo.
echo Tab Seragam (DU Seragam):
echo  - paketSeragam: nullable ^> REQUIRED
echo  - opsiPembayaranSeragam: nullable ^> REQUIRED
echo  - bankSeragam: nullable ^> REQUIRED
echo  - buktiSeragam: nullable ^> REQUIRED
echo  - namaPengirimSeragam: nullable ^> REQUIRED
echo.
echo Tab Ukuran Seragam:
echo  - ukuran_jas: nullable ^> REQUIRED
echo  - ukuran_baju_olahraga: nullable ^> REQUIRED
echo  - ukuran_celana_olahraga: nullable ^> REQUIRED
echo  - ukuran_baju_harian: nullable ^> REQUIRED
echo  - ukuran_celana_rok_harian: nullable ^> REQUIRED
echo  - ukuran_kopyah: nullable ^> REQUIRED
echo.
echo TESTING CHECKLIST:
echo [ ] Test submit form tanpa isi field ^> harus muncul error
echo [ ] Test submit Tab Madrasah dengan semua field diisi ^> harus berhasil
echo [ ] Test submit Tab Seragam dengan semua field diisi ^> harus berhasil
echo [ ] Test submit Tab Ukuran dengan semua ukuran dipilih ^> harus berhasil
echo [ ] Verify error message clear dan informatif
echo.
pause
