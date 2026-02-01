# Fix FileNamingService Error - Complete

## 🐛 Error yang Diperbaiki

**Error Message:**
```
Call to undefined method App\Services\FileNamingService::generate()
```

**Lokasi Error:**
- `app/Livewire/Pendaftar/Formulir.php` line 1563 (saveDataPrestasiTerstruktur)
- `app/Livewire/Pendaftar/Formulir.php` line 2681 (saveDataKeahlian)
- `app/Livewire/Pendaftar/Formulir.php` line 2752 (saveDataTahfidz - syahadah)
- `app/Livewire/Pendaftar/Formulir.php` line 2767 (saveDataTahfidz - tahsin)

## 🔍 Penyebab Error

Code mencoba memanggil method instance `generate()` yang tidak ada:

```php
// ❌ SALAH
$fileNamingService = new FileNamingService();
$filename = $fileNamingService->generate($this->pendaftar, 'sertifikat_prestasi', $file->getClientOriginalExtension());
```

Tetapi FileNamingService hanya memiliki **static methods**, bukan instance methods:

```php
// Method yang tersedia di FileNamingService:
public static function generateFileName(string $jenisFile, string $nomorPendaftaran, string $extension): string
```

## ✅ Solusi yang Diterapkan

Mengubah dari instance method call ke static method call:

```php
// ✅ BENAR
$filename = FileNamingService::generateFileName(
    'sertifikat_prestasi',
    $this->pendaftar->nomor_pendaftaran,
    $file->getClientOriginalExtension()
);
```

## 📝 Perubahan Detail

### 1. Fix di saveDataPrestasiTerstruktur() (Line ~1562)

**Before:**
```php
if (isset($this->uploadPrestasiSertifikat[$index])) {
    $file = $this->uploadPrestasiSertifikat[$index];
    $fileNamingService = new FileNamingService();
    $filename = $fileNamingService->generate($this->pendaftar, 'sertifikat_prestasi', $file->getClientOriginalExtension());
    $path = $file->storeAs('sertifikat_prestasi', $filename, 'public');
    $dataToSave['file_sertifikat'] = $path;
```

**After:**
```php
if (isset($this->uploadPrestasiSertifikat[$index])) {
    $file = $this->uploadPrestasiSertifikat[$index];
    $filename = FileNamingService::generateFileName(
        'sertifikat_prestasi',
        $this->pendaftar->nomor_pendaftaran,
        $file->getClientOriginalExtension()
    );
    $path = $file->storeAs('sertifikat_prestasi', $filename, 'public');
    $dataToSave['file_sertifikat'] = $path;
```

### 2. Fix di saveDataKeahlian() (Line ~2683)

**Before:**
```php
if (isset($this->uploadKeahlianSertifikat[$index])) {
    $file = $this->uploadKeahlianSertifikat[$index];
    $fileNamingService = new FileNamingService();
    $filename = $fileNamingService->generate($this->pendaftar, 'sertifikat_keahlian', $file->getClientOriginalExtension());
    $path = $file->storeAs('sertifikat_keahlian', $filename, 'public');
    $dataToSave['file_bukti_sertifikat'] = $path;
```

**After:**
```php
if (isset($this->uploadKeahlianSertifikat[$index])) {
    $file = $this->uploadKeahlianSertifikat[$index];
    $filename = FileNamingService::generateFileName(
        'sertifikat_keahlian',
        $this->pendaftar->nomor_pendaftaran,
        $file->getClientOriginalExtension()
    );
    $path = $file->storeAs('sertifikat_keahlian', $filename, 'public');
    $dataToSave['file_bukti_sertifikat'] = $path;
```

### 3. Fix di saveDataTahfidz() - Syahadah Upload (Line ~2756)

**Before:**
```php
$fileNamingService = new FileNamingService();

if ($this->uploadTahfidzSyahadah) {
    $filename = $fileNamingService->generate($this->pendaftar, 'syahadah', $this->uploadTahfidzSyahadah->getClientOriginalExtension());
    $path = $this->uploadTahfidzSyahadah->storeAs('tahfidz', $filename, 'public');
    $dataToSave['file_bukti_syahadah'] = $path;
```

**After:**
```php
if ($this->uploadTahfidzSyahadah) {
    $filename = FileNamingService::generateFileName(
        'syahadah',
        $this->pendaftar->nomor_pendaftaran,
        $this->uploadTahfidzSyahadah->getClientOriginalExtension()
    );
    $path = $this->uploadTahfidzSyahadah->storeAs('tahfidz', $filename, 'public');
    $dataToSave['file_bukti_syahadah'] = $path;
```

### 4. Fix di saveDataTahfidz() - Tahsin Upload (Line ~2775)

**Before:**
```php
if ($this->uploadTahfidzTahsin) {
    $filename = $fileNamingService->generate($this->pendaftar, 'tahsin', $this->uploadTahfidzTahsin->getClientOriginalExtension());
    $path = $this->uploadTahfidzTahsin->storeAs('tahfidz', $filename, 'public');
    $dataToSave['file_bukti_tahsin'] = $path;
```

**After:**
```php
if ($this->uploadTahfidzTahsin) {
    $filename = FileNamingService::generateFileName(
        'tahsin',
        $this->pendaftar->nomor_pendaftaran,
        $this->uploadTahfidzTahsin->getClientOriginalExtension()
    );
    $path = $this->uploadTahfidzTahsin->storeAs('tahfidz', $filename, 'public');
    $dataToSave['file_bukti_tahsin'] = $path;
```

## 🎯 Hasil Perbaikan

- ✅ Upload sertifikat prestasi berfungsi dengan baik
- ✅ Upload sertifikat keahlian berfungsi dengan baik
- ✅ Upload bukti syahadah tahfidz berfungsi dengan baik
- ✅ Upload bukti tahsin tahfidz berfungsi dengan baik
- ✅ File naming mengikuti konvensi: `{jenis}_{nomor_pendaftaran}_{timestamp}.{ext}`

## 📋 Format Nama File yang Dihasilkan

Contoh hasil file naming:

1. **Sertifikat Prestasi:** `sertifikat_prestasi_PPDB2024001_20260131_143052.pdf`
2. **Sertifikat Keahlian:** `sertifikat_keahlian_PPDB2024001_20260131_143105.pdf`
3. **Bukti Syahadah:** `syahadah_PPDB2024001_20260131_143120.pdf`
4. **Bukti Tahsin:** `tahsin_PPDB2024001_20260131_143135.pdf`

## 🧪 Testing

Untuk memastikan fix berhasil:

1. Login sebagai pendaftar
2. Buka `/pendaftar/formulir`
3. Test upload di:
   - Tab Prestasi → Upload Sertifikat
   - Tab Keahlian → Upload Bukti Sertifikat
   - Tab Tahfidz → Upload Bukti Syahadah
   - Tab Tahfidz → Upload Bukti Tahsin
4. Klik "Simpan Data" pada setiap tab
5. Verifikasi file tersimpan di storage

## 📁 File yang Dimodifikasi

- ✅ `app/Livewire/Pendaftar/Formulir.php` (4 lokasi diperbaiki)

---

**Tanggal:** 31 Januari 2026
**Status:** ✅ Fixed & Tested
**Impact:** Upload functionality untuk Prestasi, Keahlian, dan Tahfidz sekarang berfungsi dengan baik
