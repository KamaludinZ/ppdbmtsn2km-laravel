# Redesign: Madrasah Hierarchical Packages - 8 Februari 2026

**Status:** ✅ SELESAI (Backend + Frontend Admin)

---

## 🎯 Tujuan

Mengubah sistem daftar ulang madrasah dari flat structure menjadi hierarchical package system (sama seperti seragam).

**User Request:**
> "sekarang bantu sesuaikan lagi di daftar ulang madrasah di bagian Konfirmasi Nominal yang Dibayarkan samakan seperti di Konfirmasi Nominal yang Dibayarkan yang ada di seragam, jadi di /admin/daftar-ulang?activeTab=settings tidak perlu lagi menambahakan Opsi Nominal yang Dibayarkan."

---

## 📋 Apa yang Berubah?

### Before: Flat Structure (2 Separate Sections)

**Section 1: Nominal Biaya**
```
- SPP Bulan Juli: Rp 500,000
- SPP Bulan Agustus: Rp 500,000
- Biaya Daftar Ulang: Rp 1,000,000
```

**Section 2: Opsi Nominal yang Dibayarkan**
```
- Bayar Penuh: Rp 2,000,000
- Bayar Separuh: Rp 1,000,000
- Cicilan: Rp 500,000
```

**Masalah:**
- Admin harus maintain 2 list terpisah
- Tidak ada hubungan antara nominal biaya dan opsi pembayaran
- Tidak flexible untuk pricing berbeda per opsi

---

### After: Hierarchical Structure (Paket → Opsi)

**Paket Madrasah (Hierarchical)**
```
Paket Lengkap
  ├─ Bayar Penuh: Rp 2,500,000
  ├─ Bayar Separuh: Rp 1,250,000
  └─ Cicilan 3x: Rp 900,000

Paket Minimal
  ├─ SPP Juli + Agustus: Rp 1,000,000
  └─ SPP Juli Saja: Rp 500,000
```

**Keuntungan:**
- Single unified system
- Clear package → option relationship
- Flexible pricing per option
- Same pattern as Seragam
- Easier to maintain

---

## 💻 Implementasi Teknis

### 1. Frontend Admin View (COMPLETED ✅)

**File:** `resources/views/livewire/admin/daftar-ulang.blade.php`

**Changes:**
- **Lines 328-423**: Replaced 2 separate sections with single hierarchical "Paket Madrasah" section
- Removed: "Nominal Biaya" section (flat list)
- Removed: "Opsi Nominal yang Dibayarkan" section (separate flat list)
- Added: Hierarchical package CRUD UI (Green box for packages, Cyan box for options)
- Updated: Single "Opsi Lainnya" checkbox for konfirmasi nominal

**Visual Structure:**
```blade
<!-- Paket Madrasah (Hierarchical) -->
<div class="bg-green-50 border border-green-200 rounded-lg p-4">
    <!-- Package Header -->
    <div>+ Tambah Paket</div>

    @foreach($madrasahPackages as $pkgIndex => $package)
        <!-- Green Package Box -->
        <div class="bg-white border-2 border-green-300 rounded-lg p-4">
            <input wire:model="madrasahPackages.{{ $pkgIndex }}.name" placeholder="Nama Paket">
            <textarea wire:model="madrasahPackages.{{ $pkgIndex }}.description"></textarea>
            <button wire:click="removeMadrasahPackage({{ $pkgIndex }})">Hapus Paket</button>

            <!-- Cyan Options Box -->
            <div class="bg-cyan-50 border border-cyan-300 rounded-lg p-3">
                <div>+ Tambah Opsi</div>

                @foreach($package['options'] as $optIndex => $option)
                    <div>
                        <input wire:model="madrasahPackages.{{ $pkgIndex }}.options.{{ $optIndex }}.name">
                        <input wire:model="madrasahPackages.{{ $pkgIndex }}.options.{{ $optIndex }}.amount">
                        <button wire:click="removeMadrasahPackageOption({{ $pkgIndex }}, {{ $optIndex }})">Hapus</button>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

<!-- Checkbox: Aktifkan Opsi Lainnya -->
<div class="bg-yellow-50">
    <input type="checkbox" wire:model="madrasahEnableOpsiLainnya">
    Aktifkan "Opsi Lainnya" untuk Konfirmasi Nominal yang Dibayarkan
</div>
```

---

### 2. Backend Livewire Component (COMPLETED ✅)

**File:** `app/Livewire/Admin/DaftarUlang.php`

#### A. Properties Added/Updated (Lines 74-85)

```php
// NEW: Hierarchical packages
public $madrasahPackages = []; // Replaces madrasahNominals & madrasahPaymentNominals

// DEPRECATED: Old flat structures (kept for backward compatibility)
public $madrasahNominals = [];
public $madrasahPaymentNominals = [];

// UNIFIED: Single Opsi Lainnya flag (removed madrasahPaymentNominalsEnableOpsiLainnya)
public $madrasahEnableOpsiLainnya = false;
```

**Data Structure:**
```php
$madrasahPackages = [
    [
        'id' => 'pkg_65abc123',
        'name' => 'Paket Lengkap',
        'description' => 'Termasuk SPP Juli-Agustus + Biaya Madrasah',
        'options' => [
            [
                'id' => 'opt_65abc456',
                'name' => 'Bayar Penuh',
                'amount' => 2500000
            ],
            [
                'id' => 'opt_65abc789',
                'name' => 'Cicilan 3x',
                'amount' => 900000
            ]
        ]
    ]
]
```

#### B. Mount Method Updated (Line 155)

```php
public function mount()
{
    // ...

    // NEW: Load hierarchical packages
    $this->madrasahPackages = \App\Models\Setting::get('daftar_ulang_madrasah_packages', []);

    // DEPRECATED: Still load for backward compatibility
    $this->madrasahNominals = \App\Models\Setting::get('daftar_ulang_madrasah_nominals', []);
    $this->madrasahPaymentNominals = \App\Models\Setting::get('daftar_ulang_madrasah_payment_nominals', []);

    // ...
}
```

#### C. New Methods Added (Lines 534-576)

```php
/**
 * Add new madrasah package (hierarchical structure)
 */
public function addMadrasahPackage()
{
    $this->madrasahPackages[] = [
        'id' => 'pkg_' . uniqid(),
        'name' => '',
        'description' => '',
        'options' => []
    ];
}

/**
 * Remove madrasah package by index
 */
public function removeMadrasahPackage($index)
{
    unset($this->madrasahPackages[$index]);
    $this->madrasahPackages = array_values($this->madrasahPackages);
}

/**
 * Add option to a specific madrasah package
 */
public function addMadrasahPackageOption($packageIndex)
{
    if (!isset($this->madrasahPackages[$packageIndex]['options'])) {
        $this->madrasahPackages[$packageIndex]['options'] = [];
    }

    $this->madrasahPackages[$packageIndex]['options'][] = [
        'id' => 'opt_' . uniqid(),
        'name' => '',
        'amount' => ''
    ];
}

/**
 * Remove option from a specific madrasah package
 */
public function removeMadrasahPackageOption($packageIndex, $optionIndex)
{
    unset($this->madrasahPackages[$packageIndex]['options'][$optionIndex]);
    $this->madrasahPackages[$packageIndex]['options'] = array_values($this->madrasahPackages[$packageIndex]['options']);
}
```

#### D. Old Methods Marked DEPRECATED (Lines 578-599)

```php
// DEPRECATED: Old flat structure methods (kept for backward compatibility, but not used in new UI)
public function addMadrasahNominal() { /* ... */ }
public function removeMadrasahNominal($index) { /* ... */ }
public function addMadrasahPaymentNominal() { /* ... */ }
public function removeMadrasahPaymentNominal($index) { /* ... */ }
```

#### E. Save Method Updated (Lines 612-637)

```php
public function saveMadrasahSettings()
{
    // Handle Template Upload (unchanged)
    if ($this->madrasahTemplateSuratPernyataan) { /* ... */ }

    // NEW: Save hierarchical packages
    \App\Models\Setting::set('daftar_ulang_madrasah_packages', $this->madrasahPackages, 'json');

    // Unified Opsi Lainnya flag
    \App\Models\Setting::set('daftar_ulang_madrasah_enable_opsi_lainnya', $this->madrasahEnableOpsiLainnya, 'boolean');

    // DEPRECATED: Still save old structures for backward compatibility
    \App\Models\Setting::set('daftar_ulang_madrasah_nominals', $this->madrasahNominals, 'json');
    \App\Models\Setting::set('daftar_ulang_madrasah_payment_nominals', $this->madrasahPaymentNominals, 'json');

    $this->dispatch('saved', message: 'Pengaturan daftar ulang madrasah berhasil disimpan.');
}
```

---

## 📁 Files Modified

### 1. resources/views/livewire/admin/daftar-ulang.blade.php
- **Lines 328-423**: Replaced with hierarchical package CRUD UI
- **Removed**: Lines for "Nominal Biaya" section
- **Removed**: Lines for "Opsi Nominal yang Dibayarkan" section
- **Added**: Hierarchical "Paket Madrasah" section
- **Added**: Single "Opsi Lainnya" checkbox

### 2. app/Livewire/Admin/DaftarUlang.php
- **Line 77**: Added `$madrasahPackages` property
- **Lines 78-79**: Marked old properties as DEPRECATED
- **Line 85**: Removed `$madrasahPaymentNominalsEnableOpsiLainnya` (unified with `$madrasahEnableOpsiLainnya`)
- **Line 155**: Load `$madrasahPackages` in mount()
- **Lines 534-576**: Added 4 new hierarchical package methods
- **Lines 578-599**: Marked old methods as DEPRECATED
- **Lines 622-637**: Updated save method to save hierarchical structure

---

## 🧪 Testing Guide

### Admin Settings Page

1. **Navigate to Admin Settings**
   ```
   http://localhost:8000/admin/daftar-ulang?activeTab=settings
   ```

2. **Scroll to "Daftar Ulang Madrasah" Section**
   - ✅ See single "Paket Madrasah" section (Green background)
   - ❌ Should NOT see separate "Nominal Biaya" section
   - ❌ Should NOT see separate "Opsi Nominal yang Dibayarkan" section

3. **Test Add Package**
   - Click "+ Tambah Paket" (green button)
   - ✅ New package box appears (white with green border)
   - ✅ Fields: Nama Paket, Deskripsi, Hapus Paket button
   - ✅ Nested "Opsi dari Paket ini" section (cyan background)

4. **Test Add Option**
   - Inside package, click "+ Tambah Opsi" (cyan button)
   - ✅ New option row appears
   - ✅ Fields: Nama Opsi (text), Nominal (number), Hapus button

5. **Test Fill Data**
   Fill in:
   ```
   Paket: "Paket Lengkap"
   Deskripsi: "Termasuk SPP Juli-Agustus + Biaya Madrasah"

   Opsi 1:
     Nama: "Bayar Penuh"
     Nominal: 2500000

   Opsi 2:
     Nama: "Cicilan 3x"
     Nominal: 900000
   ```

6. **Test Save**
   - Click "Simpan Pengaturan" button
   - ✅ Success message: "Pengaturan daftar ulang madrasah berhasil disimpan."

7. **Test Persistence**
   - Reload page (Ctrl + Shift + R)
   - ✅ Data still there
   - ✅ Package name, description, options all persisted

8. **Test Remove Option**
   - Click "Hapus" button on an option
   - ✅ Option removed from list
   - Save and reload
   - ✅ Deletion persisted

9. **Test Remove Package**
   - Click "Hapus Paket" button
   - ✅ Confirmation dialog: "Yakin ingin menghapus paket ini beserta semua opsinya?"
   - Confirm
   - ✅ Package and all its options removed

10. **Test Opsi Lainnya**
    - ✅ See yellow box with checkbox
    - ✅ Label: "Aktifkan 'Opsi Lainnya' untuk Konfirmasi Nominal yang Dibayarkan"
    - Check/uncheck
    - Save and reload
    - ✅ State persisted

11. **Test Empty State**
    - Remove all packages
    - ✅ See empty state message
    - ✅ Icon and text: "Belum ada paket daftar ulang madrasah"
    - ✅ Instruction: "Klik 'Tambah Paket' untuk membuat paket baru"

---

## 📊 Before vs After Comparison

| Aspect | Before | After |
|--------|--------|-------|
| **Structure** | Flat (2 separate lists) | Hierarchical (Paket → Opsi) |
| **Admin Sections** | 2 sections ("Nominal Biaya" + "Opsi Nominal") | 1 unified "Paket Madrasah" section |
| **Relationship** | No connection between lists | Clear parent-child relationship |
| **Flexibility** | Single price list | Different prices per option |
| **Opsi Lainnya** | 2 separate flags | 1 unified flag |
| **UI Complexity** | Medium | Lower (unified interface) |
| **Consistency** | Different from Seragam | Same as Seragam ✅ |
| **Maintenance** | Must sync 2 lists manually | Single source of truth |

---

## 🎨 UI Components

### Color Scheme (Same as Seragam)

**Green** - Package Level
- Background: `bg-green-50` (light green)
- Border: `border-green-200` / `border-green-300`
- Button: `bg-green-600 hover:bg-green-700` (green button)

**Cyan** - Options Level
- Background: `bg-cyan-50` (light cyan)
- Border: `border-cyan-300`
- Button: `bg-cyan-600 hover:bg-cyan-700` (cyan button)

**Yellow** - Opsi Lainnya
- Background: `bg-yellow-50` (light yellow)
- Border: `border-yellow-200`

---

## ⚠️ Breaking Changes

**None!** This is fully backward compatible:
- Old properties still exist (marked DEPRECATED)
- Old methods still work (marked DEPRECATED)
- Old settings keys still saved (for rollback capability)
- New UI uses new hierarchical structure
- Old data can be migrated if needed

---

## 🚀 Next Steps (Pending)

### 1. Update Pendaftar Madrasah View ⏳
**File:** `resources/views/livewire/pendaftar/daftar-ulang.blade.php` (Tab Madrasah)

**Changes Needed:**
- Replace flat nominal selection with dependent dropdown
- Step 1: Select Package (dropdown)
- Step 2: Select Option (dependent dropdown based on package)
- Auto-populate konfirmasi nominal from selected option
- Add "Opsi Lainnya" for manual input (if enabled in admin)

### 2. Update Pendaftar Madrasah Livewire Component ⏳
**File:** `app/Livewire/Pendaftar/DaftarUlang.php`

**Changes Needed:**
- Add `$paketMadrasah` property (selected package)
- Add `$opsiMadrasah` property (selected option)
- Update validation rules
- Update `saveMadrasah()` method to handle hierarchical selection
- Store selected package and option in database

### 3. Database Migration (If Needed) ⏳
**Consider Adding:**
- `opsi_madrasah` column to `daftar_ulang` table (TEXT, nullable)
- Similar to `opsi_seragam` in `seragam` table

---

## 📝 Database Schema (Settings)

**New Key:**
```
daftar_ulang_madrasah_packages: JSON
```

**Example Value:**
```json
[
    {
        "id": "pkg_65abc123",
        "name": "Paket Lengkap",
        "description": "Termasuk SPP Juli-Agustus + Biaya Madrasah",
        "options": [
            {
                "id": "opt_65abc456",
                "name": "Bayar Penuh",
                "amount": 2500000
            },
            {
                "id": "opt_65abc789",
                "name": "Cicilan 3x",
                "amount": 900000
            }
        ]
    },
    {
        "id": "pkg_65abc999",
        "name": "Paket Minimal",
        "description": "SPP Juli + Agustus saja",
        "options": [
            {
                "id": "opt_65abcaaa",
                "name": "SPP Juli + Agustus",
                "amount": 1000000
            }
        ]
    }
]
```

---

## ✅ Completion Status

### Completed ✅
- [x] **Frontend Admin View** - Hierarchical package CRUD UI
- [x] **Backend Properties** - Added `$madrasahPackages`
- [x] **Backend Methods** - 4 new hierarchical methods
- [x] **Mount Method** - Load hierarchical packages
- [x] **Save Method** - Save hierarchical packages
- [x] **Backward Compatibility** - Old methods marked DEPRECATED

### Pending ⏳
- [ ] **Frontend Pendaftar View** - Dependent dropdown UI
- [ ] **Backend Pendaftar Component** - Handle hierarchical selection
- [ ] **Database Migration** - Add `opsi_madrasah` column (if needed)
- [ ] **Testing** - End-to-end testing with real data

---

## 📚 Related Files

1. **Frontend:**
   - `resources/views/livewire/admin/daftar-ulang.blade.php` ✅
   - `resources/views/livewire/pendaftar/daftar-ulang.blade.php` ⏳

2. **Backend:**
   - `app/Livewire/Admin/DaftarUlang.php` ✅
   - `app/Livewire/Pendaftar/DaftarUlang.php` ⏳

3. **Models:**
   - `app/Models/DaftarUlang.php` (may need `opsi_madrasah` in fillable) ⏳

4. **Database:**
   - Migration file for `opsi_madrasah` column ⏳

---

## 🎯 Benefits

### For Admin:
- ✅ **Single unified interface** - No more managing 2 separate lists
- ✅ **Clear organization** - Package → Option hierarchy is intuitive
- ✅ **Flexible pricing** - Different prices for different options
- ✅ **Easier maintenance** - Single source of truth
- ✅ **Consistent with Seragam** - Same pattern everywhere

### For Pendaftar (When Implemented):
- ✅ **Better UX** - Dependent dropdown guides selection
- ✅ **Clear pricing** - See exact amount for each option
- ✅ **Flexibility** - "Opsi Lainnya" for custom amounts
- ✅ **Less confusion** - Hierarchical structure is clearer

---

**Implementation Date:** 8 Februari 2026
**Status:** Backend + Admin Frontend COMPLETED ✅
**Next:** Pendaftar Frontend Implementation ⏳
**Pattern:** Same as Seragam hierarchical system
**Backward Compatible:** Yes (old methods deprecated but functional)

---

**Developed by:** Claude Code
**Session Time:** ~1 hour (Backend + Admin Frontend)
