@echo off
echo ========================================
echo Upload Tombol Cetak Detail Pendaftar Admin
echo Tanggal: 15 Februari 2026
echo FIXED: Semua tombol buka di TAB BARU
echo ========================================
echo.

REM Upload file yang dimodifikasi
echo [1/6] Uploading blade view detail-pendaftar.blade.php...
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\detail-pendaftar.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/

echo [2/6] Uploading DetailPendaftar.php backend (cleaned)...
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Admin\DetailPendaftar.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/

echo [3/6] Uploading PreviewKartuTes.php component...
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Admin\PreviewKartuTes.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/

echo [4/6] Uploading PreviewInvoiceController.php (NEW)...
scp "C:\ppdbmtsn2km-laravel\app\Http\Controllers\Admin\PreviewInvoiceController.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Http/Controllers/Admin/

echo [5/7] Uploading print.blade.php layout (NEW)...
scp "C:\ppdbmtsn2km-laravel\resources\views\components\layouts\print.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/components/layouts/

echo [6/7] Uploading kartu-tes-seleksi.blade.php view (support preview mode)...
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\pendaftar\kartu-tes-seleksi.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/

echo [7/7] Uploading web.php routes...
scp "C:\ppdbmtsn2km-laravel\routes\web.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/routes/

echo.
echo ========================================
echo Clear cache production...
echo ========================================
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"

echo.
echo ========================================
echo Upload Complete!
echo ========================================
echo.
echo File yang di-upload (7 files):
echo - resources/views/livewire/admin/detail-pendaftar.blade.php (Link dengan target=_blank)
echo - app/Livewire/Admin/DetailPendaftar.php (Cleaned - method dihapus)
echo - app/Livewire/Admin/PreviewKartuTes.php (NEW - isPreviewMode flag)
echo - app/Http/Controllers/Admin/PreviewInvoiceController.php (NEW)
echo - resources/views/components/layouts/print.blade.php (NEW - Layout clean)
echo - resources/views/livewire/pendaftar/kartu-tes-seleksi.blade.php (Support preview mode)
echo - routes/web.php (4 route baru)
echo.
echo PERUBAHAN PENTING:
echo - Semua tombol sekarang menggunakan link a href dengan target=_blank
echo - Tombol akan MEMBUKA DI TAB BARU (bukan download langsung)
echo - Invoice menggunakan Controller terpisah (bukan Livewire method)
echo - Preview Kartu Tes CLEAN (tanpa wrapper, judul, tombol - hanya kartu saja)
echo - Menggunakan layout print khusus (tanpa sidebar/header)
echo - Code lebih clean dan terstruktur
echo.
echo Silakan test di production:
echo 1. Login sebagai admin
echo 2. Buka /admin/pendaftar/348 (atau ID lain)
echo 3. Scroll ke section "Cetak Dokumen"
echo 4. Test semua tombol (harusnya buka di TAB BARU):
echo    - Kartu Tes (biru) - Tab baru CLEAN (hanya kartu, no wrapper/tombol)
echo    - Invoice Psikologi (ungu) - Tab baru PDF
echo    - Invoice Madrasah (hijau) - Tab baru PDF
echo    - Invoice Seragam (orange) - Tab baru PDF
echo.
echo PENTING - Verifikasi Kartu Tes:
echo - Harusnya TIDAK ada judul "Kartu Tanda Peserta Tes Seleksi"
echo - Harusnya TIDAK ada tombol "Cetak Kartu" dan "Bergabung ke Grup"
echo - Harusnya TIDAK ada wrapper card (shadow/rounded)
echo - Harusnya langsung tampil kartu saja seperti tampilan invoice PDF
echo.
pause
