# Update: Invoice Menggunakan Nominal Dinamis dari Pilihan Pendaftar

**Tanggal**: 31 Januari 2026
**Status**: ✅ **COMPLETE**

---

## 🎯 Summary

Berhasil mengubah invoice Daftar Ulang Madrasah dan Seragam untuk menampilkan **nominal yang dipilih oleh pendaftar** (dari field "Konfirmasi Nominal yang Dibayarkan"), bukan menampilkan semua opsi nominal yang tersedia.

---

## ❌ Problem Sebelumnya

### Invoice Madrasah & Seragam:
- Invoice menampilkan **semua opsi nominal** yang tersedia di settings
- Menggunakan loop `@foreach($details as $item)` untuk menampilkan semua item
- Tidak menampilkan nominal spesifik yang dipilih oleh pendaftar

**Contoh sebelumnya (Hardcoded)**:
```php
@foreach($details as $item)
<tr>
    <td>{{ $item['name'] ?? '-' }}</td>
    <td class="text-right">Rp {{ number_format($item['amount'] ?? 0, 0, ',', '.') }}</td>
</tr>
@php $total += ($item['amount'] ?? 0); @endphp
@endforeach
```

---

## ✅ Solution Implemented

### Menggunakan Field Database yang Tepat:

1. **DaftarUlang Model**:
   - Field: `nominal_biaya` (cast as 'array')
   - Struktur: `['name' => 'Nama Opsi', 'amount' => 500000]`
   - Ini adalah nominal yang **telah dipilih/dikonfirmasi** oleh pendaftar

2. **Seragam Model**:
   - Field: `nominal_seragam` (cast as 'array')
   - Struktur: `['name' => 'Paket Seragam', 'amount' => 800000]`
   - Ini adalah paket seragam yang **telah dipilih/dikonfirmasi** oleh pendaftar

---

## 📋 Changes Made

### 1. Invoice Daftar Ulang Madrasah ✅

**File**: `resources/views/pdf/invoice-daftar-ulang-madrasah.blade.php`

**Before** (Lines 244-251):
```php
@php $total = 0; @endphp
@foreach($details as $item)
<tr>
    <td>{{ $item['name'] ?? '-' }}</td>
    <td class="text-right">Rp {{ number_format($item['amount'] ?? 0, 0, ',', '.') }}</td>
</tr>
@php $total += ($item['amount'] ?? 0); @endphp
@endforeach
<tr class="bg-gray">
    <td style="font-weight: bold;">Total</td>
    <td class="text-right" style="font-weight: bold;">Rp {{ number_format($total, 0, ',', '.') }}</td>
</tr>
```

**After** (Lines 244-257):
```php
@php
    // Use the confirmed nominal selected by pendaftar
    $nominalBiaya = $daftarUlang->nominal_biaya ?? [];
    $keterangan = $nominalBiaya['name'] ?? 'Biaya Daftar Ulang Madrasah';
    $jumlah = $nominalBiaya['amount'] ?? 0;
@endphp
<tr>
    <td>{{ $keterangan }}</td>
    <td class="text-right">Rp {{ number_format($jumlah, 0, ',', '.') }}</td>
</tr>
<tr class="bg-gray">
    <td style="font-weight: bold;">Total</td>
    <td class="text-right" style="font-weight: bold;">Rp {{ number_format($jumlah, 0, ',', '.') }}</td>
</tr>
```

---

### 2. Invoice Daftar Ulang Seragam ✅

**File**: `resources/views/pdf/invoice-daftar-ulang-seragam.blade.php`

**Before** (Lines 271-282):
```php
@php $total = 0; @endphp
@foreach($details as $item)
<tr>
    <td>{{ $item['name'] ?? '-' }}</td>
    <td class="text-right">Rp {{ number_format($item['amount'] ?? 0, 0, ',', '.') }}</td>
</tr>
@php $total += ($item['amount'] ?? 0); @endphp
@endforeach
<tr class="bg-gray">
    <td style="font-weight: bold;">Total</td>
    <td class="text-right" style="font-weight: bold;">Rp {{ number_format($total, 0, ',', '.') }}</td>
</tr>
```

**After** (Lines 271-284):
```php
@php
    // Use the confirmed nominal selected by pendaftar
    $nominalSeragam = $seragam->nominal_seragam ?? [];
    $keterangan = $nominalSeragam['name'] ?? 'Biaya Seragam & Atribut';
    $jumlah = $nominalSeragam['amount'] ?? 0;
@endphp
<tr>
    <td>{{ $keterangan }}</td>
    <td class="text-right">Rp {{ number_format($jumlah, 0, ',', '.') }}</td>
</tr>
<tr class="bg-gray">
    <td style="font-weight: bold;">Total</td>
    <td class="text-right" style="font-weight: bold;">Rp {{ number_format($jumlah, 0, ',', '.') }}</td>
</tr>
```

---

### 3. Preview Invoice Madrasah (Admin Settings) ✅

**File**: `resources/views/livewire/admin/pengaturan-kartu.blade.php`

**Before** (Lines 872-886):
```html
<tbody>
    <tr>
        <td class="border border-gray-300 px-2 py-1">Biaya Pendaftaran</td>
        <td class="border border-gray-300 px-2 py-1 text-right">Rp 250.000</td>
    </tr>
    <tr>
        <td class="border border-gray-300 px-2 py-1">SPP Bulan Pertama</td>
        <td class="border border-gray-300 px-2 py-1 text-right">Rp 200.000</td>
    </tr>
    <tr>
        <td class="border border-gray-300 px-2 py-1">Biaya Kegiatan</td>
        <td class="border border-gray-300 px-2 py-1 text-right">Rp 150.000</td>
    </tr>
    <tr class="bg-gray-50 font-semibold">
        <td class="border border-gray-300 px-2 py-1">Total</td>
        <td class="border border-gray-300 px-2 py-1 text-right">Rp 600.000</td>
    </tr>
</tbody>
```

**After** (Lines 872-892):
```html
<tbody>
    <tr>
        <td class="border border-gray-300 px-2 py-1">
            <em class="text-blue-600">[Nominal yang dipilih pendaftar]</em>
            <br>
            <span class="text-xs text-gray-500">Contoh: Biaya Daftar Ulang Full</span>
        </td>
        <td class="border border-gray-300 px-2 py-1 text-right">
            <em class="text-blue-600">[Jumlah sesuai pilihan]</em>
            <br>
            <span class="text-xs text-gray-500">Contoh: Rp 500.000</span>
        </td>
    </tr>
    <tr class="bg-gray-50 font-semibold">
        <td class="border border-gray-300 px-2 py-1">Total</td>
        <td class="border border-gray-300 px-2 py-1 text-right">
            <em class="text-blue-600">[Total sesuai pilihan]</em>
            <br>
            <span class="text-xs text-gray-500">Contoh: Rp 500.000</span>
        </td>
    </tr>
</tbody>
<div class="mt-2 bg-blue-50 border border-blue-200 rounded px-2 py-1" style="font-size: 7px;">
    <strong>ℹ️ Info:</strong> Invoice akan menampilkan nominal yang dipilih oleh pendaftar pada field "Konfirmasi Nominal yang Dibayarkan", bukan daftar semua opsi.
</div>
```

---

### 4. Preview Invoice Seragam (Admin Settings) ✅

**File**: `resources/views/livewire/admin/pengaturan-kartu.blade.php`

**Before** (Lines 1060-1077):
```html
<tbody>
    <tr>
        <td class="border border-gray-300 px-2 py-1">Seragam Putih Abu-abu</td>
        <td class="border border-gray-300 px-2 py-1 text-right">Rp 200.000</td>
    </tr>
    <tr>
        <td class="border border-gray-300 px-2 py-1">Seragam Pramuka</td>
        <td class="border border-gray-300 px-2 py-1 text-right">Rp 180.000</td>
    </tr>
    <tr>
        <td class="border border-gray-300 px-2 py-1">Atribut Seragam</td>
        <td class="border border-gray-300 px-2 py-1 text-right">Rp 120.000</td>
    </tr>
    <tr class="bg-gray-50 font-semibold">
        <td class="border border-gray-300 px-2 py-1">Total</td>
        <td class="border border-gray-300 px-2 py-1 text-right">Rp 500.000</td>
    </tr>
</tbody>
```

**After** (Lines 1060-1084):
```html
<tbody>
    <tr>
        <td class="border border-gray-300 px-2 py-1">
            <em class="text-blue-600">[Paket seragam yang dipilih pendaftar]</em>
            <br>
            <span class="text-xs text-gray-500">Contoh: Paket Seragam Lengkap</span>
        </td>
        <td class="border border-gray-300 px-2 py-1 text-right">
            <em class="text-blue-600">[Jumlah sesuai pilihan]</em>
            <br>
            <span class="text-xs text-gray-500">Contoh: Rp 800.000</span>
        </td>
    </tr>
    <tr class="bg-gray-50 font-semibold">
        <td class="border border-gray-300 px-2 py-1">Total</td>
        <td class="border border-gray-300 px-2 py-1 text-right">
            <em class="text-blue-600">[Total sesuai pilihan]</em>
            <br>
            <span class="text-xs text-gray-500">Contoh: Rp 800.000</span>
        </td>
    </tr>
</tbody>
<div class="mt-2 bg-blue-50 border border-blue-200 rounded px-2 py-1" style="font-size: 7px;">
    <strong>ℹ️ Info:</strong> Invoice akan menampilkan paket seragam yang dipilih oleh pendaftar pada field "Konfirmasi Nominal Seragam yang Dibayarkan", bukan daftar semua opsi.
</div>
```

---

## 📊 Files Modified Summary

| No | File Path | Type | Changes |
|----|-----------|------|---------|
| 1 | `resources/views/pdf/invoice-daftar-ulang-madrasah.blade.php` | **MODIFIED** | Lines 244-257 - Menggunakan `$daftarUlang->nominal_biaya` |
| 2 | `resources/views/pdf/invoice-daftar-ulang-seragam.blade.php` | **MODIFIED** | Lines 271-284 - Menggunakan `$seragam->nominal_seragam` |
| 3 | `resources/views/livewire/admin/pengaturan-kartu.blade.php` | **MODIFIED** | Lines 872-896 - Preview Madrasah updated |
| 4 | `resources/views/livewire/admin/pengaturan-kartu.blade.php` | **MODIFIED** | Lines 1060-1084 - Preview Seragam updated |

**Total**: 4 file sections modified

---

## 🔍 Before & After Comparison

### Invoice Madrasah

| Aspek | Before | After |
|-------|--------|-------|
| **Data Source** | `$details` (semua opsi dari settings) | `$daftarUlang->nominal_biaya` (pilihan pendaftar) |
| **Jumlah Rows** | 3+ rows (semua opsi) | 1 row (hanya yang dipilih) |
| **Keterangan** | Hardcoded/dari settings | Dinamis dari pilihan pendaftar |
| **Jumlah** | Total semua opsi | Sesuai pilihan pendaftar |

### Invoice Seragam

| Aspek | Before | After |
|-------|--------|-------|
| **Data Source** | `$details` (semua paket dari settings) | `$seragam->nominal_seragam` (pilihan pendaftar) |
| **Jumlah Rows** | 3+ rows (semua paket) | 1 row (hanya yang dipilih) |
| **Keterangan** | Hardcoded/dari settings | Dinamis dari pilihan pendaftar |
| **Jumlah** | Total semua paket | Sesuai pilihan pendaftar |

---

## 💾 Database Structure

### DaftarUlang Table

```php
'nominal_biaya' => 'array',  // JSON: {"name": "Opsi Name", "amount": 500000}
```

**Contoh Data**:
```json
{
  "name": "Biaya Daftar Ulang Full",
  "amount": 600000
}
```

Atau untuk "Opsi Lainnya":
```json
{
  "name": "Opsi Lainnya",
  "amount": 450000
}
```

### Seragam Table

```php
'nominal_seragam' => 'array',  // JSON: {"name": "Paket Name", "amount": 800000}
```

**Contoh Data**:
```json
{
  "name": "Paket Seragam Lengkap",
  "amount": 850000
}
```

Atau untuk "Opsi Lainnya":
```json
{
  "name": "Opsi Lainnya",
  "amount": 700000
}
```

---

## ✅ Testing Checklist

### Invoice Madrasah:
- [ ] Pendaftar memilih nominal "Biaya Daftar Ulang Full (Rp 600.000)"
- [ ] Download invoice → menampilkan 1 row dengan keterangan dan jumlah yang benar
- [ ] Total sama dengan nominal yang dipilih
- [ ] Pendaftar memilih "Opsi Lainnya" dengan nominal custom Rp 450.000
- [ ] Download invoice → menampilkan "Opsi Lainnya - Rp 450.000"

### Invoice Seragam:
- [ ] Pendaftar memilih paket "Seragam Lengkap (Rp 850.000)"
- [ ] Download invoice → menampilkan 1 row dengan paket dan jumlah yang benar
- [ ] Total sama dengan paket yang dipilih
- [ ] Pendaftar memilih "Opsi Lainnya" dengan nominal custom Rp 700.000
- [ ] Download invoice → menampilkan "Opsi Lainnya - Rp 700.000"

### Admin Preview:
- [ ] Akses `/admin/pengaturan-kartu?activeTab=invoice-madrasah`
- [ ] Preview menampilkan placeholder blue text dengan contoh
- [ ] Info box menjelaskan bahwa invoice akan dinamis
- [ ] Akses `/admin/pengaturan-kartu?activeTab=invoice-seragam`
- [ ] Preview menampilkan placeholder blue text dengan contoh
- [ ] Info box menjelaskan bahwa invoice akan dinamis

---

## 📝 Technical Notes

### 1. Field Naming Consistency

**Database Setting Keys** (tidak berubah untuk backward compatibility):
- `daftar_ulang_madrasah_nominals` - List semua opsi nominal (untuk form selection)
- `daftar_ulang_seragam_nominals` - List semua paket seragam (untuk form selection)

**Database Record Fields** (yang digunakan di invoice):
- `nominal_biaya` - Nominal yang dipilih pendaftar untuk Madrasah
- `nominal_seragam` - Paket yang dipilih pendaftar untuk Seragam

### 2. Data Flow

**Pendaftar Side**:
1. Form menampilkan semua opsi dari settings
2. Pendaftar memilih salah satu opsi atau isi "Opsi Lainnya"
3. Pilihan disimpan ke `nominal_biaya` / `nominal_seragam` dalam format array

**Invoice Generation**:
1. Controller memanggil view PDF dengan data `$daftarUlang` / `$seragam`
2. View mengakses `$daftarUlang->nominal_biaya` / `$seragam->nominal_seragam`
3. Extract `name` dan `amount` dari array
4. Display single row dengan data tersebut

### 3. Fallback Values

Jika `nominal_biaya` atau `nominal_seragam` null/kosong:
- **Keterangan**: Default ke "Biaya Daftar Ulang Madrasah" atau "Biaya Seragam & Atribut"
- **Jumlah**: Default ke 0

---

## 🎯 Impact

**User Experience**:
- ✅ Invoice lebih jelas dan akurat
- ✅ Hanya menampilkan nominal yang benar-benar dipilih
- ✅ Tidak membingungkan dengan list semua opsi

**Technical**:
- ✅ Menggunakan data dari database yang tepat
- ✅ Invoice mencerminkan pilihan aktual pendaftar
- ✅ Lebih mudah untuk audit dan verifikasi pembayaran

**Admin**:
- ✅ Preview di settings page lebih informatif
- ✅ Jelas bahwa invoice akan menggunakan data dinamis
- ✅ Contoh membantu memahami tampilan akhir

---

## 🔄 Related Features

**Feature ini terkait dengan**:
1. **Konfirmasi Nominal Pembayaran** - Form pilihan nominal di halaman Daftar Ulang
2. **Opsi Lainnya** - Fitur untuk input nominal custom
3. **Verifikasi Pembayaran** - Admin melihat nominal yang benar di invoice

---

**Completion Date**: 31 Januari 2026
**Status**: ✅ **COMPLETE - Ready for testing**
**Next Step**: Testing invoice generation dengan berbagai pilihan nominal
