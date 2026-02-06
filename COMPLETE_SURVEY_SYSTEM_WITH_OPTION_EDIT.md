# ✅ COMPLETE SURVEY SYSTEM - ID-Based Storage + Individual Option Edit

**Date:** February 5, 2026
**Systems Updated:** Survey Tes Psikologi + Survey Umum (Pendaftaran)
**Status:** READY FOR TESTING

---

## 🎯 Executive Summary

**COMPLETE IMPLEMENTATION** yang mencakup:

✅ **Survey Tes Psikologi** - ID-based storage
✅ **Survey Umum Pendaftaran** - ID-based storage
✅ **Individual Option Edit** - Edit/delete opsi per item dengan modal
✅ **Protection System** - Prevent delete option yang disubmit
✅ **Backward Compatible** - Data lama tetap terbaca

---

## 🆕 NEW FEATURE: Individual Option Edit/Delete

### What's New?

Sebelumnya, untuk edit satu opsi saja, admin harus:
1. Klik "Edit" pertanyaan
2. Edit seluruh form pertanyaan
3. Scroll ke opsi yang mau diubah
4. Simpan seluruh pertanyaan

**Sekarang:**
1. Hover pada opsi yang mau diubah
2. Klik tombol "Edit" (muncul saat hover)
3. Modal terbuka dengan form edit opsi tersebut saja
4. Simpan - selesai!

### UI Improvements

```
┌─────────────────────────────────────────────┐
│ ID:1   Sangat Setuju (value: sangat_setuju)│ ← Hover here
│                              [Edit] [Delete]│ ← Buttons appear
└─────────────────────────────────────────────┘
```

**Features:**
- ✨ **Hover Effect**: Edit/delete buttons muncul saat hover
- 🔵 **Edit Button**: Buka modal edit untuk opsi tersebut saja
- 🔴 **Delete Button**: Hapus opsi dengan protection check
- 🛡️ **Protection**: Error message jika opsi sudah disubmit
- 💬 **Info Box**: Tooltip menjelaskan benefit ID-based storage

---

## 📁 Files Modified (8 files)

### 1. Survey Tes Psikologi - Form
**File:** `resources/views/livewire/pendaftar/tes-psikologi.blade.php`
- Radio: `value="{{ $option->id }}"`
- Checkbox: `value="{{ $option->id }}"`
- Select: `value="{{ $option->id }}"`

### 2. Survey Umum - Form
**File:** `resources/views/tab_templates/tab-survey.blade.php`
- Radio: `value="{{ $option->id }}"`
- Checkbox: `value="{{ $option->id }}"`
- Select: `value="{{ $option->id }}"`

### 3. Survey Umum - Backend with Checkbox Fix
**File:** `app/Livewire/Pendaftar/Formulir.php`
- Fixed checkbox loading: linear array `[1, 3]` instead of associative `[1 => true]`
- Added checkbox initialization
- Added boolean filter in save method

### 4. Admin Display - Tes Psikologi (NEW METHODS!)
**File:** `app/Livewire/Admin/TesPsikologi.php`

**Added Properties:**
```php
public $showOptionModal = false;
public $editingOptionId = null;
public $optionLabel = '';
public $optionValue = '';
public $optionUrutan = 0;
public $optionQuestionId = null;
```

**Added Methods:**
- `editOption($id)` - Load option data into modal
- `saveOption()` - Save/update option
- `deleteOption($id)` - Delete with protection check
- `closeOptionModal()` - Reset modal state

**Protection Logic:**
```php
$answersQuery = SurveyAnswer::where('survey_question_id', $option->survey_question_id)
    ->where(function($query) use ($option) {
        $query->whereJsonContains('jawaban', $option->value)
              ->orWhereJsonContains('jawaban', $option->label)
              ->orWhereJsonContains('jawaban', $option->id)
              ->orWhereJsonContains('jawaban', (string)$option->id);
    });

if ($submissionCount > 0) {
    session()->flash('error', 'Tidak dapat menghapus...');
    return;
}
```

### 5. Admin Survey Management - UI (NEW!)
**File:** `resources/views/livewire/admin/partials/tes-psikologi-survey.blade.php`

**Added Edit/Delete Buttons:**
```blade
<div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition">
    <button wire:click="editOption({{ $option->id }})" class="p-1 text-xs text-blue-600 hover:bg-blue-100 rounded transition" title="Edit Opsi">
        <!-- Edit Icon -->
    </button>
    <button wire:click="deleteOption({{ $option->id }})" wire:confirm="Yakin ingin menghapus opsi ini?" class="p-1 text-xs text-red-600 hover:bg-red-100 rounded transition" title="Hapus Opsi">
        <!-- Delete Icon -->
    </button>
</div>
```

**Added Modal:**
- Form fields: Label, Value, Urutan
- Info box explaining ID-based benefit
- Validation with Livewire `wire:model`

### 6. Panitia Display - Tes Psikologi
**File:** `app/Livewire/Panitia/TesPsikologi.php`
- Same logic as Admin (ID first, fallback to VALUE)

### 7. Survey Answer Model
**File:** `app/Models/SurveyAnswer.php`
- Method `getJawabanFormatted()` with ID-first priority

### 8. Migration Script
**File:** `migrate_survey_answers_value_to_id.php`
- Converts ALL survey answers (both contexts)
- From VALUE → OPTION ID

---

## 🎨 User Experience Flow

### Admin Edit Option Workflow

**Step 1: Browse Options**
```
Admin membuka: /admin/tes-psikologi?mainTab=survey
Melihat list pertanyaan dengan opsi-opsinya
```

**Step 2: Hover & Click**
```
Hover mouse pada opsi yang ingin diubah
→ Button [Edit] [Delete] muncul
Click [Edit]
```

**Step 3: Modal Edit Terbuka**
```
┌──────────────────────────────────────┐
│ Edit Opsi                        [X] │
├──────────────────────────────────────┤
│ Label Opsi *                         │
│ [Sangat Setuju              ]        │
│ Teks yang ditampilkan ke pengguna    │
│                                      │
│ Value Opsi *                         │
│ [sangat_setuju              ]        │
│ Value internal (lowercase)           │
│                                      │
│ Urutan                               │
│ [0]                                  │
│ Angka lebih kecil tampil lebih dulu  │
│                                      │
│ ℹ️ Benefit ID-Based Storage          │
│ Anda aman mengubah LABEL dan VALUE   │
│ kapan saja! Data tersimpan by ID.    │
├──────────────────────────────────────┤
│              [Batal] [Simpan]        │
└──────────────────────────────────────┘
```

**Step 4: Simpan & Auto Refresh**
```
Click [Simpan]
→ Data disimpan
→ Modal tertutup
→ List survey refresh
→ Flash message: "Opsi berhasil diperbarui"
```

---

## 🚀 Key Benefits

### 1. **Admin Freedom** 🎨
```
Admin bisa edit LABEL/VALUE kapan saja!
- Edit: "Opsi A" → "Pilihan Alpha"
- Result: Display update otomatis, data aman!
```

### 2. **Fast Individual Edit** ⚡
```
Sebelum: Edit seluruh pertanyaan untuk ubah 1 opsi
Sekarang: Hover → Click → Edit → Save (5 detik!)
```

### 3. **Data Integrity** 🔒
```
Option ID: 1, 2, 3 (never changes)
Label: "Opsi A" (can change anytime)
Value: "opt_a" (can change anytime)
```

### 4. **No More `[VALUE NOT FOUND]`** ✅
```
Before: Admin delete option → [VALUE NOT FOUND] ⚠️
Now: System prevent deletion jika ada submission!
```

### 5. **Better UX for Admin** 💡
- Hover effect - buttons tidak berantakan
- Modal fokus - edit satu item saja
- Clear feedback - info box dan flash messages
- Protection visible - error message dengan count

---

## 🧪 Testing Checklist

### **Test 1: Individual Option Edit**

#### A. UI Interaction
```
1. Login sebagai admin
2. Buka /admin/tes-psikologi?mainTab=survey
3. Cari pertanyaan dengan opsi (radio/checkbox/select)
4. Hover mouse pada salah satu opsi
```

**Expected Result:**
- ✅ Button [Edit] dan [Delete] muncul saat hover
- ✅ Button hilang saat mouse keluar
- ✅ Button punya icon yang jelas
- ✅ Hover effect smooth (transition)

#### B. Edit Modal
```
1. Click button [Edit] pada salah satu opsi
```

**Expected Result:**
- ✅ Modal terbuka dengan data opsi tersebut
- ✅ Label, Value, Urutan ter-populate dengan benar
- ✅ Info box ID-based benefit tampil
- ✅ Form validation ready (required fields)

#### C. Save Edit
```
1. Edit Label: "Sangat Setuju" → "Strongly Agree"
2. Edit Value: "sangat_setuju" → "strongly_agree"
3. Click [Simpan]
```

**Expected Result:**
- ✅ Modal tertutup
- ✅ Flash message: "Opsi berhasil diperbarui"
- ✅ List refresh - opsi tampil dengan data baru
- ✅ Database updated: `survey_question_options` table

#### D. Data Integrity Check
```
1. Setelah edit opsi, buka tab "Jawaban Survey"
2. Cari pendaftar yang sudah submit dengan opsi tersebut
```

**Expected Result:**
- ✅ Jawaban tampil dengan LABEL BARU "Strongly Agree"
- ✅ Tidak ada error
- ✅ Tidak ada `[VALUE NOT FOUND]`
- ✅ Data tetap valid

**THIS IS THE MAIN BENEFIT!**

### **Test 2: Delete Protection**

#### A. Try Delete Submitted Option
```
1. Pilih opsi yang sudah pernah disubmit pendaftar
2. Hover dan click [Delete]
3. Confirm dialog
```

**Expected Result:**
- ✅ Confirmation dialog muncul
- ✅ Setelah confirm, ERROR muncul
- ✅ Error message: "Tidak dapat menghapus opsi ini! Ada X pendaftar..."
- ✅ Opsi TIDAK TERHAPUS

#### B. Delete Unused Option
```
1. Buat opsi baru di pertanyaan test
2. Pastikan tidak ada pendaftar yang submit
3. Hover dan click [Delete] pada opsi baru tersebut
4. Confirm
```

**Expected Result:**
- ✅ Opsi TERHAPUS
- ✅ Flash message: "Opsi berhasil dihapus"
- ✅ List refresh - opsi hilang

### **Test 3: Survey Tes Psikologi Form**

#### A. Form Simpan ID
```
1. Login sebagai pendaftar
2. Buka /pendaftar/tes-psikologi
3. Isi survey lengkap (radio, checkbox, text)
4. Check database:
   SELECT * FROM survey_answers WHERE pendaftar_id = X;
```

**Expected Result:**
```json
jawaban: [1]        ← Radio (single ID)
jawaban: [2, 5, 7]  ← Checkbox (array of IDs)
jawaban: ["teks"]   ← Text (tetap string)
```

### **Test 4: Survey Umum Form**

#### A. Form Simpan ID
```
1. Login sebagai pendaftar
2. Buka /pendaftar/formulir
3. Klik tab "Survey"
4. Isi survey lengkap
5. Check database
```

**Expected Result:**
```json
jawaban: [3]        ← Radio (single ID)
jawaban: [1, 4]     ← Checkbox (array of IDs)
```

---

## 📦 Deployment Guide

### Step 1: Backup (CRITICAL!)

**Local:**
```bash
php artisan db:backup
```

**Production:**
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
pg_dump -U mtsnkot4 mtsnkot4_ppdb > backup_survey_complete_$(date +%Y%m%d_%H%M%S).sql
```

### Step 2: Upload Files

**Run Batch Script:**
```bash
upload_fix_survey_complete_with_option_edit.bat
```

### Step 3: Clear Cache

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan optimize:clear
php artisan view:clear
php artisan cache:clear
```

### Step 4: Run Migration (Optional)

```bash
php migrate_survey_answers_value_to_id.php
```

### Step 5: Test Production

Run ALL tests from Testing Checklist above.

---

## 🐛 Troubleshooting

### Issue: Button Edit/Delete tidak muncul saat hover

**Check:**
1. View cache - run `php artisan view:clear`
2. Browser cache - hard refresh (Ctrl+Shift+R)
3. CSS loaded - check browser console for errors

### Issue: Modal tidak terbuka saat klik Edit

**Check:**
```php
php artisan tinker
$component = new \App\Livewire\Admin\TesPsikologi();
dd($component->showOptionModal);
```

**Should be:** `false` (default)

**Fix:** Upload `app/Livewire/Admin/TesPsikologi.php` again

### Issue: Delete protection tidak bekerja

**Verify protection logic:**
```sql
SELECT COUNT(*) FROM survey_answers
WHERE survey_question_id = :question_id
  AND (
    jawaban::jsonb @> '["option_value"]'
    OR jawaban::jsonb @> '["option_label"]'
    OR jawaban::jsonb @> '[option_id]'
  );
```

Should return > 0 if option has submissions.

---

## ✅ Success Criteria

After deployment, verify:

### 1. Individual Edit Works
- Hover shows buttons
- Click Edit opens modal
- Save updates option
- Display shows new values

### 2. Protection Works
- Cannot delete submitted options
- Clear error message
- Count of affected pendaftar shown

### 3. New Submissions Save ID
```sql
SELECT * FROM survey_answers
WHERE created_at > '2026-02-05'
LIMIT 10;
```

**Expected:** `jawaban` column contains numeric IDs: `[1]`, `[2,5]`, etc

### 4. Display Shows LABEL
- No `⚠️` markers for new data
- All labels match current option definitions

---

## 📊 Comparison: Before vs After

| Aspect | Before | After |
|--------|--------|-------|
| **Form Tes Psikologi** | `value="opsi A"` | `value="1"` ✅ |
| **Form Survey Umum** | `value="opsi A"` | `value="1"` ✅ |
| **Database** | `["opsi A"]` | `[1]` ✅ |
| **Admin Edit Label** | ❌ Corrupt data | ✅ Safe ✅ |
| **Admin Edit VALUE** | ❌ Corrupt data | ✅ Safe ✅ |
| **Delete Option** | ❌ Cause errors | ✅ Protected ✅ |
| **Edit One Option** | ❌ Edit full question | ✅ Individual modal ✅ |
| **UX - Edit Speed** | 😐 Slow (many steps) | ✅ Fast (hover → click) ✅ |
| **Data Integrity** | ⚠️ Fragile | ✅ Robust ✅ |
| **Backward Compat** | N/A | ✅ Yes ✅ |

---

## 🎯 Final Checklist

### Before Production:
- [ ] Backup database (local dan production)
- [ ] Test Survey Tes Psikologi lokal
- [ ] Test Survey Umum lokal
- [ ] Test individual option edit/delete
- [ ] Test protection system
- [ ] Upload all 8 files
- [ ] Clear cache production

### After Production:
- [ ] Test hover effect on options
- [ ] Test edit modal (open, save, close)
- [ ] Test delete protection
- [ ] Test edit option - verify display update
- [ ] Test new submissions save ID
- [ ] Monitor Laravel log

---

## 🎉 Expected Outcomes

### Immediate:
✅ New survey answers save **OPTION ID**
✅ Display shows **LABEL** correctly
✅ Individual option edit via **hover → modal**
✅ Protection prevents option deletion with submissions
✅ No `[VALUE NOT FOUND]` for new data

### Long Term:
✅ **Admin freedom:** Edit LABEL/VALUE anytime
✅ **Fast editing:** Individual option edit in seconds
✅ **Data integrity:** Stable ID references
✅ **Better UX:** Hover effects, clear modals
✅ **Easier maintenance:** Clear relational structure

---

**STATUS: ✅ READY FOR LOCAL TESTING**

Test SEMUA fitur (Tes Psikologi + Survey Umum + Individual Edit) secara menyeluruh di lokal sebelum upload ke production!
