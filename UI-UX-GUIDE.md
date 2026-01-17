# Panduan UI/UX SPMBM MTsN 2 Kota Malang

## 📋 Daftar Isi
- [Overview](#overview)
- [Struktur Layout](#struktur-layout)
- [Halaman Public](#halaman-public)
- [Dashboard Admin](#dashboard-admin)
- [Dashboard Pendaftar](#dashboard-pendaftar)
- [Dashboard Panitia Pendaftaran](#dashboard-panitia-pendaftaran)
- [Dashboard Panitia Daftar Ulang](#dashboard-panitia-daftar-ulang)
- [Komponen UI](#komponen-ui)
- [Color Scheme](#color-scheme)
- [Typography](#typography)
- [Icons](#icons)

## Overview

Sistem SPMBM MTsN 2 Kota Malang memiliki 5 jenis tampilan utama:

1. **Public Layout** - Untuk pengunjung umum
2. **Admin Dashboard** - Untuk administrator sistem
3. **Pendaftar Dashboard** - Untuk calon siswa yang mendaftar
4. **Panitia Pendaftaran Dashboard** - Untuk panitia verifikasi berkas
5. **Panitia Daftar Ulang Dashboard** - Untuk panitia daftar ulang

## Struktur Layout

### File Struktur
```
resources/
├── views/
│   ├── components/
│   │   └── layouts/
│   │       ├── app.blade.php           # Generic layout (legacy)
│   │       ├── public.blade.php        # Public layout
│   │       └── dashboard.blade.php     # Unified dashboard layout
│   ├── public/
│   │   └── home.blade.php              # Homepage
│   └── dashboard/
│       ├── admin/
│       │   └── index.blade.php         # Admin dashboard
│       ├── pendaftar/
│       │   └── index.blade.php         # Pendaftar dashboard
│       ├── panitia-pendaftaran/
│       │   └── index.blade.php         # Panitia pendaftaran dashboard
│       └── panitia-daftarulang/
│           └── index.blade.php         # Panitia daftar ulang dashboard
```

## Halaman Public

### Layout: `components/layouts/public.blade.php`

**Features:**
- Sticky navigation dengan menu utama
- Hero section dengan CTA
- Footer lengkap dengan informasi kontak
- Responsive design
- Social media links

**Navigation Menu:**
- Beranda
- Tentang
- Informasi
- Jalur Pendaftaran
- Pengumuman
- Login / Daftar Sekarang (CTA)

**Cara Penggunaan:**
```blade
<x-layouts.public>
    <h1>Konten Halaman</h1>
</x-layouts.public>
```

### Homepage (`public/home.blade.php`)

**Sections:**
1. **Hero Section**
   - Gradient background (emerald)
   - Informasi tahun ajaran
   - CTA buttons (Daftar Sekarang, Info Lengkap)
   - Card informasi penting dengan timeline

2. **Jalur Pendaftaran**
   - 3 cards untuk jalur: Prestasi, Reguler, Tahfidz
   - Setiap card berisi:
     - Icon distintif
     - Deskripsi jalur
     - Persyaratan utama (checklist)
     - CTA "Info Lengkap"

3. **Statistics Section**
   - 4 statistik utama (Siswa, Lulus UN, Pengajar, Tahun)
   - Background abu-abu muda

4. **CTA Section**
   - Background emerald
   - Final call-to-action untuk pendaftaran

**Route:**
```php
Route::get('/', function () {
    return view('public.home');
});
```

**Preview:** http://localhost:8000/

## Dashboard Admin

### Layout: `components/layouts/dashboard.blade.php` (role="admin")

**Features:**
- Sidebar navigation dengan icon
- Top bar dengan page title
- Notification badge
- User profile dropdown
- Logout button

**Menu Items:**
- Dashboard
- Data Pendaftar
- Manajemen Panitia
- Jalur Pendaftaran
- Pengumuman
- Laporan
- Pengaturan

**Dashboard Content (`dashboard/admin/index.blade.php`):**
1. **Stats Cards (4):**
   - Total Pendaftar
   - Menunggu Verifikasi
   - Diterima
   - Panitia Aktif

2. **Pendaftar Terbaru Table:**
   - Avatar dengan inisial
   - Nama & NISN
   - Jalur pendaftaran (badge)
   - Status verifikasi
   - Tanggal daftar

3. **Sidebar:**
   - Quick Actions (3 tombol)
   - Statistik Jalur (progress bars)

**Route:**
```php
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.admin.index');
    });
});
```

**Preview:** http://localhost:8000/admin/dashboard

## Dashboard Pendaftar

### Layout: `components/layouts/dashboard.blade.php` (role="pendaftar")

**Menu Items:**
- Dashboard
- Formulir Pendaftaran
- Upload Dokumen
- Status Pendaftaran
- Pembayaran
- Kartu Ujian

**Dashboard Content (`dashboard/pendaftar/index.blade.php`):**
1. **Status Card:**
   - Nomor pendaftaran
   - Status saat ini (badge)
   - Gradient background

2. **Progress Steps (4 steps):**
   - Formulir (✓ Selesai)
   - Verifikasi (🔄 Proses)
   - Ujian (⏳ Menunggu)
   - Pengumuman (⏳ Menunggu)

3. **Main Content:**
   - Data Pribadi (card)
   - Dokumen status (list dengan icon status)

4. **Sidebar:**
   - Menu Cepat (3 actions)
   - Jadwal Ujian (card)
   - Info Penting (alert box)

**Route:**
```php
Route::prefix('pendaftar')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.pendaftar.index');
    });
});
```

**Preview:** http://localhost:8000/pendaftar/dashboard

## Dashboard Panitia Pendaftaran

### Layout: `components/layouts/dashboard.blade.php` (role="panitia_pendaftaran")

**Menu Items:**
- Dashboard
- Verifikasi Berkas
- Data Pendaftar
- Jadwal Ujian
- Laporan

**Dashboard Content (`dashboard/panitia-pendaftaran/index.blade.php`):**
1. **Stats Cards (4):**
   - Menunggu Verifikasi (yellow)
   - Terverifikasi (green)
   - Ditolak (red)
   - Total Hari Ini (blue)

2. **Menunggu Verifikasi Table:**
   - Pendaftar info
   - Jalur badge
   - Tanggal & waktu
   - CTA "Verifikasi"

3. **Sidebar:**
   - Ringkasan Hari Ini
   - Status Dokumen (progress bars)
   - Aksi Cepat (2 buttons)

**Route:**
```php
Route::prefix('panitia')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.panitia-pendaftaran.index');
    });
});
```

**Preview:** http://localhost:8000/panitia/dashboard

## Dashboard Panitia Daftar Ulang

### Layout: `components/layouts/dashboard.blade.php` (role="panitia_daftarulang")

**Menu Items:**
- Dashboard
- Siswa Diterima
- Verifikasi Daftar Ulang
- Verifikasi Pembayaran
- Data Seragam
- Laporan

**Dashboard Content (`dashboard/panitia-daftarulang/index.blade.php`):**
1. **Stats Cards (4):**
   - Siswa Diterima
   - Sudah Daftar Ulang
   - Belum Daftar Ulang
   - Pembayaran Lunas

2. **Daftar Ulang Hari Ini Table:**
   - Siswa info
   - Status pembayaran
   - Status seragam
   - Status keseluruhan

3. **Sidebar:**
   - Ringkasan Pembayaran
   - Data Seragam (progress)
   - Aksi Cepat (3 buttons)
   - Reminder (batas waktu)

**Route:**
```php
Route::prefix('daftarulang')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.panitia-daftarulang.index');
    });
});
```

**Preview:** http://localhost:8000/daftarulang/dashboard

## Komponen UI

### Stats Card
```blade
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-600">Label</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">Value</p>
            <p class="text-sm text-green-600 mt-2">Description</p>
        </div>
        <div class="w-14 h-14 bg-blue-100 rounded-lg flex items-center justify-center">
            <!-- Icon SVG -->
        </div>
    </div>
</div>
```

### Badge Status
```blade
<!-- Success -->
<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
    Diterima
</span>

<!-- Warning -->
<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
    Menunggu
</span>

<!-- Danger -->
<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
    Ditolak
</span>

<!-- Info -->
<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
    Prestasi
</span>
```

### Progress Bar
```blade
<div>
    <div class="flex justify-between text-sm mb-1">
        <span class="text-gray-600">Label</span>
        <span class="font-semibold text-green-600">85%</span>
    </div>
    <div class="w-full bg-gray-200 rounded-full h-2">
        <div class="bg-green-600 h-2 rounded-full" style="width: 85%"></div>
    </div>
</div>
```

### Avatar with Initials
```blade
<div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
    <span class="text-emerald-600 font-semibold">AM</span>
</div>
```

### Action Button
```blade
<!-- Primary -->
<a href="#" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">
    Action
</a>

<!-- Secondary -->
<a href="#" class="inline-flex items-center px-4 py-2 border border-emerald-600 text-emerald-600 rounded-lg hover:bg-emerald-50 transition">
    Action
</a>
```

### Alert Box
```blade
<!-- Warning -->
<div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
    <div class="flex">
        <svg class="w-5 h-5 text-yellow-600 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <!-- Icon path -->
        </svg>
        <div>
            <p class="text-sm font-medium text-yellow-800">Title</p>
            <p class="text-xs text-yellow-700 mt-1">Description</p>
        </div>
    </div>
</div>
```

## Color Scheme

### Primary Colors
```css
emerald-50:  #ecfdf5
emerald-100: #d1fae5
emerald-600: #059669  /* Primary */
emerald-700: #047857
emerald-800: #065f46
```

### Secondary Colors
```css
blue-600:    #2563eb
yellow-600:  #ca8a04
green-600:   #16a34a
red-600:     #dc2626
purple-600:  #9333ea
```

### Neutral Colors
```css
gray-50:     #f9fafb
gray-100:    #f3f4f6
gray-200:    #e5e7eb
gray-600:    #4b5563
gray-900:    #111827
```

## Typography

### Font Family
- Primary: `Inter` (via Bunny Fonts)
- Fallback: `sans-serif`

### Font Weights
- Regular: 400
- Medium: 500
- Semibold: 600
- Bold: 700

### Text Sizes
```css
text-xs:   0.75rem   (12px)
text-sm:   0.875rem  (14px)
text-base: 1rem      (16px)
text-lg:   1.125rem  (18px)
text-xl:   1.25rem   (20px)
text-2xl:  1.5rem    (24px)
text-3xl:  1.875rem  (30px)
text-4xl:  2.25rem   (36px)
```

## Icons

Menggunakan **Heroicons** (SVG inline):
- Outline untuk navigation
- Solid untuk badges dan states

### Contoh Icons:
- Home: `M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6`
- Users: `M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z`
- Check Circle: `M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z`
- Clock: `M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z`

## Routes Tersedia

### Public Routes
```
GET  /                          # Homepage
GET  /welcome                   # Livewire Demo
GET  /exports                   # Export Demo
```

### Dashboard Routes (Demo - No Auth)
```
GET  /admin/dashboard           # Admin Dashboard
GET  /pendaftar/dashboard       # Pendaftar Dashboard
GET  /panitia/dashboard         # Panitia Pendaftaran Dashboard
GET  /daftarulang/dashboard     # Panitia Daftar Ulang Dashboard
```

### Export Routes
```
GET  /export/pdf                # Generate PDF
GET  /export/excel              # Generate Excel
```

## Responsive Breakpoints

```css
sm:  640px   /* Small devices */
md:  768px   /* Medium devices */
lg:  1024px  /* Large devices */
xl:  1280px  /* Extra large devices */
```

## Best Practices

1. **Consistency**: Gunakan komponen yang sudah ada
2. **Spacing**: Gunakan Tailwind spacing scale (4px increments)
3. **Colors**: Stick to color scheme yang sudah ditentukan
4. **Icons**: Gunakan Heroicons dengan size konsisten
5. **Typography**: Gunakan hierarchy yang jelas
6. **Accessibility**: Pastikan contrast ratio memadai
7. **Mobile First**: Design untuk mobile terlebih dahulu

## Development Tips

### Clear Cache
```bash
php artisan view:clear
```

### Hot Reload
Vite sudah dikonfigurasi untuk auto-reload saat file blade berubah.

### Testing Responsive
Gunakan Chrome DevTools untuk test berbagai ukuran layar.

## Future Enhancements

- [ ] Dark mode support
- [ ] Multi-language (ID/EN)
- [ ] Print-friendly views
- [ ] Accessibility improvements (ARIA labels)
- [ ] Animation & transitions
- [ ] Loading states & skeletons
- [ ] Toast notifications
- [ ] Modal dialogs
- [ ] Form validation UI

---

**Version:** 1.0.0
**Last Updated:** 5 Januari 2025
**Designer:** Tim SPMBM MTsN 2 Kota Malang
