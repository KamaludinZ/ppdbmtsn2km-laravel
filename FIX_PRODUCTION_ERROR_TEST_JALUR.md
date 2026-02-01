# ✅ FIX ERROR: test_jalur.php Not Found

## Error yang Terjadi
```
ErrorException
require(/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/routes/test_jalur.php):
Failed to open stream: No such file or directory
at routes/web.php:542
```

## Penyebab
File `routes/web.php` di local development memiliki baris:
```php
require __DIR__ . '/test_jalur.php';
```

File `test_jalur.php` adalah file testing yang hanya ada di development, tidak perlu di production.

## Solusi
Baris tersebut sudah di-comment pada `routes/web.php` line 542:
```php
// require __DIR__ . '/test_jalur.php'; // Disabled for production
```

## Cara Upload Fix ke Production

### Opsi A: Via Git (Quick)
```bash
# Di local (Windows)
cd C:\ppdbmtsn2km-laravel
git add routes/web.php
git commit -m "Fix: disable test_jalur.php require for production"
git push origin main

# Di server
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
git pull origin main
```

### Opsi B: Via FTP/SFTP
1. Upload file `routes/web.php` dari local ke server
2. Path: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/routes/web.php`

### Opsi C: Edit Langsung di Server (Quickest)
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
nano routes/web.php
# Cari baris 542, tambahkan // di depan:
# // require __DIR__ . '/test_jalur.php';
# Save: Ctrl+O, Enter, Exit: Ctrl+X
```

## Setelah Fix

Jalankan migration lagi:
```bash
php artisan migrate
```

Seharusnya sekarang sudah tidak ada error!

## Status
✅ **FIXED** - File routes/web.php sudah diperbaiki
