# 🚀 Deployment Guide - Production Tanpa Vite

## ✅ Status Aplikasi

Aplikasi **SUDAH SIAP** untuk production tanpa Vite!

### Hasil Pemeriksaan:
- ✅ Semua file CSS dan JS tersedia di `public/`
- ✅ Layout menggunakan asset-loader component
- ✅ Tidak ada referensi @vite yang blocking
- ✅ Database connection berfungsi
- ✅ Storage folder writable

---

## 📁 File yang HARUS Diupload ke Production

### Folder Utama:
```
ppdbmtsn2km-laravel/
├── app/                    ✅ Semua
├── bootstrap/              ✅ Semua
├── config/                 ✅ Semua (termasuk assets.php)
├── database/migrations/    ✅ Semua migrations
├── public/                 ✅ Semua, terutama:
│   ├── css/
│   │   ├── app-production.css     ✅ WAJIB
│   │   ├── tailwind.min.css       ✅ WAJIB
│   │   └── display-fix.css        ✅ Optional
│   ├── js/
│   │   ├── app.js                  ✅ WAJIB
│   │   ├── livewire.js             ✅ WAJIB
│   │   └── countdown-function.js   ✅ WAJIB
│   ├── build/                      ✅ Optional (jika ada)
│   ├── .htaccess                   ✅ WAJIB
│   └── index.php                   ✅ WAJIB
├── resources/views/        ✅ Semua
├── routes/                 ✅ Semua
├── storage/                ✅ Structure only (kosongkan isi)
├── vendor/                 ✅ Atau jalankan composer install
├── .env                    ✅ Sesuaikan untuk production
├── artisan                 ✅ WAJIB
├── composer.json           ✅ WAJIB
└── composer.lock           ✅ WAJIB
```

### File yang TIDAK perlu diupload:
```
❌ node_modules/
❌ .git/
❌ tests/
❌ *.md (documentation)
❌ *.bat, *.sh (scripts)
❌ package.json, package-lock.json
❌ vite.config.js
❌ .env.example
❌ *.zip, *.rar (archives)
```

---

## ⚙️ Konfigurasi .env untuk Production

```env
# Application
APP_NAME="PMBM MTsN 2 Kota Malang"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_SECURE_KEY_HERE
APP_URL=https://ppdb.mtsn2kotamalang.sch.id

# Asset Configuration (PENTING!)
ASSET_MODE=local
USE_VITE_IN_DEV=false

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nama_database_production
DB_USERNAME=username_production
DB_PASSWORD=password_production

# Cache & Session
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

---

## 🔧 Langkah Deployment

### 1️⃣ Persiapan di Local

```bash
# Clear semua cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Optional: Optimize untuk production
php artisan optimize:clear
```

### 2️⃣ Upload File

1. Zip folder aplikasi (exclude node_modules, .git, dll)
2. Upload ke server via FTP/cPanel File Manager
3. Extract di folder target (biasanya public_html atau subdomain folder)

### 3️⃣ Setup di Server Production

```bash
# SSH ke server (jika tersedia)
cd /path/to/application

# Install dependencies
composer install --no-dev --optimize-autoloader

# Set permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Generate key jika belum
php artisan key:generate

# Run migrations
php artisan migrate --force

# Cache untuk performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optional: Seed data awal
php artisan db:seed --class=SettingSeeder --force
```

### 4️⃣ Jika Tidak Ada SSH Access

Buat file `setup-production.php` di root:

```php
<?php
// Temporary setup file - HAPUS SETELAH SELESAI!
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$response = $kernel->call('migrate', ['--force' => true]);
echo "Migration: " . ($response === 0 ? "Success" : "Failed") . "<br>";
$response = $kernel->call('config:cache');
echo "Config Cache: " . ($response === 0 ? "Success" : "Failed") . "<br>";
$response = $kernel->call('route:cache');
echo "Route Cache: " . ($response === 0 ? "Success" : "Failed") . "<br>";
$response = $kernel->call('view:cache');
echo "View Cache: " . ($response === 0 ? "Success" : "Failed") . "<br>";
echo "<br><strong>Setup completed! DELETE THIS FILE NOW!</strong>";
```

Akses via browser: `https://yourdomain.com/setup-production.php`

**⚠️ HAPUS FILE INI SEGERA SETELAH SELESAI!**

---

## 🔍 Testing & Verifikasi

### Checklist Verifikasi:
- [ ] Homepage tampil dengan CSS yang benar
- [ ] Dapat login sebagai admin
- [ ] Dashboard loading dengan sempurna
- [ ] Form pendaftaran berfungsi
- [ ] Upload file berfungsi
- [ ] Livewire components responsive
- [ ] Tidak ada error di browser console
- [ ] Tidak ada 404 untuk assets

### Debug Jika Ada Masalah:

1. **White screen/500 error:**
   ```bash
   # Check Laravel log
   tail -f storage/logs/laravel.log

   # Check permissions
   ls -la storage/
   ls -la bootstrap/cache/
   ```

2. **CSS tidak loading:**
   - Periksa apakah file ada di `public/css/`
   - Check browser console untuk 404 errors
   - Pastikan `.htaccess` benar

3. **JavaScript error:**
   - Check apakah Alpine.js dan Livewire loaded
   - Verifikasi `countdown-function.js` ada

---

## 📝 File .htaccess untuk Apache

Pastikan file `public/.htaccess` berisi:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
</IfModule>

# Compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/json
</IfModule>

# Cache Static Assets
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>
```

---

## 🛡️ Security Checklist

- [ ] `.env` file tidak bisa diakses dari browser
- [ ] `APP_DEBUG=false` di production
- [ ] Database credentials aman
- [ ] SSL/HTTPS aktif
- [ ] File permissions benar (755 untuk folders, 644 untuk files)
- [ ] Hapus file-file test/debug dari production

---

## 📱 Maintenance Mode

Jika perlu maintenance:

```bash
# Enable maintenance
php artisan down --message="Sedang dalam perbaikan" --retry=60

# Disable maintenance
php artisan up
```

Atau buat file `storage/framework/down` untuk enable maintenance mode.

---

## ✨ Tips Optimasi

1. **Enable OPcache** di PHP untuk performance
2. **Gunakan CDN** untuk assets jika memungkinkan
3. **Compress images** sebelum upload
4. **Monitor logs** regularly: `storage/logs/laravel.log`
5. **Backup database** secara berkala

---

## 📞 Troubleshooting Cepat

### Problem: "Class not found"
```bash
composer dump-autoload
```

### Problem: "Permission denied"
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache  # Linux
```

### Problem: "419 Page Expired"
```bash
php artisan cache:clear
php artisan config:clear
```

### Problem: Assets tidak ter-update
```bash
# Tambahkan version query string
asset('css/app.css?v=' . time())
```

---

## ✅ Final Checklist

Sebelum go-live, pastikan:

- [ ] Semua file terupload dengan benar
- [ ] Database termigrasi dan ada data initial
- [ ] Admin account sudah dibuat
- [ ] Email configuration (jika digunakan)
- [ ] WhatsApp API configured (jika digunakan)
- [ ] SSL certificate installed
- [ ] Backup strategy ready
- [ ] Monitoring tools setup

---

**🎉 Selamat! Aplikasi Anda siap untuk production tanpa Vite!**

Jika ada masalah, check:
1. Laravel logs: `storage/logs/laravel.log`
2. Server error logs
3. Browser console untuk client-side errors

---

*Last updated: 18 Januari 2026*