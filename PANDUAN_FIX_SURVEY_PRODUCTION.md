# PANDUAN FIX SURVEY ANSWERS - PRODUCTION

**Tanggal**: 6 Februari 2026
**Status**: Format Fix ✅ SELESAI (1,148 fixes) | VALUE/LABEL Fix 🔄 PENDING

---

## PROGRESS SAAT INI

### ✅ SELESAI: Format Fix (Array → Single Value)
- **Script**: `fix_survey_answer_format.php`
- **Hasil**: 1,148 jawaban berhasil diubah dari `[id]` → `id`
- **Verifikasi**: Run ke-2 menunjukkan 0 fixes (semua sudah benar)

### 🔄 PENDING: VALUE/LABEL → ID Conversion
- Masih ada jawaban yang tersimpan sebagai VALUE/LABEL, bukan ID
- Contoh: `"Ya"` seharusnya `133` (option ID)
- Butuh fuzzy matching untuk cocokkan label dengan ID yang tepat

### ⚠️ ISSUE: Missing Answers Q23-Q32
- Beberapa pendaftar punya jawaban kosong untuk Q23-Q32
- Perlu investigasi apakah masalah validasi atau data entry

---

## LANGKAH-LANGKAH PERBAIKAN

### STEP 1: Upload Scripts ke Production

**File yang perlu diupload**:
1. `diagnose_survey_issues.php` - Diagnostic tool
2. `migrate_survey_answers_SMART.php` - Smart migration dengan fuzzy matching
3. `fix_survey_answer_format.php` - Format fixer (sudah dirun, backup saja)

**Cara Upload**:
```batch
# Jalankan batch file
upload_diagnostic_script.bat

# Atau manual via SCP
scp diagnose_survey_issues.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/
scp migrate_survey_answers_SMART.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/
```

---

### STEP 2: Jalankan DIAGNOSTIC Script

**Login ke production**:
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
```

**Jalankan diagnostic**:
```bash
php diagnose_survey_issues.php > diagnostic_report.txt
```

**Lihat hasil**:
```bash
cat diagnostic_report.txt
```

**Apa yang akan ditemukan**:
- ❌ **NON-NUMERIC VALUES**: Jawaban yang masih VALUE/LABEL bukan ID
- ❌ **INVALID OPTION IDs**: ID yang tidak ada di tabel options
- ⚠️ **MISSING ANSWERS**: Q23-Q32 yang kosong
- 📄 **Export File**: JSON file berisi detail semua issue

**Contoh Output**:
```
=======================================================================
DIAGNOSTIC: Survey Answer Issues
=======================================================================

Analyzing 5847 answers...

=======================================================================
ISSUES FOUND
=======================================================================

❌ NON-NUMERIC VALUES (VALUE/LABEL instead of ID): 324
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Pendaftar: 123 | Q5: "Apakah Anda memiliki saudara kandung?..." |
Current: "Ya" (radio)
   Available Options:
   - ID:133 "Ya" (similarity: 100.0%)
   - ID:134 "Tidak" (similarity: 0.0%)

... (20 contoh pertama)

✅ All option IDs are valid

⚠️  MISSING ANSWERS (empty Q23-Q32): 48
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Pendaftar 456: Missing Q23, Q24, Q25
Pendaftar 789: Missing Q23, Q24, Q25, Q26, Q27, Q28, Q29, Q30, Q31, Q32
...

=======================================================================
SUMMARY
=======================================================================
Total Answers Analyzed: 5847
Non-numeric values: 324
Invalid option IDs: 0
Missing answers: 48

📄 Issues exported to: survey_issues_export_20260206_143022.json
```

---

### STEP 3: Jalankan SMART MIGRATION

**Sebelum run, cek konfigurasi**:
```bash
nano migrate_survey_answers_SMART.php
```

**Konfigurasi penting**:
```php
$DRY_RUN = true; // Set true untuk TEST dulu
$SIMILARITY_THRESHOLD = 70; // Minimum kesamaan 70%
```

**Test run (DRY RUN)**:
```bash
php migrate_survey_answers_SMART.php > migration_test.txt
```

**Lihat hasil test**:
```bash
cat migration_test.txt
```

**Jika hasil test OK, jalankan REAL migration**:
```bash
# Edit script, ubah $DRY_RUN = false
nano migrate_survey_answers_SMART.php

# Jalankan REAL migration
php migrate_survey_answers_SMART.php > migration_result.txt
```

**Apa yang akan dilakukan**:
- ✅ Convert VALUE/LABEL → ID menggunakan fuzzy matching
- 🎯 Match berdasarkan:
  1. Exact match (100%)
  2. Case-insensitive match (95%)
  3. Fuzzy similarity (70%+)
  4. Partial match (60%)
- 📊 Log detail setiap konversi
- ⚠️ Flag items yang perlu manual review

**Contoh Output**:
```
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Pendaftar ID: 123
Question ID: Q5 - Apakah Anda memiliki saudara kandung?...
Question Type: radio
Current Answer (RAW): "Ya"
  🔄 Converting: "Ya"
     → Option ID: 133
     → Label: "Ya"
     → Value: "1"
     → Match Score: 100.0% (exact_label)
  📝 New Answer: 133
Status: ✅ MIGRATED

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
=======================================================================
MIGRATION SUMMARY
=======================================================================

Total Answers: 5847
✅ Migrated: 324
🎯 Fuzzy Matched: 12
⏭️  Skipped (already ID): 5475
⏭️  Skipped (text input): 48
❌ Failed (no match): 0

=======================================================================
MIGRATION COMPLETED!
=======================================================================
```

---

### STEP 4: Verifikasi Hasil

**Di Admin Panel**:
1. Buka `/admin/tes-psikologi?mainTab=jawaban-survey`
2. Cek RAW display untuk setiap jawaban
3. **Target**: Semua harus menunjukkan `✓ ID` (hijau)
4. **Jangan ada**: `⚠ VALUE/LABEL` (orange)

**Di Database**:
```bash
# Login PostgreSQL
psql -U mtsnkot4 -d mtsnkot4_ppdb

# Cek sample data
SELECT id, pendaftar_id, survey_question_id, jawaban
FROM survey_answers
LIMIT 20;

# Cek jawaban non-numeric (seharusnya 0)
SELECT COUNT(*)
FROM survey_answers
WHERE jawaban::text !~ '^[0-9]+$'
  AND jawaban::text !~ '^\[.*\]$';
```

---

## TROUBLESHOOTING

### Issue: "Failed (no match)" Tinggi

**Penyebab**: Label di database tidak cocok dengan option label

**Solusi**:
1. Turunkan threshold: `$SIMILARITY_THRESHOLD = 60;`
2. Cek manual di file JSON export
3. Update manual via SQL

**Contoh Update Manual**:
```sql
UPDATE survey_answers
SET jawaban = '133'
WHERE pendaftar_id = 123 AND survey_question_id = 5;
```

---

### Issue: Missing Q23-Q32

**Investigasi**:
```sql
-- Cek berapa pendaftar yang missing
SELECT COUNT(DISTINCT pendaftar_id)
FROM survey_answers
WHERE survey_question_id BETWEEN 23 AND 32;

-- Cek apakah pertanyaan exists
SELECT id, pertanyaan, wajib_diisi
FROM survey_questions
WHERE id BETWEEN 23 AND 32
  AND context = 'tes_psikologi';
```

**Kemungkinan**:
1. ❌ Validasi tidak berjalan dengan benar
2. ❌ Pertanyaan tidak wajib diisi (`wajib_diisi = false`)
3. ❌ User skip sebelum strict validation diimplementasikan

**Solusi**:
- Jika pertanyaan seharusnya wajib, update:
  ```sql
  UPDATE survey_questions
  SET wajib_diisi = true
  WHERE id BETWEEN 23 AND 32 AND context = 'tes_psikologi';
  ```
- Contact pendaftar yang affected untuk re-submit

---

## CHECKLIST FINAL

### Pre-Migration
- [ ] Backup database production
- [ ] Upload semua 3 script ke production
- [ ] Jalankan diagnostic script
- [ ] Review diagnostic report

### Migration
- [ ] Test dengan DRY_RUN = true
- [ ] Review test results
- [ ] Run REAL migration dengan DRY_RUN = false
- [ ] Save migration results

### Post-Migration
- [ ] Cek admin panel - semua RAW harus `✓ ID`
- [ ] Test edit option label - data tidak rusak
- [ ] Test submit survey baru - tersimpan sebagai ID
- [ ] Verifikasi di database - tidak ada non-numeric values

### Documentation
- [ ] Save diagnostic report
- [ ] Save migration log
- [ ] Update session summary
- [ ] Commit perubahan ke git

---

## FILE YANG TERLIBAT

### Scripts
1. `diagnose_survey_issues.php` - Diagnostic tool
2. `migrate_survey_answers_SMART.php` - Smart migration
3. `fix_survey_answer_format.php` - Format fixer (✅ sudah dirun)

### Blade Templates (RAW Display)
1. `resources/views/livewire/admin/partials/tes-psikologi-jawaban-survey.blade.php`
2. `resources/views/livewire/admin/partials/survey-responses.blade.php`
3. `resources/views/livewire/panitia/partials/tes-psikologi-jawaban-survey.blade.php`

### Models
1. `app/Models/SurveyAnswer.php` - getJawabanFormatted() method
2. `app/Models/SurveyQuestion.php`
3. `app/Models/SurveyQuestionOption.php`

---

## EKSPEKTASI HASIL AKHIR

### Sebelum Migration
```
RAW: Ya ⚠ VALUE/LABEL
Ya
```

### Sesudah Migration
```
RAW: 133 ✓ ID
Ya
```

### Database
```sql
-- Sebelum
jawaban: "Ya"

-- Sesudah
jawaban: 133
```

### Keuntungan
✅ Admin bisa edit label option tanpa merusak data
✅ Data lebih konsisten dan terstruktur
✅ Query lebih cepat (integer vs string)
✅ Backward compatible dengan data lama
✅ Future-proof untuk perubahan opsi

---

**Dibuat oleh**: Claude Code
**Tanggal**: 6 Februari 2026
**Status**: Ready for Production
