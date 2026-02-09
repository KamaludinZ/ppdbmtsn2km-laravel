# URL Tabs & Stats Overview Fix - 8 Feb 2026

## ✅ Completed Tasks

### 1. URL Tab Support Added ✅

#### Panitia\DaftarUlang
**File:** `app/Livewire/Panitia/DaftarUlang.php`
**Lines:** 61-67
**Change:** Added query string binding
```php
protected $queryString = [
    'activeTab' => ['except' => 'madrasah'],
    'search' => ['except' => ''],
    'filterStatus' => ['except' => ''],
    'filterJalur' => ['except' => ''],
];
```

#### PanitiaSeragam\DaftarUlang
**File:** `app/Livewire/PanitiaSeragam/DaftarUlang.php`
**Lines:** 25-33
**Change:** Added query string binding + removed old queryString() method
```php
protected $queryString = [
    'activeTab' => ['except' => 'seragam'],
    'search' => ['except' => ''],
    'filterStatusSeragam' => ['except' => ''],
    'filterStatusUkuran' => ['except' => ''],
    'filterJenisKelamin' => ['except' => ''],
    'filterJalur' => ['except' => ''],
];
```

**Deleted:** Old `protected function queryString()` method (lines 89-92)

## 🔄 Pending Tasks

### 2. Stats Overview Fix (In Progress)

#### Pattern from Admin\DaftarUlang (Working Correctly):

**Key Implementation Details:**
1. **Get ALL pendaftar for stats** (not filtered)
2. **Calculate stats based on activeTab**
3. **Use stats partial blade view**: `@include('livewire.admin.partials.daftar-ulang-stats', ['tab' => $activeTab])`

**Admin Stats Logic (lines 305-412):**
```php
// Get ALL pendaftar (no filters for stats)
$allPendaftar = Pendaftar::with(['daftarUlang', 'pembayaran', 'seragam'])->get();

// Get lulus pendaftar
$lulusPendaftar = $allPendaftar->filter(function($p) {
    return in_array($p->status_pendaftaran, ['lulus', 'diterima']);
});

// Calculate stats per tab
if ($this->activeTab === 'madrasah') {
    $stats = [
        'total_pendaftar' => $allPendaftar->count(),
        'lulus' => $lulusPendaftar->count(),
        'setuju' => ...,
        'tidak_setuju' => ...,
        'menunggu' => ...,
        'disetujui' => ...,
        'ditolak' => ...,
        'perlu_perbaikan' => ...,
        'upload_surat' => ...,
    ];
} elseif ($this->activeTab === 'seragam') {
    $stats = [
        'total_pendaftar' => $allPendaftar->count(),
        'lulus' => $lulusPendaftar->count(),
        'menunggu' => ...,         // dari seragam.status_seragam
        'disetujui' => ...,
        'ditolak' => ...,
        'perlu_perbaikan' => ...,
        'ukuran_menunggu' => ...,  // dari seragam.status_ukuran
        'ukuran_disetujui' => ...,
        'ukuran_ditolak' => ...,
    ];
} elseif ($this->activeTab === 'ukuran') {
    $stats = [
        'total_pendaftar' => $allPendaftar->count(),
        'lulus' => $lulusPendaftar->count(),
        'menunggu' => ...,         // dari seragam.status_ukuran
        'disetujui' => ...,
        'ditolak' => ...,
        'perlu_perbaikan' => ...,
    ];
}
```

#### Need to Apply to:

**A. Panitia\DaftarUlang**
- File: `app/Livewire/Panitia/DaftarUlang.php`
- Current issue: Stats may not be calculated correctly
- Fix: Copy stats logic from Admin (lines 305-412)
- Tabs: madrasah only (Panitia DaftarUlang only handles madrasah)

**B. PanitiaSeragam\DaftarUlang**
- File: `app/Livewire/PanitiaSeragam/DaftarUlang.php`
- Current issue: Stats may not be calculated correctly
- Fix: Copy stats logic from Admin (lines 305-412)
- Tabs: seragam, ukuran, settings

#### Stats Partial View to Use:
**File:** `resources/views/livewire/admin/partials/daftar-ulang-stats.blade.php`
- This is a universal stats view that works for all tabs
- Takes `$tab` parameter to determine which cards to show
- Already tested and working in Admin

## 🎯 Implementation Plan

### Step 1: Update Panitia\DaftarUlang render() method
1. Read current render() method
2. Add stats calculation logic from Admin
3. Pass $stats to view
4. Update blade to include stats partial

### Step 2: Update PanitiaSeragam\DaftarUlang render() method
1. Read current render() method
2. Add stats calculation logic from Admin
3. Pass $stats to view
4. Update blade to include stats partial

### Step 3: Verify stats partial exists for Panitia views
1. Check if `resources/views/livewire/panitia/partials/daftar-ulang-stats.blade.php` exists
2. If not, create it (copy from admin)
3. Check if `resources/views/livewire/panitia-seragam/partials/daftar-ulang-stats.blade.php` exists
4. If not, create it (copy from admin)

## 📁 Files Modified So Far

1. ✅ `app/Livewire/Panitia/DaftarUlang.php` - Added queryString
2. ✅ `app/Livewire/PanitiaSeragam/DaftarUlang.php` - Added queryString
3. ⏳ `app/Livewire/Panitia/DaftarUlang.php` - Need to add stats logic
4. ⏳ `app/Livewire/PanitiaSeragam/DaftarUlang.php` - Need to add stats logic
5. ⏳ `resources/views/livewire/panitia/daftar-ulang.blade.php` - Need to add stats include
6. ⏳ `resources/views/livewire/panitia-seragam/daftar-ulang.blade.php` - Need to add stats include

## 📊 Expected Results

### URL Tabs:
- ✅ `/panitia/daftar-ulang?activeTab=madrasah` - Shows madrasah tab
- ✅ `/panitia-seragam/daftar-ulang?activeTab=seragam` - Shows seragam tab
- ✅ `/panitia-seragam/daftar-ulang?activeTab=ukuran` - Shows ukuran tab
- ✅ `/panitia-seragam/daftar-ulang?activeTab=settings` - Shows settings tab

### Stats Overview:
- ✅ Stats show TOTAL counts (not filtered by search/filters)
- ✅ Different stats cards per tab
- ✅ Consistent with Admin stats
- ✅ Same calculation method across all roles

## 🧪 Testing Checklist

### URL Tabs:
1. Navigate to `/panitia/daftar-ulang`
2. Click different tabs
3. Verify URL changes: `?activeTab=madrasah`
4. Copy URL and open in new tab
5. Verify correct tab is active

### Stats Overview:
1. Login as Panitia
2. Go to `/panitia/daftar-ulang`
3. Verify stats display at top
4. Use search/filters
5. Verify stats DON'T change (they show total)
6. Compare with Admin stats - should match

## 📝 Next Steps

Continue with implementing stats logic in:
1. Panitia\DaftarUlang
2. PanitiaSeragam\DaftarUlang
