# FIX ERROR "COLUMN CODE DOES NOT EXIST" - PRODUCTION

## 📋 Ringkasan Masalah

**Error Log:**
```
[2026-02-07 06:11:39] production.ERROR: SQLSTATE[42703]: Undefined column: 7 ERROR:  column "code" does not exist
```

**Root Cause:**
- Tabel wilayah di production (`indonesia_provinces`, `indonesia_cities`, `indonesia_districts`, `indonesia_villages`) **TIDAK memiliki kolom `code`**
- File `app/Exports/PendaftarExport.php` mencoba query kolom `code` yang tidak ada
- Error terjadi saat **export data pendaftar** atau proses yang memanggil PendaftarExport

**Dampak:**
- Export data pendaftar gagal dengan error 500
- Laporan Excel pendaftar tidak bisa di-download
- Sistem error saat load data export

---

## ✅ Solusi Yang Sudah Diterapkan

### File yang Diperbaiki: `app/Exports/PendaftarExport.php`

#### 1. Method `loadRegionCache()` (Baris 50-75)

**SEBELUM (ERROR):**
```php
private function loadRegionCache()
{
    // Load provinces
    $provinces = \DB::table('indonesia_provinces')->get(['code', 'name']);  // ❌ kolom 'code' tidak ada!
    foreach ($provinces as $province) {
        $this->regionCache[trim($province->code)] = $province->name;
    }

    // ... dan seterusnya untuk cities, districts, villages
}
```

**SESUDAH (FIXED):**
```php
private function loadRegionCache()
{
    // Load provinces - use id as key (production DB stores id in pendaftar fields)
    $provinces = \DB::table('indonesia_provinces')->get(['id', 'name']);  // ✅ gunakan 'id'
    foreach ($provinces as $province) {
        $this->regionCache[(string)$province->id] = $province->name;
    }

    // Load cities (kabupaten/kota)
    $cities = \DB::table('indonesia_cities')->get(['id', 'name']);
    foreach ($cities as $city) {
        $this->regionCache[(string)$city->id] = $city->name;
    }

    // Load districts (kecamatan)
    $districts = \DB::table('indonesia_districts')->get(['id', 'name']);
    foreach ($districts as $district) {
        $this->regionCache[(string)$district->id] = $district->name;
    }

    // Load villages (kelurahan/desa)
    $villages = \DB::table('indonesia_villages')->get(['id', 'name']);
    foreach ($villages as $village) {
        $this->regionCache[(string)$village->id] = $village->name;
    }
}
```

#### 2. Method `getRegionName()` (Baris 952-968)

**SEBELUM (KOMPLEKS):**
```php
private function getRegionName($code)
{
    if (empty($code)) {
        return '';
    }

    $code = trim($code);

    if (isset($this->regionCache[$code])) {
        return $this->regionCache[$code];
    }

    // Handle 7-digit district codes (should be 6 digits)
    if (strlen($code) == 7) {
        $code6 = substr($code, 0, 6);
        if (isset($this->regionCache[$code6])) {
            return $this->regionCache[$code6];
        }
    }

    // Handle 11-digit village codes (should be 10 digits)
    if (strlen($code) == 11) {
        $code10 = substr($code, 0, 10);
        if (isset($this->regionCache[$code10])) {
            return $this->regionCache[$code10];
        }
    }

    return $code;
}
```

**SESUDAH (SIMPLIFIED):**
```php
private function getRegionName($id)
{
    if (empty($id)) {
        return '';
    }

    // Convert to string for cache lookup
    $idStr = (string)$id;

    // Lookup in cache
    if (isset($this->regionCache[$idStr])) {
        return $this->regionCache[$idStr];
    }

    // Return empty if not found (should not happen if cache loaded correctly)
    return '';
}
```

---

## 🚀 Cara Upload ke Production

### Opsi 1: Upload via Script (Otomatis)

Jika SSH connection tersedia, jalankan:
```bash
./upload_fix_export_code_column.bat
```

### Opsi 2: Upload Manual via cPanel/FTP

**Langkah 1: Upload File**
1. Upload file: `C:\ppdbmtsn2km-laravel\app\Exports\PendaftarExport.php`
2. Lokasi tujuan di server: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/`
3. **PENTING:** Backup file lama terlebih dahulu!

**Langkah 2: Clear Cache**
Via terminal/SSH:
```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan cache:clear
php artisan view:clear
```

Atau via cPanel Terminal:
```bash
cd pmbm.mtsn2kotamalang.sch.id
php artisan cache:clear
php artisan view:clear
```

### Opsi 3: Upload Manual via Terminal

```bash
# Upload file
scp app/Exports/PendaftarExport.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/

# Clear cache
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan cache:clear && php artisan view:clear"
```

---

## 🧪 Testing Setelah Upload

### Test 1: Export Data Pendaftar
1. Login sebagai Admin/Panitia
2. Buka menu **Pendaftar**
3. Klik tombol **Export Excel**
4. Pastikan file Excel ter-download tanpa error 500
5. Buka file Excel dan cek apakah data wilayah (provinsi, kota, kecamatan, kelurahan) muncul dengan benar

### Test 2: Cek Log Error
```bash
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && tail -50 storage/logs/laravel.log"
```

Pastikan tidak ada lagi error:
```
SQLSTATE[42703]: Undefined column: 7 ERROR:  column "code" does not exist
```

### Test 3: Export Laporan Lainnya
Test juga export di menu:
- **Laporan Pendaftaran**
- **Daftar Ulang**
- **Seragam**
- **Tes Psikologi**

---

## 📊 Perbandingan

| Aspek | Sebelum Fix | Setelah Fix |
|-------|-------------|-------------|
| **Query Column** | `code` (tidak ada) | `id` (ada) |
| **Cache Key** | code (string) | id (string) |
| **Error 500** | ✅ Ada | ❌ Tidak ada |
| **Export Excel** | ❌ Gagal | ✅ Berhasil |
| **Wilayah Display** | ❌ Error | ✅ Tampil nama |

---

## 🔍 Technical Details

### Struktur Database Production

**Tabel Wilayah:**
- `indonesia_provinces`: id, name (TIDAK ADA kolom `code`)
- `indonesia_cities`: id, name, province_id (TIDAK ADA kolom `code`)
- `indonesia_districts`: id, name, regency_id (TIDAK ADA kolom `code`)
- `indonesia_villages`: id, name, district_id (TIDAK ADA kolom `code`)

**Field Wilayah di Tabel Pendaftar:**
- `ayah_provinsi`, `ayah_kabupaten`, `ayah_kecamatan`, `ayah_kelurahan` → menyimpan **ID** (bukan code)
- `ibu_provinsi`, `ibu_kabupaten`, `ibu_kecamatan`, `ibu_kelurahan` → menyimpan **ID**
- `wali_provinsi`, `wali_kabupaten`, `wali_kecamatan`, `wali_kelurahan` → menyimpan **ID**
- `siswa_provinsi`, dst → menyimpan **ID**

### Mengapa Error Terjadi?

1. **Migration** di local memiliki kolom `code`:
   ```php
   // database/migrations/2016_08_03_072729_create_provinces_table.php
   $table->char('code', 2)->unique();
   ```

2. **Production** tidak menjalankan migration ini atau menggunakan struktur DB yang berbeda

3. **PendaftarExport.php** ditulis dengan asumsi kolom `code` ada

4. Saat export dipanggil → query `code` → **ERROR: column "code" does not exist**

---

## 📝 Catatan Penting

1. **Fix ini TIDAK mengubah struktur database**, hanya mengubah cara query data
2. **Kompatibel dengan data existing** karena field wilayah sudah menyimpan ID
3. **Tidak ada data loss** karena hanya mengubah cara membaca data
4. **Performa tetap baik** karena masih menggunakan cache
5. **Semua export file** sudah dicek, hanya PendaftarExport.php yang terpengaruh

---

## ✅ Checklist Setelah Upload

- [ ] File `app/Exports/PendaftarExport.php` sudah diupload
- [ ] Cache sudah di-clear (`php artisan cache:clear`)
- [ ] View cache sudah di-clear (`php artisan view:clear`)
- [ ] Test export data pendaftar berhasil
- [ ] File Excel ter-download tanpa error
- [ ] Data wilayah di Excel muncul dengan benar
- [ ] Log error tidak ada lagi error "column code does not exist"
- [ ] Export laporan lainnya juga berjalan normal

---

## 🆘 Troubleshooting

### Jika Masih Error 500 Setelah Upload

1. **Cek apakah file sudah terupload:**
   ```bash
   ssh mtsnkot4@103.30.244.77 "ls -la /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/PendaftarExport.php"
   ```

2. **Cek timestamp file:**
   ```bash
   ssh mtsnkot4@103.30.244.77 "stat /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/PendaftarExport.php"
   ```
   Timestamp harus hari ini (2026-02-07)

3. **Clear semua cache:**
   ```bash
   ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear && php artisan optimize:clear"
   ```

4. **Cek log error detail:**
   ```bash
   ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && tail -100 storage/logs/laravel.log"
   ```

---

## 📞 Kontak

Jika ada pertanyaan atau kendala, silakan hubungi tim developer.

---

**Status:** ✅ FIX READY FOR DEPLOYMENT
**Tanggal:** 2026-02-07
**File Changed:** `app/Exports/PendaftarExport.php`
**Impact:** High (Export feature affected)
**Risk:** Low (only changes query, no DB structure change)
