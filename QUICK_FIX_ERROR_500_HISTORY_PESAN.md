# Quick Fix - Error 500 History Pesan
**Problem:** Error 500 saat akses tab History Email dan History WhatsApp
**Date:** 3 February 2026

---

## 🔍 ERROR DIAGNOSIS

Error yang terjadi:
```
/livewire-e455e806/update:1  Failed to load resource: the server responded with a status of 500 ()
/admin/pengaturan-pesan?activeTab=history-email:1 Uncaught (in promise) Object
/admin/pengaturan-pesan?activeTab=history-whatsapp:1 Uncaught (in promise) Object
```

---

## 🎯 KEMUNGKINAN PENYEBAB

### Penyebab #1: Migration Belum Dijalankan (PALING MUNGKIN)
Table `message_queue` belum ada di production database.

**Solution:**
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan migrate --force
php artisan optimize:clear
```

---

### Penyebab #2: Model MessageQueue.php Belum Ter-upload

File `app/Models/MessageQueue.php` tidak ada atau versi lama.

**Solution:**
```bash
# From local
scp app/Models/MessageQueue.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/

# Then SSH
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
chmod 644 app/Models/MessageQueue.php
php artisan optimize:clear
```

---

### Penyebab #3: WhatsAppQueueService Issue

Service tidak bisa resolve atau method getStatistics() error.

**Solution:**
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Check if service exists
ls -la app/Services/WhatsAppQueueService.php

# If not exists, upload it
# (dari local)
scp app/Services/WhatsAppQueueService.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Services/

# Then clear cache
php artisan optimize:clear
```

---

### Penyebab #4: Controller Belum Update

File `app/Livewire/Admin/PengaturanPesan.php` versi lama atau tidak ter-upload.

**Solution:**
```bash
# From local
scp app/Livewire/Admin/PengaturanPesan.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/

# Then SSH
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
chmod 644 app/Livewire/Admin/PengaturanPesan.php
php artisan optimize:clear
```

---

## 🚀 QUICK DEBUG SCRIPT

Jalankan script ini untuk diagnosa cepat:

```bash
debug_production_history_pesan.bat
```

Script akan check:
1. Apakah table `message_queue` ada
2. Apakah file `MessageQueue.php` ter-upload
3. Apakah file `WhatsAppQueueService.php` ada
4. Error logs terakhir
5. Status migration

---

## 📋 STEP-BY-STEP FIX (RECOMMENDED)

### Step 1: Check Table Exists
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan tinker
```

Di tinker, ketik:
```php
Schema::hasTable('message_queue')
```

**Jika FALSE (table tidak ada):**
```bash
# Exit tinker (Ctrl+C)
php artisan migrate --force
```

**Jika TRUE (table ada):**
Lanjut ke Step 2.

---

### Step 2: Check Model File
```bash
ls -la app/Models/MessageQueue.php
```

**Jika file tidak ada atau ukuran mencurigakan:**
```bash
# Exit SSH
exit

# Upload ulang dari local
scp app/Models/MessageQueue.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/

# SSH kembali
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
chmod 644 app/Models/MessageQueue.php
```

---

### Step 3: Check Service File
```bash
ls -la app/Services/WhatsAppQueueService.php
```

**Expected output:** File should exist and be ~12-15 KB

**Jika file tidak ada atau ukuran mencurigakan:**
```bash
exit
scp app/Services/WhatsAppQueueService.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Services/
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
chmod 644 app/Services/WhatsAppQueueService.php
```

---

### Step 4: Verify Method Exists in Service
```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
grep -n "getStatistics" app/Services/WhatsAppQueueService.php
```

**Expected output:** Should show line with `public function getStatistics()`

**Jika tidak ada:**
Upload ulang file WhatsAppQueueService.php (lihat Step 3).

---

### Step 5: Verify Model Scopes
```bash
grep -n "scopeWhatsAppOnly\|scopeByStatus" app/Models/MessageQueue.php
```

**Expected output:** Should show 2 lines with these scopes

**Jika tidak ada:**
Upload ulang MessageQueue.php (lihat Step 2).

---

### Step 6: Clear All Caches
```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan optimize:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
php artisan cache:clear

# Optional but recommended
composer dump-autoload
```

---

### Step 7: Check Error Logs
```bash
tail -100 storage/logs/laravel.log | grep -A 20 "Exception\|Error"
```

Look for specific error related to:
- `MessageQueue` class not found
- `message_queue` table doesn't exist
- `WhatsAppQueueService` not found
- `getStatistics` method not found

---

### Step 8: Test in Browser
1. Clear browser cache (Ctrl+Shift+Del)
2. Go to `/admin/pengaturan-pesan`
3. Click tab "History Email"
4. Click tab "History WhatsApp"

**Expected:** No error 500, stats cards should appear.

---

## 🔧 COMPLETE RE-UPLOAD (If All Else Fails)

Jika semua step di atas tidak berhasil, upload ulang SEMUA file:

```bash
# From local Windows
scp app/Models/MessageQueue.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/
scp app/Services/WhatsAppQueueService.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Services/
scp app/Livewire/Admin/PengaturanPesan.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
scp resources/views/livewire/admin/partials/history-email-tab.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/
scp resources/views/livewire/admin/partials/history-whatsapp-tab.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/
scp database/migrations/2026_02_03_210000_create_message_queue_table.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/

# SSH and execute
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Set permissions
chmod 644 app/Models/MessageQueue.php
chmod 644 app/Services/WhatsAppQueueService.php
chmod 644 app/Livewire/Admin/PengaturanPesan.php
chmod 644 resources/views/livewire/admin/partials/history-email-tab.blade.php
chmod 644 resources/views/livewire/admin/partials/history-whatsapp-tab.blade.php
chmod 644 database/migrations/2026_02_03_210000_create_message_queue_table.php

# Run migration
php artisan migrate --force

# Clear everything
php artisan optimize:clear
php artisan view:clear
composer dump-autoload
```

---

## ⚡ SUPER QUICK FIX (Most Common Issue)

90% kemungkinan masalahnya adalah **migration belum dijalankan**:

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan migrate --force
php artisan optimize:clear
```

**Refresh browser dan test lagi.**

---

## 📞 STILL NOT WORKING?

Jika masih error, ambil error log lengkap:

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
tail -200 storage/logs/laravel.log > ~/error_history_pesan.log
cat ~/error_history_pesan.log | grep -A 50 "Exception\|Error" | head -100
```

Share error log tersebut untuk analisis lebih lanjut.

---

## ✅ VERIFICATION

Setelah fix, verify:

### Tab History Email:
- [ ] 5 stats cards muncul
- [ ] Table email history muncul
- [ ] No error 500

### Tab History WhatsApp:
- [ ] 5 stats cards muncul (including Total Pesan)
- [ ] Table WhatsApp queue muncul
- [ ] Kolom "Isi Pesan" dengan eye icon ada
- [ ] Kolom "Waktu Tersisa" dengan badge berwarna ada
- [ ] No error 500

---

**Document Created:** 3 February 2026
**Issue:** Error 500 on History Email & WhatsApp tabs
**Status:** Troubleshooting Guide
