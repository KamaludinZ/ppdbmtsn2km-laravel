# 📋 PENJELASAN: Dua Sistem Survey dalam Aplikasi PPDB

**Tanggal:** 28 Januari 2026
**Status:** ✅ KEDUA SISTEM SUDAH DIPERBAIKI

---

## 🎯 Overview

Aplikasi PPDB ini memiliki **2 (dua) sistem survey yang berbeda** dengan tujuan yang berbeda pula:

### 1. Survey Umum Pendaftaran (`/admin/survey`)
**Lokasi:** `SurveyManagement.php`
**Tujuan:** Survey umum untuk semua pendaftar
**Tampil Di:** Tab "Survey" pada detail pendaftar (`/admin/pendaftar/27`)

### 2. Survey Tes Psikologi (`/admin/tes-psikologi?mainTab=survey`)
**Lokasi:** `TesPsikologi.php`
**Tujuan:** Survey khusus untuk tes psikologi
**Tampil Di:** Tab "Pertanyaan Survey" di halaman Tes Psikologi

---

## 📊 Perbandingan Kedua Sistem

| Aspek | Survey Umum | Survey Tes Psikologi |
|-------|-------------|---------------------|
| **Route** | `/admin/survey` | `/admin/tes-psikologi?mainTab=survey` |
| **Livewire Class** | `Admin\SurveyManagement` | `Admin\TesPsikologi` |
| **View** | `livewire/admin/survey-management.blade.php` | `livewire/admin/partials/tes-psikologi-survey.blade.php` |
| **Database Table** | `survey_questions` (shared) | `survey_questions` (shared) |
| **Toggle Aktif** | Per pertanyaan (`aktif`) | Global (`survey_aktif` di settings) |
| **Validasi Upload** | Tidak | Ya (block upload bukti) |
| **Tampil di Detail** | Ya (tab-survey.blade.php) | Tidak |
| **Export** | Terpisah | Terintegrasi di PendaftarExport |

---

## 🗄️ Database Schema (Shared)

Kedua sistem menggunakan **table yang sama** dengan **pemisahan context**:

```sql
survey_questions:
  - id
  - pertanyaan (TEXT)
  - deskripsi (TEXT, nullable)
  - tipe_input (ENUM: 'text', 'textarea', 'radio', 'checkbox', 'select')
  - wajib_diisi (BOOLEAN, default: false)
  - urutan (INTEGER, default: 0)
  - aktif (BOOLEAN, default: true)
  - context (ENUM: 'umum', 'tes_psikologi', default: 'umum') ← NEW! Separator
  - created_at
  - updated_at

survey_question_options:
  - id
  - survey_question_id (FK)
  - label (VARCHAR)
  - value (VARCHAR)
  - urutan (INTEGER, default: 0)
  - created_at
  - updated_at

survey_answers:
  - id
  - pendaftar_id (FK)
  - survey_question_id (FK)
  - jawaban (TEXT/JSON)
  - created_at
  - updated_at
  - UNIQUE(pendaftar_id, survey_question_id)

tes_psikologi_settings:
  - ...existing fields...
  - survey_aktif (BOOLEAN)
  - survey_informasi (TEXT, nullable)
```

---

## 🔧 Perbedaan Teknis

### Survey Umum (`SurveyManagement.php`)

#### Features:
- ✅ CRUD pertanyaan dengan modal
- ✅ CRUD opsi jawaban
- ✅ Tab untuk melihat responses
- ✅ Statistics dashboard
- ✅ Filter per pertanyaan
- ✅ Sorting & pagination
- ✅ Tampil di detail pendaftar

#### UI Location:
```php
Route: /admin/survey
View: resources/views/livewire/admin/survey-management.blade.php
Template: resources/views/tab_templates/tab-survey.blade.php (untuk detail pendaftar)
```

#### Key Methods:
```php
// Question CRUD
createQuestion()
editQuestion($id)
storeQuestion()
updateQuestion()
deleteQuestion($id)

// Option CRUD
createOption()
editOption($id)
storeOption()
updateOption()
deleteOption($id)

// UI
manageOptions($questionId)
setMainTab($tab)  // 'responses' or 'settings'
```

---

### Survey Tes Psikologi (`TesPsikologi.php`)

#### Features:
- ✅ CRUD pertanyaan inline (no modal)
- ✅ CRUD opsi jawaban inline
- ✅ Global toggle (`survey_aktif`)
- ✅ Survey informasi text
- ✅ **Validasi:** Block upload bukti jika belum isi
- ✅ Modal lihat jawaban pendaftar
- ✅ Export terintegrasi

#### UI Location:
```php
Route: /admin/tes-psikologi?mainTab=survey
View: resources/views/livewire/admin/partials/tes-psikologi-survey.blade.php
```

#### Key Methods:
```php
// Survey Settings
simpanPengaturanSurvey()

// Question CRUD
simpanSurvey()
editSurvey($id)
hapusSurvey($id)

// Option CRUD
addSurveyOption()
removeSurveyOption($index)
simpanOpsiSurvey()

// View Answers
openSurveyAnswersModal($pendaftarId)
closeSurveyAnswersModal()
```

---

## 🎨 Perbedaan UI/UX

### Survey Umum:
```
┌─────────────────────────────────────┐
│ Manajemen Survey                     │
├─────────────────────────────────────┤
│ [Responses Tab] [Settings Tab]      │
│                                      │
│ Statistics:                          │
│ - Total Questions: 10                │
│ - Active Questions: 8                │
│ - Total Options: 45                  │
│ - Total Responses: 120               │
│                                      │
│ ┌───────────────────────────────┐   │
│ │ Question 1 ▼                  │   │
│ │ [Edit] [Delete] [Manage Opts] │   │
│ └───────────────────────────────┘   │
│                                      │
│ [+ Tambah Pertanyaan]                │
└─────────────────────────────────────┘

Modal untuk Edit/Create pertanyaan
```

### Survey Tes Psikologi:
```
┌─────────────────────────────────────┐
│ Tes Psikologi                        │
├─────────────────────────────────────┤
│ [Pengaturan] [Data Psikologi]       │
│ [➜ Pertanyaan Survey] ← NEW TAB     │
│                                      │
│ ┌─────────────────────────────────┐ │
│ │ Pengaturan Survey               │ │
│ │ Survey Aktif: [✓] ON            │ │
│ │ Informasi: [____________]       │ │
│ │ [Simpan Pengaturan]             │ │
│ └─────────────────────────────────┘ │
│                                      │
│ ┌─────────────────────────────────┐ │
│ │ Form Pertanyaan (Inline)        │ │
│ │ Pertanyaan: [____________]      │ │
│ │ Tipe: [select ▼]                │ │
│ │ [Simpan]  [Kelola Opsi]         │ │
│ └─────────────────────────────────┘ │
│                                      │
│ ┌─────────────────────────────────┐ │
│ │ Daftar Pertanyaan               │ │
│ │ 1. Motivasi Anda? [Edit][Del]   │ │
│ │ 2. Sumber info? [Edit][Del]     │ │
│ └─────────────────────────────────┘ │
└─────────────────────────────────────┘

Semua inline, no modal
```

---

## 🔄 Flow Penggunaan

### Survey Umum (Detail Pendaftar)

1. Admin buat pertanyaan di `/admin/survey`
2. Pertanyaan aktif otomatis muncul di detail pendaftar
3. Pendaftar isi survey via form di detail
4. Admin lihat jawaban di tab "Responses" di `/admin/survey`
5. **Tidak** memblokir proses apapun

### Survey Tes Psikologi

1. Admin aktifkan survey di `/admin/tes-psikologi?mainTab=survey`
2. Admin buat pertanyaan
3. Pendaftar buka `/pendaftar/tes-psikologi`
4. **Wajib isi survey** sebelum bisa upload bukti pembayaran
5. Admin lihat jawaban:
   - Via modal di tab "Data Psikologi"
   - Via export Excel
6. **Memblokir** upload bukti jika belum isi

---

## 🐛 Bug Fixes yang Dilakukan

### Survey Umum (`SurveyManagement.php`)

Semua field names telah diupdate dari schema lama ke baru:

#### Before (WRONG):
```php
$question->question_text;     // ❌
$question->question_type;     // ❌
$question->is_required;       // ❌
$question->is_active;         // ❌
$question->order;             // ❌
$option->option_text;         // ❌
```

#### After (CORRECT):
```php
$question->pertanyaan;        // ✅
$question->tipe_input;        // ✅
$question->wajib_diisi;       // ✅
$question->aktif;             // ✅
$question->urutan;            // ✅
$option->label;               // ✅
$option->value;               // ✅
```

#### Model References Fixed:
```php
// Before
use App\Models\SurveyOption;                    // ❌
SurveyOption::create([...]);                    // ❌

// After
use App\Models\SurveyQuestionOption;            // ✅
SurveyQuestionOption::create([...]);            // ✅
```

#### Scope Methods Fixed:
```php
// Before
SurveyQuestion::active()->ordered()             // ❌

// After
SurveyQuestion::aktif()->ordered()              // ✅
```

---

### Survey Tes Psikologi (Already Fixed)

File `PendaftarExport.php` dan template sudah menggunakan field names yang benar:
- ✅ `pertanyaan` instead of `question_text`
- ✅ `aktif` instead of `is_active`
- ✅ `SurveyQuestion::aktif()` instead of `active()`

---

## 📁 File Locations

### Survey Umum
```
Controller:
  app/Livewire/Admin/SurveyManagement.php

Views:
  resources/views/livewire/admin/survey-management.blade.php
  resources/views/tab_templates/tab-survey.blade.php (detail pendaftar)

Routes:
  Route::get('/admin/survey', SurveyManagement::class)
    ->middleware(['auth', 'role:admin'])
    ->name('admin.survey');
```

### Survey Tes Psikologi
```
Controller:
  app/Livewire/Admin/TesPsikologi.php
  app/Livewire/Pendaftar/TesPsikologi.php
  app/Livewire/Panitia/TesPsikologi.php

Views:
  resources/views/livewire/admin/tes-psikologi.blade.php
  resources/views/livewire/admin/partials/tes-psikologi-survey.blade.php
  resources/views/livewire/admin/partials/tes-psikologi-data.blade.php (modal)
  resources/views/livewire/pendaftar/tes-psikologi.blade.php

Export:
  app/Exports/PendaftarExport.php (include survey columns)

Routes:
  Route::get('/admin/tes-psikologi', TesPsikologi::class)
    ->middleware(['auth', 'role:admin,panitia_tes_psikologi'])
    ->name('admin.tes-psikologi');
```

---

## 🎯 Kapan Menggunakan Yang Mana?

### Gunakan Survey Umum (`/admin/survey`) untuk:
- ✅ Survey general pendaftaran
- ✅ Pertanyaan tambahan non-blocking
- ✅ Data yang tidak wajib
- ✅ Dapat diisi kapan saja

### Gunakan Survey Tes Psikologi (`/admin/tes-psikologi`) untuk:
- ✅ Survey khusus tes psikologi
- ✅ Pertanyaan yang wajib dijawab
- ✅ **Memblokir proses** jika belum diisi
- ✅ Terintegrasi dengan payment flow

---

## ⚠️ Important Notes

### Shared Database Table with Context Separation
Kedua sistem menggunakan **table yang sama** (`survey_questions`) dengan **kolom context untuk pemisahan**:
- ✅ **Sudah terpisah otomatis** melalui column `context`
- ✅ Survey Umum: `context = 'umum'`
- ✅ Survey Tes Psikologi: `context = 'tes_psikologi'`
- ✅ **Aman menghapus** pertanyaan - tidak akan affect sistem lain
- ✅ **Tidak ada konflik** - sudah difilter di semua query

### Toggle Aktif
- **Survey Umum:** Per-question toggle (`aktif` field)
- **Survey Tes Psikologi:** Global toggle (`survey_aktif` di settings)

### Validasi
- **Survey Umum:** No validation, optional
- **Survey Tes Psikologi:** **Validation blocking** - wajib isi sebelum upload

---

## 🧪 Testing Checklist

### Survey Umum (`/admin/survey`)
- [x] Buka `/admin/survey` - no error
- [x] Create pertanyaan - berhasil
- [x] Edit pertanyaan - berhasil
- [x] Delete pertanyaan - berhasil
- [x] Create opsi - berhasil
- [x] Edit opsi - berhasil
- [x] Delete opsi - berhasil
- [x] Tab responses - tampil
- [x] Statistics - update correct
- [x] Detail pendaftar - tab survey muncul

### Survey Tes Psikologi (`/admin/tes-psikologi`)
- [x] Buka `/admin/tes-psikologi?mainTab=survey` - no error
- [x] Toggle survey aktif - working
- [x] Create pertanyaan - berhasil
- [x] Edit pertanyaan - berhasil
- [x] Delete pertanyaan - berhasil
- [x] Kelola opsi - berhasil
- [x] Pendaftar view - form muncul
- [x] Validation blocking - working
- [x] Modal lihat jawaban - tampil
- [x] Export Excel - include columns

---

## ✅ Status: KEDUA SISTEM WORKING & SEPARATED

- ✅ Survey Umum fixed & tested
- ✅ Survey Tes Psikologi fixed & tested
- ✅ **Context separation implemented** (28 Jan 2026)
- ✅ **No conflict between systems** - fully separated by context
- ✅ Migration executed successfully
- ✅ All queries filtered by context
- ✅ Dokumentasi lengkap tersedia
- ✅ **PRODUCTION READY**

**Latest Fix:** Survey Separation with Context Column - see `SURVEY_SEPARATION_COMPLETE.md`

---

**Last Updated:** 28 Januari 2026
**Status:** ✅ COMPLETED & SEPARATED
