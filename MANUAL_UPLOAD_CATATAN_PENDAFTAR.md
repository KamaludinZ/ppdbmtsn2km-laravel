# MANUAL UPLOAD - CATATAN PENDAFTAR

## 🚨 Status

**SSH Connection Issue:** Server masih menutup koneksi SSH.

**Solusi:** Upload manual via cPanel File Manager atau tunggu SSH kembali tersedia.

## 📦 Files yang Perlu Diupload

### File 1: Dashboard.php (Backend)
**Local:**
```
C:\ppdbmtsn2km-laravel\app\Livewire\Pendaftar\Dashboard.php
```

**Remote:**
```
/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
```

**Perubahan:**
- Line 312-318: Added check for 'data_ditolak' status
- Progress step 2 (Formulir) now shows "Ditolak" instead of "Menunggu"

---

### File 2: dashboard.blade.php (View)
**Local:**
```
C:\ppdbmtsn2km-laravel\resources\views\livewire\pendaftar\dashboard.blade.php
```

**Remote:**
```
/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
```

**Perubahan:**
- Line 345-367: Added catatan verifikasi display box
- Line 358-360: **FIXED STYLING** - Changed from white-on-white to dark-on-yellow
- Shows ABOVE "Progress Pendaftaran" section
- Yellow/orange gradient with warning icon
- **Inner box: bg-yellow-50 + text-gray-800 (HIGH CONTRAST)**

---

### File 3: status.blade.php (View)
**Local:**
```
C:\ppdbmtsn2km-laravel\resources\views\livewire\pendaftar\status.blade.php
```

**Remote:**
```
/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
```

**Perubahan:**
- Line 69-91: Added catatan verifikasi display box
- Line 82-84: **FIXED STYLING** - Changed from white-on-white to dark-on-yellow
- Shows BELOW "Status Card" section
- Same high-contrast design as dashboard
- **Inner box: bg-yellow-50 + text-gray-800 (HIGH CONTRAST)**

---

### File 4: status-alert.blade.php (Component)
**Local:**
```
C:\ppdbmtsn2km-laravel\resources\views\components\status-alert.blade.php
```

**Remote:**
```
/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/components/
```

**Perubahan:**
- Line 5: Added lengkapi_data, perbaiki_data, data_ditolak to $showAlert array
- Line 14-33: Added config for new status values
- Enables status alert in /pendaftar/formulir

---

## 🔧 Cara Upload Manual

### Metode 1: cPanel File Manager

1. **Login ke cPanel**
   - URL: https://103.30.244.77:2083 (atau sesuai panel Anda)
   - Username: mtsnkot4
   - Password: [password Anda]

2. **Buka File Manager**
   - Klik icon "File Manager"
   - Navigate ke: `pmbm.mtsn2kotamalang.sch.id`

3. **Upload File 1: Dashboard.php**
   - Navigate: `app/Livewire/Pendaftar/`
   - Klik "Upload"
   - Pilih: `C:\ppdbmtsn2km-laravel\app\Livewire\Pendaftar\Dashboard.php`
   - Overwrite existing file

4. **Upload File 2: dashboard.blade.php**
   - Navigate: `resources/views/livewire/pendaftar/`
   - Upload: `dashboard.blade.php`
   - Overwrite existing file

5. **Upload File 3: status.blade.php**
   - Same folder as File 2
   - Upload: `status.blade.php`
   - Overwrite existing file

6. **Upload File 4: status-alert.blade.php**
   - Navigate: `resources/views/components/`
   - Upload: `status-alert.blade.php`
   - Overwrite existing file

7. **Clear Cache**
   - Open "Terminal" in cPanel
   - Run:
   ```bash
   cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
   php artisan view:clear
   php artisan config:clear
   php artisan cache:clear
   ```

---

### Metode 2: FTP/SFTP (FileZilla)

1. **Open FileZilla**
   - Host: 103.30.244.77
   - Username: mtsnkot4
   - Password: [password Anda]
   - Port: 21 (FTP) atau 22 (SFTP)

2. **Connect & Navigate**
   - Remote site: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id`

3. **Upload Files**
   - Drag & drop 4 files dari local ke remote folder yang sesuai
   - Overwrite ketika ditanya

4. **Clear Cache**
   - Via cPanel Terminal atau web-based SSH

---

### Metode 3: Wait for SSH

1. **Tunggu 10-15 menit**
   - Server mungkin sedang maintenance atau temporary block

2. **Test SSH Connection**
   ```bash
   ssh mtsnkot4@103.30.244.77 "echo 'Connection OK'"
   ```

3. **Jika Berhasil, Run Batch:**
   ```bash
   upload_catatan_pendaftar_complete.bat
   ```

---

## 🧪 Post-Upload Testing

### Test 1: Progress Formulir Ditolak
```sql
-- Via phpMyAdmin
UPDATE pendaftar
SET status_pendaftaran = 'data_ditolak',
    catatan_verifikasi = 'NISN tidak valid'
WHERE id = [test_id];
```

**Expected:**
- Access /pendaftar/dashboard
- Progress Step 2: "Ditolak" (RED) ✅

---

### Test 2: Catatan Dashboard
```sql
UPDATE pendaftar
SET status_pendaftaran = 'perbaiki_data',
    catatan_verifikasi = 'Mohon lengkapi data orang tua'
WHERE id = [test_id];
```

**Expected:**
- Access /pendaftar/dashboard
- Yellow/orange box appears ABOVE progress ✅
- Catatan text displayed clearly ✅

---

### Test 3: Catatan Status Page
**Same setup as Test 2**

**Expected:**
- Access /pendaftar/status
- Catatan box appears BELOW status card ✅

---

### Test 4: Status Alert Formulir
**Same setup as Test 2**

**Expected:**
- Access /pendaftar/formulir
- Status alert shows in header ✅
- Catatan displayed inside alert ✅

---

## ✅ Verification Commands

```bash
# SSH to server
ssh mtsnkot4@103.30.244.77

# Navigate
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Verify Dashboard.php change
grep -n "data_ditolak" app/Livewire/Pendaftar/Dashboard.php

# Verify dashboard.blade.php
grep -n "Catatan Verifikasi" resources/views/livewire/pendaftar/dashboard.blade.php

# Verify status.blade.php
grep -n "Catatan Verifikasi" resources/views/livewire/pendaftar/status.blade.php

# Verify status-alert.blade.php
grep -n "lengkapi_data" resources/views/components/status-alert.blade.php

# Check file timestamps
ls -lh app/Livewire/Pendaftar/Dashboard.php
ls -lh resources/views/livewire/pendaftar/dashboard.blade.php
ls -lh resources/views/livewire/pendaftar/status.blade.php
ls -lh resources/views/components/status-alert.blade.php
```

---

## 📝 Summary

**Total Files:** 4
**Upload Method:** Manual (SSH unavailable)
**Post-Upload:** Clear cache
**Testing:** 4 test scenarios

**Features:**
1. ✅ Progress formulir shows "Ditolak" for data_ditolak
2. ✅ Catatan display on dashboard (above progress)
3. ✅ Catatan display on status page (below status card)
4. ✅ Status alert on formulir page (header info)

---
**Created:** 6 Februari 2026
**SSH Status:** Connection closed by server
**Workaround:** Manual upload via cPanel/FTP
