# Fix Menu Panitia Daftar Ulang - Sidebar & Dashboard

**Tanggal:** 30 Januari 2026
**Status:** ✅ SELESAI

## Overview

Menyesuaikan menu untuk role **panitia_daftarulang** agar lebih fokus pada tugas mereka:
1. **Hapus** menu "Data Pendaftar" dari sidebar
2. **Ubah** Aksi Cepat di dashboard (hapus menu yang tidak relevan, tambah menu Kelulusan & Daftar Ulang)

---

## Changes Made

### 1. ✅ Sidebar - Hapus Menu "Data Pendaftar"

**File:** `resources/views/components/layouts/dashboard.blade.php`
**Line:** 379-410

**BEFORE (6 menu items):**
```blade
@elseif($role === 'panitia_daftarulang')
    <a href="/panitia-daftarulang/dashboard">Dashboard</a>
    <a href="/panitia-daftarulang/pendaftar">Data Pendaftar</a>    ❌ DIHAPUS
    <a href="/panitia-daftarulang/kelulusan">Kelulusan</a>
    <a href="/panitia-daftarulang/daftar-ulang">Daftar Ulang</a>
    <a href="/panitia-daftarulang/pengumuman">Pengumuman</a>
    <a href="/panitia-daftarulang/laporan">Laporan</a>
```

**AFTER (5 menu items):**
```blade
@elseif($role === 'panitia_daftarulang')
    <a href="/panitia-daftarulang/dashboard">Dashboard</a>
    <a href="/panitia-daftarulang/kelulusan">Kelulusan</a>           ✅
    <a href="/panitia-daftarulang/daftar-ulang">Daftar Ulang</a>     ✅
    <a href="/panitia-daftarulang/pengumuman">Pengumuman</a>
    <a href="/panitia-daftarulang/laporan">Laporan</a>
```

**Removed:**
- ❌ Data Pendaftar (`/panitia-daftarulang/pendaftar`)

---

### 2. ✅ Dashboard - Ubah Aksi Cepat

**File:** `resources/views/livewire/panitia/dashboard.blade.php`
**Line:** 252-320

**BEFORE (Panitia Daftar Ulang menggunakan menu default dengan 6 items):**
```blade
@if($isPanitiaPsikologi)
    <!-- Menu Panitia Psikologi -->
@else
    <!-- Menu default untuk SEMUA panitia lainnya -->
    - Lihat Data Pendaftar         ❌
    - Data Asal Sekolah            ❌
    - Jalur Pendaftaran            ❌
    - Jadwal                       ❌
    - Pengumuman
    - Lihat Laporan
@endif
```

**AFTER (Panitia Daftar Ulang memiliki menu khusus dengan 3 items):**
```blade
@if($isPanitiaPsikologi)
    <!-- Menu Panitia Psikologi -->
    - Tes Psikologi
    - Laporan Tes Psikologi
@elseif($isPanitiaDaftarUlang)
    <!-- Menu khusus Panitia Daftar Ulang -->     ✅ BARU
    - Kelulusan                                   ✅
    - Daftar Ulang                                ✅
    - Laporan                                     ✅
@else
    <!-- Menu default untuk panitia lainnya -->
    - Lihat Data Pendaftar
    - Data Asal Sekolah
    - Jalur Pendaftaran
    - Jadwal
    - Pengumuman
    - Lihat Laporan
@endif
```

**Added Condition:**
```blade
@elseif($isPanitiaDaftarUlang)
    <!-- Menu khusus untuk Panitia Daftar Ulang -->
    <a href="/panitia-daftarulang/kelulusan" class="flex items-center px-4 py-3 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition">
        <svg class="w-5 h-5 text-emerald-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="text-sm font-medium text-gray-900">Kelulusan</span>
    </a>
    <a href="/panitia-daftarulang/daftar-ulang" class="flex items-center px-4 py-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
        <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
        </svg>
        <span class="text-sm font-medium text-gray-900">Daftar Ulang</span>
    </a>
    <a href="/panitia-daftarulang/laporan" class="flex items-center px-4 py-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition">
        <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <span class="text-sm font-medium text-gray-900">Laporan</span>
    </a>
```

---

## Comparison: Menu Per Role

### Sidebar Comparison

| Menu | Admin | Panitia | Panitia DaftarUlang | Panitia Psikologi | Panitia Seragam |
|------|-------|---------|---------------------|-------------------|-----------------|
| Dashboard | ✅ | ✅ | ✅ | ✅ | ✅ |
| Data Pendaftar | ✅ | ✅ | ❌ **DIHAPUS** | ❌ | ❌ |
| Kelulusan | ✅ | ✅ | ✅ | ❌ | ❌ |
| Daftar Ulang | ✅ | ✅ | ✅ | ❌ | ✅ |
| Tes Psikologi | ✅ | ✅ | ❌ | ✅ | ❌ |
| Pengumuman | ✅ | ✅ | ✅ | ✅ | ✅ |
| Laporan | ✅ | ✅ | ✅ | ✅ | ✅ |

### Dashboard Aksi Cepat Comparison

| Role | Aksi Cepat Items | Count |
|------|------------------|-------|
| **Admin** | Tidak ada Aksi Cepat | - |
| **Panitia** | Data Pendaftar, Asal Sekolah, Jalur, Jadwal, Pengumuman, Laporan | 6 |
| **Panitia DaftarUlang** | **Kelulusan, Daftar Ulang, Laporan** | **3** ✅ |
| **Panitia Psikologi** | Tes Psikologi, Laporan Tes Psikologi | 2 |
| **Panitia Seragam** | (Same as Panitia) | 6 |

---

## Why These Changes?

### Fokus Tugas Panitia Daftar Ulang

**Tugas Utama:**
1. ✅ Mengelola **Kelulusan** pendaftar
2. ✅ Mengelola **Daftar Ulang** Madrasah

**Tidak Perlu Akses:**
- ❌ Data Pendaftar (sudah bisa lihat di Kelulusan & Daftar Ulang)
- ❌ Data Asal Sekolah (tugas panitia pendaftaran)
- ❌ Jalur Pendaftaran (tugas admin/panitia pendaftaran)
- ❌ Jadwal (tugas admin/panitia pendaftaran)

### Benefits

1. **Simplified Navigation** - Sidebar lebih ringkas (5 menu vs 6)
2. **Focused Dashboard** - Aksi Cepat hanya 3 menu yang relevan (Kelulusan, Daftar Ulang, Laporan)
3. **Clear Role Separation** - Pembagian tugas lebih jelas
4. **Better UX** - User tidak bingung dengan menu yang tidak perlu
5. **Complete Workflow** - Dari kelulusan, daftar ulang, hingga laporan dalam satu tempat

---

## Akses yang Masih Ada

### Panitia Daftar Ulang Masih Bisa Akses:

**Via Sidebar:**
1. ✅ Dashboard - Statistik jalur & pendaftar
2. ✅ Kelulusan - Kelola status kelulusan
3. ✅ Daftar Ulang - Kelola daftar ulang Madrasah
4. ✅ Pengumuman - Kelola pengumuman
5. ✅ Laporan - Export & cetak laporan

**Via Dashboard Aksi Cepat:**
1. ✅ Kelulusan - Link cepat ke halaman kelulusan
2. ✅ Daftar Ulang - Link cepat ke halaman daftar ulang
3. ✅ Laporan - Link cepat ke halaman laporan

---

## Backend Support

**Variable in Dashboard.php:**
```php
public $isPanitiaDaftarUlang = false;
```

**Set in mount():**
```php
$this->isPanitiaDaftarUlang = Auth::user()->role === 'panitia_daftarulang';
```

✅ Already exists - no backend changes needed

---

## Files Modified

1. ✅ **resources/views/components/layouts/dashboard.blade.php** (Line 379-410)
   - Removed "Data Pendaftar" menu item from sidebar

2. ✅ **resources/views/livewire/panitia/dashboard.blade.php** (Line 267-286)
   - Added `@elseif($isPanitiaDaftarUlang)` condition
   - Added 3 custom menu items for Panitia Daftar Ulang: Kelulusan, Daftar Ulang, Laporan

**Total Changes:** 2 files, ~30 lines modified

---

## Testing Checklist

- [x] Sidebar updated (menu removed)
- [x] Dashboard Aksi Cepat updated (new condition added)
- [ ] Login as panitia_daftarulang
- [ ] Verify sidebar shows only 5 menus
- [ ] Verify "Data Pendaftar" not in sidebar
- [ ] Verify Dashboard Aksi Cepat shows only 3 items
- [ ] Click "Kelulusan" from Aksi Cepat
- [ ] Click "Daftar Ulang" from Aksi Cepat
- [ ] Click "Laporan" from Aksi Cepat
- [ ] Verify other panitia roles still see their menus

---

## User Impact

### For Panitia Daftar Ulang:
1. ✅ **Cleaner Interface** - Sidebar lebih ringkas (5 menu)
2. ✅ **Faster Access** - Aksi Cepat hanya 3 menu penting (Kelulusan, Daftar Ulang, Laporan)
3. ✅ **Less Confusion** - Tidak ada menu yang tidak relevan
4. ✅ **Still Full Access** - Semua fitur penting tetap ada

### For Other Panitia Roles:
- ✅ **No Impact** - Menu mereka tidak berubah
- ✅ **Same Experience** - Tetap 6 menu di Aksi Cepat

---

## Route Access Control

**Important:** Menu dihapus dari UI, tapi route masih bisa diakses jika diketik manual.

**Existing Route Protection:**
```php
Route::middleware(['auth', 'role:panitia_daftarulang,panitia'])
    ->prefix('panitia-daftarulang')
    ->group(function () {
        Route::get('/pendaftar', [Pendaftar::class, 'index']); // Still accessible if typed manually
        Route::get('/kelulusan', [Kelulusan::class, 'index']);
        Route::get('/daftar-ulang', [DaftarUlang::class, 'index']);
        // ...
    });
```

✅ Role `panitia_daftarulang` masih bisa akses `/panitia-daftarulang/pendaftar` jika perlu (via URL langsung), tapi tidak ada link di UI.

---

## Visual Changes

### Sidebar - Before vs After

**BEFORE:**
```
📊 Dashboard
👥 Data Pendaftar      ← DIHAPUS
✅ Kelulusan
📋 Daftar Ulang
📢 Pengumuman
📄 Laporan
```

**AFTER:**
```
📊 Dashboard
✅ Kelulusan
📋 Daftar Ulang
📢 Pengumuman
📄 Laporan
```

### Dashboard Aksi Cepat - Before vs After

**BEFORE (menggunakan menu default):**
```
⚡ Aksi Cepat
├─ Lihat Data Pendaftar     ← DIHAPUS
├─ Data Asal Sekolah        ← DIHAPUS
├─ Jalur Pendaftaran        ← DIHAPUS
├─ Jadwal                   ← DIHAPUS
├─ Pengumuman               ← DIHAPUS
└─ Lihat Laporan            ← DIHAPUS
```

**AFTER (menu khusus):**
```
⚡ Aksi Cepat
├─ Kelulusan        ✅ BARU
├─ Daftar Ulang     ✅ BARU
└─ Laporan          ✅ BARU
```

---

**Status:** ✅ COMPLETE - Ready for testing

**Expected Result:**
- Sidebar Panitia Daftar Ulang sekarang hanya menampilkan 5 menu (tanpa "Data Pendaftar")
- Dashboard Panitia Daftar Ulang menampilkan Aksi Cepat dengan 3 menu: Kelulusan, Daftar Ulang, dan Laporan
- Menu lebih fokus pada tugas utama Panitia Daftar Ulang
