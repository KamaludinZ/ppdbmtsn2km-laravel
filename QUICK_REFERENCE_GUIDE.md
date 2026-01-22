# Quick Reference Guide - PPDB MTsN 2 Kota Malang

## Development Commands

### Start Development Server
```bash
# Development with Vite HMR
npm run dev

# PHP Artisan Serve
php artisan serve

# Atau gunakan composer script (recommended)
composer dev
```

### Build for Production
```bash
# Build assets
npm run build

# Optimize Laravel
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Clear Cache
```bash
# Clear all caches
php artisan optimize:clear

# Clear specific caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## Asset Loading Modes

### Local Development (dengan Vite)
- Otomatis detect jika Vite dev server running
- HMR (Hot Module Replacement) aktif
- File: `public/hot` akan ada

### Production (tanpa Vite)
- Menggunakan pre-compiled assets
- Files di `public/css/` dan `public/js/`
- Cache busting dengan file modification time

### Force Production Mode di Local
Edit `.env`:
```
APP_ENV=production
```

---

## Export Functionality

### Menggunakan Export di Admin/Panitia

#### 1. Export Pendaftar Lengkap
```php
// Di Livewire Component:
public function exportDataLengkap()
{
    $fileName = 'data_pendaftar_lengkap_' . date('Y-m-d_His') . '.xlsx';

    return Excel::download(
        new PendaftarExport(
            $this->filterJalur,
            $this->filterStatus,
            null
        ),
        $fileName
    );
}
```

**Output**: Single sheet dengan 95+ columns

#### 2. Export Daftar Ulang (3 Sheets)
```php
// Di Livewire Component:
public function exportDaftarUlang()
{
    $fileName = 'data_daftar_ulang_' . date('Y-m-d_His') . '.xlsx';

    return Excel::download(
        new DaftarUlangExport(
            $this->filterStatus,
            null
        ),
        $fileName
    );
}
```

**Output**:
- Sheet 1: Daftar Ulang Madrasah
- Sheet 2: Daftar Ulang Seragam
- Sheet 3: Ukuran Seragam & Atribut

---

## Security Best Practices

### 1. Never Put These in Fillable
```php
// ❌ WRONG
protected $fillable = ['name', 'email', 'role', 'aktif'];

// ✅ CORRECT
protected $fillable = ['name', 'email'];
// role dan aktif di-set explicitly di code
```

### 2. Always Use Eloquent for Queries
```php
// ✅ GOOD - Automatic parameter binding
$users = User::where('email', $request->email)->get();

// ⚠️ AVOID - Risk of SQL injection
$users = DB::select("SELECT * FROM users WHERE email = '{$request->email}'");
```

### 3. Always Escape Output
```blade
{{-- ✅ GOOD - Auto-escaped --}}
{{ $user->name }}

{{-- ⚠️ DANGEROUS - Not escaped --}}
{!! $user->name !!}
```

### 4. Use CSRF Protection
```blade
<form method="POST">
    @csrf
    <!-- form fields -->
</form>
```

---

## Database Optimization Tips

### 1. Eager Loading (Prevent N+1)
```php
// ❌ BAD - N+1 problem
$pendaftars = Pendaftar::all();
foreach($pendaftars as $pendaftar) {
    echo $pendaftar->user->name; // Query di loop!
}

// ✅ GOOD - Eager loading
$pendaftars = Pendaftar::with('user')->get();
foreach($pendaftars as $pendaftar) {
    echo $pendaftar->user->name; // Tidak query lagi
}
```

### 2. Use Chunking for Large Data
```php
// Process data in chunks
Pendaftar::chunk(200, function ($pendaftars) {
    foreach ($pendaftars as $pendaftar) {
        // Process each record
    }
});
```

### 3. Index Important Columns
```php
// In migration:
$table->index('status_pendaftaran');
$table->index('jalur_pendaftaran_id');
$table->index(['status_pendaftaran', 'created_at']); // Composite index
```

---

## Role-Based Access Control

### Available Roles:
1. `admin` - Full access
2. `pendaftar` - Applicant access
3. `panitia_pendaftaran` - Registration committee
4. `panitia_daftarulang` - Re-registration committee
5. `panitia` - General committee

### Protecting Routes:
```php
// routes/web.php
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', AdminDashboard::class);
});

Route::middleware(['auth', 'role:pendaftar'])->group(function () {
    Route::get('/pendaftar/dashboard', PendaftarDashboard::class);
});
```

### Checking Role in Livewire:
```php
public function mount()
{
    $user = Auth::user();
    if (!$user || $user->role !== 'admin') {
        return redirect()->to('/login/admin');
    }
}
```

---

## Testing

### Run Tests
```bash
# All tests
php artisan test

# Specific test
php artisan test --filter=PendaftarTest

# With coverage
php artisan test --coverage
```

### Create New Test
```bash
php artisan make:test PendaftarExportTest
```

---

## Troubleshooting

### 1. Vite Not Loading
```bash
# Check if Vite is running
npm run dev

# Check hot file
cat public/hot

# Force rebuild
rm -rf node_modules
npm install
npm run dev
```

### 2. Livewire Not Working
```bash
# Clear cache
php artisan livewire:discover
php artisan optimize:clear

# Check Livewire version
composer show livewire/livewire
```

### 3. Export Error
```bash
# Check permissions
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# Check disk space
df -h

# Check PHP memory limit
php -i | grep memory_limit
```

### 4. Database Connection Error
```bash
# Check database credentials in .env
cat .env | grep DB_

# Test connection
php artisan db:show

# Run migrations
php artisan migrate
```

---

## Performance Monitoring

### Check Query Performance
```php
// Enable query logging
DB::enableQueryLog();

// Your code here

// Get queries
dd(DB::getQueryLog());
```

### Laravel Telescope (Optional)
```bash
composer require laravel/telescope
php artisan telescope:install
php artisan migrate
```

---

## Backup & Restore

### Database Backup
```bash
# PostgreSQL
pg_dump -U username -d database_name > backup.sql

# Restore
psql -U username -d database_name < backup.sql
```

### File Backup
```bash
# Backup storage
tar -czf storage_backup.tar.gz storage/

# Backup uploads
tar -czf uploads_backup.tar.gz public/uploads/
```

---

## Environment Configuration

### Local Development (.env)
```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
```

### Production (.env)
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Security
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxx
```

---

## Useful Artisan Commands

```bash
# Generate APP_KEY
php artisan key:generate

# Create symlink for storage
php artisan storage:link

# List routes
php artisan route:list

# List registered service providers
php artisan about

# Clear and optimize
php artisan optimize

# Maintenance mode
php artisan down
php artisan up
```

---

## Git Workflow

```bash
# Check status
git status

# Add changes
git add .

# Commit
git commit -m "Your message"

# Push
git push origin main

# Pull latest
git pull origin main
```

---

## Contact & Support

### Check Logs
```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Nginx/Apache logs
tail -f /var/log/nginx/error.log
```

### Debug Mode
Edit `.env`:
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

---

**Last Updated**: 19 Januari 2026
**Version**: 1.0.0
