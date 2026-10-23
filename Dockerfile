# syntax=docker/dockerfile:1
#
# Image produksi aplikasi PMBM untuk Coolify (atau Docker biasa).
# Satu image dipakai untuk 3 peran (lihat docker-compose.yml):
#   app       : Nginx + PHP-FPM (port 8080), menjalankan migrasi & cache saat start
#   worker    : php artisan queue:work   (notifikasi/antrian)
#   scheduler : php artisan schedule:work (refresh dashboard, antrian WhatsApp)
#
# Basis: serversideup/php (Debian, berjalan sebagai www-data, bukan root).

ARG PHP_VERSION=8.4

############################################
# Basis: ekstensi PHP + konfigurasi keamanan
############################################
FROM serversideup/php:${PHP_VERSION}-fpm-nginx AS base

USER root

# gd     : captcha, PDF (dompdf), gambar (intervention/image), Excel
# intl   : format tanggal/angka
# exif   : orientasi foto upload
# bcmath : perhitungan angka besar (Excel)
# postgresql-client : pg_dump/psql untuk menu Backup & Restore
RUN install-php-extensions gd intl exif bcmath \
    && apt-get update \
    && apt-get install -y --no-install-recommends postgresql-client \
    && rm -rf /var/lib/apt/lists/*

# Mitigasi PHP2Shell versi Nginx/PHP (Nginx tidak membaca public/.htaccess & .user.ini)
COPY docker/php/zz-ppdb.ini /usr/local/etc/php/conf.d/zz-ppdb.ini
COPY docker/nginx/ppdb-http.conf /etc/nginx/conf.d/ppdb-http.conf
COPY docker/nginx/ppdb-server.conf /etc/nginx/server-opts.d/ppdb-server.conf
RUN echo 'fastcgi_param HTTP_COOKIE $ppdb_cookie if_not_empty;' >> /etc/nginx/fastcgi_params \
    && echo 'fastcgi_param PPDB_UPLOAD_GUARD nginx;' >> /etc/nginx/fastcgi_params

############################################
# Dependensi Composer (tanpa paket dev)
############################################
FROM base AS vendor

WORKDIR /var/www/html
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-progress --no-scripts --no-autoloader --prefer-dist

############################################
# Build CSS Tailwind (pengganti "npm run build:css" manual)
############################################
FROM node:22-alpine AS assets

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund
COPY vite.config.js postcss.config.js tailwind.config.js ./
COPY scripts/build-production-css.mjs ./scripts/
COPY app ./app
COPY resources ./resources
RUN mkdir -p public/css && npm run build:css

############################################
# Image akhir
############################################
FROM base AS production

ENV PHP_OPCACHE_ENABLE=1 \
    PHP_DATE_TIMEZONE=Asia/Jakarta \
    PHP_MEMORY_LIMIT=512M \
    PHP_MAX_EXECUTION_TIME=300 \
    PHP_UPLOAD_MAX_FILE_SIZE=50M \
    PHP_POST_MAX_SIZE=55M \
    NGINX_CLIENT_MAX_BODY_SIZE=55M \
    # IPv4 saja: sebagian VPS mematikan IPv6 sehingga Nginx gagal start; proxy Coolify memakai IPv4
    NGINX_LISTEN_IP_PROTOCOL=ipv4

WORKDIR /var/www/html

COPY --chown=www-data:www-data --from=vendor /var/www/html/vendor ./vendor
COPY --chown=www-data:www-data . .
COPY --chown=www-data:www-data --from=assets /app/public/css/app-production-full.css ./public/css/app-production-full.css

RUN composer dump-autoload --optimize --no-dev --no-interaction \
    # Link upload publik: public/storagepmbm -> storage/app/public (lihat config/filesystems.php)
    && rm -rf public/storagepmbm \
    && ln -s ../storage/app/public public/storagepmbm \
    && mkdir -p storage/app/public storage/app/private storage/logs \
        storage/framework/cache/data storage/framework/sessions storage/framework/views bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache public/storagepmbm

USER www-data

# package:discover butuh vendor lengkap; dijalankan sebagai www-data agar cache-nya bisa ditulis
RUN php artisan package:discover --ansi
