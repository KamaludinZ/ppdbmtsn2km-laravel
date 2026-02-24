# MANUAL UPLOAD - ERROR 500 FIX TES PSIKOLOGI
## 12 Februari 2026

## 🔴 CRITICAL - SSH CONNECTION REFUSED

**Automated upload GAGAL!**
SSH connection ke server production refused.

**UPLOAD MANUAL VIA CPANEL REQUIRED!**

---

## 📤 MANUAL UPLOAD INSTRUCTIONS

### File yang Harus Di-Upload:

**Local Path:**
```
C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\partials\tes-psikologi-data.blade.php
```

**Production Path:**
```
/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/
```

**File Name:**
```
tes-psikologi-data.blade.php
```

---

## 🔧 STEP-BY-STEP UPLOAD VIA CPANEL

### Step 1: Login ke cPanel
1. Buka browser
2. Akses cPanel URL
3. Login dengan kredensial cPanel

### Step 2: Buka File Manager
1. Di cPanel dashboard, cari **"File Manager"**
2. Klik **"File Manager"**
3. File Manager akan terbuka di tab/window baru

### Step 3: Navigate ke Folder Target
1. Di sidebar kiri File Manager, expand folder:
   ```
   home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/
   ```

2. Klik folder:
   ```
   resources/
   ```

3. Klik folder:
   ```
   views/
   ```

4. Klik folder:
   ```
   livewire/
   ```

5. Klik folder:
   ```
   admin/
   ```

6. Klik folder:
   ```
   partials/
   ```

7. Anda sekarang berada di:
   ```
   /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/
   ```

### Step 4: Backup File Lama (Optional tapi Recommended)
1. Cari file: **tes-psikologi-data.blade.php**
2. Right-click pada file tersebut
3. Pilih **"Copy"**
4. Rename copy menjadi: **tes-psikologi-data.blade.php.backup-12feb2026**

### Step 5: Upload File Baru
1. Di toolbar File Manager, klik tombol **"Upload"**
2. Upload dialog akan muncul
3. Klik tombol **"Select File"** atau drag & drop
4. Navigate ke folder lokal:
   ```
   C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\partials\
   ```
5. Pilih file: **tes-psikologi-data.blade.php**
6. Klik **"Open"**
7. Upload akan dimulai
8. Tunggu sampai upload 100% selesai

### Step 6: Replace File Lama
1. Jika ada prompt "File already exists", pilih **"Overwrite"**
2. File lama akan di-replace dengan file baru

### Step 7: Verify Upload
1. Di File Manager, refresh halaman
2. Cari file: **tes-psikologi-data.blade.php**
3. Right-click → **"View"** atau **"Edit"**
4. Verify bahwa file sudah ter-update:
   - Cek line 52-58: Tidak ada option "belum_upload"
   - Cek line 74-104: Header structure simplified
   - Cek line 206: Direct `$data->pendaftar_id` access

---

## ✅ VERIFICATION CHECKLIST

**After Upload, Check:**

### ✅ File Upload Success
- [ ] File **tes-psikologi-data.blade.php** exists di production
- [ ] File size ~18-20 KB (similar to local file)
- [ ] Timestamp updated (recent upload time)

### ✅ Backup Created (Optional)
- [ ] Backup file **tes-psikologi-data.blade.php.backup-12feb2026** exists

---

## 🧪 TESTING AFTER UPLOAD

**URGENT - TEST SEGERA SETELAH UPLOAD!**

### Test 1: Akses Halaman Admin ⭐ PRIORITY
```
Action:
1. Buka browser baru (incognito/private mode)
2. Login sebagai Admin
3. Navigate: Menu Tes Psikologi > Data Psikologi
4. URL: /admin/tes-psikologi

Expected:
✅ Halaman load normal tanpa error 500
✅ Table tampil dengan data
✅ Filter dropdown ada 5 options (tanpa "Belum Upload")
```

### Test 2: Akses Halaman Panitia
```
Action:
1. Login sebagai Panitia (role: panitia_tes_psikologi)
2. Navigate: Menu Tes Psikologi
3. URL: /panitia/tes-psikologi

Expected:
✅ Halaman load normal tanpa error 500
✅ Table tampil dengan data
```

### Test 3: Filter Dropdown
```
Action:
Check filter dropdown options

Expected:
✅ Semua Status
✅ Menunggu Verifikasi
✅ Disetujui
✅ Ditolak
✅ Perlu Perbaikan
❌ TIDAK ADA "Belum Upload"
```

### Test 4: Filter "Semua Status"
```
Action:
Pilih filter "Semua Status"

Expected:
✅ Tampil semua data tes psikologi
✅ Table ada 12 kolom
✅ Extended columns (WhatsApp, Ubah Status, dll) tampil
```

### Test 5: Filter "Menunggu Verifikasi"
```
Action:
Pilih filter "Menunggu Verifikasi"

Expected:
✅ Tampil hanya data dengan status menunggu
✅ Table normal
✅ No error
```

### Test 6: Filter Lainnya
```
Action:
Test filter "Disetujui", "Ditolak", "Perlu Perbaikan"

Expected:
✅ Semua filter berfungsi normal
✅ No error 500
```

### Test 7: WhatsApp Clickable
```
Action:
Klik nomor di kolom "Ortu WhatsApp"

Expected:
✅ Buka WhatsApp Web/App
✅ Chat dengan nomor tersebut ready
```

### Test 8: Sorting
```
Action:
Klik header "Waktu Transfer" untuk sort

Expected:
✅ Data ter-sort ascending/descending
✅ Sort icon muncul
```

### Test 9: Survey Modal
```
Action:
Klik tombol "Lihat" di kolom Status Survey (jika ada)

Expected:
✅ Modal muncul
✅ Tampil jawaban survey pendaftar
```

### Test 10: Extended Columns
```
Action:
Test kolom:
- Ubah Status (dropdown)
- Edit Catatan (tombol)
- Kirim Email (tombol)
- Kirim WhatsApp (tombol)

Expected:
✅ Semua fitur berfungsi normal
```

---

## ❌ TROUBLESHOOTING

### Issue: File Upload Failed
**Solution:**
- Check file size (should be ~18-20 KB)
- Check file permissions (644 or 755)
- Try upload again
- Contact hosting support

### Issue: Still Error 500 After Upload
**Check:**
1. File benar-benar ter-upload (check timestamp)
2. File size correct
3. File content correct (open & verify)
4. Try hard refresh browser (Ctrl+Shift+R)
5. Clear browser cache

**If Still Error:**
- Check production error log
- Revert to backup file
- Contact developer

### Issue: Filter Dropdown Still Shows "Belum Upload"
**Solution:**
- Hard refresh browser (Ctrl+Shift+R)
- Clear browser cache
- Try incognito/private mode
- Verify file upload (check content)

---

## 📊 SUCCESS CRITERIA

**Fix Successful if ALL of these pass:**

- ✅ `/admin/tes-psikologi` loads tanpa error 500
- ✅ `/panitia/tes-psikologi` loads tanpa error 500
- ✅ Filter dropdown ada 5 options (no "Belum Upload")
- ✅ Semua filter berfungsi normal
- ✅ WhatsApp clickable works
- ✅ Sorting works
- ✅ Survey modal works
- ✅ Extended columns accessible

**If ANY test fails:**
- Screenshot error
- Check error log
- Revert to backup
- Report issue

---

## 🔄 ROLLBACK PLAN (If Fix Fails)

### If Upload Causes Issues:

**Step 1: Restore Backup**
1. Di File Manager
2. Navigate ke folder partials
3. Find backup: `tes-psikologi-data.blade.php.backup-12feb2026`
4. Right-click → Copy
5. Rename copy menjadi: `tes-psikologi-data.blade.php`
6. Overwrite file yang baru di-upload

**Step 2: Test Again**
1. Refresh halaman `/admin/tes-psikologi`
2. Verify halaman kembali ke kondisi sebelumnya

**Step 3: Report**
- Screenshot error yang terjadi
- Check production error log
- Report ke developer

---

## 📝 NOTES

**File Size:**
- Local file: ~18-20 KB
- Production file: Should match

**Permissions:**
- Default: 644 (rw-r--r--)
- No need to change unless specified

**Cache:**
- Laravel view cache: Not needed (view file auto-recompiled)
- Browser cache: May need hard refresh

**No Cache Clear Needed!**
View files di Laravel auto-recompiled on next request.
Cukup refresh browser.

---

## 📚 RELATED DOCUMENTATION

**Main Fix Documentation:**
- FIX_ERROR_500_TES_PSIKOLOGI_REMOVE_BELUM_UPLOAD_FILTER_12_FEB_2026.md

**Summary:**
- FINAL_SOLUTION_ERROR_500_TES_PSIKOLOGI_12_FEB_2026.md

**Features Documentation:**
- ADD_SORTING_TES_PSIKOLOGI_12_FEB_2026.md
- ADD_WHATSAPP_CLICKABLE_ORTU_12_FEB_2026.md

---

## ⏰ TIMELINE

**Estimated Time:**

1. Login cPanel: ~1 min
2. Navigate folder: ~1 min
3. Backup file (optional): ~1 min
4. Upload file: ~2 min
5. Verify upload: ~1 min
6. Testing: ~5 min

**TOTAL: ~10-15 minutes**

---

## 🎯 CURRENT STATUS

**Upload Status:** ❌ SSH Connection Refused → Manual upload required
**File Ready:** ✅ Local file ready for upload
**Documentation:** ✅ Complete
**Testing Checklist:** ✅ Ready

**NEXT ACTION:** Upload file via cPanel sesuai instruksi di atas!

---

**Priority:** 🔴 URGENT - CRITICAL FIX

**Reason:** Halaman Tes Psikologi tidak bisa diakses (Error 500)

**Action:** Upload SEGERA via cPanel! 🚀
