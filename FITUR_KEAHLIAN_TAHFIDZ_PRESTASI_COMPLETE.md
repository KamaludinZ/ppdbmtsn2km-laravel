# Fitur Baru: Tab Keahlian, Tahfidz, dan Upload Sertifikat Prestasi

## 📋 Ringkasan Perubahan

Telah ditambahkan 3 fitur utama pada formulir pendaftaran:

1. **Tab Keahlian (Kursus/Club/Sanggar)** - Tab baru untuk mencatat keahlian tambahan siswa
2. **Tab Tahfidz** - Tab baru untuk mencatat hafalan Al-Qur'an
3. **Upload Sertifikat Prestasi** - Kolom upload sertifikat di tab prestasi yang sudah ada

## 🎯 Fitur 1: Tab Keahlian (Kursus/Club/Sanggar)

### Lokasi
- **URL**: `/pendaftar/formulir?activeTab=keahlian`
- **Posisi**: Setelah tab "Prestasi Siswa", sebelum tab "Tahfidz"

### Field yang Tersedia
1. **Bidang Keahlian** - Bidang keahlian (Contoh: Komputer, Bahasa, Musik, dll)
2. **Nama Keahlian** - Nama spesifik keahlian (Contoh: Adobe Photoshop, Bahasa Jepang)
3. **Sertifikasi** - Sertifikat yang dimiliki (Contoh: Sertifikat Adobe, JLPT N5)
4. **Lembaga Penyelenggara** - Lembaga yang menyelenggarakan (Contoh: LPK Bina Komputer)
5. **Hasil/Tingkat/Skor** - Hasil yang dicapai (Contoh: Level Intermediate, Skor 85)
6. **Upload Bukti Sertifikat** - File PDF/Gambar maksimal 2MB

### Fitur Tambahan
- ✅ Dapat menambahkan lebih dari satu keahlian
- ✅ Tombol "Tambah Keahlian" untuk menambah entry baru
- ✅ Tombol "Hapus" untuk menghapus entry (min. 1 entry harus ada)
- ✅ Semua field bersifat **opsional** (tidak wajib diisi)
- ✅ Upload file otomatis dihapus jika entry dihapus

## 🎯 Fitur 2: Tab Tahfidz

### Lokasi
- **URL**: `/pendaftar/formulir?activeTab=tahfidz`
- **Posisi**: Setelah tab "Keahlian", sebelum tab "Beasiswa & Bantuan"

### Field yang Tersedia
1. **Juz Al-Qur'an yang Dihafal** - Text (Contoh: "Juz 30", "Juz 1-3", atau "5 Juz")
2. **Upload Bukti Syahadah** - File PDF/Gambar maksimal 2MB
3. **Upload Bukti Tahsin** - File PDF/Gambar maksimal 2MB

### Fitur Tambahan
- ✅ Data tahfidz bersifat **single record** (hanya 1 data per pendaftar)
- ✅ Semua field bersifat **opsional** (tidak wajib diisi)
- ✅ File lama otomatis terhapus jika upload file baru
- ✅ Tampilan view mode menampilkan tombol "Lihat Syahadah" dan "Lihat Tahsin"

## 🎯 Fitur 3: Upload Sertifikat Prestasi

### Lokasi
- **URL**: `/pendaftar/formulir?activeTab=prestasi`
- **Perubahan**: Menambahkan field upload sertifikat di tab prestasi yang sudah ada

### Field Baru
- **Upload Sertifikat Prestasi** - File PDF/Gambar maksimal 2MB

### Kolom Tabel Baru (View Mode)
- **Kolom "Sertifikat"** dengan tombol "Lihat Sertifikat" untuk file yang sudah diupload

### Fitur Tambahan
- ✅ Field bersifat **opsional** (tidak wajib diisi)
- ✅ File lama otomatis terhapus jika upload file baru
- ✅ File otomatis terhapus jika prestasi dihapus

---

## 🗄️ Perubahan Database

### Tabel Baru

#### 1. Tabel `keahlian`
```sql
CREATE TABLE keahlian (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    pendaftar_id BIGINT NOT NULL,
    bidang_keahlian VARCHAR(255),
    nama_keahlian VARCHAR(255),
    sertifikasi VARCHAR(255),
    lembaga_penyelenggara VARCHAR(255),
    hasil_tingkat_skor VARCHAR(100),
    file_bukti_sertifikat VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (pendaftar_id) REFERENCES pendaftar(id) ON DELETE CASCADE
);
```

#### 2. Tabel `tahfidz`
```sql
CREATE TABLE tahfidz (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    pendaftar_id BIGINT NOT NULL,
    juz_alquran_dihafal VARCHAR(255),
    file_bukti_syahadah VARCHAR(255),
    file_bukti_tahsin VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (pendaftar_id) REFERENCES pendaftar(id) ON DELETE CASCADE
);
```

### Tabel yang Dimodifikasi

#### Tabel `prestasis`
- Field `file_sertifikat` sudah ada sejak awal (tidak ada perubahan struktur)
- Hanya ditambahkan fungsionalitas upload di UI

---

## 📁 File yang Dibuat/Dimodifikasi

### File Baru
1. **Models**
   - `app/Models/Keahlian.php` - Model untuk data keahlian
   - `app/Models/Tahfidz.php` - Model untuk data tahfidz

2. **Migrations**
   - `database/migrations/2026_01_31_210630_create_keahlian_table.php`
   - `database/migrations/2026_01_31_210632_create_tahfidz_table.php`

3. **Views/Templates**
   - `resources/views/tab_templates/tab-keahlian.blade.php` - Template tab keahlian
   - `resources/views/tab_templates/tab-tahfidz.blade.php` - Template tab tahfidz

### File yang Dimodifikasi
1. **Models**
   - `app/Models/Pendaftar.php` - Tambah relasi `keahlians()` dan `tahfidz()`

2. **Livewire Components**
   - `app/Livewire/Pendaftar/Formulir.php`
     - Tambah properties untuk keahlian dan tahfidz
     - Tambah methods: `loadKeahlian()`, `addKeahlian()`, `removeKeahlian()`, `saveDataKeahlian()`
     - Tambah methods: `loadTahfidz()`, `saveDataTahfidz()`
     - Update `saveDataPrestasiTerstruktur()` untuk handle upload sertifikat
     - Update `loadPrestasi()` untuk include field sertifikat

3. **Views**
   - `resources/views/livewire/pendaftar/formulir.blade.php`
     - Tambah tab navigation untuk Keahlian dan Tahfidz
     - Tambah content section untuk kedua tab

   - `resources/views/tab_templates/tab-prestasi.blade.php`
     - Tambah field upload sertifikat di edit mode
     - Tambah kolom "Sertifikat" dengan tombol "Lihat Sertifikat" di view mode

---

## 🔄 Workflow Penggunaan

### Untuk Pendaftar

1. **Tab Keahlian**
   - Login sebagai pendaftar
   - Buka `/pendaftar/formulir`
   - Klik tab "Keahlian" (setelah tab Prestasi)
   - Klik tombol "Edit Data"
   - Isi data keahlian yang dimiliki
   - Upload file sertifikat jika ada
   - Klik "Tambah Keahlian" jika ingin menambah lebih dari satu
   - Klik "Simpan Data"

2. **Tab Tahfidz**
   - Klik tab "Tahfidz"
   - Klik tombol "Edit Data"
   - Isi juz yang dihafal
   - Upload file syahadah dan tahsin
   - Klik "Simpan Data"

3. **Upload Sertifikat Prestasi**
   - Klik tab "Prestasi Siswa"
   - Klik tombol "Edit Data"
   - Scroll ke field "Upload Sertifikat Prestasi" di setiap entry prestasi
   - Upload file sertifikat
   - Klik "Simpan Data"
   - File yang diupload akan muncul di kolom "Sertifikat" dengan tombol "Lihat Sertifikat"

### Untuk Admin/Panitia
- *(Coming Soon)* Kolom status keahlian dan tahfidz di tabel pendaftar
- *(Coming Soon)* Export laporan include data keahlian dan tahfidz

---

## 📝 Catatan Penting

### Validasi
- Semua field di tab Keahlian dan Tahfidz bersifat **opsional**
- Upload file maksimal **2MB**
- Format file yang diterima: **PDF, JPG, JPEG, PNG**

### Penyimpanan File
- Sertifikat Keahlian: `storage/app/public/sertifikat_keahlian/`
- Bukti Syahadah/Tahsin: `storage/app/public/tahfidz/`
- Sertifikat Prestasi: `storage/app/public/sertifikat_prestasi/`

### Keamanan
- File otomatis dihapus jika:
  - Entry dihapus dari list
  - Upload file baru (file lama terhapus otomatis)
  - Pendaftar dihapus (CASCADE DELETE di database)

### Naming Convention
File menggunakan FileNamingService dengan format:
- `{nisn}_{timestamp}_{jenis}_{counter}.{ext}`
- Contoh: `0051234567_20260131_sertifikat_keahlian_1.pdf`

---

## 🚀 Next Steps (Tahap Berikutnya)

Masih perlu dilakukan:

1. ✅ **SELESAI**: Tab Keahlian dan Tahfidz di formulir pendaftar
2. ✅ **SELESAI**: Upload sertifikat di tab Prestasi
3. ✅ **SELESAI**: Fix FileNamingService error (lihat FIX_FILENAMING_SERVICE_ERROR.md)
4. ⏳ **PENDING**: Update `Admin/Pendaftar.php` - Tambah kolom status keahlian dan tahfidz
5. ⏳ **PENDING**: Update `Panitia/Pendaftar.php` - Tambah kolom status keahlian dan tahfidz
6. ⏳ **PENDING**: Update `PendaftarExport.php` - Include data keahlian dan tahfidz di export
7. ⏳ **PENDING**: Update view laporan - Tambah keterangan kolom export

---

## 📊 Status Implementasi

| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Migration Keahlian & Tahfidz | ✅ SELESAI | Tabel berhasil dibuat |
| Model Keahlian & Tahfidz | ✅ SELESAI | Relasi ke Pendaftar sudah ada |
| Livewire Logic | ✅ SELESAI | Methods CRUD lengkap |
| View Template | ✅ SELESAI | UI lengkap dengan edit & view mode |
| Upload Sertifikat Prestasi | ✅ SELESAI | UI dan logic complete |
| Fix FileNamingService Error | ✅ SELESAI | Lihat FIX_FILENAMING_SERVICE_ERROR.md |
| Admin/Panitia List | ✅ SELESAI | Kolom status sudah ditambahkan (lihat ADD_STATUS_COLUMNS_KEAHLIAN_TAHFIDZ_COMPLETE.md) |
| Export Laporan | ⏳ PENDING | Data belum masuk ke export |

---

## 🐛 Troubleshooting

### File tidak bisa diupload
- Pastikan folder `storage/app/public/sertifikat_keahlian/` dan `tahfidz/` ada dan writable
- Jalankan `php artisan storage:link` jika belum

### Tab tidak muncul
- Clear cache: `php artisan view:clear`
- Hard refresh browser (Ctrl+Shift+R)

### Error saat save
- Check Laravel logs: `storage/logs/laravel.log`
- Pastikan migration sudah dijalankan

---

## 📞 Dukungan

Jika ada pertanyaan atau masalah, silakan cek:
- Laravel logs di `storage/logs/laravel.log`
- Browser console untuk error JavaScript
- Network tab di browser DevTools untuk request yang gagal

---

**Dokumentasi dibuat pada**: 31 Januari 2026
**Versi**: 1.0
**Status**: ✅ Implementasi Complete (Pendaftar Side)
