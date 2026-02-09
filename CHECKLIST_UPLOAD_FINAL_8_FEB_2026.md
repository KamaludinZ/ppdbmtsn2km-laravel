# Checklist Upload Final - 8 Februari 2026

## 📋 Summary of Changes

1. ✅ **Redesign Paket Seragam** - Dependent dropdown (Paket → Opsi)
2. ✅ **Hapus Duplikat Section** - Removed old "Paket Seragam" section
3. ✅ **Perbaiki Upload Bukti** - Improved file upload UI
4. ✅ **Tambah Warning Perbedaan Ukuran** - Warning muncul jika ukuran baju ≠ celana

---

## 📦 Files to Upload

### 1. Database Migration
```
database/migrations/2026_02_08_100000_add_opsi_seragam_to_seragam_table.php
```

### 2. Models
```
app/Models/Seragam.php
```

### 3. Livewire Components
```
app/Livewire/Admin/DaftarUlang.php
app/Livewire/Pendaftar/DaftarUlang.php
```

### 4. Views
```
resources/views/livewire/admin/daftar-ulang.blade.php
resources/views/livewire/pendaftar/daftar-ulang.blade.php
```

**PENTING:** View `pendaftar/daftar-ulang.blade.php` mencakup 4 perubahan:
- Dependent dropdown paket seragam
- Hapus duplikat section paket seragam
- Improved upload bukti UI
- **NEW:** Warning perbedaan ukuran (baju vs celana harian)

---

## 🔧 Commands to Run on Production

### 1. Upload Files
```batch
# Run on Windows (local)
upload_redesign_paket_seragam_8_feb_2026.bat
```

### 2. SSH to Server
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
```

### 3. Run Migration
```bash
# Check current migration status
php artisan migrate:status | grep opsi_seragam

# Run migration
php artisan migrate

# Verify migration ran
php artisan migrate:status | grep "2026_02_08_100000"
```

### 4. Clear Cache
```bash
# Clear all cache
php artisan optimize:clear

# Optional: Check cache cleared
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

---

## ✅ Verification Steps

### 1. Check Database
```sql
-- Connect to PostgreSQL
psql -U mtsnkot4 -d mtsnkot4_ppdb

-- Check column exists
\d seragam

-- Should show: opsi_seragam | text | nullable
```

### 2. Test Admin Settings
- [ ] Navigate to `/admin/daftar-ulang?activeTab=settings`
- [ ] Scroll to "Daftar Ulang Seragam" section
- [ ] ✅ See hierarchical "Paket Seragam" section (Cyan boxes)
- [ ] Click "+ Tambah Paket"
- [ ] ✅ New package appears with empty options section
- [ ] Click "+ Tambah Opsi" inside package
- [ ] ✅ New option fields appear (Name + Amount)
- [ ] Fill in:
  - Package name: "Paket Lengkap"
  - Package description: "Test description"
  - Option 1: Name: "Putra", Amount: 1500000
  - Option 2: Name: "Putri", Amount: 1450000
- [ ] Click "Simpan Pengaturan"
- [ ] ✅ Success message appears
- [ ] Reload page
- [ ] ✅ Data persists

### 3. Test Pendaftar Form
- [ ] Login as pendaftar
- [ ] Navigate to `/pendaftar/daftarulang?activeTab=seragam`
- [ ] ✅ **NO duplicate "Paket Seragam" section** at the top
- [ ] ✅ Form starts with "Step 1: Pilih Paket Seragam & Nominal"
- [ ] ✅ See blue gradient box for Section 1
- [ ] ✅ See dashed separator line
- [ ] ✅ See green gradient box for Section 2

### 3a. Test Warning Perbedaan Ukuran (NEW)
- [ ] **Test Ukuran BERBEDA (Warning Muncul):**
  ```sql
  UPDATE seragam SET ukuran_baju_harian = 'M', ukuran_celana_rok_harian = 'L'
  WHERE pendaftar_id = [TEST_USER_ID];
  ```
  - [ ] Reload `/pendaftar/daftarulang?activeTab=seragam`
  - [ ] ✅ See ORANGE warning box above Step 1
  - [ ] ✅ Text: "Perhatian: Perbedaan Ukuran Seragam Harian Terdeteksi!"
  - [ ] ✅ Shows: Baju (M) dan Celana (L)
  - [ ] ✅ Shows: "Ukuran untuk pembayaran: L"
  - [ ] ✅ Warning triangle icon visible
- [ ] **Test Ukuran SAMA (Tidak Ada Warning):**
  ```sql
  UPDATE seragam SET ukuran_baju_harian = 'L', ukuran_celana_rok_harian = 'L'
  WHERE pendaftar_id = [TEST_USER_ID];
  ```
  - [ ] Reload page
  - [ ] ✅ NO orange warning box appears
  - [ ] ✅ Form starts directly at Step 1
- [ ] **Test Belum Ada Ukuran (Tidak Ada Warning):**
  ```sql
  UPDATE seragam SET ukuran_baju_harian = NULL, ukuran_celana_rok_harian = NULL
  WHERE pendaftar_id = [TEST_USER_ID];
  ```
  - [ ] Reload page
  - [ ] ✅ NO warning box
  - [ ] ✅ Form normal

#### Section 1 Testing:
- [ ] Select package from dropdown
- [ ] ✅ Dependent "Pilih Opsi" section appears with radio buttons
- [ ] Select an option
- [ ] ✅ "Konfirmasi Nominal" section appears
- [ ] ✅ "Bayar Penuh" is pre-selected with correct amount
- [ ] ✅ "Opsi Lainnya" option available (if enabled in admin)

#### Section 2 Testing:
- [ ] ✅ "Nama Pengirim" field visible below Bank dropdown
- [ ] ✅ Helper text: "Nama orang yang mengirimkan pembayaran..."
- [ ] Fill all fields:
  - Opsi Pembayaran: "Lunas"
  - Bank: Select a bank
  - Nama Pengirim: "Test User"

#### Upload Bukti Testing:
- [ ] ✅ Empty state shows drag & drop area
- [ ] ✅ Text: "Klik untuk upload bukti pembayaran"
- [ ] ✅ Text: "atau drag and drop file di sini"
- [ ] Click to upload file (or drag file)
- [ ] ✅ **File Selected State** appears:
  - ✅ Large PDF/file icon (64x64)
  - ✅ Green border and background
  - ✅ Checkmark with "File Siap Disimpan"
  - ✅ File name displayed (truncated if long)
  - ✅ File type shown (PDF/JPG/PNG)
  - ✅ File size shown (e.g., "1,234 KB")
  - ✅ "Ganti File" button visible
- [ ] Click "Ganti File"
- [ ] ✅ Can select different file
- [ ] Click "Simpan Data Seragam"
- [ ] ✅ Success message
- [ ] Reload page
- [ ] ✅ All data persists

### 4. Database Verification
```sql
-- Check saved data
SELECT
    paket_seragam,
    opsi_seragam,
    nominal_seragam,
    nama_pengirim_seragam,
    bukti_pembayaran
FROM seragam
WHERE pendaftar_id = [TEST_USER_ID];
```

**Expected Result:**
```json
{
  "paket_seragam": "{\"id\":\"pkg_...\",\"name\":\"Paket Lengkap\",\"description\":\"...\",\"options\":[...]}",
  "opsi_seragam": "{\"id\":\"opt_...\",\"name\":\"Putra\",\"amount\":1500000}",
  "nominal_seragam": "{\"name\":\"Putra\",\"amount\":1500000}",
  "nama_pengirim_seragam": "Test User",
  "bukti_pembayaran": "seragam/..."
}
```

---

## 🐛 Troubleshooting

### Issue: Migration Fails
```bash
# Check if column already exists
psql -U mtsnkot4 -d mtsnkot4_ppdb
\d seragam

# If column exists, mark migration as run
php artisan migrate:status
# Manually insert into migrations table if needed
```

### Issue: View Not Updating
```bash
# Clear view cache
php artisan view:clear

# If still not updating, check file upload worked
ls -la resources/views/livewire/pendaftar/daftar-ulang.blade.php

# Check file size matches local version
```

### Issue: Settings Not Saving
```bash
# Check settings table
psql -U mtsnkot4 -d mtsnkot4_ppdb
SELECT key, value FROM settings WHERE key LIKE '%seragam%';

# Clear config cache
php artisan config:clear
```

### Issue: Upload Bukti Not Working
```bash
# Check storage permissions
ls -la storage/app/public/

# Check symbolic link
ls -la public/storage

# If symlink missing
php artisan storage:link
```

---

## 📝 Post-Deployment Notes

### What to Document
- [ ] Date and time of deployment
- [ ] Who deployed
- [ ] Any issues encountered
- [ ] Testing results
- [ ] Screenshots of working features

### Backup Reminder
Before deployment, ensure you have backups of:
- [ ] Database (`pg_dump`)
- [ ] Modified files (especially views)
- [ ] Settings table

### Rollback Plan
If critical issues occur:
```bash
# 1. Rollback migration
php artisan migrate:rollback --step=1

# 2. Restore old files
# (Use your backup files)

# 3. Clear cache
php artisan optimize:clear
```

---

## ✅ Sign-Off

### Pre-Deployment
- [ ] All files uploaded successfully
- [ ] Migration ran without errors
- [ ] Cache cleared
- [ ] No console errors in browser

### Post-Deployment Testing
- [ ] Admin can create hierarchical packages ✅
- [ ] Pendaftar sees dependent dropdowns ✅
- [ ] No duplicate sections ✅
- [ ] Upload bukti UI improved ✅
- [ ] Nama Pengirim field works ✅
- [ ] Data saves correctly ✅
- [ ] Database structure verified ✅

### Final Approval
- [ ] Tested by: _______________
- [ ] Date: _______________
- [ ] Time: _______________
- [ ] Production URL verified: https://pmbm.mtsn2kotamalang.sch.id
- [ ] No critical issues found: ✅

---

**Deployment Status:** 🟢 Ready for Production
**Last Updated:** 8 Februari 2026
