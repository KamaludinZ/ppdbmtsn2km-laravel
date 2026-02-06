# UPLOAD MANUAL FIX JAWABAN SURVEY V4

## Tanggal: 4 Februari 2026 - Final Fix

## Status: SSH Connection Refused - UPLOAD MANUAL VIA FTP/cPanel

---

## ❗ MASALAH YANG DIPERBAIKI

User melaporkan setelah V3:
```
[VALUE NOT FOUND: Sangat Tidak Setuju]
[VALUE NOT FOUND: Sangat Tidak Setuju]
[VALUE NOT FOUND: Sangat Tidak Setuju]
Sangat Tidak Setuju
Sangat Tidak Setuju
```

### Root Cause
Database menyimpan **LABEL** ("Sangat Tidak Setuju") bukan **VALUE** ("1", "2", "3", "4").
Method 1 & 2 hanya mencari VALUE → Gagal
Method 3 perlu enhancement untuk matching LABEL dengan benar

---

## ✅ SOLUSI V4: ENHANCED METHOD 3 & 4

### Method 3: Case-Insensitive Label Matching
```php
// Method 3: Try matching by label (in case value was saved as label)
if (!$option) {
    $option = $questionOptions->first(function($opt) use ($normalizedValue) {
        $normalizedLabel = trim((string)$opt->label);
        // Case-insensitive comparison
        return strcasecmp($normalizedLabel, $normalizedValue) === 0;
    });
}
```

**Keuntungan:**
- Tidak peduli huruf besar/kecil
- Handle "Sangat Setuju" vs "sangat setuju"
- Trim whitespace dari kedua sisi

### Method 4: Partial/Fuzzy Matching
```php
// Method 4: Partial match by label (for typos or extra spaces)
if (!$option) {
    $option = $questionOptions->first(function($opt) use ($normalizedValue) {
        $normalizedLabel = trim((string)$opt->label);
        // Check if label contains the value or vice versa
        return stripos($normalizedLabel, $normalizedValue) !== false
            || stripos($normalizedValue, $normalizedLabel) !== false;
    });
}
```

**Keuntungan:**
- Handle typo minor atau extra spaces
- Lebih forgiving terhadap data imperfect

---

## 📁 FILE YANG HARUS DIUPLOAD

### File 1: Admin/TesPsikologi.php
**Path Lokal:**
```
C:\ppdbmtsn2km-laravel\app\Livewire\Admin\TesPsikologi.php
```

**Path Production:**
```
/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/TesPsikologi.php
```

**Line yang diubah:** ~1032-1049 (Method 3 & 4)

---

### File 2: Panitia/TesPsikologi.php
**Path Lokal:**
```
C:\ppdbmtsn2km-laravel\app\Livewire\Panitia\TesPsikologi.php
```

**Path Production:**
```
/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/TesPsikologi.php
```

**Line yang diubah:** ~841-858 (Method 3 & 4)

---

## 🚀 CARA UPLOAD MANUAL VIA cPanel

### Opsi 1: Via cPanel File Manager

1. **Login ke cPanel**
   - URL: https://your-cpanel-url.com
   - Username: mtsnkot4
   - Password: [your password]

2. **Buka File Manager**
   - Klik icon "File Manager"
   - Navigate ke: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/`

3. **Backup File Lama (Opsional tapi Recommended)**
   - Klik kanan pada `Admin/TesPsikologi.php`
   - Pilih "Copy"
   - Rename jadi `TesPsikologi.php.backup-v3-20260204`
   - Ulangi untuk `Panitia/TesPsikologi.php`

4. **Upload File Baru**

   **Untuk Admin:**
   - Navigate ke folder `Admin/`
   - Klik tombol "Upload" di toolbar
   - Pilih file: `C:\ppdbmtsn2km-laravel\app\Livewire\Admin\TesPsikologi.php`
   - Tunggu upload selesai
   - **IMPORTANT:** Overwrite file yang ada (klik "Overwrite")

   **Untuk Panitia:**
   - Navigate ke folder `Panitia/`
   - Klik tombol "Upload"
   - Pilih file: `C:\ppdbmtsn2km-laravel\app\Livewire\Panitia\TesPsikologi.php`
   - Overwrite file yang ada

5. **Verifikasi Upload**
   - Klik kanan pada file yang baru diupload
   - Pilih "View" atau "Edit"
   - Cari baris: `// Method 4: Partial match by label`
   - Pastikan code ada di sana

---

### Opsi 2: Via FTP Client (FileZilla)

1. **Install FileZilla** (jika belum ada)
   - Download: https://filezilla-project.org/

2. **Connect ke Server**
   - Host: `ftp.mtsn2kotamalang.sch.id` atau `103.30.244.77`
   - Username: `mtsnkot4`
   - Password: [your FTP password]
   - Port: `21`
   - Klik "Quickconnect"

3. **Navigate ke Folder**
   - **Remote site (kanan):** `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/`
   - **Local site (kiri):** `C:\ppdbmtsn2km-laravel\app\Livewire\`

4. **Upload Files**
   - Drag & Drop `Admin/TesPsikologi.php` dari kiri ke folder `Admin/` di kanan
   - Drag & Drop `Panitia/TesPsikologi.php` dari kiri ke folder `Panitia/` di kanan
   - Confirm overwrite jika diminta

---

## 🧹 CLEAR CACHE PRODUCTION

**SANGAT PENTING!** Setelah upload, clear semua cache Laravel:

### Via cPanel Terminal

1. Buka **Terminal** di cPanel
2. Jalankan commands berikut satu per satu:

```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

php artisan optimize:clear
php artisan view:clear
php artisan config:clear
php artisan cache:clear
```

### Via SSH (Jika sudah available lagi)

```bash
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear && php artisan view:clear && php artisan config:clear && php artisan cache:clear"
```

### Expected Output

```
Compiled services and packages files removed successfully.
Configuration cache cleared successfully.
Application cache cleared successfully.
Compiled views cleared successfully.
Configuration cache cleared successfully.
Cache cleared successfully.
```

---

## 🧪 TESTING SETELAH UPLOAD

### Test 1: Tab Jawaban Survey di Admin

1. Buka browser (gunakan **Incognito/Private mode** untuk menghindari cache)
2. Login sebagai **Admin**
3. Buka URL:
   ```
   https://pmbm.mtsn2kotamalang.sch.id/admin/tes-psikologi?mainTab=jawaban-survey
   ```
4. **Hard Refresh:** Tekan `Ctrl+F5` (Windows) atau `Cmd+Shift+R` (Mac)

#### Expected Result ✅

Setiap kolom pertanyaan harus menampilkan LABEL yang BERBEDA dan SESUAI:

```
| No | Pendaftar    | Pertanyaan 2 | Pertanyaan 3      | Pertanyaan 5         | Pertanyaan 6    |
|----|--------------|--------------|-------------------|----------------------|-----------------|
| 1  | Ahmad        | Ya           | Guru              | Setuju               | Sangat Setuju   |
| 2  | Siti         | Ya           | Sosial Media      | Sangat Tidak Setuju  | Tidak Setuju    |
| 3  | Budi         | Ya           | Kepala Sekolah    | Sangat Setuju        | Setuju          |
```

**✅ Ciri-ciri SUCCESS:**
- Pertanyaan 2: "Ya" atau "Tidak" (bukan "Sangat Tidak Setuju")
- Pertanyaan 3: "Guru", "Kepala Sekolah", "Sosial Media", "Dinas Terkait" (variasi)
- Pertanyaan 5-20: "Sangat Tidak Setuju", "Tidak Setuju", "Setuju", "Sangat Setuju" (bervariasi)
- **BUKAN semua kolom sama!**

#### NOT Expected ❌

```
| No | Pendaftar    | Pertanyaan 2            | Pertanyaan 3            | Pertanyaan 5            |
|----|--------------|-------------------------|-------------------------|-------------------------|
| 1  | Ahmad        | Sangat Tidak Setuju     | Sangat Tidak Setuju     | Sangat Tidak Setuju     |
| 2  | Siti         | Sangat Tidak Setuju     | Sangat Tidak Setuju     | Sangat Tidak Setuju     |
| 3  | Budi         | Sangat Tidak Setuju     | Sangat Tidak Setuju     | Sangat Tidak Setuju     |
```

❌ Semua sama (ini bug yang dilaporkan user)

#### Jika Masih Muncul [VALUE NOT FOUND: xxx] ⚠️

Berarti ada mismatch antara data di database dengan options. Lanjut ke troubleshooting.

---

### Test 2: Modal "Lihat" di Kolom Status Survey

1. Di halaman yang sama (`/admin/tes-psikologi?mainTab=jawaban-survey`)
2. Klik tombol **"Lihat"** di kolom "Status Survey"
3. Modal akan popup menampilkan detail jawaban

#### Expected Result ✅

```
📋 Detail Jawaban Survey Tes Psikologi

Pendaftar: Ahmad - 20260001

Pertanyaan 2: Saya mengisi jajak pendapat ini sesuai dengan kondisi...
Jawaban: Ya ✅

Pertanyaan 3: Media/sarana yang sering saya pakai...
Jawaban: Sosial Media ✅

Pertanyaan 5: Saya sering menyampaikan masukan...
Jawaban: Setuju ✅

Pertanyaan 6: Saya beberapa kali menyampaikan pendapat...
Jawaban: Sangat Setuju ✅
```

**✅ Ciri-ciri SUCCESS:**
- Setiap pertanyaan menampilkan LABEL yang sesuai dengan opsinya
- Pertanyaan berbeda = Jawaban berbeda
- Tidak ada `[VALUE NOT FOUND: xxx]`

---

### Test 3: Tab Jawaban Survey di Panitia

1. **Logout** dari Admin
2. **Login** sebagai **Panitia Tes Psikologi**
3. Buka URL:
   ```
   https://pmbm.mtsn2kotamalang.sch.id/panitia/tes-psikologi?mainTab=jawaban-survey
   ```
4. **Hard Refresh:** `Ctrl+F5`
5. Test sama seperti Test 1 di atas
6. Hasil harus **identik** dengan di Admin

---

## 🔍 TROUBLESHOOTING

### Problem 1: Masih Muncul "[VALUE NOT FOUND: Sangat Tidak Setuju]"

#### Kemungkinan Penyebab & Solusi

**Cause 1: File Belum Terupload dengan Benar**

**Verifikasi:**
1. Via cPanel File Manager, buka file production
2. Cari text: `// Method 4: Partial match by label`
3. Jika tidak ada → File belum terupload

**Solusi:**
- Re-upload file dengan hati-hati
- Pastikan overwrite file yang lama

---

**Cause 2: Cache Belum Di-Clear**

**Verifikasi:**
- Check apakah command clear cache sudah dijalankan

**Solusi:**
```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan optimize:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
```

---

**Cause 3: Browser Cache**

**Solusi:**
1. Hard Refresh: `Ctrl+F5` atau `Cmd+Shift+R`
2. Clear browser cache manually
3. Gunakan **Incognito/Private Mode**
4. Coba browser lain (Chrome → Firefox)

---

**Cause 4: Options VALUE Berbeda dengan Jawaban di Database**

**Diagnosis:** Jalankan diagnostic script untuk cek data

**Via Windows (Local):**
```bash
C:\ppdbmtsn2km-laravel\check_jawaban_database.bat
```

**Via cPanel Terminal:**
```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Check jawaban di database
psql -U mtsnkot4 -d mtsnkot4_ppdb -c "SELECT sa.id, sa.pendaftar_id, sa.survey_question_id, SUBSTRING(sq.pertanyaan, 1, 40) as pertanyaan, sa.jawaban, sa.created_at FROM survey_answers sa JOIN survey_questions sq ON sa.survey_question_id = sq.id WHERE sq.context = 'tes_psikologi' ORDER BY sa.created_at DESC LIMIT 10;"

# Check options yang tersedia
psql -U mtsnkot4 -d mtsnkot4_ppdb -c "SELECT sqo.survey_question_id, sqo.label, sqo.value FROM survey_question_options sqo WHERE sqo.survey_question_id IN (SELECT id FROM survey_questions WHERE context = 'tes_psikologi' AND urutan IN (5, 6)) ORDER BY sqo.survey_question_id, sqo.urutan;"
```

**Analisis Output:**

**Scenario A: VALUE di options = Angka, Jawaban = Label**
```
Options:
| question_id | label                  | value |
|-------------|------------------------|-------|
| 45          | Sangat Tidak Setuju    | 1     |
| 45          | Tidak Setuju           | 2     |
| 45          | Setuju                 | 3     |
| 45          | Sangat Setuju          | 4     |

Jawaban:
| question_id | jawaban                |
|-------------|------------------------|
| 45          | Sangat Tidak Setuju    |  ← MISMATCH!
```

**Solusi:**
Ubah VALUE di options menjadi sama dengan LABEL:
```sql
UPDATE survey_question_options
SET value = label
WHERE survey_question_id IN (SELECT id FROM survey_questions WHERE context = 'tes_psikologi');
```

---

**Scenario B: VALUE di options = Label, Jawaban = Label**
```
Options:
| question_id | label                  | value                  |
|-------------|------------------------|------------------------|
| 45          | Sangat Tidak Setuju    | Sangat Tidak Setuju    |
| 45          | Tidak Setuju           | Tidak Setuju           |

Jawaban:
| question_id | jawaban                |
|-------------|------------------------|
| 45          | Sangat Tidak Setuju    |  ← MATCH! Method 3 should work!
```

**Solusi:**
- Method 3 & 4 seharusnya sudah bisa handle
- Jika masih gagal, cek log error (langkah berikutnya)

---

### Problem 2: Masih Menampilkan Label yang Sama Semua

**Cause: File V4 Belum Terupload / Cache**

**Verifikasi:**
1. Buka production file via cPanel
2. Cari line: `$questionOptions = SurveyQuestionOption::where('survey_question_id', $question->id)`
3. Pastikan ini ada di line ~1000 (Admin) dan ~809 (Panitia)

**Solusi:**
- Re-upload file
- Clear cache lagi
- Hard refresh browser

---

## 📊 CHECK LOG ERROR

Jika masih ada masalah, cek log untuk detail:

### Via cPanel Terminal

```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Check log errors terbaru
tail -100 storage/logs/laravel.log | grep -A 20 "Survey option not found"
```

### Analisis Log

Log akan menampilkan:

```json
{
  "pendaftar_id": 123,
  "question_id": 45,
  "pertanyaan": "Saya sering menyampaikan masukan...",
  "tipe_input": "radio",
  "looking_for_value": "Sangat Tidak Setuju",
  "options_count": 4,
  "available_options": [
    {
      "opt_id": 201,
      "question_id": 45,
      "label": "Sangat Tidak Setuju",
      "value": "1"
    },
    {
      "opt_id": 202,
      "question_id": 45,
      "label": "Tidak Setuju",
      "value": "2"
    }
  ]
}
```

**Yang Harus Diperhatikan:**

1. **`question_id` di semua options harus SAMA**
   - Jika berbeda → bug pada query WHERE clause

2. **`looking_for_value` vs `value` di options**
   - Jika `looking_for_value = "Sangat Tidak Setuju"` tapi `value = "1"` → Mismatch (perlu update options)
   - Jika `looking_for_value = "1"` dan `value = "1"` → Match (Method 1/2 should work)

3. **`looking_for_value` vs `label` di options**
   - Jika sama → Method 3 seharusnya match
   - Jika beda (case/spacing) → Method 4 seharusnya match

---

## 📋 CHECKLIST LENGKAP

### Pre-Upload
- [x] File V4 sudah ada di local
- [x] Method 3 & 4 sudah ada di code
- [ ] Backup file production (optional tapi recommended)

### Upload
- [ ] Upload `Admin/TesPsikologi.php` via cPanel/FTP
- [ ] Upload `Panitia/TesPsikologi.php` via cPanel/FTP
- [ ] Verifikasi file terupload dengan benar

### Post-Upload
- [ ] Clear cache: `php artisan optimize:clear`
- [ ] Clear view: `php artisan view:clear`
- [ ] Clear config: `php artisan config:clear`

### Testing
- [ ] Test Admin tab jawaban survey (setiap pertanyaan beda label)
- [ ] Test Admin modal "Lihat" (label sesuai)
- [ ] Test Panitia tab jawaban survey (sama dengan Admin)
- [ ] Test Panitia modal "Lihat" (sama dengan Admin)

### Troubleshooting (Jika Gagal)
- [ ] Verifikasi file production punya code V4
- [ ] Clear cache lagi
- [ ] Hard refresh browser (Ctrl+F5)
- [ ] Coba Incognito mode
- [ ] Jalankan diagnostic script
- [ ] Check log error
- [ ] Update options VALUE jika mismatch

---

## 🎯 EXPECTED FINAL RESULT

Setelah upload V4 dan testing, harapannya:

### ✅ SUCCESS Criteria

1. **Setiap pertanyaan tampil dengan LABEL yang berbeda:**
   - Pertanyaan 2: "Ya" atau "Tidak"
   - Pertanyaan 3: "Guru", "Kepala Sekolah", "Sosial Media", "Dinas Terkait"
   - Pertanyaan 5-20: "Sangat Tidak Setuju", "Tidak Setuju", "Setuju", "Sangat Setuju"

2. **Modal "Lihat" menampilkan jawaban yang sesuai**

3. **Tidak ada lagi pesan `[VALUE NOT FOUND: xxx]`** (atau minimal berkurang signifikan)

4. **Admin dan Panitia menampilkan data yang identik**

### ❌ Jika Masih Gagal

Kemungkinan besar ada mismatch antara:
- Jawaban di database (LABEL)
- VALUE di options (Angka)

**Solusi Final:** Update options VALUE = LABEL

```sql
UPDATE survey_question_options
SET value = label
WHERE survey_question_id IN (
  SELECT id FROM survey_questions WHERE context = 'tes_psikologi'
);
```

---

## 📞 CONTACT

Jika masih ada masalah setelah semua langkah di atas:

1. Screenshot hasil di browser
2. Copy paste log error
3. Copy paste hasil diagnostic script
4. Kirim ke developer untuk analisis lebih lanjut

---

## 🔄 VERSION HISTORY

| Version | Date | Changes |
|---------|------|---------|
| V1 | 3 Feb 2026 | Eager loading dengan `->with('options')` |
| V2 | 4 Feb 2026 | Force reload `$question->load('options')` + 3 methods |
| V3 | 4 Feb 2026 | **CRITICAL FIX:** Direct query dengan WHERE clause |
| V4 | 4 Feb 2026 | **ENHANCED:** Method 3 & 4 untuk matching LABEL dengan case-insensitive dan partial match |

---

## ✅ SUMMARY

**V4 adalah enhancement dari V3 yang:**
- Mengatasi masalah jawaban disimpan sebagai LABEL bukan VALUE
- Method 3: Case-insensitive exact label matching
- Method 4: Partial/fuzzy label matching untuk typo
- Lebih robust terhadap data imperfect

**Upload manual diperlukan karena:** SSH connection refused saat ini.

**Langkah sederhana:**
1. Upload 2 file via cPanel/FTP
2. Clear cache Laravel
3. Test di browser dengan hard refresh
4. Hasil harus menampilkan label yang benar untuk setiap pertanyaan

Good luck! 🚀
