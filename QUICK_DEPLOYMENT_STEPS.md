# QUICK DEPLOYMENT STEPS - PPDB MTsN 2
## Langkah Cepat Deploy ke https://pmbm.mtsn2kotamalang.sch.id/

### 📋 Di Local Development (Sekarang)

```bash
# 1. Build assets production
npm run build

# 2. Copy manifest ke root
powershell -Command "Copy-Item 'public\build\.vite\manifest.json' -Destination 'public\build\manifest.json'"

# 3. Jalankan script deployment
php deploy-ppdb-without-vite.php

# 4. Edit .env.production dengan credentials production Anda
```

### 📦 Upload ke Server

**Folder/File yang WAJIB di-upload:**
```
✅ app/
✅ bootstrap/
✅ config/
✅ database/
✅ public/ (TERMASUK public/build/)
✅ resources/
✅ routes/
✅ storage/ (struktur saja)
✅ .env.production (rename jadi .env)
✅ .htaccess
✅ composer.json
✅ composer.lock
✅ artisan
✅ verify-production.php (untuk testing)
```

**JANGAN upload:**
```
❌ node_modules/
❌ package.json
❌ package-lock.json
❌ vite.config.js
❌ .git/
❌ tests/
```

### ⚙️ Di Production Server

```bash
# 1. Rename environment file
mv .env.production .env

# 2. Install dependencies (jika vendor belum di-upload)
composer install --optimize-autoloader --no-dev

# 3. Generate key
php artisan key:generate

# 4. Run migrations
php artisan migrate --force

# 5. Create storage link
php artisan storage:link

# 6. Set permissions
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# 7. Optimize
php artisan optimize
```

### ✅ Verifikasi

1. Buka: https://pmbm.mtsn2kotamalang.sch.id/verify-production.php?token=verify2025
2. Check semua status hijau
3. Test homepage dan login
4. Hapus verify-production.php setelah selesai

### 🆘 Jika Ada Masalah

```bash
# Check error
tail -f storage/logs/laravel.log

# Clear all cache
php artisan optimize:clear

# Rebuild cache
php artisan optimize
```

---

**Files Helper yang Sudah Dibuat:**
- `deploy-ppdb-without-vite.php` - Script deployment otomatis
- `DEPLOYMENT_GUIDE_PRODUCTION.md` - Panduan lengkap
- `DEPLOYMENT_CHECKLIST_PPDB.txt` - Checklist deployment
- `verify-production.php` - Script verifikasi production
- `.env.production` - Template environment production
- `.htaccess` - Konfigurasi untuk subdomain

**Build Assets Location:**
- CSS: `public/build/assets/app-DPXrAvEv.css`
- JS: `public/build/assets/app-C8iUzNnm.js`
- Manifest: `public/build/manifest.json`

✅ **Aplikasi siap deploy tanpa Vite!**