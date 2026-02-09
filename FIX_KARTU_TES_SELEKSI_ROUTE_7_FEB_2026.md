# FIX ROUTE KARTU TES SELEKSI DI DASHBOARD - 7 FEB 2026

## 🐛 Problem

Di halaman `/pendaftar/dashboard`, pada bagian **"Data Terverifikasi - Siap Ujian!"**, tombol masih menggunakan route lama:
- ❌ **OLD:** `/pendaftar/kartu-ujian`
- ✅ **NEW:** `/pendaftar/kartu-tes-seleksi`

### Locations Found

Ada **2 lokasi** yang menggunakan route lama:

1. **Line 225** - Tombol besar "Cetak Kartu Ujian" di bagian "Data Terverifikasi - Siap Ujian!"
2. **Line 566** - Link "Cetak Kartu" di menu "Aksi Cepat"

---

## ✅ Solution

### Changes Made

**File:** `resources/views/livewire/pendaftar/dashboard.blade.php`

#### Fix 1: Tombol "Cetak Kartu Ujian" (Line 225)

**BEFORE:**
```html
<a href="/pendaftar/kartu-ujian" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-bold text-lg hover:bg-blue-50 transition transform hover:scale-105 shadow-lg flex items-center">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
    </svg>
    Cetak Kartu Ujian
</a>
```

**AFTER:**
```html
<a href="/pendaftar/kartu-tes-seleksi" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-bold text-lg hover:bg-blue-50 transition transform hover:scale-105 shadow-lg flex items-center">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
    </svg>
    Cetak Kartu Ujian
</a>
```

#### Fix 2: Link "Cetak Kartu" di Aksi Cepat (Line 566)

**BEFORE:**
```html
<a href="/pendaftar/kartu-ujian" class="flex items-center px-4 py-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition">
    <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
    </svg>
    <span class="text-sm font-medium text-gray-900">Cetak Kartu</span>
</a>
```

**AFTER:**
```html
<a href="/pendaftar/kartu-tes-seleksi" class="flex items-center px-4 py-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition">
    <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
    </svg>
    <span class="text-sm font-medium text-gray-900">Cetak Kartu</span>
</a>
```

---

## 🧪 Testing

### Test Case 1: Tombol "Cetak Kartu Ujian"

**Steps:**
1. Login sebagai Pendaftar dengan status "lulus"
2. Buka `/pendaftar/dashboard`
3. Scroll ke bagian **"Data Terverifikasi - Siap Ujian!"** (warna hijau)
4. Klik tombol putih **"Cetak Kartu Ujian"**

**Expected Result:**
- ✅ Redirect ke `/pendaftar/kartu-tes-seleksi`
- ✅ Halaman kartu tes seleksi tampil dengan benar

### Test Case 2: Link "Cetak Kartu" di Aksi Cepat

**Steps:**
1. Login sebagai Pendaftar dengan status "lulus"
2. Buka `/pendaftar/dashboard`
3. Scroll ke bagian **"Aksi Cepat"** (setelah greeting)
4. Klik link warna ungu **"Cetak Kartu"**

**Expected Result:**
- ✅ Redirect ke `/pendaftar/kartu-tes-seleksi`
- ✅ Halaman kartu tes seleksi tampil dengan benar

---

## 📦 Deployment

### Files to Upload

```
resources/views/livewire/pendaftar/
└── dashboard.blade.php
```

### Upload Command

```bash
# Upload dashboard view
scp resources/views/livewire/pendaftar/dashboard.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/

# Clear view cache
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear"
```

### Batch Script

```bash
./upload_fix_kartu_tes_seleksi_route.bat
```

---

## 🔍 Verification in Production

### Manual Check

1. **Check Route Consistency:**
```bash
# Search untuk route lama (harusnya tidak ada)
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && grep -r 'kartu-ujian' resources/views/livewire/pendaftar/dashboard.blade.php"

# Harusnya return empty (tidak ada hasil)
```

2. **Check Route Baru:**
```bash
# Search untuk route baru (harusnya ada 2 hasil)
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && grep -c 'kartu-tes-seleksi' resources/views/livewire/pendaftar/dashboard.blade.php"

# Expected: 2
```

### Browser Test

1. Open `/pendaftar/dashboard` in production
2. Inspect tombol "Cetak Kartu Ujian":
   - Right-click → Inspect Element
   - Check href attribute should be `/pendaftar/kartu-tes-seleksi`
3. Test click tombol dan verify landing page

---

## 📝 Notes

### Route Naming Convention

**OLD Naming:** `kartu-ujian` (generic)
**NEW Naming:** `kartu-tes-seleksi` (more specific)

**Reason for Change:**
- More descriptive naming
- Consistent with actual content (Kartu Tes Seleksi)
- Better SEO and user understanding

### Related Routes

All kartu-related routes should use the new naming:
- ✅ `/pendaftar/kartu-tes-seleksi` - View and print card
- ✅ Component: `app/Livewire/Pendaftar/KartuTesSeleksi.php`
- ✅ View: `resources/views/livewire/pendaftar/kartu-tes-seleksi.blade.php`

### Impact

- 🟢 **No Breaking Changes** - New route already exists and working
- 🟢 **No Database Changes** - Only view layer changes
- 🟢 **No User Data Affected** - Pure routing update

---

## ✅ Status

**Status:** ✅ **FIXED - READY FOR PRODUCTION**

**Changes:**
- ✅ Fixed 2 locations in dashboard.blade.php
- ✅ Updated route from `/pendaftar/kartu-ujian` to `/pendaftar/kartu-tes-seleksi`
- ✅ Tested locally (route consistency verified)
- ✅ Batch script created for deployment
- ✅ Documentation completed

**Impact:**
- 🟢 Route consistency across dashboard
- 🟢 Better naming convention
- 🟢 No breaking changes

**Risk:**
- 🟢 Very Low - Simple route update
- 🟢 No backend changes
- 🟢 Safe to deploy

---

**Generated:** 7 Feb 2026
**Developer:** Claude Code
**Session:** FIX_KARTU_TES_SELEKSI_ROUTE
