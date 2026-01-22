# 📦 UPLOAD CONSOLE ERRORS FIX - QUICK GUIDE

## File yang Harus Diupload

### 1. PHP Files (3 files):
```
app/Http/Middleware/SecurityHeaders.php
resources/views/components/asset-loader-simple.blade.php
```

### 2. JavaScript Files (1 file):
```
public/js/image-fallback.js
```

---

## Step-by-Step Upload

### STEP 1: Upload via FTP/cPanel

**Upload file ke:**
```
Local → Production

C:\ppdbmtsn2km-laravel\app\Http\Middleware\SecurityHeaders.php
  → /home/username/public_html/app/Http/Middleware/

C:\ppdbmtsn2km-laravel\resources\views\components\asset-loader-simple.blade.php
  → /home/username/public_html/resources/views/components/

C:\ppdbmtsn2km-laravel\public\js\image-fallback.js
  → /home/username/public_html/public/js/
```

---

### STEP 2: SSH ke Server

```bash
ssh username@yourdomain.com

cd /home/username/public_html
# atau
cd /path/to/your/laravel
```

---

### STEP 3: Publish Livewire Assets

```bash
php artisan livewire:publish --assets
```

**Expected Output:**
```
INFO Publishing [livewire:assets] assets.
Copying directory [.../vendor/livewire/livewire/dist] to [.../public/vendor/livewire] DONE
```

---

### STEP 4: Clear All Cache

```bash
php artisan view:clear
php artisan cache:clear
php artisan config:clear
```

**Expected Output:**
```
INFO Compiled views cleared successfully.
INFO Application cache cleared successfully.
INFO Configuration cache cleared successfully.
```

---

### STEP 5: Verify Files

```bash
# Check Livewire assets
ls -la public/vendor/livewire/livewire.min.js

# Check image fallback
ls -la public/js/image-fallback.js

# Should see both files exist
```

---

### STEP 6: Test

1. **Buka website** di browser
2. **Open Developer Tools** (F12) → Console tab
3. **Check results:**

**Should SEE:**
```
✅ countdown-function.js:116 Countdown and init functions loaded successfully
✅ Image Fallback Handler loaded
```

**Should NOT SEE:**
```
❌ Failed to load resource: 404
❌ CSP violation for axios
```

---

## Quick Copy-Paste Commands

### For Production Server:
```bash
# All in one command
cd /path/to/laravel && \
php artisan livewire:publish --assets && \
php artisan view:clear && \
php artisan cache:clear && \
php artisan config:clear && \
ls -la public/vendor/livewire/livewire.min.js && \
ls -la public/js/image-fallback.js
```

---

## Troubleshooting

### Error: "Class not found"
```bash
composer dump-autoload
php artisan clear-compiled
php artisan optimize
```

### Livewire assets tidak muncul
```bash
# Re-publish dengan force
php artisan livewire:publish --assets --force

# Check permission
chmod -R 755 public/vendor/livewire
chown -R www-data:www-data public/vendor/livewire
```

### CSP masih block
```bash
# Clear config cache
php artisan config:clear

# Check .env
# APP_ENV=production
# APP_DEBUG=false
```

### Gambar placeholder tidak muncul
```bash
# Check file exists
ls public/js/image-fallback.js

# Check permission
chmod 644 public/js/image-fallback.js

# Clear browser cache (Ctrl+Shift+R)
```

---

## Verification Checklist

- [ ] File `SecurityHeaders.php` ter-upload
- [ ] File `asset-loader-simple.blade.php` ter-upload
- [ ] File `image-fallback.js` ter-upload
- [ ] Livewire assets published (`php artisan livewire:publish --assets`)
- [ ] Cache cleared (view, cache, config)
- [ ] File `public/vendor/livewire/livewire.min.js` exists
- [ ] Console clean (no 404, no CSP errors)
- [ ] Missing images show placeholder
- [ ] YouTube thumbnails show placeholder if missing

---

## Expected Console Output

### ✅ GOOD (After Fix):
```
Countdown and init functions loaded successfully
Image Fallback Handler loaded
```

### ❌ BAD (If not fixed):
```
Failed to load resource: 404 (Not Found)
CSP violation: connect-src
```

---

## Contact & Support

Jika ada masalah:
1. Check error log: `tail -f storage/logs/laravel.log`
2. Check permission files
3. Re-upload files
4. Clear all cache again

---

**Status:** Ready to Upload
**Tested:** ✅ Local
**Breaking Changes:** ❌ None
**Downtime Required:** ❌ No
