# Fitur Pengaturan Alert Perbedaan Ukuran Seragam Harian

**Tanggal**: 22 Februari 2026
**Status**: ✅ Complete - Ready for Testing

## 📋 Deskripsi Fitur

Menambahkan kontrol pengaturan untuk alert perbedaan ukuran seragam harian di halaman `/pendaftar/daftarulang?activeTab=ukuran`. Admin dapat mengatur:

1. **Enable/Disable Alert** - Mengaktifkan atau menonaktifkan tampilan alert
2. **Mode Perhitungan Saran Pembayaran**:
   - **Ukuran Terbesar** (Default) - Pembayaran sesuai ukuran terbesar antara baju dan celana/rok harian
   - **Ukuran Baju Harian** - Pembayaran sesuai ukuran baju seragam harian/atasan yang dipilih

## 🎯 User Request

> "bantu perbaiki untuk informasi di /pendaftar/daftarulang?activeTab=ukuran yaitu pada saat muncul alert Perhatian: Perbedaan Ukuran Seragam Harian Terdeteksi! agar bisa di atur dari /admin/daftar-ulang?activeTab=settings pada bagian pengaturan daftar ulang seragam apakah diaktifkan atau di non aktifkan dan juga bisa memilih alert saran pembayaran disesuaikan dengan ukuran terbesar (seperti sekarang) atau sesuai dengan ukuran baju seragam harian/atasan (yang telah dipilih)"

## ⚙️ Cara Kerja

### 1. Pengaturan di Admin

Admin dapat mengakses pengaturan di:
```
/admin/daftar-ulang?activeTab=settings
→ Scroll ke "Pengaturan Ukuran Seragam"
→ Bagian: "Pengaturan Alert Perbedaan Ukuran Seragam Harian"
```

**Opsi Pengaturan:**

✅ **Checkbox: Tampilkan Alert Perbedaan Ukuran**
- Jika dicentang: Alert akan muncul saat ada perbedaan ukuran
- Jika tidak dicentang: Alert tidak akan muncul sama sekali

📊 **Radio Button: Mode Perhitungan Saran Pembayaran**

**Opsi 1: Sesuai Ukuran Terbesar** _(Default)_
```
Text alert: "Anda diharuskan memilih dan membayar sesuai dengan ukuran terbesar yang telah Anda pilih."
Ukuran untuk pembayaran: [ukuran terbesar antara M dan L] atau [L]
```

**Opsi 2: Sesuai Ukuran Baju Seragam Harian**
```
Text alert: "Anda diharuskan memilih dan membayar sesuai dengan ukuran baju seragam harian/atasan yang telah Anda pilih."
Ukuran untuk pembayaran: [M] (ukuran baju yang dipilih)
```

### 2. Tampilan untuk Pendaftar

Alert muncul di 2 lokasi:
- `/pendaftar/daftarulang?activeTab=seragam` (di atas form pembayaran seragam)
- `/pendaftar/daftarulang?activeTab=ukuran` (di bawah ringkasan ukuran)

**Kondisi Munculnya Alert:**
1. ✅ Setting `ukuranAlertPerbedaanEnabled` = true
2. ✅ Ada perbedaan ukuran baju dan celana/rok harian

**Contoh Tampilan Alert:**

```
┌────────────────────────────────────────────────────────────┐
│ ⚠️  Perhatian: Perbedaan Ukuran Seragam Harian Terdeteksi! │
├────────────────────────────────────────────────────────────┤
│ Anda memilih ukuran yang berbeda untuk:                   │
│ • Baju seragam harian: M                                   │
│ • Celana/rok harian: L                                     │
│                                                            │
│ 📌 Informasi Penting untuk Pembayaran:                     │
│ Saat melakukan pembayaran daftar ulang seragam,            │
│ Anda diharuskan memilih dan membayar sesuai dengan         │
│ [ukuran terbesar / ukuran baju seragam harian/atasan]     │
│ yang telah Anda pilih.                                     │
│                                                            │
│ 💰 Ukuran untuk pembayaran: L                              │
│    (atau M jika mode = baju_harian)                        │
└────────────────────────────────────────────────────────────┘
```

## 🐛 Bug Fix

**Issue:** Undefined variable `$recommendedSize` error di line 2876

**Root Cause:** Variable `$recommendedSize` hanya dihitung di alert pertama (tab seragam), tapi tidak di alert kedua (tab ukuran)

**Solution:** Tambahkan logika perhitungan `$recommendedSize` di block PHP kedua (lines 2728-2755)

```php
// Set recommendedSize based on mode
if ($ukuranAlertPerhitunganMode === 'baju_harian') {
    $recommendedSize = $actualUkuranBajuHarian;
} else {
    $recommendedSize = $largerSize;
}
```

## 📁 File yang Dimodifikasi

### Backend (Livewire Components)

**1. app/Livewire/Admin/DaftarUlang.php**
```php
// Added Properties
public $ukuranAlertPerbedaanEnabled = true;
public $ukuranAlertPerhitunganMode = 'terbesar';

// Added in mount()
$this->ukuranAlertPerbedaanEnabled = Setting::get('ukuran_alert_perbedaan_enabled', true);
$this->ukuranAlertPerhitunganMode = Setting::get('ukuran_alert_perhitungan_mode', 'terbesar');

// Added in saveUkuranSettings()
Setting::set('ukuran_alert_perbedaan_enabled', $this->ukuranAlertPerbedaanEnabled, 'boolean');
Setting::set('ukuran_alert_perhitungan_mode', $this->ukuranAlertPerhitunganMode, 'string');
```

**2. app/Livewire/Pendaftar/DaftarUlang.php**
```php
// Added Properties
public $ukuranAlertPerbedaanEnabled = true;
public $ukuranAlertPerhitunganMode = 'terbesar';

// Added in mount()
$this->ukuranAlertPerbedaanEnabled = Setting::get('ukuran_alert_perbedaan_enabled', true);
$this->ukuranAlertPerhitunganMode = Setting::get('ukuran_alert_perhitungan_mode', 'terbesar');
```

### Frontend (Blade Views)

**3. resources/views/livewire/admin/daftar-ulang.blade.php**

Added UI section untuk pengaturan (lines ~879-920):
```blade
<!-- Pengaturan Alert Perbedaan Ukuran Seragam Harian -->
<div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
    <!-- Checkbox Enable/Disable -->
    <input type="checkbox" wire:model="ukuranAlertPerbedaanEnabled">

    <!-- Radio Mode Perhitungan -->
    <input type="radio" wire:model="ukuranAlertPerhitunganMode" value="terbesar">
    <input type="radio" wire:model="ukuranAlertPerhitunganMode" value="baju_harian">
</div>
```

**4. resources/views/livewire/pendaftar/daftar-ulang.blade.php**

Updated PHP logic (lines ~1422-1455):
```php
// Added recommendedSize calculation based on mode
if ($ukuranAlertPerhitunganMode === 'baju_harian') {
    $recommendedSize = $actualUkuranBajuHarian;
} else {
    $recommendedSize = $largerSize;
}
```

Updated alert condition (2 locations):
```blade
@if($ukuranAlertPerbedaanEnabled && $hasSizeDifference)
    <div class="bg-orange-50 ...">
        <!-- Dynamic text based on mode -->
        @if($ukuranAlertPerhitunganMode === 'baju_harian')
            ukuran baju seragam harian/atasan
        @else
            ukuran terbesar
        @endif

        <!-- Use recommendedSize instead of largerSize -->
        {{ $recommendedSize }}
    </div>
@endif
```

## 🗄️ Database Settings

Settings baru yang ditambahkan:

| Key | Type | Default | Description |
|-----|------|---------|-------------|
| `ukuran_alert_perbedaan_enabled` | boolean | `true` | Kontrol tampil/sembunyikan alert |
| `ukuran_alert_perhitungan_mode` | string | `'terbesar'` | Mode perhitungan: 'terbesar' atau 'baju_harian' |

## 🧪 Testing Guide

### Test 1: Enable/Disable Alert

1. Login sebagai Admin
2. Buka `/admin/daftar-ulang?activeTab=settings`
3. Scroll ke "Pengaturan Alert Perbedaan Ukuran Seragam Harian"
4. ✅ **Centang** "Tampilkan Alert Perbedaan Ukuran" → Klik "Simpan Pengaturan Ukuran"
5. Login sebagai Pendaftar yang memiliki ukuran berbeda (baju ≠ celana)
6. Buka `/pendaftar/daftarulang?activeTab=ukuran`
7. ✅ Alert **HARUS MUNCUL**
8. Kembali ke Admin, **Uncheck** checkbox → Simpan
9. Refresh halaman pendaftar
10. ✅ Alert **TIDAK MUNCUL**

### Test 2: Mode Perhitungan - Ukuran Terbesar

**Setup:**
- Pendaftar memilih:
  - Baju Harian: M
  - Celana Harian: L

**Expected:**
```
Alert text: "...membayar sesuai dengan ukuran terbesar..."
Ukuran untuk pembayaran: L
```

**Steps:**
1. Login Admin → Settings
2. Pilih radio "Sesuai Ukuran Terbesar"
3. Simpan
4. Login Pendaftar → Tab Ukuran
5. ✅ Alert menampilkan "ukuran terbesar"
6. ✅ Ukuran pembayaran: **L**

### Test 3: Mode Perhitungan - Ukuran Baju Harian

**Setup:**
- Pendaftar memilih:
  - Baju Harian: M
  - Celana Harian: L

**Expected:**
```
Alert text: "...membayar sesuai dengan ukuran baju seragam harian/atasan..."
Ukuran untuk pembayaran: M
```

**Steps:**
1. Login Admin → Settings
2. Pilih radio "Sesuai Ukuran Baju Seragam Harian"
3. Simpan
4. Login Pendaftar → Tab Ukuran
5. ✅ Alert menampilkan "ukuran baju seragam harian/atasan"
6. ✅ Ukuran pembayaran: **M**

### Test 4: Tidak Ada Perbedaan Ukuran

**Setup:**
- Pendaftar memilih:
  - Baju Harian: M
  - Celana Harian: M (sama)

**Expected:**
- ✅ Alert **TIDAK MUNCUL** (regardless of setting)

### Test 5: Alert Muncul di 2 Lokasi

1. Login Pendaftar dengan ukuran berbeda
2. Buka `/pendaftar/daftarulang?activeTab=seragam`
3. ✅ Alert muncul **di atas form pembayaran**
4. Buka `/pendaftar/daftarulang?activeTab=ukuran`
5. ✅ Alert muncul **di bawah ringkasan ukuran**
6. ✅ Text alert **konsisten** di kedua lokasi

## 📤 Upload ke Production

**File yang Perlu Diupload:**

```bash
# Backend
app/Livewire/Admin/DaftarUlang.php
app/Livewire/Pendaftar/DaftarUlang.php

# Frontend
resources/views/livewire/admin/daftar-ulang.blade.php
resources/views/livewire/pendaftar/daftar-ulang.blade.php
```

**Cara Upload:**

```bash
# Run script
./upload_fitur_pengaturan_alert_ukuran_22_feb_2026.bat
```

**Atau manual via SSH:**

```bash
scp app/Livewire/Admin/DaftarUlang.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
scp app/Livewire/Pendaftar/DaftarUlang.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
scp resources/views/livewire/admin/daftar-ulang.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
scp resources/views/livewire/pendaftar/daftar-ulang.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/

# Clear cache
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
```

## ✅ Checklist Deployment

- [ ] Backup file production yang akan diubah
- [ ] Upload 4 file yang dimodifikasi
- [ ] Clear cache di server production
- [ ] Test enable/disable alert
- [ ] Test mode "Ukuran Terbesar"
- [ ] Test mode "Ukuran Baju Harian"
- [ ] Test alert muncul di tab seragam
- [ ] Test alert muncul di tab ukuran
- [ ] Verify text alert dinamis sesuai mode

## 📝 Catatan Penting

1. **Default Settings:**
   - Alert enabled by default
   - Mode = 'terbesar' (consistent dengan behavior sebelumnya)

2. **Backward Compatibility:**
   - Settings menggunakan default value jika belum diset
   - Tidak ada breaking changes untuk pendaftar yang sudah ada

3. **Admin UX:**
   - UI pengaturan dibuat dengan warna orange untuk match dengan alert warning
   - Penjelasan jelas untuk setiap opsi

4. **Pendaftar UX:**
   - Alert hanya muncul jika ada perbedaan ukuran
   - Text dinamis sesuai mode yang dipilih admin
   - Consistent di 2 lokasi (tab seragam & ukuran)

## 🎉 Summary

Fitur ini memberikan **fleksibilitas penuh** kepada admin untuk mengatur:
1. **Kapan** alert muncul (enable/disable)
2. **Bagaimana** pembayaran dihitung (terbesar vs baju harian)

Dengan UI yang jelas dan konsisten untuk pendaftar! 🚀
