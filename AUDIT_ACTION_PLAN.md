# ACTION PLAN - AUDIT PPDB MTsN 2 Kota Malang

**Tanggal:** 16 Januari 2026
**Total Issues:** 57
**Estimated Time:** 56 hours (~7 working days)

---

## 🎯 OVERVIEW

Dokumen ini adalah action plan yang bisa di-track untuk menyelesaikan semua findings dari audit. Setiap item memiliki checkbox untuk progress tracking.

---

## 🔴 PHASE 1: CRITICAL FIXES (Day 1-2, ~11 hours)

**Deadline:** 18 Januari 2026 (48 jam)
**Priority:** MUST FIX - Production Security Risk

### Security (8 hours)

#### Issue #1: Implement Role-Based Middleware
- [ ] **Create middleware** `app/Http/Middleware/CheckRole.php`
  ```php
  public function handle($request, Closure $next, ...$roles)
  {
      if (!auth()->check() || !in_array(auth()->user()->role, $roles)) {
          abort(403, 'Unauthorized action.');
      }
      return $next($request);
  }
  ```
- [ ] **Register middleware** di `bootstrap/app.php`
- [ ] **Update admin routes** `routes/web.php:231-257`
  ```php
  Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(...)
  ```
- [ ] **Update pendaftar routes** `routes/web.php:264-273`
  ```php
  Route::prefix('pendaftar')->middleware(['auth', 'role:pendaftar'])->group(...)
  ```
- [ ] **Update panitia routes** `routes/web.php:275-289`
  ```php
  Route::prefix('panitia')->middleware(['auth', 'role:panitia_pendaftaran,panitia_daftarulang'])->group(...)
  ```
- [ ] **Test dengan role admin** - akses semua routes
- [ ] **Test dengan role pendaftar** - try access /admin → should get 403
- [ ] **Test dengan role panitia** - verify access permissions
- [ ] **Clear route cache** `php artisan route:clear`
- **Estimated Time:** 2 hours
- **Assigned To:** _____________
- **Status:** ☐ Not Started ☐ In Progress ☑ Completed

---

#### Issue #2: Fix IDOR Vulnerabilities
- [ ] **Update Admin/DetailPendaftar.php** `app/Livewire/Admin/DetailPendaftar.php`
  ```php
  public function mount($id)
  {
      $this->pendaftar = Pendaftar::findOrFail($id);

      // Authorization check for pendaftar role
      if (auth()->user()->role === 'pendaftar') {
          if (auth()->user()->id !== $this->pendaftar->user_id) {
              abort(403, 'Unauthorized access to this data');
          }
      }
  }
  ```
- [ ] **Update Panitia/DetailPendaftar.php** (same logic)
- [ ] **Create Policy** (optional, better approach):
  ```bash
  php artisan make:policy PendaftarPolicy
  ```
- [ ] **Test pendaftar** try change ID di URL → should get 403
- [ ] **Test admin** should be able to view all pendaftar
- [ ] **Test panitia** verify can view assigned pendaftar only (if applicable)
- **Estimated Time:** 3 hours
- **Assigned To:** _____________
- **Status:** ☐ Not Started ☐ In Progress ☑ Completed

---

#### Issue #3: Move Files to Private Storage
- [ ] **Backup current files** di `storage/app/public/dokumen/`
- [ ] **Update Dokumen.php** `app/Livewire/Pendaftar/Dokumen.php:137`
  ```php
  // OLD: $path = $file->store('dokumen/' . $this->pendaftar->id, 'public');
  // NEW:
  $path = $file->store('dokumen/' . $this->pendaftar->id); // Default to private
  ```
- [ ] **Create download route** `routes/web.php`
  ```php
  Route::get('/dokumen/{id}/download', function($id) {
      $dokumen = Dokumen::findOrFail($id);

      // Authorization check
      if (auth()->user()->role === 'pendaftar' &&
          auth()->user()->id !== $dokumen->pendaftar->user_id) {
          abort(403);
      }

      return response()->file(storage_path('app/' . $dokumen->path_file));
  })->middleware('auth')->name('dokumen.download');
  ```
- [ ] **Update views** untuk gunakan download route instead of asset()
- [ ] **Migrate existing files** dari public ke private storage
- [ ] **Test download** dengan authenticated user
- [ ] **Test direct URL access** should fail (404)
- **Estimated Time:** 2 hours
- **Assigned To:** _____________
- **Status:** ☐ Not Started ☐ In Progress ☑ Completed

---

#### Issue #4: Rotate All Credentials
- [ ] **Generate new APP_KEY**
  ```bash
  php artisan key:generate
  ```
- [ ] **Change DB_PASSWORD** di database server
  ```sql
  ALTER USER postgresppdb WITH PASSWORD 'NewSecurePassword123!';
  ```
- [ ] **Update .env** dengan password baru
- [ ] **Rotate WhatsApp API tokens** di provider dashboard
- [ ] **Update WHATSAPP_ACCESS_TOKEN** di .env
- [ ] **Test database connection** `php artisan db:show`
- [ ] **Test WhatsApp service** send test message
- [ ] **Document new credentials** di password manager (NOT in Git!)
- [ ] **Verify .env di .gitignore**
- **Estimated Time:** 1 hour
- **Assigned To:** _____________
- **Status:** ☐ Not Started ☐ In Progress ☑ Completed

---

### Database (2.5 hours)

#### Issue #5: Re-add Users Email Uniqueness
- [ ] **Check for duplicate emails**
  ```sql
  SELECT email, COUNT(*)
  FROM users
  GROUP BY email
  HAVING COUNT(*) > 1;
  ```
- [ ] **Resolve duplicates** (manual cleanup or script)
  - Keep oldest user
  - Merge data if needed
  - Delete duplicates
- [ ] **Create migration** `php artisan make:migration add_unique_to_users_email`
  ```php
  public function up()
  {
      Schema::table('users', function (Blueprint $table) {
          $table->unique('email');
      });
  }
  ```
- [ ] **Run migration** `php artisan migrate`
- [ ] **Test registration** dengan duplicate email → should fail
- [ ] **Test login** verify no confusion
- **Estimated Time:** 2 hours
- **Assigned To:** _____________
- **Status:** ☐ Not Started ☐ In Progress ☑ Completed

---

#### Issue #6: Fix Timezone & Locale
- [ ] **Update config/app.php**
  ```php
  'timezone' => 'Asia/Jakarta',  // Changed from 'UTC'
  'locale' => 'id',               // Changed from 'en'
  'fallback_locale' => 'en',
  ```
- [ ] **Clear config cache** `php artisan config:clear`
- [ ] **Test timestamps** di dashboard → should show Jakarta time
- [ ] **Test date formatting** verify Indonesian locale
- **Estimated Time:** 30 minutes
- **Assigned To:** _____________
- **Status:** ☐ Not Started ☐ In Progress ☑ Completed

---

### Routes (30 minutes)

#### Issue #7: Protect Export Routes
- [ ] **Update routes/web.php:306-308**
  ```php
  Route::middleware(['auth', 'role:admin,panitia_pendaftaran,panitia_daftarulang'])->group(function() {
      Route::get('/exports', [ExportController::class, 'index'])->name('exports.index');
      Route::get('/export/pdf', [ExportController::class, 'generatePDF'])->name('export.pdf');
      Route::get('/export/excel', [ExportController::class, 'generateExcel'])->name('export.excel');
  });
  ```
- [ ] **Test unauthenticated access** → should redirect to login
- [ ] **Test pendaftar access** → should get 403
- [ ] **Test admin/panitia access** → should work
- **Estimated Time:** 30 minutes
- **Assigned To:** _____________
- **Status:** ☐ Not Started ☐ In Progress ☑ Completed

---

## 🟠 PHASE 2: HIGH PRIORITY (Week 1-2, ~19 hours)

**Deadline:** 30 Januari 2026 (2 weeks)
**Priority:** HIGH - Performance & Security Improvements

### Performance (12 hours)

#### Issue #8: Add Missing Database Indexes
- [ ] **Create migration** `php artisan make:migration add_performance_indexes_to_pendaftar`
- [ ] **Add indexes**
  ```php
  public function up()
  {
      Schema::table('pendaftar', function (Blueprint $table) {
          $table->index('nama_lengkap');
          $table->index('status_pendaftaran');
          $table->index('asal_kabkota');
          $table->index(['jalur_pendaftaran_id', 'status_pendaftaran'], 'idx_jalur_status');
      });

      Schema::table('dokumen', function (Blueprint $table) {
          $table->index('status_verifikasi');
      });

      Schema::table('verifikasi_berkas', function (Blueprint $table) {
          $table->index('status');
      });
  }
  ```
- [ ] **Run migration** `php artisan migrate`
- [ ] **Test query performance** dengan EXPLAIN ANALYZE
- [ ] **Verify index usage** di slow query log
- [ ] **Benchmark search** before/after indexes
- **Estimated Time:** 1 hour
- **Assigned To:** _____________
- **Status:** ☐ Not Started ☐ In Progress ☑ Completed

---

#### Issue #9: Fix N+1 Query in EMIS Stats
- [ ] **Refactor loadEmisCompletenessStats()** `app/Livewire/Admin/Dashboard.php:108`
- [ ] **Option 1: Database Aggregation** (Recommended)
  ```php
  // Instead of looping, use database query
  $stats = DB::table('pendaftar')
      ->selectRaw('
          COUNT(*) as total,
          AVG(emis_completeness_percentage) as avg_percentage,
          SUM(CASE WHEN emis_completeness_percentage = 100 THEN 1 ELSE 0 END) as complete_count
      ')
      ->first();
  ```
- [ ] **Option 2: Add Computed Column** (Better long-term)
  ```php
  // Add emis_completeness_percentage to pendaftar table
  // Calculate on save instead of on-the-fly
  ```
- [ ] **Add caching**
  ```php
  $stats = Cache::remember('emis_stats', 3600, function() {
      // Calculation logic
  });
  ```
- [ ] **Test dashboard load** should be < 2 seconds
- [ ] **Verify no timeout** errors
- [ ] **Monitor query count** should reduce drastically
- **Estimated Time:** 4 hours
- **Assigned To:** _____________
- **Status:** ☐ Not Started ☐ In Progress ☑ Completed

---

#### Issue #10: Optimize Count Queries
- [ ] **Refactor Kelulusan stats** `app/Livewire/Admin/Kelulusan.php:82-86`
  ```php
  // Instead of 5 separate count queries
  $stats = Pendaftar::selectRaw('
      COUNT(*) as total,
      SUM(CASE WHEN status_pendaftaran = "menunggu_verifikasi" THEN 1 ELSE 0 END) as menunggu,
      SUM(CASE WHEN status_pendaftaran = "data_disetujui" THEN 1 ELSE 0 END) as lulus,
      ...
  ')->first();
  ```
- [ ] **Apply same pattern** ke Panitia/Kelulusan.php
- [ ] **Apply to Pendaftar stats** `Admin/Pendaftar.php:160-167`
- [ ] **Test query performance** single query vs multiple
- [ ] **Benchmark before/after**
- **Estimated Time:** 2 hours
- **Assigned To:** _____________
- **Status:** ☐ Not Started ☐ In Progress ☑ Completed

---

#### Issue #11: Setup Materialized View Refresh
- [ ] **Create command** `php artisan make:command RefreshMaterializedViews`
  ```php
  public function handle()
  {
      DB::statement('REFRESH MATERIALIZED VIEW CONCURRENTLY mv_dashboard_stats');
      DB::statement('REFRESH MATERIALIZED VIEW CONCURRENTLY mv_jalur_stats');
      DB::statement('REFRESH MATERIALIZED VIEW CONCURRENTLY mv_recent_pendaftar');
      DB::statement('REFRESH MATERIALIZED VIEW CONCURRENTLY mv_panitia_stats');
      $this->info('Materialized views refreshed successfully');
  }
  ```
- [ ] **Add to scheduler** `app/Console/Kernel.php`
  ```php
  protected function schedule(Schedule $schedule)
  {
      $schedule->command('materialized-views:refresh')->hourly();
  }
  ```
- [ ] **Test command** `php artisan materialized-views:refresh`
- [ ] **Verify scheduler** `php artisan schedule:list`
- [ ] **Monitor refresh duration** should be < 30 seconds
- **Estimated Time:** 2 hours
- **Assigned To:** _____________
- **Status:** ☐ Not Started ☐ In Progress ☑ Completed

---

#### Issue #12: Implement Query Result Caching
- [ ] **Cache JalurPendaftaran** wherever it's queried
  ```php
  $jalurPendaftaran = Cache::remember('jalur_aktif', 3600, function() {
      return JalurPendaftaran::aktif()->ordered()->get();
  });
  ```
- [ ] **Cache Settings** - already implemented in Setting model, verify TTL
- [ ] **Add cache invalidation** when data changes
  ```php
  // In JalurPendaftaran model
  protected static function booted()
  {
      static::saved(function () {
          Cache::forget('jalur_aktif');
      });
  }
  ```
- [ ] **Monitor cache hit ratio**
- [ ] **Test cache clearing** when jalur updated
- **Estimated Time:** 2 hours
- **Assigned To:** _____________
- **Status:** ☐ Not Started ☐ In Progress ☑ Completed

---

#### Issue #13: Implement Image Optimization
- [ ] **Use ImageService** yang sudah ada di `app/Services/ImageService.php`
- [ ] **Update Formulir.php** untuk optimize foto upload
  ```php
  use App\Services\ImageService;

  public function uploadFoto()
  {
      $this->validate(['newFoto' => 'required|image|max:2048']);

      $imageService = app(ImageService::class);
      $path = $imageService->optimize($this->newFoto, [
          'width' => 800,
          'quality' => 85,
          'format' => 'webp'
      ]);

      $this->pendaftar->foto = $path;
      $this->pendaftar->save();
  }
  ```
- [ ] **Add WebP support** dengan fallback
- [ ] **Test image upload** verify compressed
- [ ] **Test page load** dengan optimized images
- **Estimated Time:** 3 hours (if refactoring needed)
- **Assigned To:** _____________
- **Status:** ☐ Not Started ☐ In Progress ☑ Completed

---

### Security (7 hours)

#### Issue #14: Implement reCAPTCHA v3
- [ ] **Register** di Google reCAPTCHA console
- [ ] **Install package** `composer require google/recaptcha`
- [ ] **Add to .env**
  ```
  RECAPTCHA_SITE_KEY=your_site_key
  RECAPTCHA_SECRET_KEY=your_secret_key
  ```
- [ ] **Update Login.php** `app/Livewire/Auth/Login.php`
  ```php
  use ReCaptcha\ReCaptcha;

  public function login()
  {
      $recaptcha = new ReCaptcha(config('services.recaptcha.secret'));
      $resp = $recaptcha->verify($this->recaptchaToken, request()->ip());

      if (!$resp->isSuccess()) {
          session()->flash('error', 'reCAPTCHA verification failed');
          return;
      }
      // ... rest of login logic
  }
  ```
- [ ] **Update view** add reCAPTCHA v3 script
- [ ] **Test login** with reCAPTCHA
- [ ] **Apply to AdminLogin** dan Register components
- **Estimated Time:** 3 hours
- **Assigned To:** _____________
- **Status:** ☐ Not Started ☐ In Progress ☑ Completed

---

#### Issue #15: Add Account Lockout Mechanism
- [ ] **Update Login.php**
  ```php
  use Illuminate\Support\Facades\RateLimiter;

  public function login()
  {
      $throttleKey = strtolower($this->email) . '|' . request()->ip();

      if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
          $seconds = RateLimiter::availableIn($throttleKey);
          session()->flash('error', "Too many attempts. Try again in {$seconds} seconds.");
          return;
      }

      // Attempt authentication
      if (Auth::attempt([...])) {
          RateLimiter::clear($throttleKey);
          // Success
      } else {
          RateLimiter::hit($throttleKey, 300); // 5 minutes
          // Fail
      }
  }
  ```
- [ ] **Test lockout** after 5 failed attempts
- [ ] **Verify countdown** shows remaining seconds
- [ ] **Test successful login** clears limiter
- [ ] **Apply to AdminLogin**
- **Estimated Time:** 2 hours
- **Assigned To:** _____________
- **Status:** ☐ Not Started ☐ In Progress ☑ Completed

---

#### Issue #16: Encrypt Sensitive Settings
- [ ] **Update Setting model** `app/Models/Setting.php`
  ```php
  use Illuminate\Support\Facades\Crypt;

  protected $casts = [
      'value' => 'encrypted', // For sensitive settings
  ];

  // Or selectively encrypt
  public static function setEncrypted($key, $value)
  {
      return self::set($key, Crypt::encryptString($value));
  }

  public static function getEncrypted($key)
  {
      $value = self::get($key);
      return $value ? Crypt::decryptString($value) : null;
  }
  ```
- [ ] **Update Settings.php** Livewire component
  ```php
  // When saving
  Setting::setEncrypted('whatsapp_api_key', $this->whatsapp_api_key);

  // When loading
  $this->whatsapp_api_key = Setting::getEncrypted('whatsapp_api_key');
  ```
- [ ] **Migrate existing sensitive data** encrypt current values
- [ ] **Test save & load** encrypted settings
- **Estimated Time:** 2 hours
- **Assigned To:** _____________
- **Status:** ☐ Not Started ☐ In Progress ☑ Completed

---

#### Issue #17: Enable SSL Verification
- [ ] **Update WhatsAppGatewayService.php** `app/Services/WhatsAppGatewayService.php:230`
  ```php
  // OLD: ->withOptions(['verify' => false])
  // NEW:
  ->withOptions(['verify' => true])
  ```
- [ ] **Test WhatsApp API calls** verify no SSL errors
- [ ] **If errors**, download CA certificate bundle
  ```php
  ->withOptions([
      'verify' => storage_path('cacert.pem')
  ])
  ```
- [ ] **Document SSL config** di README
- **Estimated Time:** 1 hour
- **Assigned To:** _____________
- **Status:** ☐ Not Started ☐ In Progress ☑ Completed

---

## 🟡 PHASE 3: MEDIUM PRIORITY (Week 3-4, ~18 hours)

**Deadline:** 13 Februari 2026 (1 month)
**Priority:** MEDIUM - Code Quality & Cleanup

### Code Quality (10 hours)

#### Issue #18: Resolve Migration Conflicts
- [ ] **Backup database** `pg_dump postgresppdb > backup_before_migration_cleanup.sql`
- [ ] **Identify which region system is used**
  ```sql
  -- Check which tables exist
  SELECT tablename FROM pg_tables WHERE schemaname = 'public' AND tablename LIKE '%province%';
  ```
- [ ] **Decision: Use IndoRegion (azishapidin/indoregion)** - Recommended
- [ ] **Delete unused migrations**
  ```bash
  # If using IndoRegion, delete:
  rm database/migrations/2016_08_03_072729_create_provinces_table.php
  rm database/migrations/2016_08_03_072750_create_cities_table.php
  rm database/migrations/2016_08_03_072804_create_districts_table.php
  rm database/migrations/2016_08_03_072819_create_villages_table.php
  rm database/migrations/2025_03_05_000001_create_indonesia_regions_table.php
  ```
- [ ] **Verify Models** use correct table names
- [ ] **Test forms** dengan dropdown provinsi/kota/kecamatan
- [ ] **Document decision** di README
- **Estimated Time:** 3 hours
- **Assigned To:** _____________
- **Status:** ☐ Not Started ☐ In Progress ☑ Completed

---

#### Issue #19: Cleanup Unused Files
- [ ] **Delete unused Livewire components**
  ```bash
  rm app/Livewire/Welcome.php
  rm resources/views/livewire/welcome.blade.php
  ```
- [ ] **Delete unused controllers**
  ```bash
  rm app/Http/Controllers/FaqController.php
  ```
- [ ] **Delete empty trait**
  ```bash
  rm app/WithSorting.php
  ```
- [ ] **Move utility files**
  ```bash
  mv *.py scripts/
  mv check_*.php scripts/
  mv debug_*.php scripts/
  mv show_*.php scripts/
  mv test-*.php scripts/
  mv inject_*.* scripts/
  mv transform_*.php scripts/
  ```
- [ ] **Delete empty directories**
  ```bash
  rmdir appExports appLivewire resourcesviewserrors resourcesviewsexports resourcesviewslivewire
  ```
- [ ] **Delete typo files**
  ```bash
  rm nul
  rm resources/views/nul
  ```
- [ ] **Run composer dump-autoload** `composer dump-autoload -o`
- [ ] **Test aplikasi** verify no broken references
- **Estimated Time:** 2 hours
- **Assigned To:** _____________
- **Status:** ☐ Not Started ☐ In Progress ☑ Completed

---

#### Issue #20: Fix Enum Inconsistencies
- [ ] **Standardize dokumen.jenis_dokumen values**
  ```sql
  UPDATE dokumen SET jenis_dokumen = 'kartu_keluarga' WHERE jenis_dokumen = 'kk';
  UPDATE dokumen SET jenis_dokumen = 'rapor' WHERE jenis_dokumen = 'raport';
  ```
- [ ] **Update CHECK constraint** di migration
- [ ] **Update views/forms** gunakan standardized values
- [ ] **Test dokumen upload** dengan berbagai jenis
- **Estimated Time:** 1 hour
- **Assigned To:** _____________
- **Status:** ☐ Not Started ☐ In Progress ☑ Completed

---

#### Issue #21: Refactor Large Blade File (OPTIONAL)
- [ ] **Create address form component**
  ```bash
  php artisan make:component Forms/AddressSection
  ```
- [ ] **Extract repetitive code**
  ```blade
  <x-forms.address-section
      :prefix="'ayah'"
      :title="'Alamat Ayah Kandung'"
      :data="$pendaftar"
      color="blue"
  />
  ```
- [ ] **Replace in tab-data-alamat.blade.php**
- [ ] **Test form** verify all fields work
- [ ] **Verify data saving** correctly
- **Estimated Time:** 4 hours
- **Assigned To:** _____________
- **Status:** ☐ Not Started ☐ In Progress ☑ Completed

---

### Configuration (8 hours)

#### Issue #22: Disable Debug Mode for Production
- [ ] **Update .env** untuk production
  ```
  APP_ENV=production
  APP_DEBUG=false
  LOG_LEVEL=error
  ```
- [ ] **Create custom error pages**
  ```bash
  php artisan vendor:publish --tag=laravel-errors
  ```
- [ ] **Customize 404, 500 pages** di `resources/views/errors/`
- [ ] **Test error pages** in production mode
- [ ] **Setup error monitoring** (Sentry, Bugsnag)
- **Estimated Time:** 2 hours (if creating custom error pages)
- **Assigned To:** _____________
- **Status:** ☐ Not Started ☐ In Progress ☑ Completed

---

#### Issue #23: Setup Redis for Production
- [ ] **Install Redis** di server
  ```bash
  # Ubuntu
  sudo apt install redis-server
  sudo systemctl enable redis-server
  ```
- [ ] **Install PHP Redis extension**
  ```bash
  sudo pecl install redis
  ```
- [ ] **Update .env**
  ```
  CACHE_STORE=redis
  QUEUE_CONNECTION=redis
  SESSION_DRIVER=redis

  REDIS_HOST=127.0.0.1
  REDIS_PASSWORD=null
  REDIS_PORT=6379
  ```
- [ ] **Test Redis connection** `php artisan tinker` → `Cache::put('test', 'value')`
- [ ] **Migrate cache** from database to Redis
- [ ] **Monitor Redis** with redis-cli
- [ ] **Setup Redis persistence** (RDB/AOF)
- **Estimated Time:** 3 hours
- **Assigned To:** _____________
- **Status:** ☐ Not Started ☐ In Progress ☑ Completed

---

#### Issue #24: Implement Asset Optimization
- [ ] **Update vite.config.js**
  ```javascript
  export default defineConfig({
      build: {
          minify: 'terser',
          terserOptions: {
              compress: {
                  drop_console: true
              }
          },
          rollupOptions: {
              output: {
                  manualChunks: {
                      'vendor': ['livewire'],
                      'alpine': ['alpinejs']
                  }
              }
          }
      },
      plugins: [
          laravel({
              input: ['resources/css/app.css', 'resources/js/app.js'],
              refresh: true
          })
      ]
  });
  ```
- [ ] **Build production assets** `npm run build`
- [ ] **Verify minification** check file sizes
- [ ] **Test code splitting** verify chunks loaded
- [ ] **Monitor page load** with lighthouse
- **Estimated Time:** 2 hours
- **Assigned To:** _____________
- **Status:** ☐ Not Started ☐ In Progress ☑ Completed

---

## ⚪ PHASE 4: LOW PRIORITY (Ongoing, ~8 hours)

**Deadline:** Ongoing maintenance
**Priority:** LOW - Best Practices & Improvements

#### Issue #25: Remove 'role' from User $fillable
- [ ] **Update User model** `app/Models/User.php:20-26`
  ```php
  protected $fillable = [
      'name',
      'email',
      'password',
      // REMOVED: 'role', 'aktif'
  ];

  protected $guarded = [
      'role',
      'aktif',
  ];
  ```
- [ ] **Update registration logic** explicit role assignment
- [ ] **Test registration** verify role not mass-assignable
- **Estimated Time:** 1 hour

---

#### Issue #26: Add wire:loading Indicators
- [ ] **Add to search forms**
  ```blade
  <input wire:model.live="search" ...>
  <span wire:loading wire:target="search">Searching...</span>
  ```
- [ ] **Add to submit buttons**
  ```blade
  <button wire:click="save" wire:loading.attr="disabled">
      <span wire:loading.remove>Save</span>
      <span wire:loading>Saving...</span>
  </button>
  ```
- [ ] **Add global loading** di layout
- **Estimated Time:** 2 hours

---

#### Issue #27: Implement Search Debouncing
- [ ] **Update search inputs**
  ```blade
  <input wire:model.live.debounce.500ms="search">
  ```
- [ ] **Test typing** verify not querying on every keystroke
- **Estimated Time:** 1 hour

---

#### Issue #28-30: Miscellaneous
- [ ] **Enable session encryption** `.env`: `SESSION_ENCRYPT=true`
- [ ] **Review commented code** di 10 files, hapus atau uncomment
- [ ] **Setup security scanning** `composer require --dev enlightn/enlightn`
- **Estimated Time:** 4 hours total

---

## 📊 PROGRESS TRACKING

### Overall Progress
```
Phase 1 (Critical):  ☐☐☐☐☐☐☐ 0/7 (0%)
Phase 2 (High):      ☐☐☐☐☐☐☐☐☐☐ 0/10 (0%)
Phase 3 (Medium):    ☐☐☐☐☐☐☐ 0/7 (0%)
Phase 4 (Low):       ☐☐☐☐☐☐ 0/6 (0%)

TOTAL: 0/30 issues resolved (0%)
```

### Time Tracking
```
Estimated Total: 56 hours
Time Spent: 0 hours
Remaining: 56 hours
```

### Team Assignment
```
Developer 1: _____________ (Security issues)
Developer 2: _____________ (Performance issues)
Developer 3: _____________ (Code cleanup)
DevOps: _____________ (Configuration & deployment)
```

---

## ✅ VERIFICATION & TESTING

Setelah selesai setiap phase, jalankan:

### Security Verification
```bash
# After Phase 1
□ composer audit
□ npm audit
□ Test role-based access control
□ Test file download authorization
□ Verify no duplicate emails
```

### Performance Verification
```bash
# After Phase 2
□ Run Laravel Debugbar di development
□ Check query count per page (target < 20)
□ Measure page load times (target < 2s)
□ Verify no N+1 queries
□ Test with 1000+ pendaftar records
```

### Code Quality Verification
```bash
# After Phase 3
□ php artisan pint (code style)
□ php artisan test (run tests)
□ Check for unused files (manually)
□ Review migration list (no duplicates)
```

### Production Readiness
```bash
# Before deployment
□ APP_DEBUG=false
□ All critical issues resolved
□ Backup created
□ Rollback plan ready
□ Monitoring setup
```

---

## 🚀 DEPLOYMENT PLAN

### Pre-Deployment Checklist
- [ ] All Phase 1 (Critical) issues resolved
- [ ] All Phase 2 (High) issues resolved
- [ ] Database backup created
- [ ] .env configured for production
- [ ] Redis installed dan configured (if applicable)
- [ ] Queue workers configured
- [ ] Cron jobs setup untuk scheduler
- [ ] Monitoring setup (Telescope, logging)

### Deployment Steps
1. [ ] Enable maintenance mode `php artisan down`
2. [ ] Pull latest code `git pull`
3. [ ] Install dependencies `composer install --no-dev`
4. [ ] Run migrations `php artisan migrate --force`
5. [ ] Clear caches `php artisan optimize`
6. [ ] Rebuild assets `npm run build`
7. [ ] Restart queue workers
8. [ ] Test critical paths
9. [ ] Disable maintenance mode `php artisan up`
10. [ ] Monitor logs untuk errors

### Rollback Plan
```bash
# If issues occur
php artisan down
git reset --hard [previous-commit]
composer install --no-dev
php artisan migrate:rollback --step=X
php artisan optimize
php artisan up
# Restore database dari backup if needed
```

---

**Document Status:** ACTIVE
**Last Updated:** 2026-01-16
**Next Review:** After each phase completion

**Progress Updates:**
- 2026-01-16: Action plan created
- _____________: Phase 1 started
- _____________: Phase 1 completed
- _____________: Phase 2 started
- _____________: Phase 2 completed
- _____________: Phase 3 started
- _____________: Phase 3 completed
- _____________: All issues resolved

---

**Catatan:** Update dokumen ini setelah setiap issue resolved. Check boxes dan track progress untuk memastikan semua issues ditangani.
