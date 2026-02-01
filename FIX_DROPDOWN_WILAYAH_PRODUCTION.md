# FIX DROPDOWN WILAYAH TIDAK MUNCUL DI PRODUCTION

## Masalah

Dropdown cascade wilayah (Provinsi, Kabupaten/Kota, Kecamatan, Kelurahan/Desa) di halaman `/pendaftar/formulir?activeTab=alamat` tidak muncul di production, padahal di local berfungsi normal.

## Root Cause

Kemungkinan besar **data wilayah Indonesia tidak ada atau tidak lengkap** di database production.

## Verifikasi di Local

Di environment local, data wilayah lengkap:
```
✓ Provinces: 34
✓ Regencies: 514
✓ Districts: 7,215
✓ Villages: 80,534
```

## Cara Mengecek di Production

### Step 1: Upload Script Check

Upload file ini ke production:
```
File lokal:
C:\ppdbmtsn2km-laravel\check_wilayah_data.php

Upload ke:
/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/check_wilayah_data.php
```

### Step 2: Jalankan Check Script

Via SSH atau Terminal di cPanel:
```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php check_wilayah_data.php
```

### Step 3: Lihat Hasil

**Jika data lengkap:**
```
✓ All wilayah data exists!
Total data:
  - Provinces: 34
  - Regencies: 514
  - Districts: 7,215
  - Villages: 80,534
```

**Jika data kosong/tidak lengkap:**
```
❌ Some wilayah data is missing!

You need to seed wilayah data by running:
  php artisan db:seed --class=IndoRegionSeeder
  OR
  php artisan laravolt:region:seed
```

## Solusi Jika Data Wilayah Kosong

### Opsi 1: Seed Data Wilayah (RECOMMENDED)

**Via Artisan Command:**
```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Coba salah satu command ini:
php artisan laravolt:region:seed

# Atau jika tidak ada:
php artisan db:seed --class=IndoRegionSeeder
```

**Catatan:**
- Command ini akan mengisi data provinsi, kabupaten, kecamatan, dan kelurahan seluruh Indonesia
- Proses bisa memakan waktu beberapa menit
- Total data yang akan di-seed: ~88,000+ records

### Opsi 2: Import Database dari Local

Jika seeding gagal, bisa export tabel wilayah dari local dan import ke production:

**Export dari Local:**
```bash
# Export 4 tabel wilayah
mysqldump -u root -p ppdb_db provinces regencies districts villages > wilayah_data.sql
```

**Import ke Production:**
```bash
# Upload file wilayah_data.sql ke production
# Lalu import:
mysql -u username -p database_name < wilayah_data.sql
```

### Opsi 3: Cek Package IndoRegion

Pastikan package `azishapidin/indoregion` ter-install:

```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Cek apakah package ada di composer.json
cat composer.json | grep "indoregion"

# Jika tidak ada, install:
composer require azishapidin/indoregion

# Publish migrations dan seeds:
php artisan vendor:publish --provider="AzisHapidin\IndoRegion\IndoRegionServiceProvider"

# Run migrations:
php artisan migrate

# Seed data:
php artisan laravolt:region:seed
```

## Detail Teknis

### Model yang Digunakan

Aplikasi menggunakan 4 model untuk data wilayah:

1. **Province** (`app/Models/Province.php`)
   - Tabel: `provinces`
   - Package: `azishapidin/indoregion`
   - Total: 34 provinsi

2. **Regency** (`app/Models/Regency.php`)
   - Tabel: `regencies`
   - Total: 514 kabupaten/kota

3. **District** (`app/Models/District.php`)
   - Tabel: `districts`
   - Total: 7,215 kecamatan

4. **Village** (`app/Models/Village.php`)
   - Tabel: `villages`
   - Total: 80,534 kelurahan/desa

### Cascade Loading di Livewire

Method `loadCascadeData()` di `app/Livewire/Pendaftar/Formulir.php`:

```php
public function loadCascadeData()
{
    // Load all provinces (always available)
    $this->provincesList = Province::orderBy('name')->get()->map(function($item) {
        return ['id' => (string)$item->id, 'name' => $item->name];
    })->toArray();

    // Ayah cascade data
    if ($this->ayah_provinsi) {
        $this->ayahCitiesList = Regency::where('province_id', $this->ayah_provinsi)
            ->orderBy('name')->get()->map(function($item) {
                return ['id' => (string)$item->id, 'name' => $item->name];
            })->toArray();
    }

    // ... dst untuk ibu dan wali
}
```

### Dropdown di View

Dropdown cascade di `resources/views/tab_templates/tab-data-alamat.blade.php`:

```blade
<!-- Provinsi -->
<select wire:model.live="ayah_provinsi" class="..." required>
    <option value="">Pilih Provinsi</option>
    @foreach($provincesList as $province)
        <option value="{{ $province['id'] }}">{{ $province['name'] }}</option>
    @endforeach
</select>

<!-- Kabupaten/Kota (disabled jika provinsi belum dipilih) -->
<select wire:model.live="ayah_kabupaten" @if(!$ayah_provinsi) disabled @endif>
    <option value="">Pilih Kabupaten/Kota</option>
    @foreach($ayahCitiesList as $city)
        <option value="{{ $city['id'] }}">{{ $city['name'] }}</option>
    @endforeach
</select>

<!-- Kecamatan (disabled jika kabupaten belum dipilih) -->
<select wire:model.live="ayah_kecamatan" @if(!$ayah_kabupaten) disabled @endif>
    <option value="">Pilih Kecamatan</option>
    @foreach($ayahDistrictsList as $district)
        <option value="{{ $district['id'] }}">{{ $district['name'] }}</option>
    @endforeach
</select>

<!-- Kelurahan/Desa (disabled jika kecamatan belum dipilih) -->
<select wire:model.live="ayah_kelurahan" @if(!$ayah_kecamatan) disabled @endif>
    <option value="">Pilih Kelurahan/Desa</option>
    @foreach($ayahVillagesList as $village)
        <option value="{{ $village['id'] }}">{{ $village['name'] }}</option>
    @endforeach
</select>
```

## Testing Setelah Fix

Setelah data wilayah berhasil di-seed:

1. Login sebagai pendaftar
2. Buka: https://pmbm.mtsn2kotamalang.sch.id/pendaftar/formulir?activeTab=alamat
3. Klik tombol "Edit Data"
4. Dropdown "Provinsi" seharusnya sudah berisi 34 provinsi Indonesia
5. Pilih provinsi → dropdown "Kabupaten/Kota" akan terisi
6. Pilih kabupaten → dropdown "Kecamatan" akan terisi
7. Pilih kecamatan → dropdown "Kelurahan/Desa" akan terisi

## Checklist

- [ ] Upload `check_wilayah_data.php` ke production
- [ ] Jalankan check script: `php check_wilayah_data.php`
- [ ] Jika data kosong, jalankan seed: `php artisan laravolt:region:seed`
- [ ] Hapus check script: `rm check_wilayah_data.php`
- [ ] Test dropdown di production: `/pendaftar/formulir?activeTab=alamat`
- [ ] Verify semua dropdown terisi dengan benar

## File yang Terlibat

```
app/Models/Province.php                              - Model provinsi
app/Models/Regency.php                              - Model kabupaten/kota
app/Models/District.php                             - Model kecamatan
app/Models/Village.php                              - Model kelurahan/desa
app/Livewire/Pendaftar/Formulir.php                 - Logic cascade loading
resources/views/tab_templates/tab-data-alamat.blade.php - View dropdown
```

## Package yang Digunakan

```json
{
    "require": {
        "azishapidin/indoregion": "^3.0"
    }
}
```

## Database Tables

```sql
-- Struktur tabel wilayah
provinces (id, name)
regencies (id, province_id, name)
districts (id, regency_id, name)
villages (id, district_id, name)
```

## Catatan Penting

⚠️ **PENTING**: Pastikan data wilayah sudah ter-seed di production SEBELUM ada pendaftar yang mengisi formulir. Jika ada data pendaftar dengan kode wilayah yang tidak valid, bisa menyebabkan error.

⚠️ **BACKUP**: Sebaiknya backup database dulu sebelum menjalankan seeding data wilayah.

⚠️ **WAKTU**: Proses seeding bisa memakan waktu 5-10 menit tergantung kecepatan server.
