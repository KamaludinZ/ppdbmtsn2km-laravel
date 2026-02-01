# Session Fixes - 31 Januari 2026

## Ringkasan Perbaikan

Sesi ini melanjutkan dari session sebelumnya dan menyelesaikan beberapa issue production yang tersisa.

---

## 1. Fix Login Panitia Psikologi - 404 Error

**Masalah:**
- Login berhasil tapi redirect ke dashboard menghasilkan 404
- URL: `/panitia-psikologi/dashboard`
- Terjadi untuk semua role panitia

**Root Cause:**
- Bootstrap cache (`bootstrap/cache/routes-v7.php`) masih menyimpan konfigurasi lama
- File `routes/web.php` sudah benar, tapi cache belum di-refresh

**Solusi:**
```bash
rm -f bootstrap/cache/config.php
rm -f bootstrap/cache/routes-v7.php
rm -f bootstrap/cache/events.php
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
```

**Files Modified:**
- Tidak ada perubahan code, hanya clear cache

**Status:** ✅ **RESOLVED**

---

## 2. Fix Toggle Survey - Tampilan Tidak Bagus

**Masalah:**
- Toggle "Aktifkan Survey" di `/panitia-psikologi/tes-psikologi?mainTab=survey` tidak tampil dengan benar
- CSS modern dengan `peer-checked` tidak ter-compile di production

**Root Cause:**
- Toggle menggunakan Tailwind CSS class modern (`peer`, `peer-checked`, `peer-focus`)
- Class tersebut tidak ada di compiled CSS production

**Solusi:**
1. Buat custom CSS toggle component: `public/css/toggle-switch.css`
2. Update `asset-loader.blade.php` untuk load CSS toggle
3. Replace toggle HTML di 2 file:
   - `resources/views/livewire/admin/partials/tes-psikologi-survey.blade.php`
   - `resources/views/livewire/panitia/partials/tes-psikologi-survey.blade.php`

**HTML SEBELUM:**
```html
<label class="relative inline-flex items-center cursor-pointer">
    <input type="checkbox" wire:model="survey_aktif" class="sr-only peer">
    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full..."></div>
</label>
```

**HTML SETELAH:**
```html
<label class="toggle-switch">
    <input type="checkbox" wire:model="survey_aktif">
    <span class="toggle-slider"></span>
</label>
```

**Files Modified:**
- ✅ `public/css/toggle-switch.css` (NEW)
- ✅ `resources/views/components/asset-loader.blade.php`
- ✅ `resources/views/livewire/admin/partials/tes-psikologi-survey.blade.php`
- ✅ `resources/views/livewire/panitia/partials/tes-psikologi-survey.blade.php`

**Status:** ✅ **RESOLVED**

---

## 3. Fix Tombol Edit & Hapus di /admin/jadwal

**Masalah:**
- Tombol Edit dan Hapus tidak responsif (tidak bisa diklik)

**Root Cause:**
- Duplikasi kode `@if(Auth::user()->role === 'admin')` di line 171-172
- Double `@endif` menyebabkan HTML tidak valid

**Kode Bermasalah (Line 171-178):**
```blade
@if(Auth::user()->role === 'admin')
    @if(Auth::user()->role === 'admin')  <!-- DUPLIKAT! -->
        <button wire:click="delete({{ $item->id }})">...</button>
    @endif
@endif  <!-- DOUBLE @endif -->
```

**Solusi:**
```blade
@if(Auth::user()->role === 'admin')
    <button wire:click="delete({{ $item->id }})" wire:confirm="Yakin ingin menghapus jadwal ini?" class="text-red-600 hover:text-red-900" title="Hapus">
        <svg>...</svg>
    </button>
@endif
```

**Files Modified:**
- ✅ `resources/views/livewire/admin/jadwal.blade.php` (Line 165-178)

**Status:** ✅ **RESOLVED**

---

## 4. Fix Pagination - Filter Tampil Per Halaman

**Masalah:**
- Saat mengubah dropdown "Tampilkan per halaman" menjadi 100, data tidak tampil semua
- Terjadi di 4 halaman:
  - `/admin/jadwal`
  - `/panitia-pendaftaran/jadwal` (dan role panitia lainnya)
  - `/admin/pendaftar`
  - `/panitia-pendaftaran/pendaftar` (dan role panitia lainnya)

**Root Cause:**
- Saat `perPage` berubah, pagination masih di halaman lama (misal halaman 5)
- Dengan perPage=100, mungkin cuma ada 2 halaman total, jadi halaman 5 kosong
- Tidak ada method `updatingPerPage()` untuk reset ke halaman 1

**Solusi:**
Tambahkan method ini ke semua component:

```php
public function updatingPerPage()
{
    $this->resetPage();
}
```

**Files Modified:**
- ✅ `app/Livewire/Admin/Jadwal.php` (Line 64-67)
- ✅ `app/Livewire/Panitia/Jadwal.php` (Line 72-75)
- ✅ `app/Livewire/Admin/Pendaftar.php` (Line 130-133)
- ✅ `app/Livewire/Panitia/Pendaftar.php` (Line 126-129)

**Penjelasan:**
- Method `updatingPerPage()` dipanggil Livewire otomatis saat property `perPage` berubah
- `$this->resetPage()` akan reset pagination kembali ke halaman 1
- Sekarang saat user ubah perPage ke 100, otomatis kembali ke halaman 1 dan tampilkan 100 item

**Status:** ✅ **RESOLVED**

---

## Summary Upload Production

Total **12 files** yang diupload ke production:

### CSS & Components:
1. `public/css/toggle-switch.css`
2. `resources/views/components/asset-loader.blade.php`

### Views - Admin:
3. `resources/views/livewire/admin/partials/tes-psikologi-survey.blade.php`
4. `resources/views/livewire/admin/jadwal.blade.php`

### Views - Panitia:
5. `resources/views/livewire/panitia/partials/tes-psikologi-survey.blade.php`

### Livewire Components - Admin:
6. `app/Livewire/Admin/Jadwal.php`
7. `app/Livewire/Admin/Pendaftar.php`

### Livewire Components - Panitia:
8. `app/Livewire/Panitia/Dashboard.php`
9. `app/Livewire/Panitia/Jadwal.php`
10. `app/Livewire/Panitia/Pendaftar.php`
11. `app/Livewire/Panitia/TesPsikologi.php`
12. `app/Livewire/Panitia/DaftarUlang.php`

*(Plus 9 file Panitia components lainnya yang sudah diupload sebelumnya)*

---

## Commands untuk Clear Cache

Setelah upload semua file, selalu jalankan:

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Clear all caches
php artisan optimize:clear

# Atau manual:
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Rebuild cache (opsional)
php artisan config:cache
php artisan route:cache

exit
```

---

## Testing Checklist

- [x] Login panitia psikologi → Dashboard tampil (bukan 404)
- [x] Login panitia pendaftaran → Dashboard tampil
- [x] Login panitia daftar ulang → Dashboard tampil
- [x] Login panitia seragam → Dashboard tampil
- [x] Toggle survey di /panitia-psikologi/tes-psikologi?mainTab=survey → Tampil bagus dengan animasi smooth
- [x] Toggle survey di /admin/tes-psikologi?mainTab=survey → Tampil bagus
- [x] Tombol Edit di /admin/jadwal → Bisa diklik, modal muncul
- [x] Tombol Hapus di /admin/jadwal → Bisa diklik, confirm dialog muncul
- [x] Filter per halaman = 100 di /admin/jadwal → Tampil 100 item
- [x] Filter per halaman = 100 di /admin/pendaftar → Tampil 100 item
- [x] Filter per halaman = 100 di /panitia-pendaftaran/pendaftar → Tampil 100 item

---

## Lessons Learned

### 1. Bootstrap Cache Issue
- **Problem:** Routes dan config cache bisa menyebabkan aplikasi tidak reflect perubahan terbaru
- **Solution:** Selalu clear bootstrap cache setelah perubahan routing atau config besar
- **Command:** `rm -f bootstrap/cache/*.php && php artisan optimize:clear`

### 2. Tailwind CSS Modern Classes
- **Problem:** Class modern seperti `peer-*` mungkin tidak ter-compile di production CSS
- **Solution:** Buat custom CSS component yang standalone dan tidak depend on Tailwind compilation
- **Best Practice:** Gunakan vanilla CSS untuk component critical yang harus selalu tampil bagus

### 3. Livewire Property Watchers
- **Problem:** Perubahan property tidak otomatis reset state lain (seperti pagination)
- **Solution:** Gunakan `updating{PropertyName}()` hook untuk side effects
- **Pattern:**
  ```php
  public function updatingPerPage() {
      $this->resetPage(); // Reset pagination saat perPage berubah
  }
  ```

### 4. Blade Syntax Errors
- **Problem:** Duplikasi `@if` atau missing `@endif` sulit di-detect karena tidak ada syntax highlighting di production
- **Solution:** Selalu test perubahan view di local dulu, gunakan IDE dengan Blade syntax checking

---

## Waktu Pengerjaan

- **Total Session Time:** ~2 jam
- **Issue 1 (404 Login):** 45 menit (debugging, finding root cause di cache)
- **Issue 2 (Toggle):** 30 menit (create CSS component, update views)
- **Issue 3 (Button):** 15 menit (fix duplikasi code)
- **Issue 4 (Pagination):** 30 menit (fix 4 files + testing)

---

## Status Akhir

**✅ SEMUA ISSUE RESOLVED**

Aplikasi production sekarang:
- Login semua role panitia berfungsi normal
- Toggle survey tampil indah dengan animasi
- Tombol edit/hapus responsif
- Pagination per halaman berfungsi sempurna hingga 100 item

**Production URL:** https://pmbm.mtsn2kotamalang.sch.id
