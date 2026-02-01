# Laporan Tes Psikologi - Update Filter & Export Excel

## Ringkasan Perubahan
Telah dilakukan perbaikan pada halaman **Laporan Tes Psikologi** untuk Admin dan Panitia sesuai permintaan:

✅ **Filter Tanggal Dihapus** - Filter "Tanggal Mulai" dan "Tanggal Akhir" telah dihapus dari tab Tes Psikologi
✅ **Tombol Export Excel Ditambahkan** - Tombol export Excel ditambahkan untuk mengekspor data tes psikologi

## Lokasi Perubahan

### 1. File Baru yang Dibuat

#### `app/Exports/TesPsikologiExport.php` ✨ BARU
Export class untuk mengekspor data tes psikologi ke Excel dengan kolom:
- No. Pendaftaran
- Nama Lengkap
- NISN
- Jalur Pendaftaran
- Opsi Tes Psikologi
- Bank Transfer
- Waktu Transfer
- Tanggal Upload Bukti
- Status Verifikasi
- Catatan Verifikasi
- Tanggal Verifikasi
- Diverifikasi Oleh
- Email Pendaftar
- No. HP
- **Status Survey** ✨ (jika survey aktif)
- **[Kolom Dinamis Pertanyaan Survey]** ✨ (satu kolom per pertanyaan aktif)

### 2. File Backend yang Dimodifikasi

#### `app/Livewire/Admin/Laporan.php`
**Perubahan:**
1. ✅ Menambahkan import `use App\Exports\TesPsikologiExport;`
2. ✅ Menghapus filter tanggal dari method `getTesPsikologiStats()`:
   - Menghapus `->when($this->tanggalMulai, ...)`
   - Menghapus `->when($this->tanggalAkhir, ...)`
3. ✅ Menambahkan method `exportTesPsikologi()` untuk export Excel

**Kode yang Ditambahkan:**
```php
public function exportTesPsikologi()
{
    // Export tes psikologi data
    $fileName = 'laporan_tes_psikologi_' . date('Y-m-d_His') . '.xlsx';

    return Excel::download(
        new TesPsikologiExport(
            $this->filterJalur,
            $this->filterStatusTesPsikologi
        ),
        $fileName
    );
}
```

#### `app/Livewire/Panitia/Laporan.php`
**Perubahan:**
1. ✅ Menambahkan import `use App\Exports\TesPsikologiExport;`
2. ✅ Menghapus filter tanggal dari method `getTesPsikologiStats()`:
   - Menghapus `->when($this->tanggalMulai, ...)`
   - Menghapus `->when($this->tanggalAkhir, ...)`
3. ✅ Menambahkan method `exportTesPsikologi()` untuk export Excel

**Kode yang Ditambahkan:**
```php
public function exportTesPsikologi()
{
    // Export tes psikologi data
    $fileName = 'laporan_tes_psikologi_' . date('Y-m-d_His') . '.xlsx';

    return Excel::download(
        new TesPsikologiExport(
            $this->filterJalur,
            $this->filterStatusTesPsikologi
        ),
        $fileName
    );
}
```

### 3. File View yang Dimodifikasi

#### `resources/views/livewire/admin/laporan.blade.php`
**Perubahan pada Tab Tes Psikologi (baris ~1192-1233):**

**SEBELUM:**
- Grid 4 kolom dengan filter: Tanggal Mulai, Tanggal Akhir, Jalur, Status Verifikasi
- Tidak ada tombol export

**SESUDAH:**
- Grid 2 kolom dengan filter: Jalur Pendaftaran, Status Verifikasi
- Tombol **Export Excel** di header (warna putih dengan teks purple)
- Filter tanggal dihapus sepenuhnya

#### `resources/views/livewire/panitia/laporan.blade.php`
**Perubahan pada Tab Tes Psikologi (baris ~382-426):**

**SEBELUM:**
- Grid 4 kolom dengan filter: Tanggal Mulai, Tanggal Akhir, Jalur, Status Verifikasi
- Tidak ada tombol export

**SESUDAH:**
- Grid 2 kolom dengan filter: Jalur Pendaftaran, Status Verifikasi
- Tombol **Export Excel** di header (warna putih dengan teks purple)
- Filter tanggal dihapus sepenuhnya

## Cara Menggunakan

### Akses Halaman
1. **Admin:** `/admin/laporan?activeTab=tes_psikologi`
2. **Panitia Pendaftaran:** `/panitia-pendaftaran/laporan?activeTab=tes_psikologi`

### Filter yang Tersedia
Sekarang hanya ada 2 filter pada tab Tes Psikologi:
1. **Jalur Pendaftaran** - Filter berdasarkan jalur (opsional, default: Semua Jalur)
2. **Status Verifikasi** - Filter berdasarkan status (opsional, default: Semua Status)
   - Belum Upload
   - Menunggu Verifikasi
   - Disetujui
   - Ditolak
   - Perlu Perbaikan

### Export Excel
1. Pilih filter yang diinginkan (opsional)
2. Klik tombol **Export Excel** di pojok kanan atas header
3. File Excel akan terdownload dengan nama: `laporan_tes_psikologi_YYYY-MM-DD_HHMMSS.xlsx`
4. File berisi data tes psikologi yang terfilter sesuai pilihan

## Format File Excel

File Excel yang dihasilkan memiliki struktur:
- **Header:** Background purple dengan teks putih, bold
- **Kolom:** 14 kolom data dasar + kolom survey dinamis (jika survey aktif)
- **Auto-size:** Lebar kolom otomatis menyesuaikan isi
- **Format Tanggal:** DD/MM/YYYY HH:MM

### Kolom Survey (Dinamis)
Jika survey tes psikologi aktif di `/admin/tes-psikologi?mainTab=survey`, maka kolom tambahan akan ditambahkan:

#### Status Survey
Menunjukkan status pengisian survey dengan nilai:
- **"Sudah Mengisi"** - Semua pertanyaan wajib telah dijawab
- **"Belum Lengkap (X/Y)"** - X dari Y pertanyaan wajib sudah dijawab
- **"Belum Mengisi"** - Belum ada jawaban sama sekali

#### Kolom Pertanyaan Survey
- Satu kolom untuk setiap pertanyaan survey yang aktif
- Kolom header = teks pertanyaan
- Isi kolom = jawaban pendaftar
- Untuk checkbox (multiple choice): jawaban dipisah dengan koma
- Jika belum dijawab: tanda "-"

## Catatan Penting

### ⚠️ Perbedaan dengan Tab Lain
Tab **Tes Psikologi** sekarang **TIDAK menggunakan filter tanggal**, berbeda dengan tab lain (Pendaftaran, Data Lengkap, dll) yang masih menggunakan filter tanggal. Ini sesuai dengan permintaan khusus untuk tab Tes Psikologi.

### 📊 Data yang Diekspor
Export Excel akan mengambil:
- ✅ Data sesuai filter Jalur (jika dipilih)
- ✅ Data sesuai filter Status Verifikasi (jika dipilih)
- ✅ **SEMUA periode** (tidak dibatasi tanggal)
- ✅ **Data survey** (jika fitur survey tes psikologi aktif)

### 📋 Fitur Survey Tes Psikologi
Kolom survey **hanya muncul** jika:
1. Survey tes psikologi diaktifkan di `/admin/tes-psikologi?mainTab=settings`
2. Ada pertanyaan survey yang aktif dengan context "Tes Psikologi" di `/admin/tes-psikologi?mainTab=survey`

**Manfaat:**
- Melihat status pengisian survey setiap pendaftar
- Menganalisis jawaban survey dalam satu file Excel
- Kolom otomatis menyesuaikan dengan pertanyaan yang aktif
- Format jawaban yang mudah dibaca (comma-separated untuk multiple choice)

### 🔒 Akses
- **Admin** dapat mengakses melalui `/admin/laporan`
- **Panitia** dapat mengakses melalui `/panitia-pendaftaran/laporan`, `/panitia-daftarulang/laporan`, atau `/panitia-psikologi/laporan` (tergantung role)

## Testing

### ✅ Yang Harus Ditest
1. Akses halaman `/admin/laporan?activeTab=tes_psikologi`
2. Pastikan filter tanggal tidak muncul
3. Pastikan hanya ada 2 filter: Jalur dan Status Verifikasi
4. Klik tombol "Export Excel" dan pastikan file terdownload
5. Buka file Excel dan verifikasi:
   - Header berwarna purple
   - 14 kolom data
   - Data sesuai dengan filter yang dipilih
6. Ulangi testing untuk panitia di `/panitia-pendaftaran/laporan?activeTab=tes_psikologi`

### 📋 Test Scenarios
1. **Export semua data** - Tanpa filter
2. **Export per jalur** - Pilih satu jalur
3. **Export per status** - Pilih satu status verifikasi
4. **Export dengan kombinasi filter** - Pilih jalur + status
5. **Test kolom survey:**
   - Aktifkan survey di `/admin/tes-psikologi?mainTab=settings`
   - Buat minimal 2 pertanyaan survey dengan context "Tes Psikologi"
   - Export Excel dan pastikan kolom "Status Survey" dan kolom pertanyaan muncul
   - Verifikasi status survey: "Belum Mengisi", "Belum Lengkap (X/Y)", atau "Sudah Mengisi"
   - Verifikasi jawaban survey tampil dengan benar
6. **Test tanpa survey:**
   - Nonaktifkan survey di settings
   - Export Excel dan pastikan hanya 14 kolom dasar yang muncul

## File yang Diubah

```
app/
├── Exports/
│   └── TesPsikologiExport.php          [BARU]
└── Livewire/
    ├── Admin/
    │   └── Laporan.php                  [DIMODIFIKASI]
    └── Panitia/
        └── Laporan.php                  [DIMODIFIKASI]

resources/
└── views/
    └── livewire/
        ├── admin/
        │   └── laporan.blade.php        [DIMODIFIKASI]
        └── panitia/
            └── laporan.blade.php        [DIMODIFIKASI]
```

## Status
✅ **SELESAI** - Semua perubahan telah diterapkan
📅 **Tanggal:** 29 Januari 2026
👤 **Updated by:** Claude Code

---

**Catatan:** Perubahan ini hanya mempengaruhi tab **Tes Psikologi** pada halaman Laporan. Tab lain (Pendaftaran, Data Lengkap, Daftar Ulang, Seragam) tetap menggunakan filter tanggal seperti sebelumnya.
