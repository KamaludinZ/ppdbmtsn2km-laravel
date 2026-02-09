# SYNC PANITIA SETTINGS COMPLETE - 9 Februari 2026

**Status:** 🚧 IN PROGRESS - Backend DONE, Frontend PENDING
**Tanggal:** 9 Februari 2026

---

## 🎯 OBJECTIVES

Sync fitur Settings dari **Admin** ke **Panitia-Seragam** dan **Panitia**, dengan menambahkan tab Pengaturan yang berisi:

1. **Panitia-Seragam:** Tab Pengaturan untuk Daftar Ulang Seragam + Ukuran Seragam
2. **Panitia:** Tab Pengaturan untuk Daftar Ulang Madrasah

---

## ✅ COMPLETED TASKS

### 1. Panitia-Seragam Backend ✅ DONE

**File:** `app/Livewire/PanitiaSeragam/DaftarUlang.php`

**Changes Made:**

#### A. Added WithFileUploads Trait
```php
use Livewire\WithFileUploads;

class DaftarUlang extends Component
{
    use WithPagination, WithFileUploads;  // Added WithFileUploads
```

#### B. Added Settings Properties (Lines 40-75)
```php
// ========== SETTINGS PROPERTIES ==========
// Seragam Settings
public $seragamInfo = '';
public $seragamPackages = [];
public $seragamPaymentOptions = ['lunas' => false, 'angsuran' => false, 'potongan' => false];
public $seragamBanks = [];
public $seragamPaymentStart = null;
public $seragamPaymentEnd = null;
public $seragamRequirePaymentProof = false;
public $seragamEnableOpsiLainnya = false;
public $seragamPackagesEnableOpsiLainnya = false;

// Ukuran Settings
public $ukuranInfo = '';
public $ukuranByGender = false;
public $ukuranJasImage = null;
public $ukuranJasOptions = [];
public $ukuranJasEnableOpsiLainnya = false;
public $ukuranBajuHarianImage = null;
public $ukuranBajuHarianOptions = [];
public $ukuranBajuHarianEnableOpsiLainnya = false;
public $ukuranCelanaRokHarianImage = null;
public $ukuranCelanaRokHarianOptions = [];
public $ukuranCelanaRokHarianEnableOpsiLainnya = false;
public $ukuranBajuOlahragaImage = null;
public $ukuranBajuOlahragaOptions = [];
public $ukuranBajuOlahragaEnableOpsiLainnya = false;
public $ukuranCelanaOlahragaImage = null;
public $ukuranCelanaOlahragaOptions = [];
public $ukuranCelanaOlahragaEnableOpsiLainnya = false;
public $ukuranKopyahImage = null;
public $ukuranKopyahOptions = [];
public $ukuranKopyahEnableOpsiLainnya = false;
// Legacy
public $ukuranAtasanOptions = [];
public $ukuranBawahanOptions = [];
```

#### C. Added loadSettings() Method (Lines 96-127)
Loads all Seragam and Ukuran settings from database

#### D. Updated mount() Method
```php
public function mount()
{
    // ... auth check ...
    // Load settings for tab pengaturan
    $this->loadSettings();
}
```

#### E. Updated switchTab() Method
```php
public function switchTab($tab)
{
    if (in_array($tab, ['seragam', 'ukuran', 'settings'])) {  // Added 'settings'
        $this->activeTab = $tab;
        $this->resetPage();
    }
}
```

#### F. Added Seragam Settings Methods (Lines 581-642)
- `addSeragamPackage()`
- `removeSeragamPackage($index)`
- `addSeragamPackageOption($packageIndex)`
- `removeSeragamPackageOption($packageIndex, $optionIndex)`
- `addSeragamBank()`
- `removeSeragamBank($index)`
- `saveSeragamSettings()`

#### G. Added Ukuran Settings Methods (Lines 644-770)
- `addUkuranJas()`, `removeUkuranJas($index)`
- `addUkuranBajuHarian()`, `removeUkuranBajuHarian($index)`
- `addUkuranCelanaRokHarian()`, `removeUkuranCelanaRokHarian($index)`
- `addUkuranBajuOlahraga()`, `removeUkuranBajuOlahraga($index)`
- `addUkuranCelanaOlahraga()`, `removeUkuranCelanaOlahraga($index)`
- `addUkuranKopyah()`, `removeUkuranKopyah($index)`
- `saveUkuranSettings()` - with image upload handling

**Total Lines Added:** ~200 lines

---

## 🚧 PENDING TASKS

### 2. Panitia-Seragam Frontend 🚧 PENDING

**File:** `resources/views/livewire/panitia-seragam/daftar-ulang.blade.php`

**Changes Needed:**

#### A. Add Settings Tab Button (After line ~45)
```blade
<button
    wire:click="switchTab('settings')"
    class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'settings' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
    <svg class="w-5 h-5 mr-2 {{ $activeTab === 'settings' ? 'text-purple-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
    </svg>
    Pengaturan
</button>
```

#### B. Add Settings Content Section (Before closing div)
Copy from `resources/views/livewire/admin/daftar-ulang.blade.php` lines 505-797 (Settings for Seragam and Ukuran only)

**Sections to copy:**
1. Pengaturan Daftar Ulang Seragam (with hierarchical packages)
2. Pengaturan Ukuran Seragam & Atribut (with size options and image uploads)

**What to EXCLUDE (Admin-only):**
- Pengaturan Umum Daftar Ulang (lines 223-273 in Admin)
- Pengaturan Daftar Ulang Madrasah (lines 275-503 in Admin)

**Total Lines to Add:** ~300 lines

---

### 3. Panitia Backend 🚧 PENDING

**File:** `app/Livewire/Panitia/DaftarUlang.php`

**Changes Needed:**

Similar to PanitiaSeragam, but for Madrasah only:

#### A. Add WithFileUploads Trait

#### B. Add Madrasah Settings Properties
```php
// Madrasah Settings
public $madrasahInfo = '';
public $madrasahTemplateSuratPernyataan = null;
public $existingMadrasahTemplate = null;
public $madrasahPackages = [];
public $madrasahNominals = [];  // DEPRECATED
public $madrasahPaymentNominals = [];  // DEPRECATED
public $madrasahPaymentOptions = ['lunas' => false, 'angsuran' => false, 'potongan' => false];
public $madrasahBanks = [];
public $madrasahPaymentStart = null;
public $madrasahPaymentEnd = null;
public $madrasahRequirePaymentProof = false;
public $madrasahEnableOpsiLainnya = false;
public $madrasahPackagesEnableOpsiLainnya = false;
```

#### C. Add loadSettings() Method

#### D. Update mount() to call loadSettings()

#### E. Update switchTab() to include 'settings'

#### F. Add Madrasah Settings Methods
- `addMadrasahPackage()`
- `removeMadrasahPackage($index)`
- `addMadrasahPackageOption($packageIndex)`
- `removeMadrasahPackageOption($packageIndex, $optionIndex)`
- `addMadrasahBank()`
- `removeMadrasahBank($index)`
- `saveMadrasahSettings()`

**Total Lines to Add:** ~150 lines

---

### 4. Panitia Frontend 🚧 PENDING

**File:** `resources/views/livewire/panitia/daftar-ulang.blade.php`

**Changes Needed:**

#### A. Add Settings Tab Button

#### B. Add Settings Content Section
Copy from `resources/views/livewire/admin/daftar-ulang.blade.php` lines 275-503 (Madrasah settings only)

**Total Lines to Add:** ~230 lines

---

## 📋 ADDITIONAL FIXES (From User Request)

### 5. Fix Kelulusan Stats 🚧 PENDING

**Issue:** Status "menunggu" harus diganti dengan "data disetujui", dan tambahkan status "cadangan"

**Files to Fix:**
- `app/Livewire/Admin/Kelulusan.php`
- `app/Livewire/Panitia/Kelulusan.php`
- `resources/views/livewire/admin/kelulusan.blade.php`
- `resources/views/livewire/panitia/kelulusan.blade.php`

**Changes:**
```php
// OLD:
'menunggu' => $allPendaftar->filter(function($p) {
    return $p->status_kelulusan === 'menunggu';
})->count(),

// NEW:
'data_disetujui' => $allPendaftar->filter(function($p) {
    return $p->status_pendaftaran === 'data_disetujui';
})->count(),
'cadangan' => $allPendaftar->filter(function($p) {
    return $p->status_kelulusan === 'cadangan';
})->count(),
```

---

### 6. Fix Daftar Ulang Stats 🚧 PENDING

**Issue:** "Total Pendaftar" dan "Lulus" harus ambil data dari status_kelulusan di menu kelulusan

**Files to Fix:**
- `app/Livewire/Admin/DaftarUlang.php`
- `app/Livewire/Panitia/DaftarUlang.php`
- `app/Livewire/PanitiaSeragam/DaftarUlang.php`

**Changes:**
```php
// OLD:
$allPendaftar = Pendaftar::whereIn('status_pendaftaran', ['data_disetujui', 'lulus', 'diterima'])->get();

// NEW:
$allPendaftar = Pendaftar::where('status_kelulusan', 'lulus')->get();

$stats = [
    'total_pendaftar' => Pendaftar::count(),
    'lulus' => Pendaftar::where('status_kelulusan', 'lulus')->count(),
    // ... other stats
];
```

---

### 7. Fix Seragam Paket Display (JSON to Readable) 🚧 PENDING

**Issue:** Kolom paket seragam menampilkan JSON raw seperti `{"name":"PAKET LENGKAP",...}`

**Files to Fix:**
- `resources/views/livewire/admin/daftar-ulang.blade.php`
- `resources/views/livewire/panitia-seragam/daftar-ulang.blade.php`

**Solution:**
```blade
<!-- OLD (showing JSON): -->
<td>{{ $item->seragam->paket_seragam }}</td>

<!-- NEW (showing readable name): -->
<td class="px-6 py-4">
    @php
        $paketSeragamData = null;
        if ($item->seragam && $item->seragam->paket_seragam) {
            if (is_string($item->seragam->paket_seragam)) {
                $paketSeragamData = json_decode($item->seragam->paket_seragam, true);
            } elseif (is_array($item->seragam->paket_seragam)) {
                $paketSeragamData = $item->seragam->paket_seragam;
            }
        }
    @endphp

    @if($paketSeragamData && isset($paketSeragamData['name']))
        <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
            {{ $paketSeragamData['name'] }}
        </span>
    @elseif($item->seragam && $item->seragam->paket_seragam === 'paket_lainnya')
        <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
            {{ $item->seragam->paket_seragam_custom_name ?? 'Paket Lainnya' }}
        </span>
    @else
        <span class="text-xs text-gray-500">-</span>
    @endif
</td>
```

---

### 8. Add RTE to Pengumuman 🚧 PENDING

**Issue:** Pengumuman perlu mendukung Rich Text Editor untuk format yang lebih baik

**Files to Fix:**
- `app/Livewire/Admin/Pengumuman.php`
- `app/Livewire/Panitia/Pengumuman.php`
- `resources/views/livewire/admin/pengumuman.blade.php`
- `resources/views/livewire/panitia/pengumuman.blade.php`
- `resources/views/public/pengumuman.blade.php` (display)
- `resources/views/livewire/pendaftar/dashboard.blade.php` (display)

**Solution Options:**

**Option 1: Trix Editor (Built-in Laravel)**
```blade
<!-- In form -->
<input id="isi_pengumuman" type="hidden" name="isi_pengumuman" wire:model="isi_pengumuman">
<trix-editor input="isi_pengumuman"></trix-editor>

<!-- Load Trix -->
<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
<script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
```

**Option 2: TinyMCE (More Features)**
```blade
<textarea id="isi_pengumuman" wire:model="isi_pengumuman"></textarea>

<script src="https://cdn.tiny.cloud/1/YOUR_API_KEY/tinymce/6/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: '#isi_pengumuman',
    plugins: 'lists link image',
    toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist'
});
</script>
```

**Option 3: Quill Editor (Lightweight)**
```blade
<div id="editor" wire:ignore></div>
<input type="hidden" wire:model="isi_pengumuman">

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
var quill = new Quill('#editor', {
    theme: 'snow',
    modules: {
        toolbar: [
            ['bold', 'italic', 'underline'],
            ['link', 'image'],
            [{ list: 'ordered' }, { list: 'bullet' }]
        ]
    }
});

quill.on('text-change', function() {
    @this.set('isi_pengumuman', quill.root.innerHTML);
});
</script>
```

**Display (with HTML rendering):**
```blade
<!-- Use {!! !!} untuk render HTML -->
<div class="prose prose-sm max-w-none">
    {!! $pengumuman->isi_pengumuman !!}
</div>
```

**Recommended:** Option 1 (Trix) - Built-in Laravel, simple, secure

---

## 📂 FILES SUMMARY

### Files Modified (Backend):
1. ✅ `app/Livewire/PanitiaSeragam/DaftarUlang.php` - DONE
2. 🚧 `app/Livewire/Panitia/DaftarUlang.php` - PENDING
3. 🚧 `app/Livewire/Admin/Kelulusan.php` - PENDING (stats fix)
4. 🚧 `app/Livewire/Panitia/Kelulusan.php` - PENDING (stats fix)
5. 🚧 `app/Livewire/Admin/DaftarUlang.php` - PENDING (stats fix)
6. 🚧 `app/Livewire/Admin/Pengumuman.php` - PENDING (RTE)
7. 🚧 `app/Livewire/Panitia/Pengumuman.php` - PENDING (RTE)

### Files Modified (Frontend):
8. 🚧 `resources/views/livewire/panitia-seragam/daftar-ulang.blade.php` - PENDING
9. 🚧 `resources/views/livewire/panitia/daftar-ulang.blade.php` - PENDING
10. 🚧 `resources/views/livewire/admin/daftar-ulang.blade.php` - PENDING (paket display fix)
11. 🚧 `resources/views/livewire/admin/kelulusan.blade.php` - PENDING (stats display)
12. 🚧 `resources/views/livewire/panitia/kelulusan.blade.php` - PENDING (stats display)
13. 🚧 `resources/views/livewire/admin/pengumuman.blade.php` - PENDING (RTE)
14. 🚧 `resources/views/livewire/panitia/pengumuman.blade.php` - PENDING (RTE)
15. 🚧 `resources/views/public/pengumuman.blade.php` - PENDING (RTE display)
16. 🚧 `resources/views/livewire/pendaftar/dashboard.blade.php` - PENDING (RTE display)

**Total Files:** 16 files (1 done, 15 pending)

---

## 🚀 NEXT STEPS

1. ✅ Complete Panitia-Seragam Backend - DONE
2. 🔄 Complete Panitia-Seragam Frontend - **IN PROGRESS**
3. ⏳ Complete Panitia Backend
4. ⏳ Complete Panitia Frontend
5. ⏳ Fix Kelulusan Stats
6. ⏳ Fix Daftar Ulang Stats
7. ⏳ Fix Seragam Paket Display
8. ⏳ Add RTE to Pengumuman
9. ⏳ Create upload scripts
10. ⏳ Create comprehensive testing checklist

---

## 📝 NOTES

- All changes follow Admin structure exactly
- Panitia-Seragam: Seragam + Ukuran settings only (no Madrasah)
- Panitia: Madrasah settings only (no Seragam/Ukuran)
- Settings are read-only for Panitia (same as Admin)
- All save methods dispatch 'saved' event for notifications

**Estimated Time:** 2-3 hours for all tasks
**Priority:** HIGH - Multiple user-requested fixes
**Risk Level:** LOW - Follows existing patterns

---

**Documentation Status:** COMPLETE ✅
**Backend Status:** 25% COMPLETE (1/4 done)
**Frontend Status:** 0% COMPLETE (0/4 done)
**Fixes Status:** 0% COMPLETE (0/4 done)
