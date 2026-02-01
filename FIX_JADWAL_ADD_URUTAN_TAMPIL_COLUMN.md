# Fix Jadwal - Tambah Kolom Urutan Tampil

## 🎯 Tujuan

Menambahkan kolom "Urutan Tampil" di halaman jadwal untuk admin dan panitia pendaftaran agar user bisa melihat urutan timeline secara visual.

## 📋 Perubahan

### Kolom Baru: "Urutan Tampil"

**Purpose:** Menampilkan urutan/sequence kegiatan di timeline PPDB.

**Display:** Badge bulat dengan warna indigo (purple-blue) menampilkan nomor urutan.

**Position:** Setelah kolom "No", sebelum kolom "Kegiatan".

## 📊 Table Structure

### Before (Missing Column):
```
| No | Kegiatan | Tanggal | Waktu | Tempat | Jalur | Status | Aksi |
```
**Total:** 8 kolom

### After (With Urutan Tampil):
```
| No | Urutan Tampil | Kegiatan | Tanggal | Waktu | Tempat | Jalur | Status | Aksi |
```
**Total:** 9 kolom

## 📁 Files Modified

### 1. `resources/views/livewire/admin/jadwal.blade.php`

#### Table Header - Added Column (Line 74)

**BEFORE:**
```blade
<thead class="bg-gray-50">
    <tr>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">No</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kegiatan</th>
```

**AFTER:**
```blade
<thead class="bg-gray-50">
    <tr>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">No</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Urutan Tampil</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kegiatan</th>
```

#### Table Body - Added Cell (Lines 90-94)

**BEFORE:**
```blade
<tr class="hover:bg-gray-50">
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">
        {{ $data->firstItem() + $index }}
    </td>
    <td class="px-6 py-4">
        <div class="text-sm font-semibold text-gray-900">{{ $item->judul_kegiatan }}</div>
```

**AFTER:**
```blade
<tr class="hover:bg-gray-50">
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">
        {{ $data->firstItem() + $index }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-indigo-100 text-indigo-800 font-bold text-sm">
            {{ $item->urutan ?? '-' }}
        </span>
    </td>
    <td class="px-6 py-4">
        <div class="text-sm font-semibold text-gray-900">{{ $item->judul_kegiatan }}</div>
```

#### Empty State - Updated Colspan (Line 183)

**BEFORE:**
```blade
<td colspan="8" class="px-6 py-12 text-center">
```

**AFTER:**
```blade
<td colspan="9" class="px-6 py-12 text-center">
```

### 2. `resources/views/livewire/panitia/jadwal.blade.php`

**Same changes as admin file:**
- Added header column (Line 74)
- Added body cell (Lines 90-94)
- Updated colspan from 8 to 9 (Line 175)

## 🎨 UI Details

### Kolom "Urutan Tampil"

**Display Component:**
- Badge circular dengan diameter 40px (w-10 h-10)
- Background: Light indigo (`bg-indigo-100`)
- Text color: Dark indigo (`text-indigo-800`)
- Font: Bold, size small
- Center aligned (both horizontal & vertical)

**Value:**
- Displays: `$item->urutan` (integer)
- Fallback: `-` jika urutan null/empty

**Example Display:**
```
┌────┬──────────────┬─────────────┐
│ No │ Urutan Tampil│ Kegiatan    │
├────┼──────────────┼─────────────┤
│ 1  │     ①        │ Pendaftaran │
│ 2  │     ②        │ Seleksi     │
│ 3  │     ③        │ Pengumuman  │
└────┴──────────────┴─────────────┘
```

## 🔍 Data Source

### Model: `TimelinePendaftaran`

**Table:** `timeline_pendaftaran`

**Field:** `urutan`
- **Type:** Integer
- **Fillable:** Yes
- **Cast:** Integer
- **Purpose:** Menentukan urutan tampil timeline di halaman publik

**Query Ordering:**
```php
$query->orderBy('urutan')->orderBy('tanggal_mulai')->paginate($this->perPage);
```

Timeline diurutkan berdasarkan:
1. **Primary:** `urutan` (ASC) - Urutan prioritas
2. **Secondary:** `tanggal_mulai` (ASC) - Jika urutan sama, sort by date

## ✅ Testing Checklist

### Test 1: Column Display - Admin
1. Login sebagai admin
2. Akses `/admin/jadwal`
3. Verifikasi:
   - ✅ Kolom "Urutan Tampil" muncul setelah kolom "No"
   - ✅ Header "URUTAN TAMPIL" uppercase tampil
   - ✅ Width kolom sesuai (w-24)

### Test 2: Column Display - Panitia
1. Login sebagai panitia_pendaftaran
2. Akses `/panitia-pendaftaran/jadwal`
3. Verifikasi:
   - ✅ Kolom "Urutan Tampil" muncul setelah kolom "No"
   - ✅ Header sama dengan admin

### Test 3: Data Display - With Urutan
1. Pastikan ada jadwal dengan urutan terisi (contoh: 1, 2, 3)
2. Access jadwal page
3. Verifikasi:
   - ✅ Badge circular indigo tampil
   - ✅ Angka urutan tampil dengan benar
   - ✅ Badge centered di cell

### Test 4: Data Display - Without Urutan
1. Pastikan ada jadwal dengan urutan NULL/empty
2. Access jadwal page
3. Verifikasi:
   - ✅ Badge tampil dengan "-" di dalamnya
   - ✅ Tidak error atau blank

### Test 5: Ordering
1. Buat beberapa jadwal dengan urutan: 3, 1, 2
2. Access jadwal page
3. Verifikasi:
   - ✅ Jadwal ter-sort berdasarkan urutan: 1, 2, 3
   - ✅ Bukan berdasarkan urutan insert

### Test 6: Empty State
1. Hapus semua jadwal atau filter sehingga tidak ada data
2. Verifikasi:
   - ✅ Empty state centered
   - ✅ No layout break (colspan benar: 9)

### Test 7: Responsive
1. Resize browser ke mobile view
2. Verifikasi:
   - ✅ Horizontal scroll berfungsi
   - ✅ Kolom urutan tetap visible
   - ✅ Badge tidak overflow

### Test 8: Filtering
1. Apply filter Jalur
2. Apply filter Status
3. Verifikasi:
   - ✅ Kolom urutan tetap tampil
   - ✅ Data filtered correctly
   - ✅ Ordering tetap berdasarkan urutan

### Test 9: Pagination
1. Buat lebih dari 10 jadwal
2. Navigate ke page 2
3. Verifikasi:
   - ✅ Kolom urutan tampil di semua page
   - ✅ Ordering consistent across pages

## 🔧 Backend Context

### Component: `app/Livewire/Admin/Jadwal.php`

**Property:**
```php
public $urutan;
```

**Render Query:**
```php
$data = $query->orderBy('urutan')->orderBy('tanggal_mulai')->paginate($this->perPage);
```

**Form Fields:**
- Field `urutan` sudah ada di form modal untuk create/edit
- User bisa set urutan saat create/edit jadwal
- Urutan digunakan untuk sorting

### Component: `app/Livewire/Panitia/Jadwal.php`

Same as admin component - uses same model and query structure.

## 💡 Use Cases

### 1. Timeline Sequence Management
- Admin set urutan: 1, 2, 3, 4, ...
- Public timeline tampil sesuai urutan
- Mudah reorder tanpa ubah tanggal

### 2. Visual Priority
- Badge circular eye-catching
- Quick scan urutan timeline
- Identify gaps in sequence (1, 2, 4 - missing 3)

### 3. Data Validation
- Verify urutan set correctly
- Ensure no duplicate urutan
- Check if urutan logical

### 4. Planning & Coordination
- Panitia pendaftaran lihat sequence
- Coordinate dengan admin
- Understand full timeline flow

## 📊 Column Comparison

### Kolom "No" vs "Urutan Tampil"

| Feature | No | Urutan Tampil |
|---------|-----|---------------|
| **Purpose** | Pagination numbering | Timeline sequence |
| **Source** | `$data->firstItem() + $index` | `$item->urutan` |
| **Changes on** | Page navigation | Never (data field) |
| **Value** | 1-10, 11-20, etc | 1, 2, 3, ... (absolute) |
| **Style** | Plain text, gray | Badge, indigo, bold |
| **Editable** | No | Yes (via form) |

**Example:**
```
Page 1:
| No | Urutan | Kegiatan       |
|----|--------|----------------|
| 1  | 1      | Pendaftaran    |
| 2  | 2      | Verifikasi     |
| 3  | 5      | Pengumuman     | ← Urutan 5 (maybe 3,4 deleted)

Page 2:
| No | Urutan | Kegiatan       |
|----|--------|----------------|
| 11 | 6      | Daftar Ulang   | ← No = 11, but Urutan = 6
| 12 | 7      | MPLS           |
```

## 🚀 Deployment

### Files to Upload:
```
MODIFIED:
- resources/views/livewire/admin/jadwal.blade.php
- resources/views/livewire/panitia/jadwal.blade.php
```

### No Backend Changes:
- ✅ No migration needed (field already exists)
- ✅ No model changes
- ✅ No component changes
- ✅ Pure view update

### Clear Cache After Deploy:
```bash
php artisan view:clear
php artisan cache:clear
```

### Verify After Deploy:
1. Login sebagai admin → Check jadwal page
2. Login sebagai panitia_pendaftaran → Check jadwal page
3. Verify kolom "Urutan Tampil" visible
4. Test with data that has urutan values
5. Test empty state (colspan correct)

## 🎯 Benefits

### Before Fix:
- ❌ Tidak bisa lihat urutan timeline
- ❌ Harus check tanggal untuk understand sequence
- ❌ Tidak ada visual indicator untuk order

### After Fix:
- ✅ Visual jelas untuk timeline sequence
- ✅ Badge eye-catching dengan color coding
- ✅ Easy to identify missing sequence numbers
- ✅ Better understanding of timeline flow
- ✅ Consistent display admin & panitia

## 📝 Notes

### Why Indigo Color?
- Different from other badges (blue for jalur, green for status)
- Eye-catching but not overwhelming
- Consistent with modern timeline UI patterns

### Why Circular Badge?
- Visual distinction from text content
- Center alignment natural for numbers
- Common pattern for sequence indicators

### Why After "No" Column?
- Logical flow: No → Urutan → Content
- No column is pagination index
- Urutan is data sequence
- Both numeric, grouped together

### Fallback "-" for NULL
- Handles edge case gracefully
- Visual indicator that urutan not set
- Prevents empty/broken display

## ✅ Status

**Status:** ✅ **COMPLETE & READY FOR TESTING**

**Fixed On:** 29 Januari 2026
**Fixed By:** Claude Code
**Breaking Changes:** None
**Backward Compatible:** Yes

---

**Dokumentasi:** 29 Januari 2026
**Version:** 1.0.0
