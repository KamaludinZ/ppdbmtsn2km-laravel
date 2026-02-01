# Menambahkan Tab Keahlian dan Tahfidz ke Admin & Panitia Detail Pendaftar

## 🎯 Tujuan
Menambahkan tab **Keahlian** dan **Tahfidz** ke halaman detail pendaftar di:
- `/admin/pendaftar/{id}` (Admin)
- `/panitia-pendaftaran/pendaftar/{id}` (Panitia)

Sebelumnya tab ini hanya ada di `/pendaftar/formulir` (halaman pendaftar).

## ✅ Yang Sudah Dikerjakan

### 1. Update Admin/DetailPendaftar.php

#### **File**: `app/Livewire/Admin/DetailPendaftar.php`

**A. Tambah Imports** (Line 11-18):
```php
use App\Models\Keahlian;
use App\Models\Tahfidz;
use App\Services\FileNamingService;
```

**B. Tambah Properties** (Line 346-360):
```php
// Keahlian List (for structured keahlian data)
public $keahlianList = [];

// Tahfidz Data (single record)
public $tahfidz = [
    'id' => null,
    'juz_alquran_dihafal' => null,
    'file_bukti_syahadah' => null,
    'file_bukti_tahsin' => null,
];

// Upload properties for Keahlian & Tahfidz
public $uploadKeahlianSertifikat = [];
public $uploadTahfidzSyahadah;
public $uploadTahfidzTahsin;
```

**C. Tambah Load Calls di loadDataToProperties()** (Line 764-768):
```php
// Load Keahlian Data
$this->loadKeahlian();

// Load Tahfidz Data
$this->loadTahfidz();
```

**D. Tambah Methods** (Line 2224-2480):
- `loadKeahlian()` - Load data keahlian dari database
- `addKeahlian()` - Tambah entry keahlian baru
- `removeKeahlian($index)` - Hapus keahlian
- `saveDataKeahlian()` - Simpan data keahlian (create/update)
- `loadTahfidz()` - Load data tahfidz dari database
- `saveDataTahfidz()` - Simpan data tahfidz (create/update)

---

### 2. Update Admin detail-pendaftar.blade.php

#### **File**: `resources/views/livewire/admin/detail-pendaftar.blade.php`

**A. Tambah Tab Navigation Buttons** (Line 162-169):
```blade
<button @click="activeTab = 'keahlian'" ...>
    <svg class="w-5 h-5 inline mr-2" ...>
        <!-- Icon lightbulb/keahlian -->
    </svg>
    Keahlian
</button>
<button @click="activeTab = 'tahfidz'" ...>
    <svg class="w-5 h-5 inline mr-2" ...>
        <!-- Icon book/tahfidz -->
    </svg>
    Tahfidz
</button>
```

**B. Tambah Tab Content** (Line 217-225):
```blade
<!-- Tab Keahlian -->
<div x-show="activeTab === 'keahlian'" x-transition>
    @include('tab_templates.tab-keahlian')
</div>

<!-- Tab Tahfidz -->
<div x-show="activeTab === 'tahfidz'" x-transition>
    @include('tab_templates.tab-tahfidz')
</div>
```

---

### 3. Update Panitia/DetailPendaftar.php

#### **File**: `app/Livewire/Panitia/DetailPendaftar.php`

**A. Tambah Imports** (Line 16-19):
```php
use App\Models\Keahlian;
use App\Models\Tahfidz;
use App\Services\FileNamingService;
```

**B. Tambah Properties** (Line 112-126):
```php
// Keahlian List (for structured keahlian data)
public $keahlianList = [];

// Tahfidz Data (single record)
public $tahfidz = [
    'id' => null,
    'juz_alquran_dihafal' => null,
    'file_bukti_syahadah' => null,
    'file_bukti_tahsin' => null,
];

// Upload properties for Keahlian & Tahfidz
public $uploadKeahlianSertifikat = [];
public $uploadTahfidzSyahadah;
public $uploadTahfidzTahsin;
```

**C. Tambah Load Calls di loadDataToProperties()** (Line 280-284):
```php
// Load Keahlian Data
$this->loadKeahlian();

// Load Tahfidz Data
$this->loadTahfidz();
```

**D. Tambah Methods** (Line 574-830):
- `loadKeahlian()` - Load data keahlian dari database
- `addKeahlian()` - Tambah entry keahlian baru
- `removeKeahlian($index)` - Hapus keahlian
- `saveDataKeahlian()` - Simpan data keahlian (create/update)
- `loadTahfidz()` - Load data tahfidz dari database
- `saveDataTahfidz()` - Simpan data tahfidz (create/update)

---

### 4. Update Panitia detail-pendaftar.blade.php

#### **File**: `resources/views/livewire/panitia/detail-pendaftar.blade.php`

**A. Tambah Tab Navigation Buttons** (Line 162-169):
```blade
<button @click="activeTab = 'keahlian'" ...>
    <svg class="w-5 h-5 inline mr-2" ...>
        <!-- Icon lightbulb/keahlian -->
    </svg>
    Keahlian
</button>
<button @click="activeTab = 'tahfidz'" ...>
    <svg class="w-5 h-5 inline mr-2" ...>
        <!-- Icon book/tahfidz -->
    </svg>
    Tahfidz
</button>
```

**B. Tambah Tab Content** (Line 217-225):
```blade
<!-- Tab Keahlian -->
<div x-show="activeTab === 'keahlian'" x-transition>
    @include('tab_templates.tab-keahlian')
</div>

<!-- Tab Tahfidz -->
<div x-show="activeTab === 'tahfidz'" x-transition>
    @include('tab_templates.tab-tahfidz')
</div>
```

---

## 🎨 Tampilan Setelah Update

### Tab Navigation (Admin & Panitia)
```
┌────────────────────────────────────────────────────────────────────────┐
│  [Data Siswa] [Orangtua] [Alamat] [Prestasi] [Beasiswa] [Pendidikan]  │
│  [Keahlian] [Tahfidz] [Dokumen] [Daftar Ulang] [Survey]               │
└────────────────────────────────────────────────────────────────────────┘
```

### Tab Keahlian
- Mode View: Menampilkan list keahlian yang sudah diinput
- Mode Edit: Form untuk input/edit keahlian dengan upload sertifikat
- Tombol: Edit Data, Simpan, Batal, Tambah Keahlian, Hapus

### Tab Tahfidz
- Mode View: Menampilkan juz yang dihafal dan link download bukti
- Mode Edit: Form untuk input/edit data tahfidz dengan upload bukti syahadah dan tahsin
- Tombol: Edit Data, Simpan, Batal

---

## 🔧 Technical Details

### Reusable Templates
Tab menggunakan template yang sudah ada:
- `resources/views/tab_templates/tab-keahlian.blade.php`
- `resources/views/tab_templates/tab-tahfidz.blade.php`

Template ini digunakan di 3 lokasi:
1. `/pendaftar/formulir` (Pendaftar)
2. `/admin/pendaftar/{id}` (Admin)
3. `/panitia-pendaftaran/pendaftar/{id}` (Panitia)

### Edit Mode Support
Template mendukung 2 mode:
```php
// Pendaftar: per-tab edit mode
$isEditMode = $editModeDataKeahlian ?? false;

// Admin/Panitia: global edit mode
$isEditMode = $editMode ?? false;
```

### File Upload Handling
```php
// Keahlian
$filename = FileNamingService::generateFileName(
    'sertifikat_keahlian',
    $this->pendaftar->nomor_pendaftaran,
    $file->getClientOriginalExtension()
);
$filePath = $file->storeAs('sertifikat_keahlian', $filename, 'public');

// Tahfidz Syahadah
$filename = FileNamingService::generateFileName(
    'syahadah',
    $this->pendaftar->nomor_pendaftaran,
    $this->uploadTahfidzSyahadah->getClientOriginalExtension()
);
$syahadahPath = $this->uploadTahfidzSyahadah->storeAs('tahfidz', $filename, 'public');

// Tahfidz Tahsin
$filename = FileNamingService::generateFileName(
    'tahsin',
    $this->pendaftar->nomor_pendaftaran,
    $this->uploadTahfidzTahsin->getClientOriginalExtension()
);
$tahsinPath = $this->uploadTahfidzTahsin->storeAs('tahfidz', $filename, 'public');
```

### Delete Old Files
Saat update file baru, file lama otomatis dihapus:
```php
if ($oldKeahlian && $oldKeahlian->file_bukti_sertifikat &&
    Storage::disk('public')->exists($oldKeahlian->file_bukti_sertifikat)) {
    Storage::disk('public')->delete($oldKeahlian->file_bukti_sertifikat);
}
```

---

## 🧪 Testing Checklist

### Admin (http://localhost:8000/admin/pendaftar/29)
- [ ] Login sebagai admin
- [ ] Buka detail pendaftar
- [ ] Verify: Tab "Keahlian" dan "Tahfidz" muncul di navigation
- [ ] Klik tab "Keahlian"
- [ ] Verify: Tampil data keahlian (atau pesan "belum ada data")
- [ ] Klik "Edit Data"
- [ ] Input data keahlian
- [ ] Upload sertifikat
- [ ] Klik "Simpan"
- [ ] Verify: Data tersimpan dan file ter-upload
- [ ] Klik tab "Tahfidz"
- [ ] Verify: Tampil data tahfidz (atau pesan "belum ada data")
- [ ] Klik "Edit Data"
- [ ] Input juz yang dihafal
- [ ] Upload bukti syahadah dan tahsin
- [ ] Klik "Simpan"
- [ ] Verify: Data tersimpan dan file ter-upload

### Panitia (http://localhost:8000/panitia-pendaftaran/pendaftar/29)
- [ ] Login sebagai panitia_pendaftaran
- [ ] Buka detail pendaftar
- [ ] Verify: Tab "Keahlian" dan "Tahfidz" muncul di navigation
- [ ] Test sama seperti Admin di atas

---

## 📊 Database Impact

Tidak ada perubahan schema database. Menggunakan tabel yang sudah ada:
- `keahlians` (relasi hasMany ke pendaftar)
- `tahfidz` (relasi hasOne ke pendaftar)

---

## 🚀 Next Steps

1. **Testing** ✅
   - Test di browser untuk memastikan tab muncul
   - Test CRUD functionality (Create, Read, Update, Delete)
   - Test file upload functionality

2. **Export Data** (Belum)
   - Update `PendaftarExport.php` untuk include data keahlian dan tahfidz
   - Update view laporan dengan keterangan kolom baru

3. **Documentation** (Belum)
   - Update user manual/guide jika ada
   - Update changelog

---

## 📝 Files Modified Summary

### Backend (Livewire Components)
1. ✅ `app/Livewire/Admin/DetailPendaftar.php`
2. ✅ `app/Livewire/Panitia/DetailPendaftar.php`

### Frontend (Blade Views)
3. ✅ `resources/views/livewire/admin/detail-pendaftar.blade.php`
4. ✅ `resources/views/livewire/panitia/detail-pendaftar.blade.php`

### Existing Templates (Reused)
- `resources/views/tab_templates/tab-keahlian.blade.php` (sudah ada)
- `resources/views/tab_templates/tab-tahfidz.blade.php` (sudah ada)

---

**Tanggal**: 31 Januari 2026
**Status**: ✅ Complete
**Impact**: Tab Keahlian dan Tahfidz sekarang dapat diakses dan diedit dari halaman Admin dan Panitia
