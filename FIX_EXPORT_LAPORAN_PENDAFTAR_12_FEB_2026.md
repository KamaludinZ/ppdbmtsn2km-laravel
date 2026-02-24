# FIX EXPORT LAPORAN DATA PENDAFTAR LENGKAP - 12 Februari 2026

## Masalah yang Diperbaiki

### 1. NIK dan Nomor KK Digit Terakhir Menjadi 0
**Root Cause:**
Excel menginterpretasikan angka panjang (16 digit) sebagai number dan karena limitation precision Excel, digit terakhir dibulatkan menjadi 0.

**Solusi:**
Menambahkan tab character (`\t`) di depan NIK, Nomor KK, Nomor KKS, dan Nomor PKH untuk memaksa Excel memperlakukan data sebagai text.

**Field yang Diperbaiki:**
- NIK Siswa
- Nomor KK Siswa
- NIK Ayah
- Nomor KKS
- Nomor PKH
- NIK Ibu
- NIK Wali

### 2. Data Bergeser dari Kolom (Kolom Tidak Sesuai)
**Root Cause:**
Inkonsistensi jumlah kolom antara headings dan data mapping untuk conditional fields (ayah_status, ibu_status).

**Solusi:**
Memperbaiki jumlah kolom di `array_fill()` agar sesuai dengan jumlah headings:

**Perbaikan Detail:**
- **Data Ayah**: Line 212 - ubah dari `array_fill(0, 13, '')` ke `array_fill(0, 12, '')`
  - Headings: 14 kolom total (Nama Ayah + Status Ayah + 12 kolom detail)
  - Data jika masih hidup: 2 + 12 = 14 kolom ✓
  - Data jika tidak hidup: 2 + 12 = 14 kolom ✓

- **Data Ibu**: Line 242 - ubah dari `array_fill(0, 11, '')` ke `array_fill(0, 10, '')`
  - Headings: 12 kolom total (Nama Ibu + Status Ibu + 10 kolom detail)
  - Data jika masih hidup: 2 + 10 = 12 kolom ✓
  - Data jika tidak hidup: 2 + 10 = 12 kolom ✓

## File yang Diubah

### app/Exports/PendaftarExport.php
1. **Line 140**: NIK siswa + tab character
2. **Line 172**: Nomor KK siswa + tab character
3. **Line 195**: NIK ayah + tab character
4. **Line 207-208**: Nomor KKS dan PKH + tab character
5. **Line 212**: Fix jumlah kolom untuk ayah (13 → 12)
6. **Line 228**: NIK ibu + tab character
7. **Line 242**: Fix jumlah kolom untuk ibu (11 → 10)
8. **Line 259**: NIK wali + tab character

## Cara Test

1. Login sebagai Admin atau Panitia
2. Buka menu "Laporan" atau "Pendaftar"
3. Klik tombol "Export Data Pendaftar Lengkap"
4. Buka file Excel yang di-download
5. Verifikasi:
   - NIK dan Nomor KK lengkap 16 digit (tidak ada digit terakhir yang jadi 0)
   - Semua kolom sesuai dengan header-nya (tidak ada data yang bergeser)
   - Data ayah/ibu/wali yang tidak hidup atau tidak ada tetap aligned dengan header

## Upload ke Production

Jalankan:
```
upload_fix_export_laporan_pendaftar_12_feb_2026.bat
```

## Impact

- ✅ NIK dan Nomor KK sekarang lengkap 16 digit
- ✅ Data tidak lagi bergeser antar kolom
- ✅ Export lebih akurat untuk import ke sistem lain (EMIS, dll)
- ✅ Semua role terdampak: Admin, Panitia, Panitia Seragam, Panitia Tes Psikologi

## Notes

Tab character (`\t`) di Excel akan tampil sebagai space kecil di depan angka, tapi ini necessary untuk preserve semua digit. Ini adalah best practice untuk export data numerik panjang ke Excel.
