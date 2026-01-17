# Sortable Table Headers - Quick Start

Fitur untuk membuat header tabel bisa di-sort dengan klik.

---

## ✨ Fitur

- 🔄 **Click to Sort** - Klik header untuk sort ascending/descending
- 🎨 **Visual Feedback** - Icon arrow dan highlight untuk kolom aktif
- ⚡ **Fast & Responsive** - Menggunakan Livewire untuk update cepat
- 📱 **Mobile Friendly** - Responsive di semua device
- 🔗 **Persistent** - State tersimpan di URL query string

---

## 🎯 Status Implementasi

### ✅ Yang Sudah Dibuat

1. **Trait `WithSorting`** - `app/Traits/WithSorting.php`
2. **Component `sortable-header`** - `resources/views/components/sortable-header.blade.php`

### ✅ Halaman yang Sudah Diimplementasi

1. **Admin > Data Pendaftar** - Fully implemented
2. **Panitia > Data Pendaftar** - Fully implemented

### 📋 Halaman yang Perlu Diimplementasi

Lihat daftar lengkap di [SORTABLE_TABLE_GUIDE.md](SORTABLE_TABLE_GUIDE.md#-daftar-component-yang-perlu-diupdate)

**Total:** ~25+ halaman dengan tabel data

---

## 🚀 Quick Start - Implementasi di Halaman Baru

### 1. Update PHP Component

```php
// File: app/Livewire/Admin/NamaComponent.php

use App\Traits\WithSorting;  // Import trait

class NamaComponent extends Component
{
    use WithPagination, WithSorting;  // Gunakan trait

    protected $queryString = [
        // ... existing
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function render()
    {
        $query = Model::query()
            ->when($this->search, ...)
            ->when($this->filter, ...);

        // Ganti ->latest() dengan:
        $data = $this->applySorting($query)->paginate($this->perPage);

        return view('...', compact('data'));
    }
}
```

### 2. Update Blade View

```blade
<!-- File: resources/views/livewire/admin/nama-component.blade.php -->

<thead class="bg-gray-50">
    <tr>
        <!-- Kolom yang TIDAK sortable -->
        <th class="px-6 py-3 ...">No</th>

        <!-- Kolom yang SORTABLE -->
        <x-sortable-header field="nama" :sort-field="$sortField" :sort-direction="$sortDirection">
            Nama
        </x-sortable-header>

        <x-sortable-header field="created_at" :sort-field="$sortField" :sort-direction="$sortDirection">
            Tanggal
        </x-sortable-header>

        <!-- Kolom aksi tidak sortable -->
        <th class="px-6 py-3 ...">Aksi</th>
    </tr>
</thead>
```

### 3. Test

- ✅ Klik header → Sort ascending
- ✅ Klik lagi → Sort descending
- ✅ Klik header lain → Pindah sorting
- ✅ Icon berubah sesuai arah
- ✅ URL query string update

---

## 📖 Dokumentasi Lengkap

**Baca:** [SORTABLE_TABLE_GUIDE.md](SORTABLE_TABLE_GUIDE.md)

Dokumentasi lengkap mencakup:
- ✅ Step-by-step implementation
- ✅ Field mapping guide
- ✅ Best practices
- ✅ Troubleshooting
- ✅ Testing checklist
- ✅ Daftar semua halaman yang perlu diupdate

---

## 🎨 Visual Demo

### Before (Non-Sortable)
```
┌─────────────────────────────────────┐
│ Nama        │ Email      │ Tanggal  │
├─────────────────────────────────────┤
│ Ahmad       │ a@mail.com │ 01/01/25 │
│ Budi        │ b@mail.com │ 02/01/25 │
│ ...         │ ...        │ ...      │
└─────────────────────────────────────┘
```

### After (Sortable) - Dengan Hover & Click

```
┌─────────────────────────────────────┐
│ Nama ↑↓    │ Email ↑↓   │ Tanggal▼ │  ← Active (sorted desc)
├─────────────────────────────────────┤
│ Cecep      │ c@mail.com │ 03/01/25 │
│ Budi       │ b@mail.com │ 02/01/25 │
│ Ahmad      │ a@mail.com │ 01/01/25 │
└─────────────────────────────────────┘
```

**Features:**
- ↑↓ = Sortable columns (neutral state)
- ▲ = Sorted ascending
- ▼ = Sorted descending
- Highlight background untuk kolom aktif

---

## 🔧 Components API

### Trait: `WithSorting`

**Properties:**
```php
public $sortField = 'created_at';      // Default field
public $sortDirection = 'desc';        // Default direction
```

**Methods:**
```php
sortBy($field)                         // Handle click
applySorting($query)                   // Apply to query
getSortIcon($field)                    // Get icon
isSortedBy($field)                     // Check active
```

### Component: `<x-sortable-header>`

**Props:**
```blade
field="nama"                           // Database field name (required)
:sort-field="$sortField"              // Current sorted field
:sort-direction="$sortDirection"      // Current direction
```

**Slot:**
```blade
<x-sortable-header field="nama" ...>
    Nama Lengkap  ← This is the slot (label)
</x-sortable-header>
```

---

## 🎯 Kolom yang Bagus untuk Sortable

✅ **Recommended:**
- Nama/Identitas (nama, NISN, email)
- Tanggal (created_at, updated_at, tanggal_daftar)
- Status (status_pendaftaran, status_verifikasi)
- Lokasi (asal_sekolah, kabupaten, kota)
- Angka (nilai, score)

❌ **Not Recommended:**
- Kolom "No" urut
- Kolom "Aksi" (buttons)
- Kolom dengan kondisi kompleks
- Kolom relasi tanpa join

---

## 📊 Progress Tracking

**Selesai:** 2 / ~25 halaman (8%)

**Next Priority:**
1. Admin\Panitia
2. Admin\Jalur
3. Admin\DataAsalSekolah
4. Admin\Kelulusan
5. ... (lihat daftar lengkap di guide)

---

## 💡 Tips

1. **Test di browser** - Pastikan icon muncul dan sorting bekerja
2. **Check console** - Pastikan tidak ada error JavaScript
3. **URL query** - Sorting tersimpan di `?sortField=nama&sortDirection=asc`
4. **Persistent** - Sorting tetap saat pagination atau filter
5. **Field names** - Harus exact match dengan database column

---

## 🆘 Troubleshooting

**Sorting tidak bekerja?**
- Check apakah trait `WithSorting` sudah di-use
- Check field name di `<x-sortable-header>` sesuai database

**Icon tidak muncul?**
- Check props `:sort-field` dan `:sort-direction` (pakai titik dua)
- Check console browser untuk error

**Column not found?**
- Field name salah atau typo
- Field tidak ada di tabel database

**Lihat troubleshooting lengkap:** [SORTABLE_TABLE_GUIDE.md](SORTABLE_TABLE_GUIDE.md#-troubleshooting)

---

## 📞 Support

**Dokumentasi:**
- [SORTABLE_TABLE_GUIDE.md](SORTABLE_TABLE_GUIDE.md) - Panduan lengkap
- [DOCUMENTATION_INDEX.md](DOCUMENTATION_INDEX.md) - Index semua docs

**Contoh Implementasi:**
- `app/Livewire/Admin/Pendaftar.php`
- `resources/views/livewire/admin/pendaftar.blade.php`

---

**Created:** 2026-01-11
**Status:** In Progress
**Next Update:** Setelah implementasi 5 halaman berikutnya
