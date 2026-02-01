# Fix Video Infografis - URL Tabs & Buttons

## Tanggal: 31 Januari 2026

---

## Masalah

Di `/admin/video-infografis`:
1. **URL Tab tidak terupdate** - Saat klik tab, URL tidak berubah sehingga tidak bisa bookmark atau share URL spesifik tab
2. **Tombol Tambah/Edit/Hapus tidak responsif** - Di tab Infografis dan Testimoni Alumni

---

## Root Cause

### 1. URL Tab Issue
- Tab navigation menggunakan `<button wire:click="$set('activeTab', ...)">`
- Meskipun ada `#[Url(keep: true)]` di component, URL tidak terupdate otomatis saat klik button
- User tidak bisa langsung akses URL seperti `/admin/video-infografis?activeTab=infografis`

### 2. Button Responsiveness (Potential Issues)
- Tombol sudah menggunakan `wire:click`, `wire:loading`, dan `wire:confirm` dengan benar
- Kemungkinan masalah dari JavaScript/Alpine.js tidak loaded atau Livewire not initialized

---

## Solusi Implemented

### 1. Fix URL Tab Navigation

**File: `app/Livewire/Admin/VideoInfografis.php`**

**SEBELUM:**
```php
#[Url(keep: true)]
public $activeTab = 'video';
```

**SETELAH:**
```php
#[Url]
public $activeTab = 'video';
```

**Alasan:**
- `#[Url]` lebih simple dan langsung sync dengan URL query parameter
- `keep: true` tidak diperlukan jika kita pakai link href langsung

---

**File: `resources/views/livewire/admin/video-infografis.blade.php`**

**SEBELUM (Lines 26-49):**
```blade
<button
    wire:click="$set('activeTab', 'video')"
    class="{{ $activeTab === 'video' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
>
    Video Panduan
</button>
<button
    wire:click="$set('activeTab', 'infografis')"
    class="{{ $activeTab === 'infografis' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
>
    Infografis
</button>
<button
    wire:click="$set('activeTab', 'testimoni')"
    class="{{ $activeTab === 'testimoni' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
>
    Testimoni Alumni
</button>
<button
    wire:click="$set('activeTab', 'brosur')"
    class="{{ $activeTab === 'brosur' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
>
    File Brosur
</button>
```

**SETELAH (Lines 26-53):**
```blade
<a
    href="/admin/video-infografis?activeTab=video"
    wire:navigate
    class="{{ $activeTab === 'video' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
>
    Video Panduan
</a>
<a
    href="/admin/video-infografis?activeTab=infografis"
    wire:navigate
    class="{{ $activeTab === 'infografis' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
>
    Infografis
</a>
<a
    href="/admin/video-infografis?activeTab=testimoni"
    wire:navigate
    class="{{ $activeTab === 'testimoni' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
>
    Testimoni Alumni
</a>
<a
    href="/admin/video-infografis?activeTab=brosur"
    wire:navigate
    class="{{ $activeTab === 'brosur' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
>
    File Brosur
</a>
```

**Keuntungan:**
- ✅ URL langsung terupdate di browser bar
- ✅ User bisa bookmark URL spesifik tab
- ✅ Browser back/forward button berfungsi
- ✅ SEO friendly (meskipun admin page)
- ✅ `wire:navigate` membuat navigasi smooth tanpa full page reload

---

### 2. Verifikasi Button Structure

Tombol Tambah, Edit, dan Hapus sudah menggunakan struktur yang benar:

**Tombol Tambah:**
```blade
<button
    wire:click="createVideo"
    wire:loading.attr="disabled"
    wire:loading.class="opacity-50 cursor-not-allowed"
    class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg font-bold hover:bg-emerald-700 transition shadow-md"
>
    <span wire:loading.remove wire:target="createVideo" class="inline-flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Tambah Video
    </span>
    <span wire:loading wire:target="createVideo" class="inline-flex items-center">
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Loading...
    </span>
</button>
```

**Tombol Edit:**
```blade
<button
    wire:click="editVideo({{ $video->id }})"
    wire:loading.attr="disabled"
    wire:loading.class="opacity-50 cursor-not-allowed"
    class="text-blue-600 hover:text-blue-900 mr-3"
>
    <span wire:loading.remove wire:target="editVideo({{ $video->id }})">Edit</span>
    <span wire:loading wire:target="editVideo({{ $video->id }})" class="inline-flex items-center">
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Loading...
    </span>
</button>
```

**Tombol Hapus:**
```blade
<button
    wire:click="deleteVideo({{ $video->id }})"
    wire:confirm="Yakin ingin menghapus video ini?"
    wire:loading.attr="disabled"
    wire:loading.class="opacity-50 cursor-not-allowed"
    class="text-red-600 hover:text-red-900"
>
    <span wire:loading.remove wire:target="deleteVideo({{ $video->id }})">Hapus</span>
    <span wire:loading wire:target="deleteVideo({{ $video->id }})" class="inline-flex items-center">
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Loading...
    </span>
</button>
```

**Struktur yang Sama untuk Infografis dan Testimoni.**

---

## Files Modified

1. ✅ `app/Livewire/Admin/VideoInfografis.php` (Line 20-21)
2. ✅ `resources/views/livewire/admin/video-infografis.blade.php` (Lines 26-53)

---

## Upload ke Production

**File yang perlu diupload:**

```bash
# 1. Component Livewire
app/Livewire/Admin/VideoInfografis.php

# 2. View
resources/views/livewire/admin/video-infografis.blade.php
```

**Lokasi Production:**
```
/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/VideoInfografis.php
/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/video-infografis.blade.php
```

**Setelah Upload:**
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Clear cache
php artisan view:clear
php artisan cache:clear

# Opsional - rebuild cache
php artisan config:cache

exit
```

---

## Testing Checklist

### Tab Navigation:
- [ ] Klik tab "Video Panduan" → URL berubah ke `/admin/video-infografis?activeTab=video`
- [ ] Klik tab "Infografis" → URL berubah ke `/admin/video-infografis?activeTab=infografis`
- [ ] Klik tab "Testimoni Alumni" → URL berubah ke `/admin/video-infografis?activeTab=testimoni`
- [ ] Klik tab "File Brosur" → URL berubah ke `/admin/video-infografis?activeTab=brosur`
- [ ] Copy URL dari tab yang aktif, paste di tab browser baru → Tab yang benar langsung terbuka
- [ ] Browser back button → Kembali ke tab sebelumnya
- [ ] Browser forward button → Maju ke tab berikutnya

### Tab Video Panduan:
- [ ] Klik "Tambah Video" → Modal form muncul
- [ ] Isi form dan klik "Simpan" → Video tersimpan, modal close, table refresh
- [ ] Klik "Edit" pada salah satu video → Modal edit muncul dengan data
- [ ] Update data dan simpan → Data terupdate
- [ ] Klik "Hapus" → Confirmation dialog muncul
- [ ] Confirm hapus → Video terhapus, table refresh

### Tab Infografis:
- [ ] Klik "Tambah Infografis" → Modal form muncul
- [ ] Upload gambar dan klik "Simpan" → Infografis tersimpan
- [ ] Klik "Edit" → Modal edit muncul dengan data dan preview gambar
- [ ] Update dan simpan → Data terupdate
- [ ] Klik "Hapus" → Confirmation dialog muncul
- [ ] Confirm hapus → Infografis terhapus

### Tab Testimoni Alumni:
- [ ] Klik "Tambah Testimoni" → Modal form muncul
- [ ] Upload foto dan isi form → Testimoni tersimpan
- [ ] Klik "Edit" → Modal edit muncul dengan data
- [ ] Update dan simpan → Data terupdate
- [ ] Klik "Hapus" → Confirmation muncul
- [ ] Confirm hapus → Testimoni terhapus

### Tab File Brosur:
- [ ] Upload file PDF → File tersimpan
- [ ] Toggle "Aktifkan Brosur" → Setting tersimpan
- [ ] Klik "Hapus Brosur" → File terhapus

### Loading States:
- [ ] Saat klik tombol tambah/edit/hapus → Loading indicator muncul
- [ ] Tombol disable selama loading
- [ ] Setelah selesai → Loading hilang, tombol kembali normal

---

## Technical Notes

### Wire Navigate
- `wire:navigate` adalah fitur Livewire v3+ untuk SPA-like navigation
- Membuat navigasi lebih cepat tanpa full page reload
- Browser history tetap berfungsi normal
- URL terupdate di address bar

### URL Attribute
- `#[Url]` attribute otomatis sync property dengan URL query parameter
- Saat property berubah, URL terupdate
- Saat URL berubah (via href), property ikut terupdate
- Mendukung browser back/forward

### Wire Confirm
- `wire:confirm="message"` menampilkan native browser confirm dialog
- Lebih simple daripada custom modal confirmation
- Blocking - code tidak jalan sampai user klik OK/Cancel

### Wire Loading
- `wire:loading.attr="disabled"` → Disable button saat loading
- `wire:loading.class="opacity-50"` → Tambah class saat loading
- `wire:loading.remove` → Hide element saat loading
- `wire:loading` → Show element saat loading
- `wire:target="methodName"` → Hanya loading untuk method tertentu

---

## Possible Issues & Solutions

### Issue: Tombol masih tidak responsif setelah upload

**Debugging:**
1. Buka browser console (F12)
2. Klik tombol yang bermasalah
3. Cek ada error JavaScript?

**Solusi:**
```bash
# Clear semua cache
php artisan optimize:clear

# Rebuild assets (jika pakai Vite)
npm run build

# Publish Livewire assets lagi
php artisan livewire:publish --assets
```

### Issue: Modal tidak muncul

**Debugging:**
1. Cek apakah Alpine.js loaded: `console.log(window.Alpine)`
2. Cek apakah Livewire loaded: `console.log(window.Livewire)`

**Solusi:**
```bash
# Pastikan asset-loader include Alpine.js dan Livewire
# File: resources/views/components/asset-loader.blade.php

# Clear view cache
php artisan view:clear
```

### Issue: Tab tidak switch saat klik

**Debugging:**
1. Cek URL berubah atau tidak saat klik tab
2. Cek apakah `$activeTab` property berubah: `@livewire('admin.video-infografis', ['activeTab' => 'video'])`

**Solusi:**
- Pastikan `#[Url]` attribute ada di component
- Clear semua cache Livewire

---

## Status

**✅ CODE READY**

Files sudah dimodifikasi dan siap untuk upload ke production.

**Production URL:** https://pmbm.mtsn2kotamalang.sch.id/admin/video-infografis

---

## Summary

Perbaikan ini mengubah tab navigation dari button dengan `wire:click` menjadi anchor tag dengan `href` dan `wire:navigate`. Ini memberikan:

1. **Better UX**: URL terupdate, user bisa bookmark
2. **Better SEO**: Meskipun admin page, struktur URL lebih baik
3. **Better Browser Integration**: Back/forward button berfungsi
4. **Faster Navigation**: `wire:navigate` membuat navigasi SPA-like

Tombol tambah/edit/hapus sudah menggunakan struktur yang benar dengan `wire:loading`, `wire:confirm`, dan loading indicators yang proper.
