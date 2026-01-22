# Panduan Deployment Shared Hosting - PPDB MTsN 2 Kota Malang

## Struktur Folder Subdomain

### Lokasi Subdomain
**Domain**: `pmbm.mtsn2kotamalang.sch.id`

### Struktur yang Benar:
```
pmbm.mtsn2kotamalang.sch.id/            # Root subdomain
├── .htaccess                            # Redirect ke /public
├── .env                                 # Environment config (PROTECTED)
├── app/                                 # Laravel app folder (PROTECTED)
├── bootstrap/                           # Bootstrap files (PROTECTED)
├── config/                              # Config files (PROTECTED)
├── database/                            # Database files (PROTECTED)
├── resources/                           # Resources (PROTECTED)
├── routes/                              # Routes (PROTECTED)
├── storage/                             # Storage files (PROTECTED - PENTING!)
│   ├── app/
│   │   ├── private/                     # ✅ PRIVATE - tidak bisa diakses web
│   │   └── public/                      # ✅ PUBLIC - via symlink ke public/storage
│   ├── framework/
│   ├── logs/                            # ✅ PRIVATE - log files
│   └── views/                           # ✅ PRIVATE - compiled views
├── vendor/                              # Composer packages (PROTECTED)
└── public/                              # ✅ WEB ROOT - hanya ini yang accessible
    ├── .htaccess                        # Apache config dengan security rules
    ├── index.php                        # Entry point
    ├── css/                             # Static CSS (tanpa Vite)
    ├── js/                              # Static JS (tanpa Vite)
    ├── build/                           # Vite build output (optional)
    ├── uploads/                         # User uploads (PUBLIC)
    ├── storagepmbm/                     # ✅ SYMLINK ke ../storage/app/public
    └── favicon.ico
```

---

## PENTING: Keamanan Storage

### ❌ JANGAN PERNAH:
```
❌ storage/ di dalam public/ folder
❌ storage/ accessible dari browser langsung
❌ .env file di public/
❌ vendor/ di public/
```

### ✅ YANG BENAR:
```
✅ storage/ HARUS di LUAR public/ folder
✅ storage/app/public → symlink ke public/storage
✅ File private di storage/app/private/
✅ Logs di storage/logs/ (tidak accessible)
✅ .env di root (tidak accessible)
```

---

## Step-by-Step Deployment

### 1. Persiapan Local

```bash
# Build production assets
npm run build

# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Clear & cache config
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Generate optimized files
php artisan optimize
```

### 2. Upload Files ke Server

#### Via FTP/SFTP:
```
Upload SEMUA folder ke: pmbm.mtsn2kotamalang.sch.id/
KECUALI:
- node_modules/ (tidak perlu)
- .git/ (optional, bisa di-exclude)
- *.log files
- .env (akan dibuat manual di server)
```

### 3. Setup Environment (.env)

```bash
# Connect via SSH or use File Manager
cd pmbm.mtsn2kotamalang.sch.id/

# Copy .env.example ke .env
cp .env.example .env

# Edit .env
nano .env
```

**Konfigurasi PENTING di .env**:
```env
APP_NAME="PPDB MTsN 2 Kota Malang"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://pmbm.mtsn2kotamalang.sch.id

# Generate key via SSH:
# php artisan key:generate

DB_CONNECTION=mysql  # atau pgsql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=user_database_anda
DB_PASSWORD=password_database_anda

SESSION_DRIVER=database
CACHE_DRIVER=database
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
# ... mail config
```

### 4. Setup .htaccess di Root

**File**: `pmbm.mtsn2kotamalang.sch.id/.htaccess`

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>

Options -Indexes

<FilesMatch "^\.env|^\.git|composer\.(json|lock)|artisan">
    Require all denied
</FilesMatch>
```

### 5. Setup Storage Symlink

**PENTING**: Symlink menggunakan nama `storagepmbm` (bukan `storage`) untuk menghindari konflik dengan aplikasi lain di shared hosting.

```bash
# Via SSH
cd pmbm.mtsn2kotamalang.sch.id/
php artisan storage:link

# Jika tidak bisa SSH, buat manual:
# ln -s ../storage/app/public public/storagepmbm
```

**Verifikasi Symlink**:
```bash
ls -la public/storagepmbm
# Should show: storagepmbm -> ../storage/app/public
```

**Jika Ada Error "File already exists"**:
```bash
# Hapus symlink/folder lama
rm -rf public/storagepmbm
# Atau rename dulu
mv public/storagepmbm public/storagepmbm.old

# Buat symlink baru
php artisan storage:link
```

### 6. Set File Permissions

```bash
# Via SSH (adjust based on server)
chmod -R 755 pmbm.mtsn2kotamalang.sch.id/
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
chmod 644 .env

# Jika pakai cPanel, gunakan File Manager → Permissions
```

### 7. Run Migrations

```bash
# Via SSH
cd pmbm.mtsn2kotamalang.sch.id/
php artisan migrate --force

# Seed data (jika perlu)
php artisan db:seed --force
```

### 8. Optimize untuk Production

```bash
# Cache everything
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Assets Management (Tanpa Vite di Production)

### Build Assets Sebelum Upload:

```bash
# Di local
npm run build
```

Assets akan di-generate ke:
```
public/build/
├── manifest.json
└── assets/
    ├── app-[hash].css
    └── app-[hash].js
```

### Fallback Assets (Jika Vite Build Gagal):

Copy manual CSS & JS:
```bash
# Static CSS
cp resources/css/app.css public/css/app-production.css

# Atau gunakan Tailwind CLI
npx tailwindcss -i resources/css/app.css -o public/css/app-production.css --minify
```

### Asset Loader Otomatis:

File `resources/views/components/asset-loader.blade.php` sudah configured untuk:
- ✅ Detect environment (local vs production)
- ✅ Load Vite di development
- ✅ Load static assets di production
- ✅ Fallback ke CDN jika assets tidak ada

---

## Keamanan Storage

### Struktur Storage yang Aman:

```
storage/
├── app/
│   ├── private/               # ✅ TIDAK ACCESSIBLE dari web
│   │   ├── dokumen/          # Dokumen pendaftar (KTP, KK, dll)
│   │   ├── bukti_bayar/      # Bukti pembayaran
│   │   └── raport/           # File raport
│   │
│   └── public/               # ✅ ACCESSIBLE via /storage symlink
│       ├── photos/           # Foto profil (ukuran kecil, watermark)
│       └── announcements/    # Gambar pengumuman
│
├── framework/                # ✅ FRAMEWORK CACHE - tidak accessible
│   ├── cache/
│   ├── sessions/
│   └── views/
│
└── logs/                     # ✅ LOG FILES - tidak accessible
    └── laravel.log
```

### Upload File Private:

```php
// Controller/Livewire
// Upload ke storage PRIVATE
$path = $file->store('dokumen/ktp', 'private');

// Simpan path ke database
$pendaftar->ktp_path = $path;
```

### Download File Private:

```php
// Controller dengan authorization check
public function downloadKTP($id)
{
    $pendaftar = Pendaftar::findOrFail($id);

    // Check authorization
    if (Auth::id() !== $pendaftar->user_id && !Auth::user()->isAdmin()) {
        abort(403);
    }

    // Download from private storage
    return Storage::disk('private')->download($pendaftar->ktp_path);
}
```

### Upload File Public:

```php
// Upload ke storage PUBLIC (accessible via /storagepmbm)
$path = $file->store('photos', 'public');

// URL public
$url = Storage::disk('public')->url($path);
// Output: /storagepmbm/photos/filename.jpg
```

---

## Testing Deployment

### 1. Test Homepage
```
https://pmbm.mtsn2kotamalang.sch.id/
```

### 2. Test Assets Loading
```
https://pmbm.mtsn2kotamalang.sch.id/css/app-production.css
https://pmbm.mtsn2kotamalang.sch.id/js/app-production.js
```

### 3. Test Storage Symlink
```
https://pmbm.mtsn2kotamalang.sch.id/storagepmbm/
# Should show 403 Forbidden (directory listing disabled)
```

### 4. Test Security
```bash
# Test 1: .env HARUS 403 Forbidden
https://pmbm.mtsn2kotamalang.sch.id/.env

# Test 2: storage/ HARUS 403 Forbidden
https://pmbm.mtsn2kotamalang.sch.id/../storage/

# Test 3: vendor/ HARUS 403 Forbidden
https://pmbm.mtsn2kotamalang.sch.id/../vendor/

# Semua harus return 403 atau 404
```

### 5. Test Login & Functions
```
1. Register akun baru
2. Login
3. Upload dokumen
4. Export data
5. Check error logs: storage/logs/laravel.log
```

---

## Troubleshooting

### Error: 500 Internal Server Error

**Check**:
```bash
# 1. Check error logs
tail -f storage/logs/laravel.log

# 2. Check Apache error log
tail -f /var/log/apache2/error.log  # or cpanel error log

# 3. Check file permissions
ls -la storage/
ls -la bootstrap/cache/

# 4. Clear cache
php artisan cache:clear
php artisan config:clear
```

### Error: Assets Not Loading

**Fix**:
```bash
# 1. Check if assets exist
ls -la public/css/
ls -la public/js/
ls -la public/build/

# 2. Check .htaccess
cat public/.htaccess

# 3. Rebuild assets
npm run build

# 4. Check asset-loader.blade.php
cat resources/views/components/asset-loader.blade.php
```

### Error: Storage Symlink Not Working

**Fix**:
```bash
# Remove old symlink
rm public/storagepmbm

# Create new symlink
ln -s ../storage/app/public public/storagepmbm

# Or use artisan
php artisan storage:link

# Verify
ls -la public/storagepmbm
# Should show: storagepmbm -> ../storage/app/public
```

**Jika Error "File already exists"**:
```bash
# Ada file/folder dengan nama sama, hapus dulu
rm -rf public/storagepmbm

# Atau backup dulu
mv public/storagepmbm public/storagepmbm.backup

# Buat symlink baru
php artisan storage:link
```

### Error: Database Connection Failed

**Fix**:
```bash
# Check .env
cat .env | grep DB_

# Test connection
php artisan db:show

# Check database exists
mysql -u username -p
> SHOW DATABASES;
> USE database_name;
```

---

## Backup & Restore

### Backup Files:
```bash
# Backup entire application
tar -czf ppdb-backup-$(date +%Y%m%d).tar.gz pmbm.mtsn2kotamalang.sch.id/

# Backup storage only
tar -czf storage-backup-$(date +%Y%m%d).tar.gz pmbm.mtsn2kotamalang.sch.id/storage/

# Backup database
mysqldump -u username -p database_name > ppdb-db-backup-$(date +%Y%m%d).sql
```

### Restore:
```bash
# Restore files
tar -xzf ppdb-backup-20260119.tar.gz

# Restore database
mysql -u username -p database_name < ppdb-db-backup-20260119.sql
```

---

## Monitoring & Maintenance

### Daily Checks:
- ✅ Check error logs: `storage/logs/laravel.log`
- ✅ Monitor disk space
- ✅ Check database size

### Weekly Checks:
- ✅ Backup database & files
- ✅ Check server resources
- ✅ Review security logs

### Monthly Checks:
- ✅ Update dependencies (if safe)
- ✅ Review user uploads size
- ✅ Clean old logs
- ✅ Security audit

---

## Performance Optimization

### Enable OPcache (if available):
```php
; In php.ini or .user.ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
opcache.revalidate_freq=60
```

### Enable Gzip Compression:
Already configured in `public/.htaccess`:
```apache
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/css text/javascript
</IfModule>
```

### Browser Caching:
Already configured in `public/.htaccess`:
```apache
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
</IfModule>
```

---

## Security Checklist

- ✅ APP_DEBUG=false di .env
- ✅ APP_ENV=production di .env
- ✅ .htaccess di root untuk redirect
- ✅ .htaccess di public/ dengan security rules
- ✅ storage/ di LUAR public/ folder
- ✅ .env tidak accessible dari web
- ✅ vendor/ tidak accessible dari web
- ✅ File permissions correct (755/775/644)
- ✅ Storage symlink working
- ✅ HTTPS enabled (SSL certificate)
- ✅ Database password strong
- ✅ Regular backups scheduled

---

## Support & Help

### Jika Ada Masalah:

1. **Check Logs**:
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Enable Debug** (temporary, di local atau staging only):
   ```env
   APP_DEBUG=true
   ```

3. **Contact Hosting Support** untuk:
   - PHP version (harus 8.2+)
   - PHP extensions (mbstring, pdo, etc)
   - File permissions issues
   - .htaccess support
   - Database connection issues

---

**Last Updated**: 19 Januari 2026
**Tested On**: Shared Hosting cPanel
**Status**: ✅ Production Ready
