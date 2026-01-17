# Deployment Checklist - PPDB MTsN 2 Kota Malang

## 📋 Pre-Deployment

### 1. Code Review & Testing
- [ ] All features tested locally
- [ ] No errors in `php artisan test`
- [ ] Code reviewed and approved
- [ ] Git branch up to date with main/master
- [ ] Version tagged (if applicable)

### 2. Database
- [ ] Backup production database
  ```bash
  pg_dump -U postgres postgresppdb > backup_$(date +%Y%m%d_%H%M%S).sql
  ```
- [ ] Test migrations on staging
- [ ] Review migration rollback plan
- [ ] Check for breaking changes in migrations

### 3. Environment
- [ ] `.env.production` configured correctly
- [ ] `APP_DEBUG=false`
- [ ] `APP_ENV=production`
- [ ] Database credentials correct
- [ ] Redis configured (if used)
- [ ] Mail settings configured
- [ ] SSL certificates valid

### 4. Dependencies
- [ ] Composer dependencies updated
- [ ] NPM packages built for production
- [ ] No development dependencies in production

---

## 🚀 Deployment Steps

### 1. Pre-Deployment
- [ ] Notify users about maintenance
- [ ] Schedule deployment during low traffic
- [ ] Prepare rollback plan
- [ ] Team on standby

### 2. Backup
```bash
# Database
pg_dump -U postgres postgresppdb > /var/backups/ppdb/db_backup_$(date +%Y%m%d_%H%M%S).sql

# Files
tar -czf /var/backups/ppdb/files_backup_$(date +%Y%m%d_%H%M%S).tar.gz /var/www/ppdbmtsn2km-laravel
```
- [ ] Database backup created
- [ ] File backup created
- [ ] Backups verified

### 3. Enable Maintenance Mode
```bash
php artisan down --retry=60
```
- [ ] Maintenance mode enabled
- [ ] Maintenance page displays correctly

### 4. Pull Latest Code
```bash
git pull origin main
```
- [ ] Code pulled successfully
- [ ] Correct branch/tag deployed

### 5. Update Dependencies
```bash
composer install --no-dev --optimize-autoloader
npm install && npm run build
```
- [ ] Composer dependencies installed
- [ ] NPM assets built

### 6. Clear Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```
- [ ] All caches cleared

### 7. Run Migrations
```bash
php artisan migrate --force
```
- [ ] Migrations completed successfully
- [ ] No errors in migration

### 8. Refresh Materialized Views
```bash
php artisan dashboard:refresh-views
```
- [ ] All materialized views refreshed
- [ ] Views contain data

### 9. Optimize Application
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```
- [ ] Configuration cached
- [ ] Routes cached
- [ ] Views cached

### 10. Set Permissions
```bash
sudo chown -R www-data:www-data /var/www/ppdbmtsn2km-laravel
sudo chmod -R 755 /var/www/ppdbmtsn2km-laravel
sudo chmod -R 775 storage bootstrap/cache
```
- [ ] Ownership set correctly
- [ ] Permissions set correctly

### 11. Restart Services
```bash
sudo systemctl restart php8.1-fpm
sudo systemctl reload nginx
sudo supervisorctl restart all  # if using supervisor
```
- [ ] PHP-FPM restarted
- [ ] Nginx reloaded
- [ ] Queue workers restarted (if applicable)

### 12. Disable Maintenance Mode
```bash
php artisan up
```
- [ ] Maintenance mode disabled
- [ ] Website accessible

---

## ✅ Post-Deployment

### 1. Immediate Verification
- [ ] Homepage loads
- [ ] Login works (all roles)
- [ ] Dashboard displays correctly (Admin, Panitia, Pendaftar)
- [ ] Materialized views showing data
  ```bash
  ./scripts/monitor-views.sh
  ```
- [ ] No errors in logs
  ```bash
  tail -f storage/logs/laravel.log
  ```

### 2. Functionality Testing
- [ ] User registration works
- [ ] Form submission works
- [ ] File uploads work
- [ ] Email notifications sent
- [ ] Reports generate correctly
- [ ] Search functions work
- [ ] Export features work

### 3. Performance Check
- [ ] Dashboard loads < 2 seconds
- [ ] Materialized views refreshing
  ```bash
  tail -f storage/logs/materialized-views.log
  ```
- [ ] No slow queries
- [ ] Memory usage normal
- [ ] CPU usage normal

### 4. Cron Job Verification
```bash
# Check crontab
crontab -l

# Test schedule
php artisan schedule:list

# Monitor cron
sudo tail -f /var/log/syslog | grep CRON
```
- [ ] Cron job configured
- [ ] Scheduler running
- [ ] Materialized views auto-refreshing

### 5. Database Check
```sql
-- Check materialized views
SELECT matviewname, pg_size_pretty(pg_total_relation_size(schemaname||'.'||matviewname))
FROM pg_matviews WHERE matviewname LIKE 'mv_%';

-- Check last refresh
SELECT last_refreshed FROM mv_dashboard_stats;
```
- [ ] All views exist
- [ ] Views populated with data
- [ ] Indexes created

### 6. Security Check
- [ ] SSL certificate valid
- [ ] HTTPS redirect working
- [ ] Security headers present
- [ ] No sensitive data exposed
- [ ] `.env` not publicly accessible

### 7. Monitoring Setup
- [ ] Log monitoring active
- [ ] Error tracking configured
- [ ] Uptime monitoring configured
- [ ] Performance monitoring active

---

## 🔄 Rollback Plan

If deployment fails:

### 1. Quick Rollback
```bash
# Enable maintenance
php artisan down

# Restore code
git reset --hard PREVIOUS_COMMIT_HASH

# Rollback migration
php artisan migrate:rollback

# Restore database (if needed)
psql -U postgres postgresppdb < /var/backups/ppdb/db_backup_TIMESTAMP.sql

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Refresh views
php artisan dashboard:refresh-views

# Restart services
sudo systemctl restart php8.1-fpm
sudo systemctl reload nginx

# Disable maintenance
php artisan up
```

### 2. Database Rollback
```bash
# Stop application
php artisan down

# Restore database
psql -U postgres postgresppdb < /var/backups/ppdb/db_backup_TIMESTAMP.sql

# Refresh views
php artisan dashboard:refresh-views

# Restart
php artisan up
```

---

## 📊 Monitoring (First 24 Hours)

### Every Hour
- [ ] Check error logs
  ```bash
  tail -n 100 storage/logs/laravel.log | grep ERROR
  ```
- [ ] Monitor materialized views
  ```bash
  ./scripts/monitor-views.sh
  ```
- [ ] Check disk space
  ```bash
  df -h
  ```

### Every 4 Hours
- [ ] Run health check
  ```bash
  ./scripts/check-health.sh
  ```
- [ ] Check database connections
  ```sql
  SELECT count(*) FROM pg_stat_activity;
  ```
- [ ] Review slow query log

### Daily (First Week)
- [ ] Review all logs
- [ ] Check performance metrics
- [ ] User feedback review
- [ ] Database size monitoring
- [ ] Backup verification

---

## 📝 Post-Mortem Template

After deployment, document:

### Deployment Info
- **Date/Time:**
- **Duration:**
- **Deployed by:**
- **Version/Tag:**

### What Went Well
-

### Issues Encountered
-

### Lessons Learned
-

### Action Items
-

---

## 🆘 Emergency Contacts

- **System Admin:**
- **Database Admin:**
- **Lead Developer:**
- **Hosting Support:**

---

## 📞 Support Resources

- Documentation: `MATERIALIZED_VIEWS_SETUP.md`
- Scripts: `scripts/README.md`
- Laravel Docs: https://laravel.com/docs
- PostgreSQL Docs: https://www.postgresql.org/docs/

---

**Last Updated:** 2026-01-11
**Version:** 1.0
