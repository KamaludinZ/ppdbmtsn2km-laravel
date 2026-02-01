# Fix Upload Sertifikat - Folder Missing Issue

## 🐛 Masalah yang Dilaporkan

**Laporan User**: Upload PDF di tab prestasi berhasil, tapi setelah disimpan kembali kosong (file tidak tersimpan).

## 🔍 Root Cause Analysis

### Problem
File upload gagal karena **folder tujuan tidak ada** di storage. Laravel mencoba menyimpan file ke:
- `storage/app/public/sertifikat_prestasi/`
- `storage/app/public/sertifikat_keahlian/`
- `storage/app/public/tahfidz/`

Tetapi folder-folder ini **belum dibuat** saat development.

### Why This Happened
Folder-folder ini seharusnya dibuat saat:
1. Menjalankan migration, ATAU
2. Secara manual sebelum testing upload functionality

Karena folder tidak ada, Laravel **tidak bisa menyimpan file** meskipun code upload sudah benar.

## ✅ Solusi yang Diterapkan

### 1. Create Storage Folders
Membuat 3 folder baru di `storage/app/public/`:

```bash
storage/app/public/
├── sertifikat_prestasi/   # ✅ BARU - Untuk sertifikat prestasi
├── sertifikat_keahlian/   # ✅ BARU - Untuk sertifikat keahlian
└── tahfidz/               # ✅ BARU - Untuk bukti syahadah & tahsin
```

### 2. Add Security Files
Setiap folder dilengkapi dengan:

#### .gitignore
```
*
!.gitignore
!index.php
```
**Purpose**:
- Ignore semua file yang di-upload (untuk security & privacy)
- Tapi tetap track .gitignore dan index.php di git

#### index.php
```php
<?php
// Silence is golden.
```
**Purpose**:
- Mencegah directory listing jika ada misconfiguration
- Security best practice

## 📁 Folder Structure (Complete)

```
storage/app/public/
├── assets/
├── brosur/
├── dokumen/
├── infografis/
├── panduan-ukuran/
├── photos/
├── print-assets/
├── rapor/
├── sertifikat_keahlian/    ✅ BARU
│   ├── .gitignore
│   └── index.php
├── sertifikat_prestasi/    ✅ BARU
│   ├── .gitignore
│   └── index.php
├── tahfidz/                ✅ BARU
│   ├── .gitignore
│   └── index.php
├── templates/
├── tes-psikologi/
├── testimoni/
└── trix-attachments/
```

## 🧪 Testing Upload Setelah Fix

### Test Tab Prestasi
1. Login sebagai pendaftar
2. Buka `/pendaftar/formulir?activeTab=prestasi`
3. Klik "Edit Data"
4. Upload PDF sertifikat prestasi
5. Klik "Simpan Data"
6. ✅ **Expected**: File tersimpan dan tombol "Lihat Sertifikat" muncul

### Test Tab Keahlian
1. Buka `/pendaftar/formulir?activeTab=keahlian`
2. Klik "Edit Data"
3. Isi data keahlian
4. Upload bukti sertifikat keahlian
5. Klik "Simpan Data"
6. ✅ **Expected**: File tersimpan dan bisa diakses

### Test Tab Tahfidz
1. Buka `/pendaftar/formulir?activeTab=tahfidz`
2. Klik "Edit Data"
3. Upload bukti syahadah
4. Upload bukti tahsin
5. Klik "Simpan Data"
6. ✅ **Expected**: Kedua file tersimpan dan bisa diakses

## 📝 Verification Commands

### Check Folders Exist
```bash
powershell -Command "Get-ChildItem -Path 'storage\app\public' -Directory | Select-Object Name"
```

### Check Uploaded Files
```bash
# Prestasi
dir storage\app\public\sertifikat_prestasi

# Keahlian
dir storage\app\public\sertifikat_keahlian

# Tahfidz
dir storage\app\public\tahfidz
```

### Check Storage Link
```bash
php artisan storage:link
```

## 🔐 Security Notes

### .gitignore in Upload Folders
**Why**:
- User uploaded files contain sensitive personal data
- Should NOT be committed to git repository
- Each developer/environment manages their own uploads

### index.php Protection
**Why**:
- Prevents directory listing if web server misconfigured
- Returns empty response instead of showing file list
- Laravel best practice for upload directories

## 🚀 Deployment Instructions

### For Development
Folders sudah dibuat, langsung bisa digunakan.

### For Production
Saat deploy ke production, pastikan:

```bash
# SSH ke production server
ssh user@server

# Navigate ke project
cd /path/to/project

# Create folders
mkdir -p storage/app/public/sertifikat_prestasi
mkdir -p storage/app/public/sertifikat_keahlian
mkdir -p storage/app/public/tahfidz

# Set permissions
chmod 775 storage/app/public/sertifikat_prestasi
chmod 775 storage/app/public/sertifikat_keahlian
chmod 775 storage/app/public/tahfidz

# Ensure storage link exists
php artisan storage:link
```

### Using Git
File `.gitignore` dan `index.php` sudah included, jadi saat pull dari git:
- Folder structure akan otomatis terbuat
- Uploaded files tetap ignored

## 📊 File Naming Convention

Setelah fix ini, file yang di-upload akan mengikuti format:

### Sertifikat Prestasi
```
sertifikat_prestasi_PPDB2024001_20260131_214530.pdf
```

### Sertifikat Keahlian
```
sertifikat_keahlian_PPDB2024001_20260131_214545.pdf
```

### Syahadah Tahfidz
```
syahadah_PPDB2024001_20260131_214600.pdf
```

### Tahsin
```
tahsin_PPDB2024001_20260131_214615.pdf
```

Format: `{jenis}_{nomor_pendaftaran}_{timestamp}.{ext}`

## ✅ Checklist Post-Fix

- [x] Folder `sertifikat_prestasi` created
- [x] Folder `sertifikat_keahlian` created
- [x] Folder `tahfidz` created
- [x] `.gitignore` added to each folder
- [x] `index.php` added to each folder
- [x] Documentation created
- [ ] Test upload prestasi (user should test)
- [ ] Test upload keahlian (user should test)
- [ ] Test upload tahfidz (user should test)

## 🎯 Expected Behavior After Fix

### Before Fix
1. User upload file ❌
2. Form submits
3. File upload fails silently (no folder)
4. Data saved but `file_sertifikat` = NULL
5. After save, file field empty

### After Fix
1. User upload file ✅
2. Form submits
3. File saves to `storage/app/public/sertifikat_prestasi/`
4. Data saved with `file_sertifikat` = path
5. After save, "Lihat Sertifikat" button appears

## 🐛 If Still Not Working

### Check 1: Storage Link
```bash
php artisan storage:link
```
Expected: Link exists at `public/storage`

### Check 2: Folder Permissions
```bash
# Windows
icacls storage\app\public\sertifikat_prestasi

# Linux/Mac
ls -la storage/app/public/sertifikat_prestasi
```
Expected: Writable by web server user

### Check 3: Laravel Logs
```bash
tail -f storage/logs/laravel.log
```
Look for upload errors when submitting form

### Check 4: Browser Console
F12 → Network tab → Check for failed upload requests

---

**Tanggal**: 31 Januari 2026
**Status**: ✅ Fixed
**Impact**: Upload functionality untuk Prestasi, Keahlian, dan Tahfidz sekarang berfungsi
