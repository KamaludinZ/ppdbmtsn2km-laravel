# FIX KODE POS AUTO-FILL - COMPLETE GUIDE

## Masalah

Kode pos tidak terisi otomatis saat memilih kelurahan/desa di halaman `/pendaftar/formulir?activeTab=alamat`.

## Root Cause

Tabel `postal_codes` di database production **kosong atau tidak ada data**.

## Solusi yang Sudah Disiapkan

### ✅ Files yang Sudah Dibuat

1. **`export_postal_codes.php`**
   - Script untuk export data kode pos dari local database
   - Generate file SQL dengan 81,248 records
   - Output: `database/seeders/sql/postal_codes.sql` (4.71 MB)

2. **`database/seeders/PostalCodeSeeder.php`**
   - Seeder class untuk import data kode pos
   - Membaca dari file `postal_codes.sql`
   - Auto-execute INSERT statements

3. **`database/seeders/sql/postal_codes.sql`**
   - File SQL hasil export (sudah di-generate)
   - Berisi 81,248 kode pos seluruh Indonesia
   - Format: INSERT INTO postal_codes (urban, sub_district, city, province_code, postal_code)

4. **`upload_postal_codes.bat`**
   - Script otomatis untuk upload ke production
   - Upload seeder + SQL file
   - Auto-execute seeder

## Cara Upload ke Production

### Opsi 1: Menggunakan Script Otomatis (RECOMMENDED)

```batch
cd C:\ppdbmtsn2km-laravel
upload_postal_codes.bat
```

Script ini akan:
1. ✅ Upload `PostalCodeSeeder.php` ke server
2. ✅ Buat folder `database/seeders/sql/` di server
3. ✅ Upload `postal_codes.sql` (4.71 MB) ke server
4. ✅ Jalankan seeder: `php artisan db:seed --class=PostalCodeSeeder`

### Opsi 2: Manual Upload via FTP/cPanel

**Step 1: Upload Files**

Upload 2 file ini ke production:

```
File lokal:
C:\ppdbmtsn2km-laravel\database\seeders\PostalCodeSeeder.php
→ Upload ke: /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/seeders/PostalCodeSeeder.php

C:\ppdbmtsn2km-laravel\database\seeders\sql\postal_codes.sql
→ Upload ke: /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/seeders/sql/postal_codes.sql
```

**Step 2: Jalankan Seeder**

Via SSH atau Terminal di cPanel:
```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan db:seed --class=PostalCodeSeeder
```

**Expected Output:**
```
Importing postal codes data...
✓ Berhasil import 81248 kode pos
```

## Verifikasi Setelah Import

### Check via PHP Script

Upload dan jalankan `check_postal_codes.php`:

```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php check_postal_codes.php
```

**Output yang Diharapkan:**
```
=== CHECKING POSTAL CODES DATA ===

✓ Tabel 'postal_codes' exists
  Total records: 81,248

✓ Data postal codes tersedia!

Sample data:
--------------------------------------------------------------------------------
KELURAHAN                 KECAMATAN                 KOTA                 KODE POS
--------------------------------------------------------------------------------
ABAH LUENG                BANDAR BARU               PIDIE JAYA           24184
ABAIL                     TEUPAH TENGAH             SIMEULUE             23891
...

=== SUMMARY ===
✓ Tabel postal_codes: OK
✓ Total records: 81,248
✓ Auto-fill kode pos: READY
```

### Check via Browser

1. Login sebagai pendaftar
2. Buka: https://pmbm.mtsn2kotamalang.sch.id/pendaftar/formulir?activeTab=alamat
3. Klik "Edit Data"
4. Pilih urutan: Provinsi → Kabupaten → Kecamatan → Kelurahan
5. **Kode pos seharusnya terisi otomatis** setelah memilih kelurahan

## Detail Teknis

### Tabel postal_codes

**Struktur:**
```sql
CREATE TABLE postal_codes (
    id SERIAL PRIMARY KEY,
    urban VARCHAR(100),           -- Kelurahan/Desa
    sub_district VARCHAR(100),    -- Kecamatan
    city VARCHAR(100),            -- Kabupaten/Kota
    province_code INTEGER,        -- Kode Provinsi (11-92)
    postal_code VARCHAR(5)        -- Kode Pos (5 digit)
);

-- Indexes
CREATE INDEX idx_postal_codes_location ON postal_codes(urban, sub_district, city);
CREATE INDEX idx_postal_codes_code ON postal_codes(postal_code);
CREATE INDEX idx_postal_codes_province ON postal_codes(province_code);
```

**Total Data:**
- 81,248 kode pos
- Coverage: Seluruh Indonesia (34 provinsi)

### Cara Kerja Auto-Fill

Di `app/Livewire/Pendaftar/Formulir.php`:

```php
public function updatedAyahKelurahan()
{
    if ($this->ayah_kelurahan && $this->ayah_kecamatan && $this->ayah_kabupaten) {
        // Get village, district, and city names
        $village = Village::where('id', $this->ayah_kelurahan)->first();
        $district = District::where('id', $this->ayah_kecamatan)->first();
        $regency = Regency::where('id', $this->ayah_kabupaten)->first();

        if ($village && $district && $regency) {
            // Remove KABUPATEN/KOTA prefix from regency name
            $cityName = preg_replace('/^(KABUPATEN|KOTA)\s+/i', '', $regency->name);

            // Query postal_codes table
            $postalCode = \DB::table('postal_codes')
                ->where('urban', 'LIKE', '%' . $village->name . '%')
                ->where('sub_district', 'LIKE', '%' . $district->name . '%')
                ->where('city', 'LIKE', '%' . $cityName . '%')
                ->first();

            if ($postalCode) {
                $this->ayah_kode_pos = $postalCode->postal_code; // ✅ Auto-fill
            }
        }
    }
}
```

**Logika yang sama diterapkan untuk:**
- Alamat Ibu (`updatedIbuKelurahan`)
- Alamat Wali (`updatedWaliKelurahan`)
- Alamat Siswa (`updatedSiswaKelurahan`)

### Sample Data postal_codes

```
| urban                  | sub_district        | city           | province_code | postal_code |
|------------------------|---------------------|----------------|---------------|-------------|
| ABAH LUENG             | BANDAR BARU         | PIDIE JAYA     | 11            | 24184       |
| WONOSOBO               | SRENGAT             | BLITAR         | 35            | 66152       |
| JAKARTA SELATAN        | KEBAYORAN BARU      | JAKARTA SELATAN| 31            | 12110       |
| BANDUNG WETAN          | BANDUNG WETAN       | BANDUNG        | 32            | 40114       |
```

## Troubleshooting

### Problem 1: "File postal_codes.sql tidak ditemukan"

**Solusi:**
- Pastikan file `postal_codes.sql` sudah di-upload ke folder `database/seeders/sql/`
- Check path: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/seeders/sql/postal_codes.sql`

### Problem 2: Upload SQL file gagal (file terlalu besar)

**Solusi A - Split File:**
```bash
# Di local, split file menjadi 2 bagian
split -l 41000 postal_codes.sql postal_codes_part_

# Upload 2 file terpisah, lalu gabung di server
cat postal_codes_part_* > postal_codes.sql
```

**Solusi B - Direct Import via MySQL:**
```bash
# Upload via scp lalu import langsung ke database
scp postal_codes.sql mtsnkot4@103.30.244.77:/tmp/
ssh mtsnkot4@103.30.244.77
psql -U username -d database_name -f /tmp/postal_codes.sql
```

### Problem 3: Kode pos tetap tidak muncul setelah seeding

**Check:**
1. Pastikan data ter-import: `SELECT COUNT(*) FROM postal_codes;`
2. Clear cache Livewire: `php artisan livewire:clear`
3. Clear browser cache
4. Test dengan nama kelurahan yang berbeda (matching case-insensitive)

### Problem 4: Performance lambat saat auto-fill

**Solusi:**
- Pastikan index sudah dibuat (seharusnya otomatis dari migration)
- Check query performance:
```sql
EXPLAIN SELECT * FROM postal_codes
WHERE urban LIKE '%JAKARTA%'
AND sub_district LIKE '%KEBAYORAN%'
AND city LIKE '%JAKARTA%';
```

## File Structure

```
C:\ppdbmtsn2km-laravel\
├── database/
│   └── seeders/
│       ├── PostalCodeSeeder.php          ← Seeder class
│       └── sql/
│           └── postal_codes.sql          ← SQL data (4.71 MB)
├── export_postal_codes.php               ← Export script
├── check_postal_codes.php                ← Check script
└── upload_postal_codes.bat               ← Upload script
```

## Checklist

- [x] Export data postal codes dari local
- [x] Generate file SQL (81,248 records)
- [x] Buat PostalCodeSeeder class
- [x] Buat script upload otomatis
- [ ] Upload PostalCodeSeeder.php ke production
- [ ] Upload postal_codes.sql ke production
- [ ] Jalankan seeder di production
- [ ] Verify data imported (81,248 records)
- [ ] Test auto-fill kode pos di browser
- [ ] Clear cache Livewire
- [ ] Test dengan berbagai kombinasi wilayah

## Quick Commands

```bash
# Export (di local)
php export_postal_codes.php

# Check local
php check_postal_codes.php

# Upload (via Windows batch)
upload_postal_codes.bat

# Seed di production (via SSH)
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan db:seed --class=PostalCodeSeeder

# Verify di production
php check_postal_codes.php

# Clear cache
php artisan livewire:clear
php artisan cache:clear
```

## Notes

⚠️ **PENTING:**
- File SQL berukuran 4.71 MB - pastikan koneksi internet stabil saat upload
- Proses seeding bisa memakan waktu 1-2 menit
- Backup database dulu sebelum import (optional tapi recommended)
- Auto-fill menggunakan LIKE query - matching tidak case-sensitive

✅ **Setelah selesai:**
- Kode pos akan otomatis terisi saat pilih kelurahan
- Matching berdasarkan nama kelurahan, kecamatan, dan kota
- Jika tidak match, field kode pos tetap bisa diisi manual

## Data Coverage

```
Total: 81,248 kode pos
- Provinsi: 34
- Kabupaten/Kota: ~514
- Kecamatan: ~7,215
- Kelurahan/Desa: ~80,534
```

**Coverage per provinsi (sample):**
- Aceh (11): 6,464 kode pos
- Sumatera Utara (12): 5,939 kode pos
- Jawa Barat (32): 9,201 kode pos
- Jawa Timur (35): 9,479 kode pos
- DKI Jakarta (31): 267 kode pos
- dll...
