# Production Assets Checklist - PPDB MTsN 2

## Pre-Deployment Checklist

### 1. Build Production Assets ✅

```bash
# Build with Vite
npm run build

# Verify build output
ls -la public/build/

# Expected output:
# public/build/
# ├── manifest.json
# └── assets/
#     ├── app-[hash].css
#     └── app-[hash].js
```

**Files Created**:
- ✅ `public/build/manifest.json` - Asset manifest
- ✅ `public/build/assets/app-*.css` - Compiled CSS (~116 KB)
- ✅ `public/build/assets/app-*.js` - Compiled JS (~37 KB)

---

### 2. Static Assets Fallback ✅

Jika Vite build tidak digunakan di production, assets fallback sudah tersedia:

**CSS Files**:
```
public/css/
├── app-production.css              # Production CSS (manual)
├── app-production-optimized.css    # Optimized version
└── app.css                         # Development CSS
```

**JS Files**:
```
public/js/
├── app-production-optimized.js     # Optimized JS
├── app.js                          # Main app JS
└── livewire.js                     # Livewire standalone
```

**Asset Loader**: `resources/views/components/asset-loader.blade.php`
- ✅ Auto-detects environment (local vs production)
- ✅ Loads Vite build jika tersedia
- ✅ Fallback ke static assets
- ✅ Fallback ke CDN jika semua gagal

---

### 3. Security Configuration ✅

#### public/.htaccess
```apache
✅ Disable directory browsing
✅ Block access to sensitive files (.env, .git, composer.json)
✅ Security headers (X-Frame-Options, CSP, etc.)
✅ SQL injection protection
✅ Cache control for assets
✅ Gzip compression
✅ PHP security settings
```

#### Root .htaccess
File: `.htaccess.root`
```apache
✅ Redirect all requests to public/ folder
✅ Block access to storage, vendor, bootstrap
✅ Prevent directory listing
```

---

### 4. Storage Security ✅

#### Struktur Storage:
```
storage/
├── app/
│   ├── private/              ✅ NOT accessible from web
│   │   ├── dokumen/         # KTP, KK, Akta, Ijazah
│   │   ├── bukti_bayar/     # Payment proofs
│   │   └── raport/          # Report cards
│   │
│   └── public/              ✅ Accessible via /storagepmbm symlink
│       ├── photos/          # Profile photos (optimized)
│       └── announcements/   # Public announcements
│
├── framework/               ✅ Framework cache (not accessible)
│   ├── cache/
│   ├── sessions/
│   └── views/
│
└── logs/                    ✅ Log files (not accessible)
    └── laravel.log
```

#### Controller untuk Private Files:
File: `app/Http/Controllers/SecureFileController.php`
```php
✅ downloadDokumen() - Download with authorization
✅ viewDokumen() - View/preview with authorization
✅ downloadBuktiBayar() - Download payment proof
✅ Authorization checks for all methods
```

**Routes** (add to routes/web.php):
```php
Route::middleware(['auth'])->group(function () {
    Route::get('/download/dokumen/{id}', [SecureFileController::class, 'downloadDokumen'])
        ->name('download.dokumen');

    Route::get('/view/dokumen/{id}', [SecureFileController::class, 'viewDokumen'])
        ->name('view.dokumen');

    Route::get('/download/bukti-bayar/{pendaftar}/{type}', [SecureFileController::class, 'downloadBuktiBayar'])
        ->name('download.bukti-bayar');
});
```

---

### 5. Environment Configuration ✅

#### .env for Production:
```env
APP_NAME="PPDB MTsN 2 Kota Malang"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://pmbm.mtsn2kotamalang.sch.id

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_strong_password

# Session & Cache
SESSION_DRIVER=database
CACHE_DRIVER=database
QUEUE_CONNECTION=database

# Filesystem
FILESYSTEM_DISK=local

# Mail (configure as needed)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@mtsn2kotamalang.sch.id
MAIL_FROM_NAME="${APP_NAME}"
```

---

### 6. Optimization Commands ✅

```bash
# 1. Install dependencies (production)
composer install --optimize-autoloader --no-dev

# 2. Clear all caches
php artisan optimize:clear

# 3. Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Optimize application
php artisan optimize

# 5. Create storage symlink
php artisan storage:link
```

---

### 7. File Permissions ✅

```bash
# All files
chmod -R 755 pmbm.mtsn2kotamalang.sch.id/

# Storage (writable)
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# .env (protect)
chmod 644 .env

# Shell scripts (if any)
chmod +x setup-production.sh
```

---

### 8. Testing Production Setup

#### Test 1: Homepage
```
URL: https://pmbm.mtsn2kotamalang.sch.id/
Expected: Homepage loads correctly
```

#### Test 2: Assets Loading
```
URL: https://pmbm.mtsn2kotamalang.sch.id/build/assets/app-*.css
Expected: CSS file loads (200 OK)

URL: https://pmbm.mtsn2kotamalang.sch.id/build/assets/app-*.js
Expected: JS file loads (200 OK)
```

#### Test 3: Security
```bash
# Should return 403 or 404
https://pmbm.mtsn2kotamalang.sch.id/.env
https://pmbm.mtsn2kotamalang.sch.id/../storage/
https://pmbm.mtsn2kotamalang.sch.id/../vendor/
https://pmbm.mtsn2kotamalang.sch.id/composer.json
```

#### Test 4: Storage Symlink
```bash
# Check symlink exists
ls -la public/storagepmbm

# Should show: storagepmbm -> ../storage/app/public
```

#### Test 5: Private File Access
```
# Login as pendaftar
# Try to download own document: ✅ Should work
# Try to download other's document: ❌ Should fail (403)

# Login as admin
# Try to download any document: ✅ Should work
```

---

### 9. Performance Verification

#### Check Asset Sizes:
```bash
# CSS size (should be ~100-120 KB after minification)
ls -lh public/build/assets/*.css

# JS size (should be ~35-40 KB after minification)
ls -lh public/build/assets/*.js
```

#### Check Compression:
```bash
# Test Gzip (if curl available)
curl -I -H "Accept-Encoding: gzip" https://pmbm.mtsn2kotamalang.sch.id/build/assets/app-*.css

# Should return: Content-Encoding: gzip
```

#### Check Caching:
```bash
# Test cache headers
curl -I https://pmbm.mtsn2kotamalang.sch.id/build/assets/app-*.css

# Should return: Cache-Control: max-age=...
```

---

### 10. Monitoring & Logs

#### Check Application Logs:
```bash
# Laravel logs
tail -f storage/logs/laravel.log

# PHP errors (if configured)
tail -f storage/logs/php_errors.log

# Web server logs
# Nginx: /var/log/nginx/error.log
# Apache: /var/log/apache2/error.log
# cPanel: ~/logs/error_log
```

#### Monitor Disk Space:
```bash
# Check disk usage
df -h

# Check storage folder size
du -sh storage/
```

---

## Production Deployment Steps

### Step 1: Prepare Local
```bash
✅ npm run build
✅ composer install --no-dev --optimize-autoloader
✅ php artisan optimize:clear
✅ Test locally
```

### Step 2: Upload to Server
```bash
✅ Upload all files to pmbm.mtsn2kotamalang.sch.id/
✅ Exclude: node_modules/, .git/, *.log
✅ Upload .htaccess.root as .htaccess in root
```

### Step 3: Configure Server
```bash
✅ Create .env from .env.example
✅ Set APP_ENV=production, APP_DEBUG=false
✅ Configure database credentials
✅ Run: php artisan key:generate
```

### Step 4: Setup Files
```bash
✅ chmod -R 755 .
✅ chmod -R 775 storage/ bootstrap/cache/
✅ chmod 644 .env
✅ php artisan storage:link
```

### Step 5: Run Migrations
```bash
✅ php artisan migrate --force
✅ php artisan db:seed --force (if needed)
```

### Step 6: Optimize
```bash
✅ php artisan optimize
✅ php artisan config:cache
✅ php artisan route:cache
✅ php artisan view:cache
```

### Step 7: Test
```bash
✅ Test homepage
✅ Test login
✅ Test file upload
✅ Test file download
✅ Test export
✅ Check logs
```

---

## Common Issues & Solutions

### Issue 1: Assets Not Loading
**Solution**:
```bash
# Check if build files exist
ls public/build/manifest.json

# Rebuild if missing
npm run build

# Check asset-loader.blade.php
# Should auto-fallback to static assets
```

### Issue 2: 500 Internal Server Error
**Solution**:
```bash
# Check logs
tail -f storage/logs/laravel.log

# Check permissions
chmod -R 775 storage/ bootstrap/cache/

# Clear & recache
php artisan optimize:clear
php artisan optimize
```

### Issue 3: Storage Symlink Broken
**Solution**:
```bash
# Remove old symlink
rm public/storage

# Create new symlink
php artisan storage:link

# Or manual:
ln -s ../storage/app/public public/storage
```

### Issue 4: Private Files Accessible
**Solution**:
```bash
# Check storage/ is OUTSIDE public/
# Check .htaccess rules
# Use SecureFileController for downloads
```

---

## Final Checklist

- ✅ Assets built with `npm run build`
- ✅ Dependencies installed with `composer install --no-dev`
- ✅ .env configured for production
- ✅ APP_DEBUG=false
- ✅ APP_ENV=production
- ✅ Database migrated
- ✅ Storage symlink created
- ✅ File permissions correct
- ✅ .htaccess files in place
- ✅ Security headers configured
- ✅ Private files not accessible from web
- ✅ Application optimized & cached
- ✅ SSL certificate active (HTTPS)
- ✅ Backup strategy in place
- ✅ Monitoring setup
- ✅ All features tested

---

**Status**: ✅ READY FOR PRODUCTION

**Last Updated**: 19 Januari 2026
**Tested Environment**: Shared Hosting cPanel
