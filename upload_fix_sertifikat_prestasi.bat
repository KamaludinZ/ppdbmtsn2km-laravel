@echo off
echo ========================================
echo Upload Fix Sertifikat Prestasi - COMPLETE
echo ========================================
echo.
echo PERBAIKAN YANG SUDAH DILAKUKAN:
echo.
echo [1] Upload sertifikat prestasi WAJIB untuk prestasi baru
echo [2] File upload berhasil disimpan ke storage dengan naming standard
echo [3] Tampilan upload lebih jelas (hijau = sudah, kuning = belum)
echo [4] Auto delete file lama saat update atau hapus prestasi
echo.

set SERVER=mtsnkot4@103.30.244.77
set REMOTE_PATH=/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

echo [1/4] Upload Admin DetailPendaftar Controller...
scp "app/Livewire/Admin/DetailPendaftar.php" %SERVER%:%REMOTE_PATH%/app/Livewire/Admin/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload Admin controller
    pause
    exit /b 1
)
echo [OK] Admin controller uploaded

echo.
echo [2/4] Upload Tab Prestasi View...
scp "resources/views/tab_templates/tab-prestasi.blade.php" %SERVER%:%REMOTE_PATH%/resources/views/tab_templates/
if %errorlevel% neq 0 (
    echo ERROR: Gagal upload view
    pause
    exit /b 1
)
echo [OK] Tab prestasi view uploaded

echo.
echo [3/4] Clear ALL caches...
ssh %SERVER% "cd %REMOTE_PATH% && php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear && php artisan optimize:clear"
if %errorlevel% neq 0 (
    echo ERROR: Gagal clear cache
    pause
    exit /b 1
)
echo [OK] All caches cleared

echo.
echo [4/4] Delete compiled views...
ssh %SERVER% "cd %REMOTE_PATH% && rm -rf storage/framework/views/*.php"
echo [OK] Compiled views deleted

echo.
echo ========================================
echo UPLOAD BERHASIL!
echo ========================================
echo.
echo YANG SUDAH DIPERBAIKI:
echo.
echo 1. UPLOAD WAJIB:
echo    ✓ Sertifikat prestasi WAJIB untuk prestasi baru
echo    ✓ Validasi: PDF/JPG/PNG max 2MB
echo    ✓ Error message jelas jika tidak upload
echo.
echo 2. FILE TERSIMPAN:
echo    ✓ File tersimpan ke storage/app/public/sertifikat_prestasi/
echo    ✓ Naming format: sertifikat_prestasi_{nomor}_{timestamp}_{nama}.ext
echo    ✓ File lama otomatis dihapus saat update/delete
echo.
echo 3. TAMPILAN JELAS:
echo    ✓ Edit Mode: Box hijau "Sudah Upload" dengan tombol Lihat
echo    ✓ Edit Mode: Box kuning "Belum Upload" dengan warning
echo    ✓ View Mode: Badge hijau "Tersedia" atau merah "Belum Upload"
echo    ✓ View Mode: Tombol "Lihat" untuk download sertifikat
echo.
echo ========================================
echo TESTING CHECKLIST
echo ========================================
echo.
echo URL: https://pmbm.mtsn2kotamalang.sch.id/admin/pendaftar/27?activeTab=prestasi
echo.
echo TEST 1 - UPLOAD WAJIB:
echo [ ] Klik "Edit Data" → "Tambah Prestasi"
echo [ ] Isi semua field KECUALI upload sertifikat
echo [ ] Klik "Simpan"
echo [ ] EXPECTED: Error muncul - upload wajib
echo.
echo TEST 2 - UPLOAD BERHASIL:
echo [ ] Upload sertifikat (PDF/JPG) → Klik "Simpan"
echo [ ] EXPECTED: Notif berhasil + keluar edit mode
echo [ ] EXPECTED: Di tabel muncul badge "Tersedia" + tombol "Lihat"
echo [ ] Klik tombol "Lihat" → EXPECTED: File terbuka di tab baru
echo.
echo TEST 3 - VISUAL SUDAH UPLOAD:
echo [ ] Klik "Edit Data" pada prestasi existing
echo [ ] EXPECTED: Box hijau "Sertifikat Sudah Diupload" tampil
echo [ ] EXPECTED: Nama file ditampilkan
echo [ ] EXPECTED: Tombol "Lihat" berfungsi
echo.
echo TEST 4 - VISUAL BELUM UPLOAD:
echo [ ] Prestasi tanpa sertifikat di view mode
echo [ ] EXPECTED: Badge merah "Belum Upload" tampil
echo [ ] Klik "Edit Data"
echo [ ] EXPECTED: Box kuning warning tampil
echo.
echo TEST 5 - UPDATE SERTIFIKAT:
echo [ ] Edit prestasi existing → Upload sertifikat baru
echo [ ] Klik "Simpan"
echo [ ] EXPECTED: File lama terhapus, file baru tersimpan
echo [ ] EXPECTED: Link "Lihat" mengarah ke file baru
echo.
echo TEST 6 - DELETE PRESTASI:
echo [ ] Hapus prestasi yang punya sertifikat
echo [ ] EXPECTED: File juga terhapus dari server
echo.
echo Hard refresh browser: Ctrl + Shift + R
echo.
pause
