# Upload Guide - Complete Dashboard & EMIS Fixes

## 📋 Overview

Panduan upload untuk semua perbaikan dashboard admin & panitia, termasuk simplifikasi EMIS completeness sections dan fix relationship methods.

---

## ✅ Daftar Perbaikan

### 1. **Dashboard Admin & Panitia Fixes** ✅
- Fix "Pendaftar Terbaru" section (jalur & status columns)
- Fix "Statistik Jalur" (hanya jalur aktif)
- Fix "Stats Overview" (sesuai enum status_pendaftaran)
- Eager loading untuk performa

### 2. **EMIS Completeness Simplification** ✅
- Simplified sections dari 10+ menjadi 6 sections
- Sections sesuai dengan tab formulir pendaftaran
- Better UI with icons, colors, and layout
- Conditional logic yang lebih sederhana

### 3. **EMIS Relationship Methods** ✅
- Added alias methods: `prestasi()`, `beasiswa()`, `pendidikan_lain()`
- Fixed error: "Call to undefined method"
- Backward compatible dengan original methods

---

## 📁 File yang Perlu Diupload

### Backend Files:
```
1. app/Livewire/Admin/Dashboard.php
2. app/Livewire/Panitia/Dashboard.php
3. app/Models/Pendaftar.php
```

### Frontend Files:
```
4. resources/views/livewire/admin/dashboard.blade.php
5. resources/views/livewire/panitia/dashboard.blade.php (jika ada perubahan)
```

---

## 🚀 Langkah-Langkah Upload

### Step 1: Backup Files Lama

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Create backup directory
mkdir -p backups/dashboard-fixes-2026-01-21

# Backup files
cp app/Livewire/Admin/Dashboard.php backups/dashboard-fixes-2026-01-21/
cp app/Livewire/Panitia/Dashboard.php backups/dashboard-fixes-2026-01-21/
cp app/Models/Pendaftar.php backups/dashboard-fixes-2026-01-21/
cp resources/views/livewire/admin/dashboard.blade.php backups/dashboard-fixes-2026-01-21/

echo '✅ Backup completed'
```

### Step 2: Upload Files

Upload semua file yang sudah diubah ke server menggunakan FTP/SFTP:

**Source (Local):**
```
C:\ppdbmtsn2km-laravel\app\Livewire\Admin\Dashboard.php
C:\ppdbmtsn2km-laravel\app\Livewire\Panitia\Dashboard.php
C:\ppdbmtsn2km-laravel\app\Models\Pendaftar.php
C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\dashboard.blade.php
```

**Destination (Server):**
```
/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/Dashboard.php
/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/Dashboard.php
/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/Pendaftar.php
/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/dashboard.blade.php
```

### Step 3: Clear Cache

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Clear all caches
php artisan view:clear
php artisan cache:clear
php artisan config:clear

echo '✅ All caches cleared'
```

### Step 4: Verify File Permissions

```bash
# Set proper permissions
chmod 644 app/Livewire/Admin/Dashboard.php
chmod 644 app/Livewire/Panitia/Dashboard.php
chmod 644 app/Models/Pendaftar.php
chmod 644 resources/views/livewire/admin/dashboard.blade.php

echo '✅ File permissions set'
```

---

## 🧪 Testing Checklist

### Test Dashboard Admin:

1. **Login & Access**
   - [ ] Login sebagai admin
   - [ ] Access /admin/dashboard
   - [ ] Verifikasi halaman load tanpa error

2. **Stats Overview**
   - [ ] Total Pendaftar tampil dengan benar
   - [ ] Menunggu Verifikasi = menunggu + lengkapi + perbaiki
   - [ ] Diterima = data_disetujui + lulus
   - [ ] Panitia Aktif tampil dengan benar

3. **Pendaftar Terbaru**
   - [ ] 5 pendaftar terbaru tampil
   - [ ] Nomor Pendaftaran tampil
   - [ ] Nama & NISN tampil
   - [ ] Jalur tampil dengan nama dan warna yang sesuai
   - [ ] Status tampil dengan label dan warna yang tepat
   - [ ] Tanggal tampil dengan format dd M YYYY

4. **Statistik Jalur**
   - [ ] Hanya jalur aktif yang tampil
   - [ ] Diurutkan sesuai kolom urutan
   - [ ] Jumlah pendaftar per jalur akurat
   - [ ] Persentase dihitung dengan benar
   - [ ] Progress bar tampil dengan warna jalur

5. **Kelengkapan EMIS**
   - [ ] Rata-rata kelengkapan tampil
   - [ ] 4 kategori (Lengkap ≥80%, Sebagian 50-79%, Kurang Lengkap 1-49%, Belum Diisi 0%) akurat
   - [ ] **Kelengkapan Per Seksi Data:**
     - [ ] 6 sections tampil (bukan 10+)
     - [ ] Data Siswa
     - [ ] Data Orang Tua & Wali
     - [ ] Data Alamat
     - [ ] Prestasi Siswa (cek dari relasi)
     - [ ] Beasiswa & Bantuan (cek dari relasi)
     - [ ] Pendidikan Lain (cek dari relasi)
   - [ ] Icon tampil untuk setiap section
   - [ ] Warna sesuai persentase
   - [ ] Layout 2 kolom

### Test Dashboard Panitia:

1. **Panitia Pendaftaran**
   - [ ] Login sebagai panitia_pendaftaran
   - [ ] Access /panitia/dashboard
   - [ ] Test semua section seperti dashboard admin

2. **Panitia Daftar Ulang**
   - [ ] Login sebagai panitia_daftarulang
   - [ ] Access /panitia/dashboard
   - [ ] Test semua section seperti dashboard admin

### Test EMIS dengan Data Real:

1. **Pendaftar dengan data lengkap**
   - [ ] Data Siswa: ~90-100%
   - [ ] Data Orang Tua & Wali: ~90-100%
   - [ ] Data Alamat: ~90-100%
   - [ ] Prestasi: 100% (jika ada data prestasi)
   - [ ] Beasiswa: 100% (jika ada data beasiswa)
   - [ ] Pendidikan Lain: 100% (jika ada data)

2. **Pendaftar baru (minimal data)**
   - [ ] Data Siswa: ~30-40%
   - [ ] Data Orang Tua & Wali: ~20-30%
   - [ ] Data Alamat: ~20-30%
   - [ ] Prestasi: 0%
   - [ ] Beasiswa: 0%
   - [ ] Pendidikan Lain: 0%

3. **Pendaftar WNA**
   - [ ] Verifikasi NIK tidak dihitung
   - [ ] Verifikasi negara_asal & nomor_kitas dihitung

4. **Pendaftar dengan ibu sama dengan ayah**
   - [ ] Verifikasi alamat ibu tidak dihitung dalam kelengkapan

---

## 🔍 Troubleshooting

### Issue 1: Dashboard Error 500
**Cause:** Cache tidak ter-clear atau file permissions salah
**Fix:**
```bash
php artisan view:clear
php artisan cache:clear
php artisan config:clear
chmod 644 app/Livewire/Admin/Dashboard.php
```

### Issue 2: EMIS Section Masih 10+
**Cause:** View cache masih menyimpan template lama
**Fix:**
```bash
php artisan view:clear
rm -rf storage/framework/views/*.php
```

### Issue 3: Error "Call to undefined method prestasi()"
**Cause:** File Pendaftar.php belum terupload atau cache belum clear
**Fix:**
```bash
# Verify file uploaded
ls -la app/Models/Pendaftar.php

# Clear cache
php artisan cache:clear
php artisan config:clear
```

### Issue 4: Jalur Stats Tidak Akurat
**Cause:** Database belum ter-refresh atau query cache
**Fix:**
```bash
# Clear query cache
php artisan cache:clear

# Atau jika pakai MySQL query cache
mysql -u username -p -e "RESET QUERY CACHE;"
```

---

## 📊 Summary of Changes

### app/Livewire/Admin/Dashboard.php
- **Line 39-72**: Updated `loadStats()` - Aggregate status sesuai enum
- **Line 74-96**: Updated `loadRecentPendaftar()` - Eager loading jalur
- **Line 98-118**: Updated `loadJalurStats()` - Filter jalur aktif only
- **Line 231-247**: Updated `getStatusLabel()` - All status labels
- **Line 249-265**: Updated `getStatusColor()` - All status colors

### app/Livewire/Panitia/Dashboard.php
- **Identical changes** to Admin/Dashboard.php

### app/Models/Pendaftar.php
- **Line 337-343**: Added `jalur()` alias method
- **Line 393-399**: Added `prestasi()` alias method
- **Line 401-407**: Added `beasiswa()` alias method
- **Line 409-415**: Added `pendidikan_lain()` alias method
- **Line 538-690**: Simplified EMIS sections (6 sections)

### resources/views/livewire/admin/dashboard.blade.php
- **Line 290-353**: Improved EMIS section UI (icons, layout, colors)

---

## ✨ Expected Results

### Before:
- ❌ Pendaftar terbaru: Jalur & status tidak sesuai
- ❌ Statistik jalur: Menampilkan jalur non-aktif
- ❌ EMIS sections: 10+ sections, sulit dibaca
- ❌ Stats overview: Tidak sesuai enum status
- ❌ Error: "Call to undefined method prestasi()"

### After:
- ✅ Pendaftar terbaru: Jalur & status akurat dengan eager loading
- ✅ Statistik jalur: Hanya jalur aktif, diurutkan
- ✅ EMIS sections: 6 sections, sesuai formulir
- ✅ Stats overview: Sesuai enum status yang berlaku
- ✅ No errors: Semua relationship methods working

---

## 🎯 Status

```
✅ Dashboard Fixes: COMPLETE
✅ EMIS Simplification: COMPLETE
✅ Relationship Methods: COMPLETE
✅ Testing: READY
✅ Documentation: COMPLETE
```

**Overall Status:** ✅ **READY FOR PRODUCTION**

**Date:** 21 Januari 2026
**Files Changed:** 4 files
**Impact:** High (Core Dashboard & EMIS Functionality)
**Risk:** Low (No breaking changes, backward compatible)
**Estimated Test Time:** 15-20 menit

---

## 📚 Related Documentation

1. `FIX_DASHBOARD_ADMIN_PANITIA_COMPLETE.md` - Dashboard fixes detail
2. `FIX_EMIS_COMPLETENESS_SECTIONS.md` - EMIS simplification detail
3. `FIX_EMIS_RELATIONSHIP_METHODS.md` - Relationship methods fix
4. `DEPLOYMENT_CHECKLIST.md` - General deployment guide

---

## 💡 Important Notes

1. **Backup First**: Always backup before uploading
2. **Clear Cache**: Always clear cache after upload
3. **Test Thoroughly**: Test all sections before declaring success
4. **Monitor Errors**: Check `storage/logs/laravel.log` for any errors
5. **Rollback Ready**: Keep backup accessible for quick rollback if needed

---

## 🆘 Rollback Procedure

Jika terjadi masalah setelah upload:

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Restore from backup
cp backups/dashboard-fixes-2026-01-21/Dashboard.php app/Livewire/Admin/
cp backups/dashboard-fixes-2026-01-21/Dashboard.php app/Livewire/Panitia/
cp backups/dashboard-fixes-2026-01-21/Pendaftar.php app/Models/
cp backups/dashboard-fixes-2026-01-21/dashboard.blade.php resources/views/livewire/admin/

# Clear cache
php artisan view:clear
php artisan cache:clear
php artisan config:clear

echo '✅ Rollback completed'
```

---

**End of Upload Guide**
