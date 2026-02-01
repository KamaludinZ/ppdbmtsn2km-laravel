# Fix: Tombol Lihat Bukti, Download Template & Lihat Surat Pernyataan Ter-Disable di Tab Madrasah

**Tanggal**: 30 Januari 2026
**Status**: ✅ SELESAI

## 📋 Problem

Di halaman `/pendaftar/daftarulang?activeTab=madrasah`, ketika status sudah **disetujui**, beberapa tombol ikut ter-disable padahal seharusnya tombol-tombol tersebut **tetap harus bisa diklik**:

**Issue**:
1. **Section Bukti Pembayaran**:
   - Tombol "Lihat Bukti" tidak bisa diklik (ter-disable)
   - Tombol "Download Invoice" tidak bisa diklik (ter-disable)
   - User tidak bisa melihat bukti pembayaran yang telah diupload
   - User tidak bisa download invoice setelah data disetujui

2. **Section Surat Pernyataan**:
   - Tombol "Download Template" tidak bisa diklik (ter-disable)
   - Tombol "Lihat" surat pernyataan yang telah diupload tidak bisa diklik (ter-disable)
   - User tidak bisa download template surat pernyataan
   - User tidak bisa melihat surat pernyataan yang telah diupload

**Root Cause**:
Tombol-tombol tersebut berada di dalam wrapper `<div class="{{ $isMadrasahApproved ? 'pointer-events-none opacity-60' : '' }}">` yang men-disable semua elemen di dalamnya ketika status = 'disetujui'.

---

## 🔧 Solution

**File**: `resources/views/livewire/pendaftar/daftar-ulang.blade.php`

**Lines Modified**:
- Line 545-546: Close first wrapper, add comment
- Line 657-684: Surat pernyataan header + tombol (NO wrapper)
- Line 685-686: Open new wrapper for upload input only
- Line 721-722: Close wrapper, add comment

### Strategy

Memecah wrapper yang ter-disable menjadi 3 bagian terpisah:

1. **Wrapper 1** (Lines 373-545): Form fields yang harus ter-disable
   - Nominal biaya madrasah
   - Konfirmasi nominal pembayaran
   - Opsi pembayaran
   - Metode pembayaran / Bank

2. **Section tanpa wrapper** (Lines 548-655): Upload bukti pembayaran
   - **NOT DISABLED** - Tombol dapat diklik!
   - Tombol "Lihat Bukti"
   - Tombol "Download Invoice"
   - Upload bukti pembayaran (tetap ter-disable oleh kondisi lain)

3. **Section surat pernyataan - SPLIT INTO 2 PARTS**:
   - **Part A (Lines 657-683): NO WRAPPER** - Tombol dapat diklik!
     - Header & deskripsi
     - Tombol "Download Template"
     - Tombol "Lihat" surat pernyataan
   - **Part B (Lines 685-721): INSIDE WRAPPER** - Ter-disable
     - Alert "Data Sudah Disetujui"
     - Upload input surat pernyataan

### Changes Made

#### Change 1: Close First Wrapper After Form Fields

**Location**: Line 545-546

**Before**:
```html
                        @endif

                        <!-- Upload Bukti Pembayaran -->
```

**After**:
```html
                        @endif
                    </div>
                    <!-- End of disabled wrapper for form fields -->

                        <!-- Upload Bukti Pembayaran -->
```

#### Change 2: Remove Wrapper from Surat Pernyataan Header & Tombol

**Location**: Line 657-683

**Before** (All inside disabled wrapper):
```html
                    <div class="{{ $isMadrasahApproved ? 'pointer-events-none opacity-60' : '' }}">
                    @if($showPernyataan)
                    <!-- Surat Pernyataan -->
                    <div class="bg-blue-50...">
                        <h5>Surat Pernyataan</h5>
                        <p>Download template...</p>

                        <!-- Download Template button -->
                        <a href="..." download>Download Template</a>

                        <!-- Lihat surat pernyataan button -->
                        <a href="..." target="_blank">Lihat</a>

                        <!-- Upload input (should be disabled) -->
                        ...
                    </div>
                    @endif
                    </div>
```

**After** (Split into active buttons + disabled upload):
```html
                    @if($showPernyataan)
                    <!-- Surat Pernyataan -->
                    <div class="bg-blue-50...">
                        <h5>Surat Pernyataan</h5>
                        <p>Download template...</p>

                        <!-- Download Template button - ACTIVE ✅ -->
                        <a href="..." download>Download Template</a>

                        <!-- Lihat surat pernyataan button - ACTIVE ✅ -->
                        <a href="..." target="_blank">Lihat</a>

                        <div class="{{ $isMadrasahApproved ? 'pointer-events-none opacity-60' : '' }}">
                        <!-- Upload input - DISABLED ✅ -->
                        ...
                        </div>
                    </div>
                    @endif
```

#### Change 3: Add Wrapper Only for Upload Input

**Location**: Line 685-686, 721-722

Added new wrapper that only wraps the upload input section:
```html
<div class="{{ $isMadrasahApproved ? 'pointer-events-none opacity-60' : '' }}">
<!-- Start of disabled wrapper for surat pernyataan upload -->
    @if($daftarUlang && $daftarUlang->status === 'disetujui')
        <!-- Alert -->
    @else
        <!-- Upload input -->
    @endif
</div>
<!-- End of disabled wrapper for surat pernyataan upload -->
```

---

## ✅ Benefits

### 1. Tombol Tetap Berfungsi
- ✅ Tombol "Lihat Bukti" dapat diklik meskipun data sudah disetujui
- ✅ Tombol "Download Invoice" dapat diklik meskipun data sudah disetujui
- ✅ Tombol "Download Template" dapat diklik meskipun data sudah disetujui
- ✅ Tombol "Lihat" surat pernyataan dapat diklik meskipun data sudah disetujui
- ✅ User dapat melihat bukti pembayaran kapan saja
- ✅ User dapat download invoice kapan saja
- ✅ User dapat download template surat pernyataan kapan saja
- ✅ User dapat melihat surat pernyataan yang telah diupload kapan saja

### 2. Form Fields Tetap Ter-Disable
- ✅ Field nominal biaya tetap tidak bisa diubah setelah disetujui
- ✅ Field konfirmasi nominal tetap tidak bisa diubah setelah disetujui
- ✅ Field opsi pembayaran tetap tidak bisa diubah setelah disetujui
- ✅ Field bank tetap tidak bisa diubah setelah disetujui
- ✅ Surat pernyataan tetap tidak bisa diupload ulang setelah disetujui

### 3. Better UX
- ✅ User dapat mengakses bukti dan invoice mereka setelah approval
- ✅ Konsisten dengan behavior yang diharapkan
- ✅ Tidak membingungkan user

---

## 🎯 Result

### Sebelum Fix:
```
┌────────────────────────────────────────────────┐
│ Tab Madrasah - Status: Disetujui               │
├────────────────────────────────────────────────┤
│ ✅ Alert: Data Sudah Disetujui                 │
│                                                 │
│ [All form fields disabled - opacity 60%] ✅    │
│                                                 │
│ ✅ Bukti pembayaran telah diupload             │
│  [Lihat Bukti] [Download Invoice]              │
│   ❌ DISABLED   ❌ DISABLED                     │
│   (tidak bisa diklik karena pointer-events-none)│
│                                                 │
│ 📄 Surat Pernyataan                            │
│  [Download Template]  [Lihat]                  │
│   ❌ DISABLED          ❌ DISABLED              │
│   (tidak bisa diklik karena pointer-events-none)│
│  [Upload input disabled] ✅                     │
└────────────────────────────────────────────────┘
```

### Setelah Fix:
```
┌────────────────────────────────────────────────┐
│ Tab Madrasah - Status: Disetujui               │
├────────────────────────────────────────────────┤
│ ✅ Alert: Data Sudah Disetujui                 │
│                                                 │
│ [All form fields disabled - opacity 60%] ✅    │
│                                                 │
│ ✅ Bukti pembayaran telah diupload             │
│  [Lihat Bukti] [Download Invoice]              │
│   ✅ ACTIVE     ✅ ACTIVE                       │
│   (bisa diklik - OUTSIDE disabled wrapper!)    │
│                                                 │
│ 📄 Surat Pernyataan                            │
│  [Download Template]  [Lihat]                  │
│   ✅ ACTIVE            ✅ ACTIVE                │
│   (bisa diklik - OUTSIDE disabled wrapper!)    │
│  [Upload input disabled] ✅                     │
└────────────────────────────────────────────────┘
```

---

## 📝 Testing Checklist

- [ ] Login sebagai pendaftar yang status madrasah = 'disetujui'
- [ ] Buka `/pendaftar/daftarulang?activeTab=madrasah`
- [ ] Verifikasi alert box hijau muncul (data sudah disetujui)
- [ ] Verifikasi form fields ter-disable (opacity 60%, tidak bisa diubah)
- [ ] **Test tombol "Lihat Bukti"**:
  - [ ] Tombol harus **bisa diklik** (tidak ter-disable)
  - [ ] Klik tombol → Bukti pembayaran muncul di tab baru
  - [ ] File tampil dengan benar
- [ ] **Test tombol "Download Invoice"**:
  - [ ] Tombol harus **bisa diklik** (tidak ter-disable)
  - [ ] Klik tombol → Invoice ter-download
  - [ ] PDF invoice ter-download dengan benar
- [ ] **Test tombol "Download Template"** (Surat Pernyataan):
  - [ ] Tombol harus **bisa diklik** (tidak ter-disable)
  - [ ] Klik tombol → Template surat pernyataan ter-download
  - [ ] File template ter-download dengan benar
- [ ] **Test tombol "Lihat"** (Surat Pernyataan yang sudah diupload):
  - [ ] Tombol harus **bisa diklik** (tidak ter-disable)
  - [ ] Klik tombol → Surat pernyataan muncul di tab baru
  - [ ] File surat pernyataan tampil dengan benar
- [ ] Verifikasi upload surat pernyataan tetap ter-disable
- [ ] Verifikasi tombol simpan tetap disabled dengan text "Data Sudah Disetujui"

---

## 📚 Files Modified

1. `resources/views/livewire/pendaftar/daftar-ulang.blade.php`
   - Line 545-546: Closed first wrapper after form fields, added comment
   - Line 657-683: Removed wrapper from surat pernyataan header & tombol (Download Template, Lihat)
   - Line 685-686: Opened new wrapper for upload input only
   - Line 721-722: Closed wrapper, added comment

---

## 🔍 Technical Details

### Wrapper Structure

**Before Fix** (Two large wrappers):
```html
Line 373: <div class="{{ $isMadrasahApproved ? 'pointer-events-none opacity-60' : '' }}">
  Lines 374-544: Form fields (should be disabled) ✅
  Lines 548-655: Upload bukti + tombol (should NOT be disabled) ❌
Line 545: </div>

Line 657: <div class="{{ $isMadrasahApproved ? 'pointer-events-none opacity-60' : '' }}">
  Lines 659-683: Surat pernyataan header + tombol (should NOT be disabled) ❌
  Lines 687-718: Upload input (should be disabled) ✅
Line 723: </div>
```

**After Fix** (Split into 3 precise wrappers):
```html
Line 373: <div class="{{ $isMadrasahApproved ? 'pointer-events-none opacity-60' : '' }}">
  Lines 374-544: Form fields (disabled) ✅
Line 545: </div>

Lines 548-655: Upload bukti + tombol (NOT disabled) ✅

Lines 657-683: Surat pernyataan header + tombol (NOT disabled) ✅
  - Line 671-674: Download Template button ✅
  - Line 677-682: Lihat surat pernyataan button ✅

Line 685: <div class="{{ $isMadrasahApproved ? 'pointer-events-none opacity-60' : '' }}">
  Lines 687-720: Upload input surat pernyataan (disabled) ✅
Line 721: </div>
```

### Why Split the Wrapper?

1. **Form fields** harus ter-disable agar user tidak bisa ubah data setelah approval
2. **Tombol akses dokumen** harus tetap aktif agar user bisa akses dokumen mereka:
   - Lihat Bukti (bukti pembayaran)
   - Download Invoice
   - Download Template (surat pernyataan)
   - Lihat (surat pernyataan yang sudah diupload)
3. **Upload inputs** harus ter-disable agar tidak bisa upload ulang setelah approval:
   - Upload bukti pembayaran (sudah ter-disable by conditional logic)
   - Upload surat pernyataan

Dengan split wrapper, kita bisa kontrol mana yang ter-disable dan mana yang tetap aktif. User tetap bisa mengakses dokumen mereka kapan saja, tapi tidak bisa mengubah atau upload ulang setelah disetujui.

---

## 💡 Related Issues

### Tab Seragam & Ukuran

Tab Seragam dan Ukuran **tidak memiliki masalah ini** karena:
- Di tab Seragam, tombol "Lihat Bukti" dan "Download Invoice" tidak menggunakan wrapper disable
- Di tab Ukuran, tidak ada tombol akses dokumen

Jika di masa depan ada masalah serupa di tab lain, gunakan strategi yang sama: **split wrapper** agar tombol akses tetap di luar disabled area.

---

**Summary**: Successfully fixed issue where multiple access buttons were disabled when madrasah status is 'disetujui'. Split the disabled wrappers into precise sections:
1. Form fields remain disabled (cannot edit after approval)
2. Access buttons remain active (Lihat Bukti, Download Invoice, Download Template, Lihat Surat Pernyataan)
3. Upload inputs remain disabled (cannot re-upload after approval)

Users can now always access their documents (bukti pembayaran, invoice, template, surat pernyataan) even after data is approved, while form editing and re-uploading are properly restricted.
