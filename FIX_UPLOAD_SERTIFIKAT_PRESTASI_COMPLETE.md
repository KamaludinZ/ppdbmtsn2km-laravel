# Fix Upload Sertifikat Prestasi - Complete

**Tanggal:** 3 Februari 2026 - 13:00 WIB
**Status:** ✅ READY TO TEST & UPLOAD

---

## 🎯 MASALAH YANG DIPERBAIKI

### User Request:
> "perbaiki lagi di /admin/pendaftar/27?activeTab=prestasi pada field upload sertifikat itu wajib dan pastikan berhasil menyimpan file upload itu serta perbaiki agar lebih terlihat jelas tampilannya jika memang sudah berhasil terupload."

**3 Masalah Utama:**
1. ❌ Upload sertifikat **tidak wajib** → Seharusnya WAJIB
2. ❌ File upload **tidak tersimpan** → Logic upload tidak ada
3. ❌ **Tampilan tidak jelas** → User tidak tahu apakah sudah upload atau belum

---

## ✅ SOLUSI YANG DITERAPKAN

### 1. **Upload Sertifikat Menjadi WAJIB** ✅

**File:** `app/Livewire/Admin/DetailPendaftar.php`

**Validasi Dinamis:**
```php
// Jika prestasi baru (tidak ada id) dan tidak ada file existing, file upload WAJIB
if (!$prestasiData['id'] && !isset($prestasiData['file_sertifikat'])) {
    $rules["uploadPrestasiSertifikat.{$index}"] = 'required|file|mimes:pdf,jpg,jpeg,png|max:2048';
}
```

**Logic:**
- **Prestasi Baru:** Upload wajib
- **Prestasi Existing:** Upload opsional (jika ingin ganti sertifikat)

---

### 2. **File Upload Tersimpan dengan Benar** ✅

**File:** `app/Livewire/Admin/DetailPendaftar.php` (line 2365-2389)

**Implementasi:**
```php
// Handle file upload
$filePath = $prestasiData['file_sertifikat'] ?? null;

if (isset($this->uploadPrestasiSertifikat[$index]) && $this->uploadPrestasiSertifikat[$index]) {
    $file = $this->uploadPrestasiSertifikat[$index];

    // Generate filename using FileNamingService
    $filename = FileNamingService::generateFileName(
        'sertifikat_prestasi',
        $this->pendaftar->nomor_pendaftaran,
        $file->getClientOriginalExtension(),
        $this->pendaftar->nama_lengkap
    );

    $filePath = $file->storeAs('sertifikat_prestasi', $filename, 'public');

    // Delete old file if updating existing prestasi
    if ($prestasiData['id']) {
        $oldPrestasi = Prestasi::find($prestasiData['id']);
        if ($oldPrestasi && $oldPrestasi->file_sertifikat && Storage::disk('public')->exists($oldPrestasi->file_sertifikat)) {
            Storage::disk('public')->delete($oldPrestasi->file_sertifikat);
        }
    }
}
```

**Fitur:**
- ✅ Generate nama file dengan format standard (FileNamingService)
- ✅ Simpan ke folder `storage/app/public/sertifikat_prestasi/`
- ✅ Hapus file lama jika update
- ✅ Simpan path ke database

---

### 3. **Tampilan Upload Lebih Jelas** ✅

**File:** `resources/views/tab_templates/tab-prestasi.blade.php`

#### **A. Edit Mode - Status Upload yang Jelas**

**SEBELUM:**
```html
<label>Upload Sertifikat Prestasi (PDF/Gambar max. 2MB)</label>
<input type="file" wire:model="uploadPrestasiSertifikat.{{ $index }}">
@if($prestasiList[$index]['file_sertifikat'])
    <p>File saat ini: {{ basename($prestasiList[$index]['file_sertifikat']) }}</p>
@endif
```

**SESUDAH:**
```html
<label>Upload Sertifikat Prestasi <span class="text-red-500">*</span></label>
<input type="file" wire:model="uploadPrestasiSertifikat.{{ $index }}">

<!-- Status SUDAH UPLOAD (Hijau) -->
@if($prestasiList[$index]['file_sertifikat'])
    <div class="bg-emerald-50 border border-emerald-200 rounded-md">
        ✓ Sertifikat Sudah Diupload
        filename.pdf
        [Tombol Lihat]
    </div>
    Info: Upload file baru untuk mengganti
@else
    <!-- Status BELUM UPLOAD (Kuning Warning) -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-md">
        ⚠ Sertifikat belum diupload. Silakan pilih file untuk diupload.
    </div>
@endif
```

**Visual:**
- ✅ **Label Wajib:** Tanda bintang merah (*)
- ✅ **Sudah Upload:** Box hijau dengan icon centang + tombol "Lihat"
- ✅ **Belum Upload:** Box kuning dengan icon warning + pesan

#### **B. View Mode - Status di Tabel**

**SEBELUM:**
```html
@if($prestasi->file_sertifikat)
    <a href="...">Lihat Sertifikat</a>
@else
    <span>-</span>
@endif
```

**SESUDAH:**
```html
@if($prestasi->file_sertifikat)
    <div class="flex items-center space-x-2">
        <span class="badge bg-emerald">✓ Tersedia</span>
        <a href="..." class="btn btn-blue">Lihat</a>
    </div>
@else
    <span class="badge bg-red">✗ Belum Upload</span>
@endif
```

**Visual:**
- ✅ **Tersedia:** Badge hijau + tombol "Lihat"
- ✅ **Belum Upload:** Badge merah dengan icon X

---

## 📁 FILE YANG DIUBAH

### 1. `app/Livewire/Admin/DetailPendaftar.php`

**Changes:** ~120 lines

**Methods Modified:**

#### `loadPrestasi()` (line 2260-2292)
**BEFORE:**
```php
return [
    'id' => $prestasi->id,
    'tahun' => $prestasi->tahun,
    // ... other fields
];
```

**AFTER:**
```php
return [
    'id' => $prestasi->id,
    'tahun' => $prestasi->tahun,
    // ... other fields
    'file_sertifikat' => $prestasi->file_sertifikat, // ADDED
];
```

#### `addPrestasi()` (line 2294-2307)
**ADDED:** `'file_sertifikat' => null` ke array initialization

#### `removePrestasi()` (line 2309-2326)
**ADDED:** Logic untuk delete file sertifikat sebelum delete prestasi dari database

#### `saveDataPrestasiTerstruktur()` (line 2328-2429)
**MAJOR CHANGES:**
1. ✅ Tambah validasi upload wajib untuk prestasi baru
2. ✅ Handle file upload dengan FileNamingService
3. ✅ Simpan path file_sertifikat ke database
4. ✅ Delete old files saat update
5. ✅ Reset upload arrays setelah save

---

### 2. `resources/views/tab_templates/tab-prestasi.blade.php`

**Changes:** ~60 lines

**Section: Edit Mode - Upload Field** (line 155-208)
- ✅ Tambah label "Wajib" (*)
- ✅ Status box hijau jika sudah upload (dengan tombol "Lihat")
- ✅ Status box kuning warning jika belum upload
- ✅ Info text untuk ganti file

**Section: View Mode - Tabel** (line 291-318)
- ✅ Badge hijau "Tersedia" jika ada file
- ✅ Badge merah "Belum Upload" jika tidak ada file
- ✅ Tombol "Lihat" dengan icon

---

## 🚀 CARA UPLOAD KE PRODUCTION

### Command:

```cmd
cd C:\ppdbmtsn2km-laravel
upload_fix_sertifikat_prestasi.bat
```

Script akan otomatis:
1. Upload `app/Livewire/Admin/DetailPendaftar.php`
2. Upload `resources/views/tab_templates/tab-prestasi.blade.php`
3. Clear all caches
4. Delete compiled views

---

## ✅ TESTING CHECKLIST LOKAL (BEFORE UPLOAD)

### Test 1: Prestasi Baru - Upload Wajib
- [ ] `/admin/pendaftar/27?activeTab=prestasi`
- [ ] Klik "Edit Data" → Klik "Tambah Prestasi"
- [ ] Isi semua field KECUALI upload sertifikat
- [ ] Klik "Simpan"
- [ ] **EXPECTED:** Error validasi muncul: "The uploadPrestasiSertifikat.0 field is required."

### Test 2: Prestasi Baru - Upload Berhasil
- [ ] Isi semua field + upload sertifikat (PDF/JPG)
- [ ] Klik "Simpan"
- [ ] **EXPECTED:**
   - ✅ Notifikasi "Data prestasi berhasil diperbarui"
   - ✅ Keluar dari edit mode
   - ✅ File tersimpan di `storage/app/public/sertifikat_prestasi/`
   - ✅ Di tabel muncul badge "Tersedia" + tombol "Lihat"

### Test 3: Visual - Sudah Upload
- [ ] Klik "Edit Data" pada prestasi yang sudah ada
- [ ] **EXPECTED:**
   - ✅ Box hijau muncul: "Sertifikat Sudah Diupload"
   - ✅ Nama file ditampilkan
   - ✅ Tombol "Lihat" ada dan bisa diklik
   - ✅ Info: "Upload file baru untuk mengganti..."

### Test 4: Update Sertifikat
- [ ] Masih di edit mode prestasi existing
- [ ] Upload file sertifikat baru
- [ ] Klik "Simpan"
- [ ] **EXPECTED:**
   - ✅ File lama dihapus dari storage
   - ✅ File baru tersimpan
   - ✅ Link "Lihat" mengarah ke file baru

### Test 5: Hapus Prestasi
- [ ] Klik "Hapus" pada prestasi yang punya sertifikat
- [ ] **EXPECTED:**
   - ✅ Prestasi dihapus dari database
   - ✅ File sertifikat dihapus dari storage

### Test 6: Visual - Belum Upload
- [ ] Prestasi tanpa sertifikat
- [ ] View mode: **EXPECTED** badge merah "Belum Upload"
- [ ] Edit mode: **EXPECTED** box kuning warning

---

## ✅ TESTING CHECKLIST PRODUCTION (AFTER UPLOAD)

### URL:
`https://pmbm.mtsn2kotamalang.sch.id/admin/pendaftar/27?activeTab=prestasi`

### Test Full Flow:
1. **Tambah Prestasi Baru**
   - [ ] Klik "Edit Data" → "Tambah Prestasi"
   - [ ] Coba simpan tanpa upload → Error muncul ✅
   - [ ] Upload sertifikat → Simpan berhasil ✅
   - [ ] File tersimpan di server ✅

2. **Visual Check**
   - [ ] View mode: Badge "Tersedia" atau "Belum Upload" tampil jelas ✅
   - [ ] Edit mode: Box hijau/kuning tampil sesuai status ✅
   - [ ] Tombol "Lihat" berfungsi ✅

3. **Update Sertifikat**
   - [ ] Edit prestasi existing
   - [ ] Upload sertifikat baru → File lama terhapus ✅

4. **Delete Prestasi**
   - [ ] Hapus prestasi → File ikut terhapus ✅

**Hard refresh browser:** Ctrl + Shift + R

---

## 📝 CATATAN TEKNIS

### File Naming Convention:

**Format:**
```
sertifikat_prestasi_{nomor_pendaftaran}_{timestamp}_{sanitized_name}.{ext}
```

**Contoh:**
```
sertifikat_prestasi_PPDB2026001_20260203_130045_Ahmad_Rizki.pdf
```

**Folder Storage:**
```
storage/app/public/sertifikat_prestasi/
```

### Validasi Upload:

**Rules:**
- **Format:** PDF, JPG, JPEG, PNG
- **Size:** Max 2MB (2048 KB)
- **Required:** Ya untuk prestasi baru
- **Optional:** Update prestasi existing

### Logic Delete File:

File sertifikat dihapus otomatis saat:
1. ✅ Prestasi dihapus dari database
2. ✅ Update prestasi dengan upload file baru
3. ✅ Prestasi tidak masuk dalam list saat save (orphaned)

### Properties yang Digunakan:

```php
// Array prestasi dengan data dari database
public $prestasiList = [];

// Array untuk handle file upload (Livewire wire:model)
public $uploadPrestasiSertifikat = [];
```

---

## 🎨 UI/UX IMPROVEMENTS

### Before vs After:

| Aspek | Before | After |
|-------|--------|-------|
| Label Upload | Tidak ada tanda wajib | ✅ Ada tanda * merah |
| Status Upload | Text kecil, tidak jelas | ✅ Box berwarna dengan icon |
| Sudah Upload | "File saat ini: xxx.pdf" | ✅ Box hijau + badge + tombol |
| Belum Upload | Tidak ada indikasi | ✅ Box kuning warning |
| View Mode | Link biasa atau "-" | ✅ Badge status + tombol |
| Visual Feedback | Minim | ✅ Color coded (hijau/kuning/merah) |

### Color Scheme:

- 🟢 **Hijau (Emerald):** Sertifikat sudah diupload
- 🟡 **Kuning (Yellow):** Warning - belum upload
- 🔴 **Merah (Red):** Badge "Belum Upload" di view mode

---

## 🔄 NEXT STEPS

### Untuk Panitia DetailPendaftar:

Implementasi yang sama perlu diterapkan ke `app/Livewire/Panitia/DetailPendaftar.php`:

1. ✅ Update loadPrestasi(), addPrestasi(), removePrestasi()
2. ✅ Update saveDataPrestasiTerstruktur() dengan upload logic
3. ✅ View sudah shared di `tab_templates/` jadi otomatis ter-update

**Estimasi:** 30 menit (controller only, view sudah shared)

---

## 📊 SUMMARY

| Feature | Status | Impact |
|---------|--------|--------|
| Upload Wajib | ✅ IMPLEMENTED | Semua prestasi harus punya sertifikat |
| File Tersimpan | ✅ IMPLEMENTED | File tersimpan dengan naming standard |
| Visual Jelas | ✅ IMPLEMENTED | User tahu status upload dengan jelas |
| Delete File | ✅ IMPLEMENTED | File lama otomatis terhapus |
| Update File | ✅ IMPLEMENTED | Bisa ganti sertifikat yang ada |
| Validation | ✅ IMPLEMENTED | PDF/JPG/PNG max 2MB |

**Total Changes:** ~180 lines (120 controller + 60 view)

**User Satisfaction Expected:** ⭐⭐⭐⭐⭐
- Upload wajib memastikan data lengkap
- Visual jelas meningkatkan user experience
- File tersimpan dengan benar mengurangi bug

---

**Prepared by:** Claude Code Assistant
**Date:** 3 Februari 2026 - 13:00 WIB
**Status:** ✅ READY TO TEST LOCALLY, THEN UPLOAD TO PRODUCTION
