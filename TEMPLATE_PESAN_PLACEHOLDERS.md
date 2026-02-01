# 📧 TEMPLATE PESAN - DAFTAR PLACEHOLDER

**Terakhir diupdate**: 23 Januari 2026

---

## 🎯 Cara Menggunakan

Saat membuat template email atau WhatsApp, Anda bisa menggunakan **placeholder** (variabel) yang akan otomatis diganti dengan data pendaftar.

### Format Placeholder:
```
{nama_field}
```

### Contoh Penggunaan:
```
Kepada Yth. {nama},

Nomor Pendaftaran Anda: {nomor_pendaftaran}
NISN: {nisn}
Tempat, Tanggal Lahir: {tempat_lahir}, {tanggal_lahir}
```

**Akan otomatis menjadi:**
```
Kepada Yth. Kamaludin Zuh,

Nomor Pendaftaran Anda: REG-26010022
NISN: 1234567890
Tempat, Tanggal Lahir: Malang, 15/08/2010
```

---

## 📋 DAFTAR PLACEHOLDER TERSEDIA

### 1. DATA IDENTITAS PENDAFTAR

| Placeholder | Deskripsi | Contoh Output |
|-------------|-----------|---------------|
| `{nama}` | Nama lengkap pendaftar | Kamaludin Zuh |
| `{nomor_pendaftaran}` | Nomor registrasi | REG-26010022 |
| `{nisn}` | NISN pendaftar | 1234567890 |
| `{tempat_lahir}` | Tempat lahir | Malang |
| `{tanggal_lahir}` | Tanggal lahir (format d/m/Y) | 15/08/2010 |
| `{jenis_kelamin}` | Jenis kelamin (full text) | Laki-laki / Perempuan |
| `{agama}` | Agama | Islam |

---

### 2. DATA KONTAK

| Placeholder | Deskripsi | Contoh Output |
|-------------|-----------|---------------|
| `{email}` | Email pendaftar | kamaludin@gmail.com |
| `{telepon}` | Nomor HP/telepon | 08972805175 |
| `{no_hp}` | Alias untuk telepon | 08972805175 |

---

### 3. DATA ALAMAT

| Placeholder | Deskripsi | Contoh Output |
|-------------|-----------|---------------|
| `{alamat}` | Alamat lengkap | Jl. Merdeka No. 123 |
| `{provinsi}` | Nama provinsi | Jawa Timur |
| `{kabupaten}` | Nama kabupaten/kota | Kota Malang |
| `{kecamatan}` | Nama kecamatan | Lowokwaru |
| `{desa}` | Nama desa/kelurahan | Sumbersari |
| `{kode_pos}` | Kode pos | 65145 |

---

### 4. DATA ORANG TUA

| Placeholder | Deskripsi | Contoh Output |
|-------------|-----------|---------------|
| `{nama_ayah}` | Nama ayah | Ahmad Zuhri |
| `{nama_ibu}` | Nama ibu | Siti Aminah |
| `{pekerjaan_ayah}` | Pekerjaan ayah | Wiraswasta |
| `{pekerjaan_ibu}` | Pekerjaan ibu | Ibu Rumah Tangga |

---

### 5. DATA PENDIDIKAN

| Placeholder | Deskripsi | Contoh Output |
|-------------|-----------|---------------|
| `{asal_sekolah}` | Nama sekolah asal | SDN 1 Malang |
| `{nilai_rata_rata}` | Nilai rata-rata rapor | 85.50 |

---

### 6. DATA JALUR & STATUS

| Placeholder | Deskripsi | Contoh Output |
|-------------|-----------|---------------|
| `{jalur}` | Nama jalur pendaftaran | Jalur Prestasi Akademik |
| `{status}` | Status pendaftaran | menunggu_verifikasi |

---

### 7. SISTEM & LAIN-LAIN

| Placeholder | Deskripsi | Contoh Output |
|-------------|-----------|---------------|
| `{url_dashboard}` | URL dashboard | http://localhost:8000/dashboard |
| `{password}` | Password (catatan khusus)* | [Password telah dienkripsi...] |
| `{catatan}` | Catatan dari admin | Silakan lengkapi berkas |

**CATATAN PASSWORD**:
- Password di database sudah **dienkripsi** (hashed), tidak bisa ditampilkan dalam bentuk asli
- Jika user lupa password, gunakan fitur **Reset Password**
- Placeholder `{password}` akan menampilkan: *"[Password telah dienkripsi, silakan reset jika lupa]"*

---

## 📝 CONTOH TEMPLATE EMAIL

### Template 1: Notifikasi Pendaftaran Berhasil

**Subject:**
```
Pendaftaran Berhasil - {nomor_pendaftaran}
```

**Body:**
```
Kepada Yth. {nama},

Selamat! Akun pendaftaran Anda telah berhasil dibuat.

=== DATA PENDAFTARAN ANDA ===
Nomor Pendaftaran: {nomor_pendaftaran}
Nama Lengkap: {nama}
NISN: {nisn}
Tempat, Tanggal Lahir: {tempat_lahir}, {tanggal_lahir}
Jenis Kelamin: {jenis_kelamin}
Alamat: {alamat}
Telepon: {telepon}
Email: {email}
Asal Sekolah: {asal_sekolah}
Jalur Pendaftaran: {jalur}

=== AKUN LOGIN ===
Email: {email}
Password: {password}

Silakan login ke dashboard Anda di: {url_dashboard}

Tim kami akan segera meninjau berkas pendaftaran Anda. Pantau terus dashboard untuk update status terbaru.

Salam,
Panitia PPDB MTsN 2 Kota Malang
```

---

### Template 2: Notifikasi Verifikasi Berkas

**Subject:**
```
Status Verifikasi - {nomor_pendaftaran}
```

**Body:**
```
Kepada Yth. {nama},

Berkas pendaftaran Anda dengan nomor {nomor_pendaftaran} telah kami verifikasi.

Status: {status}
Catatan: {catatan}

Untuk informasi lebih lanjut, silakan cek dashboard Anda:
{url_dashboard}

Jika ada pertanyaan, silakan hubungi panitia.

Hormat kami,
Panitia PPDB MTsN 2 Kota Malang
```

---

### Template 3: Pengumuman Kelulusan

**Subject:**
```
PENGUMUMAN - {nomor_pendaftaran}
```

**Body:**
```
Kepada Yth. {nama}
Orang tua dari: {nama}

Dengan ini kami sampaikan bahwa:

Nama: {nama}
NISN: {nisn}
Asal Sekolah: {asal_sekolah}
Jalur Pendaftaran: {jalur}

Telah DITERIMA sebagai siswa/siswi MTsN 2 Kota Malang.

Untuk informasi daftar ulang, silakan login ke:
{url_dashboard}

Selamat!

Panitia PPDB MTsN 2 Kota Malang
```

---

## 📱 CONTOH TEMPLATE WHATSAPP

### Template 1: Reminder Lengkapi Berkas

```
Halo {nama},

Kami dari Panitia PPDB MTsN 2 Kota Malang ingin mengingatkan:

📋 No. Pendaftaran: {nomor_pendaftaran}
⚠️ Status: {status}

{catatan}

Silakan segera lengkapi melalui:
{url_dashboard}

Terima kasih 🙏
```

---

### Template 2: Konfirmasi Daftar Ulang

```
Assalamu'alaikum {nama},

Selamat! Anda telah diterima di MTsN 2 Kota Malang.

✅ No. Pendaftaran: {nomor_pendaftaran}
📚 Jalur: {jalur}

Silakan segera melakukan DAFTAR ULANG melalui:
{url_dashboard}

Info lebih lanjut: {telepon}

Panitia PPDB MTsN 2 Kota Malang
```

---

## ⚠️ CATATAN PENTING

### 1. Placeholder Kosong
Jika data tidak ada di database, placeholder akan diganti dengan **string kosong** (tidak error).

**Contoh:**
- Jika `asal_sekolah` kosong → `{asal_sekolah}` akan menjadi `` (kosong)
- Tidak akan muncul error atau text "null"

### 2. Format Tanggal
- `{tanggal_lahir}` otomatis diformat: **dd/mm/yyyy**
- Contoh: 15/08/2010

### 3. Jenis Kelamin
- Database menyimpan: `L` atau `P`
- Placeholder `{jenis_kelamin}` otomatis dikonversi:
  - `L` → **Laki-laki**
  - `P` → **Perempuan**

### 4. Case Sensitive
Placeholder **case sensitive** (huruf besar/kecil berpengaruh):
- ✅ `{nama}` → Benar
- ❌ `{Nama}` → Salah
- ❌ `{NAMA}` → Salah

### 5. Spasi dalam Placeholder
Jangan tambahkan spasi di dalam kurung kurawal:
- ✅ `{nama}` → Benar
- ❌ `{ nama }` → Salah
- ❌ `{nama }` → Salah

---

## 🧪 CARA TESTING

1. **Buat template baru** dengan placeholder
2. **Pilih pendaftar** yang datanya lengkap
3. **Klik "Kirim Pesan"**
4. **Cek preview** sebelum kirim
5. **Verifikasi** apakah semua placeholder ter-replace dengan benar

---

## 📊 CHECKLIST FIELD YANG SERING KOSONG

Beberapa field ini sering kosong pada pendaftar baru:

- [ ] `{provinsi}` - Jika belum pilih provinsi
- [ ] `{kabupaten}` - Jika belum pilih kabupaten
- [ ] `{kecamatan}` - Jika belum pilih kecamatan
- [ ] `{desa}` - Jika belum pilih desa
- [ ] `{asal_sekolah}` - Jika belum isi asal sekolah
- [ ] `{nilai_rata_rata}` - Jika belum isi nilai

**Tip**: Gunakan template yang tidak terlalu banyak placeholder opsional untuk pendaftar baru.

---

## 🔧 TROUBLESHOOTING

### Placeholder tidak ter-replace?

**Cek:**
1. ✅ Ejaan placeholder sudah benar?
2. ✅ Tidak ada spasi di dalam `{ }`?
3. ✅ Huruf kecil semua?
4. ✅ Data pendaftar sudah terisi di database?
5. ✅ Cache sudah di-clear? (`php artisan optimize:clear`)

### Data masih muncul `{field_name}`?

**Solusi:**
1. Refresh halaman (Ctrl + F5)
2. Clear browser cache
3. Logout dan login ulang
4. Cek apakah placeholder ada di daftar di atas

---

## 📞 SUPPORT

Jika ada placeholder tambahan yang dibutuhkan, silakan request ke tim developer.

**File yang perlu dimodifikasi:**
- `app/Livewire/Admin/Pendaftar.php` (method `fillMessageTemplate()`)

---

**Dibuat**: 23 Januari 2026
**Dokumentasi untuk**: Admin PPDB MTsN 2 Kota Malang
**Status**: ✅ Complete & Ready to Use
