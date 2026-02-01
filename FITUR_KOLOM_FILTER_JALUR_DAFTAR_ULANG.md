# Fitur Kolom dan Filter Jalur - Daftar Ulang

**Tanggal:** 29 Januari 2026
**Status:** ✅ SELESAI

## Ringkasan

Menambahkan kolom "Jalur" dan filter jalur pendaftaran pada halaman Daftar Ulang untuk tiga role:
1. **Admin** - `/admin/daftar-ulang`
2. **Panitia Daftar Ulang** - `/panitia-daftarulang/daftar-ulang`
3. **Panitia Seragam** - `/panitia-seragam/daftar-ulang`

Fitur ini memungkinkan user untuk:
- Melihat jalur pendaftaran setiap siswa dalam tabel
- Memfilter data berdasarkan jalur pendaftaran tertentu
- Badge jalur dengan warna kustom sesuai pengaturan jalur

---

## 1. Admin - Daftar Ulang

### Backend: `app/Livewire/Admin/DaftarUlang.php`

#### Perubahan:
1. **Tambah property filter** (line 27):
```php
public $filterJalur = '';
```

2. **Tambah eager loading** (line 262):
```php
$query = Pendaftar::with(['daftarUlang', 'pembayaran', 'seragam', 'dokumen', 'jalurPendaftaran'])
```

3. **Tambah logika filter** (lines 285-287):
```php
// Filter jalur
if ($this->filterJalur) {
    $query->where('jalur_pendaftaran_id', $this->filterJalur);
}
```

4. **Tambah data untuk view** (lines 380-388):
```php
// Load jalur pendaftaran untuk filter
$jalurPendaftaran = \App\Models\JalurPendaftaran::where('aktif', true)
    ->orderBy('urutan')
    ->get();

return view('livewire.admin.daftar-ulang', [
    'pendaftar' => $pendaftar,
    'stats' => $stats,
    'jalurPendaftaran' => $jalurPendaftaran
]);
```

### Frontend: `resources/views/livewire/admin/daftar-ulang.blade.php`

#### Perubahan:
1. **Tambah filter dropdown** (lines 63-70) - setelah search, sebelum filter lainnya:
```blade
<div>
    <select wire:model.live="filterJalur" class="w-full sm:w-auto text-sm rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
        <option value="">Semua Jalur</option>
        @foreach($jalurPendaftaran as $jalur)
            <option value="{{ $jalur->id }}">{{ $jalur->nama }}</option>
        @endforeach
    </select>
</div>
```

2. **Tambah kolom header** (line 968) - setelah "Jenis Kelamin":
```blade
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jalur</th>
```

3. **Tambah badge jalur** (lines 1030-1038) - dalam tbody:
```blade
<td class="px-6 py-4 whitespace-nowrap">
    @if($item->jalurPendaftaran)
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full text-white" style="background-color: {{ $item->jalurPendaftaran->warna ?? '#6B7280' }}">
            {{ $item->jalurPendaftaran->nama }}
        </span>
    @else
        <span class="text-sm text-gray-500">-</span>
    @endif
</td>
```

---

## 2. Panitia Daftar Ulang

### Backend: `app/Livewire/Panitia/DaftarUlang.php`

#### Perubahan:
1. **Tambah property filter** (line 26):
```php
public $filterJalur = ''; // Filter jalur
```

2. **Tambah eager loading** (line 166):
```php
$query = Pendaftar::with(['daftarUlang', 'pembayaran', 'seragam', 'dokumen', 'jalurPendaftaran'])
```

3. **Tambah logika filter** (lines 189-191):
```php
// Filter jalur
if ($this->filterJalur) {
    $query->where('jalur_pendaftaran_id', $this->filterJalur);
}
```

4. **Tambah data untuk view** (lines 284-292):
```php
// Load jalur pendaftaran untuk filter
$jalurPendaftaran = \App\Models\JalurPendaftaran::where('aktif', true)
    ->orderBy('urutan')
    ->get();

return view('livewire.panitia.daftar-ulang', [
    'pendaftar' => $pendaftar,
    'stats' => $stats,
    'jalurPendaftaran' => $jalurPendaftaran
]);
```

### Frontend: `resources/views/livewire/panitia/daftar-ulang.blade.php`

#### Perubahan:
1. **Tambah filter dropdown** (lines 60-67):
```blade
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">Jalur</label>
    <select wire:model.live="filterJalur" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
        <option value="">Semua Jalur</option>
        @foreach($jalurPendaftaran as $jalur)
            <option value="{{ $jalur->id }}">{{ $jalur->nama }}</option>
        @endforeach
    </select>
</div>
```

2. **Tambah kolom header** (line 138):
```blade
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jalur</th>
```

3. **Tambah badge jalur** (lines 179-187):
```blade
<td class="px-6 py-4 whitespace-nowrap">
    @if($item->jalurPendaftaran)
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full text-white" style="background-color: {{ $item->jalurPendaftaran->warna ?? '#6B7280' }}">
            {{ $item->jalurPendaftaran->nama }}
        </span>
    @else
        <span class="text-sm text-gray-500">-</span>
    @endif
</td>
```

---

## 3. Panitia Seragam

### Backend: `app/Livewire/PanitiaSeragam/DaftarUlang.php`

#### Perubahan:
1. **Tambah property filter** (line 22):
```php
public $filterJalur = ''; // Filter jalur
```

2. **Eager loading sudah ada** (line 91):
```php
$query = Pendaftar::with(['jalurPendaftaran', 'daftarUlang', 'seragam'])
```

3. **Tambah logika filter** (lines 108-111):
```php
// Jalur filter
if ($this->filterJalur) {
    $query->where('jalur_pendaftaran_id', $this->filterJalur);
}
```

4. **Tambah data untuk view** (lines 133-140):
```php
// Load jalur pendaftaran untuk filter
$jalurPendaftaran = \App\Models\JalurPendaftaran::where('aktif', true)
    ->orderBy('urutan')
    ->get();

return view('livewire.panitia-seragam.daftar-ulang', [
    'pendaftar' => $pendaftar,
    'jalurPendaftaran' => $jalurPendaftaran
]);
```

### Frontend: `resources/views/livewire/panitia-seragam/daftar-ulang.blade.php`

#### Perubahan:
1. **Tambah filter dropdown** (lines 81-90):
```blade
{{-- Filter Jalur --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">Jalur</label>
    <select wire:model.live="filterJalur" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
        <option value="">Semua Jalur</option>
        @foreach($jalurPendaftaran as $jalur)
            <option value="{{ $jalur->id }}">{{ $jalur->nama }}</option>
        @endforeach
    </select>
</div>
```

2. **Update grid filter** - dari 3 kolom ke 4 kolom (line 74):
```blade
<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
```

3. **Kolom header sudah ada** (line 118):
```blade
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jalur</th>
```

4. **Perbaiki badge jalur** (lines 149-157) - dari dynamic Tailwind ke inline style:
```blade
<td class="px-6 py-4 whitespace-nowrap">
    @if($p->jalurPendaftaran)
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full text-white" style="background-color: {{ $p->jalurPendaftaran->warna ?? '#6B7280' }}">
            {{ $p->jalurPendaftaran->nama }}
        </span>
    @else
        <span class="text-sm text-gray-500">-</span>
    @endif
</td>
```

---

## Detail Implementasi

### Filter Jalur
- **Type:** Dropdown select
- **Options:** Semua jalur yang aktif (aktif = true)
- **Default:** "Semua Jalur" (tidak ada filter)
- **Urutan:** Berdasarkan kolom `urutan` pada tabel `jalur_pendaftaran`
- **Binding:** `wire:model.live="filterJalur"` untuk update real-time

### Kolom Jalur
- **Posisi:** Setelah kolom "Nama" atau "Jenis Kelamin" (tergantung layout)
- **Data:** Relasi `jalurPendaftaran` dari model `Pendaftar`
- **Display:** Badge dengan warna kustom
- **Fallback:** Tampilkan "-" jika tidak ada jalur

### Badge Styling
- **Background:** Inline style menggunakan warna dari `jalur.warna`
- **Text Color:** Putih (`text-white`)
- **Default Color:** `#6B7280` (gray-500) jika warna tidak tersedia
- **Classes:** `px-2 inline-flex text-xs leading-5 font-semibold rounded-full`

**Mengapa Inline Style?**
- Dynamic Tailwind classes (`bg-{{ $color }}-100`) tidak di-compile oleh Tailwind
- Inline style memastikan warna kustom ditampilkan dengan benar
- Warna disimpan sebagai hex code (#RRGGBB) di database

---

## Query Optimization

### Eager Loading
Semua implementasi menggunakan eager loading untuk mencegah N+1 query problem:
```php
$query = Pendaftar::with(['jalurPendaftaran', ...])
```

Tanpa eager loading, akan ada 1 query tambahan per siswa untuk mengambil data jalur.
Dengan 100 siswa, ini berarti 100 query tambahan yang tidak perlu!

### Filter Efficiency
Filter menggunakan WHERE clause pada query utama:
```php
if ($this->filterJalur) {
    $query->where('jalur_pendaftaran_id', $this->filterJalur);
}
```

Ini lebih efisien daripada filter di collection karena filter dilakukan di database level.

---

## Testing

### Test Case 1: Filter Jalur
1. Buka halaman daftar ulang
2. Pilih jalur tertentu dari dropdown "Jalur"
3. Verifikasi hanya siswa dari jalur tersebut yang tampil
4. Pilih "Semua Jalur"
5. Verifikasi semua siswa tampil kembali

### Test Case 2: Badge Warna
1. Pastikan setiap jalur memiliki warna berbeda di pengaturan
2. Buka halaman daftar ulang
3. Verifikasi badge jalur tampil dengan warna yang sesuai
4. Untuk siswa tanpa jalur, verifikasi tampil "-"

### Test Case 3: Kombinasi Filter
1. Gunakan filter jalur + filter status
2. Verifikasi hasil sesuai dengan kombinasi filter
3. Gunakan filter jalur + search
4. Verifikasi hasil filter kombinasi bekerja dengan benar

### Test Case 4: Pagination
1. Filter dengan jalur tertentu
2. Navigasi antar halaman pagination
3. Verifikasi filter tetap aktif saat berpindah halaman

---

## Catatan Penting

### ⚠️ Perbedaan Panitia Seragam
- View panitia seragam sudah punya kolom "Jalur" sebelumnya
- Yang ditambahkan hanya filter dropdown dan perbaikan styling badge
- Grid filter diubah dari 3 kolom menjadi 4 kolom

### ✅ Konsistensi Implementasi
- Semua tiga component menggunakan pattern yang sama
- Filter dropdown selalu menggunakan `wire:model.live`
- Badge selalu menggunakan inline style untuk background color
- Eager loading selalu diterapkan untuk optimasi

### 🎨 Design Pattern
**Filter Dropdown:**
- Admin: Style lebih compact, tanpa label
- Panitia: Ada label, styling lebih besar dengan ring focus

**Badge:**
- Semua menggunakan pattern yang sama untuk konsistensi
- White text on colored background untuk readability

---

## Files Modified

### Backend (3 files)
1. `app/Livewire/Admin/DaftarUlang.php`
2. `app/Livewire/Panitia/DaftarUlang.php`
3. `app/Livewire/PanitiaSeragam/DaftarUlang.php`

### Frontend (3 files)
1. `resources/views/livewire/admin/daftar-ulang.blade.php`
2. `resources/views/livewire/panitia/daftar-ulang.blade.php`
3. `resources/views/livewire/panitia-seragam/daftar-ulang.blade.php`

---

## Dependencies

### Models
- `App\Models\Pendaftar` - Model utama dengan relasi `jalurPendaftaran`
- `App\Models\JalurPendaftaran` - Model jalur dengan field `nama`, `warna`, `aktif`, `urutan`

### Relasi
```php
// Di model Pendaftar
public function jalurPendaftaran()
{
    return $this->belongsTo(JalurPendaftaran::class);
}
```

### Database Columns
- `pendaftar.jalur_pendaftaran_id` (foreign key)
- `jalur_pendaftaran.id`
- `jalur_pendaftaran.nama`
- `jalur_pendaftaran.warna`
- `jalur_pendaftaran.aktif`
- `jalur_pendaftaran.urutan`

---

## Troubleshooting

### Problem: Warna badge tidak muncul
**Solution:** Pastikan field `warna` di tabel `jalur_pendaftaran` berisi hex code yang valid (contoh: #FF5733)

### Problem: Filter tidak bekerja
**Solution:**
1. Pastikan `wire:model.live` digunakan (bukan `wire:model`)
2. Cek property `$filterJalur` sudah dideklarasikan public
3. Pastikan method `updatingFilterJalur()` memanggil `resetPage()`

### Problem: N+1 Query
**Solution:** Pastikan `with(['jalurPendaftaran'])` ada di query builder

### Problem: Badge tampil tapi tanpa warna
**Solution:** Gunakan inline style `style="background-color: ..."` bukan dynamic Tailwind class

---

## Status Implementasi

| Component | Backend | Frontend | Status |
|-----------|---------|----------|--------|
| Admin Daftar Ulang | ✅ | ✅ | SELESAI |
| Panitia Daftar Ulang | ✅ | ✅ | SELESAI |
| Panitia Seragam | ✅ | ✅ | SELESAI |

**Total Completion:** 100% ✅

---

**Dokumentasi dibuat:** 29 Januari 2026
**Author:** Claude Code Assistant
