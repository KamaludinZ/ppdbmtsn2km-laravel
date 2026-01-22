# Catatan Khusus untuk Shared Hosting
## MTsN 2 Kota Malang - pmbm.mtsn2kotamalang.sch.id

### Storage Link Issue

Jika muncul error saat `php artisan storage:link`:
```
ERROR  The [public/storage] link already exists.
```

**INI BUKAN MASALAH!** Artinya link sudah ada dari sebelumnya.

#### Verifikasi Storage Link:

```bash
# Check symlink
ls -la public/ | grep storage

# Test baca directory
ls public/storage/

# Jika output normal, storage sudah OK
```

#### Hanya jika benar-benar perlu re-create:

```bash
# Hapus link lama
rm public/storage

# Buat baru
php artisan storage:link

# Atau manual
ln -s $(pwd)/storage/app/public $(pwd)/public/storage
```

### Permission Issues di Shared Hosting

Jika tidak bisa chmod 775:

```bash
# Alternatif permission
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

# Atau hanya folder yang perlu write
chmod 755 storage/logs
chmod 755 storage/framework/sessions
chmod 755 storage/framework/views
chmod 755 storage/framework/cache
chmod 755 storage/app/public
```

### Database Migration Issues

Jika migration gagal dengan "permission denied":

```bash
# Gunakan --force flag
php artisan migrate --force

# Atau run specific migration
php artisan migrate --path=/database/migrations/2026_01_05_062529_create_jalur_pendaftaran_table.php --force
```

### Composer Memory Limit

Jika composer install gagal karena memory:

```bash
# Increase memory limit
php -d memory_limit=512M $(which composer) install --no-dev

# Atau gunakan prestissimo
composer global require hirak/prestissimo
composer install --no-dev --optimize-autoloader
```

### Artisan Command Limitations

Beberapa shared hosting block certain commands:

```bash
# Jika php artisan tidak jalan, gunakan direct PHP
/usr/local/bin/php artisan migrate

# Atau dengan full path
/usr/local/bin/php /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/artisan migrate
```

### Clear Cache Alternative

Jika `php artisan cache:clear` tidak jalan:

```bash
# Manual clear cache
rm -rf storage/framework/cache/data/*
rm -rf storage/framework/views/*.php
rm -rf storage/framework/sessions/*
rm -rf bootstrap/cache/*.php
```

### Cron Job di cPanel

Untuk schedule Laravel:

```
* * * * * /usr/local/bin/php /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/artisan schedule:run >> /dev/null 2>&1
```

### .htaccess untuk Subdomain

File `.htaccess` di root (sudah dibuat):

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>

Options -Indexes

<IfModule mod_headers.c>
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-Content-Type-Options "nosniff"
    Header set X-XSS-Protection "1; mode=block"
</IfModule>
```

### Environment Variables

Jika `.env` tidak terbaca, check:

1. File permission: `chmod 644 .env`
2. Ownership: `chown mtsnkot4:mtsnkot4 .env`
3. Clear config cache: `php artisan config:clear`

### PHP Version

Pastikan PHP 8.2 di cPanel:

1. Login cPanel
2. Software → Select PHP Version
3. Pilih PHP 8.2
4. Check extensions:
   - pdo_pgsql
   - mbstring
   - openssl
   - fileinfo
   - bcmath

### PostgreSQL Connection

Jika database connection refused:

```env
# Try different host
DB_HOST=localhost
# atau
DB_HOST=127.0.0.1
# atau
DB_HOST=postgres.mtsn2kotamalang.sch.id
```

### Debug Mode

Untuk debugging di production (TEMPORARY ONLY):

```env
APP_DEBUG=true
APP_ENV=local
```

**IMPORTANT:** Set kembali ke `APP_DEBUG=false` dan `APP_ENV=production` setelah selesai debug!

### File Upload Issues

Jika upload gagal:

```bash
# Check PHP limits
php -i | grep upload_max
php -i | grep post_max

# Create .user.ini di public/
echo "upload_max_filesize = 10M" > public/.user.ini
echo "post_max_size = 10M" >> public/.user.ini
```

---

## Quick Commands Reference

```bash
# Deploy sequence
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan storage:link  # Skip jika error "already exists"
chmod -R 755 storage/ bootstrap/cache/
php artisan optimize

# Verify
php verify-production.php
php check-storage-link.php

# Clean up
rm verify-production.php
rm check-storage-link.php
rm deploy-ppdb-without-vite.php
```

✅ Deployment di shared hosting memang punya limitasi, tapi semua bisa diatasi dengan workaround di atas!