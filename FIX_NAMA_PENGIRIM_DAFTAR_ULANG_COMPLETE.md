# FIX DAFTAR ULANG - NAMA PENGIRIM FEATURE
**Tanggal:** 5 Februari 2026
**Status:** ✅ COMPLETED

## 📋 RINGKASAN PERUBAHAN

Implementasi lengkap fitur **Nama Pengirim** pada halaman Daftar Ulang (Madrasah dan Seragam), termasuk:
1. ✅ Access control untuk halaman /pendaftar/daftarulang
2. ✅ Penambahan kolom `nama_pengirim` di database
3. ✅ Update form input untuk kedua jenis pembayaran
4. ✅ Update invoice PDF untuk menampilkan nama pengirim
5. ✅ Update export Excel dengan kolom nama pengirim

---

## 🔐 1. ACCESS CONTROL

### Lokasi File
- `app/Livewire/Pendaftar/DaftarUlang.php` (method `mount()`)

### Implementasi
Halaman `/pendaftar/daftarulang` hanya dapat diakses jika memenuhi 3 kondisi:
1. **Status = 'lulus'** - Pendaftar telah dinyatakan lulus tes seleksi
2. **setuju_daftar_ulang = true** - Pendaftar telah menyetujui untuk melanjutkan daftar ulang
3. **Tanggal ≥ Payment Start Date** - Tanggal saat ini harus >= tanggal mulai pembayaran yang diatur di `/admin/daftar-ulang`

### Error Messages
- Jika status bukan 'lulus': "Halaman daftar ulang hanya dapat diakses oleh pendaftar yang dinyatakan lulus tes seleksi."
- Jika belum setuju: "Anda harus menyetujui untuk melanjutkan daftar ulang terlebih dahulu."
- Jika belum waktunya: "Pendaftaran ulang belum dibuka. Silakan cek kembali sesuai jadwal yang telah ditentukan."

---

## 💾 2. DATABASE CHANGES

### Migration Files Created
1. `database/migrations/2026_02_05_120000_add_nama_pengirim_to_daftar_ulang_table.php`
   - Menambahkan kolom `nama_pengirim` (nullable, string) ke tabel `daftar_ulang`
   - Posisi: setelah kolom `bukti_pembayaran`

2. `database/migrations/2026_02_05_120001_add_nama_pengirim_to_seragam_table.php`
   - Menambahkan kolom `nama_pengirim_seragam` (nullable, string) ke tabel `seragam`
   - Posisi: setelah kolom `bukti_pembayaran_seragam`

### Rollback
Kedua migration memiliki method `down()` untuk rollback:
```php
Schema::table('daftar_ulang', function (Blueprint $table) {
    $table->dropColumn('nama_pengirim');
});
```

---

## 📦 3. MODEL UPDATES

### File: `app/Models/DaftarUlang.php`
**Perubahan:**
- Menambahkan `'nama_pengirim'` ke array `$fillable`

### File: `app/Models/Seragam.php`
**Perubahan:**
- Menambahkan `'nama_pengirim_seragam'` ke array `$fillable`

---

## 🎮 4. CONTROLLER UPDATES

### File: `app/Livewire/Pendaftar/DaftarUlang.php`

#### Properties Added
```php
public $namaPengirim;           // Untuk pembayaran madrasah
public $namaPengirimSeragam;    // Untuk pembayaran seragam
```

#### Validation Rules Added (saveMadrasah)
```php
'namaPengirim' => 'nullable|string|max:255',
```

#### Validation Rules Added (saveSeragam)
```php
'namaPengirimSeragam' => 'nullable|string|max:255',
```

#### Data Update (saveMadrasah)
```php
$dataToUpdate = [
    // ... existing fields
    'nama_pengirim' => $this->namaPengirim,
    // ... rest of fields
];
```

#### Data Update (saveSeragam)
```php
$dataToUpdate = [
    // ... existing fields
    'nama_pengirim_seragam' => $this->namaPengirimSeragam,
    // ... rest of fields
];
```

---

## 🎨 5. VIEW UPDATES

### File: `resources/views/livewire/pendaftar/daftar-ulang.blade.php`

#### Madrasah Form (after bukti pembayaran upload)
```blade
<!-- Nama Pengirim -->
<div class="mt-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Nama Pengirim
        <span class="text-gray-500 font-normal">(Opsional)</span>
    </label>
    <input type="text" wire:model="namaPengirim" placeholder="Contoh: Budi Santoso"
           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $isMadrasahApproved ? 'bg-gray-100 cursor-not-allowed' : '' }}"
           {{ $isMadrasahApproved ? 'disabled' : '' }}>
    <p class="text-xs text-gray-500 mt-1">Isikan nama orang yang melakukan transfer pembayaran</p>
    @error('namaPengirim') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
</div>
```

#### Seragam Form (after bukti seragam upload)
```blade
<!-- Nama Pengirim Seragam -->
<div class="mt-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Nama Pengirim
        <span class="text-gray-500 font-normal">(Opsional)</span>
    </label>
    <input type="text" wire:model="namaPengirimSeragam" placeholder="Contoh: Budi Santoso"
           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $isSeragamApproved ? 'bg-gray-100 cursor-not-allowed' : '' }}"
           {{ $isSeragamApproved ? 'disabled' : '' }}>
    <p class="text-xs text-gray-500 mt-1">Isikan nama orang yang melakukan transfer pembayaran</p>
    @error('namaPengirimSeragam') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
</div>
```

---

## 📄 6. INVOICE PDF UPDATES

### File: `resources/views/pdf/invoice-daftar-ulang-madrasah.blade.php`
**Lokasi:** Section "Informasi Pembayaran", setelah "Bank Dari"

```blade
<tr>
    <td class="label" style="width: 30%;">Nama Pengirim</td>
    <td class="separator">:</td>
    <td class="value">{{ $daftarUlang->nama_pengirim ?? '-' }}</td>
</tr>
```

### File: `resources/views/pdf/invoice-daftar-ulang-seragam.blade.php`
**Lokasi:** Section "Informasi Pembayaran", setelah "Bank Dari"

```blade
<tr>
    <td class="label" style="width: 30%;">Nama Pengirim</td>
    <td class="separator">:</td>
    <td class="value">{{ $seragam->nama_pengirim_seragam ?? '-' }}</td>
</tr>
```

---

## 📊 7. EXCEL EXPORT UPDATES

### File: `app/Exports/DaftarUlangMadrasahSheet.php`

#### Heading Added (line 107)
```php
'Nama Pengirim',
```

#### Data Mapping Added (line 78)
```php
$daftarUlang->nama_pengirim ?? '-',
```

**Posisi:** Setelah kolom "Metode Pembayaran", sebelum "Status Bukti Bayar"

### File: `app/Exports/DaftarUlangSeragamSheet.php`

#### Heading Added (line 99)
```php
'Nama Pengirim',
```

#### Data Mapping Added (line 75)
```php
$seragam->nama_pengirim_seragam ?? '-',
```

**Posisi:** Setelah kolom "Bank", sebelum "Status Pembayaran"

---

## 📝 8. DAFTAR FILE YANG DIUBAH

### Database
1. `database/migrations/2026_02_05_120000_add_nama_pengirim_to_daftar_ulang_table.php` ✨ NEW
2. `database/migrations/2026_02_05_120001_add_nama_pengirim_to_seragam_table.php` ✨ NEW

### Models
3. `app/Models/DaftarUlang.php` ✏️ MODIFIED
4. `app/Models/Seragam.php` ✏️ MODIFIED

### Controllers
5. `app/Livewire/Pendaftar/DaftarUlang.php` ✏️ MODIFIED

### Views
6. `resources/views/livewire/pendaftar/daftar-ulang.blade.php` ✏️ MODIFIED
7. `resources/views/pdf/invoice-daftar-ulang-madrasah.blade.php` ✏️ MODIFIED
8. `resources/views/pdf/invoice-daftar-ulang-seragam.blade.php` ✏️ MODIFIED

### Exports
9. `app/Exports/DaftarUlangMadrasahSheet.php` ✏️ MODIFIED
10. `app/Exports/DaftarUlangSeragamSheet.php` ✏️ MODIFIED

**Total:** 10 files (2 new, 8 modified)

---

## 🚀 9. DEPLOYMENT GUIDE

### A. Upload ke Production

Gunakan script otomatis:
```bash
upload_fix_nama_pengirim.bat
```

Script akan melakukan:
1. Upload 2 migration files
2. Upload 2 model files
3. Upload 1 controller file
4. Upload 1 view file
5. Upload 2 invoice PDF files
6. Upload 2 export class files
7. Menjalankan migrations di production
8. Clear cache production

### B. Manual Upload (jika diperlukan)

#### Step 1: Upload Files
```bash
# Migrations
scp database/migrations/2026_02_05_120000_add_nama_pengirim_to_daftar_ulang_table.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/
scp database/migrations/2026_02_05_120001_add_nama_pengirim_to_seragam_table.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/

# Models
scp app/Models/DaftarUlang.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/
scp app/Models/Seragam.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/

# Controller
scp app/Livewire/Pendaftar/DaftarUlang.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/

# View
scp resources/views/livewire/pendaftar/daftar-ulang.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/

# Invoices
scp resources/views/pdf/invoice-daftar-ulang-madrasah.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/pdf/
scp resources/views/pdf/invoice-daftar-ulang-seragam.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/pdf/

# Exports
scp app/Exports/DaftarUlangMadrasahSheet.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/
scp app/Exports/DaftarUlangSeragamSheet.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/
```

#### Step 2: Run Migrations
```bash
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan migrate --force"
```

#### Step 3: Clear Cache
```bash
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
```

---

## ✅ 10. TESTING CHECKLIST

### A. Test Access Control
- [ ] Coba akses /pendaftar/daftarulang dengan status bukan 'lulus' → harus redirect ke dashboard dengan error message
- [ ] Coba akses dengan status 'lulus' tapi setuju_daftar_ulang = false → harus redirect dengan error
- [ ] Coba akses sebelum tanggal pembayaran dibuka → harus redirect dengan error
- [ ] Akses dengan semua kondisi terpenuhi → halaman harus terbuka normal

### B. Test Form Input - Madrasah
- [ ] Form nama pengirim muncul setelah upload bukti pembayaran
- [ ] Field bersifat opsional (bisa dikosongkan)
- [ ] Placeholder "Contoh: Budi Santoso" terlihat
- [ ] Field disabled jika status sudah disetujui
- [ ] Data tersimpan dengan benar saat klik "Simpan Data Madrasah"

### C. Test Form Input - Seragam
- [ ] Form nama pengirim muncul setelah upload bukti seragam
- [ ] Field bersifat opsional (bisa dikosongkan)
- [ ] Placeholder "Contoh: Budi Santoso" terlihat
- [ ] Field disabled jika status sudah disetujui
- [ ] Data tersimpan dengan benar saat klik "Simpan Data Seragam"

### D. Test Invoice PDF - Madrasah
- [ ] Download invoice madrasah
- [ ] Periksa section "Informasi Pembayaran"
- [ ] Baris "Nama Pengirim" harus muncul setelah "Bank Dari"
- [ ] Menampilkan nama pengirim jika ada, atau tanda "-" jika kosong

### E. Test Invoice PDF - Seragam
- [ ] Download invoice seragam
- [ ] Periksa section "Informasi Pembayaran"
- [ ] Baris "Nama Pengirim" harus muncul setelah "Bank Dari"
- [ ] Menampilkan nama pengirim jika ada, atau tanda "-" jika kosong

### F. Test Excel Export - Madrasah
- [ ] Export data daftar ulang madrasah ke Excel
- [ ] Buka file Excel
- [ ] Kolom "Nama Pengirim" harus ada setelah kolom "Metode Pembayaran"
- [ ] Data nama pengirim terisi dengan benar, atau "-" jika kosong

### G. Test Excel Export - Seragam
- [ ] Export data daftar ulang seragam ke Excel
- [ ] Buka file Excel
- [ ] Kolom "Nama Pengirim" harus ada setelah kolom "Bank"
- [ ] Data nama pengirim terisi dengan benar, atau "-" jika kosong

---

## 🔧 11. TROUBLESHOOTING

### Issue: Migration Error "Column already exists"
**Solusi:**
```bash
# Cek apakah kolom sudah ada
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && psql -U mtsnkot4 -d mtsnkot4_ppdb -c '\d daftar_ulang'"

# Jika sudah ada, rollback migration dulu
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan migrate:rollback --step=1"

# Lalu migrate ulang
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan migrate --force"
```

### Issue: Form field tidak muncul
**Solusi:**
```bash
# Clear view cache
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear"
```

### Issue: Data tidak tersimpan
**Cek:**
1. Pastikan kolom ada di database
2. Pastikan nama field di `$fillable` model
3. Cek validation rules
4. Cek browser console untuk error Livewire

---

## 📖 12. TECHNICAL NOTES

### Field Properties
- **Type:** String
- **Nullable:** Yes (opsional)
- **Max Length:** 255 characters
- **Default Value:** NULL
- **Validation:** `nullable|string|max:255`

### Database Columns
- `daftar_ulang.nama_pengirim` (untuk pembayaran madrasah)
- `seragam.nama_pengirim_seragam` (untuk pembayaran seragam)

### Livewire Properties
- `$namaPengirim` - bound to madrasah form
- `$namaPengirimSeragam` - bound to seragam form

### UI Behavior
- Field muncul setelah upload bukti pembayaran
- Field disabled jika status sudah disetujui
- Field tidak required (bisa dikosongkan)
- Placeholder: "Contoh: Budi Santoso"
- Helper text: "Isikan nama orang yang melakukan transfer pembayaran"

---

## 📋 13. REMAINING TASKS

### Status: PENDING ⏳
**Hide form fields when status menunggu verifikasi**

Belum diimplementasikan dalam fix ini. Untuk implementasi:
- Tambahkan conditional logic di view untuk hide form fields
- Show only status badge saat status = 'menunggu verifikasi'
- Bisa diimplementasikan di future update

---

## ✨ SUMMARY

**Completed Features:**
1. ✅ Access control untuk halaman daftar ulang
2. ✅ Database migrations untuk nama_pengirim
3. ✅ Model updates (fillable)
4. ✅ Controller logic (validation + save)
5. ✅ Form input fields (madrasah + seragam)
6. ✅ Invoice PDF display
7. ✅ Excel export columns

**Files Modified:** 10 files (2 new migrations, 8 updates)

**Ready for Production:** YES ✅

**Deployment Method:** Gunakan `upload_fix_nama_pengirim.bat`

---

**Dokumentasi dibuat:** 5 Februari 2026
**Developer:** AI Assistant
**Ticket:** Fix Nama Pengirim - Daftar Ulang Madrasah & Seragam
