# SOLUSI ERROR 500 - Masalah Vite & Cache

## Masalah Utama yang Ditemukan

1. **Vite Manifest Location Issue**
   - File `manifest.json` ada di folder `.vite` padahal Laravel mencarinya di root `build`
   - Ini menyebabkan error saat Laravel mencoba load assets

2. **Cache Route Duplicate**
   - Ada duplicate route name 'login' yang menyebabkan route cache error

3. **Missing Build Assets**
   - Assets JavaScript dan CSS belum di-build untuk production

## Solusi Immediate

### Option 1: Upload Script Emergency Fix (RECOMMENDED)

1. **Upload file `emergency_fix_production.php`** ke root website
2. **Akses via browser**:
   ```
   https://pmbm.mtsn2kotamalang.sch.id/emergency_fix_production.php?key=emergency2026
   ```
3. **HAPUS file setelah berhasil!**

Script ini akan:
- Fix lokasi manifest.json
- Clear semua cache
- Create fallback assets jika perlu
- Check permissions
- Rebuild cache

### Option 2: Manual Fix via cPanel

#### A. Upload Build Files
Upload folder `public/build/` lengkap dari local ke production, termasuk:
- `public/build/manifest.json` (PENTING!)
- `public/build/assets/` (folder dengan CSS dan JS files)
- `public/build/.vite/` (optional)

#### B. Clear Cache via File Manager
1. Masuk ke folder `bootstrap/cache/`, hapus semua file PHP
2. Masuk ke folder `storage/framework/views/`, hapus semua file PHP
3. Masuk ke folder `storage/framework/cache/`, hapus semua

#### C. Fix Permissions
Set permission 755 untuk:
- Folder `storage/` dan semua subfolder
- Folder `bootstrap/cache/`

### Option 3: Disable Vite Temporarily

Jika urgent dan tidak bisa build assets, edit file `.env` di production:
```env
VITE_ENABLED=false
```

Lalu upload fallback assets:
- `public/css/app.css` (basic CSS)
- `public/js/app.js` (basic JS)

## Build Assets Lokal untuk Production

Jika belum build assets:

```bash
# Di komputer lokal
npm install
npm run build

# Folder yang harus di-upload:
# public/build/ (SELURUH FOLDER)
```

## Files yang Sudah Disiapkan

1. **emergency_fix_production.php** - Script otomatis fix semua masalah (UPLOAD INI!)
2. **production_fix.php** - Script fix standar
3. **clear_cache_production.php** - Script clear cache only

## Struktur Build yang Benar

```
public/
  build/
    manifest.json         <-- HARUS ADA!
    assets/
      app-xxxxx.css      <-- Generated CSS
      app-xxxxx.js       <-- Generated JS
    .vite/
      manifest.json      <-- Optional
```

## Verifikasi Setelah Fix

1. Akses website: https://pmbm.mtsn2kotamalang.sch.id
2. Jika masih error, cek:
   - Database connection di `.env`
   - PHP version (minimal 8.1)
   - PostgreSQL service running

## PENTING!

**Manifest.json adalah file KRITIS untuk Laravel dengan Vite!**

Jika tidak ada atau salah lokasi, website akan error 500.

## Quick Checklist

- [ ] Upload `emergency_fix_production.php` dan jalankan
- [ ] Pastikan `public/build/manifest.json` ada
- [ ] Clear semua cache
- [ ] Set permission folder storage ke 755
- [ ] Test website
- [ ] HAPUS semua script fix setelah berhasil!