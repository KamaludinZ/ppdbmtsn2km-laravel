Implementasi Tanpa Vite di Production - Panduan Lengkap

  Berdasarkan analisa aplikasi, berikut panduan implementasi aplikasi ini di production TANPA Vite atau Node.js:    

  1. Konsep Dasar

  Situasi Saat Ini:

  - Aplikasi menggunakan Vite untuk build assets (CSS/JS)
  - Ada 6 blade templates masih menggunakan @vite directive
  - NAMUN: Assets sudah di-compile di public/build/
  - Main layout (app.blade.php) sudah menggunakan fallback PHP

  Assets Yang Sudah Ter-compile:

  public/build/
  ├── manifest.json (458 bytes)
  └── assets/
      ├── app-1aCOSxfN.css (115KB) → Tailwind CSS
      ├── app-KNSYXkWY.js (38KB)   → Main JavaScript
      └── alpine-BGGahNR6.js (43KB) → Alpine.js

  2. Metode Implementasi Tanpa Vite

  Metode A: Gunakan Pre-compiled Assets (RECOMMENDED)

  Langkah-langkah:

  1. Build assets di development (lokal):
  # Di komputer development yang ada Node.js
  npm install
  npm run build

  2. Upload hasil build ke production:
  Upload folder ini ke production:
  └── public/build/  → Upload seluruh isinya
      ├── manifest.json
      └── assets/

  3. Fix Blade templates yang masih pakai @vite:

  Ganti semua @vite directive dengan PHP fallback. Contoh untuk resources/views/layouts/guest.blade.php:

  <!-- SEBELUM (menggunakan Vite): -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- SESUDAH (tanpa Vite): -->
  @php
      $manifestPath = public_path('build/manifest.json');
      $cssPath = 'build/assets/app-1aCOSxfN.css'; // fallback
      $jsPath = 'build/assets/app-KNSYXkWY.js';   // fallback

      if (file_exists($manifestPath)) {
          $manifest = json_decode(file_get_contents($manifestPath), true);
          if (isset($manifest['resources/css/app.css']['file'])) {
              $cssPath = 'build/' . $manifest['resources/css/app.css']['file'];
          }
          if (isset($manifest['resources/js/app.js']['file'])) {
              $jsPath = 'build/' . $manifest['resources/js/app.js']['file'];
          }
      }
  @endphp
  <link rel="stylesheet" href="{{ asset($cssPath) }}">
  <script type="module" src="{{ asset($jsPath) }}"></script>

  4. Files yang perlu di-update:
  - resources/views/layouts/guest.blade.php
  - resources/views/errors/404.blade.php
  - resources/views/errors/403.blade.php
  - resources/views/errors/500.blade.php
  - resources/views/errors/general.blade.php
  - resources/views/maintenance.blade.php

  Metode B: Gunakan CDN (Alternative - Tanpa Build Process)

  Jika tidak bisa build sama sekali, gunakan CDN untuk Tailwind dan Alpine:

  <!-- Di head section -->
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
      /* Paste custom CSS dari resources/css/app.css */
      .btn { @apply px-4 py-2 rounded-lg... }
      /* dst... */
  </style>

  <!-- Sebelum closing body -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  Kekurangan CDN:
  - Performa lebih lambat
  - Dependency pada CDN external
  - Tidak optimal untuk production

  3. Script Deployment untuk Production

  Saya buatkan script helper untuk deploy tanpa Vite:

  deploy-without-vite.php

  <?php
  /**
   * Deploy Helper untuk Production Tanpa Vite
   * Jalankan: php deploy-without-vite.php
   */

  echo "=== Deploy Without Vite Helper ===\n\n";

  // 1. Check if build assets exist
  if (!file_exists('public/build/manifest.json')) {
      echo "❌ ERROR: Build assets tidak ditemukan!\n";
      echo "   Jalankan 'npm run build' di development dulu.\n";
      exit(1);
  }

  echo "✅ Build assets ditemukan\n";

  // 2. Fix Blade templates
  $templates = [
      'resources/views/layouts/guest.blade.php',
      'resources/views/errors/404.blade.php',
      'resources/views/errors/403.blade.php',
      'resources/views/errors/500.blade.php',
      'resources/views/errors/general.blade.php',
      'resources/views/maintenance.blade.php'
  ];

  $replacementCode = <<<'PHP'
  @php
      $manifestPath = public_path('build/manifest.json');
      $cssPath = 'build/assets/app-1aCOSxfN.css';
      $jsPath = 'build/assets/app-KNSYXkWY.js';

      if (file_exists($manifestPath)) {
          $manifest = json_decode(file_get_contents($manifestPath), true);
          if (isset($manifest['resources/css/app.css']['file'])) {
              $cssPath = 'build/' . $manifest['resources/css/app.css']['file'];
          }
          if (isset($manifest['resources/js/app.js']['file'])) {
              $jsPath = 'build/' . $manifest['resources/js/app.js']['file'];
          }
      }
  @endphp
  <link rel="stylesheet" href="{{ asset($cssPath) }}">
  <script type="module" src="{{ asset($jsPath) }}"></script>
  PHP;

  foreach ($templates as $template) {
      if (file_exists($template)) {
          $content = file_get_contents($template);

          // Replace @vite directive
          $pattern = '/@vite\(\[.*?\]\)/s';
          if (preg_match($pattern, $content)) {
              $newContent = preg_replace($pattern, $replacementCode, $content);
              file_put_contents($template, $newContent);
              echo "✅ Fixed: $template\n";
          } else {
              echo "⏭️  Skip: $template (no @vite found)\n";
          }
      }
  }

  // 3. Clear caches
  echo "\n=== Clearing Caches ===\n";
  system('php artisan view:clear');
  system('php artisan config:clear');
  system('php artisan route:clear');

  echo "\n✅ Deployment preparation complete!\n";
  echo "Upload folder berikut ke production:\n";
  echo "  - app/\n";
  echo "  - bootstrap/\n";
  echo "  - config/\n";
  echo "  - database/\n";
  echo "  - public/ (termasuk public/build/)\n";
  echo "  - resources/\n";
  echo "  - routes/\n";
  echo "  - storage/ (create new with proper permissions)\n";
  echo "  - .env (configure for production)\n";
  echo "  - composer.json & composer.lock\n";
  echo "  - artisan\n";

  4. Checklist Deployment Production Tanpa Vite

  Pre-deployment (Local):

  ✅ npm install && npm run build (di local)
  ✅ php deploy-without-vite.php (fix blade templates)
  ✅ Test di local dengan APP_ENV=production

  Upload ke Production:

  # Files yang HARUS di-upload:
  ✅ app/
  ✅ bootstrap/
  ✅ config/
  ✅ database/
  ✅ public/ (TERMASUK public/build/)
  ✅ resources/
  ✅ routes/
  ✅ composer.json & composer.lock
  ✅ artisan
  ✅ .env.production (rename jadi .env)

  # Files yang TIDAK perlu:
  ❌ node_modules/
  ❌ package.json
  ❌ package-lock.json
  ❌ vite.config.js
  ❌ tailwind.config.js
  ❌ .git/
  ❌ tests/

  Di Production Server:

  # 1. Install PHP dependencies (tanpa dev)
  composer install --optimize-autoloader --no-dev

  # 2. Setup environment
  cp .env.production .env
  php artisan key:generate

  # 3. Run migrations
  php artisan migrate --force

  # 4. Create storage symlink
  php artisan storage:link

  # 5. Set permissions
  chmod -R 775 storage/
  chmod -R 775 bootstrap/cache/

  # 6. Optimize
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache

  # 7. Test
  php artisan --version

  5. Troubleshooting

  Error: Vite manifest not found

  Solusi: Pastikan folder public/build/ sudah di-upload dengan isinya

  Error: @vite is not defined

  Solusi: Jalankan script deploy-without-vite.php untuk fix blade templates

  CSS/JS tidak load

  Solusi: Check path di public/build/manifest.json dan sesuaikan fallback

  Style berantakan

  Solusi: Clear browser cache, pastikan file CSS ter-load dengan benar

  6. Konfigurasi Web Server

  Apache .htaccess:

  <IfModule mod_rewrite.c>
      RewriteEngine On
      RewriteRule ^(.*)$ public/$1 [L]
  </IfModule>

  Nginx:

  location / {
      try_files $uri $uri/ /index.php?$query_string;
  }

  location ~ \.php$ {
      fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
      fastcgi_index index.php;
      include fastcgi_params;
  }

  7. Ukuran Final Production

  Estimasi ukuran deployment:
  - Source code: ~50MB
  - Vendor (Composer): 95MB (actual)
  - Public assets: ~200KB
  - Total: ~150MB (tanpa user uploads)

  Kesimpulan

  Aplikasi ini 100% bisa berjalan tanpa Vite di production karena:
  1. Assets sudah pre-compiled di public/build/
  2. Laravel mendukung static asset loading
  3. Main layout sudah punya fallback mechanism

  Yang perlu dilakukan:
  1. Build assets sekali di development
  2. Fix 6 blade templates yang masih pakai @vite
  3. Upload hasil build ke production
  4. Tidak perlu Node.js/NPM di production server

  Metode ini cocok untuk shared hosting yang tidak support Node.js!