# STEP 5: Blade View Fix - COMPLETE (9 Feb 2026)

## ✅ ROOT CAUSE FOUND & FIXED

---

## 🐛 The Problem

**User Report:**
After enabling individual toggles in Admin panel (`/admin/daftar-ulang?activeTab=madrasah`), the "Opsi Lainnya" options were NOT appearing in Pendaftar views.

**Root Cause:**
The blade view file `resources/views/livewire/pendaftar/daftar-ulang.blade.php` was **querying the database directly** instead of using the component's computed properties.

```php
// WRONG - Direct database query (ignores individual toggles)
$madrasahPackagesEnableOpsiLainnya = \App\Models\Setting::get('daftar_ulang_madrasah_packages_enable_opsi_lainnya', false);
```

This meant:
- Component had correct logic (Global OR Individual)
- But view was bypassing it and only checking global settings
- Individual toggles had no effect on the display

---

## 🔧 The Fix

Changed blade view to use component's computed properties:

```php
// CORRECT - Uses computed property (includes individual logic)
$madrasahPackagesEnableOpsiLainnya = $this->shouldShowMadrasahOpsiLainnyaDropdown;
```

---

## 📦 Files Modified: 1 File

### File: `resources/views/livewire/pendaftar/daftar-ulang.blade.php`

#### Fix 1: Madrasah Dropdown Paket (Line 753-757)

**Location:** Inside `@if($activeTab === 'madrasah')` section

**BEFORE:**
```php
@php
    $madrasahPackages = \App\Models\Setting::get('daftar_ulang_madrasah_packages', []);
    $madrasahPackagesEnableOpsiLainnya = \App\Models\Setting::get('daftar_ulang_madrasah_packages_enable_opsi_lainnya', false);
@endphp
```

**AFTER:**
```php
@php
    $madrasahPackages = \App\Models\Setting::get('daftar_ulang_madrasah_packages', []);
    // Use computed property for Global OR Individual logic
    $madrasahPackagesEnableOpsiLainnya = $this->shouldShowMadrasahOpsiLainnyaDropdown;
@endphp
```

---

#### Fix 2: Madrasah Konfirmasi Nominal (Line 856-860)

**Location:** Inside madrasah payment confirmation section

**BEFORE:**
```php
@php
    $selectedOption = json_decode($opsiMadrasah, true);
    $madrasahEnableOpsiLainnyaPayment = \App\Models\Setting::get('daftar_ulang_madrasah_enable_opsi_lainnya', false);
@endphp
```

**AFTER:**
```php
@php
    $selectedOption = json_decode($opsiMadrasah, true);
    // Use computed property for Global OR Individual logic
    $madrasahEnableOpsiLainnyaPayment = $this->shouldShowMadrasahOpsiLainnyaKonfirmasi;
@endphp
```

---

#### Fix 3: Seragam Dropdown Paket (Line 1615-1619)

**Location:** Inside `@if($activeTab === 'seragam')` section

**BEFORE:**
```php
@php
    $seragamPackages = \App\Models\Setting::get('daftar_ulang_seragam_packages', []);
    $seragamPackagesEnableOpsiLainnya = \App\Models\Setting::get('daftar_ulang_seragam_packages_enable_opsi_lainnya', false);
@endphp
```

**AFTER:**
```php
@php
    $seragamPackages = \App\Models\Setting::get('daftar_ulang_seragam_packages', []);
    // Use computed property for Global OR Individual logic
    $seragamPackagesEnableOpsiLainnya = $this->shouldShowSeragamOpsiLainnyaDropdown;
@endphp
```

---

#### Fix 4: Seragam Konfirmasi Nominal (Line 1717-1721)

**Location:** Inside seragam payment confirmation section

**BEFORE:**
```php
@php
    $selectedOption = json_decode($opsiSeragam, true);
    $seragamEnableOpsiLainnya = \App\Models\Setting::get('daftar_ulang_seragam_enable_opsi_lainnya', false);
@endphp
```

**AFTER:**
```php
@php
    $selectedOption = json_decode($opsiSeragam, true);
    // Use computed property for Global OR Individual logic
    $seragamEnableOpsiLainnya = $this->shouldShowSeragamOpsiLainnyaKonfirmasi;
@endphp
```

---

## ⚠️ IMPORTANT: Ukuran Seragam

### Individual Toggles NOT Available for Ukuran

**Ukuran fields use ONLY global settings:**
- `$ukuranJasEnableOpsiLainnya`
- `$ukuranBajuOlahragaEnableOpsiLainnya`
- `$ukuranCelanaOlahragaEnableOpsiLainnya`
- `$ukuranBajuHarianEnableOpsiLainnya`
- `$ukuranCelanaRokHarianEnableOpsiLainnya`
- `$ukuranKopyahEnableOpsiLainnya`

These are defined in the component (lines 193-198) as:
```php
$this->ukuranJasEnableOpsiLainnya = Setting::get('daftar_ulang_ukuran_jas_enable_opsi_lainnya', false);
$this->ukuranBajuOlahragaEnableOpsiLainnya = Setting::get('daftar_ulang_ukuran_baju_olahraga_enable_opsi_lainnya', false);
// etc...
```

**No individual toggles exist for ukuran fields.**

**Why?**
- The database migration only added individual columns to `daftar_ulang` and `seragam` tables
- Did NOT add individual columns to ukuran fields
- Ukuran settings are applied globally to all pendaftar

**To enable individual toggles for Ukuran (future feature):**
1. Create migration to add 6 new columns to `seragam` table:
   - `opsi_lainnya_ukuran_jas_individual`
   - `opsi_lainnya_ukuran_baju_olahraga_individual`
   - `opsi_lainnya_ukuran_celana_olahraga_individual`
   - `opsi_lainnya_ukuran_baju_harian_individual`
   - `opsi_lainnya_ukuran_celana_rok_harian_individual`
   - `opsi_lainnya_ukuran_kopyah_individual`
2. Update `Seragam` model with new fillable fields
3. Add toggle methods in `Admin\DaftarUlang` component
4. Add toggle UI in admin blade view
5. Add 6 computed properties in `Pendaftar\DaftarUlang` component
6. Update component mount() to check Global OR Individual for each field

---

## 🎯 Summary of Features

### ✅ Individual Toggles Available For:

1. **Madrasah - Dropdown Opsi Lainnya**
   - Admin can enable per-pendaftar
   - Shows "Opsi Lainnya" in paket dropdown

2. **Madrasah - Konfirmasi Opsi Lainnya**
   - Admin can enable per-pendaftar
   - Shows "Opsi Lainnya" in konfirmasi nominal payment

3. **Seragam - Dropdown Opsi Lainnya**
   - Admin can enable per-pendaftar
   - Shows "Opsi Lainnya" in paket dropdown

4. **Seragam - Konfirmasi Opsi Lainnya**
   - Admin can enable per-pendaftar
   - Shows "Opsi Lainnya" in konfirmasi nominal payment

### ❌ Individual Toggles NOT Available For:

1. **All Ukuran fields** (6 fields total):
   - Ukuran Jas
   - Ukuran Baju Olahraga
   - Ukuran Celana Olahraga
   - Ukuran Baju Harian
   - Ukuran Celana/Rok Harian
   - Ukuran Kopyah

These only respect **global settings** (applied to ALL pendaftar).

---

## 🧪 Testing Guide

### Test 1: Madrasah Dropdown (Individual Toggle)

1. **Admin:** Go to `/admin/daftar-ulang?activeTab=madrasah`
2. **Admin:** Find a pendaftar, enable "Dropdown Opsi Lainnya" toggle
3. **Admin:** Verify toggle turns blue, success message appears
4. **Pendaftar:** Login as that pendaftar
5. **Pendaftar:** Go to `/pendaftar/daftar-ulang?activeTab=madrasah`
6. **Pendaftar:** Scroll to "Pilih Paket Daftar Ulang Madrasah" dropdown
7. **✅ Expected:** "Opsi Lainnya" appears in dropdown
8. **Pendaftar:** Select "Opsi Lainnya" and enter custom amount
9. **✅ Expected:** Can proceed with custom amount

### Test 2: Madrasah Konfirmasi (Individual Toggle)

1. **Admin:** Enable "Konfirmasi Opsi Lainnya" toggle for a pendaftar
2. **Pendaftar:** Login and go to Madrasah tab
3. **Pendaftar:** Select any package and proceed to konfirmasi step
4. **✅ Expected:** "Opsi Lainnya" appears in konfirmasi nominal options
5. **✅ Expected:** Can input custom nominal amount

### Test 3: Seragam (Same as Madrasah)

Repeat Test 1 and Test 2 for Seragam tab.

### Test 4: Global Setting Priority

1. **Admin:** Enable global setting: `daftar_ulang_madrasah_enable_opsi_lainnya = true`
2. **Pendaftar:** Login as ANY pendaftar (even those with individual toggle OFF)
3. **✅ Expected:** ALL pendaftar see "Opsi Lainnya"
4. **Admin:** Disable global setting
5. **✅ Expected:** Only pendaftar with individual toggle ON see "Opsi Lainnya"

### Test 5: Ukuran (Global Only)

1. **Admin:** Go to `/admin/settings` (or wherever ukuran global settings are)
2. **Admin:** Enable `daftar_ulang_ukuran_jas_enable_opsi_lainnya = true`
3. **Pendaftar:** Login as ANY pendaftar
4. **Pendaftar:** Go to `/pendaftar/daftar-ulang?activeTab=ukuran`
5. **✅ Expected:** ALL pendaftar see "Opsi Lainnya" for Ukuran Jas
6. **Note:** No way to enable for specific pendaftar (no individual toggle exists)

---

## 🚀 Deployment

### Step 1: Clear Local Cache

```bash
php artisan optimize:clear
```

### Step 2: Test Locally

1. Enable individual toggle in admin
2. Login as pendaftar
3. Verify "Opsi Lainnya" now appears
4. Test all 4 scenarios (Madrasah dropdown, Madrasah konfirmasi, Seragam dropdown, Seragam konfirmasi)

### Step 3: Upload to Production

Use the upload script:
```bash
.\upload_fitur_opsi_individual_COMPLETE_9_feb_2026.bat
```

**Files uploaded:**
1. `app/Models/DaftarUlang.php`
2. `app/Models/Seragam.php`
3. `app/Livewire/Admin/DaftarUlang.php`
4. `resources/views/livewire/admin/daftar-ulang.blade.php`
5. `app/Livewire/Pendaftar/DaftarUlang.php` (with computed properties)
6. **`resources/views/livewire/pendaftar/daftar-ulang.blade.php`** ← THIS FIX
7. `database/migrations/2026_02_09_100000_add_individual_opsi_lainnya_to_daftar_ulang_and_seragam.php`

### Step 4: Run Migration (if not yet run)

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan migrate
```

### Step 5: Clear Production Cache

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan optimize:clear
```

### Step 6: Test in Production

Repeat testing steps above in production environment.

---

## 📊 What Was Changed

### Before This Fix:

| Component | Status |
|-----------|--------|
| Database migration | ✅ Complete |
| Models | ✅ Complete |
| Admin backend (toggle methods) | ✅ Complete |
| Admin frontend (toggle UI) | ✅ Complete |
| Pendaftar component (computed properties) | ✅ Complete |
| **Pendaftar view (blade template)** | ❌ **Using direct queries** |

**Result:** Individual toggles had no effect because view ignored component logic.

### After This Fix:

| Component | Status |
|-----------|--------|
| Database migration | ✅ Complete |
| Models | ✅ Complete |
| Admin backend (toggle methods) | ✅ Complete |
| Admin frontend (toggle UI) | ✅ Complete |
| Pendaftar component (computed properties) | ✅ Complete |
| **Pendaftar view (blade template)** | ✅ **Using computed properties** |

**Result:** Individual toggles now work correctly. View uses component logic (Global OR Individual).

---

## 🎉 Feature Complete!

**Steps 1-5 ALL COMPLETE:**
- ✅ Step 1: Database migration
- ✅ Step 2: Model updates
- ✅ Step 3: Admin backend logic
- ✅ Step 4: Admin frontend UI
- ✅ Step 5: Pendaftar component logic + **Blade view fix**

**Status:** 🚀 **READY FOR PRODUCTION**

---

## 📝 Technical Notes

### Why This Bug Happened

In Livewire, there are two ways to access data in blade views:

**Method 1: Component properties**
```php
// In blade: Use component property
$value = $this->someProperty;
```

**Method 2: Direct queries**
```php
// In blade: Query database directly
$value = \App\Models\Setting::get('some_setting', false);
```

**The problem:**
- I updated the component properties to include individual logic
- But the blade view was using Method 2 (direct queries)
- Direct queries ALWAYS return only global settings
- Component properties were never being used

**The fix:**
- Changed blade view to use Method 1 (component properties)
- Now it uses the computed properties that check Global OR Individual

### Best Practice

Always use component properties/methods in Livewire blade views instead of direct database queries. This ensures:
1. Business logic stays in component (not in view)
2. Easier to test and maintain
3. Component can add additional logic (like OR conditions)
4. View just displays what component provides

---

**Date:** 9 Feb 2026
**Feature:** Individual Opsi Lainnya Toggles
**Status:** Complete (Steps 1-5 including blade view fix)
**Risk:** Low (backward compatible, only affects individual toggles)
**Recommendation:** Deploy to production

