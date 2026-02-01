# Fix Dropdown Status Ukuran - Complete

## Masalah

User melaporkan bahwa di `/admin/daftar-ulang?activeTab=ukuran`, kolom "Ubah Status" yang muncul masih milik ubah status daftar ulang seragam, padahal seharusnya untuk ubah status ukuran seragam.

## Verifikasi

### 1. Kode Dropdown Sudah Benar ✅

**File**: `resources/views/livewire/admin/daftar-ulang.blade.php` (Line 1562-1582)

Kode dropdown untuk tab ukuran sudah benar:
- Menggunakan `wire:change="changeStatusUkuran({{ $item->id }}, $event.target.value)"`
- Menggunakan `$item->seragam->status_ukuran` untuk selected value
- Berbeda dari tab seragam yang menggunakan `status_seragam`

### 2. Backend Method Sudah Benar ✅

**File**: `app/Livewire/Admin/DaftarUlang.php` (Line 920-940)

Method `changeStatusUkuran` sudah ada dan bekerja dengan benar:
```php
public function changeStatusUkuran($pendaftarId, $status)
{
    $seragam = Seragam::firstOrCreate(
        ['pendaftar_id' => $pendaftarId],
        ['status_ukuran' => $status]
    );

    $seragam->update(['status_ukuran' => $status]);  // ✅ Update status_ukuran

    $this->dispatch('saved', message: "Status Ukuran berhasil diubah menjadi {$statusLabel}.");
}
```

### 3. Data Database Sudah Benar ✅

**Query Test**:
```json
{
    "id": 2,
    "pendaftar_id": 27,
    "status_seragam": "disetujui",     // Status untuk tab seragam
    "status_ukuran": "menunggu"        // Status untuk tab ukuran ✅ BERBEDA
}
```

Data menunjukkan `status_seragam` dan `status_ukuran` memiliki nilai yang berbeda dan independen.

## Solusi - Improvement

Meskipun kode sudah benar, saya menambahkan improvement untuk memperjelas:

### 1. Tambah Komentar pada Dropdown

**File**: `resources/views/livewire/admin/daftar-ulang.blade.php`

**Perubahan (Line 1562-1582)**:
```blade
@else
    <!-- Tab Ukuran: Update status_ukuran -->  ✅ TAMBAH KOMENTAR
    <select
        wire:change="changeStatusUkuran({{ $item->id }}, $event.target.value)"
        class="px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
    >
        <option value="">-- Pilih Status --</option>
        <option value="menunggu" {{ ($item->seragam->status_ukuran ?? '') === 'menunggu' ? 'selected' : '' }}>
            Menunggu  ✅ TAMBAH OPTION
        </option>
        <option value="disetujui" {{ ($item->seragam->status_ukuran ?? '') === 'disetujui' ? 'selected' : '' }}>
            Disetujui
        </option>
        <option value="ditolak" {{ ($item->seragam->status_ukuran ?? '') === 'ditolak' ? 'selected' : '' }}>
            Ditolak
        </option>
        <option value="perbaiki" {{ ($item->seragam->status_ukuran ?? '') === 'perbaiki' ? 'selected' : '' }}>
            Perbaiki
        </option>
    </select>
@endif
```

### 2. Tambah Option "Menunggu"

Sebelumnya dropdown ukuran tidak memiliki option "menunggu", sehingga ketika status_ukuran = "menunggu", dropdown akan menampilkan "-- Pilih Status --" (tidak ter-select).

Sekarang option "menunggu" ditambahkan agar admin bisa melihat dan memilih status "menunggu" dengan jelas.

## Perbedaan Antar Tab

### Tab Madrasah:
- **Wire Method**: `changeStatusMadrasah`
- **Status Field**: `$item->daftarUlang->status`
- **Focus Ring**: Emerald (hijau)

### Tab Seragam:
- **Wire Method**: `changeStatusSeragam`
- **Status Field**: `$item->seragam->status_seragam`
- **Focus Ring**: Purple (ungu)

### Tab Ukuran:
- **Wire Method**: `changeStatusUkuran` ✅
- **Status Field**: `$item->seragam->status_ukuran` ✅
- **Focus Ring**: Indigo (biru tua)

## Status Options

Setiap dropdown memiliki 4 options:
1. **Menunggu** - Pendaftar sudah submit, menunggu verifikasi
2. **Disetujui** - Admin menyetujui
3. **Ditolak** - Admin menolak
4. **Perbaiki** - Admin meminta perbaikan

## Testing

### Test 1 - Visual Verification:

1. Login sebagai **admin**
2. Buka `/admin/daftar-ulang?activeTab=ukuran`
3. Lihat dropdown "Ubah Status"
4. ✅ Dropdown harus menampilkan selected value sesuai `status_ukuran`
5. ✅ Dropdown focus ring harus berwarna indigo (biru tua)
6. ✅ Ubah status dan verify dengan refresh

### Test 2 - Functional Verification:

```javascript
// Buka Browser Console di tab ukuran
// Cari dropdown element
const dropdown = document.querySelector('[wire\\:change^="changeStatusUkuran"]');
console.log('Method:', dropdown.getAttribute('wire:change'));
// Expected output: "changeStatusUkuran(27, $event.target.value)"

// Cek selected option
const selectedOption = dropdown.querySelector('option[selected]');
console.log('Selected Status:', selectedOption ? selectedOption.value : 'none');
// Should match status_ukuran value from database
```

### Test 3 - Database Verification:

```php
php artisan tinker

// Get seragam with different statuses
$seragam = \App\Models\Seragam::find(2);
echo "Status Seragam: " . $seragam->status_seragam . "\n";
echo "Status Ukuran: " . $seragam->status_ukuran . "\n";
// Should show different values

// Test changeStatusUkuran
$livewire = new \App\Livewire\Admin\DaftarUlang();
$livewire->changeStatusUkuran(27, 'disetujui');

// Verify
$seragam->refresh();
echo "New Status Ukuran: " . $seragam->status_ukuran . "\n";
// Should be 'disetujui'
```

## Cache Cleared ✅

Cache sudah dibersihkan:
```bash
php artisan view:clear       ✅
php artisan cache:clear      ✅
php artisan config:clear     ✅
```

## Kemungkinan Penyebab Masalah User

1. **Browser Cache** - User perlu hard refresh (Ctrl+Shift+R)
2. **Salah Tab** - User mungkin melihat tab seragam, bukan ukuran
3. **Old View Cache** - Sebelum cache dibersihkan
4. **Missing "Menunggu" Option** - Status menunggu tidak ter-select di dropdown

## Files Changed

1. **resources/views/livewire/admin/daftar-ulang.blade.php**
   - Line 1563: Tambah komentar "Tab Ukuran: Update status_ukuran"
   - Line 1569-1571: Tambah option "menunggu"

---

**Tanggal**: 30 Januari 2026
**Status**: ✅ Complete - Kode sudah benar, ditambahkan improvement
**Testing**: Silakan hard refresh (Ctrl+Shift+R) halaman `/admin/daftar-ulang?activeTab=ukuran`

## Instruksi untuk User

Jika masalah masih muncul:

1. **Hard Refresh Browser**: Tekan `Ctrl+Shift+R` (Windows) atau `Cmd+Shift+R` (Mac)
2. **Clear Browser Cache**: Settings → Privacy → Clear browsing data
3. **Verify Tab**: Pastikan berada di tab "Ukuran" bukan "Seragam"
4. **Check Focus Ring Color**:
   - Ukuran = Indigo (biru tua)
   - Seragam = Purple (ungu)
   - Madrasah = Emerald (hijau)

## Related Issues Fixed in This Session

1. ✅ Split Biaya & Pembayaran column (Madrasah & Seragam tabs)
2. ✅ Fix Bank/Metode column display
3. ✅ Fix Waktu Pembayaran timestamp
4. ✅ Fix Surat Pernyataan and Bukti Pembayaran status
5. ✅ Add download file route
6. ✅ Fix Baju Seragam Harian column (Ukuran tab)
7. ✅ Fix Status DU Madrasah (changed from 'proses' to 'menunggu')
8. ✅ Verify and improve Dropdown Status Ukuran
