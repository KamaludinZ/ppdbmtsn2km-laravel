# Fix 403 Error - Surat Pernyataan di Panitia Daftar Ulang

**Tanggal:** 30 Januari 2026
**Status:** ✅ SELESAI

## Problem

Ketika Panitia Daftar Ulang mengklik "Lihat File" untuk Surat Pernyataan di halaman `/panitia-daftarulang/daftar-ulang`, muncul error **403 Forbidden**.

---

## Root Cause Analysis

### 1. View Panitia Menggunakan Direct Asset Link

**File:** `resources/views/livewire/panitia/daftar-ulang.blade.php` (Line 272)

**Kode Lama (Salah):**
```blade
<a href="{{ asset('storage/' . $item->daftarUlang->surat_pernyataan_path) }}"
   target="_blank"
   class="inline-flex items-center text-blue-600 hover:text-blue-900 text-xs transition-colors">
    Lihat File
</a>
```

**Masalah:**
- Menggunakan `asset('storage/...')` yang mencoba akses file dari **public/storage**
- File disimpan di **storage/app/private/** (bukan public)
- Direct access ke private storage akan ditolak oleh server (403 Forbidden)

---

### 2. Admin Menggunakan Secure Route

**File:** `resources/views/livewire/admin/daftar-ulang.blade.php` (Line 1232)

**Kode Admin (Benar):**
```blade
<a href="{{ route('pendaftar.download-file', ['type' => 'surat_pernyataan', 'pendaftarId' => $item->id]) }}"
   target="_blank"
   class="block text-blue-600 hover:text-blue-900 text-xs mt-1">
    Lihat File
</a>
```

**Mengapa Benar:**
- Menggunakan route `pendaftar.download-file` yang memiliki authorization logic
- Route mengakses file dari private storage dengan aman
- Role `panitia_daftarulang` sudah diizinkan di route authorization

---

## Solution

### File Modified: `resources/views/livewire/panitia/daftar-ulang.blade.php`

**Line 262-276 - Updated Surat Pernyataan Column:**

```blade
<!-- Surat Pernyataan -->
<td class="px-6 py-4">
    @if($item->daftarUlang && $item->daftarUlang->surat_pernyataan_path)
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
            ✓ Sudah Upload
        </span>
        <a href="{{ route('pendaftar.download-file', ['type' => 'surat_pernyataan', 'pendaftarId' => $item->id]) }}"
           target="_blank"
           class="block text-blue-600 hover:text-blue-900 text-xs mt-1">Lihat File</a>
    @else
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
            ✗ Belum Upload
        </span>
    @endif
</td>
```

**Changes Made:**
1. ✅ Changed from `asset('storage/...')` to `route('pendaftar.download-file', ...)`
2. ✅ Added `type` parameter: `'surat_pernyataan'`
3. ✅ Added `pendaftarId` parameter: `$item->id`
4. ✅ Matched Admin view styling exactly

---

## Route Authorization (Already Correct)

**File:** `routes/web.php` (Line 521-603)

**Authorization Check (Line 525):**
```php
if (!$user || !in_array($user->role, ['admin', 'panitia', 'panitia_daftarulang', 'panitia_seragam', 'pendaftar'])) {
    abort(403, 'Anda tidak memiliki akses ke file ini.');
}
```

✅ Role `panitia_daftarulang` **sudah diizinkan**

---

## How the Route Works

### Flow Diagram

```
User clicks "Lihat File"
    ↓
Route: /download-file/surat_pernyataan/{pendaftarId}
    ↓
Authorization Check (Line 525)
    ↓ (If panitia_daftarulang → PASS)
Get Pendaftar from ID (Line 534-535)
    ↓
Get surat_pernyataan_path from DaftarUlang (Line 548-552)
    ↓
Check if file exists in private storage (Line 582)
    ↓
Return file with proper headers (Line 599-602)
    ↓
File displayed in browser
```

### Key Points

1. **Private Storage (Line 582-587):**
   ```php
   // Check if file exists in private storage
   if (!Storage::disk('private')->exists($filePath)) {
       abort(404, 'File tidak ditemukan di storage: ' . $filePath);
   }

   // Get full path to file
   $fullPath = storage_path('app/private/' . $filePath);
   ```

2. **Inline Display (Line 599-602):**
   ```php
   return response()->file($fullPath, [
       'Content-Type' => $mimeType,
       'Content-Disposition' => 'inline; filename="' . $fileName . '"'
   ]);
   ```
   - Files are displayed **inline** in browser (not downloaded)
   - Supports PDF, JPG, PNG formats

---

## Why This Fix Works

| Aspect | Before (Wrong) | After (Correct) |
|--------|---------------|-----------------|
| **Access Method** | Direct public asset | Secure route with auth |
| **Storage Location** | Assumes public/storage | Accesses storage/app/private |
| **Authorization** | None (403 error) | Role-based (panitia_daftarulang allowed) |
| **Security** | Insecure | Secure with authorization check |
| **Consistency** | Different from Admin | Matches Admin exactly |

---

## Testing Checklist

- [x] View updated to use route instead of asset
- [ ] Click "Lihat File" on Surat Pernyataan column
- [ ] File opens in new tab without 403 error
- [ ] PDF displays correctly in browser
- [ ] Other roles (admin, panitia) can still access files
- [ ] Pendaftar can still access their own files

---

## Additional Notes

### Other Files Using Same Route (Already Correct)

1. **Bukti Pembayaran Madrasah** (Line 344):
   ```blade
   <a href="{{ route('pendaftar.download-file', ['type' => 'bukti_pembayaran_madrasah', 'pendaftarId' => $item->id]) }}"
   ```
   ✅ Already using correct route

2. **Bukti Pembayaran Seragam** (Admin only):
   ```blade
   <a href="{{ route('pendaftar.download-file', ['type' => 'bukti_pembayaran_seragam', 'pendaftarId' => $item->id]) }}"
   ```
   ✅ Already using correct route

---

## Security Benefits

1. **Authorization Control** - Only authorized roles can access files
2. **Private Storage** - Files not accessible directly from web
3. **Audit Trail** - All file access goes through authenticated route
4. **Consistent Security** - Same security model for all file types

---

## Files Modified

1. **resources/views/livewire/panitia/daftar-ulang.blade.php** (Line 262-276)
   - Changed from `asset()` to `route()`
   - Added proper parameters
   - Matched Admin view styling

---

**Status:** ✅ FIX COMPLETE - Ready for testing

**Expected Result:**
- Clicking "Lihat File" for Surat Pernyataan should now open the PDF file in a new browser tab without 403 error
- File displays correctly for role `panitia_daftarulang`
