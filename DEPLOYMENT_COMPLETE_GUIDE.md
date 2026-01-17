# Deployment Guide for PPDB MTsN 2 Kota Malang
## Complete Deployment Instructions for Shared Hosting

### 📋 Overview
This guide provides comprehensive instructions for deploying the PPDB MTsN 2 Kota Malang application to shared hosting. It addresses common issues and provides solutions for successful deployment.

### 🚀 Pre-Deployment Checklist

#### 1. Verify System Requirements
- [ ] PHP 8.1+ with required extensions (OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, Fileinfo, GD, PostgreSQL)
- [ ] PostgreSQL 12+ database access
- [ ] Composer available
- [ ] Sufficient disk space (minimum 500MB recommended)

#### 2. Application Preparation
- [ ] All development dependencies removed
- [ ] Assets compiled for production
- [ ] Configurations cached
- [ ] Security settings configured

### 📦 Creating Deployment Package

#### Method 1: Using ZIP Command (Recommended)
```bash
# Navigate to project directory
cd /path/to/ppdbmtsn2km-laravel

# Install production dependencies
composer install --no-dev --optimize-autoloader

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize --no-dev

# Create deployment package (Linux/Mac)
zip -r ppdb_production_$(date +%Y%m%d_%H%M%S).zip \
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
    package.json \
    package-lock.json \
    -x "*.git*" -x "*node_modules*" -x "*.env*" -x "*tests*" -x "*docs*" -x "*.md" -x "*phpunit*" -x "*docker*" -x "*docker-compose*"
```

#### Method 2: Using Windows ZIP Tool
```cmd
# Navigate to project directory
cd C:\path\to\ppdbmtsn2km-laravel

# Install production dependencies
composer install --no-dev --optimize-autoloader

# Clear and cache configurations
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer dump-autoload --optimize --no-dev

# Use Windows Explorer or command line to create ZIP
# Select all required folders and files, right-click and "Send to > Compressed folder"
```

#### Method 3: Manual File Selection
Create a deployment package by manually selecting these files/folders:
- app/
- bootstrap/
- config/
- database/
- public/
- resources/
- routes/
- storage/ (include for structure, exclude contents initially)
- vendor/
- artisan
- composer.json
- composer.lock
- package.json (if you need to rebuild assets on server)
- package-lock.json

### 🏗️ Shared Hosting Directory Structure

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
│   ├── storage/ (needs write permissions)
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

### 📄 Modified Index.php for Shared Hosting

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

### 🔧 Environment Configuration

**File:** `ppdb-app/.env`
```env
APP_NAME="PPDB MTsN 2 Kota Malang"
APP_ENV=production
APP_KEY=  # Generate after upload: php artisan key:generate
APP_DEBUG=false
APP_URL=https://ppdb.mtsn2km.sch.id  # Update to your domain

APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=id_ID
APP_TIMEZONE=Asia/Jakarta

BCRYPT_ROUNDS=12

LOG_CHANNEL=daily
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=pgsql
DB_HOST=localhost  # Or your database host
DB_PORT=5432
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_strong_password

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_PATH=/
SESSION_DOMAIN=null

QUEUE_CONNECTION=database

CACHE_STORE=file
CACHE_PREFIX=ppdb_cache_

MAIL_MAILER=smtp
MAIL_HOST=your_smtp_server
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@your-domain.com"
MAIL_FROM_NAME="${APP_NAME}"

# WhatsApp Gateway (if used)
WHATSAPP_GATEWAY_URL=https://your-whatsapp-gateway.com
WHATSAPP_ACCESS_TOKEN=your-access-token
WHATSAPP_SENDER_NUMBER=your-sender-number

VITE_APP_NAME="${APP_NAME}"
```

### 🗄️ Database Setup

#### 1. Create Database
Via cPanel:
- Go to PostgreSQL Databases
- Create new database
- Create database user
- Assign user to database with full privileges

#### 2. Run Migrations
```bash
# Navigate to application directory
cd /home/username/ppdb.mtsn2km.sch.id/ppdb-app

# Run migrations
php artisan migrate --force

# Run seeders if needed
php artisan db:seed --force
```

### 🔐 Security Configuration

#### 1. File Permissions
```bash
# Set permissions for storage directory
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# Make artisan executable
chmod 755 artisan
```

#### 2. Protect Sensitive Directories
**File:** `ppdb-app/.htaccess`
```apache
# Deny all access to application root
Deny from all
```

#### 3. Public Directory .htaccess
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
```

### ⚙️ Post-Deployment Tasks

#### 1. Generate Application Key
```bash
cd /home/username/ppdb.mtsn2km.sch.id/ppdb-app
php artisan key:generate
```

#### 2. Optimize Application
```bash
cd /home/username/ppdb.mtsn2km.sch.id/ppdb-app
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

#### 3. Set Up Cron Jobs (if needed)
```bash
# Add to crontab
* * * * * cd /home/username/ppdb.mtsn2km.sch.id/ppdb-app && php artisan schedule:run >> /dev/null 2>&1
```

### 🔍 Troubleshooting Common Issues

#### Issue 1: 500 Internal Server Error
**Solutions:**
- Check file permissions (storage/ should be writable)
- Verify .env file exists and is readable
- Check PHP version compatibility
- Review error logs in `storage/logs/`

#### Issue 2: Database Connection Failed
**Solutions:**
- Verify database credentials in .env
- Check if PostgreSQL service is running
- Confirm database user has proper permissions
- Test connection with: `php artisan tinker` and `DB::connection()->getPdo()`

#### Issue 3: Assets Not Loading
**Solutions:**
- Verify public directory is set as document root
- Check .htaccess configuration
- Confirm asset paths in config/app.php

#### Issue 4: File Upload Issues
**Solutions:**
- Ensure storage/app/ directory is writable
- Check PHP upload limits in php.ini
- Verify file size restrictions in application

### 🧪 Testing After Deployment

#### 1. Basic Functionality
- [ ] Homepage loads correctly
- [ ] Login works for all user types
- [ ] Dashboard accessible
- [ ] Form submissions work
- [ ] File uploads work
- [ ] Email notifications work

#### 2. Security Verification
- [ ] .env file not accessible via web
- [ ] APP_DEBUG is false
- [ ] Admin routes protected
- [ ] Authentication working properly

### 🔄 Rollback Plan

If deployment fails:

1. **Database Rollback:**
   ```bash
   php artisan migrate:rollback
   ```

2. **File Rollback:**
   - Restore from backup
   - Clear caches: `php artisan optimize:clear`

3. **Quick Recovery:**
   - Have a backup copy ready
   - Document the exact steps for restoration

### 📞 Support Resources

- Laravel Documentation: https://laravel.com/docs
- Shared Hosting Provider Support
- PostgreSQL Documentation: https://www.postgresql.org/docs/

---

**Document Version:** 2.0  
**Last Updated:** January 17, 2026  
**Application:** PPDB MTsN 2 Kota Malang