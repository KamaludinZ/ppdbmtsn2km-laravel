# FIX SURVEY STRING IDs - Convert "21" to 21

**Problem**: Beberapa jawaban survey tersimpan sebagai string `"21"` bukan integer `21`
**Impact**: Display issue, filtering issue, data type inconsistency
**Solution**: Convert semua numeric strings ke integers

---

## 🎯 PROBLEM EXAMPLE

**Database saat ini**:
```sql
SELECT id, survey_question_id, jawaban FROM survey_answers LIMIT 10;

 id | survey_question_id | jawaban
----+--------------------+---------
  1 |                  1 | "133"    ← String (with quotes) ❌
  2 |                  2 | 129      ← Integer (correct) ✅
  3 |                  3 | "7"      ← String ❌
  4 |                  4 | 29       ← Integer ✅
```

**Yang diinginkan**:
```sql
 id | survey_question_id | jawaban
----+--------------------+---------
  1 |                  1 | 133      ← Integer ✅
  2 |                  2 | 129      ← Integer ✅
  3 |                  3 | 7        ← Integer ✅
  4 |                  4 | 29       ← Integer ✅
```

---

## ✅ SOLUSI (3 METHODS)

### **METHOD 1: Via PHP Script** (Recommended)

#### **Step 1: Upload Script ke Production**

Via cPanel File Manager:
- Upload: `fix_survey_string_to_integer.php`
- Ke: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/`

#### **Step 2: Run Script**

Via cPanel Terminal:
```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php fix_survey_string_to_integer.php
```

**Output Example**:
```
[1/4] Fetching all survey answers...
Found: 150 answers

[2/4] Analyzing answers...
  Answer ID 1 (Q1): "133" → 133 (convert to integer)
  Answer ID 3 (Q3): "7" → 7 (convert to integer)
  ...

Analysis Results:
  Total answers: 150
  Already correct (integer): 100
  Needs fix (string numbers): 50
  Skipped (text answers): 0

[3/4] Ready to fix 50 answers.
Continue? (yes/no):
```

Ketik: **yes** + Enter

```
[4/4] Fixing answers...
  ✓ Fixed Answer ID 1 (Q1)
  ✓ Fixed Answer ID 3 (Q3)
  ...

✅ Successfully fixed 50 answers!
```

#### **Step 3: Clear Cache**

```bash
php artisan view:clear
php artisan optimize:clear
```

---

### **METHOD 2: Via SQL Query** (Quick)

#### **Step 1: Check Affected Data**

Via phpPgAdmin:
```sql
-- See current string numbers
SELECT id, survey_question_id, jawaban
FROM survey_answers
WHERE jawaban::text ~ '^"\d+"$'  -- "123" format
LIMIT 20;
```

#### **Step 2: Count Affected Rows**

```sql
SELECT COUNT(*) as affected_rows
FROM survey_answers
WHERE jawaban::text ~ '^"\d+"$';
```

#### **Step 3: Fix - Remove Quotes & Convert to Integer**

```sql
-- BACKUP FIRST!
-- Create backup table
CREATE TABLE survey_answers_backup AS
SELECT * FROM survey_answers;

-- Fix: Remove quotes from "123" format
UPDATE survey_answers
SET jawaban = (TRIM(BOTH '"' FROM jawaban::text)::integer)::jsonb
WHERE jawaban::text ~ '^"\d+"$';
```

#### **Step 4: Verify Fix**

```sql
-- Check if any quoted numbers remain
SELECT COUNT(*) FROM survey_answers
WHERE jawaban::text ~ '^"\d+"$';
-- Expected: 0

-- Verify data looks correct
SELECT id, survey_question_id, jawaban
FROM survey_answers
LIMIT 20;
```

---

### **METHOD 3: Via Manual SQL (Step by Step)**

Jika Method 1 & 2 gagal, fix manual:

#### **Step 1: Identify Affected Records**

```sql
SELECT id, survey_question_id, jawaban
FROM survey_answers
WHERE jawaban::text LIKE '"%"'
ORDER BY id;
```

#### **Step 2: Fix One by One** (Small Dataset)

```sql
-- Example: Fix answer ID 1
UPDATE survey_answers
SET jawaban = 133  -- Remove quotes, just integer
WHERE id = 1;

-- Example: Fix answer ID 3
UPDATE survey_answers
SET jawaban = 7
WHERE id = 3;

-- Repeat for each affected row...
```

---

## 🔍 DETAILED SQL EXPLANATION

### **PostgreSQL JSONB Type**

PostgreSQL stores `jawaban` as JSONB type:
- `133` → Stored as JSONB number (correct)
- `"133"` → Stored as JSONB string (incorrect)

### **Regex Pattern**

- `^\d+$` → Pure digits: `123`
- `^"\d+"$` → Quoted digits: `"123"`

### **Conversion**

```sql
-- Remove quotes
TRIM(BOTH '"' FROM '"123"')  → '123'

-- Convert to integer
'123'::integer  → 123

-- Convert to JSONB
123::jsonb  → JSONB number
```

---

## ⚠️ IMPORTANT NOTES

### **1. Backup First!**

```sql
-- Create backup table before ANY update
CREATE TABLE survey_answers_backup AS
SELECT * FROM survey_answers;
```

### **2. Don't Convert Text Answers**

Script hanya convert **NUMERIC** strings:
- `"21"` → `21` ✅ Convert
- `"Ya"` → Keep as is ✅ Don't convert
- `"Text answer"` → Keep as is ✅ Don't convert

### **3. Handle Arrays (Checkbox)**

Untuk checkbox dengan multiple values:
```sql
-- Before:
jawaban = ["10", "15", "20"]

-- After:
jawaban = [10, 15, 20]
```

PHP script handles this automatically!

---

## 🧪 TESTING

### **Test 1: Check Data Types**

```sql
SELECT
    id,
    survey_question_id,
    jawaban,
    jsonb_typeof(jawaban) as type
FROM survey_answers
LIMIT 20;
```

**Expected**:
```
 id | jawaban | type
----+---------+--------
  1 | 133     | number  ← Correct!
  2 | 129     | number  ← Correct!
  3 | 7       | number  ← Correct!
```

**NOT**:
```
 id | jawaban | type
----+---------+--------
  1 | "133"   | string  ← Wrong!
```

---

### **Test 2: Check Admin Panel**

1. Login sebagai Admin
2. Buka: `/admin/tes-psikologi?mainTab=jawaban-survey`
3. Check RAW display untuk pendaftar

**Expected**:
```
RAW: 133 ✓ ID     ← Green (correct)
Ya
```

**Before Fix**:
```
RAW: "133" ⚠ VALUE/LABEL   ← Orange (string)
Ya
```

---

## 📊 SUCCESS CRITERIA

Fix berhasil jika:

1. ✅ Database: Semua `jawaban` numeric adalah **integer** (bukan string)
2. ✅ Query: `SELECT COUNT(*) FROM survey_answers WHERE jawaban::text ~ '^"\d+"$'` returns **0**
3. ✅ Admin panel: Semua RAW menunjukkan `✓ ID` (hijau)
4. ✅ No more `⚠ VALUE/LABEL` (orange) untuk numeric answers

---

## 🔧 TROUBLESHOOTING

### Issue 1: PHP Script Error

**Error**: `Class not found` atau `Connection refused`

**Solution**: Run script dari root directory project:
```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php fix_survey_string_to_integer.php
```

---

### Issue 2: SQL Update Fails

**Error**: `cannot cast type jsonb to integer`

**Solution**: Use explicit casting:
```sql
UPDATE survey_answers
SET jawaban = (jawaban::text::integer)::jsonb
WHERE jawaban::text ~ '^\d+$';
```

---

### Issue 3: Some Records Not Fixed

**Check**: Apakah ada format lain?
```sql
-- Find unusual formats
SELECT DISTINCT jawaban::text
FROM survey_answers
WHERE jawaban::text ~ '"'  -- Contains quotes
LIMIT 20;
```

---

## 📋 DEPLOYMENT CHECKLIST

### **Pre-Fix**:
- [ ] Backup database: `CREATE TABLE survey_answers_backup AS SELECT * FROM survey_answers;`
- [ ] Count affected rows: `SELECT COUNT(*) FROM survey_answers WHERE jawaban::text ~ '^"\d+"$';`
- [ ] Test query works

### **During Fix**:
- [ ] Upload PHP script (Method 1) OR
- [ ] Run SQL query (Method 2)
- [ ] Verify no errors

### **Post-Fix Verification**:
- [ ] Check count: `SELECT COUNT(*) FROM survey_answers WHERE jawaban::text ~ '^"\d+"$'` = 0
- [ ] Check sample: `SELECT * FROM survey_answers LIMIT 20` - all integers
- [ ] Check admin panel: All RAW show `✓ ID` (green)
- [ ] Clear cache: `php artisan optimize:clear`

---

## 🎯 RECOMMENDATION

**Use Method 1 (PHP Script)** karena:
- ✅ Safe (shows preview before fixing)
- ✅ Confirmation required (`yes/no`)
- ✅ Detailed progress output
- ✅ Error handling
- ✅ Statistics report
- ✅ Handles arrays (checkbox) automatically

**Use Method 2 (SQL)** jika:
- PHP script gagal
- Prefer direct database manipulation
- Comfortable with SQL

---

## 📁 FILES

**For Method 1 (PHP)**:
- `fix_survey_string_to_integer.php` (main script)

**For Method 2 (SQL)**:
- `fix_survey_quotes_simple.sql` (SQL queries)

**Documentation**:
- `FIX_SURVEY_STRING_IDS.md` (this file)

---

## ⏱️ ESTIMASI

- **Method 1 (PHP)**: 10-15 menit
- **Method 2 (SQL)**: 5-10 menit
- **Method 3 (Manual)**: 30+ menit (tergantung jumlah data)

---

**Dibuat**: 6 Februari 2026, 22:00 WIB
**Priority**: 🟡 MEDIUM (Data cleanup, not urgent)
**Recommended**: Method 1 (PHP Script)
