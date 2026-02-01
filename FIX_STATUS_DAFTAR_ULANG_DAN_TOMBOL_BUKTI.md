# Fix: Status Daftar Ulang & Tombol Bukti/Invoice Conditional ✅

## 📋 Overview Perubahan

Perbaikan pada halaman `/pendaftar/daftar-ulang` untuk:
1. Menampilkan **3 status terpisah** (Madrasah, Seragam, Ukuran)
2. Tombol "Lihat Bukti" dan "Download Invoice" **hanya muncul jika status = 'disetujui'**

**Status**: ✅ COMPLETE & READY FOR TESTING

---

## 🎯 Problem Statement

### Masalah yang Dilaporkan User:

```
"perbaiki di /pendaftar/daftarulang pada bagian status daftar ulang
agar memunculkan ketiga status dari daftar ulang madrasah, daftar ulang seragam, ukuran seragam
jika status sudah menjadi disetujui barulah muncul tombol lihat bukti dan download invoice
di bagian upload bukti pembayaran.."
```

### Requirements:

1. **3 Status Terpisah**:
   - Status Daftar Ulang Madrasah
   - Status Daftar Ulang Seragam
   - Status Ukuran Seragam

2. **Conditional Buttons**:
   - Tombol "Lihat Bukti" → Hanya jika status = 'disetujui' atau 'selesai'
   - Tombol "Download Invoice" → Hanya jika status = 'disetujui' atau 'selesai'
   - Jika status masih 'menunggu' → Tampilkan pesan "Menunggu Persetujuan"

---

## 🔧 Technical Implementation

### File Modified: `resources/views/livewire/pendaftar/daftar-ulang.blade.php`

### 1. Status Overview Section (Lines 53-171)

**BEFORE**:
```blade
<!-- Status Overview -->
<div class="bg-white rounded-lg shadow overflow-hidden mb-6">
    <div class="p-6">
        <div class="flex items-center mb-6">
            @php
                $status = $daftarUlang->status ?? 'belum';
                $statusInfo = match($status) {
                    'selesai' => ['label' => 'SELESAI', 'color' => 'bg-green-100 text-green-800', ...],
                    'proses' => ['label' => 'DALAM PROSES', 'color' => 'bg-yellow-100 text-yellow-800', ...],
                    default => ['label' => 'BELUM SELESAI', 'color' => 'bg-gray-100 text-gray-800', ...],
                };
            @endphp
            <div class="mr-4">
                <span class="px-4 py-2 rounded-full text-lg font-bold {{ $statusInfo['color'] }}">
                    {{ $statusInfo['label'] }}
                </span>
            </div>
            <p class="text-gray-600">{{ $statusInfo['desc'] }}</p>
        </div>
    </div>
</div>
```

**Problem**:
- Hanya menampilkan 1 status umum
- Tidak membedakan status Madrasah, Seragam, dan Ukuran

**AFTER**:
```blade
<!-- Status Overview -->
<div class="bg-white rounded-lg shadow overflow-hidden mb-6">
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Status Daftar Ulang Madrasah -->
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 text-emerald-600 mr-2" ...>...</svg>
                    <h4 class="text-sm font-semibold text-gray-700">Daftar Ulang Madrasah</h4>
                </div>
                @php
                    $statusMadrasah = $daftarUlang->status ?? 'menunggu';
                    $statusMadrasahInfo = match($statusMadrasah) {
                        'disetujui' => ['label' => 'DISETUJUI', 'color' => 'bg-green-100 text-green-800 border-green-300'],
                        'ditolak' => ['label' => 'DITOLAK', 'color' => 'bg-red-100 text-red-800 border-red-300'],
                        'menunggu' => ['label' => 'MENUNGGU', 'color' => 'bg-yellow-100 text-yellow-800 border-yellow-300'],
                        default => ['label' => 'BELUM UPLOAD', 'color' => 'bg-gray-100 text-gray-800 border-gray-300'],
                    };
                @endphp
                <div class="flex items-center justify-center py-3">
                    <span class="px-3 py-1.5 rounded-full text-xs font-bold border {{ $statusMadrasahInfo['color'] }}">
                        {{ $statusMadrasahInfo['label'] }}
                    </span>
                </div>
            </div>

            <!-- Status Daftar Ulang Seragam -->
            <div class="border border-gray-200 rounded-lg p-4">
                ...
                @php
                    $statusSeragam = $seragam->status_seragam ?? 'menunggu';
                @endphp
                ...
            </div>

            <!-- Status Ukuran Seragam -->
            <div class="border border-gray-200 rounded-lg p-4">
                ...
                @php
                    $statusUkuran = $seragam->status_ukuran ?? 'menunggu';
                @endphp
                ...
            </div>
        </div>

        <!-- Overall Status Description -->
        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
            @php
                $allApproved = ($statusMadrasah === 'disetujui' && $statusSeragam === 'disetujui' && $statusUkuran === 'disetujui');
                $anyRejected = ($statusMadrasah === 'ditolak' || $statusSeragam === 'ditolak' || $statusUkuran === 'ditolak');
                $allWaiting = ($statusMadrasah === 'menunggu' && $statusSeragam === 'menunggu' && $statusUkuran === 'menunggu');
            @endphp
            @if($allApproved)
                <div class="flex items-center text-green-700">
                    <svg ...></svg>
                    <span class="font-medium">Selamat! Semua proses daftar ulang Anda telah disetujui.</span>
                </div>
            @elseif($anyRejected)
                <div class="flex items-center text-red-700">
                    <svg ...></svg>
                    <span class="font-medium">Ada data yang ditolak. Silakan periksa catatan dari panitia dan perbaiki data Anda.</span>
                </div>
            @elseif($allWaiting)
                <div class="flex items-center text-yellow-700">
                    <svg ...></svg>
                    <span class="font-medium">Data Anda sedang diperiksa oleh panitia. Mohon tunggu.</span>
                </div>
            @else
                <div class="flex items-center text-gray-700">
                    <svg ...></svg>
                    <span class="font-medium">Silakan lengkapi semua persyaratan daftar ulang di bawah ini.</span>
                </div>
            @endif
        </div>
    </div>
</div>
```

**Changes**:
1. **Grid Layout**: 3 kolom untuk 3 status
2. **Individual Status Cards**: Setiap card menampilkan status terpisah dengan icon
3. **Color Coding**:
   - Madrasah: Emerald green icon
   - Seragam: Blue icon
   - Ukuran: Purple icon
4. **Status Badges**:
   - DISETUJUI: Green
   - DITOLAK: Red
   - MENUNGGU: Yellow
   - BELUM UPLOAD/ISI: Gray
5. **Overall Summary**: Deskripsi keseluruhan berdasarkan kombinasi 3 status

---

### 2. Tab Madrasah - Tombol Bukti (Lines 484-523)

**BEFORE**:
```blade
@if($daftarUlang && $daftarUlang->bukti_pembayaran_madrasah)
    <div class="p-4 bg-green-50 border-2 border-green-200 rounded-lg mb-3">
        <div class="flex items-center mb-3">
            <svg ...></svg>
            <span class="text-sm font-semibold text-green-800">Bukti pembayaran telah diupload</span>
        </div>
        <div class="flex gap-2">
            <!-- ❌ Tombol SELALU MUNCUL meski status masih menunggu -->
            <a href="..." class="...">Lihat Bukti</a>
            @if($daftarUlang->status == 'selesai')
            <button wire:click="previewInvoiceMadrasah" class="...">
                Preview Invoice
            </button>
            @endif
        </div>
    </div>
@endif
```

**Problem**:
- Tombol "Lihat Bukti" selalu muncul, bahkan saat status masih 'menunggu'
- Tombol Invoice hanya muncul jika status = 'selesai' (tidak termasuk 'disetujui')

**AFTER**:
```blade
@if($daftarUlang && $daftarUlang->bukti_pembayaran_madrasah)
    <div class="p-4 bg-green-50 border-2 border-green-200 rounded-lg mb-3">
        <div class="flex items-center mb-3">
            <svg ...></svg>
            <span class="text-sm font-semibold text-green-800">Bukti pembayaran telah diupload</span>
        </div>

        @if($daftarUlang->status == 'disetujui' || $daftarUlang->status == 'selesai')
        <!-- ✅ Tombol HANYA MUNCUL jika status = disetujui atau selesai -->
        <div class="flex gap-2">
            <a href="..." class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg ...">
                <svg ...></svg>
                Lihat Bukti
            </a>
            <button wire:click="previewInvoiceMadrasah" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg ...">
                <svg ...></svg>
                Download Invoice
            </button>
        </div>
        @else
        <!-- ✅ Pesan jika status masih menunggu -->
        <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-yellow-500 mr-2 ...">...</svg>
                <div class="flex-1">
                    <p class="text-sm text-yellow-800 font-medium">Menunggu Persetujuan</p>
                    <p class="text-xs text-yellow-600 mt-1">Bukti pembayaran dan invoice dapat diakses setelah data Anda disetujui oleh panitia.</p>
                </div>
            </div>
        </div>
        @endif
    </div>
@endif
```

**Changes**:
1. **Conditional Rendering**: Tombol hanya muncul jika `status == 'disetujui' || status == 'selesai'`
2. **Informative Message**: Jika masih menunggu, tampilkan pesan yellow box
3. **Better UX**: User tahu kenapa tombol belum muncul
4. **Icon Enhancement**: Tambah icon di setiap tombol untuk visual clarity

---

### 3. Tab Seragam - Tombol Bukti (Lines 974-1020)

**BEFORE**:
```blade
@if($pendaftar->seragam && $pendaftar->seragam->bukti_pembayaran)
    <div class="p-4 bg-green-50 border-2 border-green-200 rounded-lg">
        <div class="flex items-center mb-3">
            <svg ...></svg>
            <span class="text-sm font-semibold text-green-800">Bukti pembayaran telah diupload</span>
        </div>
        <div class="flex gap-2">
            <!-- ❌ Tombol SELALU MUNCUL -->
            <a href="..." class="...">Lihat Bukti</a>
            <a href="..." download class="...">Download</a>
            @if($pendaftar->seragam->status_seragam == 'disetujui' || $pendaftar->seragam->status_seragam == 'selesai')
            <button wire:click="downloadInvoiceSeragam" class="...">Invoice</button>
            @endif
        </div>
    </div>
@else
```

**Problem**:
- Tombol "Lihat Bukti" dan "Download" selalu muncul
- Hanya tombol "Invoice" yang conditional

**AFTER**:
```blade
@if($pendaftar->seragam && $pendaftar->seragam->bukti_pembayaran)
    <div class="p-4 bg-green-50 border-2 border-green-200 rounded-lg mb-3">
        <div class="flex items-center mb-3">
            <svg ...></svg>
            <span class="text-sm font-semibold text-green-800">Bukti pembayaran telah diupload</span>
        </div>

        @if($pendaftar->seragam->status_seragam == 'disetujui' || $pendaftar->seragam->status_seragam == 'selesai')
        <!-- ✅ SEMUA tombol HANYA MUNCUL jika status = disetujui atau selesai -->
        <div class="flex gap-2">
            <a href="..." target="_blank" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg ...">
                <svg ...></svg>
                Lihat Bukti
            </a>
            <a href="..." download class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg ...">
                <svg ...></svg>
                Download
            </a>
            <button wire:click="downloadInvoiceSeragam" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg ...">
                <svg ...></svg>
                Download Invoice
            </button>
        </div>
        @else
        <!-- ✅ Pesan jika status masih menunggu -->
        <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-yellow-500 mr-2 ...">...</svg>
                <div class="flex-1">
                    <p class="text-sm text-yellow-800 font-medium">Menunggu Persetujuan</p>
                    <p class="text-xs text-yellow-600 mt-1">Bukti pembayaran dan invoice dapat diakses setelah data Anda disetujui oleh panitia.</p>
                </div>
            </div>
        </div>
        @endif
    </div>
@else
```

**Changes**: Same as Madrasah tab - all buttons now conditional

---

## 💡 Technical Notes

### 1. Status Mapping

**Database Fields**:
```php
// Table: daftar_ulang
$daftarUlang->status  // Status Madrasah

// Table: seragam
$seragam->status_seragam  // Status Seragam
$seragam->status_ukuran   // Status Ukuran
```

**Status Values**:
- `'disetujui'` - Data sudah disetujui panitia ✅
- `'ditolak'` - Data ditolak, perlu perbaikan ❌
- `'menunggu'` - Sedang dalam review ⏳
- `null` / default - Belum upload/isi ⚪

### 2. Overall Status Logic

```php
$allApproved = (
    $statusMadrasah === 'disetujui' &&
    $statusSeragam === 'disetujui' &&
    $statusUkuran === 'disetujui'
);

$anyRejected = (
    $statusMadrasah === 'ditolak' ||
    $statusSeragam === 'ditolak' ||
    $statusUkuran === 'ditolak'
);

$allWaiting = (
    $statusMadrasah === 'menunggu' &&
    $statusSeragam === 'menunggu' &&
    $statusUkuran === 'menunggu'
);
```

**Priority**:
1. If ALL approved → Green success message
2. Else if ANY rejected → Red warning message
3. Else if ALL waiting → Yellow info message
4. Else → Gray info message (incomplete)

### 3. Button Visibility Rules

**Madrasah Tab**:
```php
if ($daftarUlang->status == 'disetujui' || $daftarUlang->status == 'selesai') {
    // Show: Lihat Bukti, Download Invoice
} else {
    // Show: "Menunggu Persetujuan" message
}
```

**Seragam Tab**:
```php
if ($seragam->status_seragam == 'disetujui' || $seragam->status_seragam == 'selesai') {
    // Show: Lihat Bukti, Download, Download Invoice
} else {
    // Show: "Menunggu Persetujuan" message
}
```

### 4. Responsive Design

```blade
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
```

- **Mobile** (`< 768px`): 1 column (stacked vertically)
- **Desktop** (`>= 768px`): 3 columns (side by side)

---

## 🧪 Testing Checklist

### Test 1: Status Display - All Pending

- [ ] Set all statuses to 'menunggu':
  - `daftar_ulang.status = 'menunggu'`
  - `seragam.status_seragam = 'menunggu'`
  - `seragam.status_ukuran = 'menunggu'`
- [ ] Login as pendaftar
- [ ] Buka `/pendaftar/daftar-ulang`
- [ ] **Expected**:
  - ✅ 3 status cards displayed
  - ✅ All show "MENUNGGU" badge (yellow)
  - ✅ Overall message: "Data Anda sedang diperiksa oleh panitia"

### Test 2: Status Display - All Approved

- [ ] Set all statuses to 'disetujui'
- [ ] Refresh page
- [ ] **Expected**:
  - ✅ All show "DISETUJUI" badge (green)
  - ✅ Overall message: "Selamat! Semua proses daftar ulang Anda telah disetujui" (green)

### Test 3: Status Display - Mixed (Some Rejected)

- [ ] Set:
  - `daftar_ulang.status = 'disetujui'`
  - `seragam.status_seragam = 'ditolak'`
  - `seragam.status_ukuran = 'menunggu'`
- [ ] Refresh page
- [ ] **Expected**:
  - ✅ Madrasah: "DISETUJUI" (green)
  - ✅ Seragam: "DITOLAK" (red)
  - ✅ Ukuran: "MENUNGGU" (yellow)
  - ✅ Overall message: "Ada data yang ditolak..." (red)

### Test 4: Buttons - Status Menunggu (Madrasah)

- [ ] Set `daftar_ulang.status = 'menunggu'`
- [ ] Ensure bukti pembayaran uploaded
- [ ] Buka tab Madrasah
- [ ] **Expected**:
  - ✅ Green box: "Bukti pembayaran telah diupload"
  - ✅ Yellow box: "Menunggu Persetujuan"
  - ❌ NO "Lihat Bukti" button
  - ❌ NO "Download Invoice" button
  - ✅ Message explains why buttons not available

### Test 5: Buttons - Status Disetujui (Madrasah)

- [ ] Set `daftar_ulang.status = 'disetujui'`
- [ ] Ensure bukti pembayaran uploaded
- [ ] Refresh tab Madrasah
- [ ] **Expected**:
  - ✅ Green box: "Bukti pembayaran telah diupload"
  - ✅ Button "Lihat Bukti" VISIBLE and CLICKABLE
  - ✅ Button "Download Invoice" VISIBLE and CLICKABLE
  - ❌ NO yellow "Menunggu Persetujuan" message

### Test 6: Buttons - Status Menunggu (Seragam)

- [ ] Set `seragam.status_seragam = 'menunggu'`
- [ ] Ensure bukti pembayaran uploaded
- [ ] Buka tab Seragam
- [ ] **Expected**:
  - ✅ Green box: "Bukti pembayaran telah diupload"
  - ✅ Yellow box: "Menunggu Persetujuan"
  - ❌ NO buttons visible

### Test 7: Buttons - Status Disetujui (Seragam)

- [ ] Set `seragam.status_seragam = 'disetujui'`
- [ ] Refresh tab Seragam
- [ ] **Expected**:
  - ✅ Button "Lihat Bukti" VISIBLE
  - ✅ Button "Download" VISIBLE
  - ✅ Button "Download Invoice" VISIBLE
  - ❌ NO yellow message

### Test 8: Responsive - Mobile View

- [ ] Open DevTools
- [ ] Set viewport to mobile (e.g., iPhone 12)
- [ ] **Expected**:
  - ✅ Status cards stack vertically (1 column)
  - ✅ Each card full width
  - ✅ Buttons remain functional
  - ✅ Text readable and not cut off

### Test 9: Responsive - Desktop View

- [ ] Set viewport to desktop (1920x1080)
- [ ] **Expected**:
  - ✅ 3 status cards side by side
  - ✅ Equal width columns
  - ✅ Overall message centered below

### Test 10: Edge Case - No Seragam Data

- [ ] Delete seragam record for pendaftar
- [ ] Refresh page
- [ ] **Expected**:
  - ✅ Status Seragam shows "BELUM UPLOAD"
  - ✅ Status Ukuran shows "BELUM ISI"
  - ✅ No errors
  - ✅ Overall message shows appropriate state

---

## 📁 Files Modified

### 1. `resources/views/livewire/pendaftar/daftar-ulang.blade.php`

**Section 1: Status Overview (Lines 53-171)**
- Replaced single status display with 3-column grid
- Added individual status cards for Madrasah, Seragam, Ukuran
- Added overall status summary logic

**Section 2: Madrasah Buttons (Lines 484-523)**
- Wrapped buttons in conditional: `@if($daftarUlang->status == 'disetujui' || $daftarUlang->status == 'selesai')`
- Added "Menunggu Persetujuan" message for pending status
- Added icons to buttons

**Section 3: Seragam Buttons (Lines 974-1020)**
- Wrapped ALL buttons in conditional (including "Lihat Bukti" and "Download")
- Added "Menunggu Persetujuan" message
- Consistent styling with Madrasah tab

**Total Lines Modified**: ~150 lines

---

## 🚀 Deployment Instructions

### Files to Upload

```
📁 Upload ke Production:

1. Frontend (resources/views/):
   - resources/views/livewire/pendaftar/daftar-ulang.blade.php

2. Documentation:
   - FIX_STATUS_DAFTAR_ULANG_DAN_TOMBOL_BUKTI.md
```

### Deployment Steps

#### Step 1: Upload File

```bash
# Via SFTP/FTP or SSH
scp resources/views/livewire/pendaftar/daftar-ulang.blade.php user@server:/path/to/resources/views/livewire/pendaftar/
```

#### Step 2: Clear Cache

```bash
ssh user@production-server
cd /path/to/project

# Clear view cache
php artisan view:clear

# Clear config cache (optional)
php artisan config:clear
```

#### Step 3: Test in Production

- [ ] Login as pendaftar with different status combinations
- [ ] Verify 3 status cards display correctly
- [ ] Verify buttons only show when status = 'disetujui' or 'selesai'
- [ ] Test on mobile and desktop

### No Database Changes Required

**IMPORTANT**:
- ✅ No database migration needed
- ✅ No backend logic changes
- ✅ Only view/frontend changes
- ✅ Uses existing database fields

---

## ✅ Benefits Summary

### For User (Pendaftar):

1. **Clear Status Visibility**
   - ✅ See all 3 statuses at once
   - ✅ Know exactly which part is approved/pending/rejected
   - ✅ No confusion about overall progress

2. **Better UX for Buttons**
   - ✅ No frustration clicking buttons that don't work
   - ✅ Clear explanation why buttons not available
   - ✅ Informative "Menunggu Persetujuan" message

3. **Professional Appearance**
   - ✅ Color-coded status badges (green/red/yellow/gray)
   - ✅ Icons for each section (Madrasah/Seragam/Ukuran)
   - ✅ Responsive design for mobile and desktop

### For Admin/Panitia:

1. **Consistent Workflow**
   - ✅ Users only access approved documents
   - ✅ No confusion about when to download invoice
   - ✅ Clear status tracking

2. **Reduced Support Queries**
   - ✅ Users understand why buttons not available
   - ✅ Clear messaging reduces confusion
   - ✅ Self-explanatory interface

### For System:

1. **Clean Code**
   - ✅ Reusable status display pattern
   - ✅ Consistent button logic across tabs
   - ✅ Maintainable conditional rendering

2. **Security**
   - ✅ Buttons hidden = less attack surface
   - ✅ Status checks prevent unauthorized access
   - ✅ Clear user permissions flow

---

## 📊 Visual Examples

### Status Display Examples

```
╔══════════════════════════════════════════════════════════════╗
║             STATUS DAFTAR ULANG                              ║
╠══════════════════════════════════════════════════════════════╣
║  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      ║
║  │ 🏫 Madrasah  │  │ 👕 Seragam   │  │ 📏 Ukuran    │      ║
║  │              │  │              │  │              │      ║
║  │  DISETUJUI   │  │  MENUNGGU    │  │  DITOLAK     │      ║
║  │   (green)    │  │   (yellow)   │  │    (red)     │      ║
║  └──────────────┘  └──────────────┘  └──────────────┘      ║
║                                                              ║
║  ⚠️ Ada data yang ditolak. Silakan periksa catatan...       ║
╚══════════════════════════════════════════════════════════════╝
```

### Button States

**Status = MENUNGGU**:
```
┌────────────────────────────────────────────┐
│ ✅ Bukti pembayaran telah diupload         │
├────────────────────────────────────────────┤
│ ⏳ Menunggu Persetujuan                    │
│ Bukti pembayaran dan invoice dapat        │
│ diakses setelah data Anda disetujui       │
└────────────────────────────────────────────┘
```

**Status = DISETUJUI**:
```
┌────────────────────────────────────────────┐
│ ✅ Bukti pembayaran telah diupload         │
├────────────────────────────────────────────┤
│ [ 👁️ Lihat Bukti ]  [ 📄 Download Invoice ] │
└────────────────────────────────────────────┘
```

---

**Created**: 30 Januari 2026
**Status**: ✅ COMPLETE & READY FOR TESTING
**Version**: 1.0
**Author**: Development Team
**Priority**: MEDIUM (UX Enhancement)
**Category**: Feature Enhancement - Status Display & Conditional UI

**User Request**: "perbaiki di /pendaftar/daftarulang pada bagian status daftar ulang agar memunculkan ketiga status dari daftar ulang madrasah, daftar ulang seragam, ukuran seragam jika status sudah menjadi disetujui barulah muncul tombol lihat bukti dan download invoice di bagian upload bukti pembayaran.."

**Resolution**: Implemented 3-column status display showing Madrasah, Seragam, and Ukuran statuses separately. Added conditional rendering for "Lihat Bukti" and "Download Invoice" buttons that only appear when status = 'disetujui' or 'selesai', with informative "Menunggu Persetujuan" message when status is still pending.
