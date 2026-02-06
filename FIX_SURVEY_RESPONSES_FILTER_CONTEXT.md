# FIX: Survey Responses Filter by Context

**Tanggal**: 5 Februari 2026
**Status**: ✅ FIXED

---

## 🔍 MASALAH DITEMUKAN

Di halaman `/admin/survey?mainTab=responses`, jawaban dari **Survey Tes Psikologi** ikut muncul bercampur dengan jawaban **Survey Umum**.

**Dampak:**
- Data responses tercampur
- Admin tidak bisa membedakan mana jawaban survey umum dan mana survey tes psikologi
- Halaman `/admin/survey` seharusnya HANYA menampilkan survey umum
- Survey tes psikologi punya halaman sendiri di `/admin/tes-psikologi`

---

## 🎯 ROOT CAUSE

### Masalah di Controller

File: `app/Livewire/Admin/SurveyManagement.php` **Line 99-105**

**BEFORE (❌ SALAH):**
```php
$responsesQuery = \App\Models\Pendaftar::with([
    'surveyAnswers.question'  // ← Load SEMUA jawaban (umum + tes psikologi)!
])->whereHas('surveyAnswers', function($q) {
    $q->whereHas('question', function($q2) {
        $q2->umum(); // ← Hanya filter pendaftar, tapi tetap load semua jawaban!
    });
});
```

**Penjelasan Error:**
1. `whereHas()` hanya filter **pendaftar** yang punya jawaban survey umum
2. TAPI `with('surveyAnswers')` tetap load **SEMUA jawaban** pendaftar tersebut (umum + tes psikologi)
3. Akibatnya di view, ketika loop `@foreach($pendaftar->surveyAnswers as $answer)`, semua jawaban muncul

---

## ✅ SOLUSI

### Fix di Controller

**AFTER (✅ BENAR):**
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

**Penjelasan Fix:**
1. Tambahkan closure function di `with('surveyAnswers')`
2. Di dalam closure, filter hanya jawaban yang `question->context = 'umum'`
3. Sekarang eager loading hanya load jawaban survey umum saja
4. Survey tes psikologi tidak akan muncul di responses

---

## 📊 PERBANDINGAN

### Before Fix

**Query Result:**
```
Pendaftar #1
├─ surveyAnswers: [
│  ├─ Jawaban Survey Umum #1 ✓
│  ├─ Jawaban Survey Umum #2 ✓
│  ├─ Jawaban Survey Tes Psikologi #1 ❌ (SEHARUSNYA TIDAK MUNCUL!)
│  └─ Jawaban Survey Tes Psikologi #2 ❌ (SEHARUSNYA TIDAK MUNCUL!)
]
```

**View Display:**
- Menampilkan 4 jawaban (umum + tes psikologi) ❌

---

### After Fix

**Query Result:**
```
Pendaftar #1
├─ surveyAnswers: [
│  ├─ Jawaban Survey Umum #1 ✓
│  └─ Jawaban Survey Umum #2 ✓
]
```

**View Display:**
- Menampilkan 2 jawaban (hanya umum) ✅

---

## 🧪 CARA TEST

### Test Case 1: Tab Responses - Jawaban per Pendaftar

1. **Setup Data:**
   - Login sebagai pendaftar
   - Isi Survey Umum (misal: 3 pertanyaan)
   - Isi Survey Tes Psikologi (misal: 5 pertanyaan)
   - Logout

2. **Test di Admin:**
   - Login sebagai admin
   - Buka `/admin/survey?mainTab=responses`
   - Cari pendaftar tadi di tabel responses

3. **Expected Result:**
   - ✅ Kolom "Jawaban" hanya menampilkan 3 jawaban (dari survey umum)
   - ❌ 5 jawaban survey tes psikologi TIDAK muncul
   - ✅ Total jawaban = 3 (sesuai jumlah pertanyaan survey umum)

4. **Before Fix Result:**
   - ❌ Kolom "Jawaban" menampilkan 8 jawaban (3 umum + 5 tes psikologi)
   - ❌ Data tercampur

---

### Test Case 2: Ringkasan Jawaban (Chart)

1. **Test:**
   - Scroll ke bawah ke section "Ringkasan Jawaban"
   - Lihat bar chart untuk setiap pertanyaan

2. **Expected Result:**
   - ✅ Hanya pertanyaan survey umum yang muncul di chart
   - ✅ Jumlah jawaban akurat (hanya hitung jawaban survey umum)
   - ❌ Pertanyaan survey tes psikologi TIDAK muncul di chart

3. **Verifikasi:**
   - Cek nama pertanyaan di chart
   - Pastikan semua adalah pertanyaan survey umum (context = 'umum')

---

### Test Case 3: Stats Cards

1. **Test:**
   - Lihat 4 kartu statistik di atas:
     - Total Pertanyaan
     - Pertanyaan Aktif
     - Total Opsi
     - Total Responden

2. **Expected Result:**
   - ✅ "Total Responden" = jumlah pendaftar yang sudah isi survey umum
   - ❌ Tidak termasuk pendaftar yang hanya isi survey tes psikologi

---

## 📝 FILE YANG DI-UPDATE

| File | Perubahan |
|------|-----------|
| `app/Livewire/Admin/SurveyManagement.php` | Line 99-111: Tambah filter di eager loading `surveyAnswers` |

---

## 🚀 UPLOAD TO PRODUCTION

File ini sudah termasuk dalam batch upload error 500 survey:

```bash
upload_fix_survey_error_500.bat
```

**Total files:** 7 files (termasuk fix ini)

---

## 📚 TECHNICAL NOTES

### Laravel Eager Loading dengan Constraint

**Syntax:**
```php
Model::with(['relationship' => function($query) {
    $query->where('condition', 'value');
}])
```

**Cara Kerja:**
- Closure function di `with()` memfilter data relationship sebelum di-load
- Berbeda dengan `whereHas()` yang hanya filter parent model
- `with()` + constraint = filter data relationship yang di-load

**Dokumentasi:**
- Laravel Docs: Eager Loading Constraints
- https://laravel.com/docs/10.x/eloquent-relationships#constraining-eager-loads

---

### Survey Context di Database

**Survey Questions Table:**
```
| id | pertanyaan | tipe_input | context | aktif | urutan |
|----|------------|------------|---------|-------|--------|
| 1  | Hobi?      | text       | umum    | 1     | 1      |
| 2  | Cita-cita? | text       | umum    | 1     | 2      |
| 15 | IQ Test Q1 | radio      | tes_psikologi | 1 | 1   |
| 16 | IQ Test Q2 | radio      | tes_psikologi | 1 | 2   |
```

**Survey Answers Table:**
```
| id | pendaftar_id | survey_question_id | jawaban |
|----|--------------|-------------------|---------|
| 1  | 100          | 1                 | "Membaca" |
| 2  | 100          | 2                 | "Dokter" |
| 3  | 100          | 15                | "A" |      ← TES PSIKOLOGI
| 4  | 100          | 16                | "B" |      ← TES PSIKOLOGI
```

**Filter Query:**
```php
// Hanya jawaban survey umum (question_id 1-14)
whereHas('question', function($q) {
    $q->where('context', 'umum');
})
```

---

## ✅ SUMMARY

| Aspek | Before | After |
|-------|--------|-------|
| **Jawaban Ditampilkan** | Umum + Tes Psikologi ❌ | Hanya Umum ✅ |
| **Data Tercampur** | Ya ❌ | Tidak ✅ |
| **Filter Context** | Hanya filter pendaftar | Filter pendaftar DAN jawaban ✅ |
| **Eager Loading** | Tidak ada constraint | Ada constraint filter umum ✅ |
| **Stats Accuracy** | Kurang akurat | Akurat ✅ |

---

**Dibuat oleh**: Claude Code AI Assistant
**Tanggal**: 5 Februari 2026
**Versi**: 1.0

---

## 🎯 NEXT VERIFICATION

Setelah upload ke production, test:

1. ✅ `/admin/survey?mainTab=responses` - Hanya jawaban survey umum
2. ✅ `/admin/tes-psikologi?mainTab=data` - Hanya jawaban survey tes psikologi
3. ✅ Tidak ada data tercampur
4. ✅ Stats cards akurat

**STATUS: READY TO UPLOAD** ✅
