# DEPLOYMENT - 2 FIXES SEKALIGUS

**Status**: 🔥 **URGENT - 2 Critical Bugs**
**Tanggal**: 6 Februari 2026, 21:30 WIB

---

## 🎯 OVERVIEW

Ada **2 BUG CRITICAL** yang perlu di-fix:

### **BUG #1: Error 500 Dashboard** 🚨 PRIORITY 1
- **Error**: `Call to undefined function format_pengumuman()`
- **Impact**: Dashboard pendaftar tidak bisa diakses
- **Blocker**: Semua pendaftar tidak bisa login
- **Files**: 2 files (Helpers)

### **BUG #2: Survey Overwrite** 🎯 PRIORITY 2
- **Error**: Tombol "Selesai & Simpan" overwrite semua jawaban
- **Impact**: Data survey corrupt
- **Blocker**: Survey tidak bisa disubmit dengan benar
- **Files**: 2 files (TesPsikologi.php + SurveyAnswer.php)

---

## 📋 URUTAN DEPLOYMENT (IMPORTANT!)

**Deploy FIX #1 DULU, lalu FIX #2:**

### **Kenapa urutan ini?**
1. Dashboard error **blocking ALL pendaftar** dari akses sistem
2. Survey bug hanya terjadi **saat submit survey**
3. Jika Dashboard error, pendaftar tidak bisa akses survey
4. **Fix Dashboard dulu** agar sistem bisa diakses

---

## 🔧 FIX #1: ERROR 500 DASHBOARD (15 MENIT)

### **Files to Upload (2 files)**:
1. `app/Helpers/helpers.php`
2. `app/Helpers/TextFormatter.php`

### **Upload via cPanel File Manager**:
1. Navigate: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Helpers/`
   - Jika folder `Helpers` tidak ada, **buat folder baru** "Helpers"
2. Upload kedua file dari local:
   - `C:\ppdbmtsn2km-laravel\app\Helpers\helpers.php`
   - `C:\ppdbmtsn2km-laravel\app\Helpers\TextFormatter.php`

### **Regenerate Autoload** ⚠️ CRITICAL:
```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
composer dump-autoload
```

### **Clear Cache**:
```bash
php artisan view:clear
php artisan optimize:clear
```

### **Test Fix #1**:
1. Login sebagai **pendaftar**
2. Dashboard harus load **tanpa error 500**
3. Pengumuman harus tampil dengan formatting

✅ **Jika test berhasil, lanjut ke Fix #2**

---

## 🎯 FIX #2: SURVEY OVERWRITE (30 MENIT)

### **Files to Upload (2 files)**:
1. `app/Livewire/Pendaftar/TesPsikologi.php` ⭐ CRITICAL
2. `app/Models/SurveyAnswer.php`

### **Upload via cPanel File Manager**:
1. **File #1**:
   - Local: `C:\ppdbmtsn2km-laravel\app\Livewire\Pendaftar\TesPsikologi.php`
   - Remote: `app/Livewire/Pendaftar/` (overwrite existing)

2. **File #2**:
   - Local: `C:\ppdbmtsn2km-laravel\app\Models\SurveyAnswer.php`
   - Remote: `app/Models/` (overwrite existing)

### **Clear Cache**:
```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan view:clear
php artisan optimize:clear
php artisan cache:clear
```

### **Delete Corrupt Data** ⚠️ REQUIRED:
```sql
-- Via phpPgAdmin:
DELETE FROM survey_answers;
```

### **Test Fix #2**:
1. Login sebagai **pendaftar**
2. Buka: `/pendaftar/tes-psikologi`
3. Isi survey dengan jawaban **BERBEDA** tiap pertanyaan
4. Klik **"Selanjutnya"** untuk setiap pertanyaan (auto-save!)
5. Klik **"Selesai & Simpan"** di pertanyaan terakhir
6. **Verify Database**:
   ```sql
   SELECT survey_question_id, jawaban
   FROM survey_answers
   WHERE pendaftar_id = [ID]
   ORDER BY survey_question_id;
   ```
7. **Expected**: Semua jawaban **BERBEDA** (133, 129, 7, 29...)

---

## 📊 COMPLETE CHECKLIST

### **PRE-DEPLOYMENT**:
- [ ] Error 500 Dashboard confirmed
- [ ] Survey overwrite bug confirmed
- [ ] All files ready in local

### **FIX #1 - DASHBOARD** (Do First!):
- [ ] Upload `helpers.php`
- [ ] Upload `TextFormatter.php`
- [ ] Run `composer dump-autoload`
- [ ] Clear caches
- [ ] Test: Login pendaftar → Dashboard loads
- [ ] ✅ **Dashboard working? Proceed to Fix #2**

### **FIX #2 - SURVEY** (Do Second!):
- [ ] Upload `TesPsikologi.php`
- [ ] Upload `SurveyAnswer.php`
- [ ] Clear caches
- [ ] Delete `survey_answers` table data
- [ ] Test: Submit survey with different answers
- [ ] Verify: Database shows all answers different
- [ ] ✅ **Survey working? Deployment complete!**

---

## 🚀 ALL-IN-ONE TERMINAL COMMANDS

**Setelah upload semua 4 files**, jalankan di cPanel Terminal:

```bash
# Navigate to project
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# FIX #1: Regenerate autoload (for Helpers)
composer dump-autoload

# Clear ALL caches
php artisan view:clear
php artisan optimize:clear
php artisan cache:clear
php artisan config:clear

echo "Caches cleared! Test Dashboard now."
```

**Untuk delete survey data** (via Terminal):
```bash
psql -U mtsnkot4_uspmbm -d mtsnkot4_dbpmbm -c "DELETE FROM survey_answers;"
```

---

## 📁 FILES SUMMARY

**Total**: 4 files to upload

### **Group A - Dashboard Fix** (Priority 1):
1. ✅ `app/Helpers/helpers.php` (NEW - create folder if not exists)
2. ✅ `app/Helpers/TextFormatter.php` (NEW)

### **Group B - Survey Fix** (Priority 2):
3. ✅ `app/Livewire/Pendaftar/TesPsikologi.php` (OVERWRITE)
4. ✅ `app/Models/SurveyAnswer.php` (OVERWRITE)

---

## 🔧 TROUBLESHOOTING

### Issue: Dashboard masih error 500 setelah upload

**Check**:
```bash
# Verify files exist
ls -la app/Helpers/

# Verify autoload includes helpers
grep -A 5 '"files"' composer.json

# Re-run autoload
composer dump-autoload

# Clear cache again
php artisan optimize:clear
```

### Issue: Survey masih overwrite

**Check**:
```bash
# Verify file content
grep "CRITICAL FIX" app/Livewire/Pendaftar/TesPsikologi.php
grep "REMOVED" app/Models/SurveyAnswer.php

# Verify cache cleared
ls bootstrap/cache/
# Should be empty or only .gitignore

# Verify data deleted
psql -U mtsnkot4_uspmbm -d mtsnkot4_dbpmbm -c "SELECT COUNT(*) FROM survey_answers;"
# Should return: 0
```

---

## ✅ SUCCESS CRITERIA

**Fix #1 - Dashboard**:
- ✅ Login pendaftar berhasil
- ✅ Dashboard load tanpa error 500
- ✅ Pengumuman tampil dengan formatting
- ✅ No "Call to undefined function" error

**Fix #2 - Survey**:
- ✅ Submit survey berhasil
- ✅ Database: Semua jawaban berbeda (tidak overwrite)
- ✅ Database: Semua jawaban stored as INTEGER
- ✅ No more "all answers become 126" bug

---

## 📚 DETAILED GUIDES

Untuk panduan lengkap setiap fix:

### **FIX #1 - Dashboard**:
- 📄 `FIX_ERROR_500_DASHBOARD.md` (detailed guide)
- 📄 `QUICK_FIX_ERROR_500.txt` (quick reference)
- 📄 `upload_helpers_fix.bat` (batch file)

### **FIX #2 - Survey**:
- 📄 `MANUAL_UPLOAD_INCREMENTAL_FIX.md` (detailed guide)
- 📄 `QUICK_FIX_INCREMENTAL_SAVE.md` (quick reference)
- 📄 `upload_INCREMENTAL_SAVE_FIX.bat` (batch file)

---

## ⏱️ ESTIMASI WAKTU

- **Fix #1 (Dashboard)**: 15 menit
  - Upload: 5 menit
  - Autoload + cache: 5 menit
  - Test: 5 menit

- **Fix #2 (Survey)**: 30 menit
  - Upload: 5 menit
  - Clear cache: 5 menit
  - Delete data: 5 menit
  - Test + verify: 15 menit

**Total**: 45-60 menit untuk kedua fix

---

## 🎉 AFTER DEPLOYMENT

Setelah kedua fix berhasil:

1. **Notify Pendaftar**:
   - Sistem sudah normal
   - Bisa isi survey ulang (data lama dihapus)

2. **Monitor**:
   - Check logs: `tail -100 storage/logs/laravel.log`
   - Monitor 10-20 submissions pertama
   - Verify no errors

3. **Cleanup** (Optional):
   - Remove debug logging dari TesPsikologi.php
   - Remove log statements (optional, nanti)

---

**Dibuat**: 6 Februari 2026, 21:30 WIB
**Priority**: 🔥 **CRITICAL - Deploy ASAP**
**Estimasi Total**: 45-60 menit
