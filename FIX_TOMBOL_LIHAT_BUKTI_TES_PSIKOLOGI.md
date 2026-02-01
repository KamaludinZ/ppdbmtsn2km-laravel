# Fix: Tombol "Lihat Bukti" di Tes Psikologi

**Tanggal**: 30 Januari 2026
**Status**: ✅ SELESAI

## 📋 Problem

Di halaman `/pendaftar/tes-psikologi`, ketika status pembayaran sudah **disetujui**, terdapat tombol "Lihat Invoice" yang mengarah ke halaman preview invoice.

**User Request**: Tombol ini harus diubah menjadi "Lihat Bukti" dan ketika diklik harus menampilkan **bukti transfer yang telah diupload**, bukan invoice.

---

## 🔧 Solution

**File**: `resources/views/livewire/pendaftar/tes-psikologi.blade.php`

**Lines Modified**: 324-332

### Changes Made:

**SEBELUM**:
```html
<a href="{{ route('pendaftar.tes-psikologi.preview-invoice') }}" target="_blank" class="...">
    <svg>...</svg>
    Lihat Invoice
</a>
```

**SESUDAH**:
```html
@if($tesPsikologiData->bukti_transfer)
    <a href="{{ asset('storage/' . $tesPsikologiData->bukti_transfer) }}" target="_blank" class="...">
        <svg>...</svg>
        Lihat Bukti
    </a>
@endif
```

### Key Changes:

1. ✅ **Changed URL**: `route('pendaftar.tes-psikologi.preview-invoice')` → `asset('storage/' . $tesPsikologiData->bukti_transfer)`
2. ✅ **Changed Label**: "Lihat Invoice" → "Lihat Bukti"
3. ✅ **Added Conditional**: Tombol hanya muncul jika bukti transfer ada (`@if($tesPsikologiData->bukti_transfer)`)

---

## ✅ Benefits

### 1. User Experience
- ✅ User dapat langsung melihat bukti transfer yang telah diupload
- ✅ Lebih intuitif - "Lihat Bukti" lebih deskriptif daripada "Lihat Invoice"
- ✅ Bukti transfer dibuka di tab baru untuk kemudahan verifikasi

### 2. Consistency
- ✅ Tombol menampilkan data yang sudah diupload oleh user
- ✅ Konsisten dengan tombol "Download Invoice" yang tetap ada

### 3. Safety
- ✅ Conditional check memastikan tombol hanya muncul jika bukti ada
- ✅ Menggunakan `asset('storage/...')` yang sudah standar Laravel

---

## 🎯 Result

### Status: Bukti Transfer Disetujui

**Before Fix**:
```
┌─────────────────────────────────────────────┐
│ ✅ Bukti Transfer Disetujui                 │
│                                              │
│  [Lihat Invoice]  [Download Invoice]        │
│   (ke preview     (download invoice PDF)    │
│    invoice page)                             │
└─────────────────────────────────────────────┘
```

**After Fix**:
```
┌─────────────────────────────────────────────┐
│ ✅ Bukti Transfer Disetujui                 │
│                                              │
│  [Lihat Bukti]  [Download Invoice]          │
│   (buka bukti    (download invoice PDF)     │
│    transfer di                               │
│    tab baru)                                 │
└─────────────────────────────────────────────┘
```

---

## 📝 Testing Checklist

- [ ] Login sebagai pendaftar yang status tes psikologi = 'disetujui'
- [ ] Buka `/pendaftar/tes-psikologi`
- [ ] Verifikasi tombol berubah dari "Lihat Invoice" menjadi "Lihat Bukti"
- [ ] Klik tombol "Lihat Bukti"
- [ ] Verifikasi bukti transfer yang diupload muncul di tab baru
- [ ] Verifikasi tombol "Download Invoice" tetap berfungsi untuk download PDF invoice
- [ ] Test jika `bukti_transfer` null → tombol "Lihat Bukti" tidak muncul

---

## 🔍 Technical Details

### Bukti Transfer Storage Path

Bukti transfer disimpan di:
```
storage/app/public/{path_dari_database}
```

Dan diakses melalui:
```php
asset('storage/' . $tesPsikologiData->bukti_transfer)
```

### Why asset() instead of Storage::url()?

Karena di bagian lain file (line 378) sudah menggunakan pattern yang sama:
```php
<img src="{{ asset('storage/' . $tesPsikologiData->bukti_transfer) }}" alt="Bukti Transfer">
```

Jadi kita ikuti pattern yang sudah ada untuk consistency.

### Conditional Display

Tombol hanya ditampilkan jika bukti transfer ada:
```php
@if($tesPsikologiData->bukti_transfer)
    <a href="...">Lihat Bukti</a>
@endif
```

Ini mencegah error 404 jika bukti transfer belum diupload atau sudah dihapus.

---

## 📚 Related Files

1. `resources/views/livewire/pendaftar/tes-psikologi.blade.php` - Line 324-332 ✅ Modified

---

## 💡 Additional Notes

### Tombol "Download Invoice" Tetap Ada

Tombol "Download Invoice" (line 333-338) tetap ada dan berfungsi untuk mendownload invoice resmi dalam format PDF. Jadi sekarang user memiliki 2 tombol:

1. **Lihat Bukti** (biru) - Untuk melihat bukti transfer yang telah diupload
2. **Download Invoice** (hijau) - Untuk mendownload invoice resmi

Ini memberikan flexibilitas kepada user untuk:
- Verifikasi bukti transfer yang telah diupload
- Download invoice resmi untuk arsip

---

**Summary**: Successfully changed "Lihat Invoice" button to "Lihat Bukti" button that displays the uploaded payment proof when clicked, instead of showing the invoice page. The button now opens the actual uploaded proof image/document in a new tab.
