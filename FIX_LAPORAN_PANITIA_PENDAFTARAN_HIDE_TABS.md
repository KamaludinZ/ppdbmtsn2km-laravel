# Fix Laporan Panitia Pendaftaran - Hide Tab Daftar Ulang & Seragam

## 🎯 Tujuan

Menghilangkan akses tab "Daftar Ulang Madrasah" dan "Seragam & Ukuran" dari halaman laporan untuk role **panitia_pendaftaran**, karena tab-tab tersebut tidak relevan untuk role ini.

## 📋 Perubahan

### Role Access Matrix

| Tab | Admin | Panitia Pendaftaran | Panitia Daftar Ulang | Panitia Tes Psikologi |
|-----|-------|---------------------|----------------------|----------------------|
| 📊 Laporan Pendaftaran | ✅ | ✅ | ✅ | ❌ |
| 📝 Data Pendaftar Lengkap | ✅ | ✅ | ✅ | ❌ |
| ✅ Daftar Ulang Madrasah | ✅ | **❌ HIDDEN** | ✅ | ❌ |
| 👕 Seragam & Ukuran | ✅ | **❌ HIDDEN** | ✅ | ❌ |
| 🧠 Tes Psikologi | ✅ | ✅ | ✅ | ✅ (only this tab) |

### Tab Navigation - Before:
```
┌────────────────────────────────────────────────────────────────────────────┐
│ [Pendaftaran] [Data Lengkap] [Daftar Ulang] [Seragam] [Tes Psikologi]     │
└────────────────────────────────────────────────────────────────────────────┘
```
Panitia Pendaftaran melihat semua 5 tab

### Tab Navigation - After:
```
┌────────────────────────────────────────────────────────────────────────────┐
│ [Pendaftaran] [Data Lengkap] [Tes Psikologi]                               │
└────────────────────────────────────────────────────────────────────────────┘
```
Panitia Pendaftaran hanya melihat 3 tab yang relevan

## 📁 Files Modified

### 1. `app/Livewire/Panitia/Laporan.php`

#### Property Added:
```php
public $isPanitiaPendaftaran = false; // Flag untuk panitia pendaftaran
```

#### Mount Method Updated:
```php
public function mount()
{
    // ... existing code ...

    // Detect panitia tes psikologi role
    $this->isPanitiaPsikologi = ($user->role === 'panitia_tes_psikologi');

    // Detect panitia pendaftaran role
    $this->isPanitiaPendaftaran = ($user->role === 'panitia_pendaftaran');

    // ... rest of code ...
}
```

#### Render Method Updated:
Pass `isPanitiaPendaftaran` to view in both return statements:

**For Panitia Tes Psikologi:**
```php
return view('livewire.panitia.laporan', [
    'stats' => [],
    'jalurStats' => [],
    'jalurPendaftaran' => $jalurPendaftaran,
    'tesPsikologiStats' => $tesPsikologiStats,
    'isPanitiaPsikologi' => $this->isPanitiaPsikologi,
    'isPanitiaPendaftaran' => $this->isPanitiaPendaftaran // ADDED
])
```

**For Other Panitia:**
```php
return view('livewire.panitia.laporan', [
    'stats' => $stats,
    'jalurStats' => $jalurStats,
    'jalurPendaftaran' => $jalurPendaftaran,
    'tesPsikologiStats' => $tesPsikologiStats,
    'isPanitiaPsikologi' => $this->isPanitiaPsikologi,
    'isPanitiaPendaftaran' => $this->isPanitiaPendaftaran // ADDED
])
```

### 2. `resources/views/livewire/panitia/laporan.blade.php`

#### Tab Navigation Updated (lines 17-28):

**BEFORE:**
```blade
<button wire:click="switchTab('data_lengkap')" class="...">
    📝 Data Pendaftar Lengkap
</button>
<button wire:click="switchTab('daftar_ulang')" class="...">
    ✅ Daftar Ulang Madrasah
</button>
<button wire:click="switchTab('seragam')" class="...">
    👕 Seragam & Ukuran
</button>
<button wire:click="switchTab('tes_psikologi')" class="...">
    🧠 Tes Psikologi
</button>
```

**AFTER:**
```blade
<button wire:click="switchTab('data_lengkap')" class="...">
    📝 Data Pendaftar Lengkap
</button>
@if(!$isPanitiaPendaftaran)
<button wire:click="switchTab('daftar_ulang')" class="...">
    ✅ Daftar Ulang Madrasah
</button>
<button wire:click="switchTab('seragam')" class="...">
    👕 Seragam & Ukuran
</button>
@endif
<button wire:click="switchTab('tes_psikologi')" class="...">
    🧠 Tes Psikologi
</button>
```

## 🔐 Security & Logic

### Role Detection Logic:
```php
$user = Auth::user();
$this->isPanitiaPendaftaran = ($user->role === 'panitia_pendaftaran');
```

### View Conditional:
```blade
@if(!$isPanitiaPendaftaran)
    <!-- Tab Daftar Ulang Madrasah -->
    <!-- Tab Seragam & Ukuran -->
@endif
```

**Logic:**
- Jika user **BUKAN** panitia_pendaftaran (`!$isPanitiaPendaftaran`) → Tab muncul
- Jika user **ADALAH** panitia_pendaftaran → Tab hidden

## ✅ Testing Checklist

### Test 1: Panitia Pendaftaran Access
1. Login sebagai user dengan role `panitia_pendaftaran`
2. Akses `/panitia-pendaftaran/laporan`
3. Verifikasi:
   - ✅ Tab "📊 Laporan Pendaftaran" muncul
   - ✅ Tab "📝 Data Pendaftar Lengkap" muncul
   - ❌ Tab "✅ Daftar Ulang Madrasah" **TIDAK muncul**
   - ❌ Tab "👕 Seragam & Ukuran" **TIDAK muncul**
   - ✅ Tab "🧠 Tes Psikologi" muncul

### Test 2: Panitia Daftar Ulang Access
1. Login sebagai user dengan role `panitia_daftarulang`
2. Akses `/panitia-daftarulang/laporan`
3. Verifikasi:
   - ✅ Semua 5 tab muncul (tidak ada yang hidden)
   - ✅ Tab "Daftar Ulang Madrasah" dapat diakses
   - ✅ Tab "Seragam & Ukuran" dapat diakses

### Test 3: Panitia Tes Psikologi Access
1. Login sebagai user dengan role `panitia_tes_psikologi`
2. Akses `/panitia-tes-psikologi/laporan`
3. Verifikasi:
   - ✅ Hanya menampilkan halaman Tes Psikologi (behavior existing)
   - ✅ Tidak ada tab navigation (sesuai logic existing)

### Test 4: Admin/Panitia Umum Access
1. Login sebagai admin atau panitia umum
2. Akses halaman laporan
3. Verifikasi:
   - ✅ Semua 5 tab muncul
   - ✅ Semua tab dapat diakses

### Test 5: Direct URL Access
1. Login sebagai `panitia_pendaftaran`
2. Try to access `/panitia-pendaftaran/laporan?activeTab=daftar_ulang`
3. Verifikasi:
   - Tab content masih bisa di-load karena tidak ada backend restriction
   - **Note:** Tab navigation tersembunyi, tapi URL parameter masih work
   - Jika perlu restrict backend, tambahkan validation di component

### Test 6: Tab Switching
1. Login sebagai `panitia_pendaftaran`
2. Akses `/panitia-pendaftaran/laporan`
3. Klik tab "Laporan Pendaftaran" → ✅ Works
4. Klik tab "Data Pendaftar Lengkap" → ✅ Works
5. Klik tab "Tes Psikologi" → ✅ Works
6. Verifikasi URL update dengan `?activeTab=...`

## 🔍 Rationale

### Why Hide These Tabs for Panitia Pendaftaran?

#### Tab "Daftar Ulang Madrasah":
- **Purpose:** Mengelola proses daftar ulang siswa yang diterima
- **Handled by:** Panitia Daftar Ulang (`panitia_daftarulang`)
- **Not relevant for:** Panitia Pendaftaran (fokus pada proses pendaftaran awal)

#### Tab "Seragam & Ukuran":
- **Purpose:** Mengelola data ukuran seragam siswa yang daftar ulang
- **Handled by:** Panitia Daftar Ulang (`panitia_daftarulang`)
- **Not relevant for:** Panitia Pendaftaran (fokus pada proses pendaftaran awal)

### What Panitia Pendaftaran SHOULD Access:
- ✅ **Laporan Pendaftaran:** Monitor statistik pendaftaran
- ✅ **Data Pendaftar Lengkap:** Export dan analisa data pendaftar
- ✅ **Tes Psikologi:** Monitor pendaftar yang daftar tes psikologi

### Separation of Concerns:
```
Panitia Pendaftaran → Handle pendaftaran awal & verifikasi
                      ↓
Panitia Daftar Ulang → Handle daftar ulang & seragam
                       ↓
Admin → Full access to all
```

## 📝 Additional Notes

### Backend Access Control (Optional):
If you want to restrict backend access as well, add validation in `switchTab()` method:

```php
public function switchTab($tab)
{
    // Prevent panitia_pendaftaran from accessing daftar_ulang and seragam tabs
    if ($this->isPanitiaPendaftaran && in_array($tab, ['daftar_ulang', 'seragam'])) {
        session()->flash('error', 'Anda tidak memiliki akses ke tab ini.');
        return;
    }

    $this->activeTab = $tab;
    $this->filterJalur = '';
    $this->filterStatus = '';
}
```

### URL Direct Access:
Currently, if someone manually types `?activeTab=daftar_ulang`, the content will still load because:
- Only frontend tab buttons are hidden
- Backend doesn't restrict based on role for these tabs

If you need to prevent this, implement the backend validation above.

## 🚀 Deployment

### Files to Upload:
```
MODIFIED:
- app/Livewire/Panitia/Laporan.php
- resources/views/livewire/panitia/laporan.blade.php
```

### No Database Changes:
- ✅ No migration needed
- ✅ No model changes
- ✅ Pure logic/view changes

### Clear Cache After Deploy:
```bash
php artisan view:clear
php artisan cache:clear
php artisan config:clear
```

### Verify After Deploy:
1. Test with `panitia_pendaftaran` user
2. Verify tabs are hidden
3. Test with other panitia roles
4. Verify tabs still visible for them

## 💡 Future Improvements

### 1. Backend Validation:
Add role-based tab access validation in component

### 2. Permissions System:
Implement Laravel permissions/gates for more granular control

### 3. Role-Based Tab Configuration:
Store tab visibility in database or config file for easier management

### 4. Audit Log:
Log when users try to access restricted tabs

## ✅ Status

**Status:** ✅ **COMPLETE & READY FOR TESTING**

**Fixed On:** 29 Januari 2026
**Fixed By:** Claude Code
**Breaking Changes:** None
**Backward Compatible:** Yes

---

**Dokumentasi:** 29 Januari 2026
**Version:** 1.0.0
