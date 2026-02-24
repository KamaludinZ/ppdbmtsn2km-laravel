# Fix Preview Kartu Tes - Clean Mode
**Tanggal**: 15 Februari 2026
**Jenis**: Bug Fix - UI Enhancement

---

## Masalah

Ketika admin membuka preview kartu tes dari tombol cetak (`/admin/preview-kartu-tes/{id}`), masih terlihat:
- ❌ Wrapper card dengan shadow dan rounded
- ❌ Judul "Kartu Tanda Peserta Tes Seleksi"
- ❌ Tombol "Cetak Kartu" dan "Bergabung ke Grup"

Seharusnya seperti invoice PDF - **hanya kartu saja** tanpa elemen UI tambahan.

---

## Solusi

### 1. Menambahkan Flag `$isPreviewMode`

**File**: `app/Livewire/Admin/PreviewKartuTes.php`

```php
public $isPreviewMode = true; // Flag untuk mode preview admin
```

Flag ini menandakan bahwa kartu dibuka dari preview admin, bukan dari dashboard pendaftar.

### 2. Conditional Rendering di View

**File**: `resources/views/livewire/pendaftar/kartu-tes-seleksi.blade.php`

Menggunakan conditional untuk membedakan tampilan:

**Mode Preview Admin** (`$isPreviewMode = true`):
```blade
@if(isset($isPreviewMode) && $isPreviewMode)
    <!-- Langsung tampilkan kartu saja tanpa wrapper -->
    <div class="mx-auto border-2 ...">
        <!-- Konten kartu -->
    </div>
@else
    <!-- Mode Pendaftar Normal: Dengan wrapper, judul, tombol -->
    <div class="bg-white rounded-lg shadow-lg ...">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2>Kartu Tanda Peserta Tes Seleksi</h2>
                <div>
                    <button>Cetak Kartu</button>
                    <a>Bergabung ke Grup</a>
                </div>
            </div>
            <div class="mx-auto border-2 ...">
                <!-- Konten kartu -->
            </div>
        </div>
    </div>
@endif
```

### 3. Menggunakan Layout Print

**File**: `app/Livewire/Admin/PreviewKartuTes.php`

```php
->layout('components.layouts.print', [...])
```

Layout print tidak memiliki sidebar, header, atau navigation - hanya konten murni.

---

## File yang Dimodifikasi

1. **app/Livewire/Admin/PreviewKartuTes.php**
   - Menambahkan property `public $isPreviewMode = true;`

2. **resources/views/livewire/pendaftar/kartu-tes-seleksi.blade.php**
   - Menambahkan conditional rendering berdasarkan `$isPreviewMode`
   - Wrapper dan header hanya ditampilkan jika `$isPreviewMode = false` atau tidak ada

3. **resources/views/components/layouts/print.blade.php**
   - Layout baru yang clean tanpa dashboard elements

---

## Perbandingan: Before vs After

### Before (Masalah)
```
┌────────────────────────────────────────┐
│ Sidebar │ ┌──────────────────────────┐│
│         │ │ Kartu Tanda Peserta Tes  ││
│  Menu   │ │ [Cetak] [Grup]          ││
│         │ │                          ││
│         │ │ ┌──────────────────────┐ ││
│         │ │ │   Konten Kartu      │ ││
│         │ │ │                      │ ││
│         │ │ └──────────────────────┘ ││
│         │ └──────────────────────────┘│
└────────────────────────────────────────┘
```
❌ Masih ada sidebar, wrapper, judul, tombol

### After (Fixed)
```
┌──────────────────────┐
│   Konten Kartu      │
│                      │
│   (Hanya Kartu)     │
│                      │
└──────────────────────┘
```
✅ Hanya kartu saja - clean seperti invoice PDF

---

## Cara Kerja

1. **Pendaftar** mengakses dari `/pendaftar/kartu-tes-seleksi`:
   - Component: `App\Livewire\Pendaftar\KartuTesSeleksi`
   - `$isPreviewMode` tidak ada atau `false`
   - Tampilan: **Lengkap** dengan wrapper, judul, tombol

2. **Admin** mengakses dari `/admin/preview-kartu-tes/{id}`:
   - Component: `App\Livewire\Admin\PreviewKartuTes`
   - `$isPreviewMode = true`
   - Tampilan: **Clean** hanya kartu saja

**View yang sama**, **behavior yang berbeda** berdasarkan flag.

---

## Testing Checklist

### Test 1: Preview dari Admin
- [ ] Login sebagai admin
- [ ] Buka `/admin/pendaftar/27` (atau ID lain)
- [ ] Klik tombol **Kartu Tes** (biru)
- [ ] Tab baru terbuka
- [ ] **VERIFIKASI**: Tidak ada sidebar
- [ ] **VERIFIKASI**: Tidak ada judul "Kartu Tanda Peserta Tes Seleksi"
- [ ] **VERIFIKASI**: Tidak ada tombol "Cetak Kartu"
- [ ] **VERIFIKASI**: Tidak ada tombol "Bergabung ke Grup"
- [ ] **VERIFIKASI**: Tidak ada wrapper card (shadow/rounded)
- [ ] **VERIFIKASI**: Hanya kartu saja yang terlihat
- [ ] Tekan Ctrl+P → Harusnya langsung siap print

### Test 2: Akses dari Pendaftar (Pastikan Tidak Rusak)
- [ ] Login sebagai pendaftar dengan status "data_disetujui"
- [ ] Buka `/pendaftar/kartu-tes-seleksi`
- [ ] **VERIFIKASI**: Ada wrapper card dengan shadow
- [ ] **VERIFIKASI**: Ada judul "Kartu Tanda Peserta Tes Seleksi"
- [ ] **VERIFIKASI**: Ada tombol "Cetak Kartu"
- [ ] **VERIFIKASI**: Ada tombol "Bergabung ke Grup" (jika diaktifkan)
- [ ] **VERIFIKASI**: Tampilan normal seperti biasa

---

## Catatan Teknis

### Kenapa Menggunakan Flag `$isPreviewMode`?

1. **Single Source of Truth**: View yang sama untuk pendaftar dan admin
2. **Easy Maintenance**: Perubahan konten kartu otomatis berlaku di kedua tempat
3. **Conditional Rendering**: Hanya UI wrapper yang berbeda, konten kartu tetap sama
4. **Clean Code**: Tidak perlu duplikasi view

### Kenapa Tidak Membuat View Terpisah?

- ❌ Duplikasi code
- ❌ Sulit maintenance (update di 2 tempat)
- ❌ Risk inconsistency (lupa update salah satu)

### Kenapa Pakai `isset()` Check?

```php
@if(isset($isPreviewMode) && $isPreviewMode)
```

Untuk backward compatibility:
- Jika dipanggil dari component lama yang tidak punya `$isPreviewMode`, tidak error
- Default behavior: tampilan normal (pendaftar)

---

## Impact

### Positif
- ✅ Preview kartu tes sekarang clean seperti invoice PDF
- ✅ Admin bisa langsung print tanpa elemen UI yang mengganggu
- ✅ Consistent experience (semua preview clean)
- ✅ View pendaftar tetap normal (tidak terpengaruh)

### Negatif
- Tidak ada negative impact
- Backward compatible dengan existing code

---

## Rencana Selanjutnya

1. Tambahkan watermark "PREVIEW" untuk membedakan dengan kartu asli
2. Tambahkan info admin yang membuka preview (audit trail)
3. Pertimbangkan membuat shortcut keyboard untuk print (Ctrl+P)

---

**Status**: ✅ Fixed
**Tested**: ⏳ Pending Production Test
**Ready to Deploy**: ✅ Yes
