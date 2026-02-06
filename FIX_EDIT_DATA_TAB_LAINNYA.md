# Fix Edit Data di Tab Lainnya - Error 500

## Status Saat Ini

✅ **BERHASIL:**
- Edit Nilai Rapor di tab "Verifikasi Dokumen" → BERFUNGSI
- Fitur baru sudah terimplementasi dengan baik

❌ **MASIH ERROR:**
- Tombol "Edit Data" di tab lainnya (Siswa, Orangtua, Alamat, dll) → ERROR 500
- Error: `Public method [toggleEdit] not found on component`

## Analisis Masalah

### Penyebab
File controller sudah terupload dengan benar (terbukti Edit Nilai Rapor berfungsi), tapi **cache di production belum clear sempurna**. Livewire masih menggunakan compiled component lama.

### Method yang Dibutuhkan
Semua tab menggunakan:
```php
wire:click="toggleEdit('siswa')"    // Tab Siswa
wire:click="toggleEdit('orangtua')" // Tab Orangtua
wire:click="toggleEdit('alamat')"   // Tab Alamat
// dst...
```

Method di controller (SUDAH ADA):
```php
// Line 307 (Panitia) / Line 795 (Admin)
public function toggleEdit($section = null)
{
    $this->toggleEditMode();
}

// Line 299 (Panitia) / Line 783 (Admin)
public function toggleEditMode()
{
    $this->editMode = !$this->editMode;
    if (!$this->editMode) {
        $this->loadDataToProperties();
    }
}
```

## Solusi

### Step 1: Clear Cache Agresif
Jalankan script baru yang lebih agresif:

```cmd
cd C:\ppdbmtsn2km-laravel
clear_cache_production.bat
```

Script ini akan:
1. ✅ Clear application cache
2. ✅ Clear config cache
3. ✅ Clear route cache
4. ✅ Clear view cache
5. ✅ Clear optimize cache
6. ✅ **Delete compiled views manually** (rm -rf storage/framework/views/*.php)
7. ✅ **Delete Livewire cache** (rm -rf storage/framework/cache/livewire-tmp/*)
8. ✅ Rebuild optimize

### Step 2: Hard Refresh Browser
Setelah clear cache, lakukan hard refresh di browser:
- **Chrome/Edge:** Ctrl + Shift + R
- **Firefox:** Ctrl + Shift + R
- **Safari:** Cmd + Shift + R

### Step 3: Test Kembali
Test tombol "Edit Data" di berbagai tab:
1. Tab Siswa → Klik "Edit Data" → Harus berfungsi
2. Tab Orangtua → Klik "Edit Data" → Harus berfungsi
3. Tab Alamat → Klik "Edit Data" → Harus berfungsi
4. Tab Prestasi → Klik "Edit Data" → Harus berfungsi
5. dst...

## Jika Masih Error

### A. Verifikasi File Production
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Cek method toggleEdit ada
grep -n "public function toggleEdit" app/Livewire/Panitia/DetailPendaftar.php
grep -n "public function toggleEdit" app/Livewire/Admin/DetailPendaftar.php

# Harus keluar:
# 307:    public function toggleEdit($section = null)  (Panitia)
# 795:    public function toggleEdit($section = null)  (Admin)
```

### B. Cek Error Log Terbaru
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Lihat error terakhir
tail -100 storage/logs/laravel.log | grep -A 20 "toggleEdit\|toggleEditMode"
```

### C. Restart PHP-FPM (Jika Perlu)
Kadang PHP-FPM perlu di-restart untuk load file baru:

```bash
# Cek versi PHP yang digunakan
php -v

# Restart PHP-FPM (sesuaikan versi PHP)
sudo systemctl restart php8.2-fpm

# Atau restart Apache
sudo systemctl restart apache2
```

### D. Clear OPcache (Jika Ada)
Jika server menggunakan OPcache, clear juga:

```bash
# Via Artisan
php artisan opcache:clear

# Atau via PHP
php -r "opcache_reset();"

# Atau restart PHP-FPM
sudo systemctl restart php8.2-fpm
```

## Checklist Troubleshooting

- [ ] File controller sudah terupload (cek dengan wc -l atau grep)
- [ ] Cache application sudah di-clear
- [ ] Cache config sudah di-clear
- [ ] Cache route sudah di-clear
- [ ] Cache view sudah di-clear
- [ ] Compiled views manual sudah dihapus
- [ ] Livewire cache sudah dihapus
- [ ] Browser sudah hard refresh
- [ ] PHP-FPM sudah di-restart (jika perlu)
- [ ] OPcache sudah di-clear (jika ada)

## Tab Template yang Terpengaruh

File yang menggunakan `toggleEdit()`:
- `resources/views/tab_templates/tab-data-siswa.blade.php`
- `resources/views/tab_templates/tab-data-orangtua.blade.php`
- `resources/views/tab_templates/tab-data-alamat.blade.php`
- `resources/views/tab_templates/tab-prestasi.blade.php`
- `resources/views/tab_templates/tab-keahlian.blade.php`
- `resources/views/tab_templates/tab-tahfidz.blade.php`
- `resources/views/tab_templates/tab-beasiswa.blade.php`
- `resources/views/tab_templates/tab-pendidikan-lain.blade.php`

Semua tab ini memanggil `wire:click="toggleEdit('section_name')`

## Catatan Penting

⚠️ **Method toggleEdit() sudah ada di lokal dan sudah terupload**
- File lokal sudah benar (terbukti Edit Nilai Rapor berfungsi)
- Yang perlu dilakukan hanya clear cache yang lebih agresif

⚠️ **Jangan upload ulang file**
- File sudah benar, tidak perlu upload ulang
- Focus pada clear cache saja

⚠️ **Livewire Component Caching**
- Livewire meng-cache compiled component
- Perlu delete manual cache di `storage/framework/cache/livewire-tmp/`
- Perlu delete compiled views di `storage/framework/views/`

---

Tanggal: 3 Februari 2026 - 05:45 WIB
