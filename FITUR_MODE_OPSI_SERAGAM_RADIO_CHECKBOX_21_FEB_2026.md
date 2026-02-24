# Fitur Mode Opsi Seragam: Radio Button atau Checkbox
**Tanggal:** 21 Februari 2026
**Tujuan:** Membuat pilihan opsi paket seragam bisa menggunakan radio button (single select) atau checkbox (multi select)

## Ringkasan Fitur

Saat ini, pilihan opsi pada paket seragam hanya menggunakan **radio button** (single select). Fitur ini akan menambahkan kemampuan untuk:

1. **Admin** dapat memilih mode opsi (radio/checkbox) untuk setiap paket seragam
2. **Pendaftar** akan melihat radio button atau checkbox sesuai pengaturan admin
3. **Konfirmasi Nominal** akan otomatis menjumlahkan jika mode checkbox (multi-select)
4. **Invoice** akan menampilkan rincian semua opsi yang dipilih jika multi-select

## Struktur Data

### Admin Settings - seragamPackages
```json
[
  {
    "id": "pkg_unique_id",
    "name": "Paket Lengkap",
    "description": "Semua seragam dan atribut",
    "selection_mode": "radio",  // NEW: "radio" atau "checkbox"
    "options": [
      {
        "id": "opt_unique_id",
        "name": "Semua dengan Jas",
        "amount": 1500000
      },
      {
        "id": "opt_unique_id_2",
        "name": "Tanpa Jas",
        "amount": 1200000
      }
    ]
  }
]
```

### Pendaftar Data - opsiSeragam
```json
// Jika radio (single select):
{
  "id": "opt_unique_id",
  "name": "Semua dengan Jas",
  "amount": 1500000
}

// Jika checkbox (multi select):
[
  {
    "id": "opt_unique_id",
    "name": "Semua dengan Jas",
    "amount": 1500000
  },
  {
    "id": "opt_unique_id_2",
    "name": "Tanpa Jas",
    "amount": 1200000
  }
]
```

## Perubahan yang Diperlukan

### 1. Admin: Blade View Settings
**File:** `resources/views/livewire/admin/daftar-ulang.blade.php`

Tambahkan dropdown selection_mode untuk setiap package:
```blade
<select wire:model="seragamPackages.{{ $pkgIndex }}.selection_mode">
  <option value="radio">Radio Button (Pilih Satu)</option>
  <option value="checkbox">Checkbox (Pilih Banyak)</option>
</select>
```

### 2. Pendaftar: Component Logic
**File:** `app/Livewire/Pendaftar/DaftarUlang.php`

- Update property `$opsiSeragam` untuk bisa array atau single object
- Update method `saveSeragam()` untuk handle multi-select
- Update logic konfirmasi nominal untuk sum jika multi-select

### 3. Pendaftar: Blade View
**File:** `resources/views/livewire/pendaftar/daftar-ulang.blade.php`

- Deteksi `selection_mode` dari package
- Tampilkan checkbox jika mode = "checkbox"
- Tampilkan radio jika mode = "radio"
- Update konfirmasi nominal untuk auto-sum jika checkbox

### 4. Model Seragam
**File:** `app/Models/Seragam.php`

- Update cast untuk `opsi_seragam` agar bisa handle array atau single object

### 5. Admin: View Kolom Nominal
**File:** `resources/views/livewire/admin/daftar-ulang.blade.php`

- Update tampilan kolom nominal di tab seragam untuk bisa menampilkan multi-select

### 6. Invoice
**File:** `resources/views/pdf/invoice-daftar-ulang-seragam.blade.php`

- Update untuk menampilkan rincian per baris jika multi-select
- Hitung total semua selected options

## Testing

1. **Test Radio Mode (Single Select):**
   - Admin set package mode = "radio"
   - Pendaftar pilih 1 opsi
   - Konfirmasi nominal = nominal opsi yang dipilih
   - Invoice tampilkan 1 baris opsi

2. **Test Checkbox Mode (Multi Select):**
   - Admin set package mode = "checkbox"
   - Pendaftar pilih 2+ opsi
   - Konfirmasi nominal = sum semua opsi yang dipilih
   - Invoice tampilkan semua opsi dengan rincian per baris

3. **Test Backward Compatibility:**
   - Package tanpa selection_mode default ke "radio"
   - Data lama tetap berfungsi normal

## Catatan Penting

- Default selection_mode = "radio" untuk backward compatibility
- opsiSeragam di database bisa berupa single object atau array
- Konfirmasi nominal otomatis menyesuaikan berdasarkan selection_mode
- Invoice harus flexible untuk tampilkan 1 atau multiple items
