# Upload Manual ke Production - 8 Februari 2026
## Rangkuman Lengkap Semua Perbaikan

> ⚠️ **PENTING:** Ikuti urutan ini dengan hati-hati. Backup dulu sebelum upload!

---

## 📋 Ringkasan Perubahan

Dari session hari ini (8 Februari 2026), kita telah melakukan **5 perbaikan major**:

1. ✅ Fix undefined variable `$paketMadrasah`
2. ✅ Fix Step 2 styling Madrasah (matching dengan Step 1)
3. ✅ Fix footer layout broken (orphaned divs)
4. ✅ Fix Opsi Lainnya setting key mismatch
5. ✅ **FITUR BARU:** Opsi Lainnya untuk Dropdown Paket (Madrasah & Seragam)

---

## 📦 File yang Harus Diupload

### Total: **3 Files**

1. `app/Livewire/Admin/DaftarUlang.php`
2. `app/Livewire/Pendaftar/DaftarUlang.php`
3. `resources/views/livewire/pendaftar/daftar-ulang.blade.php`
4. `resources/views/livewire/admin/daftar-ulang.blade.php`

---

## 🔍 Detail Perubahan Per File

### 1. File: `app/Livewire/Admin/DaftarUlang.php`

**Lokasi Production:**
```
/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/DaftarUlang.php
```

**Perubahan:**
- **Line 86:** Added `public $madrasahPackagesEnableOpsiLainnya = false;`
- **Line 96:** Added `public $seragamPackagesEnableOpsiLainnya = false;`
- **Line 166:** Added loading setting `madrasahPackagesEnableOpsiLainnya`
- **Line 176:** Added loading setting `seragamPackagesEnableOpsiLainnya`
- **Line 635:** Added save setting `madrasahPackagesEnableOpsiLainnya`
- **Line 702:** Added save setting `seragamPackagesEnableOpsiLainnya`

**Purpose:**
- Menambahkan toggle switch untuk "Opsi Lainnya" dropdown paket di admin settings

---

### 2. File: `app/Livewire/Pendaftar/DaftarUlang.php`

**Lokasi Production:**
```
/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/DaftarUlang.php
```

**Perubahan:**
- **Line 70-73:** Added properties untuk Madrasah:
  ```php
  public $paketMadrasah;  // Already added in previous fix
  public $opsiMadrasah;   // Already added in previous fix
  public $paketMadrasahCustomName;  // NEW
  public $paketMadrasahCustomAmount;  // NEW
  ```

- **Line 84-87:** Added properties untuk Seragam:
  ```php
  public $paketSeragam;  // Already exists
  public $opsiSeragam;   // Already exists
  public $paketSeragamCustomName;  // NEW
  public $paketSeragamCustomAmount;  // NEW
  ```

**Purpose:**
- Support custom package input untuk "Paket Lainnya"
- Fix undefined variable error

---

### 3. File: `resources/views/livewire/pendaftar/daftar-ulang.blade.php`

**Lokasi Production:**
```
/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/daftar-ulang.blade.php
```

**Perubahan Major:**

#### A. Madrasah Tab - Line 754-798

**Before:**
```blade
@php
    $madrasahPackages = \App\Models\Setting::get('daftar_ulang_madrasah_packages', []);
@endphp

<select wire:model.live="paketMadrasah" ...>
    <option value="">-- Pilih Paket Madrasah --</option>
    @foreach($madrasahPackages as $package)
        <option value="{{ json_encode($package) }}">...</option>
    @endforeach
</select>
```

**After:**
```blade
@php
    $madrasahPackages = \App\Models\Setting::get('daftar_ulang_madrasah_packages', []);
    $madrasahPackagesEnableOpsiLainnya = \App\Models\Setting::get('daftar_ulang_madrasah_packages_enable_opsi_lainnya', false);
@endphp

<select wire:model.live="paketMadrasah" ...>
    <option value="">-- Pilih Paket Madrasah --</option>
    @foreach($madrasahPackages as $package)
        <option value="{{ json_encode($package) }}">...</option>
    @endforeach
    @if($madrasahPackagesEnableOpsiLainnya)
        <option value="paket_lainnya">Paket Lainnya (Input Manual)</option>
    @endif
</select>

{{-- Manual Input Paket Lainnya --}}
@if($paketMadrasah === 'paket_lainnya')
    <div class="mb-4 ml-6 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Nama Paket Custom <span class="text-red-500">*</span>
        </label>
        <input type="text"
               wire:model.live="paketMadrasahCustomName"
               placeholder="Contoh: Paket Khusus Beasiswa"
               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
        <p class="text-xs text-gray-600 mt-1">Masukkan nama paket yang tidak tersedia di daftar</p>
        @error('paketMadrasahCustomName')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
@endif
```

#### B. Madrasah Tab - Line 801

**Before:**
```blade
@if($paketMadrasah)
```

**After:**
```blade
@if($paketMadrasah && $paketMadrasah !== 'paket_lainnya')
```

**Purpose:** Skip "Pilih Opsi" section jika paket custom dipilih

#### C. Madrasah Tab - Line 820 (FIX CRITICAL!)

**Before:**
```blade
$madrasahEnableOpsiLainnyaPayment = \App\Models\Setting::get('daftar_ulang_madrasah_payment_enable_opsi_lainnya', false);
```

**After:**
```blade
$madrasahEnableOpsiLainnyaPayment = \App\Models\Setting::get('daftar_ulang_madrasah_enable_opsi_lainnya', false);
```

**Purpose:** Fix setting key mismatch (Opsi Lainnya untuk konfirmasi nominal)

#### D. Madrasah Tab - Line 837-852

**Added:**
```blade
{{-- Manual Input Nominal untuk Paket Lainnya --}}
@if($paketMadrasah === 'paket_lainnya')
    <div class="mb-4 ml-6 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Nominal Biaya (Rp) <span class="text-red-500">*</span>
        </label>
        <input type="number"
               wire:model.live="paketMadrasahCustomAmount"
               placeholder="Contoh: 500000"
               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
        <p class="text-xs text-gray-600 mt-1">Masukkan nominal biaya untuk paket custom</p>
        @error('paketMadrasahCustomAmount')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
@endif
```

#### E. Madrasah Tab - Line 855

**Before:**
```blade
@if($opsiMadrasah)
```

**After:**
```blade
@if($opsiMadrasah || ($paketMadrasah === 'paket_lainnya' && $paketMadrasahCustomAmount))
```

**Purpose:** Show konfirmasi nominal untuk paket custom juga

#### F. Madrasah Tab - Line 880-882 (FIX CRITICAL!)

**Removed orphaned divs:**
```blade
<!-- REMOVED:
</div>
<!-- End of bg-emerald-50 container for form fields -->
</div>
<!-- End of disabled wrapper for form fields -->
-->

<!-- REPLACED WITH: -->
</div>
{{-- End of Step 1 container --}}
```

**Purpose:** Fix footer broken layout

#### G. Madrasah Tab - Line 884-891 (Step 2 Styling)

**Before:**
```blade
<!-- Upload Bukti Pembayaran -->
<div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
```

**After:**
```blade
<!-- SECTION 2: INFORMASI PEMBAYARAN & UPLOAD BUKTI -->
<div class="bg-gradient-to-br from-emerald-50 to-teal-50 border-2 border-emerald-200 rounded-lg p-6 mb-6">
    <h4 class="text-lg font-bold text-emerald-900 mb-4 flex items-center">
        <svg class="w-6 h-6 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
        </svg>
        Step 2: Informasi Pembayaran & Upload Bukti
    </h4>
```

**Purpose:** Matching styling dengan Step 1

#### H. Seragam Tab - Line 1615-1677

**Same changes as Madrasah** (Opsi Lainnya dropdown + manual input)

---

### 4. File: `resources/views/livewire/admin/daftar-ulang.blade.php`

**Lokasi Production:**
```
/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/daftar-ulang.blade.php
```

**Perubahan:**

#### A. Madrasah Settings - Line 410-423

**Added toggle switch BEFORE existing "Opsi Lainnya" (konfirmasi nominal):**
```blade
<!-- Checkbox: Aktifkan Opsi Lainnya untuk Dropdown Paket -->
<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
    <label class="flex items-center">
        <input type="checkbox"
               wire:model="madrasahPackagesEnableOpsiLainnya"
               class="rounded border-gray-300 text-blue-600">
        <span class="ml-2 text-sm font-semibold text-blue-900">
            Aktifkan "Opsi Lainnya" untuk Dropdown Paket Madrasah
        </span>
    </label>
    <p class="text-xs text-blue-700 mt-2 ml-6">
        Jika diaktifkan, pendaftar dapat memilih "Paket Lainnya" di dropdown dan mengisi nama paket secara manual (untuk paket yang tidak ada di daftar).
    </p>
</div>
```

#### B. Seragam Settings - Line 638-651

**Added toggle switch BEFORE existing "Opsi Lainnya" (konfirmasi nominal):**
```blade
<!-- Checkbox: Aktifkan Opsi Lainnya untuk Dropdown Paket -->
<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
    <label class="flex items-center">
        <input type="checkbox"
               wire:model="seragamPackagesEnableOpsiLainnya"
               class="rounded border-gray-300 text-blue-600">
        <span class="ml-2 text-sm font-semibold text-blue-900">
            Aktifkan "Opsi Lainnya" untuk Dropdown Paket Seragam
        </span>
    </label>
    <p class="text-xs text-blue-700 mt-2 ml-6">
        Jika diaktifkan, pendaftar dapat memilih "Paket Lainnya" di dropdown dan mengisi nama paket secara manual (untuk paket yang tidak ada di daftar).
    </p>
</div>
```

---

## 🚀 Langkah Upload Manual ke Production

### Step 1: Backup File Production (WAJIB!)

```bash
ssh mtsnkot4@103.30.244.77

cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Backup semua file yang akan dimodifikasi
cp app/Livewire/Admin/DaftarUlang.php app/Livewire/Admin/DaftarUlang.php.backup-8feb2026
cp app/Livewire/Pendaftar/DaftarUlang.php app/Livewire/Pendaftar/DaftarUlang.php.backup-8feb2026
cp resources/views/livewire/pendaftar/daftar-ulang.blade.php resources/views/livewire/pendaftar/daftar-ulang.blade.php.backup-8feb2026
cp resources/views/livewire/admin/daftar-ulang.blade.php resources/views/livewire/admin/daftar-ulang.blade.php.backup-8feb2026

# Verify backup
ls -lah app/Livewire/Admin/DaftarUlang.php*
ls -lah app/Livewire/Pendaftar/DaftarUlang.php*
ls -lah resources/views/livewire/pendaftar/daftar-ulang.blade.php*
ls -lah resources/views/livewire/admin/daftar-ulang.blade.php*
```

### Step 2: Upload File ke Production

**Dari local (Windows), jalankan satu per satu:**

```batch
REM Upload file 1
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Admin\DaftarUlang.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/

REM Upload file 2
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Pendaftar\DaftarUlang.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/

REM Upload file 3
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\pendaftar\daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/

REM Upload file 4
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
```

**Verify upload berhasil:**
```bash
ssh mtsnkot4@103.30.244.77

cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Check file size dan timestamp
ls -lah app/Livewire/Admin/DaftarUlang.php
ls -lah app/Livewire/Pendaftar/DaftarUlang.php
ls -lah resources/views/livewire/pendaftar/daftar-ulang.blade.php
ls -lah resources/views/livewire/admin/daftar-ulang.blade.php
```

### Step 3: Clear Cache di Production (PENTING!)

```bash
ssh mtsnkot4@103.30.244.77

cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Clear semua cache
php artisan view:clear
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
```

**Expected output:**
```
✓ Compiled views cleared successfully.
✓ Clearing cached bootstrap files.
✓ Config cache cleared
✓ Route cache cleared
```

### Step 4: Set Permissions (Jika Perlu)

```bash
ssh mtsnkot4@103.30.244.77

cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Set correct permissions
chmod 644 app/Livewire/Admin/DaftarUlang.php
chmod 644 app/Livewire/Pendaftar/DaftarUlang.php
chmod 644 resources/views/livewire/pendaftar/daftar-ulang.blade.php
chmod 644 resources/views/livewire/admin/daftar-ulang.blade.php
```

---

## 🧪 Testing Checklist (Urutan Wajib)

### Test 1: Admin Settings

**URL:** `https://pmbm.mtsn2kotamalang.sch.id/admin/daftar-ulang?activeTab=settings`

**Madrasah Settings:**
- [ ] Hard refresh: `Ctrl + Shift + R`
- [ ] Scroll ke bagian "Paket Daftar Ulang Madrasah"
- [ ] **VERIFY:** Ada toggle BIRU baru "Aktifkan 'Opsi Lainnya' untuk Dropdown Paket Madrasah"
- [ ] **VERIFY:** Ada toggle KUNING existing "Aktifkan 'Opsi Lainnya' untuk Konfirmasi Nominal"
- [ ] Checklist toggle biru ON
- [ ] Klik "Simpan Pengaturan Madrasah"
- [ ] **VERIFY:** Muncul notifikasi "Pengaturan daftar ulang madrasah berhasil disimpan"

**Seragam Settings:**
- [ ] Scroll ke bagian "Paket Daftar Ulang Seragam"
- [ ] **VERIFY:** Ada toggle BIRU baru "Aktifkan 'Opsi Lainnya' untuk Dropdown Paket Seragam"
- [ ] **VERIFY:** Ada toggle KUNING existing "Aktifkan 'Opsi Lainnya' untuk Konfirmasi Nominal"
- [ ] Checklist toggle biru ON
- [ ] Klik "Simpan Pengaturan Seragam"
- [ ] **VERIFY:** Muncul notifikasi "Pengaturan daftar ulang seragam berhasil disimpan"

### Test 2: Pendaftar - Madrasah Tab

**URL:** `https://pmbm.mtsn2kotamalang.sch.id/pendaftar/daftarulang?activeTab=madrasah`

**Visual Verification:**
- [ ] Hard refresh: `Ctrl + Shift + R`
- [ ] **VERIFY:** No error 500
- [ ] **VERIFY:** No undefined variable error
- [ ] **VERIFY:** Step 1 has gradient background (emerald to teal)
- [ ] **VERIFY:** Step 2 has gradient background (same style as Step 1)
- [ ] **VERIFY:** Both headers bold with icons
- [ ] **VERIFY:** Footer displays correctly (no layout break)

**Dropdown Paket Madrasah:**
- [ ] **VERIFY:** Dropdown shows paket dari daftar
- [ ] **VERIFY:** Dropdown shows "Paket Lainnya (Input Manual)" sebagai opsi terakhir
- [ ] Click dropdown, select "Paket Lainnya (Input Manual)"
- [ ] **VERIFY:** Blue box muncul dengan 2 input:
  - Nama Paket Custom *
  - Nominal Biaya (Rp) *
- [ ] Input nama: "Paket Khusus Testing"
- [ ] Input nominal: 500000
- [ ] **VERIFY:** Section "Pilih Opsi dari Paket" TIDAK muncul
- [ ] **VERIFY:** Bisa scroll ke "Step 2: Informasi Pembayaran"

**Switch Paket:**
- [ ] Select paket normal dari dropdown (bukan Paket Lainnya)
- [ ] **VERIFY:** Blue box manual input HILANG
- [ ] **VERIFY:** Section "Pilih Opsi dari Paket" MUNCUL
- [ ] Select "Paket Lainnya" lagi
- [ ] **VERIFY:** Blue box manual input MUNCUL lagi

**Opsi Lainnya Konfirmasi Nominal (existing feature):**
- [ ] Select paket normal dan pilih salah satu opsi
- [ ] Scroll ke "Konfirmasi Nominal yang Dibayarkan"
- [ ] **VERIFY:** Ada 2 pilihan:
  - Bayar Penuh (GREEN)
  - Opsi Lainnya (YELLOW) ← Should appear now!
- [ ] Click "Opsi Lainnya"
- [ ] **VERIFY:** Manual input field muncul
- [ ] Input nominal: 250000
- [ ] **VERIFY:** Accepted

### Test 3: Pendaftar - Seragam Tab

**URL:** `https://pmbm.mtsn2kotamalang.sch.id/pendaftar/daftarulang?activeTab=seragam`

**Same tests as Madrasah:**
- [ ] Visual verification (gradient backgrounds, headers, footer)
- [ ] Dropdown shows "Paket Lainnya (Input Manual)"
- [ ] Blue box manual input works
- [ ] Can input custom name and amount
- [ ] Section "Pilih Opsi" skipped for custom package
- [ ] Can switch between normal and custom package

### Test 4: Edge Cases

**Browser Compatibility:**
- [ ] Test on Chrome (latest)
- [ ] Test on Firefox (latest)
- [ ] Test on Mobile browser

**Data Persistence:**
- [ ] Fill form partially
- [ ] Refresh browser (F5)
- [ ] **VERIFY:** Livewire data persists

**Validation:**
- [ ] Select "Paket Lainnya" without filling inputs
- [ ] Try to submit
- [ ] **VERIFY:** Validation errors appear

---

## ⚠️ Troubleshooting

### Issue 1: Error 500 setelah upload

**Solution:**
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Check error log
tail -100 storage/logs/laravel.log
```

**Common fixes:**
```bash
# Clear all caches
php artisan optimize:clear

# Fix permissions
chmod -R 755 storage bootstrap/cache
chown -R mtsnkot4:mtsnkot4 storage bootstrap/cache
```

### Issue 2: Opsi Lainnya tidak muncul

**Check setting:**
```bash
php artisan tinker --execute="echo \App\Models\Setting::whereIn('key', ['daftar_ulang_madrasah_packages_enable_opsi_lainnya', 'daftar_ulang_seragam_packages_enable_opsi_lainnya'])->get(['key', 'value'])->toJson(JSON_PRETTY_PRINT);"
```

**Enable manually:**
```bash
php artisan tinker --execute="\App\Models\Setting::updateOrCreate(['key' => 'daftar_ulang_madrasah_packages_enable_opsi_lainnya'], ['value' => '1']);"

php artisan tinker --execute="\App\Models\Setting::updateOrCreate(['key' => 'daftar_ulang_seragam_packages_enable_opsi_lainnya'], ['value' => '1']);"
```

### Issue 3: Styling tidak berubah

**Clear browser cache:**
- `Ctrl + Shift + Delete` (Chrome/Firefox)
- Hard refresh: `Ctrl + Shift + R`

**Clear server cache:**
```bash
php artisan view:clear
php artisan config:clear
```

### Issue 4: Footer masih broken

**Verify file upload:**
```bash
# Check if orphaned divs removed at line 880-882
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
grep -n "End of bg-emerald-50 container" resources/views/livewire/pendaftar/daftar-ulang.blade.php
```

**Should return:** NOTHING (line should be removed)

---

## 📊 Summary Checklist

### Pre-Upload
- [ ] Backup all 4 files on production
- [ ] Verify local files are latest version
- [ ] Close any editor that might lock files

### Upload
- [ ] Upload `app/Livewire/Admin/DaftarUlang.php`
- [ ] Upload `app/Livewire/Pendaftar/DaftarUlang.php`
- [ ] Upload `resources/views/livewire/pendaftar/daftar-ulang.blade.php`
- [ ] Upload `resources/views/livewire/admin/daftar-ulang.blade.php`
- [ ] Verify all uploads successful

### Post-Upload
- [ ] Clear view cache
- [ ] Clear optimize cache
- [ ] Clear config cache
- [ ] Clear route cache
- [ ] Verify permissions

### Testing
- [ ] Test admin settings (both toggles)
- [ ] Test pendaftar madrasah (all scenarios)
- [ ] Test pendaftar seragam (all scenarios)
- [ ] Test edge cases
- [ ] Verify no errors in log

---

## 📝 Catatan Penting

### Fitur yang Akan Aktif:

1. **Fix Bugs:**
   - ✅ No more undefined variable `$paketMadrasah`
   - ✅ Step 2 styling matches Step 1 (gradient, header, icon)
   - ✅ Footer layout fixed (no more broken)
   - ✅ Opsi Lainnya konfirmasi nominal works (setting key fixed)

2. **Fitur Baru:**
   - ✅ Admin can enable "Opsi Lainnya" untuk dropdown paket (Madrasah)
   - ✅ Admin can enable "Opsi Lainnya" untuk dropdown paket (Seragam)
   - ✅ Pendaftar can select "Paket Lainnya" dan input manual
   - ✅ Progressive disclosure: manual input only shown when selected

### Setting Keys Baru:

```
daftar_ulang_madrasah_packages_enable_opsi_lainnya (boolean)
daftar_ulang_seragam_packages_enable_opsi_lainnya (boolean)
```

### Database Impact:

- **No migration needed** (settings stored in existing `settings` table)
- **No schema changes**

---

## 🎯 Quick Reference Card

```
┌─────────────────────────────────────────────────────┐
│ UPLOAD SEQUENCE (DO NOT SKIP!)                      │
├─────────────────────────────────────────────────────┤
│ 1. Backup production files                          │
│ 2. Upload 4 files via SCP                          │
│ 3. Clear all caches                                 │
│ 4. Test admin settings                              │
│ 5. Test pendaftar view                              │
│ 6. Enable toggles if needed                         │
│ 7. Final verification                               │
└─────────────────────────────────────────────────────┘

FILES TO UPLOAD:
├── app/Livewire/Admin/DaftarUlang.php
├── app/Livewire/Pendaftar/DaftarUlang.php
├── resources/views/livewire/pendaftar/daftar-ulang.blade.php
└── resources/views/livewire/admin/daftar-ulang.blade.php

CACHE COMMANDS:
php artisan view:clear
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
```

---

## ✅ Verification Success Criteria

Upload dianggap **BERHASIL** jika:

1. ✅ No error 500 di pendaftar view
2. ✅ No undefined variable error
3. ✅ Step 1 dan Step 2 memiliki gradient background yang sama
4. ✅ Footer displays correctly (tidak broken)
5. ✅ Admin settings shows 2 toggles (blue + yellow) untuk Madrasah
6. ✅ Admin settings shows 2 toggles (blue + yellow) untuk Seragam
7. ✅ Pendaftar dropdown shows "Paket Lainnya" when enabled
8. ✅ Manual input fields work correctly
9. ✅ Can submit form dengan paket custom
10. ✅ Existing "Opsi Lainnya" konfirmasi nominal works

---

**Status:** ✅ Ready for Production Upload
**Date:** 8 Februari 2026
**Total Files:** 4 files
**Estimated Time:** 15-20 minutes (including testing)

---

**Good luck with the upload! Ikuti step by step dengan hati-hati! 🚀**
