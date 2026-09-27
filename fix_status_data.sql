-- =============================================
-- FIX STATUS DATA SEBELUM MIGRATION
-- =============================================
-- Jalankan script ini DI DATABASE PRODUCTION
-- untuk memperbaiki data status yang tidak valid
-- =============================================

-- Step 1: Update semua NULL atau empty string ke 'belum_diisi'
UPDATE daftar_ulang 
SET status = 'belum_diisi' 
WHERE status IS NULL OR status = '';

UPDATE seragam 
SET status_seragam = 'belum_diisi' 
WHERE status_seragam IS NULL OR status_seragam = '';

UPDATE seragam 
SET status_ukuran = 'belum_diisi' 
WHERE status_ukuran IS NULL OR status_ukuran = '';

-- Step 2: Update status yang tidak valid ke 'belum_diisi'
-- Cek status yang ada di daftar_ulang
UPDATE daftar_ulang 
SET status = 'belum_diisi' 
WHERE status NOT IN ('belum_diisi', 'menunggu', 'disetujui', 'ditolak', 'perbaiki', 'proses');

-- Cek status yang ada di seragam (status_seragam)
UPDATE seragam 
SET status_seragam = 'belum_diisi' 
WHERE status_seragam NOT IN ('belum_diisi', 'menunggu', 'disetujui', 'ditolak', 'perbaiki');

-- Cek status yang ada di seragam (status_ukuran)
UPDATE seragam 
SET status_ukuran = 'belum_diisi' 
WHERE status_ukuran NOT IN ('belum_diisi', 'menunggu', 'disetujui', 'ditolak', 'perbaiki');

-- Step 3: Verifikasi data sudah benar
-- Seharusnya tidak ada hasil dari query ini
SELECT id, status FROM daftar_ulang 
WHERE status NOT IN ('belum_diisi', 'menunggu', 'disetujui', 'ditolak', 'perbaiki', 'proses');

SELECT id, status_seragam FROM seragam 
WHERE status_seragam NOT IN ('belum_diisi', 'menunggu', 'disetujui', 'ditolak', 'perbaiki');

SELECT id, status_ukuran FROM seragam 
WHERE status_ukuran NOT IN ('belum_diisi', 'menunggu', 'disetujui', 'ditolak', 'perbaiki');

-- Step 4: Jika semua sudah bersih (tidak ada hasil dari Step 3), jalankan migration
-- php artisan migrate
