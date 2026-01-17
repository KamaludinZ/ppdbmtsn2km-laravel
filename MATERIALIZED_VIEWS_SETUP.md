# Panduan Setup Materialized Views & Deployment

## 📋 Daftar Isi
- [Pengenalan](#pengenalan)
- [Setup Development](#setup-development)
- [Setup Cron Job](#setup-cron-job)
- [Deploy ke Production](#deploy-ke-production)
- [Monitoring & Troubleshooting](#monitoring--troubleshooting)

---

## 🎯 Pengenalan

Materialized views adalah fitur database yang menyimpan hasil query dalam tabel fisik untuk meningkatkan performa. Aplikasi ini menggunakan 4 materialized views untuk dashboard:

1. **mv_dashboard_stats** - Statistik pendaftar (total, status)
2. **mv_jalur_stats** - Statistik per jalur pendaftaran
3. **mv_recent_pendaftar** - 100 pendaftar terbaru
4. **mv_panitia_stats** - Statistik panitia aktif

### Manfaat:
- ⚡ Dashboard loading **5-10x lebih cepat**
- 📊 Mengurangi beban query ke database utama
- 🔄 Auto-refresh dengan schedule yang bisa dikonfigurasi

---

## 🛠 Setup Development

### 1. Jalankan Migration
```bash
php artisan migrate
```

### 2. Initial Refresh
```bash
php artisan dashboard:refresh-views
```

### 3. Test Dashboard
Buka dashboard dan pastikan data muncul dengan benar:
- `/admin/dashboard`
- `/panitia/dashboard`

### 4. Verifikasi Materialized Views
```bash
# Masuk ke psql
psql -U postgres -d postgresppdb

# List semua materialized views
\dm

# Lihat isi view
SELECT * FROM mv_dashboard_stats;
SELECT * FROM mv_jalur_stats;
SELECT * FROM mv_recent_pendaftar LIMIT 5;
SELECT * FROM mv_panitia_stats;
```

---

## ⏰ Setup Cron Job

### Windows (Development/Local Server)

#### Opsi 1: Task Scheduler (Recommended)

1. **Buka Task Scheduler**
   - Tekan `Win + R`
   - Ketik `taskschd.msc`
   - Enter

2. **Create Basic Task**
   - Klik "Create Basic Task"
   - Name: `Laravel Scheduler - PPDB`
   - Description: `Run Laravel task scheduler every minute`

3. **Trigger**
   - Pilih "Daily"
   - Start: Pilih waktu mulai (misal: hari ini jam 00:00)
   - Recur every: `1 days`

4. **Action**
   - Pilih "Start a program"
   - Program/script: `C:\xampp\php\php.exe` (sesuaikan path PHP Anda)
   - Arguments: `artisan schedule:run`
   - Start in: `C:\ppdbmtsn2km-laravel`

5. **Settings (Advanced)**
   - Centang "Run task as soon as possible after a scheduled start is missed"
   - Repeat task every: `1 minute`
   - For a duration of: `Indefinitely`
   - Centang "Stop task if it runs longer than: 1 hour"

6. **Finish & Test**
   - Klik "Finish"
   - Right-click task → "Run" untuk test

#### Opsi 2: PowerShell Script

Buat file `scheduler.ps1`:
```powershell
# C:\ppdbmtsn2km-laravel\scheduler.ps1
$phpPath = "C:\xampp\php\php.exe"
$projectPath = "C:\ppdbmtsn2km-laravel"

while ($true) {
    Set-Location $projectPath
    & $phpPath artisan schedule:run
    Start-Sleep -Seconds 60
}
```

Jalankan dengan:
```powershell
powershell -ExecutionPolicy Bypass -File C:\ppdbmtsn2km-laravel\scheduler.ps1
```

### Linux/Ubuntu (Production Server)

#### 1. Edit Crontab
```bash
# Edit crontab untuk user www-data (atau user yang menjalankan web server)
sudo crontab -u www-data -e

# Atau untuk user current
crontab -e
```

#### 2. Tambahkan Cron Entry
```bash
# Laravel Scheduler - Berjalan setiap menit
* * * * * cd /var/www/ppdbmtsn2km-laravel && php artisan schedule:run >> /dev/null 2>&1

# Atau dengan logging
* * * * * cd /var/www/ppdbmtsn2km-laravel && php artisan schedule:run >> /var/log/laravel-scheduler.log 2>&1
```

#### 3. Verifikasi Crontab
```bash
# Lihat crontab yang aktif
crontab -l

# Test manual
cd /var/www/ppdbmtsn2km-laravel
php artisan schedule:run

# Lihat schedule yang terdaftar
php artisan schedule:list
```

#### 4. Monitor Cron Job
```bash
# Lihat log cron
sudo tail -f /var/log/cron
# atau
sudo tail -f /var/log/syslog | grep CRON

# Lihat log materialized views
tail -f storage/logs/materialized-views.log
```

---

## 🚀 Deploy ke Production

### Pre-requisites
- ✅ PostgreSQL 12+ terinstall
- ✅ PHP 8.1+ dengan ekstensi pgsql
- ✅ Composer terinstall
- ✅ Git terinstall
- ✅ Web server (Nginx/Apache)

### Langkah Deploy

#### 1. Clone & Setup Project
```bash
# Clone repository
cd /var/www
sudo git clone <repository-url> ppdbmtsn2km-laravel
cd ppdbmtsn2km-laravel

# Set ownership
sudo chown -R www-data:www-data /var/www/ppdbmtsn2km-laravel
sudo chmod -R 755 /var/www/ppdbmtsn2km-laravel

# Set storage & cache permissions
sudo chmod -R 775 storage bootstrap/cache
```

#### 2. Install Dependencies
```bash
# Install composer dependencies (production)
composer install --no-dev --optimize-autoloader

# Install NPM dependencies (jika ada)
npm install
npm run build
```

#### 3. Configure Environment
```bash
# Copy environment file
cp .env.example .env

# Edit .env untuk production
nano .env
```

**Konfigurasi .env Production:**
```env
APP_NAME="PPDB MTsN 2 Kota Malang"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://ppdb.mtsn2kotamalang.sch.id

# Database
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=postgresppdb
DB_USERNAME=postgres
DB_PASSWORD=<strong_password>

# Cache & Session
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

#### 4. Generate Key & Setup
```bash
# Generate application key
php artisan key:generate

# Clear all cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 5. Database Migration
```bash
# Backup database terlebih dahulu!
pg_dump -U postgres postgresppdb > backup_before_migration.sql

# Run migrations
php artisan migrate --force

# Initial refresh materialized views
php artisan dashboard:refresh-views

# Verify views created
psql -U postgres -d postgresppdb -c "\dm"
```

#### 6. Setup Cron Job (Production)
```bash
# Edit crontab
sudo crontab -u www-data -e

# Tambahkan:
* * * * * cd /var/www/ppdbmtsn2km-laravel && php artisan schedule:run >> /dev/null 2>&1
```

#### 7. Setup Supervisor (Queue Worker - Optional)
Jika menggunakan queue:

```bash
# Install supervisor
sudo apt-get install supervisor

# Create config
sudo nano /etc/supervisor/conf.d/ppdb-worker.conf
```

**Isi config:**
```ini
[program:ppdb-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/ppdbmtsn2km-laravel/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/ppdbmtsn2km-laravel/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
# Reload supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start ppdb-worker:*
```

#### 8. Web Server Configuration

**Nginx:**
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name ppdb.mtsn2kotamalang.sch.id;

    # Redirect to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name ppdb.mtsn2kotamalang.sch.id;

    root /var/www/ppdbmtsn2km-laravel/public;
    index index.php index.html;

    # SSL Configuration
    ssl_certificate /etc/ssl/certs/ppdb.crt;
    ssl_certificate_key /etc/ssl/private/ppdb.key;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
# Test & reload nginx
sudo nginx -t
sudo systemctl reload nginx
```

#### 9. Setup SSL (Let's Encrypt)
```bash
# Install certbot
sudo apt-get install certbot python3-certbot-nginx

# Generate certificate
sudo certbot --nginx -d ppdb.mtsn2kotamalang.sch.id

# Test auto-renewal
sudo certbot renew --dry-run
```

#### 10. Final Checks
```bash
# Check scheduler running
php artisan schedule:list

# Test materialized views
php artisan dashboard:refresh-views

# Check permissions
ls -la storage/
ls -la bootstrap/cache/

# Check services
sudo systemctl status nginx
sudo systemctl status php8.1-fpm
sudo systemctl status postgresql
sudo supervisorctl status
```

---

## 📊 Monitoring & Troubleshooting

### 1. Monitor Materialized Views

#### Check Last Refresh Time
```sql
-- Dashboard Stats
SELECT last_refreshed FROM mv_dashboard_stats;

-- Jalur Stats
SELECT last_refreshed FROM mv_jalur_stats LIMIT 1;

-- Recent Pendaftar
SELECT last_refreshed FROM mv_recent_pendaftar LIMIT 1;

-- Panitia Stats
SELECT last_refreshed FROM mv_panitia_stats;
```

#### Check View Size
```sql
SELECT
    schemaname,
    matviewname,
    pg_size_pretty(pg_total_relation_size(schemaname||'.'||matviewname)) AS size
FROM pg_matviews
WHERE matviewname LIKE 'mv_%'
ORDER BY pg_total_relation_size(schemaname||'.'||matviewname) DESC;
```

### 2. Manual Refresh (Emergency)

```bash
# Refresh semua views
php artisan dashboard:refresh-views

# Refresh dengan concurrent (tidak lock)
php artisan dashboard:refresh-views --concurrent

# Refresh langsung via SQL
psql -U postgres -d postgresppdb -c "REFRESH MATERIALIZED VIEW CONCURRENTLY mv_dashboard_stats;"
```

### 3. Log Files

```bash
# Laravel scheduler log
tail -f storage/logs/materialized-views.log

# Laravel application log
tail -f storage/logs/laravel.log

# Cron log (Linux)
sudo tail -f /var/log/syslog | grep CRON

# Nginx error log
sudo tail -f /var/log/nginx/error.log

# PostgreSQL log
sudo tail -f /var/log/postgresql/postgresql-14-main.log
```

### 4. Performance Monitoring

#### Query Performance
```sql
-- Bandingkan performa view vs query langsung
EXPLAIN ANALYZE SELECT * FROM mv_dashboard_stats;

EXPLAIN ANALYZE
SELECT
    COUNT(*) as total_pendaftar,
    COUNT(*) FILTER (WHERE status_pendaftaran = 'menunggu') as menunggu_verifikasi
FROM pendaftar;
```

#### Index Status
```sql
-- Check indexes on materialized views
SELECT
    schemaname,
    tablename,
    indexname,
    indexdef
FROM pg_indexes
WHERE tablename LIKE 'mv_%';
```

### 5. Common Issues & Solutions

#### Issue 1: Views Tidak Ter-refresh
```bash
# Check if cron running
sudo systemctl status cron

# Check crontab entry
crontab -l

# Manual test
cd /var/www/ppdbmtsn2km-laravel
php artisan schedule:run
```

#### Issue 2: Data Tidak Update
```bash
# Force refresh
php artisan dashboard:refresh-views

# Check last refresh
php artisan tinker
>>> App\Models\DashboardStats::first()
>>> App\Models\DashboardStats::first()->last_refreshed
```

#### Issue 3: Permission Denied
```bash
# Fix ownership
sudo chown -R www-data:www-data /var/www/ppdbmtsn2km-laravel

# Fix permissions
sudo chmod -R 755 /var/www/ppdbmtsn2km-laravel
sudo chmod -R 775 storage bootstrap/cache
```

#### Issue 4: View Kosong
```sql
-- Manual refresh via SQL
REFRESH MATERIALIZED VIEW mv_dashboard_stats;
REFRESH MATERIALIZED VIEW mv_jalur_stats;
REFRESH MATERIALIZED VIEW mv_recent_pendaftar;
REFRESH MATERIALIZED VIEW mv_panitia_stats;
```

### 6. Rollback (Jika Ada Masalah)

```bash
# Rollback migration terakhir
php artisan migrate:rollback --step=1

# Atau hapus manual via SQL
psql -U postgres -d postgresppdb
```

```sql
DROP MATERIALIZED VIEW IF EXISTS mv_dashboard_stats CASCADE;
DROP MATERIALIZED VIEW IF EXISTS mv_jalur_stats CASCADE;
DROP MATERIALIZED VIEW IF EXISTS mv_recent_pendaftar CASCADE;
DROP MATERIALIZED VIEW IF EXISTS mv_panitia_stats CASCADE;
```

---

## 🔧 Maintenance Commands

### Regular Maintenance
```bash
# Refresh views manually (during low traffic)
php artisan dashboard:refresh-views

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Database optimization
php artisan db:optimize
```

### Database Vacuum (PostgreSQL)
```bash
# Jalankan vacuum untuk optimasi
psql -U postgres -d postgresppdb -c "VACUUM ANALYZE;"

# Khusus materialized views
psql -U postgres -d postgresppdb -c "VACUUM ANALYZE mv_dashboard_stats;"
psql -U postgres -d postgresppdb -c "VACUUM ANALYZE mv_jalur_stats;"
psql -U postgres -d postgresppdb -c "VACUUM ANALYZE mv_recent_pendaftar;"
psql -U postgres -d postgresppdb -c "VACUUM ANALYZE mv_panitia_stats;"
```

---

## 📈 Performance Tips

1. **Jadwal Refresh yang Optimal:**
   - Jam sibuk (07:00-18:00): Setiap 5 menit
   - Jam sepi: Setiap 15 menit
   - Bisa disesuaikan di `routes/console.php`

2. **Gunakan Concurrent Refresh:**
   - Tidak mengunci view saat refresh
   - Dashboard tetap responsif

3. **Monitor Database Size:**
   - Materialized views menambah storage
   - Monitor dengan query size di atas

4. **Backup Regular:**
   - Backup database sebelum migration
   - Setup automated backup

---

## 📞 Support

Jika ada masalah:
1. Check logs: `storage/logs/laravel.log`
2. Check materialized views log: `storage/logs/materialized-views.log`
3. Test manual refresh: `php artisan dashboard:refresh-views`
4. Verify cron: `crontab -l`

---

**Dokumentasi ini dibuat untuk PPDB MTsN 2 Kota Malang**
**Last Updated:** 2026-01-11
