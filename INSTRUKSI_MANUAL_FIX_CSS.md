# 🔧 INSTRUKSI MANUAL FIX CSS PRODUCTION

## ⚠️ MASALAH YANG TERJADI
File `public/css/app.css` Anda berisi kode Vite:
```css
@import 'tailwindcss';
```
Ini TIDAK BEKERJA di production karena butuh Vite untuk memproses.

## ✅ SOLUSI MANUAL (Via cPanel File Manager)

### LANGKAH 1: Login ke cPanel
1. Buka cPanel hosting Anda
2. Masuk ke **File Manager**
3. Navigate ke folder: `/home/[username]/pmbm.mtsn2kotamalang.sch.id/public/css/`

### LANGKAH 2: Backup File Lama
1. Klik kanan pada file `app.css`
2. Pilih **Rename**
3. Rename jadi: `app-backup-vite.css`
4. Klik **Rename File**

### LANGKAH 3: Buat File CSS Baru
1. Klik tombol **+ File** (New File)
2. Nama file: `app.css`
3. Klik **Create New File**

### LANGKAH 4: Edit File CSS Baru
1. Klik kanan pada `app.css` yang baru dibuat
2. Pilih **Edit**
3. **HAPUS SEMUA** isi yang ada
4. **COPY PASTE SEMUA KODE INI:**

```css
/* PPDB MTsN 2 Kota Malang - Production CSS */
@import url("https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css");
@import url("https://fonts.bunny.net/css?family=figtree:300,400,500,600,700&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap");

:root {
    --primary: #10b981;
    --secondary: #3b82f6;
    --danger: #ef4444;
    --warning: #f59e0b;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Figtree', 'Inter', system-ui, -apple-system, sans-serif;
    background-color: #f9fafb;
    color: #111827;
    line-height: 1.5;
}

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

.hidden { display: none; }
.block { display: block; }
.flex { display: flex; }
.grid { display: grid; }

.btn {
    display: inline-flex;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-weight: 500;
    cursor: pointer;
}

.btn-primary {
    background-color: #10b981;
    color: white;
}

.btn-primary:hover {
    background-color: #059669;
}
```

5. Klik **Save Changes**

### LANGKAH 5: Buat File Fallback
1. Klik tombol **+ File** lagi
2. Nama file: `tailwindcss` (TANPA EXTENSION .css)
3. Klik **Create New File**
4. Edit dan paste kode yang SAMA seperti di atas
5. Klik **Save Changes**

### LANGKAH 6: Clear Cache Laravel
Via Terminal cPanel (jika ada):
```bash
cd /home/[username]/pmbm.mtsn2kotamalang.sch.id
php artisan view:clear
php artisan cache:clear
```

ATAU Manual:
1. Navigate ke `/storage/framework/views/`
2. Delete SEMUA file `.php` (JANGAN hapus `.gitignore`)

### LANGKAH 7: Clear Cache Browser
1. Buka Chrome
2. Tekan: **Ctrl + Shift + Delete**
3. Pilih **Cached images and files**
4. Klik **Clear data**
5. ATAU buka di **Incognito Mode** (Ctrl + Shift + N)

## 📱 TEST HASIL

### Test 1: Cek File CSS
Buka browser, akses:
```
https://pmbm.mtsn2kotamalang.sch.id/css/app.css
```
✅ Harus muncul kode CSS, BUKAN error 404
✅ Harus ada text: `@import url("https://cdn.jsdelivr.net/npm/tailwindcss`

### Test 2: Cek Website
1. Buka: `https://pmbm.mtsn2kotamalang.sch.id`
2. Tekan F12 untuk buka Console
3. ✅ Tidak boleh ada error "Refused to apply style from '/css/tailwindcss'"
4. ✅ Tampilan harus normal dengan styling

## 🔄 UNTUK LOCAL DEVELOPMENT

**JANGAN UBAH APAPUN DI LOCAL!**

Di local, tetap pakai:
- File `resources/css/app.css` dengan `@import 'tailwindcss';`
- Command `npm run dev` untuk development
- Vite tetap jalan normal

## 📁 STRUKTUR FILE SETELAH FIX

```
Production (cPanel):
/public/css/
├── app.css              ← CSS dengan Tailwind CDN (yang baru)
├── app-backup-vite.css  ← Backup file lama
└── tailwindcss          ← Fallback file

Local (Jangan diubah):
/resources/css/
└── app.css              ← Tetap pakai @import 'tailwindcss'
```

## ❓ JIKA MASIH ERROR

### Error: CSS tidak load
1. Check permission file:
   - Klik kanan file `app.css`
   - Pilih **Change Permissions**
   - Set ke: **644**

### Error: Tampilan masih berantakan
1. Pastikan sudah clear cache browser
2. Test di browser lain atau device lain
3. Tunggu 5 menit (CDN cache)

### Alternative: Test dengan file HTML
1. Buat file: `/public/test.html`
2. Isi:
```html
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold text-green-500">CSS Test OK!</h1>
        <button class="btn btn-primary mt-4">Test Button</button>
    </div>
</body>
</html>
```
3. Akses: `https://pmbm.mtsn2kotamalang.sch.id/test.html`

## ✔️ CHECKLIST FINAL

- [ ] File `app.css` sudah diganti dengan kode baru
- [ ] File `tailwindcss` sudah dibuat
- [ ] Cache Laravel sudah clear
- [ ] Cache browser sudah clear
- [ ] Test akses `/css/app.css` berhasil
- [ ] Console tidak ada error CSS
- [ ] Tampilan website normal

---

**PENTING:** Ini HANYA untuk PRODUCTION. Jangan ubah apapun di LOCAL development!