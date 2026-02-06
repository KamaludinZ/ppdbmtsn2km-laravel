# FIX ERROR 500 - /admin/survey Production

**Tanggal**: 5 Februari 2026
**Status**: ✅ READY TO UPLOAD

---

## 🔍 MASALAH

Halaman `/admin/survey` di production mengalami error 500.

### Error Details:
- **URL**: `https://pmbm.mtsn2kotamalang.sch.id/admin/survey`
- **Error**: HTTP 500 Internal Server Error
- **Symptom**: Halaman tidak bisa dibuka, blank atau error message

---

## 🎯 ROOT CAUSE

**ACTUAL ERROR (from production log):**
```
Call to undefined relationship [option] on model [App\Models\SurveyAnswer]
```

**Root Cause Analysis:**

Production menggunakan VIEW LAMA (`survey-responses.blade.php`) yang mencoba mengakses relationship `option` yang TIDAK ADA di model SurveyAnswer.

### Schema Changes:

**OLD Schema (yang diharapkan oleh view production):**
- SurveyAnswer memiliki column `survey_option_id`
- SurveyAnswer memiliki column `answer_text`
- SurveyAnswer memiliki relationship `option()`

**NEW Schema (yang sekarang digunakan):**
- SurveyAnswer hanya punya column `jawaban` (text/JSON)
- TIDAK ada column `survey_option_id`
- TIDAK ada column `answer_text`
- TIDAK ada relationship `option()`
- Jawaban disimpan sebagai JSON dalam field `jawaban`

### Missing Files:
1. ❌ `app/Traits/WithSorting.php` - Trait untuk sorting data
2. ❌ `app/Models/SurveyQuestion.php` - Model updated dengan scope baru
3. ❌ `app/Models/SurveyQuestionOption.php` - Model opsi jawaban
4. ❌ `app/Models/SurveyAnswer.php` - Model jawaban pendaftar
5. ❌ `app/Livewire/Admin/SurveyManagement.php` - Controller updated
6. ❌ `resources/views/livewire/admin/survey-management.blade.php` - View updated
7. ❌ **`resources/views/livewire/admin/partials/survey-responses.blade.php`** - **CRITICAL FIX**

###Technical Details:

**Controller (`SurveyManagement.php`) line 15:**
```php
use WithPagination, WithSorting; // ← WithSorting trait tidak ada di production
```

**Controller line 72:**
```php
$query = SurveyQuestion::umum()->with('options'); // ← Scope umum() tidak ada
$questions = $this->applySorting($query)->paginate(10); // ← Method dari WithSorting
```

**Scope yang missing di Model:**
- `scopeUmum()` - Filter survey context 'umum'
- `scopeAktif()` - Filter survey aktif
- `scopeOrdered()` - Sort by urutan

---

## ✅ SOLUSI

Upload 7 files yang missing/outdated ke production.

### File yang Perlu Di-upload:

#### 1. **app/Traits/WithSorting.php** (NEW)
**Trait untuk sorting data dengan Livewire**

**Key Methods:**
```php
public $sortField = 'created_at';
public $sortDirection = 'desc';

public function sortBy($field) { ... }
protected function applySorting($query) { ... }
public function getSortIcon($field) { ... }
```

**Fungsi**:
- Menyediakan sorting functionality untuk table
- Toggle sort direction (asc/desc)
- Reset page saat sorting berubah

---

#### 2. **app/Models/SurveyQuestion.php** (UPDATED)
**Model dengan scope tambahan**

**New Scopes:**
```php
// Scope: Filter pertanyaan aktif
public function scopeAktif($query) {
    return $query->where('aktif', true);
}

// Scope: Sort by urutan
public function scopeOrdered($query) {
    return $query->orderBy('urutan');
}

// Scope: Filter survey umum
public function scopeUmum($query) {
    return $query->where('context', 'umum');
}

// Scope: Filter survey tes psikologi
public function scopeTesPsikologi($query) {
    return $query->where('context', 'tes_psikologi');
}
```

**Relationships:**
```php
public function options() {
    return $this->hasMany(SurveyQuestionOption::class)->orderBy('urutan');
}

public function answers() {
    return $this->hasMany(SurveyAnswer::class);
}
```

---

#### 3. **app/Models/SurveyQuestionOption.php**
**Model untuk opsi jawaban survey**

**Fillable:**
- survey_question_id
- label
- value
- urutan

**Relationship:**
```php
public function question() {
    return $this->belongsTo(SurveyQuestion::class, 'survey_question_id');
}
```

---

#### 4. **app/Models/SurveyAnswer.php**
**Model untuk jawaban pendaftar**

**Fillable:**
- pendaftar_id
- survey_question_id
- jawaban

**Casts:**
```php
protected $casts = [
    'jawaban' => 'array', // JSON to array for checkbox
];
```

**Relationships:**
```php
public function pendaftar() {
    return $this->belongsTo(Pendaftar::class);
}

public function question() {
    return $this->belongsTo(SurveyQuestion::class, 'survey_question_id');
}
```

**Helper Methods:**
- `getJawabanArray()` - Get jawaban as array
- `getJawabanFormatted()` - Get jawaban as formatted string

---

#### 5. **app/Livewire/Admin/SurveyManagement.php** (UPDATED)
**Controller dengan fitur:**

**Features:**
- ✅ Filter by context ('umum' vs 'tes_psikologi')
- ✅ Stats calculation (total questions, active, options, responses)
- ✅ Question CRUD operations
- ✅ Option CRUD operations
- ✅ Response viewing
- ✅ Sorting dengan WithSorting trait

**Key Logic (line 69-116):**
```php
public function render()
{
    // Filter by context = 'umum' untuk Survey Umum
    $query = SurveyQuestion::umum()->with('options');
    $questions = $this->applySorting($query)->paginate(10);

    $stats = [
        'total_questions' => SurveyQuestion::umum()->count(),
        'active_questions' => SurveyQuestion::umum()->where('aktif', true)->count(),
        'total_options' => SurveyQuestionOption::whereHas('question', function($q) {
            $q->umum();
        })->count(),
        'total_responses' => SurveyAnswer::whereHas('question', function($q) {
            $q->umum();
        })->distinct('pendaftar_id')->count('pendaftar_id'),
    ];

    // ... more code
}
```

---

#### 6. **resources/views/livewire/admin/survey-management.blade.php** (UPDATED)
**View dengan UI components:**

**Components:**
- 4 Stats cards (Total Pertanyaan, Aktif, Opsi, Responden)
- Tab navigation (Settings, Responses)
- Question management table with sorting
- Add/Edit question modal
- Add/Edit option modal
- Response viewing table

---

#### 7. **resources/views/livewire/admin/partials/survey-responses.blade.php** (CRITICAL FIX)
**Partial view untuk tab "Responses" - INI FILE YANG MENYEBABKAN ERROR 500**

**Masalah di Production:**
Production menggunakan view LAMA yang masih memanggil relationship `option` yang tidak ada.

**Error di View Lama:**
```blade
<!-- Line 88 - SALAH -->
{{ $answer->option->option_text ?? '-' }}

<!-- Line 128 - SALAH -->
->with('option')

<!-- Line 143 - SALAH -->
$questionAnswers->where('survey_option_id', $option->id)
```

**Fix yang Diterapkan:**

1. **Line 85** - Gunakan helper method:
```blade
<!-- BENAR -->
{{ $answer->getJawabanFormatted() }}
```

2. **Line 124** - Hapus eager loading yang salah:
```php
// BENAR - tanpa ->with('option')
$questionAnswers = \App\Models\SurveyAnswer::where('survey_question_id', $question->id)
    ->get();
```

3. **Line 139-142** - Filter jawaban by value:
```php
// BENAR - filter by jawaban content
$optionCount = $questionAnswers->filter(function($answer) use ($option) {
    $jawaban = $answer->getJawabanArray();
    return in_array($option->value, $jawaban) || in_array($option->label, $jawaban);
})->count();
```

4. **Line 148** - Gunakan field yang benar:
```blade
<!-- BENAR - gunakan 'label' bukan 'option_text' -->
{{ $option->label }}
```

**Kenapa File Ini CRITICAL:**
- File ini yang LANGSUNG menyebabkan error 500
- Tanpa fix file ini, upload 6 file lain tidak akan menyelesaikan masalah
- Production view cache mungkin masih gunakan file lama

---

## 🔧 BONUS FIX: FILTER CONTEXT DI CONTROLLER

**Masalah Ditemukan Saat Testing:**

Di `/admin/survey?mainTab=responses`, jawaban dari **Survey Tes Psikologi** ikut muncul bercampur dengan jawaban **Survey Umum**.

### Fix Applied di `SurveyManagement.php` Line 100-105

**Before:**
```php
$responsesQuery = \App\Models\Pendaftar::with([
    'surveyAnswers.question'  // ❌ Load SEMUA jawaban!
])->whereHas(...);
```

**After:**
```php
$responsesQuery = \App\Models\Pendaftar::with([
    'surveyAnswers' => function($query) {
        // FILTER: Hanya jawaban survey UMUM
        $query->whereHas('question', function($q) {
            $q->umum();
        });
    },
    'surveyAnswers.question'
])->whereHas(...);
```

**Impact:**
- Sebelumnya: Jawaban survey umum + tes psikologi tercampur
- Sekarang: Hanya jawaban survey umum yang muncul
- `/admin/survey` → Survey Umum saja
- `/admin/tes-psikologi` → Survey Tes Psikologi saja

---

## 📦 CARA UPLOAD

### OPSI 1: Menggunakan Batch Script (RECOMMENDED)

```bash
upload_fix_survey_error_500.bat
```

**Keterangan:**
- Upload 7 files otomatis
- Set permissions otomatis
- Clear cache otomatis (PENTING untuk view!)
- ⏱️ Waktu: ~6-8 menit

---

### OPSI 2: Manual Upload via SCP

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

# 5. Upload CRITICAL Partial View (FIX ERROR 500)
scp resources/views/livewire/admin/partials/survey-responses.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/

# 6. Set permissions & clear cache
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && chmod 644 app/Traits/WithSorting.php && chmod 644 app/Models/SurveyQuestion.php && chmod 644 app/Models/SurveyQuestionOption.php && chmod 644 app/Models/SurveyAnswer.php && chmod 644 app/Livewire/Admin/SurveyManagement.php && chmod 644 resources/views/livewire/admin/survey-management.blade.php && chmod 644 resources/views/livewire/admin/partials/survey-responses.blade.php && php artisan optimize:clear"
```

---

## 🧪 TESTING SETELAH UPLOAD

### Test Checklist:

1. **Akses Halaman Survey**
   - [ ] Buka `https://pmbm.mtsn2kotamalang.sch.id/admin/survey`
   - [ ] Halaman tidak error 500
   - [ ] Halaman terbuka dengan baik

2. **Stats Cards**
   - [ ] 4 kartu statistik muncul:
     - Total Pertanyaan (icon: question mark)
     - Pertanyaan Aktif (icon: check)
     - Total Opsi (icon: list)
     - Total Responden (icon: users)
   - [ ] Angka statistik benar sesuai data

3. **Tab Navigation**
   - [ ] Tab "Settings" ada
   - [ ] Tab "Responses" ada (untuk admin)
   - [ ] Klik tab berpindah dengan baik

4. **Question Management (Tab Settings)**
   - [ ] Tabel pertanyaan survey muncul
   - [ ] Kolom: Pertanyaan, Tipe Input, Wajib, Urutan, Status, Aksi
   - [ ] Tombol "Tambah Pertanyaan" ada
   - [ ] Klik "Tambah Pertanyaan" → Modal muncul
   - [ ] Isi form dan simpan → Pertanyaan baru ditambahkan
   - [ ] Klik "Edit" → Modal edit muncul
   - [ ] Edit dan simpan → Perubahan tersimpan
   - [ ] Sorting tabel bekerja (klik header kolom)

5. **Option Management**
   - [ ] Klik "Kelola Opsi" pada pertanyaan
   - [ ] List opsi muncul
   - [ ] Tombol "Tambah Opsi" ada
   - [ ] Bisa tambah opsi baru
   - [ ] Bisa edit opsi
   - [ ] Bisa hapus opsi

6. **Responses Viewing (Tab Responses)**
   - [ ] Tabel response pendaftar muncul
   - [ ] Data jawaban survey ditampilkan
   - [ ] Pagination bekerja
   - [ ] **PENTING**: Jawaban HANYA dari survey umum (tidak ada jawaban tes psikologi!)
   - [ ] Pilih pendaftar yang isi survey umum + tes psikologi
   - [ ] Pastikan hanya jawaban survey umum yang muncul (misal: 3 jawaban, bukan 8)

7. **Verifikasi Filter Context**
   - [ ] `/admin/survey?mainTab=responses` → Hanya jawaban Survey Umum
   - [ ] `/admin/tes-psikologi?mainTab=data` → Hanya jawaban Survey Tes Psikologi
   - [ ] Data tidak tercampur antara kedua halaman
   - [ ] Jumlah jawaban akurat sesuai context

---

## ⚠️ TROUBLESHOOTING

### Jika Masih Error 500 Setelah Upload:

#### 1. Cek Log Error
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
tail -100 storage/logs/laravel.log | grep -A 20 "survey\|Survey"
```

#### 2. Cek Database Tables
```bash
php artisan tinker
>>> \DB::table('survey_questions')->count();
>>> \DB::table('survey_question_options')->count();
>>> \DB::table('survey_answers')->count();
```

**Expected:**
- Tabel ada dan bisa di-query
- Jika tidak ada, perlu run migration

#### 3. Cek Column 'context' Ada
```bash
php artisan tinker
>>> \Schema::hasColumn('survey_questions', 'context');
// Should return: true
```

**Jika false:**
Run migration: `database/migrations/2026_01_28_100000_add_context_to_survey_questions_table.php`

```bash
php artisan migrate --path=database/migrations/2026_01_28_100000_add_context_to_survey_questions_table.php --force
```

#### 4. Cek Route Exists
```bash
php artisan route:list | grep survey
```

**Expected output:**
```
GET|HEAD  admin/survey ............... admin.survey › App\Livewire\Admin\SurveyManagement
```

#### 5. Clear All Caches (Agresif)
```bash
rm -rf storage/framework/views/*.php
rm -rf storage/framework/cache/*
php artisan optimize:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

---

## 📊 DEPENDENCIES

### Database Tables Required:
✅ `survey_questions` - Tabel pertanyaan (migration: 2026_01_28_000001)
✅ `survey_question_options` - Tabel opsi (migration: 2026_01_28_000002)
✅ `survey_answers` - Tabel jawaban (migration: 2026_01_28_000003)

**Note:** Migration sudah di-run sebelumnya, tinggal pastikan tabel ada.

### Columns Required:
✅ Column `context` di tabel `survey_questions` (migration: 2026_01_28_100000)

---

## 📝 SUMMARY

| Item | Status | Notes |
|------|--------|-------|
| **Root Cause** | ✅ Identified | View lama memanggil relationship yang tidak ada |
| **Files to Upload** | 7 files | Trait, Models, Controller, Views |
| **Risk Level** | 🟢 LOW | No database changes needed |
| **Estimated Time** | 6-8 min | Auto upload + test |
| **Migration Needed** | ❌ NO | Tables already exist |
| **Cache Clear** | ✅ YES | **CRITICAL** for view changes! |

---

## ✅ APPROVAL

**Ready to Upload**: ✅ YES
**Tested Locally**: ✅ YES
**Backup Required**: ⬜ NO (No database changes)
**Production Impact**: 🟢 MINIMAL (Only affects /admin/survey page)

---

**Dibuat oleh**: Claude Code AI Assistant
**Tanggal**: 5 Februari 2026
**Versi**: 1.0
**Referensi**: FIX_SURVEY_ERROR_500_COMPLETE.md (Jan 2026)
