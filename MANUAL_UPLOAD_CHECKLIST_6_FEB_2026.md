# 📋 MANUAL UPLOAD CHECKLIST - 6 FEBRUARI 2026

## Via cPanel File Manager

**SSH Status:** ❌ Connection Closed
**Method:** Manual upload via cPanel
**Total Files:** 7 files
**Estimated Time:** 15-20 minutes

---

## 🔐 LOGIN CPANEL

- [ ] Buka browser: https://103.30.244.77:2083
- [ ] Username: **mtsnkot4**
- [ ] Password: [your password]
- [ ] Klik "File Manager"
- [ ] Navigate ke: **pmbm.mtsn2kotamalang.sch.id**

---

## 📤 UPLOAD FILE 1: Dashboard.php

**Local Path:**
```
C:\ppdbmtsn2km-laravel\app\Livewire\Pendaftar\Dashboard.php
```

**Remote Path:**
```
pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
```

**Steps:**
- [ ] Navigate ke folder: `app/Livewire/Pendaftar/`
- [ ] Klik "Upload"
- [ ] Pilih file: `Dashboard.php`
- [ ] Wait for upload to complete
- [ ] **Overwrite existing:** Yes
- [ ] Verify file exists in list

**Verification:**
```
Dashboard.php
Size: ~40-50 KB
Modified: [today's date]
```

---

## 📤 UPLOAD FILE 2: dashboard.blade.php

**Local Path:**
```
C:\ppdbmtsn2km-laravel\resources\views\livewire\pendaftar\dashboard.blade.php
```

**Remote Path:**
```
pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
```

**Steps:**
- [ ] Navigate ke folder: `resources/views/livewire/pendaftar/`
- [ ] Klik "Upload"
- [ ] Pilih file: `dashboard.blade.php`
- [ ] Wait for upload to complete
- [ ] **Overwrite existing:** Yes
- [ ] Verify file exists in list

**Verification:**
```
dashboard.blade.php
Size: ~30-40 KB
Modified: [today's date]
```

---

## 📤 UPLOAD FILE 3: status.blade.php

**Local Path:**
```
C:\ppdbmtsn2km-laravel\resources\views\livewire\pendaftar\status.blade.php
```

**Remote Path:**
```
pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
```

**Steps:**
- [ ] **Same folder as File 2** (already there)
- [ ] Klik "Upload"
- [ ] Pilih file: `status.blade.php`
- [ ] Wait for upload to complete
- [ ] **Overwrite existing:** Yes
- [ ] Verify file exists in list

**Verification:**
```
status.blade.php
Size: ~20-30 KB
Modified: [today's date]
```

---

## 📤 UPLOAD FILE 4: status-alert.blade.php

**Local Path:**
```
C:\ppdbmtsn2km-laravel\resources\views\components\status-alert.blade.php
```

**Remote Path:**
```
pmbm.mtsn2kotamalang.sch.id/resources/views/components/
```

**Steps:**
- [ ] Navigate ke folder: `resources/views/components/`
- [ ] Klik "Upload"
- [ ] Pilih file: `status-alert.blade.php`
- [ ] Wait for upload to complete
- [ ] **Overwrite existing:** Yes
- [ ] Verify file exists in list

**Verification:**
```
status-alert.blade.php
Size: ~5-10 KB
Modified: [today's date]
```

---

## 📤 UPLOAD FILE 5: TesPsikologi.php (Pendaftar)

**Local Path:**
```
C:\ppdbmtsn2km-laravel\app\Livewire\Pendaftar\TesPsikologi.php
```

**Remote Path:**
```
pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
```

**Steps:**
- [ ] Navigate ke folder: `app/Livewire/Pendaftar/`
- [ ] Klik "Upload"
- [ ] Pilih file: `TesPsikologi.php`
- [ ] Wait for upload to complete
- [ ] **Overwrite existing:** Yes
- [ ] Verify file exists in list

**Verification:**
```
TesPsikologi.php
Size: ~50-70 KB
Modified: [today's date]
```

**⚠️ CRITICAL FIX:**
This file contains the survey checkbox fix at:
- Line 257: `nextQuestion()` method
- Line 382: `simpanSurvey()` method

---

## 📤 UPLOAD FILE 6: Formulir.php

**Local Path:**
```
C:\ppdbmtsn2km-laravel\app\Livewire\Pendaftar\Formulir.php
```

**Remote Path:**
```
pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
```

**Steps:**
- [ ] **Same folder as File 5** (already there)
- [ ] Klik "Upload"
- [ ] Pilih file: `Formulir.php`
- [ ] Wait for upload to complete
- [ ] **Overwrite existing:** Yes
- [ ] Verify file exists in list

**Verification:**
```
Formulir.php
Size: ~150-200 KB (large file!)
Modified: [today's date]
```

**⚠️ CRITICAL FIX:**
This file contains the survey checkbox fix at:
- Line 2216: `simpanSurvey()` method

**Note:** File size is large (~150-200KB). Upload may take longer.

---

## 📤 UPLOAD FILE 7: TesPsikologi.php (Admin)

**Local Path:**
```
C:\ppdbmtsn2km-laravel\app\Livewire\Admin\TesPsikologi.php
```

**Remote Path:**
```
pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
```

**Steps:**
- [ ] Navigate ke folder: `app/Livewire/Admin/`
- [ ] Klik "Upload"
- [ ] Pilih file: `TesPsikologi.php`
- [ ] Wait for upload to complete
- [ ] **Overwrite existing:** Yes
- [ ] Verify file exists in list

**Verification:**
```
TesPsikologi.php
Size: ~80-100 KB
Modified: [today's date]
```

**⚠️ CRITICAL FIX:**
This file contains the survey checkbox fix at:
- Line 1212: `updateAnswer()` method

---

## 🧹 CLEAR CACHE

### Via cPanel Terminal

- [ ] Di cPanel, klik "Terminal"
- [ ] Run command 1:
  ```bash
  cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
  ```
- [ ] Run command 2:
  ```bash
  php artisan view:clear
  ```
  **Expected:** "Compiled views cleared!"

- [ ] Run command 3:
  ```bash
  php artisan config:clear
  ```
  **Expected:** "Configuration cache cleared!"

- [ ] Run command 4:
  ```bash
  php artisan cache:clear
  ```
  **Expected:** "Application cache cleared!"

- [ ] Run command 5:
  ```bash
  php artisan optimize:clear
  ```
  **Expected:** "Caches cleared successfully!"

**All commands run successfully?** ✅

---

## ✅ VERIFICATION

### Check File Timestamps

In cPanel File Manager, verify all 7 files show **today's date**:

**Catatan Display Files:**
- [ ] `app/Livewire/Pendaftar/Dashboard.php` - Modified: [today]
- [ ] `resources/views/livewire/pendaftar/dashboard.blade.php` - Modified: [today]
- [ ] `resources/views/livewire/pendaftar/status.blade.php` - Modified: [today]
- [ ] `resources/views/components/status-alert.blade.php` - Modified: [today]

**Survey Fix Files:**
- [ ] `app/Livewire/Pendaftar/TesPsikologi.php` - Modified: [today]
- [ ] `app/Livewire/Pendaftar/Formulir.php` - Modified: [today]
- [ ] `app/Livewire/Admin/TesPsikologi.php` - Modified: [today]

### Quick Content Check (Optional)

Via cPanel File Manager "Edit" button:

**Check File 2:** `dashboard.blade.php`
- [ ] Search for: `bg-yellow-50`
- [ ] Search for: `text-gray-800`
- [ ] Line 358-360 should contain high-contrast styling

**Check File 5:** `TesPsikologi.php` (Pendaftar)
- [ ] Search for: `json_encode`
- [ ] Should appear 2 times in file
- [ ] Line 257 and line 382

**Check File 6:** `Formulir.php`
- [ ] Search for: `json_encode`
- [ ] Should appear at line 2216

**Check File 7:** `TesPsikologi.php` (Admin)
- [ ] Search for: `json_encode`
- [ ] Should appear at line 1212

---

## 🧪 QUICK TEST

### Test 1: Catatan Display
- [ ] Login sebagai pendaftar
- [ ] Access: `/pendaftar/dashboard`
- [ ] **Expected:** Catatan box appears (if status requires)
- [ ] **Text is clearly visible** (dark gray on light yellow)

### Test 2: Survey Checkbox
- [ ] Login sebagai pendaftar
- [ ] Access: `/pendaftar/tes-psikologi`
- [ ] Jawab checkbox question (select multiple)
- [ ] Klik "Simpan & Lanjut"
- [ ] **Expected:** NO error "Array to string conversion"
- [ ] **Success!** Can proceed to next question

---

## 📊 COMPLETION SUMMARY

**Upload Date:** [Fill in]
**Upload Time:** [Fill in]
**Uploaded By:** [Your name]

**Files Uploaded:** 7/7 ✅
- [x] Dashboard.php
- [x] dashboard.blade.php
- [x] status.blade.php
- [x] status-alert.blade.php
- [x] TesPsikologi.php (Pendaftar)
- [x] Formulir.php
- [x] TesPsikologi.php (Admin)

**Cache Cleared:** Yes ✅
**Initial Test:** [PASS/FAIL]
**Issues Found:** [None/List any]

---

## 🚨 TROUBLESHOOTING

### Upload Fails or Slow
**Possible causes:**
- File size too large (especially Formulir.php ~150-200KB)
- Network timeout
- Server busy

**Solutions:**
1. Try uploading one file at a time
2. Wait 1-2 minutes between uploads
3. Use "Upload" button instead of drag-drop
4. Check browser console for errors

### Cannot Overwrite File
**Possible causes:**
- File permissions
- File is locked

**Solutions:**
1. Delete old file first (select → Delete)
2. Then upload new file
3. Set permissions to 644 after upload

### Terminal Commands Fail
**Possible causes:**
- Wrong directory
- PHP not in PATH

**Solutions:**
1. Verify you're in correct directory:
   ```bash
   pwd
   # Should show: /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
   ```
2. Use full PHP path:
   ```bash
   /usr/bin/php artisan view:clear
   ```

### Cache Not Clearing
**Alternative method:**
Delete cache files manually:
- Navigate: `storage/framework/views/`
- Select all `.php` files
- Click "Delete"
- Refresh site

---

## 📞 SUPPORT CONTACT

If upload fails or issues occur:

1. **Check Laravel logs:**
   - cPanel File Manager → `storage/logs/laravel.log`
   - Look for errors related to uploaded files

2. **Check web server error log:**
   - cPanel → "Errors" section
   - Look for recent PHP errors

3. **Restore from backup if needed:**
   - Keep backups of old files before overwrite
   - Can revert by re-uploading old version

---

## ✅ FINAL CHECKLIST

Before closing:

- [ ] All 7 files uploaded successfully
- [ ] File timestamps show today's date
- [ ] All caches cleared
- [ ] Quick test passed (catatan visible, survey works)
- [ ] No errors in Laravel log
- [ ] Browser cache cleared (Ctrl+Shift+R)
- [ ] Documented completion details above

**Deployment Status:** ✅ COMPLETE

**Next Steps:**
→ Run full test suite: `QUICK_TEST_GUIDE_6_FEB_2026.md`
→ Monitor logs for 1-2 hours
→ Notify users if needed

---

**Created:** 6 Februari 2026
**Method:** Manual cPanel upload (SSH unavailable)
**Priority:** HIGH - Critical fixes
**Estimated Upload Time:** 15-20 minutes
**Post-Upload Test Time:** 15-20 minutes
**Total Time:** ~30-40 minutes
