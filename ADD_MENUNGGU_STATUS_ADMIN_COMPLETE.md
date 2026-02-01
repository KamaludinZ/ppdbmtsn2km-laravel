# Tambah Status "Menunggu" di Admin Daftar Ulang Madrasah

**Tanggal:** 30 Januari 2026
**Status:** ✅ SELESAI

## Overview

Menambahkan pilihan **"Menunggu"** di kolom "Ubah Status" pada halaman Admin Daftar Ulang tab Madrasah (`/admin/daftar-ulang?activeTab=madrasah`).

---

## Changes Made

### File Modified: `resources/views/livewire/admin/daftar-ulang.blade.php`

**Location:** Line 1530-1548

**Before:**
```blade
<select
    wire:change="changeStatusMadrasah({{ $item->id }}, $event.target.value)"
    class="px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
>
    <option value="">-- Pilih Status --</option>
    <option value="disetujui" {{ ($item->daftarUlang->status ?? '') === 'disetujui' ? 'selected' : '' }}>
        Disetujui
    </option>
    <option value="ditolak" {{ ($item->daftarUlang->status ?? '') === 'ditolak' ? 'selected' : '' }}>
        Ditolak
    </option>
    <option value="perbaiki" {{ ($item->daftarUlang->status ?? '') === 'perbaiki' ? 'selected' : '' }}>
        Perbaiki
    </option>
</select>
```

**After:**
```blade
<select
    wire:change="changeStatusMadrasah({{ $item->id }}, $event.target.value)"
    class="px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
>
    <option value="">-- Pilih Status --</option>
    <option value="menunggu" {{ ($item->daftarUlang->status ?? '') === 'menunggu' ? 'selected' : '' }}>
        Menunggu
    </option>
    <option value="disetujui" {{ ($item->daftarUlang->status ?? '') === 'disetujui' ? 'selected' : '' }}>
        Disetujui
    </option>
    <option value="ditolak" {{ ($item->daftarUlang->status ?? '') === 'ditolak' ? 'selected' : '' }}>
        Ditolak
    </option>
    <option value="perbaiki" {{ ($item->daftarUlang->status ?? '') === 'perbaiki' ? 'selected' : '' }}>
        Perbaiki
    </option>
</select>
```

---

## Status Options Order

### Admin Daftar Ulang Madrasah (SELECT Dropdown)

1. ⏳ **Menunggu** (NEW - Added)
2. ✅ **Disetujui**
3. ❌ **Ditolak**
4. 🔧 **Perbaiki**

### Panitia Daftar Ulang (Alpine.js Dropdown)

Status yang sama sudah ada di Panitia (Line 396-419):

1. ⏳ **Menunggu** (✅ Already exists)
2. ✅ **Disetujui**
3. ❌ **Ditolak**
4. 🔧 **Perlu Perbaikan**

---

## Consistency Check

| Feature | Admin | Panitia | Status |
|---------|-------|---------|--------|
| Status "Menunggu" | ✅ Added | ✅ Exists | ✅ Konsisten |
| Status "Disetujui" | ✅ | ✅ | ✅ Konsisten |
| Status "Ditolak" | ✅ | ✅ | ✅ Konsisten |
| Status "Perbaiki" | ✅ | ✅ | ✅ Konsisten |
| Dropdown Type | SELECT | Alpine.js | Different UI |

---

## Why "Menunggu" Status?

### Use Cases

1. **Initial State** - Status default saat pendaftar baru submit daftar ulang
2. **Pending Review** - Admin/Panitia sedang me-review dokumen
3. **Awaiting Payment** - Menunggu konfirmasi pembayaran
4. **Queue Status** - Dalam antrian untuk diproses

### Status Flow Example

```
Menunggu → Disetujui (Success)
Menunggu → Ditolak (Rejected)
Menunggu → Perbaiki → Menunggu → Disetujui
```

---

## Backend Method (Already Exists)

**File:** `app/Livewire/Admin/DaftarUlang.php`

**Method:** `changeStatusMadrasah()`

The method already supports any status value, including 'menunggu':

```php
public function changeStatusMadrasah($pendaftarId, $status)
{
    $pendaftar = Pendaftar::findOrFail($pendaftarId);

    $daftarUlang = DaftarUlangModel::firstOrCreate(
        ['pendaftar_id' => $pendaftarId],
        ['status' => $status]
    );

    $daftarUlang->update(['status' => $status]);

    $this->dispatch('saved', message: 'Status DU Madrasah berhasil diubah');
}
```

✅ No backend changes needed - method already handles all status values.

---

## Database Validation

**Table:** `daftar_ulang`

**Column:** `status` (VARCHAR/TEXT)

The column accepts any string value, so 'menunggu' is valid.

**Common Values:**
- `menunggu` ⏳
- `disetujui` ✅
- `ditolak` ❌
- `perbaiki` 🔧

---

## Badge Colors (Display)

**Location:** Line 1192-1199 in Admin view

The status badge colors are already defined:

```php
@php
    $statusColors = [
        'proses' => 'bg-blue-100 text-blue-800',
        'menunggu' => 'bg-yellow-100 text-yellow-800',  // ✅ Already defined
        'disetujui' => 'bg-green-100 text-green-800',
        'ditolak' => 'bg-red-100 text-red-800',
        'perbaiki' => 'bg-orange-100 text-orange-800',
    ];
@endphp
```

✅ Badge color for "menunggu" already exists (yellow)

---

## Testing Checklist

- [x] Added "Menunggu" option to Admin dropdown
- [ ] Select "Menunggu" status for a pendaftar
- [ ] Verify status badge shows yellow color
- [ ] Verify status saved to database correctly
- [ ] Check notification sent (if enabled)
- [ ] Verify Panitia can also see the status
- [ ] Test status change from Menunggu → Disetujui
- [ ] Test status change from Menunggu → Ditolak
- [ ] Test status change from Menunggu → Perbaiki

---

## User Impact

### Admin Benefits
1. **More Control** - Can explicitly set items to "waiting" status
2. **Better Workflow** - Clear distinction between unprocessed and pending items
3. **Status Tracking** - Easier to track which items are in review

### Pendaftar Benefits
1. **Clear Status** - Knows their submission is being reviewed
2. **Not Rejected** - "Menunggu" is neutral, not negative
3. **Expectation Setting** - Understands they need to wait

---

## Related Files

### View Files
1. ✅ **resources/views/livewire/admin/daftar-ulang.blade.php** (Line 1536-1538)
   - Added "Menunggu" option

2. ✅ **resources/views/livewire/panitia/daftar-ulang.blade.php** (Line 396-401)
   - Already has "Menunggu" option

### Backend Files
No changes needed - existing methods already support all status values:
- `app/Livewire/Admin/DaftarUlang.php` - `changeStatusMadrasah()`
- `app/Livewire/Panitia/DaftarUlang.php` - `changeStatusMadrasah()`

---

## Status Summary

| Aspect | Status |
|--------|--------|
| **View Updated** | ✅ Complete |
| **Backend Method** | ✅ Already supports |
| **Database** | ✅ Compatible |
| **Badge Color** | ✅ Already defined |
| **Panitia Consistency** | ✅ Already has it |
| **Testing** | ⏳ Ready for testing |

---

**Status:** ✅ COMPLETE - Ready to use

**Expected Result:**
- Admin can now select "Menunggu" status for any pendaftar in Daftar Ulang Madrasah
- Status displays with yellow badge
- Status saved to database correctly
- Matches Panitia Daftar Ulang functionality
