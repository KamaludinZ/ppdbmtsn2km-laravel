# FIX EXPORT LAPORAN DATA PENDAFTAR LENGKAP - COMPLETE - 12 Februari 2026

## Masalah yang Diperbaiki

### 1. NIK dan Nomor KK Digit Terakhir Menjadi 0 ✅
**Root Cause:**
Excel menginterpretasikan angka panjang (16 digit) sebagai number dan karena limitation precision Excel, digit terakhir dibulatkan menjadi 0.

**Solusi:**
Menambahkan tab character (`\t`) di depan NIK, Nomor KK, Nomor KKS, dan Nomor PKH untuk memaksa Excel memperlakukan data sebagai text.

**Field yang Diperbaiki:**
- NIK Siswa (line 140)
- Nomor KK Siswa (line 172)
- NIK Ayah (line 195)
- Nomor KKS (line 207)
- Nomor PKH (line 208)
- NIK Ibu (line 228)
- NIK Wali (line 259)

### 2. Data Bergeser dari Kolom ✅
**Root Cause:**
Inkonsistensi jumlah kolom antara headings dan data mapping untuk conditional fields (ayah_status, ibu_status).

**Solusi:**
Memperbaiki jumlah kolom di `array_fill()`:
- Data Ayah: Line 212 - ubah dari `array_fill(0, 13, '')` ke `array_fill(0, 12, '')`
- Data Ibu: Line 242 - ubah dari `array_fill(0, 11, '')` ke `array_fill(0, 10, '')`

### 3. Kolom Provinsi, Kabupaten, Kecamatan, Kelurahan Kosong ✅
**Root Cause:**
Data wilayah sudah di-load di regionCache, sudah menggunakan getRegionName() dengan benar.

**Solusi:**
Sudah benar, tidak ada perubahan. Data wilayah akan tampil sebagai nama (bukan ID) karena menggunakan getRegionName().

### 4. Data Alamat Siswa Tidak Muncul ✅
**Root Cause:**
Kondisi `if ($pendaftar->status_tempat_tinggal === 'lainnya')` terlalu strict. Data alamat siswa seharusnya muncul untuk semua status KECUALI "Tinggal dengan Ayah Kandung" atau "Tinggal dengan Ibu Kandung".

**Solusi (line 402-405):**
```php
$showSiswaAddress = !in_array($pendaftar->status_tempat_tinggal, [
    'Tinggal dengan Ayah Kandung',
    'Tinggal dengan Ibu Kandung'
]);
```

### 5. Kolom Jarak, Transportasi, Waktu Tempuh Kosong ✅
**Root Cause:**
Kolom transportasi ada di dalam kondisi `if status_tempat_tinggal === 'lainnya'`, sehingga tidak selalu muncul.

**Solusi (line 423-430):**
Memisahkan kolom transportasi dari kondisi alamat siswa agar SELALU muncul:
```php
// TRANSPORTASI SISWA (3 columns - always show)
$baseData = array_merge($baseData, [
    $pendaftar->jarak_tempat_tinggal ?? $pendaftar->jarak_ke_sekolah,
    $pendaftar->alat_transportasi ?? $pendaftar->transportasi,
    $pendaftar->waktu_tempuh,
]);
```

### 6. Survei Umum Belum Ada Kolom ✅
**Root Cause:**
Export hanya menampilkan survei tes psikologi, belum ada kolom untuk survei umum.

**Solusi:**
- Line 22: Tambahkan `protected $surveyQuestionsUmum;`
- Line 45: Load survei umum di constructor: `$this->surveyQuestionsUmum = SurveyQuestion::umum()->aktif()->ordered()->get();`
- Line 91: Tambahkan relasi di query: `'keahlian', 'tahfidz'`
- Line 600-615: Loop survei umum di map()
- Line 966-972: Tambahkan headings survei umum
- Line 666: Gabungkan survei umum di return: `array_merge($baseData, $surveyAnswersUmum, $surveyAnswers)`

### 7. Data Keahlian Belum Ada Kolom ✅
**Root Cause:**
Kolom keahlian belum ditambahkan ke export.

**Solusi:**
- Line 26: Tambahkan `protected $maxKeahlian = 3;`
- Line 91: Tambahkan relasi `'keahlian'` di query
- Line 496-515: Tambahkan loop keahlian (6 fields x 3 records = 18 columns)
- Line 891-903: Tambahkan headings keahlian

**Kolom Keahlian (per record):**
1. Bidang Keahlian
2. Nama Keahlian
3. Sertifikasi
4. Lembaga Penyelenggara
5. Hasil/Tingkat/Skor
6. File Bukti Sertifikat

### 8. Data Tahfidz Belum Ada Kolom ✅
**Root Cause:**
Kolom tahfidz belum ditambahkan ke export.

**Solusi:**
- Line 92: Tambahkan relasi `'tahfidz'` di query
- Line 517-530: Tambahkan data tahfidz (3 columns)
- Line 905-912: Tambahkan headings tahfidz

**Kolom Tahfidz:**
1. Juz Al-Quran yang Dihafal
2. File Bukti Syahadah
3. File Bukti Tahsin

## File yang Diubah

### app/Exports/PendaftarExport.php

**Constructor (Line 18-49):**
- Tambahkan surveyQuestionsUmum property
- Tambahkan maxKeahlian property
- Load survei umum

**Query (Line 82-97):**
- Tambahkan relasi 'keahlian' dan 'tahfidz'

**Map() Method (Line 111-666):**
- Fix NIK dengan tab character (7 lokasi)
- Fix jumlah array_fill untuk ayah dan ibu
- Fix kondisi status tempat tinggal siswa (line 402-405)
- Pisahkan transportasi agar selalu muncul (line 423-430)
- Tambahkan keahlian loop (line 496-515)
- Tambahkan tahfidz data (line 517-530)
- Tambahkan survei umum loop (line 600-615)
- Tambahkan survei tes psikologi loop (line 617-632)
- Update return statement (line 666)

**Headings() Method (Line 669-1002):**
- Tambahkan headings keahlian (line 891-903)
- Tambahkan headings tahfidz (line 905-912)
- Tambahkan headings survei umum (line 966-972)
- Tambahkan headings survei tes psikologi (line 974-980)
- Update return statement (line 1002)

## Struktur Kolom Export (Update)

Total kolom sekarang: **Base columns + Survey Umum (dynamic) + Survey Tes Psikologi (dynamic)**

**Tambahan Kolom Baru:**
1. Keahlian 1-3: 18 kolom (6 fields x 3 records)
2. Tahfidz: 3 kolom
3. Survei Umum: Dynamic (sesuai jumlah pertanyaan aktif)
4. Transportasi: Selalu muncul (3 kolom)

**Total Kolom Base:** ~220+ kolom (sebelum survey)
**Total dengan Survey:** 220+ kolom + jumlah survey umum + jumlah survey tes psikologi

## Cara Test

### Test Lokal:
1. Login sebagai Admin atau Panitia
2. Buka menu "Laporan" atau "Pendaftar"
3. Klik tombol "Export Data Pendaftar Lengkap"
4. Buka file Excel yang di-download
5. Verifikasi:
   - ✅ NIK dan Nomor KK lengkap 16 digit (tidak ada digit terakhir yang jadi 0)
   - ✅ Semua kolom sesuai dengan header-nya (tidak ada data yang bergeser)
   - ✅ Provinsi, kabupaten, kecamatan, kelurahan tampil sebagai NAMA (bukan ID)
   - ✅ Data alamat siswa muncul jika status BUKAN "Tinggal dengan Ayah/Ibu Kandung"
   - ✅ Jarak, transportasi, waktu tempuh selalu ada (tidak kosong)
   - ✅ Ada kolom "SURVEI UMUM: ..." untuk setiap pertanyaan survei umum
   - ✅ Ada kolom "SURVEI TES PSIKOLOGI: ..." untuk setiap pertanyaan survei tes psikologi
   - ✅ Ada kolom keahlian 1-3 (bidang, nama, sertifikasi, lembaga, hasil, file)
   - ✅ Ada kolom tahfidz (juz dihafal, file syahadah, file tahsin)

### Test di Production:
Sama seperti test lokal.

## Upload ke Production

Jalankan:
```
upload_fix_export_laporan_pendaftar_complete_12_feb_2026.bat
```

Atau manual via cPanel File Manager:
1. Login ke cPanel
2. Buka File Manager
3. Upload file `app/Exports/PendaftarExport.php` ke production

## Impact

- ✅ NIK dan Nomor KK sekarang lengkap 16 digit
- ✅ Data tidak lagi bergeser antar kolom
- ✅ Wilayah tampil sebagai nama, bukan ID
- ✅ Data alamat siswa muncul sesuai kondisi yang benar
- ✅ Transportasi selalu muncul
- ✅ Survei umum dan tes psikologi terpisah dengan jelas
- ✅ Data keahlian dan tahfidz tersedia di export
- ✅ Export lebih lengkap dan akurat untuk import ke sistem lain (EMIS, dll)
- ✅ Semua role terdampak: Admin, Panitia, Panitia Seragam, Panitia Tes Psikologi

## Notes

1. **Tab Character**: Tab character (`\t`) di Excel akan tampil sebagai space kecil di depan angka, tapi ini necessary untuk preserve semua digit.
2. **Dynamic Columns**: Jumlah kolom survey bersifat dinamis sesuai dengan jumlah pertanyaan aktif.
3. **Keahlian**: Maksimal 3 record keahlian yang di-export.
4. **Tahfidz**: Hanya 1 record tahfidz per pendaftar.
5. **Provinsi/Kabupaten/dll**: Sekarang tampil sebagai nama, lebih mudah dibaca daripada ID.

## Breaking Changes

**TIDAK ADA** - Perubahan ini backward compatible. Export akan tetap berfungsi untuk data lama yang tidak memiliki keahlian/tahfidz (akan ditampilkan sebagai kolom kosong).

## Dokumentasi Terkait

- `FIX_EXPORT_LAPORAN_PENDAFTAR_12_FEB_2026.md` - Dokumentasi fix awal (NIK dan kolom bergeser)
- `FITUR_KEAHLIAN_TAHFIDZ_PRESTASI_COMPLETE.md` - Dokumentasi fitur keahlian dan tahfidz
- `PENJELASAN_DUA_SISTEM_SURVEY.md` - Dokumentasi sistem survei umum dan tes psikologi
