# FIX EXPORT WILAYAH KOSONG - 12 Februari 2026

## Masalah

Setelah fix sebelumnya, kolom Provinsi/Kabupaten/Kecamatan/Kelurahan di alamat ayah, ibu, wali, dan siswa masih tetap kosong meskipun ada datanya.

## Root Cause

Ada 2 kemungkinan masalah:

1. **Whitespace/Trailing Space**: Data ID di database mungkin memiliki trailing space atau whitespace, sehingga lookup di cache gagal.
   - Database: `"35  "` (dengan space)
   - Cache key: `"35"` (tanpa space)
   - Result: NOT FOUND

2. **Cache Miss**: Jika ada ID yang tidak ter-load di cache (misalnya data baru atau issue loading), tidak ada fallback.

## Solusi

### 1. Tambahkan `trim()` di loadRegionCache() (Line 56-85)

Pastikan semua key di cache di-trim sehingga tidak ada whitespace:

```php
private function loadRegionCache()
{
    // Load provinces
    $provinces = \DB::table('indonesia_provinces')->get(['id', 'name']);
    foreach ($provinces as $province) {
        $key = trim((string)$province->id);  // TRIM di sini
        $this->regionCache[$key] = $province->name;
    }

    // Sama untuk cities, districts, villages
    ...
}
```

### 2. Tambahkan Fallback Query di getRegionName() (Line 1055-1100)

Jika ID tidak ditemukan di cache, langsung query ke database:

```php
private function getRegionName($id)
{
    if (empty($id)) {
        return '';
    }

    // Trim whitespace dan convert ke string
    $idStr = trim((string)$id);

    // Lookup in cache
    if (isset($this->regionCache[$idStr])) {
        return $this->regionCache[$idStr];
    }

    // FALLBACK: Query langsung jika tidak ada di cache
    // Check provinces
    $province = \DB::table('indonesia_provinces')->where('id', $idStr)->value('name');
    if ($province) {
        $this->regionCache[$idStr] = $province; // Cache untuk next time
        return $province;
    }

    // Check cities
    $city = \DB::table('indonesia_cities')->where('id', $idStr)->value('name');
    if ($city) {
        $this->regionCache[$idStr] = $city;
        return $city;
    }

    // Check districts
    $district = \DB::table('indonesia_districts')->where('id', $idStr)->value('name');
    if ($district) {
        $this->regionCache[$idStr] = $district;
        return $district;
    }

    // Check villages
    $village = \DB::table('indonesia_villages')->where('id', $idStr)->value('name');
    if ($village) {
        $this->regionCache[$idStr] = $village;
        return $village;
    }

    // Return empty jika tidak ditemukan di mana pun
    return '';
}
```

## File yang Diubah

### app/Exports/PendaftarExport.php

1. **Line 56-85**: Update loadRegionCache() - tambahkan trim()
2. **Line 1055-1100**: Update getRegionName() - tambahkan trim() dan fallback query

## Cara Test

1. Login sebagai Admin atau Panitia
2. Export data pendaftar lengkap
3. Buka file Excel
4. Cek kolom wilayah di alamat:
   - Provinsi Ayah
   - Kabupaten/Kota Ayah
   - Kecamatan Ayah
   - Kelurahan/Desa Ayah
   - (Sama untuk Ibu, Wali, Siswa)
5. Verifikasi tampil sebagai NAMA wilayah, bukan ID atau kosong

## Expected Result

**Sebelum:**
- Provinsi Ayah: (kosong)
- Kabupaten Ayah: (kosong)
- Kecamatan Ayah: (kosong)
- Kelurahan Ayah: (kosong)

**Sesudah:**
- Provinsi Ayah: Jawa Timur
- Kabupaten Ayah: Kota Malang
- Kecamatan Ayah: Lowokwaru
- Kelurahan Ayah: Tunggulwulung

## Upload ke Production

Jalankan:
```
upload_fix_export_wilayah_12_feb_2026.bat
```

Atau manual via cPanel File Manager.

## Performance Impact

**Minimal** - Fallback query hanya dijalankan jika:
1. ID tidak ada di cache (rare)
2. Ada whitespace issue yang tidak ter-handle oleh trim

Cache tetap di-load di awal untuk performance, fallback hanya sebagai safety net.

## Notes

- Trim() akan handle trailing/leading whitespace di ID
- Fallback query memastikan data tetap muncul bahkan jika cache miss
- Hasil fallback akan di-cache untuk penggunaan selanjutnya
- Solusi ini backward compatible dan tidak break existing data

## Related Issues

- Fix export laporan data pendaftar (NIK, kolom bergeser, dll)
- Data wilayah di formulir pendaftaran (sudah benar, menggunakan ID)
