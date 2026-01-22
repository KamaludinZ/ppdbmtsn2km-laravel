# Solusi Symlink Storage - PPDB MTsN 2 Kota Malang

## 🎯 Masalah

Di shared hosting, symlink dengan nama `storage` sudah digunakan oleh aplikasi lain, sehingga terjadi error:
```
File already exists: public/storage
```

## ✅ Solusi Implemented

Symlink diubah dari `public/storage` menjadi `public/storagepmbm` untuk menghindari konflik.

---

## 📋 Perubahan Konfigurasi

### 1. File: `config/filesystems.php`

#### A. Disk 'public' URL diubah:
```php
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL').'/storagepmbm',  // ✅ Diubah dari '/storage'
    'visibility' => 'public',
],
```

#### B. Symlink configuration diubah:
```php
'links' => [
    public_path('storagepmbm') => storage_path('app/public'),  // ✅ Diubah dari 'storage'
],
```

---

## 🚀 Cara Membuat Symlink

### Opsi 1: Via Artisan Command (Recommended)
```bash
# Di server
cd pmbm.mtsn2kotamalang.sch.id/
php artisan storage:link
```

**Output**:
```
The [public/storagepmbm] link has been connected to [storage/app/public].
```

### Opsi 2: Manual via SSH
```bash
# Di server
cd pmbm.mtsn2kotamalang.sch.id/public/
ln -s ../storage/app/public storagepmbm
```

### Opsi 3: Manual via cPanel File Manager
1. Buka File Manager di cPanel
2. Navigate ke folder `pmbm.mtsn2kotamalang.sch.id/public/`
3. Klik "Create Symbolic Link" (jika tersedia)
4. Source: `../storage/app/public`
5. Destination: `storagepmbm`

---

## 🔍 Verifikasi Symlink

### Via SSH:
```bash
ls -la public/storagepmbm
# Expected output:
# storagepmbm -> ../storage/app/public
```

### Via Browser:
```
https://pmbm.mtsn2kotamalang.sch.id/storagepmbm/
# Expected: 403 Forbidden (directory listing disabled)
# Jika ada file test.jpg di storage/app/public/:
https://pmbm.mtsn2kotamalang.sch.id/storagepmbm/test.jpg
# Expected: File bisa diakses (200 OK)
```

---

## 📁 Struktur Folder Final

```
pmbm.mtsn2kotamalang.sch.id/
├── storage/
│   ├── app/
│   │   ├── private/              # ✅ PRIVATE - not accessible
│   │   │   ├── dokumen/
│   │   │   ├── bukti_bayar/
│   │   │   └── raport/
│   │   │
│   │   └── public/               # ✅ PUBLIC - via symlink
│   │       ├── photos/
│   │       └── announcements/
│   │
│   ├── framework/
│   └── logs/
│
└── public/
    ├── index.php
    ├── .htaccess
    ├── css/
    ├── js/
    └── storagepmbm/              # ✅ SYMLINK ke ../storage/app/public
```

---

## 🔧 Update Kode Aplikasi (Otomatis)

Karena kita menggunakan helper Laravel `Storage::disk('public')->url($path)`, semua URL akan otomatis menggunakan `/storagepmbm` sesuai konfigurasi.

### Contoh:
```php
// Upload file
$path = $request->file('photo')->store('photos', 'public');

// Get URL
$url = Storage::disk('public')->url($path);
// Output: https://pmbm.mtsn2kotamalang.sch.id/storagepmbm/photos/filename.jpg

// Atau via model accessor
public function getPhotoUrlAttribute()
{
    return $this->photo_path
        ? Storage::disk('public')->url($this->photo_path)
        : null;
}
```

**TIDAK PERLU** hardcode `/storage` di kode!

---

## ⚠️ Troubleshooting

### Issue 1: Symlink Gagal Dibuat

**Error**:
```
symlink(): File exists
```

**Penyebab**: Masih ada folder/file dengan nama `storagepmbm`

**Solusi**:
```bash
# Hapus folder/symlink lama jika ada
rm -rf public/storagepmbm

# Atau rename dulu untuk backup
mv public/storagepmbm public/storagepmbm.old

# Buat symlink baru
php artisan storage:link
```

---

### Issue 2: File Tidak Muncul

**Test akses file**:
```
https://pmbm.mtsn2kotamalang.sch.id/storagepmbm/photos/test.jpg
```

**Jika 404 Not Found**:
```bash
# 1. Check symlink exists
ls -la public/storagepmbm

# 2. Check file exists in storage
ls -la storage/app/public/photos/test.jpg

# 3. Check permissions
chmod -R 755 storage/app/public/
```

---

### Issue 3: URL Masih Menggunakan `/storage`

**Penyebab**: Cache belum di-clear

**Solusi**:
```bash
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

---

## 🔄 Migrasi dari Symlink Lama (Jika Ada)

Jika sebelumnya sudah pakai `public/storage`:

### 1. Hapus symlink lama
```bash
rm public/storage
```

### 2. Buat symlink baru
```bash
php artisan storage:link
```

### 3. Clear cache
```bash
php artisan config:cache
php artisan cache:clear
```

### 4. Test aplikasi
- Upload foto profil
- Check URL foto
- Verify akses file

---

## 📝 Dokumentasi untuk Developer

### Environment Variable (Opsional)

Jika ingin custom nama symlink via .env:

**File: `.env`**
```env
# Custom storage symlink name (default: storagepmbm)
STORAGE_SYMLINK_NAME=storagepmbm
```

**File: `config/filesystems.php`** (jika ingin support ENV):
```php
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL').'/'.env('STORAGE_SYMLINK_NAME', 'storagepmbm'),
    'visibility' => 'public',
],

'links' => [
    public_path(env('STORAGE_SYMLINK_NAME', 'storagepmbm')) => storage_path('app/public'),
],
```

---

## ✅ Testing Checklist

Setelah setup symlink:

- [ ] Symlink exists: `ls -la public/storagepmbm`
- [ ] Symlink correct: `storagepmbm -> ../storage/app/public`
- [ ] Directory listing disabled: `https://pmbm.../storagepmbm/` returns 403
- [ ] File accessible: Upload foto dan check URL
- [ ] Upload works: Test upload foto profil
- [ ] Download works: Test download file
- [ ] No `/storage` in URLs: Check source HTML untuk URL file

---

## 🔒 Security Note

**PENTING**: Pastikan symlink hanya mengarah ke `storage/app/public`, BUKAN `storage/app/private`!

```bash
# ✅ CORRECT
public/storagepmbm -> ../storage/app/public

# ❌ WRONG - NEVER DO THIS!
public/storagepmbm -> ../storage/
public/storagepmbm -> ../storage/app/private/
```

File di `storage/app/private/` harus tetap PRIVATE dan diakses via controller dengan authorization check!

---

## 📞 FAQ

### Q: Kenapa tidak pakai nama `storage` saja?
**A**: Karena di shared hosting, nama `storage` sudah digunakan oleh aplikasi lain, sehingga terjadi konflik "File already exists".

### Q: Apakah semua URL otomatis berubah?
**A**: Ya, karena kita menggunakan `Storage::disk('public')->url()` helper, semua URL akan otomatis menggunakan `/storagepmbm`.

### Q: Apakah perlu update database?
**A**: Tidak perlu. Path file di database tetap sama (contoh: `photos/user.jpg`). Yang berubah hanya public URL-nya.

### Q: Apakah bisa pakai nama lain selain `storagepmbm`?
**A**: Ya, bisa. Edit di `config/filesystems.php`:
- Ubah URL: `'/namaanda'`
- Ubah links: `public_path('namaanda')`

### Q: Bagaimana dengan file yang sudah di-upload sebelumnya?
**A**: File tetap aman di `storage/app/public/`. Hanya symlink yang berubah, sehingga URL berubah dari `/storage/...` menjadi `/storagepmbm/...`.

---

## 🎉 Summary

**Perubahan**:
- ❌ Old: `public/storage` → `storage/app/public`
- ✅ New: `public/storagepmbm` → `storage/app/public`

**URL**:
- ❌ Old: `https://pmbm.../storage/photos/user.jpg`
- ✅ New: `https://pmbm.../storagepmbm/photos/user.jpg`

**Keuntungan**:
- ✅ No conflict dengan aplikasi lain
- ✅ Mudah di-deploy di shared hosting
- ✅ Semua URL otomatis update via helper Laravel
- ✅ Security tetap terjaga

---

**Last Updated**: 19 Januari 2026
**Status**: ✅ Tested & Working
**Compatibility**: Laravel 11/12, Shared Hosting cPanel
