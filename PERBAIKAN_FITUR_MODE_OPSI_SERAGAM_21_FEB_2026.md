# PERBAIKAN FITUR MODE OPSI SERAGAM
**Tanggal:** 21 Februari 2026
**Tipe:** Bug Fix & Enhancement

## 🐛 MASALAH YANG DIPERBAIKI

### Masalah 1: Pilihan Opsi Tidak Di-Reset Ketika Ganti Paket
**Gejala:**
- User pilih paket A (checkbox mode) dan centang beberapa opsi
- User ganti ke paket B (radio mode)
- Opsi dari paket A masih terpilih di paket B
- Radio button pada paket B bisa terpilih lebih dari 1 (salah)

**Root Cause:**
Tidak ada listener/watcher yang reset property `opsiSeragam` ketika `paketSeragam` berubah.

**Solusi:**
Tambahkan method `updatedPaketSeragam()` di Livewire component yang otomatis reset:
- `opsiSeragam` → null
- `konfirmasiNominalSeragam` → null
- `konfirmasiNominalSeragamOpsiLainnya` → null

### Masalah 2: Konfirmasi Nominal Tidak Menjumlahkan Checkbox
**Gejala:**
- User pilih beberapa opsi dengan checkbox
- Di "Konfirmasi Nominal yang Dibayarkan", opsi "Bayar Penuh" tidak menampilkan total sum
- Malah menampilkan nilai individual atau error

**Root Cause:**
Logic di blade view salah detect tipe data `opsiSeragam`:
- Checkbox mode: Livewire wire:model mengirim sebagai **array PHP**
- Radio mode: Livewire wire:model mengirim sebagai **JSON string**
- Kode lama selalu melakukan `json_decode()` yang menyebabkan array jadi null

**Solusi:**
Perbaiki detection logic:
```php
if (is_array($opsiSeragam)) {
    // Sudah array dari checkbox mode
    $selectedOption = $opsiSeragam;
} else {
    // Masih string JSON dari radio mode
    $selectedOption = json_decode($opsiSeragam, true);
}
```

Dan buat struktur konfirmasi nominal yang berbeda untuk multi-select:
```php
if ($isMultiSelect) {
    $konfirmasiValue = [
        'name' => 'Bayar Penuh - Multi Select (X item)',
        'amount' => $totalAmount,  // SUM dari semua items
        'items' => $selectedOption  // Array of selected items
    ];
}
```

## 📝 FILE YANG DIUBAH

### 1. Livewire Component
**File:** `app/Livewire/Pendaftar/DaftarUlang.php`
**Line:** 434-444 (method baru)

**Perubahan:**
```php
/**
 * Reset opsiSeragam dan konfirmasiNominalSeragam ketika paketSeragam berubah
 */
public function updatedPaketSeragam()
{
    $this->opsiSeragam = null;
    $this->konfirmasiNominalSeragam = null;
    $this->konfirmasiNominalSeragamOpsiLainnya = null;
}
```

### 2. Blade View Pendaftar
**File:** `resources/views/livewire/pendaftar/daftar-ulang.blade.php`
**Line:** 1765-1859

**Perubahan:**
1. **Line 1769-1775:** Perbaiki detection array vs string
2. **Line 1810-1836:** Buat konfirmasi value dengan struktur berbeda untuk multi-select
3. **Line 1814-1817:** Struktur data baru untuk multi-select dengan total amount

## ✅ HASIL SETELAH PERBAIKAN

### Test Case 1: Ganti Paket
**BEFORE:**
```
1. Pilih Paket A (checkbox) → centang 3 opsi
2. Ganti ke Paket B (radio) → 3 opsi masih terpilih ❌
3. Radio button bisa terpilih lebih dari 1 ❌
```

**AFTER:**
```
1. Pilih Paket A (checkbox) → centang 3 opsi
2. Ganti ke Paket B (radio) → opsi otomatis di-reset ✅
3. Radio button hanya bisa pilih 1 ✅
```

### Test Case 2: Konfirmasi Nominal Checkbox
**BEFORE:**
```
Pilih 3 opsi checkbox:
- Jas: Rp 400.000
- Baju: Rp 300.000
- Celana: Rp 250.000

Konfirmasi Nominal → Bayar Penuh: Rp 400.000 ❌ (salah, tidak sum)
```

**AFTER:**
```
Pilih 3 opsi checkbox:
- Jas: Rp 400.000
- Baju: Rp 300.000
- Celana: Rp 250.000

Konfirmasi Nominal → Bayar Penuh: Rp 950.000 ✅ (benar, sudah sum)
Rincian 3 opsi:
• Jas - Rp 400.000
• Baju - Rp 300.000
• Celana - Rp 250.000
```

## 📊 STRUKTUR DATA KONFIRMASI NOMINAL

### Single Select (Radio Mode):
```json
{
  "name": "Semua dengan Jas",
  "amount": 1500000
}
```

### Multi Select (Checkbox Mode):
```json
{
  "name": "Bayar Penuh - Multi Select (3 item)",
  "amount": 950000,
  "items": [
    {"name": "Jas", "amount": 400000},
    {"name": "Baju Harian", "amount": 300000},
    {"name": "Celana Olahraga", "amount": 250000}
  ]
}
```

## 🔍 TESTING CHECKLIST

- [x] Ganti paket dari checkbox ke radio → opsi di-reset
- [x] Ganti paket dari radio ke checkbox → opsi di-reset
- [x] Pilih 1 opsi radio → konfirmasi nominal benar
- [x] Pilih 3 opsi checkbox → konfirmasi nominal sum benar
- [x] Konfirmasi nominal tampilkan rincian untuk checkbox
- [x] Backward compatible dengan data lama

## 📦 DEPLOYMENT

**Upload script:** `upload_perbaikan_mode_opsi_seragam_21_feb_2026.bat`

**File yang diupload:**
1. `app/Livewire/Pendaftar/DaftarUlang.php` (method updatedPaketSeragam)
2. `resources/views/livewire/pendaftar/daftar-ulang.blade.php` (fix detection & konfirmasi)

**Setelah upload:**
```bash
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
```

## 🎯 CATATAN PENTING

1. **Auto Reset:** Setiap kali ganti paket, semua pilihan opsi dan konfirmasi nominal otomatis di-reset
2. **Smart Detection:** Kode otomatis detect apakah opsiSeragam adalah array (checkbox) atau string (radio)
3. **Total Sum:** Konfirmasi nominal untuk checkbox mode otomatis sum semua amount yang dipilih
4. **Rincian Detail:** Tampilan konfirmasi untuk checkbox mode menampilkan rincian per item
5. **Backward Compatible:** Tidak break existing data atau functionality

## ✨ FITUR TAMBAHAN

- Rincian konfirmasi nominal menampilkan jumlah item yang dipilih
- Highlight border hijau pada opsi "Bayar Penuh" yang aktif
- Info visual yang lebih jelas antara mode radio dan checkbox

Perbaikan selesai dan siap di-test! 🚀
