# Panduan Mode Maintenance - PPDB MTsN 2 Kota Malang

## Masalah yang Diperbaiki

### Masalah Sebelumnya:
- Halaman maintenance (503.blade.php) mencoba memuat asset lokal (Livewire, CSS, JS)
- Asset lokal juga ter-block oleh mode maintenance, menyebabkan error 503
- Halaman maintenance tidak bisa render dengan benar
- Console menunjukkan error: `Failed to load resource: 503`

### Solusi:
- Halaman 503.blade.php sekarang menggunakan **CDN** untuk Tailwind CSS
- Tidak ada dependensi ke asset lokal yang ter-block
- Halaman standalone yang bisa render tanpa masalah

## Cara Mengaktifkan Mode Maintenance

### 1. Aktifkan Maintenance Mode
```bash
php artisan down
```

### 2. Aktifkan dengan Custom Message
```bash
php artisan down --message="Sistem sedang upgrade ke versi terbaru"
```

### 3. Aktifkan dengan Secret Token (untuk akses admin)
```bash
php artisan down --secret="ppdb2026"
```

Kemudian akses: `https://yourdomain.com/ppdb2026` untuk bypass maintenance mode.

### 4. Aktifkan dengan Retry Time
```bash
php artisan down --retry=3600
```

Ini memberitahu browser untuk retry setelah 3600 detik (1 jam).

### 5. Kombinasi (Recommended)
```bash
php artisan down --secret="ppdb2026" --message="Perawatan sistem PPDB. Estimasi selesai 2 jam." --retry=7200
```

## Cara Menonaktifkan Mode Maintenance

```bash
php artisan up
```

## Testing Mode Maintenance

### 1. Aktifkan mode maintenance di local:
```bash
php artisan down --secret="testing123"
```

### 2. Buka browser dan akses website Anda
Seharusnya muncul halaman maintenance yang cantik dengan:
- Icon maintenance yang beranimasi (floating)
- Countdown timer (2 jam dari sekarang)
- Tombol "Coba Lagi" untuk reload
- Informasi kontak
- Tanpa error di console

### 3. Untuk akses sebagai admin (bypass):
```
https://yourdomain.com/testing123
```

### 4. Setelah testing, matikan:
```bash
php artisan up
```

## Fitur Halaman Maintenance Baru

1. **Standalone Design**
   - Tidak bergantung pada asset lokal
   - Menggunakan Tailwind CSS dari CDN
   - Font dari Google Fonts CDN

2. **Countdown Timer**
   - Otomatis hitung mundur 2 jam dari aktivasi
   - Auto-reload ketika countdown selesai

3. **Animasi**
   - Icon maintenance dengan efek floating
   - Loading dots dengan bounce animation

4. **Responsive**
   - Tampil sempurna di semua ukuran layar
   - Mobile-friendly

5. **Informasi Kontak**
   - Nomor telepon klik untuk call
   - Modern design

## Upload ke Production

### File yang Perlu Diupload:
```
resources/views/errors/503.blade.php
```

### Setelah Upload:

1. **Clear view cache** (jika ada):
```bash
php artisan view:clear
```

2. **Clear application cache**:
```bash
php artisan cache:clear
```

3. **Test maintenance mode**:
```bash
php artisan down --secret="admin123" --message="Upgrading PPDB System"
```

4. **Akses website** untuk memastikan halaman maintenance tampil dengan benar

5. **Bypass dengan secret token** untuk testing:
```
https://ppdb.mtsn2kotamalang.sch.id/admin123
```

6. **Matikan setelah testing**:
```bash
php artisan up
```

## Troubleshooting

### Jika halaman masih tidak muncul:

1. **Clear semua cache**:
```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
```

2. **Pastikan file 503.blade.php ter-upload dengan benar**

3. **Check permission file**:
```bash
chmod 644 resources/views/errors/503.blade.php
```

4. **Test dengan browser mode incognito** untuk menghindari cache browser

5. **Check error log Laravel**:
```bash
tail -f storage/logs/laravel.log
```

### Jika ada error "Multiple Alpine instances":
Ini sudah diperbaiki dengan menghapus semua dependensi Livewire/Alpine dari halaman 503.

## Kustomisasi

### Mengubah Durasi Countdown:
Edit file `resources/views/errors/503.blade.php` pada baris 125-126:
```javascript
const maintenanceEndTime = new Date();
maintenanceEndTime.setHours(maintenanceEndTime.getHours() + 2); // Ubah angka 2 sesuai kebutuhan
```

### Mengubah Pesan Custom:
Gunakan parameter `--message` saat menjalankan `php artisan down`:
```bash
php artisan down --message="Pesan kustom Anda di sini"
```

Pesan ini akan muncul di halaman maintenance.

## Catatan Penting

1. **Jangan lupa secret token** jika ingin tetap bisa akses saat maintenance
2. **Informasikan ke user** sebelum maintenance via WhatsApp/Email
3. **Lakukan maintenance di jam-jam sepi** (misalnya malam hari)
4. **Test di local/staging** sebelum production
5. **Siapkan backup** sebelum maintenance besar

## Checklist Sebelum Maintenance Production

- [ ] Backup database
- [ ] Backup files
- [ ] Informasikan user via WhatsApp/Email
- [ ] Set secret token untuk admin access
- [ ] Test halaman maintenance di staging
- [ ] Siapkan rollback plan
- [ ] Catat waktu mulai dan target selesai
- [ ] Monitor error logs selama maintenance

## Contoh Scenario Maintenance

### Scenario 1: Quick Update (30 menit)
```bash
# Aktifkan
php artisan down --secret="quick2026" --message="Update cepat sistem. Selesai dalam 30 menit." --retry=1800

# Lakukan update...

# Matikan
php artisan up
```

### Scenario 2: Major Update (2 jam)
```bash
# Aktifkan
php artisan down --secret="major2026" --message="Upgrade sistem PPDB ke versi terbaru. Estimasi 2 jam." --retry=7200

# Lakukan update besar...

# Matikan
php artisan up
```

### Scenario 3: Emergency Maintenance
```bash
# Aktifkan segera
php artisan down --secret="emergency" --message="Perbaikan darurat. Mohon tunggu beberapa saat."

# Fix issue...

# Matikan
php artisan up
```

---

**Dibuat:** 20 Januari 2026
**Update Terakhir:** 20 Januari 2026
**Versi:** 1.0
