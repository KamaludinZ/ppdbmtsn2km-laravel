# Perbaikan Halaman Panitia Seragam - Daftar Ulang

**Tanggal:** 24 Februari 2026
**Status:** ✅ SELESAI

## 🎯 Tujuan

Membuat halaman `/panitia-seragam/daftar-ulang` (tab seragam dan ukuran) mirip dengan halaman `/admin/daftar-ulang`, termasuk:
- ✅ Sortable table headers (bisa diklik untuk sorting)
- ✅ Kolom "Catatan Internal" di tabel
- ✅ Modal popup untuk edit catatan internal (tidak ditampilkan ke pendaftar)
- ✅ Modal popup untuk edit catatan pendaftar (ditampilkan ke pendaftar)

## 📝 Perubahan yang Dilakukan

### 1. File Livewire Component: `app/Livewire/PanitiaSeragam/DaftarUlang.php`

#### A. Tambah Trait WithSorting

```php
use WithPagination, WithSorting, WithFileUploads;
```

#### B. Tambah Properties untuk Catatan Internal

```php
// Catatan Internal Modal
public $showCatatanInternalModal = false;
public $catatanInternalType = ''; // 'seragam' atau 'ukuran'
public $catatanInternalPendaftarId = null;
public $catatanInternalText = '';
```

#### C. Tambah Methods untuk Catatan Internal

```php
public function openCatatanInternalModal($type, $pendaftarId, $currentCatatan = '')
{
    $this->catatanInternalType = $type;
    $this->catatanInternalPendaftarId = $pendaftarId;
    $this->catatanInternalText = $currentCatatan;
    $this->showCatatanInternalModal = true;
}

public function closeCatatanInternalModal()
{
    $this->showCatatanInternalModal = false;
    $this->catatanInternalType = '';
    $this->catatanInternalPendaftarId = null;
    $this->catatanInternalText = '';
}

public function saveCatatanInternal()
{
    try {
        if ($this->catatanInternalType === 'seragam') {
            $seragam = Seragam::firstOrNew(['pendaftar_id' => $this->catatanInternalPendaftarId]);
            $seragam->catatan_internal_seragam = $this->catatanInternalText;
            $seragam->save();

            $pendaftar = Pendaftar::find($this->catatanInternalPendaftarId);
            $pendaftar->touch();

            $this->dispatch('saved', message: 'Catatan internal seragam berhasil disimpan');
        } elseif ($this->catatanInternalType === 'ukuran') {
            $seragam = Seragam::firstOrNew(['pendaftar_id' => $this->catatanInternalPendaftarId]);
            $seragam->catatan_internal_ukuran = $this->catatanInternalText;
            $seragam->save();

            $pendaftar = Pendaftar::find($this->catatanInternalPendaftarId);
            $pendaftar->touch();

            $this->dispatch('saved', message: 'Catatan internal ukuran berhasil disimpan');
        }

        $this->closeCatatanInternalModal();
    } catch (\Exception $e) {
        $this->dispatch('saved', message: 'Gagal menyimpan catatan internal: ' . $e->getMessage());
    }
}
```

### 2. File View: `resources/views/livewire/panitia-seragam/daftar-ulang.blade.php`

#### A. Ubah Table Headers Menjadi Sortable

**Before:**
```blade
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
    Nomor Pendaftaran
</th>
```

**After:**
```blade
<x-sortable-header field="nomor_pendaftaran" :sort-field="$sortField" :sort-direction="$sortDirection" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
    Nomor Pendaftaran
</x-sortable-header>
```

Kolom yang dibuat sortable:
- `nomor_pendaftaran`
- `nama_lengkap`
- `jenis_kelamin`

#### B. Tambah Kolom "Catatan Internal" di Header Tabel

Ditambahkan untuk kedua tab (seragam dan ukuran):
```blade
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan Internal</th>
```

#### C. Tambah Cell "Catatan Internal" di Body Tabel

**Tab Seragam:**
```blade
<td class="px-6 py-4">
    <div class="flex items-center gap-2">
        @if($item->seragam && $item->seragam->catatan_internal_seragam)
            <div class="text-xs text-gray-700 bg-red-50 p-2 rounded border border-red-200 max-w-xs">
                <div class="font-semibold text-red-700 mb-1">🔒 Internal:</div>
                <p class="whitespace-pre-wrap">{{ $item->seragam->catatan_internal_seragam }}</p>
            </div>
        @else
            <span class="text-xs text-gray-400 italic">Belum ada catatan internal</span>
        @endif
        <button
            wire:click="openCatatanInternalModal('seragam', {{ $item->id }}, '{{ addslashes($item->seragam->catatan_internal_seragam ?? '') }}')"
            class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-700 bg-red-50 border border-red-300 rounded hover:bg-red-100 transition-colors"
            title="Edit Catatan Internal (Tidak ditampilkan ke pendaftar)">
            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            Edit
        </button>
    </div>
</td>
```

**Tab Ukuran:** (sama seperti tab seragam, tapi menggunakan `catatan_internal_ukuran`)

#### D. Tambah Modal Catatan Internal

Ditambahkan setelah modal catatan biasa (sebelum closing `</div>`):

```blade
{{-- Catatan Internal Modal --}}
<div x-data="{ show: @entangle('showCatatanInternalModal').live }"
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     aria-labelledby="modal-title"
     role="dialog"
     aria-modal="true">
    <!-- Modal backdrop dan content -->
    <div class="mt-3 text-center sm:mt-5">
        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
            🔒 Edit Catatan Internal {{ ucfirst($catatanInternalType) }}
        </h3>
        <p class="mt-2 text-sm text-red-600 font-semibold">
            ⚠️ Catatan ini TIDAK akan ditampilkan kepada pendaftar
        </p>
        <div class="mt-4">
            <textarea
                wire:model="catatanInternalText"
                rows="5"
                class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md"
                placeholder="Masukkan catatan internal (hanya untuk panitia/admin)..."></textarea>
        </div>
    </div>
    <!-- Save and Cancel buttons -->
</div>
```

## 🎨 Visual Design

### Perbedaan Catatan Pendaftar vs Catatan Internal

| Aspek | Catatan Pendaftar | Catatan Internal |
|-------|------------------|------------------|
| **Warna Theme** | Biru (indigo) | Merah (red) |
| **Icon** | 📝 Edit biasa | 🔒 Lock icon |
| **Visibility** | ✅ Ditampilkan ke pendaftar | ❌ TIDAK ditampilkan ke pendaftar |
| **Border Color** | `border-blue-300` | `border-red-300` |
| **Background** | `bg-blue-50` | `bg-red-50` |
| **Warning Text** | - | "⚠️ Catatan ini TIDAK akan ditampilkan kepada pendaftar" |

## 🗄️ Database Fields

### Table: `seragam`

- **`catatan_seragam`** - Catatan untuk pendaftar (visible)
- **`catatan_internal_seragam`** - Catatan internal seragam (hidden from pendaftar)
- **`catatan_ukuran`** - Catatan ukuran untuk pendaftar (visible)
- **`catatan_internal_ukuran`** - Catatan internal ukuran (hidden from pendaftar)

## 🧪 Testing

### Test Sortable Headers:
1. Buka `/panitia-seragam/daftar-ulang?activeTab=seragam`
2. Klik header "Nomor Pendaftaran" → tabel harus ter-sort ascending
3. Klik lagi → tabel harus ter-sort descending
4. Ulangi untuk "Nama Lengkap" dan "Jenis Kelamin"

### Test Catatan Internal - Tab Seragam:
1. Buka `/panitia-seragam/daftar-ulang?activeTab=seragam`
2. Klik tombol "Edit" di kolom "Catatan Internal"
3. Modal popup muncul dengan:
   - Judul: "🔒 Edit Catatan Internal Seragam"
   - Warning: "⚠️ Catatan ini TIDAK akan ditampilkan kepada pendaftar"
   - Textarea dengan placeholder
4. Ketik catatan, klik "💾 Simpan Catatan Internal"
5. Modal tutup, catatan muncul di tabel dengan background merah

### Test Catatan Internal - Tab Ukuran:
1. Buka `/panitia-seragam/daftar-ulang?activeTab=ukuran`
2. Klik tombol "Edit" di kolom "Catatan Internal"
3. Modal popup muncul dengan judul "🔒 Edit Catatan Internal Ukuran"
4. Simpan catatan
5. Verifikasi catatan tersimpan di field `catatan_internal_ukuran`

### Test Modal Catatan Pendaftar (sudah ada sebelumnya):
1. Klik tombol "Edit Catatan" di kolom "Catatan" (bukan Catatan Internal)
2. Modal biru muncul (tanpa warning merah)
3. Catatan ini akan VISIBLE ke pendaftar

## ✅ Checklist Verifikasi

- [x] Trait WithSorting ditambahkan ke Livewire component
- [x] Properties catatan internal ditambahkan
- [x] Methods openCatatanInternalModal, closeCatatanInternalModal, saveCatatanInternal ditambahkan
- [x] Table headers diganti dengan sortable-header component
- [x] Kolom "Catatan Internal" ditambahkan di header tabel (seragam & ukuran)
- [x] Cell catatan internal ditambahkan di body tabel (seragam & ukuran)
- [x] Modal catatan internal ditambahkan di view
- [x] Cache Laravel di-clear

## 🚀 Deployment ke Production

Jika ingin deploy ke production:

```bash
# 1. Upload file yang diubah
scp app/Livewire/PanitiaSeragam/DaftarUlang.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/PanitiaSeragam/
scp resources/views/livewire/panitia-seragam/daftar-ulang.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia-seragam/

# 2. SSH ke server dan clear cache
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear && php artisan cache:clear"
```

## 📊 Files Changed

1. `app/Livewire/PanitiaSeragam/DaftarUlang.php` - Livewire component logic
2. `resources/views/livewire/panitia-seragam/daftar-ulang.blade.php` - View template

## 🎯 Impact

- ✅ Panitia Seragam sekarang punya fitur lengkap seperti Admin
- ✅ Bisa sorting data di tabel
- ✅ Bisa membuat catatan internal yang tidak terlihat pendaftar
- ✅ UI konsisten antara role Admin dan Panitia Seragam

---

**Status:** COMPLETED ✅
**Tested:** Ready for testing
**Breaking Changes:** Tidak ada
