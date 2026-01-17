#!/bin/bash

###############################################################################
# Setup Cron Job untuk Laravel Scheduler
# Author: System Administrator
# Usage: sudo ./scripts/setup-cron.sh
###############################################################################

set -e

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  Setup Cron Job untuk Laravel${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Get current directory
APP_DIR=$(pwd)
USER="www-data"

# Check if running as root
if [[ $EUID -ne 0 ]]; then
   echo "This script must be run as root (use sudo)"
   exit 1
fi

echo -e "${YELLOW}➜ Setting up cron job for user: $USER${NC}"
echo -e "${YELLOW}➜ Application directory: $APP_DIR${NC}"
echo ""

# Create cron entry
CRON_ENTRY="* * * * * cd $APP_DIR && php artisan schedule:run >> /dev/null 2>&1"

# Check if cron entry already exists
(crontab -u $USER -l 2>/dev/null | grep -q "artisan schedule:run") && {
    echo -e "${YELLOW}Cron job already exists. Skipping...${NC}"
} || {
    # Add cron entry
    (crontab -u $USER -l 2>/dev/null; echo "$CRON_ENTRY") | crontab -u $USER -
    echo -e "${GREEN}✓ Cron job added successfully${NC}"
}

echo ""
echo -e "${GREEN}Current crontab for $USER:${NC}"
crontab -u $USER -l

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  Setup Completed!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo "The scheduler will run every minute."
echo "Materialized views will refresh automatically based on schedule."
echo ""
echo "To verify:"
echo "1. Check scheduled tasks: php artisan schedule:list"
echo "2. Monitor logs: tail -f storage/logs/materialized-views.log"
echo ""
