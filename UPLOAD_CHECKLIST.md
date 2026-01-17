# ✅ CHECKLIST UPLOAD SOURCE CODE KE PRODUCTION

## 📋 PERSIAPAN (Sudah Selesai)
- [x] Jalankan `php prepare_for_production.php`
- [x] Semua @vite directive sudah di-fix
- [x] File CSS/JS production sudah di-generate
- [x] .env.production sudah dibuat
- [x] .htaccess sudah disiapkan
- [x] Cache sudah di-clear

## 📦 YANG HARUS DILAKUKAN SEKARANG

### 1️⃣ Install Composer Dependencies
```bash
composer install --no-dev --optimize-autoloader
```

### 2️⃣ Edit .env.production
Isi dengan data database Anda:
```env
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=[nama_database_anda]
DB_USERNAME=[username_database_anda]
DB_PASSWORD=[password_database_anda]
```

## 📤 PROSES UPLOAD

### 3️⃣ Login ke cPanel
- URL: https://cpanel.mtsn2kotamalang.sch.id (atau sesuai hosting)
- Username: [username cpanel]
- Password: [password cpanel]

### 4️⃣ Backup Data Lama (Jika Ada)
- File Manager → Navigate ke folder subdomain
- Select All → Compress → Download
- Hapus folder lama

### 5️⃣ Upload Files via File Manager

**FOLDER YANG DI-UPLOAD:**
```
✅ app/
✅ bootstrap/
✅ config/
✅ database/
✅ lang/
✅ public/        ← PASTIKAN ada css/app.css dan js/app.js
✅ resources/
✅ routes/
✅ storage/
✅ vendor/
```

**FILE DI ROOT:**
```
✅ artisan
✅ composer.json
✅ composer.lock
✅ .env.production → rename jadi .env
```

**JANGAN UPLOAD:**
```
❌ node_modules/
❌ .git/
❌ tests/
❌ .env (local)
❌ package.json
❌ vite.config.js
❌ *.log
```

### 6️⃣ Set Document Root
- cPanel → Domains → Subdomains
- Edit `pmbm.mtsn2kotamalang.sch.id`
- Document Root: `/home/[username]/pmbm.mtsn2kotamalang.sch.id/public`
- Save

### 7️⃣ Rename .env File
- File Manager → Rename `.env.production` menjadi `.env`

### 8️⃣ Set Permissions
Klik kanan folder → Change Permissions → 755:
- `storage/` dan semua subfolder
- `bootstrap/cache/`

## ⚙️ KONFIGURASI DI SERVER

### 9️⃣ Via Terminal cPanel (atau SSH)

```bash
# Navigate ke folder aplikasi
cd /home/[username]/pmbm.mtsn2kotamalang.sch.id

# Generate key
php artisan key:generate

# Clear cache
php artisan optimize:clear

# Cache config
php artisan optimize

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link
```

### 🔟 Setup Cron Job
cPanel → Cron Jobs → Add:
```
* * * * * cd /home/[username]/pmbm.mtsn2kotamalang.sch.id && php artisan schedule:run >> /dev/null 2>&1
```

## ✅ VERIFIKASI

### Test Website
- [ ] Buka https://pmbm.mtsn2kotamalang.sch.id
- [ ] Cek tampilan (CSS loaded)
- [ ] Cek interaksi (JS loaded)
- [ ] Test login
- [ ] Test upload file

### Jika Error 500
1. Check error log: `storage/logs/laravel.log`
2. Temporary: Edit .env → `APP_DEBUG=true`
3. Lihat error, fix, kembalikan `APP_DEBUG=false`

## 🎯 FINAL CHECK

- [ ] Website bisa diakses
- [ ] Login berfungsi
- [ ] Database connected
- [ ] Upload file bisa
- [ ] Tidak ada error
- [ ] APP_DEBUG=false
- [ ] Hapus file instalasi/script

## 📞 JIKA ADA MASALAH

1. **Error 500**: Check permissions dan .env
2. **CSS/JS tidak load**: Pastikan ada di public/css/ dan public/js/
3. **Database error**: Check credentials di .env
4. **Permission denied**: chmod 755 untuk storage

---

**PENTING:**
- Production TIDAK perlu Node.js/NPM
- Document root HARUS ke folder `/public`
- Selalu backup sebelum update

---

✅ **Source code sudah siap di-upload!**
- Semua @vite sudah di-fix
- CSS/JS production sudah ada
- Tinggal ikuti checklist di atas