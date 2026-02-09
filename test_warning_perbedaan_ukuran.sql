-- Test Warning Perbedaan Ukuran di Tab Seragam
-- Tanggal: 8 Februari 2026

-- ============================================================
-- PENTING: Warning HANYA muncul jika ukuran baju ≠ ukuran celana
-- ============================================================

-- 1. Check data seragam user yang sedang login
SELECT
    s.id,
    s.pendaftar_id,
    s.ukuran_baju_harian,
    s.ukuran_celana_rok_harian,
    u.email,
    u.name
FROM seragam s
JOIN pendaftar p ON s.pendaftar_id = p.id
JOIN users u ON p.user_id = u.id
ORDER BY s.updated_at DESC
LIMIT 10;

-- ============================================================
-- TEST 1: Ukuran BERBEDA → Warning HARUS MUNCUL ✅
-- ============================================================
-- GANTI 'your-email@example.com' dengan email Anda!

UPDATE seragam
SET
    ukuran_baju_harian = 'M',
    ukuran_celana_rok_harian = 'L'
WHERE pendaftar_id IN (
    SELECT p.id FROM pendaftar p
    JOIN users u ON p.user_id = u.id
    WHERE u.email = 'your-email@example.com'
);

-- Verify
SELECT
    ukuran_baju_harian,
    ukuran_celana_rok_harian,
    u.email
FROM seragam s
JOIN pendaftar p ON s.pendaftar_id = p.id
JOIN users u ON p.user_id = u.id
WHERE u.email = 'your-email@example.com';

-- EXPECTED RESULT di browser:
-- ✅ ORANGE warning box muncul di atas Step 1
-- ✅ Text: "Perhatian: Perbedaan Ukuran Seragam Harian Terdeteksi!"
-- ✅ Menampilkan: Baju (M) dan Celana (L)
-- ✅ Ukuran untuk pembayaran: L (yang lebih besar)

-- ============================================================
-- TEST 2: Ukuran SAMA → Warning TIDAK BOLEH MUNCUL ❌
-- ============================================================

UPDATE seragam
SET
    ukuran_baju_harian = 'L',
    ukuran_celana_rok_harian = 'L'
WHERE pendaftar_id IN (
    SELECT p.id FROM pendaftar p
    JOIN users u ON p.user_id = u.id
    WHERE u.email = 'your-email@example.com'
);

-- Verify
SELECT
    ukuran_baju_harian,
    ukuran_celana_rok_harian,
    u.email
FROM seragam s
JOIN pendaftar p ON s.pendaftar_id = p.id
JOIN users u ON p.user_id = u.id
WHERE u.email = 'your-email@example.com';

-- EXPECTED RESULT di browser:
-- ❌ TIDAK ADA warning box
-- ✅ Form langsung mulai dari Step 1
-- ✅ Tidak ada pesan perbedaan ukuran

-- ============================================================
-- TEST 3: Ukuran Berbeda dengan Urutan Terbalik (Baju > Celana)
-- ============================================================

UPDATE seragam
SET
    ukuran_baju_harian = 'XL',
    ukuran_celana_rok_harian = 'M'
WHERE pendaftar_id IN (
    SELECT p.id FROM pendaftar p
    JOIN users u ON p.user_id = u.id
    WHERE u.email = 'your-email@example.com'
);

-- EXPECTED RESULT di browser:
-- ✅ ORANGE warning box muncul
-- ✅ Menampilkan: Baju (XL) dan Celana (M)
-- ✅ Ukuran untuk pembayaran: XL (yang lebih besar)

-- ============================================================
-- TEST 4: Belum Ada Data Ukuran → Warning TIDAK MUNCUL ❌
-- ============================================================

UPDATE seragam
SET
    ukuran_baju_harian = NULL,
    ukuran_celana_rok_harian = NULL
WHERE pendaftar_id IN (
    SELECT p.id FROM pendaftar p
    JOIN users u ON p.user_id = u.id
    WHERE u.email = 'your-email@example.com'
);

-- EXPECTED RESULT di browser:
-- ❌ TIDAK ADA warning box
-- ✅ Form normal

-- ============================================================
-- TEST 5: Hanya Salah Satu Terisi → Warning TIDAK MUNCUL ❌
-- ============================================================

-- Case A: Hanya baju terisi
UPDATE seragam
SET
    ukuran_baju_harian = 'M',
    ukuran_celana_rok_harian = NULL
WHERE pendaftar_id IN (
    SELECT p.id FROM pendaftar p
    JOIN users u ON p.user_id = u.id
    WHERE u.email = 'your-email@example.com'
);

-- EXPECTED: TIDAK ADA warning (belum lengkap)

-- Case B: Hanya celana terisi
UPDATE seragam
SET
    ukuran_baju_harian = NULL,
    ukuran_celana_rok_harian = 'L'
WHERE pendaftar_id IN (
    SELECT p.id FROM pendaftar p
    JOIN users u ON p.user_id = u.id
    WHERE u.email = 'your-email@example.com'
);

-- EXPECTED: TIDAK ADA warning (belum lengkap)

-- ============================================================
-- RESET ke Normal (jika sudah selesai test)
-- ============================================================
UPDATE seragam
SET
    ukuran_baju_harian = 'M',
    ukuran_celana_rok_harian = 'M'
WHERE pendaftar_id IN (
    SELECT p.id FROM pendaftar p
    JOIN users u ON p.user_id = u.id
    WHERE u.email = 'your-email@example.com'
);

-- ============================================================
-- QUICK CHECK: Lihat semua kombinasi ukuran
-- ============================================================
SELECT
    ukuran_baju_harian,
    ukuran_celana_rok_harian,
    COUNT(*) as jumlah,
    CASE
        WHEN ukuran_baju_harian IS NULL OR ukuran_celana_rok_harian IS NULL THEN 'Belum lengkap'
        WHEN ukuran_baju_harian = ukuran_celana_rok_harian THEN 'Sama (No warning)'
        ELSE 'Berbeda (Warning!)'
    END as status_warning
FROM seragam
WHERE ukuran_baju_harian IS NOT NULL OR ukuran_celana_rok_harian IS NOT NULL
GROUP BY ukuran_baju_harian, ukuran_celana_rok_harian
ORDER BY status_warning DESC;

-- ============================================================
-- CREATE TEST DATA (jika belum ada data seragam)
-- ============================================================
INSERT INTO seragam (
    pendaftar_id,
    ukuran_baju_harian,
    ukuran_celana_rok_harian,
    created_at,
    updated_at
)
SELECT
    p.id,
    'M',  -- Baju M
    'L',  -- Celana L (BERBEDA → Warning muncul!)
    NOW(),
    NOW()
FROM pendaftar p
JOIN users u ON p.user_id = u.id
WHERE u.email = 'your-email@example.com'
AND NOT EXISTS (
    SELECT 1 FROM seragam s WHERE s.pendaftar_id = p.id
)
LIMIT 1;

-- ============================================================
-- CATATAN PENTING
-- ============================================================
-- Warning HANYA muncul jika:
-- 1. ✅ ukuran_baju_harian IS NOT NULL
-- 2. ✅ ukuran_celana_rok_harian IS NOT NULL
-- 3. ✅ ukuran_baju_harian != ukuran_celana_rok_harian
--
-- Warning TIDAK muncul jika:
-- - ❌ Salah satu atau kedua ukuran NULL
-- - ❌ Ukuran baju = Ukuran celana (sama)
-- - ❌ Belum pernah isi data ukuran
--
-- Ukuran standar untuk perbandingan:
-- XS < S < M < L < XL < XXL < XXXL
--
-- Yang ditampilkan di warning:
-- - Ukuran baju dan celana yang dipilih
-- - Ukuran TERBESAR untuk pembayaran
