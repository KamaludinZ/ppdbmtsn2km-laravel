# Update File Naming Format - Include Nama Pendaftar

**Tanggal**: 31 Januari 2026
**Status**: ✅ Complete

## 🎯 Tujuan

Mengubah format penamaan file upload dari:
```
{jenis}_{nomor_pendaftaran}_{timestamp}.{ext}
```

Menjadi:
```
{jenis}-{nomor_pendaftaran}-{nama_pendaftar}-{timestamp}.{ext}
```

**Contoh**:
- **Sebelum**: `rapor_PPDB2024001_20260131_143052.pdf`
- **Setelah**: `rapor-PPDB2024001-Ahmad_Fulan-20260131_143052.pdf`

## 📝 Scope Perubahan

Semua upload file dari pendaftar sekarang menggunakan format baru yang mencakup nama pendaftar:

1. ✅ **Dokumen Persyaratan** (rapor, akta, KK, NISN, ijazah, sertifikat, KIP, dll)
2. ✅ **Foto Pendaftar** (foto profil)
3. ✅ **Bukti Tes Psikologi** (bukti transfer psikologi)
4. ✅ **Surat Pernyataan Daftar Ulang Madrasah**
5. ✅ **Bukti Daftar Ulang Madrasah** (bukti pembayaran)
6. ✅ **Bukti Daftar Ulang Seragam** (bukti pembayaran seragam)
7. ✅ **Sertifikat Prestasi**
8. ✅ **Sertifikat Keahlian**
9. ✅ **Syahadah Tahfidz**
10. ✅ **Tahsin Tahfidz**

---

## 🔧 Changes Applied

### 1. FileNamingService.php

**File**: `app/Services/FileNamingService.php`

#### A. Updated `generateFileName()` Method (Line 15-61)

**Sebelum**:
```php
public static function generateFileName(
    string $jenisFile,
    string $nomorPendaftaran,
    string $extension
): string {
    // ... logic ...
    return "{$jenisFile}_{$nomorPendaftaran}_{$timestamp}.{$extension}";
}
```

**Setelah**:
```php
public static function generateFileName(
    string $jenisFile,
    string $nomorPendaftaran,
    string $extension,
    ?string $namaPendaftar = null  // NEW PARAMETER
): string {
    // Clean nama pendaftar
    $namaPendaftarClean = '';
    if ($namaPendaftar) {
        $namaPendaftarClean = preg_replace('/\s+/', '_', trim($namaPendaftar));
        $namaPendaftarClean = preg_replace('/[^A-Za-z0-9_]/', '', $namaPendaftarClean);
        $namaPendaftarClean = substr($namaPendaftarClean, 0, 50);
    }

    // Generate timestamp
    $timestamp = now()->format('Ymd_His');

    // Format with or without name
    if ($namaPendaftarClean) {
        return "{$jenisFile}-{$nomorPendaftaran}-{$namaPendaftarClean}-{$timestamp}.{$extension}";
    }

    // Fallback to old format if nama pendaftar not provided
    return "{$jenisFile}_{$nomorPendaftaran}_{$timestamp}.{$extension}";
}
```

**Features**:
- Accepts optional 4th parameter: `$namaPendaftar`
- Cleans nama: spaces → underscores, removes special chars, max 50 chars
- Uses dash (`-`) as separator when name is provided
- Falls back to underscore (`_`) format when name not provided (backward compatible)

#### B. Updated `generateImageFileName()` Method (Line 86-106)

**Sebelum**:
```php
public static function generateImageFileName(
    string $jenisGambar,
    string $nomorPendaftaran,
    string $extension
): string {
    return self::generateFileName($jenisGambar, $nomorPendaftaran, $extension);
}
```

**Setelah**:
```php
public static function generateImageFileName(
    string $jenisGambar,
    string $nomorPendaftaran,
    string $extension,
    ?string $namaPendaftar = null  // NEW PARAMETER
): string {
    return self::generateFileName($jenisGambar, $nomorPendaftaran, $extension, $namaPendaftar);
}
```

#### C. Updated `generateBuktiBayarFileName()` Method (Line 129-150)

**Sebelum**:
```php
public static function generateBuktiBayarFileName(
    string $jenisPembayaran,
    string $nomorPendaftaran,
    string $extension
): string {
    $jenisFile = "bukti_{$jenisPembayaran}";
    return self::generateFileName($jenisFile, $nomorPendaftaran, $extension);
}
```

**Setelah**:
```php
public static function generateBuktiBayarFileName(
    string $jenisPembayaran,
    string $nomorPendaftaran,
    string $extension,
    ?string $namaPendaftar = null  // NEW PARAMETER
): string {
    $jenisFile = "bukti_{$jenisPembayaran}";
    return self::generateFileName($jenisFile, $nomorPendaftaran, $extension, $namaPendaftar);
}
```

---

### 2. ImageService.php

**File**: `app/Services/ImageService.php`

#### Updated `uploadProfilePhoto()` Method (Line 66-99)

**Sebelum**:
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
    }
    // ...
}
```

**Setelah**:
```php
public function uploadProfilePhoto(
    UploadedFile $file,
    string $directory = 'photos',
    int $size = 500,
    int $quality = 80,
    ?string $nomorPendaftaran = null,
    ?string $jenisFoto = 'foto_profil',
    ?string $namaPendaftar = null  // NEW PARAMETER
): string {
    if ($nomorPendaftaran) {
        $filename = FileNamingService::generateImageFileName(
            $jenisFoto,
            $nomorPendaftaran,
            'webp',
            $namaPendaftar  // PASSED HERE
        );
    }
    // ...
}
```

---

### 3. Pendaftar\Formulir.php

**File**: `app/Livewire/Pendaftar/Formulir.php`

Updated 5 locations:

#### A. Foto Profil Upload (Line ~938)
```php
$fotoPath = $imageService->uploadProfilePhoto(
    $this->newFoto,
    'photos',
    500,
    80,
    $this->pendaftar->nomor_pendaftaran,
    'foto_profil',
    $this->pendaftar->nama_lengkap  // ADDED
);
```

#### B. Sertifikat Prestasi Upload (Line ~1562)
```php
$filename = FileNamingService::generateFileName(
    'sertifikat_prestasi',
    $this->pendaftar->nomor_pendaftaran,
    $file->getClientOriginalExtension(),
    $this->pendaftar->nama_lengkap  // ADDED
);
```

#### C. Sertifikat Keahlian Upload (Line ~2684)
```php
$filename = FileNamingService::generateFileName(
    'sertifikat_keahlian',
    $this->pendaftar->nomor_pendaftaran,
    $file->getClientOriginalExtension(),
    $this->pendaftar->nama_lengkap  // ADDED
);
```

#### D. Syahadah Tahfidz Upload (Line ~2758)
```php
$filename = FileNamingService::generateFileName(
    'syahadah',
    $this->pendaftar->nomor_pendaftaran,
    $this->uploadTahfidzSyahadah->getClientOriginalExtension(),
    $this->pendaftar->nama_lengkap  // ADDED
);
```

#### E. Tahsin Tahfidz Upload (Line ~2778)
```php
$filename = FileNamingService::generateFileName(
    'tahsin',
    $this->pendaftar->nomor_pendaftaran,
    $this->uploadTahfidzTahsin->getClientOriginalExtension(),
    $this->pendaftar->nama_lengkap  // ADDED
);
```

---

### 4. Pendaftar\Dokumen.php

**File**: `app/Livewire/Pendaftar/Dokumen.php`

#### Dokumen Persyaratan Upload (Line ~150)
```php
$customFileName = FileNamingService::generateFileName(
    $jenisClean,
    $this->pendaftar->nomor_pendaftaran,
    $extension,
    $this->pendaftar->nama_lengkap  // ADDED
);
```

**Applies to all dokumen types**:
- Rapor
- Akta Kelahiran
- Kartu Keluarga
- NISN
- Ijazah
- SKHUN
- Sertifikat Prestasi
- KIP / PKH / KKS

---

### 5. Pendaftar\DaftarUlang.php

**File**: `app/Livewire/Pendaftar/DaftarUlang.php`

Updated 3 locations:

#### A. Bukti Pembayaran Madrasah (Line ~368)
```php
$customFileName = FileNamingService::generateBuktiBayarFileName(
    'daftar_ulang',
    $this->pendaftar->nomor_pendaftaran,
    $extension,
    $this->pendaftar->nama_lengkap  // ADDED
);
```

#### B. Surat Pernyataan (Line ~389)
```php
$customFileName = FileNamingService::generateFileName(
    'surat_pernyataan',
    $this->pendaftar->nomor_pendaftaran,
    $extension,
    $this->pendaftar->nama_lengkap  // ADDED
);
```

#### C. Bukti Pembayaran Seragam (Line ~484)
```php
$customFileName = FileNamingService::generateBuktiBayarFileName(
    'seragam',
    $this->pendaftar->nomor_pendaftaran,
    $extension,
    $this->pendaftar->nama_lengkap  // ADDED
);
```

---

### 6. Pendaftar\TesPsikologi.php

**File**: `app/Livewire/Pendaftar/TesPsikologi.php`

#### Bukti Transfer Tes Psikologi (Line ~215)
```php
$customFileName = FileNamingService::generateBuktiBayarFileName(
    'psikologi',
    $this->pendaftar->nomor_pendaftaran,
    $extension,
    $this->pendaftar->nama_lengkap  // ADDED
);
```

---

### 7. Auth\Register.php

**File**: `app/Livewire/Auth/Register.php`

#### Rapor Upload During Registration (Line ~503)
```php
$customFileName = FileNamingService::generateFileName(
    'rapor',
    $pendaftar->nomor_pendaftaran,
    $extension,
    $pendaftar->nama_lengkap  // ADDED
);
```

---

### 8. Auth\RegisterFixed.php

**File**: `app/Livewire/Auth/RegisterFixed.php`

#### Rapor Upload (Line ~831)
```php
$customFileName = FileNamingService::generateFileName(
    'rapor',
    $pendaftar->nomor_pendaftaran,
    $extension,
    $pendaftar->nama_lengkap  // ADDED
);
```

---

### 9. Auth\RegisterOptimized.php

**File**: `app/Livewire/Auth/RegisterOptimized.php`

#### Rapor Upload (Line ~497)
```php
$customFileName = FileNamingService::generateFileName(
    'rapor',
    $pendaftar->nomor_pendaftaran,
    $extension,
    $pendaftar->nama_lengkap  // ADDED
);
```

---

### 10. Admin\DetailPendaftar.php

**File**: `app/Livewire/Admin/DetailPendaftar.php`

Updated 3 locations:

#### A. Sertifikat Keahlian (Line ~2331)
```php
$filename = FileNamingService::generateFileName(
    'sertifikat_keahlian',
    $this->pendaftar->nomor_pendaftaran,
    $file->getClientOriginalExtension(),
    $this->pendaftar->nama_lengkap  // ADDED
);
```

#### B. Syahadah Tahfidz (Line ~2413)
```php
$filename = FileNamingService::generateFileName(
    'syahadah',
    $this->pendaftar->nomor_pendaftaran,
    $this->uploadTahfidzSyahadah->getClientOriginalExtension(),
    $this->pendaftar->nama_lengkap  // ADDED
);
```

#### C. Tahsin Tahfidz (Line ~2435)
```php
$filename = FileNamingService::generateFileName(
    'tahsin',
    $this->pendaftar->nomor_pendaftaran,
    $this->uploadTahfidzTahsin->getClientOriginalExtension(),
    $this->pendaftar->nama_lengkap  // ADDED
);
```

---

### 11. Panitia\DetailPendaftar.php

**File**: `app/Livewire/Panitia/DetailPendaftar.php`

Updated 3 locations (same as Admin):

#### A. Sertifikat Keahlian (Line ~681)
#### B. Syahadah Tahfidz (Line ~763)
#### C. Tahsin Tahfidz (Line ~785)

All updated with `$this->pendaftar->nama_lengkap` parameter.

---

## 📊 Files Modified Summary

### Services (2 files)
1. ✅ `app/Services/FileNamingService.php` - Core naming logic
2. ✅ `app/Services/ImageService.php` - Image upload handling

### Pendaftar Components (4 files)
3. ✅ `app/Livewire/Pendaftar/Formulir.php` - 5 locations (foto, prestasi, keahlian, syahadah, tahsin)
4. ✅ `app/Livewire/Pendaftar/Dokumen.php` - 1 location (all dokumen types)
5. ✅ `app/Livewire/Pendaftar/DaftarUlang.php` - 3 locations (bukti madrasah, surat pernyataan, bukti seragam)
6. ✅ `app/Livewire/Pendaftar/TesPsikologi.php` - 1 location (bukti psikologi)

### Auth Components (3 files)
7. ✅ `app/Livewire/Auth/Register.php` - 1 location (rapor)
8. ✅ `app/Livewire/Auth/RegisterFixed.php` - 1 location (rapor)
9. ✅ `app/Livewire/Auth/RegisterOptimized.php` - 1 location (rapor)

### Admin Components (1 file)
10. ✅ `app/Livewire/Admin/DetailPendaftar.php` - 3 locations (keahlian, syahadah, tahsin)

### Panitia Components (1 file)
11. ✅ `app/Livewire/Panitia/DetailPendaftar.php` - 3 locations (keahlian, syahadah, tahsin)

**Total**: 11 files modified, 21 upload locations updated

---

## 💡 Technical Details

### Nama Pendaftar Cleaning Logic

```php
// 1. Trim whitespace
$namaPendaftar = trim($namaPendaftar);

// 2. Replace spaces with underscores
$namaPendaftarClean = preg_replace('/\s+/', '_', $namaPendaftar);

// 3. Remove special characters (keep only alphanumeric and underscore)
$namaPendaftarClean = preg_replace('/[^A-Za-z0-9_]/', '', $namaPendaftarClean);

// 4. Limit to 50 characters
$namaPendaftarClean = substr($namaPendaftarClean, 0, 50);
```

**Example transformations**:
- `Ahmad Fulan Bin Zubair` → `Ahmad_Fulan_Bin_Zubair`
- `Siti Aisyah Binti Abdullah` → `Siti_Aisyah_Binti_Abdullah`
- `Muhammad Al-Fatih` → `Muhammad_AlFatih`

### Separator Change

**Old format**: Underscore (`_`)
```
rapor_PPDB2024001_20260131_143052.pdf
```

**New format**: Dash (`-`) with underscores in name
```
rapor-PPDB2024001-Ahmad_Fulan-20260131_143052.pdf
```

### Backward Compatibility

The `generateFileName()` method has backward compatibility:
- If `$namaPendaftar` is null/empty → uses old underscore format
- If `$namaPendaftar` is provided → uses new dash format with name

---

## 🧪 Testing Guide

### 1. Test New Uploads

Upload each type of file and verify format:

```bash
# Check storage folders
ls storage/app/public/photos/
ls storage/app/public/sertifikat_prestasi/
ls storage/app/public/sertifikat_keahlian/
ls storage/app/public/tahfidz/
ls storage/app/private/dokumen/
ls storage/app/private/bukti_bayar/
```

**Expected format**:
```
foto_profil-PPDB2024001-Ahmad_Fulan-20260131_143052.webp
sertifikat_prestasi-PPDB2024001-Ahmad_Fulan-20260131_143052.pdf
rapor-PPDB2024001-Ahmad_Fulan-20260131_143052.pdf
bukti_daftar_ulang-PPDB2024001-Ahmad_Fulan-20260131_143052.jpg
```

### 2. Test Upload Locations

#### Pendaftar:
- [ ] Upload foto di `/pendaftar/formulir` tab Data Siswa
- [ ] Upload rapor di `/register` (new registration)
- [ ] Upload dokumen di `/pendaftar/dokumen`
- [ ] Upload sertifikat prestasi di `/pendaftar/formulir` tab Prestasi
- [ ] Upload sertifikat keahlian di `/pendaftar/formulir` tab Keahlian
- [ ] Upload syahadah dan tahsin di `/pendaftar/formulir` tab Tahfidz
- [ ] Upload bukti daftar ulang madrasah di `/pendaftar/daftar-ulang` tab Madrasah
- [ ] Upload bukti seragam di `/pendaftar/daftar-ulang` tab Seragam
- [ ] Upload bukti tes psikologi di `/pendaftar/tes-psikologi`

#### Admin:
- [ ] Upload keahlian di `/admin/pendaftar/{id}` tab Keahlian
- [ ] Upload syahadah di `/admin/pendaftar/{id}` tab Tahfidz
- [ ] Upload tahsin di `/admin/pendaftar/{id}` tab Tahfidz

#### Panitia:
- [ ] Upload keahlian di `/panitia-pendaftaran/pendaftar/{id}` tab Keahlian
- [ ] Upload syahadah di `/panitia-pendaftaran/pendaftar/{id}` tab Tahfidz
- [ ] Upload tahsin di `/panitia-pendaftaran/pendaftar/{id}` tab Tahfidz

### 3. Verify Download Links Still Work

Check that old files can still be downloaded and new files are saved correctly.

---

## 🚀 Benefits

### For Users:
1. **Easier File Identification** - Filename includes student name
2. **Better Organization** - Files sorted by name + registration number
3. **Quick Search** - Can search by student name in file explorer

### For Admins:
1. **Audit Trail** - Easy to identify who uploaded what
2. **Data Recovery** - Files are self-documenting
3. **Export Friendly** - Files can be bulk exported with meaningful names

### Technical:
1. **Backward Compatible** - Old code still works
2. **Consistent Format** - All uploads follow same pattern
3. **Maintainable** - Centralized in FileNamingService

---

## 🔄 Migration Notes

**Important**: This change is **non-breaking**.

- ✅ Existing files with old format will continue to work
- ✅ New uploads will use new format automatically
- ✅ No database migration needed
- ✅ No manual file renaming required

Old files will remain in old format until they are replaced by new uploads.

---

**Completion Date**: 31 Januari 2026
**Impact**: All file uploads now include nama pendaftar in filename
**Status**: ✅ Fully Implemented and Ready for Testing
