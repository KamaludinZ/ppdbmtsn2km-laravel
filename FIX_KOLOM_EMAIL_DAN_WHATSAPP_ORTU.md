# Fix Kolom Email dan WhatsApp Orang Tua

**Tanggal:** 2 Februari 2026
**Status:** ✅ SELESAI

## 📋 Ringkasan Perubahan

Telah dilakukan perbaikan pada halaman `/admin/pendaftar` dan `/panitia/pendaftar` untuk:
1. ✅ Mengubah kolom "Kontak" menjadi kolom "Email" (menghapus nomor HP yang ditampilkan)
2. ✅ Memastikan tombol "Kirim WhatsApp" menggunakan nomor WhatsApp Orang Tua (`telepon_ortu`)

---

## 🔧 File yang Dimodifikasi

### 1. **Admin Pendaftar - View**
**File:** `resources/views/livewire/admin/pendaftar.blade.php`

**Perubahan:**
- ✅ Mengubah header kolom dari "Kontak" menjadi "Email" (line 275)
- ✅ Menghapus tampilan nomor HP di bawah email (line 449-452)

**Sebelum:**
```blade
@if($showColumns['kontak'])
    <th>Kontak</th>
@endif

<!-- ... -->

@if($showColumns['kontak'])
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm text-gray-900">{{ $item->email }}</div>
        @if($item->no_hp)
            <div class="text-sm text-gray-500">{{ $item->no_hp }}</div>
        @endif
    </td>
@endif
```

**Sesudah:**
```blade
@if($showColumns['kontak'])
    <th>Email</th>
@endif

<!-- ... -->

@if($showColumns['kontak'])
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm text-gray-900">{{ $item->email }}</div>
    </td>
@endif
```

---

### 2. **Panitia Pendaftar - View**
**File:** `resources/views/livewire/panitia/pendaftar.blade.php`

**Perubahan:**
- ✅ Mengubah header kolom dari "Kontak" menjadi "Email" (line 259)
- ✅ Menghapus tampilan nomor HP di bawah email (line 398-401)

**Perubahan sama dengan Admin Pendaftar**

---

### 3. **Admin Pendaftar - Controller**
**File:** `app/Livewire/Admin/Pendaftar.php`

**Perubahan pada method `sendMessage()`:**

**Sebelum (line 446):**
```php
// Determine recipient based on message type
$penerima = $this->messageType === 'email' ? $pendaftar->email : $pendaftar->no_hp;
```

**Sesudah:**
```php
// Determine recipient based on message type
$penerima = $this->messageType === 'email' ? $pendaftar->email : $pendaftar->telepon_ortu;
```

**Sebelum (line 479-487):**
```php
$phoneNumber = $pendaftar->no_hp;
if (!$phoneNumber) {
    // Update log to failed
    $riwayatPesan->update([
        'status' => 'gagal',
        'error_message' => 'Nomor WhatsApp tidak ditemukan',
    ]);

    session()->flash('error', 'Nomor WhatsApp tidak ditemukan.');
    return;
}
```

**Sesudah:**
```php
$phoneNumber = $pendaftar->telepon_ortu;
if (!$phoneNumber) {
    // Update log to failed
    $riwayatPesan->update([
        'status' => 'gagal',
        'error_message' => 'Nomor WhatsApp Orang Tua tidak ditemukan',
    ]);

    session()->flash('error', 'Nomor WhatsApp Orang Tua tidak ditemukan.');
    return;
}
```

---

## 📊 Dampak Perubahan

### Tampilan UI
- **Admin/Pendaftar:**
  - Kolom "Kontak" sekarang bernama "Email"
  - Hanya menampilkan alamat email pendaftar
  - Nomor HP pendaftar tidak lagi ditampilkan di kolom ini

- **Panitia/Pendaftar:**
  - Perubahan yang sama dengan Admin

### Fungsionalitas WhatsApp
- **SEBELUM:** Tombol "Kirim WhatsApp" mengirim ke nomor HP pendaftar (`no_hp`)
- **SEKARANG:** Tombol "Kirim WhatsApp" mengirim ke nomor WhatsApp Orang Tua (`telepon_ortu`)

### Error Message
- Pesan error lebih spesifik: "Nomor WhatsApp Orang Tua tidak ditemukan" (sebelumnya: "Nomor WhatsApp tidak ditemukan")

---

## ✅ Cara Testing

### 1. Test Tampilan Kolom Email
1. Login sebagai **Admin** atau **Panitia**
2. Buka halaman **Data Pendaftar**
3. **Verifikasi:**
   - ✅ Header kolom menampilkan "Email" (bukan "Kontak")
   - ✅ Kolom hanya menampilkan alamat email
   - ✅ Tidak ada nomor HP yang ditampilkan di bawah email

### 2. Test Tombol Kirim WhatsApp
1. Login sebagai **Admin**
2. Buka halaman **Data Pendaftar**
3. Klik tombol **"Kirim WhatsApp"** pada salah satu pendaftar
4. **Verifikasi:**
   - ✅ Modal terbuka untuk kirim WhatsApp
   - ✅ Jika data pendaftar **memiliki `telepon_ortu`**, pesan akan disiapkan
   - ✅ Jika data pendaftar **tidak memiliki `telepon_ortu`**, muncul error: "Nomor WhatsApp Orang Tua tidak ditemukan"

### 3. Test di Production
**PENTING:** Sebelum upload ke production, test terlebih dahulu di local:

```bash
# Test di local development
php artisan serve
```

Buka browser dan test semua fungsi di atas.

---

## 📝 Catatan Penting

### Field Database yang Digunakan
- **`email`** - Email pendaftar (untuk kolom Email)
- **`telepon_ortu`** - Nomor WhatsApp Orang Tua (untuk tombol WhatsApp)
- **`no_hp`** - Nomor HP pendaftar (tidak lagi ditampilkan di kolom kontak)

### Kolom "Ortu WhatsApp"
- Masih tersedia sebagai kolom terpisah (`ortu_whatsapp`)
- Dapat diaktifkan melalui Filter Kolom
- Menampilkan field `telepon_ortu`

### Backward Compatibility
- ✅ Tidak ada perubahan database
- ✅ Tidak ada perubahan routes
- ✅ Tidak ada perubahan model relationships
- ✅ Hanya perubahan tampilan dan logika pengiriman WhatsApp

---

## 🚀 Upload ke Production

### File yang Perlu Di-upload:
```
resources/views/livewire/admin/pendaftar.blade.php
resources/views/livewire/panitia/pendaftar.blade.php
app/Livewire/Admin/Pendaftar.php
```

### Perintah Upload (via SSH):
```bash
# Masuk ke server
ssh mtsnkot4@103.30.244.77

# Masuk ke direktori project
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Backup file lama (opsional)
cp resources/views/livewire/admin/pendaftar.blade.php resources/views/livewire/admin/pendaftar.blade.php.backup-20260202
cp resources/views/livewire/panitia/pendaftar.blade.php resources/views/livewire/panitia/pendaftar.blade.php.backup-20260202
cp app/Livewire/Admin/Pendaftar.php app/Livewire/Admin/Pendaftar.php.backup-20260202

# Clear cache
php artisan view:clear
php artisan cache:clear

# Verify
tail -100 storage/logs/laravel.log
```

### Atau via Batch File (Windows):
```batch
@echo off
echo Uploading Email and WhatsApp Ortu Fixes...

scp resources/views/livewire/admin/pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
scp resources/views/livewire/panitia/pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/
scp app/Livewire/Admin/Pendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/

echo.
echo Upload complete! Remember to clear cache on server:
echo ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear && php artisan cache:clear"

pause
```

---

## ✅ Checklist Sebelum Production

- [ ] Test di local development berhasil
- [ ] Test tampilan kolom Email (admin & panitia)
- [ ] Test tombol WhatsApp dengan data yang **memiliki** `telepon_ortu`
- [ ] Test tombol WhatsApp dengan data yang **tidak memiliki** `telepon_ortu`
- [ ] Backup file production
- [ ] Upload file ke production
- [ ] Clear cache di production
- [ ] Test di production
- [ ] Verifikasi log error (tidak ada error baru)

---

## 🎯 Hasil Akhir

### ✅ Kolom Email
- Header: **"Email"** (bukan "Kontak")
- Isi: **Hanya alamat email** (tanpa nomor HP)

### ✅ Tombol WhatsApp
- Menggunakan: **`telepon_ortu`** (nomor WhatsApp Orang Tua)
- Error message jelas jika nomor tidak ada

### ✅ Data Lengkap
- Field `no_hp` masih tersimpan di database
- Bisa diakses via kolom terpisah jika diperlukan
- Field `telepon_ortu` digunakan untuk WhatsApp

---

**Dibuat oleh:** Claude Code
**Tanggal:** 2 Februari 2026
