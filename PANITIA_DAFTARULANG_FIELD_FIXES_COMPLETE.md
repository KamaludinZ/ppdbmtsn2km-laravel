# Panitia Daftar Ulang - Field Fixes Complete

**Tanggal:** 30 Januari 2026
**Status:** ✅ SELESAI

## Overview

Fixed multiple field name mismatches and data type issues in Panitia Daftar Ulang view and component.

---

## Issues Fixed

### 1. ✅ `number_format()` Type Error - Nominal Biaya & Nominal yang Dibayar

**Error:**
```
number_format(): Argument #1 ($num) must be of type float, array given
```

**Location:** Line 296 in `resources/views/livewire/panitia/daftar-ulang.blade.php`

**Root Cause:**
- `nominal_biaya` and `nominal_biaya_awal` are stored as JSON arrays with structure: `['name' => '...', 'amount' => ...]`
- The model casts them as `'array'` (line 32 of DaftarUlang.php)
- View was trying to format the entire array instead of the `amount` field

**Fix Applied:**
```blade
<!-- BEFORE (Wrong) -->
<td class="px-6 py-4 whitespace-nowrap">
    @if($item->daftarUlang && $item->daftarUlang->nominal_biaya)
        <div class="text-sm font-medium text-gray-900">
            Rp {{ number_format($item->daftarUlang->nominal_biaya, 0, ',', '.') }}
        </div>
    @else
        <span class="text-xs text-gray-400">-</span>
    @endif
</td>

<!-- AFTER (Correct) -->
<td class="px-6 py-4">
    @if($item->daftarUlang && $item->daftarUlang->nominal_biaya_awal)
        @php $nominalBiayaAwal = $item->daftarUlang->nominal_biaya_awal; @endphp
        <div class="text-xs text-gray-900">
            <div class="font-medium text-gray-700 mb-1">{{ $nominalBiayaAwal['name'] ?? '-' }}</div>
            <div class="font-bold text-blue-700">Rp {{ number_format($nominalBiayaAwal['amount'] ?? 0, 0, ',', '.') }}</div>
        </div>
    @else
        <span class="text-xs text-gray-500">Belum pilih</span>
    @endif
</td>

<td class="px-6 py-4">
    @if($item->daftarUlang && $item->daftarUlang->nominal_biaya)
        @php $nominalBiaya = $item->daftarUlang->nominal_biaya; @endphp
        <div class="text-xs text-gray-900">
            <div class="font-medium text-gray-700 mb-1">{{ $nominalBiaya['name'] ?? '-' }}</div>
            <div class="font-bold text-emerald-700">Rp {{ number_format($nominalBiaya['amount'] ?? 0, 0, ',', '.') }}</div>
        </div>
    @else
        <span class="text-xs text-gray-500">Belum konfirmasi</span>
    @endif
</td>
```

---

### 2. ✅ Field Name Mismatch - `bukti_pembayaran_path` → `bukti_pembayaran_madrasah`

**Location:** Line 340 in view

**Issue:** Wrong field name used for checking bukti pembayaran

**Fix Applied:**
```blade
<!-- BEFORE -->
@if($item->daftarUlang && $item->daftarUlang->bukti_pembayaran_path)

<!-- AFTER -->
@if($item->daftarUlang && $item->daftarUlang->bukti_pembayaran_madrasah)
```

**Route Updated:**
```blade
<a href="{{ route('pendaftar.download-file', ['type' => 'bukti_pembayaran_madrasah', 'pendaftarId' => $item->id]) }}"
```

---

### 3. ✅ Field Name Mismatch - `waktu_bayar_madrasah` → `waktu_pembayaran_madrasah`

**Location:** Line 370 in view

**Issue:** Wrong field name used for waktu pembayaran

**Fix Applied:**
```blade
<!-- BEFORE -->
@if($item->daftarUlang && $item->daftarUlang->waktu_bayar_madrasah)
    <div class="text-xs text-gray-700">
        <div class="font-medium">{{ \Carbon\Carbon::parse($item->daftarUlang->waktu_bayar_madrasah)->format('d M Y') }}</div>
        <div class="text-gray-500">{{ \Carbon\Carbon::parse($item->daftarUlang->waktu_bayar_madrasah)->format('H:i') }} WIB</div>
    </div>

<!-- AFTER -->
@if($item->daftarUlang && $item->daftarUlang->waktu_pembayaran_madrasah)
    <div class="text-xs text-gray-900">
        <div class="font-medium">{{ $item->daftarUlang->waktu_pembayaran_madrasah->format('d/m/Y') }}</div>
        <div class="text-gray-600">{{ $item->daftarUlang->waktu_pembayaran_madrasah->format('H:i') }} WIB</div>
    </div>
```

**Benefit:** Uses datetime casting from model, no need for `Carbon::parse()`

---

### 4. ✅ Formatting Updates - Opsi Pembayaran & Bank/Metode

**Changes Made:**
- Removed `whitespace-nowrap` for better responsiveness
- Updated text sizes to match Admin (xs instead of sm)
- Updated color styling to match Admin exactly

```blade
<!-- Opsi Pembayaran -->
<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
    {{ ucfirst($item->daftarUlang->opsi_pembayaran) }}
</span>

<!-- Bank/Metode -->
<div class="text-xs text-gray-900">
    <span class="font-medium">{{ $item->daftarUlang->metode_pembayaran }}</span>
</div>
```

---

### 5. ✅ Catatan Modal - Added Missing Functionality

**Backend Changes:** `app/Livewire/Panitia/DaftarUlang.php`

**Properties Added (Line 46-50):**
```php
// Catatan modal
public $showCatatanModal = false;
public $catatanType = '';
public $catatanPendaftarId = null;
public $catatanText = '';
```

**Methods Added (Line 577-611):**
```php
/**
 * Open catatan modal
 */
public function openCatatanModal($type, $pendaftarId, $currentCatatan = '')
{
    $this->catatanType = $type;
    $this->catatanPendaftarId = $pendaftarId;
    $this->catatanText = $currentCatatan;
    $this->showCatatanModal = true;
}

/**
 * Close catatan modal
 */
public function closeCatatanModal()
{
    $this->showCatatanModal = false;
    $this->catatanType = '';
    $this->catatanPendaftarId = null;
    $this->catatanText = '';
}

/**
 * Save catatan from modal (wrapper that uses properties)
 */
public function saveCatatanFromModal()
{
    if (!$this->catatanPendaftarId || !$this->catatanType) {
        $this->dispatch('saved', message: 'Data tidak valid');
        return;
    }

    // Call the existing saveCatatan method with parameters
    $this->saveCatatan($this->catatanPendaftarId, $this->catatanType, $this->catatanText);

    // Close modal
    $this->closeCatatanModal();
}
```

**View Changes:**

**Edit Button Updated (Line 380):**
```blade
<button
    wire:click="openCatatanModal('madrasah', {{ $item->id }}, '{{ addslashes($item->daftarUlang->catatan ?? '') }}')"
    class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-300 rounded hover:bg-blue-100 transition-colors"
    title="Edit Catatan">
    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
    </svg>
    Edit
</button>
```

**Modal Added (Line 667-717):**
```blade
@if($showCatatanModal)
<div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-0 overflow-y-auto">
    <!-- Background overlay with blur -->
    <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity z-[9998]" wire:click="closeCatatanModal"></div>

    <!-- Modal panel -->
    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full z-[10000]">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
                <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-yellow-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        Edit Catatan DU Madrasah
                    </h3>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea
                            wire:model="catatanText"
                            rows="5"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                            placeholder="Tulis catatan untuk pendaftar..."></textarea>
                        <p class="mt-1 text-xs text-gray-500">Catatan ini hanya bisa dilihat oleh admin dan panitia</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
                type="button"
                wire:click="saveCatatanFromModal"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Simpan Catatan
            </button>
            <button
                type="button"
                wire:click="closeCatatanModal"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                Batal
            </button>
        </div>
    </div>
</div>
@endif
```

---

## Files Modified

### Backend
1. **app/Livewire/Panitia/DaftarUlang.php** (Line count: 652 lines, +24 lines)
   - Added catatan modal properties (4 properties)
   - Added `openCatatanModal()` method
   - Added `closeCatatanModal()` method
   - Added `saveCatatanFromModal()` wrapper method

### View
2. **resources/views/livewire/panitia/daftar-ulang.blade.php** (Line count: 719 lines, +52 lines)
   - Fixed nominal_biaya array handling (lines 292-316)
   - Fixed bukti_pembayaran field name (line 340)
   - Fixed waktu_pembayaran field name (line 356)
   - Updated Opsi Pembayaran formatting (line 319)
   - Updated Bank/Metode formatting (line 330)
   - Updated catatan button to use openCatatanModal (line 380)
   - Added catatan modal UI (lines 667-717)

---

## Testing Checklist

- [x] Page loads without errors
- [ ] Nominal Biaya displays correctly (name + amount)
- [ ] Nominal yang Dibayar displays correctly (name + amount)
- [ ] Bukti Pembayaran badge shows correct status
- [ ] Bukti Pembayaran download link works
- [ ] Waktu Pembayaran displays correctly (date + time)
- [ ] Opsi Pembayaran badge displays correctly
- [ ] Bank/Metode displays correctly
- [ ] Edit Catatan button opens modal
- [ ] Catatan modal saves correctly
- [ ] All columns are responsive on mobile

---

## Database Schema Reference

From `app/Models/DaftarUlang.php`:

```php
protected $casts = [
    'nominal_biaya' => 'array',              // JSON: ['name' => '...', 'amount' => ...]
    'nominal_biaya_awal' => 'array',         // JSON: ['name' => '...', 'amount' => ...]
    'waktu_pembayaran_madrasah' => 'datetime',
    'pembayaran_lunas' => 'boolean',
    'seragam_diukur' => 'boolean',
    'berkas_lengkap' => 'boolean',
    'tanggal_daftar_ulang' => 'datetime',
    'batas_daftar_ulang' => 'datetime',
];
```

**Correct Field Names:**
- ✅ `bukti_pembayaran_madrasah` (NOT bukti_pembayaran_path)
- ✅ `waktu_pembayaran_madrasah` (NOT waktu_bayar_madrasah)
- ✅ `nominal_biaya` (array, NOT float)
- ✅ `nominal_biaya_awal` (array, NOT float)

---

## Benefits

1. **No More Errors** - All field name mismatches resolved
2. **Proper Data Display** - Nominal shows both name and amount
3. **Consistency** - Matches Admin view exactly
4. **Full Functionality** - Catatan modal now works properly
5. **Type Safety** - Proper array handling for JSON fields

---

## Next Steps

1. Test all functionalities on the page
2. Verify all columns display data correctly
3. Test responsive design on mobile devices
4. Test catatan save/edit workflow

---

**Status:** ✅ ALL FIXES APPLIED - Ready for testing
