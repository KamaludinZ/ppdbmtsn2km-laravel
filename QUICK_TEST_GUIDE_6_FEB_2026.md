# 🧪 QUICK TEST GUIDE - 6 FEBRUARI 2026

## Panduan Cepat Testing Setelah Deploy

**Total Tests:** 5 test scenarios
**Estimated Time:** 15-20 minutes
**Recommended Order:** Follow sequence below

---

## ✅ PRE-TEST: Setup Test Data

### Create Test Pendaftar

Via phpMyAdmin atau psql:

```sql
-- Setup test pendaftar dengan catatan
UPDATE pendaftar
SET status_pendaftaran = 'perbaiki_data',
    catatan_verifikasi = 'Mohon lengkapi data berikut:
- Email ayah belum diisi
- Nomor HP ibu tidak valid
- Alamat rumah belum lengkap'
WHERE id = 1;  -- Ganti dengan ID pendaftar test Anda
```

---

## 🧪 TEST 1: CATATAN DI DASHBOARD

### Steps:
1. Login sebagai **pendaftar** (gunakan ID yang sudah di-setup)
2. Access: `/pendaftar/dashboard`

### Expected Results:
✅ Catatan box muncul DI ATAS "Progress Pendaftaran"
✅ Background: Light yellow (kuning muda)
✅ Text: Dark gray (abu tua) - **JELAS TERLIHAT**
✅ Border: 2px solid yellow
✅ Icon: Warning triangle (⚠️)
✅ Title: "📝 Catatan dari Panitia"
✅ Multi-line text dengan line breaks

### Visual Check:
```
┌─────────────────────────────────────┐
│ ⚠️  📝 Catatan dari Panitia         │
│                                     │
│ ┌───────────────────────────────┐   │
│ │ 🟨 LIGHT YELLOW BACKGROUND    │   │
│ │                               │   │
│ │ ⬛ DARK GRAY TEXT             │   │
│ │ Mohon lengkapi data berikut:  │   │
│ │ - Email ayah belum diisi      │   │
│ │ - Nomor HP ibu tidak valid    │   │
│ │ - Alamat rumah belum lengkap  │   │
│ └───────────────────────────────┘   │
│                                     │
│ 💡 Silakan perbaiki data...         │
└─────────────────────────────────────┘
```

### ❌ Fail Indicators:
- Text putih/tidak terlihat
- Box tidak muncul
- Text dalam single line (tidak ada line breaks)

---

## 🧪 TEST 2: PROGRESS STEP 2 - STATUS DITOLAK

### Steps:
1. Update status pendaftar:
```sql
UPDATE pendaftar
SET status_pendaftaran = 'data_ditolak',
    catatan_verifikasi = 'NISN tidak valid'
WHERE id = 1;
```
2. Refresh `/pendaftar/dashboard`
3. Scroll ke "Progress Pendaftaran"
4. Lihat Step 2: "Formulir"

### Expected Results:
✅ Step 2 shows: **"Ditolak"** (RED color)
✅ NOT showing "Menunggu" (yellow)
✅ Catatan box tetap muncul di atas

### Visual Check:
```
Progress Pendaftaran:
[✓] Step 1: Pendaftaran (Green)
[❌] Step 2: Formulir - DITOLAK (Red) ← MUST BE RED!
[ ] Step 3: Tes Psikologi
[ ] Step 4: Daftar Ulang
```

### ❌ Fail Indicators:
- Step 2 shows "Menunggu" (yellow)
- Step 2 shows "Lengkapi" (yellow)

---

## 🧪 TEST 3: CATATAN DI STATUS PAGE

### Steps:
1. Same pendaftar as Test 1 or Test 2
2. Access: `/pendaftar/status`
3. Scroll down setelah "Status Card"

### Expected Results:
✅ Catatan box muncul DI BAWAH "Status Card"
✅ Same styling as dashboard (yellow bg, dark text)
✅ Text clearly visible
✅ Multi-line preserved

### Location:
```
┌────────────────────┐
│   STATUS CARD      │ ← Section 1
└────────────────────┘

┌────────────────────┐
│ CATATAN BOX        │ ← Section 2 (HERE!)
└────────────────────┘

┌────────────────────┐
│   DOCUMENTS        │ ← Section 3
└────────────────────┘
```

---

## 🧪 TEST 4: SURVEY CHECKBOX - PENDAFTAR

### Pre-requisite:
Pastikan ada survey question dengan tipe_input = 'checkbox'

### Steps:
1. Login sebagai **pendaftar**
2. Access: `/pendaftar/tes-psikologi`
3. Jawab pertanyaan sampai menemukan **checkbox** (multiple choice)
4. **SELECT MULTIPLE OPTIONS** (minimal 2 pilihan)
5. Klik "Simpan & Lanjut"

### Expected Results:
✅ **NO ERROR** "Array to string conversion"
✅ Success message/notification
✅ Can proceed to next question
✅ No page crash

### ❌ Fail Indicators:
- Error "Array to string conversion"
- Page crash/500 error
- Cannot proceed to next question

### Database Check:
```sql
SELECT id, pendaftar_id, survey_question_id, jawaban, created_at
FROM survey_answers
WHERE pendaftar_id = [your_test_id]
ORDER BY created_at DESC
LIMIT 5;
```

**Expected jawaban format:**
```
jawaban: ["1","3","5"]  ← JSON array string (CORRECT)
```

**NOT:**
```
jawaban: Array  ← PHP array (WRONG - will crash)
jawaban: 1      ← Single value (WRONG for checkbox)
```

---

## 🧪 TEST 5: SURVEY CHECKBOX - ADMIN EDIT

### Steps:
1. Login sebagai **admin**
2. Access: `/admin/tes-psikologi?mainTab=jawaban-survey`
3. Click "Edit" pada salah satu pendaftar
4. Cari pertanyaan dengan **checkbox**
5. **CHANGE** selection (pilih multiple options)
6. Klik "Simpan"

### Expected Results:
✅ **NO ERROR**
✅ Success message: "Jawaban berhasil diupdate"
✅ Modal closes
✅ Table refreshes with updated data

### ❌ Fail Indicators:
- Error "Array to string conversion"
- Page crash
- Modal tidak close
- Data tidak tersimpan

### Database Verification:
```sql
SELECT sa.id, sa.pendaftar_id, sq.pertanyaan, sa.jawaban, sa.updated_at
FROM survey_answers sa
JOIN survey_questions sq ON sa.survey_question_id = sq.id
WHERE sa.pendaftar_id = [edited_pendaftar_id]
  AND sq.tipe_input = 'checkbox'
ORDER BY sa.updated_at DESC;
```

Check that `jawaban` = JSON array format

---

## 🔍 BONUS TEST: FORMULIR SURVEY TAB

### Steps:
1. Login sebagai **pendaftar**
2. Access: `/pendaftar/formulir?tab=survey`
3. Jawab survey questions (termasuk checkbox)
4. Klik "Simpan Survey"

### Expected Results:
✅ No errors
✅ Success message
✅ Checkbox answers saved as JSON

---

## 📊 COMPLETE SUCCESS CRITERIA

All tests must pass:

- [x] Test 1: Catatan di dashboard ✅
- [x] Test 2: Progress step 2 "Ditolak" ✅
- [x] Test 3: Catatan di status page ✅
- [x] Test 4: Survey checkbox pendaftar ✅
- [x] Test 5: Survey checkbox admin edit ✅
- [x] Bonus: Formulir survey tab ✅

---

## 🚨 TROUBLESHOOTING

### Problem: Catatan tidak muncul
**Check:**
1. Status pendaftar = `perbaiki_data`, `lengkapi_data`, atau `data_ditolak`?
2. `catatan_verifikasi` tidak kosong?
3. Cache sudah di-clear?

**Fix:**
```bash
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear"
```

### Problem: Text catatan tidak terlihat
**Check:**
1. Browser cache - tekan Ctrl+Shift+R
2. File `dashboard.blade.php` dan `status.blade.php` sudah ter-upload?
3. Line 358-360 (dashboard) dan 82-84 (status) sudah berisi `bg-yellow-50` dan `text-gray-800`?

**Fix:**
Re-upload view files dan clear cache

### Problem: Survey masih error "Array to string"
**Check:**
1. File `TesPsikologi.php` (Pendaftar & Admin) dan `Formulir.php` sudah ter-upload?
2. Line numbers match dokumentasi?
3. Cache di-clear?

**Fix:**
```bash
# Re-upload files
scp app/Livewire/Pendaftar/TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
scp app/Livewire/Pendaftar/Formulir.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
scp app/Livewire/Admin/TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/

# Clear cache
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
```

### Problem: Progress step 2 masih "Menunggu"
**Check:**
1. File `Dashboard.php` sudah ter-upload?
2. Line 312-318 contains check for `data_ditolak`?
3. Status pendaftar = `data_ditolak`?

**Fix:**
Re-upload `app/Livewire/Pendaftar/Dashboard.php`

---

## 📋 POST-TEST CHECKLIST

After all tests pass:

- [ ] Clear browser cache
- [ ] Test dengan multiple pendaftar accounts
- [ ] Monitor error logs for 1 hour:
  ```bash
  ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && tail -f storage/logs/laravel.log"
  ```
- [ ] Check database for proper JSON format in `survey_answers.jawaban`
- [ ] Notify users (optional)
- [ ] Update dokumentasi internal (optional)

---

## ✅ SUCCESS CONFIRMATION

**All tests passed?** ✅

Deployment successful! Features working correctly:

1. ✅ Catatan verifikasi visible to pendaftar
2. ✅ Progress tracking accurate
3. ✅ Survey checkbox working without errors
4. ✅ Data integrity maintained

**Total testing time:** ~15-20 minutes
**Impact:** High - Critical UX & data fixes
**Priority:** Monitor for 24 hours

---

**Created:** 6 Februari 2026
**Version:** 1.0
**Tested by:** [Your name]
**Date tested:** [Fill in after testing]
**Result:** [PASS/FAIL]
**Notes:** [Any observations]
