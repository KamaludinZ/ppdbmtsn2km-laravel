@echo off
echo ========================================
echo UPLOAD: Fix Bukti Pembayaran Upload for Status Perbaiki/Ditolak
echo Date: 8 February 2026
echo ========================================
echo.
echo Changes:
echo - Seragam Tab: Allow re-upload when status is 'perbaiki' or 'ditolak'
echo - Madrasah Tab: Allow re-upload when status is 'perbaiki' or 'ditolak'
echo.
echo File Modified:
echo - resources/views/livewire/pendaftar/daftar-ulang.blade.php
echo.
echo Lines Changed:
echo - Line 1559: Seragam tab bukti upload condition
echo - Line 958: Madrasah tab bukti upload condition
echo.
echo ========================================
echo.

set /p confirm="Upload file to production? (y/n): "
if /i not "%confirm%"=="y" (
    echo Upload cancelled.
    exit /b
)

echo.
echo [1/1] Uploading Pendaftar DaftarUlang view...
scp resources/views/livewire/pendaftar/daftar-ulang.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/

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
echo [Seragam Tab - Status Perbaiki]
echo [ ] Login as pendaftar with existing bukti_seragam
echo [ ] Admin sets status_seragam = 'perbaiki' in database
echo [ ] Navigate to /pendaftar/daftar-ulang?activeTab=seragam
echo [ ] Verify upload form is shown (NOT "Menunggu Persetujuan")
echo [ ] Upload new bukti pembayaran
echo [ ] Verify upload succeeds
echo.
echo [Seragam Tab - Status Ditolak]
echo [ ] Admin sets status_seragam = 'ditolak'
echo [ ] Refresh page
echo [ ] Verify upload form is shown
echo [ ] Upload new file
echo.
echo [Madrasah Tab - Status Perbaiki]
echo [ ] Login as pendaftar with existing bukti_madrasah
echo [ ] Admin sets status = 'perbaiki' in database
echo [ ] Navigate to /pendaftar/daftar-ulang?activeTab=madrasah
echo [ ] Verify upload form is shown
echo [ ] Upload new file
echo.
echo [Madrasah Tab - Status Ditolak]
echo [ ] Admin sets status = 'ditolak'
echo [ ] Refresh page
echo [ ] Verify upload form is shown
echo [ ] Upload new file
echo.
echo [Status Menunggu - Should NOT Change]
echo [ ] Set status to 'menunggu'
echo [ ] Verify "Menunggu Persetujuan" message shown
echo [ ] Verify upload form is HIDDEN
echo.
echo [Status Disetujui - Should NOT Change]
echo [ ] Set status to 'disetujui'
echo [ ] Verify "Menunggu Persetujuan" message shown
echo [ ] Verify upload form is HIDDEN
echo.
echo ========================================
pause
