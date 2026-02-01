# Fix File Naming Format: Datestamp Only + Export Excel Status

**Tanggal**: 31 Januari 2026
**Status**: ✅ Complete

## 🎯 Changes Made

### 1. Timestamp Format Changed to Datestamp

**Issue**: File naming terlalu panjang dengan datetime timestamp `Ymd_His` (20260131_143052)

**Solution**: Gunakan datestamp saja `Ymd` (20260131)

#### Before:
```
rapor-PPDB2024001-Ahmad_Fulan-20260131_143052.pdf
```

#### After:
```
rapor-PPDB2024001-Ahmad_Fulan-20260131.pdf
```

**Benefits**:
- Filename lebih pendek
- Tetap unique karena unlikely ada 2 upload dari pendaftar yang sama di hari yang sama
- Lebih readable dan clean

---

### 2. Added Filename + Status Columns in Excel Export

**Issue**: Export Excel hanya menampilkan status upload, tidak menampilkan nama file yang sebenarnya

**Solution**: Tambahkan kolom terpisah untuk nama file DAN status upload

#### A. Dokumen Persyaratan (8 jenis dokumen)

**Kolom Ditambahkan** (16 kolom total - 2 per dokumen):

| No | Nama Kolom | Isi |
|----|------------|-----|
| 1 | Nama File Akta Kelahiran | Filename lengkap dengan format baru |
| 2 | Status File Akta Kelahiran | "Sudah Upload" / "Belum Upload" |
| 3 | Nama File Rapor | Filename |
| 4 | Status File Rapor | Status |
| 5 | Nama File Kartu Keluarga | Filename |
| 6 | Status File Kartu Keluarga | Status |
| 7 | Nama File NISN | Filename |
| 8 | Status File NISN | Status |
| 9 | Nama File Ijazah | Filename |
| 10 | Status File Ijazah | Status |
| 11 | Nama File Sertifikat | Filename |
| 12 | Status File Sertifikat | Status |
| 13 | Nama File KIP | Filename |
| 14 | Status File KIP | Status |
| 15 | Nama File Foto | Filename |
| 16 | Status File Foto | Status |

#### B. Dokumen Daftar Ulang & Tes Psikologi (4 jenis dokumen)

**Kolom Ditambahkan** (8 kolom total):

| No | Nama Kolom | Isi |
|----|------------|-----|
| 1 | Nama File Bukti Daftar Ulang Madrasah | Filename bukti pembayaran DU madrasah |
| 2 | Status File Bukti DU Madrasah | Status upload |
| 3 | Nama File Surat Pernyataan DU | Filename surat pernyataan |
| 4 | Status File Surat Pernyataan DU | Status upload |
| 5 | Nama File Bukti Daftar Ulang Seragam | Filename bukti pembayaran seragam |
| 6 | Status File Bukti DU Seragam | Status upload |
| 7 | Nama File Bukti Tes Psikologi | Filename bukti transfer psikologi |
| 8 | Status File Bukti Tes Psikologi | Status upload |

**Total Kolom Baru**: 24 kolom (16 dokumen persyaratan + 8 dokumen pembayaran)

---

## 🔧 Technical Implementation

### File: `app/Services/FileNamingService.php`

#### Change: Line 51-60

**Before**:
```php
// Generate timestamp (format: YYYYMMDD_HHMMSS)
$timestamp = now()->format('Ymd_His');
```

**After**:
```php
// Generate timestamp (format: YYYYMMDD for date only)
$timestamp = now()->format('Ymd');
```

**Impact**: All file uploads will now use datestamp only

---

### File: `app/Exports/PendaftarExport.php`

#### A. Updated Headings (Line 795-846)

**Before (8 columns)**:
```php
$baseHeadings = array_merge($baseHeadings, [
    'File Akta Kelahiran',
    'File Rapor',
    'File Kartu Keluarga',
    'File NISN',
    'File Ijazah',
    'File Sertifikat',
    'File KIP',
    'File Foto',
]);
```

**After (16 columns)**:
```php
$baseHeadings = array_merge($baseHeadings, [
    'Nama File Akta Kelahiran',
    'Status File Akta Kelahiran',
    'Nama File Rapor',
    'Status File Rapor',
    'Nama File Kartu Keluarga',
    'Status File Kartu Keluarga',
    'Nama File NISN',
    'Status File NISN',
    'Nama File Ijazah',
    'Status File Ijazah',
    'Nama File Sertifikat',
    'Status File Sertifikat',
    'Nama File KIP',
    'Status File KIP',
    'Nama File Foto',
    'Status File Foto',
]);
```

**Added New Section (8 columns)**:
```php
// UPLOAD BERKAS TAMBAHAN (8 columns)
$baseHeadings = array_merge($baseHeadings, [
    'Nama File Bukti Daftar Ulang Madrasah',
    'Status File Bukti DU Madrasah',
    'Nama File Surat Pernyataan DU',
    'Status File Surat Pernyataan DU',
    'Nama File Bukti Daftar Ulang Seragam',
    'Status File Bukti DU Seragam',
    'Nama File Bukti Tes Psikologi',
    'Status File Bukti Tes Psikologi',
]);
```

#### B. Updated Mapping Logic (Line 491-527)

**Before**:
```php
foreach ($dokumenTypes as $jenis => $label) {
    if ($jenis === 'foto') {
        $baseData[] = $pendaftar->foto ?? '';
    } else {
        $dokumen = $pendaftar->dokumen->where('jenis_dokumen', $jenis)->first();
        $baseData[] = $dokumen ? ($dokumen->nama_file ?? '') : '';
    }
}
```

**After**:
```php
foreach ($dokumenTypes as $jenis => $label) {
    if ($jenis === 'foto') {
        $fotoExists = !empty($pendaftar->foto);
        $baseData[] = $pendaftar->foto ?? '';  // Filename
        $baseData[] = $fotoExists ? 'Sudah Upload' : 'Belum Upload';  // Status
    } else {
        $dokumen = $pendaftar->dokumen->where('jenis_dokumen', $jenis)->first();
        $filename = $dokumen ? ($dokumen->nama_file ?? '') : '';
        $baseData[] = $filename;  // Filename
        $baseData[] = $filename ? 'Sudah Upload' : 'Belum Upload';  // Status
    }
}
```

**Added New Section (Line 506-527)**:
```php
// UPLOAD BERKAS TAMBAHAN (8 columns)
// Bukti Daftar Ulang Madrasah
$buktiDuMadrasah = $pendaftar->daftarUlang->bukti_pembayaran_madrasah ?? '';
$baseData[] = $buktiDuMadrasah ? basename($buktiDuMadrasah) : '';
$baseData[] = $buktiDuMadrasah ? 'Sudah Upload' : 'Belum Upload';

// Surat Pernyataan DU
$suratPernyataan = $pendaftar->daftarUlang->surat_pernyataan_path ?? '';
$baseData[] = $suratPernyataan ? basename($suratPernyataan) : '';
$baseData[] = $suratPernyataan ? 'Sudah Upload' : 'Belum Upload';

// Bukti Daftar Ulang Seragam
$buktiDuSeragam = $pendaftar->seragam->bukti_pembayaran ?? '';
$baseData[] = $buktiDuSeragam ? basename($buktiDuSeragam) : '';
$baseData[] = $buktiDuSeragam ? 'Sudah Upload' : 'Belum Upload';

// Bukti Tes Psikologi
$buktiPsikologi = $pendaftar->tesPsikologiPendaftar->bukti_transfer ?? '';
$baseData[] = $buktiPsikologi ? basename($buktiPsikologi) : '';
$baseData[] = $buktiPsikologi ? 'Sudah Upload' : 'Belum Upload';
```

---

## 📊 Excel Export Example

### Before (Old Format):

| File Akta | File Rapor | File KK | Status Pendaftaran |
|-----------|------------|---------|-------------------|
| akta_kelahiran_PPDB2024001_20260131_143052.pdf | rapor_PPDB2024001_20260131_143052.pdf | kartu_keluarga_PPDB2024001_20260131_143052.pdf | Lulus |

### After (New Format):

| Nama File Akta | Status File Akta | Nama File Rapor | Status File Rapor | Nama File KK | Status File KK | ... |
|----------------|------------------|-----------------|-------------------|--------------|----------------|-----|
| akta_kelahiran-PPDB2024001-Ahmad_Fulan-20260131.pdf | Sudah Upload | rapor-PPDB2024001-Ahmad_Fulan-20260131.pdf | Sudah Upload | kartu_keluarga-PPDB2024001-Ahmad_Fulan-20260131.pdf | Sudah Upload | ... |

---

## 🎯 Benefits

### For Export Excel:
1. **Filename Column**: Admin/Panitia bisa lihat nama file yang ACTUAL untuk tracking
2. **Status Column**: Mudah filter pendaftar yang sudah/belum upload
3. **Better Audit**: Bisa verify format file name sesuai dengan standar baru
4. **Easy Reference**: Copy-paste nama file untuk searching di storage

### For File Naming:
1. **Shorter Filename**: Dari ~55 chars menjadi ~45 chars
2. **Still Unique**: Datestamp masih cukup untuk uniqueness
3. **More Readable**: Tanpa time membuat lebih clean
4. **Better Organization**: Easier to sort by date in file manager

---

## 📝 Files Modified

1. ✅ `app/Services/FileNamingService.php` - Line 51-60 (timestamp format)
2. ✅ `app/Exports/PendaftarExport.php` - Lines 491-527, 795-846 (export columns)

**Total**: 2 files modified

---

## 🧪 Testing Checklist

### File Naming Test:
- [ ] Upload dokumen baru → verify format: `{jenis}-{nomor}-{nama}-{datestamp}.{ext}`
- [ ] Check storage folder → verify hanya tanggal, tanpa jam
- [ ] Verify nama pendaftar included in filename

### Excel Export Test:
- [ ] Export data pendaftar dari `/admin/laporan`
- [ ] Open Excel file
- [ ] Verify kolom "Nama File {Jenis}" menampilkan filename lengkap
- [ ] Verify kolom "Status File {Jenis}" menampilkan "Sudah Upload" atau "Belum Upload"
- [ ] Verify 24 kolom baru ter-generate dengan benar

### Example Expected Filenames:
```
rapor-PPDB2024001-Ahmad_Fulan-20260131.pdf
foto_profil-PPDB2024001-Siti_Aisyah-20260131.webp
bukti_daftar_ulang-PPDB2024001-Muhammad_AlFatih-20260131.jpg
sertifikat_prestasi-PPDB2024001-Fatimah_Zahra-20260131.pdf
```

---

## 🔄 Backward Compatibility

**File Naming**:
- ✅ Old files with time in filename still work
- ✅ New uploads use new format automatically
- ✅ Both formats can coexist

**Excel Export**:
- ✅ Existing exports will show new columns starting from next export
- ✅ No data migration needed
- ✅ Empty cells for pendaftar without uploads

---

**Completion Date**: 31 Januari 2026
**Status**: ✅ Complete - Datestamp format + Excel export dengan filename dan status
**Impact**: Lebih mudah tracking file upload dan audit trail

---

## 🎉 Migration Status

**Migration of existing files**: ✅ **COMPLETE**

Lihat detail di: `MIGRATION_FILE_NAMING_COMPLETE.md`

**Summary**:
- ✅ 14 files successfully migrated to new format
- ✅ All files verified (nama + datestamp)
- ✅ Tes Psikologi files moved from public to private disk
- ✅ 0 errors during migration
