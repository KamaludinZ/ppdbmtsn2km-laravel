# FIX: Paket Lainnya (Manual Input) - Tab Seragam (21 Feb 2026)

## 🐛 Problem

**Sama seperti Tab Madrasah**, ketika user pilih **"Paket Lainnya"** di Tab Seragam:

1. **Display Issue**: Section "Konfirmasi Nominal yang Dibayarkan" **tidak muncul sama sekali**
2. **Save Issue**: Kalau section muncul, data **tidak tersimpan** dengan benar

**Root Cause**: Code tidak handle paket_lainnya (manual input) dengan benar.

---

## ✅ Solution Applied

### 1. **Fix Blade Template** - Conditional untuk Paket Lainnya

**File**: `resources/views/livewire/pendaftar/daftar-ulang.blade.php`

**Line 1822** (BEFORE):
```php
@if($opsiSeragam)
```

**Line 1822** (AFTER):
```php
@if($opsiSeragam || ($paketSeragam === 'paket_lainnya' && $paketSeragamCustomAmount))
```

**Result**: Section konfirmasi nominal sekarang **muncul** untuk paket_lainnya!

---

**Line 1823-1864** (BEFORE):
```php
@php
    // Langsung decode opsiSeragam (NULL kalau paket_lainnya!)
    if (is_array($opsiSeragam)) {
        $selectedOption = $opsiSeragam;
    } else {
        $selectedOption = json_decode($opsiSeragam, true);
    }

    $isMultiSelect = is_array($selectedOption) && isset($selectedOption[0]);
    // ... rest of code
@endphp
```

**Line 1823-1864** (AFTER):
```php
@php
    // Check if this is paket_lainnya (manual input)
    if ($paketSeragam === 'paket_lainnya') {
        // Build selectedOption from custom fields
        $selectedOption = [
            'name' => $paketSeragamCustomName ?? 'Paket Custom',
            'amount' => $paketSeragamCustomAmount ?? 0
        ];
        $isMultiSelect = false;
        $totalAmount = $selectedOption['amount'];
        $selectedNames = [$selectedOption['name']];
    } else {
        // Normal package: Deteksi array (checkbox) atau string (radio)
        if (is_array($opsiSeragam)) {
            $selectedOption = $opsiSeragam;
        } else {
            $selectedOption = json_decode($opsiSeragam, true);
        }

        $isMultiSelect = is_array($selectedOption) && isset($selectedOption[0]);

        if ($isMultiSelect) {
            $totalAmount = 0;
            $selectedNames = [];
            foreach ($selectedOption as $opt) {
                $totalAmount += $opt['amount'] ?? 0;
                $selectedNames[] = $opt['name'] ?? '';
            }
        } else {
            $totalAmount = $selectedOption['amount'] ?? 0;
            $selectedNames = [$selectedOption['name'] ?? ''];
        }
    }

    $seragamEnableOpsiLainnya = $this->shouldShowSeragamOpsiLainnyaKonfirmasi;
@endphp
```

**Result**:
- ✅ Paket normal → decode dari opsiSeragam (support radio & checkbox mode)
- ✅ Paket lainnya → build dari custom fields
- ✅ Display menampilkan nama dan nominal yang benar

---

### 2. **Fix Backend Validation** - Conditional Based on Paket Type

**File**: `app/Livewire/Pendaftar/DaftarUlang.php`

**Line 632-660** (BEFORE):
```php
// Custom validation untuk opsiSeragam (checkbox mode)
if (is_array($this->opsiSeragam) && count($this->opsiSeragam) === 0) {
    $this->addError('opsiSeragam', 'Silakan pilih minimal 1 opsi dari paket seragam.');
    return;
}

$this->validate([
    'paketSeragam' => 'required|string',
    'opsiSeragam' => 'required',  // Always required (fails for paket_lainnya!)
    // ...
]);
```

**Line 632-701** (AFTER):
```php
// Check if this is paket_lainnya (manual input)
$isPaketLainnya = ($this->paketSeragam === 'paket_lainnya');

if (!$isPaketLainnya) {
    // Custom validation untuk opsiSeragam (checkbox mode)
    // Only for normal packages, not for paket_lainnya
    if (is_array($this->opsiSeragam) && count($this->opsiSeragam) === 0) {
        $this->addError('opsiSeragam', 'Silakan pilih minimal 1 opsi dari paket seragam.');
        return;
    }
}

if ($isPaketLainnya) {
    // Validation for Paket Lainnya (manual input)
    $this->validate([
        'paketSeragam' => 'required|string',
        'paketSeragamCustomName' => 'required|string|max:255',
        'paketSeragamCustomAmount' => 'required|numeric|min:0',
        'konfirmasiNominalSeragam' => 'required|string',
        // ... other fields
    ], [
        'paketSeragamCustomName.required' => 'Nama paket custom harus diisi.',
        'paketSeragamCustomAmount.required' => 'Nominal biaya harus diisi.',
        // ... other messages
    ]);
} else {
    // Validation for Normal Package (dropdown selection with opsiSeragam)
    $this->validate([
        'paketSeragam' => 'required|string',
        'opsiSeragam' => 'required',  // Only required for normal packages
        'konfirmasiNominalSeragam' => 'required|string',
        // ... other fields
    ]);
}
```

**Result**:
- ✅ Paket normal → validate opsiSeragam (required)
- ✅ Paket lainnya → validate custom name & amount (required)
- ✅ Validation tidak lagi gagal untuk paket_lainnya

---

### 3. **Fix Backend Parsing** - Build selectedOption dari Custom Fields

**File**: `app/Livewire/Pendaftar/DaftarUlang.php`

**Line 712-720** (BEFORE):
```php
// Parse selected option from package
if (is_array($this->opsiSeragam)) {
    $selectedOption = $this->opsiSeragam;
} else {
    $selectedOption = json_decode($this->opsiSeragam, true);
}
```

**Line 712-728** (AFTER):
```php
// Parse selected option from package
if ($isPaketLainnya) {
    // Paket Lainnya: Build from custom fields
    $selectedOption = [
        'name' => $this->paketSeragamCustomName,
        'amount' => $this->paketSeragamCustomAmount
    ];
} else {
    // Normal package: Handle both string (radio mode) and array (checkbox mode)
    if (is_array($this->opsiSeragam)) {
        $selectedOption = $this->opsiSeragam;
    } else {
        $selectedOption = json_decode($this->opsiSeragam, true);
    }
}
```

**Result**:
- ✅ Paket normal → parse dari opsiSeragam
- ✅ Paket lainnya → build dari custom fields
- ✅ Data tersimpan dengan benar

---

### 4. **Fix opsiSeragamToStore Encoding**

**File**: `app/Livewire/Pendaftar/DaftarUlang.php`

**Line 740-741** (BEFORE):
```php
// Encode opsiSeragam as JSON untuk disimpan
$opsiSeragamToStore = is_array($this->opsiSeragam) ? json_encode($this->opsiSeragam) : $this->opsiSeragam;
```

**Line 740-747** (AFTER):
```php
// Encode opsiSeragam as JSON untuk disimpan
if ($isPaketLainnya) {
    // For paket_lainnya, encode from selectedOption (built from custom fields)
    $opsiSeragamToStore = json_encode($selectedOption);
} else {
    // For normal packages, encode from opsiSeragam
    $opsiSeragamToStore = is_array($this->opsiSeragam) ? json_encode($this->opsiSeragam) : $this->opsiSeragam;
}
```

**Result**:
- ✅ Paket lainnya → encode dari selectedOption (bukan NULL)
- ✅ Data opsi tersimpan dengan benar di database

---

## 📁 Files Changed

1. **resources/views/livewire/pendaftar/daftar-ulang.blade.php** (MODIFIED)
   - Line 1822: Updated condition to include paket_lainnya
   - Line 1823-1864: Build selectedOption dari custom fields kalau paket_lainnya

2. **app/Livewire/Pendaftar/DaftarUlang.php** (MODIFIED)
   - Line 632-701: Conditional validation (different for paket_lainnya)
   - Line 712-728: Conditional parsing (build from custom fields)
   - Line 740-747: Conditional encoding (from selectedOption)

3. **FIX_PAKET_LAINNYA_TAB_SERAGAM_21_FEB_2026.md** (NEW)
   - This documentation file

---

## 🧪 Testing Instructions

### Test Case: Paket Lainnya Tab Seragam

**Step 1**: Akses Tab Seragam
```
URL: /pendaftar/daftarulang?activeTab=seragam
```

**Step 2**: Pilih "Paket Lainnya"
1. Dropdown "Pilih Paket Seragam" → **"Paket Lainnya (Input Manual)"**
2. Fields akan muncul:
   - Nama Paket Custom
   - Nominal Biaya (Rp)

**Step 3**: Isi Custom Fields
1. **Nama Paket Custom**: "Paket Seragam Khusus"
2. **Nominal Biaya (Rp)**: 2000000

**Step 4**: Verify Display
- Section "Konfirmasi Nominal yang Dibayarkan" **harus muncul**
- Opsi "Bayar Penuh" harus menampilkan:
  ```
  ✅ Bayar Penuh
     Paket Seragam Khusus
     Rp 2.000.000
  ```

**Step 5**: Lengkapi dan Submit
1. Pilih "Bayar Penuh"
2. Pilih Opsi Pembayaran
3. Pilih Bank
4. Upload Bukti Pembayaran
5. Isi Nama Pengirim
6. Klik "Simpan dan Lanjutkan"

**Step 6**: Verify Database
```bash
# Check seragam table
php artisan tinker
>>> $seragam = \App\Models\Seragam::where('pendaftar_id', 6)->first();
>>> $seragam->paket_seragam
>>> $seragam->opsi_seragam
>>> $seragam->nominal_seragam_awal
>>> $seragam->nominal_seragam
```

**Expected**:
- `paket_seragam`: "paket_lainnya"
- `opsi_seragam`: `{"name":"Paket Seragam Khusus","amount":2000000}`
- `nominal_seragam_awal`: `{"name":"Paket Seragam Khusus","amount":2000000}`
- `nominal_seragam`: `{"name":"Paket Seragam Khusus","amount":2000000}`

---

## ✅ Success Criteria

- [x] Kondisi @if include paket_lainnya
- [x] Build selectedOption dari custom fields kalau paket_lainnya
- [x] Section konfirmasi nominal muncul untuk paket_lainnya
- [x] Display "Bayar Penuh" menampilkan nama dan nominal custom
- [x] Validation conditional based on paket type
- [x] Custom fields validated untuk paket_lainnya
- [x] Parsing build dari custom fields (bukan opsiSeragam NULL)
- [x] Encoding dari selectedOption (bukan opsiSeragam NULL)
- [x] Data tersimpan dengan nama paket custom yang benar
- [x] Nominal tersimpan dengan nilai yang benar
- [x] No error saat submit paket_lainnya

---

## 📝 Notes

- Fix ini **identik dengan Tab Madrasah** untuk konsistensi
- Tab Seragam juga support **checkbox mode** untuk normal packages
- Paket Lainnya di Tab Seragam sekarang **fully functional**
- Validation message lebih **specific** untuk paket_lainnya
- Code sekarang handle 3 mode: radio, checkbox, dan paket_lainnya

---

## 🔗 Related Fixes

- `FIX_PAKET_LAINNYA_TAB_MADRASAH_21_FEB_2026.md` - Same issue, same solution pattern
- Tab Madrasah dan Tab Seragam sekarang **konsisten** dalam handling paket_lainnya

---

**Author**: Claude Code
**Date**: 21 Februari 2026
**Related Files**: daftar-ulang.blade.php, DaftarUlang.php
**Issue Type**: Feature Bug, Display Issue, Save Issue, Conditional Logic
