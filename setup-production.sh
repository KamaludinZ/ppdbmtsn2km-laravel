#!/bin/bash

# Production Setup Script for Shared Hosting
# PPDB MTsN 2 Kota Malang
# Run this script after uploading files to server

echo "======================================================"
echo "  Production Setup - PPDB MTsN 2 Kota Malang"
echo "======================================================"
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo -e "${RED}Error: artisan file not found. Make sure you're in the project root.${NC}"
    exit 1
fi

echo -e "${YELLOW}Step 1: Checking environment...${NC}"
if [ ! -f ".env" ]; then
    echo -e "${YELLOW}.env file not found. Copying from .env.example...${NC}"
    cp .env.example .env
    echo -e "${GREEN}✓ .env file created${NC}"
else
    echo -e "${GREEN}✓ .env file exists${NC}"
fi

echo ""
echo -e "${YELLOW}Step 2: Setting file permissions...${NC}"
chmod -R 755 .
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chmod 644 .env
echo -e "${GREEN}✓ Permissions set${NC}"

echo ""
echo -e "${YELLOW}Step 3: Generating application key...${NC}"
php artisan key:generate --force
echo -e "${GREEN}✓ Application key generated${NC}"

echo ""
echo -e "${YELLOW}Step 4: Creating storage symlink...${NC}"
echo -e "${YELLOW}Note: Symlink akan dibuat sebagai 'storagepmbm' untuk menghindari konflik${NC}"
php artisan storage:link
echo -e "${GREEN}✓ Storage symlink created (public/storagepmbm -> storage/app/public)${NC}"

echo ""
echo -e "${YELLOW}Step 5: Clearing caches...${NC}"
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
echo -e "${GREEN}✓ Caches cleared${NC}"

echo ""
echo -e "${YELLOW}Step 6: Optimizing for production...${NC}"
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo -e "${GREEN}✓ Application optimized${NC}"

echo ""
echo -e "${YELLOW}Step 7: Running migrations...${NC}"
read -p "Do you want to run migrations? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan migrate --force
    echo -e "${GREEN}✓ Migrations completed${NC}"
else
    echo -e "${YELLOW}⚠ Migrations skipped${NC}"
fi

echo ""
echo -e "${GREEN}======================================================"
echo -e "  Production Setup Completed!"
echo -e "======================================================${NC}"
echo ""
echo "Next steps:"
echo "1. Update .env file with your production database credentials"
echo "2. Set APP_ENV=production and APP_DEBUG=false in .env"
echo "3. Configure your database settings"
echo "4. Test the application"
echo ""
echo "Important files:"
echo "- .env (configure your environment)"
echo "- public/.htaccess (web server configuration)"
echo "- storage/ (ensure it's not publicly accessible)"
echo ""
echo -e "${GREEN}Your application is ready for production!${NC}"
