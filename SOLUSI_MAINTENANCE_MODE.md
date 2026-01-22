# Solusi Maintenance Mode - Halaman Error 503

## Masalah

Ketika aplikasi dalam mode maintenance (`php artisan down`), halaman error 503 tidak tampil dengan benar dan menampilkan error di console:

```
Failed to load resource: the server responded with a status of 503 ()
Livewire: The published Livewire assets are out of date
Detected multiple instances of Alpine running
```

## Penyebab Masalah

Ketika aplikasi dalam **maintenance mode**, Laravel mengembalikan **HTTP 503** untuk **SEMUA REQUEST**, termasuk:
- Request ke halaman utama → 503
- Request ke CSS (`/css/app.css`) → 503  ← **Ini masalahnya!**
- Request ke JS (`/js/app.js`) → 503    ← **Ini masalahnya!**
- Request ke Livewire → 503             ← **Ini masalahnya!**

Jadi halaman 503.blade.php mencoba load CSS/JS lokal, tapi semua request juga return 503, sehingga halaman tidak bisa render dengan baik.

## Solusi

**Gunakan CDN untuk semua asset di halaman error**, JANGAN gunakan asset lokal.

### File yang Diperbaiki:
```
resources/views/errors/503.blade.php
```

### Perubahan:
**SEBELUM:**
```blade
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>503 - Maintenance Mode</title>
    <!-- Ini akan GAGAL karena asset lokal juga return 503 -->
    <x-asset-loader-simple />
    @livewireStyles
</head>
```

**SESUDAH:**
```blade
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>503 - Maintenance Mode</title>
    {{-- Menggunakan Tailwind CDN karena asset lokal ter-block saat maintenance --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
```

**Dan hapus Livewire di bagian bawah:**
```blade
<!-- DIHAPUS:
@livewireScripts
<script src="{{ asset('js/livewire.js') }}" defer></script>
-->
```

## Penerapan Solusi

### 1. File sudah diperbaiki di:
```
C:\ppdbmtsn2km-laravel\resources\views\errors\503.blade.php
```

### 2. Prinsip yang sama untuk semua error pages:

Untuk halaman error lainnya (403, 404, 419, 500), gunakan pendekatan yang sama:

**JANGAN:**
```blade
<x-asset-loader />  <!-- Asset lokal bisa gagal -->
@vite(['resources/css/app.css'])  <!-- Vite bisa gagal -->
```

**GUNAKAN:**
```blade
<script src="https://cdn.tailwindcss.com"></script>  <!-- CDN selalu work -->
```

## Testing

### 1. Clear cache:
```bash
php artisan view:clear
php artisan cache:clear
```

### 2. Aktifkan maintenance mode:
```bash
php artisan down --secret="test123"
```

### 3. Buka browser (atau incognito mode):
```
http://localhost atau http://yourdomain.com
```

### 4. Yang harus terlihat:
✅ Halaman maintenance tampil sempurna
✅ Styling Tailwind bekerja dengan baik
✅ Tidak ada error di console
✅ Tidak ada "Failed to load resource: 503"

### 5. Bypass untuk testing sebagai admin:
```
http://localhost/test123
```

### 6. Matikan maintenance mode:
```bash
php artisan up
```

## Upload ke Production

### File yang perlu diupload:
```
resources/views/errors/503.blade.php
```

### Langkah setelah upload:

1. **SSH ke server production**

2. **Clear cache:**
```bash
cd /path/to/your/laravel
php artisan view:clear
php artisan cache:clear
```

3. **Test maintenance mode:**
```bash
php artisan down --secret="admin2026"
```

4. **Akses via browser** untuk memastikan halaman tampil OK

5. **Bypass dengan secret:**
```
https://ppdb.mtsn2kotamalang.sch.id/admin2026
```

6. **Matikan setelah testing:**
```bash
php artisan up
```

## Perbedaan Pendekatan

### Error Pages (503, 404, 500, dll)
- ❌ JANGAN gunakan asset lokal
- ✅ GUNAKAN CDN (Tailwind, Alpine, dll)
- ❌ JANGAN gunakan Livewire
- ✅ GUNAKAN inline styles jika perlu
- **Alasan:** Request ke asset bisa gagal/ter-block

### Halaman Normal (Dashboard, Form, dll)
- ✅ BOLEH gunakan asset lokal
- ✅ BOLEH gunakan Livewire
- ✅ BOLEH gunakan Vite
- **Alasan:** Halaman normal tidak ter-block

## Catatan Penting

1. **Halaman error harus STANDALONE** - tidak bergantung pada resource yang bisa gagal
2. **Gunakan CDN** - karena CDN eksternal tidak ter-block oleh maintenance mode
3. **Tampilan tetap sama** - hanya cara load asset yang berbeda (CDN vs lokal)
4. **Tidak perlu compile** - CDN langsung bisa digunakan, tidak perlu `npm run build`

## FAQ

### Q: Apakah halaman error lain (404, 500) juga perlu diperbaiki?
**A:** Ya, semua halaman error sebaiknya menggunakan CDN, bukan asset lokal.

### Q: Apakah bisa menggunakan asset lokal untuk error pages?
**A:** Bisa, TAPI harus dijamin file CSS/JS-nya tidak ter-block. Untuk maintenance mode (503), asset lokal pasti ter-block.

### Q: Bagaimana jika ingin custom styling?
**A:** Bisa menggunakan inline `<style>` atau Tailwind CDN dengan konfigurasi inline.

### Q: Apakah perlu rebuild asset?
**A:** TIDAK. Karena menggunakan CDN, tidak perlu compile apapun.

### Q: Error "Multiple Alpine instances"?
**A:** Sudah diperbaiki dengan tidak load Alpine/Livewire di halaman error.

---

**Dibuat:** 20 Januari 2026
**Status:** ✅ Diperbaiki dan Tested
**Versi:** 1.0
