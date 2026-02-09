# Redesign Paket Seragam dengan Dependent Dropdown

**Tanggal:** 8 Februari 2026
**Status:** 🚧 IN PROGRESS

---

## 🎯 TUJUAN PERUBAHAN

Mengubah sistem Paket Seragam menjadi hierarchical dependent dropdown untuk memberikan fleksibilitas lebih dalam pengelolaan paket dan opsi pembayaran.

### Perubahan Utama:

**BEFORE:**
- Paket Seragam: Hanya nama dan deskripsi (tidak ada harga)
- Nominal Biaya Seragam: Daftar flat nominal terpisah
- Nominal yang Dibayarkan: Daftar flat nominal terpisah

**AFTER:**
- Paket Seragam: Hierarchical (Paket → Opsi dengan harga)
- Konfirmasi Nominal: Auto-populate dari opsi yang dipilih (atau input manual jika bayar tidak penuh)
- Form Split: 2 section terpisah (Paket/Nominal vs Pembayaran/Upload)

---

## 📊 NEW DATA STRUCTURE

### Admin Settings - seragamPackages

```php
seragamPackages = [
    [
        'id' => 'pkg_1',  // unique identifier
        'name' => 'Paket Lengkap',
        'description' => 'Semua seragam (Harian + Olahraga + Jas + Kopyah)',
        'options' => [
            [
                'id' => 'opt_1',
                'name' => 'Opsi 1 - Semua dengan Jas',
                'amount' => 1500000
            ],
            [
                'id' => 'opt_2',
                'name' => 'Opsi 2 - Semua tanpa Jas',
                'amount' => 1200000
            ]
        ]
    ],
    [
        'id' => 'pkg_2',
        'name' => 'Paket Harian Saja',
        'description' => 'Seragam harian (Baju + Celana/Rok + Kopyah)',
        'options' => [
            [
                'id' => 'opt_3',
                'name' => 'Baju + Celana/Rok + Kopyah',
                'amount' => 500000
            ],
            [
                'id' => 'opt_4',
                'name' => 'Baju + Celana/Rok (tanpa Kopyah)',
                'amount' => 400000
            ]
        ]
    ],
    [
        'id' => 'pkg_3',
        'name' => 'Paket Olahraga Saja',
        'description' => 'Seragam olahraga',
        'options' => [
            [
                'id' => 'opt_5',
                'name' => 'Baju + Celana Olahraga',
                'amount' => 350000
            ]
        ]
    ]
]
```

### Settings yang Dihapus

```php
// DEPRECATED - Will be removed
seragamNominals = []  // Diganti dengan options di dalam packages
seragamPaymentNominals = []  // Auto-populate dari opsi yang dipilih
```

### Settings yang Tetap Ada

```php
seragamEnableOpsiLainnya = true/false  // Untuk input manual di konfirmasi nominal
seragamPaymentOptions = ['lunas' => true, 'angsuran' => true, 'potongan' => false]
seragamBanks = [['name' => 'BCA'], ['name' => 'BRI']]
seragamPaymentStart = '2026-02-10 08:00:00'
seragamPaymentEnd = '2026-03-15 23:59:59'
seragamRequirePaymentProof = true
```

---

## 🔄 FLOW DIAGRAM

### Admin Flow

```
Admin Settings
    ↓
[Create Paket "Paket Lengkap"]
    ↓
[Add Option 1: "Semua dengan Jas" - Rp 1.500.000]
[Add Option 2: "Semua tanpa Jas" - Rp 1.200.000]
    ↓
[Save Settings]
```

### Pendaftar Flow

```
[1. Pilih Paket Seragam]
Dropdown: -- Pilih Paket --
          Paket Lengkap
          Paket Harian Saja
          Paket Olahraga Saja

    ↓ (select "Paket Lengkap")

[2. Pilih Opsi dari Paket]
Dropdown: -- Pilih Opsi --
          Opsi 1 - Semua dengan Jas (Rp 1.500.000)
          Opsi 2 - Semua tanpa Jas (Rp 1.200.000)

    ↓ (select "Opsi 1")

[3. Konfirmasi Nominal yang Dibayarkan]
Auto-populate: Rp 1.500.000 (dari Opsi 1)
OR
Pilih "Opsi Lainnya" untuk input manual (bayar tidak penuh)

=========== SECTION BREAK ===========

[4. Pilih Opsi Pembayaran]
Dropdown: Lunas / Angsuran / Potongan

[5. Pilih Bank/Metode Pembayaran]
Dropdown: BCA / BRI / Tunai

[6. Nama Pengirim] 🆕
Input text: Ahmad Zulkifli

[7. Upload Bukti Pembayaran]
File upload: bukti_transfer.jpg
```

---

## 📝 CHANGES BREAKDOWN

### 1. Admin View Changes

**File:** `resources/views/livewire/admin/daftar-ulang.blade.php`

**Section: Pengaturan Daftar Ulang Seragam (Lines 467-665)**

**REMOVE:**
- "Nominal Biaya Seragam" section (lines 526-554)
- "Opsi Nominal yang Dibayarkan" section (lines 556-585)

**UPDATE:**
- "Paket Seragam" section menjadi hierarchical CRUD with nested options

**NEW STRUCTURE:**

```blade
<!-- Paket Seragam (Hierarchical) -->
<div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4">
    <div class="flex items-center justify-between mb-3">
        <label class="block text-sm font-semibold text-cyan-900">Paket Seragam</label>
        <button type="button" wire:click="addSeragamPackage" class="text-xs bg-cyan-600 text-white px-3 py-1 rounded hover:bg-cyan-700">
            + Tambah Paket
        </button>
    </div>
    <p class="text-xs text-gray-600 mb-3">Buat paket seragam dengan opsi-opsi dan harga yang berbeda</p>

    @foreach($seragamPackages as $pkgIndex => $package)
        <div class="bg-white border-2 border-cyan-300 rounded-lg p-4 mb-4">
            <!-- Package Header -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                <input type="text"
                       wire:model="seragamPackages.{{ $pkgIndex }}.name"
                       placeholder="Nama Paket (contoh: Paket Lengkap)"
                       class="rounded border-cyan-400 text-sm font-semibold">
                <textarea wire:model="seragamPackages.{{ $pkgIndex }}.description"
                          placeholder="Deskripsi paket"
                          rows="2"
                          class="rounded border-cyan-400 text-sm"></textarea>
                <button type="button"
                        wire:click="removeSeragamPackage({{ $pkgIndex }})"
                        wire:confirm="Yakin ingin menghapus paket ini beserta semua opsinya?"
                        class="text-red-600 hover:text-red-800 text-sm font-medium">
                    🗑️ Hapus Paket
                </button>
            </div>

            <!-- Package Options -->
            <div class="bg-green-50 border border-green-300 rounded-lg p-3">
                <div class="flex items-center justify-between mb-2">
                    <label class="text-xs font-semibold text-green-900">Opsi dari {{ $package['name'] ?? 'Paket ini' }}</label>
                    <button type="button"
                            wire:click="addSeragamPackageOption({{ $pkgIndex }})"
                            class="text-xs bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700">
                        + Tambah Opsi
                    </button>
                </div>

                @if(isset($package['options']) && is_array($package['options']))
                    @foreach($package['options'] as $optIndex => $option)
                        <div class="bg-white border border-green-300 rounded p-2 mb-2">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
                                <input type="text"
                                       wire:model="seragamPackages.{{ $pkgIndex }}.options.{{ $optIndex }}.name"
                                       placeholder="Nama Opsi (contoh: Semua dengan Jas)"
                                       class="rounded border-gray-300 text-xs col-span-2">
                                <input type="number"
                                       wire:model="seragamPackages.{{ $pkgIndex }}.options.{{ $optIndex }}.amount"
                                       placeholder="Harga (Rp)"
                                       class="rounded border-gray-300 text-xs">
                                <button type="button"
                                        wire:click="removeSeragamPackageOption({{ $pkgIndex }}, {{ $optIndex }})"
                                        wire:confirm="Yakin ingin menghapus opsi ini?"
                                        class="text-red-600 hover:text-red-800 text-xs">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endif

                @if(!isset($package['options']) || count($package['options']) == 0)
                    <p class="text-xs text-gray-500 italic text-center py-2">Belum ada opsi. Klik "+ Tambah Opsi" untuk menambah.</p>
                @endif
            </div>
        </div>
    @endforeach

    @if(count($seragamPackages) == 0)
        <div class="text-center py-8 text-gray-500">
            <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
            </svg>
            <p class="text-sm">Belum ada paket seragam</p>
            <p class="text-xs mt-1">Klik "Tambah Paket" untuk membuat paket baru</p>
        </div>
    @endif
</div>

<!-- Checkbox: Aktifkan Opsi Lainnya untuk Konfirmasi Nominal -->
<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
    <label class="flex items-center">
        <input type="checkbox"
               wire:model="seragamEnableOpsiLainnya"
               class="rounded border-gray-300 text-yellow-600">
        <span class="ml-2 text-sm font-semibold text-yellow-900">
            Aktifkan "Opsi Lainnya" untuk Konfirmasi Nominal yang Dibayarkan
        </span>
    </label>
    <p class="text-xs text-yellow-700 mt-2 ml-6">
        Jika diaktifkan, pendaftar dapat memilih "Opsi Lainnya" untuk mengisi nominal pembayaran secara manual (misalnya untuk pembayaran tidak penuh/angsuran).
    </p>
</div>
```

---

### 2. Admin Livewire Changes

**File:** `app/Livewire/Admin/DaftarUlang.php`

**Properties to Remove:**
```php
public $seragamNominals = [];
public $seragamPaymentNominals = [];
public $seragamPaymentNominalsEnableOpsiLainnya = false;
```

**Properties to Keep/Update:**
```php
public $seragamPackages = [];  // Now hierarchical
public $seragamEnableOpsiLainnya = false;  // For manual input
```

**New Methods:**

```php
public function addSeragamPackage()
{
    $this->seragamPackages[] = [
        'id' => 'pkg_' . uniqid(),
        'name' => '',
        'description' => '',
        'options' => []
    ];
}

public function removeSeragamPackage($index)
{
    unset($this->seragamPackages[$index]);
    $this->seragamPackages = array_values($this->seragamPackages);
}

public function addSeragamPackageOption($packageIndex)
{
    if (!isset($this->seragamPackages[$packageIndex]['options'])) {
        $this->seragamPackages[$packageIndex]['options'] = [];
    }

    $this->seragamPackages[$packageIndex]['options'][] = [
        'id' => 'opt_' . uniqid(),
        'name' => '',
        'amount' => ''
    ];
}

public function removeSeragamPackageOption($packageIndex, $optionIndex)
{
    unset($this->seragamPackages[$packageIndex]['options'][$optionIndex]);
    $this->seragamPackages[$packageIndex]['options'] = array_values($this->seragamPackages[$packageIndex]['options']);
}

public function saveSeragamSettings()
{
    // Save settings
    \App\Models\Setting::set('daftar_ulang_seragam_info', $this->seragamInfo);
    \App\Models\Setting::set('daftar_ulang_seragam_packages', $this->seragamPackages, 'json');
    // Remove old settings
    // \App\Models\Setting::set('daftar_ulang_seragam_nominals', [], 'json');  // Deprecated
    // \App\Models\Setting::set('daftar_ulang_seragam_payment_nominals', [], 'json');  // Deprecated
    \App\Models\Setting::set('daftar_ulang_seragam_payment_options', $this->seragamPaymentOptions, 'json');
    \App\Models\Setting::set('daftar_ulang_seragam_banks', $this->seragamBanks, 'json');
    \App\Models\Setting::set('daftar_ulang_seragam_payment_start', $this->seragamPaymentStart);
    \App\Models\Setting::set('daftar_ulang_seragam_payment_end', $this->seragamPaymentEnd);
    \App\Models\Setting::set('daftar_ulang_seragam_require_payment_proof', $this->seragamRequirePaymentProof, 'boolean');
    \App\Models\Setting::set('daftar_ulang_seragam_enable_opsi_lainnya', $this->seragamEnableOpsiLainnya, 'boolean');

    $this->dispatch('saved', message: 'Pengaturan daftar ulang seragam berhasil disimpan.');
}
```

**Mount Method - Update Loading:**

```php
// In mount()
$this->seragamPackages = \App\Models\Setting::get('daftar_ulang_seragam_packages', []);
// Remove these:
// $this->seragamNominals = \App\Models\Setting::get('daftar_ulang_seragam_nominals', []);
// $this->seragamPaymentNominals = \App\Models\Setting::get('daftar_ulang_seragam_payment_nominals', []);
// $this->seragamPaymentNominalsEnableOpsiLainnya = \App\Models\Setting::get('daftar_ulang_seragam_payment_nominals_enable_opsi_lainnya', false);
```

---

### 3. Pendaftar View Changes

**File:** `resources/views/livewire/pendaftar/daftar-ulang.blade.php`

**Location:** Seragam Tab (around lines 1359-1595)

**NEW STRUCTURE:**

```blade
<!-- SECTION 1: PILIH PAKET & NOMINAL -->
<div class="bg-gradient-to-br from-blue-50 to-cyan-50 border-2 border-blue-200 rounded-lg p-6 mb-6">
    <h4 class="text-lg font-bold text-blue-900 mb-4 flex items-center">
        <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
        </svg>
        Step 1: Pilih Paket Seragam & Nominal
    </h4>

    @php
        $seragamPackages = \App\Models\Setting::get('daftar_ulang_seragam_packages', []);
    @endphp

    <!-- 1. Pilih Paket Seragam -->
    @if($seragamPackages && count($seragamPackages) > 0)
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Pilih Paket Seragam <span class="text-red-500">*</span>
            </label>
            <select wire:model.live="paketSeragam"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 {{ $isSeragamApproved ? 'opacity-60 cursor-not-allowed' : '' }}"
                    {{ $isSeragamApproved ? 'disabled' : '' }}>
                <option value="">-- Pilih Paket Seragam --</option>
                @foreach($seragamPackages as $package)
                    <option value="{{ json_encode($package) }}">
                        {{ $package['name'] ?? 'Paket' }}
                        @if(!empty($package['description'])) - {{ $package['description'] }}@endif
                    </option>
                @endforeach
            </select>
            @error('paketSeragam')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- 2. Pilih Opsi dari Paket (Dependent Dropdown) -->
        @if($paketSeragam)
            @php
                $selectedPackage = json_decode($paketSeragam, true);
                $packageOptions = $selectedPackage['options'] ?? [];
            @endphp

            @if(count($packageOptions) > 0)
                <div class="mb-4 ml-4 p-4 bg-white border-l-4 border-blue-500 rounded-lg">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Opsi dari {{ $selectedPackage['name'] ?? 'Paket' }} <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-2">
                        @foreach($packageOptions as $option)
                            <label class="flex items-center p-3 border rounded-lg {{ $isSeragamApproved ? 'cursor-not-allowed opacity-60' : 'cursor-pointer hover:bg-blue-50' }} transition-colors {{ $opsiSeragam == json_encode($option) ? 'border-blue-500 bg-blue-50' : 'border-gray-300' }}">
                                <input type="radio"
                                       wire:model.live="opsiSeragam"
                                       value="{{ json_encode($option) }}"
                                       class="text-blue-600 focus:ring-blue-500"
                                       {{ $isSeragamApproved ? 'disabled' : '' }}>
                                <div class="ml-3 flex-1 flex justify-between items-center">
                                    <span class="font-medium text-gray-900">{{ $option['name'] ?? '-' }}</span>
                                    <span class="font-bold text-blue-700">Rp {{ number_format($option['amount'] ?? 0, 0, ',', '.') }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('opsiSeragam')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @else
                <div class="mb-4 ml-4 p-4 bg-yellow-50 border border-yellow-300 rounded-lg">
                    <p class="text-sm text-yellow-800">Paket ini belum memiliki opsi. Silakan hubungi admin.</p>
                </div>
            @endif
        @endif

        <!-- 3. Konfirmasi Nominal yang Dibayarkan -->
        @if($opsiSeragam)
            @php
                $selectedOption = json_decode($opsiSeragam, true);
                $seragamEnableOpsiLainnya = \App\Models\Setting::get('daftar_ulang_seragam_enable_opsi_lainnya', false);
            @endphp

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Konfirmasi Nominal yang Dibayarkan <span class="text-red-500">*</span>
                </label>
                <p class="text-xs text-gray-600 mb-2">Pilih nominal yang akan dibayarkan (default: sesuai opsi yang dipilih)</p>

                <div class="space-y-2">
                    <!-- Option: Bayar Penuh (Auto from selected option) -->
                    <label class="flex items-center p-3 border rounded-lg {{ $isSeragamApproved ? 'cursor-not-allowed opacity-60' : 'cursor-pointer hover:bg-green-50' }} transition-colors {{ $konfirmasiNominalSeragam == json_encode($selectedOption) ? 'border-green-500 bg-green-50' : 'border-gray-300' }}">
                        <input type="radio"
                               wire:model.live="konfirmasiNominalSeragam"
                               value="{{ json_encode($selectedOption) }}"
                               class="text-green-600 focus:ring-green-500"
                               {{ $isSeragamApproved ? 'disabled' : '' }}>
                        <div class="ml-3 flex-1 flex justify-between items-center">
                            <div>
                                <span class="font-medium text-gray-900">Bayar Penuh</span>
                                <p class="text-xs text-gray-600 mt-1">{{ $selectedOption['name'] ?? '' }}</p>
                            </div>
                            <span class="font-bold text-green-700">Rp {{ number_format($selectedOption['amount'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </label>

                    <!-- Option: Opsi Lainnya (Manual Input) -->
                    @if($seragamEnableOpsiLainnya)
                        <label class="flex items-center p-3 border rounded-lg {{ $isSeragamApproved ? 'cursor-not-allowed opacity-60' : 'cursor-pointer hover:bg-yellow-50' }} transition-colors {{ $konfirmasiNominalSeragam == 'opsi_lainnya' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-300' }}">
                            <input type="radio"
                                   wire:model.live="konfirmasiNominalSeragam"
                                   value="opsi_lainnya"
                                   class="text-yellow-600 focus:ring-yellow-500"
                                   {{ $isSeragamApproved ? 'disabled' : '' }}>
                            <div class="ml-3 flex-1">
                                <span class="font-medium text-gray-900">Opsi Lainnya (Bayar Tidak Penuh)</span>
                                <p class="text-xs text-gray-500 mt-1">Isi nominal secara manual (untuk angsuran/potongan)</p>
                            </div>
                        </label>

                        @if($konfirmasiNominalSeragam == 'opsi_lainnya')
                            <div class="ml-6 mt-3 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Masukkan Nominal (Rp)</label>
                                <input type="number"
                                       wire:model.live="konfirmasiNominalSeragamOpsiLainnya"
                                       placeholder="Contoh: 750000"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200"
                                       {{ $isSeragamApproved ? 'disabled' : '' }}>
                                @error('konfirmasiNominalSeragamOpsiLainnya')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    @endif
                </div>

                @error('konfirmasiNominalSeragam')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        @endif
    @else
        <div class="p-4 bg-gray-50 border border-gray-300 rounded-lg text-center">
            <p class="text-sm text-gray-600">Belum ada paket seragam yang tersedia. Silakan hubungi admin.</p>
        </div>
    @endif
</div>

<!-- SECTION BREAK -->
<div class="my-6 border-t-4 border-dashed border-gray-300"></div>

<!-- SECTION 2: PEMBAYARAN & UPLOAD -->
<div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-lg p-6">
    <h4 class="text-lg font-bold text-green-900 mb-4 flex items-center">
        <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        Step 2: Informasi Pembayaran & Upload Bukti
    </h4>

    <!-- 4. Opsi Pembayaran -->
    @php
        $seragamPaymentOptions = \App\Models\Setting::get('daftar_ulang_seragam_payment_options', ['lunas' => false, 'angsuran' => false, 'potongan' => false]);
        $availableSeragamOptions = array_filter($seragamPaymentOptions);
    @endphp

    @if(!empty($availableSeragamOptions))
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Opsi Pembayaran <span class="text-red-500">*</span></label>
            <select wire:model="opsiPembayaranSeragam"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 {{ $isSeragamApproved ? 'opacity-60 cursor-not-allowed' : '' }}"
                    {{ $isSeragamApproved ? 'disabled' : '' }}>
                <option value="">-- Pilih Opsi --</option>
                @foreach($availableSeragamOptions as $option => $enabled)
                    @if($enabled)
                        <option value="{{ $option }}">{{ ucfirst($option) }}</option>
                    @endif
                @endforeach
            </select>
            @error('opsiPembayaranSeragam')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    @endif

    <!-- 5. Bank/Metode Pembayaran -->
    @php
        $seragamBanks = \App\Models\Setting::get('daftar_ulang_seragam_banks', []);
    @endphp

    @if($seragamBanks && count($seragamBanks) > 0)
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Daftar Bank/Metode Pembayaran <span class="text-red-500">*</span></label>
            <select wire:model="bankSeragam"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 {{ $isSeragamApproved ? 'opacity-60 cursor-not-allowed' : '' }}"
                    {{ $isSeragamApproved ? 'disabled' : '' }}>
                <option value="">-- Pilih Bank/Metode Pembayaran --</option>
                @foreach($seragamBanks as $bank)
                    <option value="{{ $bank['name'] ?? '' }}">
                        {{ $bank['name'] ?? 'Bank' }}@if(!empty($bank['account_number'])) - {{ $bank['account_number'] }}@endif@if(!empty($bank['account_name'])) ({{ $bank['account_name'] }})@endif
                    </option>
                @endforeach
            </select>
            @error('bankSeragam')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    @endif

    <!-- 6. Nama Pengirim 🆕 -->
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Nama Pengirim <span class="text-red-500">*</span>
        </label>
        <p class="text-xs text-gray-600 mb-2">Nama orang yang mengirimkan pembayaran (sesuai bukti transfer)</p>
        <input type="text"
               wire:model="namaPengirimSeragam"
               placeholder="Contoh: Ahmad Zulkifli"
               class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 {{ $isSeragamApproved ? 'opacity-60 cursor-not-allowed' : '' }}"
               {{ $isSeragamApproved ? 'disabled' : '' }}>
        @error('namaPengirimSeragam')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- 7. Upload Bukti Pembayaran -->
    @php
        $requireSeragamPaymentProof = \App\Models\Setting::get('daftar_ulang_seragam_require_payment_proof', false);
    @endphp

    @if($requireSeragamPaymentProof)
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Pembayaran <span class="text-red-500">*</span></label>

            @if(!$isSeragamPaymentOpen)
                <div class="p-4 bg-red-50 border-2 border-red-200 rounded-lg mb-3">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-red-800">Waktu Upload Telah Berakhir</p>
                            <p class="text-xs text-red-600 mt-1">Upload bukti pembayaran tidak dapat dilakukan karena melewati batas waktu yang ditentukan.</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($pendaftar->seragam && $pendaftar->seragam->bukti_pembayaran && !in_array($pendaftar->seragam->status_seragam, ['perbaiki', 'ditolak']))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-green-900">
                                Bukti pembayaran telah diupload. Menunggu persetujuan Admin/Panitia.
                            </p>
                            @if($pendaftar->seragam->waktu_pembayaran_seragam)
                                <p class="text-xs text-green-700 mt-1">
                                    Diupload: {{ \Carbon\Carbon::parse($pendaftar->seragam->waktu_pembayaran_seragam)->format('d M Y H:i') }}
                                </p>
                            @endif
                        </div>
                        <a href="{{ route('download.file', ['type' => 'bukti_seragam', 'id' => $pendaftar->id]) }}"
                           target="_blank"
                           class="ml-4 px-4 py-2 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition-colors">
                            Lihat Bukti
                        </a>
                    </div>
                </div>
            @else
                <div class="space-y-3">
                    <input type="file"
                           wire:model="bukti_seragam"
                           accept="image/*,.pdf"
                           class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none {{ $isSeragamApproved ? 'opacity-60 cursor-not-allowed' : '' }}"
                           {{ $isSeragamApproved ? 'disabled' : '' }}>
                    @error('bukti_seragam')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500">Format: JPG, PNG, atau PDF (Max 2MB)</p>
                </div>
            @endif
        </div>
    @endif
</div>

<!-- Save Button -->
<div class="mt-6 flex justify-end">
    <button type="submit"
            wire:loading.attr="disabled"
            class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            {{ !$isSeragamPaymentOpen || $isSeragamApproved ? 'disabled' : '' }}>
        <span wire:loading.remove wire:target="saveSeragam">Simpan Data Seragam</span>
        <span wire:loading wire:target="saveSeragam">Menyimpan...</span>
    </button>
</div>
```

---

### 4. Pendaftar Livewire Changes

**File:** `app/Livewire/Pendaftar/DaftarUlang.php`

**New Properties:**

```php
public $paketSeragam = null;  // Selected package (JSON)
public $opsiSeragam = null;  // Selected option from package (JSON)
public $konfirmasiNominalSeragam = null;  // Confirmation (auto or manual)
public $konfirmasiNominalSeragamOpsiLainnya = null;  // Manual input amount
public $namaPengirimSeragam = null;  // NEW: Sender name
```

**Remove Properties:**

```php
// public $nominalSeragam = null;  // DEPRECATED
// public $seragamOpsiLainnyaNominal = null;  // DEPRECATED
```

**Validation Rules:**

```php
protected function rules()
{
    return [
        'paketSeragam' => 'required',
        'opsiSeragam' => 'required',
        'konfirmasiNominalSeragam' => 'required',
        'konfirmasiNominalSeragamOpsiLainnya' => 'required_if:konfirmasiNominalSeragam,opsi_lainnya|nullable|numeric|min:0',
        'opsiPembayaranSeragam' => 'required',
        'bankSeragam' => 'required',
        'namaPengirimSeragam' => 'required|string|max:255',  // NEW
        'bukti_seragam' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ];
}
```

**Save Method:**

```php
public function saveSeragam()
{
    $this->validate();

    // Parse selected option
    $selectedOption = json_decode($this->opsiSeragam, true);

    // Determine final nominal
    if ($this->konfirmasiNominalSeragam == 'opsi_lainnya') {
        $finalNominal = [
            'name' => 'Opsi Lainnya (Manual)',
            'amount' => $this->konfirmasiNominalSeragamOpsiLainnya
        ];
    } else {
        $finalNominal = json_decode($this->konfirmasiNominalSeragam, true);
    }

    // Save to database
    Seragam::updateOrCreate(
        ['pendaftar_id' => $this->pendaftar->id],
        [
            'paket_seragam' => $this->paketSeragam,  // JSON string
            'opsi_seragam' => $this->opsiSeragam,  // JSON string - NEW field
            'nominal_seragam' => $finalNominal,  // Array
            'nominal_seragam_awal' => $selectedOption,  // Array - original option
            'opsi_pembayaran_seragam' => $this->opsiPembayaranSeragam,
            'bank_seragam' => $this->bankSeragam,
            'nama_pengirim_seragam' => $this->namaPengirimSeragam,  // NEW
            'bukti_pembayaran' => $buktiPath,  // After upload
            'waktu_pembayaran_seragam' => now(),
            'status_seragam' => 'menunggu',
        ]
    );

    session()->flash('success', 'Data seragam berhasil disimpan!');
}
```

---

### 5. Model Changes

**File:** `app/Models/Seragam.php`

**Add to Fillable:**

```php
protected $fillable = [
    // ... existing fields ...
    'paket_seragam',  // JSON string
    'opsi_seragam',  // JSON string - NEW
    'nominal_seragam',  // Array
    'nominal_seragam_awal',  // Array
    'opsi_pembayaran_seragam',
    'bank_seragam',
    'nama_pengirim_seragam',  // NEW - string
    'bukti_pembayaran',
    'waktu_pembayaran_seragam',
    'status_seragam',
    // ... other fields ...
];
```

**Add to Casts:**

```php
protected $casts = [
    'nominal_seragam' => 'array',
    'nominal_seragam_awal' => 'array',
    'waktu_pembayaran_seragam' => 'datetime',
    // ... other casts ...
];
```

---

### 6. Database Migration

**File:** `database/migrations/2026_02_08_100000_add_opsi_seragam_and_nama_pengirim_to_seragam_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seragam', function (Blueprint $table) {
            $table->text('opsi_seragam')->nullable()->after('paket_seragam');
            // nama_pengirim_seragam might already exist if added in previous migration
            if (!Schema::hasColumn('seragam', 'nama_pengirim_seragam')) {
                $table->string('nama_pengirim_seragam')->nullable()->after('bank_seragam');
            }
        });
    }

    public function down(): void
    {
        Schema::table('seragam', function (Blueprint $table) {
            $table->dropColumn(['opsi_seragam']);
            // Don't drop nama_pengirim_seragam as it might be needed by other features
        });
    }
};
```

---

## 🎯 BENEFITS

### For Admin:
✅ **Hierarchical Management** - Organize packages with multiple options
✅ **Flexible Pricing** - Different prices for different options in same package
✅ **Clear Structure** - Easy to manage and understand package hierarchy
✅ **Less Redundancy** - No need separate nominal lists

### For Pendaftar:
✅ **Guided Selection** - Step-by-step process with dependent dropdowns
✅ **Clear Separation** - Paket/Nominal section separate from Payment/Upload section
✅ **Auto-populate** - Konfirmasi nominal auto-filled from selected option
✅ **Flexibility** - Still can input manual amount with "Opsi Lainnya"
✅ **Transparency** - See exact package → option → price flow

---

## 📊 TESTING CHECKLIST

### Admin Settings Testing:

- [ ] Can create new package
- [ ] Can add package name and description
- [ ] Can add options to package
- [ ] Can set option name and amount
- [ ] Can delete option from package
- [ ] Can delete entire package
- [ ] Can save settings successfully
- [ ] Checkbox "Aktifkan Opsi Lainnya" works
- [ ] Settings persist after save

### Pendaftar Flow Testing:

- [ ] Dropdown "Pilih Paket Seragam" shows all packages
- [ ] Selecting package shows dependent dropdown "Pilih Opsi"
- [ ] Opsi dropdown only shows options from selected package
- [ ] Selecting opsi auto-populates "Bayar Penuh" in konfirmasi nominal
- [ ] "Opsi Lainnya" appears if enabled in settings
- [ ] Can input manual amount in "Opsi Lainnya"
- [ ] Section 1 (Paket/Nominal) visually separated from Section 2 (Payment/Upload)
- [ ] "Nama Pengirim" field appears after Bank dropdown
- [ ] Can save all data successfully
- [ ] Data persists after reload

### Edge Cases:

- [ ] Package without options shows warning message
- [ ] Empty packages list shows empty state
- [ ] Validation works for all required fields
- [ ] Status "perbaiki"/"ditolak" allows re-upload
- [ ] Approved status disables all fields

---

## 🏁 DEPLOYMENT CHECKLIST

- [ ] Run migration for new fields
- [ ] Update Seragam model
- [ ] Update Admin Livewire component
- [ ] Update Admin view
- [ ] Update Pendaftar Livewire component
- [ ] Update Pendaftar view
- [ ] Test admin CRUD
- [ ] Test pendaftar flow
- [ ] Clear cache
- [ ] Test on production

---

## 📝 NOTES

This is a major refactor that changes the fundamental structure of how seragam packages work. The new hierarchical structure provides more flexibility and better user experience.

**Backward Compatibility:** Old data using `seragamNominals` and `seragamPaymentNominals` will need to be migrated to the new structure manually by admin through the settings interface.
