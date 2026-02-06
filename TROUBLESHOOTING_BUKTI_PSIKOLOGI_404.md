# Troubleshooting: Bukti Psikologi Tidak Muncul

**Untuk User yang mengalami masalah gambar bukti tidak muncul setelah upload**

---

## 🔴 Symptoms

1. Setelah upload bukti tes psikologi sebagai pendaftar, gambar tidak muncul
2. Muncul placeholder "Gagal memuat gambar"
3. Admin/Panitia klik "Lihat" dapat 404
4. Browser console menunjukkan error 404 atau 403

---

## ✅ Quick Fix Steps

### Step 1: Upload Files Terbaru

```bash
upload_fix_404_bukti_psikologi_COMPLETE.bat
```

File ini akan:
- Upload controller dengan method `viewBuktiBayar()`
- Upload route baru `/view/bukti-bayar/{pendaftar}/{type}`
- Upload view yang sudah di-update
- Clear semua cache

### Step 2: Hard Refresh Browser

**Penting!** Setelah upload, user HARUS hard refresh:

- **Windows:** `Ctrl + Shift + R` atau `Ctrl + F5`
- **Mac:** `Cmd + Shift + R`
- **Atau:** Clear browser cache dan reload

### Step 3: Verify di Production

SSH ke server dan jalankan:

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# 1. Check route exists
php artisan route:list | grep "view.bukti-bayar"
# Expected output: GET | view/bukti-bayar/{pendaftar}/{type}

# 2. Check controller method
grep -n "function viewBuktiBayar" app/Http/Controllers/SecureFileController.php
# Expected: Should show line number with function definition

# 3. Check view updated
grep -n "route.*view.bukti-bayar.*psikologi" resources/views/livewire/pendaftar/tes-psikologi.blade.php
# Expected: Should show line numbers (around 325 and 383)

# 4. Clear cache again
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan config:clear
```

---

## 🔍 Diagnosis Checklist

### Check 1: Route Registered?

```bash
php artisan route:list --name=view.bukti-bayar
```

**Expected Output:**
```
GET|HEAD  view/bukti-bayar/{pendaftar}/{type}
          SecureFileController@viewBuktiBayar
          view.bukti-bayar
          auth
```

**If NOT found:** Route belum ter-register
- **Fix:** Upload `routes/web.php` lagi
- Run: `php artisan route:clear`

### Check 2: Controller Method Exists?

```bash
grep -A 30 "function viewBuktiBayar" app/Http/Controllers/SecureFileController.php
```

**Expected:** Should show complete method definition

**If NOT found:** Controller belum ter-update
- **Fix:** Upload `SecureFileController.php` lagi

### Check 3: View Updated?

```bash
# Check pendaftar view
grep "view.bukti-bayar" resources/views/livewire/pendaftar/tes-psikologi.blade.php

# Check admin view
grep "view.bukti-bayar" resources/views/livewire/admin/partials/tes-psikologi-data.blade.php
```

**Expected:** Should find routes in both files

**If NOT found:** View belum ter-update
- **Fix:** Upload view files lagi
- Run: `php artisan view:clear`

### Check 4: File Permissions

```bash
# Check storage directory writable
ls -la storage/app/private/bukti_bayar/

# Check file exists for specific pendaftar (ganti 27 dengan ID real)
ls -la storage/app/private/bukti_bayar/27/

# Check file readable
stat storage/app/private/bukti_bayar/27/*.jpg
```

**Expected:**
- Directory: `drwxr-xr-x` (755)
- Files: `-rw-r--r--` (644)

**If permission denied:**
```bash
chmod 755 storage/app/private/bukti_bayar
chmod 755 storage/app/private/bukti_bayar/*
chmod 644 storage/app/private/bukti_bayar/*/*.jpg
```

### Check 5: Test Route Directly

**Via browser (logged in):**
```
https://pmbm.mtsn2kotamalang.sch.id/view/bukti-bayar/{ID_PENDAFTAR}/psikologi
```

Ganti `{ID_PENDAFTAR}` dengan ID real pendaftar yang punya bukti.

**Via curl:**
```bash
curl -I https://pmbm.mtsn2kotamalang.sch.id/view/bukti-bayar/27/psikologi
```

**Expected Response:**
- **200 OK** = Success, file loaded
- **302 Found** = Redirect to login (not logged in)
- **403 Forbidden** = Authorization issue
- **404 Not Found** = Route not found or file missing
- **500 Error** = Server error, check logs

### Check 6: Check Logs

```bash
# Check recent errors
tail -100 storage/logs/laravel.log | grep -i "bukti\|secure\|404\|403"

# Check specific error details
tail -100 storage/logs/laravel.log | grep -A 10 "SecureFileController"
```

**Common errors:**
- `Class 'SecureFileController' not found` = Autoload issue, run `composer dump-autoload`
- `Call to undefined method` = Method tidak ter-upload
- `File not found` = Path salah atau file tidak ada
- `Unauthorized` = Authorization logic issue

---

## 🐛 Common Issues & Solutions

### Issue 1: Gambar Masih 404 Setelah Upload Files

**Cause:** View cache belum ter-clear

**Solution:**
```bash
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear
```

Kemudian user hard refresh browser.

### Issue 2: Error "Route [view.bukti-bayar] not defined"

**Cause:** Route belum ter-register

**Solution:**
```bash
php artisan route:clear
php artisan route:cache  # Optional: cache untuk performance
```

Verify:
```bash
php artisan route:list | grep view.bukti-bayar
```

### Issue 3: 403 Forbidden

**Cause:** Authorization check gagal

**Debug:**
1. Check user logged in?
2. Check relasi `Pendaftar->tesPsikologiPendaftar` ada?
3. Check user ID match dengan pendaftar?

**Solution:**
Check di `SecureFileController.php` method `canAccessBuktiBayar()`:
```php
// Admin dan Panitia harus bisa akses
if ($user->isAdmin() || $user->isPanitia()) {
    return true;
}

// Pendaftar hanya bisa akses milik sendiri
if ($user->isPendaftar() && $user->id === $pendaftar->user_id) {
    return true;
}
```

### Issue 4: 500 Internal Server Error

**Cause:** PHP error di controller

**Check logs:**
```bash
tail -50 storage/logs/laravel.log
```

**Common causes:**
- Relasi model tidak ada
- Method tidak ditemukan
- File path salah
- Disk 'private' tidak configured

**Solution:** Check logs dan fix error

### Issue 5: File Tidak Ditemukan di Storage

**Cause:** File belum ter-upload atau path salah

**Verify file exists:**
```bash
# Find all bukti psikologi files
find storage/app/private/bukti_bayar -name "*bukti_psikologi*" -type f

# Check specific pendaftar
ls -la storage/app/private/bukti_bayar/{ID_PENDAFTAR}/
```

**If file exists tapi masih error:**
- Check permissions (chmod 644)
- Check disk config di `config/filesystems.php`

### Issue 6: Browser Caching

**Symptom:** User masih lihat error meskipun sudah fixed

**Solution:** Instruksikan user untuk:
1. Hard refresh: `Ctrl + Shift + R`
2. Clear browser cache
3. Atau gunakan Incognito/Private mode untuk test

---

## 🧪 Manual Testing Steps

### Test 1: Pendaftar View

1. Login sebagai pendaftar yang sudah upload bukti
2. URL: `https://pmbm.mtsn2kotamalang.sch.id/pendaftar/tes-psikologi`
3. **Expected:**
   - Status "Menunggu Verifikasi" atau "Disetujui"
   - Gambar bukti muncul
   - Tidak ada placeholder error

4. **If gagal:**
   - Open browser DevTools (F12)
   - Check Console tab untuk error
   - Check Network tab untuk request status
   - Screenshot dan kirim ke developer

### Test 2: Admin View

1. Login sebagai admin
2. URL: `https://pmbm.mtsn2kotamalang.sch.id/admin/tes-psikologi`
3. Pilih tab "Data Psikologi"
4. Cari pendaftar yang ada bukti
5. Klik tombol "Lihat"
6. **Expected:** Gambar muncul di tab baru

7. **If gagal:**
   - Note URL yang di-generate
   - Copy error message
   - Check browser console

### Test 3: Panitia View

1. Login sebagai panitia
2. URL: `https://pmbm.mtsn2kotamalang.sch.id/panitia/tes-psikologi`
3. Klik tombol "Lihat" pada bukti transfer
4. **Expected:** Gambar muncul di tab baru

---

## 📊 Verification Checklist

After upload, verify ALL of these:

- [ ] Route `view.bukti-bayar` exists: `php artisan route:list | grep view.bukti-bayar`
- [ ] Controller method exists: `grep "viewBuktiBayar" app/Http/Controllers/SecureFileController.php`
- [ ] Pendaftar view updated: `grep "view.bukti-bayar" resources/views/livewire/pendaftar/tes-psikologi.blade.php`
- [ ] Admin view updated: `grep "view.bukti-bayar" resources/views/livewire/admin/partials/tes-psikologi-data.blade.php`
- [ ] Cache cleared: `php artisan route:clear && php artisan view:clear && php artisan cache:clear`
- [ ] Test pendaftar: Gambar muncul ✅
- [ ] Test admin: Klik "Lihat" works ✅
- [ ] Test panitia: Klik "Lihat" works ✅
- [ ] Browser hard refresh done: `Ctrl+Shift+R` ✅

---

## 🆘 Still Not Working?

If masih bermasalah setelah semua step di atas:

1. **Collect Information:**
   ```bash
   # 1. Environment info
   php artisan about

   # 2. Recent logs
   tail -200 storage/logs/laravel.log > debug_logs.txt

   # 3. Route list
   php artisan route:list > routes.txt

   # 4. File permissions
   ls -laR storage/app/private/bukti_bayar/ > permissions.txt
   ```

2. **Screenshot:**
   - Browser console error
   - Network tab showing failed request
   - Pendaftar page showing placeholder error
   - Admin page showing 404

3. **Report Issue:**
   - Kirim semua info di atas
   - Mention: "Sudah jalankan troubleshooting steps"
   - Include: URL yang diakses, role user, ID pendaftar

---

## 💡 Prevention Tips

**Untuk deployment berikutnya:**

1. ✅ Always clear cache after upload:
   ```bash
   php artisan optimize:clear
   ```

2. ✅ Verify files uploaded correctly:
   ```bash
   md5sum local_file
   ssh server "md5sum remote_file"
   # MD5 harus sama
   ```

3. ✅ Test di staging dulu sebelum production

4. ✅ Instruksikan user untuk hard refresh browser

5. ✅ Monitor logs selama 10 menit setelah deployment:
   ```bash
   tail -f storage/logs/laravel.log | grep -i error
   ```

---

**Created:** 2 Februari 2026
**Last Updated:** 2 Februari 2026
**Status:** Active Troubleshooting Guide
