# Aplikasi SPMBM MTS N 2 KM

> Aplikasi Sistem Penerimaan Murid Baru Madrasah (SPMBM) berbasis Laravel 12 dengan Livewire 3

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel)](https://laravel.com)
[![Livewire](https://img.shields.io/badge/Livewire-3.7-FB70A9?logo=livewire)](https://livewire.laravel.com)
[![Vite](https://img.shields.io/badge/Vite-7-646CFF?logo=vite)](https://vitejs.dev)
[![Tailwind](https://img.shields.io/badge/Tailwind-4-38B2AC?logo=tailwind-css)](https://tailwindcss.com)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-12+-336791?logo=postgresql)](https://www.postgresql.org)

## 🚀 Tech Stack

- **Laravel 12** - PHP Framework terbaru
- **Livewire 3.7.3** - Dynamic UI Components
- **Vite 7.0** - Modern Frontend Build Tool
- **Tailwind CSS 4** - Utility-first CSS Framework
- **PostgreSQL 12+** - Production-grade Database
- **DomPDF 3.0** - PDF Generation
- **Laravel Excel 3.1** - Excel Import/Export
- **PHP 8.2+** - Required

## ✨ Features

### Core Features

- 🎨 Modern UI dengan Livewire 3 & Tailwind CSS 4
- 📊 Export data ke PDF & Excel
- 🔒 Security features lengkap (CSRF, Rate Limiting, Security Headers)
- 🎯 Custom error pages yang menarik
- ⚡ Hot Module Replacement dengan Vite
- 📱 Responsive design
- 🌐 CORS configuration
- 🔐 Input validation & XSS protection

### Security Features

✅ **Security Headers** - CSP, HSTS, X-Frame-Options, X-XSS-Protection
✅ **Rate Limiting** - API throttling & Login attempts protection
✅ **CSRF Protection** - Auto-enabled untuk semua POST requests
✅ **Custom Error Pages** - Professional error handling (404, 403, 500, 503, 419)
✅ **Input Validation** - XSS & SQL Injection protection

### Export Features

📄 **PDF Export** - Professional document generation dengan DomPDF
📊 **Excel Export** - Spreadsheet export dengan formatting & styling

## 📦 Quick Start

### Development Mode

```bash
# 1. Install dependencies
composer install
npm install

# 2. Setup environment
copy .env.example .env
php artisan key:generate

# 3. Setup PostgreSQL database
# Buat database: CREATE DATABASE spmbm_mtsn2km;
# Sesuaikan DB_USERNAME dan DB_PASSWORD di .env

# 4. Run migrations
php artisan migrate

# 5. Run development server
composer dev
```

Buka browser: `http://localhost:8000`

**Catatan**: Pastikan PostgreSQL sudah terinstall dan berjalan.

### Production Build

```bash
# 1. Install dependencies (production only)
composer install --optimize-autoloader --no-dev

# 2. Build assets
npm run build:production

# 3. Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🔧 Configuration

### Environment Variables

Lihat `.env.example` untuk development dan `.env.production.example` untuk production setup.

### PostgreSQL Setup

Aplikasi ini menggunakan PostgreSQL sebagai database. Pastikan PostgreSQL 12+ sudah terinstall:

```bash
# Login ke PostgreSQL
psql -U postgres

# Buat database
CREATE DATABASE spmbm_mtsn2km;
```

### CORS Configuration

Edit `config/cors.php` untuk mengatur allowed origins:

```php
'allowed_origins' => [
    env('APP_URL', 'http://localhost'),
    'https://your-domain.com',
],
```

## 📄 Export Usage

### PDF Export Example

```php
use Barryvdh\DomPDF\Facade\Pdf;

$pdf = Pdf::loadView('template', $data);
return $pdf->download('file.pdf');
```

### Excel Export Example

```php
use Maatwebsite\Excel\Facades\Excel;

return Excel::download(new DataExport, 'file.xlsx');
```

**Demo page:** `/exports`

## 🛠 Useful Commands

```bash
# Development
composer dev                  # Run all services
npm run dev                   # Vite dev server only
php artisan serve             # Laravel server only

# Production
npm run build:production      # Build optimized assets

# Cache
php artisan optimize:clear    # Clear all cache
php artisan config:cache      # Cache config
php artisan route:cache       # Cache routes

# Code Quality
./vendor/bin/pint             # Format code
php artisan test              # Run tests
```

## 🌐 Routes

### Public Routes
- `/` - Homepage SPMBM
- `/welcome` - Livewire demo
- `/exports` - PDF & Excel export demo
- `/export/pdf` - Download sample PDF
- `/export/excel` - Download sample Excel

### Dashboard Routes (Demo)
- `/admin/dashboard` - Admin Dashboard
- `/pendaftar/dashboard` - Pendaftar Dashboard
- `/panitia/dashboard` - Panitia Pendaftaran Dashboard
- `/daftarulang/dashboard` - Panitia Daftar Ulang Dashboard

## 🎨 UI/UX Layouts

Aplikasi ini memiliki 5 jenis layout yang berbeda:

### 1. Public Layout (`layouts/public.blade.php`)
Untuk halaman publik dengan navigation menu lengkap, hero section, dan footer.

**Penggunaan:**
```blade
<x-layouts.public>
    <!-- Content -->
</x-layouts.public>
```

### 2. Dashboard Layout (`layouts/dashboard.blade.php`)
Layout unified untuk semua role dengan sidebar dinamis berdasarkan role.

**Penggunaan:**
```blade
<x-layouts.dashboard role="admin" pageTitle="Dashboard" pageDescription="Selamat datang">
    <!-- Content -->
</x-layouts.dashboard>
```

**Available Roles:**
- `admin` - Administrator
- `pendaftar` - Calon Siswa
- `panitia_pendaftaran` - Panitia Verifikasi
- `panitia_daftarulang` - Panitia Daftar Ulang

### Demo Pages
✅ **Homepage Public** - http://localhost:8000/
✅ **Admin Dashboard** - http://localhost:8000/admin/dashboard
✅ **Pendaftar Dashboard** - http://localhost:8000/pendaftar/dashboard
✅ **Panitia Pendaftaran** - http://localhost:8000/panitia/dashboard
✅ **Panitia Daftar Ulang** - http://localhost:8000/daftarulang/dashboard

## 📁 Project Structure

```
app/
├── Exports/              # Excel export classes
├── Http/
│   ├── Controllers/      # Controllers
│   └── Middleware/       # Security middleware
└── Livewire/            # Livewire components

resources/
├── css/                 # Tailwind CSS
├── js/                  # JavaScript
└── views/
    ├── components/
    │   └── layouts/     # Layout components
    │       ├── app.blade.php        # Generic layout
    │       ├── public.blade.php     # Public layout
    │       └── dashboard.blade.php  # Dashboard layout
    ├── public/          # Public pages
    │   └── home.blade.php
    ├── dashboard/       # Dashboard pages
    │   ├── admin/
    │   ├── pendaftar/
    │   ├── panitia-pendaftaran/
    │   └── panitia-daftarulang/
    ├── errors/          # Custom error pages
    ├── exports/         # Export templates
    └── livewire/        # Livewire views

config/
├── cors.php            # CORS configuration
└── dompdf.php          # PDF configuration
```

## 🐛 Troubleshooting

**Livewire tidak bekerja?**
- Pastikan `@livewireStyles` ada di `<head>`
- Pastikan `@livewireScripts` ada sebelum `</body>`
- Run: `php artisan view:clear`

**Vite error?**
- Hapus `node_modules` dan run: `npm install`
- Force rebuild: `npm run dev -- --force`

**Composer lambat di Windows?**
- Normal saat "Generating autoload files"
- Disable antivirus sementara untuk folder project

## 🐳 Deploy (Docker / Coolify)

Aplikasi siap di-deploy dengan Docker: `Dockerfile` (Nginx + PHP-FPM 8.4) dan `docker-compose.yml`
(web, worker antrian, scheduler, PostgreSQL). Langkah lengkap, pemindahan data dari cPanel, update,
dan backup ada di [`docs/DEPLOY_COOLIFY.md`](docs/DEPLOY_COOLIFY.md).

## 📖 Documentation

- [`docs/DEPLOY_COOLIFY.md`](docs/DEPLOY_COOLIFY.md): deploy dengan Docker/Coolify, pindah data dari cPanel, update & backup
- [`docs/AUDIT_REPORT_2026-09-27.md`](docs/AUDIT_REPORT_2026-09-27.md): hasil audit, perbaikan keamanan & langkah deploy cPanel
- [`docs/referensi/`](docs/referensi/): dokumen field data siswa EMIS
- [`scripts/README.md`](scripts/README.md): skrip cron & monitoring materialized views

## 📝 License

MIT License

## 👨‍💻 Developer

Tim Developer SPMBM MTS N 2 KM

---

**Ready for Development & Production! 🎉**
