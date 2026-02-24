# Fix Ukuran Seragam - Pengaturan Wajib/Tidak Wajib
**Tanggal:** 23 Februari 2026
**Status:** ✅ Selesai - Siap Upload Production

## 📋 Ringkasan Perubahan

### 1. **Fix Ukuran Kopyah untuk Perempuan**
- Field ukuran kopyah **hanya untuk pendaftar laki-laki**
- Untuk perempuan:
  - Field tidak ditampilkan di form (sudah benar sebelumnya)
  - **Validasi tidak require** (FIXED)
  - **Disimpan sebagai NULL** di database (FIXED)

### 2. **Pengaturan Wajib/Tidak Wajib Field Ukuran**
- Tambah pengaturan di **Admin → Daftar Ulang → Settings**
- Admin dapat mengatur apakah field ukuran **wajib semua** atau **optional**
- Default: **Tidak Wajib** (bisa isi sebagian)

## 🔧 File yang Diubah

### 1. `app/Livewire/Admin/DaftarUlang.php`
**Perubahan:**
```php
// Line 100: Tambah property baru
public $ukuranFieldsRequired = false;

// Line 183: Load setting dari database
$this->ukuranFieldsRequired = \App\Models\Setting::get('ukuran_fields_required', false);

// Line 880: Simpan setting ke database
\App\Models\Setting::set('ukuran_fields_required', $this->ukuranFieldsRequired, 'boolean');
```

**Fungsi:**
- Menambahkan property untuk menyimpan pengaturan wajib/tidak wajib
- Load dan save setting ke database
- Digunakan di admin settings

### 2. `app/Livewire/Pendaftar/DaftarUlang.php`
**Perubahan:**
```php
// Method saveUkuran() - Line 798-854
public function saveUkuran()
{
    // Check admin setting
    $ukuranFieldsRequired = Setting::get('ukuran_fields_required', false);

    if ($ukuranFieldsRequired) {
        // Mode WAJIB: Semua field required (kecuali kopyah untuk perempuan)
        $rules = [
            'ukuran_jas' => 'required|string',
            'ukuran_baju_olahraga' => 'required|string',
            'ukuran_celana_olahraga' => 'required|string',
            'ukuran_baju_harian' => 'required|string',
            'ukuran_celana_rok_harian' => 'required|string',
        ];

        // Kopyah only for laki-laki
        if ($this->pendaftar->jenis_kelamin === 'L') {
            $rules['ukuran_kopyah'] = 'required|string';
        }
    } else {
        // Mode OPTIONAL: Minimal 1 field harus diisi
        $rules = [
            'ukuran_jas' => 'nullable|string',
            // ... semua nullable
        ];

        // Validasi minimal 1 field terisi
        if (!$anyFieldFilled) {
            session()->flash('error_ukuran', 'Silakan isi minimal satu ukuran seragam.');
            return;
        }
    }
}

// Line 883-893: Simpan ukuran kopyah HANYA untuk laki-laki
if ($this->pendaftar->jenis_kelamin === 'L' && $this->ukuran_kopyah) {
    $dataToUpdate['ukuran_kopyah'] = $ukuran_kopyah_value;
} elseif ($this->pendaftar->jenis_kelamin === 'P') {
    // Perempuan: set NULL
    $dataToUpdate['ukuran_kopyah'] = null;
}
```

**Fungsi:**
- Dynamic validation berdasarkan setting admin
- Ukuran kopyah tidak required untuk perempuan
- Mode wajib: semua field harus diisi
- Mode optional: minimal 1 field harus diisi

### 3. `resources/views/livewire/admin/daftar-ulang.blade.php`
**Perubahan:**
```html
<!-- Line 879-908: Tambah UI Toggle Setting -->
<div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
    <div class="flex items-start space-x-2 mb-3">
        <label class="block text-sm font-semibold text-blue-900">
            Validasi Field Ukuran Seragam
        </label>
        <p class="text-xs text-blue-700 mt-1">
            Atur apakah semua field ukuran seragam wajib diisi atau bisa diisi sebagian saja
        </p>
    </div>

    <div class="p-3 bg-white rounded-lg border border-blue-300">
        <label class="flex items-center">
            <input type="checkbox" wire:model="ukuranFieldsRequired"
                   class="rounded border-gray-300 text-blue-600">
            <span class="ml-2 text-sm font-medium text-gray-700">
                Wajib Isi Semua Field Ukuran
            </span>
        </label>

        <p class="text-xs text-gray-600 mt-2 ml-6">
            <span class="font-semibold">Jika dicentang:</span>
            Pendaftar wajib mengisi semua field ukuran seragam
            (Jas, Baju Olahraga, Celana Olahraga, Baju Harian,
            Celana/Rok Harian, dan Kopyah untuk laki-laki).
        </p>

        <p class="text-xs text-gray-600 mt-1 ml-6">
            <span class="font-semibold">Jika tidak dicentang:</span>
            Pendaftar dapat mengisi ukuran sesuai kebutuhan (minimal 1 field),
            field yang tidak diisi akan kosong di database.
        </p>

        <div class="mt-2 ml-6 p-2 bg-yellow-50 border border-yellow-200 rounded">
            <p class="text-xs text-yellow-800">
                <strong>Catatan:</strong> Ukuran kopyah hanya untuk pendaftar laki-laki.
                Untuk perempuan, field ini tidak ditampilkan dan tidak wajib diisi.
            </p>
        </div>
    </div>
</div>
```

**Fungsi:**
- Menambahkan toggle checkbox di admin settings
- Penjelasan lengkap tentang mode wajib vs optional
- Warning tentang kopyah untuk laki-laki saja

## 📊 Database Setting

**Setting Key:** `ukuran_fields_required`
**Type:** Boolean
**Default:** `false` (tidak wajib)
**Lokasi:** Table `settings`

```sql
-- Cek setting saat ini
SELECT * FROM settings WHERE key = 'ukuran_fields_required';

-- Set manual jika perlu
INSERT INTO settings (key, value, type, created_at, updated_at)
VALUES ('ukuran_fields_required', 'false', 'boolean', NOW(), NOW())
ON DUPLICATE KEY UPDATE value = 'false', updated_at = NOW();
```

## 🎯 Cara Penggunaan

### A. Pengaturan Admin

1. **Login sebagai Admin**
2. **Buka halaman:** `/admin/daftar-ulang?activeTab=settings`
3. **Scroll ke:** "Pengaturan Ukuran Seragam"
4. **Pilih mode:**
   - ✅ **Centang** = Wajib isi semua field
   - ⬜ **Tidak centang** = Optional (minimal 1 field)
5. **Klik:** "Simpan Pengaturan Ukuran"

### B. Pengalaman Pendaftar

#### Mode WAJIB (Checkbox Dicentang)
```
Login → /pendaftar/daftarulang?activeTab=ukuran

LAKI-LAKI:
- Harus isi: Jas, Baju Olahraga, Celana Olahraga, Baju Harian, Celana Harian, Kopyah
- Jika ada 1 kosong → Error: "Ukuran [nama field] harus dipilih"

PEREMPUAN:
- Harus isi: Jas, Baju Olahraga, Celana Olahraga, Baju Harian, Rok Harian
- Field Kopyah: TIDAK ADA/TIDAK DITAMPILKAN
- Jika ada 1 kosong → Error: "Ukuran [nama field] harus dipilih"
```

#### Mode OPTIONAL (Checkbox Tidak Dicentang - DEFAULT)
```
Login → /pendaftar/daftarulang?activeTab=ukuran

LAKI-LAKI:
- Bisa isi: Minimal 1 dari (Jas, Baju Olahraga, Celana Olahraga, Baju Harian, Celana Harian, Kopyah)
- Field kosong → Disimpan sebagai NULL
- Jika semua kosong → Error: "Silakan isi minimal satu ukuran seragam"

PEREMPUAN:
- Bisa isi: Minimal 1 dari (Jas, Baju Olahraga, Celana Olahraga, Baju Harian, Rok Harian)
- Field Kopyah: TIDAK ADA/TIDAK DITAMPILKAN
- Field kosong → Disimpan sebagai NULL
- Jika semua kosong → Error: "Silakan isi minimal satu ukuran seragam"
```

## ✅ Testing Checklist

### Test 1: Admin Settings
- [ ] Login sebagai admin
- [ ] Buka `/admin/daftar-ulang?activeTab=settings`
- [ ] Lihat toggle "Wajib Isi Semua Field Ukuran"
- [ ] Centang → Simpan → Success message muncul
- [ ] Tidak centang → Simpan → Success message muncul
- [ ] Refresh halaman → Setting tersimpan

### Test 2: Pendaftar Laki-laki - Mode WAJIB
- [ ] Admin: Centang "Wajib Isi Semua Field Ukuran"
- [ ] Login sebagai pendaftar laki-laki lulus
- [ ] Buka `/pendaftar/daftarulang?activeTab=ukuran`
- [ ] Field kopyah MUNCUL
- [ ] Kosongkan 1 field → Klik Simpan → Error muncul
- [ ] Isi semua field → Klik Simpan → Berhasil!

### Test 3: Pendaftar Perempuan - Mode WAJIB
- [ ] Admin: Centang "Wajib Isi Semua Field Ukuran"
- [ ] Login sebagai pendaftar perempuan lulus
- [ ] Buka `/pendaftar/daftarulang?activeTab=ukuran`
- [ ] Field kopyah TIDAK MUNCUL
- [ ] Kosongkan 1 field → Klik Simpan → Error muncul
- [ ] Isi semua field (tanpa kopyah) → Klik Simpan → Berhasil!
- [ ] Cek database: `ukuran_kopyah` = NULL

### Test 4: Pendaftar Laki-laki - Mode OPTIONAL
- [ ] Admin: Tidak centang "Wajib Isi Semua Field Ukuran"
- [ ] Login sebagai pendaftar laki-laki lulus
- [ ] Buka `/pendaftar/daftarulang?activeTab=ukuran`
- [ ] Field kopyah MUNCUL
- [ ] Isi hanya 1 field → Klik Simpan → Berhasil!
- [ ] Cek database: Field kosong = NULL, field terisi = value
- [ ] Semua kosong → Klik Simpan → Error: "minimal satu ukuran"

### Test 5: Pendaftar Perempuan - Mode OPTIONAL
- [ ] Admin: Tidak centang "Wajib Isi Semua Field Ukuran"
- [ ] Login sebagai pendaftar perempuan lulus
- [ ] Buka `/pendaftar/daftarulang?activeTab=ukuran`
- [ ] Field kopyah TIDAK MUNCUL
- [ ] Isi hanya 1 field → Klik Simpan → Berhasil!
- [ ] Cek database: Field kosong = NULL, `ukuran_kopyah` = NULL
- [ ] Semua kosong → Klik Simpan → Error: "minimal satu ukuran"

## 📦 Upload ke Production

```bash
# Jalankan batch file
upload_fix_ukuran_seragam_pengaturan_23_feb_2026.bat

# Atau manual:
# 1. Upload file
scp app/Livewire/Admin/DaftarUlang.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
scp app/Livewire/Pendaftar/DaftarUlang.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
scp resources/views/livewire/admin/daftar-ulang.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/

# 2. Clear cache
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
```

## 🎓 Penjelasan Teknis

### Mengapa Ukuran Kopyah Khusus Laki-laki?

**Alasan:**
- Kopyah adalah peci/kopiah untuk laki-laki muslim
- Perempuan muslim menggunakan jilbab/kerudung, bukan kopyah
- Field ini hanya relevan untuk pendaftar laki-laki

**Implementasi:**
1. **Blade View:** Conditional rendering dengan `@if($pendaftar->jenis_kelamin === 'L')`
2. **Validasi:** Dynamic rules berdasarkan jenis kelamin
3. **Database:** NULL untuk perempuan, value untuk laki-laki

### Logic Mode Wajib vs Optional

**Mode WAJIB (`ukuran_fields_required = true`):**
```php
// Semua field required (kecuali kopyah untuk perempuan)
$rules = [
    'ukuran_jas' => 'required|string',
    'ukuran_baju_olahraga' => 'required|string',
    // ... semua required
];

// Kopyah conditional
if ($jenis_kelamin === 'L') {
    $rules['ukuran_kopyah'] = 'required|string';
}
```

**Mode OPTIONAL (`ukuran_fields_required = false` - DEFAULT):**
```php
// Semua field nullable
$rules = [
    'ukuran_jas' => 'nullable|string',
    'ukuran_baju_olahraga' => 'nullable|string',
    // ... semua nullable
];

// Validasi minimal 1 field terisi
$anyFieldFilled = $ukuran_jas || $ukuran_baju_olahraga || ...;

if (!$anyFieldFilled) {
    return error('Silakan isi minimal satu ukuran seragam.');
}
```

### Penyimpanan Data

**Hanya simpan field yang terisi:**
```php
$dataToUpdate = ['status_ukuran' => 'menunggu'];

// Simpan hanya jika terisi
if ($this->ukuran_jas) {
    $dataToUpdate['ukuran_jas'] = $ukuran_jas_value;
}

// Kopyah: Khusus laki-laki
if ($jenis_kelamin === 'L' && $this->ukuran_kopyah) {
    $dataToUpdate['ukuran_kopyah'] = $ukuran_kopyah_value;
} elseif ($jenis_kelamin === 'P') {
    // Perempuan: Explicitly set NULL
    $dataToUpdate['ukuran_kopyah'] = null;
}
```

## 🔍 Troubleshooting

### Issue 1: Setting tidak tersimpan
**Solusi:**
```sql
-- Cek di database
SELECT * FROM settings WHERE key = 'ukuran_fields_required';

-- Insert manual jika kosong
INSERT INTO settings (key, value, type)
VALUES ('ukuran_fields_required', 'false', 'boolean');
```

### Issue 2: Perempuan tetap error kopyah required
**Penyebab:** Cache tidak ter-clear
**Solusi:**
```bash
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
```

### Issue 3: Mode optional tetap require semua
**Penyebab:** Setting admin belum disimpan
**Solusi:**
1. Buka `/admin/daftar-ulang?activeTab=settings`
2. Pastikan checkbox **TIDAK** dicentang
3. Klik "Simpan Pengaturan Ukuran"
4. Refresh halaman pendaftar

## 📝 Catatan Penting

1. **Default Behavior:** Mode OPTIONAL (tidak wajib semua)
2. **Kopyah:** HANYA untuk laki-laki, perempuan = NULL
3. **Minimal 1 Field:** Dalam mode optional, minimal 1 field harus diisi
4. **Database NULL:** Field kosong disimpan sebagai NULL, bukan empty string
5. **Backward Compatible:** Existing data tidak terpengaruh

## ✨ Kesimpulan

Fitur ini memberikan **fleksibilitas** kepada admin untuk mengatur:
- Apakah semua field ukuran wajib diisi
- Atau pendaftar bisa isi sebagian sesuai kebutuhan

Dengan tetap memastikan:
- Ukuran kopyah hanya untuk laki-laki
- Validasi yang proper untuk setiap jenis kelamin
- Data integrity di database

---
**Dokumentasi dibuat:** 23 Februari 2026
**Status:** ✅ Ready for Production
**Next:** Upload ke production dan testing
