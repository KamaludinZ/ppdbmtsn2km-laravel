#!/bin/bash
# Test in Production Mode
echo "Testing in PRODUCTION mode..."
APP_ENV=production APP_DEBUG=false php artisan serve --port=8001