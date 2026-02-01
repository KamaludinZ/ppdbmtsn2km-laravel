# Fix Admin Daftar Ulang - Complete Documentation

## Masalah yang Diperbaiki

User melaporkan beberapa masalah di halaman `/admin/daftar-ulang?activeTab=madrasah`:

1. ❌ Kolom "Biaya & Pembayaran" hanya menampilkan 1 nilai, padahal ada 2 field berbeda:
   - Nominal Biaya (yang dipilih untuk surat pernyataan)
   - Nominal yang Dibayar (konfirmasi pembayaran)

2. ❌ Kolom "Bank/Metode" tidak menampilkan nama bank

3. ❌ Kolom "Waktu Pembayaran" tidak menampilkan waktu

4. ❌ Kolom "Surat Pernyataan" dan "Bukti Pembayaran" menunjukkan status "Belum upload" padahal sudah di-upload

---

## Solusi yang Diterapkan

### 1. Database Migration - Tambah Field Baru

**File**: `database/migrations/2026_01_30_000001_add_nominal_awal_and_waktu_pembayaran_fields.php`

Menambahkan field baru ke tabel `daftar_ulang` dan `seragam`:

**Tabel `daftar_ulang`:**
- `nominal_biaya_awal` (JSON) - Nominal yang dipilih pertama kali untuk surat pernyataan
- `waktu_pembayaran_madrasah` (TIMESTAMP) - Waktu pembayaran madrasah

**Tabel `seragam`:**
- `nominal_seragam_awal` (JSON) - Nominal yang dipilih pertama kali untuk surat pernyataan
- `waktu_pembayaran_seragam` (TIMESTAMP) - Waktu pembayaran seragam

```bash
php artisan migrate --path=database/migrations/2026_01_30_000001_add_nominal_awal_and_waktu_pembayaran_fields.php
```

---

### 2. Update Model - Tambah Field ke Fillable & Casts

#### app/Models/DaftarUlang.php

**Fillable:**
```php
protected $fillable = [
    'pendaftar_id',
    'nominal_biaya',
    'nominal_biaya_awal',           // ✅ DITAMBAHKAN
    'waktu_pembayaran_madrasah',    // ✅ DITAMBAHKAN
    'status',
    // ... field lainnya
];
```

**Casts:**
```php
protected $casts = [
    'nominal_biaya' => 'array',
    'nominal_biaya_awal' => 'array',              // ✅ DITAMBAHKAN
    'waktu_pembayaran_madrasah' => 'datetime',    // ✅ DITAMBAHKAN
    // ... cast lainnya
];
```

#### app/Models/Seragam.php

**Fillable:**
```php
protected $fillable = [
    'pendaftar_id',
    'nominal_seragam',
    'nominal_seragam_awal',         // ✅ DITAMBAHKAN
    'waktu_pembayaran_seragam',     // ✅ DITAMBAHKAN
    'paket_seragam',
    // ... field lainnya
];
```

**Casts:**
```php
protected $casts = [
    'nominal_seragam' => 'array',
    'nominal_seragam_awal' => 'array',            // ✅ DITAMBAHKAN
    'waktu_pembayaran_seragam' => 'datetime',     // ✅ DITAMBAHKAN
    // ... cast lainnya
];
```

---

### 3. Update Pendaftar Save Logic

#### app/Livewire/Pendaftar/DaftarUlang.php

**Fungsi `saveMadrasah()` - Line 260-266:**

**Sebelum:**
```php
$dataToUpdate = [
    'nominal_biaya' => $konfirmasiNominalData, // Hanya simpan konfirmasi
    'opsi_pembayaran' => $this->opsiPembayaran,
    'metode_pembayaran' => $this->bankPilihan,
];
```

**Sesudah:**
```php
$dataToUpdate = [
    'nominal_biaya_awal' => $nominalData,          // ✅ Nominal awal (surat pernyataan)
    'nominal_biaya' => $konfirmasiNominalData,     // ✅ Konfirmasi pembayaran
    'opsi_pembayaran' => $this->opsiPembayaran,
    'metode_pembayaran' => $this->bankPilihan,
    'waktu_pembayaran_madrasah' => now(),          // ✅ Timestamp pembayaran
];
```

**Fungsi `saveSeragam()` - Line 374-381:**

**Sebelum:**
```php
$dataToUpdate = [
    'paket_seragam' => $this->paketSeragam,
    'nominal_seragam' => $konfirmasiNominalSeragamData, // Hanya simpan konfirmasi
    'opsi_pembayaran_seragam' => $this->opsiPembayaranSeragam,
    'bank_seragam' => $this->bankSeragam,
];
```

**Sesudah:**
```php
$dataToUpdate = [
    'paket_seragam' => $this->paketSeragam,
    'nominal_seragam_awal' => $nominalSeragamData,          // ✅ Nominal awal
    'nominal_seragam' => $konfirmasiNominalSeragamData,     // ✅ Konfirmasi pembayaran
    'opsi_pembayaran_seragam' => $this->opsiPembayaranSeragam,
    'bank_seragam' => $this->bankSeragam,
    'waktu_pembayaran_seragam' => now(),                    // ✅ Timestamp pembayaran
];
```

---

### 4. Update Admin View - Header Tabel

#### resources/views/livewire/admin/daftar-ulang.blade.php

**Tab Madrasah - Line 1043-1051:**

**Sebelum:**
```blade
<th>Surat Pernyataan</th>
<th>Biaya & Pembayaran</th>          <!-- ❌ 1 kolom -->
<th>Opsi Pembayaran</th>
<th>Bank/Metode</th>
<th>Bukti Pembayaran</th>
<th>Waktu Pembayaran</th>
```

**Sesudah:**
```blade
<th>Surat Pernyataan</th>
<th>Nominal Biaya</th>               <!-- ✅ Kolom 1: Nominal awal -->
<th>Nominal yang Dibayar</th>        <!-- ✅ Kolom 2: Konfirmasi -->
<th>Opsi Pembayaran</th>
<th>Bank/Metode</th>
<th>Bukti Pembayaran</th>
<th>Waktu Pembayaran</th>
```

**Tab Seragam - Line 1052-1060:**
Update yang sama dengan tab Madrasah.

---

### 5. Update Admin View - Body Tabel Madrasah

#### A. Kolom Surat Pernyataan (Line 1223-1236)

**Sebelum:**
```blade
@if($item->dokumen->where('jenis_dokumen', 'pernyataan')->first())
    <!-- ❌ SALAH: Cek di tabel dokumen -->
```

**Sesudah:**
```blade
@if($item->daftarUlang && $item->daftarUlang->surat_pernyataan_path)
    <!-- ✅ BENAR: Cek field surat_pernyataan_path -->
    <span class="bg-green-100 text-green-800">✓ Sudah Upload</span>
    <a href="{{ route('pendaftar.download-file', ['type' => 'surat_pernyataan']) }}">
        Lihat File
    </a>
@else
    <span class="bg-red-100 text-red-800">✗ Belum Upload</span>
@endif
```

#### B. Kolom Nominal (Split menjadi 2) - Line 1238-1261

**Kolom "Nominal Biaya" (Awal):**
```blade
<td class="px-6 py-4">
    @if($item->daftarUlang && $item->daftarUlang->nominal_biaya_awal)
        @php $nominalBiayaAwal = $item->daftarUlang->nominal_biaya_awal; @endphp
        <div class="text-xs text-gray-900">
            <div class="font-medium text-gray-700 mb-1">{{ $nominalBiayaAwal['name'] ?? '-' }}</div>
            <div class="font-bold text-blue-700">Rp {{ number_format($nominalBiayaAwal['amount'] ?? 0, 0, ',', '.') }}</div>
        </div>
    @else
        <span class="text-xs text-gray-500">Belum pilih</span>
    @endif
</td>
```

**Kolom "Nominal yang Dibayar" (Konfirmasi):**
```blade
<td class="px-6 py-4">
    @if($item->daftarUlang && $item->daftarUlang->nominal_biaya)
        @php $nominalBiaya = $item->daftarUlang->nominal_biaya; @endphp
        <div class="text-xs text-gray-900">
            <div class="font-medium text-gray-700 mb-1">{{ $nominalBiaya['name'] ?? '-' }}</div>
            <div class="font-bold text-emerald-700">Rp {{ number_format($nominalBiaya['amount'] ?? 0, 0, ',', '.') }}</div>
        </div>
    @else
        <span class="text-xs text-gray-500">Belum konfirmasi</span>
    @endif
</td>
```

#### C. Kolom Bank/Metode - Line 1272-1288

**Sebelum:**
```blade
@if($item->daftarUlang && $item->daftarUlang->metode_pembayaran)
    <span>{{ $item->daftarUlang->bank_name ?? 'Bank' }}</span>
    <!-- ❌ Field bank_name tidak ada -->
@endif
```

**Sesudah:**
```blade
@if($item->daftarUlang && $item->daftarUlang->metode_pembayaran)
    @php
        $metodePembayaran = json_decode($item->daftarUlang->metode_pembayaran, true);
    @endphp
    @if($metodePembayaran && is_array($metodePembayaran))
        <span class="font-medium">{{ $metodePembayaran['name'] ?? '-' }}</span>
        <!-- ✅ Decode JSON dan ambil name -->
    @endif
@endif
```

#### D. Kolom Bukti Pembayaran - Line 1289-1303

**Sebelum:**
```blade
@if($item->pembayaran->where('jenis_pembayaran', 'daftar_ulang')->first())
    <!-- ❌ SALAH: Cek di tabel pembayaran -->
```

**Sesudah:**
```blade
@if($item->daftarUlang && $item->daftarUlang->bukti_pembayaran_madrasah)
    <!-- ✅ BENAR: Cek field bukti_pembayaran_madrasah -->
    <span class="bg-green-100 text-green-800">✓ Sudah Upload</span>
    <a href="{{ route('pendaftar.download-file', ['type' => 'bukti_pembayaran_madrasah']) }}">
        Lihat File
    </a>
@else
    <span class="bg-red-100 text-red-800">✗ Belum Upload</span>
@endif
```

#### E. Kolom Waktu Pembayaran - Line 1304-1314

**Sebelum:**
```blade
@php
    $pembayaranMadrasah = $item->pembayaran->where('jenis_pembayaran', 'daftar_ulang')->first();
@endphp
@if($pembayaranMadrasah && $pembayaranMadrasah->created_at)
    <!-- ❌ SALAH: Ambil dari tabel pembayaran -->
```

**Sesudah:**
```blade
@if($item->daftarUlang && $item->daftarUlang->waktu_pembayaran_madrasah)
    <!-- ✅ BENAR: Ambil dari field waktu_pembayaran_madrasah -->
    <div class="text-xs text-gray-900">
        <div class="font-medium">{{ $item->daftarUlang->waktu_pembayaran_madrasah->format('d/m/Y') }}</div>
        <div class="text-gray-600">{{ $item->daftarUlang->waktu_pembayaran_madrasah->format('H:i') }} WIB</div>
    </div>
@else
    <span class="text-xs text-gray-500">-</span>
@endif
```

---

### 6. Update Admin View - Body Tabel Seragam

Update yang sama diterapkan untuk tab Seragam (Line 1352-1428):

- **Kolom "Nominal Biaya"**: Dari `nominal_seragam_awal`
- **Kolom "Nominal yang Dibayar"**: Dari `nominal_seragam`
- **Kolom "Opsi Pembayaran"**: Dari `opsi_pembayaran_seragam` (bukan `opsi_pembayaran`)
- **Kolom "Bank/Metode"**: Decode JSON dari `bank_seragam`
- **Kolom "Bukti Pembayaran"**: Dari `bukti_pembayaran` (field seragam)
- **Kolom "Waktu Pembayaran"**: Dari `waktu_pembayaran_seragam`

---

## Struktur Data

### 1. Nominal Biaya (JSON)

**Format Data:**
```json
{
    "name": "Lunas",
    "amount": 500000
}
```

Atau untuk "Opsi Lainnya":
```json
{
    "name": "Opsi Lainnya",
    "amount": 750000
}
```

### 2. Metode Pembayaran (JSON)

**Format Data:**
```json
{
    "name": "BNI Syariah"
}
```

### 3. Waktu Pembayaran (Timestamp)

**Format Data:**
```
2026-01-30 14:35:22
```

**Tampilan:**
```
30/01/2026
14:35 WIB
```

---

## Alur Data

### Proses Pendaftar Mengisi Form Madrasah:

1. **Step 1 - Pilih Nominal Biaya (untuk surat pernyataan)**
   - Pendaftar memilih dari dropdown atau "Opsi Lainnya"
   - Disimpan ke: `$nominalData`

2. **Step 2 - Konfirmasi Nominal yang Dibayarkan**
   - Pendaftar memilih dari dropdown atau "Opsi Lainnya"
   - Disimpan ke: `$konfirmasiNominalData`

3. **Step 3 - Pilih Opsi Pembayaran**
   - Lunas / Angsuran / Potongan
   - Disimpan ke: `opsi_pembayaran`

4. **Step 4 - Pilih Bank/Metode**
   - Pilih dari dropdown bank
   - Disimpan ke: `metode_pembayaran` (JSON)

5. **Step 5 - Upload Dokumen**
   - Upload Surat Pernyataan → `surat_pernyataan_path`
   - Upload Bukti Pembayaran → `bukti_pembayaran_madrasah`

6. **Step 6 - Simpan**
   - Timestamp disimpan ke: `waktu_pembayaran_madrasah`

### Data yang Tersimpan di Database:

```php
daftar_ulang:
    nominal_biaya_awal: {"name": "Lunas", "amount": 500000}
    nominal_biaya: {"name": "Lunas", "amount": 500000}
    opsi_pembayaran: "lunas"
    metode_pembayaran: {"name": "BNI Syariah"}
    surat_pernyataan_path: "dokumen/123/surat_pernyataan_..."
    bukti_pembayaran_madrasah: "bukti_bayar/123/bukti_daftar_ulang_..."
    waktu_pembayaran_madrasah: "2026-01-30 14:35:22"
```

---

## Testing Checklist

### Test Tab Madrasah:

- [ ] **Kolom "Surat Pernyataan"**
  - [ ] Status "Sudah Upload" muncul jika file sudah di-upload
  - [ ] Link "Lihat File" berfungsi
  - [ ] Status "Belum Upload" muncul jika file belum di-upload

- [ ] **Kolom "Nominal Biaya"**
  - [ ] Menampilkan nama opsi (misal: "Lunas")
  - [ ] Menampilkan nominal dengan format Rp (misal: "Rp 500.000")
  - [ ] Warna biru (text-blue-700)

- [ ] **Kolom "Nominal yang Dibayar"**
  - [ ] Menampilkan nama opsi (misal: "Lunas" atau "Opsi Lainnya")
  - [ ] Menampilkan nominal dengan format Rp
  - [ ] Warna hijau (text-emerald-700)

- [ ] **Kolom "Opsi Pembayaran"**
  - [ ] Menampilkan: Lunas / Angsuran / Potongan
  - [ ] Badge warna ungu (bg-purple-100)

- [ ] **Kolom "Bank/Metode"**
  - [ ] Menampilkan nama bank (misal: "BNI Syariah")
  - [ ] Tidak lagi menampilkan "Bank" atau field kosong

- [ ] **Kolom "Bukti Pembayaran"**
  - [ ] Status "Sudah Upload" muncul jika file sudah di-upload
  - [ ] Link "Lihat File" berfungsi
  - [ ] Status "Belum Upload" muncul jika file belum di-upload

- [ ] **Kolom "Waktu Pembayaran"**
  - [ ] Menampilkan tanggal (format: dd/mm/yyyy)
  - [ ] Menampilkan waktu (format: HH:mm WIB)
  - [ ] Tidak lagi menampilkan "-" jika sudah bayar

### Test Tab Seragam:

- [ ] **Kolom "Paket Seragam"**
  - [ ] Menampilkan nama paket dengan benar

- [ ] **Kolom "Nominal Biaya"**
  - [ ] Menampilkan nominal awal (warna biru)

- [ ] **Kolom "Nominal yang Dibayar"**
  - [ ] Menampilkan konfirmasi nominal (warna hijau)

- [ ] **Kolom "Opsi Pembayaran"**
  - [ ] Menampilkan: Lunas / Angsuran / Potongan

- [ ] **Kolom "Bank/Metode"**
  - [ ] Menampilkan nama bank dari dropdown

- [ ] **Kolom "Bukti Pembayaran"**
  - [ ] Status upload benar
  - [ ] Link download berfungsi

- [ ] **Kolom "Waktu Pembayaran"**
  - [ ] Menampilkan tanggal dan waktu dengan benar

---

## Files yang Diubah

1. **database/migrations/2026_01_30_000001_add_nominal_awal_and_waktu_pembayaran_fields.php** - Migration baru
2. **app/Models/DaftarUlang.php** - Tambah fillable & casts
3. **app/Models/Seragam.php** - Tambah fillable & casts
4. **app/Livewire/Pendaftar/DaftarUlang.php** - Update save logic
5. **resources/views/livewire/admin/daftar-ulang.blade.php** - Update header & body tabel

---

## Hasil Akhir

✅ **Kolom "Nominal Biaya"** - Menampilkan nominal yang dipilih untuk surat pernyataan (warna biru)

✅ **Kolom "Nominal yang Dibayar"** - Menampilkan konfirmasi nominal pembayaran (warna hijau)

✅ **Kolom "Bank/Metode"** - Menampilkan nama bank dengan benar

✅ **Kolom "Waktu Pembayaran"** - Menampilkan tanggal dan waktu pembayaran

✅ **Kolom "Surat Pernyataan"** - Status upload sudah benar

✅ **Kolom "Bukti Pembayaran"** - Status upload sudah benar

---

**Tanggal**: 30 Januari 2026
**Status**: ✅ Complete
**Testing**: Silakan test dengan flow lengkap dari pendaftar → admin

## Catatan Penting

1. **Data Lama**: Pendaftar yang sudah mengisi sebelum fix ini perlu mengisi ulang karena:
   - `nominal_biaya_awal` masih NULL (belum ada data lama)
   - `waktu_pembayaran_madrasah` masih NULL

2. **Backward Compatibility**: Data tetap bisa dibaca untuk pendaftar lama:
   - Kolom "Nominal Biaya" akan menampilkan "Belum pilih"
   - Kolom "Waktu Pembayaran" akan menampilkan "-"

3. **Download File**: Link download file menggunakan route `pendaftar.download-file` yang sudah ada dengan parameter `type`.
