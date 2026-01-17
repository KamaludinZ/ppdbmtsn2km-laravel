# FIX MANUAL CSS PRODUCTION (Tanpa Merusak Vite Local)

## MASALAH YANG DITEMUKAN

File `public/css/app.css` mengandung:
```css
@import 'tailwindcss';
```

Ini adalah sintaks Vite/development yang **TIDAK BEKERJA di production**. Browser mencoba mencari `/css/tailwindcss` dan gagal.

## SOLUSI MANUAL

### DI PRODUCTION (cPanel File Manager):

#### LANGKAH 1: Backup CSS Original

1. Navigate ke `/public/css/`
2. Rename `app.css` → `app-vite.css` (backup untuk development)

#### LANGKAH 2: Buat CSS Production Baru

1. Buat file baru: `app.css`
2. **Copy paste SEMUA kode ini:**

```css
/* PPDB MTsN 2 Kota Malang - Production CSS */

/* Tailwind CSS dari CDN */
@import url("https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css");

/* Custom Fonts */
@import url("https://fonts.bunny.net/css?family=figtree:300,400,500,600,700&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap");

/* Root Variables */
:root {
    --primary: #10b981;
    --secondary: #3b82f6;
    --danger: #ef4444;
    --warning: #f59e0b;
}

/* Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Base Styles */
body {
    font-family: 'Figtree', 'Inter', system-ui, -apple-system, sans-serif;
    background-color: #f9fafb;
    color: #111827;
}

/* Livewire Loading */
.livewire-loading {
    opacity: 0.5;
    pointer-events: none;
}

[wire\:loading] {
    display: inline-block;
}

[wire\:loading].hidden {
    display: none !important;
}

/* Container */
.container {
    width: 100%;
    margin: 0 auto;
    padding: 0 1rem;
}

@media (min-width: 640px) {
    .container { max-width: 640px; }
}

@media (min-width: 768px) {
    .container { max-width: 768px; }
}

@media (min-width: 1024px) {
    .container { max-width: 1024px; }
}

@media (min-width: 1280px) {
    .container { max-width: 1280px; }
}

/* Animations */
@keyframes spin {
    to { transform: rotate(360deg); }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes pulse {
    50% { opacity: .5; }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f3f4f6;
}

::-webkit-scrollbar-thumb {
    background: #9ca3af;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #6b7280;
}
```

3. **Save file**

#### LANGKAH 3: Clear Cache

Via Terminal cPanel (jika ada):
```bash
cd /home/username/pmbm.mtsn2kotamalang.sch.id
php artisan view:clear
php artisan cache:clear
```

ATAU Manual via File Manager:
1. Navigate ke `/storage/framework/views/`
2. Delete semua file `.php` (jangan hapus `.gitignore`)

#### LANGKAH 4: Clear Browser Cache

1. **Chrome:** Ctrl + Shift + F5
2. **Atau:** Buka di Incognito Mode
3. **Atau:** Settings → Privacy → Clear browsing data

---

## DI LOCAL (Tetap Pakai Vite)

### JANGAN UBAH APAPUN!

Local development tetap menggunakan:
- Vite untuk hot reload
- File `resources/css/app.css` dengan Tailwind v4
- Command `npm run dev` untuk development

File struktur local:
```
resources/
├── css/
│   └── app.css          ← Source CSS dengan @import 'tailwindcss'
├── js/
│   └── app.js           ← Source JS
```

Command development:
```bash
npm run dev              # Hot reload development
npm run build            # Build untuk production (jangan upload hasilnya)
```

---

## STRUKTUR FILE PRODUCTION vs LOCAL

### PRODUCTION:
```
public/
├── css/
│   ├── app.css          ← CSS production dengan Tailwind CDN
│   └── app-vite.css     ← Backup (tidak dipakai)
├── js/
│   └── app.js           ← JavaScript production
```

### LOCAL:
```
resources/
├── css/
│   └── app.css          ← Source CSS untuk Vite
├── js/
│   └── app.js           ← Source JS untuk Vite

public/build/            ← Generated oleh Vite (JANGAN upload)
```

---

## VERIFIKASI

### Test CSS di Production:

1. Buka: `https://pmbm.mtsn2kotamalang.sch.id/css/app.css`
   - Harus muncul CSS code, bukan error 404
   - Harus ada `@import url("https://cdn.jsdelivr.net/npm/tailwindcss...`

2. Buka website dalam Incognito mode
   - Tampilan harus normal
   - Tidak ada error di console

### Test Vite di Local:

1. Jalankan `npm run dev`
2. Buka `http://localhost:8000`
3. Vite hot reload harus tetap bekerja

---

## TROUBLESHOOTING

### Jika CSS masih tidak load:

1. **Check permission:**
   ```bash
   chmod 644 public/css/app.css
   ```

2. **Check .htaccess di `/public/`:**
   Pastikan ada dan tidak memblokir CSS files

3. **Alternative - Inline Tailwind CDN:**

   Edit `/resources/views/components/layouts/app.blade.php`:

   Replace:
   ```html
   <link rel="stylesheet" href="{{ asset('css/app.css') }}">
   ```

   Dengan:
   ```html
   @if(app()->environment('production'))
       <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
       <style>
           body { font-family: 'Figtree', 'Inter', sans-serif; }
       </style>
   @else
       @vite(['resources/css/app.css', 'resources/js/app.js'])
   @endif
   ```

---

## PENTING!

1. **JANGAN upload `public/build/` folder** - Ini untuk Vite local saja
2. **JANGAN ubah `resources/css/app.css`** - Ini untuk development
3. **Production pakai CSS static**, local pakai Vite
4. **Clear browser cache** setelah update CSS

---

## FILE YANG HARUS ADA DI PRODUCTION:

- [x] `/public/css/app.css` - CSS dengan Tailwind CDN (BUKAN @import 'tailwindcss')
- [x] `/public/js/app.js` - JavaScript dengan countdown function
- [x] Clear cache browser dan server

Setelah langkah ini, tampilan akan normal di production tanpa merusak Vite di local!