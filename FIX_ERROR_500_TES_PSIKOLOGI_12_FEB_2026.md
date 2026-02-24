# FIX ERROR 500 - TES PSIKOLOGI - 12 Februari 2026

## Problem

Error 500 saat akses `/admin/tes-psikologi` dan `/panitia/tes-psikologi`

## Root Cause (2 Issues)

**Lokasi:** `resources/views/livewire/admin/partials/tes-psikologi-data.blade.php`

### Issue 1: Kolom Tes Psikologi Data di Luar Conditional

**Masalah:** Kode untuk kolom Opsi/Biaya, Bank, Waktu Transfer, Nama Pengirim, Catatan, Bukti Transfer, dan Status Psikologi berada **DI LUAR** blok conditional `@endif`.

### Issue 2: Extended Columns Tampil untuk Filter "Belum Upload"

**Masalah:** Kolom "Ortu WhatsApp", "Ubah Status", "Kirim Pesan", "Riwayat Pesan" mencoba akses property yang tidak ada di Pendaftar model:
- `$data->id` (untuk changeStatus, openCatatanModal)
- `$data->status_verifikasi` (untuk selected option)
- `$data->pendaftar_id` (untuk link detail)

**BEFORE (SALAH):**
```blade
@if($filterStatus === 'belum_upload')
    {{-- Kolom untuk Pendaftar model --}}
    <td>{{ $data->nomor_pendaftaran }}</td>
    ...
@else
    {{-- Kolom untuk TesPsikologiPendaftar model --}}
    <td>{{ $data->pendaftar->nomor_pendaftaran }}</td>
    ...
@endif
{{-- MASALAH: Kode di bawah ini TIDAK di dalam conditional! --}}
<td>{{ $data->opsi->nama }}</td>        {{-- Error jika filter = belum_upload --}}
<td>{{ $data->bank->nama_bank }}</td>   {{-- Error jika filter = belum_upload --}}
<td>{{ $data->waktu_transfer }}</td>    {{-- Error jika filter = belum_upload --}}
...
```

**Kenapa Error?**
- Ketika `$filterStatus = 'belum_upload'`, `$data` adalah **Pendaftar model**
- Model Pendaftar **TIDAK PUNYA** relasi `opsi`, `bank`, `waktu_transfer`, `nama_pengirim`, dll
- Sistem mencoba akses property yang tidak ada → **Error 500**

## Solution

### Fix 1: Pindahkan Kolom Tes Psikologi ke Dalam Conditional

Pindahkan kode untuk kolom opsi/bank/waktu/dll **KE DALAM** blok `@else`:

### Fix 2: Sembunyikan Extended Columns untuk Filter "Belum Upload"

Tambahkan conditional `&& $filterStatus !== 'belum_upload'` pada extended columns:

**BEFORE:**
```blade
@if($showExtendedColumns)
    <th>Ortu WhatsApp</th>
    <th>Ubah Status</th>
    <th>Kirim Pesan</th>
```

**AFTER:**
```blade
@if($showExtendedColumns && $filterStatus !== 'belum_upload')
    <th>Ortu WhatsApp</th>
    <th>Ubah Status</th>
    <th>Kirim Pesan</th>
```

**Penjelasan:**
- Filter "Belum Upload" menampilkan pendaftar yang **belum punya record** di `tes_psikologi_pendaftar`
- Kolom "Ubah Status", "Kirim Pesan", dll hanya relevan untuk data yang **sudah ada** di tes psikologi
- Jadi masuk akal untuk menyembunyikan kolom extended saat filter = "belum_upload"

**Struktur Final:**
```blade
@if($filterStatus === 'belum_upload')
    {{-- Kolom untuk Pendaftar model --}}
    <td>{{ $data->nomor_pendaftaran }}</td>
    <td>{{ $data->nisn }}</td>
    <td>{{ $data->nama_lengkap }}</td>
    <td>{{ $data->jalurPendaftaran->nama }}</td>
    <td>{{ $data->jenis_kelamin }}</td>
    <td>{{ $data->email }}</td>
    <td>{{ $data->no_hp }}</td>
    <td>{{ $data->status_pendaftaran }}</td>
@else
    {{-- Kolom untuk TesPsikologiPendaftar model --}}
    <td>{{ $data->pendaftar->nomor_pendaftaran }}</td>
    <td>{{ $data->pendaftar->nisn }}</td>
    <td>{{ $data->pendaftar->nama_lengkap }}</td>
    <td>{{ $data->pendaftar->jalurPendaftaran->nama }}</td>
    <td>{{ $data->pendaftar->jenis_kelamin }}</td>
    {{-- Kolom yang hanya ada di TesPsikologiPendaftar --}}
    <td>{{ $data->opsi->nama }}</td>
    <td>{{ $data->bank->nama_bank }}</td>
    <td>{{ $data->waktu_transfer }}</td>
    <td>{{ $data->nama_pengirim }}</td>
    <td>{{ $data->catatan_psikologi }}</td>
    <td>{{ $data->bukti_transfer }}</td>
    <td>{{ $data->status_verifikasi }}</td>
@endif

{{-- Kolom yang SELALU tampil terlepas filter --}}
@if($setting && $setting->survey_aktif)
    <td>Survey</td>
@endif

@if($showExtendedColumns)
    <td>Ortu WhatsApp</td>
    <td>Ubah Status</td>
    <td>Kirim Pesan</td>
    <td>Riwayat Pesan</td>
@endif
```

## Changes Made

**File:** `resources/views/livewire/admin/partials/tes-psikologi-data.blade.php`

**Line 176-256:** Pindahkan kode kolom opsi/bank/waktu/dll ke dalam blok `@else` sebelum `@endif`

**Struktur Baru:**
- Line 132-161: Kolom untuk filter "belum_upload" (8 kolom)
- Line 162-256: Kolom untuk filter lainnya (12 kolom) + `@endif`
- Line 257+: Kolom yang selalu tampil (survey, whatsapp, ubah status, dll)

## Testing Steps

### 1. Test Filter "Belum Upload"

1. Login sebagai **Admin**
2. Buka menu **Tes Psikologi → Data Psikologi**
3. Pilih filter **"Belum Upload"**
4. ✅ Halaman load tanpa error
5. ✅ Tampil 8 kolom (tanpa extended columns):
   - Nomor Pendaftaran
   - NISN
   - Nama Pendaftar
   - Jalur
   - Jenis Kelamin
   - Email
   - No HP
   - Status Pendaftaran
6. ✅ **TIDAK ADA** kolom: Ortu WhatsApp, Ubah Status, Kirim Pesan, Riwayat Pesan
7. ✅ Masuk akal karena belum ada data tes psikologi

### 2. Test Filter Lainnya

1. Pilih filter **"Menunggu Verifikasi"**
2. ✅ Halaman load tanpa error
3. ✅ Tampil 12 kolom:
   - Nomor, NISN, Nama, Jalur, Jenis Kelamin
   - **Opsi/Biaya**
   - **Bank Transfer**
   - **Waktu Transfer**
   - **Nama Pengirim**
   - **Catatan Psikologi**
   - **Bukti Transfer**
   - **Status Psikologi**

### 3. Test Filter "Semua Status"

1. Pilih filter **"Semua Status"**
2. ✅ Halaman load tanpa error
3. ✅ Tampil semua data

### 4. Test di Panitia

1. Login sebagai **Panitia** (panitia_tes_psikologi)
2. Buka menu **Tes Psikologi**
3. Test semua filter (sama seperti Admin)
4. ✅ Semua berfungsi normal

## Expected Result

### SEBELUM (ERROR):
- ❌ Error 500 saat akses /admin/tes-psikologi
- ❌ Error 500 saat pilih filter "Belum Upload"
- ❌ Sistem mencoba akses $data->opsi pada Pendaftar model

### SESUDAH (FIXED):
- ✅ Halaman load normal
- ✅ Filter "Belum Upload" tampil 8 kolom (sesuai Pendaftar)
- ✅ Filter "Belum Upload" **TIDAK TAMPIL** extended columns (masuk akal, belum ada data tes)
- ✅ Filter lainnya tampil 12 kolom + extended columns (Ortu WhatsApp, Ubah Status, dll)
- ✅ Works di Admin DAN Panitia

## Upload ke Production

Jalankan:
```
upload_fix_error_500_tes_psikologi_12_feb_2026.bat
```

**File to upload:**
1. `resources/views/livewire/admin/partials/tes-psikologi-data.blade.php`

## Related Issue

Issue ini muncul karena penambahan kolom **Ortu WhatsApp** di file yang sama. Saat menambahkan kolom baru, struktur conditional tidak diperhatikan dengan baik.

## Confidence Level

**10/10** - Fix sederhana, root cause jelas, solusi straightforward!

## Design Decision

**Mengapa Extended Columns Tidak Tampil untuk Filter "Belum Upload"?**

Filter "Belum Upload" menampilkan pendaftar yang:
- Sudah disetujui (`status_pendaftaran = 'data_disetujui'`)
- Tapi **belum ada record** di table `tes_psikologi_pendaftar`

Kolom yang disembunyikan:
1. **Ortu WhatsApp** - Bisa diakses di menu Pendaftar
2. **Ubah Status** - Belum ada status tes psikologi untuk diubah
3. **Kirim Pesan** - Reminder lebih cocok via menu Pendaftar
4. **Riwayat Pesan** - Belum ada riwayat pesan tes psikologi

Ini **UX improvement** yang membuat interface lebih clean dan logis.

## Notes

- Panitia Tes Psikologi menggunakan partial yang sama dengan Admin
- Satu file fix → apply ke Admin DAN Panitia
- Tidak perlu clear cache (hanya view yang berubah)
- Extended columns hanya tampil untuk data yang sudah upload tes psikologi
