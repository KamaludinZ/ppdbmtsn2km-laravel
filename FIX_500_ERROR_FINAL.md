# FIX ERROR 500 - SOLUSI FINAL
## https://pmbm.mtsn2kotamalang.sch.id

## PENYEBAB ERROR

1. **Production tidak menggunakan Vite** tapi template masih ada `@vite` directive
2. **Tidak ada file CSS/JS** di production (`public/css/app.css` dan `public/js/app.js`)
3. **Cache route corrupt** dari development

## SOLUSI IMMEDIATE (2 MENIT)

### Option A: Script Otomatis (RECOMMENDED)

1. **Extract `production_assets_fix.zip`**
2. **Upload ke root subdomain:**
   - `fix_production_subdomain.php`
   - Folder `css/` ke `public/css/`
   - Folder `js/` ke `public/js/`

3. **Akses via browser:**
   ```
   https://pmbm.mtsn2kotamalang.sch.id/fix_production_subdomain.php?key=fix2026
   ```

4. **HAPUS `fix_production_subdomain.php` setelah berhasil!**

### Option B: Manual via cPanel

1. **Upload Assets:**
   - Upload `public/css/app.css`
   - Upload `public/js/app.js`

2. **Clear Cache via File Manager:**
   - Hapus semua file di `bootstrap/cache/` (kecuali `.gitignore`)
   - Hapus semua file di `storage/framework/views/`
   - Hapus semua file di `storage/framework/cache/`

3. **Edit Blade Templates** (jika belum difix):

   Edit files berikut via File Manager:
   - `resources/views/components/layouts/app.blade.php`
   - `resources/views/components/layouts/dashboard.blade.php`
   - `resources/views/components/layouts/public.blade.php`

   **Cari dan replace:**
   ```blade
   @vite(['resources/css/app.css', 'resources/js/app.js'])
   ```

   **Ganti dengan:**
   ```blade
   <link rel="stylesheet" href="/css/app.css">
   <script src="/js/app.js" defer></script>
   ```

## FILE YANG SUDAH DISIAPKAN

### 1. **production_assets_fix.zip**
   Berisi:
   - `fix_production_subdomain.php` - Script otomatis
   - `css/app.css` - Production CSS
   - `js/app.js` - Production JavaScript

### 2. **Scripts Individual:**
   - `fix_production_subdomain.php` - Fix utama untuk subdomain
   - `compile_assets_production.php` - Generate CSS/JS (sudah dijalankan)

## VERIFIKASI SETELAH FIX

1. Buka: https://pmbm.mtsn2kotamalang.sch.id
2. Jika masih error, check:
   - View source, pastikan ada link ke `/css/app.css` dan `/js/app.js`
   - Cek console browser untuk JavaScript errors
   - Cek `storage/logs/laravel.log` untuk detail error

## STRUKTUR YANG BENAR DI PRODUCTION

```
pmbm.mtsn2kotamalang.sch.id/
├── public/               ← Document root subdomain
│   ├── css/
│   │   └── app.css      ← HARUS ADA!
│   ├── js/
│   │   └── app.js       ← HARUS ADA!
│   ├── .htaccess
│   └── index.php
├── resources/
│   └── views/           ← Templates tidak boleh ada @vite
├── storage/             ← Permission 755
├── bootstrap/cache/     ← Permission 755
└── .env                 ← APP_ENV=production, APP_DEBUG=false
```

## QUICK COMMANDS

Jika ada Terminal di cPanel:

```bash
# Clear semua cache
cd pmbm.mtsn2kotamalang.sch.id
php artisan optimize:clear

# Check status
php artisan about
```

## JIKA MASIH ERROR

1. **Temporary Debug Mode:**
   Edit `.env`: `APP_DEBUG=true`
   Lihat error detail, lalu kembalikan ke `false`

2. **Check Database:**
   ```bash
   php artisan tinker
   >>> DB::connection()->getPdo();
   ```

3. **Check PHP Version:**
   Minimal PHP 8.1

4. **Hubungi Hosting:**
   Minta bantuan untuk:
   - Check error log server
   - Verify PostgreSQL running
   - Check folder permissions

## IMPORTANT NOTES

- **Production TIDAK pakai Vite**, hanya pakai CSS/JS static
- **Document root subdomain** harus pointing ke folder `/public`
- **Permissions** folder `storage` dan `bootstrap/cache` harus 755
- **Selalu HAPUS script fix** setelah berhasil untuk keamanan

---

**Upload dan jalankan `fix_production_subdomain.php` untuk fix otomatis semua masalah!**