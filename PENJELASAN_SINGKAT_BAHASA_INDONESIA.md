# Penjelasan Singkat - Perbaikan Blade View (9 Feb 2026)

## ✅ MASALAH SUDAH DIPERBAIKI!

---

## 🔍 Apa yang Anda Laporkan

Anda sudah aktifkan toggle individual di Admin untuk Pendaftar, tapi **"Opsi Lainnya" tidak muncul** di halaman Pendaftar untuk:
- Tab Daftar Ulang Madrasah
- Tab Daftar Ulang Seragam
- Tab Ukuran Seragam

---

## 🎯 Penyebab Masalah

**Masalah utama:**
- Logika di component sudah BENAR (cek Global OR Individual)
- Tapi file blade view mengabaikan logika component
- Blade view query database langsung (hanya cek global setting)
- Akibatnya toggle individual tidak ada efek

**Analoginya seperti:**
- Component sudah bilang "Tampilkan opsi lainnya untuk user ini!"
- Tapi view tidak dengar, view tanya langsung ke database: "Apakah global setting ON?"
- Kalau global OFF → view tidak tampilkan, meskipun individual ON

---

## ✅ Perbaikan yang Sudah Dilakukan

**File yang diperbaiki:**
`resources/views/livewire/pendaftar/daftar-ulang.blade.php`

**4 lokasi diubah:**
1. Baris 756: Madrasah dropdown paket
2. Baris 859: Madrasah konfirmasi nominal
3. Baris 1618: Seragam dropdown paket
4. Baris 1720: Seragam konfirmasi nominal

**Yang diubah:**

❌ **SEBELUM (Salah):**
```php
$madrasahPackagesEnableOpsiLainnya = \App\Models\Setting::get('daftar_ulang_madrasah_packages_enable_opsi_lainnya', false);
// Query database langsung, hanya cek global setting
```

✅ **SESUDAH (Benar):**
```php
$madrasahPackagesEnableOpsiLainnya = $this->shouldShowMadrasahOpsiLainnyaDropdown;
// Pakai computed property dari component, cek Global OR Individual
```

---

## 🧪 Cara Test Sekarang (Lokal)

### Test 1: Cek Toggle Individual Bekerja

1. **Di Admin:**
   ```
   Buka: http://localhost/admin/daftar-ulang?activeTab=madrasah
   Cari pendaftar manapun di tabel
   Klik toggle BIRU "Dropdown Opsi Lainnya" → Nyalakan (ON)
   Pastikan: Toggle berubah warna biru/hijau, muncul notifikasi sukses
   ```

2. **Di Pendaftar:**
   ```
   Login sebagai pendaftar yang tadi di-toggle
   Buka: http://localhost/pendaftar/daftar-ulang?activeTab=madrasah
   Scroll ke dropdown "Pilih Paket Daftar Ulang Madrasah"
   ```

3. **✅ Hasil yang Diharapkan:**
   - **"Opsi Lainnya" SEKARANG MUNCUL di dropdown**
   - Bisa pilih "Opsi Lainnya" dan masukkan nominal custom
   - Ini HARUS bekerja meskipun global setting OFF

### Test 2: Cek Pendaftar Lain Tidak Lihat

1. **Login sebagai pendaftar yang berbeda (yang toggle-nya OFF)**
2. **Buka halaman yang sama**
3. **✅ Hasil yang Diharapkan:**
   - "Opsi Lainnya" TIDAK muncul
   - Ini membuktikan toggle individual bekerja per-user

---

## ⚠️ PENTING: Tentang Tab Ukuran

### Anda Tanyakan: "begitu juga di tab ukuran seragam"

**Klarifikasi:**

Tab Ukuran Seragam **TIDAK PUNYA** toggle individual.

**Kenapa?**

| Fitur | Toggle Individual | Alasan |
|-------|-------------------|--------|
| Madrasah Dropdown | ✅ ADA | Migrasi database sudah tambah kolom |
| Madrasah Konfirmasi | ✅ ADA | Migrasi database sudah tambah kolom |
| Seragam Dropdown | ✅ ADA | Migrasi database sudah tambah kolom |
| Seragam Konfirmasi | ✅ ADA | Migrasi database sudah tambah kolom |
| **Ukuran Jas** | ❌ TIDAK ADA | Migrasi database TIDAK tambah kolom |
| **Ukuran Baju Olahraga** | ❌ TIDAK ADA | Migrasi database TIDAK tambah kolom |
| **Ukuran Celana Olahraga** | ❌ TIDAK ADA | Migrasi database TIDAK tambah kolom |
| **Ukuran Baju Harian** | ❌ TIDAK ADA | Migrasi database TIDAK tambah kolom |
| **Ukuran Celana/Rok Harian** | ❌ TIDAK ADA | Migrasi database TIDAK tambah kolom |
| **Ukuran Kopyah** | ❌ TIDAK ADA | Migrasi database TIDAK tambah kolom |

**Untuk Ukuran:**
- Hanya ada **global setting** (untuk semua pendaftar)
- Tidak bisa enable untuk pendaftar tertentu saja
- Kalau global setting ON → SEMUA pendaftar lihat "Opsi Lainnya"
- Kalau global setting OFF → TIDAK ADA pendaftar lihat "Opsi Lainnya"

**Ini by design** (memang begitu didesain dari awal).

---

## 📦 File yang Diubah

**Session ini (Perbaikan Blade View):**
- `resources/views/livewire/pendaftar/daftar-ulang.blade.php` (4 lokasi)

**File lain (dari pekerjaan sebelumnya - sudah selesai):**
1. `app/Models/DaftarUlang.php`
2. `app/Models/Seragam.php`
3. `app/Livewire/Admin/DaftarUlang.php`
4. `resources/views/livewire/admin/daftar-ulang.blade.php`
5. `app/Livewire/Pendaftar/DaftarUlang.php`
6. `database/migrations/2026_02_09_100000_add_individual_opsi_lainnya_to_daftar_ulang_and_seragam.php`

---

## 🚀 Upload ke Production

### Setelah Test Lokal Berhasil:

**Jalankan script:**
```bash
.\upload_fitur_opsi_individual_BLADE_FIX_9_feb_2026.bat
```

**Script akan:**
1. Upload semua 7 file ke production
2. Ingatkan untuk run migration via SSH
3. Clear cache production
4. Siap test di production

**Langkah manual yang diperlukan:**
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan migrate
```

---

## 💡 Cara Kerja Sekarang

### Logika: Global Setting ATAU Individual Setting

```
JIKA global_setting == ON:
    → Tampilkan "Opsi Lainnya" untuk SEMUA pendaftar

JIKA TIDAK DAN individual_setting == ON:
    → Tampilkan "Opsi Lainnya" untuk pendaftar ITU SAJA

JIKA TIDAK:
    → Sembunyikan "Opsi Lainnya"
```

### Tabel Kebenaran

| Global | Individual | Hasil | Penjelasan |
|--------|-----------|-------|------------|
| ✅ ON | ❌ OFF | ✅ Tampil | Global punya prioritas |
| ✅ ON | ✅ ON | ✅ Tampil | Keduanya ON (global menang) |
| ❌ OFF | ✅ ON | ✅ Tampil | Individual saja |
| ❌ OFF | ❌ OFF | ❌ Sembunyi | Keduanya OFF |

---

## 📋 Ringkasan Singkat

### Yang Salah:
- Blade view query database langsung
- Tidak pakai computed property dari component
- Toggle individual tidak ada efek

### Yang Diperbaiki:
- Blade view sekarang pakai computed property
- Menghormati logika Global OR Individual
- Toggle individual sekarang bekerja

### Cara Test:
1. Enable toggle individual untuk satu pendaftar
2. Login sebagai pendaftar itu
3. Cek: "Opsi Lainnya" MUNCUL di dropdown
4. Login sebagai pendaftar lain
5. Cek: "Opsi Lainnya" TIDAK MUNCUL

### Tentang Ukuran:
- Ukuran TIDAK PUNYA toggle individual
- Hanya ada global setting
- Ini memang by design
- Semua pendaftar lihat opsi ukuran yang sama

---

## 🎉 Siap Test!

**Cache sudah di-clear:** ✅
**File sudah diubah:** ✅
**Dokumentasi sudah dibuat:** ✅

**Langkah selanjutnya:**
1. Test di lokal (ikuti langkah Test 1 & Test 2 di atas)
2. Pastikan "Opsi Lainnya" sekarang muncul saat toggle ON
3. Kalau sudah bekerja, upload ke production

---

## 📞 Jika Ada Masalah

### Kalau masih belum muncul:

1. **Hard refresh browser:**
   - Windows: Tekan `Ctrl + Shift + F5`
   - Mac: Tekan `Cmd + Shift + R`

2. **Clear cache browser:**
   - Chrome: Settings → Privacy → Clear browsing data
   - Firefox: Settings → Privacy → Clear Data

3. **Clear cache Laravel:**
   ```bash
   php artisan optimize:clear
   ```

4. **Cek nilai di database:**
   ```bash
   php artisan tinker
   $pendaftar = App\Models\Pendaftar::find(1);
   $pendaftar->daftarUlang->opsi_lainnya_dropdown_individual;
   // Harus return: true
   ```

---

**Tanggal:** 9 Feb 2026
**Masalah:** Toggle individual tidak mempengaruhi tampilan Pendaftar
**Penyebab:** Blade view pakai query langsung, bukan computed property
**Solusi:** Ubah blade view pakai computed property (4 lokasi)
**Status:** ✅ SUDAH DIPERBAIKI
**Silakan test sekarang!**

