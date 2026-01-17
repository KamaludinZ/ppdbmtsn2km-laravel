# Deployment Guide for PPDB MTsN 2 Kota Malang

## Table of Contents
1. [Prerequisites](#prerequisites)
2. [Server Setup](#server-setup)
3. [Environment Configuration](#environment-configuration)
4. [Database Setup](#database-setup)
5. [Application Deployment](#application-deployment)
6. [Web Server Configuration](#web-server-configuration)
7. [Security Best Practices](#security-best-practices)
8. [Post-Deployment Tasks](#post-deployment-tasks)
9. [Troubleshooting](#troubleshooting)

## Prerequisites

Before deploying your application, ensure your server meets the following requirements:

### System Requirements
- **Operating System**: Ubuntu 20.04 LTS or CentOS 8/RHEL 8
- **PHP Version**: 8.1 or higher
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **Database**: PostgreSQL 12+ or MySQL 8.0+

### PHP Extensions Required
```bash
sudo apt-get install php8.1-{cli,common,curl,xml,mbstring,zip,intl,bcmath,sqlite3,pdo-pgsql,gd,imagick}
```

### Additional Tools
- Git
- Composer
- Node.js and npm (for asset compilation)
- Certbot (for SSL certificates)

## Server Setup

### 1. Update System Packages
```bash
sudo apt update && sudo apt upgrade -y
```

### 2. Install PHP and Required Extensions
```bash
sudo apt install software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update

sudo apt install php8.1 php8.1-cli php8.1-common php8.1-curl php8.1-xml php8.1-mbstring php8.1-zip php8.1-bcmath php8.1-gd php8.1-intl php8.1-pgsql php8.1-sqlite3 php8.1-imagick
```

### 3. Install Composer
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer
```

### 4. Install Node.js and npm
```bash
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs
```

## Environment Configuration

### 1. Clone the Repository
```bash
cd /var/www
git clone https://github.com/your-username/ppdbmtsn2km-laravel.git
cd ppdbmtsn2km-laravel
```

### 2. Copy Environment File
```bash
cp .env.example .env
```

### 3. Generate Application Key
```bash
php artisan key:generate
```

### 4. Configure Environment Variables
Edit the `.env` file with your production settings:

```env
APP_NAME="PPDB MTsN 2 Kota Malang"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=your_database_name
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
SESSION_DRIVER=database
SESSION_LIFETIME=120
QUEUE_CONNECTION=database

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@your-domain.com"
MAIL_FROM_NAME="${APP_NAME}"

# WhatsApp Gateway Configuration
WHATSAPP_GATEWAY_URL=https://your-whatsapp-gateway.com
WHATSAPP_ACCESS_TOKEN=your-access-token
WHATSAPP_SENDER_NUMBER=your-sender-number

# Maintenance Mode
CUSTOM_MAINTENANCE_MODE=false
MAINTENANCE_ALLOWED_IPS=127.0.0.1,::1,your-ip-address
```

## Database Setup

### 1. Create Database
```bash
# For PostgreSQL
sudo -u postgres createdb your_database_name
sudo -u postgres createuser --interactive --pwprompt your_db_username

# Grant privileges
sudo -u postgres psql -c "GRANT ALL PRIVILEGES ON DATABASE your_database_name TO your_db_username;"
```

### 2. Run Migrations
```bash
php artisan migrate --force
```

### 3. Seed Database (if needed)
```bash
php artisan db:seed --force
```

## Application Deployment

### 1. Install Dependencies
```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build  # If you have assets to compile
```

### 2. Set Permissions
```bash
sudo chown -R www-data:www-data /var/www/ppdbmtsn2km-laravel
sudo chmod -R 755 /var/www/ppdbmtsn2km-laravel
sudo chmod -R 775 /var/www/ppdbmtsn2km-laravel/storage
sudo chmod -R 775 /var/www/ppdbmtsn2km-laravel/bootstrap/cache
```

### 3. Clear Caches
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

## Web Server Configuration

### Apache Configuration
Create a virtual host file:

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /var/www/ppdbmtsn2km-laravel/public

    <Directory /var/www/ppdbmtsn2km-laravel/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/your-domain_error.log
    CustomLog ${APACHE_LOG_DIR}/your-domain_access.log combined
</VirtualHost>
```

Enable the site and modules:
```bash
sudo a2ensite your-domain.conf
sudo a2enmod rewrite
sudo systemctl reload apache2
```

### Nginx Configuration
Create a server block:

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/ppdbmtsn2km-laravel/public;
    
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

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

Enable the site:
```bash
sudo ln -s /etc/nginx/sites-available/your-domain /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

## Security Best Practices

### 1. SSL Certificate
```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com
```

### 2. PHP Security Settings
Add to your PHP configuration:
```ini
expose_php = Off
allow_url_fopen = Off
display_errors = Off
log_errors = On
```

### 3. .htaccess Security (for Apache)
Ensure your Laravel public/.htaccess has security rules:
```apache
Options -Indexes
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} -s [OR]
RewriteCond %{REQUEST_FILENAME} -l [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^.*$ - [NC,L]
RewriteRule ^.*$ /index.php [NC,L]
```

### 4. Regular Updates
Set up automatic security updates:
```bash
sudo dpkg-reconfigure -plow unattended-upgrades
```

## Post-Deployment Tasks

### 1. Schedule Cron Jobs
Add to crontab (`crontab -e`):
```bash
* * * * * cd /var/www/ppdbmtsn2km-laravel && php artisan schedule:run >> /dev/null 2>&1
```

### 2. Configure Queue Workers
```bash
# Create a systemd service for queue workers
sudo nano /etc/systemd/system/laravel-worker.service
```

Content for the service file:
```ini
[Unit]
Description=Laravel Worker
After=network.target

[Service]
Type=forking
User=www-data
Group=www-data
Restart=always
RestartSec=3
ExecStart=/usr/bin/php /var/www/ppdbmtsn2km-laravel/artisan queue:work --daemon
ExecReload=/bin/kill -USR2 $MAINPID
StandardOutput=append:/var/log/laravel-worker.log
StandardError=append:/var/log/laravel-worker-error.log

[Install]
WantedBy=multi-user.target
```

Enable and start the service:
```bash
sudo systemctl enable laravel-worker.service
sudo systemctl start laravel-worker.service
```

### 3. Set Up Monitoring
```bash
# Install Laravel Telescope for debugging (optional, in development only)
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

## Troubleshooting

### Common Issues and Solutions

#### 1. Permission Errors
```bash
sudo chown -R www-data:www-data /var/www/ppdbmtsn2km-laravel
sudo chmod -R 775 /var/www/ppdbmtsn2km-laravel/storage
sudo chmod -R 775 /var/www/ppdbmtsn2km-laravel/bootstrap/cache
```

#### 2. Cache Issues
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

#### 3. Database Connection Issues
- Verify database credentials in `.env`
- Check if database service is running
- Ensure firewall allows database connections

#### 4. Memory Limit Issues
Increase memory limit in PHP configuration:
```ini
memory_limit = 512M
```

#### 5. Maintenance Mode
To put the application in maintenance mode:
```bash
php artisan down
```

To bring the application out of maintenance mode:
```bash
php artisan up
```

### Log Files Location
- Laravel logs: `/var/www/ppdbmtsn2km-laravel/storage/logs/`
- Apache logs: `/var/log/apache2/`
- Nginx logs: `/var/log/nginx/`
- PHP-FPM logs: `/var/log/php8.1-fpm.log`

## Rollback Procedure

If you need to rollback to a previous version:

1. Backup current files and database
2. Revert to previous git commit
3. Run migrations if needed
4. Clear caches
5. Restart services

```bash
# Backup
cp -r /var/www/ppdbmtsn2km-laravel /var/backups/ppdbmtsn2km-laravel-backup-$(date +%F)

# Revert to previous commit
git reset --hard HEAD~1

# Clear caches
php artisan cache:clear
php artisan config:clear

# Restart services
sudo systemctl restart apache2  # or nginx
```

## Performance Optimization

### 1. OPcache Configuration
Enable and configure OPcache in PHP:
```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0  # Production
opcache.save_comments=1
opcache.fast_shutdown=1
```

### 2. Redis for Caching
Configure Redis in `.env`:
```env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### 3. CDN for Assets
Consider using a CDN for static assets to improve load times.

---

**Note**: Always test your deployment process in a staging environment before deploying to production. Keep your server and application updated with the latest security patches.