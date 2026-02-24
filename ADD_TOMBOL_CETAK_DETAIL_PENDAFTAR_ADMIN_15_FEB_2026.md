# Penambahan Tombol Cetak di Detail Pendaftar Admin
**Tanggal**: 15 Februari 2026
**Jenis Perubahan**: Enhancement - Fitur Baru
**Status**: ✅ Completed & Ready for Production

---

## Ringkasan Perubahan

Menambahkan section tombol cetak di halaman detail pendaftar admin (`/admin/pendaftar/{id}`) untuk memudahkan admin mencetak dokumen-dokumen penting seperti kartu tes, invoice tes psikologi, invoice daftar ulang madrasah, dan invoice daftar ulang seragam.

**Semua tombol menggunakan link dengan `target="_blank"`** yang membuka preview di tab baru, bukan download langsung.

Tombol-tombol ini akan **tetap menampilkan data seadanya** meskipun data belum lengkap atau masih kosong, sehingga admin dapat mencetak kapan saja.

---

## Fitur yang Ditambahkan

### 1. Section Tombol Cetak di Header Info
- **Lokasi**: Di bawah informasi jalur pendaftaran di header detail pendaftar
- **Tampilan**: Grid responsive (1 kolom mobile, 2 kolom tablet, 4 kolom desktop)
- **Implementasi**: Link `<a>` dengan `target="_blank"` untuk membuka di tab baru
- **Tombol**:
  1. **Kartu Tes** (Biru) - Membuka preview kartu tes seleksi di tab baru
  2. **Invoice Psikologi** (Ungu) - Menampilkan PDF invoice tes psikologi di tab baru
  3. **Invoice Madrasah** (Hijau) - Menampilkan PDF invoice daftar ulang madrasah di tab baru
  4. **Invoice Seragam** (Orange) - Menampilkan PDF invoice daftar ulang seragam di tab baru

### 2. Controller Baru: PreviewInvoiceController.php

Controller baru di namespace `App\Http\Controllers\Admin` untuk handle preview invoice.

**Method**:

#### a. `previewInvoicePsikologi($pendaftar_id)`
- Generate PDF invoice tes psikologi
- Menggunakan library Barryvdh DomPDF
- Format: A5 Landscape
- Tetap generate meskipun data belum lengkap (opsi dan bank bisa null)
- Route: `/admin/preview-invoice-psikologi/{pendaftar_id}`

#### b. `previewInvoiceMadrasah($pendaftar_id)`
- Generate PDF invoice daftar ulang madrasah
- Menggunakan library Barryvdh DomPDF
- Format: A5 Landscape
- Tetap generate meskipun data daftar ulang belum ada
- Route: `/admin/preview-invoice-madrasah/{pendaftar_id}`

#### c. `previewInvoiceSeragam($pendaftar_id)`
- Generate PDF invoice daftar ulang seragam
- Menggunakan library Barryvdh DomPDF
- Format: A5 Landscape
- Tetap generate meskipun data seragam belum ada
- Route: `/admin/preview-invoice-seragam/{pendaftar_id}`

### 3. Component Baru: PreviewKartuTes.php

Component Livewire baru di namespace `App\Livewire\Admin` untuk menampilkan preview kartu tes seleksi dari sisi admin.

**Fitur**:
- Menggunakan view yang sama dengan pendaftar (`livewire.pendaftar.kartu-tes-seleksi`)
- Load data pendaftar berdasarkan `pendaftar_id` yang dikirim dari admin
- Generate alamat lengkap otomatis
- Menampilkan jadwal tes dan kartu kendali
- Support link grup WhatsApp/Telegram (jika diaktifkan)
- Route: `/admin/preview-kartu-tes/{pendaftar_id}`

### 4. Route Baru

```php
// Preview Kartu Tes (Livewire Component)
Route::get('/preview-kartu-tes/{pendaftar_id}', \App\Livewire\Admin\PreviewKartuTes::class)
    ->name('admin.preview-kartu-tes');

// Preview Invoice (Controller Methods)
Route::get('/preview-invoice-psikologi/{pendaftar_id}', [\App\Http\Controllers\Admin\PreviewInvoiceController::class, 'previewInvoicePsikologi'])
    ->name('admin.preview-invoice-psikologi');
Route::get('/preview-invoice-madrasah/{pendaftar_id}', [\App\Http\Controllers\Admin\PreviewInvoiceController::class, 'previewInvoiceMadrasah'])
    ->name('admin.preview-invoice-madrasah');
Route::get('/preview-invoice-seragam/{pendaftar_id}', [\App\Http\Controllers\Admin\PreviewInvoiceController::class, 'previewInvoiceSeragam'])
    ->name('admin.preview-invoice-seragam');
```

---

## File yang Dimodifikasi

### 1. **resources/views/livewire/admin/detail-pendaftar.blade.php**
- **Baris 209-252**: Menambahkan section tombol cetak dengan 4 link (bukan button)
- Menggunakan `<a>` tag dengan `target="_blank"` untuk membuka di tab baru
- Setiap link menggunakan route yang sesuai dengan parameter `pendaftar_id`

### 2. **app/Livewire/Admin/DetailPendaftar.php**
- Tidak ada method yang ditambahkan (clean code)
- Semua logic preview dipindahkan ke Controller terpisah

### 3. **routes/web.php**
- **Baris 273-276**: Menambahkan 4 route baru untuk preview

## File yang Dibuat

### 1. **app/Http/Controllers/Admin/PreviewInvoiceController.php**
- Controller baru untuk handle preview invoice
- 3 method untuk preview invoice psikologi, madrasah, dan seragam

### 2. **app/Livewire/Admin/PreviewKartuTes.php**
- Component Livewire baru untuk preview kartu tes dari admin
- Reuse view pendaftar untuk konsistensi tampilan
- Menggunakan layout print (clean, tanpa sidebar)

### 3. **resources/views/components/layouts/print.blade.php**
- Layout baru khusus untuk preview/print
- Tidak ada sidebar, header, atau navigation
- Hanya konten utama untuk keperluan cetak
- Mendukung print-friendly styling

---

## Cara Testing

### 1. Testing Tombol Cetak Kartu Tes
1. Login sebagai admin
2. Buka halaman detail pendaftar: `/admin/pendaftar/{id}` (contoh: `/admin/pendaftar/348`)
3. Scroll ke section **"Cetak Dokumen"** di bawah header info
4. Klik tombol **"Kartu Tes"** (biru)
5. **Tab baru akan terbuka** menampilkan kartu tes seleksi
6. Verifikasi data yang ditampilkan
7. Gunakan Ctrl+P atau tombol "Cetak Kartu" untuk print

### 2. Testing Tombol Invoice Tes Psikologi
1. Di halaman detail pendaftar, klik tombol **"Invoice Psikologi"** (ungu)
2. **Tab baru akan terbuka** menampilkan PDF invoice tes psikologi
3. Verifikasi data yang ditampilkan (nama, nomor pendaftaran, opsi tes, bank, dll)
4. Cek apakah PDF tetap ditampilkan meskipun data belum lengkap
5. Gunakan fitur print browser untuk mencetak

### 3. Testing Tombol Invoice Madrasah
1. Klik tombol **"Invoice Madrasah"** (hijau)
2. **Tab baru akan terbuka** menampilkan PDF invoice daftar ulang madrasah
3. Verifikasi data nominal pembayaran dan bank
4. Cek apakah PDF tetap ditampilkan meskipun data daftar ulang belum ada
5. Gunakan fitur print browser untuk mencetak

### 4. Testing Tombol Invoice Seragam
1. Klik tombol **"Invoice Seragam"** (orange)
2. **Tab baru akan terbuka** menampilkan PDF invoice daftar ulang seragam
3. Verifikasi data ukuran seragam dan nominal pembayaran
4. Cek apakah PDF tetap ditampilkan meskipun data seragam belum ada
5. Gunakan fitur print browser untuk mencetak

### 5. Testing dengan Data Kosong
1. Pilih pendaftar yang belum melakukan tes psikologi atau daftar ulang
2. Klik semua tombol invoice
3. Verifikasi bahwa PDF tetap ter-generate dengan field kosong atau nilai default
4. Pastikan tidak ada error 500 atau crash

---

## Catatan Penting

1. **Membuka di Tab Baru**: Semua tombol menggunakan `target="_blank"` sehingga membuka preview di tab baru, bukan download langsung

2. **Data Seadanya**: Semua tombol akan tetap berfungsi dan menampilkan dokumen meskipun data belum lengkap. Jika ada field yang kosong, akan ditampilkan dengan nilai default atau "-"

3. **Tidak Ada Validasi Status**: Berbeda dengan fitur cetak di sisi pendaftar yang memerlukan status tertentu, di sisi admin tidak ada validasi status sehingga bisa dicetak kapan saja

4. **PDF Inline Display**: Tombol invoice akan menampilkan PDF secara inline di browser (bukan download langsung) sehingga bisa di-preview terlebih dahulu

5. **Reuse View**: Component PreviewKartuTes menggunakan view yang sama dengan pendaftar untuk konsistensi tampilan

6. **Clean Code**: Method preview tidak ada di DetailPendaftar Livewire component untuk menjaga single responsibility - semua logic preview ada di Controller terpisah

7. **Layout Print Khusus**: Preview kartu tes menggunakan layout print yang baru (`components.layouts.print`) - tidak ada sidebar, header, atau navigation. Hanya konten kartu saja untuk keperluan preview dan print

---

## Arsitektur Keputusan

### Kenapa Menggunakan Link `<a>` dengan `target="_blank"`?
- Lebih sederhana dan native browser behavior
- Tidak perlu event listener JavaScript tambahan
- Lebih SEO friendly (walau untuk admin)
- Support middle-click dan right-click untuk open in new tab/window

### Kenapa Invoice Menggunakan Controller, Bukan Livewire?
- PDF generation cocok di Controller (stateless)
- Livewire component lebih cocok untuk interactive UI
- Memisahkan concern: Livewire untuk UI, Controller untuk PDF
- Lebih mudah di-cache dan di-optimize

### Kenapa Kartu Tes Menggunakan Livewire Component?
- Reuse existing view (`kartu-tes-seleksi.blade.php`)
- View ini sudah memiliki struktur HTML lengkap untuk print
- Konsistensi tampilan antara pendaftar dan admin
- Support untuk fitur tambahan seperti link grup

### Kenapa Membuat Layout Print Baru?
- Layout `public` masih memiliki header dan navigation
- Layout `dashboard` memiliki sidebar yang tidak perlu untuk print
- Layout `print` khusus untuk preview/cetak tanpa elemen UI tambahan
- Lebih ringan dan fokus pada konten yang akan dicetak
- Memudahkan print dengan Ctrl+P tanpa elemen yang mengganggu

---

## Dependensi

- Barryvdh DomPDF (sudah terinstall) - untuk generate PDF
- Livewire 3.x - untuk component PreviewKartuTes
- TailwindCSS - untuk styling tombol

---

## Screenshot Lokasi Tombol

```
┌─────────────────────────────────────────────────────────┐
│ Header Info (Foto, Nama, NISN, Jalur, Status)          │
├─────────────────────────────────────────────────────────┤
│ Cetak Dokumen                                           │
│ ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐  │
│ │ Kartu    │ │ Invoice  │ │ Invoice  │ │ Invoice  │  │
│ │ Tes      │ │ Psikologi│ │ Madrasah │ │ Seragam  │  │
│ │ (Biru)   │ │ (Ungu)   │ │ (Hijau)  │ │ (Orange) │  │
│ └──────────┘ └──────────┘ └──────────┘ └──────────┘  │
└─────────────────────────────────────────────────────────┘
```

---

## Rencana Pengembangan Selanjutnya

1. Tambahkan tombol download (selain preview) untuk semua dokumen
2. Tambahkan log aktivitas pencetakan dokumen oleh admin
3. Tambahkan watermark "DRAFT" jika data belum lengkap/disetujui
4. Tambahkan batch print untuk multiple pendaftar
5. Tambahkan preview thumbnail sebelum membuka tab baru

---

## Perbandingan: Before vs After

### Before
- Admin harus masuk ke halaman pendaftar untuk melihat dokumen
- Tidak ada cara cepat untuk preview invoice
- Harus copy-paste URL manual untuk preview

### After
- Admin bisa langsung klik tombol untuk preview dokumen
- Semua dokumen bisa diakses dari satu tempat
- Preview terbuka di tab baru, tidak menggangu workflow
- Mendukung data kosong/belum lengkap

---

**Status**: ✅ Completed
**Tested**: ⏳ Pending Testing di Production
**Ready to Deploy**: ✅ Yes
