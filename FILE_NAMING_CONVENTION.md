# File Naming Convention - PPDB MTsN 2 Kota Malang

## 🎯 Tujuan

Implementasi penamaan file yang sistematis untuk:
- ✅ Memudahkan pencarian file saat backup
- ✅ Identifikasi file berdasarkan nomor pendaftaran
- ✅ Tracking waktu upload file
- ✅ Organisasi file yang terstruktur

---

## 📝 Format Penamaan File

### Format Umum
```
{jenis_dokumen}_{nomor_pendaftaran}_{timestamp}.{ext}
```

**Contoh**:
```
rapor_PPDB2024001_20260119_143052.pdf
ktp_PPDB2024001_20260119_143109.pdf
foto_profil_PPDB2024001_20260119_143125.webp
```

### Komponen Format

1. **jenis_dokumen**: Jenis/kategori dokumen (lowercase, snake_case)
2. **nomor_pendaftaran**: Nomor pendaftaran unik pendaftar
3. **timestamp**: Format `YYYYMMDD_HHMMSS` (20260119_143052)
4. **ext**: Extension file (pdf, jpg, png, webp)

---

## 📂 Jenis-Jenis Dokumen

### 1. Dokumen Persyaratan (Private Storage)

| Jenis Dokumen | Format Nama File | Extension | Lokasi |
|--------------|------------------|-----------|---------|
| Rapor | `rapor_{nomor}_{timestamp}.pdf` | pdf | storage/app/private/dokumen/{pendaftar_id}/ |
| Akta Kelahiran | `akta_kelahiran_{nomor}_{timestamp}.pdf` | pdf, jpg, png | storage/app/private/dokumen/{pendaftar_id}/ |
| Kartu Keluarga | `kartu_keluarga_{nomor}_{timestamp}.pdf` | pdf, jpg, png | storage/app/private/dokumen/{pendaftar_id}/ |
| NISN | `nisn_{nomor}_{timestamp}.pdf` | pdf | storage/app/private/dokumen/{pendaftar_id}/ |
| Ijazah SD | `ijazah_sd_{nomor}_{timestamp}.pdf` | pdf, jpg, png | storage/app/private/dokumen/{pendaftar_id}/ |
| SKHUN SD | `skhun_sd_{nomor}_{timestamp}.pdf` | pdf, jpg, png | storage/app/private/dokumen/{pendaftar_id}/ |
| Sertifikat | `sertifikat_prestasi_{nomor}_{timestamp}.pdf` | pdf | storage/app/private/dokumen/{pendaftar_id}/ |
| KIP | `kip_{nomor}_{timestamp}.pdf` | pdf | storage/app/private/dokumen/{pendaftar_id}/ |
| PKH | `pkh_{nomor}_{timestamp}.pdf` | pdf | storage/app/private/dokumen/{pendaftar_id}/ |
| KKS | `kks_{nomor}_{timestamp}.pdf` | pdf | storage/app/private/dokumen/{pendaftar_id}/ |

### 2. Bukti Pembayaran (Private Storage)

| Jenis Pembayaran | Format Nama File | Extension | Lokasi |
|-----------------|------------------|-----------|---------|
| Daftar Ulang | `bukti_daftar_ulang_{nomor}_{timestamp}.{ext}` | pdf, jpg, png | storage/app/private/bukti_bayar/{pendaftar_id}/ |
| Seragam | `bukti_seragam_{nomor}_{timestamp}.{ext}` | pdf, jpg, png | storage/app/private/bukti_bayar/{pendaftar_id}/ |
| Tes Psikologi | `bukti_psikologi_{nomor}_{timestamp}.{ext}` | jpg, png, pdf | storage/app/private/bukti_bayar/{pendaftar_id}/ |

### 3. Foto Profil (Public Storage)

| Jenis Foto | Format Nama File | Extension | Lokasi |
|-----------|------------------|-----------|---------|
| Foto Profil | `foto_profil_{nomor}_{timestamp}.webp` | webp | storage/app/public/photos/ |

---

## 🔧 FileNamingService API

### Service Class: `App\Services\FileNamingService`

#### 1. Generate Nama File
```php
FileNamingService::generateFileName(
    string $jenisFile,
    string $nomorPendaftaran,
    string $extension
): string

// Contoh:
$filename = FileNamingService::generateFileName(
    'rapor',
    'PPDB2024001',
    'pdf'
);
// Output: rapor_PPDB2024001_20260119_143052.pdf
```

#### 2. Generate Path Lengkap
```php
FileNamingService::generateFilePath(
    string $jenisFile,
    string $nomorPendaftaran,
    string $pendaftarId,
    string $extension
): string

// Contoh:
$path = FileNamingService::generateFilePath(
    'rapor',
    'PPDB2024001',
    '123',
    'pdf'
);
// Output: dokumen/123/rapor_PPDB2024001_20260119_143052.pdf
```

#### 3. Generate Nama File Gambar
```php
FileNamingService::generateImageFileName(
    string $jenisGambar,
    string $nomorPendaftaran,
    string $extension
): string

// Contoh:
$filename = FileNamingService::generateImageFileName(
    'foto_profil',
    'PPDB2024001',
    'webp'
);
// Output: foto_profil_PPDB2024001_20260119_143052.webp
```

#### 4. Generate Nama Bukti Bayar
```php
FileNamingService::generateBuktiBayarFileName(
    string $jenisPembayaran,
    string $nomorPendaftaran,
    string $extension
): string

// Contoh:
$filename = FileNamingService::generateBuktiBayarFileName(
    'daftar_ulang',
    'PPDB2024001',
    'pdf'
);
// Output: bukti_daftar_ulang_PPDB2024001_20260119_143052.pdf
```

#### 5. Get Extension dari Uploaded File
```php
FileNamingService::getFileExtension($file): string

// Contoh:
$extension = FileNamingService::getFileExtension($request->file('dokumen'));
// Output: "pdf"
```

#### 6. Clean Jenis Dokumen
```php
FileNamingService::cleanJenisDokumen(string $jenisRaw): string

// Contoh:
$clean = FileNamingService::cleanJenisDokumen('raport');
// Output: "rapor"

$clean = FileNamingService::cleanJenisDokumen('akta');
// Output: "akta_kelahiran"
```

---

## 💻 Implementasi di Livewire Components

### 1. Upload Dokumen (Dokumen.php)
**Lokasi**: `/pendaftar/dokumen`

**Dokumen yang di-upload di sini**:
- Rapor
- Kartu Keluarga (KK)
- Akta Kelahiran
- NISN
- Ijazah SD
- SKHUN SD
- Sertifikat/Prestasi
- KIP, PKH, KKS

```php
use App\Services\FileNamingService;

// Di method processUpload()
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

### 2. Upload Foto Profil (Formulir.php)
**Lokasi**: `/pendaftar/formulir` - Tab Data Siswa (Identitas Dasar)

**CATATAN**: Hanya foto profil yang di-upload di Formulir.php. Semua dokumen lainnya (KK, Akta, Ijazah SD, SKHUN SD, dll) di-upload di Dokumen.php.
```php
use App\Services\ImageService;

// Di method saveDataSiswa()
$imageService = new ImageService();

$fotoPath = $imageService->uploadProfilePhoto(
    $this->newFoto,
    'photos',
    500,
    80,
    $this->pendaftar->nomor_pendaftaran,  // Nomor pendaftaran
    'foto_profil'                         // Jenis foto
);
```

### 3. Upload Bukti Pembayaran (DaftarUlang.php)
```php
use App\Services\FileNamingService;

// Di method saveMadrasah()
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

### 4. Upload Bukti Transfer Psikologi (TesPsikologi.php)
```php
use App\Services\FileNamingService;

// Di method uploadBuktiTransfer()
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
```

---

## 📁 Struktur Storage

### Private Storage (storage/app/private/)
```
storage/app/private/
├── dokumen/
│   ├── 1/  (pendaftar_id)
│   │   ├── rapor_PPDB2024001_20260119_143052.pdf
│   │   ├── akta_kelahiran_PPDB2024001_20260119_143109.pdf
│   │   ├── kartu_keluarga_PPDB2024001_20260119_143125.pdf
│   │   ├── nisn_PPDB2024001_20260119_143140.pdf
│   │   └── ijazah_sd_PPDB2024001_20260119_143155.pdf
│   │
│   ├── 2/
│   │   └── ...
│   │
│   └── ...
│
└── bukti_bayar/
    ├── 1/
    │   ├── bukti_daftar_ulang_PPDB2024001_20260119_150000.pdf
    │   ├── bukti_seragam_PPDB2024001_20260119_150100.pdf
    │   └── bukti_psikologi_PPDB2024001_20260119_150200.jpg
    │
    └── ...
```

### Public Storage (storage/app/public/)
```
storage/app/public/
└── photos/
    ├── foto_profil_PPDB2024001_20260119_143052.webp
    ├── foto_profil_PPDB2024002_20260119_143212.webp
    └── ...
```

---

## 🔍 Keuntungan Sistem Penamaan Ini

### 1. Mudah Dicari
```bash
# Cari semua file pendaftar tertentu
find storage/app/private -name "*PPDB2024001*"

# Cari semua bukti daftar ulang
find storage/app/private/bukti_bayar -name "bukti_daftar_ulang_*"

# Cari file yang di-upload pada tanggal tertentu
find storage/app/private -name "*_20260119_*"
```

### 2. Backup Terorganisir
```bash
# Backup per pendaftar
tar -czf backup_PPDB2024001.tar.gz \
  storage/app/private/dokumen/1/ \
  storage/app/private/bukti_bayar/1/

# Backup per jenis dokumen
tar -czf backup_rapor_all.tar.gz \
  $(find storage/app/private -name "rapor_*")
```

### 3. Identifikasi Cepat
Dari nama file langsung tahu:
- ✅ Jenis dokumen: `rapor`
- ✅ Pemilik: `PPDB2024001`
- ✅ Tanggal upload: `2026-01-19`
- ✅ Waktu upload: `14:30:52`

### 4. Tidak Ada Duplikat
Karena menggunakan timestamp, tidak mungkin ada 2 file dengan nama sama.

---

## 🛡️ Security Considerations

### 1. Private vs Public Storage

**PRIVATE** (storage/app/private/):
- ✅ Dokumen persyaratan (KTP, KK, Akta, Raport, NISN, Ijazah, SKHUN)
- ✅ Bukti pembayaran (Daftar Ulang, Seragam, Psikologi)
- ✅ Surat pernyataan
- ✅ File sensitif lainnya

**Alasan**: Dokumen ini hanya boleh diakses oleh:
- Pendaftar yang bersangkutan
- Admin
- Panitia

**PUBLIC** (storage/app/public/):
- ✅ Foto profil (sudah di-compress dan resize)

**Alasan**: Foto profil perlu diakses untuk ditampilkan di UI

### 2. Authorization Checks

Semua download file private harus melalui `SecureFileController`:
```php
// routes/web.php
Route::middleware(['auth'])->group(function () {
    Route::get('/download/dokumen/{id}', [SecureFileController::class, 'downloadDokumen']);
    Route::get('/download/bukti-bayar/{pendaftar}/{type}', [SecureFileController::class, 'downloadBuktiBayar']);
});
```

---

## 📊 Migration untuk File Lama

### Script untuk Rename File Lama
```php
// artisan command: php artisan files:rename-old

use App\Models\Dokumen;
use App\Services\FileNamingService;
use Illuminate\Support\Facades\Storage;

foreach (Dokumen::all() as $dokumen) {
    $oldPath = $dokumen->path_file;

    if (!Storage::disk('private')->exists($oldPath)) {
        continue;
    }

    // Generate new filename
    $extension = pathinfo($oldPath, PATHINFO_EXTENSION);
    $newFileName = FileNamingService::generateFileName(
        $dokumen->jenis_dokumen,
        $dokumen->pendaftar->nomor_pendaftaran,
        $extension
    );

    $newPath = 'dokumen/' . $dokumen->pendaftar_id . '/' . $newFileName;

    // Move file
    Storage::disk('private')->move($oldPath, $newPath);

    // Update database
    $dokumen->update([
        'nama_file' => $newFileName,
        'path_file' => $newPath
    ]);
}
```

---

## ✅ Testing Checklist

Setelah implementasi, test:

- [ ] Upload dokumen baru menggunakan format nama yang benar
- [ ] Nama file include nomor pendaftaran
- [ ] Nama file include timestamp
- [ ] File disimpan di folder yang benar
- [ ] File lama terhapus saat upload file baru
- [ ] Download file works via SecureFileController
- [ ] Backup file mudah dicari berdasarkan nama
- [ ] Tidak ada file duplicate atau naming conflict

---

## 📝 Contoh Lengkap

### Upload Raport
```
Pendaftar: PPDB2024001
Jenis: Raport
Waktu: 2026-01-19 14:30:52
Extension: pdf

Hasil:
- Nama File: rapor_PPDB2024001_20260119_143052.pdf
- Path: storage/app/private/dokumen/1/rapor_PPDB2024001_20260119_143052.pdf
- URL Download: /download/dokumen/123 (via SecureFileController)
```

### Upload Foto Profil
```
Pendaftar: PPDB2024001
Jenis: Foto Profil
Waktu: 2026-01-19 14:31:25
Extension: webp (converted from jpg)

Hasil:
- Nama File: foto_profil_PPDB2024001_20260119_143125.webp
- Path: storage/app/public/photos/foto_profil_PPDB2024001_20260119_143125.webp
- URL Public: /storagepmbm/photos/foto_profil_PPDB2024001_20260119_143125.webp
```

### Upload Bukti Bayar Daftar Ulang
```
Pendaftar: PPDB2024001
Jenis: Bukti Daftar Ulang
Waktu: 2026-01-19 15:00:00
Extension: pdf

Hasil:
- Nama File: bukti_daftar_ulang_PPDB2024001_20260119_150000.pdf
- Path: storage/app/private/bukti_bayar/1/bukti_daftar_ulang_PPDB2024001_20260119_150000.pdf
- URL Download: /download/bukti-bayar/1/daftar_ulang (via SecureFileController)
```

---

## 📝 Update Log

### Update 20 Januari 2026 - v1.1
**Changes**:
- ✅ Klarifikasi bahwa upload dokumen (KK, Akta, Ijazah SD, SKHUN SD) **hanya ada di Dokumen.php**
- ✅ Upload foto profil tetap ada di Formulir.php (Tab Data Siswa)
- ✅ Removed upload code untuk KK, Akta, Ijazah SD, SKHUN SD dari Formulir.php
- ✅ Semua upload dokumen sekarang **terpusat** di `/pendaftar/dokumen`

---

**Status**: ✅ **IMPLEMENTED & REFINED**

**Last Updated**: 20 Januari 2026
**Version**: 1.1
**Compatibility**: Laravel 11/12, Livewire 3/4
