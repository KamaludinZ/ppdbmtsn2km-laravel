# FIX ERROR 500 - Dashboard Pendaftar

**Status**: 🚨 **URGENT - Dashboard tidak bisa diakses**
**Error**: `Call to undefined function format_pengumuman()`

---

## 🎯 ROOT CAUSE

**Error Message**:
```
Call to undefined function format_pengumuman()
at storage/framework/views/d6a9bd973df6d5b88ab2f38e5b9962a9.php:988
```

**Penyebab**:
- File `app/Helpers/helpers.php` tidak ter-autoload di production
- Class `App\Helpers\TextFormatter` tidak ter-autoload
- Autoload classmap tidak ter-regenerate setelah upload files

**Impact**:
- ❌ Dashboard pendaftar error 500
- ❌ Pendaftar tidak bisa login
- ❌ Pengumuman tidak bisa ditampilkan

---

## ✅ SOLUSI (3 STEPS)

### **STEP 1: Upload Helper Files** (2 Files)

#### **File #1: app/Helpers/helpers.php**
**Path Local**: `C:\ppdbmtsn2km-laravel\app\Helpers\helpers.php`
**Path Production**: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Helpers/helpers.php`

Via cPanel File Manager:
1. Navigate ke: `app/Helpers/`
2. Check apakah folder `Helpers` ada
   - **Jika TIDAK ADA**: Buat folder baru "Helpers" (case-sensitive!)
3. Upload `helpers.php` dari local
4. Verify file ter-upload

#### **File #2: app/Helpers/TextFormatter.php**
**Path Local**: `C:\ppdbmtsn2km-laravel\app\Helpers\TextFormatter.php`
**Path Production**: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Helpers/TextFormatter.php`

Via cPanel File Manager:
1. Navigate ke: `app/Helpers/`
2. Upload `TextFormatter.php` dari local
3. Verify file ter-upload

---

### **STEP 2: Regenerate Autoload Classmap** ⚠️ CRITICAL!

Via cPanel Terminal:

```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Regenerate autoload
composer dump-autoload

# Expected output:
# Generating optimized autoload files
# Generated optimized autoload files containing X classes
```

**Kenapa ini penting?**
- Composer perlu tahu bahwa ada helper functions baru
- File `helpers.php` perlu di-autoload agar fungsi tersedia globally
- Tanpa `composer dump-autoload`, Laravel tidak akan load helper functions

---

### **STEP 3: Clear ALL Caches**

Via cPanel Terminal:

```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

php artisan view:clear
php artisan optimize:clear
php artisan cache:clear
php artisan config:clear
```

---

## ✅ VERIFY FIX

### **Test 1: Check Autoload**
Via cPanel Terminal:
```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan tinker

# In tinker shell, test:
>>> format_pengumuman('Test *bold* dan _italic_')
# Expected: HTML formatted output

>>> exit
```

**Jika berhasil**: Fungsi return HTML formatted text
**Jika gagal**: Error "Call to undefined function"

---

### **Test 2: Login Dashboard**
1. Buka: `https://pmbm.mtsn2kotamalang.sch.id/login`
2. Login sebagai **pendaftar**
3. Dashboard harus load tanpa error 500
4. Pengumuman harus tampil dengan formatting

---

## 🔧 TROUBLESHOOTING

### Issue 1: Folder Helpers tidak ada

**Solution**:
1. cPanel File Manager
2. Navigate: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/`
3. Klik **New Folder**
4. Nama folder: `Helpers` (capital H!)
5. Upload kedua file ke folder ini

---

### Issue 2: composer dump-autoload gagal

**Error**: `composer: command not found`

**Solution A - Find composer path**:
```bash
# Try different paths:
/usr/local/bin/composer dump-autoload
/usr/bin/composer dump-autoload
php /usr/local/bin/composer dump-autoload
```

**Solution B - Via PHP**:
```bash
php composer.phar dump-autoload
```

**Solution C - Contact hosting support**:
Jika semua gagal, hubungi support hosting untuk run:
```
composer dump-autoload -d /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
```

---

### Issue 3: Error masih terjadi setelah fix

**Check**:
1. Verify files uploaded:
   ```bash
   ls -la app/Helpers/
   # Harus ada: helpers.php dan TextFormatter.php
   ```

2. Verify composer.json includes helpers:
   ```bash
   grep -A 5 '"files"' composer.json
   # Harus ada: "app/Helpers/helpers.php"
   ```

3. Clear cache LAGI:
   ```bash
   php artisan optimize:clear
   ```

4. Check permissions:
   ```bash
   chmod 644 app/Helpers/*.php
   ```

---

## 📋 CHECKLIST

**Pre-Fix**:
- [ ] Error 500 saat login pendaftar
- [ ] Error: "Call to undefined function format_pengumuman()"

**During Fix**:
- [ ] Upload `app/Helpers/helpers.php`
- [ ] Upload `app/Helpers/TextFormatter.php`
- [ ] Run `composer dump-autoload`
- [ ] Clear all caches

**Post-Fix Verification**:
- [ ] Test `format_pengumuman()` in tinker
- [ ] Login pendaftar berhasil
- [ ] Dashboard load tanpa error 500
- [ ] Pengumuman tampil dengan formatting

---

## 🎯 QUICK FIX COMMANDS

**All-in-one (Copy-paste di cPanel Terminal)**:

```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Regenerate autoload
composer dump-autoload

# Clear caches
php artisan view:clear
php artisan optimize:clear
php artisan cache:clear
php artisan config:clear

# Test
echo "Testing format_pengumuman function..."
php artisan tinker --execute="echo format_pengumuman('Test *bold*');"

echo "Fix completed! Test login now."
```

---

## 📁 FILES TO UPLOAD (2 FILES)

Priority: 🔥 **CRITICAL** (Dashboard tidak bisa diakses!)

1. **`app/Helpers/helpers.php`**
   - Size: ~500 bytes
   - Contains: `format_pengumuman()` dan `strip_formatting()` functions

2. **`app/Helpers/TextFormatter.php`**
   - Size: ~3 KB
   - Contains: TextFormatter class dengan formatPengumuman() method

---

## 📊 EXPECTED RESULT

**BEFORE FIX** ❌:
```
Error 500: Call to undefined function format_pengumuman()
Dashboard tidak bisa diakses
```

**AFTER FIX** ✅:
```
Dashboard load normal
Pengumuman tampil dengan formatting:
- *bold text* → <strong>bold text</strong>
- _italic text_ → <em>italic text</em>
- URLs clickable
```

---

## 🔄 URUTAN DEPLOYMENT

Karena ada 2 bug sekaligus:

### **1. FIX Dashboard DULU** (File ini):
- Upload Helpers files
- Run composer dump-autoload
- Clear cache
- **Test**: Login pendaftar → Dashboard harus load

### **2. FIX Survey KEMUDIAN** (Survey overwrite fix):
- Upload TesPsikologi.php
- Upload SurveyAnswer.php
- Clear cache
- Delete survey_answers
- **Test**: Submit survey → No overwrite

**Kenapa urutan ini?**
- Dashboard error blocking semua pendaftar
- Survey bug hanya affect saat submit
- Fix Dashboard dulu agar pendaftar bisa akses sistem

---

**Dibuat**: 6 Februari 2026, 21:30 WIB
**Priority**: 🔥 **CRITICAL - Blocking all pendaftar**
**Estimasi**: 15 menit (upload + autoload + test)
