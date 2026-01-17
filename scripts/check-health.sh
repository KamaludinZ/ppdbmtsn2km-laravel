#!/bin/bash

###############################################################################
# Health Check Script for PPDB Application
# Author: System Administrator
# Usage: ./scripts/check-health.sh
###############################################################################

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

APP_DIR="/var/www/ppdbmtsn2km-laravel"
DB_USER="postgres"
DB_NAME="postgresppdb"

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  PPDB Health Check${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Counter for issues
ISSUES=0

# Function to check service
check_service() {
    local service=$1
    echo -n "Checking $service... "
    if systemctl is-active --quiet $service; then
        echo -e "${GREEN}✓ Running${NC}"
    else
        echo -e "${RED}✗ Not Running${NC}"
        ((ISSUES++))
    fi
}

# 1. Check services
echo -e "${YELLOW}1. System Services:${NC}"
check_service nginx
check_service php8.1-fpm
check_service postgresql
check_service redis-server
echo ""

# 2. Check database connection
echo -e "${YELLOW}2. Database Connection:${NC}"
echo -n "PostgreSQL connection... "
if psql -U $DB_USER -d $DB_NAME -c "SELECT 1" > /dev/null 2>&1; then
    echo -e "${GREEN}✓ Connected${NC}"
else
    echo -e "${RED}✗ Failed${NC}"
    ((ISSUES++))
fi
echo ""

# 3. Check materialized views
echo -e "${YELLOW}3. Materialized Views:${NC}"
VIEWS=("mv_dashboard_stats" "mv_jalur_stats" "mv_recent_pendaftar" "mv_panitia_stats")
for view in "${VIEWS[@]}"; do
    echo -n "Checking $view... "
    COUNT=$(psql -U $DB_USER -d $DB_NAME -t -A -c "SELECT COUNT(*) FROM pg_matviews WHERE matviewname='$view';")
    if [ "$COUNT" -eq "1" ]; then
        echo -e "${GREEN}✓ Exists${NC}"
    else
        echo -e "${RED}✗ Missing${NC}"
        ((ISSUES++))
    fi
done
echo ""

# 4. Check cron job
echo -e "${YELLOW}4. Cron Job:${NC}"
echo -n "Scheduler cron entry... "
if sudo crontab -u www-data -l 2>/dev/null | grep -q "artisan schedule:run"; then
    echo -e "${GREEN}✓ Configured${NC}"
else
    echo -e "${RED}✗ Missing${NC}"
    ((ISSUES++))
fi
echo ""

# 5. Check file permissions
echo -e "${YELLOW}5. File Permissions:${NC}"
echo -n "Storage directory... "
if [ -w "$APP_DIR/storage" ]; then
    echo -e "${GREEN}✓ Writable${NC}"
else
    echo -e "${RED}✗ Not Writable${NC}"
    ((ISSUES++))
fi

echo -n "Cache directory... "
if [ -w "$APP_DIR/bootstrap/cache" ]; then
    echo -e "${GREEN}✓ Writable${NC}"
else
    echo -e "${RED}✗ Not Writable${NC}"
    ((ISSUES++))
fi
echo ""

# 6. Check disk space
echo -e "${YELLOW}6. Disk Space:${NC}"
DISK_USAGE=$(df -h / | awk 'NR==2 {print $5}' | sed 's/%//')
echo -n "Root partition usage: $DISK_USAGE%... "
if [ "$DISK_USAGE" -lt 80 ]; then
    echo -e "${GREEN}✓ OK${NC}"
elif [ "$DISK_USAGE" -lt 90 ]; then
    echo -e "${YELLOW}⚠ Warning${NC}"
    ((ISSUES++))
else
    echo -e "${RED}✗ Critical${NC}"
    ((ISSUES++))
fi
echo ""

# 7. Check application status
echo -e "${YELLOW}7. Application Status:${NC}"
cd $APP_DIR
echo -n "Maintenance mode... "
if php artisan down --status > /dev/null 2>&1; then
    echo -e "${YELLOW}⚠ Enabled${NC}"
else
    echo -e "${GREEN}✓ Disabled${NC}"
fi

echo -n "Queue workers... "
if pgrep -f "artisan queue:work" > /dev/null; then
    echo -e "${GREEN}✓ Running${NC}"
else
    echo -e "${YELLOW}⚠ Not Running${NC}"
fi
echo ""

# 8. Check recent logs for errors
echo -e "${YELLOW}8. Recent Errors:${NC}"
ERROR_COUNT=$(tail -n 100 $APP_DIR/storage/logs/laravel.log 2>/dev/null | grep -c "ERROR" || echo "0")
echo "Last 100 log lines contain $ERROR_COUNT errors"
if [ "$ERROR_COUNT" -gt 10 ]; then
    echo -e "${RED}⚠ High error rate detected${NC}"
    ((ISSUES++))
fi
echo ""

# Summary
echo -e "${GREEN}========================================${NC}"
if [ $ISSUES -eq 0 ]; then
    echo -e "${GREEN}  ✓ All Checks Passed!${NC}"
else
    echo -e "${YELLOW}  ⚠ Found $ISSUES issue(s)${NC}"
fi
echo -e "${GREEN}========================================${NC}"
echo ""

exit $ISSUES
