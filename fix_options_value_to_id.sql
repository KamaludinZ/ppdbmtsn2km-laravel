-- ============================================================
-- FIX OPTIONS VALUE - Update VALUE = OPTION ID
-- ============================================================
-- Date: 4 Februari 2026
--
-- Problem: OPTIONS VALUE menggunakan LABEL yang bisa duplikat
--   Q8 Option ID:29 → value="Sangat Tidak Setuju"
--   Q9 Option ID:33 → value="Sangat Tidak Setuju" (VALUE SAMA!)
--   Q10 Option ID:37 → value="Sangat Tidak Setuju" (VALUE SAMA!)
--
-- Solution: Update VALUE = OPTION ID (unique)
--   Q8 Option ID:29 → value="29"
--   Q9 Option ID:33 → value="33"
--   Q10 Option ID:37 → value="37"
-- ============================================================

-- BACKUP DULU (IMPORTANT!)
-- CREATE TABLE survey_question_options_backup AS SELECT * FROM survey_question_options;

-- UPDATE OPTIONS VALUE = OPTION ID untuk semua survey tes psikologi
UPDATE survey_question_options
SET value = CAST(id AS VARCHAR)
WHERE survey_question_id IN (
    SELECT id FROM survey_questions WHERE context = 'tes_psikologi'
);

-- Verify hasil update
SELECT
    sqo.id as option_id,
    sqo.survey_question_id as question_id,
    sq.pertanyaan,
    sqo.label,
    sqo.value,
    CASE
        WHEN sqo.value = CAST(sqo.id AS VARCHAR) THEN '✓ CORRECT'
        ELSE '✗ NEEDS FIX'
    END as status
FROM survey_question_options sqo
JOIN survey_questions sq ON sqo.survey_question_id = sq.id
WHERE sq.context = 'tes_psikologi'
ORDER BY sq.urutan, sqo.urutan
LIMIT 50;

-- Expected result:
-- | option_id | question_id | label                  | value | status    |
-- |-----------|-------------|------------------------|-------|-----------|
-- | 28        | 1           | Ya                     | 28    | ✓ CORRECT |
-- | 29        | 8           | Sangat Tidak Setuju    | 29    | ✓ CORRECT |
-- | 30        | 8           | Tidak Setuju           | 30    | ✓ CORRECT |
-- | 31        | 8           | Setuju                 | 31    | ✓ CORRECT |
-- | 32        | 8           | Sangat Setuju          | 32    | ✓ CORRECT |
-- | 33        | 9           | Sangat Tidak Setuju    | 33    | ✓ CORRECT |
-- | 34        | 9           | Tidak Setuju           | 34    | ✓ CORRECT |
