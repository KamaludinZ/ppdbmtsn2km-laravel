# FIX: UPLOAD BUTTON & TOMBOL LIHAT BUKTI

**Tanggal:** 5 Februari 2026
**Status:** COMPLETED

---

## 📋 RINGKASAN PERUBAHAN

### Perubahan Utama:
1. ✅ Batalkan auto-upload (wire:model.live), gunakan tombol Upload terpisah
2. ✅ Tambahkan tombol "Lihat Bukti" yang prominent untuk file yang sudah terupload
3. ✅ Terapkan perubahan yang sama untuk Tab Madrasah dan Tab Seragam

---

## 🎯 PERUBAHAN YANG DILAKUKAN

### 1. Tab Madrasah - Surat Pernyataan

**Before:**
- Auto-upload dengan wire:model.live
- Nama file ditampilkan setelah pilih

**After:**
- File picker dengan wire:model
- Tombol "Upload Surat Pernyataan" muncul setelah pilih file
- Loading indicator saat upload
- Tombol "Lihat Bukti" (biru) setelah file terupload

**Lokasi:** `resources/views/livewire/pendaftar/daftar-ulang.blade.php` (Lines 827-863)

---

### 2. Tab Madrasah - Bukti Pembayaran

**Before:**
- Upload form dengan loading indicator sederhana
- Tombol "Lihat Bukti" tidak prominent

**After:**
- File picker dengan wire:model
- Tombol "Upload Bukti Pembayaran" muncul setelah pilih file
- Loading indicator dengan spinner animation
- Tombol "Lihat Bukti" (emerald green) yang prominent di sebelah kanan
- Tombol "Download Invoice" full-width jika sudah disetujui

**Lokasi:** `resources/views/livewire/pendaftar/daftar-ulang.blade.php` (Lines 681-722, 746-782)

---

### 3. Tab Seragam - Bukti Pembayaran

**Before:**
- Upload form sederhana
- Tombol "Lihat Bukti" tidak prominent

**After:**
- File picker dengan wire:model
- Tombol "Upload Bukti Pembayaran" muncul setelah pilih file
- Loading indicator dengan spinner animation
- Tombol "Lihat Bukti" (biru) yang prominent di sebelah kanan
- Tombol "Download Invoice" full-width jika sudah disetujui

**Lokasi:** `resources/views/livewire/pendaftar/daftar-ulang.blade.php` (Lines 1379-1482)

---

## 💻 METHOD CONTROLLER BARU

### 1. uploadSuratPernyataan()

**Fungsi:** Upload surat pernyataan secara terpisah tanpa menyimpan data form lain

**Fitur:**
- Validasi file (PDF, max 2MB)
- Check status approved
- Generate filename sistematis
- Delete old file jika ada
- Upload ke private storage
- Reset file input setelah berhasil

**Lokasi:** `app/Livewire/Pendaftar/DaftarUlang.php` (Lines 825-881)

---

### 2. uploadBuktiPembayaran()

**Fungsi:** Upload bukti pembayaran madrasah secara terpisah

**Fitur:**
- Check payment period
- Validasi file (PDF/JPG/PNG, max 2MB)
- Check status approved
- Check surat pernyataan sudah upload (validasi bertahap)
- Generate filename sistematis
- Delete old file jika ada
- Upload ke private storage
- Update status ke "menunggu"
- Reset file input setelah berhasil

**Lokasi:** `app/Livewire/Pendaftar/DaftarUlang.php` (Lines 883-947)

---

### 3. uploadBuktiSeragam()

**Fungsi:** Upload bukti pembayaran seragam secara terpisah

**Fitur:**
- Check payment period
- Validasi file (PDF/JPG/PNG, max 5MB)
- Check status approved
- Generate filename sistematis
- Delete old file jika ada
- Upload ke private storage
- Update status ke "menunggu"
- Reset file input setelah berhasil

**Lokasi:** `app/Livewire/Pendaftar/DaftarUlang.php` (Lines 949-1015)

---

## 🎨 UI/UX IMPROVEMENTS

### Upload Flow:

**Before:**
```
1. User pilih file
2. File langsung auto-upload (wire:model.live)
3. Loading state tidak jelas
4. File tersimpan
```

**After:**
```
1. User pilih file
2. Nama file ditampilkan (blue highlight)
3. Tombol "Upload" muncul dengan loading indicator
4. User klik tombol "Upload"
5. Loading indicator (spinner + text)
6. File tersimpan
7. Tombol "Lihat Bukti" muncul (prominent)
```

### Tombol "Lihat Bukti":

**Design:**
- Background: Blue/Emerald (tergantung tab)
- Icon: Eye icon (SVG)
- Text: "Lihat Bukti"
- Style: `inline-flex items-center px-3 py-2`
- Target: `_blank` (buka di tab baru)

**Posisi:**
- Berada di sebelah kanan message "File telah diupload"
- Mudah dilihat dan diklik

---

## 📝 FILES MODIFIED

### 1. View (1 file)
```
resources/views/livewire/pendaftar/daftar-ulang.blade.php
```

**Changes:**
- Upload Surat Pernyataan: Lines 827-863
- Upload Bukti Pembayaran Madrasah: Lines 681-722, 746-782
- Upload Bukti Pembayaran Seragam: Lines 1379-1482

### 2. Controller (1 file)
```
app/Livewire/Pendaftar/DaftarUlang.php
```

**New Methods:**
- `uploadSuratPernyataan()`: Lines 825-881
- `uploadBuktiPembayaran()`: Lines 883-947
- `uploadBuktiSeragam()`: Lines 949-1015

---

## 🚀 DEPLOYMENT

### Files to Upload:
1. `resources/views/livewire/pendaftar/daftar-ulang.blade.php`
2. `app/Livewire/Pendaftar/DaftarUlang.php`

### Upload Command:
```bash
scp resources/views/livewire/pendaftar/daftar-ulang.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/

scp app/Livewire/Pendaftar/DaftarUlang.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/

ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
```

---

## ✅ TESTING CHECKLIST

### Tab Madrasah:

- [ ] **Surat Pernyataan:**
  - [ ] Klik area upload → file picker terbuka
  - [ ] Pilih file PDF → nama file muncul (blue)
  - [ ] Tombol "Upload Surat Pernyataan" muncul
  - [ ] Klik upload → loading indicator muncul
  - [ ] Setelah upload → tombol "Lihat Bukti" muncul (biru)
  - [ ] Klik "Lihat Bukti" → file terbuka di tab baru

- [ ] **Bukti Pembayaran:**
  - [ ] Klik area upload → file picker terbuka
  - [ ] Pilih file (PDF/JPG/PNG) → nama file muncul (emerald)
  - [ ] Tombol "Upload Bukti Pembayaran" muncul
  - [ ] Klik upload → loading indicator muncul
  - [ ] Setelah upload → tombol "Lihat Bukti" muncul (emerald)
  - [ ] Klik "Lihat Bukti" → file terbuka di tab baru
  - [ ] Jika disetujui → tombol "Download Invoice" muncul

### Tab Seragam:

- [ ] **Bukti Pembayaran:**
  - [ ] Klik area upload → file picker terbuka
  - [ ] Pilih file (PDF/JPG/PNG) → nama file muncul (blue)
  - [ ] Tombol "Upload Bukti Pembayaran" muncul
  - [ ] Klik upload → loading indicator muncul
  - [ ] Setelah upload → tombol "Lihat Bukti" muncul (biru)
  - [ ] Klik "Lihat Bukti" → file terbuka di tab baru
  - [ ] Jika disetujui → tombol "Download Invoice" muncul

### Validations:

- [ ] Upload tanpa pilih file → error validation muncul
- [ ] Upload file selain format yang diperbolehkan → error muncul
- [ ] Upload file melebihi ukuran maksimal → error muncul
- [ ] Upload saat status "disetujui" → error "tidak dapat diubah"
- [ ] Upload saat payment period closed → error "waktu berakhir"

---

## ⚠️ CATATAN PENTING

1. **Sequential Upload (Tab Madrasah):**
   - Surat Pernyataan HARUS diupload terlebih dahulu
   - Bukti Pembayaran baru bisa diupload setelah Surat Pernyataan ada
   - Validasi ada di controller method `uploadBuktiPembayaran()`

2. **File Storage:**
   - Semua file disimpan di `private` storage
   - Filename menggunakan sistem naming yang konsisten
   - Old file dihapus saat upload file baru

3. **Payment Period:**
   - Upload terkunci jika melewati batas waktu pembayaran
   - Check dilakukan di controller sebelum proses upload

4. **Status Approved:**
   - Upload tidak bisa dilakukan jika status sudah "disetujui"
   - Check ada di semua upload method

---

## 📊 SUMMARY

### Total Changes:
- **1 View file** modified
- **1 Controller file** modified
- **3 New methods** added
- **3 Upload sections** redesigned

### UI Improvements:
- ✅ Tombol upload terpisah (lebih jelas)
- ✅ Loading indicator dengan spinner animation
- ✅ Tombol "Lihat Bukti" yang prominent dan mudah diklik
- ✅ File name preview dengan color highlight
- ✅ Better user feedback

### Security:
- ✅ Validasi file type dan size
- ✅ Check payment period
- ✅ Check approval status
- ✅ Sequential upload validation (Madrasah)
- ✅ Private storage untuk dokumen

---

**Dibuat:** 5 Februari 2026
**Developer:** AI Assistant
**Status:** Ready for Production
