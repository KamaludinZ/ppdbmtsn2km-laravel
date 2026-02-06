-- FIX SURVEY ANSWERS - Remove Quotes from Numeric IDs
-- Problem: Answers stored as "21" instead of 21
-- Solution: Convert string numbers to integers

-- This is for PostgreSQL JSONB

-- WARNING: Test this on backup first!
-- This script will convert all numeric strings to integers

-- Example: "21" → 21, "126" → 126

-- IMPORTANT: Run this with caution!

-- For PostgreSQL, we need to update JSONB values
-- If jawaban is stored as text '21' (with quotes), convert to integer

-- Step 1: Check current data (before fix)
SELECT
    id,
    survey_question_id,
    jawaban,
    pg_typeof(jawaban) as type
FROM survey_answers
WHERE jawaban::text LIKE '"%"'  -- Strings with quotes
LIMIT 20;

-- Step 2: Show count of affected rows
SELECT COUNT(*) as affected_rows
FROM survey_answers
WHERE jawaban::text ~ '^\d+$'  -- Pure numeric string
   OR jawaban::text ~ '^"\d+"$';  -- Quoted numeric string

-- Step 3: FIX - Convert numeric strings to integers
-- For single value answers (radio/select)
UPDATE survey_answers
SET jawaban = (jawaban::text::integer)::jsonb
WHERE jawaban::text ~ '^\d+$';  -- Pure digits only

-- Step 4: FIX - Remove quotes from numeric strings
UPDATE survey_answers
SET jawaban = (TRIM(BOTH '"' FROM jawaban::text)::integer)::jsonb
WHERE jawaban::text ~ '^"\d+"$';  -- "123" format

-- Step 5: Verify fix
SELECT
    id,
    survey_question_id,
    jawaban,
    pg_typeof(jawaban) as type
FROM survey_answers
LIMIT 20;

-- Step 6: Check if any string numbers remain
SELECT COUNT(*) as remaining_string_numbers
FROM survey_answers
WHERE jawaban::text ~ '^"\d+"$';

-- Expected: 0 (no more quoted numbers)
