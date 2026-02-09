# Fix: Warning Perbedaan Ukuran di Tab Seragam - 8 Februari 2026

**Status:** ✅ SELESAI

---

## 🎯 Tujuan

Menampilkan warning perbedaan ukuran dari tab ukuran untuk muncul juga di tab seragam.

**User Request:**
> "tetap munculkan warning yang ada di ukuran untuk muncul juga di atas step 1 di /pendaftar/daftarulang?activeTab=seragam hanya jika memang ada perbedaan ukuran, tapi jika sama di baju seragam harian dan celana/rok harian maka tidak muncul warning itu"

---

## 📋 Apa yang Berubah?

### Warning yang Ditambahkan:

**🟠 Orange Warning Box (Perbedaan Ukuran)**
- **Muncul HANYA jika:** Ukuran baju seragam harian ≠ Ukuran celana/rok harian
- **TIDAK muncul jika:** Ukuran baju = Ukuran celana (sama)
- **Lokasi:** Di atas "Step 1: Pilih Paket Seragam & Nominal"

---

## 🔍 Logika Warning

### Kapan Warning Muncul?

```php
// Warning HANYA muncul jika:
ukuran_baju_harian != ukuran_celana_rok_harian

// Contoh muncul:
- Baju: "M" dan Celana: "L" → ✅ WARNING MUNCUL
- Baju: "XL" dan Celana: "L" → ✅ WARNING MUNCUL

// Contoh TIDAK muncul:
- Baju: "L" dan Celana: "L" → ❌ TIDAK ADA WARNING
- Baju: "M" dan Celana: "M" → ❌ TIDAK ADA WARNING
- Belum pilih ukuran → ❌ TIDAK ADA WARNING
```

### Penentuan Ukuran Terbesar

Warning akan menentukan ukuran mana yang lebih besar berdasarkan urutan standar:
```
XS < S < M < L < XL < XXL < XXXL
```

**Untuk pembayaran:** User harus pilih paket seragam sesuai **ukuran terbesar**.

---

## 🎨 Tampilan Warning

### Visual Design

```
┌────────────────────────────────────────────────────────────┐
│ ⚠️ ⚠️ Perhatian: Perbedaan Ukuran Seragam Harian          │
│    Terdeteksi!                                             │
│                                                             │
│ Anda memilih ukuran yang berbeda untuk baju seragam       │
│ harian (M) dan celana/rok harian (L).                     │
│                                                             │
│ ┌──────────────────────────────────────────────────────┐  │
│ │ 📌 Informasi Penting untuk Pembayaran:              │  │
│ │                                                       │  │
│ │ Saat melakukan pembayaran daftar ulang seragam,     │  │
│ │ Anda diharuskan memilih dan membayar sesuai dengan  │  │
│ │ ukuran terbesar dari pilihan ukuran seragam harian  │  │
│ │ Anda.                                                │  │
│ │                                                       │  │
│ │ 💰 Ukuran untuk pembayaran: L                        │  │
│ └──────────────────────────────────────────────────────┘  │
│                                                             │
│ Pastikan Anda memilih paket seragam dan nominal           │
│ pembayaran yang sesuai dengan ukuran terbesar tersebut    │
│ pada saat mengisi form pembayaran di bawah ini.           │
└────────────────────────────────────────────────────────────┘
```

**Warna:**
- Background: Orange-50
- Border: Orange-500 (left border 4px)
- Icon: Orange warning triangle
- Text: Orange-800/900

---

## 💻 Implementasi Teknis

### 1. PHP Logic (Lines 1330-1354)

**File:** `resources/views/livewire/pendaftar/daftar-ulang.blade.php`

```blade
{{-- PHP Logic for Warning Perbedaan Ukuran --}}
@php
    // Get ukuran data from seragam table
    $actualUkuranBajuHarian = $seragam->ukuran_baju_harian ?? '';
    $actualUkuranCelanaHarian = $seragam->ukuran_celana_rok_harian ?? '';

    // Check if there's a difference
    $hasSizeDifference = false;
    $largerSize = '';

    if ($actualUkuranBajuHarian && $actualUkuranCelanaHarian &&
        $actualUkuranBajuHarian != $actualUkuranCelanaHarian) {

        $hasSizeDifference = true;

        // Determine which size is larger
        $sizeOrder = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
        $bajuIndex = array_search($actualUkuranBajuHarian, $sizeOrder);
        $celanaIndex = array_search($actualUkuranCelanaHarian, $sizeOrder);

        if ($bajuIndex !== false && $celanaIndex !== false) {
            $largerSize = $bajuIndex > $celanaIndex ?
                          $actualUkuranBajuHarian : $actualUkuranCelanaHarian;
        } else {
            $largerSize = 'ukuran terbesar antara ' .
                          $actualUkuranBajuHarian . ' dan ' . $actualUkuranCelanaHarian;
        }
    }
@endphp
```

### 2. Warning Box HTML (Lines 1356-1390)

```blade
@if($hasSizeDifference)
<div class="bg-orange-50 border-l-4 border-orange-500 rounded-lg p-5 shadow-md mb-6">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <svg class="w-6 h-6 text-orange-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3 flex-1">
            <h3 class="text-base font-bold text-orange-900 mb-2">
                ⚠️ Perhatian: Perbedaan Ukuran Seragam Harian Terdeteksi!
            </h3>
            <div class="text-sm text-orange-800 space-y-2">
                <p class="font-semibold">
                    Anda memilih ukuran yang berbeda untuk baju seragam harian
                    (<span class="font-bold text-orange-900">{{ $actualUkuranBajuHarian }}</span>)
                    dan celana/rok harian
                    (<span class="font-bold text-orange-900">{{ $actualUkuranCelanaHarian }}</span>).
                </p>
                <div class="bg-orange-100 border border-orange-300 rounded-md p-3 mt-3">
                    <p class="font-semibold text-orange-900 mb-2">📌 Informasi Penting untuk Pembayaran:</p>
                    <p class="leading-relaxed">
                        Saat melakukan pembayaran daftar ulang seragam,
                        <span class="font-bold underline">Anda diharuskan memilih dan membayar sesuai dengan ukuran terbesar</span>
                        dari pilihan ukuran seragam harian Anda.
                    </p>
                    <p class="mt-2 font-bold text-orange-900">
                        💰 Ukuran untuk pembayaran: <span class="text-lg">{{ $largerSize }}</span>
                    </p>
                </div>
                <p class="text-xs mt-2 italic">
                    Pastikan Anda memilih paket seragam dan nominal pembayaran yang sesuai dengan ukuran terbesar tersebut pada saat mengisi form pembayaran di bawah ini.
                </p>
            </div>
        </div>
    </div>
</div>
@endif
```

---

## 📁 Files Modified

### 1. resources/views/livewire/pendaftar/daftar-ulang.blade.php

**Changes:**
- Lines 1203-1206: Undo variable `$isSeragamWaiting` (tidak diperlukan)
- Lines 1330-1354: **NEW** - PHP logic untuk deteksi perbedaan ukuran
- Lines 1356-1390: **NEW** - Warning box HTML
- Removed: Lines 1332-1358 (warning approved/waiting yang salah)

**Total Lines Added:** ~65 lines (PHP logic + HTML)

---

## 🧪 Cara Test

### Skenario 1: Ukuran BERBEDA → Warning MUNCUL ✅

#### Step 1: Set Ukuran Berbeda di Database
```sql
-- GANTI 'your-email@example.com' dengan email Anda
UPDATE seragam
SET
    ukuran_baju_harian = 'M',
    ukuran_celana_rok_harian = 'L'
WHERE pendaftar_id IN (
    SELECT p.id FROM pendaftar p
    JOIN users u ON p.user_id = u.id
    WHERE u.email = 'your-email@example.com'
);
```

#### Step 2: Buka Browser
```
http://localhost:8000/pendaftar/daftarulang?activeTab=seragam
```

Tekan **Ctrl + Shift + R** (hard reload)

#### Step 3: Verify ✅
**HARUS MUNCUL:**
- 🟠 **Orange warning box** di atas Step 1
- ⚠️ Icon warning triangle orange
- 📝 Text: "Perhatian: Perbedaan Ukuran Seragam Harian Terdeteksi!"
- 📝 Menampilkan: Baju (M) dan Celana (L)
- 💰 Ukuran untuk pembayaran: **L** (yang lebih besar)

---

### Skenario 2: Ukuran SAMA → Warning TIDAK MUNCUL ❌

#### Step 1: Set Ukuran Sama di Database
```sql
-- GANTI 'your-email@example.com' dengan email Anda
UPDATE seragam
SET
    ukuran_baju_harian = 'L',
    ukuran_celana_rok_harian = 'L'
WHERE pendaftar_id IN (
    SELECT p.id FROM pendaftar p
    JOIN users u ON p.user_id = u.id
    WHERE u.email = 'your-email@example.com'
);
```

#### Step 2: Reload Browser
Tekan **Ctrl + Shift + R**

#### Step 3: Verify ❌
**HARUS:**
- ❌ **TIDAK ADA** orange warning box
- ✅ Form langsung mulai dari "Step 1: Pilih Paket Seragam & Nominal"
- ✅ Tidak ada pesan tentang perbedaan ukuran

---

### Skenario 3: Belum Ada Ukuran → Warning TIDAK MUNCUL ❌

#### Step 1: Hapus Data Ukuran
```sql
UPDATE seragam
SET
    ukuran_baju_harian = NULL,
    ukuran_celana_rok_harian = NULL
WHERE pendaftar_id IN (
    SELECT p.id FROM pendaftar p
    JOIN users u ON p.user_id = u.id
    WHERE u.email = 'your-email@example.com'
);
```

#### Step 2: Reload Browser

#### Step 3: Verify ❌
- ❌ **TIDAK ADA** warning
- ✅ Form normal

---

## 📊 Contoh Kasus

### Kasus 1: Baju XL, Celana L
- **Warning:** ✅ YA
- **Ukuran pembayaran:** **XL** (lebih besar)

### Kasus 2: Baju M, Celana M
- **Warning:** ❌ TIDAK
- **Keterangan:** Ukuran sama, tidak perlu warning

### Kasus 3: Baju S, Celana XL
- **Warning:** ✅ YA
- **Ukuran pembayaran:** **XL** (lebih besar)

### Kasus 4: Baju "Custom 42", Celana "Custom 44"
- **Warning:** ✅ YA
- **Ukuran pembayaran:** "ukuran terbesar antara Custom 42 dan Custom 44"
- **Keterangan:** Ukuran custom tidak ada di array standar

---

## ✅ Benefits

1. **Informasi Jelas:** User langsung tahu ada perbedaan ukuran
2. **Panduan Pembayaran:** User tahu harus bayar ukuran yang mana
3. **Konsisten:** Same warning dengan tab ukuran
4. **Conditional:** Hanya muncul jika memang ada perbedaan
5. **Visual Jelas:** Orange warning box menonjol

---

## 🔍 Troubleshooting

### Warning Tidak Muncul Padahal Ukuran Berbeda

**Solusi 1: Clear Cache**
```bash
php artisan view:clear
php artisan cache:clear
```

**Solusi 2: Verify Data di Database**
```sql
SELECT
    ukuran_baju_harian,
    ukuran_celana_rok_harian
FROM seragam
WHERE pendaftar_id IN (
    SELECT p.id FROM pendaftar p
    JOIN users u ON p.user_id = u.id
    WHERE u.email = 'your-email@example.com'
);
```

**Solusi 3: Hard Reload Browser**
- Ctrl + Shift + R (Windows/Linux)
- Cmd + Shift + R (Mac)

---

## 📝 Summary

### What Changed:
- ✅ Tambah PHP logic untuk deteksi perbedaan ukuran
- ✅ Tambah orange warning box di atas Step 1
- ✅ Warning hanya muncul jika baju ≠ celana
- ❌ Removed: Warning approved/waiting (yang salah)

### Impact:
- User mendapat informasi penting sebelum bayar
- Mencegah kesalahan pilih paket seragam
- Konsisten dengan tab ukuran
- No backend changes (view-only)

---

**Perubahan oleh:** Claude Code
**Tanggal:** 8 Februari 2026
**File Modified:** 1 file (pendaftar/daftar-ulang.blade.php)
**Lines Changed:** ~65 lines (PHP logic + warning box)
**Feature:** Warning perbedaan ukuran seragam harian
