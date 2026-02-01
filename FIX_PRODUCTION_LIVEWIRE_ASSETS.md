# Fix Production Login & Livewire Assets Issue

**Date**: 30 Januari 2026
**Status**: 🚨 CRITICAL - Production Login Gagal
**Error**: `livewire.min.js?id=8b575521` tidak ditemukan

---

## 🔍 Diagnosis Masalah

### Symptom:
- ❌ Login tidak berhasil di production
- ❌ Console error: `https://pmbm.mtsn2kotamalang.sch.id/vendor/livewire/livewire.min.js?id=8b575521`
- ✅ Cache sudah cleared (config, cache, view)

### Root Cause:
**Livewire assets belum terpublish di production**

Livewire v4 memerlukan assets JavaScript yang harus dipublish ke `public/vendor/livewire/`. Ketika file ini tidak ada, halaman tidak bisa berfungsi dengan benar dan login gagal.

---

## ✅ Solusi Lengkap (Step-by-Step)

### Step 1: Connect ke Production Server

```bash
# Connect via SSH
ssh mtsnkot4@103.30.244.77

# Navigate ke direktori aplikasi
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
```

### Step 2: Publish Livewire Assets

```bash
# Publish Livewire assets (CRITICAL)
php artisan livewire:publish --force

# Output yang diharapkan:
# Livewire assets published successfully.
```

**Apa yang dilakukan command ini:**
- Meng-copy file Livewire JavaScript dari `vendor/livewire/` ke `public/vendor/livewire/`
- File yang akan dibuat:
  - `public/vendor/livewire/livewire.min.js`
  - `public/vendor/livewire/livewire.min.js.map`
  - `public/vendor/livewire/livewire.esm.js`
  - `public/vendor/livewire/manifest.json`

### Step 3: Verify Assets Terbuat

```bash
# Check apakah file livewire sudah ada
ls -la public/vendor/livewire/

# Output yang diharapkan:
# livewire.min.js
# livewire.min.js.map
# livewire.esm.js
# manifest.json
```

### Step 4: Set Permission yang Benar

```bash
# Set permission untuk direktori vendor
chmod -R 755 public/vendor/

# Set permission untuk file livewire
chmod 644 public/vendor/livewire/*
```

### Step 5: Clear All Caches (Lagi)

```bash
# Clear semua cache setelah publish assets
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# IMPORTANT: Clear OPcache jika ada
php artisan optimize:clear
```

### Step 6: Verify Symlink Storage

```bash
# Check apakah symlink sudah benar
ls -la public/ | grep storage

# Output yang diharapkan:
# storagepmbm -> /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/storage/app/public

# Jika belum ada atau salah, buat ulang:
rm -f public/storagepmbm
php artisan storage:link
```

### Step 7: Test di Browser

1. **Buka browser** (gunakan Incognito/Private mode)
2. **Clear browser cache**: Ctrl + Shift + Delete
3. **Visit**: `https://pmbm.mtsn2kotamalang.sch.id`
4. **Open Developer Console** (F12)
5. **Check Console Tab**: Seharusnya tidak ada error livewire.min.js
6. **Try Login**: Login seharusnya berfungsi

---

## 🔍 Troubleshooting

### Issue 1: File livewire.min.js Masih 404

**Check 1: Verify file ada di server**
```bash
# Check apakah file benar-benar ada
cat public/vendor/livewire/livewire.min.js | head -n 1

# Jika error "No such file", artinya publish gagal
```

**Fix:**
```bash
# Coba publish lagi dengan verbose
php artisan livewire:publish --force -vvv

# Jika masih gagal, manual copy:
mkdir -p public/vendor/livewire
cp -r vendor/livewire/livewire/dist/* public/vendor/livewire/
```

### Issue 2: Permission Denied

**Check:**
```bash
# Check owner dan permission
ls -la public/vendor/livewire/
```

**Fix:**
```bash
# Set owner ke user yang benar
chown -R mtsnkot4:mtsnkot4 public/vendor/

# Set permission
chmod -R 755 public/vendor/
chmod 644 public/vendor/livewire/*
```

### Issue 3: .htaccess Blocking Access

**Check:**
```bash
# Check .htaccess di public/
cat public/.htaccess | grep -A 5 "vendor"
```

**Fix jika vendor di-block:**
```bash
# Edit .htaccess dan pastikan vendor tidak di-block
nano public/.htaccess

# Pastikan ada:
# Allow access to vendor directory
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_URI} !^/vendor/
    # ... rest of rules
</IfModule>
```

### Issue 4: Nginx Config Issue (Jika pakai Nginx)

**Check Nginx config:**
```bash
# Check nginx config
cat /etc/nginx/sites-available/pmbm.mtsn2kotamalang.sch.id
```

**Fix: Pastikan location /vendor/ tidak di-deny:**
```nginx
location /vendor/ {
    alias /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/public/vendor/;
    access_log off;
    expires max;
}
```

### Issue 5: CDN/Cloudflare Caching

**Fix:**
```bash
# Jika menggunakan Cloudflare:
# 1. Login ke Cloudflare dashboard
# 2. Purge Cache untuk domain pmbm.mtsn2kotamalang.sch.id
# 3. Atau tambahkan cache-busting: ?v=2 di URL
```

---

## 🛠️ Alternative: Manual Asset Copy (Jika artisan gagal)

Jika `php artisan livewire:publish` gagal, copy manual dari local:

### Dari Local (Windows):

```bash
# Di local, zip livewire assets
cd C:\ppdbmtsn2km-laravel\public\vendor\livewire
powershell Compress-Archive -Path * -DestinationPath C:\livewire-assets.zip
```

### Upload ke Production:

```bash
# Upload via SCP
scp C:\livewire-assets.zip mtsnkot4@103.30.244.77:/home/mtsnkot4/

# Di server, extract
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/public/vendor/
mkdir -p livewire
cd livewire
unzip /home/mtsnkot4/livewire-assets.zip
chmod 644 *
```

---

## 📋 Quick Fix Script (All-in-One)

Buat script untuk fix semua sekaligus:

```bash
#!/bin/bash
# File: fix_livewire_assets.sh

echo "🔧 Fixing Livewire Assets..."

# Navigate to app directory
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Publish Livewire assets
echo "📦 Publishing Livewire assets..."
php artisan livewire:publish --force

# Set permissions
echo "🔐 Setting permissions..."
chmod -R 755 public/vendor/
chmod 644 public/vendor/livewire/*

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan optimize:clear

# Verify files
echo "✅ Verifying assets..."
if [ -f "public/vendor/livewire/livewire.min.js" ]; then
    echo "✅ livewire.min.js found!"
    ls -lh public/vendor/livewire/livewire.min.js
else
    echo "❌ ERROR: livewire.min.js not found!"
    exit 1
fi

echo "✅ All done! Please test login in browser."
```

**Usage:**
```bash
# Upload script
scp fix_livewire_assets.sh mtsnkot4@103.30.244.77:/home/mtsnkot4/

# Run script
ssh mtsnkot4@103.30.244.77
chmod +x fix_livewire_assets.sh
./fix_livewire_assets.sh
```

---

## 🔍 Verification Checklist

Setelah menjalankan fix, verify berikut:

### ✅ Server-Side Check:
```bash
# 1. Check file exists
[ -f public/vendor/livewire/livewire.min.js ] && echo "✅ File exists" || echo "❌ File missing"

# 2. Check file size (should be > 0)
du -h public/vendor/livewire/livewire.min.js

# 3. Check permissions
ls -l public/vendor/livewire/livewire.min.js
# Should be: -rw-r--r-- (644)

# 4. Check content
head -c 100 public/vendor/livewire/livewire.min.js
# Should start with JavaScript code, not HTML error page
```

### ✅ Browser-Side Check:
```
1. Open: https://pmbm.mtsn2kotamalang.sch.id
2. Open DevTools (F12)
3. Go to Network tab
4. Filter: livewire
5. Refresh page (Ctrl+F5)
6. Check: livewire.min.js should be:
   - Status: 200 (not 404)
   - Type: application/javascript
   - Size: ~100-200KB
```

### ✅ Functional Check:
```
1. Visit login page
2. Enter credentials
3. Click login button
4. Should login successfully (no errors)
5. Console should be clean (no red errors)
```

---

## 📝 Root Cause Analysis

### Mengapa Masalah Ini Terjadi?

**Livewire v4 Asset Publishing:**
- Livewire v4 memerlukan assets JavaScript yang di-publish ke folder public
- Saat `composer install` atau `composer update`, assets TIDAK otomatis ter-publish
- Harus manual run `php artisan livewire:publish`

**Common Scenarios:**
1. ✅ **Local Development**: Assets ter-publish saat `composer install`
2. ❌ **Production**: Sering lupa run `livewire:publish` setelah update

### Pencegahan di Masa Depan:

**Option 1: Tambahkan ke composer.json post-install hook**
```json
{
    "scripts": {
        "post-autoload-dump": [
            "@php artisan package:discover --ansi",
            "@php artisan livewire:publish --force"
        ]
    }
}
```

**Option 2: Tambahkan ke deployment script**
```bash
# Dalam deploy.sh
composer install --no-dev --optimize-autoloader
php artisan livewire:publish --force
php artisan optimize:clear
```

**Option 3: Include assets di version control** (NOT RECOMMENDED tapi works)
```bash
# Add public/vendor/ to git (normally in .gitignore)
git add -f public/vendor/livewire/
git commit -m "Include Livewire assets"
```

---

## 🚀 Post-Fix Actions

Setelah masalah teratasi:

### 1. Update Documentation
```bash
# Update deployment checklist
echo "- [ ] Run: php artisan livewire:publish --force" >> DEPLOYMENT_CHECKLIST.md
```

### 2. Test All Features
- [ ] Login as Admin
- [ ] Login as Panitia (all 4 roles)
- [ ] Login as Pendaftar
- [ ] Test form submissions
- [ ] Test file uploads
- [ ] Test all Livewire components

### 3. Monitor Logs
```bash
# Check for any new errors
tail -f storage/logs/laravel.log

# Check Livewire specific errors
grep -i "livewire" storage/logs/laravel.log
```

---

## 📞 Emergency Contact

Jika masalah masih berlanjut setelah semua langkah di atas:

### Quick Emergency Fix:
```bash
# Emergency rollback ke versi sebelumnya
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
git log --oneline -5
git reset --hard [commit-hash-sebelum-update]
php artisan optimize:clear
```

### Check Logs:
```bash
# Laravel logs
tail -100 storage/logs/laravel.log

# Nginx/Apache logs
tail -100 /var/log/nginx/error.log

# PHP-FPM logs
tail -100 /var/log/php8.x-fpm.log
```

---

## ✅ Expected Result

Setelah semua fix diterapkan:

```
✅ File public/vendor/livewire/livewire.min.js exists
✅ File size: ~150-200 KB
✅ Permissions: 644 (rw-r--r--)
✅ Browser console: No errors
✅ Login: Works perfectly
✅ All Livewire components: Functional
```

---

**Created**: 30 Januari 2026
**Priority**: 🚨 CRITICAL
**Estimated Fix Time**: 5-10 minutes
**Status**: ⚠️ AWAITING EXECUTION

🎯 **Next Action**: SSH ke production dan jalankan `php artisan livewire:publish --force`
