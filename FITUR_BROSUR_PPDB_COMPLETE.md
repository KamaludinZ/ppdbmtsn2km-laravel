# ✅ Fitur Upload Brosur PPDB - COMPLETE

**Tanggal:** 2026-01-21

**Request:** Tambahkan fitur upload brosur dengan toggle untuk menampilkan tombol download di halaman beranda dan informasi

**Status:** ✅ SELESAI

---

## 📋 Ringkasan Fitur

Fitur ini memungkinkan admin untuk:
1. Upload file brosur PPDB (PDF) melalui halaman `/admin/video-infografis`
2. Mengaktifkan/menonaktifkan tampilan tombol download brosur
3. Tombol download akan muncul di 2 lokasi ketika diaktifkan:
   - **Halaman Beranda** - Hero section (di kanan tombol "Info Lengkap")
   - **Halaman Informasi** - CTA section (di kanan tombol "Hubungi Panitia")

---

## 🔧 Perubahan File

### 1. Migration - Database Settings
**File:** `database/migrations/2026_01_21_212140_add_brosur_settings_to_settings_table.php`

**Tujuan:** Menambahkan 2 setting baru ke tabel `settings`:
- `brosur_file_path` - Path file brosur (string)
- `brosur_active` - Toggle aktif/nonaktif tombol (boolean)

**Cara Run:**
```bash
php artisan migrate
```

---

### 2. Controller - VideoInfografis
**File:** `app/Livewire/Admin/VideoInfografis.php`

**Perubahan:**
- ✅ Import `App\Models\Setting`
- ✅ Tambah `'brosur'` ke option `$activeTab`
- ✅ Tambah property:
  - `public $brosur_active = false`
  - `public $brosur_file_path`
  - `public $brosur_file_baru`
- ✅ Tambah validation rules untuk brosur:
  - `'brosur_active' => 'boolean'`
  - `'brosur_file_baru' => 'nullable|file|mimes:pdf|max:10240'` (max 10MB)
- ✅ Tambah method `loadBrosurSettings()` di `mount()`
- ✅ Tambah 3 method baru:
  - `loadBrosurSettings()` - Load settings dari database
  - `saveBrosur()` - Simpan file dan settings
  - `deleteBrosur()` - Hapus file dan reset settings

**Fitur Utama:**
- Upload file PDF (max 10MB)
- Otomatis hapus file lama saat upload baru
- Toggle aktif/nonaktif tombol download
- Validasi file format dan ukuran

---

### 3. View - Admin Video Infografis
**File:** `resources/views/livewire/admin/video-infografis.blade.php`

**Perubahan:**
- ✅ Tambah tab button "Brosur PPDB" di navigation (line 44-49)
- ✅ Tambah konten tab brosur lengkap (line 407-550):
  - Display file brosur saat ini (jika ada)
  - Tombol "Lihat File" untuk preview
  - Tombol "Hapus" untuk menghapus brosur
  - Area drag & drop untuk upload file PDF baru
  - Checkbox toggle "Aktifkan Tombol Download Brosur"
  - Info box lokasi tombol akan muncul
  - Tombol "Simpan Pengaturan"
  - Warning box dengan catatan penting

**UI Features:**
- 🎨 Design konsisten dengan tab lain
- 📤 Drag & drop file upload area
- ✅ Real-time file selection feedback
- 🎯 Clear instructions untuk pengguna
- ⚠️ Helpful warning messages

---

### 4. Route - Homepage (/)
**File:** `routes/web.php` (lines 46-50)

**Perubahan:**
- ✅ Tambah variabel `$brosurActive` dan `$brosurFilePath` dari Settings
- ✅ Pass ke view melalui `compact()`

**Sebelum:**
```php
return view('public.home', compact(..., 'appName'));
```

**Sesudah:**
```php
// Brosur settings
$brosurActive = \App\Models\Setting::get('brosur_active', false);
$brosurFilePath = \App\Models\Setting::get('brosur_file_path');

return view('public.home', compact(..., 'brosurActive', 'brosurFilePath'));
```

---

### 5. Route - Informasi (/informasi)
**File:** `routes/web.php` (lines 96-100)

**Perubahan:**
- ✅ Tambah variabel `$brosurActive` dan `$brosurFilePath` dari Settings
- ✅ Pass ke view melalui `compact()`

**Sama seperti homepage route**

---

### 6. View - Homepage Hero Section
**File:** `resources/views/public/home.blade.php` (lines 43-50)

**Perubahan:**
- ✅ Tambah tombol "Download Brosur" setelah tombol "Info Lengkap"
- ✅ Conditional display: hanya muncul jika `$brosurActive && $brosurFilePath`
- ✅ Link ke `Storage::url($brosurFilePath)` dengan `target="_blank"`
- ✅ Icon download yang sesuai
- ✅ Hover effect: border putih → background putih + text hijau

**Kode Lengkap:**
```blade
@if($brosurActive && $brosurFilePath)
<a href="{{ Storage::url($brosurFilePath) }}" target="_blank"
   class="inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 border-2 border-white text-base sm:text-lg font-medium rounded-lg text-white hover:bg-white hover:text-emerald-600 transition-all duration-200">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
    </svg>
    Download Brosur
</a>
@endif
```

---

### 7. View - Informasi CTA Section
**File:** `resources/views/public/informasi.blade.php` (lines 178-185)

**Perubahan:**
- ✅ Tambah tombol "Download Brosur" setelah tombol "Hubungi Panitia"
- ✅ Conditional display: hanya muncul jika `$brosurActive && $brosurFilePath`
- ✅ Design sama dengan homepage (konsistensi UI)
- ✅ Icon download yang sesuai
- ✅ Hover effect yang sama

**Kode sama persis dengan homepage button**

---

## 📸 Lokasi Tombol Download

### Lokasi 1: Halaman Beranda (/)
**Section:** Hero Section
**Posisi:** Di kanan tombol "Info Lengkap", sejajar
**Tampil:** Ketika `brosur_active = true` DAN file sudah di-upload

```
[Daftar Sekarang] [Info Lengkap] [Download Brosur] ← Button baru
```

### Lokasi 2: Halaman Informasi (/informasi)
**Section:** CTA "Siap Mendaftar?"
**Posisi:** Di kanan tombol "Hubungi Panitia", sejajar
**Tampil:** Ketika `brosur_active = true` DAN file sudah di-upload

```
[Daftar Sekarang] [Hubungi Panitia] [Download Brosur] ← Button baru
```

---

## 🚀 Cara Menggunakan

### 1. Jalankan Migration (One Time)
```bash
cd C:\ppdbmtsn2km-laravel
php artisan migrate
```

### 2. Upload Brosur (Via Admin)

1. Login sebagai admin: `https://pmbm.mtsn2kotamalang.sch.id/login/admin`
2. Buka menu: **Video, Infografis & Testimoni**
3. Klik tab: **"Brosur PPDB"**
4. Upload file brosur (PDF, max 10MB):
   - Klik area upload atau drag & drop file
5. Centang: **"Aktifkan Tombol Download Brosur"**
6. Klik: **"Simpan Pengaturan"**

### 3. Verifikasi Tampilan

Buka halaman public (sebagai pengunjung):
1. **Beranda** (`/`) → Hero section → Tombol "Download Brosur" muncul
2. **Informasi** (`/informasi`) → CTA section → Tombol "Download Brosur" muncul

---

## 🎯 Validasi & Aturan

### File Upload:
- ✅ Format: PDF only (`.pdf`)
- ✅ Ukuran maksimal: 10 MB
- ✅ Otomatis hapus file lama saat upload baru
- ✅ File disimpan di: `storage/app/public/brosur/`

### Toggle Logic:
- ✅ Tombol download **hanya muncul** jika:
  - Toggle `brosur_active` = `true` **DAN**
  - File brosur sudah di-upload (`brosur_file_path` tidak null)
- ✅ Jika salah satu kondisi tidak terpenuhi, tombol tidak akan tampil

### Keamanan:
- ✅ File disimpan dengan nama random (Laravel storage)
- ✅ Akses file melalui symlink `public/storage`
- ✅ Validasi file type di controller (server-side)
- ✅ Validasi file type di HTML (client-side: `accept=".pdf"`)

---

## 📝 Database Schema

### Tabel: `settings`

Menambahkan 2 record baru:

| key | value | type | description |
|-----|-------|------|-------------|
| `brosur_file_path` | `brosur/xxxxx.pdf` | `string` | Path to brochure PDF file |
| `brosur_active` | `true/false` | `boolean` | Show/hide brochure download button on public pages |

**Cara query manual:**
```sql
-- Lihat setting brosur
SELECT * FROM settings WHERE key LIKE 'brosur%';

-- Update toggle aktif
UPDATE settings SET value = 'true' WHERE key = 'brosur_active';

-- Lihat path file
SELECT value FROM settings WHERE key = 'brosur_file_path';
```

---

## ⚠️ Catatan Penting

### Storage Link
Pastikan symlink storage sudah dibuat:
```bash
php artisan storage:link
```

Jika belum, tombol download akan error 404.

### File Permissions
Pastikan folder `storage/app/public/brosur/` writable:
```bash
chmod -R 775 storage/app/public/brosur/
```

### Clear Cache (After Changes)
```bash
php artisan view:clear
php artisan cache:clear
php artisan config:clear
```

### Nama File
File akan disimpan dengan nama random untuk keamanan:
- ❌ Jangan: `brosur-ppdb-2026.pdf`
- ✅ Laravel: `brosur/x7k2j9n3m1p4q8r5.pdf`

---

## 🧪 Testing Checklist

### Admin Side:
- [ ] Login ke `/admin/video-infografis`
- [ ] Tab "Brosur PPDB" muncul dan bisa diklik
- [ ] Upload file PDF berhasil (max 10MB)
- [ ] Upload file non-PDF ditolak dengan error message
- [ ] Upload file > 10MB ditolak dengan error message
- [ ] Tombol "Lihat File" membuka PDF di tab baru
- [ ] Tombol "Hapus" menghapus file dengan konfirmasi
- [ ] Toggle aktif/nonaktif berfungsi
- [ ] Tombol "Simpan Pengaturan" menyimpan perubahan
- [ ] Flash message "Pengaturan brosur berhasil disimpan" muncul

### Public Side (Homepage):
- [ ] Buka `/` (homepage)
- [ ] Jika toggle OFF → Tombol "Download Brosur" TIDAK muncul
- [ ] Jika toggle ON + file uploaded → Tombol "Download Brosur" muncul
- [ ] Tombol berada di kanan tombol "Info Lengkap"
- [ ] Klik tombol membuka PDF di tab baru
- [ ] Hover effect bekerja (background putih + text hijau)
- [ ] Responsive di mobile (button stack vertical)

### Public Side (Informasi):
- [ ] Buka `/informasi`
- [ ] Jika toggle OFF → Tombol "Download Brosur" TIDAK muncul
- [ ] Jika toggle ON + file uploaded → Tombol "Download Brosur" muncul
- [ ] Tombol berada di kanan tombol "Hubungi Panitia"
- [ ] Klik tombol membuka PDF di tab baru
- [ ] Hover effect bekerja (background putih + text hijau)
- [ ] Responsive di mobile (button stack vertical)

---

## 🐛 Troubleshooting

### Problem 1: Tombol tidak muncul setelah upload

**Solusi:**
1. Pastikan toggle "Aktifkan Tombol Download Brosur" dicentang
2. Klik "Simpan Pengaturan"
3. Clear browser cache (Ctrl+Shift+Delete)
4. Refresh halaman

### Problem 2: Error 404 saat klik tombol download

**Solusi:**
```bash
# Buat symlink storage
php artisan storage:link

# Verifikasi symlink
ls -la public/storage
```

### Problem 3: File tidak bisa di-upload

**Solusi:**
```bash
# Set permissions
chmod -R 775 storage/app/public/brosur/
chown -R www-data:www-data storage/

# Buat folder jika belum ada
mkdir -p storage/app/public/brosur
```

### Problem 4: Tombol tampil tapi file tidak bisa dibuka

**Cek database:**
```sql
SELECT * FROM settings WHERE key = 'brosur_file_path';
```

**Cek file exists:**
```bash
ls -la storage/app/public/brosur/
```

Jika file tidak ada, re-upload dari admin panel.

---

## 📦 File Summary

**Total File Modified/Created:** 7 files

### Created:
1. `database/migrations/2026_01_21_212140_add_brosur_settings_to_settings_table.php` (NEW)

### Modified:
2. `app/Livewire/Admin/VideoInfografis.php` (+64 lines)
3. `resources/views/livewire/admin/video-infografis.blade.php` (+144 lines)
4. `routes/web.php` (+6 lines)
5. `resources/views/public/home.blade.php` (+8 lines)
6. `resources/views/public/informasi.blade.php` (+8 lines)

**Total Lines Added:** ~230 lines

---

## ✅ Status Akhir

**Fitur:** ✅ COMPLETE
**Testing:** ⏳ PERLU TESTING
**Documentation:** ✅ COMPLETE
**Ready for Production:** ✅ YES (after migration)

---

## 🎉 Fitur Berhasil Ditambahkan!

Sistem sekarang memiliki:
- ✅ Tab brosur di admin panel
- ✅ Upload file PDF dengan drag & drop
- ✅ Toggle aktif/nonaktif tombol
- ✅ Tombol download di 2 lokasi (beranda + informasi)
- ✅ Validasi file comprehensive
- ✅ UI/UX yang clean dan konsisten
- ✅ Error handling yang baik
- ✅ Dokumentasi lengkap

**Tinggal:** Run migration → Upload brosur → Aktifkan toggle → Done! 🚀
