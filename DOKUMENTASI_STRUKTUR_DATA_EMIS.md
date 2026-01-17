# DOKUMENTASI STRUKTUR DATA SISWA EMIS
**Referensi:** EMIS Kemenag 4.0 & Dapodik
**Versi:** 1.0
**Tanggal:** 12 Januari 2026

---

## DAFTAR ISI

1. [Overview](#overview)
2. [Mapping Database Existing vs EMIS](#mapping-database)
3. [Field Wajib (Mandatory)](#field-wajib)
4. [Kategori Data](#kategori-data)
5. [Rencana Implementasi](#rencana-implementasi)
6. [Migration Plan](#migration-plan)

---

## OVERVIEW

Dokumen ini berisi rancangan struktur data siswa yang akan diintegrasikan ke dalam sistem PPDB MTsN 2 Kota Malang berdasarkan standar EMIS Kemenag 4.0 dan Dapodik Kemendikbud.

### Tujuan Implementasi:
- ✅ Menyesuaikan sistem PPDB dengan standar data nasional (EMIS & Dapodik)
- ✅ Memudahkan integrasi data siswa ke sistem EMIS setelah diterima
- ✅ Melengkapi data pendaftar untuk keperluan administrasi
- ✅ Memenuhi standar dokumentasi pendidikan nasional

### Cakupan Data:
10 kategori data utama dengan total **150+ field**

---

## MAPPING DATABASE

### Tabel Utama: `pendaftar`

#### ✅ Field yang SUDAH ADA di Database

| Field Database Existing | Field EMIS | Status | Keterangan |
|------------------------|------------|--------|------------|
| `nisn` | NISN | ✅ Ada | Sudah sesuai |
| `nama_lengkap` | Nama Lengkap | ✅ Ada | Sudah sesuai |
| `jenis_kelamin` | Jenis Kelamin | ✅ Ada | Format: L/P, perlu disesuaikan jadi enum 1/2 |
| `tempat_lahir` | Tempat Lahir | ✅ Ada | Sudah sesuai |
| `tanggal_lahir` | Tanggal Lahir | ✅ Ada | Sudah sesuai |
| `agama` | Agama | ✅ Ada | Perlu disesuaikan ke enum 1-6 |
| `alamat` | Alamat Jalan | ✅ Ada | Sudah sesuai |
| `provinsi` | Provinsi | ✅ Ada | Sudah sesuai |
| `kabupaten` | Kabupaten/Kota | ✅ Ada | Sudah sesuai |
| `kecamatan` | Kecamatan | ✅ Ada | Sudah sesuai |
| `desa` | Kelurahan/Desa | ✅ Ada | Sudah sesuai |
| `kode_pos` | Kode Pos | ✅ Ada | Sudah sesuai |
| `jarak_ke_sekolah` | Jarak ke Sekolah | ✅ Ada | Format perlu disesuaikan |
| `transportasi` | Moda Transportasi | ✅ Ada | Format perlu disesuaikan |
| `email` | Email | ✅ Ada | Sudah sesuai |
| `no_hp` | No. Handphone | ✅ Ada | Sudah sesuai |
| `foto` | Foto Siswa | ✅ Ada | Sudah sesuai |
| `nama_ayah` | Nama Lengkap Ayah | ✅ Ada | Sudah sesuai |
| `nama_ibu` | Nama Lengkap Ibu | ✅ Ada | Sudah sesuai |
| `pekerjaan_ayah` | Pekerjaan Ayah | ✅ Ada | Format perlu disesuaikan |
| `pekerjaan_ibu` | Pekerjaan Ibu | ✅ Ada | Format perlu disesuaikan |
| `penghasilan_ortu` | Penghasilan Bulanan | ✅ Ada | Format perlu disesuaikan |
| `telepon_ortu` | No. Handphone Ortu | ✅ Ada | Sudah sesuai |
| `asal_sekolah` | Nama Sekolah Asal | ✅ Ada | Sudah sesuai |
| `jenis_sekolah_asal` | Jenis Sekolah Asal | ✅ Ada | Format perlu disesuaikan |
| `npsn_sekolah_asal` | NPSN Sekolah Asal | ✅ Ada | Sudah sesuai |
| `nsm_sekolah_asal` | NSM Sekolah Asal | ✅ Ada | Sudah sesuai |
| `alamat_sekolah` | Alamat Sekolah Asal | ✅ Ada | Sudah sesuai |

#### ❌ Field BARU yang PERLU DITAMBAHKAN

##### 1. DATA IDENTITAS SISWA (14 field baru)

| Field Baru | Tipe | Enum/Pilihan | Mandatory |
|-----------|------|--------------|-----------|
| `nik` | VARCHAR(16) | - | ✅ Ya |
| `nis` | VARCHAR(20) | - | ❌ Tidak |
| `no_registrasi_akta_lahir` | VARCHAR(30) | - | ❌ Tidak |
| `kewarganegaraan` | ENUM | 1=WNI, 2=WNA | ✅ Ya |
| `negara_asal` | VARCHAR(50) | - | Jika WNA |
| `no_passport_kitas` | VARCHAR(20) | - | Jika WNA |
| `anak_ke` | INTEGER | - | ❌ Tidak |
| `jumlah_saudara_kandung` | INTEGER | - | ❌ Tidak |
| `golongan_darah` | ENUM | 1=A, 2=B, 3=AB, 4=O, 5=Tidak Tahu | ❌ Tidak |
| `rhesus` | ENUM | 1=Positif, 2=Negatif, 3=Tidak Tahu | ❌ Tidak |
| `tinggi_badan` | INTEGER | cm | ❌ Tidak |
| `berat_badan` | DECIMAL(5,2) | kg | ❌ Tidak |
| `lingkar_kepala` | DECIMAL(5,2) | cm | ❌ Tidak |

##### 2. DATA TEMPAT TINGGAL (10 field baru)

| Field Baru | Tipe | Enum/Pilihan | Mandatory |
|-----------|------|--------------|-----------|
| `status_tempat_tinggal` | ENUM | 1-8 (lihat doc) | ❌ Tidak |
| `rt` | VARCHAR(3) | - | ✅ Ya |
| `rw` | VARCHAR(3) | - | ✅ Ya |
| `nama_dusun` | VARCHAR(50) | - | ❌ Tidak |
| `koordinat_lintang` | DECIMAL(10,8) | GPS | ❌ Tidak |
| `koordinat_bujur` | DECIMAL(11,8) | GPS | ❌ Tidak |
| `waktu_tempuh_ke_sekolah` | INTEGER | menit | ❌ Tidak |

##### 3. DATA ORANG TUA/WALI (30+ field baru)

**Untuk Ayah:**
| Field Baru | Tipe | Mandatory |
|-----------|------|-----------|
| `nik_ayah` | VARCHAR(16) | ✅ Ya |
| `status_ayah` | ENUM | 1=Hidup, 2=Meninggal, 3=Tidak Diketahui |
| `tempat_lahir_ayah` | VARCHAR(50) | ❌ Tidak |
| `tanggal_lahir_ayah` | DATE | ❌ Tidak |
| `pendidikan_ayah` | ENUM | 1-10 | ❌ Tidak |
| `alamat_ayah` | TEXT | ❌ Tidak |
| `berkebutuhan_khusus_ayah` | ENUM | 0=Tidak, 1=Ya |

**Untuk Ibu:**
| Field Baru | Tipe | Mandatory |
|-----------|------|-----------|
| `nik_ibu` | VARCHAR(16) | ✅ Ya |
| `status_ibu` | ENUM | 1=Hidup, 2=Meninggal, 3=Tidak Diketahui |
| `tempat_lahir_ibu` | VARCHAR(50) | ❌ Tidak |
| `tanggal_lahir_ibu` | DATE | ❌ Tidak |
| `pendidikan_ibu` | ENUM | 1-10 | ❌ Tidak |
| `alamat_ibu` | TEXT | ❌ Tidak |
| `berkebutuhan_khusus_ibu` | ENUM | 0=Tidak, 1=Ya |

**Untuk Wali (jika ada):**
| Field Baru | Tipe | Mandatory |
|-----------|------|-----------|
| `nama_wali` | VARCHAR(100) | Jika ada wali |
| `nik_wali` | VARCHAR(16) | Jika ada wali |
| `hubungan_wali` | ENUM | 1-8 |
| `tempat_lahir_wali` | VARCHAR(50) | ❌ Tidak |
| `tanggal_lahir_wali` | DATE | ❌ Tidak |
| `pendidikan_wali` | ENUM | 1-10 | ❌ Tidak |
| `pekerjaan_wali` | ENUM | 1-10 | ❌ Tidak |
| `penghasilan_wali` | ENUM | 1-6 | ❌ Tidak |
| `no_hp_wali` | VARCHAR(15) | ❌ Tidak |
| `alamat_wali` | TEXT | ❌ Tidak |

**Upload Dokumen:**
| Field Baru | Tipe | Format |
|-----------|------|--------|
| `upload_kartu_keluarga` | VARCHAR(255) | JPG/PNG/PDF, max 200KB |

##### 4. RIWAYAT PENDIDIKAN (25+ field baru)

**4.1 Riwayat PAUD:**
| Field Baru | Tipe |
|-----------|------|
| `pernah_paud` | ENUM | 1=Pernah, 2=Tidak |
| `nama_lembaga_paud` | VARCHAR(100) |
| `npsn_paud` | VARCHAR(8) |
| `alamat_paud` | VARCHAR(200) |
| `tahun_masuk_paud` | YEAR |
| `tahun_keluar_paud` | YEAR |

**4.2 Riwayat TK/RA:**
| Field Baru | Tipe |
|-----------|------|
| `pernah_tk` | ENUM | 1=Pernah, 2=Tidak |
| `jenis_tk` | ENUM | 1=TK Negeri, 2=TK Swasta, 3=RA, 4=BA, 5=Lainnya |
| `nama_tk` | VARCHAR(100) |
| `npsn_tk` | VARCHAR(8) |
| `alamat_tk` | VARCHAR(200) |
| `tahun_masuk_tk` | YEAR |
| `tahun_lulus_tk` | YEAR |
| `no_ijazah_tk` | VARCHAR(30) |

**4.3 Riwayat SD/MI:**
| Field Baru | Tipe |
|-----------|------|
| `tahun_lulus_sd` | YEAR |
| `no_peserta_un_sd` | VARCHAR(20) |
| `no_skhun_sd` | VARCHAR(20) |
| `no_ijazah_sd` | VARCHAR(30) |

##### 5. DATA KESEHATAN & VAKSINASI (50+ field)

**5.1 Kesehatan Umum:**
| Field Baru | Tipe |
|-----------|------|
| `riwayat_penyakit` | TEXT |
| `alergi` | TEXT |
| `kondisi_fisik` | ENUM | 1=Sehat, 2=Dalam Perawatan, 3=Penanganan Khusus |

**5.2 Riwayat Imunisasi/Vaksinasi:**

Tabel terpisah: `vaksinasi_siswa`
| Field | Tipe | Keterangan |
|-------|------|------------|
| `id` | BIGINT | PK |
| `pendaftar_id` | BIGINT | FK ke pendaftar |
| `jenis_vaksin` | VARCHAR(50) | Hepatitis B, BCG, Polio, DPT, dll |
| `status` | ENUM | 0=Belum, 1=Sudah |
| `tanggal_vaksinasi` | DATE | - |
| `tempat_vaksinasi` | VARCHAR(100) | Puskesmas/RS/Posyandu |
| `batch_number` | VARCHAR(30) | Nomor batch (optional) |
| `bukti_vaksinasi` | VARCHAR(255) | Path file |

**Jenis Vaksin yang Perlu Didata:**
- Hepatitis B (HB-0)
- BCG (Tuberkulosis)
- Polio (Dosis 1-4)
- DPT-HB-Hib (Dosis 1-3 + Booster)
- Campak/MR (Dosis 1-2)
- PCV (Pneumonia)
- Rotavirus
- Japanese Encephalitis
- HPV (Kelas 5 SD)
- Td/Tdap
- COVID-19 (Dosis 1, 2, Booster)

##### 6. DATA KEBUTUHAN KHUSUS (1 field)

| Field Baru | Tipe | Enum |
|-----------|------|------|
| `kebutuhan_khusus` | ENUM | 01-19 (lihat doc) |

Kode: 01=Normal, 02=Tuna Netra, 03=Tuna Rungu, 04-05=Tuna Grahita, 06-07=Tuna Daksa, 08=Tuna Wicara, 09=Tuna Laras, 10=Tuna Ganda, 11=ADHD, 12=Gifted, 13=Talented, 14=Learning Disability, 15=Gangguan Komunikasi, 16=Narkoba, 17=Indigo, 18=Down Syndrome, 19=Autis

##### 7. DATA AKTIVITAS BELAJAR (8 field)

| Field Baru | Tipe | Keterangan |
|-----------|------|------------|
| `status_siswa` | ENUM | 1=Aktif, 2=Tidak Aktif, 3=Lulus, 4=Mutasi, 5=DO, 6=Wafat |
| `jenjang_pendidikan` | ENUM | 1=RA/TK, 2=MI/SD, 3=MTs/SMP, 4=MA/SMA |
| `tingkat_kelas` | ENUM | 1-12 |
| `rombongan_belajar` | VARCHAR(20) | VII-A, VIII-B |
| `jurusan_peminatan` | ENUM | 1=IPA, 2=IPS, 3=Bahasa, 4=Agama, 5=Umum |
| `tahun_masuk` | YEAR | - |
| `tanggal_masuk_sekolah` | DATE | - |
| `jenis_pendaftaran` | ENUM | 1=Siswa Baru, 2=Pindahan, 3=Kembali Sekolah |
| `kurikulum` | ENUM | 1=K13, 2=Merdeka, 3=Khusus |

##### 8. DATA BEASISWA & BANTUAN (15 field)

| Field Baru | Tipe |
|-----------|------|
| `penerima_kip` | ENUM | 0=Tidak, 1=Ya |
| `nomor_kip` | VARCHAR(20) |
| `nama_di_kip` | VARCHAR(100) |
| `terima_fisik_kip` | ENUM | 0=Belum, 1=Sudah |
| `upload_kip` | VARCHAR(255) |
| `penerima_kps_pkh` | ENUM | 0=Tidak, 1=Ya |
| `nomor_kps_pkh` | VARCHAR(20) |
| `penerima_kks` | ENUM | 0=Tidak, 1=Ya |
| `nomor_kks` | VARCHAR(20) |
| `layak_pip` | ENUM | 0=Tidak, 1=Ya |
| `alasan_layak_pip` | ENUM | 1-6 |
| `jenis_beasiswa` | ENUM | 1-6 |
| `bank_penerima` | ENUM | 1-6 |
| `no_rekening` | VARCHAR(20) |
| `atas_nama_rekening` | VARCHAR(100) |

##### 9. DATA PRESTASI

Tabel terpisah: `prestasi_siswa`
| Field | Tipe |
|-------|------|
| `id` | BIGINT | PK |
| `pendaftar_id` | BIGINT | FK |
| `jenis_prestasi` | ENUM | 1-6 |
| `tingkat` | ENUM | 1-6 |
| `nama_prestasi` | VARCHAR(200) |
| `penyelenggara` | VARCHAR(100) |
| `peringkat` | ENUM | 1-7 |
| `tahun` | YEAR |
| `upload_sertifikat` | VARCHAR(255) |

##### 10. DATA TAMBAHAN (7 field opsional)

| Field Baru | Tipe |
|-----------|------|
| `hobi` | ENUM | 1-8 |
| `cita_cita` | ENUM | 1-9 |
| `ekstrakurikuler` | JSON | Multi-select |
| `kemampuan_bahasa_asing` | JSON | Multi-select |
| `hafalan_alquran` | ENUM | 1-7 |
| `tinggal_di_pesantren` | ENUM | 0=Tidak, 1=Ya |
| `nama_pesantren` | VARCHAR(100) |

---

## FIELD WAJIB (MANDATORY)

Berdasarkan dokumen EMIS, field-field berikut **WAJIB** diisi:

### ✅ Sudah Ada di Sistem:
1. NISN
2. Nama Lengkap
3. Jenis Kelamin
4. Tempat Lahir
5. Tanggal Lahir
6. Agama
7. Alamat Lengkap (dengan RT/RW)
8. Data Orang Tua (minimal Ibu Kandung)

### ❌ Perlu Ditambahkan:
1. **NIK** - Nomor Induk Kependudukan (16 digit)
2. **NIK Ayah** - NIK Ayah Kandung
3. **NIK Ibu** - NIK Ibu Kandung
4. **Kewarganegaraan** - WNI/WNA
5. **RT/RW** - Nomor RT dan RW

---

## KATEGORI DATA

### 1️⃣ DATA IDENTITAS SISWA
- ✅ Priority: **HIGH**
- 📊 Total Field: 20 field
- 🔴 Mandatory: NIK, Nama, Jenis Kelamin, Tempat/Tanggal Lahir, Agama

### 2️⃣ DATA TEMPAT TINGGAL
- ✅ Priority: **HIGH**
- 📊 Total Field: 17 field
- 🔴 Mandatory: Alamat, RT, RW, Kelurahan, Kecamatan, Kabupaten, Provinsi

### 3️⃣ DATA ORANG TUA/WALI
- ✅ Priority: **HIGH**
- 📊 Total Field: 40+ field (Ayah, Ibu, Wali)
- 🔴 Mandatory: Nama Ibu, NIK Ibu

### 4️⃣ RIWAYAT PENDIDIKAN
- ✅ Priority: **MEDIUM**
- 📊 Total Field: 30+ field
- 🟡 Mostly Optional

### 5️⃣ DATA KESEHATAN & VAKSINASI
- ✅ Priority: **MEDIUM**
- 📊 Total Field: 60+ field
- 🟡 Optional, tapi penting untuk record

### 6️⃣ DATA KEBUTUHAN KHUSUS
- ✅ Priority: **MEDIUM**
- 📊 Total Field: 1 field (19 options)
- 🟡 Optional

### 7️⃣ DATA AKTIVITAS BELAJAR
- ✅ Priority: **LOW**
- 📊 Total Field: 9 field
- 🟢 Akan diisi setelah siswa diterima

### 8️⃣ DATA BEASISWA & BANTUAN
- ✅ Priority: **MEDIUM**
- 📊 Total Field: 15 field
- 🟡 Optional tapi penting untuk KIP/PIP

### 9️⃣ DATA PRESTASI
- ✅ Priority: **LOW**
- 📊 Tabel terpisah (multiple records)
- 🟢 Optional, sudah ada di sistem saat ini

### 🔟 DATA TAMBAHAN
- ✅ Priority: **LOW**
- 📊 Total Field: 7 field
- 🟢 Semuanya optional

---

## RENCANA IMPLEMENTASI

### 🎯 FASE 1: Data Prioritas Tinggi (MANDATORY)
**Target: Minggu 1-2**

#### Step 1.1: Update Data Identitas Siswa
- [ ] Tambah field `nik` (VARCHAR 16)
- [ ] Tambah field `kewarganegaraan` (ENUM)
- [ ] Tambah field `anak_ke`, `jumlah_saudara_kandung`
- [ ] Update enum `jenis_kelamin` (L/P → 1/2)
- [ ] Update enum `agama` (sesuaikan kode 1-6)

#### Step 1.2: Update Data Tempat Tinggal
- [ ] Tambah field `rt` (VARCHAR 3)
- [ ] Tambah field `rw` (VARCHAR 3)
- [ ] Tambah field `status_tempat_tinggal` (ENUM)
- [ ] Update format `jarak_ke_sekolah` (text → ENUM)

#### Step 1.3: Update Data Orang Tua
- [ ] Tambah field `nik_ayah` (VARCHAR 16)
- [ ] Tambah field `nik_ibu` (VARCHAR 16)
- [ ] Tambah field `status_ayah`, `status_ibu` (ENUM)
- [ ] Tambah field `upload_kartu_keluarga`
- [ ] Update format `pendidikan_ayah`, `pendidikan_ibu` (ENUM 1-10)
- [ ] Update format `pekerjaan_ayah`, `pekerjaan_ibu` (ENUM 1-10)
- [ ] Update format `penghasilan_ortu` (ENUM 1-6)

#### Step 1.4: Data Wali
- [ ] Tambah semua field data wali (10+ field)

**Deliverables Fase 1:**
- ✅ Migration file
- ✅ Update Model Pendaftar
- ✅ Update Form Pendaftar (step 1: Data Pribadi)
- ✅ Update Halaman Detail Pendaftar (tab Data Pribadi & Orang Tua)
- ✅ Validasi form sesuai mandatory fields

---

### 🎯 FASE 2: Data Prioritas Menengah
**Target: Minggu 3-4**

#### Step 2.1: Riwayat Pendidikan
- [ ] Tambah field riwayat PAUD (6 field)
- [ ] Tambah field riwayat TK/RA (8 field)
- [ ] Tambah field riwayat SD/MI (4 field tambahan)
- [ ] Update form pendaftar (tab Pendidikan)

#### Step 2.2: Data Beasiswa & Bantuan
- [ ] Tambah field KIP/PIP (15 field)
- [ ] Upload dokumen KIP/KKS
- [ ] Update form pendaftar (tab Beasiswa)

#### Step 2.3: Data Kesehatan Dasar
- [ ] Tambah field kesehatan umum (golongan darah, tinggi, berat, dll)
- [ ] Tambah field riwayat penyakit, alergi
- [ ] Update form pendaftar (tab Kesehatan)

**Deliverables Fase 2:**
- ✅ Migration files
- ✅ Update Form Pendaftar (tambah 3 tab baru)
- ✅ Update Halaman Detail Pendaftar (tambah 3 tab baru)

---

### 🎯 FASE 3: Data Vaksinasi & Kebutuhan Khusus
**Target: Minggu 5**

#### Step 3.1: Tabel Vaksinasi
- [ ] Buat tabel `vaksinasi_siswa`
- [ ] Buat model `VaksinasiSiswa`
- [ ] Buat relationship ke Pendaftar
- [ ] Buat form input vaksinasi (18+ jenis vaksin)
- [ ] Upload bukti vaksinasi

#### Step 3.2: Data Kebutuhan Khusus
- [ ] Tambah field `kebutuhan_khusus` (ENUM 01-19)
- [ ] Update form dengan daftar kebutuhan khusus

**Deliverables Fase 3:**
- ✅ Tabel & Model baru
- ✅ Form vaksinasi
- ✅ Update form kebutuhan khusus

---

### 🎯 FASE 4: Data Aktivitas & Prestasi
**Target: Minggu 6**

#### Step 4.1: Data Aktivitas Belajar
- [ ] Tambah field status siswa, jenjang, tingkat, rombel, dll
- [ ] Field ini diisi setelah siswa diterima

#### Step 4.2: Tabel Prestasi (Enhancement)
- [ ] Update tabel prestasi yang sudah ada
- [ ] Sesuaikan dengan format EMIS

#### Step 4.3: Data Tambahan
- [ ] Tambah field hobi, cita-cita, ekstrakurikuler, dll
- [ ] Field hafalan Al-Quran
- [ ] Field pesantren

**Deliverables Fase 4:**
- ✅ Form aktivitas belajar (untuk admin/panitia)
- ✅ Update form prestasi
- ✅ Form data tambahan

---

## MIGRATION PLAN

### Urutan Eksekusi Migration:

```
1. 2026_01_12_000001_add_identitas_siswa_fields.php
2. 2026_01_12_000002_add_tempat_tinggal_fields.php
3. 2026_01_12_000003_add_orang_tua_wali_fields.php
4. 2026_01_12_000004_add_riwayat_pendidikan_fields.php
5. 2026_01_12_000005_add_kesehatan_fields.php
6. 2026_01_12_000006_create_vaksinasi_siswa_table.php
7. 2026_01_12_000007_add_kebutuhan_khusus_field.php
8. 2026_01_12_000008_add_beasiswa_bantuan_fields.php
9. 2026_01_12_000009_add_aktivitas_belajar_fields.php
10. 2026_01_12_000010_update_prestasi_siswa_table.php
11. 2026_01_12_000011_add_data_tambahan_fields.php
12. 2026_01_12_000012_update_existing_enum_fields.php
```

---

## ENUM REFERENCE

### Jenis Kelamin
```php
1 = Laki-laki
2 = Perempuan
```

### Agama
```php
1 = Islam
2 = Kristen
3 = Katolik
4 = Hindu
5 = Buddha
6 = Konghucu
```

### Kewarganegaraan
```php
1 = WNI
2 = WNA
```

### Golongan Darah
```php
1 = A
2 = B
3 = AB
4 = O
5 = Tidak Tahu
```

### Status (Ayah/Ibu/Wali)
```php
1 = Masih Hidup
2 = Sudah Meninggal
3 = Tidak Diketahui
```

### Pendidikan Terakhir
```php
1 = Tidak Sekolah
2 = SD/Sederajat
3 = SMP/Sederajat
4 = SMA/Sederajat
5 = D1
6 = D2
7 = D3
8 = D4/S1
9 = S2
10 = S3
```

### Pekerjaan
```php
1 = Tidak Bekerja
2 = PNS/TNI/Polri
3 = Karyawan Swasta
4 = Wiraswasta
5 = Petani
6 = Nelayan
7 = Buruh
8 = Pensiunan
9 = TKI
10 = Lainnya
```

### Penghasilan Bulanan
```php
1 = Tidak Berpenghasilan
2 = < Rp 500.000
3 = Rp 500.000 - 1.000.000
4 = Rp 1.000.000 - 2.000.000
5 = Rp 2.000.000 - 5.000.000
6 = > Rp 5.000.000
```

### Jarak ke Sekolah
```php
1 = < 1 Km
2 = 1-5 Km
3 = 5-10 Km
4 = 10-20 Km
5 = 20-30 Km
6 = > 30 Km
```

### Moda Transportasi
```php
1 = Jalan Kaki
2 = Sepeda
3 = Sepeda Motor
4 = Mobil Pribadi
5 = Antar Jemput Sekolah
6 = Angkutan Umum
7 = Ojek
8 = Andong/Becak
9 = Perahu/Rakit
10 = Lainnya
```

---

## CHECKLIST IMPLEMENTASI

### ✅ Persiapan
- [ ] Review dokumen EMIS lengkap
- [ ] Diskusi dengan stakeholder
- [ ] Tentukan prioritas field
- [ ] Tentukan timeline implementasi

### 📝 Development Fase 1
- [ ] Buat migration untuk data identitas
- [ ] Buat migration untuk data tempat tinggal
- [ ] Buat migration untuk data orang tua/wali
- [ ] Update model Pendaftar
- [ ] Update form pendaftaran (Data Pribadi)
- [ ] Update form pendaftaran (Data Alamat)
- [ ] Update form pendaftaran (Data Orang Tua)
- [ ] Update halaman detail pendaftar
- [ ] Testing & validasi

### 🧪 Testing
- [ ] Test input data manual
- [ ] Test validasi field mandatory
- [ ] Test upload file KK
- [ ] Test tampilan di berbagai role
- [ ] Test responsiveness mobile

### 🚀 Deployment
- [ ] Backup database
- [ ] Run migration di production
- [ ] Monitoring error
- [ ] Training user

---

## CATATAN PENTING

### ⚠️ Breaking Changes
- Beberapa field existing perlu diubah formatnya (dari text ke ENUM)
- Data existing perlu dimigrasikan ke format baru

### 📌 Integrasi Sistem
- NIK akan terintegrasi dengan Dukcapil (future)
- NISN akan terintegrasi dengan Verval PD Kemendikbud (future)
- Data vaksinasi dapat diintegrasikan dengan Puskesmas (future)

### 💾 Upload File
- Format: JPG, PNG, PDF
- Ukuran maksimal: 200KB per file
- Perlu validasi file type dan size

### 🔐 Security
- NIK adalah data sensitif, perlu enkripsi
- Foto siswa perlu watermark
- File upload perlu validasi ketat

---

**Dokumen ini akan terus diupdate seiring progress implementasi.**

**Contact:** Tim Developer PPDB MTsN 2 Kota Malang
