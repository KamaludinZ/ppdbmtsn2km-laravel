# 🚀 DEPLOYMENT GUIDE - FLEXIBLE ASSET SYSTEM

## 📋 OVERVIEW
Aplikasi ini sekarang mendukung sistem aset yang fleksibel:
- **Development**: Menggunakan Vite untuk hot reload
- **Production**: Bisa pakai Vite build ATAU static CSS
- **CDN**: Cloudflare CDN dengan fallback lokal

## 🎯 KONFIGURASI ASSET

### Environment Variables (.env)
```env
# Asset loading mode: vite, cdn, local, mixed
ASSET_MODE=mixed

# Use Vite in development
USE_VITE_IN_DEV=true

# Asset version for cache busting
ASSET_VERSION=1.0.0
```

### Mode Options:
- **vite**: Gunakan Vite (development only)
- **cdn**: Gunakan CDN untuk Tailwind & Alpine
- **local**: Semua aset dari server
- **mixed**: CDN + local fallback (RECOMMENDED)

## 📦 PERSIAPAN PRODUCTION

### Option 1: Dengan Vite Build (Recommended)
```bash
# 1. Build production assets
npm run build

# 2. Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Set environment
echo "ASSET_MODE=mixed" >> .env.production
```

### Option 2: Tanpa Vite (Simple)
```bash
# 1. Copy production CSS
cp public/css/app-production.css public/css/app.css

# 2. Set environment
echo "ASSET_MODE=cdn" >> .env.production
```

## 📂 STRUKTUR FILE PRODUCTION

```
public/
├── build/                 # Vite build output (optional)
│   └── assets/
│       ├── app-*.css
│       └── app-*.js
├── css/
│   ├── app.css           # Main production CSS
│   ├── app-production.css # Pre-built CSS dengan Tailwind CDN
│   └── tailwind.min.css  # Local Tailwind fallback
├── js/
│   ├── app.js            # Main JavaScript
│   ├── alpine.min.js     # Alpine.js fallback
│   └── countdown-function.js # Custom functions
└── index.php
```

## 🌐 CDN YANG DIGUNAKAN

### Cloudflare CDN (Primary)
- Tailwind CSS: `https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css`
- Alpine.js: `https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.3/cdn.min.js`

### Bunny CDN (Fonts)
- Figtree: `https://fonts.bunny.net/css?family=figtree:300,400,500,600,700`
- Inter: `https://fonts.bunny.net/css?family=inter:300,400,500,600,700`

## 🔧 UPLOAD KE PRODUCTION

### Via cPanel
1. **Compress files** (exclude node_modules)
   ```bash
   zip -r ppdb-production.zip . -x "node_modules/*" ".git/*"
   ```

2. **Upload & Extract**
   - Upload via File Manager
   - Extract di document root

3. **Set Permissions**
   ```bash
   chmod -R 755 storage bootstrap/cache
   chmod 644 public/css/*.css public/js/*.js
   ```

4. **Configure .env**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   ASSET_MODE=mixed
   ```

### Via SSH/Terminal
```bash
# 1. Clone repository
git clone [repository] /path/to/production

# 2. Install dependencies
composer install --no-dev --optimize-autoloader

# 3. Copy environment file
cp .env.production .env

# 4. Generate key
php artisan key:generate

# 5. Migrate database
php artisan migrate --force

# 6. Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Set permissions
chmod -R 755 storage bootstrap/cache
```

## ✅ TESTING PRODUCTION

### 1. Check CSS Loading
```
https://yourdomain.com/css/app.css
```
Should return CSS content, not 404

### 2. Check JavaScript
```
https://yourdomain.com/js/app.js
```
Should return JS content

### 3. Check Console
- Open browser console (F12)
- Should have NO errors about missing assets
- Should have NO "refused to apply style" errors

### 4. Test Fallback
- Block Cloudflare CDN in browser
- Site should still load with local fallback

## 🔄 SWITCHING MODES

### Development → Production
```bash
# Windows
prepare-production.bat

# Linux/Mac
./prepare-production.sh
```

### Production → Development
```bash
npm run dev
```

## 🐛 TROUBLESHOOTING

### CSS tidak load
1. Check file exists: `/public/css/app.css`
2. Check permissions: `644`
3. Clear cache: `php artisan view:clear`

### Tampilan berantakan
1. Check ASSET_MODE in .env
2. Try mode "cdn" or "local"
3. Clear browser cache (Ctrl+F5)

### Alpine.js errors
1. Check `/public/js/countdown-function.js` exists
2. Check Alpine loaded before Livewire

### CDN blocked
1. Switch to ASSET_MODE=local
2. Download Tailwind CSS manually
3. Place in `/public/css/tailwind.min.css`

## 📝 CHECKLIST DEPLOYMENT

- [ ] Build production assets (`npm run build`)
- [ ] Copy correct CSS to `app.css`
- [ ] Set ASSET_MODE in .env
- [ ] Upload all files except node_modules
- [ ] Set file permissions
- [ ] Clear all caches
- [ ] Test in browser
- [ ] Check console for errors

## 🎨 CUSTOM STYLING

Tambah custom CSS di `public/css/app-production.css`:
```css
/* Custom styles */
.custom-class {
    /* your styles */
}
```

## 💡 TIPS

1. **Untuk shared hosting**: Gunakan mode "cdn" atau "mixed"
2. **Untuk VPS**: Bisa gunakan Vite build
3. **Untuk development**: Selalu gunakan Vite
4. **Cache busting**: Update ASSET_VERSION di .env

## 📞 SUPPORT

Jika ada masalah:
1. Check error logs: `storage/logs/laravel.log`
2. Check browser console
3. Try different ASSET_MODE
4. Clear all caches

---

**Note**: Sistem ini dirancang fleksibel. Anda bisa development dengan Vite tapi production tanpa Node.js!