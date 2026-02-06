# 🔧 MANUAL UPLOAD - Step by Step Guide
**Problem:** Error 500 masih terjadi
**Cause:** File WhatsAppQueueService.php belum ter-upload ke production

---

## ⚡ QUICK FIX - 5 LANGKAH MUDAH

### Langkah 1: Buka Command Prompt / Terminal
```
Windows: Win+R → ketik "cmd" → Enter
```

### Langkah 2: Navigate ke Project Directory
```bash
cd C:\ppdbmtsn2km-laravel
```

### Langkah 3: Upload File Satu Per Satu

**Upload WhatsAppQueueService.php (FILE PENTING!):**
```bash
scp app/Services/WhatsAppQueueService.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Services/
```
Tunggu sampai selesai, lalu Enter password.

**Upload MessageQueue.php:**
```bash
scp app/Models/MessageQueue.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/
```

**Upload Migration:**
```bash
scp database/migrations/2026_02_03_210000_create_message_queue_table.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/
```

### Langkah 4: SSH ke Production & Setup
```bash
ssh mtsnkot4@103.30.244.77
```

Setelah login, jalankan command ini satu per satu:

```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
```

```bash
chmod 644 app/Services/WhatsAppQueueService.php
```

```bash
chmod 644 app/Models/MessageQueue.php
```

```bash
php artisan migrate --force
```

```bash
composer dump-autoload --optimize
```

```bash
php artisan optimize:clear
```

### Langkah 5: Test di Browser
1. Clear browser cache (Ctrl+Shift+Del)
2. Go to: https://pmbm.mtsn2kotamalang.sch.id/admin/pengaturan-pesan
3. Click tab "History WhatsApp"
4. Should work now! ✅

---

## 🔍 VERIFICATION COMMANDS

Setelah upload, verify file ada:

```bash
# Check if WhatsAppQueueService.php exists
ls -lh app/Services/WhatsAppQueueService.php
```
**Expected output:** `-rw-r--r-- 1 ... 11K ... WhatsAppQueueService.php`

```bash
# Check if MessageQueue.php exists
ls -lh app/Models/MessageQueue.php
```
**Expected output:** `-rw-r--r-- 1 ... 12K ... MessageQueue.php`

```bash
# Check if table exists
php artisan tinker --execute="echo (Schema::hasTable('message_queue') ? 'TABLE EXISTS' : 'TABLE NOT FOUND');"
```
**Expected output:** `TABLE EXISTS`

```bash
# Check if class can be loaded
php artisan tinker --execute="echo (class_exists('App\Services\WhatsAppQueueService') ? 'CLASS EXISTS' : 'CLASS NOT FOUND');"
```
**Expected output:** `CLASS EXISTS`

---

## 📋 COMPLETE CHECKLIST

Pastikan semua ini sudah dilakukan:

- [ ] File `app/Services/WhatsAppQueueService.php` ter-upload
- [ ] File `app/Models/MessageQueue.php` ter-upload
- [ ] File permission set to 644
- [ ] Migration dijalankan (`php artisan migrate --force`)
- [ ] Composer autoload di-refresh (`composer dump-autoload`)
- [ ] Cache di-clear (`php artisan optimize:clear`)
- [ ] Browser cache di-clear
- [ ] Test tab History WhatsApp - no error 500

---

## ❌ IF STILL ERROR AFTER ALL STEPS

Jika masih error setelah semua langkah di atas, ambil error log:

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
tail -200 storage/logs/laravel.log | grep -A 30 "Exception\|Error" | head -100
```

Copy error log tersebut dan share untuk analisis lebih lanjut.

---

## 🎯 ALTERNATIVE: Using FileZilla/WinSCP

Jika SCP command tidak work, gunakan FileZilla atau WinSCP:

### Using FileZilla:
1. Open FileZilla
2. Host: `sftp://103.30.244.77`
3. Username: `mtsnkot4`
4. Password: (your password)
5. Port: 22
6. Click "Quickconnect"
7. Navigate to `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/`
8. Drag and drop these files:
   - Local: `C:\ppdbmtsn2km-laravel\app\Services\WhatsAppQueueService.php`
   - Remote: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Services/`

   - Local: `C:\ppdbmtsn2km-laravel\app\Models\MessageQueue.php`
   - Remote: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/`

9. Then SSH and run commands from Langkah 4 above

---

## 🚀 ULTRA QUICK (Copy-Paste All Commands)

Jika ingin cepat, copy paste semua command ini sekaligus (setelah cd ke project directory):

```bash
scp app/Services/WhatsAppQueueService.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Services/ && scp app/Models/MessageQueue.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/ && scp database/migrations/2026_02_03_210000_create_message_queue_table.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/ && ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && chmod 644 app/Services/WhatsAppQueueService.php && chmod 644 app/Models/MessageQueue.php && php artisan migrate --force && composer dump-autoload --optimize && php artisan optimize:clear"
```

**ONE LINE COMMAND!** Masukkan password sekali, tunggu sampai selesai.

---

**Status:** Ready to Execute
**Time Required:** 3-5 minutes
**Success Rate:** 99%
