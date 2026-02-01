# Fitur Status Daftar Jalur - Dokumentasi

## Ringkasan
Telah ditambahkan fitur baru untuk mengatur kemunculan jalur pendaftaran di halaman `/daftar` terpisah dari status aktif jalur. Fitur ini memungkinkan admin untuk:
- Menjaga jalur tetap aktif tetapi tidak ditampilkan di form pendaftaran
- Mengontrol secara spesifik jalur mana saja yang muncul di halaman pendaftaran

## Perubahan yang Dilakukan

### 1. Database Migration
**File**: `database/migrations/2026_01_23_053343_add_tampil_di_daftar_to_jalur_pendaftaran_table.php`
- Menambahkan kolom `tampil_di_daftar` (boolean) dengan default `true`
- Kolom ini terpisah dari kolom `aktif` yang sudah ada

### 2. Model JalurPendaftaran
**File**: `app/Models/JalurPendaftaran.php`
- Menambahkan `tampil_di_daftar` ke `$fillable`
- Menambahkan cast `tampil_di_daftar` sebagai `boolean`
- Menambahkan scope baru `scopeTampilDiDaftar()` untuk filtering

```php
public function scopeTampilDiDaftar($query)
{
    return $query->where('tampil_di_daftar', true);
}
```

### 3. Livewire Admin Jalur
**File**: `app/Livewire/Admin/Jalur.php`
- Menambahkan property `$tampil_di_daftar`
- Update method `edit()` untuk mengisi nilai `tampil_di_daftar`
- Update method `store()` untuk menyimpan nilai `tampil_di_daftar`
- Update method `update()` untuk mengupdate nilai `tampil_di_daftar`
- Update method `resetFields()` untuk reset default `tampil_di_daftar = true`

### 4. View Admin Jalur
**File**: `resources/views/livewire/admin/jalur.blade.php`
- Menambahkan kolom header "Status Daftar" di tabel
- Menampilkan badge status:
  - **Tampil** (biru) jika `tampil_di_daftar = true`
  - **Tersembunyi** (abu-abu) jika `tampil_di_daftar = false`
- Menambahkan checkbox "Tampil di Halaman Daftar" di form modal

### 5. Halaman Registrasi
**File**: `app/Livewire/Auth/Register.php`
- Update query di method `render()`:
  ```php
  $jalurPendaftaran = JalurPendaftaran::tampilDiDaftar()->ordered()->get();
  ```
- Sekarang **HANYA** kolom `tampil_di_daftar = true` yang menentukan kemunculan di halaman `/daftar`
- Kolom "Status Aktif" **TIDAK BERPENGARUH** pada kemunculan di `/daftar`

## Cara Penggunaan

### Di Halaman Admin `/admin/jalur`:

1. **Lihat Status Jalur**
   - Kolom "Status" menunjukkan apakah jalur aktif/nonaktif secara umum
   - Kolom "Status Daftar" menunjukkan apakah jalur tampil di halaman pendaftaran

2. **Edit Jalur**
   - Klik tombol "Edit" pada jalur yang ingin diubah
   - Di form modal, terdapat 3 checkbox:
     - ☑️ **Jalur Aktif** - Status aktif umum jalur
     - ☑️ **Tampil di Halaman Daftar** - Mengatur kemunculan di `/daftar`
     - ☑️ **Paling Populer** - Penanda jalur populer

3. **Skenario Penggunaan**

   **⚠️ PENTING**: Kolom "Status Daftar" adalah SATU-SATUNYA yang menentukan kemunculan di `/daftar`!

   **Skenario A**: Jalur tampil di daftar (tidak peduli status aktif)
   - ✅ Tampil di Halaman Daftar: Checked
   - ✅ Jalur Aktif: Checked atau Unchecked (TIDAK BERPENGARUH)
   - **Hasil**: Jalur **MUNCUL** di halaman `/daftar`

   **Skenario B**: Jalur tidak tampil di daftar (tidak peduli status aktif)
   - ❌ Tampil di Halaman Daftar: Unchecked
   - ✅ Jalur Aktif: Checked atau Unchecked (TIDAK BERPENGARUH)
   - **Hasil**: Jalur **TIDAK MUNCUL** di halaman `/daftar`

   **Skenario C**: Jalur nonaktif tapi tampil di daftar
   - ❌ Jalur Aktif: Unchecked
   - ✅ Tampil di Halaman Daftar: Checked
   - **Hasil**: Jalur **TETAP MUNCUL** di halaman `/daftar` (berguna untuk soft-disable jalur tanpa menghilangkan dari form)

## Manfaat

1. **Kontrol Independen**
   - Kolom "Status Aktif" untuk keperluan sistem internal
   - Kolom "Status Daftar" KHUSUS untuk kontrol kemunculan di form pendaftaran
   - Tidak ada ketergantungan antara keduanya

2. **Fleksibilitas Maksimal**
   - Bisa menonaktifkan jalur tapi tetap tampil di form (soft disable)
   - Bisa menyembunyikan jalur dari form tanpa menonaktifkan sistem
   - Mudah mengatur jalur mana yang tampil di periode tertentu

3. **User Experience**
   - Pendaftar hanya melihat jalur yang memang tersedia untuk pendaftaran
   - Mengurangi kebingungan dengan opsi yang tidak relevan
   - Admin punya kontrol penuh atas apa yang dilihat calon pendaftar

## Testing

Untuk menguji fitur ini:

1. Akses `/admin/jalur`
2. Edit salah satu jalur
3. Ubah checkbox "Tampil di Halaman Daftar"
4. Simpan perubahan
5. Akses `/daftar` (logout jika perlu)
6. Verifikasi bahwa jalur muncul/tidak muncul sesuai pengaturan

## Catatan Penting

- **Default Value**: Semua jalur yang sudah ada otomatis memiliki `tampil_di_daftar = true`
- **Jalur baru**: Default `tampil_di_daftar = true` saat dibuat
- **⚠️ LOGIKA BARU**: Kolom "Status Daftar" adalah **SATU-SATUNYA** kontrol untuk kemunculan di `/daftar`
- **Kolom "Status Aktif"**: TIDAK mempengaruhi kemunculan di form `/daftar`
- **Independen**: Kedua kolom status bekerja secara terpisah
- **Cache**: Sudah di-clear otomatis, tidak perlu clear manual

## File yang Diubah

1. `database/migrations/2026_01_23_053343_add_tampil_di_daftar_to_jalur_pendaftaran_table.php` (NEW)
2. `app/Models/JalurPendaftaran.php` (MODIFIED)
3. `app/Livewire/Admin/Jalur.php` (MODIFIED)
4. `resources/views/livewire/admin/jalur.blade.php` (MODIFIED)
5. `app/Livewire/Auth/Register.php` (MODIFIED)

---

**Tanggal**: 23 Januari 2026
**Status**: ✅ Selesai dan Siap Digunakan
