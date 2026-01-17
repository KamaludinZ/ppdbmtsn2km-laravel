# Panduan Implementasi Sortable Table Headers

Dokumentasi lengkap untuk menerapkan fitur sorting pada tabel data di semua halaman.

---

## ✅ Yang Sudah Dibuat

### 1. Trait `WithSorting`
**Lokasi:** `app/Traits/WithSorting.php`

Trait yang menyediakan functionality untuk sorting tabel.

**Fitur:**
- Property `$sortField` dan `$sortDirection`
- Method `sortBy($field)` - Handle klik pada header
- Method `applySorting($query)` - Apply sorting ke query
- Method `getSortIcon($field)` - Get icon untuk header
- Method `isSortedBy($field)` - Check apakah field sedang di-sort

### 2. Blade Component `sortable-header`
**Lokasi:** `resources/views/components/sortable-header.blade.php`

Component untuk rendering header tabel yang bisa di-sort.

**Props:**
- `field` - Nama field database untuk sorting
- `sortField` - Field yang sedang aktif di-sort
- `sortDirection` - Arah sorting (asc/desc)

**Visual:**
- Hover effect
- Arrow icon untuk arah sorting
- Highlight untuk column yang aktif

### 3. Contoh Implementasi
- ✅ **Admin\Pendaftar** - Sudah diimplementasi penuh
- ✅ **Panitia\Pendaftar** - Sudah diimplementasi penuh

---

## 📋 Cara Implementasi di Halaman Lain

### Step 1: Update Livewire Component PHP

#### A. Import Trait

Tambahkan use statement:

```php
use App\Traits\WithSorting;
```

#### B. Gunakan Trait di Class

```php
class NamaComponent extends Component
{
    use WithPagination, WithSorting;  // Tambahkan WithSorting

    // ... existing code
}
```

#### C. Update Query String (Optional tapi Recommended)

```php
protected $queryString = [
    'search' => ['except' => ''],
    // ... existing filters
    'sortField' => ['except' => 'created_at'],    // Tambahkan ini
    'sortDirection' => ['except' => 'desc'],      // Tambahkan ini
];
```

#### D. Apply Sorting ke Query

Cari bagian query yang menggunakan `->latest()` atau `->orderBy()`, ganti dengan:

**Sebelum:**
```php
$data = $query->latest('created_at')->paginate($this->perPage);
```

**Sesudah:**
```php
// Apply sorting
$data = $this->applySorting($query)->paginate($this->perPage);
```

### Step 2: Update Blade View

#### A. Cari Table Header

Cari bagian `<thead>` di view file:

```blade
<thead class="bg-gray-50">
    <tr>
        <th class="px-6 py-3 ...">Column Name</th>
        <!-- ... more headers -->
    </tr>
</thead>
```

#### B. Ganti dengan Sortable Header

Untuk kolom yang INGIN dibuat sortable:

**Sebelum:**
```blade
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
    Nama Lengkap
</th>
```

**Sesudah:**
```blade
<x-sortable-header field="nama_lengkap" :sort-field="$sortField" :sort-direction="$sortDirection">
    Nama Lengkap
</x-sortable-header>
```

Untuk kolom yang TIDAK perlu sortable (No, Aksi, dll):

```blade
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
    No
</th>
```

---

## 📝 Daftar Component yang Perlu Diupdate

### Admin Role

- [x] **Admin\Pendaftar** - ✅ Sudah
- [ ] **Admin\Panitia** - File: `app/Livewire/Admin/Panitia.php`
- [ ] **Admin\Jalur** - File: `app/Livewire/Admin/Jalur.php`
- [ ] **Admin\DataAsalSekolah** - File: `app/Livewire/Admin/DataAsalSekolah.php`
- [ ] **Admin\Kelulusan** - File: `app/Livewire/Admin/Kelulusan.php`
- [ ] **Admin\DaftarUlang** - File: `app/Livewire/Admin/DaftarUlang.php`
- [ ] **Admin\Pengumuman** - File: `app/Livewire/Admin/Pengumuman.php`
- [ ] **Admin\Kontak** - File: `app/Livewire/Admin/Kontak.php`
- [ ] **Admin\KontakPanitia** - File: `app/Livewire/Admin/KontakPanitia.php`
- [ ] **Admin\Jadwal** - File: `app/Livewire/Admin/Jadwal.php`
- [ ] **Admin\Informasi** - File: `app/Livewire/Admin/Informasi.php`
- [ ] **Admin\VideoInfografis** - File: `app/Livewire/Admin/VideoInfografis.php`
- [ ] **Admin\EmisInput** - File: `app/Livewire/Admin/EmisInput.php`
- [ ] **Admin\Laporan** - File: `app/Livewire/Admin/Laporan.php`
- [ ] **Admin\Monitoring** - File: `app/Livewire/Admin/Monitoring.php`
- [ ] **Admin\Security** - File: `app/Livewire/Admin/Security.php`
- [ ] **Admin\BackupRestore** - File: `app/Livewire/Admin/BackupRestore.php`
- [ ] **Admin\Notifikasi** - File: `app/Livewire/Admin/Notifikasi.php`

### Panitia Role

- [x] **Panitia\Pendaftar** - ✅ Sudah
- [ ] **Panitia\Jalur** - File: `app/Livewire/Panitia/Jalur.php`
- [ ] **Panitia\DataAsalSekolah** - File: `app/Livewire/Panitia/DataAsalSekolah.php`
- [ ] **Panitia\Kelulusan** - File: `app/Livewire/Panitia/Kelulusan.php`
- [ ] **Panitia\DaftarUlang** - File: `app/Livewire/Panitia/DaftarUlang.php`
- [ ] **Panitia\Pengumuman** - File: `app/Livewire/Panitia/Pengumuman.php`
- [ ] **Panitia\Jadwal** - File: `app/Livewire/Panitia/Jadwal.php`
- [ ] **Panitia\EmisInput** - File: `app/Livewire/Panitia/EmisInput.php`
- [ ] **Panitia\Laporan** - File: `app/Livewire/Panitia/Laporan.php`

### Pendaftar Role

Pendaftar tidak memiliki tabel data yang banyak, tapi bisa diterapkan jika ada.

---

## 🎯 Contoh Mapping Field Database

Berikut adalah field-field umum yang sering digunakan untuk sorting:

| Label Header | Field Database | Sortable? |
|--------------|----------------|-----------|
| No | - | ❌ Tidak |
| Nomor Pendaftaran | `nomor_pendaftaran` | ✅ Ya |
| Nama | `nama_lengkap` atau `nama` | ✅ Ya |
| NISN | `nisn` | ✅ Ya |
| Email | `email` | ✅ Ya |
| Jenis Kelamin | `jenis_kelamin` | ✅ Ya |
| Asal Sekolah | `asal_sekolah` | ✅ Ya |
| Asal Kab/Kota | `asal_kabkota` | ✅ Ya |
| Jalur | `jalur_pendaftaran_id` | ❌ Tidak (gunakan relasi) |
| Status | `status_pendaftaran` atau `status` | ✅ Ya |
| Tanggal Daftar | `tanggal_daftar` atau `created_at` | ✅ Ya |
| Diperbarui | `updated_at` | ✅ Ya |
| Aksi | - | ❌ Tidak |

---

## 💡 Tips & Best Practices

### 1. Field Names Harus Sesuai Database

```blade
<!-- BENAR -->
<x-sortable-header field="nama_lengkap" ...>

<!-- SALAH (jika field di database adalah nama_lengkap) -->
<x-sortable-header field="nama" ...>
```

### 2. Kolom yang Sebaiknya TIDAK Sortable

- **Kolom Nomor Urut (No)** - Karena ini auto-generated
- **Kolom Aksi** - Hanya berisi tombol
- **Kolom dengan Kondisi** - Yang isi nya conditional/computed
- **Kolom Relasi Kompleks** - Kecuali sudah di-eager load

### 3. Kolom yang BAGUS untuk Sortable

- Nama/Identitas (nama, NISN, email, dll)
- Status (status pendaftaran, status verifikasi)
- Tanggal (tanggal daftar, tanggal update)
- Angka (nilai, score, jumlah)
- Lokasi (asal sekolah, kabupaten, kota)
- Jenis Kelamin

### 4. Default Sorting

Atur default sorting di trait property:

```php
public $sortField = 'created_at';      // Default sort by created_at
public $sortDirection = 'desc';        // Default descending (terbaru dulu)
```

Atau bisa override di component:

```php
public $sortField = 'nama_lengkap';    // Default sort by nama
public $sortDirection = 'asc';         // Default ascending (A-Z)
```

### 5. Sorting dengan Relasi

Untuk field yang ada di relasi, perlu sedikit modifikasi:

**Contoh: Sort by nama jalur**

```php
// Di method render()
$query = Pendaftar::with('jalurPendaftaran')
    ->when($this->sortField === 'jalur_nama', function($q) {
        $q->join('jalur_pendaftaran', 'pendaftar.jalur_pendaftaran_id', '=', 'jalur_pendaftaran.id')
          ->orderBy('jalur_pendaftaran.nama', $this->sortDirection)
          ->select('pendaftar.*');
    })
    ->when($this->sortField !== 'jalur_nama', function($q) {
        return $this->applySorting($q);
    });
```

---

## 🔧 Troubleshooting

### Error: "Column not found"

**Penyebab:** Field name salah atau tidak ada di database.

**Solusi:** Cek nama field di database, pastikan sama persis (case-sensitive).

### Sorting Tidak Bekerja

**Penyebab:** Trait tidak digunakan atau method `sortBy()` tidak ada.

**Solusi:**
1. Pastikan `use WithSorting;` ada di class
2. Pastikan trait di-import: `use App\Traits\WithSorting;`

### Icon Tidak Muncul

**Penyebab:** Component tidak menerima props dengan benar.

**Solusi:** Pastikan pass props dengan format:
```blade
:sort-field="$sortField" :sort-direction="$sortDirection"
```

Bukan:
```blade
sort-field="$sortField" sort-direction="$sortDirection"
```

### Sorting Reset Saat Filter

**Penyebab:** Method `resetPage()` di trait sudah handle ini.

**Solusi:** Pastikan query string sudah di-set dengan benar di `$queryString`.

---

## 📊 Testing Checklist

Setelah implementasi, test hal berikut:

- [ ] Klik header pertama kali = Sort ascending
- [ ] Klik header kedua kali = Sort descending
- [ ] Klik header ketiga kali = Sort ascending lagi
- [ ] Klik header lain = Pindah sorting ke column baru (ascending)
- [ ] Icon arrow muncul dan berubah sesuai arah
- [ ] Column yang aktif ter-highlight (bg-gray-50, text-emerald-600)
- [ ] Sorting tetap persisten saat pindah halaman pagination
- [ ] Sorting tetap persisten saat apply filter
- [ ] Query string di URL berubah (`?sortField=nama&sortDirection=asc`)

---

## 🚀 Quick Implementation Script

Untuk mempercepat implementasi, ikuti checklist ini untuk setiap halaman:

### Checklist per Component

```markdown
Component: Admin\NamaComponent
- [ ] 1. Import `use App\Traits\WithSorting;`
- [ ] 2. Add `use WithSorting;` in class
- [ ] 3. Add `sortField` dan `sortDirection` to `$queryString`
- [ ] 4. Replace `->latest()` with `$this->applySorting($query)`
- [ ] 5. Update view: Ganti `<th>` dengan `<x-sortable-header>`
- [ ] 6. Test: Sort ascending, descending, multiple columns
- [ ] 7. Test: Sorting dengan filter dan pagination
```

---

## 📞 Need Help?

Jika ada masalah dalam implementasi:

1. Lihat contoh di `app/Livewire/Admin/Pendaftar.php`
2. Lihat contoh view di `resources/views/livewire/admin/pendaftar.blade.php`
3. Check trait di `app/Traits/WithSorting.php`
4. Check component di `resources/views/components/sortable-header.blade.php`

---

**Dokumentasi dibuat:** 2026-01-11
**Last Updated:** 2026-01-11
**Version:** 1.0
