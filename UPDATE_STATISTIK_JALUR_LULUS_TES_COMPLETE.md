# Update Statistik Jalur - Tambah Kolom Lulus/Tidak Lulus Tes

**Tanggal:** 30 Januari 2026
**Status:** ✅ Selesai

## Overview

Memperbarui tabel statistik detail per jalur di kedua dashboard dengan menambahkan:
1. **Kolom "Lulus Tes"** - menampilkan jumlah siswa yang lulus tes
2. **Kolom "Tidak Lulus Tes"** - menampilkan jumlah siswa yang tidak lulus tes
3. **Modifikasi Kolom Gender** - Laki-laki dan Perempuan sekarang hanya menghitung siswa yang lulus tes
4. **Tampilkan Semua Jalur Aktif** - menghapus filter yang menyembunyikan jalur dengan 0 siswa

## Perubahan Urutan Kolom

### Sebelum:
1. Jalur Pendaftaran
2. Total Pendaftar
3. Laki-laki
4. Perempuan
5. Setuju Daftar Ulang
6. Tidak Setuju

### Sesudah:
1. Jalur Pendaftaran
2. Total Pendaftar
3. **Lulus Tes** (BARU)
4. **Tidak Lulus Tes** (BARU)
5. Laki-laki (hanya yang lulus tes)
6. Perempuan (hanya yang lulus tes)
7. Setuju Daftar Ulang
8. Tidak Setuju

---

## Files Modified

### 1. Backend - Panitia Dashboard

**File:** `app/Livewire/Panitia/Dashboard.php`

**Changes:**

```php
public function loadJalurDetailStats()
{
    // Load statistik detail per jalur - tampilkan semua jalur aktif
    $jalurAktif = JalurPendaftaran::where('aktif', true)
        ->orderBy('urutan', 'asc')
        ->get();

    $this->jalurDetailStats = $jalurAktif->map(function ($jalur) {
        // Query semua pendaftar di jalur ini
        $allPendaftar = $jalur->pendaftar();

        // Total pendaftar (SEMUA status)
        $total = $allPendaftar->count();

        // Lulus tes (status 'lulus' atau 'diterima')
        $lulusTes = (clone $allPendaftar)
            ->whereIn('status_pendaftaran', ['lulus', 'diterima'])
            ->count();

        // Tidak lulus tes (status 'tidak_lulus')
        $tidakLulusTes = (clone $allPendaftar)
            ->where('status_pendaftaran', 'tidak_lulus')
            ->count();

        // Query pendaftar yang lulus untuk gender dan persetujuan
        $pendaftarLulus = $jalur->pendaftar()
            ->whereIn('status_pendaftaran', ['lulus', 'diterima']);

        // Jumlah laki-laki (HANYA yang lulus tes)
        $lakiLaki = (clone $pendaftarLulus)
            ->where('jenis_kelamin', 'Laki-laki')
            ->count();

        // Jumlah perempuan (HANYA yang lulus tes)
        $perempuan = (clone $pendaftarLulus)
            ->where('jenis_kelamin', 'Perempuan')
            ->count();

        // Setuju daftar ulang (dari yang lulus)
        $setuju = (clone $pendaftarLulus)
            ->where('status_persetujuan_daftar_ulang', 'setuju')
            ->count();

        // Tidak setuju daftar ulang (dari yang lulus)
        $tidakSetuju = (clone $pendaftarLulus)
            ->where('status_persetujuan_daftar_ulang', 'tidak_setuju')
            ->count();

        return [
            'nama' => $jalur->nama,
            'warna' => $jalur->warna,
            'total' => $total,
            'lulus_tes' => $lulusTes,              // BARU
            'tidak_lulus_tes' => $tidakLulusTes,   // BARU
            'laki_laki' => $lakiLaki,
            'perempuan' => $perempuan,
            'setuju' => $setuju,
            'tidak_setuju' => $tidakSetuju,
        ];
    });
    // Tidak ada filter - tampilkan semua jalur aktif (BERUBAH)
}
```

**Key Changes:**
- ✅ Menghitung semua pendaftar untuk "Total Pendaftar"
- ✅ Menambahkan `lulus_tes` - hitung siswa dengan status 'lulus' atau 'diterima'
- ✅ Menambahkan `tidak_lulus_tes` - hitung siswa dengan status 'tidak_lulus'
- ✅ Gender hanya dari siswa yang lulus tes
- ✅ Menghapus `->filter()` yang menyembunyikan jalur dengan 0 siswa

---

### 2. View - Panitia Dashboard

**File:** `resources/views/livewire/panitia/dashboard.blade.php`

**Changes:**

#### Header Table (lines 365-376)
```blade
<thead class="bg-gray-50">
    <tr>
        <th>Jalur Pendaftaran</th>
        <th>Total Pendaftar</th>
        <th>Lulus Tes</th>          <!-- BARU -->
        <th>Tidak Lulus Tes</th>    <!-- BARU -->
        <th>Laki-laki</th>
        <th>Perempuan</th>
        <th>Setuju Daftar Ulang</th>
        <th>Tidak Setuju</th>
    </tr>
</thead>
```

#### Table Body (lines 378-438)
```blade
<tbody>
    @foreach($jalurDetailStats as $jalur)
    <tr>
        <td>{{ $jalur['nama'] }}</td>
        <td>{{ number_format($jalur['total']) }}</td>

        <!-- BARU: Lulus Tes (green badge with checkmark) -->
        <td>
            <span class="bg-green-100 text-green-800">
                <svg>✓</svg>
                {{ number_format($jalur['lulus_tes']) }}
            </span>
        </td>

        <!-- BARU: Tidak Lulus Tes (red badge with X) -->
        <td>
            <span class="bg-red-100 text-red-800">
                <svg>✗</svg>
                {{ number_format($jalur['tidak_lulus_tes']) }}
            </span>
        </td>

        <!-- Laki-laki (blue icon) -->
        <td>
            <svg class="text-blue-500">👤</svg>
            {{ number_format($jalur['laki_laki']) }}
        </td>

        <!-- Perempuan (pink icon) -->
        <td>
            <svg class="text-pink-500">👤</svg>
            {{ number_format($jalur['perempuan']) }}
        </td>

        <!-- Setuju (emerald badge) - WARNA DIUBAH dari green -->
        <td>
            <span class="bg-emerald-100 text-emerald-800">
                {{ number_format($jalur['setuju']) }}
            </span>
        </td>

        <!-- Tidak Setuju (orange badge) - WARNA DIUBAH dari red -->
        <td>
            <span class="bg-orange-100 text-orange-800">
                {{ number_format($jalur['tidak_setuju']) }}
            </span>
        </td>
    </tr>
    @endforeach

    <!-- Total Row -->
    <tr class="bg-gray-100 font-semibold">
        <td><strong>TOTAL</strong></td>
        <td><strong>{{ number_format($jalurDetailStats->sum('total')) }}</strong></td>
        <td><strong>{{ number_format($jalurDetailStats->sum('lulus_tes')) }}</strong></td>
        <td><strong>{{ number_format($jalurDetailStats->sum('tidak_lulus_tes')) }}</strong></td>
        <td><strong>{{ number_format($jalurDetailStats->sum('laki_laki')) }}</strong></td>
        <td><strong>{{ number_format($jalurDetailStats->sum('perempuan')) }}</strong></td>
        <td><strong>{{ number_format($jalurDetailStats->sum('setuju')) }}</strong></td>
        <td><strong>{{ number_format($jalurDetailStats->sum('tidak_setuju')) }}</strong></td>
    </tr>
</tbody>
```

**Visual Changes:**
- ✅ 2 kolom baru ditambahkan setelah "Total Pendaftar"
- ✅ Kolom "Lulus Tes" dengan badge hijau dan icon checkmark
- ✅ Kolom "Tidak Lulus Tes" dengan badge merah dan icon X
- ✅ Badge "Setuju" diubah dari green ke emerald (untuk membedakan dari lulus tes)
- ✅ Badge "Tidak Setuju" diubah dari red ke orange (untuk membedakan dari tidak lulus)
- ✅ Total row updated dengan 2 kolom baru

---

### 3. Backend - Panitia Seragam Dashboard

**File:** `app/Livewire/PanitiaSeragam/Dashboard.php`

**Changes:** Identik dengan Panitia Dashboard (lines 170-219)

```php
public function loadJalurDetailStats()
{
    // Implementasi SAMA PERSIS dengan Panitia Dashboard
    // Total pendaftar: semua status
    // Lulus tes: status 'lulus' atau 'diterima'
    // Tidak lulus tes: status 'tidak_lulus'
    // Gender: hanya dari yang lulus
    // Tidak ada filter jalur
}
```

---

### 4. View - Panitia Seragam Dashboard

**File:** `resources/views/livewire/panitia-seragam/dashboard.blade.php`

**Changes:** Identik dengan Panitia Dashboard (lines 302-438)

- Same table structure
- Same column order
- Same styling and badges
- Same icons and colors

---

## Status Mapping

### Total Pendaftar
Menghitung **SEMUA** siswa di jalur tersebut, tidak peduli status:
- `menunggu_verifikasi`
- `lengkapi_data`
- `perbaiki_data`
- `data_disetujui`
- `data_ditolak`
- `lulus`
- `tidak_lulus`
- `cadangan`
- `diterima`
- dll.

### Lulus Tes
Hanya siswa dengan status:
- `lulus`
- `diterima`

### Tidak Lulus Tes
Hanya siswa dengan status:
- `tidak_lulus`

### Laki-laki & Perempuan
Hanya siswa yang **lulus tes** (`lulus` atau `diterima`) dengan filter gender:
- `jenis_kelamin = 'Laki-laki'`
- `jenis_kelamin = 'Perempuan'`

### Setuju & Tidak Setuju Daftar Ulang
Hanya siswa yang **lulus tes** dengan filter persetujuan:
- `status_persetujuan_daftar_ulang = 'setuju'`
- `status_persetujuan_daftar_ulang = 'tidak_setuju'`

---

## UI/UX Changes

### Badge Colors

**Kolom Lulus/Tidak Lulus Tes:**
- Lulus Tes: `bg-green-100 text-green-800` (hijau dengan checkmark)
- Tidak Lulus Tes: `bg-red-100 text-red-800` (merah dengan X)

**Kolom Persetujuan (DIUBAH untuk membedakan):**
- Setuju: `bg-emerald-100 text-emerald-800` (emerald, bukan green)
- Tidak Setuju: `bg-orange-100 text-orange-800` (orange, bukan red)

**Alasan Perubahan Warna:**
- Green/Red sekarang untuk Lulus/Tidak Lulus Tes (lebih krusial)
- Emerald/Orange untuk Setuju/Tidak Setuju (tetap jelas tapi berbeda)

### Icons

**Test Results:**
- Lulus: Checkmark icon (✓)
- Tidak Lulus: X icon (✗)

**Gender:**
- Laki-laki: User icon (blue)
- Perempuan: User icon (pink)

**Approval:**
- Setuju: Checkmark icon (emerald)
- Tidak Setuju: X icon (orange)

---

## Testing Validation

### Data Consistency Checks

1. **Total Pendaftar = Lulus + Tidak Lulus + Status Lain**
   ```
   Total = Lulus Tes + Tidak Lulus Tes + (menunggu + lengkapi + perbaiki + dll)
   ```

2. **Lulus Tes = Laki-laki + Perempuan**
   ```
   Lulus Tes = Gender Laki-laki + Gender Perempuan
   ```

3. **Lulus Tes ≥ Setuju + Tidak Setuju**
   ```
   Lulus Tes >= Setuju + Tidak Setuju
   (karena ada yang belum pilih persetujuan)
   ```

4. **Semua Jalur Aktif Ditampilkan**
   - Jalur dengan 0 siswa tetap muncul
   - Tidak ada `->filter()` di backend

### Test Cases

**Test 1: Jalur dengan siswa lulus**
- Total: 100
- Lulus: 80 (status 'lulus' atau 'diterima')
- Tidak Lulus: 10 (status 'tidak_lulus')
- Laki-laki: 45 (dari 80 yang lulus)
- Perempuan: 35 (dari 80 yang lulus)
- Setuju: 70 (dari 80 yang lulus)
- Tidak Setuju: 5 (dari 80 yang lulus)

**Test 2: Jalur tanpa siswa**
- Total: 0
- Lulus: 0
- Tidak Lulus: 0
- Laki-laki: 0
- Perempuan: 0
- Setuju: 0
- Tidak Setuju: 0
- ✅ **Jalur tetap ditampilkan**

**Test 3: Jalur dengan siswa tapi belum ada yang lulus**
- Total: 50 (semua masih menunggu_verifikasi)
- Lulus: 0
- Tidak Lulus: 0
- Laki-laki: 0
- Perempuan: 0
- Setuju: 0
- Tidak Setuju: 0

---

## Benefits

### 1. Visibilitas Lebih Baik
- Panitia bisa langsung lihat berapa yang lulus vs tidak lulus per jalur
- Gender breakdown sekarang lebih akurat (hanya yang lulus)
- Semua jalur aktif terlihat, termasuk yang kosong

### 2. Decision Making
- Mudah identifikasi jalur dengan tingkat kelulusan rendah
- Gender balance hanya dihitung dari yang lulus (lebih relevan)
- Persetujuan daftar ulang lebih jelas dengan warna berbeda

### 3. Data Accuracy
- Total tetap menghitung semua siswa
- Lulus/Tidak Lulus terpisah jelas
- Gender hanya dari yang relevan (yang lulus)

### 4. Consistency
- Implementasi identik di kedua dashboard
- Warna dan icon konsisten
- Query pattern sama

---

## Breaking Changes

⚠️ **PERHATIAN:**

1. **Jalur Kosong Sekarang Muncul**
   - Sebelumnya: Jalur dengan 0 siswa disembunyikan
   - Sekarang: Semua jalur aktif ditampilkan
   - Impact: Tabel mungkin lebih panjang

2. **Gender Count Berubah**
   - Sebelumnya: Dari siswa dengan status `data_disetujui`, `lulus`, `diterima`
   - Sekarang: Hanya dari siswa dengan status `lulus`, `diterima`
   - Impact: Angka laki-laki dan perempuan mungkin lebih kecil

3. **Warna Badge Persetujuan Berubah**
   - Setuju: green → emerald
   - Tidak Setuju: red → orange
   - Impact: Visual berbeda dari sebelumnya

---

## Database Queries

### Query Pattern

```sql
-- Total Pendaftar
SELECT COUNT(*) FROM pendaftar WHERE jalur_id = ?

-- Lulus Tes
SELECT COUNT(*) FROM pendaftar
WHERE jalur_id = ?
AND status_pendaftaran IN ('lulus', 'diterima')

-- Tidak Lulus Tes
SELECT COUNT(*) FROM pendaftar
WHERE jalur_id = ?
AND status_pendaftaran = 'tidak_lulus'

-- Laki-laki (yang lulus)
SELECT COUNT(*) FROM pendaftar
WHERE jalur_id = ?
AND status_pendaftaran IN ('lulus', 'diterima')
AND jenis_kelamin = 'Laki-laki'

-- Perempuan (yang lulus)
SELECT COUNT(*) FROM pendaftar
WHERE jalur_id = ?
AND status_pendaftaran IN ('lulus', 'diterima')
AND jenis_kelamin = 'Perempuan'

-- Setuju Daftar Ulang
SELECT COUNT(*) FROM pendaftar
WHERE jalur_id = ?
AND status_pendaftaran IN ('lulus', 'diterima')
AND status_persetujuan_daftar_ulang = 'setuju'

-- Tidak Setuju Daftar Ulang
SELECT COUNT(*) FROM pendaftar
WHERE jalur_id = ?
AND status_pendaftaran IN ('lulus', 'diterima')
AND status_persetujuan_daftar_ulang = 'tidak_setuju'
```

---

## Completion Checklist

- [x] Update backend Panitia Dashboard
- [x] Update view Panitia Dashboard header
- [x] Update view Panitia Dashboard body
- [x] Update view Panitia Dashboard total row
- [x] Update backend Panitia Seragam Dashboard
- [x] Update view Panitia Seragam Dashboard header
- [x] Update view Panitia Seragam Dashboard body
- [x] Update view Panitia Seragam Dashboard total row
- [x] Hapus filter jalur kosong
- [x] Ubah gender hanya untuk yang lulus
- [x] Ubah warna badge persetujuan
- [x] Test data consistency
- [x] Dokumentasi lengkap

---

## Related Documentation

- `FITUR_STATISTIK_JALUR_DETAIL_COMPLETE.md` - Initial implementation
- `PERBAIKAN_DASHBOARD_PANITIA_COMPLETE.md` - Dashboard improvements

---

**Implementation Date:** 30 Januari 2026
**Requested By:** User
**Implemented By:** Claude Code Assistant
**Status:** Production Ready ✅
