# Setup Laravel di Shared Hosting Subdomain
## https://pmbm.mtsn2kotamalang.sch.id

## Masalah Error 500 di Production

### Penyebab Utama:
1. **Template masih menggunakan `@vite` directive** - Production tidak pakai Vite
2. **Struktur folder tidak sesuai** untuk shared hosting subdomain
3. **Asset files (CSS/JS) tidak ada** di production

## SOLUSI CEPAT

### Step 1: Upload & Jalankan Script Fix

Upload file `fix_production_subdomain.php` ke root subdomain, lalu akses:
```
https://pmbm.mtsn2kotamalang.sch.id/fix_production_subdomain.php?key=fix2026
```

Script ini akan otomatis:
- Replace semua `@vite` directive dengan link CSS/JS biasa
- Create production assets (CSS/JS)
- Fix .htaccess
- Clear semua cache
- Check permissions

**HAPUS file setelah berhasil!**

### Step 2: Compile & Upload Assets

Di local, jalankan:
```bash
php compile_assets_production.php
```

Lalu upload:
- `public/css/app.css`
- `public/js/app.js`

## STRUKTUR FOLDER YANG BENAR

### Struktur di Shared Hosting:

```
/home/username/
├── pmbm.mtsn2kotamalang.sch.id/    ← Root subdomain
│   ├── app/
│   ├── bootstrap/
│   │   └── cache/                  ← Permission 755
│   ├── config/
│   ├── database/
│   ├── public/                     ← Document Root subdomain
│   │   ├── .htaccess
│   │   ├── index.php
│   │   ├── css/
│   │   │   └── app.css            ← Production CSS
│   │   ├── js/
│   │   │   └── app.js             ← Production JS
│   │   └── storage/                ← Symlink ke ../storage/app/public
│   ├── resources/
│   ├── routes/
│   ├── storage/                    ← Permission 755
│   │   ├── app/
│   │   ├── framework/
│   │   └── logs/
│   ├── vendor/
│   ├── .env                        ← Configuration file
│   └── artisan
```

### Setting Document Root di cPanel:

1. Masuk ke cPanel → Domains → Subdomains
2. Edit subdomain `pmbm.mtsn2kotamalang.sch.id`
3. Set Document Root ke: `/home/username/pmbm.mtsn2kotamalang.sch.id/public`

## FILE .ENV PRODUCTION

```env
APP_NAME="PPDB MTs N 2 Kota Malang"
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
APP_DEBUG=false
APP_URL=https://pmbm.mtsn2kotamalang.sch.id
APP_TIMEZONE=Asia/Jakarta

LOG_CHANNEL=daily
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

# Database PostgreSQL
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# Session & Cache
SESSION_DRIVER=file
SESSION_LIFETIME=120
CACHE_DRIVER=file
CACHE_PREFIX=ppdb_cache
QUEUE_CONNECTION=database

# Mail (sesuaikan dengan hosting)
MAIL_MAILER=smtp
MAIL_HOST=mail.mtsn2kotamalang.sch.id
MAIL_PORT=465
MAIL_USERNAME=noreply@mtsn2kotamalang.sch.id
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="noreply@mtsn2kotamalang.sch.id"
MAIL_FROM_NAME="${APP_NAME}"

# WhatsApp Gateway (optional)
WHATSAPP_GATEWAY_URL=
WHATSAPP_GATEWAY_TOKEN=

# Filesystem
FILESYSTEM_DISK=public

# IMPORTANT: Disable Vite in Production
VITE_ENABLED=false
```

## FIX MANUAL VIA cPanel

### 1. Fix @vite di Templates

Via File Manager, edit file berikut dan replace `@vite`:

**Files yang perlu diedit:**
- `resources/views/components/layouts/app.blade.php`
- `resources/views/components/layouts/dashboard.blade.php`
- `resources/views/components/layouts/public.blade.php`
- `resources/views/layouts/app.blade.php`

**Replace:**
```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

**Dengan:**
```blade
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<script src="{{ asset('js/app.js') }}" defer></script>
```

### 2. Create Production Assets

Buat folder dan file:
- `public/css/app.css` - Copy dari `compile_assets_production.php`
- `public/js/app.js` - Copy dari `compile_assets_production.php`

### 3. Clear Cache

Via Terminal di cPanel (jika ada):
```bash
cd pmbm.mtsn2kotamalang.sch.id
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

Atau hapus manual:
- Semua file di `bootstrap/cache/` (kecuali `.gitignore`)
- Semua file di `storage/framework/cache/`
- Semua file di `storage/framework/views/`
- Semua file di `storage/framework/sessions/`

### 4. Fix Permissions

Set permission 755 untuk:
- Folder `storage/` dan semua subfolder
- Folder `bootstrap/cache/`

## DEPLOYMENT CHECKLIST

- [ ] Upload semua files kecuali `node_modules` dan `.git`
- [ ] Upload & run `fix_production_subdomain.php`
- [ ] Upload production assets (`public/css/app.css` dan `public/js/app.js`)
- [ ] Set document root ke `/public` folder
- [ ] Configure `.env` dengan database credentials yang benar
- [ ] Set folder permissions (755 untuk storage dan bootstrap/cache)
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Run seeder jika perlu: `php artisan db:seed`
- [ ] Create storage link: `php artisan storage:link`
- [ ] Clear all caches
- [ ] Test website
- [ ] DELETE semua script fix untuk keamanan!

## COMMAND SHORTCUTS

```bash
# Clear everything
php artisan optimize:clear

# Cache everything
php artisan optimize

# Check Laravel configuration
php artisan about

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();
```

## TROUBLESHOOTING

### Jika masih Error 500:

1. **Check Error Log:**
   - `storage/logs/laravel.log`
   - cPanel → Errors (untuk server errors)

2. **Enable Debug Temporarily:**
   Edit `.env`: `APP_DEBUG=true`
   (Jangan lupa kembalikan ke `false` setelah selesai!)

3. **Check PHP Version:**
   Minimal PHP 8.1

4. **Check PHP Extensions:**
   - pdo_pgsql
   - mbstring
   - openssl
   - fileinfo
   - gd atau imagick

5. **Database Connection:**
   Test dengan script sederhana atau tinker

### Common Issues:

1. **"Class not found"** - Run `composer install --no-dev`
2. **"Permission denied"** - Fix folder permissions
3. **"No application encryption key"** - Run `php artisan key:generate`
4. **"Database connection refused"** - Check credentials di `.env`

## FILES YANG SUDAH DISIAPKAN

1. **fix_production_subdomain.php** - Script otomatis fix semua masalah
2. **compile_assets_production.php** - Generate CSS/JS untuk production
3. **production_fix.php** - Alternative fix script
4. **emergency_fix_production.php** - Emergency fix dengan Vite bypass

Upload dan jalankan `fix_production_subdomain.php` terlebih dahulu!