# Scripts Directory

Koleksi script helper untuk maintenance dan deployment aplikasi PPDB.

## 📁 Daftar Scripts

### Linux/Production Scripts

#### 1. `setup-cron.sh`
Setup cron job untuk Laravel scheduler.

```bash
# Run with sudo
sudo ./scripts/setup-cron.sh
```

**Fungsi:**
- Menambahkan cron entry untuk Laravel scheduler
- Berjalan sebagai user `www-data`
- Scheduler akan run setiap menit

---

#### 2. `monitor-views.sh`
Monitor status materialized views.

```bash
./scripts/monitor-views.sh
```

**Output:**
- Status semua materialized views
- Ukuran setiap view
- Waktu terakhir refresh
- Jumlah records
- Status indexes

---

#### 3. `check-health.sh`
Health check menyeluruh untuk aplikasi.

```bash
./scripts/check-health.sh
```

**Cek:**
- Status services (Nginx, PHP, PostgreSQL, Redis)
- Database connection
- Materialized views
- Cron job
- File permissions
- Disk space
- Application status
- Error logs

**Exit codes:**
- `0` = Semua OK
- `> 0` = Jumlah issues yang ditemukan

---

### Windows Scripts

#### 1. `scheduler.bat`
Menjalankan Laravel scheduler terus menerus.

```cmd
scripts\scheduler.bat
```

**Fungsi:**
- Loop tanpa henti menjalankan `php artisan schedule:run`
- Interval 60 detik
- Berguna untuk development di Windows

**Alternatif:** Setup sebagai Windows Service atau Task Scheduler

---

#### 2. `refresh-views.bat`
Manual refresh materialized views.

```cmd
# Normal refresh
scripts\refresh-views.bat

# Concurrent refresh (tidak lock)
scripts\refresh-views.bat concurrent
```

---

## 🚀 Quick Start

### Setup Awal (Linux Production)

```bash
# 1. Set permissions
chmod +x scripts/*.sh

# 2. Setup cron job
sudo ./scripts/setup-cron.sh

# 3. Run health check
./scripts/check-health.sh

# 4. Monitor materialized views
./scripts/monitor-views.sh
```

### Setup Awal (Windows Development)

```cmd
REM 1. Edit path di scheduler.bat dan refresh-views.bat
REM    Sesuaikan PHP_PATH dan PROJECT_PATH

REM 2. Test refresh
scripts\refresh-views.bat

REM 3. Setup Task Scheduler (lihat dokumentasi utama)
```

---

## 📊 Monitoring Routine

### Daily Checks
```bash
# Health check pagi
./scripts/check-health.sh

# Monitor views
./scripts/monitor-views.sh
```

### Weekly Maintenance
```bash
# Manual refresh (optional)
php artisan dashboard:refresh-views
```

---

## 🔧 Customization

### Mengubah Jadwal Refresh

Edit `routes/console.php`:

```php
// Contoh: Setiap 10 menit
Schedule::command('dashboard:refresh-views --concurrent')
    ->everyTenMinutes()
    ->withoutOverlapping();
```

### Mengubah User Cron

Edit `scripts/setup-cron.sh`:

```bash
# Ganti www-data dengan user lain
USER="your-user"
```

### Mengubah Path PHP (Windows)

Edit `scripts/scheduler.bat` dan `scripts/refresh-views.bat`:

```batch
SET PHP_PATH=C:\path\to\php.exe
```

---

## 🐛 Troubleshooting

### Cron tidak berjalan

```bash
# Check crontab
crontab -l

# Check cron service
sudo systemctl status cron

# Check logs
sudo tail -f /var/log/syslog | grep CRON
```

### Views tidak refresh

```bash
# Manual refresh
php artisan dashboard:refresh-views

# Check last refresh
./scripts/monitor-views.sh

# Check logs
tail -f storage/logs/materialized-views.log
```

### Permission errors

```bash
# Fix ownership
sudo chown -R www-data:www-data /var/www/ppdbmtsn2km-laravel

# Fix permissions
sudo chmod -R 755 /var/www/ppdbmtsn2km-laravel
sudo chmod -R 775 storage bootstrap/cache
```

---

## 📝 Notes

- **Backup:** Selalu backup database sebelum deployment
- **Testing:** Test di staging sebelum production
- **Monitoring:** Setup monitoring untuk materialized views
- **Logs:** Regularly check logs untuk errors

---

## 🔗 Related Documentation

- Deploy Docker/Coolify (scheduler sudah berjalan sebagai container sendiri, cron tidak diperlukan): `docs/DEPLOY_COOLIFY.md`
- Laravel docs: https://laravel.com/docs/scheduling
- PostgreSQL materialized views: https://www.postgresql.org/docs/current/sql-creatematerializedview.html
