@echo off
echo ========================================
echo UPLOAD: Add New Columns to Admin/Panitia Daftar Ulang Tables
echo Date: 8 February 2026
echo ========================================
echo.
echo Changes:
echo 1. Admin Seragam Tab:
echo    - Added 2 columns after "Nominal yang Dibayar": Ukuran Baju Harian, Celana/Rok Harian
echo    - Added 1 column after "Bank/Metode": Nama Pengirim
echo.
echo 2. Admin Madrasah Tab:
echo    - Added 1 column after "Bank/Metode": Nama Pengirim
echo.
echo 3. Panitia Madrasah Tab:
echo    - Added 1 column after "Bank/Metode": Nama Pengirim
echo.
echo ========================================
echo.

set /p confirm="Upload files to production? (y/n): "
if /i not "%confirm%"=="y" (
    echo Upload cancelled.
    exit /b
)

echo.
echo [1/2] Uploading Admin DaftarUlang view...
scp resources/views/livewire/admin/daftar-ulang.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/

echo.
echo [2/2] Uploading Panitia DaftarUlang view...
scp resources/views/livewire/panitia/daftar-ulang.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/

echo.
echo ========================================
echo Clearing cache on production...
echo ========================================
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear && php artisan optimize:clear"

echo.
echo ========================================
echo Upload complete!
echo ========================================
echo.
echo TESTING CHECKLIST:
echo.
echo [Admin - Seragam Tab]
echo [ ] Navigate to /admin/daftar-ulang?activeTab=seragam
echo [ ] Verify "Ukuran Baju Harian" column appears after "Nominal yang Dibayar"
echo [ ] Verify "Celana/Rok Harian" column appears after "Ukuran Baju Harian"
echo [ ] Verify "Nama Pengirim" column appears after "Bank/Metode"
echo [ ] Check data displays correctly (green badge for baju, teal badge for celana)
echo [ ] Check "Nama Pengirim" data displays correctly
echo.
echo [Admin - Madrasah Tab]
echo [ ] Navigate to /admin/daftar-ulang?activeTab=madrasah
echo [ ] Verify "Nama Pengirim" column appears after "Bank/Metode"
echo [ ] Check data displays correctly
echo.
echo [Panitia - Madrasah Tab]
echo [ ] Navigate to /panitia/daftar-ulang
echo [ ] Verify "Nama Pengirim" column appears after "Bank/Metode"
echo [ ] Check data displays correctly
echo.
echo ========================================
pause
