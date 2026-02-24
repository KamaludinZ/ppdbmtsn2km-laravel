# FIX EXPORT WILAYAH KOSONG - NESTED IF PROBLEM - 12 Februari 2026

## Root Cause - DITEMUKAN! ✅

Masalahnya adalah **NESTED IF yang terlalu strict** di kode export. Data wilayah hanya ditampilkan jika SEMUA kondisi terpenuhi:

### Alamat Ayah (Line 291-308 SEBELUM)
```php
if ($pendaftar->ayah_status === 'masih_hidup') {
    if (!$pendaftar->ayah_tinggal_luar_negeri) {  // <-- MASALAH DI SINI
        // Tampilkan provinsi, kabupaten, dll
    } else {
        // Kosongkan 9 kolom
    }
}
```

**Kondisi yang harus terpenuhi:**
1. `ayah_status === 'masih_hidup'` ✅
2. `ayah_tinggal_luar_negeri === false/0` ❌ STRICT!

**Masalah:**
- Jika `ayah_tinggal_luar_negeri` bernilai `true`, `1`, atau `NULL` → wilayah KOSONG
- Meskipun data `ayah_provinsi` ada di database → tidak ditampilkan

### Alamat Ibu (Line 313-331 SEBELUM)
```php
if ($pendaftar->ibu_status === 'masih_hidup') {
    if (!$pendaftar->ibu_sama_dengan_ayah) {  // <-- NESTED 1
        if (!$pendaftar->ibu_tinggal_luar_negeri) {  // <-- NESTED 2
            // Tampilkan provinsi, kabupaten, dll
        }
    }
}
```

**Kondisi yang harus terpenuhi:**
1. `ibu_status === 'masih_hidup'` ✅
2. `ibu_sama_dengan_ayah === false/0` ❌ STRICT!
3. `ibu_tinggal_luar_negeri === false/0` ❌ STRICT!

**Masalah:**
- Triple nested if! Jika salah satu kondisi tidak terpenuhi → wilayah KOSONG
- Meskipun data `ibu_provinsi` ada → tidak ditampilkan

### Alamat Wali (Line 333-359 SEBELUM)
```php
if ($pendaftar->wali_status_wali === 'lainnya') {
    if (!$pendaftar->wali_sama_dengan_ayah) {
        if (!$pendaftar->wali_tinggal_luar_negeri) {
            // Tampilkan provinsi, kabupaten, dll
        }
    }
}
```

**Masalah sama:** Triple nested if yang strict.

## Solusi

### Hapus Nested If - Tampilkan Data Jika Ada! ✅

**Prinsip:** Export seharusnya menampilkan SEMUA data yang ada, terlepas dari kondisi logika bisnis lainnya.

### SESUDAH - Alamat Ayah (Line 291-308)
```php
if ($pendaftar->ayah_status === 'masih_hidup') {
    $baseData = array_merge($baseData, [
        $this->formatBoolean($pendaftar->ayah_tinggal_luar_negeri, 'Ya', 'Tidak'),
        // Always show address data if exists (regardless of tinggal_luar_negeri)
        $pendaftar->ayah_status_kepemilikan_rumah,
        $this->getRegionName($pendaftar->ayah_provinsi),
        $this->getRegionName($pendaftar->ayah_kabupaten),
        $this->getRegionName($pendaftar->ayah_kecamatan),
        $this->getRegionName($pendaftar->ayah_kelurahan),
        $pendaftar->ayah_rt,
        $pendaftar->ayah_rw,
        $pendaftar->alamat_ayah,
        $pendaftar->ayah_kode_pos,
    ]);
}
```

**Perubahan:**
- ✅ Hapus `if (!$pendaftar->ayah_tinggal_luar_negeri)`
- ✅ SELALU tampilkan data wilayah jika ada
- ✅ Kolom `ayah_tinggal_luar_negeri` tetap ditampilkan (Ya/Tidak)

### SESUDAH - Alamat Ibu (Line 313-331)
```php
if ($pendaftar->ibu_status === 'masih_hidup') {
    $baseData = array_merge($baseData, [
        $this->formatBoolean($pendaftar->ibu_sama_dengan_ayah, 'Sama dengan Ayah', 'Berbeda'),
        $this->formatBoolean($pendaftar->ibu_tinggal_luar_negeri, 'Ya', 'Tidak'),
        // Always show address data if exists (regardless of sama_dengan_ayah or tinggal_luar_negeri)
        $pendaftar->ibu_status_kepemilikan_rumah,
        $this->getRegionName($pendaftar->ibu_provinsi),
        $this->getRegionName($pendaftar->ibu_kabupaten),
        $this->getRegionName($pendaftar->ibu_kecamatan),
        $this->getRegionName($pendaftar->ibu_kelurahan),
        $pendaftar->ibu_rt,
        $pendaftar->ibu_rw,
        $pendaftar->alamat_ibu,
        $pendaftar->ibu_kode_pos,
    ]);
}
```

**Perubahan:**
- ✅ Hapus nested `if (!$pendaftar->ibu_sama_dengan_ayah)`
- ✅ Hapus nested `if (!$pendaftar->ibu_tinggal_luar_negeri)`
- ✅ SELALU tampilkan data wilayah jika ada
- ✅ Kedua kolom flag tetap ditampilkan

### SESUDAH - Alamat Wali (Line 333-359)
```php
if ($pendaftar->wali_status_wali === 'lainnya') {
    $baseData = array_merge($baseData, [
        $this->formatBoolean($pendaftar->wali_sama_dengan_ayah, 'Sama dengan Ayah', 'Berbeda'),
        $this->formatBoolean($pendaftar->wali_tinggal_luar_negeri, 'Ya', 'Tidak'),
        // Always show address data if exists (regardless of sama_dengan_ayah or tinggal_luar_negeri)
        $pendaftar->wali_status_kepemilikan_rumah,
        $this->getRegionName($pendaftar->wali_provinsi),
        $this->getRegionName($pendaftar->wali_kabupaten),
        $this->getRegionName($pendaftar->wali_kecamatan),
        $this->getRegionName($pendaftar->wali_kelurahan),
        $pendaftar->wali_rt,
        $pendaftar->wali_rw,
        $pendaftar->alamat_wali,
        $pendaftar->wali_kode_pos,
    ]);
}
```

**Perubahan sama dengan Ibu.**

### Alamat Siswa
Sudah benar dari sebelumnya (tidak ada nested if untuk tinggal_luar_negeri).

## File yang Diubah

### app/Exports/PendaftarExport.php

1. **Line 291-308**: Alamat Ayah - hapus nested if tinggal_luar_negeri
2. **Line 313-331**: Alamat Ibu - hapus nested if sama_dengan_ayah dan tinggal_luar_negeri
3. **Line 333-359**: Alamat Wali - hapus nested if sama_dengan_ayah dan tinggal_luar_negeri

## Expected Result

### SEBELUM (dengan nested if):
```
Provinsi Ayah: (kosong)
Kabupaten Ayah: (kosong)
Provinsi Ibu: (kosong)
Kabupaten Ibu: (kosong)
```

### SESUDAH (tanpa nested if):
```
Provinsi Ayah: Jawa Timur
Kabupaten Ayah: Kota Malang
Kecamatan Ayah: Lowokwaru
Kelurahan Ayah: Tunggulwulung
Provinsi Ibu: Jawa Timur
Kabupaten Ibu: Kota Malang
Kecamatan Ibu: Lowokwaru
Kelurahan Ibu: Mojolangu
```

## Impact

**Positif:**
- ✅ Data wilayah sekarang SELALU ditampilkan jika ada di database
- ✅ Export lebih lengkap dan informatif
- ✅ Tidak ada data yang "tersembunyi" karena kondisi flag
- ✅ Memudahkan analisis data dan import ke sistem lain

**Tidak Ada Breaking Changes:**
- Struktur kolom tetap sama
- Headings tidak berubah
- Data yang NULL tetap ditampilkan kosong
- Backward compatible

## Cara Test

1. Login sebagai Admin/Panitia
2. Export data pendaftar lengkap
3. Buka file Excel
4. Cari pendaftar yang punya data wilayah di database
5. Verifikasi kolom provinsi/kabupaten/kecamatan/kelurahan MUNCUL dengan NAMA
6. Cek untuk ayah, ibu, wali, dan siswa

## Upload ke Production

Jalankan:
```
upload_fix_export_wilayah_nested_if_12_feb_2026.bat
```

## Notes

- Nested if yang dihapus adalah untuk kondisi "tinggal_luar_negeri" dan "sama_dengan_ayah"
- Kolom flag tetap ditampilkan (Ya/Tidak atau Sama dengan Ayah/Berbeda)
- Logika validasi di formulir pendaftaran TIDAK berubah
- Ini HANYA untuk export agar data lebih lengkap

## Debug Script (Opsional)

Jika masih bermasalah, upload `debug_wilayah_export.php` ke production dan akses via browser untuk melihat data mentah.

## Related Fixes

- FIX_EXPORT_LAPORAN_PENDAFTAR_COMPLETE_12_FEB_2026.md
- FIX_EXPORT_WILAYAH_KOSONG_12_FEB_2026.md (trim + fallback)
