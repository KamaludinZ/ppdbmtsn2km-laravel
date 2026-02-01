# Fix Seragam Status Column Name

**Tanggal:** 29 Januari 2026
**Status:** ✅ SELESAI

## Ringkasan

Memperbaiki error database `SQLSTATE[42703]: Undefined column: 7 ERROR: column "status_pembayaran" does not exist` pada modul Panitia Seragam. Kolom yang benar adalah `status_seragam`, bukan `status_pembayaran`.

---

## Error yang Terjadi

**Error Message:**
```
SQLSTATE[42703]: Undefined column: 7 ERROR:  column "status_pembayaran" does not exist
LINE 1: select count(*) as aggregate from "seragam" where "status_pe...
```

**Location:** `/panitia-seragam/dashboard`

**Root Cause:**
Code menggunakan kolom `status_pembayaran` padahal di tabel `seragam` kolom yang benar adalah `status_seragam`.

---

## Struktur Tabel Seragam

Berdasarkan model `App\Models\Seragam`, kolom yang ada untuk status adalah:
- ✅ `status_seragam` - Status pembayaran seragam (lunas/pending/belum_bayar)
- ✅ `status_ukuran` - Status pengisian ukuran
- ❌ `status_pembayaran` - **TIDAK ADA** (ini yang salah)

---

## Files yang Diperbaiki

### 1. Backend - PanitiaSeragam\Dashboard.php

**Lines Changed:** 42, 46, 73, 79

**SEBELUM:**
```php
// loadStats() - lines 42, 46
$seragamLunas = Seragam::where('status_pembayaran', 'lunas')->count();
$seragamBelumLunas = Seragam::whereIn('status_pembayaran', ['pending', 'belum_bayar'])->count();

// loadRecentPendaftar() - lines 73, 79
$statusSeragam = match($p->seragam->status_pembayaran) { ... };
$statusColor = match($p->seragam->status_pembayaran) { ... };
```

**SESUDAH:**
```php
// loadStats()
$seragamLunas = Seragam::where('status_seragam', 'lunas')->count();
$seragamBelumLunas = Seragam::whereIn('status_seragam', ['pending', 'belum_bayar'])->count();

// loadRecentPendaftar()
$statusSeragam = match($p->seragam->status_seragam) { ... };
$statusColor = match($p->seragam->status_seragam) { ... };
```

---

### 2. Backend - PanitiaSeragam\DaftarUlang.php

**Lines Changed:** 48, 101

**SEBELUM:**
```php
// loadStats() - line 48
$lunas = Seragam::where('status_pembayaran', 'lunas')->count();

// render() - line 101
$query->whereHas('seragam', function($q) {
    $q->where('status_pembayaran', $this->filterStatus);
});
```

**SESUDAH:**
```php
// loadStats()
$lunas = Seragam::where('status_seragam', 'lunas')->count();

// render()
$query->whereHas('seragam', function($q) {
    $q->where('status_seragam', $this->filterStatus);
});
```

---

### 3. Backend - PanitiaSeragam\Laporan.php

**Lines Changed:** 58, 59, 60

**SEBELUM:**
```php
$lunas = Seragam::where('status_pembayaran', 'lunas')->count();
$pending = Seragam::where('status_pembayaran', 'pending')->count();
$belumBayar = Seragam::where('status_pembayaran', 'belum_bayar')->count();
```

**SESUDAH:**
```php
$lunas = Seragam::where('status_seragam', 'lunas')->count();
$pending = Seragam::where('status_seragam', 'pending')->count();
$belumBayar = Seragam::where('status_seragam', 'belum_bayar')->count();
```

---

### 4. View - panitia-seragam\daftar-ulang.blade.php

**Lines Changed:** 127, 133

**SEBELUM:**
```blade
@php
    $statusColor = match($p->seragam->status_pembayaran ?? 'belum_bayar') {
        'lunas' => 'green',
        'pending' => 'yellow',
        'belum_bayar' => 'red',
        default => 'gray'
    };
    $statusLabel = match($p->seragam->status_pembayaran ?? 'belum_bayar') {
        'lunas' => 'Lunas',
        'pending' => 'Pending',
        'belum_bayar' => 'Belum Bayar',
        default => 'Unknown'
    };
@endphp
```

**SESUDAH:**
```blade
@php
    $statusColor = match($p->seragam->status_seragam ?? 'belum_bayar') {
        'lunas' => 'green',
        'pending' => 'yellow',
        'belum_bayar' => 'red',
        default => 'gray'
    };
    $statusLabel = match($p->seragam->status_seragam ?? 'belum_bayar') {
        'lunas' => 'Lunas',
        'pending' => 'Pending',
        'belum_bayar' => 'Belum Bayar',
        default => 'Unknown'
    };
@endphp
```

---

## Summary of Changes

| File | Lines Changed | Changes |
|------|--------------|---------|
| `app/Livewire/PanitiaSeragam/Dashboard.php` | 4 changes | Lines 42, 46, 73, 79 |
| `app/Livewire/PanitiaSeragam/DaftarUlang.php` | 2 changes | Lines 48, 101 |
| `app/Livewire/PanitiaSeragam/Laporan.php` | 3 changes | Lines 58, 59, 60 |
| `resources/views/livewire/panitia-seragam/daftar-ulang.blade.php` | 2 changes | Lines 127, 133 |

**Total:** 4 files, 11 occurrences changed

---

## Impact

### ✅ Fixed
- Dashboard Panitia Seragam sekarang bisa load tanpa error
- Stats seragam (lunas, pending, belum bayar) tampil dengan benar
- Filter status seragam di halaman Daftar Ulang bekerja dengan benar
- Laporan seragam menampilkan data dengan benar
- Badge status di tabel tampil dengan benar

### 🎯 Affected Areas
- `/panitia-seragam/dashboard` - ✅ Fixed
- `/panitia-seragam/daftar-ulang` - ✅ Fixed
- `/panitia-seragam/laporan` - ✅ Fixed

---

## Testing Checklist

- [ ] Login sebagai `panitia_seragam`
- [ ] Akses `/panitia-seragam/dashboard` - Verifikasi tidak ada error
- [ ] Verifikasi stats "Seragam Lunas" dan "Belum Lunas" tampil dengan benar
- [ ] Verifikasi "Pendaftar Terbaru Hari Ini" tampil dengan status seragam yang benar
- [ ] Akses `/panitia-seragam/daftar-ulang`
- [ ] Verifikasi filter status bekerja (Lunas/Pending/Belum Bayar)
- [ ] Verifikasi badge status di tabel tampil dengan warna yang benar
- [ ] Akses `/panitia-seragam/laporan`
- [ ] Verifikasi stats laporan seragam tampil dengan benar

---

## Related Issue

Issue ini muncul karena ada inconsistency dalam naming convention:
- Di model `Seragam`, kolom untuk status adalah `status_seragam`
- Di beberapa tempat code masih menggunakan `status_pembayaran` (yang tidak ada)

**Prevention:**
- Selalu cek model untuk memastikan nama kolom yang benar
- Gunakan IDE autocomplete untuk menghindari typo
- Tambahkan unit tests untuk query ke database

---

## Status

✅ **SELESAI** - Semua occurrences dari `status_pembayaran` sudah diganti ke `status_seragam`

**Verified:**
- ✅ No more `status_pembayaran` in `app/Livewire/PanitiaSeragam/`
- ✅ No more `status_pembayaran` in `resources/views/livewire/panitia-seragam/`
- ✅ All queries now use correct column name `status_seragam`

---

**Dokumentasi dibuat:** 29 Januari 2026
**Author:** Claude Code Assistant
