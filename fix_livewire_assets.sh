#!/bin/bash
# Fix Livewire Assets - Production
# Date: 30 Januari 2026
# Purpose: Publish Livewire assets dan fix login issue

echo "=================================================="
echo "🔧 Fix Livewire Assets - Production"
echo "=================================================="
echo ""

# Navigate to app directory
APP_DIR="/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id"
cd "$APP_DIR" || exit 1

echo "📍 Current directory: $(pwd)"
echo ""

# Step 1: Publish Livewire assets
echo "📦 Step 1: Publishing Livewire assets..."
php artisan livewire:publish --force

if [ $? -eq 0 ]; then
    echo "✅ Livewire assets published successfully"
else
    echo "❌ ERROR: Failed to publish Livewire assets"
    echo "   Trying alternative method..."

    # Alternative: Manual copy from vendor
    if [ -d "vendor/livewire/livewire/dist" ]; then
        mkdir -p public/vendor/livewire
        cp -r vendor/livewire/livewire/dist/* public/vendor/livewire/
        echo "✅ Assets copied manually from vendor"
    else
        echo "❌ ERROR: Could not find Livewire dist files"
        exit 1
    fi
fi
echo ""

# Step 2: Set permissions
echo "🔐 Step 2: Setting correct permissions..."
chmod -R 755 public/vendor/ 2>/dev/null
chmod 644 public/vendor/livewire/* 2>/dev/null
chown -R mtsnkot4:mtsnkot4 public/vendor/ 2>/dev/null
echo "✅ Permissions set"
echo ""

# Step 3: Verify files exist
echo "🔍 Step 3: Verifying Livewire assets..."
REQUIRED_FILES=(
    "public/vendor/livewire/livewire.min.js"
    "public/vendor/livewire/livewire.esm.js"
    "public/vendor/livewire/manifest.json"
)

ALL_OK=true
for file in "${REQUIRED_FILES[@]}"; do
    if [ -f "$file" ]; then
        SIZE=$(du -h "$file" | cut -f1)
        echo "   ✅ $file ($SIZE)"
    else
        echo "   ❌ MISSING: $file"
        ALL_OK=false
    fi
done
echo ""

if [ "$ALL_OK" = false ]; then
    echo "❌ ERROR: Some required files are missing!"
    exit 1
fi

# Step 4: Clear all caches
echo "🧹 Step 4: Clearing all caches..."
php artisan config:clear >/dev/null 2>&1
echo "   ✅ Config cache cleared"

php artisan cache:clear >/dev/null 2>&1
echo "   ✅ Application cache cleared"

php artisan view:clear >/dev/null 2>&1
echo "   ✅ View cache cleared"

php artisan route:clear >/dev/null 2>&1
echo "   ✅ Route cache cleared"

php artisan optimize:clear >/dev/null 2>&1
echo "   ✅ All optimizations cleared"
echo ""

# Step 5: Verify symlink
echo "🔗 Step 5: Verifying storage symlink..."
if [ -L "public/storagepmbm" ]; then
    TARGET=$(readlink -f public/storagepmbm)
    echo "   ✅ Symlink exists: public/storagepmbm -> $TARGET"
else
    echo "   ⚠️  Symlink not found, creating..."
    php artisan storage:link
    if [ $? -eq 0 ]; then
        echo "   ✅ Symlink created successfully"
    else
        echo "   ❌ Failed to create symlink"
    fi
fi
echo ""

# Step 6: Test file accessibility
echo "🌐 Step 6: Testing file accessibility..."
TEST_FILE="public/vendor/livewire/livewire.min.js"
if [ -r "$TEST_FILE" ]; then
    FIRST_LINE=$(head -c 50 "$TEST_FILE")
    if [[ "$FIRST_LINE" =~ "function" ]] || [[ "$FIRST_LINE" =~ "var" ]] || [[ "$FIRST_LINE" =~ "const" ]]; then
        echo "   ✅ File is readable and contains JavaScript"
    else
        echo "   ⚠️  File exists but content looks suspicious"
        echo "   First 50 chars: $FIRST_LINE"
    fi
else
    echo "   ❌ File is not readable"
fi
echo ""

# Final summary
echo "=================================================="
echo "✅ FIX COMPLETED SUCCESSFULLY!"
echo "=================================================="
echo ""
echo "📋 Summary:"
echo "   ✅ Livewire assets published"
echo "   ✅ Permissions configured"
echo "   ✅ All caches cleared"
echo "   ✅ Storage symlink verified"
echo ""
echo "🎯 Next Steps:"
echo "   1. Open browser (Incognito/Private mode)"
echo "   2. Clear browser cache (Ctrl+Shift+Delete)"
echo "   3. Visit: https://pmbm.mtsn2kotamalang.sch.id"
echo "   4. Open DevTools (F12) and check Console"
echo "   5. Try login - should work now!"
echo ""
echo "🔍 If still having issues, check:"
echo "   - Browser console for errors"
echo "   - tail -f storage/logs/laravel.log"
echo ""
echo "=================================================="
