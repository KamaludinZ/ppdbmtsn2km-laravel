# FIX SURVEY ERROR 500 - COMPLETE

## Error yang Ditemukan

```
[2026-02-01 19:47:21] production.ERROR: Call to undefined method App\Models\SurveyQuestion::active()
```

Error ini terjadi karena ada ketidaksesuaian antara kode di `Formulir.php` dengan struktur database dan model yang sebenarnya.

## Root Cause

### 1. Scope Method yang Salah
**Kode lama menggunakan:**
```php
$surveyQuestions = \App\Models\SurveyQuestion::active()->ordered()->get();
```

**Scope yang benar:**
```php
$surveyQuestions = \App\Models\SurveyQuestion::aktif()->umum()->ordered()->get();
```

### 2. Nama Field yang Salah
**Kode lama menggunakan field:**
- `question_text` → seharusnya `pertanyaan`
- `question_type` → seharusnya `tipe_input`
- `is_required` → seharusnya `wajib_diisi`
- `survey_option_id` → tidak ada di database
- `answer_text` → tidak ada di database

**Field yang benar:**
- `pertanyaan` - untuk teks pertanyaan
- `tipe_input` - untuk tipe input (text, textarea, radio, dropdown, checkbox)
- `wajib_diisi` - untuk status required
- `jawaban` - untuk menyimpan jawaban (text, JSON array, atau option ID)

### 3. Struktur Database SurveyAnswer

**Tabel `survey_answers` hanya memiliki:**
- `id`
- `pendaftar_id`
- `survey_question_id`
- `jawaban` (TEXT - di-cast ke array/JSON)
- `timestamps`

**Field `jawaban` digunakan untuk semua tipe jawaban:**
- Text/Textarea: string langsung
- Radio/Dropdown: single option ID
- Checkbox: array of option IDs (JSON)

## Perbaikan yang Dilakukan

### File: `app/Livewire/Pendaftar/Formulir.php`

#### 1. Method `saveDataSurvey()` (Lines 1883-1946)

**SEBELUM:**
```php
public function saveDataSurvey()
{
    // Build validation rules for required questions
    $surveyQuestions = \App\Models\SurveyQuestion::active()->ordered()->get(); // ❌ SALAH
    $validationRules = [];
    $validationMessages = [];

    foreach ($surveyQuestions as $question) {
        if ($question->is_required) { // ❌ SALAH
            $validationRules["surveyAnswers.{$question->id}"] = 'required';
            $validationMessages["surveyAnswers.{$question->id}.required"] = "Pertanyaan '{$question->question_text}' wajib diisi."; // ❌ SALAH
        }
    }

    // ...

    if ($question->question_type === 'text') { // ❌ SALAH
        \App\Models\SurveyAnswer::create([
            'pendaftar_id' => $this->pendaftar->id,
            'survey_question_id' => $question->id,
            'survey_option_id' => null,  // ❌ TIDAK ADA DI DATABASE
            'answer_text' => $answerValue, // ❌ TIDAK ADA DI DATABASE
        ]);
    }
}
```

**SESUDAH:**
```php
public function saveDataSurvey()
{
    // Build validation rules for required questions
    $surveyQuestions = \App\Models\SurveyQuestion::aktif()->umum()->ordered()->get(); // ✅ BENAR
    $validationRules = [];
    $validationMessages = [];

    foreach ($surveyQuestions as $question) {
        if ($question->wajib_diisi) { // ✅ BENAR
            $validationRules["surveyAnswers.{$question->id}"] = 'required';
            $validationMessages["surveyAnswers.{$question->id}.required"] = "Pertanyaan '{$question->pertanyaan}' wajib diisi."; // ✅ BENAR
        }
    }

    // Validate if there are required questions
    if (!empty($validationRules)) {
        $this->validate($validationRules, $validationMessages);
    }

    foreach ($surveyQuestions as $question) {
        $answerValue = $this->surveyAnswers[$question->id] ?? null;

        if ($answerValue) {
            // Delete existing answer
            \App\Models\SurveyAnswer::where('pendaftar_id', $this->pendaftar->id)
                ->where('survey_question_id', $question->id)
                ->delete();

            // Prepare jawaban based on question type
            $jawaban = null;

            if ($question->tipe_input === 'text' || $question->tipe_input === 'textarea') { // ✅ BENAR
                // For text type: store text directly
                $jawaban = $answerValue;
            } elseif ($question->tipe_input === 'checkbox') { // ✅ BENAR
                // For checkbox: store array of selected option IDs
                if (is_array($answerValue)) {
                    $selectedOptions = [];
                    foreach ($answerValue as $optionId => $checked) {
                        if ($checked) {
                            $selectedOptions[] = $optionId;
                        }
                    }
                    $jawaban = $selectedOptions; // Will be auto-cast to JSON
                }
            } else {
                // For dropdown and radio: store single option ID
                $jawaban = $answerValue;
            }

            // Create new answer with jawaban field
            if ($jawaban !== null && (!is_array($jawaban) || !empty($jawaban))) {
                \App\Models\SurveyAnswer::create([
                    'pendaftar_id' => $this->pendaftar->id,
                    'survey_question_id' => $question->id,
                    'jawaban' => $jawaban, // ✅ BENAR - menggunakan field jawaban
                ]);
            }
        }
    }

    $this->editModeSurvey = false;
    $this->dispatch('statusUpdated', ['type' => 'success', 'message' => 'Data survey berhasil diperbarui.']);
}
```

#### 2. Loading Survey Answers (Lines 660-681)

**SEBELUM:**
```php
// Load Survey Answers
$surveyAnswers = $this->pendaftar->surveyAnswers;
foreach ($surveyAnswers as $answer) {
    $questionId = $answer->survey_question_id;
    if ($answer->question->question_type === 'text') { // ❌ SALAH
        $this->surveyAnswers[$questionId] = $answer->answer_text; // ❌ TIDAK ADA
    } elseif ($answer->question->question_type === 'checkbox') { // ❌ SALAH
        // ...
        $this->surveyAnswers[$questionId][$answer->survey_option_id] = true; // ❌ TIDAK ADA
    } else {
        $this->surveyAnswers[$questionId] = $answer->survey_option_id; // ❌ TIDAK ADA
    }
}
```

**SESUDAH:**
```php
// Load Survey Answers
$surveyAnswers = $this->pendaftar->surveyAnswers;
foreach ($surveyAnswers as $answer) {
    $questionId = $answer->survey_question_id;
    if ($answer->question->tipe_input === 'text' || $answer->question->tipe_input === 'textarea') { // ✅ BENAR
        // For text: jawaban contains the text string
        $this->surveyAnswers[$questionId] = $answer->jawaban; // ✅ BENAR
    } elseif ($answer->question->tipe_input === 'checkbox') { // ✅ BENAR
        // For checkbox: jawaban is array of selected option IDs
        if (!isset($this->surveyAnswers[$questionId])) {
            $this->surveyAnswers[$questionId] = [];
        }
        // Convert array of IDs to checkbox format (ID => true)
        $selectedIds = is_array($answer->jawaban) ? $answer->jawaban : [$answer->jawaban];
        foreach ($selectedIds as $optionId) {
            $this->surveyAnswers[$questionId][$optionId] = true;
        }
    } else {
        // For radio/dropdown: jawaban contains single option ID
        $this->surveyAnswers[$questionId] = $answer->jawaban; // ✅ BENAR
    }
}
```

## Instruksi Upload ke Production

### Manual Upload via FTP/SFTP/cPanel:

**File yang harus di-upload:**
```
File lokal:
C:\ppdbmtsn2km-laravel\app\Livewire\Pendaftar\Formulir.php

Upload ke:
/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/Formulir.php
```

**Setelah upload, clear cache via SSH atau Terminal di cPanel:**
```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan view:clear
php artisan cache:clear
php artisan config:clear
```

### Upload via Script (jika SSH bisa):
```batch
cd C:\ppdbmtsn2km-laravel
upload_fix_survey.bat
```

## Testing

Setelah upload, silakan test dengan:
1. Login sebagai pendaftar
2. Buka: https://pmbm.mtsn2kotamalang.sch.id/pendaftar/formulir?activeTab=survey
3. Isi survey dan klik Simpan
4. Seharusnya tidak ada error 500 lagi

## Ringkasan Perubahan

### ✅ Yang Diperbaiki:
1. ✅ Mengubah `active()` → `aktif()`
2. ✅ Menambahkan filter `umum()` untuk survey umum (bukan tes psikologi)
3. ✅ Mengubah `question_type` → `tipe_input`
4. ✅ Mengubah `is_required` → `wajib_diisi`
5. ✅ Mengubah `question_text` → `pertanyaan`
6. ✅ Menghapus field `survey_option_id` dan `answer_text`
7. ✅ Menggunakan field `jawaban` untuk semua tipe jawaban
8. ✅ Menambahkan support untuk `textarea` selain `text`

### 📋 Field yang Benar di Database:

**Tabel `survey_questions`:**
- `pertanyaan` (string) - teks pertanyaan
- `tipe_input` (enum) - text, textarea, radio, dropdown, checkbox
- `wajib_diisi` (boolean) - required atau tidak
- `urutan` (integer) - urutan tampil
- `aktif` (boolean) - aktif atau tidak
- `context` (enum) - umum, tes_psikologi

**Tabel `survey_answers`:**
- `pendaftar_id` (foreign key)
- `survey_question_id` (foreign key)
- `jawaban` (text/json) - untuk semua tipe jawaban

### 🎯 Scope yang Tersedia di Model SurveyQuestion:
- `aktif()` - filter pertanyaan aktif
- `ordered()` - urut berdasarkan urutan
- `umum()` - filter survey umum
- `tesPsikologi()` - filter survey tes psikologi
- `context($context)` - filter berdasarkan context

## File yang Berubah

```
modified:   app/Livewire/Pendaftar/Formulir.php
    - Method saveDataSurvey() (lines 1883-1946)
    - Loading Survey Answers (lines 660-681)
```

## Catatan Penting

- Survey sekarang dipisah berdasarkan `context`:
  - `umum` - Survey pendaftaran umum
  - `tes_psikologi` - Survey khusus tes psikologi

- Field `jawaban` otomatis di-cast ke array (JSON) di model, jadi bisa menyimpan:
  - String untuk text/textarea
  - Integer untuk radio/dropdown (option ID)
  - Array untuk checkbox (multiple option IDs)

## Status

✅ **SELESAI** - Survey error 500 sudah diperbaiki
🔄 **PENDING UPLOAD** - Menunggu upload file ke production
