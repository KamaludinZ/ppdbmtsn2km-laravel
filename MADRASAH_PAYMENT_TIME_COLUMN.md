# Payment Time Column - Daftar Ulang Madrasah

## Summary
Added "Waktu Pembayaran" (Payment Time) column to Daftar Ulang Madrasah table in Admin Daftar Ulang module.

## Problem
Tab "Daftar Ulang Madrasah" tidak menampilkan waktu pembayaran, sehingga admin tidak bisa melihat kapan pendaftar melakukan pembayaran daftar ulang madrasah.

## Solution
Add new column "Waktu Pembayaran" that displays payment timestamp from the `pembayaran` table.

## Changes Made

### File: `resources/views/livewire/admin/daftar-ulang.blade.php`

#### 1. Header Column Addition (line 905)

**Added:**
```blade
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Pembayaran</th>
```

**Position:**
After "Bukti Pembayaran" column, before `@elseif($activeTab == 'seragam')`

#### 2. Body Cell Addition (line 1119-1132)

**Added:**
```blade
<!-- Waktu Pembayaran -->
<td class="px-6 py-4">
    @php
        $pembayaranMadrasah = $item->pembayaran->where('jenis_pembayaran', 'daftar_ulang')->first();
    @endphp
    @if($pembayaranMadrasah && $pembayaranMadrasah->created_at)
        <div class="text-xs text-gray-900">
            <div class="font-medium">{{ $pembayaranMadrasah->created_at->format('d/m/Y') }}</div>
            <div class="text-gray-600">{{ $pembayaranMadrasah->created_at->format('H:i') }} WIB</div>
        </div>
    @else
        <span class="text-xs text-gray-500">-</span>
    @endif
</td>
```

## Display Format

**When payment exists:**
```
25/01/2026
14:30 WIB
```

**When no payment:**
```
-
```

## Data Source

- **Table**: `pembayaran`
- **Filter**: `jenis_pembayaran = 'daftar_ulang'`
- **Field**: `created_at` timestamp
- **Format**:
  - Date: `d/m/Y` (e.g., 25/01/2026)
  - Time: `H:i WIB` (e.g., 14:30 WIB)

## Table Structure After Changes

### Tab Daftar Ulang Madrasah - Table Columns

| # | Column Name | Description |
|---|-------------|-------------|
| 1 | Nomor Pendaftaran | Registration number |
| 2 | Pendaftar | Student name & NISN |
| 3 | Jenis Kelamin | Gender (L/P) |
| 4 | Persetujuan | Re-registration agreement status |
| 5 | Aksi Persetujuan | Agreement actions dropdown |
| 6 | Status DU Madrasah | Re-registration status |
| 7 | Surat Pernyataan | Statement letter upload status |
| 8 | Biaya & Pembayaran | Payment amount |
| 9 | Opsi Pembayaran | Payment option (lunas/cicilan) |
| 10 | Bank/Metode | Payment method/bank |
| 11 | Bukti Pembayaran | Payment proof upload status |
| **12** | **Waktu Pembayaran** | **Payment timestamp** ✅ NEW |
| 13 | Ubah Status | Status change dropdown |
| 14 | Aksi Pesan | Message actions |
| 15 | Riwayat Pesan | Message history |

## Benefits

1. **Payment Tracking**: Admin can see exactly when students uploaded their payment proof
2. **Chronological Reference**: Easy to track payment submission timeline
3. **Verification Aid**: Helps identify recent vs old payments
4. **Consistency**: Now both Madrasah and Seragam tabs have payment time column

## Consistency Across Tabs

After this update, both re-registration tabs now have payment time column:

| Tab | Has Payment Time Column |
|-----|------------------------|
| Daftar Ulang Madrasah | ✅ Yes |
| Daftar Ulang Seragam | ✅ Yes |
| Ukuran Seragam & Atribut | ❌ No (not applicable) |

## Testing Checklist

- [ ] Navigate to `/admin/daftar-ulang`
- [ ] Click "Daftar Ulang Madrasah" tab
- [ ] Verify "Waktu Pembayaran" column appears in table header
- [ ] Check students with payment uploads show date and time
- [ ] Check students without payments show "-"
- [ ] Verify time format displays correctly (dd/mm/yyyy HH:mm WIB)
- [ ] Test table horizontal scrolling works properly
- [ ] Verify column aligns properly with other columns

## Cache Cleared

View cache has been cleared:
```bash
✅ php artisan view:clear
```

## Files Modified

1. **`resources/views/livewire/admin/daftar-ulang.blade.php`**
   - Line 905: Added header column
   - Lines 1119-1132: Added body cell

## Related Documentation

This change complements the previous improvements documented in:
- `DAFTAR_ULANG_IMPROVEMENTS_COMPLETE.md` - Contains similar payment time column for Seragam tab

## Visual Example

**Table Row Example:**

```
| MTsN-2026-001 | Ahmad | L | Setuju | [Actions] | Disetujui | ✓ Upload | Rp 2.500.000 | Lunas | BNI 123456 | ✓ Upload | 25/01/2026 14:30 WIB | [Change] | [Message] | ... |
```

---

**Date**: 2026-01-25
**Status**: Complete and ready for testing
**Impact**: Admin Daftar Ulang - Madrasah tab
**Files Modified**: 1 file
**Backward Compatible**: Yes
