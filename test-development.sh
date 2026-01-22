#!/bin/bash
# Test in Development Mode
echo "Testing in DEVELOPMENT mode..."
APP_ENV=local APP_DEBUG=true php artisan serve --port=8002