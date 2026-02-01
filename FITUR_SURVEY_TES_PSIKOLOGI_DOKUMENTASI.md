# 📋 DOKUMENTASI FITUR SURVEY TES PSIKOLOGI

## 📌 Deskripsi Fitur

Fitur Survey Tes Psikologi memungkinkan admin/panitia untuk membuat pertanyaan survey/kuesioner yang wajib diisi oleh pendaftar sebelum mereka dapat mengupload bukti pembayaran tes psikologi. Fitur ini fully terintegrasi dengan sistem PPDB dan mendukung berbagai jenis input.

---

## 🎯 Fitur Utama

### 1. **Admin/Panitia**
- ✅ Aktifkan/Non-aktifkan survey secara global
- ✅ CRUD pertanyaan survey dengan berbagai tipe input
- ✅ Kelola opsi jawaban untuk pertanyaan pilihan ganda
- ✅ Lihat jawaban survey dari setiap pendaftar
- ✅ Export jawaban survey ke Excel

### 2. **Pendaftar**
- ✅ Mengisi survey sebelum upload bukti pembayaran
- ✅ Validasi otomatis (tidak bisa upload jika survey belum diisi)
- ✅ Status survey (Sudah Diisi / Belum Diisi)
- ✅ Edit jawaban survey kapan saja sebelum upload

### 3. **Tipe Input yang Didukung**
- 📝 Text (input teks pendek)
- 📄 Textarea (input teks panjang)
- 🔘 Radio (pilihan tunggal)
- ☑️ Checkbox (pilihan ganda)
- 📋 Select (dropdown pilihan)

---

## 📚 Cara Penggunaan

### A. UNTUK ADMIN/PANITIA

#### 1️⃣ Mengaktifkan Fitur Survey

1. Login sebagai Admin atau Panitia Tes Psikologi
2. Buka menu **Admin** → **Tes Psikologi**
3. Klik tab **"Pertanyaan Survey"** (di sebelah kanan tab "Data Psikologi")
4. Di bagian **"Pengaturan Survey"**:
   - Toggle switch **"Survey Aktif"** ke posisi ON (hijau)
   - Opsional: Isi **"Informasi Survey"** untuk memberikan instruksi kepada pendaftar
5. Klik tombol **"Simpan Pengaturan"**

#### 2️⃣ Membuat Pertanyaan Survey

1. Masih di tab **"Pertanyaan Survey"**
2. Scroll ke bagian **"Form Pertanyaan"**
3. Isi form pertanyaan:
   - **Pertanyaan**: Isi teks pertanyaan (wajib)
   - **Deskripsi**: Opsional, untuk penjelasan tambahan
   - **Tipe Input**: Pilih jenis input yang diinginkan
   - **Wajib Diisi**: Centang jika pertanyaan harus dijawab
   - **Urutan**: Nomor urut pertanyaan (0, 1, 2, dst)
   - **Aktif**: Centang jika pertanyaan ditampilkan
4. Klik tombol **"Simpan Pertanyaan"**

#### 3️⃣ Menambahkan Opsi Jawaban (untuk Radio/Checkbox/Select)

Setelah menyimpan pertanyaan dengan tipe Radio, Checkbox, atau Select:

1. Form **"Kelola Opsi Jawaban"** akan muncul otomatis
2. Di bagian **"Opsi Baru"**:
   - **Label**: Teks yang ditampilkan ke user (contoh: "Sangat Baik")
   - **Value**: Nilai yang disimpan (contoh: "sangat_baik")
   - **Urutan**: Nomor urut opsi
3. Klik tombol **"+ Tambah Opsi"**
4. Ulangi untuk menambah opsi lainnya
5. Setelah selesai, klik **"Tutup Form Opsi"**

**Tips:**
- Minimal 2 opsi untuk Radio/Checkbox/Select
- Gunakan value yang konsisten (lowercase, underscore)
- Urutan menentukan posisi tampilan opsi

#### 4️⃣ Mengedit Pertanyaan

1. Di bagian **"Daftar Pertanyaan"**
2. Cari pertanyaan yang ingin diedit
3. Klik tombol **"Edit"** (ikon pensil kuning)
4. Form akan terisi dengan data pertanyaan
5. Edit data yang diperlukan
6. Klik **"Simpan Pertanyaan"**
7. Untuk edit opsi jawaban, klik **"Kelola Opsi"**

#### 5️⃣ Menghapus Pertanyaan

1. Di bagian **"Daftar Pertanyaan"**
2. Cari pertanyaan yang ingin dihapus
3. Klik tombol **"Hapus"** (ikon trash merah)
4. Konfirmasi penghapusan
5. Pertanyaan dan semua jawabannya akan terhapus

⚠️ **Peringatan:** Menghapus pertanyaan akan menghapus semua jawaban pendaftar untuk pertanyaan tersebut!

#### 6️⃣ Melihat Jawaban Pendaftar

**Di Halaman Tes Psikologi:**
1. Buka menu **Admin** → **Tes Psikologi**
2. Klik tab **"Data Psikologi"**
3. Kolom **"Jawaban Survey"** akan muncul jika survey aktif
4. Klik tombol **"Lihat (N)"** pada baris pendaftar
5. Modal akan menampilkan:
   - Nama pendaftar & nomor pendaftaran
   - Daftar lengkap pertanyaan dan jawaban
   - Status "Belum diisi" jika pertanyaan belum dijawab

**Keterangan:**
- Angka dalam kurung (N) menunjukkan jumlah pertanyaan yang dijawab
- "Belum diisi" muncul jika pendaftar belum menjawab sama sekali

#### 7️⃣ Export Data Survey ke Excel

1. Buka menu **Admin** → **Laporan**
2. Pilih filter yang diinginkan (jalur, status, dll)
3. Klik tombol **"Export Data Pendaftar Lengkap"**
4. File Excel akan terdownload dengan:
   - Semua data pendaftar
   - Kolom dinamis untuk setiap pertanyaan survey (jika survey aktif)
   - Header kolom: "SURVEI: [Teks Pertanyaan]"
   - Jawaban checkbox ditampilkan dengan format comma-separated

**Format Jawaban di Excel:**
- Text/Textarea: Teks asli
- Radio/Select: Value yang dipilih
- Checkbox: "value1, value2, value3"

---

### B. UNTUK PENDAFTAR

#### 1️⃣ Mengisi Survey

1. Login sebagai Pendaftar
2. Pastikan status pendaftaran **"Data Disetujui"**
3. Buka menu **Pendaftar** → **Tes Psikologi**
4. Jika survey aktif, akan muncul section **"Pertanyaan Survey"** dengan:
   - Badge status: **"Belum Diisi"** (kuning) atau **"Sudah Diisi"** (hijau)
   - Warning box kuning: "Survey wajib diisi sebelum upload bukti pembayaran!"
5. Jawab semua pertanyaan yang ditampilkan:
   - Pertanyaan dengan tanda **bintang merah (*)** wajib diisi
   - Pilih/isi jawaban sesuai tipe input
6. Klik tombol **"Simpan Jawaban Survey"**
7. Badge status akan berubah menjadi **"Sudah Diisi"** (hijau)
8. Warning box akan hilang

#### 2️⃣ Upload Bukti Pembayaran

Setelah survey selesai diisi:

1. Scroll ke bagian **"Upload Bukti Pembayaran"**
2. Isi nominal pembayaran
3. Pilih file bukti pembayaran (JPG/PNG, max 2MB)
4. Klik tombol **"Upload Bukti Pembayaran"**

**Catatan:**
- Jika survey belum diisi lengkap, tombol upload akan diblokir
- Error message: "Harap lengkapi survey terlebih dahulu sebelum upload bukti pembayaran"

#### 3️⃣ Mengedit Jawaban Survey

Pendaftar dapat mengedit jawaban survey kapan saja sebelum verifikasi:

1. Buka halaman **Tes Psikologi**
2. Edit jawaban yang ingin diubah
3. Klik **"Simpan Jawaban Survey"** lagi
4. Jawaban akan ter-update

---

## 🧪 Testing & Quality Assurance

### 1. Test Aktifasi Survey

**Test Case 1.1: Aktifkan Survey**
```
✅ EXPECTED:
- Toggle switch berubah hijau
- Alert sukses muncul
- Tab survey muncul di halaman pendaftar

❌ NEGATIVE TEST:
- Coba aktifkan tanpa buat pertanyaan dulu
- Result: Survey aktif tapi tidak ada pertanyaan (pendaftar tidak perlu isi)
```

**Test Case 1.2: Non-aktifkan Survey**
```
✅ EXPECTED:
- Toggle switch berubah abu-abu
- Alert sukses muncul
- Tab survey hilang di halaman pendaftar
- Kolom "Jawaban Survey" hilang di tabel admin
- Export tidak include kolom survey
```

### 2. Test CRUD Pertanyaan

**Test Case 2.1: Buat Pertanyaan - Text**
```
Input:
- Pertanyaan: "Apa motivasi Anda mendaftar di madrasah ini?"
- Tipe: Text
- Wajib Diisi: Ya
- Urutan: 1

✅ EXPECTED:
- Pertanyaan muncul di daftar
- Muncul di halaman pendaftar
- Input text box tersedia
```

**Test Case 2.2: Buat Pertanyaan - Radio + Opsi**
```
Input:
- Pertanyaan: "Bagaimana Anda mengetahui informasi PPDB kami?"
- Tipe: Radio
- Wajib Diisi: Ya
- Urutan: 2
- Opsi:
  * Label: "Media Sosial", Value: "medsos"
  * Label: "Website", Value: "website"
  * Label: "Teman/Keluarga", Value: "referral"

✅ EXPECTED:
- Pertanyaan muncul di daftar
- Form opsi muncul setelah save
- Radio buttons muncul di halaman pendaftar
- Hanya bisa pilih 1 opsi
```

**Test Case 2.3: Buat Pertanyaan - Checkbox + Opsi**
```
Input:
- Pertanyaan: "Ekstrakurikuler apa yang Anda minati?"
- Tipe: Checkbox
- Wajib Diisi: Tidak
- Urutan: 3
- Opsi:
  * Label: "Pramuka", Value: "pramuka"
  * Label: "Olahraga", Value: "olahraga"
  * Label: "Seni", Value: "seni"

✅ EXPECTED:
- Pertanyaan muncul di daftar
- Checkboxes muncul di halaman pendaftar
- Bisa pilih lebih dari 1 opsi
- Data tersimpan sebagai array
```

**Test Case 2.4: Edit Pertanyaan**
```
✅ EXPECTED:
- Form terisi dengan data lama
- Setelah save, perubahan tersimpan
- Jawaban pendaftar yang sudah ada tidak berubah
```

**Test Case 2.5: Hapus Pertanyaan**
```
⚠️ WARNING TEST:
- Hapus pertanyaan yang sudah dijawab 10 pendaftar
- Konfirmasi muncul

✅ EXPECTED:
- Pertanyaan terhapus
- Semua jawaban pendaftar ikut terhapus
- Export tidak include kolom pertanyaan tersebut
```

### 3. Test Validasi Pendaftar

**Test Case 3.1: Upload Bukti Tanpa Isi Survey**
```
Steps:
1. Survey aktif, ada 5 pertanyaan (3 wajib, 2 optional)
2. Pendaftar belum isi survey
3. Langsung coba upload bukti

✅ EXPECTED:
- Upload diblokir
- Error message: "Harap lengkapi survey terlebih dahulu"
- Badge status tetap "Belum Diisi"
```

**Test Case 3.2: Isi Survey Sebagian**
```
Steps:
1. Isi 2 dari 3 pertanyaan wajib
2. Klik "Simpan Jawaban Survey"

✅ EXPECTED:
- Validasi error muncul
- Badge tetap "Belum Diisi"
- Upload bukti tetap diblokir
```

**Test Case 3.3: Isi Survey Lengkap**
```
Steps:
1. Isi semua 3 pertanyaan wajib
2. Skip 2 pertanyaan optional
3. Klik "Simpan Jawaban Survey"

✅ EXPECTED:
- Alert sukses
- Badge berubah "Sudah Diisi"
- Upload bukti aktif
```

**Test Case 3.4: Edit Jawaban Survey**
```
Steps:
1. Survey sudah diisi dan disimpan
2. Edit 1 jawaban
3. Simpan lagi

✅ EXPECTED:
- Jawaban ter-update
- Badge tetap "Sudah Diisi"
- Data di database ter-update
```

### 4. Test Lihat Jawaban (Admin/Panitia)

**Test Case 4.1: Lihat Jawaban Pendaftar**
```
Steps:
1. Buka tab Data Psikologi
2. Klik "Lihat (5)" pada pendaftar yang sudah isi survey

✅ EXPECTED:
- Modal muncul
- Nama & nomor pendaftar tampil
- 5 pertanyaan & jawaban tampil dengan format:
  * Text: Teks biasa
  * Radio: 1 value
  * Checkbox: "value1, value2"
- Tombol close berfungsi
```

**Test Case 4.2: Lihat Pendaftar Belum Isi**
```
Steps:
1. Cek pendaftar yang belum isi survey

✅ EXPECTED:
- Kolom menampilkan: "Belum diisi" (italic abu-abu)
- Tidak ada tombol "Lihat"
```

### 5. Test Export Excel

**Test Case 5.1: Export dengan Survey Aktif**
```
Setup:
- Survey aktif dengan 3 pertanyaan:
  1. Text: "Motivasi"
  2. Radio: "Sumber Info"
  3. Checkbox: "Ekstrakurikuler"
- 10 pendaftar sudah isi survey

Steps:
1. Admin → Laporan
2. Klik "Export Data Pendaftar Lengkap"

✅ EXPECTED:
- File Excel terdownload
- Ada 3 kolom tambahan:
  * "SURVEI: Motivasi"
  * "SURVEI: Sumber Info"
  * "SURVEI: Ekstrakurikuler"
- Jawaban checkbox format: "pramuka, olahraga"
- Pendaftar yang belum isi: cell kosong
```

**Test Case 5.2: Export dengan Survey Non-aktif**
```
Steps:
1. Non-aktifkan survey
2. Export data

✅ EXPECTED:
- File Excel terdownload
- Tidak ada kolom survey
- Hanya data pendaftar biasa
```

### 6. Test Edge Cases

**Test Case 6.1: Survey Aktif Tanpa Pertanyaan**
```
Setup:
- Survey aktif
- Tidak ada pertanyaan aktif

✅ EXPECTED:
- Pendaftar tidak lihat form survey
- Upload bukti langsung bisa (tidak diblokir)
- Export tidak ada kolom survey
```

**Test Case 6.2: Pertanyaan Dinonaktifkan Setelah Dijawab**
```
Steps:
1. Pendaftar isi survey dengan 5 pertanyaan
2. Admin nonaktifkan 2 pertanyaan
3. Cek halaman pendaftar

✅ EXPECTED:
- Hanya 3 pertanyaan aktif yang muncul
- Jawaban 2 pertanyaan nonaktif tetap tersimpan di database
- Tidak muncul di export
```

**Test Case 6.3: Hapus Opsi Setelah Pendaftar Pilih**
```
Steps:
1. Pertanyaan radio dengan opsi A, B, C
2. Pendaftar pilih B
3. Admin hapus opsi B dan tambah opsi D
4. Lihat jawaban pendaftar

✅ EXPECTED:
- Jawaban tetap "B" (value tersimpan)
- Di export tetap muncul "B"
- Pendaftar lihat opsi: A, C, D (B hilang)
```

**Test Case 6.4: Ganti Tipe Pertanyaan**
```
⚠️ DANGEROUS TEST:
1. Pertanyaan radio (value: "a")
2. Pendaftar jawab: "a"
3. Admin ganti tipe jadi checkbox
4. Pendaftar lihat lagi

✅ EXPECTED:
- Jawaban lama tetap tersimpan
- Form berubah jadi checkbox
- Pendaftar bisa jawab ulang
```

### 7. Test Performa

**Test Case 7.1: Load Time dengan Banyak Pertanyaan**
```
Setup:
- 20 pertanyaan aktif
- 1000 pendaftar
- 500 sudah isi survey

Test:
- Buka halaman admin Data Psikologi
- Export Excel

✅ EXPECTED:
- Load < 3 detik
- Export < 30 detik
- Tidak ada timeout error
```

**Test Case 7.2: Concurrent Edit Pertanyaan**
```
Setup:
- 2 admin login bersamaan
- Edit pertanyaan yang sama

Test:
- Admin A edit pertanyaan ID 1
- Admin B edit pertanyaan ID 1
- Save bersamaan

✅ EXPECTED:
- Save terakhir yang berlaku (last write wins)
- Tidak ada data corruption
```

---

## 🐛 Common Issues & Troubleshooting

### Issue 1: Survey tidak muncul di halaman pendaftar
**Kemungkinan Penyebab:**
- Survey belum diaktifkan
- Tidak ada pertanyaan aktif
- Cache view belum di-clear

**Solusi:**
```bash
php artisan view:clear
php artisan cache:clear
```

### Issue 2: Error "Method active does not exist"
**Penyebab:** Method scope salah di model

**Solusi:**
Pastikan di `SurveyQuestion.php` ada method:
```php
public function scopeAktif($query)
{
    return $query->where('aktif', true);
}
```

### Issue 3: Jawaban checkbox tidak tersimpan
**Penyebab:** Casting array tidak berfungsi

**Solusi:**
Pastikan di `SurveyAnswer.php`:
```php
protected $casts = [
    'jawaban' => 'array',
];
```

### Issue 4: Export error "question_text not found"
**Penyebab:** Field name tidak sesuai

**Solusi:**
Di `PendaftarExport.php` line 821, pastikan:
```php
$surveyHeadings[] = 'SURVEI: ' . $question->pertanyaan;
```

### Issue 5: Modal jawaban tidak muncul
**Penyebab:** JavaScript conflict atau z-index

**Solusi:**
Pastikan modal punya z-index tinggi:
```html
<div class="fixed inset-0 z-[9999]">
```

---

## 📊 Database Schema

### Table: `survey_questions`
```sql
id                  BIGINT (PK)
pertanyaan          TEXT
deskripsi           TEXT (nullable)
tipe_input          ENUM('text','textarea','radio','checkbox','select')
wajib_diisi         BOOLEAN (default: false)
urutan              INTEGER (default: 0)
aktif               BOOLEAN (default: true)
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

### Table: `survey_question_options`
```sql
id                  BIGINT (PK)
survey_question_id  BIGINT (FK → survey_questions.id)
label               VARCHAR
value               VARCHAR
urutan              INTEGER (default: 0)
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

### Table: `survey_answers`
```sql
id                  BIGINT (PK)
pendaftar_id        BIGINT (FK → pendaftar.id)
survey_question_id  BIGINT (FK → survey_questions.id)
jawaban             TEXT (JSON for checkbox)
created_at          TIMESTAMP
updated_at          TIMESTAMP

UNIQUE KEY: (pendaftar_id, survey_question_id)
```

### Table: `tes_psikologi_settings`
```sql
...existing columns...
survey_aktif        BOOLEAN (default: false)
survey_informasi    TEXT (nullable)
```

---

## 🔒 Security Considerations

1. **Authorization:**
   - ✅ Hanya admin & panitia_tes_psikologi bisa CRUD survey
   - ✅ Pendaftar hanya bisa isi/edit jawaban sendiri
   - ✅ Middleware role checking aktif

2. **Validation:**
   - ✅ Server-side validation di Livewire component
   - ✅ Unique constraint untuk prevent duplicate answers
   - ✅ XSS protection dengan blade `{{ }}` syntax

3. **Data Integrity:**
   - ✅ Foreign key constraints dengan onDelete cascade
   - ✅ Transaction untuk prevent data inconsistency
   - ✅ Unique constraint (pendaftar_id, survey_question_id)

---

## 📈 Performance Optimization

1. **Query Optimization:**
   - ✅ Eager loading: `with('surveyAnswers.question.options')`
   - ✅ Scopes untuk reusable queries
   - ✅ Region cache untuk export (prevent N+1)

2. **Caching:**
   - Survey questions di-load sekali di constructor export
   - View cache di-clear setelah perubahan

3. **Database Indexing:**
   - Index pada: `aktif`, `urutan`, `survey_question_id`
   - Unique index pada `(pendaftar_id, survey_question_id)`

---

## 📞 Support & Maintenance

### File Locations
```
Models:
├── app/Models/SurveyQuestion.php
├── app/Models/SurveyQuestionOption.php
├── app/Models/SurveyAnswer.php
└── app/Models/TesPsikologiSetting.php

Migrations:
├── database/migrations/2026_01_28_000001_create_survey_questions_table.php
├── database/migrations/2026_01_28_000002_create_survey_question_options_table.php
├── database/migrations/2026_01_28_000003_create_survey_answers_table.php
└── database/migrations/2026_01_28_000004_add_survey_fields_to_tes_psikologi_settings_table.php

Livewire Components:
├── app/Livewire/Admin/TesPsikologi.php
├── app/Livewire/Panitia/TesPsikologi.php
└── app/Livewire/Pendaftar/TesPsikologi.php

Views:
├── resources/views/livewire/admin/partials/tes-psikologi-survey.blade.php
├── resources/views/livewire/admin/partials/tes-psikologi-data.blade.php
└── resources/views/livewire/pendaftar/tes-psikologi.blade.php

Export:
└── app/Exports/PendaftarExport.php
```

### Maintenance Commands
```bash
# Clear cache
php artisan view:clear
php artisan cache:clear
php artisan config:clear

# Check database
php artisan tinker
>>> \App\Models\SurveyQuestion::count()
>>> \App\Models\SurveyAnswer::count()

# Rollback migrations (DANGER!)
php artisan migrate:rollback --step=4
```

---

## 🎓 Best Practices

1. **Membuat Pertanyaan:**
   - Gunakan bahasa yang jelas dan sederhana
   - Hindari pertanyaan ganda (compound questions)
   - Limit 10-15 pertanyaan untuk user experience
   - Urutan logis (dari umum ke spesifik)

2. **Mengelola Opsi:**
   - Gunakan value yang konsisten (lowercase_underscore)
   - Minimal 2 opsi, maksimal 10 opsi
   - Sertakan opsi "Lainnya" jika perlu
   - Hindari opsi yang terlalu mirip

3. **Testing:**
   - Test setelah setiap perubahan
   - Test dengan berbagai role (admin, panitia, pendaftar)
   - Test edge cases (data kosong, data invalid)
   - Test performa dengan data besar

---

## 📝 Changelog

### Version 1.0.0 (2026-01-28)
- ✅ Initial release
- ✅ CRUD pertanyaan survey
- ✅ 5 tipe input support
- ✅ Validasi upload bukti pembayaran
- ✅ Modal lihat jawaban
- ✅ Export Excel integration
- ✅ Role-based access control

---

## 📄 License & Credits

**Developed for:** MTsN 2 Kota Malang PPDB System
**Date:** January 28, 2026
**Framework:** Laravel 12.47.0 + Livewire v3
**Database:** PostgreSQL

---

**🚀 Happy Surveying! 🎯**
