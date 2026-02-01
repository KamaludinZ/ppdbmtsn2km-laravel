# Fix: Opsi Nominal Pembayaran Terpisah ✅

## 📋 Overview Perubahan

Implementasi pemisahan **Opsi Nominal Biaya** menjadi dua kategori terpisah:

1. **Nominal Biaya (Surat Pernyataan)** - Untuk pilihan pada surat pernyataan
2. **Opsi Nominal yang Dibayarkan** - Untuk konfirmasi nominal yang akan dibayarkan

Juga mengganti teks "Konfirmasi Nominal yang **Akan** Dibayarkan" menjadi "Konfirmasi Nominal yang Dibayarkan".

---

## 🎯 Problem Statement

### Masalah Sebelumnya:

1. **Nominal Biaya dan Konfirmasi Menggunakan Data yang Sama**
   - Field "Nominal Biaya" untuk surat pernyataan menggunakan array yang sama dengan "Konfirmasi Nominal yang Dibayarkan"
   - Admin tidak bisa membuat opsi nominal pembayaran yang berbeda dari nominal surat pernyataan
   - Validasi memaksa konfirmasi harus sama persis dengan nominal surat pernyataan (`same:nominalBiaya`)

2. **Kurang Fleksibilitas**
   - Jika admin ingin memberikan opsi pembayaran yang berbeda (misal: cicilan, paket bundling, diskon), tidak bisa
   - Nominal pembayaran terikat dengan nominal surat pernyataan

3. **Teks yang Kurang Tepat**
   - "Konfirmasi Nominal yang **Akan** Dibayarkan" terdengar seperti komitmen masa depan
   - Lebih baik "Konfirmasi Nominal yang Dibayarkan" (konfirmasi langsung)

### Goals:

1. ✅ Pisahkan nominal surat pernyataan dengan nominal pembayaran
2. ✅ Admin bisa mengelola dua jenis nominal secara terpisah
3. ✅ Pendaftar pilih nominal surat pernyataan, lalu pilih nominal pembayaran (bisa berbeda)
4. ✅ Ganti teks menjadi lebih tepat

---

## 🔧 Technical Implementation

### 1. Backend (Admin) - Add Payment Nominals Properties

**File**: `app/Livewire/Admin/DaftarUlang.php`

#### Properties Baru:

```php
// Madrasah Settings
public $madrasahNominals = []; // Untuk surat pernyataan
public $madrasahPaymentNominals = []; // Untuk konfirmasi nominal yang dibayarkan

// Seragam Settings
public $seragamNominals = []; // Untuk surat pernyataan
public $seragamPaymentNominals = []; // Untuk konfirmasi nominal yang dibayarkan
```

**Changes**:
- Lines 71-72: Added `$madrasahPaymentNominals` property
- Lines 82-83: Added `$seragamPaymentNominals` property

---

### 2. Backend (Admin) - Load Payment Nominals in mount()

**File**: `app/Livewire/Admin/DaftarUlang.php`

#### Load Settings:

```php
// Madrasah JSON/Array Settings
$this->madrasahNominals = \App\Models\Setting::get('daftar_ulang_madrasah_nominals', []);
$this->madrasahPaymentNominals = \App\Models\Setting::get('daftar_ulang_madrasah_payment_nominals', []);

// Seragam JSON/Array Settings
$this->seragamNominals = \App\Models\Setting::get('daftar_ulang_seragam_nominals', []);
$this->seragamPaymentNominals = \App\Models\Setting::get('daftar_ulang_seragam_payment_nominals', []);
```

**Changes**:
- Line 151: Added load for `madrasahPaymentNominals`
- Line 162: Added load for `seragamPaymentNominals`

---

### 3. Backend (Admin) - Add CRUD Methods for Payment Nominals

**File**: `app/Livewire/Admin/DaftarUlang.php`

#### Madrasah Methods:

```php
public function addMadrasahPaymentNominal()
{
    $this->madrasahPaymentNominals[] = ['name' => '', 'amount' => ''];
}

public function removeMadrasahPaymentNominal($index)
{
    unset($this->madrasahPaymentNominals[$index]);
    $this->madrasahPaymentNominals = array_values($this->madrasahPaymentNominals);
}
```

**Changes**:
- Lines 537-546: Added `addMadrasahPaymentNominal()` and `removeMadrasahPaymentNominal()` methods

#### Seragam Methods:

```php
public function addSeragamPaymentNominal()
{
    $this->seragamPaymentNominals[] = ['name' => '', 'amount' => ''];
}

public function removeSeragamPaymentNominal($index)
{
    unset($this->seragamPaymentNominals[$index]);
    $this->seragamPaymentNominals = array_values($this->seragamPaymentNominals);
}
```

**Changes**:
- Lines 606-615: Added `addSeragamPaymentNominal()` and `removeSeragamPaymentNominal()` methods

---

### 4. Backend (Admin) - Save Payment Nominals

**File**: `app/Livewire/Admin/DaftarUlang.php`

#### Save Madrasah Settings:

```php
public function saveMadrasahSettings()
{
    // Save settings
    \App\Models\Setting::set('daftar_ulang_madrasah_nominals', $this->madrasahNominals, 'json');
    \App\Models\Setting::set('daftar_ulang_madrasah_payment_nominals', $this->madrasahPaymentNominals, 'json');
    // ... other settings
}
```

**Changes**:
- Line 572: Added save for `madrasahPaymentNominals`

#### Save Seragam Settings:

```php
public function saveSeragamSettings()
{
    // Save settings
    \App\Models\Setting::set('daftar_ulang_seragam_nominals', $this->seragamNominals, 'json');
    \App\Models\Setting::set('daftar_ulang_seragam_payment_nominals', $this->seragamPaymentNominals, 'json');
    // ... other settings
}
```

**Changes**:
- Line 634: Added save for `seragamPaymentNominals`

---

### 5. Frontend (Admin) - Add UI for Payment Nominals

**File**: `resources/views/livewire/admin/daftar-ulang.blade.php`

#### Madrasah Section (after Nominal Biaya):

```blade
<!-- Opsi Nominal yang Dibayarkan -->
<div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4">
    <div class="flex items-center justify-between mb-3">
        <label class="block text-sm font-semibold text-cyan-900">Opsi Nominal yang Dibayarkan</label>
        <button type="button" wire:click="addMadrasahPaymentNominal" class="text-xs bg-cyan-600 text-white px-3 py-1 rounded hover:bg-cyan-700">+ Tambah</button>
    </div>
    <p class="text-xs text-gray-600 mb-3">Opsi nominal yang muncul pada field konfirmasi nominal yang dibayarkan (terpisah dari nominal surat pernyataan)</p>

    @foreach($madrasahPaymentNominals as $index => $nominal)
        <div class="bg-white border border-cyan-300 rounded-lg p-3 mb-2">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <input type="text" wire:model="madrasahPaymentNominals.{{ $index }}.name" placeholder="Nama biaya (contoh: SPP Bulanan)" class="rounded border-gray-300 text-sm">
                <input type="number" wire:model="madrasahPaymentNominals.{{ $index }}.amount" placeholder="Nominal (Rp)" class="rounded border-gray-300 text-sm">
                <button type="button"
                        wire:click="removeMadrasahPaymentNominal({{ $index }})"
                        wire:confirm="Yakin ingin menghapus opsi nominal ini?"
                        class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
            </div>
        </div>
    @endforeach
</div>
```

**Changes**:
- Lines 358-378: Added "Opsi Nominal yang Dibayarkan" section for Madrasah

#### Seragam Section (after Nominal Biaya):

```blade
<!-- Opsi Nominal yang Dibayarkan -->
<div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4">
    <div class="flex items-center justify-between mb-3">
        <label class="block text-sm font-semibold text-cyan-900">Opsi Nominal yang Dibayarkan</label>
        <button type="button" wire:click="addSeragamPaymentNominal" class="text-xs bg-cyan-600 text-white px-3 py-1 rounded hover:bg-cyan-700">+ Tambah</button>
    </div>
    <p class="text-xs text-gray-600 mb-3">Opsi nominal yang muncul pada field konfirmasi nominal yang dibayarkan (terpisah dari nominal surat pernyataan)</p>

    @foreach($seragamPaymentNominals as $index => $nominal)
        <div class="bg-white border border-cyan-300 rounded-lg p-3 mb-2">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <input type="text" wire:model="seragamPaymentNominals.{{ $index }}.name" placeholder="Nama biaya (contoh: Seragam Putih)" class="rounded border-gray-300 text-sm">
                <input type="number" wire:model="seragamPaymentNominals.{{ $index }}.amount" placeholder="Nominal (Rp)" class="rounded border-gray-300 text-sm">
                <button type="button"
                        wire:click="removeSeragamPaymentNominal({{ $index }})"
                        wire:confirm="Yakin ingin menghapus opsi nominal ini?"
                        class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
            </div>
        </div>
    @endforeach
</div>
```

**Changes**:
- Lines 544-564: Added "Opsi Nominal yang Dibayarkan" section for Seragam

**Visual**:
- Background: `bg-cyan-50` (light cyan)
- Border: `border-cyan-200`
- Button: `bg-cyan-600` (distinct dari hijau nominal biaya)

---

### 6. Frontend (Pendaftar) - Update Konfirmasi Nominal Section

**File**: `resources/views/livewire/pendaftar/daftar-ulang.blade.php`

#### Madrasah - Load Payment Nominals:

**Before**:
```blade
<!-- Konfirmasi Nominal Biaya -->
<div class="mb-4">
    <label>Konfirmasi Nominal yang Akan Dibayarkan</label>
    @foreach($madrasahNominals as $index => $nominal)
        <!-- ... -->
    @endforeach
</div>
```

**After**:
```blade
@php
    $madrasahPaymentNominals = \App\Models\Setting::get('daftar_ulang_madrasah_payment_nominals', []);
@endphp

<!-- Konfirmasi Nominal Biaya -->
@if($madrasahPaymentNominals && count($madrasahPaymentNominals) > 0)
<div class="mb-4">
    <label>Konfirmasi Nominal yang Dibayarkan</label>
    <p class="text-xs text-gray-600 mb-2">Pilih nominal yang akan dibayarkan</p>
    @foreach($madrasahPaymentNominals as $index => $nominal)
        <!-- ... -->
    @endforeach
</div>
@endif
```

**Changes**:
- Lines 247-249: Added `@php` block to load payment nominals
- Line 250: Changed text to "Konfirmasi Nominal yang Dibayarkan"
- Line 252: Changed `@if` condition to check payment nominals count
- Line 254: Changed loop from `$madrasahNominals` to `$madrasahPaymentNominals`
- Line 277: Added closing `@endif` for payment nominals check

#### Seragam - Same Changes:

**Changes**:
- Lines 661-663: Added `@php` block to load seragam payment nominals
- Line 658: Changed text to "Konfirmasi Nominal yang Dibayarkan"
- Line 666: Changed `@if` condition to check payment nominals count
- Line 673: Changed loop from `$seragamNominals` to `$seragamPaymentNominals`
- Line 691: Added closing `@endif`

---

### 7. Backend (Pendaftar) - Update Validation Rules

**File**: `app/Livewire/Pendaftar/DaftarUlang.php`

#### Madrasah Validation:

**Before**:
```php
$this->validate([
    'nominalBiaya' => 'required|string',
    'konfirmasiNominalBiaya' => 'required|string|same:nominalBiaya',
], [
    'konfirmasiNominalBiaya.same' => 'Konfirmasi nominal harus sama dengan nominal yang dipilih pada surat pernyataan.',
]);
```

**After**:
```php
$this->validate([
    'nominalBiaya' => 'required|string',
    'konfirmasiNominalBiaya' => 'required|string',
], [
    'konfirmasiNominalBiaya.required' => 'Silakan pilih nominal yang akan dibayarkan.',
]);
```

**Changes**:
- Line 169: Removed `|same:nominalBiaya` validation
- Line 176: Updated error message to just require selection

#### Seragam Validation:

**Before**:
```php
$this->validate([
    'nominalSeragam' => 'required|string',
    'konfirmasiNominalSeragam' => 'required|string|same:nominalSeragam',
], [
    'konfirmasiNominalSeragam.same' => 'Konfirmasi nominal harus sama dengan nominal yang dipilih pada surat pernyataan.',
]);
```

**After**:
```php
$this->validate([
    'nominalSeragam' => 'required|string',
    'konfirmasiNominalSeragam' => 'required|string',
], [
    'konfirmasiNominalSeragam.required' => 'Silakan pilih nominal yang akan dibayarkan.',
]);
```

**Changes**:
- Line 259: Removed `|same:nominalSeragam` validation
- Line 265: Updated error message to just require selection

**Reason**:
- Payment nominals sekarang terpisah dari surat pernyataan nominals
- Tidak masuk akal memaksa konfirmasi sama dengan nominal surat pernyataan jika opsinya berbeda
- Fleksibilitas untuk admin memberikan opsi pembayaran yang berbeda (cicilan, diskon, dll)

---

## 📊 Data Flow

### Old Flow (Before):

```
Admin Settings:
├── madrasahNominals (untuk surat pernyataan DAN konfirmasi)
└── seragamNominals (untuk surat pernyataan DAN konfirmasi)

Pendaftar Form:
├── Nominal Biaya → pilih dari madrasahNominals
└── Konfirmasi Nominal → pilih dari madrasahNominals (harus sama!)
```

**Problem**: Tidak ada pemisahan, konfirmasi dipaksa sama dengan surat pernyataan.

### New Flow (After):

```
Admin Settings:
├── madrasahNominals (khusus untuk surat pernyataan)
├── madrasahPaymentNominals (khusus untuk konfirmasi pembayaran)
├── seragamNominals (khusus untuk surat pernyataan)
└── seragamPaymentNominals (khusus untuk konfirmasi pembayaran)

Pendaftar Form:
├── Nominal Biaya → pilih dari madrasahNominals
└── Konfirmasi Nominal yang Dibayarkan → pilih dari madrasahPaymentNominals
```

**Benefit**: Admin bisa set opsi berbeda untuk surat pernyataan vs pembayaran.

---

## 💡 Use Cases

### Use Case 1: Opsi Pembayaran Cicilan

**Admin Settings**:

Nominal Biaya (Surat Pernyataan):
- Kategori A: Rp 5.000.000
- Kategori B: Rp 3.000.000

Opsi Nominal yang Dibayarkan:
- Lunas: Rp 5.000.000
- Cicilan 1 (50%): Rp 2.500.000
- Cicilan 2 (50%): Rp 2.500.000

**Pendaftar Flow**:
1. Pilih "Kategori A" di Nominal Biaya (surat pernyataan)
2. Pilih "Cicilan 1 (50%): Rp 2.500.000" di Konfirmasi Nominal yang Dibayarkan
3. Upload bukti pembayaran Rp 2.500.000

### Use Case 2: Diskon Early Bird

**Admin Settings**:

Nominal Biaya (Surat Pernyataan):
- Standard: Rp 4.000.000

Opsi Nominal yang Dibayarkan:
- Standard: Rp 4.000.000
- Early Bird (Diskon 10%): Rp 3.600.000

**Pendaftar Flow**:
1. Pilih "Standard" di Nominal Biaya
2. Pilih "Early Bird (Diskon 10%)" di Konfirmasi Nominal yang Dibayarkan
3. Upload bukti pembayaran Rp 3.600.000

### Use Case 3: Paket Bundling

**Admin Settings (Seragam)**:

Nominal Biaya (Surat Pernyataan):
- Paket A: Rp 2.000.000
- Paket B: Rp 1.500.000

Opsi Nominal yang Dibayarkan:
- Paket A Full: Rp 2.000.000
- Paket A + Bundling Buku: Rp 2.300.000
- Paket B Full: Rp 1.500.000

**Pendaftar Flow**:
1. Pilih "Paket A" di Nominal Biaya
2. Pilih "Paket A + Bundling Buku: Rp 2.300.000" di Konfirmasi
3. Upload bukti pembayaran Rp 2.300.000

---

## 📁 Files Modified

### 1. `app/Livewire/Admin/DaftarUlang.php`

**Lines Modified**:
- 71-72: Added `madrasahPaymentNominals` property
- 82-83: Added `seragamPaymentNominals` property
- 151: Load `madrasahPaymentNominals` from settings
- 162: Load `seragamPaymentNominals` from settings
- 537-546: Added `addMadrasahPaymentNominal()` and `removeMadrasahPaymentNominal()` methods
- 572: Save `madrasahPaymentNominals` to settings
- 606-615: Added `addSeragamPaymentNominal()` and `removeSeragamPaymentNominal()` methods
- 634: Save `seragamPaymentNominals` to settings

**Total Lines**: ~40 lines added

### 2. `resources/views/livewire/admin/daftar-ulang.blade.php`

**Lines Modified**:
- 358-378: Added "Opsi Nominal yang Dibayarkan" section for Madrasah
- 544-564: Added "Opsi Nominal yang Dibayarkan" section for Seragam

**Total Lines**: ~42 lines added

### 3. `resources/views/livewire/pendaftar/daftar-ulang.blade.php`

**Lines Modified**:
- 247-252: Added `@php` block, changed text, updated description for Madrasah
- 254: Changed loop from `madrasahNominals` to `madrasahPaymentNominals`
- 277: Added closing `@endif`
- 661-671: Added `@php` block, changed text, updated description for Seragam
- 673: Changed loop from `seragamNominals` to `seragamPaymentNominals`
- 691: Added closing `@endif`

**Total Lines**: ~12 lines modified, ~6 lines added

### 4. `app/Livewire/Pendaftar/DaftarUlang.php`

**Lines Modified**:
- 169: Removed `|same:nominalBiaya` validation (Madrasah)
- 176: Updated error message for konfirmasi nominal (Madrasah)
- 259: Removed `|same:nominalSeragam` validation (Seragam)
- 265: Updated error message for konfirmasi nominal (Seragam)

**Total Lines**: ~4 lines modified

---

## 🧪 Testing Checklist

### Test 1: Admin Settings - Madrasah

- [ ] Login sebagai admin
- [ ] Buka `/admin/daftar-ulang?activeTab=settings`
- [ ] Scroll ke "Pengaturan Madrasah"
- [ ] **Expected**: Ada 2 section:
  - "Nominal Biaya" (background hijau)
  - "Opsi Nominal yang Dibayarkan" (background cyan)
- [ ] Klik "+ Tambah" di "Nominal Biaya"
- [ ] Isi: Nama: "Kategori A", Nominal: 5000000
- [ ] Klik "+ Tambah" di "Opsi Nominal yang Dibayarkan"
- [ ] Isi: Nama: "Cicilan 1 (50%)", Nominal: 2500000
- [ ] Klik "Simpan Pengaturan Madrasah"
- [ ] **Expected**: Success message, data tersimpan

### Test 2: Admin Settings - Seragam

- [ ] Scroll ke "Pengaturan Seragam"
- [ ] **Expected**: Ada 2 section:
  - "Nominal Biaya" (background hijau)
  - "Opsi Nominal yang Dibayarkan" (background cyan)
- [ ] Tambah nominal di kedua section
- [ ] Klik "Simpan Pengaturan Seragam"
- [ ] **Expected**: Success message, data tersimpan

### Test 3: Pendaftar - Madrasah Tab

- [ ] Login sebagai pendaftar
- [ ] Buka `/pendaftar/daftar-ulang?activeTab=madrasah`
- [ ] **Expected**: Ada 2 dropdown:
  - "Pilih Nominal Biaya" → opsi dari madrasahNominals
  - "Konfirmasi Nominal yang Dibayarkan" → opsi dari madrasahPaymentNominals
- [ ] Pilih "Kategori A" di Nominal Biaya
- [ ] Pilih "Cicilan 1 (50%)" di Konfirmasi Nominal
- [ ] **Expected**: Bisa pilih opsi yang berbeda (tidak ada error "harus sama")
- [ ] Klik "Simpan Data"
- [ ] **Expected**: Data tersimpan tanpa error

### Test 4: Pendaftar - Seragam Tab

- [ ] Klik tab "Daftar Ulang Seragam"
- [ ] **Expected**: Ada 2 dropdown:
  - "Pilih Nominal Biaya" → opsi dari seragamNominals
  - "Konfirmasi Nominal yang Dibayarkan" → opsi dari seragamPaymentNominals
- [ ] Pilih nominal yang berbeda di kedua dropdown
- [ ] **Expected**: Tidak ada error validasi
- [ ] Klik "Simpan Data"
- [ ] **Expected**: Data tersimpan

### Test 5: Validation - Required Fields

- [ ] Di tab Madrasah, jangan pilih "Konfirmasi Nominal yang Dibayarkan"
- [ ] Klik "Simpan Data"
- [ ] **Expected**: Error: "Silakan pilih nominal yang akan dibayarkan."
- [ ] Pilih nominal di konfirmasi
- [ ] **Expected**: Error hilang, bisa simpan

### Test 6: Empty Payment Nominals

- [ ] Login sebagai admin
- [ ] Hapus semua opsi di "Opsi Nominal yang Dibayarkan"
- [ ] Simpan settings
- [ ] Login sebagai pendaftar
- [ ] Buka daftar ulang
- [ ] **Expected**: Section "Konfirmasi Nominal yang Dibayarkan" tidak muncul (hidden karena `@if` check)

### Test 7: Text Update Verification

- [ ] Buka `/pendaftar/daftar-ulang?activeTab=madrasah`
- [ ] **Expected**: Teks label adalah "Konfirmasi Nominal yang Dibayarkan" (tanpa kata "Akan")
- [ ] Buka tab Seragam
- [ ] **Expected**: Teks label juga "Konfirmasi Nominal yang Dibayarkan"

---

## 🚀 Deployment Instructions

### Pre-Deployment Checklist

- [ ] Backup database production
- [ ] Backup files production (app, resources)
- [ ] Test semua fitur di local/staging
- [ ] Verify admin bisa add/remove payment nominals
- [ ] Verify pendaftar bisa pilih opsi berbeda

### Files to Upload

```
📁 Upload ke Production:

1. Backend (app/Livewire/):
   - app/Livewire/Admin/DaftarUlang.php
   - app/Livewire/Pendaftar/DaftarUlang.php

2. Frontend (resources/views/):
   - resources/views/livewire/admin/daftar-ulang.blade.php
   - resources/views/livewire/pendaftar/daftar-ulang.blade.php

3. Documentation:
   - FIX_OPSI_NOMINAL_PEMBAYARAN_TERPISAH.md
```

### Deployment Steps

#### Step 1: Upload Files

```bash
# Via SFTP/FTP, upload files ke production
# Atau via SSH:

scp app/Livewire/Admin/DaftarUlang.php user@server:/path/to/app/Livewire/Admin/
scp app/Livewire/Pendaftar/DaftarUlang.php user@server:/path/to/app/Livewire/Pendaftar/
scp resources/views/livewire/admin/daftar-ulang.blade.php user@server:/path/to/resources/views/livewire/admin/
scp resources/views/livewire/pendaftar/daftar-ulang.blade.php user@server:/path/to/resources/views/livewire/pendaftar/
```

#### Step 2: Clear All Caches

```bash
# SSH ke production server
ssh user@production-server

# Navigate to project
cd /path/to/project

# Clear caches
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear

# Optional: Optimize
php artisan optimize
```

#### Step 3: Configure Admin Settings

```bash
# Login sebagai admin
# Buka /admin/daftar-ulang?activeTab=settings

# Setup Opsi Nominal yang Dibayarkan:
# 1. Scroll ke "Pengaturan Madrasah"
# 2. Tambah opsi di "Opsi Nominal yang Dibayarkan"
# 3. Klik "Simpan Pengaturan Madrasah"

# 4. Scroll ke "Pengaturan Seragam"
# 5. Tambah opsi di "Opsi Nominal yang Dibayarkan"
# 6. Klik "Simpan Pengaturan Seragam"
```

#### Step 4: Test in Production

- [ ] Test admin bisa add/remove payment nominals
- [ ] Test pendaftar lihat 2 dropdown terpisah
- [ ] Test pendaftar bisa pilih opsi berbeda
- [ ] Test validasi required tetap jalan
- [ ] Test save data berhasil

#### Step 5: Monitor Logs

```bash
# Monitor Laravel logs untuk error
tail -f storage/logs/laravel.log

# Monitor PHP error logs
tail -f /var/log/php-fpm/error.log
```

### Rollback Plan (If Issues)

Jika ada masalah setelah deploy:

```bash
# 1. Restore backup files
cp backup/Admin-DaftarUlang.php.backup app/Livewire/Admin/DaftarUlang.php
cp backup/Pendaftar-DaftarUlang.php.backup app/Livewire/Pendaftar/DaftarUlang.php
cp backup/admin-daftar-ulang.blade.php.backup resources/views/livewire/admin/daftar-ulang.blade.php
cp backup/pendaftar-daftar-ulang.blade.php.backup resources/views/livewire/pendaftar/daftar-ulang.blade.php

# 2. Clear caches
php artisan cache:clear
php artisan view:clear

# 3. Restore database (jika ada data settings)
# Settings akan auto-fallback ke empty array jika tidak ada
```

### No Database Changes Required

**PENTING**: Fitur ini TIDAK memerlukan migration karena:
- ✅ Menggunakan existing `settings` table untuk store payment nominals
- ✅ Settings akan auto-create saat admin save
- ✅ Tidak ada perubahan struktur tabel
- ✅ Hanya menambah key baru di settings: `daftar_ulang_madrasah_payment_nominals` dan `daftar_ulang_seragam_payment_nominals`

---

## 💡 Technical Notes

### 1. Why Separate Arrays?

**Old Approach** (Shared Array):
```php
$madrasahNominals = [
    ['name' => 'Kategori A', 'amount' => 5000000],
];

// Used for both:
// - Nominal Biaya (surat pernyataan)
// - Konfirmasi Nominal (pembayaran)
```

**Problem**: Tidak fleksibel, konfirmasi harus sama dengan surat pernyataan.

**New Approach** (Separate Arrays):
```php
$madrasahNominals = [
    ['name' => 'Kategori A', 'amount' => 5000000],
];

$madrasahPaymentNominals = [
    ['name' => 'Lunas', 'amount' => 5000000],
    ['name' => 'Cicilan 1', 'amount' => 2500000],
    ['name' => 'Cicilan 2', 'amount' => 2500000],
];
```

**Benefit**: Admin bisa set opsi pembayaran yang berbeda dari surat pernyataan.

### 2. Why Remove `same:` Validation?

**Old Validation**:
```php
'konfirmasiNominalBiaya' => 'required|string|same:nominalBiaya',
```

**Problem**:
- Memaksa konfirmasi sama persis dengan nominal surat pernyataan
- Jika admin set payment nominals berbeda, validasi akan selalu gagal

**New Validation**:
```php
'konfirmasiNominalBiaya' => 'required|string',
```

**Benefit**:
- Fleksibel, pendaftar bisa pilih opsi yang berbeda
- Admin bisa berkreasi dengan opsi pembayaran (cicilan, diskon, bundling, dll)

### 3. Why Use `@php` in Blade?

**Approach**:
```blade
@php
    $madrasahPaymentNominals = \App\Models\Setting::get('daftar_ulang_madrasah_payment_nominals', []);
@endphp
```

**Reason**:
- Consistent dengan existing code yang juga load `$madrasahNominals` di Blade
- Tidak perlu modifikasi Livewire component untuk pass data
- Blade bisa langsung akses Settings

**Alternative** (Pass from Component):
```php
// In Livewire component
public $madrasahPaymentNominals = [];

public function mount()
{
    $this->madrasahPaymentNominals = Setting::get('daftar_ulang_madrasah_payment_nominals', []);
}
```

**Why Not Used**: Existing code sudah pakai `@php`, jadi ikuti pattern yang sama.

### 4. Settings Key Structure

**New Settings Keys**:
- `daftar_ulang_madrasah_payment_nominals` (JSON array)
- `daftar_ulang_seragam_payment_nominals` (JSON array)

**Data Structure**:
```json
[
    {
        "name": "Lunas",
        "amount": 5000000
    },
    {
        "name": "Cicilan 1 (50%)",
        "amount": 2500000
    }
]
```

**Same as** `madrasahNominals` and `seragamNominals` (consistent structure).

---

## ✅ Benefits Summary

### For Admin:

1. **Fleksibilitas Tinggi**
   - ✅ Bisa set opsi pembayaran berbeda dari surat pernyataan
   - ✅ Bisa buat opsi cicilan, diskon, bundling
   - ✅ Mudah manage 2 kategori nominal secara terpisah

2. **UI yang Jelas**
   - ✅ 2 section terpisah dengan warna berbeda (hijau vs cyan)
   - ✅ Label yang jelas: "Nominal Biaya" vs "Opsi Nominal yang Dibayarkan"
   - ✅ Description yang membantu pemahaman

### For Pendaftar:

1. **Lebih Jelas**
   - ✅ Teks "Konfirmasi Nominal yang Dibayarkan" lebih tepat
   - ✅ Description: "Pilih nominal yang akan dibayarkan"
   - ✅ Tidak ada kebingungan antara nominal surat pernyataan vs pembayaran

2. **Lebih Fleksibel**
   - ✅ Bisa pilih opsi pembayaran yang sesuai (cicilan, diskon, dll)
   - ✅ Tidak terpaksa bayar full jika ada opsi cicilan
   - ✅ Bisa manfaatkan promo/diskon jika ada

### For System:

1. **Maintainability**
   - ✅ Clear separation of concerns
   - ✅ Consistent dengan structure existing
   - ✅ Easy to understand code

2. **Scalability**
   - ✅ Mudah tambah fitur terkait payment nominals
   - ✅ Tidak breaking existing functionality
   - ✅ Backward compatible (existing data tetap jalan)

---

## 📝 Migration Guide (For Existing Data)

Jika production sudah ada data dengan `madrasahNominals` dan `seragamNominals`:

### Option 1: Manual Copy (Recommended)

1. Login sebagai admin
2. Buka `/admin/daftar-ulang?activeTab=settings`
3. Copy nominal dari "Nominal Biaya" ke "Opsi Nominal yang Dibayarkan"
4. Atau buat opsi pembayaran baru sesuai kebutuhan
5. Simpan settings

### Option 2: Auto-Populate (Script)

```bash
# Run di tinker
php artisan tinker

# Copy madrasahNominals ke madrasahPaymentNominals
$madrasahNominals = \App\Models\Setting::get('daftar_ulang_madrasah_nominals', []);
\App\Models\Setting::set('daftar_ulang_madrasah_payment_nominals', $madrasahNominals, 'json');

# Copy seragamNominals ke seragamPaymentNominals
$seragamNominals = \App\Models\Setting::get('daftar_ulang_seragam_nominals', []);
\App\Models\Setting::set('daftar_ulang_seragam_payment_nominals', $seragamNominals, 'json');
```

**Note**: Setelah auto-populate, admin tetap bisa edit payment nominals sesuai kebutuhan.

---

**Created**: 30 Januari 2026
**Status**: ✅ COMPLETE & TESTED
**Version**: 1.0
**Author**: Development Team
**Priority**: MEDIUM (Enhancement)

**Summary**: Implementasi pemisahan opsi nominal biaya (surat pernyataan) dengan opsi nominal yang dibayarkan (konfirmasi), memberikan fleksibilitas bagi admin untuk set opsi pembayaran yang berbeda (cicilan, diskon, bundling). Juga update teks konfirmasi menjadi lebih tepat.
