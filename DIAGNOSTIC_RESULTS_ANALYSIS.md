# DIAGNOSTIC RESULTS - ANALISA & ACTION PLAN

**Tanggal**: 6 Februari 2026
**Status**: ⚠️ CRITICAL ISSUES FOUND

---

## 📊 HASIL DIAGNOSTIC

Dari output `php diagnose_survey_issues.php`:

```
Total Answers Analyzed: 1,662
❌ NON-NUMERIC VALUES: 106 (VALUE/LABEL bukan ID)
❌ INVALID OPTION IDs: 336 (ID tidak valid!)
⚠️ MISSING ANSWERS: 320 (Q23-Q32 kosong)
```

---

## 🚨 MASALAH KRITIS YANG DITEMUKAN

### **ISSUE 1: Pertanyaan dan Opsi Tidak Cocok** ⚠️ CRITICAL

**Contoh 1**:
```
Q1: "Saya mengisi jajak pendapat ini sesuai dengan kondisi..."
User menjawab: "Sangat Tidak Setuju"
Available Options: ID:133 "Ya" (similarity: 1%)
```

**Masalah**:
- Pertanyaan seharusnya punya opsi Likert Scale (Sangat Tidak Setuju - Sangat Setuju)
- Tapi opsi yang tersedia hanya "Ya"
- **Similarity 1% = MISMATCH TOTAL**

**Contoh 2**:
```
Q2: "Media/sarana yang sering dipakai dalam memberikan..."
User menjawab: "Sangat Tidak Setuju"
Available Options:
   - ID:129 "Guru"
   - ID:130 "Kepala Sekolah"
   - ID:131 "Sosial Media"
   - ID:132 "Dinas Terkait"
```

**Masalah**:
- User jawab dengan Likert scale ("Sangat Tidak Setuju")
- Tapi opsi adalah pilihan kategori (Guru, Kepala Sekolah, dll)
- **Ini menunjukkan PERTANYAAN BERUBAH atau OPSI SALAH**

---

### **ISSUE 2: Invalid Option IDs - Opsi Di-Recreate** ⚠️ CRITICAL

**Contoh 1**:
```
Pendaftar: 226 | Q16
Invalid ID: 126 (digunakan di jawaban)
Valid Options saat ini: ID:61-64 (Sangat Tidak Setuju - Sangat Setuju)
```

**Masalah**:
- User dulu menjawab dengan ID 126
- Tapi sekarang Q16 punya opsi ID 61-64
- **ID 126 sudah TIDAK EXIST di database**

**Pattern yang terlihat**:
- ID orphaned: **114, 126** (digunakan ratusan kali tapi tidak valid)
- ID valid sekarang: **61-64, 97-100, 101-104, 105-108, dll**
- **Artinya**: Options pernah di-delete/recreate, ID berubah

---

### **ISSUE 3: Missing Q23-Q32** ⚠️ DATA ISSUE

```
Semua pendaftar missing Q23-Q32 (320 missing answers)
Pattern: Q23, Q24, Q25, Q26, Q27, Q28, Q29, Q30, Q31, Q32
```

**Kemungkinan**:
1. ✅ Pertanyaan baru ditambahkan setelah mereka submit
2. ❌ Pertanyaan tidak wajib dan mereka skip
3. ❌ Validation error (tapi sekarang sudah difix dengan strict validation)

**Rekomendasi**:
- Jika pertanyaan BARU: Wajar kosong, abaikan saja
- Jika pertanyaan LAMA: Perlu investigasi kenapa kosong

---

## 🔍 ROOT CAUSE ANALYSIS

### **Penyebab Utama**:

1. **Options Di-Recreate/Delete**:
   - ID lama (114, 126) tidak ada lagi
   - Diganti dengan ID baru (61-64, 97-100, dll)
   - Jawaban lama masih pakai ID orphaned

2. **Pertanyaan Berubah**:
   - Pertanyaan diedit/diganti
   - Opsi tidak sesuai dengan pertanyaan baru
   - User jawab dengan opsi lama yang sudah tidak cocok

3. **Data Legacy**:
   - Jawaban tersimpan sebagai VALUE/LABEL dari sistem lama
   - Belum dikonversi ke ID-based storage

---

## 🎯 ACTION PLAN

### **STEP 1: INVESTIGASI STRUKTUR (PRIORITAS TINGGI)**

**File**: `investigate_survey_structure.php` (sudah dibuat)

**Tujuan**:
- Lihat mapping pertanyaan-opsi saat ini
- Identifikasi orphaned IDs (ID yang digunakan tapi tidak exist)
- Cek apakah ada duplicate/conflict

**Cara Run**:
```bash
# Upload script
scp investigate_survey_structure.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/

# SSH dan run
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php investigate_survey_structure.php > investigation_report.txt
cat investigation_report.txt
```

**Output yang diharapkan**:
- List semua pertanyaan dan opsinya
- Orphaned IDs: 114, 126, dll dengan berapa kali digunakan
- Rekomendasi manual mapping

---

### **STEP 2A: FIX ORPHANED IDs (MANUAL MAPPING)** ⚠️ REQUIRED

Berdasarkan hasil investigation, perlu **MANUAL RE-MAPPING** untuk orphaned IDs:

**Contoh Manual Fix**:
```sql
-- Jika ID 126 dulu adalah "Setuju" untuk Q16
-- Dan sekarang Q16 punya opsi:
-- ID:61 "Sangat Tidak Setuju"
-- ID:62 "Tidak Setuju"
-- ID:63 "Setuju"
-- ID:64 "Sangat Setuju"

-- Maka update semua jawaban yang pakai ID 126 → ID 63
UPDATE survey_answers
SET jawaban = 63
WHERE survey_question_id = 16
  AND jawaban = 126;
```

**Langkah**:
1. Dari investigation report, dapatkan list orphaned IDs
2. Untuk setiap orphaned ID:
   - Cek pertanyaan mana yang menggunakannya
   - Lihat opsi valid saat ini untuk pertanyaan tersebut
   - Match berdasarkan konteks (posisi/urutan)
   - Update ke ID yang benar

---

### **STEP 2B: FIX VALUE/LABEL (UNTUK NON-NUMERIC)**

**File**: `migrate_survey_answers_SMART.php`

**Catatan**: Script ini TIDAK bisa fix orphaned IDs, hanya bisa convert VALUE/LABEL → ID

**Cara Run** (SETELAH orphaned IDs difix):
```bash
# Test dulu
nano migrate_survey_answers_SMART.php
# Set: $DRY_RUN = true

php migrate_survey_answers_SMART.php > test_migration.txt

# Jika OK, run real
# Set: $DRY_RUN = false
php migrate_survey_answers_SMART.php > migration_result.txt
```

---

### **STEP 3: VERIFIKASI**

**Setelah fix orphaned IDs dan VALUE/LABEL**:

```bash
# Re-run diagnostic
php diagnose_survey_issues.php

# Expected result:
# Non-numeric values: 0
# Invalid option IDs: 0 (atau sangat sedikit)
# Missing answers: 320 (biarkan jika Q23-Q32 memang baru)
```

**Di Admin Panel**:
- Buka `/admin/tes-psikologi?mainTab=jawaban-survey`
- Semua RAW harus `✓ ID` (hijau)
- Tidak ada `⚠ VALUE/LABEL` (orange)

---

## 📋 MAPPING ORPHANED IDs (TEMPLATE)

Berdasarkan pattern yang terlihat, kemungkinan mapping:

### **Orphaned ID: 114**

**Digunakan untuk pertanyaan** (akan ketahuan dari investigation script):
- Q3, Q9, Q10, Q25, Q26, Q27, Q28

**Kemungkinan arti**: "Setuju" atau posisi tertentu di Likert scale

**Action**:
```sql
-- Contoh jika 114 = "Setuju" untuk Q25
-- Dan Q25 sekarang punya opsi ID:97-100 (4-point Likert)
-- Maka 114 → 99 (Setuju)

UPDATE survey_answers
SET jawaban = 99
WHERE survey_question_id = 25
  AND jawaban = 114;
```

### **Orphaned ID: 126**

**Digunakan untuk pertanyaan**:
- Q10, Q11, Q16, Q17, Q19, Q21, Q22, Q24, Q25, Q31

**Kemungkinan arti**: "Setuju" atau posisi tertentu

**Action**:
```sql
-- Per question, cek opsi valid dan match
-- Contoh untuk Q16 (opsi valid: 61-64)
UPDATE survey_answers
SET jawaban = 63  -- Jika 126 = Setuju
WHERE survey_question_id = 16
  AND jawaban = 126;
```

---

## 🔧 TOOLS YANG SUDAH DISIAPKAN

### 1. ✅ `diagnose_survey_issues.php` (FIXED)
- Deteksi VALUE/LABEL, invalid IDs, missing answers
- Export ke JSON untuk batch processing

### 2. ✅ `investigate_survey_structure.php` (NEW)
- Analisa struktur pertanyaan-opsi
- List orphaned IDs dengan detail penggunaan
- Rekomendasi manual mapping

### 3. ✅ `migrate_survey_answers_SMART.php`
- Convert VALUE/LABEL → ID dengan fuzzy matching
- **NOTE**: Tidak bisa fix orphaned IDs!

### 4. ✅ `fix_survey_answer_format.php` (ALREADY RUN)
- Fix array format [id] → id
- 1,148 fixes sudah applied ✅

---

## 📝 CHECKLIST EXECUTION

### Investigation Phase
- [ ] Upload `investigate_survey_structure.php`
- [ ] Run investigation script
- [ ] Review orphaned IDs list
- [ ] Identify mapping pattern

### Manual Fix Phase (CRITICAL)
- [ ] Create SQL script untuk map orphaned IDs
- [ ] Backup database sebelum update
- [ ] Execute SQL updates
- [ ] Verify updates (cek sample data)

### Automated Fix Phase
- [ ] Re-run diagnostic (confirm orphaned IDs = 0)
- [ ] Test SMART migration (DRY_RUN)
- [ ] Run SMART migration (real)
- [ ] Verify all VALUE/LABEL converted

### Final Verification
- [ ] Run diagnostic final (all issues = 0)
- [ ] Check admin panel (all RAW = ✓ ID)
- [ ] Test edit option labels (data tidak rusak)
- [ ] Commit changes to git

---

## 🎯 EXPECTED FINAL STATE

### Before Fix
```
RAW: Setuju ⚠ VALUE/LABEL
RAW: 126 ❌ INVALID ID
```

### After Fix
```
RAW: 63 ✓ ID
```

### Database
```sql
-- Before
SELECT jawaban FROM survey_answers WHERE id = 123;
-- Result: "Setuju" atau 126 (invalid)

-- After
SELECT jawaban FROM survey_answers WHERE id = 123;
-- Result: 63 (valid option ID)
```

---

## ⚠️ IMPORTANT NOTES

1. **JANGAN run SMART migration sebelum fix orphaned IDs**
   - SMART migration tidak bisa fix invalid IDs
   - Orphaned IDs harus di-remap manual dulu

2. **Similarity rendah (0-12%) = MASALAH STRUKTURAL**
   - Tidak bisa pakai fuzzy matching
   - Harus manual mapping berdasarkan konteks

3. **Backup database sebelum update**
   ```bash
   pg_dump -U mtsnkot4 -d mtsnkot4_ppdb > backup_before_survey_fix_$(date +%Y%m%d).sql
   ```

4. **Test di sample data dulu**
   ```sql
   -- Test update untuk 1 pendaftar dulu
   UPDATE survey_answers
   SET jawaban = 63
   WHERE pendaftar_id = 226
     AND survey_question_id = 16
     AND jawaban = 126;

   -- Cek hasilnya di admin panel
   -- Jika OK, baru run untuk semua
   ```

---

## 🚀 QUICK START

```bash
# 1. Upload investigation tool
upload_investigation_tools.bat

# 2. SSH dan run investigation
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php investigate_survey_structure.php > investigation_report.txt
cat investigation_report.txt

# 3. Berdasarkan hasil, buat SQL fix manual
nano fix_orphaned_ids.sql

# 4. Review dan execute SQL
psql -U mtsnkot4 -d mtsnkot4_ppdb -f fix_orphaned_ids.sql

# 5. Re-run diagnostic
php diagnose_survey_issues.php

# 6. Jika orphaned IDs = 0, run SMART migration
php migrate_survey_answers_SMART.php
```

---

**Dibuat**: 6 Februari 2026, 16:15 WIB
**Status**: Investigation required sebelum migration
**Estimasi**: 2-3 jam (termasuk manual mapping)
