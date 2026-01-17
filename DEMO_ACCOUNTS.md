# 🎓 AKUN DEMO - Sistem PPDB MTsN 2 Kota Malang

## 📋 Cara Menjalankan Demo Data Seeder

Untuk mengisi database dengan data demo yang lengkap, jalankan perintah berikut:

```bash
php artisan db:seed --class=DemoDataSeeder
```

## 🔑 Akun Login Demo

### 👤 ADMINISTRATOR
- **Email:** `demo.admin@test.com`
- **Password:** `password`
- **Role:** Admin
- **Akses:** Semua fitur admin termasuk manajemen pendaftar, jalur, pengumuman, daftar ulang, dll

---

### 🎓 PENDAFTAR - Berbagai Kondisi

#### ✅ Jalur Prestasi - LULUS & Sudah Daftar Ulang Lengkap
- **Email:** `demo.prestasi1@test.com`
- **Password:** `password`
- **Nama:** Ahmad Rizki Maulana
- **Status:** Lulus dan sudah menyelesaikan daftar ulang
- **Prestasi:** 2 prestasi (Olimpiade Matematika, Karya Ilmiah)
- **Dokumen:** Lengkap semua
- **Pembayaran:** Lunas
- **Seragam:** Sudah diambil

#### ✅ Jalur Tahfidz - LULUS & Sudah Daftar Ulang Lengkap
- **Email:** `demo.tahfidz1@test.com`
- **Password:** `password`
- **Nama:** Abdullah Al-Hafidz
- **Status:** Lulus dan sudah menyelesaikan daftar ulang
- **Dokumen:** Lengkap semua
- **Pembayaran:** Lunas
- **Seragam:** Sudah diambil

#### ⚠️ Jalur Prestasi - DITERIMA tapi Belum Daftar Ulang
- **Email:** `demo.prestasi3@test.com`
- **Password:** `password`
- **Nama:** Muhammad Fajar Sidiq
- **Status:** Diterima tapi belum melakukan daftar ulang
- **Prestasi:** 1 prestasi (Lomba Cerdas Cermat)

#### 🔍 Jalur Umum - Dalam Verifikasi
- **Email:** `demo.umum1@test.com`
- **Password:** `password`
- **Nama:** Budi Santoso
- **Status:** Sedang dalam proses verifikasi oleh admin

#### ⏳ Jalur Umum - Pending
- **Email:** `demo.umum2@test.com`
- **Password:** `password`
- **Nama:** Dewi Lestari
- **Status:** Baru mendaftar, menunggu review

#### ❌ Jalur Umum - Ditolak
- **Email:** `demo.umum3@test.com`
- **Password:** `password`
- **Nama:** Rudi Hartono
- **Status:** Tidak diterima / ditolak

#### ⏳ Jalur Afirmasi - Dalam Verifikasi
- **Email:** `demo.afirmasi1@test.com`
- **Password:** `password`
- **Nama:** Slamet Riyadi
- **Status:** Sedang dalam proses verifikasi

---

## 📊 Ringkasan Data Demo

### Jalur Pendaftaran (6 Jalur)
- ✅ **SELESAI (2 Jalur):**
  - Jalur Prestasi - Sudah ada pengumuman
  - Jalur Tahfidz - Sudah ada pengumuman

- 🔄 **BERJALAN (2 Jalur):**
  - Jalur Umum - Sedang menerima pendaftar
  - Jalur Afirmasi - Sedang menerima pendaftar

- ⏸️ **BELUM MULAI (2 Jalur):**
  - Jalur Perpindahan Tugas Orang Tua
  - Jalur Anak Guru/Karyawan

### Pendaftar (14 Orang)
- ✅ Lulus + Daftar Ulang Lengkap: **4 orang**
- ⚠️ Diterima (Belum Daftar Ulang): **2 orang**
- 🔍 Dalam Verifikasi: **2 orang**
- ⏳ Pending: **4 orang**
- ❌ Ditolak/Tidak Diterima: **2 orang**

### Data Prestasi
- **4 pendaftar** memiliki data prestasi
- Tingkat prestasi: Sekolah, Kota, Kabupaten, Provinsi

### Dokumen Upload
- Semua pendaftar yang diterima: **Dokumen Lengkap**
- Pendaftar lainnya: **Dokumen Dasar** (KK, Akta, Foto)

---

## 🧪 Skenario Testing yang Bisa Dicoba

### Sebagai Admin (`demo.admin@test.com`)
1. ✅ Lihat dashboard statistik dengan berbagai status pendaftar
2. ✅ Verifikasi pendaftar yang statusnya "verifikasi"
3. ✅ Lihat detail pendaftar dengan prestasi
4. ✅ Kelola data daftar ulang
5. ✅ Download dokumen pendaftar
6. ✅ Lihat laporan berbagai jenis
7. ✅ Lihat data EMIS (siswa yang sudah daftar ulang)
8. ✅ Monitoring sistem
9. ✅ Keamanan sistem (aktivitas mencurigakan, IP terblokir)
10. ✅ Backup & Restore database

### Sebagai Pendaftar Lulus (`demo.prestasi1@test.com` atau `demo.tahfidz1@test.com`)
1. ✅ Lihat status kelulusan
2. ✅ Lihat detail daftar ulang
3. ✅ Lihat bukti pembayaran
4. ✅ Lihat status pengambilan seragam
5. ✅ Cetak kartu pendaftaran/bukti daftar ulang

### Sebagai Pendaftar Diterima Belum Daftar Ulang (`demo.prestasi3@test.com`)
1. ✅ Lihat status diterima
2. ✅ Mulai proses daftar ulang
3. ✅ Upload bukti pembayaran
4. ✅ Isi data seragam

### Sebagai Pendaftar Pending (`demo.umum2@test.com`)
1. ✅ Edit formulir pendaftaran
2. ✅ Upload dokumen tambahan
3. ✅ Lihat status verifikasi
4. ✅ Lengkapi data prestasi (jika ada)

---

## 📁 File Dokumen Demo

Semua file dokumen dummy tersimpan di:
```
storage/app/public/demo-files/
```

File yang dibuat:
- `kk_*.pdf` - Kartu Keluarga
- `akta_*.pdf` - Akta Kelahiran
- `foto_*.jpg` - Pas Foto
- `ijazah_*.pdf` - Ijazah
- `skhun_*.pdf` - SKHUN
- `skl_*.pdf` - Surat Keterangan Lulus
- `sertifikat_*.pdf` - Sertifikat Prestasi
- `bukti_bayar_*.pdf` - Bukti Pembayaran

---

## ⚠️ Catatan Penting

1. **Data ini HANYA untuk DEMO dan TESTING**
2. Jangan gunakan di production
3. Semua password adalah: `password`
4. Email menggunakan domain `@test.com`
5. File dokumen adalah file dummy (bukan dokumen asli)
6. NISN menggunakan format: `0051234501` - `0051234514`

---

## 🔄 Reset Demo Data

Jika ingin mereset dan membuat ulang data demo:

```bash
# Jalankan ulang seeder (akan menghapus data demo lama)
php artisan db:seed --class=DemoDataSeeder
```

---

## 📞 Support

Jika ada pertanyaan atau masalah terkait demo data, hubungi tim development.

---

**Generated by:** DemoDataSeeder
**Last Updated:** 2026-01-11
