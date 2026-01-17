# Alur Pendaftaran dan Daftar Ulang - PPDB MTsN 2 Kota Malang

## 📋 **RINGKASAN ALUR**

```
1. PENDAFTARAN → 2. VERIFIKASI BERKAS → 3. TES SELEKSI → 4. PENGUMUMAN → 5. PERSETUJUAN → 6. DAFTAR ULANG
   [Pendaftar]      [Admin/Panitia]       [Admin]        [Dashboard]    [Pendaftar]    [Admin/Panitia]
```

---

## 🔄 **ALUR LENGKAP**

### **TAHAP 1: PENDAFTARAN**
**Location:** `/daftar` (Public) → Data muncul di `/admin/pendaftar`

**Proses:**
1. Calon siswa mengisi formulir pendaftaran
2. Upload dokumen yang diperlukan (KK, Akte, Rapor, dll)
3. Mendapat nomor pendaftaran otomatis
4. Data masuk ke **`/admin/pendaftar`** dengan status "Menunggu Verifikasi"

**Database:**
- Tabel: `pendaftar`
- Status awal: `menunggu`
- Data terpusat di `/admin/pendaftar`

---

### **TAHAP 2: VERIFIKASI BERKAS**
**Location:** `/admin/pendaftar` (Admin/Panitia Pendaftaran)

**Proses:**
1. Admin/Panitia melihat semua pendaftar di `/admin/pendaftar`
2. Verifikasi kelengkapan dokumen:
   - Rapor
   - Akta Kelahiran
   - Kartu Keluarga
   - NISN
   - Ijazah
   - Sertifikat Prestasi (jika ada)
   - KIP/PKH/KKS (jika ada)
3. Ubah status menjadi "Diverifikasi" jika semua dokumen lengkap

**Kolom yang Digunakan:**
- **Status**: Status proses pendaftaran
- **Verif. Rapor, Verif. Akte, dst**: Status verifikasi per dokumen

---

### **TAHAP 3: TES SELEKSI & PENENTUAN KELULUSAN**
**Location:** `/admin/kelulusan` (Admin)

**Proses:**
1. Admin membuka `/admin/kelulusan`
2. Semua pendaftar yang terverifikasi muncul di sini
3. Admin menentukan **Status Tes/Kelulusan**:
   - ✓ **LULUS** - Lolos seleksi
   - ⚠ **CADANGAN** - Cadangan
   - ✗ **TIDAK LULUS** - Tidak lolos
   - ↻ **Reset** - Kembali ke Menunggu

**Cara Update Status:**

#### **Individual:**
- Klik tombol status yang diinginkan pada setiap pendaftar
- Konfirmasi perubahan
- Status otomatis tersimpan

#### **Bulk/Massal:**
- Gunakan filter (jalur, status, search)
- Klik "Aksi Massal" di header tabel
- Pilih: "Tandai Lulus", "Tandai Cadangan", atau "Tandai Tidak Lulus"
- Semua data terfilter berubah sekaligus

**Database Update:**
- Tabel: `pendaftar`
- Field: `status_pendaftaran` → 'lulus', 'tidak_lulus', 'cadangan'
- Field: `tanggal_verifikasi` → timestamp otomatis

---

### **TAHAP 4: PENGUMUMAN & PERSETUJUAN**
**Location:** Dashboard Pendaftar (Akun Pendaftar)

**Untuk Pendaftar yang LULUS:**

#### **Tampilan Dashboard:**
1. Badge status: "LULUS SELEKSI" (hijau)
2. Card persetujuan daftar ulang muncul (orange, animasi pulse)
3. Isi card:
   ```
   🎉 SELAMAT! Anda LULUS Seleksi

   Langkah selanjutnya: DAFTAR ULANG

   Yang Perlu Anda Lakukan:
   - Klik tombol "Saya Setuju Daftar Ulang"
   - Lengkapi persyaratan daftar ulang
   - Upload dokumen yang diperlukan
   - Lakukan pembayaran

   [✓ Saya Setuju Daftar Ulang]
   ```

#### **Proses Persetujuan:**
1. Pendaftar klik tombol "Saya Setuju Daftar Ulang"
2. Konfirmasi muncul
3. Jika setuju:
   - `setuju_daftar_ulang` → true
   - `tanggal_setuju_daftar_ulang` → timestamp
   - Record `daftar_ulang` otomatis dibuat
   - Status: "Belum daftar ulang"
   - Batas waktu: 2 bulan dari tanggal setuju

#### **Setelah Setuju:**
- Card berubah warna hijau
- Tampil konfirmasi: "✓ Persetujuan Daftar Ulang Tercatat"
- Tanggal persetujuan ditampilkan
- Instruksi lanjutan: "Silakan lengkapi persyaratan daftar ulang"

**Database Update:**
- Tabel: `pendaftar`
  - `setuju_daftar_ulang` = true
  - `tanggal_setuju_daftar_ulang` = now()
- Tabel: `daftar_ulang`
  - `pendaftar_id` = id pendaftar
  - `status` = 'belum'
  - `batas_daftar_ulang` = now() + 2 bulan

---

### **TAHAP 5: MONITORING DI ADMIN**
**Location:** `/admin/pendaftar` (Admin)

**Kolom Baru di Tabel Pendaftar:**

#### **1. Kolom "Status Tes"**
- Menampilkan hasil kelulusan dari `/admin/kelulusan`
- Badge:
  - 🟢 **LULUS** (hijau, bold)
  - 🔴 **TIDAK LULUS** (merah, bold)
  - 🟠 **CADANGAN** (orange, bold)
  - 🔵 **Diverifikasi** (biru)
  - 🟡 **Menunggu** (kuning)

#### **2. Kolom "Persetujuan Daftar Ulang"**
- **Jika LULUS dan belum setuju:**
  - Badge: "Menunggu Persetujuan" (kuning)

- **Jika LULUS dan sudah setuju:**
  - Badge: "✓ Sudah Setuju" (hijau)
  - Tombol: "✗ Batalkan" (merah)
  - Tanggal persetujuan ditampilkan
  - **Admin bisa BATALKAN persetujuan** jika diperlukan

- **Jika bukan LULUS:**
  - Tampil: "-" (tidak relevan)

**Fitur Admin Batalkan Persetujuan:**
1. Admin klik tombol "✗ Batalkan"
2. Konfirmasi muncul
3. Jika konfirmasi:
   - `setuju_daftar_ulang` → false
   - `tanggal_setuju_daftar_ulang` → null
   - Record `daftar_ulang` dihapus
   - Notifikasi: "Persetujuan daftar ulang berhasil dibatalkan"

---

### **TAHAP 6: DAFTAR ULANG**
**Location:** `/admin/daftar-ulang` (Admin/Panitia Daftar Ulang)

**Filter Otomatis:**
Halaman ini **HANYA menampilkan** pendaftar yang:
- ✓ Status pendaftaran = "lulus"
- ✓ setuju_daftar_ulang = true
- ✓ Memiliki record di tabel daftar_ulang

**Proses:**
1. Pendaftar yang sudah setuju muncul di `/admin/daftar-ulang`
2. Admin/Panitia verifikasi:
   - Pembayaran daftar ulang
   - Pembayaran seragam
   - Ukuran seragam
   - Dokumen tambahan
3. Update status daftar ulang:
   - Belum
   - Proses
   - Selesai

**Urutan Data:**
- Data diurutkan berdasarkan `tanggal_setuju_daftar_ulang` (terbaru dulu)
- Pendaftar yang baru setuju muncul paling atas

---

## 🎯 **RINGKASAN KOLOM DI /admin/pendaftar**

| Kolom | Deskripsi | Sumber Data |
|-------|-----------|-------------|
| **Status** | Status proses pendaftaran (menunggu, diverifikasi, dll) | Column visibility toggle |
| **Status Tes** | Hasil kelulusan tes seleksi (Lulus/Tidak Lulus/Cadangan) | Dari `/admin/kelulusan` |
| **Persetujuan Daftar Ulang** | Status persetujuan pendaftar untuk daftar ulang | Action pendaftar di dashboard |
| **Status Daftar Ulang** | Status proses daftar ulang (belum/proses/selesai) | Column visibility toggle |

---

## 📊 **DIAGRAM ALUR DATA**

```
┌────────────────┐
│   PENDAFTAR    │
│    (Public)    │
└───────┬────────┘
        │
        ▼
┌────────────────────────┐
│  /admin/pendaftar      │ ◄── DATA TERPUSAT DI SINI
│  Status: Menunggu      │
└───────┬────────────────┘
        │
        ▼
┌────────────────────────┐
│  Admin Verifikasi      │
│  Berkas                │
│  Status: Diverifikasi  │
└───────┬────────────────┘
        │
        ▼
┌────────────────────────┐
│  /admin/kelulusan      │
│  Tentukan Status Tes   │
│  (Lulus/Tidak/Cadangan)│
└───────┬────────────────┘
        │
        ▼ (jika LULUS)
┌────────────────────────┐
│  Dashboard Pendaftar   │
│  Card: Setuju Daftar   │
│  Ulang?                │
└───────┬────────────────┘
        │
        ▼ (Klik Setuju)
┌────────────────────────┐
│  /admin/pendaftar      │
│  Kolom: ✓ Sudah Setuju │
└───────┬────────────────┘
        │
        ▼ (Auto muncul)
┌────────────────────────┐
│  /admin/daftar-ulang   │
│  Verifikasi Pembayaran │
│  & Syarat Daftar Ulang │
└────────────────────────┘
```

---

## 🔑 **POIN PENTING**

### **Data Terpusat**
✅ Semua data pendaftar ada di `/admin/pendaftar`
✅ Data mengalir ke `/admin/kelulusan` untuk penentuan status tes
✅ Setelah setuju, baru muncul di `/admin/daftar-ulang`

### **Hanya yang Lulus**
✅ Card persetujuan daftar ulang **hanya muncul** jika status = LULUS
✅ `/admin/daftar-ulang` **hanya menampilkan** yang lulus dan sudah setuju

### **Admin Control**
✅ Admin bisa **batalkan persetujuan** pendaftar kapan saja
✅ Bulk update status tes untuk banyak pendaftar sekaligus
✅ Filter dan search untuk kemudahan pengelolaan

### **User Experience**
✅ Notifikasi real-time saat update status
✅ Loading indicator saat proses
✅ Konfirmasi untuk action penting
✅ Card menarik dengan animasi untuk persetujuan

---

## 🧪 **TESTING**

### **Test Scenario 1: Flow Normal (Lulus)**
1. Login admin: `/admin/pendaftar`
2. Pilih 1 pendaftar, buka `/admin/kelulusan`
3. Klik tombol "✓ Lulus"
4. Logout admin, login sebagai pendaftar tersebut
5. Lihat dashboard → Card orange "Setuju Daftar Ulang" muncul
6. Klik "Saya Setuju Daftar Ulang"
7. Card berubah hijau
8. Logout, login admin
9. Buka `/admin/pendaftar` → Kolom "Persetujuan" = "✓ Sudah Setuju"
10. Buka `/admin/daftar-ulang` → Pendaftar muncul di list

### **Test Scenario 2: Admin Batalkan**
1. Login admin: `/admin/pendaftar`
2. Cari pendaftar yang sudah setuju
3. Klik tombol "✗ Batalkan" di kolom "Persetujuan Daftar Ulang"
4. Konfirmasi
5. Status berubah → "Menunggu Persetujuan"
6. Buka `/admin/daftar-ulang` → Pendaftar hilang dari list
7. Logout, login sebagai pendaftar
8. Dashboard → Card kembali ke orange "Setuju Daftar Ulang"

### **Test Scenario 3: Bulk Update**
1. Login admin: `/admin/kelulusan`
2. Filter jalur: "Prestasi Akademik"
3. Klik "Tandai Lulus" di Aksi Massal
4. Konfirmasi → Semua berubah LULUS
5. Buka `/admin/pendaftar` → Kolom "Status Tes" semua hijau "LULUS"

---

## 📁 **FILE YANG DIUBAH**

### **Database:**
1. `database/migrations/2026_01_11_064407_add_setuju_daftar_ulang_to_pendaftar_table.php`
   - Tambah kolom `setuju_daftar_ulang` (boolean)
   - Tambah kolom `tanggal_setuju_daftar_ulang` (timestamp)

### **Models:**
2. `app/Models/Pendaftar.php`
   - Update `$fillable` dan `$casts`

3. `app/Models/Dokumen.php`
   - Fix mapping `status_akta_kelahiran` → `status_akte`

### **Components:**
4. `app/Livewire/Admin/Pendaftar.php`
   - Tambah kolom visibility: `status_tes`, `setuju_daftar_ulang`
   - Tambah method: `batalkanPersetujuanDaftarUlang()`
   - Notifikasi dengan event `statusUpdated`

5. `app/Livewire/Admin/Kelulusan.php`
   - Fix statistik query
   - Tambah bulk update
   - Error handling dan notifikasi

6. `app/Livewire/Pendaftar/Dashboard.php`
   - Tambah method: `setujuDaftarUlang()`
   - Validasi hanya untuk status lulus
   - Create record daftar ulang otomatis

7. `app/Livewire/Admin/DaftarUlang.php`
   - Filter query: `where('status_pendaftaran', 'lulus')`
   - Filter query: `where('setuju_daftar_ulang', true)`
   - Sort by: `tanggal_setuju_daftar_ulang` DESC

### **Views:**
8. `resources/views/livewire/admin/pendaftar.blade.php`
   - Tambah header kolom: Status Tes, Persetujuan Daftar Ulang
   - Tambah body kolom dengan badge dan tombol
   - Tambah toggle visibility
   - Tambah notifikasi dan loading overlay

9. `resources/views/livewire/admin/kelulusan.blade.php`
   - Update button aksi dengan konfirmasi dan loading
   - Tambah bulk update buttons
   - Tambah stats untuk cadangan
   - Notifikasi Alpine.js

10. `resources/views/livewire/pendaftar/dashboard.blade.php`
    - Tambah card persetujuan daftar ulang (orange, animasi)
    - Tambah card konfirmasi (hijau)
    - Tombol "Saya Setuju Daftar Ulang"
    - Notifikasi Alpine.js

---

## 🎓 **KESIMPULAN**

Sistem ini memastikan:
1. ✅ **Data terpusat** di `/admin/pendaftar`
2. ✅ **Alur jelas**: Pendaftaran → Verifikasi → Tes → Persetujuan → Daftar Ulang
3. ✅ **Kontrol admin**: Bisa batalkan persetujuan kapan saja
4. ✅ **User-friendly**: Card menarik, notifikasi jelas, konfirmasi untuk action penting
5. ✅ **Efisien**: Bulk update untuk banyak pendaftar sekaligus

---

**Update Terakhir:** 11 Januari 2026
**Status:** ✅ Selesai dan Berfungsi
