# File Upload Improvements Summary - PPDB MTsN 2 Kota Malang

**Tanggal**: 19 Januari 2026
**Status**: ✅ **COMPLETED**

---

## 🎯 Tujuan Implementasi

Implementasi penamaan file yang sistematis untuk memudahkan:
- ✅ Pencarian file saat backup
- ✅ Identifikasi file berdasarkan nomor pendaftaran dan waktu upload
- ✅ Organisasi file yang terstruktur di storage
- ✅ Security dengan pemisahan private dan public storage

---

## 📝 Format Penamaan File

### Format Baru:
```
{jenis_dokumen}_{nomor_pendaftaran}_{timestamp}.{ext}
```

**Contoh**:
```
rapor_PPDB2024001_20260119_143052.pdf
foto_profil_PPDB2024001_20260119_143125.webp
bukti_daftar_ulang_PPDB2024001_20260119_150000.pdf
```

### Sebelumnya:
```
random_hash.pdf
1234567890.webp
random_string_12345.jpg
```

**Masalah**:
- ❌ Sulit identifikasi file milik siapa
- ❌ Tidak ada timestamp
- ❌ Tidak ada keterangan jenis dokumen

---

## 🔧 Files Created/Modified

### 1. **NEW: FileNamingService.php** ✅
**File**: `app/Services/FileNamingService.php`

**Purpose**: Service untuk generate nama file sistematis

**Key Methods**:
- `generateFileName()` - Generate nama file umum
- `generateFilePath()` - Generate path lengkap
- `generateImageFileName()` - Generate nama file gambar
- `generateBuktiBayarFileName()` - Generate nama bukti bayar
- `getFileExtension()` - Extract extension dari uploaded file
- `cleanJenisDokumen()` - Clean dan mapping jenis dokumen

**Example**:
```php
$filename = FileNamingService::generateFileName(
    'rapor',
    'PPDB2024001',
    'pdf'
);
// Output: rapor_PPDB2024001_20260119_143052.pdf
```

---

### 2. **UPDATED: Dokumen.php** ✅
**File**: `app/Livewire/Pendaftar/Dokumen.php`

**Changes**:
- ✅ Added `use App\Services\FileNamingService`
- ✅ Updated `processUpload()` method
- ✅ Changed storage from default `store()` to `storeAs()` with custom filename
- ✅ Ensured private storage for all documents

**Before**:
```php
$path = $file->store('dokumen/' . $this->pendaftar->id);
```

**After**:
```php
$extension = FileNamingService::getFileExtension($file);
$jenisClean = FileNamingService::cleanJenisDokumen($type);

$customFileName = FileNamingService::generateFileName(
    $jenisClean,
    $this->pendaftar->nomor_pendaftaran,
    $extension
);

$path = $file->storeAs(
    'dokumen/' . $this->pendaftar->id,
    $customFileName,
    'private'
);
```

**Affected Documents**:
- Rapor
- Akta Kelahiran
- Kartu Keluarga
- NISN
- Ijazah
- Sertifikat/Prestasi
- KIP, PKH, KKS

---

### 3. **UPDATED: Formulir.php** ✅
**File**: `app/Livewire/Pendaftar/Formulir.php`

**Changes**:
- ✅ Added `use App\Services\FileNamingService`
- ✅ Updated foto profil upload to use systematic naming in `saveDataSiswa()` method
- ✅ Foto profil remains in public storage (already optimized and safe for display)

**Note**: ~~Upload dokumen KK, Akta, Ijazah SD, dan SKHUN SD sebelumnya ada di Formulir.php namun sudah dihapus~~ karena semua upload dokumen seharusnya hanya ada di menu **Upload Dokumen** (`Dokumen.php`).

**Before (Foto Profil)**:
```php
$fotoPath = $imageService->uploadProfilePhoto($this->newFoto, 'photos', 500, 80);
```

**After (Foto Profil)**:
```php
$fotoPath = $imageService->uploadProfilePhoto(
    $this->newFoto,
    'photos',
    500,
    80,
    $this->pendaftar->nomor_pendaftaran,
    'foto_profil'
);
```

**Affected Files in Formulir.php**:
- ✅ Foto Profil only (di Tab Data Siswa - Identitas Dasar)

**Document Uploads Location**:
- ❌ KK, Akta, Ijazah SD, SKHUN SD: **Moved to Dokumen.php** (Upload Dokumen menu)
- ✅ All document uploads now centralized in `/pendaftar/dokumen`

---

### 4. **UPDATED: ImageService.php** ✅
**File**: `app/Services/ImageService.php`

**Changes**:
- ✅ Added `use App\Services\FileNamingService`
- ✅ Updated `uploadProfilePhoto()` method to accept optional parameters
- ✅ Added `$nomorPendaftaran` parameter
- ✅ Added `$jenisFoto` parameter
- ✅ Use systematic naming when nomor pendaftaran provided

**Before**:
```php
public function uploadProfilePhoto(
    UploadedFile $file,
    string $directory = 'photos',
    int $size = 500,
    int $quality = 80
): string {
    $filename = time() . '_' . uniqid() . '.webp';
    // ...
}
```

**After**:
```php
public function uploadProfilePhoto(
    UploadedFile $file,
    string $directory = 'photos',
    int $size = 500,
    int $quality = 80,
    ?string $nomorPendaftaran = null,
    ?string $jenisFoto = 'foto_profil'
): string {
    if ($nomorPendaftaran) {
        $filename = FileNamingService::generateImageFileName(
            $jenisFoto,
            $nomorPendaftaran,
            'webp'
        );
    } else {
        $filename = time() . '_' . uniqid() . '.webp';
    }
    // ...
}
```

---

### 5. **UPDATED: DaftarUlang.php** ✅
**File**: `app/Livewire/Pendaftar/DaftarUlang.php`

**Changes**:
- ✅ Added `use App\Services\FileNamingService`
- ✅ Updated `saveMadrasah()` method
- ✅ **SECURITY FIX**: Changed bukti pembayaran from 'public' to 'private' storage
- ✅ **SECURITY FIX**: Changed surat pernyataan from 'public' to 'private' storage
- ✅ Systematic file naming for both files

**Before**:
```php
$path = $this->buktiPembayaran->store('bukti-bayar-madrasah', 'public');
$path = $this->suratPernyataan->store('surat-pernyataan', 'public');
```

**After**:
```php
$extension = FileNamingService::getFileExtension($this->buktiPembayaran);
$customFileName = FileNamingService::generateBuktiBayarFileName(
    'daftar_ulang',
    $this->pendaftar->nomor_pendaftaran,
    $extension
);

$path = $this->buktiPembayaran->storeAs(
    'bukti_bayar/' . $this->pendaftar->id,
    $customFileName,
    'private'
);
```

**Affected Files**:
- Bukti Pembayaran Daftar Ulang Madrasah
- Surat Pernyataan

---

### 6. **UPDATED: TesPsikologi.php** ✅
**File**: `app/Livewire/Pendaftar/TesPsikologi.php`

**Changes**:
- ✅ Added `use App\Services\FileNamingService`
- ✅ Updated `uploadBuktiTransfer()` method
- ✅ Updated `hapusBukti()` method
- ✅ **SECURITY FIX**: Changed from 'public' to 'private' storage
- ✅ Systematic file naming

**Before**:
```php
$path = $this->convertAndStoreAsWebp($this->buktiTransfer, 'tes-psikologi');
Storage::delete('public/' . $path);
```

**After**:
```php
$extension = FileNamingService::getFileExtension($this->buktiTransfer);
$customFileName = FileNamingService::generateBuktiBayarFileName(
    'psikologi',
    $this->pendaftar->nomor_pendaftaran,
    $extension
);

$path = $this->buktiTransfer->storeAs(
    'bukti_bayar/' . $this->pendaftar->id,
    $customFileName,
    'private'
);

Storage::disk('private')->delete($path);
```

**Affected Files**:
- Bukti Transfer Tes Psikologi

---

## 🔐 Security Improvements

### Private Storage Migration

**SEBELUM**: Beberapa file sensitif disimpan di 'public' storage
```
storage/app/public/
├── dokumen/kk/random_file.pdf          ❌ PUBLICLY ACCESSIBLE
├── dokumen/akta/random_file2.pdf       ❌ PUBLICLY ACCESSIBLE
├── bukti-bayar-madrasah/payment.pdf    ❌ PUBLICLY ACCESSIBLE
└── tes-psikologi/bukti.jpg             ❌ PUBLICLY ACCESSIBLE
```

**SESUDAH**: Semua file sensitif di 'private' storage
```
storage/app/private/
├── dokumen/{pendaftar_id}/
│   ├── kartu_keluarga_PPDB2024001_20260119_143052.pdf    ✅ PRIVATE
│   └── akta_kelahiran_PPDB2024001_20260119_143109.pdf    ✅ PRIVATE
│
└── bukti_bayar/{pendaftar_id}/
    ├── bukti_daftar_ulang_PPDB2024001_20260119_150000.pdf ✅ PRIVATE
    └── bukti_psikologi_PPDB2024001_20260119_150100.jpg    ✅ PRIVATE
```

**Impact**:
- ✅ File tidak bisa diakses langsung via URL
- ✅ Harus melalui `SecureFileController` dengan authorization check
- ✅ Pendaftar hanya bisa download file sendiri
- ✅ Admin/Panitia bisa download semua file

---

## 📊 Summary of Changes

| Component | File | Status | Changes |
|-----------|------|--------|---------|
| **Service** | FileNamingService.php | ✅ Created | New service for systematic file naming |
| **Dokumen Upload** | Dokumen.php | ✅ Updated | Systematic naming + private storage (all docs) |
| **Formulir Upload** | Formulir.php | ✅ Updated | Systematic naming for foto profil only |
| **Image Service** | ImageService.php | ✅ Updated | Support systematic naming for photos |
| **Daftar Ulang** | DaftarUlang.php | ✅ Updated | Systematic naming + private storage |
| **Tes Psikologi** | TesPsikologi.php | ✅ Updated | Systematic naming + private storage |
| **Documentation** | FILE_NAMING_CONVENTION.md | ✅ Created | Complete documentation |

---

## 📂 Storage Structure

### Before:
```
storage/app/
├── public/
│   ├── dokumen/kk/random1.pdf
│   ├── dokumen/akta/random2.pdf
│   ├── bukti-bayar-madrasah/random3.pdf
│   └── photos/12345.webp
└── private/
    └── dokumen/1/random4.pdf
```

### After:
```
storage/app/
├── public/
│   └── photos/
│       └── foto_profil_PPDB2024001_20260119_143125.webp
│
└── private/
    ├── dokumen/{pendaftar_id}/
    │   ├── rapor_PPDB2024001_20260119_143052.pdf
    │   ├── akta_kelahiran_PPDB2024001_20260119_143109.pdf
    │   ├── kartu_keluarga_PPDB2024001_20260119_143125.pdf
    │   ├── nisn_PPDB2024001_20260119_143140.pdf
    │   ├── ijazah_sd_PPDB2024001_20260119_143155.pdf
    │   └── skhun_sd_PPDB2024001_20260119_143210.pdf
    │
    └── bukti_bayar/{pendaftar_id}/
        ├── bukti_daftar_ulang_PPDB2024001_20260119_150000.pdf
        ├── bukti_seragam_PPDB2024001_20260119_150100.pdf
        └── bukti_psikologi_PPDB2024001_20260119_150200.jpg
```

---

## ✅ Benefits

### 1. Easy Search & Backup
```bash
# Cari semua file pendaftar PPDB2024001
find storage/app/private -name "*PPDB2024001*"

# Backup specific pendaftar
tar -czf backup_PPDB2024001.tar.gz \
  storage/app/private/dokumen/1/ \
  storage/app/private/bukti_bayar/1/

# Cari file yang di-upload tanggal 19 Januari 2026
find storage/app/private -name "*_20260119_*"
```

### 2. Clear File Identification
Dari nama file `rapor_PPDB2024001_20260119_143052.pdf` langsung tahu:
- Jenis dokumen: **Rapor**
- Nomor pendaftaran: **PPDB2024001**
- Tanggal upload: **19 Januari 2026**
- Waktu upload: **14:30:52 WIB**

### 3. Better Security
- ✅ Semua dokumen sensitif di private storage
- ✅ Bukti pembayaran di private storage
- ✅ Hanya foto profil di public storage
- ✅ Download harus melalui authorization check

### 4. No File Conflicts
- ✅ Timestamp ensures unique filenames
- ✅ No duplicate file names
- ✅ No overwrite issues

---

## 🧪 Testing

### Test Cases:
1. ✅ Upload dokumen baru menggunakan format nama yang benar
2. ✅ Nama file include nomor pendaftaran
3. ✅ Nama file include timestamp
4. ✅ File disimpan di private storage untuk dokumen sensitif
5. ✅ File disimpan di public storage hanya untuk foto profil
6. ✅ File lama terhapus saat upload file baru
7. ✅ Download file works via SecureFileController
8. ✅ Pendaftar tidak bisa download file orang lain
9. ✅ Admin/Panitia bisa download semua file
10. ✅ Backup file mudah dicari berdasarkan nama

### Example Test Commands:
```bash
# Test 1: Upload dokumen
# Expected: rapor_PPDB2024001_YYYYMMDD_HHMMSS.pdf

# Test 2: List files for pendaftar
find storage/app/private -name "*PPDB2024001*"

# Test 3: Verify private storage
curl https://pmbm.mtsn2kotamalang.sch.id/../storage/app/private/dokumen/1/rapor.pdf
# Expected: 403 Forbidden (not accessible)

# Test 4: Download via controller
curl https://pmbm.mtsn2kotamalang.sch.id/download/dokumen/123 -H "Cookie: session=..."
# Expected: 200 OK (with authorization)
```

---

## 📝 Migration Notes

### For Existing Files

If you have existing files with old naming convention, you can create a migration command:

```php
// app/Console/Commands/RenameOldFiles.php
php artisan files:rename-old
```

This will:
1. Find all documents with old naming
2. Generate new systematic names
3. Move files to new names
4. Update database records

---

## 📝 Update Log

### Update 20 Januari 2026
**Changes Made**:
- ❌ **REMOVED** upload KK, Akta, Ijazah SD, SKHUN SD dari `Formulir.php`
- ✅ **REASON**: Semua upload dokumen seharusnya hanya ada di menu `/pendaftar/dokumen` (Dokumen.php)
- ✅ **KEPT**: Upload foto profil tetap ada di `Formulir.php` (Tab Data Siswa - Identitas Dasar)
- ✅ Cleaned up properties: Removed `newFileKartuKeluarga`, `newFileAktaKelahiran`, `newFileIjazahSd`, `newFileSkhunSd`

**Impact**:
- Upload dokumen sekarang **terpusat** di satu tempat: `/pendaftar/dokumen`
- Upload foto profil tetap di `/pendaftar/formulir` (Tab Data Siswa)
- Konsistensi UX: Pendaftar tidak bingung lokasi upload dokumen
- Code lebih bersih dan maintainable

---

## 🎉 Conclusion

**Status**: ✅ **SUCCESSFULLY IMPLEMENTED & REFINED**

All file uploads now use systematic naming convention with proper security:
- ✅ Descriptive filenames with pendaftar info
- ✅ Timestamp for unique identification
- ✅ Private storage for sensitive documents
- ✅ Public storage only for profile photos
- ✅ Easy backup and search
- ✅ Better file organization
- ✅ Centralized document upload in Dokumen.php
- ✅ Only foto profil in Formulir.php

**Last Updated**: 20 Januari 2026, 10:30 WIB
**Version**: 1.1
**Status**: Production Ready ✅
