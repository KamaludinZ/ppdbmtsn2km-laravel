# Quick Test Guide - Survey Tes Psikologi Pendaftar

## 🚀 Cara Test Fitur Survey

### Setup Awal

1. **Pastikan sudah login sebagai Admin**
2. **Buka halaman** `/admin/tes-psikologi?mainTab=survey`
3. **Aktifkan Survey**:
   - Toggle "Aktifkan Survey" menjadi ON
   - Isi "Informasi Survey" (contoh: "Mohon isi survey berikut untuk keperluan evaluasi")
   - Klik "Simpan Pengaturan"

4. **Buat Pertanyaan Survey** (minimal 2 pertanyaan):
   - Klik "Tambah Pertanyaan"
   - **Pertanyaan 1**:
     - Pertanyaan: "Dari mana Anda mengetahui informasi PPDB ini?"
     - Tipe: Radio Button
     - Options: "Media Sosial", "Website", "Teman/Keluarga", "Brosur"
     - Context: Tes Psikologi
     - Wajib Diisi: YA
     - Status: Aktif
   - **Pertanyaan 2**:
     - Pertanyaan: "Apa motivasi Anda mendaftar di madrasah ini?"
     - Tipe: Textarea
     - Context: Tes Psikologi
     - Wajib Diisi: YA
     - Status: Aktif

### Test Skenario 1: Survey Aktif - Belum Diisi

1. **Login sebagai Pendaftar**
2. Buka `/pendaftar/tes-psikologi`
3. **Cek Urutan Section**:
   ```
   ✅ Informasi Tes Psikologi
   ⭐ Informasi Survey (BARU)
   ✅ Informasi Rekening Tujuan
   ✅ Biaya Tes Psikologi
   ✅ Upload Bukti Transfer
   ```

4. **Cek Section "Informasi Survey"**:
   - [ ] Header berwarna ungu (indigo-600)
   - [ ] Ada badge "Belum Diisi" (kuning)
   - [ ] Ada alert kuning: "Survey Wajib Diisi!"
   - [ ] Muncul form pertanyaan survey
   - [ ] Ada tombol "Simpan Jawaban Survey"

5. **Scroll ke Section "Upload Bukti Transfer"**:
   - [ ] Ada alert merah: "Tidak Dapat Upload Bukti Transfer"
   - [ ] Ada icon gembok besar
   - [ ] Pesan: "Form Upload Terkunci"
   - [ ] Form upload TIDAK MUNCUL

6. **Coba Upload Tanpa Isi Survey** (test validasi backend):
   - Buka console browser
   - Coba panggil method uploadBukti via Livewire
   - Harus muncul error: "Harap lengkapi survey terlebih dahulu..."

### Test Skenario 2: Isi Survey

1. **Scroll kembali ke Section "Informasi Survey"**
2. **Isi Pertanyaan 1** (Radio): Pilih "Media Sosial"
3. **Isi Pertanyaan 2** (Textarea): Ketik minimal 10 kata
4. **Klik "Simpan Jawaban Survey"**
5. **Cek Hasil - Section Survey**:
   - [ ] Muncul notifikasi hijau: "Survey berhasil disimpan..."
   - [ ] Badge berubah menjadi "Sudah Diisi" (hijau)
   - [ ] ⭐ **Form pertanyaan DISEMBUNYIKAN**
   - [ ] ⭐ Hanya tampil pesan: "Survey Telah Dilengkapi" dengan icon checkmark hijau
   - [ ] Alert kuning HILANG
   - [ ] Informasi survey HILANG

6. **Cek Auto Scroll dan Highlight**:
   - [ ] Auto scroll ke section "Upload Bukti Transfer"
   - [ ] Section upload ter-highlight dengan ring ungu
   - [ ] Ring hilang setelah 2 detik

7. **Cek Section "Upload Bukti Transfer"**:
   - [ ] Alert merah HILANG
   - [ ] Icon gembok HILANG
   - [ ] Form upload MUNCUL dan dapat diisi
   - [ ] Bisa pilih opsi biaya, waktu transfer, bank, dan upload file

8. **Test Upload Bukti Transfer**:
   - Isi semua field
   - Upload gambar bukti transfer
   - Klik "Upload Bukti Transfer"
   - [ ] Upload BERHASIL
   - [ ] Status berubah menjadi "Menunggu Verifikasi"

9. **Test Kunjungan Ulang** (Refresh halaman):
   - [ ] Badge tetap "Sudah Diisi" (hijau)
   - [ ] Form pertanyaan tetap DISEMBUNYIKAN
   - [ ] Hanya tampil pesan sukses
   - [ ] Form upload tetap TERBUKA

### Test Skenario 3: Survey Tidak Aktif

1. **Login sebagai Admin**
2. Buka `/admin/tes-psikologi?mainTab=survey`
3. **Non-aktifkan Survey**:
   - Toggle "Aktifkan Survey" menjadi OFF
   - Klik "Simpan Pengaturan"

4. **Login sebagai Pendaftar Baru**
5. Buka `/pendaftar/tes-psikologi`
6. **Cek Urutan Section**:
   ```
   ✅ Informasi Tes Psikologi
   ❌ Informasi Survey (TIDAK MUNCUL)
   ✅ Informasi Rekening Tujuan
   ✅ Biaya Tes Psikologi
   ✅ Upload Bukti Transfer
   ```

7. **Cek Section "Upload Bukti Transfer"**:
   - [ ] Form upload langsung MUNCUL
   - [ ] TIDAK ADA alert merah atau icon gembok
   - [ ] Bisa langsung upload tanpa isi survey

8. **Test Upload Bukti Transfer**:
   - Isi semua field dan upload
   - [ ] Upload BERHASIL tanpa masalah

### Test Skenario 4: Edit Jawaban Survey

1. **Login sebagai Pendaftar yang sudah isi survey**
2. Buka `/pendaftar/tes-psikologi`
3. **Cek Section Survey**:
   - [ ] Badge: "Sudah Diisi" (hijau)
   - [ ] Jawaban sebelumnya sudah ter-load di form
   - [ ] Bisa edit jawaban
   - [ ] Klik "Simpan Jawaban Survey"
   - [ ] Muncul notifikasi sukses

### Test Skenario 5: Survey Wajib vs Opsional

1. **Login sebagai Admin**
2. Buka `/admin/tes-psikologi?mainTab=survey`
3. **Edit Pertanyaan 2**:
   - Ubah "Wajib Diisi" menjadi TIDAK
   - Simpan

4. **Login sebagai Pendaftar Baru**
5. Buka `/pendaftar/tes-psikologi`
6. **Test Isi Survey**:
   - Isi hanya Pertanyaan 1 (wajib)
   - SKIP Pertanyaan 2 (opsional)
   - Klik "Simpan Jawaban Survey"
   - [ ] Survey berhasil disimpan (karena pertanyaan wajib sudah diisi)
   - [ ] Form upload terbuka

7. **Test dengan Skip Pertanyaan Wajib**:
   - Refresh halaman
   - SKIP Pertanyaan 1 (wajib)
   - Isi Pertanyaan 2 (opsional)
   - Klik "Simpan Jawaban Survey"
   - [ ] Muncul error: "Harap lengkapi semua pertanyaan wajib"
   - [ ] Form upload tetap terkunci

## 🔍 Checklist Testing Lengkap

### Visual & UI
- [ ] Section survey muncul di posisi yang benar (setelah Informasi Tes Psikologi)
- [ ] Warna header survey: Ungu/Indigo
- [ ] Badge status survey tampil dengan benar
- [ ] Alert kuning muncul saat survey belum diisi
- [ ] Alert merah muncul di section upload saat survey belum diisi
- [ ] Icon gembok tampil dengan jelas
- [ ] Form questions tampil dengan rapi (saat belum diisi)
- [ ] ⭐ Form questions DISEMBUNYIKAN (saat sudah diisi)
- [ ] ⭐ Pesan "Survey Telah Dilengkapi" tampil (saat sudah diisi)
- [ ] Button "Simpan" tampil dengan style yang konsisten (saat belum diisi)

### Functionality
- [ ] Survey hanya muncul jika `survey_aktif = true`
- [ ] Survey tidak muncul jika `survey_aktif = false`
- [ ] Pertanyaan ter-filter berdasarkan context `tes_psikologi`
- [ ] Jawaban tersimpan dengan benar di database
- [ ] Existing answers ter-load saat page load
- [ ] Validasi pertanyaan wajib berfungsi
- [ ] Form upload terkunci jika survey belum diisi
- [ ] Form upload terbuka jika survey sudah diisi
- [ ] Upload bukti transfer berhasil setelah survey diisi
- [ ] ⭐ Form survey collapse (disembunyikan) setelah disimpan
- [ ] ⭐ Form survey tetap collapsed setelah refresh halaman
- [ ] ⭐ Badge status persist setelah refresh halaman

### User Experience
- [ ] Smooth scroll setelah simpan survey
- [ ] Highlight effect pada section upload
- [ ] Notifikasi sukses muncul setelah simpan
- [ ] Pesan error jelas dan informatif
- [ ] Loading state saat simpan survey
- [ ] Loading state saat upload bukti transfer

### Backend Validation
- [ ] Method `checkSurveyCompletion()` berfungsi
- [ ] Method `simpanSurvey()` validasi dengan benar
- [ ] Method `uploadBukti()` cek survey completion
- [ ] Event `survey-saved` di-dispatch
- [ ] Database menyimpan jawaban dengan format yang benar

## 🐛 Common Issues & Solutions

### Issue 1: Survey Section Tidak Muncul
**Solusi**:
- Pastikan `survey_aktif = true` di database
- Pastikan ada minimal 1 survey question dengan status aktif
- Pastikan survey question memiliki `context = 'tes_psikologi'`

### Issue 2: Form Upload Tetap Terkunci Setelah Simpan Survey
**Solusi**:
- Cek console browser untuk error JavaScript
- Pastikan event `survey-saved` di-dispatch
- Refresh halaman untuk reload component state
- Cek database `survey_answers` apakah jawaban tersimpan

### Issue 3: Smooth Scroll Tidak Berfungsi
**Solusi**:
- Pastikan Livewire v3 sudah terinstall
- Cek console untuk error JavaScript
- Pastikan script di push('scripts') ter-load

### Issue 4: Badge Status Tidak Update
**Solusi**:
- Refresh halaman setelah simpan survey
- Cek method `checkSurveyCompletion()` di backend
- Pastikan property `$surveyCompleted` ter-update

## 📊 Expected Results

### Database Tables yang Terpengaruh:
1. **tes_psikologi_settings**:
   - `survey_aktif` (boolean)
   - `survey_informasi` (text)

2. **survey_questions**:
   - Filter by `context = 'tes_psikologi'`
   - `aktif = 1`

3. **survey_answers**:
   - `pendaftar_id` = ID pendaftar yang login
   - `survey_question_id` = ID pertanyaan
   - `jawaban` = Array jawaban (JSON)

4. **tes_psikologi_pendaftar**:
   - Data upload bukti transfer (tidak berubah)

## ✅ Success Criteria

Fitur dianggap berhasil jika:
1. ✅ Survey muncul/tidak muncul sesuai setting `survey_aktif`
2. ✅ Form upload terkunci jika survey belum diisi
3. ✅ Form upload terbuka setelah survey diisi
4. ✅ Validasi pertanyaan wajib berfungsi
5. ✅ Smooth scroll dan highlight effect berfungsi
6. ✅ Jawaban tersimpan dengan benar di database
7. ✅ Upload bukti transfer berhasil setelah survey diisi
8. ✅ Tidak ada error di console browser
9. ✅ Tidak ada error di Laravel log

---
**Testing Date**: _________
**Tester**: _________
**Result**: ☐ PASS | ☐ FAIL
**Notes**: _________
