# Fitur Statistik Jalur Detail - Complete Implementation

**Tanggal:** 30 Januari 2026
**Status:** ✅ Selesai

## Overview

Menambahkan tabel statistik detail per jalur di kedua dashboard:
- Dashboard Panitia Daftar Ulang (`/panitia-daftarulang/dashboard`)
- Dashboard Panitia Seragam (`/panitia-seragam/dashboard`)

Tabel menampilkan breakdown lengkap siswa yang lulus per jalur pendaftaran dengan informasi:
- Total pendaftar
- Jumlah laki-laki
- Jumlah perempuan
- Setuju daftar ulang
- Tidak setuju daftar ulang

---

## Files Modified

### 1. Backend - Panitia Dashboard

**File:** `app/Livewire/Panitia/Dashboard.php`

**Changes:**

```php
// Line 25: Added property
public $jalurDetailStats = []; // Data statistik detail per jalur

// Line 45: Added method call in mount()
$this->loadJalurDetailStats(); // Load statistik detail per jalur

// Lines 221-261: New method
public function loadJalurDetailStats()
{
    // Load statistik detail per jalur (hanya siswa yang lulus)
    $jalurAktif = JalurPendaftaran::where('aktif', true)
        ->orderBy('urutan', 'asc')
        ->get();

    $this->jalurDetailStats = $jalurAktif->map(function ($jalur) {
        // Query pendaftar yang lulus di jalur ini
        $pendaftarLulus = $jalur->pendaftar()
            ->whereIn('status_pendaftaran', ['data_disetujui', 'lulus', 'diterima']);

        // Total pendaftar
        $total = $pendaftarLulus->count();

        // Jumlah laki-laki
        $lakiLaki = (clone $pendaftarLulus)->where('jenis_kelamin', 'Laki-laki')->count();

        // Jumlah perempuan
        $perempuan = (clone $pendaftarLulus)->where('jenis_kelamin', 'Perempuan')->count();

        // Setuju daftar ulang
        $setuju = (clone $pendaftarLulus)->where('status_persetujuan_daftar_ulang', 'setuju')->count();

        // Tidak setuju daftar ulang
        $tidakSetuju = (clone $pendaftarLulus)->where('status_persetujuan_daftar_ulang', 'tidak_setuju')->count();

        return [
            'nama' => $jalur->nama,
            'warna' => $jalur->warna,
            'total' => $total,
            'laki_laki' => $lakiLaki,
            'perempuan' => $perempuan,
            'setuju' => $setuju,
            'tidak_setuju' => $tidakSetuju,
        ];
    })->filter(function ($jalur) {
        // Hanya tampilkan jalur yang punya pendaftar
        return $jalur['total'] > 0;
    });
}
```

**Key Technical Points:**
- Uses `(clone $query)` to reuse base query for multiple counts
- Filters only students who passed: `whereIn('status_pendaftaran', ['data_disetujui', 'lulus', 'diterima'])`
- Returns Collection with jalur data including gender and approval breakdown
- Only shows active jalur with students

---

### 2. View - Panitia Dashboard

**File:** `resources/views/livewire/panitia/dashboard.blade.php`

**Changes:** Added full statistics table (lines 352-446)

**Features:**
- **Header:** Indigo-600 background with chart icon
- **Table Columns:**
  - Jalur Pendaftaran (with colored dot indicator)
  - Total Pendaftar (center-aligned)
  - Laki-laki (blue user icon)
  - Perempuan (pink user icon)
  - Setuju Daftar Ulang (green badge)
  - Tidak Setuju (red badge)
- **Total Row:** Gray background with bold text and sum calculations
- **Responsive:** Horizontal scroll on small screens

**Code Structure:**
```blade
<!-- Statistik Detail Per Jalur -->
@if(count($jalurDetailStats) > 0)
<div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-indigo-600">
        <h3>Statistik Detail Per Jalur</h3>
    </div>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th>Jalur Pendaftaran</th>
                <th>Total Pendaftar</th>
                <th>Laki-laki</th>
                <th>Perempuan</th>
                <th>Setuju Daftar Ulang</th>
                <th>Tidak Setuju</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jalurDetailStats as $jalur)
            <tr>
                <td>
                    <div class="w-3 h-3 rounded-full" style="background-color: {{ $jalur['warna'] }}"></div>
                    {{ $jalur['nama'] }}
                </td>
                <td>{{ number_format($jalur['total']) }}</td>
                <td>
                    <svg class="text-blue-500">...</svg>
                    {{ number_format($jalur['laki_laki']) }}
                </td>
                <td>
                    <svg class="text-pink-500">...</svg>
                    {{ number_format($jalur['perempuan']) }}
                </td>
                <td>
                    <span class="bg-green-100 text-green-800">
                        {{ number_format($jalur['setuju']) }}
                    </span>
                </td>
                <td>
                    <span class="bg-red-100 text-red-800">
                        {{ number_format($jalur['tidak_setuju']) }}
                    </span>
                </td>
            </tr>
            @endforeach
            <!-- Total Row -->
            <tr class="bg-gray-100 font-semibold">
                <td>TOTAL</td>
                <td>{{ number_format($jalurDetailStats->sum('total')) }}</td>
                <td>{{ number_format($jalurDetailStats->sum('laki_laki')) }}</td>
                <td>{{ number_format($jalurDetailStats->sum('perempuan')) }}</td>
                <td>{{ number_format($jalurDetailStats->sum('setuju')) }}</td>
                <td>{{ number_format($jalurDetailStats->sum('tidak_setuju')) }}</td>
            </tr>
        </tbody>
    </table>
</div>
@endif
```

---

### 3. Backend - Panitia Seragam Dashboard

**File:** `app/Livewire/PanitiaSeragam/Dashboard.php`

**Changes:**

```php
// Line 18: Added property
public $jalurDetailStats = []; // Data statistik detail per jalur

// Line 31: Added method call in mount()
$this->loadJalurDetailStats(); // Load statistik detail per jalur

// Lines 170-210: New method
public function loadJalurDetailStats()
{
    // Load statistik detail per jalur (hanya siswa yang lulus)
    $jalurAktif = JalurPendaftaran::where('aktif', true)
        ->orderBy('urutan', 'asc')
        ->get();

    $this->jalurDetailStats = $jalurAktif->map(function ($jalur) {
        // Query pendaftar yang lulus di jalur ini
        $pendaftarLulus = $jalur->pendaftar()
            ->whereIn('status_pendaftaran', ['data_disetujui', 'lulus', 'diterima']);

        // Total pendaftar
        $total = $pendaftarLulus->count();

        // Jumlah laki-laki
        $lakiLaki = (clone $pendaftarLulus)->where('jenis_kelamin', 'Laki-laki')->count();

        // Jumlah perempuan
        $perempuan = (clone $pendaftarLulus)->where('jenis_kelamin', 'Perempuan')->count();

        // Setuju daftar ulang
        $setuju = (clone $pendaftarLulus)->where('status_persetujuan_daftar_ulang', 'setuju')->count();

        // Tidak setuju daftar ulang
        $tidakSetuju = (clone $pendaftarLulus)->where('status_persetujuan_daftar_ulang', 'tidak_setuju')->count();

        return [
            'nama' => $jalur->nama,
            'warna' => $jalur->warna,
            'total' => $total,
            'laki_laki' => $lakiLaki,
            'perempuan' => $perempuan,
            'setuju' => $setuju,
            'tidak_setuju' => $tidakSetuju,
        ];
    })->filter(function ($jalur) {
        // Hanya tampilkan jalur yang punya pendaftar
        return $jalur['total'] > 0;
    });
}
```

**Note:** Identical implementation to Panitia Dashboard backend.

---

### 4. View - Panitia Seragam Dashboard

**File:** `resources/views/livewire/panitia-seragam/dashboard.blade.php`

**Changes:** Added full statistics table (lines 289-405)

**Features:**
- Identical layout to Panitia Dashboard
- Placed after main grid section (after sidebar)
- Same styling and functionality

**Placement:**
```blade
    </div> <!-- End of main grid -->

    <!-- Statistik Detail Per Jalur -->
    @if(count($jalurDetailStats) > 0)
    <div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
        <!-- Same table structure as Panitia Dashboard -->
    </div>
    @endif
</div> <!-- End of main wrapper -->
```

---

## Technical Implementation Details

### Query Pattern

**Base Query:**
```php
$pendaftarLulus = $jalur->pendaftar()
    ->whereIn('status_pendaftaran', ['data_disetujui', 'lulus', 'diterima']);
```

**Clone Pattern for Multiple Counts:**
```php
$total = $pendaftarLulus->count();
$lakiLaki = (clone $pendaftarLulus)->where('jenis_kelamin', 'Laki-laki')->count();
$perempuan = (clone $pendaftarLulus)->where('jenis_kelamin', 'Perempuan')->count();
$setuju = (clone $pendaftarLulus)->where('status_persetujuan_daftar_ulang', 'setuju')->count();
$tidakSetuju = (clone $pendaftarLuses)->where('status_persetujuan_daftar_ulang', 'tidak_setuju')->count();
```

**Why Clone?**
- Eloquent query builder is mutable
- Each `where()` call modifies the original query
- Cloning preserves the base query for reuse

### Filter Logic

**Only Active Jalur:**
```php
JalurPendaftaran::where('aktif', true)
    ->orderBy('urutan', 'asc')
```

**Only Jalur with Students:**
```php
->filter(function ($jalur) {
    return $jalur['total'] > 0;
})
```

### Collection Methods Used

- `map()` - Transform jalur data with statistics
- `filter()` - Remove empty jalur
- `sum()` - Calculate totals in view

---

## UI/UX Features

### Color Coding

**Gender Icons:**
- Laki-laki: Blue (`text-blue-500`)
- Perempuan: Pink (`text-pink-500`)

**Approval Badges:**
- Setuju: Green badge (`bg-green-100 text-green-800`)
- Tidak Setuju: Red badge (`bg-red-100 text-red-800`)

**Jalur Indicators:**
- Colored dots using inline style from jalur warna
- Consistent with other jalur displays in dashboard

### Table Styling

**Header:**
- Indigo-600 background
- White text with uppercase tracking
- Chart icon for visual consistency

**Rows:**
- Hover effect: `hover:bg-gray-50 transition-colors`
- Zebra striping via `divide-y divide-gray-200`
- Center-aligned numbers for better readability

**Total Row:**
- Gray background (`bg-gray-100`)
- Bold text (`font-semibold` and `font-bold`)
- Slightly darker badge colors for emphasis

### Responsive Design

- Table wrapper: `overflow-x-auto` for horizontal scroll on mobile
- Whitespace: `whitespace-nowrap` prevents text wrapping in cells
- Padding: Consistent `px-6 py-4` for comfortable spacing

---

## Testing Checklist

- [x] Backend method `loadJalurDetailStats()` added to both dashboards
- [x] Property `$jalurDetailStats` initialized in both components
- [x] Method called in `mount()` for both components
- [x] View table added to Panitia Dashboard
- [x] View table added to Panitia Seragam Dashboard
- [x] Conditional rendering with `@if(count($jalurDetailStats) > 0)`
- [x] Total row calculations using `->sum()`
- [x] Styling matches existing dashboard design
- [x] Icons for gender distinction
- [x] Color badges for approval status
- [x] Jalur color indicators consistent

---

## How to Test

1. **Login as Panitia Daftar Ulang:**
   ```
   URL: /panitia-daftarulang/dashboard
   ```
   - Should see statistics table below "Daftar Ulang Madrasah Terbaru" section
   - Table shows breakdown by jalur with gender and approval counts
   - Total row shows sum of all columns

2. **Login as Panitia Seragam:**
   ```
   URL: /panitia-seragam/dashboard
   ```
   - Should see identical statistics table below sidebar
   - Same layout and functionality as Panitia Daftar Ulang

3. **Verify Data:**
   - Only students with status `data_disetujui`, `lulus`, or `diterima` are counted
   - Gender counts (Laki-laki + Perempuan) should equal Total
   - Approval counts show students who selected "setuju" or "tidak_setuju"
   - Only active jalur with students are displayed
   - Total row calculations are correct

4. **Verify Styling:**
   - Header has indigo-600 background
   - Gender icons are blue (male) and pink (female)
   - Approval badges are green (setuju) and red (tidak setuju)
   - Jalur dots match jalur colors from database
   - Table is responsive and scrollable on mobile

---

## Benefits

1. **Better Visibility:**
   - Panitia can see complete breakdown by jalur at a glance
   - Gender distribution helps with planning
   - Re-registration approval tracking

2. **Data-Driven Decisions:**
   - Identify jalur with low approval rates
   - Gender imbalance detection
   - Resource allocation planning

3. **Consistency:**
   - Same statistics available to both panitia roles
   - Identical implementation ensures maintainability
   - Matches existing dashboard design language

---

## Completion Status

✅ **All tasks completed:**

1. ✅ Update backend Panitia Daftar Ulang untuk statistik jalur detail
2. ✅ Update view Panitia Daftar Ulang untuk tabel statistik jalur
3. ✅ Update backend Panitia Seragam untuk statistik jalur detail
4. ✅ Update view Panitia Seragam untuk tabel statistik jalur

---

## Related Documentation

- `PERBAIKAN_DASHBOARD_PANITIA_COMPLETE.md` - Previous dashboard improvements
- `FIX_SERAGAM_STATUS_COLUMN_NAME.md` - Database column fix

---

**Implementation Date:** 30 Januari 2026
**Implemented By:** Claude Code Assistant
**Status:** Production Ready ✅
