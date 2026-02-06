# Upload ke Production - History Pesan & Filter Data Enhancement
**Tanggal:** 3 Februari 2026
**Sesi:** WhatsApp Queue Enhancement + Layout Improvements + Stats Overview

---

## 🎯 Ringkasan Perubahan

Sesi ini mencakup 3 fitur utama:
1. **WhatsApp Queue System** - Kolom "Isi Pesan" dan "Waktu Tersisa Antrian"
2. **Layout Improvements** - Filter EMIS dan reorganisasi search field di Admin/Panitia Pendaftar
3. **Stats Overview** - Statistik untuk History Email dan WhatsApp

---

## 📁 DAFTAR FILE YANG HARUS DIUPLOAD

### A. Model Files (1 file)

```
app/Models/MessageQueue.php
```
**Perubahan:**
- Added `getRemainingTime()` method (lines 272-311)
- Added `getRemainingTimeBadgeClass()` method (lines 313-341)
- Untuk menampilkan waktu tersisa dan badge berwarna

---

### B. Controller Files (3 files)

#### 1. Admin Pendaftar Controller
```
app/Livewire/Admin/Pendaftar.php
```
**Perubahan:**
- Added `$filterEmisCompleteness` property (line 21)
- Added to `$queryString` array (line 93)
- Added `updatingFilterEmisCompleteness()` method (lines 138-141)
- Added EMIS filtering logic in `render()` with manual pagination (lines 177-206)
- **PENTING:** Fixed pagination error line 193 - changed `\Livewire\WithPagination::resolvePage()` to `$this->getPage()`

#### 2. Panitia Pendaftar Controller
```
app/Livewire/Panitia/Pendaftar.php
```
**Perubahan:**
- Added `$filterEmisCompleteness` property (line 20)
- Added to `$queryString` array (line 82)
- Added `updatingFilterEmisCompleteness()` method (lines 134-137)
- Added EMIS filtering logic in `render()` with manual pagination (lines 169-198)
- **PENTING:** Fixed pagination error line 185 - changed `\Livewire\WithPagination::resolvePage()` to `$this->getPage()`

#### 3. Pengaturan Pesan Controller
```
app/Livewire/Admin/PengaturanPesan.php
```
**Perubahan:**
- Added `getEmailStatistics()` method (lines 532-561)
- Updated `render()` method to pass `emailStats` to view (line 857)

---

### C. View Files (4 files)

#### 1. Admin Pendaftar View
```
resources/views/livewire/admin/pendaftar.blade.php
```
**Perubahan:**
- Moved search field to header right side (lines 152-186)
- Replaced "Pencarian" filter with "Kelengkapan EMIS" filter in grid (lines 189-198)
- Search now has dedicated space with clear button

#### 2. Panitia Pendaftar View
```
resources/views/livewire/panitia/pendaftar.blade.php
```
**Perubahan:**
- Moved search field to header right side (lines 137-171)
- Replaced "Pencarian" filter with "Kelengkapan EMIS" filter in grid (lines 174-183)
- Consistent with Admin view

#### 3. History Email Tab View
```
resources/views/livewire/admin/partials/history-email-tab.blade.php
```
**Perubahan:**
- Added stats overview grid with 5 cards (lines 3-79):
  - Total Pesan (blue)
  - Pending (yellow)
  - Terkirim (green)
  - Gagal (red)
  - Hari Ini (purple)
- Wrapped existing content in proper structure

#### 4. History WhatsApp Tab View
```
resources/views/livewire/admin/partials/history-whatsapp-tab.blade.php
```
**Perubahan:**
- Changed stats grid from 4 to 5 columns (line 4)
- Added "Total Pesan" card (lines 5-18)
- Added "Waktu Tersisa" column header (line 150)
- Added remaining time display with color-coded badges
- Added "Isi Pesan" column with eye icon button for viewing full message
- Added modal for displaying full message content (lines 374-416)
- Added JavaScript functions for modal functionality (lines 418-464)
- Added copy to clipboard functionality

---

### D. Database Migration (1 file)

```
database/migrations/2026_02_03_210000_create_message_queue_table.php
```
**Perubahan:**
- Fixed foreign key constraint on line 16
- Changed from `constrained('pendaftars')` to `constrained('pendaftar')`
- **STATUS:** Migration sudah dijalankan di local, perlu dijalankan di production

---

## 🚀 LANGKAH-LANGKAH UPLOAD KE PRODUCTION

### Step 1: Backup Production Database
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
pg_dump -U mtsnkot4 -d mtsnkot4_ppdb > backup_before_history_pesan_$(date +%Y%m%d_%H%M%S).sql
```

### Step 2: Upload Files via SCP/SFTP

Dari local directory `C:\ppdbmtsn2km-laravel\`, upload file-file berikut:

```bash
# Model
scp app/Models/MessageQueue.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/

# Controllers
scp app/Livewire/Admin/Pendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
scp app/Livewire/Panitia/Pendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/
scp app/Livewire/Admin/PengaturanPesan.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/

# Views
scp resources/views/livewire/admin/pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
scp resources/views/livewire/panitia/pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/
scp resources/views/livewire/admin/partials/history-email-tab.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/
scp resources/views/livewire/admin/partials/history-whatsapp-tab.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/

# Migration
scp database/migrations/2026_02_03_210000_create_message_queue_table.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/
```

### Step 3: SSH ke Production dan Jalankan Commands

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Pastikan permission benar
chmod 644 app/Models/MessageQueue.php
chmod 644 app/Livewire/Admin/Pendaftar.php
chmod 644 app/Livewire/Panitia/Pendaftar.php
chmod 644 app/Livewire/Admin/PengaturanPesan.php
chmod 644 resources/views/livewire/admin/pendaftar.blade.php
chmod 644 resources/views/livewire/panitia/pendaftar.blade.php
chmod 644 resources/views/livewire/admin/partials/history-email-tab.blade.php
chmod 644 resources/views/livewire/admin/partials/history-email-tab.blade.php
chmod 644 database/migrations/2026_02_03_210000_create_message_queue_table.php

# Jalankan migration
php artisan migrate --force

# Clear all caches
php artisan optimize:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
php artisan cache:clear

# Recompile views (optional tapi disarankan)
php artisan view:cache
php artisan config:cache
php artisan route:cache
```

### Step 4: Verifikasi di Production

Setelah upload, verifikasi hal-hal berikut:

1. **WhatsApp Queue Features:**
   - [ ] Buka menu "Pengaturan Pesan" > Tab "History WhatsApp"
   - [ ] Cek apakah kolom "Waktu Tersisa" muncul dengan badge berwarna
   - [ ] Cek apakah tombol mata (eye icon) untuk "Isi Pesan" berfungsi
   - [ ] Klik tombol mata, pastikan modal muncul dengan isi pesan lengkap
   - [ ] Test tombol "Copy to Clipboard"
   - [ ] Cek apakah statistik "Total Pesan" muncul di card paling kiri

2. **History Email Stats:**
   - [ ] Buka tab "History Email"
   - [ ] Pastikan 5 kartu statistik muncul: Total Pesan, Pending, Terkirim, Gagal, Hari Ini
   - [ ] Verifikasi angka-angka sesuai dengan data di tabel

3. **Admin Pendaftar - Filter EMIS:**
   - [ ] Buka `/admin/pendaftar`
   - [ ] Cek search field sudah pindah ke header (sebelah kanan "Filter Data")
   - [ ] Cek dropdown "Kelengkapan EMIS" muncul di filter grid
   - [ ] Test filter dengan memilih range: 0-25%, 26-50%, 51-75%, 76-100%
   - [ ] Verifikasi pagination bekerja dengan baik

4. **Panitia Pendaftar - Filter EMIS:**
   - [ ] Buka `/panitia-pendaftaran/pendaftar` atau `/panitia-daftarulang/pendaftar`
   - [ ] Cek search field sudah pindah ke header
   - [ ] Cek dropdown "Kelengkapan EMIS" muncul
   - [ ] Test filter dan pagination

---

## 🔍 TROUBLESHOOTING

### Jika Migration Gagal

**Error:** `relation "pendaftars" does not exist`
```bash
# Cek nama tabel yang benar
php artisan tinker
>>> (new App\Models\Pendaftar)->getTable();
# Output: "pendaftar"

# Edit migration jika masih salah
nano database/migrations/2026_02_03_210000_create_message_queue_table.php
# Line 16: pastikan constrained('pendaftar') bukan constrained('pendaftars')

# Rollback dan re-run
php artisan migrate:rollback --step=1
php artisan migrate --force
```

### Jika View Tidak Update

```bash
# Clear view cache lebih agresif
rm -rf storage/framework/views/*.php
php artisan view:clear
php artisan optimize:clear

# Restart PHP-FPM jika perlu
sudo systemctl restart php8.2-fpm
```

### Jika Pagination Error Muncul

**Error:** `Call to undefined method Livewire\WithPagination::resolvePage()`

Pastikan file controller sudah menggunakan `$this->getPage()` bukan `\Livewire\WithPagination::resolvePage()`

Check di:
- `app/Livewire/Admin/Pendaftar.php` line 193
- `app/Livewire/Panitia/Pendaftar.php` line 185

---

## 📊 SUMMARY

### Total Files to Upload: **9 files**

| Type | Count | Files |
|------|-------|-------|
| Model | 1 | MessageQueue.php |
| Controller | 3 | Admin/Pendaftar.php, Panitia/Pendaftar.php, Admin/PengaturanPesan.php |
| View | 4 | admin/pendaftar, panitia/pendaftar, partials/history-email-tab, partials/history-whatsapp-tab |
| Migration | 1 | 2026_02_03_210000_create_message_queue_table.php |

### New Features Added:
1. ✅ WhatsApp Queue - View full message content in modal
2. ✅ WhatsApp Queue - Remaining time display with color-coded badges
3. ✅ WhatsApp Queue - Copy to clipboard functionality
4. ✅ WhatsApp Queue - Total Pesan statistics card
5. ✅ Email History - Complete stats overview (5 cards)
6. ✅ Admin/Panitia Pendaftar - EMIS completeness filter
7. ✅ Admin/Panitia Pendaftar - Reorganized search field layout
8. ✅ Fixed pagination error in both Admin and Panitia controllers

### Database Changes:
- ✅ New table: `message_queue` with proper foreign key constraint

---

## ⚠️ IMPORTANT NOTES

1. **Backup First:** Selalu backup database sebelum menjalankan migration
2. **Test Locally:** Semua perubahan sudah ditest di local environment
3. **Cache Clear:** Jangan lupa clear all caches setelah upload
4. **Permission:** Pastikan file permissions correct (644 untuk files)
5. **Pagination Fix:** Ini adalah bug fix penting - harus diupload!

---

## 📝 CHECKLIST UPLOAD

- [ ] Backup production database
- [ ] Upload 1 model file
- [ ] Upload 3 controller files
- [ ] Upload 4 view files
- [ ] Upload 1 migration file
- [ ] Set correct file permissions
- [ ] Run migration in production
- [ ] Clear all caches
- [ ] Test WhatsApp History features
- [ ] Test Email History stats
- [ ] Test Admin Pendaftar filter EMIS
- [ ] Test Panitia Pendaftar filter EMIS
- [ ] Verify pagination works correctly
- [ ] Test on different browsers

---

**Generated:** 3 February 2026
**Ready for Production Deployment:** YES ✅
**Estimated Upload Time:** 15-20 minutes
**Risk Level:** LOW (all features tested locally)

---

## 🎉 HASIL AKHIR

Setelah upload berhasil, sistem akan memiliki:

1. **Tab History WhatsApp yang lebih informatif** dengan:
   - Kolom "Isi Pesan" dengan viewer modal
   - Kolom "Waktu Tersisa" dengan countdown
   - 5 kartu statistik lengkap

2. **Tab History Email yang lebih lengkap** dengan:
   - 5 kartu statistik overview

3. **Filter yang lebih powerful** di halaman Admin/Panitia Pendaftar:
   - Filter berdasarkan persentase kelengkapan EMIS
   - Layout search yang lebih baik

4. **Bug fixes** untuk pagination error

Semua siap untuk production! 🚀
