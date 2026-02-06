# Fix KIP, KKS, PKH - Alphanumeric Max 25 Karakter

## 🎯 Masalah yang Diperbaiki

### Kondisi SEBELUM:
- Field KIP, KKS, PKH hanya bisa **angka saja**
- Maksimal **17 digit**
- Input type `numeric` dengan validasi `digits_between:1,17`

### Kondisi SESUDAH:
- Field KIP, KKS, PKH bisa **huruf DAN angka** (alphanumeric)
- Maksimal **25 karakter**
- Input type `text` dengan validasi `regex:/^[A-Za-z0-9]+$/` max:25

---

## ✅ Solusi yang Diterapkan

### 1. Update Validasi di Controllers

#### File: `app/Livewire/Admin/DetailPendaftar.php`

**Line 1053-1054** (saveDataOrangtua - KKS & PKH):
```php
// SEBELUM:
'nomor_kks' => 'nullable|numeric|digits_between:1,17',
'nomor_pkh' => 'nullable|numeric|digits_between:1,17',

// SESUDAH:
'nomor_kks' => 'nullable|string|max:25|regex:/^[A-Za-z0-9]+$/',
'nomor_pkh' => 'nullable|string|max:25|regex:/^[A-Za-z0-9]+$/',
```

**Line 1415** (saveDataSiswa - KIP):
```php
// SEBELUM:
'nomor_kip' => 'nullable|numeric|digits_between:1,17|required_if:penerima_kip,true',

// SESUDAH:
'nomor_kip' => 'nullable|string|max:25|regex:/^[A-Za-z0-9]+$/|required_if:penerima_kip,true',
```

#### File: `app/Livewire/Pendaftar/Formulir.php`

**Line 862** (saveDataSiswaStep2 - KIP):
```php
// SEBELUM:
'nomor_kip' => 'nullable|string|max:17',

// SESUDAH:
'nomor_kip' => 'nullable|string|max:25|regex:/^[A-Za-z0-9]+$/',
```

**Line 1066-1067** (saveDataOrangtuaStep3 - KKS & PKH):
```php
// SEBELUM:
'nomor_kks' => 'nullable|numeric|digits_between:1,17',
'nomor_pkh' => 'nullable|numeric|digits_between:1,17',

// SESUDAH:
'nomor_kks' => 'nullable|string|max:25|regex:/^[A-Za-z0-9]+$/',
'nomor_pkh' => 'nullable|string|max:25|regex:/^[A-Za-z0-9]+$/',
```

**Line 1175-1178** (Error Messages):
```php
// SEBELUM:
'nomor_kks.numeric' => 'Nomor KKS harus berupa angka',
'nomor_kks.digits_between' => 'Nomor KKS harus 1-17 digit',
'nomor_pkh.numeric' => 'Nomor PKH harus berupa angka',
'nomor_pkh.digits_between' => 'Nomor PKH harus 1-17 digit',

// SESUDAH:
'nomor_kks.max' => 'Nomor KKS maksimal 25 karakter',
'nomor_kks.regex' => 'Nomor KKS hanya boleh huruf dan angka',
'nomor_pkh.max' => 'Nomor PKH maksimal 25 karakter',
'nomor_pkh.regex' => 'Nomor PKH hanya boleh huruf dan angka',
```

**Line 2068** (saveDataBeasiswa - KIP):
```php
// SEBELUM:
'nomor_kip' => 'nullable|string|max:17',

// SESUDAH:
'nomor_kip' => 'nullable|string|max:25|regex:/^[A-Za-z0-9]+$/',
```

### 2. Update Input Fields di Views

#### File: `resources/views/tab_templates/tab-data-siswa.blade.php`

**Line 376** (Input Nomor KIP):
```html
<!-- SEBELUM: -->
<input type="text" wire:model="nomor_kip" maxlength="17" inputmode="numeric"
       oninput="this.value = this.value.replace(/[^0-9]/g, '')" ...>

<!-- SESUDAH: -->
<input type="text" wire:model="nomor_kip" maxlength="25"
       oninput="this.value = this.value.replace(/[^A-Za-z0-9]/g, '')" ...>
```

**Line 378** (Help text):
```html
<!-- SEBELUM: -->
<p class="text-xs text-gray-500 mt-1">Maksimal 17 digit. Jika diisi, field upload KIP akan aktif...</p>

<!-- SESUDAH: -->
<p class="text-xs text-gray-500 mt-1">Maksimal 25 karakter (huruf dan angka). Jika diisi, field upload KIP akan aktif...</p>
```

#### File: `resources/views/tab_templates/tab-data-orangtua.blade.php`

**Line 555** (Input Nomor KKS):
```html
<!-- SEBELUM: -->
<input type="text" wire:model="nomor_kks" maxlength="17" inputmode="numeric"
       oninput="this.value = this.value.replace(/[^0-9]/g, '')" ...>

<!-- SESUDAH: -->
<input type="text" wire:model="nomor_kks" maxlength="25"
       oninput="this.value = this.value.replace(/[^A-Za-z0-9]/g, '')" ...>
```

**Line 557** (Help text KKS):
```html
<!-- SEBELUM: -->
<p class="text-xs text-gray-500 mt-1">Maksimal 17 digit. Jika diisi, field upload KKS akan aktif...</p>

<!-- SESUDAH: -->
<p class="text-xs text-gray-500 mt-1">Maksimal 25 karakter (huruf dan angka). Jika diisi, field upload KKS akan aktif...</p>
```

**Line 563** (Input Nomor PKH):
```html
<!-- SEBELUM: -->
<input type="text" wire:model="nomor_pkh" maxlength="17" inputmode="numeric"
       oninput="this.value = this.value.replace(/[^0-9]/g, '')" ...>

<!-- SESUDAH: -->
<input type="text" wire:model="nomor_pkh" maxlength="25"
       oninput="this.value = this.value.replace(/[^A-Za-z0-9]/g, '')" ...>
```

**Line 565** (Help text PKH):
```html
<!-- SEBELUM: -->
<p class="text-xs text-gray-500 mt-1">Maksimal 17 digit. Jika diisi, field upload PKH akan aktif...</p>

<!-- SESUDAH: -->
<p class="text-xs text-gray-500 mt-1">Maksimal 25 karakter (huruf dan angka). Jika diisi, field upload PKH akan aktif...</p>
```

---

## 📁 File yang Diubah (4 files)

### Controllers (2 files):
1. **`app/Livewire/Admin/DetailPendaftar.php`**
   - Line 1053-1054: Update validasi nomor_kks dan nomor_pkh
   - Line 1415: Update validasi nomor_kip

2. **`app/Livewire/Pendaftar/Formulir.php`**
   - Line 862: Update validasi nomor_kip (step 2)
   - Line 1066-1067: Update validasi nomor_kks dan nomor_pkh (step 3)
   - Line 1175-1178: Update error messages
   - Line 2068: Update validasi nomor_kip (saveDataBeasiswa)

### Views (2 files):
3. **`resources/views/tab_templates/tab-data-siswa.blade.php`**
   - Line 376: Update input field nomor_kip (maxlength, oninput pattern)
   - Line 378: Update help text

4. **`resources/views/tab_templates/tab-data-orangtua.blade.php`**
   - Line 555: Update input field nomor_kks
   - Line 557: Update help text KKS
   - Line 563: Update input field nomor_pkh
   - Line 565: Update help text PKH

---

## 🧪 Testing Checklist

### Test di Pendaftar - Tab Data Siswa
URL: `https://pmbm.mtsn2kotamalang.sch.id/pendaftar/formulir?activeTab=siswa`

**Nomor KIP:**
- [ ] Bisa input huruf: `ABC123` ✓
- [ ] Bisa input angka: `1234567` ✓
- [ ] Bisa input kombinasi: `KIP2024ABC` ✓
- [ ] Maksimal 25 karakter
- [ ] Tidak bisa input karakter spesial: `@#$%` (otomatis terhapus)
- [ ] Tidak bisa input spasi
- [ ] Validasi error muncul jika lebih dari 25 karakter

### Test di Pendaftar - Tab Data Orang Tua
URL: `https://pmbm.mtsn2kotamalang.sch.id/pendaftar/formulir?activeTab=orangtua`

**Nomor KKS:**
- [ ] Bisa input huruf: `ABC123` ✓
- [ ] Bisa input angka: `1234567` ✓
- [ ] Bisa input kombinasi: `KKS2024ABC` ✓
- [ ] Maksimal 25 karakter
- [ ] Tidak bisa input karakter spesial
- [ ] Validasi error muncul jika lebih dari 25 karakter

**Nomor PKH:**
- [ ] Bisa input huruf: `ABC123` ✓
- [ ] Bisa input angka: `1234567` ✓
- [ ] Bisa input kombinasi: `PKH2024ABC` ✓
- [ ] Maksimal 25 karakter
- [ ] Tidak bisa input karakter spesial
- [ ] Validasi error muncul jika lebih dari 25 karakter

### Test di Admin - Edit Data Siswa
URL: `https://pmbm.mtsn2kotamalang.sch.id/admin/pendaftar/13`

- [ ] Tab "Data Siswa" → Edit Data → Input KIP dengan huruf+angka → Simpan → Berhasil
- [ ] Tab "Data Orang Tua" → Edit Data → Input KKS dengan huruf+angka → Simpan → Berhasil
- [ ] Tab "Data Orang Tua" → Edit Data → Input PKH dengan huruf+angka → Simpan → Berhasil

---

## 📊 Regex Pattern Explanation

**Pattern:** `/^[A-Za-z0-9]+$/`

- `^` = Mulai dari awal string
- `[A-Za-z0-9]` = Karakter yang dibolehkan:
  - `A-Z` = Huruf besar
  - `a-z` = Huruf kecil
  - `0-9` = Angka
- `+` = Minimal 1 karakter
- `$` = Sampai akhir string

**Contoh VALID:**
- `ABC123`
- `kip2024`
- `KKS123ABC`
- `PKH456XYZ`
- `123456789`
- `ABCDEFG`

**Contoh INVALID:**
- `ABC-123` ❌ (ada dash)
- `KIP 2024` ❌ (ada spasi)
- `PKH@2024` ❌ (ada @)
- `KKS_123` ❌ (ada underscore)

---

## 🚀 Cara Upload

File ini sudah included di batch script utama:

```cmd
cd C:\ppdbmtsn2km-laravel
upload_all_fixes_complete.bat
```

Script akan upload:
1. ✅ 3 Controllers (Admin, Panitia, Pendaftar)
2. ✅ 4 Views (Admin, Panitia, tab-data-siswa, tab-data-orangtua)
3. ✅ Clear all caches

---

## 📝 Catatan Penting

1. **Backward Compatibility:**
   - Data lama yang hanya angka tetap valid
   - Data baru bisa huruf+angka maksimal 25 karakter

2. **Database Schema:**
   - Tidak perlu migration karena kolom sudah `VARCHAR`
   - Cukup update validasi di aplikasi

3. **Input Filtering:**
   - Real-time filtering dengan `oninput` event
   - Karakter selain A-Z, a-z, 0-9 otomatis dihapus saat user mengetik

4. **User Experience:**
   - Tidak perlu `inputmode="numeric"` lagi
   - Keyboard akan tampil keyboard standar (bukan numeric)
   - Help text sudah diupdate untuk menjelaskan format baru

---

Tanggal: 3 Februari 2026 - 07:30 WIB
Status: ✅ READY FOR UPLOAD
