@echo off
echo ============================================
echo FIX SURAT PERNYATAAN - FILE NAMING DAN 404
echo ============================================
echo.
echo PERUBAHAN YANG AKAN DI-UPLOAD:
echo [1] File naming sistematis untuk surat pernyataan
echo [2] Fix error 404 saat klik tombol "Lihat"
echo [3] Change storage dari public ke private
echo [4] Konsistensi dengan berkas upload lainnya
echo.
echo DETAIL PERBAIKAN:
echo =================
echo.
echo BEFORE:
echo   File name: random hash (h7f8g9a2b3c4d5e6.pdf)
echo   Storage: public (tidak aman)
echo   Path: surat-pernyataan/hash.pdf
echo   Error: 404 saat klik "Lihat"
echo.
echo AFTER:
echo   File name: surat_pernyataan-PPDBXXXX-Nama_Pendaftar-timestamp.pdf
echo   Storage: private (aman)
echo   Path: dokumen/{pendaftar_id}/surat_pernyataan-xxx.pdf
echo   Error: FIXED - no more 404
echo.
echo FILES YANG AKAN DI-UPLOAD:
echo - app/Livewire/Pendaftar/DaftarUlang.php
echo.
pause

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
echo PERBAIKAN YANG TELAH DITERAPKAN:
echo ==================================
echo.
echo 1. FILE NAMING SISTEMATIS
echo    -----------------------
echo    Format baru: surat_pernyataan-{nomor}-{nama}-{timestamp}.pdf
echo    Contoh: surat_pernyataan-PPDB2026001-Ahmad_Fulan-20260207_143052.pdf
echo.
echo    Benefits:
echo    [x] Mudah dicari dan diidentifikasi
echo    [x] Include nomor pendaftaran
echo    [x] Include nama pendaftar
echo    [x] Timestamp untuk tracking
echo    [x] Konsisten dengan berkas lain
echo.
echo 2. STORAGE DISK: PUBLIC -^> PRIVATE
echo    ================================
echo    Old: storage/app/public/surat-pernyataan/hash.pdf
echo    New: storage/app/private/dokumen/123/surat_pernyataan-xxx.pdf
echo.
echo    Benefits:
echo    [x] File lebih aman (tidak bisa diakses langsung)
echo    [x] Authorization check via route
echo    [x] Hanya bisa akses file milik sendiri
echo    [x] Konsisten dengan bukti pembayaran lainnya
echo.
echo 3. FIX ERROR 404
echo    =============
echo    Problem: Route mencari di disk 'private', tapi file ada di disk 'public'
echo    Solution: Semua file sekarang di disk 'private'
echo    Result: NO MORE 404 ERROR!
echo.
echo ============================================
echo TESTING CHECKLIST
echo ============================================
echo.
echo TEST 1: Upload Surat Pernyataan Baru
echo ======================================
echo 1. Login sebagai Pendaftar (status: lulus)
echo 2. Buka /pendaftar/daftarulang?activeTab=madrasah
echo 3. Upload file PDF sebagai surat pernyataan
echo 4. Klik "Simpan Surat Pernyataan"
echo 5. VERIFY:
echo    [x] Success message: "Surat pernyataan berhasil disimpan!"
echo    [x] Green badge: "Surat pernyataan telah diupload [Lihat]"
echo    [x] Form berikutnya (Pilih Nominal, Upload Bukti) muncul
echo    [x] Upload area hidden
echo.
echo TEST 2: Klik "Lihat" Surat Pernyataan
echo =======================================
echo 1. Setelah upload berhasil
echo 2. Klik tombol "Lihat" pada green badge
echo 3. VERIFY:
echo    [x] File PDF terbuka di tab baru
echo    [x] **NO ERROR 404** (CRITICAL!)
echo    [x] PDF dapat dilihat dengan baik (inline preview)
echo    [x] URL format: /pendaftar/daftarulang/view-file/pernyataan/filename.pdf
echo.
echo TEST 3: Check File di Server
echo =============================
echo Run command di server:
echo.
echo ssh mtsnkot4@103.30.244.77
echo cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
echo ls -la storage/app/private/dokumen/{pendaftar_id}/
echo.
echo VERIFY:
echo [x] File ada dengan format: surat_pernyataan-PPDBXXXX-Nama-timestamp.pdf
echo [x] Timestamp sesuai dengan waktu upload
echo [x] Nama file sesuai dengan nomor pendaftaran dan nama pendaftar
echo.
echo TEST 4: Check Database
echo ======================
echo Run SQL query:
echo.
echo SELECT id, pendaftar_id, surat_pernyataan_path, created_at, updated_at
echo FROM daftar_ulang
echo WHERE pendaftar_id = {test_pendaftar_id}
echo ORDER BY updated_at DESC
echo LIMIT 1;
echo.
echo VERIFY:
echo [x] Column surat_pernyataan_path berisi path dengan format baru
echo [x] Path: dokumen/{pendaftar_id}/surat_pernyataan-XXX.pdf
echo [x] NOT: surat-pernyataan/hash.pdf (old format)
echo.
echo TEST 5: Re-upload Test
echo ======================
echo 1. Upload surat pernyataan baru (file berbeda)
echo 2. Klik "Simpan Surat Pernyataan"
echo 3. VERIFY:
echo    [x] Success message muncul
echo    [x] File baru tersimpan
echo    [x] Klik "Lihat" menampilkan file BARU (bukan file lama)
echo.
echo 4. Check di server:
echo    ssh: find storage/app/private/dokumen/{id}/ -name "surat_pernyataan-*" ^| wc -l
echo    Expected: 1 (hanya file baru, file lama sudah terhapus)
echo.
echo TEST 6: Konsistensi Penamaan
echo ==============================
echo Compare file naming dengan berkas lain:
echo.
echo Bukti Madrasah:
echo bukti_bayar/123/bukti_daftar_ulang-PPDB2026001-Ahmad_Fulan-20260207_143052.pdf
echo.
echo Surat Pernyataan (NEW):
echo dokumen/123/surat_pernyataan-PPDB2026001-Ahmad_Fulan-20260207_143052.pdf
echo.
echo Bukti Seragam:
echo bukti_bayar/123/bukti_seragam-PPDB2026001-Ahmad_Fulan-20260207_150230.pdf
echo.
echo VERIFY:
echo [x] Format konsisten (jenis-nomor-nama-timestamp.ext)
echo [x] Semua di private storage
echo [x] Include nomor pendaftaran dan nama
echo.
echo ============================================
echo SECURITY IMPROVEMENTS
echo ============================================
echo.
echo BEFORE (OLD):
echo -------------
echo File location: storage/app/public/surat-pernyataan/hash.pdf
echo Direct access: https://domain.com/storage/surat-pernyataan/hash.pdf
echo Security: ❌ Anyone dapat akses jika tahu URL
echo.
echo AFTER (NEW):
echo ------------
echo File location: storage/app/private/dokumen/123/surat_pernyataan-XXX.pdf
echo Access via: https://domain.com/pendaftar/daftarulang/view-file/pernyataan/filename
echo Security:
echo   [x] Harus login
echo   [x] Authorization check (hanya bisa akses file sendiri)
echo   [x] Route protected dengan middleware auth
echo   [x] File tidak bisa diakses langsung
echo.
echo ============================================
echo MIGRATION NOTE
echo ============================================
echo.
echo FILE LAMA YANG SUDAH ADA:
echo -------------------------
echo Jika ada file existing dengan format lama:
echo - File lama akan otomatis terhapus saat pendaftar re-upload
echo - Tidak perlu manual migration
echo - File lama di public storage bisa dibersihkan manual
echo.
echo OPTIONAL CLEANUP:
echo -----------------
echo Untuk hapus file lama di public storage (jika ada):
echo.
echo ssh mtsnkot4@103.30.244.77
echo cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
echo find storage/app/public/surat-pernyataan/ -type f -mtime +7 -delete
echo # ^(hapus file yang lebih dari 7 hari^)
echo.
echo ============================================
echo TROUBLESHOOTING
echo ============================================
echo.
echo PROBLEM: Error 404 masih muncul
echo SOLUTION:
echo   1. Clear cache: php artisan optimize:clear
echo   2. Check symlink: php artisan storage:link
echo   3. Verify file location: ls -la storage/app/private/dokumen/
echo.
echo PROBLEM: File tidak tersimpan
echo SOLUTION:
echo   1. Check permissions: chmod -R 775 storage/
echo   2. Check disk config di config/filesystems.php
echo   3. Check logs: tail -f storage/logs/laravel.log
echo.
echo PROBLEM: Nama file terlalu panjang atau aneh
echo SOLUTION:
echo   - FileNamingService auto-clean special characters
echo   - Nama pendaftar di-limit 50 karakter
echo   - Spasi diganti underscore
echo   - Karakter spesial dihapus
echo   - This is expected behavior
echo.
echo ============================================
echo BENEFITS SUMMARY
echo ============================================
echo.
echo FOR USERS:
echo ----------
echo [x] File upload lebih aman (private storage)
echo [x] No more 404 error saat klik "Lihat"
echo [x] Konsisten dengan berkas upload lainnya
echo.
echo FOR ADMINS:
echo -----------
echo [x] File mudah dicari di server (nama sistematis)
echo [x] Bisa identify file by nomor pendaftaran
echo [x] Timestamp memudahkan tracking
echo [x] Organized per pendaftar folder
echo.
echo FOR DEVELOPERS:
echo ---------------
echo [x] Konsisten dengan FileNamingService pattern
echo [x] Easier maintenance
echo [x] Better security implementation
echo [x] Follow Laravel best practices
echo.
echo ============================================
echo QUICK REFERENCE
echo ============================================
echo.
echo Documentation: FIX_SURAT_PERNYATAAN_FILE_NAMING_404_7_FEB_2026.md
echo Related Feature: FIX_SURAT_PERNYATAAN_REQUIRED_FIRST_7_FEB_2026.md
echo Service Used: app/Services/FileNamingService.php
echo Route: routes/web.php (line 538-598)
echo.
pause
