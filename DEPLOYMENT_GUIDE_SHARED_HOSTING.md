# PANDUAN DEPLOYMENT KE SHARED HOSTING

**Aplikasi:** PPDB MTsN 2 Kota Malang
**Target:** Shared Hosting (Subdomain)
**Tanggal:** 16 Januari 2026

---

## 📋 DAFTAR ISI

1. [Persiapan Sebelum Deploy](#persiapan-sebelum-deploy)
2. [Requirements Shared Hosting](#requirements-shared-hosting)
3. [Step-by-Step Deployment](#step-by-step-deployment)
4. [Konfigurasi Environment](#konfigurasi-environment)
5. [Database Setup](#database-setup)
6. [File Permissions](#file-permissions)
7. [Optimisasi untuk Shared Hosting](#optimisasi-untuk-shared-hosting)
8. [Security Checklist](#security-checklist)
9. [Troubleshooting](#troubleshooting)
10. [Maintenance & Backup](#maintenance--backup)

---

## ⚠️ PERSIAPAN SEBELUM DEPLOY

### 1. Checklist Pre-Deployment

#### Development Environment:
- [ ] Semua critical fixes sudah implemented
- [ ] Migrations tested di development
- [ ] Routes tested dengan semua roles
- [ ] Assets compiled (`npm run build`)
- [ ] Database backup created
- [ ] .env.example updated

#### Local Testing:
```bash
# Run tests
php artisan test

# Check for errors
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Compile production assets
npm run build

# Test production mode locally
APP_DEBUG=false APP_ENV=production php artisan serve
```

### 2. Backup Everything

```bash
# Backup database
php artisan db:backup  # or manual PostgreSQL backup
pg_dump -U postgres postgresppdb > backup_$(date +%Y%m%d).sql

# Backup files
zip -r ppdb_backup_$(date +%Y%m%d).zip \
    app/ config/ database/ public/ resources/ routes/ storage/ \
    composer.json composer.lock package.json .env
```

---

## 🖥️ REQUIREMENTS SHARED HOSTING

### Minimum Requirements:

1. **PHP Version:** 8.2 or higher
2. **PHP Extensions:**
   - OpenSSL
   - PDO
   - Mbstring
   - Tokenizer
   - XML
   - Ctype
   - JSON
   - BCMath
   - Fileinfo
   - GD atau Imagick (untuk image processing)
   - PostgreSQL (pgsql, pdo_pgsql)

3. **Database:** PostgreSQL 12+ dengan akses remote

4. **Disk Space:** Minimum 500MB (recommended 1GB+)

5. **Memory:** Minimum 256MB PHP memory_limit (recommended 512MB)

6. **Composer:** Tersedia di server atau lokal untuk prepare files

7. **Node.js:** Untuk compile assets (bisa di lokal sebelum upload)

### Check Server Compatibility:

Upload file `check_requirements.php` ke server:

```php
<?php
echo "<h2>PHP Version</h2>";
echo phpversion();

echo "<h2>Required Extensions</h2>";
$extensions = ['openssl', 'pdo', 'mbstring', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath', 'fileinfo', 'gd', 'pgsql'];

foreach ($extensions as $ext) {
    echo $ext . ': ' . (extension_loaded($ext) ? '✓ Installed' : '✗ Missing') . '<br>';
}

echo "<h2>Memory Limit</h2>";
echo ini_get('memory_limit');

echo "<h2>Max Upload Size</h2>";
echo ini_get('upload_max_filesize');

echo "<h2>Max Post Size</h2>";
echo ini_get('post_max_size');

echo "<h2>Execution Time</h2>";
echo ini_get('max_execution_time');
?>
```

---

## 🚀 STEP-BY-STEP DEPLOYMENT

### Step 1: Prepare Application Locally

```bash
# 1. Install production dependencies only
composer install --no-dev --optimize-autoloader

# 2. Compile production assets
npm install
npm run build

# 3. Clear & cache configs
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Create zip archive (exclude unnecessary files)
zip -r ppdb_production.zip \
    app/ \
    bootstrap/ \
    config/ \
    database/ \
    public/ \
    resources/ \
    routes/ \
    storage/ \
    vendor/ \
    artisan \
    composer.json \
    composer.lock \
    -x "*.git*" -x "*node_modules*" -x "*.env*" -x "*tests*"
```

### Step 2: Upload ke Shared Hosting

#### Via cPanel File Manager:
1. Login ke cPanel
2. Buka File Manager
3. Navigate ke subdomain directory (e.g., `/home/username/ppdb.mtsn2km.sch.id/`)
4. Upload `ppdb_production.zip`
5. Extract zip file
6. Delete zip file setelah extract

#### Via FTP/SFTP:
```bash
# Upload menggunakan lftp atau FileZilla
# Target directory: /home/username/ppdb.mtsn2km.sch.id/
```

### Step 3: Setup Directory Structure di Shared Hosting

**Standard Laravel di Shared Hosting:**

```
/home/username/ppdb.mtsn2km.sch.id/  (subdomain root)
│
├── ppdb-app/  (Laravel application root - PRIVATE)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   └── artisan
│
└── public_html/  (Public web root - DOCUMENT ROOT)
    ├── css/
    ├── js/
    ├── build/
    ├── images/
    ├── .htaccess
    └── index.php (modified)
```

**Setup Commands:**

```bash
# Via SSH (if available)
cd /home/username/ppdb.mtsn2km.sch.id/

# Create directory structure
mkdir -p ppdb-app public_html

# Move Laravel files to ppdb-app
mv app bootstrap config database resources routes storage vendor artisan composer.* ppdb-app/

# Move public files to public_html
mv public/* public_html/
mv public/.htaccess public_html/
```

### Step 4: Modify public_html/index.php

**File:** `public_html/index.php`

```php
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../ppdb-app/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../ppdb-app/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../ppdb-app/bootstrap/app.php')
    ->handleRequest(Request::capture());
```

### Step 5: Configure .htaccess

**File:** `public_html/.htaccess`

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

# Disable directory browsing
Options -Indexes

# Security Headers
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
    Header set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# Compress files
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/json
</IfModule>

# Cache static files
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/pdf "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType application/x-javascript "access plus 1 month"
    ExpiresByType image/x-icon "access plus 1 year"
</IfModule>
```

---

## ⚙️ KONFIGURASI ENVIRONMENT

### Step 6: Create .env File

**Location:** `/home/username/ppdb.mtsn2km.sch.id/ppdb-app/.env`

```env
APP_NAME="PPDB MTsN 2 Kota Malang"
APP_ENV=production
APP_KEY=  # Generate dengan: php artisan key:generate
APP_DEBUG=false
APP_URL=https://ppdb.mtsn2km.sch.id  # Sesuaikan dengan subdomain Anda

APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=id_ID
APP_TIMEZONE=Asia/Jakarta

APP_MAINTENANCE_DRIVER=file

# IMPORTANT: Set to 16 for better security in production
BCRYPT_ROUNDS=12

# Logging
LOG_CHANNEL=daily
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error
LOG_DAILY_DAYS=14

# Database - Sesuaikan dengan credentials dari hosting
DB_CONNECTION=pgsql
DB_HOST=localhost  # atau IP PostgreSQL server
DB_PORT=5432
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_strong_password

# Session - File-based untuk shared hosting
SESSION_DRIVER=file
SESSION_LIFETIME=30  # 30 minutes untuk security
SESSION_ENCRYPT=true
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local

# Queue - Database queue untuk shared hosting
QUEUE_CONNECTION=database

# Cache - File-based untuk shared hosting
CACHE_STORE=file
CACHE_PREFIX=ppdb_cache_

# Email Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com  # Sesuaikan dengan SMTP hosting
MAIL_PORT=587
MAIL_USERNAME=your_email@example.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@mtsn2km.sch.id"
MAIL_FROM_NAME="${APP_NAME}"

# WhatsApp Gateway (jika digunakan)
WHATSAPP_ACCESS_TOKEN=your_api_token_here
WHATSAPP_PHONE_NUMBER_ID=your_phone_number_id

# AWS (jika tidak digunakan, biarkan kosong)
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
```

### Generate APP_KEY:

```bash
# Via SSH
cd /home/username/ppdb.mtsn2km.sch.id/ppdb-app
php artisan key:generate

# Via local then copy
php artisan key:generate --show
# Copy output ke .env file di server
```

---

## 💾 DATABASE SETUP

### Step 7: Create Database di Hosting

#### Via cPanel:
1. Login ke cPanel
2. Buka "PostgreSQL Databases"
3. Create new database: `username_ppdb`
4. Create user: `username_ppdb_user`
5. Set strong password
6. Grant ALL PRIVILEGES ke user

#### Via phpPgAdmin:
1. Login phpPgAdmin
2. Create database
3. Create user
4. Grant permissions

### Step 8: Import Database

#### Option 1: Via phpPgAdmin
1. Open database
2. Click "SQL"
3. Upload atau paste SQL dump
4. Execute

#### Option 2: Via Command Line (if SSH available)
```bash
psql -h localhost -U username_ppdb_user -d username_ppdb < backup_20260116.sql
```

#### Option 3: Fresh Migration
```bash
cd /home/username/ppdb.mtsn2km.sch.id/ppdb-app

# Run migrations
php artisan migrate --force

# Run seeders (if any)
php artisan db:seed --force
```

---

## 🔒 FILE PERMISSIONS

### Step 9: Set Correct Permissions

```bash
cd /home/username/ppdb.mtsn2km.sch.id/ppdb-app

# Directories: 755
find . -type d -exec chmod 755 {} \;

# Files: 644
find . -type f -exec chmod 644 {} \;

# Storage & Bootstrap Cache: 775 (writable)
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# Artisan: executable
chmod 755 artisan

# Public directory
cd ../public_html
chmod -R 755 .
```

### Verify Permissions:
```bash
ls -la storage/
# Should show: drwxrwxr-x (775)

ls -la bootstrap/cache/
# Should show: drwxrwxr-x (775)
```

---

## ⚡ OPTIMISASI UNTUK SHARED HOSTING

### Step 10: Optimize Application

```bash
cd /home/username/ppdb.mtsn2km.sch.id/ppdb-app

# Clear all caches first
php artisan optimize:clear

# Cache configs, routes, views
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize --no-dev
```

### Step 11: Performance Tuning

#### a. OPcache Configuration (if accessible via php.ini)

Create `.user.ini` in ppdb-app directory:

```ini
; OPcache settings
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
opcache.fast_shutdown=1

; File upload limits
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 60
max_input_time = 60
memory_limit = 512M
```

#### b. Laravel-Specific Optimizations

**File:** `ppdb-app/config/app.php`

```php
'providers' => ServiceProvider::defaultProviders()->merge([
    // Remove unused service providers in production
    // App\Providers\BroadcastServiceProvider::class,  // If not using broadcasting
])->toArray(),
```

**File:** `ppdb-app/config/cache.php`

```php
'stores' => [
    'file' => [
        'driver' => 'file',
        'path' => storage_path('framework/cache/data'),
        'lock_path' => storage_path('framework/cache/data'), // Same path for locks
    ],
],
```

### Step 12: Queue Worker (Cron Job)

**Setup Cron di cPanel:**

1. Open cPanel → Cron Jobs
2. Add new cron:

```bash
# Run queue worker every minute
* * * * * cd /home/username/ppdb.mtsn2km.sch.id/ppdb-app && /usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

3. Add queue processing:

```bash
# Process queue every 5 minutes
*/5 * * * * cd /home/username/ppdb.mtsn2km.sch.id/ppdb-app && /usr/bin/php artisan queue:work --stop-when-empty --timeout=60 >> /dev/null 2>&1
```

---

## 🛡️ SECURITY CHECKLIST

### Step 13: Security Hardening

#### a. Protect Sensitive Files

Create `.htaccess` in `ppdb-app/.htaccess`:

```apache
# Deny all access to application root
Deny from all
```

#### b. Hide Server Info

Add to `public_html/.htaccess`:

```apache
# Hide server signature
ServerSignature Off

# Prevent PHP info
<Files ~ "\.php$">
    php_flag display_errors 0
    php_flag log_errors 1
</Files>
```

#### c. Environment Protection

```bash
# Verify .env permissions
chmod 600 ppdb-app/.env

# Verify .env not accessible via web
curl https://ppdb.mtsn2km.sch.id/../ppdb-app/.env
# Should return 403 Forbidden
```

#### d. Disable Debug Mode

**Verify in .env:**
```env
APP_DEBUG=false
LOG_LEVEL=error
```

#### e. Setup HTTPS (SSL)

1. Request SSL Certificate di cPanel (Let's Encrypt free)
2. Install SSL certificate
3. Force HTTPS in `.htaccess`:

```apache
# Force HTTPS
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

---

## 🔍 TROUBLESHOOTING

### Common Issues & Solutions

#### Issue 1: 500 Internal Server Error

**Causes:**
- Wrong file permissions
- .env file missing or misconfigured
- PHP version incompatibility

**Solutions:**
```bash
# Check error logs
tail -f ppdb-app/storage/logs/laravel.log

# Verify permissions
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# Clear caches
php artisan optimize:clear
```

#### Issue 2: Database Connection Failed

**Check:**
- Database credentials in .env
- PostgreSQL server running
- User has proper permissions

**Test connection:**
```bash
php artisan tinker
> DB::connection()->getPdo();
```

#### Issue 3: Assets Not Loading (404)

**Causes:**
- Incorrect asset path
- .htaccess not working

**Solutions:**
```bash
# Verify assets exist
ls -la public_html/build/

# Check .htaccess
cat public_html/.htaccess

# Clear view cache
php artisan view:clear
```

#### Issue 4: File Upload Fails

**Check:**
```bash
# Verify storage permissions
ls -la storage/app/

# Create directories if missing
mkdir -p storage/app/dokumen
chmod -R 775 storage/

# Check PHP limits in phpinfo()
```

#### Issue 5: Session Not Working

**Solutions:**
```bash
# Verify session directory
mkdir -p storage/framework/sessions
chmod -R 775 storage/framework/

# Clear sessions
php artisan session:clear

# Verify in .env
SESSION_DRIVER=file
```

---

## 🔄 MAINTENANCE & BACKUP

### Daily Maintenance

```bash
# Clear expired cache
php artisan cache:clear

# Clear old logs (keep last 14 days)
find storage/logs -name "*.log" -mtime +14 -delete
```

### Weekly Backup

#### Database Backup Script:
```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/home/username/backups"

mkdir -p $BACKUP_DIR

# Database backup
pg_dump -h localhost -U username_ppdb_user username_ppdb > $BACKUP_DIR/db_$DATE.sql

# Compress
gzip $BACKUP_DIR/db_$DATE.sql

# Keep only last 30 days
find $BACKUP_DIR -name "db_*.sql.gz" -mtime +30 -delete
```

#### Files Backup:
```bash
# Backup uploaded files
tar -czf /home/username/backups/storage_$DATE.tar.gz \
    /home/username/ppdb.mtsn2km.sch.id/ppdb-app/storage/app/dokumen/
```

### Monitoring

**Setup Log Monitoring:**

Create `ppdb-app/check_errors.sh`:

```bash
#!/bin/bash
ERROR_LOG="storage/logs/laravel.log"
ADMIN_EMAIL="admin@mtsn2km.sch.id"

# Check for errors in last 5 minutes
if grep -q "ERROR" $ERROR_LOG; then
    tail -n 50 $ERROR_LOG | mail -s "PPDB Error Alert" $ADMIN_EMAIL
fi
```

Add to cron:
```bash
*/5 * * * * cd /home/username/ppdb.mtsn2km.sch.id/ppdb-app && bash check_errors.sh
```

---

## ✅ POST-DEPLOYMENT CHECKLIST

### Verify Everything Works:

- [ ] Homepage loads correctly (https://ppdb.mtsn2km.sch.id)
- [ ] Login works (test dengan semua role)
- [ ] Registration works
- [ ] Dashboard loads untuk setiap role
- [ ] File upload works
- [ ] File download works (private storage)
- [ ] Export PDF/Excel works
- [ ] Form submission works
- [ ] Email sending works
- [ ] WhatsApp notification works (if configured)
- [ ] Search functionality works
- [ ] Timestamps show correct timezone (WIB)
- [ ] No errors in logs
- [ ] SSL certificate active (HTTPS)
- [ ] Performance acceptable (< 3s page load)

### Security Verification:

- [ ] APP_DEBUG=false
- [ ] .env file not accessible via web
- [ ] Admin routes require admin role
- [ ] Pendaftar routes require pendaftar role
- [ ] Unauthorized access returns 403
- [ ] File uploads go to private storage
- [ ] HTTPS forced
- [ ] Security headers present
- [ ] No sensitive data in HTML source
- [ ] CSRF protection active

---

## 📞 SUPPORT & CONTACTS

### When Things Go Wrong:

1. **Check Laravel Logs:** `storage/logs/laravel.log`
2. **Check Server Error Logs:** cPanel → Error Logs
3. **Check PHP Error Logs:** Check via cPanel or phpinfo()

### Emergency Rollback:

```bash
# Restore database
psql -h localhost -U username_ppdb_user username_ppdb < backup_safe.sql

# Restore files
unzip ppdb_backup_safe.zip -d /home/username/ppdb.mtsn2km.sch.id/

# Clear all caches
php artisan optimize:clear
```

---

## 📚 ADDITIONAL RESOURCES

### Documentation:
- Laravel Documentation: https://laravel.com/docs
- Livewire Documentation: https://livewire.laravel.com/docs
- PostgreSQL Documentation: https://www.postgresql.org/docs/

### Performance Monitoring:
- Google PageSpeed Insights: https://pagespeed.web.dev/
- GTmetrix: https://gtmetrix.com/

### Security Scanning:
- Security Headers: https://securityheaders.com/
- SSL Labs: https://www.ssllabs.com/ssltest/

---

**Deployment Guide Version:** 1.0
**Last Updated:** 2026-01-16
**For Application:** PPDB MTsN 2 Kota Malang v1.0

**Next Update:** After production deployment success

---

*Good luck with your deployment! 🚀*

**NOTE:** Simpan dokumen ini untuk referensi dan update maintenance di masa depan.
