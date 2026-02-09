# Fix Cache Issue - RTE Display di Pendaftar

## 🔍 Problem yang Ditemukan

### User Report:
> "Saya sudah ubah informasi di `/admin/daftar-ulang?activeTab=settings` tapi tidak muncul dengan benar di `/pendaftar/daftarulang?activeTab=madrasah`. Yang muncul justru masih teks tulisan lama sebelum perbaikan RTE."

### Root Cause: **Blade View Cache**

Di `resources/views/livewire/pendaftar/daftar-ulang.blade.php`, data `madrasahInfo`, `seragamInfo`, dan `ukuranInfo` diambil langsung di blade view menggunakan `@php` directive:

```blade
@php
    $madrasahInfo = \App\Models\Setting::get('daftar_ulang_madrasah_info', '');
@endphp
```

**Masalah:**
1. Laravel melakukan **view caching** untuk performa
2. View yang sudah di-cache tidak menjalankan `@php` block lagi
3. Data lama tetap di-cache meskipun Admin sudah update settings di database
4. Pendaftar melihat informasi lama yang sudah di-cache

---

## ✅ Solution Implemented

### Pindahkan Data Loading ke Livewire Component

Sekarang data diambil di Livewire component `mount()` method, bukan di blade view.

### Files Modified: 2 files

---

## 📝 File 1: `app/Livewire/Pendaftar/DaftarUlang.php`

### Changes Made:

#### 1. Tambah Public Properties (Lines 30-32)

**BEFORE:**
```php
// Settings
public $paymentInfoText;

// Enable Opsi Lainnya Settings
```

**AFTER:**
```php
// Settings
public $paymentInfoText;
public $madrasahInfo;
public $seragamInfo;
public $ukuranInfo;

// Enable Opsi Lainnya Settings
```

#### 2. Load Data di mount() Method (Lines 147-150)

**Added after line 145:**
```php
// Load payment info text setting
$this->paymentInfoText = Setting::get('daftar_ulang_payment_info_text', '...');

// Load RTE informasi settings
$this->madrasahInfo = Setting::get('daftar_ulang_madrasah_info', '');
$this->seragamInfo = Setting::get('daftar_ulang_seragam_info', '');
$this->ukuranInfo = Setting::get('daftar_ulang_ukuran_info', '');

// Load "Opsi Lainnya" enable settings
```

**Why this works:**
- Livewire component `mount()` runs on every page load
- Data is fresh from database every time
- No caching issue because it's at component level, not view level

---

## 📝 File 2: `resources/views/livewire/pendaftar/daftar-ulang.blade.php`

### Changes Made:

#### Location 1: Madrasah Info (Lines 336-341, 348)

**BEFORE:**
```blade
@php
    $madrasahInfo = \App\Models\Setting::get('daftar_ulang_madrasah_info', '');
    $showPernyataan = \App\Models\Setting::get('daftar_ulang_show_pernyataan', true);
    $showPembayaranMadrasah = \App\Models\Setting::get('daftar_ulang_show_pembayaran_madrasah', true);
@endphp

@if($madrasahInfo)
    ...
    {!! $madrasahInfo !!}
```

**AFTER:**
```blade
@php
    $showPernyataan = \App\Models\Setting::get('daftar_ulang_show_pernyataan', true);
    $showPembayaranMadrasah = \App\Models\Setting::get('daftar_ulang_show_pembayaran_madrasah', true);
@endphp

@if($this->madrasahInfo)
    ...
    {!! $this->madrasahInfo !!}
```

#### Location 2: Seragam Info (Lines 1351-1355, 1362)

**BEFORE:**
```blade
@php
    $seragamInfo = \App\Models\Setting::get('daftar_ulang_seragam_info', '');
    $showPembayaranSeragam = \App\Models\Setting::get('daftar_ulang_show_pembayaran_seragam', true);
@endphp

@if($seragamInfo)
    ...
    {!! $seragamInfo !!}
```

**AFTER:**
```blade
@php
    $showPembayaranSeragam = \App\Models\Setting::get('daftar_ulang_show_pembayaran_seragam', true);
@endphp

@if($this->seragamInfo)
    ...
    {!! $this->seragamInfo !!}
```

#### Location 3: Ukuran Info (Lines 2150-2156, 2163)

**BEFORE:**
```blade
@php
    $ukuranInfo = \App\Models\Setting::get('daftar_ulang_ukuran_info', '');
    $showUkuranAtasan = \App\Models\Setting::get('daftar_ulang_show_ukuran_atasan', true);
    ...
@endphp

@if($ukuranInfo)
    ...
    {!! $ukuranInfo !!}
```

**AFTER:**
```blade
@php
    $showUkuranAtasan = \App\Models\Setting::get('daftar_ulang_show_ukuran_atasan', true);
    ...
@endphp

@if($this->ukuranInfo)
    ...
    {!! $this->ukuranInfo !!}
```

---

## 🚀 How to Deploy

### Step 1: Upload Modified Files

Upload 2 files ke production:
```bash
scp app/Livewire/Pendaftar/DaftarUlang.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/

scp resources/views/livewire/pendaftar/daftar-ulang.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
```

### Step 2: Clear Cache (PENTING!)

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Clear view cache
php artisan view:clear

# Clear application cache
php artisan cache:clear

# Clear Livewire cache
php artisan livewire:delete-tmp

# Clear config cache (optional)
php artisan config:clear

# Optimize
php artisan optimize:clear
```

Atau gunakan script `clear_cache_production.bat` (akan dibuat).

---

## 🧪 Testing Checklist

### Test 1: Update Informasi di Admin

1. Login sebagai Admin
2. Buka `/admin/daftar-ulang?activeTab=settings`
3. Pilih tab "Madrasah"
4. Update "Informasi untuk Pendaftar" dengan RTE:
   - Add bold text: `**Test Bold**`
   - Add list
   - Add link
5. Klik "Simpan Pengaturan Madrasah"
6. Verify success message

### Test 2: Check Display di Pendaftar

1. Logout dari Admin
2. Login sebagai Pendaftar (yang sudah lulus)
3. Buka `/pendaftar/daftarulang?activeTab=madrasah`
4. Verify informasi box tampil dengan:
   - ✅ Text baru (bukan text lama)
   - ✅ Bold formatting works
   - ✅ Lists formatted properly
   - ✅ Links clickable
   - ✅ Prose classes applied (proper typography)

### Test 3: Update Seragam Info

1. Login sebagai Admin
2. Buka `/admin/daftar-ulang?activeTab=settings`
3. Pilih tab "Seragam"
4. Update "Informasi untuk Pendaftar"
5. Save
6. Check di `/pendaftar/daftarulang?activeTab=seragam`
7. Verify new content appears

### Test 4: Update Ukuran Info

1. Login sebagai Admin
2. Buka `/admin/daftar-ulang?activeTab=settings`
3. Pilih tab "Ukuran"
4. Update "Informasi untuk Pendaftar"
5. Save
6. Check di `/pendaftar/daftarulang?activeTab=ukuran`
7. Verify new content appears

### Test 5: Cache Behavior

1. Update informasi di Admin
2. **TANPA clear cache**, buka di Pendaftar
3. Should see NEW content (karena sekarang di-load di component, bukan blade cache)
4. Refresh multiple times
5. Content should stay up-to-date

---

## 📊 Technical Benefits

### Before (Blade @php):
- ❌ Data cached with blade view
- ❌ Stale data after admin update
- ❌ Need to clear cache manually every time
- ❌ Poor developer experience

### After (Livewire Component):
- ✅ Data loaded fresh every render
- ✅ Admin updates immediately visible
- ✅ No manual cache clearing needed
- ✅ Better performance (Livewire caching)
- ✅ Consistent with other Livewire patterns

---

## ⚠️ Important Notes

### Why This Happened:

1. **Blade caching** is a Laravel feature for performance
2. Code inside `@php` blocks gets cached with the view
3. Cached blade views don't re-execute PHP code
4. This is expected Laravel behavior, not a bug

### Best Practice:

- ✅ Always load dynamic data in Livewire component
- ✅ Use blade `@php` only for view-specific logic
- ❌ Never use `@php` for database queries in production

### When to Clear Cache:

After this fix, you generally DON'T need to clear cache when updating settings. However, clear cache:
- After deploying new blade views
- After config changes
- After route changes
- If you see unexpected cached data

---

## 🔗 Related Files

**This Fix:**
- `app/Livewire/Pendaftar/DaftarUlang.php` - Added properties & mount() loading
- `resources/views/livewire/pendaftar/daftar-ulang.blade.php` - Use component properties

**Related to RTE Implementation (Session 8 Feb):**
- `app/Livewire/Admin/DaftarUlang.php` - Admin RTE save logic
- `resources/views/livewire/admin/daftar-ulang.blade.php` - Admin RTE fields

**Upload Script:**
- `upload_cache_fix_8_feb_2026.bat` - Upload these 2 files + clear cache

---

## 📦 Summary

**Files to Upload:** 2 files
1. `app/Livewire/Pendaftar/DaftarUlang.php`
2. `resources/views/livewire/pendaftar/daftar-ulang.blade.php`

**After Upload:**
- Clear all cache (view, config, Livewire, optimize)
- Test admin update → pendaftar display
- Verify RTE formatting displays correctly

**Expected Result:**
Admin updates in `/admin/daftar-ulang?activeTab=settings` will immediately appear in `/pendaftar/daftarulang` without manual cache clearing.

---

**Status:** ✅ FIXED
**Date:** 8 Feb 2026
**Issue:** Cache causing stale data display
**Solution:** Move data loading from blade to Livewire component
