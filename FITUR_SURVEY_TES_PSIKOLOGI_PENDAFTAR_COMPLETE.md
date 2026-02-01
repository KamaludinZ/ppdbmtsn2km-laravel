# Fitur Survey Tes Psikologi Pendaftar - COMPLETE ✅

## 📋 Ringkasan Perubahan
Menambahkan fitur survey yang bersifat **conditional** pada halaman `/pendaftar/tes-psikologi`. Survey muncul di bawah "Informasi Tes Psikologi" dan **wajib diisi** sebelum pendaftar dapat upload bukti pembayaran.

## 🎯 Tujuan
1. Menampilkan bagian "Informasi Survey" di posisi yang tepat (setelah Informasi Tes Psikologi)
2. Survey **hanya muncul** jika diaktifkan di admin (`/tes-psikologi?mainTab=survey`)
3. Jika survey **aktif dan belum diisi** → form upload bukti transfer **TERKUNCI**
4. Jika survey **tidak aktif** → bagian survey tidak muncul dan pendaftar langsung bisa upload

## 📂 File yang Dimodifikasi

### 1. **resources/views/livewire/pendaftar/tes-psikologi.blade.php**

#### Perubahan Urutan Section:
```
1. Informasi Tes Psikologi ✅
2. ⭐ Informasi Survey (BARU - Conditional) ⭐
3. Informasi Rekening Tujuan
4. Biaya Tes Psikologi
5. Status Alert
6. Upload Bukti Transfer (dengan validasi survey)
```

#### Fitur Baru pada Section Survey:
- **Badge Status**: Menampilkan "Sudah Diisi" (hijau) atau "Belum Diisi" (kuning)
- **Conditional Display**:
  - ✅ **Jika sudah diisi**: Hanya tampilkan pesan sukses "Survey Telah Dilengkapi" + badge hijau
  - ⚠️ **Jika belum diisi**: Tampilkan form lengkap dengan alert dan pertanyaan
- **Peringatan**: Alert kuning jika survey belum diisi lengkap
- **Informasi Survey**: Menampilkan `survey_informasi` dari setting (hanya saat belum diisi)
- **Form Survey**: Pertanyaan dengan berbagai tipe input (text, textarea, radio, checkbox, select)
- **Button Simpan**: Tombol untuk menyimpan jawaban survey

#### Fitur Baru pada Section Upload:
- **Peringatan Merah**: Muncul jika survey aktif tapi belum diisi
- **Form Terkunci**: Icon gembok dan pesan jika survey belum lengkap
- **Form Muncul**: Hanya muncul jika `$surveyCompleted === true`

### 2. **app/Livewire/Pendaftar/TesPsikologi.php**

#### Perubahan pada Method `simpanSurvey()`:
```php
// Tambahan setelah berhasil simpan
$this->dispatch('survey-saved');
session()->flash('message', 'Survey berhasil disimpan. Anda sekarang dapat melanjutkan ke upload bukti transfer.');
```

**Fungsi**:
- Dispatch event `survey-saved` untuk trigger smooth scroll ke section upload
- Pesan sukses yang lebih informatif

#### Logic Validasi yang Sudah Ada:
```php
public function uploadBukti()
{
    // Check survey completion first if survey is active
    if ($this->setting->survey_aktif && !$this->surveyCompleted) {
        session()->flash('error', 'Harap lengkapi survey terlebih dahulu sebelum upload bukti pembayaran');
        return;
    }
    // ... rest of upload logic
}
```

## 🎨 UI/UX Improvements

### 1. **Warna Section Survey**: `bg-indigo-600` (Ungu)
   - Membedakan dengan section lain
   - Mudah dikenali oleh pendaftar

### 2. **Badge Status Survey**:
   - ✅ **Hijau** dengan icon checkmark: "Sudah Diisi"
   - ⚠️ **Kuning** dengan icon warning: "Belum Diisi"

### 3. **Collapsed Content Saat Sudah Diisi**:
   - **Form pertanyaan DISEMBUNYIKAN** setelah survey disimpan
   - Hanya tampilkan pesan sukses dengan icon checkmark hijau
   - Hemat space dan fokus user ke section upload
   - User tidak perlu scroll panjang melewati form yang sudah diisi

### 4. **Alert Peringatan**:
   - **Alert Kuning** (di dalam section survey): Info bahwa survey wajib diisi (hanya saat belum diisi)
   - **Alert Merah** (di section upload): Peringatan form terkunci karena survey belum diisi

### 5. **Smooth Scroll Effect**:
   - Setelah simpan survey → auto scroll ke section upload
   - Section upload di-highlight dengan `ring-4 ring-purple-300` selama 2 detik

### 6. **Icon Gembok**:
   - Muncul saat form upload terkunci
   - Memberikan visual feedback yang jelas

## 🔄 Alur Kerja (User Flow)

### Skenario 1: Survey AKTIF - Belum Diisi
```
1. Pendaftar masuk ke /pendaftar/tes-psikologi
2. Melihat "Informasi Tes Psikologi"
3. ⭐ Melihat "Informasi Survey" dengan badge "Belum Diisi" (kuning)
4. Melihat alert kuning: "Survey Wajib Diisi!"
5. Melihat informasi survey dan form pertanyaan lengkap
6. Scroll ke bawah → form upload TERKUNCI dengan icon gembok
7. Kembali ke atas dan mengisi pertanyaan survey
8. Klik "Simpan Jawaban Survey"
9. ✅ Badge berubah menjadi "Sudah Diisi" (hijau)
10. ✅ Form pertanyaan DISEMBUNYIKAN
11. ✅ Hanya tampil pesan: "Survey Telah Dilengkapi" dengan icon checkmark
12. Auto scroll ke section "Upload Bukti Transfer"
13. Section upload ter-highlight sebentar (purple ring)
14. Form upload sekarang TERBUKA dan dapat diisi
15. Upload bukti transfer seperti biasa
```

### Skenario 1b: Survey AKTIF - Sudah Diisi (Kunjungan Ulang)
```
1. Pendaftar masuk ke /pendaftar/tes-psikologi (sudah pernah isi survey)
2. Melihat "Informasi Tes Psikologi"
3. ⭐ Melihat "Informasi Survey" dengan badge "Sudah Diisi" (hijau)
4. ✅ Content survey: Hanya pesan sukses singkat (form DISEMBUNYIKAN)
5. Langsung scroll ke section upload
6. Form upload TERBUKA dan dapat diisi
7. Upload bukti transfer seperti biasa
```

### Skenario 2: Survey TIDAK AKTIF
```
1. Pendaftar masuk ke /pendaftar/tes-psikologi
2. Melihat "Informasi Tes Psikologi"
3. (Section survey TIDAK MUNCUL)
4. Langsung ke "Informasi Rekening Tujuan"
5. Langsung ke "Biaya Tes Psikologi"
6. Langsung ke "Upload Bukti Transfer" (form TERBUKA)
7. Upload bukti transfer seperti biasa
```

### Skenario 3: Survey AKTIF tapi Pendaftar Belum Isi
```
1. Pendaftar masuk ke /pendaftar/tes-psikologi
2. Melihat survey tapi skip/scroll ke bawah
3. Di section "Upload Bukti Transfer" melihat:
   - ⚠️ Alert merah: "Tidak Dapat Upload Bukti Transfer"
   - 🔒 Icon gembok dengan pesan: "Form Upload Terkunci"
   - Instruksi: Scroll ke atas dan isi survey
4. Pendaftar scroll ke atas
5. Isi survey dan simpan
6. Form upload terbuka otomatis
```

## ✅ Validasi Backend

### 1. **Method `checkSurveyCompletion()`**:
```php
// Jika survey tidak aktif → auto set completed = true
if (!$this->setting->survey_aktif || !$this->surveyQuestions) {
    $this->surveyCompleted = true;
    return;
}

// Jika survey aktif → cek apakah semua pertanyaan wajib sudah diisi
foreach ($this->surveyQuestions as $question) {
    if ($question->wajib_diisi) {
        // Validasi jawaban tidak kosong
    }
}
```

### 2. **Method `uploadBukti()`**:
```php
// Validasi survey SEBELUM upload
if ($this->setting->survey_aktif && !$this->surveyCompleted) {
    session()->flash('error', 'Harap lengkapi survey terlebih dahulu...');
    return;
}
```

## 📝 Catatan Penting

1. **Property `$surveyCompleted`** di-set di method `mount()` dan `checkSurveyCompletion()`
2. **Jika survey tidak aktif**, `$surveyCompleted` otomatis `true` sehingga form upload langsung terbuka
3. **Survey questions** di-filter berdasarkan context `tes_psikologi` menggunakan scope `tesPsikologi()`
4. **Existing answers** dimuat saat mount untuk menampilkan jawaban yang sudah disimpan sebelumnya
5. **Real-time validation** menggunakan Livewire wire:model untuk update status survey

## 🧪 Testing

### Test Case 1: Survey Aktif - Belum Diisi
- [ ] Survey section muncul dengan badge "Belum Diisi"
- [ ] Alert kuning muncul: "Survey wajib diisi..."
- [ ] Form upload menampilkan icon gembok
- [ ] Tidak bisa upload bukti transfer

### Test Case 2: Survey Aktif - Sudah Diisi Sebagian
- [ ] Survey section muncul dengan badge "Belum Diisi"
- [ ] Alert kuning masih muncul
- [ ] Form upload masih terkunci
- [ ] Klik "Simpan" → error validation

### Test Case 3: Survey Aktif - Sudah Diisi Lengkap
- [ ] Survey section dengan badge "Sudah Diisi" (hijau)
- [ ] Alert kuning TIDAK muncul
- [ ] Form upload TERBUKA
- [ ] Bisa upload bukti transfer

### Test Case 4: Survey Tidak Aktif
- [ ] Survey section TIDAK MUNCUL
- [ ] Form upload langsung TERBUKA
- [ ] Bisa upload bukti transfer tanpa isi survey

### Test Case 5: Smooth Scroll After Save
- [ ] Isi survey → Klik "Simpan"
- [ ] Muncul notifikasi sukses hijau
- [ ] Auto scroll ke section upload
- [ ] Section upload ter-highlight purple ring
- [ ] Ring hilang setelah 2 detik

## 📊 Status
✅ **COMPLETE** - Semua fitur telah diimplementasikan dan siap untuk testing

## 🔗 Related Files
- `app/Livewire/Pendaftar/TesPsikologi.php`
- `resources/views/livewire/pendaftar/tes-psikologi.blade.php`
- `resources/views/livewire/pendaftar/tes-psikologi-form.blade.php`
- `app/Models/TesPsikologiSetting.php`
- `app/Models/SurveyQuestion.php`
- `app/Models/SurveyAnswer.php`

## 🎯 Next Steps
1. Test semua skenario di environment development
2. Pastikan survey questions sudah dibuat di admin
3. Pastikan setting `survey_aktif` dan `survey_informasi` sudah diatur
4. Test upload flow dengan berbagai kondisi survey
5. Upload ke production setelah testing berhasil

---
**Dibuat**: 29 Januari 2026
**Developer**: AI Assistant
**Status**: Ready for Testing ✅
