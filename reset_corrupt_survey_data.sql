-- ============================================================
-- RESET CORRUPT SURVEY DATA
-- ============================================================
-- Date: 5 Februari 2026
--
-- Problem: 19 pendaftar punya data corrupt (semua jawaban jadi ["126"] atau ["127"])
-- Cause: Submit dengan form lama/buggy sebelum fix terupload
-- Solution: Reset data corrupt, suruh pendaftar isi ulang dengan form yang benar
-- ============================================================

-- STEP 1: BACKUP DATA CORRUPT (IMPORTANT!)
CREATE TABLE survey_answers_backup_corrupt_optionid_20260205 AS
SELECT sa.*
FROM survey_answers sa
WHERE sa.pendaftar_id IN (
    -- Data corrupt dengan jawaban ["126"] (13 pendaftar)
    200, 54, 198, 195, 180, 172, 13, 142, 229, 224, 222, 235, 196,
    -- Data corrupt dengan jawaban ["127"] (4 pendaftar)
    165, 141, 225, 97,
    -- Data corrupt dengan jawaban lain (2 pendaftar)
    42  -- ["95"]
    -- ID 196 sudah masuk di atas (punya 2 jenis corrupt: ["126"] dan ["66"])
)
AND sa.survey_question_id IN (
    SELECT id FROM survey_questions WHERE context = 'tes_psikologi'
);

-- STEP 2: VERIFY BACKUP
SELECT
    COUNT(*) as total_records_backup,
    COUNT(DISTINCT pendaftar_id) as total_pendaftar_backup
FROM survey_answers_backup_corrupt_optionid_20260205;

-- Expected: ~513 records (19 pendaftar x 27 pertanyaan rata-rata)

-- STEP 3: DELETE DATA CORRUPT
DELETE FROM survey_answers
WHERE pendaftar_id IN (
    200, 54, 198, 195, 180, 172, 13, 142, 229, 224, 222, 235, 196,
    165, 141, 225, 97, 42
)
AND survey_question_id IN (
    SELECT id FROM survey_questions WHERE context = 'tes_psikologi'
);

-- STEP 4: VERIFY DELETION
SELECT
    pendaftar_id,
    COUNT(*) as remaining_answers
FROM survey_answers
WHERE pendaftar_id IN (
    200, 54, 198, 195, 180, 172, 13, 142, 229, 224, 222, 235, 196,
    165, 141, 225, 97, 42
)
AND survey_question_id IN (
    SELECT id FROM survey_questions WHERE context = 'tes_psikologi'
)
GROUP BY pendaftar_id;

-- Expected: 0 rows (semua sudah terhapus)

-- ============================================================
-- CATATAN UNTUK ADMIN
-- ============================================================
-- Setelah menjalankan script ini:
--
-- 1. ✅ Data corrupt sudah dihapus
-- 2. ✅ Backup tersimpan di tabel: survey_answers_backup_corrupt_optionid_20260205
-- 3. ⚠️  19 Pendaftar harus isi ulang survey tes psikologi
-- 4. ✅ Form sudah diperbaiki (save Option ID), data baru akan benar
--
-- List Pendaftar yang Perlu Isi Ulang:
-- - ID: 200, 54, 198, 195, 180 (corrupt ["126"])
-- - ID: 172, 13, 142, 229, 224 (corrupt ["126"])
-- - ID: 222, 235, 196 (corrupt ["126"])
-- - ID: 165, 141, 225, 97 (corrupt ["127"])
-- - ID: 42 (corrupt ["95"])
--
-- Cara Notifikasi Pendaftar:
-- Bisa kirim email/whatsapp atau tampilkan alert di dashboard mereka
-- bahwa survey mereka perlu diisi ulang.
-- ============================================================
