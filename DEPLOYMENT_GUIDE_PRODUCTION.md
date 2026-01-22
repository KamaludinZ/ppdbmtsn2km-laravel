# Panduan Deployment PPDB MTsN 2 Kota Malang ke Production
# Tanpa Vite - Menggunakan Pre-compiled Assets

## 🎯 Overview
Aplikasi PPDB ini menggunakan Laravel 11 dengan Vite untuk development, namun di production akan berjalan TANPA Vite menggunakan assets yang sudah di-compile.

## 📋 Persyaratan Production Server
- PHP 8.2 atau lebih tinggi
- PostgreSQL 13 atau lebih tinggi
- Composer (untuk install dependencies)
- Shared hosting dengan akses SSH (opsional tapi recommended)
- Subdomain: https://pmbm.mtsn2kotamalang.sch.id/

## 🔧 Step 1: Persiapan di Local Development

### 1.1 Build Production Assets
```bash
# Install dependencies
npm install

# Build untuk production
npm run build

# Hasil build akan ada di:
# public/build/
# ├── manifest.json
# └── assets/
#     ├── app-DPXrAvEv.css (Tailwind CSS)
#     └── app-C8iUzNnm.js (Alpine + App JS)
```

### 1.2 Copy Manifest ke Root Build
```bash
# Windows PowerShell
Copy-Item 'public\build\.vite\manifest.json' -Destination 'public\build\manifest.json'

# atau Linux/Mac
cp public/build/.vite/manifest.json public/build/manifest.json
```

### 1.3 Jalankan Script Deployment
```bash
php deploy-ppdb-without-vite.php
```

Script ini akan:
- ✅ Check build assets
- ✅ Fix blade templates (remove @vite)
- ✅ Create .env.production
- ✅ Create .htaccess
- ✅ Generate deployment checklist

### 1.4 Update .env.production
Edit file `.env.production` dengan credentials production:

```env
APP_NAME="PPDB MTsN 2 Kota Malang"
APP_ENV=production
APP_KEY=base64:YOUR_KEY_HERE
APP_DEBUG=false
APP_URL=https://pmbm.mtsn2kotamalang.sch.id

DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=nama_database_production
DB_USERNAME=user_database_production
DB_PASSWORD=password_database_production

# Cache & Session
SESSION_DRIVER=database
CACHE_STORE=database
```

## 📦 Step 2: Upload ke Production Server

### 2.1 Struktur Folder yang HARUS di-upload

```
pmbm.mtsn2kotamalang.sch.id/
├── app/                  ✅ Wajib (Business logic)
├── bootstrap/            ✅ Wajib (Framework bootstrap)
├── config/               ✅ Wajib (Konfigurasi)
├── database/             ✅ Wajib (Migrations & Seeders)
├── public/               ✅ Wajib (TERMASUK public/build/)
│   ├── build/           ✅ PENTING! (Compiled assets)
│   │   ├── manifest.json
│   │   └── assets/
│   ├── css/             ✅ (Custom CSS jika ada)
│   ├── js/              ✅ (Custom JS jika ada)
│   └── index.php        ✅ (Entry point)
├── resources/            ✅ Wajib (Views & raw assets)
│   └── views/           ✅ (100+ blade templates)
├── routes/               ✅ Wajib (Route definitions)
├── storage/              ✅ Wajib (Struktur folder saja)
├── vendor/               ⚠️ Optional (bisa install via composer)
├── .env                  ✅ (Rename dari .env.production)
├── .htaccess            ✅ (Untuk subdomain redirect)
├── composer.json        ✅ (PHP dependencies)
├── composer.lock        ✅ (Lock file)
└── artisan              ✅ (Laravel CLI)
```

### 2.2 Folder yang TIDAK PERLU di-upload

```
❌ node_modules/         (56MB+ dev dependencies)
❌ package.json          (Node dependencies)
❌ package-lock.json     (Node lock file)
❌ vite.config.js        (Vite config)
❌ tailwind.config.js    (Tailwind config)
❌ postcss.config.js     (PostCSS config)
❌ .git/                 (Version control)
❌ tests/                (Test files)
❌ .env.local            (Local environment)
❌ *.log                 (Log files)
❌ Documentation files (*.md)
❌ Debug scripts (*.php di public/)
```

### 2.3 Method Upload

#### Option A: Via FTP/cPanel File Manager
1. Compress semua folder (kecuali yang tidak perlu)
2. Upload file .zip ke server
3. Extract di document root subdomain

#### Option B: Via Git (jika tersedia)
```bash
# Di server
git clone [repository-url]
cd ppdb-mtsn2km-laravel
git checkout production
```

## ⚙️ Step 3: Setup di Production Server

### 3.1 Install PHP Dependencies
```bash
# Jika vendor belum di-upload
composer install --optimize-autoloader --no-dev

# Jika vendor sudah di-upload, skip step ini
```

### 3.2 Setup Environment
```bash
# Rename environment file
mv .env.production .env

# Generate application key
php artisan key:generate

# Set permissions
chmod 600 .env
```

### 3.3 Setup Database
```bash
# Run migrations
php artisan migrate --force

# Run seeders (jika diperlukan)
php artisan db:seed --class=SettingSeeder --force
php artisan db:seed --class=JalurPendaftaranSeeder --force
php artisan db:seed --class=TimelinePendaftaranSeeder --force
```

### 3.4 Create Storage Symlink
```bash
# Create symlink untuk storage
php artisan storage:link
```

### 3.5 Set Folder Permissions
```bash
# Storage writable
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# Create required directories
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs
mkdir -p storage/app/public
```

### 3.6 Clear & Optimize
```bash
# Clear old cache
php artisan view:clear
php artisan route:clear
php artisan config:clear

# Create new cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

## 🔍 Step 4: Verifikasi

### 4.1 Test Basic
```bash
# Check Laravel version
php artisan --version

# Check database connection
php artisan tinker
>>> DB::connection()->getPdo();
>>> exit
```

### 4.2 Test Website
1. Buka https://pmbm.mtsn2kotamalang.sch.id/
2. Check console browser (F12) untuk error
3. Test halaman:
   - Homepage
   - Login page
   - Registration page
   - Admin dashboard (setelah login)

### 4.3 Checklist Final
- ✅ Homepage tampil normal dengan CSS
- ✅ JavaScript (Alpine.js) berfungsi
- ✅ Livewire components berfungsi
- ✅ Countdown timer berfungsi
- ✅ Form pendaftaran berfungsi
- ✅ Upload dokumen berfungsi
- ✅ Login admin berfungsi

## 🐛 Troubleshooting

### Problem: 500 Internal Server Error
```bash
# Check error log
tail -f storage/logs/laravel.log

# Check PHP error
php -v
php -m | grep pgsql

# Fix permissions
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

### Problem: Vite manifest not found
**Solution:**
1. Pastikan `public/build/manifest.json` exists
2. Check path di `config/assets.php`
3. Clear view cache: `php artisan view:clear`

### Problem: Style CSS berantakan
**Solution:**
1. Clear browser cache (Ctrl+F5)
2. Check file exists: `public/build/assets/app-*.css`
3. Check console untuk 404 errors

### Problem: Alpine.js not defined
**Solution:**
Alpine.js sudah included dalam build. Check:
1. File `public/build/assets/app-*.js` exists
2. Tidak ada JavaScript errors di console

### Problem: Database connection refused
**Solution:**
1. Check `.env` credentials
2. Test koneksi: `php artisan tinker` lalu `DB::connection()->getPdo()`
3. Check PostgreSQL service running

### Problem: Livewire components not working
**Solution:**
1. Check `@livewireScripts` ada di layout
2. Clear cache: `php artisan optimize:clear`
3. Re-publish Livewire assets: `php artisan livewire:publish --assets`

## 📝 Maintenance Mode

### Aktifkan Maintenance Mode
```bash
php artisan down --render="errors::503"
```

### Disable Maintenance Mode
```bash
php artisan up
```

## 🔄 Update Deployment

Untuk update aplikasi di production:

1. **Di Local:**
```bash
# Pull latest changes
git pull origin main

# Build assets
npm run build

# Copy manifest
Copy-Item 'public\build\.vite\manifest.json' -Destination 'public\build\manifest.json'
```

2. **Upload Changes:**
- Upload files yang berubah
- Upload folder `public/build/` (wajib jika ada perubahan CSS/JS)

3. **Di Production:**
```bash
# Update dependencies jika ada perubahan
composer install --optimize-autoloader --no-dev

# Run migrations jika ada
php artisan migrate --force

# Clear cache
php artisan optimize:clear

# Recreate cache
php artisan optimize
```

## 📞 Support

Jika ada masalah saat deployment:
1. Check dokumentasi ini
2. Check file TROUBLESHOOTING_500_ERROR.md
3. Check logs di `storage/logs/laravel.log`

## ✅ Kesimpulan

Aplikasi PPDB ini sudah siap untuk deployment ke production tanpa memerlukan Node.js atau Vite. Semua assets sudah di-compile dan aplikasi akan berjalan dengan PHP + PostgreSQL saja.

**Key Points:**
- ✅ Tidak perlu Node.js di production
- ✅ Assets sudah pre-compiled
- ✅ Support shared hosting
- ✅ Optimized untuk production

---

*Last Updated: Januari 2025*
*Deployment Target: https://pmbm.mtsn2kotamalang.sch.id/*