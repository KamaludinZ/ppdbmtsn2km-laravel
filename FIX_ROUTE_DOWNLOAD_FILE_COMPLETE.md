# Fix Route Download File - Complete

## Masalah

Error `RouteNotFoundException` ketika membuka halaman `/admin/daftar-ulang`:
```
Route [pendaftar.download-file] not defined.
```

Error terjadi di `resources/views/livewire/admin/daftar-ulang.blade.php:1230`

## Root Cause

Route `pendaftar.download-file` digunakan di admin view untuk link download file, tetapi route ini belum didefinisikan di `routes/web.php`.

## Solusi

### 1. Tambah Route Baru di routes/web.php

**File**: `routes/web.php` (Line 520-603)

**Route Baru:**
```php
Route::get('/download-file/{type}/{pendaftarId?}', function ($type, $pendaftarId = null) {
    $user = auth()->user();

    // Authorization check - Allow admin and panitia roles
    if (!$user || !in_array($user->role, ['admin', 'panitia', 'panitia_daftarulang', 'panitia_seragam', 'pendaftar'])) {
        abort(403, 'Anda tidak memiliki akses ke file ini.');
    }

    // If pendaftar, use their own ID
    if ($user->role === 'pendaftar') {
        $pendaftar = $user->pendaftar;
    } else {
        // For admin/panitia, get pendaftar from ID if provided
        if ($pendaftarId) {
            $pendaftar = \App\Models\Pendaftar::findOrFail($pendaftarId);
        } else {
            abort(404, 'Pendaftar ID tidak ditemukan.');
        }
    }

    // Determine file path based on type
    $filePath = null;
    $fileName = null;

    switch ($type) {
        case 'surat_pernyataan':
            $daftarUlang = $pendaftar->daftarUlang;
            if ($daftarUlang && $daftarUlang->surat_pernyataan_path) {
                $filePath = $daftarUlang->surat_pernyataan_path;
                $fileName = 'surat_pernyataan_' . $pendaftar->nomor_pendaftaran . '.pdf';
            }
            break;

        case 'bukti_pembayaran_madrasah':
            $daftarUlang = $pendaftar->daftarUlang;
            if ($daftarUlang && $daftarUlang->bukti_pembayaran_madrasah) {
                $filePath = $daftarUlang->bukti_pembayaran_madrasah;
                $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                $fileName = 'bukti_pembayaran_madrasah_' . $pendaftar->nomor_pendaftaran . '.' . $extension;
            }
            break;

        case 'bukti_pembayaran_seragam':
            $seragam = $pendaftar->seragam;
            if ($seragam && $seragam->bukti_pembayaran) {
                $filePath = $seragam->bukti_pembayaran;
                $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                $fileName = 'bukti_pembayaran_seragam_' . $pendaftar->nomor_pendaftaran . '.' . $extension;
            }
            break;

        default:
            abort(404, 'Tipe file tidak valid.');
    }

    if (!$filePath) {
        abort(404, 'File tidak ditemukan.');
    }

    // Check if file exists in private storage
    if (!Storage::disk('private')->exists($filePath)) {
        abort(404, 'File tidak ditemukan di storage: ' . $filePath);
    }

    // Get full path to file
    $fullPath = storage_path('app/private/' . $filePath);

    // Determine mime type based on extension
    $extension = pathinfo($filePath, PATHINFO_EXTENSION);
    $mimeType = match(strtolower($extension)) {
        'pdf' => 'application/pdf',
        'jpg', 'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        default => 'application/octet-stream',
    };

    // Return file for viewing in browser (inline)
    return response()->file($fullPath, [
        'Content-Type' => $mimeType,
        'Content-Disposition' => 'inline; filename="' . $fileName . '"'
    ]);
})->middleware('auth')->name('pendaftar.download-file');
```

### 2. Update Admin View untuk Pass Pendaftar ID

**File**: `resources/views/livewire/admin/daftar-ulang.blade.php`

**Sebelum:**
```blade
<a href="{{ route('pendaftar.download-file', ['type' => 'surat_pernyataan']) }}"
   target="_blank">Lihat File</a>
```

**Sesudah:**
```blade
<a href="{{ route('pendaftar.download-file', ['type' => 'surat_pernyataan', 'pendaftarId' => $item->id]) }}"
   target="_blank">Lihat File</a>
```

**Lokasi Update:**
1. **Line 1230** - Link Surat Pernyataan (Tab Madrasah)
2. **Line 1296** - Link Bukti Pembayaran Madrasah (Tab Madrasah)
3. **Line 1409** - Link Bukti Pembayaran Seragam (Tab Seragam)

## Fitur Route

### Authorization
Route ini dapat diakses oleh:
- ✅ `admin`
- ✅ `panitia`
- ✅ `panitia_daftarulang`
- ✅ `panitia_seragam`
- ✅ `pendaftar` (hanya file milik sendiri)

### Supported File Types

1. **surat_pernyataan**
   - File: `daftar_ulang.surat_pernyataan_path`
   - Nama file: `surat_pernyataan_{nomor_pendaftaran}.pdf`

2. **bukti_pembayaran_madrasah**
   - File: `daftar_ulang.bukti_pembayaran_madrasah`
   - Nama file: `bukti_pembayaran_madrasah_{nomor_pendaftaran}.{ext}`

3. **bukti_pembayaran_seragam**
   - File: `seragam.bukti_pembayaran`
   - Nama file: `bukti_pembayaran_seragam_{nomor_pendaftaran}.{ext}`

### Supported File Extensions
- PDF (`application/pdf`)
- JPG/JPEG (`image/jpeg`)
- PNG (`image/png`)
- Others (`application/octet-stream`)

## URL Examples

### Untuk Admin/Panitia:
```
/download-file/surat_pernyataan/27
/download-file/bukti_pembayaran_madrasah/27
/download-file/bukti_pembayaran_seragam/27
```

### Untuk Pendaftar:
```
/download-file/surat_pernyataan
/download-file/bukti_pembayaran_madrasah
/download-file/bukti_pembayaran_seragam
```
(Pendaftar ID diambil dari user yang login)

## Security Features

1. **Authentication Required** - Route menggunakan middleware `auth`
2. **Role-Based Access** - Cek role user sebelum akses file
3. **File Ownership Check** - Pendaftar hanya bisa download file milik sendiri
4. **File Existence Check** - Cek file ada di storage sebelum download
5. **Private Storage** - File disimpan di `storage/app/private` (tidak bisa diakses langsung)

## Testing

### Test untuk Admin:

1. Login sebagai **admin**
2. Buka `/admin/daftar-ulang?activeTab=madrasah`
3. Cari pendaftar yang sudah upload file
4. Klik link **"Lihat File"** di kolom:
   - Surat Pernyataan
   - Bukti Pembayaran
5. ✅ File harus terbuka di tab baru

### Test untuk Seragam:

1. Login sebagai **admin**
2. Buka `/admin/daftar-ulang?activeTab=seragam`
3. Cari pendaftar yang sudah upload bukti pembayaran seragam
4. Klik link **"Lihat File"** di kolom Bukti Pembayaran
5. ✅ File harus terbuka di tab baru

### Test Error Handling:

1. **File Tidak Ada:**
   - ✅ Error 404: "File tidak ditemukan."

2. **File Dihapus dari Storage:**
   - ✅ Error 404: "File tidak ditemukan di storage: {path}"

3. **Akses Tanpa Login:**
   - ✅ Redirect ke login page

4. **Role Tidak Diizinkan:**
   - ✅ Error 403: "Anda tidak memiliki akses ke file ini."

## Files Changed

1. **routes/web.php** - Tambah route baru
2. **resources/views/livewire/admin/daftar-ulang.blade.php** - Update 3 link dengan pendaftarId

---

**Tanggal**: 30 Januari 2026
**Status**: ✅ Complete
**Testing**: Silakan refresh halaman `/admin/daftar-ulang` dan test link download
