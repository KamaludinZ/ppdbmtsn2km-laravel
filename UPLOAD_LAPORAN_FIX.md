# Quick Upload Guide - Laporan Fix

## 📋 Overview
Fix untuk export errors dan UI issues di `/admin/laporan` dan `/panitia/laporan`.

---

## 📁 Files to Upload

### Backend (4 files):
```
1. app/Livewire/Admin/Laporan.php
2. app/Livewire/Panitia/Laporan.php
3. app/Exports/PendaftarExport.php
4. app/Exports/SeragamExport.php
```

### Frontend (2 files):
```
5. resources/views/livewire/admin/laporan.blade.php
6. resources/views/livewire/panitia/laporan.blade.php
```

---

## 🚀 Upload Steps

### Step 1: Upload via SFTP/FTP

**Source (Local):**
```
C:\ppdbmtsn2km-laravel\app\Livewire\Admin\Laporan.php
C:\ppdbmtsn2km-laravel\app\Livewire\Panitia\Laporan.php
C:\ppdbmtsn2km-laravel\app\Exports\PendaftarExport.php
C:\ppdbmtsn2km-laravel\app\Exports\SeragamExport.php
C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\laporan.blade.php
C:\ppdbmtsn2km-laravel\resources\views\livewire\panitia\laporan.blade.php
```

**Destination (Server):**
```
/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/Laporan.php
/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/Laporan.php
/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/PendaftarExport.php
/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/SeragamExport.php
/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/laporan.blade.php
/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/laporan.blade.php
```

### Step 2: Clear Cache

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Clear all caches
php artisan view:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear

echo '✅ Laporan fixes uploaded & cache cleared'
```

### Step 3: Test

1. **Test Export Functions:**
   - Navigate to `/admin/laporan`
   - Tab "Data Pendaftar Lengkap" → Click "Export Data Lengkap"
   - Should download Excel without errors ✅
   - Tab "Seragam & Ukuran" → Click "Export Seragam"
   - Should download Excel with all size data ✅

2. **Test UI:**
   - Check Stats Overview shows 6 cards (not 5)
   - Verify gradient backgrounds are visible
   - Check spacing between Stats and Statistik Per Jalur
   - Verify percentage bars show colors

3. **Test Panitia:**
   - Login as panitia
   - Navigate to panitia laporan
   - Verify same fixes apply

---

## ✅ What Was Fixed

### Export Errors:
1. ✅ Fixed `pendidikanLain` relationship error → `pendidikanLains`
2. ✅ Fixed SeragamExport column errors (ukuran_atasan, ukuran_bawahan, etc.)
3. ✅ Added 13 new columns to SeragamExport (tinggi, berat, sepatu, dll.)

### UI Improvements:
1. ✅ Added spacing (mt-6) between Stats and Statistik Per Jalur
2. ✅ Stats Overview now shows 6 cards with correct status values
3. ✅ Added gradient backgrounds to stat cards
4. ✅ Fixed percentage column colors (dynamic Tailwind → inline styles)
5. ✅ Better responsive layout (2/3/6 columns)

---

## 📊 Expected Result

### Before:
- ❌ Export Data Lengkap → Error: "undefined relationship pendidikanLain"
- ❌ Export Seragam → Error: "column ukuran_atasan does not exist"
- ❌ Stats showing wrong values (data_disetujui labeled as Lulus)
- ❌ No spacing between sections
- ❌ Percentage bars without colors

### After:
- ✅ Export Data Lengkap → Works perfectly
- ✅ Export Seragam → Works with comprehensive data
- ✅ Stats showing correct values (6 cards with proper labels)
- ✅ Good spacing between sections
- ✅ Percentage bars with beautiful colors and animations

---

## 🆘 If Issues Occur

### Export still failing:
```bash
# Check if files were uploaded correctly
ls -la app/Exports/PendaftarExport.php
ls -la app/Exports/SeragamExport.php

# Clear cache again
php artisan view:clear
php artisan cache:clear
```

### Colors not showing:
```bash
# Clear view cache
php artisan view:clear

# Clear browser cache (Ctrl+Shift+Delete)
```

### Stats not updating:
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
```

---

**Date:** 21 Januari 2026
**Status:** ✅ READY TO UPLOAD
**Risk:** Low
**Test Time:** ~5-10 menit
**Impact:** High (Fixes production errors + Better UX)

---

For detailed documentation, see: `FIX_LAPORAN_COMPLETE.md`
