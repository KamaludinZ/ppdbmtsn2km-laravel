# FITUR LINK GRUP WHATSAPP DAFTAR ULANG - 21 FEBRUARI 2026

## 📋 RINGKASAN FITUR

Fitur ini menambahkan tombol "Bergabung ke Grup WhatsApp" yang muncul di halaman Daftar Ulang tab Madrasah **di atas bagian Surat Pernyataan**, segera setelah pendaftar setuju melakukan daftar ulang dan mendapat akses ke halaman ini.

### Perbedaan dengan Link Grup yang Sudah Ada:

| Fitur | Link Grup (Existing) | Link Grup Daftar Ulang (Baru) |
|-------|---------------------|------------------------------|
| **Lokasi Tampil** | Dashboard Pendaftar & Kartu Tes | Tab Madrasah - **DI ATAS Surat Pernyataan** |
| **Kriteria Muncul** | Status kelulusan | User bisa akses halaman daftar ulang (status_pendaftaran = 'data_disetujui') |
| **Target User** | Semua yang lulus | Yang setuju melakukan daftar ulang |
| **Timing** | Setelah lulus | **SEGERA** setelah setuju daftar ulang (LEBIH AWAL) |
| **Setting Key** | `link_grup_url`, `link_grup_enabled` | `link_grup_daftar_ulang_url`, `link_grup_daftar_ulang_enabled` |
| **Pengaturan di** | `/admin/pengaturan-kartu?activeTab=link-grup` | `/admin/pengaturan-kartu?activeTab=link-grup` (bagian bawah) |

---

## 📂 FILES YANG DIMODIFIKASI (3 files)

### 1. **app/Livewire/Admin/PengaturanKartu.php**
   - **Lokasi**: Backend controller untuk pengaturan kartu
   - **Perubahan**:
     - Tambah properties: `link_grup_daftar_ulang_url`, `link_grup_daftar_ulang_enabled`
     - Update `mount()` method: Load settings dari database
     - Tambah method `saveLinkGrupDaftarUlang()`: Simpan settings

### 2. **resources/views/livewire/admin/pengaturan-kartu.blade.php**
   - **Lokasi**: Admin UI untuk settings
   - **Perubahan**:
     - Tambah section baru di tab "Link Grup"
     - Toggle untuk enable/disable link grup daftar ulang
     - Input field untuk URL grup daftar ulang
     - Preview tombol seperti yang akan muncul di Tab Madrasah

### 3. **resources/views/livewire/pendaftar/daftar-ulang.blade.php**
   - **Lokasi**: Halaman daftar ulang pendaftar
   - **Perubahan**:
     - Tambah tombol "Bergabung ke Grup WhatsApp" di Tab Madrasah
     - Tombol hanya muncul jika:
       1. `$isMadrasahApproved` = true (status = 'disetujui')
       2. `link_grup_daftar_ulang_enabled` = true
       3. `link_grup_daftar_ulang_url` tidak kosong

---

## 🎯 CARA MENGGUNAKAN

### A. Mengaktifkan Fitur (Admin)

1. Login sebagai Admin
2. Navigasi ke: `/admin/pengaturan-kartu?activeTab=link-grup`
3. Scroll ke bagian **"Link Grup WhatsApp/Telegram - Khusus Daftar Ulang"** (di bawah)
4. Aktifkan toggle **"Aktifkan Link Grup Daftar Ulang"**
5. Masukkan URL grup WhatsApp atau Telegram di field **"URL Link Grup Daftar Ulang"**
   - Contoh WhatsApp: `https://chat.whatsapp.com/ABC123XYZ`
   - Contoh Telegram: `https://t.me/nama_grup`
6. Klik tombol **"Simpan Link Grup Daftar Ulang"**
7. Preview tombol akan muncul di bawah form

### B. Cara Pendaftar Melihat Tombol

1. Login sebagai Pendaftar
2. Setuju untuk melakukan daftar ulang (status_pendaftaran = 'data_disetujui')
3. Navigasi ke: `/pendaftar/daftarulang?activeTab=madrasah`
4. **SYARAT TOMBOL MUNCUL**:
   - User bisa akses halaman daftar ulang (sudah setuju daftar ulang)
   - Admin sudah mengaktifkan toggle link grup daftar ulang
   - Admin sudah memasukkan URL grup

5. Jika semua syarat terpenuhi, tombol hijau **"Segera Bergabung ke Grup Daftar Ulang!"** akan muncul **DI ATAS bagian Surat Pernyataan**
6. Klik tombol → otomatis buka link grup di tab baru

**PENTING**: Tombol muncul **SEGERA** setelah pendaftar setuju daftar ulang, TIDAK perlu menunggu panitia approve daftar ulang madrasah!

---

## 🔍 TECHNICAL DETAILS

### Database Settings (Tidak Perlu Migration)

Settings disimpan di tabel `settings` menggunakan key-value:

```php
// Key yang digunakan:
'link_grup_daftar_ulang_url'      => 'https://...'  // String URL
'link_grup_daftar_ulang_enabled'  => true/false     // Boolean
```

### Blade Template Logic

**Di pengaturan-kartu.blade.php (Admin)**:
```blade
<form wire:submit.prevent="saveLinkGrupDaftarUlang">
    <div x-data="{ enabled: @entangle('link_grup_daftar_ulang_enabled') }">
        <!-- Toggle Switch Alpine.js -->
    </div>
    <input wire:model="link_grup_daftar_ulang_url" />
</form>
```

**Di daftar-ulang.blade.php (Pendaftar)**:
```blade
@else
{{-- Form untuk mengisi data (status belum menunggu/disetujui) --}}

{{-- Link Grup Daftar Ulang - Muncul saat sudah setuju daftar ulang --}}
@php
    $linkGrupDaftarUlangEnabled = \App\Models\Setting::get('link_grup_daftar_ulang_enabled', false);
    $linkGrupDaftarUlangUrl = \App\Models\Setting::get('link_grup_daftar_ulang_url', '');
@endphp

@if($linkGrupDaftarUlangEnabled && !empty($linkGrupDaftarUlangUrl))
    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600...">
        <h4>Segera Bergabung ke Grup Daftar Ulang!</h4>
        <p>Bergabunglah ke grup WhatsApp khusus untuk calon siswa...</p>
        <a href="{{ $linkGrupDaftarUlangUrl }}">Bergabung ke Grup WhatsApp</a>
        <p class="italic">* Silakan bergabung sebelum melanjutkan proses daftar ulang</p>
    </div>
@endif

{{-- Di atas bagian Surat Pernyataan --}}
@if($showPernyataan)
    <!-- Surat Pernyataan -->
@endif
```

**Posisi**: DI ATAS Surat Pernyataan, dalam blok `@else` (bukan di dalam `@if($isMadrasahApproved)`)

### Livewire Method

**PengaturanKartu.php**:
```php
public function saveLinkGrupDaftarUlang()
{
    $this->validate([
        'link_grup_daftar_ulang_url' => 'nullable|url|max:500',
        'link_grup_daftar_ulang_enabled' => 'boolean',
    ], [
        'link_grup_daftar_ulang_url.url' => 'Link grup harus berupa URL yang valid...',
        'link_grup_daftar_ulang_url.max' => 'Link grup maksimal 500 karakter',
    ]);

    Setting::set('link_grup_daftar_ulang_url', $this->link_grup_daftar_ulang_url);
    Setting::set('link_grup_daftar_ulang_enabled', $this->link_grup_daftar_ulang_enabled);
    session()->flash('message', 'Link grup daftar ulang berhasil disimpan.');
}
```

---

## ✅ TESTING CHECKLIST

### Test 1: Admin Settings - Enable/Disable Toggle

- [ ] Login sebagai Admin
- [ ] Akses: `/admin/pengaturan-kartu?activeTab=link-grup`
- [ ] Scroll ke bagian "Link Grup WhatsApp/Telegram - Khusus Daftar Ulang"
- [ ] Klik toggle untuk enable
- [ ] **VERIFY**: Toggle berubah dari abu-abu ke hijau
- [ ] **VERIFY**: Tulisan "Aktifkan Link Grup Daftar Ulang" terlihat
- [ ] Klik toggle lagi untuk disable
- [ ] **VERIFY**: Toggle kembali abu-abu

### Test 2: Admin Settings - Save URL

- [ ] Aktifkan toggle link grup daftar ulang
- [ ] Isi URL: `https://chat.whatsapp.com/TEST123`
- [ ] Klik "Simpan Link Grup Daftar Ulang"
- [ ] **VERIFY**: Flash message "Link grup daftar ulang berhasil disimpan." muncul
- [ ] **VERIFY**: Preview link muncul dengan URL yang benar
- [ ] **VERIFY**: Preview tombol "Bergabung ke Grup WhatsApp" muncul

### Test 3: Admin Settings - Validation

- [ ] Aktifkan toggle
- [ ] Isi URL invalid: `bukan-url`
- [ ] Klik "Simpan Link Grup Daftar Ulang"
- [ ] **VERIFY**: Error message "Link grup harus berupa URL yang valid..." muncul
- [ ] Kosongkan URL
- [ ] Klik "Simpan Link Grup Daftar Ulang"
- [ ] **VERIFY**: Berhasil disimpan (URL optional)

### Test 4: Pendaftar - Button NOT Shown (Toggle Disabled)

- [ ] Admin: Disable toggle link grup daftar ulang
- [ ] Login sebagai Pendaftar yang sudah setuju daftar ulang
- [ ] Akses: `/pendaftar/daftarulang?activeTab=madrasah`
- [ ] **VERIFY**: Tombol "Segera Bergabung ke Grup Daftar Ulang!" **TIDAK MUNCUL**

### Test 5: Pendaftar - Button NOT Shown (Belum Setuju Daftar Ulang)

- [ ] Login sebagai Pendaftar dengan status_pendaftaran **BUKAN** 'data_disetujui'
- [ ] **VERIFY**: Tidak bisa akses `/pendaftar/daftarulang` (redirect atau error)

### Test 6: Pendaftar - Button SHOWN (All Conditions Met) ⭐

- [ ] Admin: Enable toggle dan isi URL `https://chat.whatsapp.com/TEST123`
- [ ] Login sebagai Pendaftar
- [ ] Pendaftar: Setuju melakukan daftar ulang (status_pendaftaran = 'data_disetujui')
- [ ] Pendaftar: Akses `/pendaftar/daftarulang?activeTab=madrasah`
- [ ] **VERIFY**: Tombol "Segera Bergabung ke Grup Daftar Ulang!" muncul **DI ATAS Surat Pernyataan**
- [ ] **VERIFY**: Heading "Segera Bergabung ke Grup Daftar Ulang!" terlihat
- [ ] **VERIFY**: Teks "Bergabunglah ke grup WhatsApp khusus untuk calon siswa..." terlihat
- [ ] **VERIFY**: Teks kecil "* Silakan bergabung sebelum melanjutkan proses daftar ulang" terlihat
- [ ] Scroll ke bawah
- [ ] **VERIFY**: Bagian "Surat Pernyataan" berada DI BAWAH tombol grup
- [ ] Klik tombol "Bergabung ke Grup WhatsApp"
- [ ] **VERIFY**: Link terbuka di tab baru dengan URL yang benar

### Test 7: Pendaftar - Button ONLY on Tab Madrasah

- [ ] Login sebagai Pendaftar (sudah setuju daftar ulang)
- [ ] Akses: `/pendaftar/daftarulang?activeTab=seragam`
- [ ] **VERIFY**: Tombol "Bergabung ke Grup WhatsApp" **TIDAK MUNCUL** di Tab Seragam
- [ ] Akses: `/pendaftar/daftarulang?activeTab=ukuran`
- [ ] **VERIFY**: Tombol **TIDAK MUNCUL** di Tab Ukuran
- [ ] Akses: `/pendaftar/daftarulang?activeTab=madrasah`
- [ ] **VERIFY**: Tombol **MUNCUL** di Tab Madrasah (di atas Surat Pernyataan)

### Test 8: Database Settings Persistence

- [ ] Admin: Enable toggle dan isi URL
- [ ] Simpan
- [ ] Logout
- [ ] Login lagi sebagai Admin
- [ ] Akses: `/admin/pengaturan-kartu?activeTab=link-grup`
- [ ] **VERIFY**: Toggle masih enabled (hijau)
- [ ] **VERIFY**: URL masih tersimpan

---

## 🚨 TROUBLESHOOTING

### Tombol Tidak Muncul di Tab Madrasah

**Kemungkinan Penyebab**:

1. **Toggle belum diaktifkan**
   - Cek: `/admin/pengaturan-kartu?activeTab=link-grup`
   - Pastikan toggle "Aktifkan Link Grup Daftar Ulang" berwarna hijau

2. **URL belum diisi**
   - Cek: `/admin/pengaturan-kartu?activeTab=link-grup`
   - Pastikan field "URL Link Grup Daftar Ulang" tidak kosong

3. **Pendaftar belum setuju daftar ulang**
   - Cek `status_pendaftaran` di database
   - Status harus **'data_disetujui'** (sudah diterima dan setuju daftar ulang)
   - Jika belum, pendaftar harus setuju daftar ulang terlebih dahulu

4. **Cache belum dibersihkan**
   - Run: `php artisan view:clear`
   - Run: `php artisan cache:clear`
   - Refresh browser dengan Ctrl+F5

5. **Posisi salah**
   - Tombol harus muncul **DI ATAS** bagian "Surat Pernyataan"
   - Jika muncul di posisi lain, cek file daftar-ulang.blade.php line 578-613

### Settings Tidak Tersimpan

**Solusi**:

1. **Cek error validation**
   - Buka browser console (F12)
   - Lihat apakah ada error message

2. **URL format salah**
   - URL harus dimulai dengan `http://` atau `https://`
   - Contoh valid: `https://chat.whatsapp.com/ABC123`

3. **Cek Laravel log**
   ```bash
   tail -f storage/logs/laravel.log
   ```

### Tombol Muncul di Tab Lain (Bukan Madrasah)

**Ini BUG** - seharusnya tombol hanya muncul di Tab Madrasah.

**Cek**:
- File: `resources/views/livewire/pendaftar/daftar-ulang.blade.php`
- Tombol harus berada di dalam blok `@if($activeTab === 'madrasah')` dan bagian `@else` (form section)
- Posisi: Line 578-613 (di atas Surat Pernyataan)

### Tombol Muncul di Posisi Salah

**Jika tombol muncul tapi tidak di atas Surat Pernyataan**:

**Cek**:
- File: `resources/views/livewire/pendaftar/daftar-ulang.blade.php`
- Tombol harus ada di line 578-613
- Urutan harus:
  1. Tombol "Segera Bergabung ke Grup Daftar Ulang!" (line 584-613)
  2. Bagian "Surat Pernyataan" (line 615+)
- Jika urutan terbalik, pindahkan blok tombol ke atas

---

## 📤 UPLOAD KE PRODUCTION

### Files to Upload (3 files)

```bash
# 1. Backend Controller
app/Livewire/Admin/PengaturanKartu.php

# 2. Admin View
resources/views/livewire/admin/pengaturan-kartu.blade.php

# 3. Pendaftar View
resources/views/livewire/pendaftar/daftar-ulang.blade.php
```

### Upload Commands (Manual)

```bash
# Connect via SSH
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Via SCP (Windows)
scp "app/Livewire/Admin/PengaturanKartu.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/

scp "resources/views/livewire/admin/pengaturan-kartu.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/

scp "resources/views/livewire/pendaftar/daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/

# Clear cache (via SSH)
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear && php artisan cache:clear"
```

### ⚠️ IMPORTANT: NO DATABASE MIGRATION NEEDED

Fitur ini **TIDAK MEMERLUKAN MIGRASI DATABASE** karena menggunakan sistem Settings yang sudah ada.

---

## 📊 SUMMARY

### What Was Added:

✅ **Admin Settings**:
- Toggle untuk enable/disable link grup daftar ulang
- Input field untuk URL grup
- Preview tombol

✅ **Pendaftar UI**:
- Tombol "Bergabung ke Grup WhatsApp" di Tab Madrasah
- Conditional display berdasarkan status approval

✅ **Database**:
- Tidak ada perubahan schema (menggunakan Settings existing)

### Files Modified: 3
### Migrations: 0
### Testing Steps: 8

---

**Date**: 21 Februari 2026
**Feature**: Link Grup WhatsApp Daftar Ulang
**Status**: Ready for Production Upload
**Impact**: Low (No database changes, no breaking changes)

---

## 🔗 RELATED DOCUMENTATION

- **Full Upload Guide**: RANGKUMAN_FINAL_LENGKAP_21_FEB_2026.md
- **Quick Reference**: QUICK_REFERENCE_COMPLETE_21_FEB_2026.txt
- **Upload Checklist**: CHECKLIST_UPLOAD_21_FEB_2026.md
