# Laporan Seragam & Ukuran Improvements - COMPLETE

## Summary
Successfully implemented improvements to Admin Laporan module for Seragam & Ukuran tab:
1. Added gender filter to Seragam & Ukuran tab
2. Updated export data description to reflect detailed uniform size specifications
3. Updated SeragamExport class to export new detailed uniform size fields
4. Added gender and jalur filtering to export functionality

## Changes Made

### 1. Gender Filter for Seragam & Ukuran Tab

**Problem**: Tab "Seragam & Ukuran" di `/admin/laporan` tidak memiliki filter jenis kelamin, padahal berguna untuk memfilter data berdasarkan gender terutama untuk manajemen seragam.

**Solution**: Add gender filter dropdown to the filter section and implement backend filtering logic.

#### File: `resources/views/livewire/admin/laporan.blade.php`

**Before (line 1016-1030):**
```blade
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Jalur Pendaftaran</label>
        <select wire:model.live="filterJalur" class="w-full rounded-lg border-gray-300 shadow-sm">
            <option value="">Semua Jalur</option>
            @foreach($jalurPendaftaran as $jalur)
                <option value="{{ $jalur->id }}">{{ $jalur->nama }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Status Ukuran</label>
        <select wire:model.live="filterStatusSeragam" class="w-full rounded-lg border-gray-300 shadow-sm">
            <option value="">Semua Status</option>
            <option value="menunggu">Menunggu</option>
            <option value="disetujui">Disetujui</option>
            <option value="ditolak">Ditolak</option>
            <option value="perlu_perbaikan">Perlu Perbaikan</option>
        </select>
    </div>
    ...
</div>
```

**After:**
```blade
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Jalur Pendaftaran</label>
        <select wire:model.live="filterJalur" class="w-full rounded-lg border-gray-300 shadow-sm">
            <option value="">Semua Jalur</option>
            @foreach($jalurPendaftaran as $jalur)
                <option value="{{ $jalur->id }}">{{ $jalur->nama }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
        <select wire:model.live="filterJenisKelamin" class="w-full rounded-lg border-gray-300 shadow-sm">
            <option value="">Semua Jenis Kelamin</option>
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Status Ukuran</label>
        <select wire:model.live="filterStatusSeragam" class="w-full rounded-lg border-gray-300 shadow-sm">
            <option value="">Semua Status</option>
            <option value="menunggu">Menunggu</option>
            <option value="disetujui">Disetujui</option>
            <option value="ditolak">Ditolak</option>
            <option value="perlu_perbaikan">Perlu Perbaikan</option>
        </select>
    </div>
    ...
</div>
```

**Changes:**
- Changed grid from `grid-cols-3` to `grid-cols-4`
- Added new "Jenis Kelamin" filter dropdown between "Jalur Pendaftaran" and "Status Ukuran"
- Binds to `filterJenisKelamin` property with `wire:model.live`

**Filter Options:**
- Semua Jenis Kelamin (default)
- Laki-laki
- Perempuan

#### File: `app/Livewire/Admin/Laporan.php`

**Backend Filter Implementation:**

**getSeragamStats() Method (line 394-405):**
```php
private function getSeragamStats()
{
    // Get all pendaftar lulus
    $allPendaftar = Pendaftar::with(['seragam'])
        ->whereIn('status_pendaftaran', ['data_disetujui', 'lulus', 'diterima'])
        ->when($this->filterJalur, function ($q) {
            $q->where('jalur_pendaftaran_id', $this->filterJalur);
        })
        ->when($this->filterJenisKelamin, function ($q) {
            $q->where('jenis_kelamin', $this->filterJenisKelamin);
        })
        ->get();
    ...
}
```

**getUkuranStats() Method (line 440-451):**
```php
private function getUkuranStats()
{
    // Get all pendaftar lulus
    $allPendaftar = Pendaftar::with(['seragam'])
        ->whereIn('status_pendaftaran', ['data_disetujui', 'lulus', 'diterima'])
        ->when($this->filterJalur, function ($q) {
            $q->where('jalur_pendaftaran_id', $this->filterJalur);
        })
        ->when($this->filterJenisKelamin, function ($q) {
            $q->where('jenis_kelamin', $this->filterJenisKelamin);
        })
        ->get();
    ...
}
```

**Benefits:**
- Stats display now reflects filtered data by gender
- Easier to track uniform data by gender
- Better data analysis for gender-specific uniform items
- Consistent filtering experience across all tabs

### 2. Updated Export Data Description

**Problem**: Keterangan "Data yang akan diexport meliputi:" tidak sesuai dengan kolom seragam yang baru dengan spesifikasi detail.

**Solution**: Update description to list all detailed uniform size fields that will actually be exported.

#### File: `resources/views/livewire/admin/laporan.blade.php`

**Before (line 1100-1113):**
```blade
<div class="text-sm text-gray-600">
    <p class="font-semibold mb-2">Data yang akan diexport meliputi:</p>
    <ul class="list-disc list-inside space-y-1 ml-2">
        <li>Data Siswa (Nama, NISN, Jalur)</li>
        <li>Data Ukuran Seragam</li>
        <li>Status Pembayaran dan Verifikasi</li>
        <li>Informasi Tambahan</li>
    </ul>
</div>
```

**After:**
```blade
<div class="text-sm text-gray-600">
    <p class="font-semibold mb-2">Data yang akan diexport meliputi:</p>
    <ul class="list-disc list-inside space-y-1 ml-2">
        <li>Data Pribadi Siswa (Nama, NISN, Jenis Kelamin, Jalur Pendaftaran)</li>
        <li>Ukuran Jas</li>
        <li>Ukuran Baju Olahraga</li>
        <li>Ukuran Celana Olahraga</li>
        <li>Ukuran Baju Seragam Harian</li>
        <li>Ukuran Celana/Rok Harian</li>
        <li>Ukuran Kopyah (khusus laki-laki)</li>
        <li>Paket Seragam yang Dipilih</li>
        <li>Status Ukuran Seragam (Menunggu/Disetujui/Ditolak)</li>
        <li>Status Pembayaran Seragam</li>
    </ul>
</div>
```

**Benefits:**
- Clear understanding of what data will be exported
- Matches actual exported columns
- Shows detailed uniform size specifications
- Helps admin know exactly what information will be in the export file

### 3. Updated SeragamExport Class

**Problem**: SeragamExport class was exporting old generic uniform fields and didn't support gender filtering.

**Solution**: Completely revamp the export class to support new detailed uniform size fields and filtering.

#### File: `app/Exports/SeragamExport.php`

**Updated Constructor (line 15-28):**

**Before:**
```php
protected $status;
protected $search;

public function __construct($status = null, $search = null)
{
    $this->status = $status;
    $this->search = $search;
}
```

**After:**
```php
protected $status;
protected $search;
protected $jenisKelamin;
protected $jalur;

public function __construct($status = null, $search = null, $jenisKelamin = null, $jalur = null)
{
    $this->status = $status;
    $this->search = $search;
    $this->jenisKelamin = $jenisKelamin;
    $this->jalur = $jalur;
}
```

**Updated Query Method (line 30-58):**

Added gender and jalur filtering:
```php
public function query()
{
    $query = Seragam::with(['pendaftar.jalurPendaftaran', 'pendaftar.user']);

    if ($this->status) {
        $query->where('status_ukuran', $this->status);
    }

    if ($this->search) {
        $query->whereHas('pendaftar', function($q) {
            $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
              ->orWhere('nomor_pendaftaran', 'like', '%' . $this->search . '%');
        });
    }

    if ($this->jenisKelamin) {
        $query->whereHas('pendaftar', function($q) {
            $q->where('jenis_kelamin', $this->jenisKelamin);
        });
    }

    if ($this->jalur) {
        $query->whereHas('pendaftar', function($q) {
            $q->where('jalur_pendaftaran_id', $this->jalur);
        });
    }

    return $query->orderBy('created_at', 'desc');
}
```

**Updated Map Method (line 60-86):**

**Before (exported 31 columns with old fields):**
- Old generic fields: ukuran_baju, ukuran_celana, ukuran_topi, etc.
- Many measurement fields: tinggi_badan, berat_badan, lingkar_dada, etc.
- Status and tracking fields

**After (exports 20 columns with new detailed fields):**
```php
public function map($seragam): array
{
    $pendaftar = $seragam->pendaftar;

    return [
        $pendaftar->nomor_pendaftaran ?? '',
        $pendaftar->nama_lengkap ?? '',
        $pendaftar->nisn ?? '',
        $pendaftar->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
        $pendaftar->jalurPendaftaran->nama ?? '',
        $seragam->ukuran_jas ?? '',
        $seragam->ukuran_baju_olahraga ?? '',
        $seragam->ukuran_celana_olahraga ?? '',
        $seragam->ukuran_baju_seragam_harian ?? '',
        $seragam->ukuran_celana_rok_harian ?? '',
        $pendaftar->jenis_kelamin === 'L' ? ($seragam->ukuran_kopyah ?? '') : 'N/A',
        $seragam->paket_seragam ?? '',
        $this->getStatusLabel($seragam->status_ukuran),
        $this->getStatusLabel($seragam->status_seragam),
        $seragam->opsi_pembayaran_seragam ?? '',
        $seragam->bank_seragam ?? '',
        $seragam->bukti_pembayaran ? 'Sudah Upload' : 'Belum Upload',
        $seragam->catatan_ukuran ?? '',
        $seragam->catatan_seragam ?? '',
        $seragam->created_at ? Carbon::parse($seragam->created_at)->format('d-m-Y H:i') : '',
    ];
}
```

**Key Changes in Exported Data:**
1. Added **Jenis Kelamin** column (formatted as "Laki-laki"/"Perempuan")
2. Replaced old generic fields with 6 new detailed uniform size fields:
   - Ukuran Jas
   - Ukuran Baju Olahraga
   - Ukuran Celana Olahraga
   - Ukuran Baju Seragam Harian
   - Ukuran Celana/Rok Harian
   - Ukuran Kopyah (gender-aware: shows size for males, "N/A" for females)
3. Removed unnecessary measurement fields (tinggi_badan, berat_badan, lingkar_dada, etc.)
4. Streamlined export to focus on essential uniform size and payment information

**Updated Headings Method (line 88-112):**

**Before (31 columns):**
```php
'No. Pendaftaran', 'Nama Siswa', 'NISN', 'Jalur Pendaftaran', 'Asal Sekolah', 'No. HP',
'Email', 'Paket Seragam', 'Tinggi Badan (cm)', 'Berat Badan (kg)', 'Ukuran Baju',
'Ukuran Celana', 'Ukuran Topi', 'Ukuran Sepatu', 'Lingkar Dada (cm)',
'Lingkar Pinggang (cm)', 'Panjang Lengan (cm)', 'Opsi Pembayaran', 'Bank',
'Status Seragam', 'Status Ukuran', 'Tanggal Pesan', 'Tanggal Terima',
'Bukti Pembayaran', 'Sudah Diukur', 'Sudah Dipesan', 'Sudah Diterima',
'Catatan', 'Catatan Seragam', 'Catatan Ukuran', 'Tanggal Input'
```

**After (20 columns):**
```php
public function headings(): array
{
    return [
        'No. Pendaftaran',
        'Nama Siswa',
        'NISN',
        'Jenis Kelamin',
        'Jalur Pendaftaran',
        'Ukuran Jas',
        'Ukuran Baju Olahraga',
        'Ukuran Celana Olahraga',
        'Ukuran Baju Seragam Harian',
        'Ukuran Celana/Rok Harian',
        'Ukuran Kopyah (Laki-laki)',
        'Paket Seragam',
        'Status Ukuran',
        'Status Pembayaran Seragam',
        'Opsi Pembayaran',
        'Bank',
        'Bukti Pembayaran',
        'Catatan Ukuran',
        'Catatan Seragam',
        'Tanggal Input'
    ];
}
```

**Benefits:**
- Export now includes gender information for better filtering and analysis
- New detailed uniform size fields match the form and database structure
- Gender-aware kopyah column (shows size for males, N/A for females)
- Cleaner, more focused export without unnecessary measurement fields
- Reduced file size with 20 columns instead of 31
- Headers clearly indicate what data is included

### 4. Updated Export Method in Laporan Component

#### File: `app/Livewire/Admin/Laporan.php`

**exportSeragam() Method (line 522-536):**

**Before:**
```php
public function exportSeragam()
{
    $fileName = 'data_seragam_' . date('Y-m-d_His') . '.xlsx';

    return Excel::download(
        new SeragamExport(
            null,
            null
        ),
        $fileName
    );
}
```

**After:**
```php
public function exportSeragam()
{
    $fileName = 'data_seragam_' . date('Y-m-d_His') . '.xlsx';

    return Excel::download(
        new SeragamExport(
            $this->filterStatusSeragam,
            null,
            $this->filterJenisKelamin,
            $this->filterJalur
        ),
        $fileName
    );
}
```

**Changes:**
- Now passes `filterStatusSeragam` (status filter)
- Passes `filterJenisKelamin` (gender filter)
- Passes `filterJalur` (jalur filter)
- Export now respects all active filters when generating Excel file

**Benefits:**
- Exported data reflects exactly what's shown in filtered stats
- Users can export specific subsets of data (e.g., only male students from specific jalur)
- Better data analysis capabilities
- Consistent filtering between view and export

## Visual Comparison

### Filter Section

**Before:**
```
┌─────────────────────────────────────────────────────────────┐
│ [Jalur Dropdown]  [Status Dropdown]  [Export Button]        │
└─────────────────────────────────────────────────────────────┘
```

**After:**
```
┌──────────────────────────────────────────────────────────────────────────┐
│ [Jalur Dropdown]  [Gender Dropdown]  [Status Dropdown]  [Export Button]  │
└──────────────────────────────────────────────────────────────────────────┘
```

### Export Data Description

**Before:**
```
Data yang akan diexport meliputi:
• Data Siswa (Nama, NISN, Jalur)
• Data Ukuran Seragam
• Status Pembayaran dan Verifikasi
• Informasi Tambahan
```

**After:**
```
Data yang akan diexport meliputi:
• Data Pribadi Siswa (Nama, NISN, Jenis Kelamin, Jalur Pendaftaran)
• Ukuran Jas
• Ukuran Baju Olahraga
• Ukuran Celana Olahraga
• Ukuran Baju Seragam Harian
• Ukuran Celana/Rok Harian
• Ukuran Kopyah (khusus laki-laki)
• Paket Seragam yang Dipilih
• Status Ukuran Seragam (Menunggu/Disetujui/Ditolak)
• Status Pembayaran Seragam
```

## Files Modified

### 1. `resources/views/livewire/admin/laporan.blade.php`
**Changes:**
- Updated filter grid from 3 columns to 4 columns (line 1016)
- Added gender filter dropdown (lines 1029-1037)
- Updated export data description to list detailed uniform fields (lines 1100-1113)

### 2. `app/Livewire/Admin/Laporan.php`
**Changes:**
- Added gender filter to `getSeragamStats()` method (lines 402-404)
- Added gender filter to `getUkuranStats()` method (lines 448-450)
- Updated `exportSeragam()` to pass filter parameters (lines 529-532)

### 3. `app/Exports/SeragamExport.php`
**Changes:**
- Added `$jenisKelamin` and `$jalur` properties (lines 19-20)
- Updated constructor to accept 4 parameters (line 22)
- Added gender and jalur filtering to query (lines 45-55)
- Completely revamped `map()` method with new detailed fields (lines 60-86)
- Updated `headings()` to match new export structure (lines 88-112)

## Database Fields Required

The export now relies on these `seragam` table columns:

```sql
-- Detailed uniform size columns
ukuran_jas VARCHAR(50) NULLABLE
ukuran_baju_olahraga VARCHAR(50) NULLABLE
ukuran_celana_olahraga VARCHAR(50) NULLABLE
ukuran_baju_seragam_harian VARCHAR(50) NULLABLE
ukuran_celana_rok_harian VARCHAR(50) NULLABLE
ukuran_kopyah VARCHAR(50) NULLABLE

-- Seragam info columns
paket_seragam VARCHAR(100) NULLABLE
status_ukuran ENUM('menunggu', 'disetujui', 'ditolak', 'perlu_perbaikan') DEFAULT 'menunggu'
status_seragam ENUM('menunggu', 'disetujui', 'ditolak', 'perlu_perbaikan') DEFAULT 'menunggu'

-- Payment info columns
opsi_pembayaran_seragam VARCHAR(50) NULLABLE
bank_seragam VARCHAR(100) NULLABLE
bukti_pembayaran VARCHAR(255) NULLABLE

-- Notes columns
catatan_ukuran TEXT NULLABLE
catatan_seragam TEXT NULLABLE
```

**Note:** These columns should already exist from the previous migrations. If not, refer to `DAFTAR_ULANG_IMPROVEMENTS_COMPLETE.md` for migration instructions.

## Testing Checklist

### Filter Functionality

**Gender Filter:**
- [ ] Navigate to `/admin/laporan`
- [ ] Click "Seragam & Ukuran" tab
- [ ] Verify gender filter dropdown appears between Jalur and Status filters
- [ ] Select "Laki-laki" - verify stats update to show only male students
- [ ] Select "Perempuan" - verify stats update to show only female students
- [ ] Select "Semua Jenis Kelamin" - verify stats show all students
- [ ] Test combination with Jalur and Status filters
- [ ] Verify stats cards update reactively when filter changes

### Export Data Description

- [ ] Navigate to Seragam & Ukuran tab
- [ ] Scroll to export section
- [ ] Verify "Data yang akan diexport meliputi:" section displays detailed list
- [ ] Confirm all 10 bullet points are shown correctly
- [ ] Verify description matches actual exported columns

### Export Functionality

**Basic Export:**
- [ ] Click "Export Excel" button without any filters
- [ ] Verify Excel file downloads successfully
- [ ] Open Excel file and verify it has 20 columns
- [ ] Verify column headers match the headings list
- [ ] Check that data is populated correctly

**Filtered Export:**
- [ ] Set Jalur filter to specific jalur
- [ ] Set Gender filter to "Laki-laki"
- [ ] Set Status filter to "Disetujui"
- [ ] Click "Export Excel"
- [ ] Verify exported file only contains:
  - Students from selected jalur
  - Only male students
  - Only approved uniform sizes
- [ ] Check kopyah column shows sizes (not N/A) for males

**Gender-Specific Export:**
- [ ] Filter by "Perempuan" only
- [ ] Export data
- [ ] Verify "Jenis Kelamin" column shows "Perempuan" for all rows
- [ ] Verify "Ukuran Kopyah" column shows "N/A" for all rows
- [ ] Confirm no male students in export

**Detailed Fields Verification:**
- [ ] Open exported Excel file
- [ ] Verify these columns exist with data:
  - [ ] No. Pendaftaran
  - [ ] Nama Siswa
  - [ ] NISN
  - [ ] Jenis Kelamin (shows "Laki-laki" or "Perempuan")
  - [ ] Jalur Pendaftaran
  - [ ] Ukuran Jas (shows size or empty)
  - [ ] Ukuran Baju Olahraga
  - [ ] Ukuran Celana Olahraga
  - [ ] Ukuran Baju Seragam Harian
  - [ ] Ukuran Celana/Rok Harian
  - [ ] Ukuran Kopyah (Laki-laki) - shows size for males, "N/A" for females
  - [ ] Paket Seragam
  - [ ] Status Ukuran
  - [ ] Status Pembayaran Seragam
  - [ ] Opsi Pembayaran
  - [ ] Bank
  - [ ] Bukti Pembayaran (shows "Sudah Upload" or "Belum Upload")
  - [ ] Catatan Ukuran
  - [ ] Catatan Seragam
  - [ ] Tanggal Input (formatted dd-mm-yyyy HH:mm)

### Cross-Tab Consistency

- [ ] Verify gender filter persists when switching between Seragam and Ukuran tabs
- [ ] Verify gender filter does NOT appear in other tabs (Pendaftaran, Data Lengkap, Daftar Ulang)
- [ ] Test reset filters button clears gender filter

## Benefits Summary

### 1. Enhanced Filtering
- Gender filter available in Seragam & Ukuran tab
- Better data segmentation by gender for uniform management
- Stats reflect filtered data accurately
- Filtered exports for targeted data analysis

### 2. Accurate Export Description
- Users know exactly what data will be exported
- Clear list of all 10 data categories
- Matches actual exported columns
- Better transparency and user experience

### 3. Modernized Export Data
- Uses new detailed uniform size specifications (6 fields)
- Gender-aware kopyah column
- Cleaner export with 20 columns instead of 31
- Removed unnecessary measurement fields
- Focus on essential uniform and payment information

### 4. Flexible Export Filtering
- Export respects all active filters (jalur, gender, status)
- Generate targeted Excel reports for specific student groups
- Better data analysis capabilities
- Consistent filtering between view and export

### 5. Better Data Management
- Easier to track uniform sizes by gender
- Gender-specific reports for uniform orders
- Kopyah column shows "N/A" for females (gender-appropriate)
- Matches new detailed uniform form structure

## Caches Cleared

All Laravel caches have been cleared:
```bash
php artisan optimize:clear
```

Cleared caches include:
- Config cache ✅
- Application cache ✅
- Compiled views ✅
- Events cache ✅
- Routes cache ✅
- Views cache ✅

## Related Documentation

This improvement builds upon and complements:
- `DAFTAR_ULANG_IMPROVEMENTS_COMPLETE.md` - Contains similar improvements for Daftar Ulang module
- `MADRASAH_PAYMENT_TIME_COLUMN.md` - Payment time tracking for Madrasah tab
- `SERAGAM_TAB_GRID_FIX.md` - Grid layout fixes for stats display

## Migration Notes

**Database Requirements:**
The new detailed uniform size columns should already exist in the `seragam` table from previous migrations. If they don't exist, create them using:

```bash
php artisan make:migration add_detailed_uniform_size_columns_to_seragam_table
```

See `DAFTAR_ULANG_IMPROVEMENTS_COMPLETE.md` for the complete migration code.

## Backward Compatibility

✅ **Fully Backward Compatible**
- All new columns are nullable
- Filter defaults to "Semua Jenis Kelamin" (shows all data)
- Export works even if detailed size fields are empty
- Old data displays with "-" or empty cells for missing size fields
- Gender-aware kopyah column handles both genders appropriately

---

**Date**: 2026-01-25
**Status**: Complete and ready for testing
**Impact**: Admin Laporan module - Seragam & Ukuran tab
**Files Modified**: 3 files
**Backward Compatible**: Yes
**Requires Migration**: No (columns should exist from previous migration)
**Testing Priority**: High (affects reporting and exports)
