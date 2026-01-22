Struktur Folder Utama untuk Production

  1. Folder WAJIB di Production:

  ├── app/              → Business logic (Controllers, Models, Services)
  ├── bootstrap/        → Framework bootstrap files
  ├── config/           → Konfigurasi aplikasi
  ├── database/         → Migrations untuk schema database
  ├── public/           → Web-accessible files & compiled assets
  ├── resources/views/  → Blade templates (114 files)
  ├── routes/           → Route definitions
  ├── storage/          → Runtime data (logs, cache, uploads)
  ├── vendor/           → Composer dependencies (auto-generated)
  ├── .env              → Environment variables production
  ├── composer.json     → PHP dependencies
  └── artisan          → Laravel CLI tool

  2. Folder yang TIDAK diperlukan di Production:

  ✗ node_modules/      → 56MB development dependencies
  ✗ .git/              → Version control (security risk)
  ✗ tests/             → Testing code
  ✗ Documentation files (*.md)
  ✗ Deployment scripts (*.sh, *.bat)

  Organisasi Kode

  Controllers (39 files)

  Diorganisir berdasarkan role:
  - Admin/ - 22 controllers untuk admin functions
  - Employee/ - 5 controllers untuk karyawan
  - Student/ - 1 controller untuk siswa
  - Monitoring/ - 4 controllers untuk monitoring
  - API/ - 2 controllers untuk endpoints

  Models (20 files)

  Model utama: User, Employee, Student, Attendance, Schedule, Payroll, Permission, dll.

  Views (114 Blade templates)

  Struktur berdasarkan role:
  - admin/ - 50+ templates untuk admin panel
  - employee/ - Dashboard karyawan
  - student/ - Interface siswa
  - monitoring/ - Dashboard monitoring

  Storage & Upload Files

  Public Storage (Web-accessible):

  storage/app/public/
  ├── selfies/
  │   ├── employee/     → Foto selfie karyawan
  │   └── student/      → Foto selfie siswa
  ├── permissions/      → File izin/cuti
  └── attendance_corrections/  → Lampiran koreksi

  Private Storage:

  storage/app/
  ├── private/          → File private
  ├── backups/          → Database backups
  └── backup-temp/      → Temporary backup files

  Konfigurasi Production

  Environment Variables (.env.production):

  APP_ENV=production
  APP_DEBUG=false
  APP_URL=

  DB_CONNECTION=pgsql
  DB_DATABASE=
  DB_USERNAME=

  SESSION_DRIVER=database
  CACHE_STORE=database

  Build Assets:

  public/build/
  ├── manifest.json     → Asset mapping
  └── assets/
      ├── app-*.js      → Compiled JavaScript
      ├── app-*.css     → Compiled Tailwind CSS
      └── alpine-*.js   → Alpine JS bundle

  Security Implementation

  Middleware Security:

  - SecurityHeaders.php - HTTP security headers
  - EnhancedSecurity.php - Additional security checks
  - ApiRateLimitProtection.php - Rate limiting untuk API
  - CheckRole.php - Role-based access control

  Security Features:

  - Session berbasis database
  - Password hashing
  - Security & login attempt logging
  - CSRF protection
  - XSS protection

  Deployment Checklist untuk Production

  Pre-Deployment:

  1. Setup PostgreSQL database
  2. Configure .env dengan credentials production
  3. Setup SSL/HTTPS
  4. Set folder permissions:
     - storage/: 775 (writable)
     - bootstrap/cache/: 775 (writable)
     - public/: 755