# Quick Reference - Materialized Views & Deployment

Panduan cepat command-command yang sering digunakan.

---

## 🚀 Materialized Views

### Refresh Views

```bash
# Refresh semua views (normal)
php artisan dashboard:refresh-views

# Refresh dengan concurrent (tidak lock, recommended untuk production)
php artisan dashboard:refresh-views --concurrent
```

### Check Views Status

```bash
# Via script
./scripts/monitor-views.sh

# Via SQL
psql -U postgres -d postgresppdb
```

```sql
-- List semua materialized views
\dm

-- Check last refresh time
SELECT last_refreshed FROM mv_dashboard_stats;
SELECT last_refreshed FROM mv_jalur_stats LIMIT 1;
SELECT last_refreshed FROM mv_recent_pendaftar LIMIT 1;
SELECT last_refreshed FROM mv_panitia_stats;

-- Check data
SELECT * FROM mv_dashboard_stats;
SELECT * FROM mv_jalur_stats;
SELECT * FROM mv_recent_pendaftar LIMIT 10;
SELECT * FROM mv_panitia_stats;

-- Check size
SELECT
    matviewname,
    pg_size_pretty(pg_total_relation_size(schemaname||'.'||matviewname)) as size
FROM pg_matviews
WHERE matviewname LIKE 'mv_%';
```

---

## 📅 Cron Job / Scheduler

### Setup (Linux)

```bash
# Setup cron (run once)
sudo ./scripts/setup-cron.sh

# Manual check crontab
crontab -l
sudo crontab -u www-data -l

# Test scheduler
php artisan schedule:list
php artisan schedule:run
```

### Setup (Windows)

```cmd
REM Run scheduler (keep window open)
scripts\scheduler.bat

REM Or setup Task Scheduler (lihat MATERIALIZED_VIEWS_SETUP.md)
```

### Monitor Scheduler

```bash
# Check if running
ps aux | grep "artisan schedule"

# Monitor cron logs (Linux)
sudo tail -f /var/log/syslog | grep CRON

# Monitor materialized views log
tail -f storage/logs/materialized-views.log
```

---

## 🔧 Maintenance

### Clear Caches

```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Rebuild caches (production)
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Maintenance Mode

```bash
# Enable (with retry after 60 seconds)
php artisan down --retry=60

# Enable with custom message
php artisan down --message="System upgrade in progress"

# Disable
php artisan up

# Check status
php artisan down --status
```

### Database

```bash
# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Refresh database (WARNING: drops all tables)
php artisan migrate:refresh

# Check migration status
php artisan migrate:status
```

---

## 🔍 Monitoring & Debugging

### Health Check

```bash
# Full health check
./scripts/check-health.sh

# Application info
php artisan about

# Environment info
php artisan env
```

### Logs

```bash
# Laravel logs
tail -f storage/logs/laravel.log
tail -n 100 storage/logs/laravel.log | grep ERROR

# Materialized views logs
tail -f storage/logs/materialized-views.log

# Nginx logs
sudo tail -f /var/log/nginx/error.log
sudo tail -f /var/log/nginx/access.log

# PHP-FPM logs
sudo tail -f /var/log/php8.1-fpm.log

# PostgreSQL logs
sudo tail -f /var/log/postgresql/postgresql-*.log
```

### Performance

```bash
# Route list
php artisan route:list

# Show slow queries
php artisan db:show

# Queue monitoring (if using queues)
php artisan queue:work --verbose
php artisan queue:failed
```

---

## 🗄️ Database Backup & Restore

### Backup

```bash
# Full backup
pg_dump -U postgres postgresppdb > backup_$(date +%Y%m%d_%H%M%S).sql

# Compressed backup
pg_dump -U postgres postgresppdb | gzip > backup_$(date +%Y%m%d_%H%M%S).sql.gz

# Specific tables only
pg_dump -U postgres -t pendaftar -t users postgresppdb > backup_users.sql
```

### Restore

```bash
# Restore from backup
psql -U postgres postgresppdb < backup_20260111_120000.sql

# Restore compressed backup
gunzip -c backup_20260111_120000.sql.gz | psql -U postgres postgresppdb

# Restore with dropping existing database
dropdb -U postgres postgresppdb
createdb -U postgres postgresppdb
psql -U postgres postgresppdb < backup_20260111_120000.sql
```

---

## 🚀 Deployment

### Quick Deploy

```bash
# Automated deployment
./deploy.sh production

# Manual deployment steps
php artisan down
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan dashboard:refresh-views
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo systemctl restart php8.1-fpm
sudo systemctl reload nginx
php artisan up
```

### Rollback

```bash
# Quick rollback
git reset --hard PREVIOUS_COMMIT
php artisan migrate:rollback
php artisan dashboard:refresh-views
php artisan config:cache
sudo systemctl restart php8.1-fpm
```

---

## 🔐 Permissions

### Fix Permissions

```bash
# Set ownership
sudo chown -R www-data:www-data /var/www/ppdbmtsn2km-laravel

# Set directory permissions
sudo find /var/www/ppdbmtsn2km-laravel -type d -exec chmod 755 {} \;

# Set file permissions
sudo find /var/www/ppdbmtsn2km-laravel -type f -exec chmod 644 {} \;

# Storage & cache must be writable
sudo chmod -R 775 storage bootstrap/cache
```

---

## 🔄 Services Management

### Restart Services

```bash
# PHP-FPM
sudo systemctl restart php8.1-fpm
sudo systemctl status php8.1-fpm

# Nginx
sudo systemctl restart nginx
sudo systemctl reload nginx  # reload config without restart
sudo systemctl status nginx
sudo nginx -t  # test config

# PostgreSQL
sudo systemctl restart postgresql
sudo systemctl status postgresql

# Redis (if used)
sudo systemctl restart redis-server
sudo systemctl status redis-server

# Supervisor (queue workers)
sudo supervisorctl restart all
sudo supervisorctl status
```

---

## 🧪 Testing

### Run Tests

```bash
# All tests
php artisan test

# Specific test
php artisan test --filter=DashboardTest

# With coverage
php artisan test --coverage
```

### Database Testing

```sql
-- Test materialized view performance
EXPLAIN ANALYZE SELECT * FROM mv_dashboard_stats;

-- Compare with normal query
EXPLAIN ANALYZE
SELECT
    COUNT(*) as total_pendaftar,
    COUNT(*) FILTER (WHERE status_pendaftaran = 'menunggu') as menunggu
FROM pendaftar;

-- Check connections
SELECT count(*) FROM pg_stat_activity;
SELECT * FROM pg_stat_activity WHERE state = 'active';
```

---

## 📊 Useful SQL Queries

```sql
-- Database size
SELECT pg_size_pretty(pg_database_size('postgresppdb'));

-- Table sizes
SELECT
    relname as table_name,
    pg_size_pretty(pg_total_relation_size(relid)) as total_size
FROM pg_catalog.pg_statio_user_tables
ORDER BY pg_total_relation_size(relid) DESC
LIMIT 10;

-- Active connections
SELECT
    datname,
    count(*) as connections
FROM pg_stat_activity
GROUP BY datname;

-- Long running queries
SELECT
    pid,
    now() - query_start as duration,
    query
FROM pg_stat_activity
WHERE state = 'active'
AND now() - query_start > interval '5 minutes';

-- Kill query
SELECT pg_terminate_backend(PID);
```

---

## 🎯 Windows Quick Commands

```cmd
REM Start scheduler
scripts\scheduler.bat

REM Refresh views
scripts\refresh-views.bat

REM Refresh with concurrent
scripts\refresh-views.bat concurrent

REM Clear caches
php artisan config:clear
php artisan cache:clear

REM Run migrations
php artisan migrate
```

---

## 🆘 Emergency Commands

### Site Down

```bash
# Check services
./scripts/check-health.sh
sudo systemctl status nginx php8.1-fpm postgresql

# Check logs
tail -n 100 storage/logs/laravel.log
sudo tail -n 100 /var/log/nginx/error.log

# Restart everything
sudo systemctl restart php8.1-fpm nginx
```

### High CPU/Memory

```bash
# Check processes
top
htop

# Check PHP processes
ps aux | grep php

# Kill stuck processes
pkill -9 php

# Restart PHP-FPM
sudo systemctl restart php8.1-fpm
```

### Database Issues

```bash
# Check connections
psql -U postgres -d postgresppdb -c "SELECT count(*) FROM pg_stat_activity;"

# Kill all connections
psql -U postgres -c "SELECT pg_terminate_backend(pid) FROM pg_stat_activity WHERE datname='postgresppdb' AND pid <> pg_backend_pid();"

# Restart PostgreSQL
sudo systemctl restart postgresql
```

### Views Not Updating

```bash
# Force refresh
php artisan dashboard:refresh-views

# Check schedule
php artisan schedule:list

# Test cron
sudo crontab -u www-data -l
cd /var/www/ppdbmtsn2km-laravel && php artisan schedule:run

# Check logs
tail -f storage/logs/materialized-views.log
```

---

## 📚 Additional Resources

- Full Setup Guide: `MATERIALIZED_VIEWS_SETUP.md`
- Deployment Checklist: `DEPLOYMENT_CHECKLIST.md`
- Scripts Documentation: `scripts/README.md`
- Laravel Docs: https://laravel.com/docs
- PostgreSQL Docs: https://www.postgresql.org/docs/

---

**Tip:** Bookmark halaman ini untuk akses cepat ke command-command penting!
