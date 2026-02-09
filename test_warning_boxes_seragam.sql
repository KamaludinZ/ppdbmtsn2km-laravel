-- Test Warning Boxes di Tab Seragam
-- Tanggal: 8 Februari 2026

-- ============================================================
-- PENTING: Warning boxes HANYA muncul jika ada status tertentu
-- ============================================================

-- 1. Check data seragam user yang sedang login
SELECT
    s.id,
    s.pendaftar_id,
    s.status_seragam,
    u.email,
    u.name
FROM seragam s
JOIN pendaftar p ON s.pendaftar_id = p.id
JOIN users u ON p.user_id = u.id
ORDER BY s.updated_at DESC
LIMIT 10;

-- ============================================================
-- TEST 1: Warning Box HIJAU (Disetujui)
-- ============================================================
-- Ganti EMAIL_ANDA dengan email pendaftar yang akan ditest
UPDATE seragam
SET status_seragam = 'disetujui'
WHERE pendaftar_id IN (
    SELECT p.id FROM pendaftar p
    JOIN users u ON p.user_id = u.id
    WHERE u.email = 'EMAIL_ANDA'
);

-- Verify
SELECT
    s.status_seragam,
    u.email,
    u.name
FROM seragam s
JOIN pendaftar p ON s.pendaftar_id = p.id
JOIN users u ON p.user_id = u.id
WHERE u.email = 'EMAIL_ANDA';

-- EXPECTED RESULT di browser:
-- ✅ GREEN warning box muncul di atas Step 1
-- ✅ Text: "Data Daftar Ulang Seragam Sudah Disetujui"
-- ✅ Form fields ter-disable (tidak bisa diubah)

-- ============================================================
-- TEST 2: Warning Box KUNING (Menunggu)
-- ============================================================
-- Ganti EMAIL_ANDA dengan email pendaftar yang akan ditest
UPDATE seragam
SET status_seragam = 'menunggu'
WHERE pendaftar_id IN (
    SELECT p.id FROM pendaftar p
    JOIN users u ON p.user_id = u.id
    WHERE u.email = 'EMAIL_ANDA'
);

-- Verify
SELECT
    s.status_seragam,
    u.email,
    u.name
FROM seragam s
JOIN pendaftar p ON s.pendaftar_id = p.id
JOIN users u ON p.user_id = u.id
WHERE u.email = 'EMAIL_ANDA';

-- EXPECTED RESULT di browser:
-- ✅ YELLOW warning box muncul di atas Step 1
-- ✅ Text: "Data Daftar Ulang Seragam Sedang Menunggu Persetujuan"
-- ✅ Form fields ter-disable (tidak bisa diubah)

-- ============================================================
-- TEST 3: TIDAK ADA Warning (Normal)
-- ============================================================
-- Ganti EMAIL_ANDA dengan email pendaftar yang akan ditest
UPDATE seragam
SET status_seragam = NULL  -- atau 'ditolak' atau 'perbaiki'
WHERE pendaftar_id IN (
    SELECT p.id FROM pendaftar p
    JOIN users u ON p.user_id = u.id
    WHERE u.email = 'EMAIL_ANDA'
);

-- EXPECTED RESULT di browser:
-- ✅ TIDAK ADA warning box
-- ✅ Form bisa diisi normal

-- ============================================================
-- RESET ke Normal (jika sudah selesai test)
-- ============================================================
UPDATE seragam
SET status_seragam = NULL
WHERE pendaftar_id IN (
    SELECT p.id FROM pendaftar p
    JOIN users u ON p.user_id = u.id
    WHERE u.email = 'EMAIL_ANDA'
);

-- ============================================================
-- Quick Check: Lihat semua status yang ada
-- ============================================================
SELECT
    status_seragam,
    COUNT(*) as jumlah
FROM seragam
WHERE status_seragam IS NOT NULL
GROUP BY status_seragam;

-- ============================================================
-- CATATAN PENTING
-- ============================================================
-- Warning boxes HANYA muncul jika:
-- 1. status_seragam = 'disetujui' → GREEN box
-- 2. status_seragam = 'menunggu' → YELLOW box
--
-- Warning boxes TIDAK muncul jika:
-- - status_seragam = NULL (belum ada status)
-- - status_seragam = 'ditolak'
-- - status_seragam = 'perbaiki'
-- - Belum ada data seragam sama sekali
