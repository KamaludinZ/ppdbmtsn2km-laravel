# ADD MODE OPSI PANITIA SERAGAM - RADIO/CHECKBOX
**Tanggal:** 21 Februari 2026
**Lokasi:** `/panitia-seragam/daftar-ulang`

## 🎯 TUJUAN

Menambahkan fitur mode opsi (radio button vs checkbox) di halaman Panitia Seragam, **persis seperti di Admin**, agar:
1. Panitia Seragam bisa mengatur mode pemilihan opsi per paket
2. Kolom nominal biaya bisa menampilkan multi-select dengan rincian

## 🔧 PERUBAHAN YANG DILAKUKAN

### 1. Settings Tab - Dropdown Mode Pemilihan Opsi

**File:** `resources/views/livewire/panitia-seragam/partials/settings-tab.blade.php`
**Location:** Line 102-116 (setelah package description)

**Perubahan:**
```blade
<!-- Mode Pemilihan Opsi (Radio vs Checkbox) -->
<div class="mb-3 p-3 bg-purple-50 border border-purple-200 rounded-lg">
    <label class="block text-xs font-semibold text-purple-900 mb-2">
        Mode Pemilihan Opsi
    </label>
    <select wire:model="seragamPackages.{{ $pkgIndex }}.selection_mode"
            class="w-full rounded border-purple-300 text-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
        <option value="radio">📻 Radio Button - Pilih Satu Opsi</option>
        <option value="checkbox">☑️ Checkbox - Pilih Banyak Opsi (Multi Select)</option>
    </select>
    <p class="text-xs text-purple-700 mt-2">
        • <strong>Radio Button:</strong> Pendaftar hanya bisa memilih 1 opsi<br>
        • <strong>Checkbox:</strong> Pendaftar bisa memilih beberapa opsi sekaligus (multi-select)
    </p>
</div>
```

**Benefits:**
- ✅ Same interface as Admin
- ✅ Clear explanation for each mode
- ✅ Purple theme consistent with Panitia Seragam branding

### 2. Kolom Nominal Biaya - Multi-select Display

**File:** `resources/views/livewire/panitia-seragam/daftar-ulang.blade.php`
**Location:** Line 266-304

**Before:**
```blade
<td class="px-6 py-4">
    @if($item->seragam && $item->seragam->nominal_seragam_awal)
        @php $nominalSeragamAwal = $item->seragam->nominal_seragam_awal; @endphp
        <div class="text-xs text-gray-900">
            <div class="font-medium text-gray-700 mb-1">{{ $nominalSeragamAwal['name'] ?? '-' }}</div>
            <div class="font-bold text-blue-700">Rp {{ number_format($nominalSeragamAwal['amount'] ?? 0, 0, ',', '.') }}</div>
        </div>
    @endif
</td>
```

**After:**
```blade
<td class="px-6 py-4">
    @if($item->seragam && $item->seragam->nominal_seragam_awal)
        @php
            $nominalSeragamAwal = $item->seragam->nominal_seragam_awal;
            // Deteksi apakah multi-select (array of options) atau single option
            $isMultiSelectSeragam = is_array($nominalSeragamAwal) && isset($nominalSeragamAwal[0]) && is_array($nominalSeragamAwal[0]);
        @endphp

        @if($isMultiSelectSeragam)
            {{-- Multi-select: tampilkan dengan badge --}}
            <div class="text-xs text-gray-900">
                <div class="font-medium text-purple-700 mb-1">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-purple-100 text-purple-800">
                        📦 Multi Select ({{ count($nominalSeragamAwal) }} opsi)
                    </span>
                </div>
                <div class="space-y-1 mt-1">
                    @foreach($nominalSeragamAwal as $idx => $opt)
                        <div class="text-xs text-gray-600">
                            {{ $idx + 1 }}. {{ $opt['name'] ?? '-' }} - Rp {{ number_format($opt['amount'] ?? 0, 0, ',', '.') }}
                        </div>
                    @endforeach
                    <div class="pt-1 border-t border-purple-200 font-bold text-purple-700">
                        Total: Rp {{ number_format(array_sum(array_column($nominalSeragamAwal, 'amount')), 0, ',', '.') }}
                    </div>
                </div>
            </div>
        @else
            {{-- Single option: tampilkan seperti biasa --}}
            <div class="text-xs text-gray-900">
                <div class="font-medium text-gray-700 mb-1">{{ $nominalSeragamAwal['name'] ?? '-' }}</div>
                <div class="font-bold text-blue-700">Rp {{ number_format($nominalSeragamAwal['amount'] ?? 0, 0, ',', '.') }}</div>
            </div>
        @endif
    @endif
</td>
```

**Features:**
- ✅ Badge indicator untuk multi-select
- ✅ Itemized list dengan numbering
- ✅ Total sum di bawah dengan highlight
- ✅ Backward compatible untuk single option

## 📊 TAMPILAN

### Settings Tab - Dropdown Mode Opsi:
```
┌─────────────────────────────────────────────────┐
│ Mode Pemilihan Opsi                              │
│ ┌─────────────────────────────────────────────┐ │
│ │ ☑️ Checkbox - Pilih Banyak Opsi (Multi)   ▼│ │
│ └─────────────────────────────────────────────┘ │
│                                                  │
│ • Radio Button: Pendaftar hanya bisa pilih 1    │
│ • Checkbox: Pendaftar bisa pilih beberapa       │
└─────────────────────────────────────────────────┘
```

### Kolom Nominal Biaya - Multi-select:
```
┌────────────────────────────────────────┐
│ Nominal Biaya                           │
│ ┌────────────────────────────────────┐ │
│ │ 📦 Multi Select (3 opsi)           │ │
│ │                                    │ │
│ │ 1. Baju - Rp 100.000              │ │
│ │ 2. Celana - Rp 80.000             │ │
│ │ 3. Topi - Rp 20.000               │ │
│ │ ─────────────────────────────────  │ │
│ │ Total: Rp 200.000                 │ │
│ └────────────────────────────────────┘ │
└────────────────────────────────────────┘
```

### Kolom Nominal Biaya - Single Option:
```
┌────────────────────────────────────────┐
│ Nominal Biaya                           │
│ ┌────────────────────────────────────┐ │
│ │ Baju Seragam                       │ │
│ │ Rp 100.000                         │ │
│ └────────────────────────────────────┘ │
└────────────────────────────────────────┘
```

## 📝 FILE YANG DIUBAH

### 1. Settings Tab Partial
**File:** `resources/views/livewire/panitia-seragam/partials/settings-tab.blade.php`
**Lines:** 102-116

**Changes:**
- Added selection_mode dropdown after package description
- Purple theme matching Panitia Seragam branding
- Clear explanation for each mode option

### 2. Main Daftar Ulang View
**File:** `resources/views/livewire/panitia-seragam/daftar-ulang.blade.php`
**Lines:** 266-304

**Changes:**
- Added detection logic for multi-select vs single option
- Multi-select: Badge + itemized list + total
- Single option: Original display format

## 🎯 FITUR YANG SAMA DENGAN ADMIN

### ✅ Settings Tab:
1. Dropdown mode opsi (radio/checkbox) per paket
2. Penjelasan untuk setiap mode
3. Wire:model binding ke `seragamPackages.{index}.selection_mode`

### ✅ Kolom Nominal Biaya:
1. Badge "📦 Multi Select (X opsi)" untuk multi-select
2. List dengan numbering untuk setiap opsi
3. Total sum di bawah dengan highlight
4. Backward compatible untuk single option

### ✅ Data Structure:
- Multi-select: Array of objects `[{name, amount}, {name, amount}]`
- Single: Object `{name, amount}`
- Detection logic sama persis

## ✅ TESTING CHECKLIST

### Test 1: Settings Tab - Mode Opsi
**Steps:**
1. Login sebagai Panitia Seragam
2. Go to `/panitia-seragam/daftar-ulang?activeTab=settings`
3. Buat/edit paket seragam
4. Lihat dropdown "Mode Pemilihan Opsi"

**Expected Result:**
- ✅ Dropdown tampil dengan 2 opsi (Radio/Checkbox)
- ✅ Penjelasan tampil di bawah dropdown
- ✅ Purple theme consistent

### Test 2: Save Settings
**Steps:**
1. Pilih mode "Checkbox" untuk paket A
2. Pilih mode "Radio" untuk paket B
3. Simpan pengaturan
4. Reload page

**Expected Result:**
- ✅ Mode tersimpan dengan benar
- ✅ Dropdown menampilkan pilihan yang benar

### Test 3: Kolom Nominal - Multi-select
**Steps:**
1. Pendaftar memilih paket checkbox, centang 3 opsi
2. Login sebagai Panitia Seragam
3. Go to tab "Daftar Ulang Seragam"
4. Lihat kolom "Nominal Biaya"

**Expected Result:**
- ✅ Badge "📦 Multi Select (3 opsi)"
- ✅ List 3 opsi dengan numbering
- ✅ Total sum di bawah
- ✅ Format: "1. Nama - Rp Amount"

### Test 4: Kolom Nominal - Single Option
**Steps:**
1. Pendaftar memilih paket radio, pilih 1 opsi
2. Login sebagai Panitia Seragam
3. Lihat kolom "Nominal Biaya"

**Expected Result:**
- ✅ Tampil nama opsi
- ✅ Tampil amount (Rp ...)
- ✅ Tidak ada badge/list

### Test 5: Konsistensi dengan Admin
**Steps:**
1. Login sebagai Admin, lihat kolom nominal biaya di `/admin/daftar-ulang?activeTab=seragam`
2. Login sebagai Panitia Seragam, lihat kolom yang sama
3. Bandingkan tampilan

**Expected Result:**
- ✅ Format tampilan identical
- ✅ Multi-select displayed sama
- ✅ Single option displayed sama

## 🚀 DEPLOYMENT

### Files Changed:
1. `resources/views/livewire/panitia-seragam/partials/settings-tab.blade.php` - Lines 102-116
2. `resources/views/livewire/panitia-seragam/daftar-ulang.blade.php` - Lines 266-304

### Upload Command:
```bash
upload_add_mode_opsi_panitia_seragam_21_feb_2026.bat
```

### After Upload:
```bash
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
```

## ✨ BENEFITS

### 1. Feature Parity
- Panitia Seragam punya akses yang sama dengan Admin
- Tidak perlu switch ke akun Admin untuk ubah mode opsi

### 2. Transparency
- Multi-select ditampilkan dengan jelas
- Total sum terlihat langsung
- Itemized breakdown memudahkan verifikasi

### 3. Consistency
- UI/UX sama dengan Admin
- Data structure sama
- Behavior sama

### 4. Flexibility
- Panitia Seragam bisa sesuaikan mode per paket
- Bisa mix radio dan checkbox di paket berbeda

## 📋 SUMMARY

**Sebelum:**
- ❌ Panitia Seragam tidak bisa atur mode opsi
- ❌ Kolom nominal biaya hanya tampil 1 opsi untuk multi-select
- ❌ Harus ke Admin untuk ubah mode

**Setelah:**
- ✅ Panitia Seragam bisa atur mode opsi di settings
- ✅ Kolom nominal biaya tampil semua opsi multi-select dengan total
- ✅ Independent dari Admin (tapi tetap synchronized)

Fix complete! 🎉
