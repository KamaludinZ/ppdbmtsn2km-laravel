# Deploy ke Coolify (Docker Compose)

Panduan memindahkan aplikasi PMBM dari cPanel ke server Coolify, lalu cara update ke depannya.

## Gambaran singkat

`docker-compose.yml` menjalankan 4 container dari repository ini:

| Layanan | Isi | Keterangan |
|---|---|---|
| `app` | Nginx + PHP-FPM 8.4 (port 8080) | Halaman web. Saat start otomatis: migrasi database + cache config/route/view |
| `worker` | `php artisan queue:work` | Mengirim notifikasi di antrian |
| `scheduler` | `php artisan schedule:work` | Refresh dashboard & antrian WhatsApp tiap menit |
| `postgres` | PostgreSQL 17 | Database |

Data permanen disimpan di **volume** dan **tidak hilang saat update/redeploy**:

| Volume | Isi |
|---|---|
| `pmbm-storage` | Upload pendaftar, dokumen, file backup (`storage/app`) |
| `pmbm-logs` | Log aplikasi (`storage/logs`), terbaca di menu Log Monitoring |
| `pmbm-db` | Database |

CSS Tailwind dibangun otomatis saat build image, jadi `npm run build:css` tidak perlu lagi untuk server Coolify.

## 1. Siapkan nilai rahasia

Siapkan 3 nilai ini (simpan di password manager, **jangan** di-commit ke Git):

1. **APP_KEY**: pakai `APP_KEY` dari `.env` di cPanel supaya sama dengan sebelumnya.
   Untuk aplikasi baru, buat dengan: `php artisan key:generate --show`
   (atau `echo "base64:$(openssl rand -base64 32)"`).
   Setelah dipakai, **jangan diganti-ganti**: semua pengguna akan ter-logout.
2. **DB_PASSWORD**: password baru yang acak dan panjang, misalnya hasil `openssl rand -base64 24`.
3. **APP_URL**: alamat lengkap, misalnya `https://pmbm.mtsn2kotamalang.sch.id`.

## 2. Buat aplikasi di Coolify

1. **Projects → (pilih project) → + New → Private Repository (with GitHub App)**, pilih repository ini dan branch `main`.
2. **Build Pack: Docker Compose**, **Docker Compose Location: `/docker-compose.yml`**.
3. Tab **Environment Variables**, isi minimal:
   - `APP_KEY`, `APP_URL`, `DB_PASSWORD` (wajib, deploy gagal bila kosong)
   - Opsional: `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`,
     `WHATSAPP_GATEWAY_URL`, `WHATSAPP_ACCESS_TOKEN`, `WHATSAPP_SENDER_NUMBER`
4. Pada layanan **app**, isi **Domains** dengan domain + port container, misalnya
   `https://pmbm.mtsn2kotamalang.sch.id:8080`. Coolify mengurus HTTPS (Let's Encrypt) otomatis.
   Layanan `worker`, `scheduler`, `postgres` **tidak** diberi domain.
5. Arahkan DNS domain (record A) ke IP server Coolify.
6. Klik **Deploy**. Build pertama sekitar 3–6 menit.

Setelah selesai, buka domain. Database masih kosong (tabel sudah dibuat oleh migrasi).
Lanjutkan ke langkah 3 untuk memindahkan data dari cPanel.

> Jangan menjalankan `db:seed` di production: seeder membuat akun uji dengan password `password`.

## 3. Pindahkan data dari cPanel

### a. Database

Di cPanel (Terminal atau SSH), buat dump **tanpa owner/privilege** supaya bisa dipulihkan ke user baru:

```bash
pg_dump -h localhost -U <user_db_cpanel> -d <nama_db_cpanel> --no-owner --no-privileges -f pmbm.sql
```

Unduh `pmbm.sql`, lalu upload ke server Coolify (misalnya lewat `scp`). Di server Coolify (SSH):

```bash
# cari nama container postgres & app milik aplikasi ini
docker ps --format '{{.Names}}' | grep -E 'postgres|app'

PG=<nama-container-postgres>
docker cp pmbm.sql $PG:/tmp/pmbm.sql
# kosongkan tabel hasil migrasi awal, lalu pulihkan data lama
docker exec -i $PG psql -U pmbm -d pmbm -c "DROP SCHEMA public CASCADE; CREATE SCHEMA public;"
docker exec -i $PG psql -U pmbm -d pmbm -f /tmp/pmbm.sql
docker exec -i $PG rm /tmp/pmbm.sql
```

Lalu di Coolify klik **Restart**. Migrasi yang belum ada di data lama akan dijalankan otomatis.

### b. File upload

Di cPanel, kompres folder `storage/app` (berisi `private/`, `public/`, dll.) menjadi `storage-app.zip`.
Jika folder `public/storagepmbm` di cPanel berisi file sungguhan (bukan symlink), ikutkan juga isinya ke `storage/app/public`.

Di server Coolify:

```bash
APP=<nama-container-app>
unzip storage-app.zip -d storage-app
docker cp storage-app/. $APP:/var/www/html/storage/app/
docker exec -u root $APP chown -R www-data:www-data /var/www/html/storage/app
```

### c. Periksa

- Login admin, buka beberapa detail pendaftar dan pastikan dokumen bisa dibuka.
- **Admin → Keamanan Sistem**: semua pemeriksaan "Aman" (versi PHP, upload progress Off, pembatas upload Nginx).
- Setelah semua cocok, ganti DNS ke server baru dan matikan aplikasi lama di cPanel.

## 4. Update & redeploy ke depannya

1. Commit & push perubahan ke branch `main` di GitHub.
2. Coolify membangun image baru dan mengganti container.
   Aktifkan **Auto Deploy** di Coolify supaya otomatis setiap push, atau klik **Redeploy** manual.
3. Saat container `app` baru start, migrasi database dijalankan otomatis (`php artisan migrate --force`).

Yang **aman** dan tidak hilang saat redeploy: database, file upload, log, dan sesi login (sesi disimpan di database).

Hal yang perlu diingat:

- **Jangan** mengubah `APP_KEY`, `DB_PASSWORD`, `DB_DATABASE`, atau `DB_USERNAME` setelah deploy pertama.
  Password database tersimpan di volume saat pertama dibuat; mengganti `DB_PASSWORD` di Coolify tidak mengubah password di database.
- Saat menghapus resource di Coolify, **jangan centang "Delete volumes"** kecuali memang ingin menghapus semua data.
- Sebelum update besar (ada migrasi baru), buat backup dulu (langkah 5). Rollback image di Coolify tidak membatalkan migrasi database.
- Bila menambah form upload biasa (bukan Livewire), tambahkan URL-nya di `docker/nginx/ppdb-server.conf` dan `public/.htaccess`.

## 5. Backup

- **Menu Admin → Backup & Restore** di aplikasi (memakai `pg_dump` yang sudah ada di image). Unduh hasilnya ke komputer secara rutin.
- Manual dari server Coolify:

  ```bash
  docker exec <nama-container-postgres> pg_dump -U pmbm -d pmbm --no-owner > pmbm-$(date +%F).sql
  ```

## 6. Keamanan yang sudah terpasang di image

- PHP 8.4 dari image `serversideup/php`, berjalan sebagai user `www-data` (bukan root).
- Mitigasi PHP2Shell (CVE-2026-109618/109619), padanan dari `public/.htaccess`:
  `session.upload_progress.enabled = Off`, `multipart/form-data` hanya ke endpoint upload Livewire,
  cookie `PHPSESSID` dibuang sebelum sampai ke PHP, dan header `X-Powered-By` disembunyikan.
- Hanya `public/index.php` yang bisa dijalankan sebagai PHP; file tersembunyi (`.env`, `.htaccess`) dan PHP di folder upload diblokir.
- Untuk update keamanan PHP, cukup **Redeploy**: image dasar `serversideup/php:8.4-fpm-nginx` ikut diperbarui saat build.

## 7. Jika ada masalah

| Gejala | Penyebab umum | Solusi |
|---|---|---|
| Deploy gagal "APP_KEY / DB_PASSWORD wajib diisi" | Variabel belum diisi | Isi di tab Environment Variables |
| Error 500 setelah deploy | Lihat log | Coolify → layanan `app` → Logs, atau menu Log Monitoring |
| Tidak bisa login, kembali ke halaman login | Domain belum HTTPS, cookie sesi `secure` | Pastikan domain memakai `https://` |
| `worker`/`scheduler` unhealthy | Database belum siap / error kode | Lihat Logs layanan tersebut |
| Gambar/dokumen lama tidak muncul | File upload belum dipindah | Ulangi langkah 3b |

## Menjalankan di komputer sendiri (opsional)

Butuh Docker Desktop. Buat file `.env` berisi `APP_KEY`, `APP_URL=http://localhost:8080`, `DB_PASSWORD`, dan
`SESSION_SECURE_COOKIE=false`, lalu tambahkan `ports: ["8080:8080"]` pada layanan `app` dan jalankan:

```bash
docker compose up -d --build
```
