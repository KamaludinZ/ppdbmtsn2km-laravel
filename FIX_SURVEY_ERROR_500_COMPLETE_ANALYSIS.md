# ANALISIS LENGKAP ERROR 500 - /admin/survey Production

**Tanggal**: 5 Februari 2026
**Status**: ✅ FIXED - READY TO UPLOAD

---

## 📍 ERROR PRODUCTION

```
Call to undefined relationship [option] on model [App\Models\SurveyAnswer]
```

**Source**: `storage/logs/laravel.log`
**File**: `/storage/framework/views/0c9f0f2650023377a1322f9600a3a594.php:135`
**URL**: `https://pmbm.mtsn2kotamalang.sch.id/admin/survey`

---

## 🔍 ROOT CAUSE ANALYSIS

### Masalah Utama

Production menggunakan **VIEW LAMA** yang masih mengakses schema database LAMA yang sudah tidak digunakan lagi.

### Schema Migration History

#### OLD Schema (❌ Sudah tidak digunakan)
```
survey_answers table:
- id
- pendaftar_id
- survey_question_id
- survey_option_id    ← KOLOM INI TIDAK ADA LAGI
- answer_text         ← KOLOM INI TIDAK ADA LAGI
- timestamps

SurveyAnswer model relationships:
- option()            ← RELATIONSHIP INI TIDAK ADA LAGI
```

#### NEW Schema (✅ Yang sekarang digunakan)
```
survey_answers table:
- id
- pendaftar_id
- survey_question_id
- jawaban             ← TEXT/JSON field untuk semua jenis jawaban
- timestamps

SurveyAnswer model relationships:
- pendaftar()
- question()
```

### File yang Menyebabkan Error

**File**: `resources/views/livewire/admin/partials/survey-responses.blade.php`

**3 Error di File Ini:**

1. **Line 88** - Mengakses relationship yang tidak ada:
   ```blade
   <!-- SALAH ❌ -->
   {{ $answer->option->option_text ?? '-' }}
   ```

2. **Line 128** - Eager load relationship yang tidak ada:
   ```php
   <!-- SALAH ❌ -->
   ->with('option')
   ```

3. **Line 143** - Menggunakan column yang tidak ada:
   ```php
   <!-- SALAH ❌ -->
   $questionAnswers->where('survey_option_id', $option->id)
   ```

---

## ✅ SOLUSI YANG DITERAPKAN

### 1. Fix Line 85 - Gunakan Helper Method

**Before:**
```blade
@if($answer->question->question_type === 'text')
    {{ $answer->answer_text }}
@else
    {{ $answer->option->option_text ?? '-' }}
@endif
```

**After:**
```blade
{{ $answer->getJawabanFormatted() }}
```

**Penjelasan:**
- Helper method `getJawabanFormatted()` sudah menangani semua jenis jawaban
- Otomatis convert array ke comma-separated string untuk checkbox
- Lebih simple dan tidak perlu cek tipe question

---

### 2. Fix Line 124 - Hapus Eager Loading yang Salah

**Before:**
```php
$questionAnswers = \App\Models\SurveyAnswer::where('survey_question_id', $question->id)
    ->with('option')  // ❌ Relationship tidak ada
    ->get();
```

**After:**
```php
$questionAnswers = \App\Models\SurveyAnswer::where('survey_question_id', $question->id)
    ->get();
```

**Penjelasan:**
- Relationship `option` tidak ada di SurveyAnswer model
- Eager loading tidak diperlukan karena jawaban sudah tersimpan di field `jawaban`

---

### 3. Fix Line 139-142 - Filter Berdasarkan Jawaban Content

**Before:**
```php
$optionCount = $questionAnswers->where('survey_option_id', $option->id)->count();
```

**After:**
```php
$optionCount = $questionAnswers->filter(function($answer) use ($option) {
    $jawaban = $answer->getJawabanArray();
    return in_array($option->value, $jawaban) || in_array($option->label, $jawaban);
})->count();
```

**Penjelasan:**
- Column `survey_option_id` tidak ada
- Jawaban disimpan sebagai value/label di field `jawaban` (JSON/text)
- Filter dengan `in_array()` untuk cek apakah option dipilih
- Support both `value` dan `label` untuk compatibility

---

### 4. Fix Line 148 - Gunakan Field yang Benar

**Before:**
```blade
{{ $option->option_text }}
```

**After:**
```blade
{{ $option->label }}
```

**Penjelasan:**
- SurveyQuestionOption model menggunakan field `label`, bukan `option_text`
- Field structure: `label` (display text) dan `value` (stored value)

---

## 📦 FILE YANG PERLU DI-UPLOAD

### Total: 7 Files

| # | File | Kategori | Keterangan |
|---|------|----------|------------|
| 1 | `app/Traits/WithSorting.php` | Trait | Sorting functionality untuk table |
| 2 | `app/Models/SurveyQuestion.php` | Model | Dengan scope: umum(), aktif(), ordered() |
| 3 | `app/Models/SurveyQuestionOption.php` | Model | Model untuk opsi jawaban |
| 4 | `app/Models/SurveyAnswer.php` | Model | Model jawaban dengan helper methods |
| 5 | `app/Livewire/Admin/SurveyManagement.php` | **Controller** | **2 FIX: Filter context + eager loading constraint** |
| 6 | `resources/views/livewire/admin/survey-management.blade.php` | View | Main view survey management |
| 7 | `resources/views/livewire/admin/partials/survey-responses.blade.php` | **View** | **🔴 CRITICAL - File yang menyebabkan error 500!** |

### Penjelasan Fix di Controller (#5):

**Fix #1: Filter Context Umum**
- Line 72: Query questions filter `umum()` saja
- Line 95: `$allQuestions` filter `umum()` untuk ringkasan

**Fix #2: Eager Loading Constraint (BARU!)**
- Line 100-105: Filter `surveyAnswers` hanya jawaban survey umum
- Mencegah jawaban survey tes psikologi muncul di responses
- Sebelumnya: jawaban tercampur (umum + tes psikologi)
- Sesudahnya: hanya jawaban survey umum

---

## 🚀 CARA UPLOAD

### Opsi 1: AUTO UPLOAD (Recommended)

```bash
upload_fix_survey_error_500.bat
```

**Apa yang dilakukan script:**
1. Upload 7 files ke production
2. Set permissions (chmod 644)
3. Clear cache (optimize:clear, view:clear, config:clear)
4. Waktu: ~6-8 menit

---

### Opsi 2: MANUAL UPLOAD

```bash
# 1. Upload Trait
scp app/Traits/WithSorting.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Traits/

# 2. Upload Models
scp app/Models/SurveyQuestion.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/
scp app/Models/SurveyQuestionOption.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/
scp app/Models/SurveyAnswer.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/

# 3. Upload Controller
scp app/Livewire/Admin/SurveyManagement.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/

# 4. Upload Views
scp resources/views/livewire/admin/survey-management.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
scp resources/views/livewire/admin/partials/survey-responses.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/

# 5. Set Permissions & Clear Cache
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && chmod 644 app/Traits/WithSorting.php && chmod 644 app/Models/SurveyQuestion.php && chmod 644 app/Models/SurveyQuestionOption.php && chmod 644 app/Models/SurveyAnswer.php && chmod 644 app/Livewire/Admin/SurveyManagement.php && chmod 644 resources/views/livewire/admin/survey-management.blade.php && chmod 644 resources/views/livewire/admin/partials/survey-responses.blade.php && php artisan optimize:clear && php artisan view:clear && php artisan config:clear"
```

---

## 🧪 TESTING CHECKLIST

### Test Setelah Upload:

1. **Akses Halaman Survey**
   - [ ] Buka `https://pmbm.mtsn2kotamalang.sch.id/admin/survey`
   - [ ] Halaman terbuka tanpa error 500
   - [ ] Tidak ada error di console browser
   - [ ] Tidak ada error di `storage/logs/laravel.log`

2. **Stats Cards**
   - [ ] 4 kartu statistik muncul:
     - Total Pertanyaan
     - Pertanyaan Aktif
     - Total Opsi
     - Total Responden
   - [ ] Angka statistik sesuai data

3. **Tab Navigation**
   - [ ] Tab "Settings" ada dan bisa diklik
   - [ ] Tab "Responses" ada dan bisa diklik
   - [ ] Klik tab tidak error

4. **Tab Settings - Question Management**
   - [ ] Tabel pertanyaan survey muncul
   - [ ] Kolom: Pertanyaan, Tipe Input, Wajib, Urutan, Status, Aksi
   - [ ] Tombol "Tambah Pertanyaan" berfungsi
   - [ ] Bisa tambah pertanyaan baru
   - [ ] Bisa edit pertanyaan existing
   - [ ] Bisa hapus pertanyaan
   - [ ] Sorting table bekerja (klik header kolom)

5. **Tab Settings - Option Management**
   - [ ] Klik "Kelola Opsi" pada pertanyaan multiple choice
   - [ ] List opsi muncul dengan benar
   - [ ] Tombol "Tambah Opsi" berfungsi
   - [ ] Bisa tambah opsi baru
   - [ ] Bisa edit opsi existing
   - [ ] Bisa hapus opsi

6. **Tab Responses - View Answers** (FILE YANG DI-FIX!)
   - [ ] **CRITICAL**: Tab Responses terbuka tanpa error
   - [ ] Tabel responden muncul
   - [ ] Kolom jawaban menampilkan data dengan format benar
   - [ ] Jawaban text muncul sebagai text
   - [ ] Jawaban multiple choice muncul sebagai label opsi
   - [ ] Jawaban checkbox multiple muncul comma-separated
   - [ ] **PENTING**: Cek jawaban HANYA dari survey umum (tidak ada jawaban survey tes psikologi!)
   - [ ] Untuk pendaftar yang isi survey umum + tes psikologi, hanya survey umum yang muncul
   - [ ] **Ringkasan Jawaban** section muncul
   - [ ] Bar chart untuk setiap opsi muncul dengan persentase benar
   - [ ] Bar chart HANYA untuk pertanyaan survey umum (tidak ada pertanyaan tes psikologi!)
   - [ ] Pagination bekerja

7. **Verifikasi Filter Context**
   - [ ] Pilih pendaftar yang sudah isi Survey Umum + Survey Tes Psikologi
   - [ ] Di `/admin/survey?mainTab=responses`, hanya jawaban Survey Umum yang muncul
   - [ ] Jumlah jawaban = jumlah pertanyaan Survey Umum (misal: 3 jawaban)
   - [ ] Tidak ada jawaban Survey Tes Psikologi (misal: tidak ada 5 jawaban tambahan dari tes psikologi)
   - [ ] Di `/admin/tes-psikologi?mainTab=data`, hanya jawaban Survey Tes Psikologi yang muncul
   - [ ] Data tidak tercampur antara kedua halaman

---

## ⚠️ TROUBLESHOOTING

### Jika Masih Error 500 Setelah Upload

#### 1. Clear View Cache Agresif

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Hapus compiled views
rm -rf storage/framework/views/*.php

# Clear all caches
php artisan optimize:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

**Kenapa ini penting:**
- File `survey-responses.blade.php` adalah VIEW
- Laravel compile blade views dan cache hasilnya
- Tanpa clear cache, Laravel mungkin masih gunakan compiled view lama

---

#### 2. Cek Log Error Baru

```bash
tail -100 storage/logs/laravel.log | grep -A 30 "survey\|Survey"
```

**Lihat:**
- Apakah masih ada error "undefined relationship [option]"?
- Apakah ada error baru?

---

#### 3. Cek Database Tables Ada

```bash
php artisan tinker
>>> \DB::table('survey_questions')->count();
>>> \DB::table('survey_question_options')->count();
>>> \DB::table('survey_answers')->count();
```

**Expected:**
- Ketiga tabel ada dan bisa di-query
- Jika error "Table doesn't exist", perlu run migration

---

#### 4. Cek Column 'context' Ada

```bash
php artisan tinker
>>> \Schema::hasColumn('survey_questions', 'context');
// Should return: true
```

**Jika false:**
```bash
php artisan migrate --path=database/migrations/2026_01_28_100000_add_context_to_survey_questions_table.php --force
```

---

#### 5. Verify File Upload Success

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Cek file sizes
ls -lh app/Traits/WithSorting.php
ls -lh resources/views/livewire/admin/partials/survey-responses.blade.php

# Cek file content (baris critical)
grep "getJawabanFormatted" resources/views/livewire/admin/partials/survey-responses.blade.php
```

**Expected:**
- File sizes > 0 bytes
- `getJawabanFormatted` muncul di grep result

---

## 🔧 FIX #2: FILTER CONTEXT DI EAGER LOADING

### Masalah Ditemukan

Di halaman `/admin/survey?mainTab=responses&responsesPage=3`, jawaban dari **Survey Tes Psikologi** ikut muncul bercampur dengan jawaban **Survey Umum**.

**Seharusnya:**
- `/admin/survey` → HANYA Survey Umum
- `/admin/tes-psikologi` → HANYA Survey Tes Psikologi
- Data tidak tercampur

---

### Root Cause #2

**File:** `app/Livewire/Admin/SurveyManagement.php` Line 99-105

**Before Fix:**
```php
$responsesQuery = \App\Models\Pendaftar::with([
    'surveyAnswers.question'  // ❌ Load SEMUA jawaban (umum + tes psikologi)!
])->whereHas('surveyAnswers', function($q) {
    $q->whereHas('question', function($q2) {
        $q2->umum(); // ← Hanya filter pendaftar, tapi tetap load semua jawaban!
    });
});
```

**Masalah:**
1. `whereHas()` hanya filter **pendaftar** yang punya jawaban survey umum
2. `with('surveyAnswers')` tetap load **SEMUA jawaban** (umum + tes psikologi)
3. Di view, loop `@foreach($pendaftar->surveyAnswers)` menampilkan semua jawaban

---

### Fix Applied

**After Fix:**
```php
$responsesQuery = \App\Models\Pendaftar::with([
    'surveyAnswers' => function($query) {
        // FILTER: Hanya jawaban untuk survey UMUM, bukan tes psikologi
        $query->whereHas('question', function($q) {
            $q->umum();
        });
    },
    'surveyAnswers.question'
])->whereHas('surveyAnswers', function($q) {
    $q->whereHas('question', function($q2) {
        $q2->umum();
    });
});
```

**Penjelasan:**
- Tambahkan closure constraint di `with('surveyAnswers')`
- Filter hanya jawaban yang `question->context = 'umum'`
- Sekarang eager loading hanya load jawaban survey umum
- Survey tes psikologi tidak akan muncul

---

### Impact of Fix #2

**Before:**
```
Pendaftar #1
├─ Survey Umum Jawaban: 3 jawaban ✓
└─ Survey Tes Psikologi Jawaban: 5 jawaban ❌ (SEHARUSNYA TIDAK MUNCUL!)

Display: 8 jawaban (tercampur!)
```

**After:**
```
Pendaftar #1
└─ Survey Umum Jawaban: 3 jawaban ✓

Display: 3 jawaban (hanya umum!)
```

---

## 📊 PERBANDINGAN SEBELUM & SESUDAH

### Sebelum Fix

```blade
<!-- ❌ ERROR: Call to undefined relationship [option] -->
{{ $answer->option->option_text ?? '-' }}
```

**Result:** Error 500, halaman tidak bisa dibuka

---

### Sesudah Fix

```blade
<!-- ✅ BENAR: Menggunakan helper method -->
{{ $answer->getJawabanFormatted() }}
```

**Result:**
- Halaman terbuka normal
- Jawaban ditampilkan dengan benar
- Support semua tipe input (text, radio, checkbox, select)
- Format otomatis (comma-separated untuk multiple)

---

## 📝 SUMMARY

| Aspek | Detail |
|-------|--------|
| **Root Cause #1** | View lama menggunakan relationship `option` yang tidak ada |
| **Root Cause #2** | Eager loading tidak filter context, jawaban tercampur |
| **File Critical** | `survey-responses.blade.php` + `SurveyManagement.php` |
| **Total Files** | 7 files |
| **Total Fixes** | 2 issues (error 500 + filter context) |
| **Schema Change** | Dari `survey_option_id` + `answer_text` → `jawaban` (JSON) |
| **Migration Needed** | ❌ NO (tabel sudah ada) |
| **Cache Clear** | ✅ YES (CRITICAL!) |
| **Risk Level** | 🟢 LOW |
| **Estimated Time** | 6-8 menit |
| **Testing Priority** | Tab "Responses" - view jawaban survey + filter context |

---

## ✅ APPROVAL

**Ready to Upload**: ✅ YES
**Tested Locally**: ✅ YES
**Root Cause Identified**: ✅ YES
**Fix Verified**: ✅ YES
**Documentation Complete**: ✅ YES
**Backup Required**: ⬜ NO (no database changes)
**Production Impact**: 🟢 MINIMAL (hanya /admin/survey)

---

## 📚 REFERENSI

**Documentation Files:**
- `FIX_SURVEY_ERROR_500_PRODUCTION.md` - Detail lengkap semua file
- `upload_fix_survey_error_500.bat` - Batch script upload otomatis
- `FIX_SURVEY_ERROR_500_COMPLETE_ANALYSIS.md` - Dokumen ini

**Model Files:**
- `app/Models/SurveyAnswer.php` - Helper methods: `getJawabanArray()`, `getJawabanFormatted()`
- `app/Models/SurveyQuestion.php` - Scopes: `umum()`, `aktif()`, `ordered()`, `tesPsikologi()`
- `app/Models/SurveyQuestionOption.php` - Fields: `label`, `value`, `urutan`

**Migration Files:**
- `2026_01_28_000001_create_survey_questions_table.php`
- `2026_01_28_000002_create_survey_question_options_table.php`
- `2026_01_28_000003_create_survey_answers_table.php`
- `2026_01_28_100000_add_context_to_survey_questions_table.php`

---

**Dibuat oleh**: Claude Code AI Assistant
**Tanggal**: 5 Februari 2026
**Versi**: 2.0 - Complete Analysis

---

## 🎯 NEXT STEPS

1. **Jalankan batch script:**
   ```bash
   upload_fix_survey_error_500.bat
   ```

2. **Tunggu upload selesai** (~6-8 menit)

3. **Test halaman /admin/survey:**
   - Akses URL
   - Cek tab Settings
   - **Cek tab Responses** (yang di-fix!)

4. **Verify di log:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

5. **Jika sukses**, tandai sebagai ✅ DEPLOYED

---

**SELESAI - READY TO DEPLOY** ✅
