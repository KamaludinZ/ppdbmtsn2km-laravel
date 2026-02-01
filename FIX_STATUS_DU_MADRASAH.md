# Fix Status DU Madrasah - Complete

## Masalah

Kolom "Status DU Madrasah" di `/admin/daftar-ulang?activeTab=madrasah` menampilkan status "Proses" padahal pendaftar sudah upload file lengkap. Seharusnya status berubah menjadi "Menunggu" ketika sudah upload.

## Root Cause

Backend menggunakan status `'proses'` sebagai default untuk daftar ulang madrasah, berbeda dengan seragam dan ukuran yang menggunakan `'menunggu'`.

### Status Default Sebelum Fix:

**Madrasah** (`app/Livewire/Pendaftar/DaftarUlang.php`):
- Line 273: Status awal = `'proses'` ❌
- Line 330: Status setelah upload = `'proses'` ❌

**Seragam & Ukuran**:
- Status awal = `'menunggu'` ✅
- Status setelah upload = `'menunggu'` ✅

**Inkonsistensi**:
- Madrasah: `proses` → tidak ada di admin view statusColors/statusLabels
- Seragam: `menunggu` → ada di statusColors/statusLabels

## Solusi

### 1. Update Backend - Status Default Madrasah

**File**: `app/Livewire/Pendaftar/DaftarUlang.php`

**Perubahan 1 - Status Awal (Line 270-275)**:
```php
// SEBELUM
if (!$this->daftarUlang) {
    $this->daftarUlang = DaftarUlangModel::create([
        'pendaftar_id' => $this->pendaftar->id,
        'status' => 'proses',  ❌
    ]);
}

// SESUDAH
if (!$this->daftarUlang) {
    $this->daftarUlang = DaftarUlangModel::create([
        'pendaftar_id' => $this->pendaftar->id,
        'status' => 'menunggu',  ✅
    ]);
}
```

**Perubahan 2 - Status Setelah Upload (Line 329-331)**:
```php
// SEBELUM
$dataToUpdate['bukti_pembayaran_madrasah'] = $path;
$dataToUpdate['status'] = 'proses';  ❌

// SESUDAH
$dataToUpdate['bukti_pembayaran_madrasah'] = $path;
$dataToUpdate['status'] = 'menunggu';  ✅
```

### 2. Update Admin View - Tambah Status 'Proses'

**File**: `resources/views/livewire/admin/daftar-ulang.blade.php`

Untuk backward compatibility dengan data lama yang masih berstatus 'proses', tambahkan ke statusColors dan statusLabels:

**Perubahan (Line 1193-1207)**:
```php
// SEBELUM
@php
    $statusColors = [
        'menunggu' => 'bg-yellow-100 text-yellow-800',
        'disetujui' => 'bg-green-100 text-green-800',
        'ditolak' => 'bg-red-100 text-red-800',
        'perbaiki' => 'bg-orange-100 text-orange-800',
    ];
    $statusLabels = [
        'menunggu' => 'Menunggu',
        'disetujui' => 'Disetujui',
        'ditolak' => 'Ditolak',
        'perbaiki' => 'Perbaiki',
    ];
@endphp

// SESUDAH
@php
    $statusColors = [
        'proses' => 'bg-blue-100 text-blue-800',        // ✅ ADDED
        'menunggu' => 'bg-yellow-100 text-yellow-800',
        'disetujui' => 'bg-green-100 text-green-800',
        'ditolak' => 'bg-red-100 text-red-800',
        'perbaiki' => 'bg-orange-100 text-orange-800',
    ];
    $statusLabels = [
        'proses' => 'Proses',                           // ✅ ADDED
        'menunggu' => 'Menunggu',
        'disetujui' => 'Disetujui',
        'ditolak' => 'Ditolak',
        'perbaiki' => 'Perbaiki',
    ];
@endphp
```

### 3. Migration - Update Data Lama

**File**: `database/migrations/2026_01_30_123759_update_status_proses_to_menunggu_in_daftar_ulang.php`

Migration untuk mengupdate semua data lama yang masih berstatus 'proses' menjadi 'menunggu':

```php
public function up(): void
{
    // Update all records with status 'proses' to 'menunggu'
    DB::table('daftar_ulang')
        ->where('status', 'proses')
        ->update(['status' => 'menunggu']);
}

public function down(): void
{
    // Rollback: change 'menunggu' back to 'proses' if needed
    DB::table('daftar_ulang')
        ->where('status', 'menunggu')
        ->update(['status' => 'proses']);
}
```

**Migration Executed**: ✅ `php artisan migrate` (55.39ms)

## Status System

### Status Flow untuk Daftar Ulang Madrasah:

1. **Menunggu** (bg-yellow) - Pendaftar sudah upload file, menunggu verifikasi admin
2. **Disetujui** (bg-green) - Admin menyetujui dokumen
3. **Ditolak** (bg-red) - Admin menolak dokumen
4. **Perbaiki** (bg-orange) - Admin meminta perbaikan dokumen
5. **Proses** (bg-blue) - Legacy status untuk backward compatibility

### Konsistensi Antar Tab:

| Tab | Status Awal | Status Setelah Upload | Warna Badge |
|-----|-------------|----------------------|-------------|
| Madrasah | `menunggu` ✅ | `menunggu` ✅ | Yellow (warning) |
| Seragam | `menunggu` ✅ | `menunggu` ✅ | Yellow (warning) |
| Ukuran | `menunggu` ✅ | `menunggu` ✅ | Yellow (warning) |

## Testing

### Test 1 - Data Baru:

1. Login sebagai **pendaftar** (buat data baru)
2. Buka halaman daftar ulang
3. Upload surat pernyataan dan bukti pembayaran
4. Simpan

5. Login sebagai **admin**
6. Buka `/admin/daftar-ulang?activeTab=madrasah`
7. ✅ Status DU Madrasah harus menampilkan: **"Menunggu"** dengan badge kuning

### Test 2 - Data Lama (Sudah Diupdate via Migration):

1. Login sebagai **admin**
2. Buka `/admin/daftar-ulang?activeTab=madrasah`
3. ✅ Semua data yang tadinya "Proses" sekarang berubah menjadi **"Menunggu"**

### Test 3 - Backend Code:

Jalankan query untuk memverifikasi:
```sql
SELECT id, pendaftar_id, status, created_at, updated_at
FROM daftar_ulang
WHERE status = 'proses';
```

Expected result: **0 rows** (semua sudah berubah ke 'menunggu')

```sql
SELECT id, pendaftar_id, status, created_at, updated_at
FROM daftar_ulang
WHERE status = 'menunggu';
```

Expected result: **All uploaded records** dengan status 'menunggu'

## Files Changed

1. **app/Livewire/Pendaftar/DaftarUlang.php**
   - Line 273: Status awal dari 'proses' → 'menunggu'
   - Line 330: Status upload dari 'proses' → 'menunggu'

2. **resources/views/livewire/admin/daftar-ulang.blade.php**
   - Line 1195: Added 'proses' to statusColors
   - Line 1202: Added 'proses' to statusLabels

3. **database/migrations/2026_01_30_123759_update_status_proses_to_menunggu_in_daftar_ulang.php**
   - New migration to update existing data

## Impact Analysis

### ✅ Benefits:

1. **Konsistensi**: Semua tab (madrasah, seragam, ukuran) menggunakan status yang sama
2. **Clarity**: "Menunggu" lebih jelas menunjukkan bahwa dokumen sudah diupload dan menunggu verifikasi
3. **User Experience**: Pendaftar tahu dokumen mereka sudah berhasil diupload
4. **Admin Workflow**: Admin tahu mana yang perlu diverifikasi (status "Menunggu")

### ⚠️ Backward Compatibility:

- Status 'proses' tetap didukung di admin view untuk data lama
- Migration otomatis mengupdate semua data lama
- Tidak ada breaking changes untuk data yang sudah ada

---

**Tanggal**: 30 Januari 2026
**Status**: ✅ Complete
**Testing**: Silakan refresh halaman `/admin/daftar-ulang?activeTab=madrasah` dan test dengan upload file baru

## Related Issues Fixed in This Session

1. ✅ Split Biaya & Pembayaran column (Madrasah & Seragam tabs)
2. ✅ Fix Bank/Metode column display
3. ✅ Fix Waktu Pembayaran timestamp
4. ✅ Fix Surat Pernyataan and Bukti Pembayaran status
5. ✅ Add download file route
6. ✅ Fix Baju Seragam Harian column (Ukuran tab)
7. ✅ Fix Status DU Madrasah (changed from 'proses' to 'menunggu')
