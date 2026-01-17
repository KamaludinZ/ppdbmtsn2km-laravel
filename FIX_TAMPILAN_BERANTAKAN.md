# FIX TAMPILAN BERANTAKAN DI PRODUCTION

## Masalah yang Ditemukan:
1. **CSS tidak ter-load** - Path salah, mencari `/css/tailwindcss` bukan `/css/app.css`
2. **Countdown function tidak ada** - Alpine.js error karena function `countdown` belum didefinisikan
3. **Assets belum lengkap** di production

## SOLUSI CEPAT (2 Menit)

### Option A: Script Otomatis (RECOMMENDED)

1. **Extract `FIX_PRODUCTION_ASSETS.zip`**

2. **Upload via cPanel File Manager:**
   - Upload `fix_production_assets.php` ke root subdomain
   - Upload folder `css/` ke `public/css/` (replace existing)
   - Upload folder `js/` ke `public/js/` (replace existing)

3. **Jalankan script:**
   ```
   https://pmbm.mtsn2kotamalang.sch.id/fix_production_assets.php?key=fix2026
   ```

4. **Clear browser cache** (Ctrl+F5)

5. **HAPUS script setelah berhasil!**

### Option B: Manual Upload

1. **Upload files berikut ke production:**

   **File CSS** → `public/css/app.css`:
   - Sudah include Tailwind CSS dari CDN
   - Include fonts dan styles
   - Support Livewire loading states

   **File JS** → `public/js/app.js`:
   - Include countdown function
   - CSRF token setup
   - Form validation
   - Livewire hooks

2. **Clear cache di server:**
   ```bash
   php artisan view:clear
   php artisan cache:clear
   ```

3. **Clear browser cache** (Ctrl+F5)

## Test Setelah Fix

1. **Buka website:**
   ```
   https://pmbm.mtsn2kotamalang.sch.id
   ```

2. **Cek di browser console:**
   - Tidak ada error CSS
   - Tidak ada error countdown
   - Message: "PPDB Application Loaded - Production Mode"

3. **Test countdown:**
   - Countdown timer harus berjalan
   - Tidak ada Alpine.js errors

## File yang Sudah Disiapkan:

### 1. `FIX_PRODUCTION_ASSETS.zip`
Berisi:
- `css/app.css` - Production CSS lengkap
- `js/app.js` - JavaScript dengan countdown function
- `fix_production_assets.php` - Script otomatis

### 2. Files Individual:
- `public/css/app.css` - CSS production
- `public/js/app.js` - JS production dengan countdown

## Struktur Assets yang Benar:

```
public/
├── css/
│   └── app.css         ← Main CSS (include Tailwind)
├── js/
│   ├── app.js          ← Main JS (include countdown)
│   └── livewire.js     ← Livewire support
└── index.php
```

## Troubleshooting:

### Jika masih berantakan:

1. **Clear browser cache completely:**
   - Chrome: Ctrl+Shift+Del → Clear browsing data
   - Atau buka di Incognito mode

2. **Check di View Source:**
   - Pastikan ada: `<link rel="stylesheet" href="/css/app.css">`
   - Pastikan ada: `<script src="/js/app.js">`

3. **Test dengan test page:**
   ```
   https://pmbm.mtsn2kotamalang.sch.id/test.html
   ```

### Jika countdown masih error:

JavaScript countdown function sudah include di `app.js`:
```javascript
window.countdown = function(targetDate) {
    // Function implementation
}
```

### Jika CSS masih tidak load:

1. Check permission folder `public/css/` - harus 755
2. Pastikan file `app.css` ada dan bisa diakses
3. Test direct access: `https://pmbm.mtsn2kotamalang.sch.id/css/app.css`

## Quick Commands:

```bash
# Clear cache di server
php artisan optimize:clear

# Check if files exist
ls -la public/css/
ls -la public/js/

# Check permissions
chmod 755 public/css
chmod 755 public/js
chmod 644 public/css/app.css
chmod 644 public/js/app.js
```

## PENTING:

- Production **TIDAK pakai Vite/npm**, hanya CSS/JS static
- Tailwind CSS di-load dari CDN untuk production
- Countdown function sudah include di `app.js`
- Clear browser cache setelah update!

---

**Upload dan jalankan `fix_production_assets.php` untuk fix otomatis!**