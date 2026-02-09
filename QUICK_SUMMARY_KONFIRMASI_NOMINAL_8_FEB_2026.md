# Quick Summary: Konfirmasi Nominal Madrasah - 8 Feb 2026

## Problem
Di tab Madrasah, bagian "Konfirmasi Nominal yang Dibayarkan" berbeda dengan tab Seragam. User harus pilih nominal dua kali (redundant).

## Solution
Implementasi pattern yang sama seperti Seragam tab:
1. User pilih nominal di "Pilih Nominal Biaya" (Step 1)
2. Di "Konfirmasi Nominal yang Dibayarkan" (Step 2), muncul:
   - **"Bayar Penuh"** - otomatis isi nominal yang dipilih
   - **"Opsi Lainnya"** - untuk input manual (jika enabled)

## Changes Made

**File:** `resources/views/livewire/pendaftar/daftar-ulang.blade.php`
**Lines:** 818-879

### Before
- Loop semua nominals dari setting `daftar_ulang_madrasah_payment_nominals`
- User pilih lagi dari full list
- Confusing & redundant

### After
- Conditional: `@if($nominalBiaya && $nominalBiaya != 'opsi_lainnya')`
- Show "Bayar Penuh" dengan nominal yang dipilih dari Step 1
- Show "Opsi Lainnya" untuk manual input (setting-based)
- Clear & consistent with Seragam

## Key Features

### Option 1: Bayar Penuh (Green)
```blade
<input type="radio" wire:model.live="konfirmasiNominalBiaya" value="{{ json_encode($selectedNominalForPayment) }}">
<span>Bayar Penuh</span>
<p>{{ $selectedNominalForPayment['name'] }}</p>
<span>Rp {{ number_format($selectedNominalForPayment['amount']) }}</span>
```

### Option 2: Opsi Lainnya (Yellow)
```blade
@if($madrasahEnableOpsiLainnyaPayment)
    <input type="radio" wire:model.live="konfirmasiNominalBiaya" value="opsi_lainnya">
    <span>Opsi Lainnya (Bayar Tidak Penuh)</span>
    <p>Isi nominal secara manual (untuk angsuran/potongan)</p>

    @if($konfirmasiNominalBiaya == 'opsi_lainnya')
        <input type="number" wire:model.live="konfirmasiNominalBiayaOpsiLainnya" placeholder="Contoh: 500000">
    @endif
@endif
```

## Settings

**Setting Key:** `daftar_ulang_madrasah_payment_enable_opsi_lainnya`
- `true` = Enable "Opsi Lainnya" option
- `false` = Only show "Bayar Penuh" (default)

## Flow

```
Step 1: Pilih Nominal Biaya
  └─> User pilih: "Nominal A - Rp 1.000.000"
      Display: "Nominal yang dipilih: Rp 1.000.000"

Step 2: Konfirmasi Nominal yang Dibayarkan
  ├─> [●] Bayar Penuh
  │       Nominal A
  │       Rp 1.000.000
  │
  └─> [ ] Opsi Lainnya (Bayar Tidak Penuh)
          [Manual input jika dipilih]
```

## Testing

1. Visit `/pendaftar/daftarulang?activeTab=madrasah`
2. Pilih nominal di "Pilih Nominal Biaya"
3. Check "Konfirmasi Nominal yang Dibayarkan" muncul
4. Verify "Bayar Penuh" shows selected nominal
5. Verify "Opsi Lainnya" (if enabled)

## Upload

```batch
upload_fitur_konfirmasi_nominal_madrasah_8_feb_2026.bat
```

## Consistency Achieved

| Feature | Seragam | Madrasah |
|---------|---------|----------|
| Step 1 | Pilih Paket + Opsi | Pilih Nominal Biaya |
| Step 2 | Konfirmasi Nominal | Konfirmasi Nominal |
| Bayar Penuh | ✅ Green | ✅ Emerald |
| Opsi Lainnya | ✅ Yellow | ✅ Yellow |
| Manual Input | ✅ | ✅ |

---

**Status:** ✅ Ready for deployment
**Time:** ~20 minutes
**Impact:** Better UX, consistent pattern
