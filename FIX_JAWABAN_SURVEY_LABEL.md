# FIX JAWABAN SURVEY - TAMPILKAN LABEL BUKAN VALUE

## Tanggal
4 Februari 2026

## Masalah yang Dilaporkan

Di halaman `/admin/tes-psikologi?mainTab=jawaban-survey` dan di panitia, jawaban survey yang muncul **tidak sesuai dengan opsi** yang telah dibuat di `/admin/tes-psikologi?mainTab=survey`.

### Contoh Masalah

**Opsi yang dibuat di tab Survey**:
- Label: "Sangat Setuju" → Value: "5"
- Label: "Setuju" → Value: "4"
- Label: "Netral" → Value: "3"
- Label: "Tidak Setuju" → Value: "2"
- Label: "Sangat Tidak Setuju" → Value: "1"

**Yang tampil di tab Jawaban Survey (SALAH)**:
```
❌ 5
❌ 4
❌ sangat_setuju
```

**Yang seharusnya tampil (BENAR)**:
```
✅ Sangat Setuju
✅ Setuju
✅ Sangat Setuju
```

## Root Cause

Di kedua file `TesPsikologi.php` (Admin dan Panitia), saat menyiapkan data untuk tabel jawaban survey, code langsung menampilkan **VALUE** dari database tanpa mengkonversi ke **LABEL** yang user-friendly:

```php
// ❌ SALAH - menampilkan VALUE mentah
$answers[$question->id] = $answer ? $answer->jawaban : '-';
```

Padahal:
- **VALUE** adalah data yang disimpan di database (misal: "5", "sangat_setuju")
- **LABEL** adalah text yang seharusnya ditampilkan ke user (misal: "Sangat Setuju")

## Update 2: Debug dan Perbaikan Lanjutan (4 Feb 2026 - Sore)

Setelah upload pertama, user melaporkan masalah baru:
> "Munculnya hasil jawaban tidak sama dengan opsi yang tersedia di setiap pertanyaan. Misal di pertanyaan pertama opsinya iya dan di pertanyaan kedua setuju dan tidak setuju tapi yang tampil di hasil jawaban tidak setuju semua."

### Root Cause Tambahan
Masalah **type comparison** dan **whitespace** dalam value:
- VALUE di database mungkin: `"5"` (string)
- VALUE di options mungkin: `5` (integer)
- Comparison `"5" === 5` akan FALSE

### Perbaikan Tambahan yang Diterapkan

1. **Normalize Value untuk Comparison**:
   - Trim whitespace
   - Convert ke string untuk perbandingan yang konsisten
   - Gunakan closure function untuk comparison yang lebih flexible

2. **Debug Logging**:
   - Tambahkan logging jika option tidak ditemukan
   - Log akan mencatat pertanyaan, value yang dicari, dan options yang tersedia

## Solusi yang Diterapkan

### Perubahan 1: Eager Loading Options
Tambahkan `->with('options')` saat query pertanyaan survey agar data opsi sudah ter-load:

**SEBELUM**:
```php
$surveyQuestions = SurveyQuestion::tesPsikologi()
    ->aktif()
    ->ordered()
    ->get();
```

**SESUDAH**:
```php
$surveyQuestions = SurveyQuestion::tesPsikologi()
    ->aktif()
    ->ordered()
    ->with('options') // Load options untuk konversi label
    ->get();
```

### Perubahan 2: Konversi VALUE ke LABEL
Update loop yang mengambil jawaban untuk mengkonversi VALUE menjadi LABEL:

**SEBELUM**:
```php
foreach ($surveyQuestions as $question) {
    $answer = SurveyAnswer::where('pendaftar_id', $pendaftar->id)
        ->where('survey_question_id', $question->id)
        ->first();

    $answers[$question->id] = $answer ? $answer->jawaban : '-'; // ❌ VALUE mentah
}
```

**SESUDAH**:
```php
foreach ($surveyQuestions as $question) {
    $answer = SurveyAnswer::where('pendaftar_id', $pendaftar->id)
        ->where('survey_question_id', $question->id)
        ->first();

    if (!$answer) {
        $answers[$question->id] = '-';
        continue;
    }

    $jawaban = $answer->jawaban;

    // Format jawaban berdasarkan tipe - convert VALUE to LABEL for options-based questions
    if (in_array($question->tipe_input, ['radio', 'checkbox', 'select'])) {
        // Get label from options instead of showing raw value
        $jawabanLabels = [];
        foreach ((array)$jawaban as $value) {
            $option = $question->options->where('value', $value)->first();
            $jawabanLabels[] = $option ? $option->label : $value;
        }
        $answers[$question->id] = implode(', ', $jawabanLabels); // ✅ LABEL user-friendly
    } else {
        // For text/textarea, show as is
        $answers[$question->id] = is_array($jawaban) ? implode(', ', $jawaban) : $jawaban;
    }
}
```

### Logic Konversi VALUE → LABEL

1. Cek tipe pertanyaan: radio, checkbox, atau select
2. Loop setiap value dalam jawaban (untuk checkbox bisa multiple values)
3. Cari option yang sesuai berdasarkan VALUE
4. Ambil LABEL dari option tersebut
5. Gabungkan semua label dengan koma (untuk checkbox)

## File yang Diupdate

### 1. `app/Livewire/Admin/TesPsikologi.php`
**Baris yang diubah**: ~948-1010
- Eager load options: line ~952
- Konversi VALUE ke LABEL: line ~985-1010

### 2. `app/Livewire/Panitia/TesPsikologi.php`
**Baris yang diubah**: ~757-819
- Eager load options: line ~761
- Konversi VALUE ke LABEL: line ~794-819

## Status Modal "Lihat"

Modal yang muncul saat klik tombol "Lihat" di kolom Status Survey **SUDAH BENAR SEJAK AWAL** dan tidak perlu diperbaiki.

Di kedua file (Admin dan Panitia), method `openSurveyAnswersModal()` sudah memiliki logic yang benar untuk mengkonversi VALUE ke LABEL.

## Cara Upload ke Production

### Opsi 1: Via Batch File
```bash
upload_fix_jawaban_survey.bat
```

### Opsi 2: Manual via SCP
```bash
# Upload Admin
scp app/Livewire/Admin/TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/

# Upload Panitia
scp app/Livewire/Panitia/TesPsikologi.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/
```

### Opsi 3: Manual via FTP/cPanel
Upload 2 file ke:
1. `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/TesPsikologi.php`
2. `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/TesPsikologi.php`

## Langkah Setelah Upload

### 1. Clear Cache
```bash
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
```

### 2. Test di Admin
1. Login sebagai Admin
2. Buka `/admin/tes-psikologi?mainTab=jawaban-survey`
3. **Jawaban survey harus menampilkan LABEL yang benar**:
   - ✅ "Sangat Setuju" (bukan "5")
   - ✅ "Laki-laki" (bukan "L")
   - ✅ "SMA Negeri 1" (bukan "sma_negeri_1")

### 3. Test di Panitia
1. Login sebagai **Panitia Tes Psikologi**
2. Buka `/panitia/tes-psikologi?mainTab=jawaban-survey`
3. **Jawaban survey harus menampilkan LABEL yang sama**

### 4. Test Modal "Lihat"
1. Di tab jawaban-survey, klik tombol **"Lihat"** di kolom Status Survey
2. Modal harus menampilkan jawaban dengan LABEL yang benar
3. **Modal sudah OK sejak awal**, tidak ada perubahan

### 5. Test untuk Multiple Choice (Checkbox)
Jika ada pertanyaan tipe checkbox dengan multiple values:
- User pilih: "Option A", "Option C", "Option D"
- **Harus tampil**: "Option A, Option C, Option D"
- **BUKAN**: "A, C, D" atau "option_a, option_c, option_d"

## Contoh Perbandingan

### Pertanyaan: "Tingkat Kepuasan Anda?"
**Opsi yang dibuat**:
```
Label: "Sangat Puas"    → Value: "5"
Label: "Puas"           → Value: "4"
Label: "Cukup"          → Value: "3"
Label: "Kurang Puas"    → Value: "2"
Label: "Tidak Puas"     → Value: "1"
```

**Sebelum Fix (SALAH)**:
| Nomor | Nama | Pertanyaan: Tingkat Kepuasan? |
|-------|------|-------------------------------|
| P001  | Ahmad| 5 ❌                          |
| P002  | Budi | 4 ❌                          |
| P003  | Citra| 1 ❌                          |

**Setelah Fix (BENAR)**:
| Nomor | Nama | Pertanyaan: Tingkat Kepuasan? |
|-------|------|-------------------------------|
| P001  | Ahmad| Sangat Puas ✅                |
| P002  | Budi | Puas ✅                       |
| P003  | Citra| Tidak Puas ✅                 |

### Pertanyaan: "Hobi Anda?" (Checkbox - Multiple)
**Opsi yang dibuat**:
```
Label: "Membaca"    → Value: "baca"
Label: "Olahraga"   → Value: "olahraga"
Label: "Musik"      → Value: "musik"
Label: "Melukis"    → Value: "lukis"
```

**Sebelum Fix (SALAH)**:
```
baca, olahraga, musik ❌
```

**Setelah Fix (BENAR)**:
```
Membaca, Olahraga, Musik ✅
```

## Troubleshooting

### Jika Masih Tampil VALUE Setelah Upload

1. **Clear cache lagi**:
   ```bash
   php artisan optimize:clear
   ```

2. **Hard refresh browser** dengan Ctrl+F5

3. **Cek file sudah terupload**:
   ```bash
   # Cek Admin
   grep "convert VALUE to LABEL" app/Livewire/Admin/TesPsikologi.php

   # Cek Panitia
   grep "convert VALUE to LABEL" app/Livewire/Panitia/TesPsikologi.php
   ```
   Harus muncul comment tersebut

4. **Cek options sudah dibuat** di tab Survey:
   - Buka `/admin/tes-psikologi?mainTab=survey`
   - Pastikan setiap pertanyaan radio/checkbox/select punya options
   - Setiap option harus punya LABEL dan VALUE

### Jika VALUE Masih Muncul untuk Pertanyaan Tertentu

Kemungkinan **opsi untuk pertanyaan itu belum dibuat**:
1. Buka `/admin/tes-psikologi?mainTab=survey`
2. Cari pertanyaan yang bermasalah
3. Klik **Edit**
4. Pastikan sudah ada opsi jawaban dengan Label dan Value
5. Simpan

### Jika Option Tidak Ditemukan

Kalau VALUE tidak ditemukan di list options, akan ditampilkan VALUE mentah sebagai fallback:
```php
$jawabanLabels[] = $option ? $option->label : $value;
                           // ↑ Gunakan label   ↑ Fallback ke value
```

Ini untuk menghindari error jika:
- Option dihapus setelah pendaftar menjawab
- Data lama sebelum options dibuat

## Kesimpulan

**Jawaban survey sekarang menampilkan LABEL yang user-friendly**, bukan VALUE mentah dari database:

| Tipe Pertanyaan | Sebelum Fix | Setelah Fix |
|----------------|-------------|-------------|
| Radio Button   | 5, 4, 3 ❌  | Sangat Setuju, Setuju, Netral ✅ |
| Checkbox       | A, B, C ❌  | Option A, Option B, Option C ✅ |
| Select/Dropdown| kota_malang ❌ | Kota Malang ✅ |
| Text/Textarea  | Tetap sama ✅ | Tetap sama ✅ |

**Fix diterapkan di**:
- ✅ Admin → Tab Jawaban Survey (tabel)
- ✅ Panitia → Tab Jawaban Survey (tabel)
- ✅ Admin → Modal "Lihat" (sudah benar sejak awal)
- ✅ Panitia → Modal "Lihat" (sudah benar sejak awal)

Setelah upload dan clear cache, jawaban survey akan tampil dengan benar! 🎉
