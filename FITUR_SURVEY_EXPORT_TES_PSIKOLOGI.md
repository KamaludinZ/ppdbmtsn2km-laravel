# Fitur Survey pada Export Laporan Tes Psikologi

## Overview
Export Excel untuk Laporan Tes Psikologi sekarang dilengkapi dengan **kolom survey dinamis** yang otomatis menyesuaikan dengan pertanyaan survey yang aktif di sistem.

## Cara Kerja

### 1. Kondisi Kolom Survey Muncul
Kolom survey akan **otomatis ditambahkan** ke file Excel jika:
- ✅ Survey tes psikologi diaktifkan di `/admin/tes-psikologi?mainTab=settings`
- ✅ Ada pertanyaan survey aktif dengan context **"Tes Psikologi"** (bukan "Umum")

### 2. Struktur Kolom Survey

#### Kolom: Status Survey
Kolom pertama setelah data dasar, menampilkan status pengisian survey:

| Status | Keterangan |
|--------|------------|
| **Sudah Mengisi** | Semua pertanyaan wajib telah dijawab |
| **Belum Lengkap (2/5)** | 2 dari 5 pertanyaan wajib sudah dijawab |
| **Belum Mengisi** | Belum ada jawaban sama sekali |

#### Kolom: Pertanyaan Survey (Dinamis)
- Setiap pertanyaan survey yang aktif mendapatkan 1 kolom sendiri
- Header kolom = Teks pertanyaan
- Urutan kolom mengikuti urutan pertanyaan (field `urutan`)
- Format jawaban otomatis disesuaikan dengan tipe input

### 3. Format Jawaban Berdasarkan Tipe Input

| Tipe Input | Format di Excel | Contoh |
|------------|-----------------|--------|
| **Text** | String biasa | "Sangat puas dengan pelayanan" |
| **Textarea** | String panjang | "Komentar panjang dari pendaftar..." |
| **Radio** | Satu nilai terpilih | "Ya" atau "Tidak" |
| **Checkbox** | Multiple values (comma-separated) | "Pilihan 1, Pilihan 2, Pilihan 3" |
| **Select** | Satu nilai terpilih | "Opsi A" |
| **Belum dijawab** | Tanda strip | "-" |

## Implementasi Teknis

### File yang Dimodifikasi
`app/Exports/TesPsikologiExport.php`

### Kode Utama

#### 1. Load Survey Questions
```php
// Di constructor
$setting = TesPsikologiSetting::first();
if ($setting && $setting->survey_aktif) {
    $this->surveyAktif = true;
    $this->surveyQuestions = SurveyQuestion::tesPsikologi()->aktif()->ordered()->get();
} else {
    $this->surveyQuestions = collect();
}
```

#### 2. Eager Loading Survey Answers
```php
// Di query method
$query = TesPsikologiPendaftar::with([
    'pendaftar.jalurPendaftaran',
    'pendaftar.user',
    'pendaftar.surveyAnswers.question', // ← Survey answers
    'opsi',
    'bank',
    'verifiedBy'
]);
```

#### 3. Dynamic Headings
```php
// Di headings method
if ($this->surveyAktif) {
    $baseHeadings[] = 'Status Survey';

    foreach ($this->surveyQuestions as $question) {
        $baseHeadings[] = $question->pertanyaan;
    }
}
```

#### 4. Survey Data Mapping
```php
// Di map method
if ($this->surveyAktif) {
    // Filter survey answers untuk context tes_psikologi
    $surveyAnswers = $tesPsikologi->pendaftar->surveyAnswers->filter(function($answer) {
        return $answer->question && $answer->question->context === 'tes_psikologi';
    });

    // Hitung status survey
    $totalWajib = $this->surveyQuestions->where('wajib_diisi', true)->count();
    $jawabanWajib = $surveyAnswers->filter(function($answer) {
        return $answer->question && $answer->question->wajib_diisi;
    })->count();

    // Format jawaban untuk setiap pertanyaan
    foreach ($this->surveyQuestions as $question) {
        $answer = $surveyAnswers->firstWhere('survey_question_id', $question->id);
        if ($answer) {
            $jawaban = $answer->jawaban;
            if (is_array($jawaban)) {
                $baseData[] = implode(', ', $jawaban); // Multiple values
            } else {
                $baseData[] = $jawaban; // Single value
            }
        } else {
            $baseData[] = '-'; // Tidak ada jawaban
        }
    }
}
```

## Contoh Output Excel

### Scenario 1: Survey Aktif dengan 3 Pertanyaan

| No. Pendaftaran | Nama | ... | Status Survey | Bagaimana pelayanan kami? | Apakah Anda puas? | Saran untuk kami |
|-----------------|------|-----|---------------|---------------------------|-------------------|------------------|
| 2026001 | Ahmad | ... | Sudah Mengisi | Sangat baik | Ya | Pertahankan kualitas |
| 2026002 | Siti | ... | Belum Lengkap (2/3) | Baik | Ya | - |
| 2026003 | Budi | ... | Belum Mengisi | - | - | - |

### Scenario 2: Survey Tidak Aktif

| No. Pendaftaran | Nama | ... | Email | No. HP |
|-----------------|------|-----|-------|--------|
| 2026001 | Ahmad | ... | ahmad@email.com | 081234567890 |
| 2026002 | Siti | ... | siti@email.com | 081234567891 |

## Use Cases

### 1. Analisis Kepuasan Pendaftar
- Export data dengan filter status "Disetujui"
- Buka Excel, lihat kolom survey
- Analisis jawaban untuk evaluasi layanan

### 2. Follow Up Pendaftar yang Belum Mengisi Survey
- Filter export untuk melihat semua data
- Cari baris dengan "Status Survey" = "Belum Mengisi"
- Hubungi pendaftar tersebut untuk mengisi survey

### 3. Laporan Komprehensif
- Export tanpa filter untuk mendapat semua data
- Satu file Excel berisi: data tes psikologi + data survey
- Cocok untuk presentasi atau arsip

## Tips & Best Practices

### ✅ DO
1. **Aktifkan survey sebelum periode pendaftaran** agar semua pendaftar mengisi
2. **Buat pertanyaan jelas dan singkat** untuk header Excel yang mudah dibaca
3. **Gunakan pertanyaan wajib** untuk data penting yang perlu dijawab
4. **Export berkala** untuk monitoring progress pengisian survey

### ❌ DON'T
1. **Jangan ubah pertanyaan survey** setelah ada pendaftar yang mengisi (bisa inconsistent)
2. **Jangan terlalu banyak pertanyaan** (maksimal 10-15 untuk readability Excel)
3. **Jangan lupa cek context** - pastikan pertanyaan context-nya "Tes Psikologi" bukan "Umum"

## Troubleshooting

### Q: Kolom survey tidak muncul di Excel?
**A:** Periksa:
1. Apakah survey sudah diaktifkan di `/admin/tes-psikologi?mainTab=settings`?
2. Apakah ada pertanyaan survey yang aktif dengan context "Tes Psikologi"?
3. Clear cache Laravel: `php artisan cache:clear`

### Q: Status survey selalu "Belum Mengisi" padahal sudah dijawab?
**A:** Periksa:
1. Apakah context pertanyaan = "Tes Psikologi" (bukan "Umum")?
2. Apakah jawaban tersimpan di tabel `survey_answers` dengan `survey_question_id` yang benar?

### Q: Jawaban checkbox tidak muncul lengkap?
**A:** Jawaban checkbox disimpan sebagai JSON array, di Excel akan diformat sebagai "Pilihan 1, Pilihan 2". Pastikan field `jawaban` di database bertipe JSON.

### Q: Urutan kolom pertanyaan acak?
**A:** Urutan mengikuti field `urutan` di tabel `survey_questions`. Edit urutan di `/admin/tes-psikologi?mainTab=survey`.

## Database Schema

### Table: survey_questions
```sql
- id
- pertanyaan (text)
- deskripsi (text nullable)
- tipe_input (enum: text, textarea, radio, checkbox, select)
- wajib_diisi (boolean)
- urutan (integer)
- aktif (boolean)
- context (enum: 'umum', 'tes_psikologi') ← PENTING!
```

### Table: survey_answers
```sql
- id
- pendaftar_id (foreign key)
- survey_question_id (foreign key)
- jawaban (json) ← Array untuk checkbox, string untuk lainnya
```

### Relasi
```
TesPsikologiPendaftar
  → pendaftar (Pendaftar)
    → surveyAnswers (SurveyAnswer)
      → question (SurveyQuestion)
```

## Update Notes

### Version 1.1.0 - Survey Integration
- ✅ Kolom "Status Survey" otomatis ditambahkan
- ✅ Kolom dinamis untuk setiap pertanyaan survey
- ✅ Format jawaban otomatis (single/multiple values)
- ✅ Filter berdasarkan context "Tes Psikologi"
- ✅ Perhitungan status: Sudah Mengisi / Belum Lengkap / Belum Mengisi

### Backward Compatibility
- ✅ Jika survey tidak aktif, Excel tetap berfungsi normal (14 kolom dasar)
- ✅ Tidak ada perubahan di UI atau logic backend lainnya
- ✅ Filter dan sorting tetap berfungsi seperti biasa

---

**Dokumentasi:** 29 Januari 2026
**Author:** Claude Code
**Related Files:** `app/Exports/TesPsikologiExport.php`
