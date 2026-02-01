# Fix Kolom Bank/Metode - Complete

## Masalah

Kolom "Bank/Metode" di `/admin/daftar-ulang` tidak menampilkan nama bank yang dipilih oleh pendaftar.

## Root Cause

Admin view mencoba decode JSON dari field `metode_pembayaran`, padahal data yang disimpan adalah **string biasa** (nama bank), bukan JSON.

### Data yang Disimpan di Database:

**Tabel `daftar_ulang`:**
```
metode_pembayaran: "BNI Syariah"  ← STRING, bukan JSON
```

**Tabel `seragam`:**
```
bank_seragam: "BCA Syariah"  ← STRING, bukan JSON
```

### Alur Data dari Pendaftar:

**Form Pendaftar** (`resources/views/livewire/pendaftar/daftar-ulang.blade.php`):

**Madrasah - Line 360:**
```blade
<select wire:model="bankPilihan">
    <option value="">-- Pilih Bank/Metode Pembayaran --</option>
    @foreach($madrasahBanks as $bank)
        <option value="{{ $bank['name'] ?? '' }}">
            {{ $bank['name'] ?? 'Bank' }}
            @if(!empty($bank['account_number'])) - {{ $bank['account_number'] }}@endif
            @if(!empty($bank['account_name'])) ({{ $bank['account_name'] }})@endif
        </option>
    @endforeach
</select>
```

**Seragam - Line 826:**
```blade
<select wire:model="bankSeragam">
    <option value="">-- Pilih Bank/Metode Pembayaran --</option>
    @foreach($seragamBanks as $bank)
        <option value="{{ $bank['name'] ?? '' }}">
            {{ $bank['name'] ?? 'Bank' }}
            @if(!empty($bank['account_number'])) - {{ $bank['account_number'] }}@endif
            @if(!empty($bank['account_name'])) ({{ $bank['account_name'] }})@endif
        </option>
    @endforeach
</select>
```

**Save Logic** (`app/Livewire/Pendaftar/DaftarUlang.php`):

```php
$dataToUpdate = [
    // ...
    'metode_pembayaran' => $this->bankPilihan,  // ← String: "BNI Syariah"
    // ...
];
```

Jadi yang disimpan adalah **nama bank saja** sebagai string, bukan object JSON.

## Solusi

### Update Admin View - Tab Madrasah

**File**: `resources/views/livewire/admin/daftar-ulang.blade.php`

**Sebelum** (Line 1272-1288):
```blade
<td class="px-6 py-4">
    @if($item->daftarUlang && $item->daftarUlang->metode_pembayaran)
        @php
            $metodePembayaran = json_decode($item->daftarUlang->metode_pembayaran, true);
        @endphp
        @if($metodePembayaran && is_array($metodePembayaran))
            <div class="text-xs text-gray-900">
                <span class="font-medium">{{ $metodePembayaran['name'] ?? '-' }}</span>
            </div>
        @else
            <span class="text-xs text-gray-500">-</span>
        @endif
    @else
        <span class="text-xs text-gray-500">-</span>
    @endif
</td>
```

**Sesudah**:
```blade
<td class="px-6 py-4">
    @if($item->daftarUlang && $item->daftarUlang->metode_pembayaran)
        <div class="text-xs text-gray-900">
            <span class="font-medium">{{ $item->daftarUlang->metode_pembayaran }}</span>
        </div>
    @else
        <span class="text-xs text-gray-500">-</span>
    @endif
</td>
```

### Update Admin View - Tab Seragam

**Sebelum** (Line 1386-1402):
```blade
<td class="px-6 py-4">
    @if($item->seragam && $item->seragam->bank_seragam)
        @php
            $bankSeragam = json_decode($item->seragam->bank_seragam, true);
        @endphp
        @if($bankSeragam && is_array($bankSeragam))
            <div class="text-xs text-gray-900">
                <span class="font-medium">{{ $bankSeragam['name'] ?? '-' }}</span>
            </div>
        @else
            <span class="text-xs text-gray-500">-</span>
        @endif
    @else
        <span class="text-xs text-gray-500">-</span>
    @endif
</td>
```

**Sesudah**:
```blade
<td class="px-6 py-4">
    @if($item->seragam && $item->seragam->bank_seragam)
        <div class="text-xs text-gray-900">
            <span class="font-medium">{{ $item->seragam->bank_seragam }}</span>
        </div>
    @else
        <span class="text-xs text-gray-500">-</span>
    @endif
</td>
```

## Contoh Data yang Ditampilkan

### Tab Madrasah - Kolom Bank/Metode:
```
BNI Syariah
BCA Syariah
Bank Muamalat
Transfer Bank BRI
```

### Tab Seragam - Kolom Bank/Metode:
```
BNI Syariah
Mandiri Syariah
BCA
```

## Format Data di Admin Settings

Admin mengatur bank di `/admin/daftar-ulang?activeTab=settings` dalam format JSON array:

**Contoh `madrasahBanks` di settings:**
```json
[
    {
        "name": "BNI Syariah",
        "account_number": "1234567890",
        "account_name": "MTs Negeri 2 Kota Malang"
    },
    {
        "name": "BCA Syariah",
        "account_number": "0987654321",
        "account_name": "MTs Negeri 2 Kota Malang"
    }
]
```

**Yang disimpan ke database pendaftar:**
- Hanya `name` saja sebagai string
- Contoh: `"BNI Syariah"`

## Testing

### Test Tab Madrasah:

1. Login sebagai **pendaftar**
2. Isi form daftar ulang madrasah
3. Pilih bank dari dropdown "Daftar Bank/Metode Pembayaran"
   - Contoh: Pilih "BNI Syariah"
4. Simpan

5. Login sebagai **admin**
6. Buka `/admin/daftar-ulang?activeTab=madrasah`
7. ✅ Kolom "Bank/Metode" harus menampilkan: **"BNI Syariah"**

### Test Tab Seragam:

1. Login sebagai **pendaftar**
2. Isi form daftar ulang seragam
3. Pilih bank dari dropdown "Daftar Bank/Metode Pembayaran"
   - Contoh: Pilih "Mandiri Syariah"
4. Simpan

5. Login sebagai **admin**
6. Buka `/admin/daftar-ulang?activeTab=seragam`
7. ✅ Kolom "Bank/Metode" harus menampilkan: **"Mandiri Syariah"**

## Files Changed

1. **resources/views/livewire/admin/daftar-ulang.blade.php**
   - Tab Madrasah: Kolom Bank/Metode (remove JSON decode)
   - Tab Seragam: Kolom Bank/Metode (remove JSON decode)

---

**Tanggal**: 30 Januari 2026
**Status**: ✅ Complete
**Testing**: Silakan test dengan data yang sudah ada atau isi form baru dari pendaftar
