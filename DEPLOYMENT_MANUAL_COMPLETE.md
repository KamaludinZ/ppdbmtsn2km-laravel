# PANDUAN DEPLOYMENT MANUAL LENGKAP
## Upload Ulang Source Code ke Subdomain Shared Hosting
### https://pmbm.mtsn2kotamalang.sch.id

---

## 📋 PERSIAPAN DI LOCAL (Sebelum Upload)

### STEP 1: Prepare Source Code

```bash
# Jalankan script prepare
php prepare_for_production.php
```

Script ini akan otomatis:
- Fix semua @vite directive
- Generate CSS/JS production
- Create .env.production template
- Clear semua cache
- Remove development files

### STEP 2: Install Dependencies untuk Production

```bash
# Install composer dependencies tanpa dev
composer install --no-dev --optimize-autoloader

# TIDAK PERLU npm/node di production!
```

### STEP 3: Edit .env.production

Edit file `.env.production` dan isi dengan data Anda:

```env
APP_NAME="PPDB MTs N 2 Kota Malang"
APP_ENV=production
APP_KEY=                              # Akan di-generate nanti
APP_DEBUG=false
APP_URL=https://pmbm.mtsn2kotamalang.sch.id

# Database PostgreSQL
DB_CONNECTION=pgsql
DB_HOST=localhost                     # Atau IP server database
DB_PORT=5432
DB_DATABASE=nama_database_anda        # Isi dengan nama database
DB_USERNAME=username_database_anda    # Isi dengan username
DB_PASSWORD=password_database_anda    # Isi dengan password

# Email (sesuaikan dengan hosting)
MAIL_HOST=mail.mtsn2kotamalang.sch.id
MAIL_USERNAME=email@mtsn2kotamalang.sch.id
MAIL_PASSWORD=password_email
```

### STEP 4: Verifikasi Files yang Sudah Disiapkan

Pastikan ada:
- ✅ `public/css/app.css` (production CSS)
- ✅ `public/js/app.js` (production JS)
- ✅ `public/.htaccess`
- ✅ `.env.production`
- ✅ Tidak ada @vite di templates

---

## 📁 STRUKTUR FOLDER DI SHARED HOSTING

### Yang Benar untuk Subdomain:

```
/home/username/
└── pmbm.mtsn2kotamalang.sch.id/     ← Root subdomain
    ├── app/
    ├── bootstrap/
    ├── config/
    ├── database/
    ├── public/                       ← Set sebagai Document Root
    │   ├── .htaccess
    │   ├── index.php
    │   ├── css/
    │   │   └── app.css              ← WAJIB ADA!
    │   ├── js/
    │   │   └── app.js               ← WAJIB ADA!
    │   └── storage/                 ← Symlink (akan dibuat)
    ├── resources/
    ├── routes/
    ├── storage/                     ← Permission 755
    ├── vendor/
    ├── .env                         ← Rename dari .env.production
    ├── artisan
    └── composer.json
```

---

## 🚀 PROSES UPLOAD KE PRODUCTION

### STEP 1: Backup Data Lama (Jika Ada)

Via cPanel File Manager:
1. Compress folder lama menjadi zip
2. Download sebagai backup
3. Hapus folder lama

### STEP 2: Upload Files

#### A. Via cPanel File Manager:

1. **Login ke cPanel**
2. **Masuk ke File Manager**
3. **Navigate ke folder subdomain:**
   ```
   /home/username/pmbm.mtsn2kotamalang.sch.id/
   ```

4. **Upload file-file berikut:**

   **UPLOAD SEMUA FOLDER:**
   - `app/`
   - `bootstrap/`
   - `config/`
   - `database/`
   - `lang/`
   - `public/`
   - `resources/`
   - `routes/`
   - `storage/`
   - `vendor/`

   **UPLOAD FILE DI ROOT:**
   - `artisan`
   - `composer.json`
   - `composer.lock`
   - `.env.production` (rename jadi `.env`)

   **JANGAN UPLOAD:**
   - `node_modules/`
   - `.git/`
   - `tests/`
   - `.env` (local)
   - `*.log`
   - `package.json`
   - `package-lock.json`
   - `vite.config.js`
   - `tailwind.config.js`

#### B. Via FTP Client (FileZilla):

1. **Connect ke FTP:**
   - Host: ftp.mtsn2kotamalang.sch.id
   - Username: [FTP username]
   - Password: [FTP password]
   - Port: 21

2. **Upload semua files ke:**
   ```
   /pmbm.mtsn2kotamalang.sch.id/
   ```

### STEP 3: Set Document Root

Via cPanel:
1. **Domains → Subdomains**
2. **Edit subdomain** `pmbm.mtsn2kotamalang.sch.id`
3. **Set Document Root:**
   ```
   /home/username/pmbm.mtsn2kotamalang.sch.id/public
   ```
4. **Save**

### STEP 4: Configure .env di Production

Via File Manager:
1. **Rename** `.env.production` menjadi `.env`
2. **Edit** `.env` dan isi:
   - Database credentials
   - Email settings
   - Pastikan `APP_ENV=production`
   - Pastikan `APP_DEBUG=false`

### STEP 5: Set Permissions

Via File Manager atau Terminal:

```bash
# Via Terminal (jika ada)
cd /home/username/pmbm.mtsn2kotamalang.sch.id
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Via File Manager
# Klik kanan folder → Change Permissions → 755
```

Folder yang perlu permission 755:
- `storage/`
- `storage/app/`
- `storage/app/public/`
- `storage/framework/`
- `storage/framework/cache/`
- `storage/framework/sessions/`
- `storage/framework/views/`
- `storage/logs/`
- `bootstrap/cache/`

---

## ⚙️ KONFIGURASI DI PRODUCTION

### STEP 1: Generate Application Key

Via Terminal cPanel atau SSH:
```bash
cd /home/username/pmbm.mtsn2kotamalang.sch.id
php artisan key:generate
```

### STEP 2: Clear & Cache Config

```bash
# Clear semua cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Cache untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### STEP 3: Run Database Migrations

```bash
# Create database tables
php artisan migrate --force

# Seed initial data (optional)
php artisan db:seed --force
```

### STEP 4: Create Storage Link

```bash
php artisan storage:link
```

### STEP 5: Setup Cron Job

Via cPanel → Cron Jobs, add:
```
* * * * * cd /home/username/pmbm.mtsn2kotamalang.sch.id && php artisan schedule:run >> /dev/null 2>&1
```

---

## ✅ VERIFIKASI & TESTING

### 1. Test Website

Buka browser:
```
https://pmbm.mtsn2kotamalang.sch.id
```

### 2. Check List:

- [ ] Website bisa diakses
- [ ] Tidak ada error 500
- [ ] CSS loaded (tampilan bagus)
- [ ] JavaScript loaded (interaktif)
- [ ] Login bisa
- [ ] Database connected
- [ ] Upload file bisa
- [ ] Email bisa dikirim

### 3. Debug Jika Error

**Temporary enable debug:**
```bash
# Edit .env
APP_DEBUG=true

# Lihat error
# Kembalikan setelah selesai
APP_DEBUG=false
```

**Check error log:**
```bash
tail -f storage/logs/laravel.log
```

---

## 🔧 TROUBLESHOOTING

### Error 500
```bash
# Clear all caches
php artisan optimize:clear

# Check permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Check .env exists
ls -la .env
```

### Database Connection Error
```bash
# Test connection
php artisan tinker
>>> DB::connection()->getPdo();
```

### Missing CSS/JS
- Pastikan ada `public/css/app.css`
- Pastikan ada `public/js/app.js`
- Check source code di browser

### Class Not Found
```bash
# Regenerate autoload
composer dump-autoload -o
```

### Permission Denied
```bash
# Fix ownership (tanya hosting untuk user yang benar)
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

---

## 📝 FINAL CHECKLIST

### Pre-Upload:
- [x] Run `prepare_for_production.php`
- [x] Fix all @vite directives
- [x] Generate production assets
- [x] Configure .env.production
- [x] Run composer install --no-dev

### Upload Process:
- [ ] Backup old files (if any)
- [ ] Upload all folders
- [ ] Upload root files
- [ ] Rename .env.production to .env
- [ ] Set document root to /public

### Post-Upload:
- [ ] Generate APP_KEY
- [ ] Set folder permissions
- [ ] Clear and cache config
- [ ] Run migrations
- [ ] Create storage link
- [ ] Setup cron job
- [ ] Test website

### Security:
- [ ] APP_DEBUG=false
- [ ] Remove install scripts
- [ ] Check file permissions
- [ ] SSL certificate active

---

## 📞 KONTAK SUPPORT

Jika ada masalah:
1. **Hosting Support** - Untuk masalah server, database, permission
2. **Check Logs** - `storage/logs/laravel.log`
3. **Enable Debug** - Temporary untuk lihat error detail

---

## ⚡ QUICK COMMANDS

```bash
# Go to app directory
cd /home/username/pmbm.mtsn2kotamalang.sch.id

# Clear everything
php artisan optimize:clear

# Cache everything
php artisan optimize

# Check status
php artisan about

# View routes
php artisan route:list

# Test database
php artisan tinker
>>> DB::connection()->getPdo();
>>> exit
```

---

**IMPORTANT NOTES:**

1. **Production TIDAK perlu Node.js/NPM** - Sudah pakai CSS/JS static
2. **Document root HARUS ke folder /public** - Bukan root aplikasi
3. **Permissions penting** - Storage dan cache harus writable
4. **Always backup** - Sebelum update atau changes
5. **Monitor logs** - Regularly check error logs

---

Deployment selesai! Website siap digunakan di production. 🚀