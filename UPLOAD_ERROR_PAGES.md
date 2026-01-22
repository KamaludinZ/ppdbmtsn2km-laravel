# 📦 UPLOAD ERROR PAGES KE PRODUCTION

## File yang Harus Diupload

```
resources/views/errors/403.blade.php  ✅
resources/views/errors/404.blade.php  ✅
resources/views/errors/419.blade.php  ✅
resources/views/errors/500.blade.php  ✅
resources/views/errors/503.blade.php  ✅
```

## Langkah Upload

### 1. Upload Via FTP/cPanel File Manager
```
Local Path: C:\ppdbmtsn2km-laravel\resources\views\errors\

Upload ke:
/home/username/public_html/resources/views/errors/
atau
/path/to/your/laravel/resources/views/errors/
```

Upload 5 file:
- 403.blade.php
- 404.blade.php
- 419.blade.php
- 500.blade.php
- 503.blade.php

### 2. SSH ke Server dan Clear Cache

```bash
cd /path/to/your/laravel

# Clear semua cache
php artisan view:clear
php artisan cache:clear
```

### 3. Test (Opsional tapi Recommended)

**Test 503 - Maintenance Mode:**
```bash
php artisan down --secret="admin2026"
```
- Buka browser → halaman maintenance harus tampil sempurna
- Check console → tidak ada error
- Check footer → $appAcronym muncul sesuai setting admin
- Bypass: `https://yourdomain.com/admin2026`
- Matikan: `php artisan up`

**Test 404:**
- Akses URL random: `https://yourdomain.com/halaman-tidak-ada`
- Halaman 404 harus tampil dengan styling yang benar

**Test Console:**
- Buka Developer Tools (F12)
- Check Console tab
- Tidak boleh ada error "Failed to load resource: 503"
- Tidak boleh ada error "Multiple Alpine instances"

### 4. Verify $appAcronym

**Check di Admin Panel:**
1. Login sebagai admin
2. Buka Settings/Pengaturan
3. Cek field "Singkatan Aplikasi" / "App Acronym"
4. Misal terisi: "PPDB"

**Check di Error Page:**
1. Akses halaman error (404 atau maintenance)
2. Lihat footer
3. Harus muncul: "© 2026 PPDB MTsN 2 Kota Malang. Hak Cipta Dilindungi."

## Quick Commands Reference

```bash
# SSH ke server
ssh username@yourdomain.com

# Navigate ke Laravel
cd /home/username/public_html
# atau
cd /path/to/your/laravel

# Clear cache
php artisan view:clear && php artisan cache:clear

# Test maintenance mode
php artisan down --secret="admin123"
# Akses: https://yourdomain.com
# Bypass: https://yourdomain.com/admin123
php artisan up

# Check logs jika ada error
tail -f storage/logs/laravel.log
```

## Troubleshooting

### Error: "Class 'App\Models\Setting' not found"
```bash
# Pastikan Setting model ada di server
ls -la app/Models/Setting.php

# Jika tidak ada, upload juga:
app/Models/Setting.php
```

### Tampilan Berantakan (CSS tidak load)
**Penyebab:** CDN Tailwind ter-block atau internet issue

**Solusi:**
1. Check internet connection server
2. Test CDN: `curl -I https://cdn.tailwindcss.com`
3. Jika ter-block, ganti CDN di file error pages:
   ```blade
   <script src="https://unpkg.com/tailwindcss@3.4.0/src/cdn.js"></script>
   ```

### Footer tidak tampil $appAcronym
**Penyebab:** Setting belum ada di database

**Solusi:**
```bash
php artisan tinker
>>> \App\Models\Setting::set('app_acronym', 'PPDB', 'string');
>>> exit
```

## Checklist Setelah Upload

- [ ] 5 file error pages ter-upload dengan benar
- [ ] Clear cache di server (view:clear, cache:clear)
- [ ] Test halaman 404 (akses URL random)
- [ ] Test halaman 503 (php artisan down)
- [ ] Check console browser (tidak ada error)
- [ ] Check footer (muncul $appAcronym sesuai setting)
- [ ] Test bypass maintenance mode (dengan secret token)
- [ ] Matikan maintenance mode (php artisan up)

## Expected Results

✅ Halaman error tampil sempurna dengan styling Tailwind
✅ Footer menampilkan akronim sesuai setting admin
✅ Tidak ada error di browser console
✅ Responsive di semua device (mobile, tablet, desktop)
✅ Loading cepat (CDN umumnya lebih cepat)

---

**Ready to Upload:** ✅ YES
**Last Updated:** 20 Januari 2026
