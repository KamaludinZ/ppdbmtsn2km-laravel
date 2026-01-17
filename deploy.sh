#!/bin/bash

###############################################################################
# Deploy Script untuk PPDB MTsN 2 Kota Malang
# Author: System Administrator
# Usage: ./deploy.sh [production|staging]
###############################################################################

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
ENVIRONMENT=${1:-production}
APP_DIR="/var/www/ppdbmtsn2km-laravel"
BACKUP_DIR="/var/backups/ppdb"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  PPDB Deployment Script${NC}"
echo -e "${GREEN}  Environment: $ENVIRONMENT${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Function to print step
step() {
    echo -e "${YELLOW}➜ $1${NC}"
}

# Function to print success
success() {
    echo -e "${GREEN}✓ $1${NC}"
}

# Function to print error
error() {
    echo -e "${RED}✗ $1${NC}"
    exit 1
}

# Check if running as root or with sudo
if [[ $EUID -eq 0 ]]; then
   error "This script should not be run as root. Use sudo when needed."
fi

# 1. Backup Database
step "1. Creating database backup..."
sudo -u postgres pg_dump postgresppdb > "$BACKUP_DIR/db_backup_$TIMESTAMP.sql" || error "Database backup failed"
success "Database backed up to $BACKUP_DIR/db_backup_$TIMESTAMP.sql"

# 2. Enable Maintenance Mode
step "2. Enabling maintenance mode..."
cd $APP_DIR
php artisan down --retry=60 || error "Failed to enable maintenance mode"
success "Maintenance mode enabled"

# 3. Pull latest code
step "3. Pulling latest code from repository..."
git pull origin main || error "Git pull failed"
success "Code updated"

# 4. Install/Update Dependencies
step "4. Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader || error "Composer install failed"
success "Composer dependencies installed"

# 5. Clear caches
step "5. Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
success "Caches cleared"

# 6. Run migrations
step "6. Running database migrations..."
php artisan migrate --force || error "Migration failed"
success "Migrations completed"

# 7. Refresh materialized views
step "7. Refreshing materialized views..."
php artisan dashboard:refresh-views || error "Materialized views refresh failed"
success "Materialized views refreshed"

# 8. Optimize application
step "8. Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
success "Application optimized"

# 9. Set permissions
step "9. Setting correct permissions..."
sudo chown -R www-data:www-data $APP_DIR
sudo chmod -R 755 $APP_DIR
sudo chmod -R 775 $APP_DIR/storage $APP_DIR/bootstrap/cache
success "Permissions set"

# 10. Restart services
step "10. Restarting services..."
sudo systemctl restart php8.1-fpm
sudo systemctl reload nginx
if systemctl is-active --quiet supervisor; then
    sudo supervisorctl restart all
fi
success "Services restarted"

# 11. Disable Maintenance Mode
step "11. Disabling maintenance mode..."
php artisan up || error "Failed to disable maintenance mode"
success "Maintenance mode disabled"

# 12. Verify deployment
step "12. Verifying deployment..."
php artisan about
success "Deployment verification completed"

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  Deployment Completed Successfully!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo "Backup location: $BACKUP_DIR/db_backup_$TIMESTAMP.sql"
echo "Deployed to: $APP_DIR"
echo ""
echo "Next steps:"
echo "1. Check website: https://ppdb.mtsn2kotamalang.sch.id"
echo "2. Monitor logs: tail -f storage/logs/laravel.log"
echo "3. Check scheduler: php artisan schedule:list"
echo ""
