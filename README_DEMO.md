# 🎓 Panduan Akun Demo - PPDB MTsN 2 Kota Malang

## 📋 Daftar Isi
- [Akun Demo yang Tersedia](#akun-demo-yang-tersedia)
- [Cara Menggunakan](#cara-menggunakan)
- [Fitur yang Bisa Diuji](#fitur-yang-bisa-diuji)
- [Troubleshooting](#troubleshooting)

---

## ✅ Akun Demo yang Tersedia

### 🎯 Versi Sederhana (RECOMMENDED untuk mulai cepat)

Jalankan perintah:
```bash
php artisan db:seed --class=SimpleDemoSeeder
```

**Akun yang dibuat:**

| Role | Email | Password | Keterangan |
|------|-------|----------|------------|
| Admin | `admin@demo.test` | `password` | Akses penuh sistem admin |
| Pendaftar | `siswa1@demo.test` | `password` | Akun siswa demo 1 |
| Pendaftar | `siswa2@demo.test` | `password` | Akun siswa demo 2 |

### 🚀 Versi Lengkap (DALAM PENGEMBANGAN)

Untuk data demo yang lebih lengkap dengan berbagai kondisi, Anda bisa memodifikasi seeder `DemoDataSeeder.php` sesuai dengan struktur database Anda.

---

## 🎯 Cara Menggunakan

### 1. Persiapan Awal

**Pastikan database sudah di-migrate:**
```bash
php artisan migrate
```

**Jalankan seeder untuk membuat akun demo:**
```bash
php artisan db:seed --class=SimpleDemoSeeder
```

### 2. Login Pertama Kali

#### Sebagai Admin
1. Buka browser ke aplikasi Anda
2. Login dengan:
   - Email: `admin@demo.test`
   - Password: `password`
3. Anda akan masuk ke dashboard admin

#### Sebagai Pendaftar
1. Buka browser ke aplikasi Anda
2. Login dengan:
   - Email: `siswa1@demo.test` atau `siswa2@demo.test`
   - Password: `password`
3. Lengkapi formulir pendaftaran

---

## 🧪 Fitur yang Bisa Diuji

### 🔐 Sebagai ADMIN (`admin@demo.test`)

#### Dashboard & Monitoring
- ✅ **Dashboard** - Lihat statistik pendaftar, jalur, dan sistem
- ✅ **Monitoring** - Monitor kesehatan sistem, database, server
- ✅ **Keamanan** - Lihat aktivitas mencurigakan, IP terblokir, log keamanan

#### Manajemen Konten
- ✅ **Tentang** - Kelola Visi, Misi, Tujuan, Fasilitas madrasah
- ✅ **Informasi & Panduan** - Kelola timeline, syarat pendaftaran, FAQ
- ✅ **Jadwal** - Buat dan kelola jadwal kegiatan PPDB
- ✅ **Pengumuman** - Buat pengumuman kelulusan
- ✅ **Kontak** - Kelola kontak dan alamat madrasah
- ✅ **Video & Infografis** - Upload video dan gambar infografis

#### Manajemen Pendaftaran
- ✅ **Jalur Pendaftaran** - Buat dan kelola jalur pendaftaran (Prestasi, Tahfidz, Umum, dll)
- ✅ **Data Pendaftar** - Lihat, verifikasi, ubah status pendaftar
- ✅ **Data Asal Sekolah** - Kelola data sekolah asal siswa
- ✅ **Kelulusan** - Tentukan siswa yang diterima/tidak diterima
- ✅ **Daftar Ulang** - Kelola siswa yang melakukan daftar ulang
- ✅ **Input EMIS** - Data siswa yang sudah daftar ulang untuk input ke EMIS

#### Laporan
- ✅ **Laporan Pendaftaran** - Laporan per jalur, status, tanggal
- ✅ **Laporan Data Lengkap** - Laporan lengkap termasuk orang tua, prestasi, dll
- ✅ **Laporan Daftar Ulang** - Laporan pembayaran dan berkas daftar ulang
- ✅ **Laporan Seragam** - Laporan ukuran seragam siswa

#### Sistem
- ✅ **Manajemen Panitia** - Kelola akun panitia/admin
- ✅ **Backup & Restore** - Backup dan restore database
- ✅ **Pengaturan** - Konfigurasi aplikasi (nama, logo, tahun ajaran, dll)

### 🎓 Sebagai PENDAFTAR (`siswa1@demo.test` atau `siswa2@demo.test`)

#### Pendaftaran
- ✅ **Dashboard** - Lihat status pendaftaran dan timeline
- ✅ **Formulir Pendaftaran** - Lengkapi data diri, orang tua, sekolah asal
- ✅ **Upload Dokumen** - Upload KK, Akta, Ijazah, SKHUN, Foto
- ✅ **Data Prestasi** - Tambah data prestasi (jika jalur prestasi)
- ✅ **Status Pendaftaran** - Lihat status verifikasi dan hasil seleksi

#### Setelah Diterima
- ✅ **Pembayaran** - Upload bukti pembayaran daftar ulang
- ✅ **Daftar Ulang** - Lengkapi data daftar ulang
- ✅ **Pilih Ukuran Seragam** - Pilih ukuran seragam
- ✅ **Kartu Ujian** - Cetak kartu ujian/pendaftaran

---

## 🛠️ Skenario Testing yang Direkomendasikan

### Scenario 1: Proses Pendaftaran Lengkap

**Step by Step:**

1. **Login sebagai Admin** (`admin@demo.test`)
   - Buat jalur pendaftaran baru (misal: "Jalur Prestasi")
   - Set kuota dan persyaratan

2. **Login sebagai Pendaftar** (`siswa1@demo.test`)
   - Pilih jalur pendaftaran
   - Lengkapi formulir (data diri, orang tua, sekolah)
   - Upload dokumen yang diperlukan
   - Submit pendaftaran

3. **Kembali sebagai Admin**
   - Lihat pendaftar baru di menu "Data Pendaftar"
   - Verifikasi dokumen
   - Ubah status menjadi "Verifikasi" atau "Diterima"

4. **Login lagi sebagai Pendaftar**
   - Lihat status pendaftaran yang sudah berubah
   - Jika diterima, lakukan daftar ulang

### Scenario 2: Pengumuman Kelulusan

1. **Sebagai Admin:**
   - Buat pengumuman di menu "Pengumuman"
   - Tentukan jalur dan siswa yang lulus
   - Publish pengumuman

2. **Sebagai Pendaftar:**
   - Cek pengumuman kelulusan
   - Lihat status diterima/tidak diterima

### Scenario 3: Daftar Ulang

1. **Pastikan pendaftar sudah diterima**

2. **Sebagai Pendaftar:**
   - Upload bukti pembayaran
   - Pilih ukuran seragam
   - Upload berkas daftar ulang

3. **Sebagai Admin:**
   - Verifikasi pembayaran
   - Verifikasi berkas daftar ulang
   - Update status seragam

---

## ⚠️ Troubleshooting

### Error saat menjalankan seeder

**Masalah:** `Class "SimpleDemoSeeder" not found`

**Solusi:**
```bash
composer dump-autoload
php artisan db:seed --class=SimpleDemoSeeder
```

### Lupa Password

**Solusi:** Reset melalui tinker
```bash
php artisan tinker
```

Kemudian:
```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::where('email', 'admin@demo.test')->first();
$user->password = Hash::make('password_baru');
$user->save();

exit
```

### Ingin mereset semua data demo

**Solusi:**
```bash
# Cara 1: Jalankan ulang seeder (akan menghapus data demo lama)
php artisan db:seed --class=SimpleDemoSeeder

# Cara 2: Manual delete via tinker
php artisan tinker
```

```php
use App\Models\User;

User::where('email', 'LIKE', '%@demo.test')->delete();
exit
```

### Akun tidak bisa login

**Checklist:**
1. Pastikan email sudah verified (`email_verified_at` tidak null)
2. Pastikan role sudah benar (`admin` atau `pendaftar`)
3. Coba reset password
4. Cek log Laravel: `storage/logs/laravel.log`

---

## 📂 File-file Terkait Demo

| File | Deskripsi |
|------|-----------|
| `SimpleDemoSeeder.php` | Seeder untuk membuat akun demo sederhana |
| `DemoDataSeeder.php` | Seeder lengkap (dalam pengembangan) |
| `DEMO_ACCOUNTS.md` | Dokumentasi detail seeder lengkap |
| `DEMO_SIMPLE.md` | Panduan demo sederhana |
| `README_DEMO.md` | File ini - panduan lengkap demo |

---

## 🔄 Update & Maintenance

### Menambah Akun Demo Baru

Edit file `SimpleDemoSeeder.php` dan tambahkan:

```php
User::create([
    'name' => 'Nama Baru',
    'email' => 'email@demo.test',
    'password' => Hash::make('password'),
    'role' => 'pendaftar', // atau 'admin'
    'email_verified_at' => now(),
]);
```

Kemudian jalankan:
```bash
php artisan db:seed --class=SimpleDemoSeeder
```

---

## 📞 Support

Jika ada pertanyaan atau masalah:
1. Cek file log: `storage/logs/laravel.log`
2. Lihat dokumentasi lengkap di folder `docs/`
3. Hubungi tim development

---

## 📝 Catatan Penting

- ⚠️ **JANGAN gunakan akun demo di production!**
- ⚠️ Data demo hanya untuk testing dan development
- ⚠️ Password default `password` harus diganti di production
- ⚠️ Email `@demo.test` hanya untuk testing lokal

---

**Last Updated:** 2026-01-11
**Version:** 1.0
**Maintained by:** Tim Development PPDB MTsN 2 Kota Malang

---

**Happy Testing! 🎉**
