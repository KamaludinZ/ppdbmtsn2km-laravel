# 🎯 PANDUAN DEMO SEDERHANA

## Akun Demo yang Sudah Ada

Aplikasi ini mungkin sudah memiliki akun untuk testing. Berikut cara membuat akun demo sederhana:

### 1. Buat Akun Admin Demo

```bash
php artisan tinker
```

Kemudian jalankan:

```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Buat akun admin
User::create([
    'name' => 'Admin Demo',
    'email' => 'admin@demo.test',
    'password' => Hash::make('password'),
    'role' => 'admin',
    'email_verified_at' => now(),
]);

// Buat akun pendaftar
User::create([
    'name' => 'Siswa Demo',
    'email' => 'siswa@demo.test',
    'password' => Hash::make('password'),
    'role' => 'pendaftar',
    'email_verified_at' => now(),
]);

// Keluar dari tinker
exit
```

### 2. Login dengan Akun Demo

**ADMIN:**
- Email: `admin@demo.test`
- Password: `password`

**PENDAFTAR:**
- Email: `siswa@demo.test`
- Password: `password`

### 3. Jalur Pendaftaran

Pastikan sudah ada jalur pendaftaran di database. Jika belum, jalankan seeder yang sudah ada:

```bash
php artisan db:seed --class=JalurPendaftaranSeeder
```

Atau buat secara manual di halaman `/admin/jalur`

### 4. Testing Fitur

#### Sebagai Admin:
1. Login dengan `admin@demo.test`
2. Buka menu:
   - Dashboard - Lihat statistik
   - Data Pendaftar - Kelola pendaftar
   - Jalur Pendaftaran - Kelola jalur
   - Pengumuman - Buat pengumuman kelulusan
   - Daftar Ulang - Kelola siswa yang daftar ulang
   - Laporan - Berbagai jenis laporan
   - Monitoring - Monitor sistem
   - Keamanan - Lihat aktivitas keamanan
   - Backup & Restore - Backup database
   - Input EMIS - Data siswa untuk EMIS

#### Sebagai Pendaftar:
1. Login dengan `siswa@demo.test`
2. Lengkapi formulir pendaftaran
3. Upload dokumen
4. Cek status pendaftaran
5. Jika lulus, lakukan daftar ulang

---

## ⚠️ Catatan

- Password default: `password`
- Data ini hanya untuk testing
- Jangan gunakan di production
- Untuk fitur lengkap, Anda bisa:
  1. Buat data pendaftar secara manual melalui interface
  2. Atau modifikasi DemoDataSeeder.php sesuai struktur database Anda

---

## 🔧 Reset Data Demo

Untuk mereset data demo:

```sql
-- Hapus data demo
DELETE FROM pendaftar WHERE email LIKE '%@demo.test';
DELETE FROM users WHERE email LIKE '%@demo.test';
```

---

## 📝 Tips Penggunaan

### Membuat Pendaftar Demo Manual

1. Login sebagai admin
2. Buka `/admin/pendaftar`
3. Klik "Tambah Pendaftar"
4. Isi data sesuai kebutuhan

### Menguji Fitur Kelulusan

1. Buat jalur pendaftaran
2. Tambah pendaftar ke jalur tersebut
3. Ubah status pendaftar menjadi "diterima"
4. Buat pengumuman di menu Pengumuman
5. Login sebagai pendaftar untuk melihat hasil

### Menguji Daftar Ulang

1. Pastikan pendaftar sudah diterima
2. Login sebagai pendaftar
3. Akses menu daftar ulang
4. Upload bukti pembayaran
5. Pilih ukuran seragam
6. Upload dokumen yang diperlukan

---

**Happy Testing! 🎉**
