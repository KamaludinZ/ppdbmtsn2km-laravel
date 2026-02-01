# Fix Kolom Baju Seragam Harian - Complete

## Masalah

Kolom "Baju Seragam Harian" di `/admin/daftar-ulang?activeTab=ukuran` tidak menampilkan data ukuran yang sudah diisi oleh pendaftar.

## Root Cause

Admin view menggunakan field name yang salah.

**Field name di database (Seragam model):**
```php
'ukuran_baju_harian'  ← CORRECT
```

**Field name yang digunakan di admin view:**
```blade
$item->seragam->ukuran_baju_seragam_harian  ← WRONG (field tidak ada)
```

### Data dari Pendaftar:

**Form Pendaftar** (`resources/views/livewire/pendaftar/daftar-ulang.blade.php` - Line 1278):
```blade
<select wire:model.live="ukuran_baju_harian">
    <option value="">-- Pilih Ukuran --</option>
    @foreach($ukuranBajuHarianOptions as $option)
        <option value="{{ $option['size'] ?? '-' }}">
            {{ $option['size'] ?? '-' }} ({{ $option['measurement'] ?? '-' }})
        </option>
    @endforeach
</select>
```

**Livewire Component** (`app/Livewire/Pendaftar/DaftarUlang.php`):
```php
$dataToUpdate = [
    // ...
    'ukuran_baju_harian' => $this->ukuran_baju_harian,  // ← String: "M", "L", "XL", dll
    // ...
];
```

## Solusi

### Update Admin View - Tab Ukuran

**File**: `resources/views/livewire/admin/daftar-ulang.blade.php`

**Sebelum** (Line 1468-1477):
```blade
<!-- Baju Seragam Harian -->
<td class="px-6 py-4">
    @if($item->seragam && $item->seragam->ukuran_baju_seragam_harian)
        <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800">
            {{ $item->seragam->ukuran_baju_seragam_harian }}
        </span>
    @else
        <span class="text-xs text-gray-500">-</span>
    @endif
</td>
```

**Sesudah**:
```blade
<!-- Baju Seragam Harian -->
<td class="px-6 py-4">
    @if($item->seragam && $item->seragam->ukuran_baju_harian)
        <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800">
            {{ $item->seragam->ukuran_baju_harian }}
        </span>
    @else
        <span class="text-xs text-gray-500">-</span>
    @endif
</td>
```

**Perubahan**:
- `ukuran_baju_seragam_harian` → `ukuran_baju_harian` ✅

## Contoh Data yang Ditampilkan

### Tab Ukuran - Kolom Baju Seragam Harian:
```
S
M
L
XL
XXL
```

Atau jika pendaftar menggunakan "Opsi Lainnya":
```
M (Custom)
38-40
dll
```

## Seragam Model Structure

**File**: `app/Models/Seragam.php` (Line 12-49)

```php
protected $fillable = [
    'pendaftar_id',
    'nominal_seragam',
    'nominal_seragam_awal',
    'waktu_pembayaran_seragam',
    'paket_seragam',
    'opsi_pembayaran_seragam',
    'bank_seragam',
    'bukti_pembayaran',
    'tinggi_badan',
    'berat_badan',
    'ukuran_baju',              // General baju size
    'ukuran_celana',            // General celana size
    'lingkar_dada',
    'lingkar_pinggang',
    'panjang_lengan',
    'ukuran_sepatu',
    'ukuran_topi',
    // Detailed uniform sizes
    'ukuran_jas',
    'ukuran_baju_olahraga',
    'ukuran_celana_olahraga',
    'ukuran_baju_harian',       // ← Baju Seragam Harian field
    'ukuran_celana_rok_harian',
    'ukuran_kopyah',
    // ... other fields
];
```

## Kolom Ukuran di Tab Ukuran

Tab ukuran menampilkan 6 kolom ukuran seragam:

1. **Jas** - `ukuran_jas`
2. **Baju Olahraga** - `ukuran_baju_olahraga`
3. **Celana Olahraga** - `ukuran_celana_olahraga`
4. **Baju Seragam Harian** - `ukuran_baju_harian` ✅ (Fixed)
5. **Celana/Rok Harian** - `ukuran_celana_rok_harian`
6. **Kopyah (L)** - `ukuran_kopyah` (khusus laki-laki)

## Testing

### Test Tab Ukuran:

1. Login sebagai **pendaftar**
2. Isi form daftar ulang seragam - Tab Ukuran
3. Pilih ukuran dari dropdown "Ukuran Baju Seragam Harian"
   - Contoh: Pilih "M (90-95 cm)"
4. Simpan

5. Login sebagai **admin**
6. Buka `/admin/daftar-ulang?activeTab=ukuran`
7. ✅ Kolom "Baju Seragam Harian" harus menampilkan: **"M"** dengan badge hijau

### Verify Data di Database:

```sql
SELECT pendaftar_id, ukuran_baju_harian
FROM seragam
WHERE ukuran_baju_harian IS NOT NULL;
```

Expected result:
```
pendaftar_id | ukuran_baju_harian
-------------+-------------------
27           | M
28           | L
29           | XL
```

## Files Changed

1. **resources/views/livewire/admin/daftar-ulang.blade.php**
   - Line 1470: Fixed field name from `ukuran_baju_seragam_harian` to `ukuran_baju_harian`
   - Line 1472: Fixed display field name

---

**Tanggal**: 30 Januari 2026
**Status**: ✅ Complete
**Testing**: Silakan refresh halaman `/admin/daftar-ulang?activeTab=ukuran` untuk melihat data ukuran baju seragam harian

## Related Issues Fixed in This Session

1. ✅ Split Biaya & Pembayaran column (Madrasah & Seragam tabs)
2. ✅ Fix Bank/Metode column display
3. ✅ Fix Waktu Pembayaran timestamp
4. ✅ Fix Surat Pernyataan and Bukti Pembayaran status
5. ✅ Add download file route
6. ✅ Fix Baju Seragam Harian column (Ukuran tab)
