# 📋 INSTRUKSI LENGKAP: FITUR LAPORAN TES PSIKOLOGI

## ✅ Yang Sudah Selesai (Backend Logic)

1. ✅ **Admin\Laporan.php** - Sudah ditambahkan method `getTesPsikologiStats()`
2. ✅ **Admin laporan view** - Sudah ditambahkan tab & UI lengkap
3. ✅ **Panitia\Laporan.php** - Sudah ditambahkan method `getTesPsikologiStats()`
4. ✅ **Box Informasi bug fix** - Field tidak kosong lagi setelah save

## 🔄 Yang Perlu Dilengkapi (UI & Config)

### 1. Update Panitia Laporan View
### 2. Update Sidebar (2 roles)
### 3. Update Routes

---

## 📝 STEP 1: Update Panitia Laporan View

**File**: `resources/views/livewire/panitia/laporan.blade.php`

### A. Tambah Tab Button

Cari bagian tab navigation (biasanya dekat awal file), lalu tambahkan button untuk tab Tes Psikologi.

**Lokasi**: Setelah tab terakhir (biasanya "Seragam"), tambahkan:

```blade
<button
    wire:click="switchTab('tes_psikologi')"
    class="{{$activeTab === 'tes_psikologi' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'}} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
    🧠 Tes Psikologi
</button>
```

### B. Tambah Tab Content

Cari bagian `@endif` yang menutup semua tab content, lalu **SEBELUM** `@endif` tersebut, tambahkan:

```blade
@elseif($activeTab === 'tes_psikologi')
<!-- Filters for Tes Psikologi -->
<div class="bg-white rounded-lg shadow overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-200 bg-purple-600 flex items-center">
        <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <h3 class="text-lg font-bold text-white uppercase tracking-wider">Filter Laporan Tes Psikologi</h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                <input type="date" wire:model.live="tanggalMulai" class="w-full rounded-lg border-gray-300 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                <input type="date" wire:model.live="tanggalAkhir" class="w-full rounded-lg border-gray-300 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jalur</label>
                <select wire:model.live="filterJalur" class="w-full rounded-lg border-gray-300 shadow-sm">
                    <option value="">Semua Jalur</option>
                    @foreach($jalurPendaftaran as $jalur)
                        <option value="{{ $jalur->id }}">{{ $jalur->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Verifikasi</label>
                <select wire:model.live="filterStatusTesPsikologi" class="w-full rounded-lg border-gray-300 shadow-sm">
                    <option value="">Semua Status</option>
                    <option value="belum_upload">Belum Upload</option>
                    <option value="menunggu">Menunggu Verifikasi</option>
                    <option value="disetujui">Disetujui</option>
                    <option value="ditolak">Ditolak</option>
                    <option value="perbaiki">Perlu Perbaikan</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards for Tes Psikologi -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
    <!-- Total -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm opacity-90 font-medium">Total</p>
                <p class="text-3xl font-bold mt-1">{{ $tesPsikologiStats['stats']['total'] }}</p>
            </div>
            <div class="bg-white/20 p-3 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Belum Upload -->
    <div class="bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm opacity-90 font-medium">Belum Upload</p>
                <p class="text-3xl font-bold mt-1">{{ $tesPsikologiStats['stats']['belum_upload'] }}</p>
            </div>
            <div class="bg-white/20 p-3 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Menunggu Verifikasi -->
    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm opacity-90 font-medium">Menunggu</p>
                <p class="text-3xl font-bold mt-1">{{ $tesPsikologiStats['stats']['menunggu'] }}</p>
            </div>
            <div class="bg-white/20 p-3 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Disetujui -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm opacity-90 font-medium">Disetujui</p>
                <p class="text-3xl font-bold mt-1">{{ $tesPsikologiStats['stats']['disetujui'] }}</p>
            </div>
            <div class="bg-white/20 p-3 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Ditolak -->
    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm opacity-90 font-medium">Ditolak</p>
                <p class="text-3xl font-bold mt-1">{{ $tesPsikologiStats['stats']['ditolak'] }}</p>
            </div>
            <div class="bg-white/20 p-3 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Perlu Perbaikan -->
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm opacity-90 font-medium">Perbaikan</p>
                <p class="text-3xl font-bold mt-1">{{ $tesPsikologiStats['stats']['perbaiki'] }}</p>
            </div>
            <div class="bg-white/20 p-3 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Table: Statistik Per Jalur -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-purple-600">
        <h3 class="text-lg font-bold text-white uppercase tracking-wider">Statistik Tes Psikologi Per Jalur</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jalur Pendaftaran</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Belum Upload</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Menunggu</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Disetujui</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ditolak</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Perbaikan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($tesPsikologiStats['jalur_stats'] as $jalurStat)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $jalurStat['nama'] }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="text-sm font-bold text-purple-600">{{ $jalurStat['total'] }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                            {{ $jalurStat['belum_upload'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            {{ $jalurStat['menunggu'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            {{ $jalurStat['disetujui'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            {{ $jalurStat['ditolak'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                            {{ $jalurStat['perbaiki'] }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada data tes psikologi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
```

---

## 📝 STEP 2: Update Sidebar

**File**: `resources/views/components/layouts/dashboard.blade.php`

### A. Tambah Menu Laporan untuk panitia_tes_psikologi

Cari bagian menu untuk `panitia_tes_psikologi` (sekitar line 418-432), lalu tambahkan menu Laporan:

**BEFORE** (saat ini hanya 2 menu):
```blade
@elseif($role === 'panitia_tes_psikologi')
    <!-- Dashboard -->
    <a href="/panitia/dashboard" class="...">
        Dashboard
    </a>
    <!-- Tes Psikologi -->
    <a href="/panitia/tes-psikologi" class="...">
        Tes Psikologi
    </a>
@endif
```

**AFTER** (tambahkan menu Laporan):
```blade
@elseif($role === 'panitia_tes_psikologi')
    <!-- Dashboard -->
    <a href="/panitia/dashboard" class="flex items-center px-4 py-3 text-sm rounded-lg transition {{ request()->is('panitia/dashboard') ? $activeClass : $inactiveClass }}">
        <svg class="w-5 h-5 mr-3 {{ request()->is('panitia/dashboard') ? 'text-emerald-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
        Dashboard
    </a>

    <!-- Tes Psikologi -->
    <a href="/panitia/tes-psikologi" class="flex items-center px-4 py-3 text-sm rounded-lg transition {{ request()->is('panitia/tes-psikologi*') ? $activeClass : $inactiveClass }}">
        <svg class="w-5 h-5 mr-3 {{ request()->is('panitia/tes-psikologi*') ? 'text-emerald-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        Tes Psikologi
    </a>

    <!-- Laporan (NEW) -->
    <a href="/panitia/laporan" class="flex items-center px-4 py-3 text-sm rounded-lg transition {{ request()->is('panitia/laporan*') ? $activeClass : $inactiveClass }}">
        <svg class="w-5 h-5 mr-3 {{ request()->is('panitia/laporan*') ? 'text-emerald-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        Laporan
    </a>
@endif
```

### B. Menu Laporan untuk panitia_pendaftaran

Menu Laporan untuk `panitia_pendaftaran` **SUDAH ADA**, tidak perlu diubah. Mereka sudah bisa akses semua tab termasuk tab Tes Psikologi yang baru ditambahkan.

---

## 📝 STEP 3: Update Routes

**File**: `routes/web.php`

Cari bagian routes untuk `panitia` (sekitar line 314-336), lalu update:

**BEFORE**:
```php
Route::prefix('panitia')->middleware(['auth', 'role:panitia_pendaftaran,panitia_daftarulang,panitia_tes_psikologi'])->group(function () {
    // Dashboard - accessible by all panitia roles
    Route::get('/dashboard', \App\Livewire\Panitia\Dashboard::class)->name('panitia.dashboard');

    // Tes Psikologi - accessible by panitia_pendaftaran and panitia_tes_psikologi
    Route::get('/tes-psikologi', \App\Livewire\Panitia\TesPsikologi::class)
        ->middleware('role:panitia_pendaftaran,panitia_tes_psikologi')
        ->name('panitia.tes-psikologi');

    // Other routes - only accessible by panitia_pendaftaran and panitia_daftarulang
    Route::middleware('role:panitia_pendaftaran,panitia_daftarulang')->group(function () {
        Route::get('/pendaftar', \App\Livewire\Panitia\Pendaftar::class)->name('panitia.pendaftar');
        // ... other routes
    });
});
```

**AFTER** (tambahkan route laporan untuk panitia_tes_psikologi):
```php
Route::prefix('panitia')->middleware(['auth', 'role:panitia_pendaftaran,panitia_daftarulang,panitia_tes_psikologi'])->group(function () {
    // Dashboard - accessible by all panitia roles
    Route::get('/dashboard', \App\Livewire\Panitia\Dashboard::class)->name('panitia.dashboard');

    // Tes Psikologi - accessible by panitia_pendaftaran and panitia_tes_psikologi
    Route::get('/tes-psikologi', \App\Livewire\Panitia\TesPsikologi::class)
        ->middleware('role:panitia_pendaftaran,panitia_tes_psikologi')
        ->name('panitia.tes-psikologi');

    // Laporan - accessible by panitia_pendaftaran and panitia_tes_psikologi
    Route::get('/laporan', \App\Livewire\Panitia\Laporan::class)
        ->middleware('role:panitia_pendaftaran,panitia_tes_psikologi')
        ->name('panitia.laporan');

    // Other routes - only accessible by panitia_pendaftaran and panitia_daftarulang
    Route::middleware('role:panitia_pendaftaran,panitia_daftarulang')->group(function () {
        Route::get('/pendaftar', \App\Livewire\Panitia\Pendaftar::class)->name('panitia.pendaftar');
        // ... other routes
    });
});
```

---

## ✅ VERIFICATION CHECKLIST

Setelah semua perubahan dilakukan, test dengan checklist ini:

### Admin Laporan
- [ ] Login sebagai **Admin**
- [ ] Buka `/admin/laporan`
- [ ] Ada tab **"🧠 Tes Psikologi"** di deretan tab
- [ ] Klik tab Tes Psikologi
- [ ] Muncul 6 cards statistik (Total, Belum Upload, Menunggu, Disetujui, Ditolak, Perbaikan)
- [ ] Muncul tabel "Statistik Tes Psikologi Per Jalur"
- [ ] Filter berfungsi (tanggal, jalur, status)

### Panitia Tes Psikologi
- [ ] Login sebagai **Panitia Tes Psikologi**
- [ ] Sidebar menampilkan **3 menu**: Dashboard, Tes Psikologi, **Laporan** (NEW)
- [ ] Klik menu **Laporan**
- [ ] Ada tab **"🧠 Tes Psikologi"** di deretan tab
- [ ] Klik tab Tes Psikologi
- [ ] Muncul 6 cards statistik
- [ ] Muncul tabel per jalur
- [ ] Filter berfungsi

### Panitia Pendaftaran
- [ ] Login sebagai **Panitia Pendaftaran**
- [ ] Sidebar sudah ada menu **Laporan** (sudah ada sebelumnya)
- [ ] Klik menu Laporan
- [ ] Ada tab **"🧠 Tes Psikologi"** di deretan tab (NEW)
- [ ] Klik tab Tes Psikologi
- [ ] Semua berfungsi seperti admin

### Box Informasi Bug Fix
- [ ] Login sebagai **Admin**
- [ ] Buka `/admin/tes-psikologi?mainTab=settings`
- [ ] Isi field **"Box Informasi Khusus Admin & Panitia"** dengan text test
- [ ] Klik **"Simpan Pengaturan"**
- [ ] Field **TIDAK KOSONG** lagi, masih menampilkan text yang baru disimpan ✅

---

## 🎯 SUMMARY

### Yang Sudah Dikerjakan
1. ✅ Backend logic lengkap (Admin & Panitia Laporan component)
2. ✅ Admin Laporan view sudah lengkap
3. ✅ Bug fix Box Informasi

### Yang Perlu User Lakukan
1. ⏳ Copy-paste code untuk Panitia Laporan view
2. ⏳ Update sidebar untuk panitia_tes_psikologi (tambah menu Laporan)
3. ⏳ Update routes (tambah laporan untuk panitia_tes_psikologi)
4. ⏳ Test semua fitur
5. ⏳ Clear cache: `php artisan optimize:clear`

---

**Dibuat**: 27 Januari 2026
**Status**: Backend Complete, UI Pending
**Estimasi Waktu**: 15-30 menit untuk complete semua steps
