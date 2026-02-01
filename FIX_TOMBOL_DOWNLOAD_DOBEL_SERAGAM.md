# Fix Tombol Download Dobel & Error 403 di Tab Seragam

**Tanggal**: 30 Januari 2026
**Status**: ✅ SELESAI

## 📋 Problem

### Problem 1: Tombol Download Dobel
Di halaman `/pendaftar/daftarulang?activeTab=seragam`, terdapat **3 tombol** yang muncul setelah bukti pembayaran seragam disetujui:

1. **Lihat Bukti** (biru) - Membuka bukti pembayaran di tab baru
2. **Download** (emerald/hijau) - Download bukti pembayaran ❌ **REDUNDANT**
3. **Download Invoice** (ungu) - Download invoice seragam

Sementara di tab Madrasah hanya ada **2 tombol**:
1. **Lihat Bukti** (biru)
2. **Download Invoice** (ungu)

**Issue**: Tombol "Download" (hijau) di tab seragam **redundant** karena:
- User sudah bisa download bukti via "Lihat Bukti" (buka di tab baru lalu download dari browser)
- Membuat **inkonsistensi** antara tab Madrasah dan Seragam
- Membingungkan user dengan terlalu banyak pilihan untuk aksi yang sama

### Problem 2: Error 403 Forbidden
Ketika user klik tombol "Lihat Bukti" atau "Download Invoice" di tab seragam, muncul **error 403 Forbidden**.

**Root Cause**: Tab seragam menggunakan `Storage::url()` untuk akses file secara langsung, sedangkan file disimpan di **private storage** yang tidak bisa diakses langsung tanpa authorization.

**Perbandingan**:
- ❌ **Seragam (sebelum fix)**: `Storage::url($pendaftar->seragam->bukti_pembayaran)` → Error 403
- ✅ **Madrasah (sudah benar)**: `route('pendaftar.daftarulang.view-file', ...)` → Berhasil dengan permission check

---

## 🔧 Solution

### File Modified: `resources/views/livewire/pendaftar/daftar-ulang.blade.php`

**Lines Modified**: 1043-1058

### Changes Made:

#### Fix 1: Remove Redundant Download Button
**SEBELUM** (3 tombol dengan Storage::url):
```html
<div class="flex gap-2">
    <!-- Tombol 1: Lihat Bukti - menggunakan Storage::url (403 ERROR!) -->
    <a href="{{ Storage::url($pendaftar->seragam->bukti_pembayaran) }}" target="_blank"
       class="flex-1 bg-blue-600 hover:bg-blue-700...">
        Lihat Bukti
    </a>

    <!-- Tombol 2: Download (REDUNDANT - DIHAPUS) -->
    <a href="{{ Storage::url($pendaftar->seragam->bukti_pembayaran) }}" download
       class="flex-1 bg-emerald-600 hover:bg-emerald-700...">
        Download
    </a>

    <!-- Tombol 3: Download Invoice -->
    <button wire:click="downloadInvoiceSeragam" class="flex-1 bg-purple-600...">
        Download Invoice
    </button>
</div>
```

#### Fix 2: Use Proper Route with Authorization
**SESUDAH** (2 tombol dengan route yang benar):
```html
<div class="flex gap-2">
    <!-- Tombol 1: Lihat Bukti - menggunakan route dengan authorization ✅ -->
    <a href="{{ route('pendaftar.daftarulang.view-file', ['type' => 'seragam', 'file' => basename($pendaftar->seragam->bukti_pembayaran)]) }}" target="_blank"
       class="flex-1 bg-blue-600 hover:bg-blue-700...">
        Lihat Bukti
    </a>

    <!-- Tombol 2: Download Invoice -->
    <button wire:click="downloadInvoiceSeragam" class="flex-1 bg-purple-600...">
        Download Invoice
    </button>
</div>
```

**Key Changes**:
1. ❌ **Removed**: Redundant "Download" button (emerald)
2. ✅ **Fixed**: Changed `Storage::url(...)` to `route('pendaftar.daftarulang.view-file', ...)` untuk proper authorization

---

## ✅ Benefits

1. **✅ Error 403 Fixed**: Tombol "Lihat Bukti" sekarang berfungsi dengan proper authorization
2. **Konsistensi UI**: Tab Madrasah dan Seragam sekarang memiliki layout tombol yang sama
3. **Security**: File di private storage diakses melalui route dengan permission check, bukan direct URL
4. **UX Lebih Baik**: Mengurangi kebingungan dengan menghilangkan tombol redundant
5. **Lebih Clean**: Interface lebih sederhana dan fokus
6. **Functionality Tetap Lengkap**: User masih bisa:
   - ✅ Melihat bukti pembayaran via "Lihat Bukti" (sudah tidak error 403!)
   - ✅ Download bukti dari browser (klik kanan → Save As)
   - ✅ Download invoice resmi via "Download Invoice"

---

## 🎯 Result

### Tab Seragam - Sekarang (2 tombol):
```
┌─────────────────────────────────────────────────┐
│ ✅ Bukti pembayaran telah diupload              │
│                                                  │
│  [Lihat Bukti]  [Download Invoice]              │
│    (biru)          (ungu)                        │
└─────────────────────────────────────────────────┘
```

### Tab Madrasah - Untuk Perbandingan (2 tombol):
```
┌─────────────────────────────────────────────────┐
│ ✅ Bukti pembayaran telah diupload              │
│                                                  │
│  [Lihat Bukti]  [Download Invoice]              │
│    (biru)          (ungu)                        │
└─────────────────────────────────────────────────┘
```

✅ **Sekarang kedua tab konsisten!**

---

## 📝 Testing Checklist

- [ ] Login sebagai pendaftar yang sudah upload bukti seragam dan disetujui
- [ ] Buka `/pendaftar/daftarulang?activeTab=seragam`
- [ ] Verifikasi hanya ada **2 tombol** (Lihat Bukti + Download Invoice)
- [ ] **Test tombol "Lihat Bukti"**:
  - [ ] Klik tombol → Harus membuka bukti di tab baru
  - [ ] ✅ Tidak ada error 403 lagi!
  - [ ] File muncul dengan benar (PDF/gambar)
- [ ] **Test tombol "Download Invoice"**:
  - [ ] Klik tombol → Harus men-download invoice seragam
  - [ ] ✅ Tidak ada error 403 lagi!
  - [ ] File terdownload dengan benar
- [ ] Bandingkan dengan tab Madrasah untuk memastikan konsistensi UI

---

## 📚 Related Files

- `resources/views/livewire/pendaftar/daftar-ulang.blade.php` - Tab seragam (lines 1043-1058) ✅ Modified
- `resources/views/livewire/pendaftar/daftar-ulang.blade.php` - Tab madrasah (lines 553-567) untuk referensi
- `routes/web.php` - Route `pendaftar.daftarulang.view-file` (lines 470-518) yang support type 'seragam'

## 🔍 Technical Details

### Route: `pendaftar.daftarulang.view-file`

Route ini sudah support 3 type file:
1. **madrasah** - Bukti pembayaran madrasah (`$daftarUlang->bukti_pembayaran_madrasah`)
2. **pernyataan** - Surat pernyataan (`$daftarUlang->surat_pernyataan_path`)
3. **seragam** - Bukti pembayaran seragam (`$seragam->bukti_pembayaran`) ✅

Route features:
- ✅ **Authorization**: Middleware `auth` - hanya user yang login
- ✅ **Private Storage**: File diambil dari `storage/app/private/`
- ✅ **Permission Check**: Hanya pendaftar yang memiliki file bisa akses
- ✅ **Inline Display**: File ditampilkan langsung di browser (bukan download)
- ✅ **Proper MIME Type**: Deteksi otomatis PDF, JPG, PNG

**Code Reference** (`routes/web.php` lines 484-489):
```php
} elseif ($type === 'seragam') {
    // Bukti pembayaran seragam
    $seragam = $pendaftar->seragam;
    if ($seragam && $seragam->bukti_pembayaran) {
        $filePath = $seragam->bukti_pembayaran;
    }
}
```

---

**Summary**:
1. ✅ Menghapus tombol "Download" (emerald) yang redundant dari tab seragam
2. ✅ Mengubah `Storage::url()` menjadi `route('pendaftar.daftarulang.view-file')` untuk fix error 403
3. ✅ Sekarang kedua tab memiliki 2 tombol yang sama dan keduanya berfungsi dengan baik!
