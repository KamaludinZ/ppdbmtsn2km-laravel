# Quick Setup: Storage Symlink (storagepmbm)

## 🚀 Quick Commands

### 1. Via Artisan (Recommended)
```bash
cd pmbm.mtsn2kotamalang.sch.id/
php artisan storage:link
```

### 2. Manual via SSH
```bash
cd pmbm.mtsn2kotamalang.sch.id/public/
ln -s ../storage/app/public storagepmbm
```

### 3. Verify
```bash
ls -la public/storagepmbm
# Expected: storagepmbm -> ../storage/app/public
```

---

## ⚠️ Jika Ada Error "File already exists"

```bash
# Hapus symlink/folder lama
rm -rf public/storagepmbm

# Atau backup dulu
mv public/storagepmbm public/storagepmbm.backup

# Buat symlink baru
php artisan storage:link
```

---

## ✅ Test Symlink Works

```bash
# Test 1: Check symlink
ls -la public/storagepmbm

# Test 2: Test via browser (directory listing disabled)
https://pmbm.mtsn2kotamalang.sch.id/storagepmbm/
# Expected: 403 Forbidden

# Test 3: Upload & test file (jika ada file test.jpg di storage/app/public/)
https://pmbm.mtsn2kotamalang.sch.id/storagepmbm/test.jpg
# Expected: 200 OK (file tampil)
```

---

## 📝 Why "storagepmbm" Instead of "storage"?

Karena di shared hosting, nama `storage` sudah digunakan oleh aplikasi lain, sehingga terjadi konflik:
```
Error: File already exists: public/storage
```

Solusi: Menggunakan nama unik `storagepmbm` untuk aplikasi PPDB ini.

---

## 🔧 Konfigurasi Sudah Diubah

File yang sudah di-update:
- ✅ `config/filesystems.php` → URL: `/storagepmbm`
- ✅ `config/filesystems.php` → Links: `public/storagepmbm`

URL otomatis akan menggunakan `/storagepmbm`:
```php
Storage::disk('public')->url('photos/user.jpg')
// Output: /storagepmbm/photos/user.jpg
```

---

## 📚 Documentation

Lihat dokumentasi lengkap di:
- **SYMLINK_SOLUTION.md** - Solusi lengkap symlink
- **SHARED_HOSTING_DEPLOYMENT_GUIDE.md** - Panduan deployment

---

**Quick Reference** | Last Updated: 19 Januari 2026
