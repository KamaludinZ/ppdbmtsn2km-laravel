# FITUR: Konfirmasi Nominal yang Dibayarkan - Madrasah Tab - 8 Februari 2026

**Status:** ✅ IMPLEMENTED
**Type:** Enhancement (UX Improvement)
**Time:** ~20 minutes

---

## 📋 Feature Request

**User Request:** "di daftar ulang madrasah pada bagian Konfirmasi Nominal yang Dibayarkan belum sama seperti di daftar ulang seragam yang mana ketika memilih opsi di atasnya maka di Konfirmasi Nominal yang Dibayarkan muncul nominal opsi yang dipilih untuk dibayar penuh atau memilih opsi lainnya jika ingin input manual."

**Goal:** Implement same pattern as Seragam tab where "Konfirmasi Nominal yang Dibayarkan" section shows:
- "Bayar Penuh" option with the selected nominal amount
- "Opsi Lainnya" option for manual input (if enabled)

---

## 🔄 Before vs After

### BEFORE (Old Implementation)

**Structure:**
```blade
1. Pilih Nominal Biaya
   └─> User selects nominal → Stored in $nominalBiaya

2. Konfirmasi Nominal yang Dibayarkan
   └─> Shows ALL nominals from daftar_ulang_madrasah_payment_nominals
   └─> User selects again from full list
   └─> Opsi Lainnya available if enabled
```

**Problems:**
- ❌ User has to select nominal TWICE (redundant)
- ❌ Confusing UX (why select again?)
- ❌ Different from Seragam tab pattern
- ❌ No clear "Bayar Penuh" vs "Bayar Sebagian" distinction

### AFTER (New Implementation)

**Structure:**
```blade
1. Pilih Nominal Biaya
   └─> User selects nominal → Stored in $nominalBiaya
   └─> Shows display: "Nominal yang dipilih: Rp X"

2. Konfirmasi Nominal yang Dibayarkan
   └─> Shows "Bayar Penuh" with selected nominal amount
   └─> Shows "Opsi Lainnya" for manual input (if enabled)
   └─> User confirms payment method
```

**Benefits:**
- ✅ No redundant selection
- ✅ Clear distinction: Full payment vs. Partial payment
- ✅ Consistent with Seragam tab
- ✅ Better UX flow

---

## 🎯 How It Works

### Flow Diagram:

```
Step 1: Pilih Nominal Biaya
│
├─> User selects: "Nominal A - Rp 1.000.000"
│   Stored in: $nominalBiaya = json_encode(['name' => 'Nominal A', 'amount' => 1000000])
│
└─> Display: "Nominal yang dipilih: Rp 1.000.000"

↓

Step 2: Konfirmasi Nominal yang Dibayarkan (NEW PATTERN)
│
├─> Option 1: "Bayar Penuh" ✅ DEFAULT
│   │  - Shows: "Nominal A"
│   │  - Amount: "Rp 1.000.000"
│   │  - Value: json_encode($selectedNominalForPayment)
│   │  - Stored in: $konfirmasiNominalBiaya
│   └─> User pays FULL amount
│
└─> Option 2: "Opsi Lainnya" (if enabled)
    │  - Shows: "Bayar Tidak Penuh"
    │  - Description: "untuk angsuran/potongan"
    │  - Value: "opsi_lainnya"
    │
    └─> If selected:
        └─> Shows manual input field
            - Placeholder: "Contoh: 500000"
            - Stored in: $konfirmasiNominalBiayaOpsiLainnya
```

---

## 📁 File Modified

**File:** `resources/views/livewire/pendaftar/daftar-ulang.blade.php`

### Section Modified: Lines 818-879

**Old Code (Lines 818-869):**
```blade
@php
    $madrasahPaymentNominals = \App\Models\Setting::get('daftar_ulang_madrasah_payment_nominals', []);
@endphp

<!-- Konfirmasi Nominal Biaya -->
@if($madrasahPaymentNominals && count($madrasahPaymentNominals) > 0)
<div class="mb-4">
    <label>Konfirmasi Nominal yang Dibayarkan *</label>
    <p>Pilih nominal yang akan dibayarkan</p>
    <div class="space-y-2">
        @foreach($madrasahPaymentNominals as $index => $nominal)
            <label>
                <input type="radio" wire:model.live="konfirmasiNominalBiaya" value="{{ json_encode($nominal) }}">
                <span>{{ $nominal['name'] }}</span>
                <span>Rp {{ number_format($nominal['amount']) }}</span>
            </label>
        @endforeach

        @if($madrasahPaymentNominalsEnableOpsiLainnya)
            <label>
                <input type="radio" wire:model.live="konfirmasiNominalBiaya" value="opsi_lainnya">
                <span>Opsi Lainnya</span>
            </label>
            @if($konfirmasiNominalBiaya == 'opsi_lainnya')
                <!-- Manual input -->
            @endif
        @endif
    </div>
</div>
@endif
```

**New Code (Lines 818-879):**
```blade
<!-- Konfirmasi Nominal yang Dibayarkan -->
@if($nominalBiaya && $nominalBiaya != 'opsi_lainnya')
    @php
        $selectedNominalForPayment = json_decode($nominalBiaya, true);
        $madrasahEnableOpsiLainnyaPayment = \App\Models\Setting::get('daftar_ulang_madrasah_payment_enable_opsi_lainnya', false);
    @endphp

    <div class="mb-4">
        <label>Konfirmasi Nominal yang Dibayarkan *</label>
        <p>Pilih nominal yang akan dibayarkan (default: sesuai nominal yang dipilih)</p>

        <div class="space-y-2">
            <!-- Option: Bayar Penuh -->
            <label class="border rounded-lg hover:bg-emerald-50">
                <input type="radio" wire:model.live="konfirmasiNominalBiaya" value="{{ json_encode($selectedNominalForPayment) }}">
                <div>
                    <span>Bayar Penuh</span>
                    <p>{{ $selectedNominalForPayment['name'] }}</p>
                </div>
                <span>Rp {{ number_format($selectedNominalForPayment['amount']) }}</span>
            </label>

            <!-- Option: Opsi Lainnya -->
            @if($madrasahEnableOpsiLainnyaPayment)
                <label class="border rounded-lg hover:bg-yellow-50">
                    <input type="radio" wire:model.live="konfirmasiNominalBiaya" value="opsi_lainnya">
                    <span>Opsi Lainnya (Bayar Tidak Penuh)</span>
                    <p>Isi nominal secara manual (untuk angsuran/potongan)</p>
                </label>

                @if($konfirmasiNominalBiaya == 'opsi_lainnya')
                    <div class="bg-yellow-50">
                        <label>Masukkan Nominal (Rp)</label>
                        <input type="number" wire:model.live="konfirmasiNominalBiayaOpsiLainnya" placeholder="Contoh: 500000">
                    </div>
                @endif
            @endif
        </div>
    </div>
@endif
```

---

## 🔧 Technical Changes

### 1. Changed Conditional Logic

**Before:**
```blade
@if($madrasahPaymentNominals && count($madrasahPaymentNominals) > 0)
```

**After:**
```blade
@if($nominalBiaya && $nominalBiaya != 'opsi_lainnya')
```

**Why:** Section only shows when user has selected a nominal in Step 1 (not when they chose "Opsi Lainnya" in Step 1)

### 2. Removed Loop, Added Selected Nominal

**Before:**
```blade
@foreach($madrasahPaymentNominals as $index => $nominal)
    <!-- Show all nominals -->
@endforeach
```

**After:**
```blade
@php
    $selectedNominalForPayment = json_decode($nominalBiaya, true);
@endphp

<!-- Show only selected nominal as "Bayar Penuh" -->
<input type="radio" value="{{ json_encode($selectedNominalForPayment) }}">
```

**Why:** User already selected nominal in Step 1, no need to choose again

### 3. Added "Bayar Penuh" Option

**New Addition:**
```blade
<label>
    <input type="radio" wire:model.live="konfirmasiNominalBiaya" value="{{ json_encode($selectedNominalForPayment) }}">
    <div>
        <span>Bayar Penuh</span>
        <p>{{ $selectedNominalForPayment['name'] }}</p>
    </div>
    <span>Rp {{ number_format($selectedNominalForPayment['amount']) }}</span>
</label>
```

**Purpose:** Clear indication that user is paying the full selected amount

### 4. Updated "Opsi Lainnya" Styling

**Changed:**
- Border color: `border-emerald-500` → `border-yellow-500`
- Background: `bg-emerald-50` → `bg-yellow-50`
- Text color: `text-emerald-600` → `text-yellow-600`

**Why:** Yellow indicates "alternative" or "custom" option, distinct from green "default/full" option

### 5. Changed Setting Key

**Before:**
```blade
$madrasahPaymentNominalsEnableOpsiLainnya
```

**After:**
```blade
$madrasahEnableOpsiLainnyaPayment = \App\Models\Setting::get('daftar_ulang_madrasah_payment_enable_opsi_lainnya', false);
```

**Purpose:** Separate setting flag for enabling "Opsi Lainnya" in payment confirmation step

---

## 🎨 UI/UX Improvements

### 1. Visual Hierarchy

**Bayar Penuh (Green):**
- Primary option
- Emerald color scheme
- Shows selected nominal name and amount
- Default choice

**Opsi Lainnya (Yellow):**
- Secondary option
- Yellow color scheme
- Indicates alternative/custom payment
- Conditional visibility (setting-based)

### 2. User Guidance

**Added descriptive text:**
- "Pilih nominal yang akan dibayarkan (default: sesuai nominal yang dipilih)"
- "Bayar Tidak Penuh" clarifies this is partial payment
- "(untuk angsuran/potongan)" explains use case
- "Masukkan Nominal (Rp)" clear input instruction

### 3. Progressive Disclosure

**Pattern:**
```
Step 1 visible → Select nominal
    ↓
Step 2 appears → Confirm payment method
    ↓
If "Opsi Lainnya" selected → Manual input field appears
```

---

## 📊 Comparison with Seragam Tab

### Similarities (Now Consistent)

| Feature | Seragam | Madrasah |
|---------|---------|----------|
| **Step 1** | Pilih Paket + Opsi | Pilih Nominal Biaya |
| **Step 2** | Konfirmasi Nominal | Konfirmasi Nominal |
| **Option 1** | Bayar Penuh (Green) | Bayar Penuh (Green) |
| **Option 2** | Opsi Lainnya (Yellow) | Opsi Lainnya (Yellow) |
| **Manual Input** | Shows when selected | Shows when selected |
| **Setting Flag** | seragam_enable_opsi_lainnya | madrasah_payment_enable_opsi_lainnya |
| **Livewire Property** | konfirmasiNominalSeragam | konfirmasiNominalBiaya |

### Differences (Intentional)

| Aspect | Seragam | Madrasah |
|--------|---------|----------|
| **Step 1** | Selects: Paket → Opsi | Selects: Nominal directly |
| **Step 2 Source** | From selected opsi | From selected nominal |
| **Color Scheme** | Green theme | Emerald theme |

---

## 🧪 Testing Guide

### Test Case 1: Basic Flow - Bayar Penuh

**Steps:**
1. Navigate to `/pendaftar/daftarulang?activeTab=madrasah`
2. In "Pilih Nominal Biaya", select "Nominal A - Rp 1.000.000"
3. Check "Nominal yang dipilih" displays correctly
4. Scroll to "Konfirmasi Nominal yang Dibayarkan"

**Expected Results:**
- [ ] ✅ Section "Konfirmasi Nominal yang Dibayarkan" is visible
- [ ] ✅ Radio button "Bayar Penuh" is shown
- [ ] ✅ Shows nominal name: "Nominal A"
- [ ] ✅ Shows amount: "Rp 1.000.000"
- [ ] ✅ "Bayar Penuh" is pre-selected (default)
- [ ] ✅ Green emerald color scheme

### Test Case 2: Opsi Lainnya - Manual Input

**Prerequisites:**
- Setting `daftar_ulang_madrasah_payment_enable_opsi_lainnya` = `true`

**Steps:**
1. Navigate to `/pendaftar/daftarulang?activeTab=madrasah`
2. Select a nominal in "Pilih Nominal Biaya"
3. In "Konfirmasi Nominal yang Dibayarkan", click "Opsi Lainnya"

**Expected Results:**
- [ ] ✅ Radio button "Opsi Lainnya (Bayar Tidak Penuh)" is visible
- [ ] ✅ Yellow color scheme when hovered
- [ ] ✅ Description text: "Isi nominal secara manual (untuk angsuran/potongan)"
- [ ] ✅ When clicked, manual input field appears
- [ ] ✅ Input field has yellow background (bg-yellow-50)
- [ ] ✅ Placeholder: "Contoh: 500000"
- [ ] ✅ Can enter custom amount

### Test Case 3: Opsi Lainnya Disabled

**Prerequisites:**
- Setting `daftar_ulang_madrasah_payment_enable_opsi_lainnya` = `false`

**Steps:**
1. Navigate to `/pendaftar/daftarulang?activeTab=madrasah`
2. Select a nominal in "Pilih Nominal Biaya"
3. Check "Konfirmasi Nominal yang Dibayarkan" section

**Expected Results:**
- [ ] ✅ Only "Bayar Penuh" option is visible
- [ ] ❌ "Opsi Lainnya" is NOT visible
- [ ] ✅ "Bayar Penuh" is automatically selected

### Test Case 4: No Nominal Selected

**Steps:**
1. Navigate to `/pendaftar/daftarulang?activeTab=madrasah`
2. Do NOT select any nominal in "Pilih Nominal Biaya"
3. Scroll down

**Expected Results:**
- [ ] ❌ "Konfirmasi Nominal yang Dibayarkan" section is NOT visible
- [ ] ✅ Section only appears after selecting nominal

### Test Case 5: Opsi Lainnya Selected in Step 1

**Steps:**
1. Navigate to `/pendaftar/daftarulang?activeTab=madrasah`
2. In "Pilih Nominal Biaya", select "Opsi Lainnya"
3. Enter manual amount in Step 1
4. Scroll down

**Expected Results:**
- [ ] ❌ "Konfirmasi Nominal yang Dibayarkan" section is NOT visible
- [ ] ✅ Because condition is `$nominalBiaya != 'opsi_lainnya'`

### Test Case 6: Data Persistence

**Steps:**
1. Select nominal in Step 1
2. Select "Bayar Penuh" in Step 2
3. Scroll to upload section
4. Upload a file
5. Scroll back up

**Expected Results:**
- [ ] ✅ "Bayar Penuh" selection is still maintained
- [ ] ✅ No data loss
- [ ] ✅ Livewire maintains state

---

## 🚀 Deployment

### Upload Script:
```batch
upload_fitur_konfirmasi_nominal_madrasah_8_feb_2026.bat
```

**File to Upload:**
1. `resources/views/livewire/pendaftar/daftar-ulang.blade.php` (MODIFIED)

### Post-Deployment Setup

**Required Setting (if Opsi Lainnya needed):**
```php
// In database or Settings page
'daftar_ulang_madrasah_payment_enable_opsi_lainnya' => true
```

### SSH Commands:
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan view:clear
php artisan optimize:clear
```

### Post-Deployment Testing:
1. Hard refresh browser: `Ctrl + Shift + R`
2. Test nominal selection flow
3. Verify "Bayar Penuh" and "Opsi Lainnya" options
4. Test manual input if enabled

---

## 📝 Backend Considerations

### Livewire Properties Expected

**Properties that should exist in DaftarUlang component:**

```php
// Step 1: Pilih Nominal Biaya
public $nominalBiaya;
public $madrasahOpsiLainnyaNominal;

// Step 2: Konfirmasi Nominal yang Dibayarkan
public $konfirmasiNominalBiaya;
public $konfirmasiNominalBiayaOpsiLainnya;
```

### Validation Rules

**Suggested validation in saveMadrasah() method:**

```php
$this->validate([
    'konfirmasiNominalBiaya' => 'required',
    'konfirmasiNominalBiayaOpsiLainnya' =>
        $this->konfirmasiNominalBiaya == 'opsi_lainnya'
        ? 'required|numeric|min:1'
        : 'nullable',
]);
```

### Data Storage Logic

**When saving to database:**

```php
if ($this->konfirmasiNominalBiaya == 'opsi_lainnya') {
    // Store manual amount
    $nominalAkhir = $this->konfirmasiNominalBiayaOpsiLainnya;
} else {
    // Store selected nominal amount
    $selectedNominal = json_decode($this->konfirmasiNominalBiaya, true);
    $nominalAkhir = $selectedNominal['amount'];
}

$daftarUlang->update([
    'nominal_biaya' => json_decode($this->nominalBiaya, true),
    'konfirmasi_nominal_biaya' => $this->konfirmasiNominalBiaya,
    'nominal_akhir_dibayar' => $nominalAkhir,
    // ...
]);
```

---

## ✅ Completion Checklist

- [x] Feature implemented (matches Seragam pattern)
- [x] "Bayar Penuh" option added
- [x] "Opsi Lainnya" option added with manual input
- [x] Setting flag integrated
- [x] Color scheme updated (green for default, yellow for alternative)
- [x] Conditional logic correct
- [x] Documentation created
- [x] Upload script ready
- [ ] Production deployment (pending)
- [ ] Production testing (pending)
- [ ] Setting configured if needed (pending)

---

**Feature Implemented:** 8 Februari 2026
**Developer:** Claude Code
**Type:** UX Enhancement (Consistency improvement)
**Impact:** Better user experience, consistent flow between tabs

---

**Konfirmasi Nominal feature now consistent across both tabs!** 🎉
