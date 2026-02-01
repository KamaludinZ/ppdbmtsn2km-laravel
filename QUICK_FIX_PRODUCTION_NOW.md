# 🚨 QUICK FIX - Jalankan SEKARANG di Production

**Status**: File `livewire.min.js` NOT FOUND (404) di production
**Impact**: Login tidak bisa berfungsi
**Solution**: Publish Livewire assets (2 menit)

---

## 🎯 COMMAND CEPAT - Copy Paste Ini:

### Option 1: One-Line Command (TERCEPAT) ⚡

```bash
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan livewire:publish --force && chmod -R 755 public/vendor/ && php artisan optimize:clear && ls -lh public/vendor/livewire/livewire.min.js"
```

**Copas command di atas, jalankan dari Command Prompt/Terminal Windows Anda.**

Output yang diharapkan:
```
Livewire assets published successfully.
-rw-r--r-- 1 mtsnkot4 mtsnkot4 150K Jan 30 16:00 public/vendor/livewire/livewire.min.js
```

---

## Option 2: Step-by-Step (Jika Option 1 Gagal)

Buka Command Prompt/Terminal, lalu:

```bash
# Step 1: Connect ke production
ssh mtsnkot4@103.30.244.77

# Step 2: Masuk ke folder aplikasi
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Step 3: Publish Livewire assets (CRITICAL!)
php artisan livewire:publish --force

# Step 4: Set permission
chmod -R 755 public/vendor/

# Step 5: Clear all caches
php artisan optimize:clear

# Step 6: Verify file exists
ls -lh public/vendor/livewire/livewire.min.js
```

**Jika Step 6 menunjukkan file exists (~150KB), SUKSES!**

---

## ✅ Setelah Command Selesai:

1. **Clear Browser Cache**:
   - Tekan: `Ctrl + Shift + Delete`
   - Pilih: "Cached images and files"
   - Click: "Clear data"

2. **Refresh Halaman**:
   - Buka: `https://pmbm.mtsn2kotamalang.sch.id/login/admin`
   - Tekan: `Ctrl + F5` (hard refresh)
   - Buka DevTools (F12)
   - Check Console → Error 404 seharusnya HILANG

3. **Test Login**:
   - Enter username & password
   - Click Login
   - Seharusnya BERHASIL masuk dashboard

---

## 🔍 Quick Verify (Setelah Command)

Cek apakah file sudah accessible dari browser:

**Visit URL ini di browser:**
```
https://pmbm.mtsn2kotamalang.sch.id/vendor/livewire/livewire.min.js
```

**Expected Result**: Anda akan melihat kode JavaScript (bukan error 404)

---

## ⚠️ Jika Masih Gagal

### Check #1: Apakah file benar-benar ada?

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
cat public/vendor/livewire/livewire.min.js | head -c 50
```

**Expected**: Harus muncul kode JavaScript (contoh: `!function(){var e...`)

### Check #2: Permission Issue?

```bash
ls -la public/vendor/livewire/
chmod 644 public/vendor/livewire/*
```

### Check #3: .htaccess Blocking?

```bash
cat public/.htaccess | grep -i deny
```

Jika ada `Deny from all` di dalam section vendor, itu masalah.

---

## 📱 Alternative: Manual Upload Assets

Jika `php artisan livewire:publish` gagal, manual upload dari local:

### Di Local Windows:

```batch
# Check if Livewire assets exist di local
dir public\vendor\livewire\livewire.min.js

# If exists, upload via SCP
scp public/vendor/livewire/* mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/public/vendor/livewire/
```

---

## 🎯 Expected Final Result:

```
✅ Command executed successfully
✅ File public/vendor/livewire/livewire.min.js exists (~150KB)
✅ Permission: 644 (rw-r--r--)
✅ Browser URL accessible: https://pmbm.mtsn2kotamalang.sch.id/vendor/livewire/livewire.min.js
✅ Console error: GONE (no more 404)
✅ Login: WORKS!
```

---

**Time to Fix**: 2 minutes
**Priority**: 🚨 CRITICAL - DO IT NOW!

Silakan jalankan command Option 1 atau Option 2, lalu beritahu saya hasilnya!
