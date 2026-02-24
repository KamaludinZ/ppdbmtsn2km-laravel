# QUICK FIX - Missing PreviewKartuTes.php
**Tanggal**: 15 Februari 2026
**Status**: 🔴 URGENT - Production Error!

---

## Error di Production

```
UnexpectedValueException: Invalid route action: [App\Livewire\Admin\PreviewKartuTes].
at vendor/laravel/framework/src/Illuminate/Routing/RouteAction.php:92
```

**Root Cause**: File `PreviewKartuTes.php` tidak ter-upload ke production!

---

## Quick Fix (Manual Upload)

### Option 1: Upload via SCP (Cepat!)

```bash
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Admin\PreviewKartuTes.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
```

**Setelah upload, clear cache:**
```bash
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
```

---

### Option 2: Run Updated Batch File

Batch file sudah di-update untuk include file yang missing.

```bash
upload_fix_kartu_tes_pdf_15_feb_2026.bat
```

**Files yang akan di-upload (7 files total)**:
1. ✅ PreviewInvoiceController.php
2. ✅ KartuTesController.php
3. 🆕 **PreviewKartuTes.php** ← MISSING FILE!
4. ✅ kartu-tes-seleksi.blade.php (PDF)
5. ✅ kartu-tes-seleksi.blade.php (Livewire)
6. ✅ detail-pendaftar.blade.php
7. ✅ web.php

---

## Penjelasan File PreviewKartuTes.php

**Purpose**: Livewire component untuk preview HTML kartu tes di admin panel

**Route yang menggunakan**:
```php
// routes/web.php line 273
Route::get('/admin/preview-kartu-tes/{pendaftar_id}',
    \App\Livewire\Admin\PreviewKartuTes::class)
    ->name('admin.preview-kartu-tes');
```

**Features**:
- Generate preview HTML kartu tes (bukan PDF)
- Load data pendaftar dengan relations
- Generate alamat lengkap
- Load jadwal tes dan kartu kendali
- Load print settings
- Render ke view `livewire.pendaftar.kartu-tes-seleksi`

**Berbeda dengan**:
- `admin.preview-kartu-tes-pdf` → Generate PDF (Controller)
- `admin.preview-kartu-tes` → Preview HTML (Livewire)

---

## Verifikasi Setelah Upload

### Test 1: Check Error Hilang
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan cache:clear
```

**Expected**: No error! ✅

### Test 2: Check File Exists
```bash
ls -la app/Livewire/Admin/PreviewKartuTes.php
```

**Expected**: File exists dengan size ~4.5KB

### Test 3: Access Route
Buka browser: `/admin/preview-kartu-tes/27`

**Expected**: Preview HTML kartu tes muncul (bukan error 500)

---

## Summary

| Item | Status |
|------|--------|
| **Root Cause** | PreviewKartuTes.php tidak ter-upload |
| **Impact** | Route error, cache:clear gagal |
| **Fix** | Upload PreviewKartuTes.php ke production |
| **Priority** | 🔴 URGENT |
| **ETA** | 1 menit (upload + cache clear) |

---

## After Fix - Test Checklist

- [ ] `php artisan cache:clear` berhasil (no error!)
- [ ] File PreviewKartuTes.php ada di server
- [ ] Route `/admin/preview-kartu-tes/27` bisa diakses
- [ ] Preview HTML kartu tes tampil dengan benar
- [ ] Tombol "Cetak Kartu" di admin berfungsi
- [ ] PDF generate dengan benar (v18 all fixes!)

---

**Ready to Fix**: ✅ Upload sekarang!
