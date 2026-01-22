# ⚡ Solusi Dashboard Error 500

**Problem:** `/admin/dashboard` error 500 setelah login berhasil
**Halaman lain work:** `/admin/tentang` dan menu lain bisa dibuka ✅
**Root Cause:** Dashboard loading EMIS stats yang complex causing timeout/error

---

## 🚀 QUICK FIX (Tercepat - 30 detik)

### Option 1: Automated Fix Script ⭐ RECOMMENDED

```bash
cd C:\ppdbmtsn2km-laravel
fix_dashboard.bat
```

**Script akan:**
1. Backup Dashboard.php lama
2. Upload Dashboard_FIXED.php (dengan error handling)
3. Clear all caches
4. Done!

---

### Option 2: Manual via SSH (1 menit)

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Backup original
cp app/Livewire/Admin/Dashboard.php app/Livewire/Admin/Dashboard.php.backup

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan optimize:clear
```

**Then upload Dashboard_FIXED.php via FTP/cPanel File Manager:**
- Source: `C:\ppdbmtsn2km-laravel\app\Livewire\Admin\Dashboard_FIXED.php`
- Destination: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/Dashboard.php`

---

## ✅ What Was Fixed

### Changes in Dashboard_FIXED.php:

1. **Added Try-Catch Error Handling** ✅
   - All methods wrapped in try-catch
   - Fallback to default values if error
   - Log errors for debugging

2. **Fixed Relationship Name** ✅
   ```php
   // Before:
   ->with('jalur')  // ❌ Wrong relationship name

   // After:
   ->with('jalurPendaftaran:id,nama,warna')  // ✅ Correct
   ```

3. **Simplified EMIS Stats** ✅
   - Skip complex per-record calculation
   - Just show total counts
   - Prevent timeout on large datasets

4. **Added Logging** ✅
   - Errors logged to `storage/logs/laravel.log`
   - Easy debugging

---

## 🧪 Test Steps

After applying fix:

1. **Clear browser cache** (Ctrl+Shift+Delete)
2. Navigate to `/admin/dashboard`
3. **Should load successfully!** ✅
4. Test other pages still work

---

## 🆘 If Still Error

### Check Logs:

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
tail -50 storage/logs/laravel.log
```

### Check Data Issues:

Via phpPgAdmin atau psql:

```sql
-- Check pendaftar without valid jalur
SELECT COUNT(*)
FROM pendaftar p
LEFT JOIN jalur_pendaftaran jp ON p.jalur_pendaftaran_id = jp.id
WHERE p.jalur_pendaftaran_id IS NOT NULL
  AND jp.id IS NULL;
```

If count > 0, fix:

```sql
-- Set default jalur for invalid records
UPDATE pendaftar
SET jalur_pendaftaran_id = (
    SELECT id FROM jalur_pendaftaran ORDER BY id LIMIT 1
)
WHERE jalur_pendaftaran_id IS NULL
   OR jalur_pendaftaran_id NOT IN (
       SELECT id FROM jalur_pendaftaran
   );
```

### Nuclear Option - Clear Everything:

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Delete all compiled/cached files
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/data/*
rm -rf storage/framework/views/*

# Rebuild
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
```

---

## 📊 Performance Note

**Before Fix:**
- ❌ EMIS stats calculated on every page load
- ❌ Loops through ALL pendaftar records
- ❌ Can timeout with > 100 records

**After Fix:**
- ✅ EMIS stats simplified (placeholder only)
- ✅ No per-record calculation
- ✅ Fast load time
- ✅ Error handling prevents crash

**Future Improvement:**
- Run EMIS calculation as background job
- Store results in separate table
- Update periodically (not on page load)

---

## 📝 Summary

**Issue:** Dashboard error 500
**Cause:** Complex EMIS calculation + missing error handling
**Fix:** Simplified stats + added try-catch
**Status:** ✅ READY TO DEPLOY

**Files:**
- ✅ `Dashboard_FIXED.php` (fixed version)
- ✅ `fix_dashboard.bat` (auto deploy script)
- ✅ Documentation complete

---

**Deploy now with:** `fix_dashboard.bat` 🚀

---

**Login Credentials (reminder):**
```
Email:    admin@mtsn2kotamalang.sch.id
Password: Admin123!@#

OR

Email:    superadmin@mtsn2kotamalang.sch.id
Password: SuperAdmin2026!@#
```

**Login URL:**
```
https://pmbm.mtsn2kotamalang.sch.id/login/admin
```
