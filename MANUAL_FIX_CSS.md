# FIX MANUAL TAMPILAN BERANTAKAN

## LANGKAH 1: Buat File CSS di Production

### Via cPanel File Manager:

1. **Navigate ke:** `/public/css/`

2. **Buat file baru:** `app.css`

3. **Copy paste kode CSS ini:**

```css
/* PPDB MTsN 2 Kota Malang - Production CSS */

/* Tailwind CSS dari CDN */
@import url("https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css");

/* Font */
@import url("https://fonts.bunny.net/css?family=figtree:300,400,500,600,700&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap");

:root {
    --primary: #10b981;
    --secondary: #3b82f6;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Figtree', 'Inter', system-ui, sans-serif;
    background-color: #f9fafb;
}

/* Livewire loading */
.livewire-loading {
    opacity: 0.5;
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

/* Buttons */
.btn {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-weight: 500;
    transition: all 0.2s;
    cursor: pointer;
}

.btn-primary {
    background-color: #10b981;
    color: white;
}

.btn-primary:hover {
    background-color: #059669;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #555;
}
```

4. **Save file**

5. **Buat file tambahan:** `tailwindcss` (tanpa extension)

6. **Copy paste CSS yang sama ke file `tailwindcss`**

---

## LANGKAH 2: Fix JavaScript

1. **Navigate ke:** `/public/js/`

2. **Edit file:** `app.js`

3. **Tambahkan di bagian bawah file:**

```javascript
// Countdown function untuk Alpine.js
window.countdown = function(targetDate) {
    return {
        days: '00',
        hours: '00',
        minutes: '00',
        seconds: '00',
        interval: null,

        init() {
            this.updateCountdown();
            this.interval = setInterval(() => {
                this.updateCountdown();
            }, 1000);
        },

        updateCountdown() {
            const target = new Date(targetDate).getTime();
            const now = new Date().getTime();
            const distance = target - now;

            if (distance < 0) {
                this.days = '00';
                this.hours = '00';
                this.minutes = '00';
                this.seconds = '00';
                if (this.interval) {
                    clearInterval(this.interval);
                }
                return;
            }

            this.days = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');
            this.hours = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
            this.minutes = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
            this.seconds = String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0');
        }
    };
};

// Load Alpine.js jika belum ada
if (typeof Alpine === 'undefined') {
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js';
    script.defer = true;
    document.head.appendChild(script);
}
```

4. **Save file**

---

## LANGKAH 3: Clear Cache di Server

### Via Terminal cPanel (jika ada):

```bash
cd /home/username/pmbm.mtsn2kotamalang.sch.id
php artisan view:clear
php artisan cache:clear
```

### Via File Manager (jika tidak ada terminal):

1. **Navigate ke:** `/storage/framework/views/`
2. **Delete semua file PHP** (jangan hapus `.gitignore`)

3. **Navigate ke:** `/storage/framework/cache/`
4. **Delete semua file** (jangan hapus `.gitignore`)

---

## LANGKAH 4: Test dengan File HTML

1. **Buat file:** `/public/test-css.html`

2. **Isi dengan:**

```html
<!DOCTYPE html>
<html>
<head>
    <title>CSS Test</title>
    <link rel="stylesheet" href="/css/app.css">
    <script src="/js/app.js" defer></script>
</head>
<body>
    <div class="min-h-screen bg-gray-50">
        <div class="container mx-auto py-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">
                    CSS Test Page
                </h1>
                <p class="text-gray-600">
                    Jika tampilan ini styled dengan benar, CSS sudah OK!
                </p>
                <button class="mt-4 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Test Button
                </button>
            </div>
        </div>
    </div>
</body>
</html>
```

3. **Test akses:** `https://pmbm.mtsn2kotamalang.sch.id/test-css.html`

---

## LANGKAH 5: Clear Browser Cache

1. **Chrome:** Ctrl + Shift + Delete → Clear browsing data
2. **Atau:** Buka di Incognito mode
3. **Atau:** Ctrl + F5 (hard refresh)

---

## STRUKTUR FILE YANG HARUS ADA:

```
/public/
├── css/
│   ├── app.css          ← File CSS utama
│   └── tailwindcss      ← Fallback (copy dari app.css)
├── js/
│   ├── app.js           ← JavaScript dengan countdown
│   └── livewire.js      ← Livewire (jika ada)
└── test-css.html        ← File test
```

---

## JIKA MASIH ERROR:

### Check Permission:
```bash
chmod 755 public/css
chmod 644 public/css/app.css
chmod 644 public/css/tailwindcss
chmod 755 public/js
chmod 644 public/js/app.js
```

### Direct Test CSS:
- Buka: `https://pmbm.mtsn2kotamalang.sch.id/css/app.css`
- Harus muncul kode CSS, bukan error 404

### Alternative: Inline CSS di Template

Jika CSS external tidak bisa, edit file `/resources/views/components/layouts/app.blade.php`:

Replace:
```html
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
```

Dengan:
```html
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
<style>
    /* Custom styles */
    body { font-family: 'Figtree', sans-serif; }
    .btn { @apply px-4 py-2 rounded; }
</style>
```

---

## PENTING:

- File `app.css` HARUS ada di `/public/css/`
- File `tailwindcss` sebagai fallback
- JavaScript countdown HARUS ada di `app.js`
- Clear cache browser setelah update!