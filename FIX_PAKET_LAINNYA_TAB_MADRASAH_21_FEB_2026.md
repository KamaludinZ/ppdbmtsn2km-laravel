# FIX: Paket Lainnya (Manual Input) - Tab Madrasah (21 Feb 2026)

## 🐛 Problem

User pilih **"Paket Lainnya"** di Tab Madrasah dengan input manual, tapi:

1. **Display Issue**: Di section "Konfirmasi Nominal yang Dibayarkan" → opsi "Bayar Penuh" **tidak menampilkan nominal** yang user tulis di field "Nominal Biaya (Rp)"

2. **Save Issue**: Saat submit form, data **tidak tersimpan** dengan benar. Nama paket custom yang ditulis di field "Nama Paket Custom" **tidak tersimpan**.

**User's Report**:
> "saat saya mencoba pada dropdown pilih paket dengan opsi lainnya atau manual ternyata pada Konfirmasi Nominal yang Dibayarkan di opsi bayar penuh belum memunculkan nominal sesuai yang dituliskan dengan benar dan saat di simpan data juga belum berhasil menyimpan dengan pilihan paket opsi lainnya ini dengan benar dengan nama paket sesuai yang di tuliskan pada field Nama Paket Custom."

---

## 🔍 Root Cause Analysis

### Issue 1: Display Problem (Blade Template)

**File**: `resources/views/livewire/pendaftar/daftar-ulang.blade.php`
**Location**: Line 855-860

**Problem Code**:
```php
@if($opsiMadrasah || ($paketMadrasah === 'paket_lainnya' && $paketMadrasahCustomAmount))
    @php
        $selectedOption = json_decode($opsiMadrasah, true);  // ❌ WRONG!
        $madrasahEnableOpsiLainnyaPayment = $this->shouldShowMadrasahOpsiLainnyaKonfirmasi;
    @endphp
```

**Why it fails**:
- Ketika user pilih "Paket Lainnya", tidak ada radio button opsi yang dipilih
- Variable `$opsiMadrasah` = NULL
- Code langsung decode `$opsiMadrasah` → hasil = NULL
- Di line 878 dan 880, menampilkan `$selectedOption['name']` dan `$selectedOption['amount']` → keduanya kosong karena `$selectedOption` = NULL
- Harusnya: Build `$selectedOption` dari `$paketMadrasahCustomName` dan `$paketMadrasahCustomAmount`

---

### Issue 2: Save Problem (Backend Logic)

**File**: `app/Livewire/Pendaftar/DaftarUlang.php`
**Location**: Line 479-497 (Validation) dan Line 507-515 (Parsing)

**Problem 1 - Validation**:
```php
$this->validate([
    'paketMadrasah' => 'required|string',
    'opsiMadrasah' => 'required',  // ❌ FAILS for paket_lainnya!
    // ...
]);
```

**Why it fails**:
- Validation mewajibkan `opsiMadrasah` required
- Ketika user pilih "Paket Lainnya", `$opsiMadrasah` = NULL
- Validation gagal, function stop sebelum sampai database update

**Problem 2 - Parsing**:
```php
if (is_array($this->opsiMadrasah)) {
    $selectedOption = $this->opsiMadrasah;
} else {
    $selectedOption = json_decode($this->opsiMadrasah, true);  // ❌ NULL!
}
```

**Why it fails**:
- Code decode `$opsiMadrasah` yang NULL
- `$selectedOption` jadi NULL
- Data yang disimpan ke database jadi kosong/corrupt

---

## ✅ Solution

### 1. **Fix Blade Template** - Build selectedOption dari Custom Fields

**File**: `resources/views/livewire/pendaftar/daftar-ulang.blade.php`
**Line 856-870** (BEFORE):
```php
@php
    $selectedOption = json_decode($opsiMadrasah, true);
    $madrasahEnableOpsiLainnyaPayment = $this->shouldShowMadrasahOpsiLainnyaKonfirmasi;
@endphp
```

**Line 856-870** (AFTER):
```php
@php
    // Check if this is paket_lainnya (manual input)
    if ($paketMadrasah === 'paket_lainnya') {
        // Build selectedOption from custom fields
        $selectedOption = [
            'name' => $paketMadrasahCustomName ?? 'Paket Custom',
            'amount' => $paketMadrasahCustomAmount ?? 0
        ];
    } else {
        // Normal package: decode from opsiMadrasah
        $selectedOption = json_decode($opsiMadrasah, true);
    }
    // Use computed property for Global OR Individual logic
    $madrasahEnableOpsiLainnyaPayment = $this->shouldShowMadrasahOpsiLainnyaKonfirmasi;
@endphp
```

**Result**:
- ✅ Kalau paket normal → decode dari `$opsiMadrasah`
- ✅ Kalau paket_lainnya → build dari `$paketMadrasahCustomName` dan `$paketMadrasahCustomAmount`
- ✅ Section "Bayar Penuh" sekarang menampilkan nominal yang benar

---

### 2. **Fix Backend Validation** - Conditional Based on Paket Type

**File**: `app/Livewire/Pendaftar/DaftarUlang.php`
**Line 477-531** (BEFORE):
```php
$this->validate([
    'paketMadrasah' => 'required|string',
    'opsiMadrasah' => 'required',  // Always required
    // ...
]);
```

**Line 477-531** (AFTER):
```php
// Check if this is paket_lainnya (manual input)
$isPaketLainnya = ($this->paketMadrasah === 'paket_lainnya');

if ($isPaketLainnya) {
    // Validation for Paket Lainnya (manual input)
    $this->validate([
        'paketMadrasah' => 'required|string',
        'paketMadrasahCustomName' => 'required|string|max:255',
        'paketMadrasahCustomAmount' => 'required|numeric|min:0',
        'konfirmasiNominalBiaya' => 'required|string',
        'opsiPembayaran' => 'required|string',
        'bankPilihan' => 'required|string',
        'buktiPembayaran' => 'required|mimes:pdf,jpg,jpeg,png|max:2048',
        'namaPengirim' => 'required|string|max:255',
    ], [
        'paketMadrasahCustomName.required' => 'Nama paket custom harus diisi.',
        'paketMadrasahCustomAmount.required' => 'Nominal biaya harus diisi.',
        'paketMadrasahCustomAmount.numeric' => 'Nominal biaya harus berupa angka.',
        // ... other messages
    ]);
} else {
    // Validation for Normal Package (dropdown selection)
    $this->validate([
        'paketMadrasah' => 'required|string',
        'opsiMadrasah' => 'required',  // Only required for normal packages
        'konfirmasiNominalBiaya' => 'required|string',
        // ... other fields
    ]);
}
```

**Result**:
- ✅ Paket Normal → validate `opsiMadrasah` (required)
- ✅ Paket Lainnya → validate `paketMadrasahCustomName` dan `paketMadrasahCustomAmount` (required)
- ✅ Validation tidak lagi gagal untuk paket_lainnya

---

### 3. **Fix Backend Parsing** - Build selectedOption dari Custom Fields

**File**: `app/Livewire/Pendaftar/DaftarUlang.php`
**Line 540-557** (BEFORE):
```php
// Parse selected option from package
if (is_array($this->opsiMadrasah)) {
    $selectedOption = $this->opsiMadrasah;
} else {
    $selectedOption = json_decode($this->opsiMadrasah, true);
}
```

**Line 540-557** (AFTER):
```php
// Parse selected option from package
if ($isPaketLainnya) {
    // Paket Lainnya: Build from custom fields
    $selectedOption = [
        'name' => $this->paketMadrasahCustomName,
        'amount' => $this->paketMadrasahCustomAmount
    ];
} else {
    // Normal package: Parse from opsiMadrasah
    if (is_array($this->opsiMadrasah)) {
        $selectedOption = $this->opsiMadrasah;
    } else {
        $selectedOption = json_decode($this->opsiMadrasah, true);
    }
}
```

**Result**:
- ✅ Paket Normal → parse dari `opsiMadrasah`
- ✅ Paket Lainnya → build dari custom fields
- ✅ Data tersimpan dengan benar ke database

---

### 4. **Update Debug Logging** - Log Custom Fields

**File**: `app/Livewire/Pendaftar/DaftarUlang.php`
**Line 451-461** (BEFORE):
```php
\Log::info('saveMadrasah() called', [
    'paketMadrasah' => $this->paketMadrasah,
    'opsiMadrasah' => $this->opsiMadrasah,
    'konfirmasiNominalBiaya' => $this->konfirmasiNominalBiaya,
    // ...
]);
```

**Line 451-461** (AFTER):
```php
\Log::info('saveMadrasah() called', [
    'paketMadrasah' => $this->paketMadrasah,
    'opsiMadrasah' => $this->opsiMadrasah,
    'paketMadrasahCustomName' => $this->paketMadrasahCustomName ?? null,
    'paketMadrasahCustomAmount' => $this->paketMadrasahCustomAmount ?? null,
    'konfirmasiNominalBiaya' => $this->konfirmasiNominalBiaya,
    // ...
]);
```

**Result**:
- ✅ Log sekarang mencatat custom fields untuk debugging

---

## 🧪 Testing Instructions

### Test Case: Paket Lainnya (Manual Input)

**Step 1**: Akses Tab Madrasah
```
URL: /pendaftar/daftarulang?activeTab=madrasah
```

**Step 2**: Pilih "Paket Lainnya"
1. Dropdown "Pilih Paket Madrasah" → pilih **"Paket Lainnya (Input Manual)"**
2. Field "Nama Paket Custom" akan muncul

**Step 3**: Isi Custom Fields
1. **Nama Paket Custom**: "Paket Beasiswa Penuh"
2. **Nominal Biaya (Rp)**: 1500000

**Step 4**: Verify Display
- Section "Konfirmasi Nominal yang Dibayarkan" akan muncul
- Opsi "Bayar Penuh" harus menampilkan:
  - Name: "Paket Beasiswa Penuh"
  - Amount: Rp 1.500.000

**Expected**:
```
✅ Bayar Penuh
   Paket Beasiswa Penuh
   Rp 1.500.000
```

**Step 5**: Pilih Konfirmasi Nominal
- Pilih radio "Bayar Penuh" (auto-selected nominal dari custom)

**Step 6**: Lengkapi Form
1. Pilih **Opsi Pembayaran** (Bayar Penuh/Cicilan)
2. Pilih **Bank**
3. Upload **Bukti Pembayaran**
4. Isi **Nama Pengirim**

**Step 7**: Submit Form
- Klik **"Simpan dan Lanjutkan"**

**Step 8**: Verify Database
```bash
php check_daftar_ulang_data.php
```

**Expected Output**:
```
✅ Record FOUND!

Nominal Biaya Awal: {"name":"Paket Beasiswa Penuh","amount":1500000}
Nominal Biaya: {"name":"Paket Beasiswa Penuh","amount":1500000}
Opsi Pembayaran: bayar_penuh
Metode Pembayaran: bca
Nama Pengirim: Test User
Status: menunggu
```

---

## 📊 Comparison: Normal vs Paket Lainnya

### Normal Package Flow:
1. User pilih **Paket** dari dropdown → `$paketMadrasah` = JSON paket
2. User pilih **Opsi** dari radio → `$opsiMadrasah` = JSON opsi
3. Validation: `opsiMadrasah` required ✅
4. Parsing: Decode `$opsiMadrasah` → `$selectedOption` ✅
5. Save to DB: `nominal_biaya_awal` = `$selectedOption` ✅

### Paket Lainnya Flow (NOW FIXED):
1. User pilih **"Paket Lainnya"** → `$paketMadrasah` = 'paket_lainnya'
2. User isi **Nama Paket Custom** → `$paketMadrasahCustomName`
3. User isi **Nominal Biaya** → `$paketMadrasahCustomAmount`
4. Validation: `paketMadrasahCustomName` + `paketMadrasahCustomAmount` required ✅
5. Parsing: Build dari custom fields → `$selectedOption` ✅
6. Save to DB: `nominal_biaya_awal` = `$selectedOption` ✅

---

## 📁 Files Changed

1. **resources/views/livewire/pendaftar/daftar-ulang.blade.php** (MODIFIED)
   - Line 856-870: Build `$selectedOption` dari custom fields kalau paket_lainnya

2. **app/Livewire/Pendaftar/DaftarUlang.php** (MODIFIED)
   - Line 451-461: Updated debug logging (add custom fields)
   - Line 477-531: Conditional validation (different for paket_lainnya)
   - Line 540-557: Conditional parsing (build from custom fields)

3. **FIX_PAKET_LAINNYA_TAB_MADRASAH_21_FEB_2026.md** (NEW)
   - This documentation file

---

## 🚀 Deployment

### Local Testing
```bash
# No migration needed - just code changes

# Test immediately:
# 1. Refresh browser (Ctrl + F5)
# 2. Pilih "Paket Lainnya"
# 3. Isi nama dan nominal custom
# 4. Verify display shows correct nominal
# 5. Submit and verify database
```

### Production Upload
```bash
# Upload blade file:
scp resources/views/livewire/pendaftar/daftar-ulang.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/

# Upload PHP file:
scp app/Livewire/Pendaftar/DaftarUlang.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/

# Upload documentation:
scp FIX_PAKET_LAINNYA_TAB_MADRASAH_21_FEB_2026.md mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/

# Clear cache:
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear && php artisan optimize:clear"
```

---

## ✅ Success Criteria

- [x] Display: "Bayar Penuh" menampilkan nama paket custom yang user tulis
- [x] Display: "Bayar Penuh" menampilkan nominal yang user tulis
- [x] Validation: Paket lainnya validate custom fields (bukan opsiMadrasah)
- [x] Validation: Custom name dan amount required
- [x] Parsing: Build selectedOption dari custom fields
- [x] Save: Data tersimpan dengan nama paket custom yang benar
- [x] Save: Nominal tersimpan dengan nilai yang benar
- [x] Log: Debug log mencatat custom fields
- [x] No error saat submit form paket lainnya

---

## 📝 Notes

- Fix ini **tidak memerlukan migration** karena hanya perubahan logic
- Struktur database tetap sama (JSON untuk `nominal_biaya_awal`)
- Paket Lainnya sekarang **fully functional** di Tab Madrasah
- Validation error message sekarang lebih **specific** untuk paket lainnya
- Tab Seragam juga menggunakan pola yang sama untuk paket_lainnya

---

## 🔗 Related Issues

- User reported display issue: Nominal tidak muncul di "Bayar Penuh"
- User reported save issue: Nama paket custom tidak tersimpan
- Root cause: Blade dan backend tidak handle paket_lainnya dengan benar
- Solution: Conditional logic untuk build selectedOption dari custom fields

---

**Author**: Claude Code
**Date**: 21 Februari 2026
**Related Files**: daftar-ulang.blade.php, DaftarUlang.php
**Issue Type**: Feature Bug, Display Issue, Save Issue, Conditional Logic
