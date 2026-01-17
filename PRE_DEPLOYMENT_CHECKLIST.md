# Pre-Deployment Checklist for PPDB MTsN 2 Kota Malang

## 📋 Deployment Preparation Overview

This checklist ensures your application is ready for deployment to shared hosting. Follow each section carefully to minimize deployment issues.

---

## 🧪 1. LOCAL TESTING & VALIDATION

### 1.1 Application Testing
- [ ] All unit tests pass: `php artisan test`
- [ ] Feature tests pass for all user roles (admin, pendaftar, panitia)
- [ ] Integration tests pass
- [ ] Manual testing completed for critical flows:
  - [ ] User registration
  - [ ] User login/logout
  - [ ] Dashboard access for each role
  - [ ] Form submissions
  - [ ] File uploads/downloads
  - [ ] Email notifications
  - [ ] WhatsApp notifications (if configured)

### 1.2 Error Checking
- [ ] Clear all caches: `php artisan config:clear`, `cache:clear`, `route:clear`, `view:clear`
- [ ] Check for any error logs in `storage/logs/`
- [ ] Verify no debug statements left in code (`dd`, `dump`, `var_dump`)
- [ ] Run PHPStan or similar static analysis: `vendor/bin/phpstan analyse`
- [ ] Check for any TODO/FIXME comments that need attention

### 1.3 Performance Testing
- [ ] Application loads within acceptable time (< 3 seconds)
- [ ] Database queries are optimized (no N+1 issues)
- [ ] Assets are minified and optimized
- [ ] Image uploads work properly

---

## 🗄️ 2. DATABASE PREPARATION

### 2.1 Migration Validation
- [ ] All migrations tested locally: `php artisan migrate:status`
- [ ] Rollback and re-run migrations to ensure they work: `php artisan migrate:rollback && php artisan migrate`
- [ ] Seeders tested: `php artisan db:seed --class=YourSeederClass`
- [ ] Database schema documented and matches production requirements

### 2.2 Data Backup
- [ ] Create local database backup: `pg_dump -U postgres postgresppdb > backup_pre_deploy_$(date +%Y%m%d_%H%M%S).sql`
- [ ] Verify backup integrity by restoring to test database
- [ ] Document any custom data that needs to be seeded in production

---

## 🏗️ 3. APPLICATION STRUCTURE & BUILD

### 3.1 Dependency Management
- [ ] Run production dependency install: `composer install --no-dev --optimize-autoloader`
- [ ] Verify `composer.lock` is up to date
- [ ] Check for any deprecated packages
- [ ] Ensure all required PHP extensions are listed in `composer.json`

### 3.2 Asset Compilation
- [ ] Install npm dependencies: `npm install`
- [ ] Build production assets: `npm run build`
- [ ] Verify compiled assets exist in `public/build/`
- [ ] Test that all CSS/JS assets load correctly

### 3.3 Configuration Caching
- [ ] Clear all caches: `php artisan config:clear && php artisan route:clear && php artisan view:clear`
- [ ] Cache configurations: `php artisan config:cache`
- [ ] Cache routes: `php artisan route:cache`
- [ ] Cache views: `php artisan view:cache`
- [ ] Optimize autoloader: `composer dump-autoload --optimize --no-dev`

---

## 🔐 4. SECURITY REVIEW

### 4.1 Environment Configuration
- [ ] `.env.example` file is complete and up to date
- [ ] Sensitive data is not hardcoded in source code
- [ ] Database credentials are properly configured
- [ ] API keys are properly configured
- [ ] APP_KEY is empty in `.env.example` (will be generated in production)

### 4.2 Security Settings
- [ ] `APP_DEBUG=false` in production
- [ ] `APP_ENV=production` in production
- [ ] CSRF protection is enabled
- [ ] XSS protection is implemented
- [ ] Input validation is in place
- [ ] File upload validation is implemented
- [ ] Authentication and authorization are working properly

### 4.3 File Permissions
- [ ] Storage directory permissions are set correctly
- [ ] Bootstrap cache directory permissions are set correctly
- [ ] No sensitive files are publicly accessible

---

## 📁 5. DIRECTORY STRUCTURE PREPARATION

### 5.1 Prepare for Shared Hosting Structure
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

### 5.2 Verify Structure Compatibility
- [ ] Public assets are in the correct location
- [ ] Private application files will be in protected directory
- [ ] Symlinks (if any) are handled properly
- [ ] File paths are relative to application root

---

## 📦 6. PACKAGE PREPARATION

### 6.1 Create Production Package
```bash
# Create deployment package excluding unnecessary files
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

### 6.2 Verify Package Contents
- [ ] Package size is reasonable (< 100MB recommended)
- [ ] All necessary files are included
- [ ] No unnecessary files are included
- [ ] No sensitive files are included

---

## 🧩 7. INTEGRATION POINTS VALIDATION

### 7.1 External Services
- [ ] WhatsApp gateway configuration verified
- [ ] Email service configuration verified
- [ ] Payment gateway (if any) configuration verified
- [ ] Third-party API integrations tested

### 7.2 Database Connections
- [ ] PostgreSQL connection works in local production mode
- [ ] Database connection pooling configured appropriately
- [ ] Database transactions work properly

---

## 📊 8. MONITORING & LOGGING

### 8.1 Logging Configuration
- [ ] Log levels are appropriate for production
- [ ] Log rotation is configured
- [ ] Error reporting is set up
- [ ] Performance monitoring is considered

### 8.2 Health Checks
- [ ] Health check endpoint works: `/up`
- [ ] Database connectivity check works
- [ ] External service connectivity checks work

---

## 🔄 9. ROLLBACK PLAN

### 9.1 Backup Strategy
- [ ] Full database backup created
- [ ] Complete file backup created
- [ ] Version control tag created: `git tag -a v1.0-pre-deploy-$(date +%Y%m%d) -m "Pre-deployment backup"`
- [ ] Push tags: `git push origin --tags`

### 9.2 Rollback Steps Documented
- [ ] Database rollback procedure documented
- [ ] File rollback procedure documented
- [ ] DNS rollback procedure documented (if applicable)

---

## 📞 10. COMMUNICATION PLAN

### 10.1 Stakeholder Notification
- [ ] Admin team notified of planned deployment
- [ ] IT support team prepared
- [ ] Communication plan for users during deployment window

### 10.2 Deployment Window
- [ ] Scheduled deployment time confirmed
- [ ] Maintenance window communicated
- [ ] Rollback team on standby during deployment

---

## ✅ FINAL PRE-DEPLOYMENT VERIFICATION

### 11.1 Last-Minute Checks
- [ ] All tests pass one final time
- [ ] Production build is working locally
- [ ] Database is backed up
- [ ] All team members are ready
- [ ] Communication channels are open
- [ ] Rollback plan is tested and ready

### 11.2 Deployment Readiness
- [ ] Production server requirements verified
- [ ] Database access confirmed
- [ ] DNS records ready (if needed)
- [ ] SSL certificates ready (if needed)
- [ ] Monitoring tools configured

---

## 🚀 DEPLOYMENT READINESS STATUS

**Ready for deployment:** [ ] Yes [ ] No [ ] Partial

**Date of readiness verification:** ___________

**Team member responsible for verification:** ___________

**Sign-off:** ___________

---

## 📝 POST-DEPLOYMENT TASKS

### Immediate Post-Deployment
- [ ] Verify homepage loads correctly
- [ ] Test login with each user role
- [ ] Verify database connectivity
- [ ] Check error logs for any issues
- [ ] Verify all external integrations work
- [ ] Confirm SSL certificate is active
- [ ] Test file upload/download functionality

### 24-Hour Monitoring
- [ ] Monitor application performance
- [ ] Check error logs regularly
- [ ] Verify user access and functionality
- [ ] Confirm backup jobs are running

---

**Document Version:** 1.0  
**Created:** January 2026  
**Last Updated:** ___________  
**Next Review:** ___________