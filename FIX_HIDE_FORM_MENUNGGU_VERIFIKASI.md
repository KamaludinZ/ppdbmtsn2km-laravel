# FIX: HIDE FORM FIELDS SAAT MENUNGGU VERIFIKASI
**Tanggal:** 5 Februari 2026
**Status:** ✅ COMPLETED

## 📋 RINGKASAN

Implementasi fitur untuk **menyembunyikan form input fields** dan **menampilkan hanya status** ketika data pendaftar sedang dalam proses verifikasi (status menunggu).

---

## 🎯 TUJUAN

Ketika pendaftar telah mengisi dan submit form daftar ulang (madrasah atau seragam):
- ✅ Form input fields akan **disembunyikan**
- ✅ Menampilkan **status verifikasi** yang jelas
- ✅ Menampilkan **data yang telah disubmit** dalam format read-only
- ✅ Menampilkan **link untuk melihat bukti pembayaran**
- ✅ Menampilkan **catatan dari panitia** (jika ada)

---

## 🔍 KONDISI TRIGGER

### Madrasah
Form akan disembunyikan jika:
```php
$isMadrasahWaiting = $daftarUlang &&
                     $daftarUlang->bukti_pembayaran_madrasah &&
                     in_array($daftarUlang->status, ['menunggu', 'proses']);
```

**Kondisi:**
1. Record daftar_ulang ada
2. Bukti pembayaran sudah diupload
3. Status = 'menunggu' ATAU 'proses'

### Seragam
Form akan disembunyikan jika:
```php
$isSeragamWaiting = $seragam &&
                    $seragam->bukti_pembayaran &&
                    in_array($seragam->status_seragam, ['menunggu', 'menunggu_verifikasi']);
```

**Kondisi:**
1. Record seragam ada
2. Bukti pembayaran seragam sudah diupload
3. Status seragam = 'menunggu' ATAU 'menunggu_verifikasi'

---

## 🎨 UI COMPONENTS

### 1. Status Badge (Yellow - Menunggu Verifikasi)

```blade
<div class="p-6 bg-yellow-50 border border-yellow-200 rounded-lg mb-6">
    <div class="flex items-start mb-4">
        <svg class="w-6 h-6 text-yellow-500 mr-3">...</svg>
        <div class="flex-1">
            <p class="text-base text-yellow-800 font-semibold">Data Sedang Diverifikasi</p>
            <p class="text-sm text-yellow-700">Data daftar ulang sedang dalam proses verifikasi...</p>
        </div>
    </div>
    ...
</div>
```

**Visual:**
- Background: Yellow (bg-yellow-50)
- Border: Yellow (border-yellow-200)
- Icon: Clock/time icon (yellow)
- Text: Bold title + description

### 2. Data Display Box (Read-Only)

```blade
<div class="mt-4 p-4 bg-white rounded-lg border border-yellow-200">
    <h5 class="font-semibold text-gray-900 mb-3 text-sm">Data yang Telah Disubmit:</h5>
    <dl class="space-y-2 text-sm">
        <!-- Data items -->
    </dl>
</div>
```

**Data yang ditampilkan (Madrasah):**
- Nominal Biaya
- Jumlah (Rp formatted)
- Opsi Pembayaran (jika ada)
- Bank (jika ada)
- Nama Pengirim (jika ada)
- Link lihat bukti pembayaran

**Data yang ditampilkan (Seragam):**
- Paket Seragam (jika ada)
- Nominal Biaya
- Jumlah (Rp formatted)
- Opsi Pembayaran (jika ada)
- Bank (jika ada)
- Nama Pengirim (jika ada)
- Link lihat bukti pembayaran

### 3. Catatan Panitia Box (Optional)

```blade
@if($daftarUlang->catatan)
<div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded">
    <p class="text-sm font-medium text-blue-900 mb-1">Catatan dari Panitia:</p>
    <p class="text-sm text-blue-700">{{ $daftarUlang->catatan }}</p>
</div>
@endif
```

**Hanya muncul jika:**
- Ada catatan dari panitia

---

## 💻 IMPLEMENTASI TEKNIS

### File Modified
`resources/views/livewire/pendaftar/daftar-ulang.blade.php`

### Madrasah Tab (Lines 270-447)

#### 1. Variable Definition (Line 274-276)
```php
@php
    // Check if madrasah is waiting for verification - hide form, show status only
    $isMadrasahWaiting = $daftarUlang && $daftarUlang->bukti_pembayaran_madrasah &&
                       in_array($daftarUlang->status, ['menunggu', 'proses']);
@endphp
```

#### 2. Conditional Display Logic
```blade
@if($isMadrasahApproved)
    <!-- Show approved message -->
@elseif($isMadrasahWaiting)
    <!-- Show waiting status + submitted data -->
@endif

@if(!$isMadrasahWaiting && !$isMadrasahApproved)
    <!-- Show form fields -->
@endif
```

### Seragam Tab (Lines 847-1029)

#### 1. Variable Definition (Line 851-853)
```php
@php
    // Check if seragam is waiting for verification - hide form, show status only
    $isSeragamWaiting = $seragam && $seragam->bukti_pembayaran &&
                      in_array($seragam->status_seragam, ['menunggu', 'menunggu_verifikasi']);
@endphp
```

#### 2. Conditional Display Logic
```blade
@if($isSeragamApproved)
    <!-- Show approved message -->
@elseif($isSeragamWaiting)
    <!-- Show waiting status + submitted data -->
@endif

@if(!$isSeragamWaiting && !$isSeragamApproved)
    <!-- Show form fields -->
@endif
```

---

## 🔄 FLOW DIAGRAM

```
┌─────────────────────────────────────┐
│ Pendaftar akses /pendaftar/daftarulang │
└─────────────┬───────────────────────┘
              │
              ▼
    ┌─────────────────────┐
    │ Check access control │
    │ - Status lulus?      │
    │ - Setuju daftar ulang? │
    │ - Tanggal valid?     │
    └─────────┬────────────┘
              │
              ▼
         ┌────┴────┐
         │ PASSED  │
         └────┬────┘
              │
              ▼
    ┌─────────────────────┐
    │ Check status data   │
    └─────────┬───────────┘
              │
    ┌─────────┴─────────┬─────────────┐
    │                   │             │
    ▼                   ▼             ▼
┌────────────┐   ┌──────────────┐  ┌────────────┐
│ DISETUJUI  │   │   MENUNGGU   │  │ BELUM ISI  │
└─────┬──────┘   └──────┬───────┘  └─────┬──────┘
      │                 │                 │
      ▼                 ▼                 ▼
┌──────────────┐  ┌──────────────┐  ┌──────────────┐
│ Show:        │  │ Show:        │  │ Show:        │
│ - Green box  │  │ - Yellow box │  │ - Form       │
│ - Status OK  │  │ - Status     │  │ - All fields │
│ - Form       │  │ - Data       │  │ - Can edit   │
│   disabled   │  │ - No form    │  │              │
└──────────────┘  └──────────────┘  └──────────────┘
```

---

## ✅ BEHAVIOR MATRIX

| Kondisi | Status | Bukti Upload | Form Visible | Status Display | Data Display |
|---------|--------|--------------|--------------|----------------|--------------|
| Belum isi | - | ❌ | ✅ Yes | - | - |
| Sudah isi, belum upload | - | ❌ | ✅ Yes | - | - |
| Sudah upload | menunggu | ✅ | ❌ No | ✅ Yellow | ✅ Read-only |
| Sudah upload | proses | ✅ | ❌ No | ✅ Yellow | ✅ Read-only |
| Disetujui | disetujui | ✅ | ⚠️ Disabled | ✅ Green | - |
| Ditolak | ditolak | ✅ | ✅ Yes | ⚠️ Red | - |
| Perlu perbaiki | perbaiki | ✅ | ✅ Yes | ⚠️ Orange | - |

---

## 🧪 TESTING CHECKLIST

### Test Scenario 1: Status Menunggu - Madrasah
- [ ] Login sebagai pendaftar yang sudah upload bukti madrasah
- [ ] Status di database = 'menunggu' atau 'proses'
- [ ] Akses halaman /pendaftar/daftarulang
- [ ] Tab Madrasah harus menampilkan:
  - [ ] Yellow box dengan teks "Data Sedang Diverifikasi"
  - [ ] White box dengan data yang sudah disubmit
  - [ ] Link "Lihat Bukti" berfungsi
  - [ ] Catatan panitia (jika ada)
- [ ] Form fields TIDAK muncul

### Test Scenario 2: Status Menunggu - Seragam
- [ ] Login sebagai pendaftar yang sudah upload bukti seragam
- [ ] Status seragam di database = 'menunggu' atau 'menunggu_verifikasi'
- [ ] Akses halaman /pendaftar/daftarulang
- [ ] Tab Seragam harus menampilkan:
  - [ ] Yellow box dengan teks "Data Sedang Diverifikasi"
  - [ ] White box dengan data yang sudah disubmit (termasuk paket seragam)
  - [ ] Link "Lihat Bukti" berfungsi
  - [ ] Catatan panitia (jika ada)
- [ ] Form fields TIDAK muncul

### Test Scenario 3: Status Belum Upload
- [ ] Login sebagai pendaftar yang belum upload bukti
- [ ] Tab Madrasah & Seragam harus menampilkan:
  - [ ] Form fields lengkap
  - [ ] Input enabled dan dapat diisi
  - [ ] Tombol simpan aktif

### Test Scenario 4: Status Disetujui
- [ ] Login sebagai pendaftar dengan status 'disetujui'
- [ ] Tab yang sudah disetujui harus menampilkan:
  - [ ] Green box "Data Sudah Disetujui"
  - [ ] Form fields muncul tapi disabled (opacity 60%)
  - [ ] Tombol simpan disabled

### Test Scenario 5: Status Ditolak/Perbaiki
- [ ] Login sebagai pendaftar dengan status 'ditolak' atau 'perbaiki'
- [ ] Tab terkait harus menampilkan:
  - [ ] Form fields aktif (dapat diubah)
  - [ ] Tombol simpan aktif
  - [ ] Alert/message mengenai penolakan/perbaikan

### Test Scenario 6: Data Display Accuracy
- [ ] Verify nominal biaya ditampilkan dengan benar (format Rupiah)
- [ ] Verify opsi pembayaran ditampilkan jika ada
- [ ] Verify bank ditampilkan jika ada
- [ ] Verify nama pengirim ditampilkan jika ada
- [ ] Verify catatan panitia muncul jika ada

---

## 📦 FILES CHANGED

**File Modified:** 1 file
- `resources/views/livewire/pendaftar/daftar-ulang.blade.php`

**Lines Added:** ~140 lines
**Lines Modified:** ~10 lines

**Sections Added:**
1. Variable definition untuk `$isMadrasahWaiting` (Madrasah tab)
2. Variable definition untuk `$isSeragamWaiting` (Seragam tab)
3. Status display section (Madrasah) - lines 375-447
4. Status display section (Seragam) - lines 951-1029
5. Conditional wrapper untuk form (both tabs)

---

## 🚀 DEPLOYMENT

### Upload to Production
File ini sudah termasuk dalam script upload sebelumnya:
```bash
upload_fix_nama_pengirim.bat
```

Atau upload manual:
```bash
scp resources/views/livewire/pendaftar/daftar-ulang.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
```

### Clear Cache After Upload
```bash
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear"
```

---

## 🎨 UI SCREENSHOTS (Description)

### Before (Form Visible - All Status)
```
┌────────────────────────────────────────┐
│ Daftar Ulang Madrasah                  │
├────────────────────────────────────────┤
│ [Form Field 1]                         │
│ [Form Field 2]                         │
│ [Form Field 3]                         │
│ [Upload Bukti]                         │
│ [Nama Pengirim]                        │
│ [ Simpan Data ]                        │
└────────────────────────────────────────┘
```

### After (Status Menunggu - Form Hidden)
```
┌────────────────────────────────────────┐
│ Daftar Ulang Madrasah                  │
├────────────────────────────────────────┤
│ ⏰ Data Sedang Diverifikasi            │
│    Data daftar ulang madrasah Anda     │
│    sedang dalam proses verifikasi...   │
│                                        │
│ ┌────────────────────────────────────┐ │
│ │ Data yang Telah Disubmit:          │ │
│ │ Nominal Biaya: Cicilan 3x          │ │
│ │ Jumlah: Rp 500.000                 │ │
│ │ Bank: BRI                          │ │
│ │ Nama Pengirim: Budi Santoso        │ │
│ │ Bukti: [Lihat Bukti]               │ │
│ └────────────────────────────────────┘ │
│                                        │
│ ℹ️ Catatan dari Panitia:               │
│    Data Anda sedang diverifikasi...    │
└────────────────────────────────────────┘
```

---

## 💡 ADDITIONAL NOTES

### Why This Feature is Important

1. **Better UX**: Pendaftar tidak bingung apakah data sudah tersubmit atau belum
2. **Prevent Double Submit**: Form hidden = tidak bisa submit ulang saat menunggu
3. **Clear Status**: Status verifikasi jelas dengan visual yang informatif
4. **Data Confirmation**: Pendaftar bisa review data yang sudah disubmit
5. **Panitia Notes**: Komunikasi panitia-pendaftar lebih jelas

### Status Values Reference

**Daftar Ulang (Madrasah):**
- `menunggu` - Menunggu verifikasi awal
- `proses` - Sedang diproses
- `disetujui` - Sudah disetujui
- `ditolak` - Ditolak
- `perbaiki` - Perlu perbaikan

**Seragam:**
- `menunggu` - Menunggu verifikasi
- `menunggu_verifikasi` - Alternate status menunggu
- `disetujui` - Sudah disetujui
- `ditolak` - Ditolak
- `perbaiki` - Perlu perbaikan

---

## ✨ SUMMARY

**Feature:** Hide form fields saat status menunggu verifikasi
**Status:** ✅ COMPLETED
**Files Modified:** 1 file (view only)
**Backend Changes:** None (pure frontend logic)
**Database Changes:** None
**Deployment:** Ready for production

---

**Dokumentasi dibuat:** 5 Februari 2026
**Developer:** AI Assistant
**Related Ticket:** FIX_NAMA_PENGIRIM_DAFTAR_ULANG_COMPLETE.md
