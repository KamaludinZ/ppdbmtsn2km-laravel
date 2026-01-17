# Production Deployment Issues Resolution Guide

## Issue 1: PostgreSQL Authentication Error

### Problem
```
SQLSTATE[08006] [7] connection to server at "127.0.0.1", port 5432 failed: FATAL: password authentication failed for user "postgres"
```

### Root Cause
The application is trying to connect to PostgreSQL with incorrect credentials specified in the .env file.

### Solution
1. **Update your .env file on the production server** with correct database credentials:
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1  # or your actual PostgreSQL server IP
DB_PORT=5432
DB_DATABASE=your_actual_database_name  # not postgresppdb
DB_USERNAME=your_postgres_username    # not necessarily postgres
DB_PASSWORD=your_secure_password
```

2. **Contact your hosting provider** to get the correct PostgreSQL connection details:
   - Database name
   - Username
   - Password
   - Host (might be different from 127.0.0.1)
   - Port (usually 5432)

3. **Create the database user if needed** (ask your hosting provider):
   - The user needs access to the specific database
   - Proper permissions should be granted

## Issue 2: Path Mismatch Error

### Problem
The error shows mixed paths indicating the application was moved from Windows to Linux environment:
- Windows path: `C:\ppdbmtsn2km-laravel\resources\views`
- Linux path: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/`

### Root Cause
The application was developed on Windows but deployed to Linux, and there might be cached paths that still reference Windows paths.

### Solution
1. **Clear all caches on the production server**:
```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear
php artisan optimize:clear
```

2. **Regenerate application key**:
```bash
php artisan key:generate --force
```

3. **Re-cache configurations**:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Complete Production Setup Steps

### 1. Update Environment File
```bash
# Edit .env file in the ppdb-app directory
nano /home/username/ppdb.mtsn2km.sch.id/ppdb-app/.env
```

Update with correct values:
```
APP_NAME="PPDB MTsN 2 Kota Malang"
APP_ENV=production
APP_KEY=  # Will be generated
APP_DEBUG=false
APP_URL=https://ppdb.mtsn2km.sch.id

DB_CONNECTION=pgsql
DB_HOST=your_db_host
DB_PORT=5432
DB_DATABASE=your_database_name
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password

SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=database
```

### 2. Run Setup Commands
```bash
cd /home/username/ppdb.mtsn2km.sch.id/ppdb-app

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Generate key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Re-cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize
composer dump-autoload --optimize
```

### 3. Set Proper Permissions
```bash
# Set permissions for storage directory
chmod -R 775 /home/username/ppdb.mtsn2km.sch.id/ppdb-app/storage/
chmod -R 775 /home/username/ppdb.mtsn2km.sch.id/ppdb-app/bootstrap/cache/
chmod 755 /home/username/ppdb.mtsn2km.sch.id/ppdb-app/artisan
```

## Verification Steps

1. **Test database connection**:
```bash
php artisan tinker
>>> DB::connection()->getPdo();
```

2. **Check if application loads** by visiting your domain

3. **Check logs** if issues persist:
```bash
tail -f /home/username/ppdb.mtsn2km.sch.id/ppdb-app/storage/logs/laravel.log
```

## Common Hosting-Specific Issues

### Shared Hosting PostgreSQL Setup
Some shared hosts require:
- Specific database prefix (e.g., username_database)
- Different hostname (e.g., localhost, yourdomain.com, or specific server)
- Database user to be created via cPanel

### Contact Your Hosting Provider For:
- PostgreSQL connection details
- Database creation procedure
- User permission setup
- Any specific configuration requirements

## Emergency Rollback
If issues persist:
1. Restore from your backup
2. Verify .env file has correct credentials
3. Ensure database exists and user has access
4. Check file permissions