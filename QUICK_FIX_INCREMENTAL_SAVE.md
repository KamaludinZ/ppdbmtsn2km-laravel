# QUICK FIX - Incremental Save (ROOT CAUSE FOUND!)

🎯 **Credit**: Root cause identified by USER!

---

## ROOT CAUSE

**User's Insight:**
> "Tombol 'Selanjutnya' save normal (tidak overwrite), tapi tombol 'Selesai & Simpan' me-replace semua jawaban"

**Meaning:**
- ✅ `nextQuestion()` → Incremental save = **OK**
- ❌ `simpanSurvey()` → Loop all answers = **BUG** (overwrite!)

---

## SOLUTION APPLIED

### **TesPsikologi.php - Method `simpanSurvey()`**

**BEFORE (BUG)**:
```php
foreach ($this->surveyAnswers as $questionId => $answer) {
    // Save ALL answers again → Overwrite bug!
}
```

**AFTER (FIX)**:
```php
// Save ONLY current/last question
$currentQuestion = $this->surveyQuestions[$this->currentQuestionIndex];
// Previous answers already saved by nextQuestion()
```

### **SurveyAnswer.php - Remove Array Cast**

**BEFORE**:
```php
protected $casts = [
    'jawaban' => 'array', // Forces ALL to array
];
```

**AFTER**:
```php
protected $casts = [
    // 'jawaban' => 'array', // REMOVED!
];
```

---

## FILES TO UPLOAD (2 FILES)

1. ⭐ `app/Livewire/Pendaftar/TesPsikologi.php` (CRITICAL!)
2. `app/Models/SurveyAnswer.php`

---

## DEPLOYMENT (4 STEPS)

### 1. UPLOAD FILES
Via cPanel File Manager:
- Upload both files (overwrite existing)

### 2. CLEAR CACHE
```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan optimize:clear
```

### 3. DELETE CORRUPT DATA
```sql
DELETE FROM survey_answers;
```

### 4. TEST SURVEY
- Login sebagai pendaftar
- Isi survey (jawaban BERBEDA tiap pertanyaan!)
- Klik "Selanjutnya" untuk setiap pertanyaan
- Klik "Selesai & Simpan" di akhir

---

## VERIFY DATABASE

```sql
SELECT survey_question_id, jawaban
FROM survey_answers
WHERE pendaftar_id = [ID]
ORDER BY survey_question_id;
```

**Expected** ✅:
```
 1 | 133   ← INTEGER, berbeda
 2 | 129   ← BERBEDA!
 3 | 7     ← BERBEDA!
...
32 | 121   ← SEMUA BERBEDA!
```

**NOT** ❌:
```
 1 | "126"
 2 | "126"  ← SAMA SEMUA = BUG!
```

---

## WHY THIS FIX WORKS

1. **Incremental Save**: Each question saved when click "Selanjutnya"
2. **No Loop**: Final button save ONLY last question
3. **No Overwrite**: Previous answers already safe in database
4. **Simple & Safe**: No complex logic, no batch operation

---

## FULL GUIDE

See: `MANUAL_UPLOAD_INCREMENTAL_FIX.md` (250+ lines, step-by-step)

---

**Status**: ✅ READY TO UPLOAD
**Estimasi**: 30-45 menit
**Priority**: 🔥 CRITICAL
