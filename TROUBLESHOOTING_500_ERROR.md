# Production 500 Error Troubleshooting Guide

## Issue: Server 500 Error
The application is returning a 500 Internal Server Error, which indicates a server-side problem.

## Common Causes & Solutions

### 1. Database Connection Issues (Most Likely)

#### Symptoms:
- Database authentication errors in logs
- Application can't connect to PostgreSQL
- Settings table not accessible

#### Solutions:
1. **Verify database credentials in `.env` file**:
   ```
   DB_CONNECTION=pgsql
   DB_HOST=correct_hostname_or_ip
   DB_PORT=5432
   DB_DATABASE=correct_database_name
   DB_USERNAME=correct_username
   DB_PASSWORD=correct_password
   ```

2. **Test database connection directly**:
   ```bash
   # Using psql command line tool
   psql -h hostname -U username -d database_name
   ```

3. **Check if PostgreSQL service is running** on your server.

### 2. File Permissions Issues

#### Symptoms:
- Cannot write to storage directory
- Cannot access configuration files
- Cache files cannot be created

#### Solutions:
1. **Set correct permissions**:
   ```bash
   # Storage directory must be writable
   chmod -R 775 storage/
   chmod -R 775 bootstrap/cache/
   
   # Ensure correct ownership
   chown -R www-data:www-data storage/
   chown -R www-data:www-data bootstrap/cache/
   ```

2. **Verify directory structure**:
   - `storage/logs/` - must be writable
   - `storage/framework/cache/` - must be writable
   - `storage/framework/views/` - must be writable
   - `storage/framework/sessions/` - must be writable

### 3. PHP Configuration Issues

#### Symptoms:
- Memory limit exceeded
- Maximum execution time exceeded
- Required PHP extensions missing

#### Solutions:
1. **Check PHP configuration**:
   ```
   memory_limit = 256M (or higher)
   max_execution_time = 60
   max_input_time = 60
   upload_max_filesize = 10M
   post_max_size = 10M
   ```

2. **Verify required PHP extensions are installed**:
   - pgsql
   - pdo_pgsql
   - openssl
   - mbstring
   - tokenizer
   - xml
   - ctype
   - json
   - bcmath
   - fileinfo
   - gd (or imagick)

### 4. Application Cache Issues

#### Symptoms:
- Cached configuration is incorrect
- Old routes or views causing conflicts
- Configuration mismatch

#### Solutions:
1. **Clear all caches**:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   php artisan view:clear
   php artisan event:clear
   php artisan optimize:clear
   ```

2. **Regenerate caches**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

### 5. .env File Issues

#### Symptoms:
- Environment variables not loaded
- Incorrect configuration values
- Missing required settings

#### Solutions:
1. **Verify `.env` file exists** in the correct location
2. **Check that `.env` file has correct syntax**
3. **Ensure `.env` file is not readable by web users** (place outside web root if possible)

## Diagnostic Steps

### 1. Check Error Logs
```bash
# Laravel logs
tail -f storage/logs/laravel.log

# System logs
tail -f /var/log/apache2/error.log  # For Apache
tail -f /var/log/nginx/error.log    # For Nginx
tail -f /var/log/php/error.log      # PHP errors
```

### 2. Run the Diagnostic Script
Upload and run the diagnose_db.php script to check:
- Database connection
- Environment configuration
- File permissions
- PHP extensions

### 3. Test Basic Connectivity
```bash
# Test if the application boots
php artisan tinker

# Test database connection
php artisan db:connection-test

# Check configuration
php artisan config:get app.name
```

## Quick Fix Checklist

Before contacting support, verify:

- [ ] Database credentials are correct in `.env`
- [ ] Database server is running and accessible
- [ ] Storage directory permissions are correct (775)
- [ ] All required PHP extensions are installed
- [ ] All caches have been cleared and regenerated
- [ ] Application key has been generated
- [ ] Web server has correct document root
- [ ] `.htaccess` file is present and correct (for Apache)

## Emergency Recovery

If nothing works:

1. **Restore from backup**
2. **Check with hosting provider** for specific requirements
3. **Verify domain and DNS settings**
4. **Check if there are any server maintenance windows**

## Contact Information

If issues persist after following this guide:
- Contact your hosting provider's technical support
- Provide them with the error logs
- Ask about any specific PostgreSQL or Laravel requirements