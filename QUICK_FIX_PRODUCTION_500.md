# Quick Fix untuk Error 500 di Production
## https://pmbm.mtsn2kotamalang.sch.id

## Masalah yang Teridentifikasi

Dari analisa error log, ditemukan masalah utama:

1. **Duplicate route name 'login'** - Ada route yang menggunakan nama yang sama
2. **Cache route yang corrupt** - File cache routing bermasalah
3. **Kemungkinan permission issue** pada folder storage

## Solusi Cepat (3 Langkah)

### Langkah 1: Upload Script Fix

1. Upload file `production_fix.php` ke root directory website Anda (public_html)
2. Akses via browser:
   ```
   https://pmbm.mtsn2kotamalang.sch.id/production_fix.php?secret=fix500error2026
   ```
3. **HAPUS file ini setelah berhasil!**

### Langkah 2: Manual via cPanel

Jika script tidak bisa diakses, lakukan manual via cPanel:

#### A. Hapus Cache Files
Via File Manager, masuk ke:
- `bootstrap/cache/` - hapus semua file kecuali `.gitignore`
- `storage/framework/cache/` - hapus semua file kecuali `.gitignore`
- `storage/framework/views/` - hapus semua file kecuali `.gitignore`
- `storage/framework/sessions/` - hapus semua file kecuali `.gitignore`

#### B. Set Permission
Klik kanan pada folder, pilih "Change Permissions":
- `storage/` dan semua subfolder: **755**
- `bootstrap/cache/`: **755**

### Langkah 3: Clear Cache via Terminal cPanel

Jika cPanel menyediakan Terminal:

```bash
cd public_html
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## Verifikasi

Setelah melakukan langkah di atas:
1. Buka website: https://pmbm.mtsn2kotamalang.sch.id
2. Jika masih error, cek file `.env` pastikan database credential benar
3. Hubungi hosting provider untuk memastikan PostgreSQL aktif

## File Script yang Sudah Disiapkan

1. **production_fix.php** - Script otomatis untuk perbaikan (upload ke server)
2. **quick_fix_500.php** - Alternative script jika yang pertama tidak jalan
3. **diagnose_db.php** - Untuk test koneksi database

## Kontak Support Hosting

Jika masih bermasalah, hubungi hosting dan minta:
1. PostgreSQL connection details yang benar
2. Bantuan untuk set permission folder storage
3. Error log dari server (Apache/Nginx error log)

## PENTING!

- **HAPUS semua script diagnostic setelah selesai** untuk keamanan
- **Jangan aktifkan APP_DEBUG=true di production** kecuali emergency
- **Backup data sebelum melakukan perubahan major**

---
*Script dan panduan ini sudah disesuaikan dengan error yang Anda alami*