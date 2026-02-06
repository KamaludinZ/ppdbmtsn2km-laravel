# 🧪 QUICK TEST - Survey Data Corruption Fix

**Date**: 7 Feb 2026
**Priority**: 🚨 CRITICAL - Test Before Production
**Time**: 15 minutes

---

## ⚡ QUICK OVERVIEW

**Problem**: User answers saved correctly, but clicking "Selesai" overwrites with wrong values ❌
**Fix**: Changed `simpanSurvey()` to save ONLY current question, trust previous saves ✅

---

## 📋 TEST CHECKLIST

### **Test 1: Normal Flow (5 min)** ✅

```
[ ] Open /pendaftar/tes-psikologi
[ ] Answer Q1, click "Next" → should save
[ ] Answer Q2, click "Next" → should save
[ ] Answer Q3 (last), click "Selesai & Simpan"
[ ] Check logs: Should only see "Saving CURRENT Q3"
[ ] Check database: All 3 questions saved correctly
```

**Expected**: ✅ All answers correct

---

### **Test 2: The Corruption Scenario (10 min)** 🚨

This test reproduces the bug you found!

```
[ ] Open /pendaftar/tes-psikologi
[ ] Q1: Select Option A (e.g., ID 3), click "Next"
    → Log should show: "nextQuestion: Q1 = 3"
[ ] Q2: Select Option B (e.g., ID 9), click "Next"
    → Log should show: "nextQuestion: Q2 = 9"
[ ] Q3: Answer, click "Next" or "Selesai"
[ ] Check database RIGHT NOW: Q1=3, Q2=9 ✓
[ ] Now click "BACK" button to Q1
[ ] Change selection to different option (e.g., ID 4)
[ ] Click "Next" (or navigate forward WITHOUT clicking Next)
[ ] Reach last question, click "Selesai & Simpan"
[ ] Check logs: Should only see "Saving CURRENT Q[last]"
[ ] Check database: Q1 should STILL be 3 (NOT 4!)
```

**Expected**: ✅ Q1 remains original value (3), NOT changed value (4)
**Old Code Would**: ❌ Q1 becomes 4 (overwrites!)
**New Code Does**: ✅ Q1 stays 3 (preserves!)

---

## 🔍 HOW TO CHECK DATABASE

### **Method 1: Laravel Log**

```bash
tail -50 storage/logs/laravel.log | grep -E "(nextQuestion|simpanSurvey|Q[0-9]+)"
```

Look for:
- ✅ `nextQuestion: Q1 = 3` → Q1 saved as 3
- ✅ `Saving CURRENT Q3: ...` → Only last question saved
- ❌ `Saving Q1: ...` → If you see this, old code is running!

### **Method 2: Database Direct**

Via phpMyAdmin or Adminer:
```sql
SELECT
    sa.survey_question_id,
    sa.jawaban,
    sa.updated_at,
    sq.pertanyaan
FROM survey_answers sa
JOIN survey_questions sq ON sa.survey_question_id = sq.id
WHERE sa.pendaftar_id = [your_test_pendaftar_id]
ORDER BY sa.survey_question_id;
```

---

## 🎯 WHAT TO LOOK FOR

### **✅ PASS Criteria**

1. Log shows: "SIMPAN SURVEY (FINAL - CURRENT QUESTION ONLY)"
2. Log shows: "Saving CURRENT Q[X]" (only ONE question)
3. Database has correct values from nextQuestion() saves
4. Going back and changing answer does NOT overwrite database

### **❌ FAIL Criteria**

1. Log shows: "SIMPAN SURVEY (FINAL - ALL QUESTIONS)"
2. Log shows: "Saving Q1:", "Saving Q2:", ... (multiple questions)
3. Database values change when clicking Selesai (overwrite)
4. Going back and changing answer overwrites database

---

## 🚀 IF TEST PASSES

1. Create clean test pendaftar (delete old test data)
2. Test full survey flow end-to-end
3. Verify /admin/tes-psikologi displays correct data
4. Upload to production:
   ```
   File: app/Livewire/Pendaftar/TesPsikologi.php
   Upload to: /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
   ```
5. Clear cache:
   ```bash
   cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
   php artisan optimize:clear
   ```

---

## ⚠️ IF TEST FAILS

1. Check if file was saved correctly
2. Run `composer dump-autoload`
3. Clear cache: `php artisan optimize:clear`
4. Check logs for errors
5. Report findings (show log output and database values)

---

## 📝 TEST RESULTS TEMPLATE

```
TEST 1 (Normal Flow): [PASS/FAIL]
- All questions saved correctly: [YES/NO]
- Log shows "CURRENT QUESTION ONLY": [YES/NO]

TEST 2 (Corruption Scenario): [PASS/FAIL]
- Q1 original value preserved: [YES/NO]
- Changed value NOT saved: [YES/NO]
- Log shows only last question saved: [YES/NO]

DATABASE CHECK:
- Q1 value: _____ (expected: original, not changed)
- Q2 value: _____ (expected: correct)
- Q3 value: _____ (expected: correct)

VERDICT: [READY FOR PRODUCTION / NEEDS MORE WORK]
```

---

**Created**: 7 Feb 2026, 01:35 WIB
**Next**: Test → Report Results → Upload if Pass ✅
