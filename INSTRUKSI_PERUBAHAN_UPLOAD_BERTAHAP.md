# INSTRUKSI PERUBAHAN UPLOAD BERTAHAP - DAFTAR ULANG
**Tanggal:** 5 Februari 2026
**Status:** MANUAL CHANGES REQUIRED

---

## 📋 RINGKASAN PERUBAHAN

### Yang Sudah Selesai:
1. ✅ Tab order diubah: Madrasah → **Ukuran** → Seragam
2. ✅ Nama Pengirim menghapus "(Opsional)" dan menambahkan tanda bintang merah (*)

### Yang Perlu Dilakukan Manual:
1. ⏳ Upload auto (wire:model.live) untuk Surat Pernyataan dan Bukti Pembayaran
2. ⏳ Reorganisasi urutan: Surat Pernyataan DULU, baru Bukti Pembayaran
3. ⏳ Disable Bukti Pembayaran jika Surat Pernyataan belum diupload
4. ⏳ Tampilkan tombol "Lihat Bukti" ketika file sudah terupload

---

## 🎯 PERUBAHAN YANG DIPERLUKAN

### File: `resources/views/livewire/pendaftar/daftar-ulang.blade.php`

### Perubahan 1: Reorganisasi Section DU Madrasah

**URUTAN BARU:**
1. Form fields (Nominal, Konfirmasi, Opsi Pembayaran, Bank)
2. **SURAT PERNYATAAN** (STEP 1 - Wajib dulu)
3. **BUKTI PEMBAYARAN** (STEP 2 - Setelah Surat Pernyataan)
4. Nama Pengirim
5. Tombol Simpan

**Lokasi:** Lines 662-860 (Tab Madrasah)

---

### Perubahan 2: Surat Pernyataan - Auto Upload

**Dari:**
```blade
<input type="file" wire:model="suratPernyataan" id="surat-pernyataan" class="hidden" accept=".pdf">
<label for="surat-pernyataan" class="cursor-pointer block w-full h-full">
    <!-- Click to upload UI -->
</label>
```

**Menjadi:**
```blade
<input type="file" wire:model.live="suratPernyataan" id="surat-pernyataan" class="hidden" accept=".pdf">
<label for="surat-pernyataan" class="cursor-pointer block w-full h-full">
    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
    </svg>
    <p class="mt-2 text-sm text-gray-600">Klik untuk upload surat pernyataan</p>
    <p class="text-xs text-gray-500 mt-1">Hanya PDF (Max. 2MB)</p>
</label>
<div wire:loading wire:target="suratPernyataan" class="mt-2">
    <div class="flex items-center justify-center">
        <svg class="animate-spin h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="text-sm text-gray-600">Uploading...</span>
    </div>
</div>
```

**Dan tambahkan display jika sudah terupload:**
```blade
@if($daftarUlang && $daftarUlang->surat_pernyataan_path)
<div class="flex items-center justify-between p-4 bg-green-50 border border-green-200 rounded-lg">
    <div class="flex items-center">
        <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>
        <span class="text-sm font-medium text-green-800">Surat pernyataan telah diupload</span>
    </div>
    <a href="{{ route('pendaftar.daftarulang.view-file', ['type' => 'pernyataan', 'file' => basename($daftarUlang->surat_pernyataan_path)]) }}"
       target="_blank"
       class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        </svg>
        Lihat Bukti
    </a>
</div>
@else
<!-- Upload form here -->
@endif
```

---

### Perubahan 3: Bukti Pembayaran - Auto Upload + Conditional

**Tambahkan kondisi BEFORE upload section:**
```blade
@php
    $suratPernyataanUploaded = $daftarUlang && $daftarUlang->surat_pernyataan_path;
@endphp

@if(!$suratPernyataanUploaded)
<div class="p-4 bg-yellow-50 border-2 border-yellow-200 rounded-lg">
    <div class="flex items-center">
        <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
        </svg>
        <div>
            <p class="text-sm font-semibold text-yellow-800">Upload Surat Pernyataan Terlebih Dahulu</p>
            <p class="text-xs text-yellow-600 mt-1">Anda harus upload surat pernyataan sebelum dapat upload bukti pembayaran.</p>
        </div>
    </div>
</div>
@else
<!-- Bukti Pembayaran upload form -->
@endif
```

**Ubah input menjadi auto-upload:**
```blade
<input type="file" wire:model.live="buktiPembayaran" id="bukti-madrasah" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
```

**Tampilan ketika sudah terupload:**
```blade
@if($daftarUlang && $daftarUlang->bukti_pembayaran_madrasah)
<div class="flex items-center justify-between p-4 bg-green-50 border border-green-200 rounded-lg">
    <div class="flex items-center">
        <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>
        <span class="text-sm font-medium text-green-800">Bukti pembayaran telah diupload</span>
    </div>
    <a href="{{ route('pendaftar.daftarulang.view-file', ['type' => 'madrasah', 'file' => basename($daftarUlang->bukti_pembayaran_madrasah)]) }}"
       target="_blank"
       class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        </svg>
        Lihat Bukti
    </a>
</div>
@else
<!-- Upload form here -->
@endif
```

---

### Perubahan 4: Terapkan Sama untuk Tab Seragam

Lakukan perubahan yang sama untuk Tab Seragam:
- Auto-upload dengan `wire:model.live="buktiSeragam"`
- Tampilkan tombol "Lihat Bukti" ketika sudah terupload
- Hapus tombol "Upload" yang terpisah

**Lokasi:** Lines 1308-1432 (Tab Seragam - Bukti Pembayaran)

---

## 🎨 UI/UX IMPROVEMENT

### Before:
1. User pilih file
2. Klik tombol "Upload"
3. Tunggu proses
4. File tersimpan

### After:
1. User pilih file
2. **Langsung auto-upload** (tanpa tombol)
3. Tampilkan progress spinner
4. Setelah terupload, tampilkan tombol **"Lihat Bukti"**

---

## 🔒 SECURITY & VALIDATION

### Logika Bertahap:

**DU Madrasah:**
```
STEP 1: Upload Surat Pernyataan
   ↓ (wajib)
STEP 2: Upload Bukti Pembayaran (unlock after Step 1)
   ↓
STEP 3: Isi Nama Pengirim
   ↓
STEP 4: Klik Simpan
```

**Validation di Controller:**
```php
public function saveMadrasah()
{
    // Check surat pernyataan uploaded first
    if (!$this->daftarUlang || !$this->daftarUlang->surat_pernyataan_path) {
        session()->flash('error', 'Silakan upload surat pernyataan terlebih dahulu.');
        return;
    }

    // Rest of validation...
}
```

---

## 📝 CHECKLIST IMPLEMENTASI

### View Changes:
- [ ] Pindahkan section Surat Pernyataan BEFORE Bukti Pembayaran
- [ ] Ubah semua upload input ke `wire:model.live`
- [ ] Tambahkan kondisi disable untuk Bukti Pembayaran
- [ ] Ganti UI upload dengan tombol "Lihat Bukti" setelah terupload
- [ ] Tambahkan warning message untuk upload bertahap
- [ ] Terapkan perubahan yang sama untuk Tab Seragam

### Controller Changes:
- [ ] Tambahkan validasi bertahap di `saveMadrasah()`
- [ ] Tambahkan validasi bertahap di `saveSeragam()`
- [ ] Handle auto-upload dengan wire:model.live

### Testing:
- [ ] Test upload Surat Pernyataan → auto-upload
- [ ] Test Bukti Pembayaran terkunci sebelum Surat Pernyataan
- [ ] Test Bukti Pembayaran unlock setelah Surat Pernyataan
- [ ] Test tombol "Lihat Bukti" berfungsi
- [ ] Test sama untuk Tab Seragam

---

## 🚀 DEPLOYMENT

**File yang perlu diupload:**
1. `resources/views/livewire/pendaftar/daftar-ulang.blade.php` (setelah manual changes)
2. `app/Livewire/Pendaftar/DaftarUlang.php` (tambah validasi)

**Upload Command:**
```bash
scp resources/views/livewire/pendaftar/daftar-ulang.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
scp app/Livewire/Pendaftar/DaftarUlang.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
```

---

## ⚠️ CATATAN PENTING

1. **Perubahan ini KOMPLEKS** dan membutuhkan reorganisasi besar pada view
2. **Backup file original** sebelum melakukan perubahan
3. **Test di local** terlebih dahulu sebelum deploy ke production
4. **wire:model.live** akan auto-trigger upload, pastikan server handle dengan baik
5. Perubahan ini mempengaruhi **flow user experience**, pastikan di-test menyeluruh

---

**Dibuat:** 5 Februari 2026
**Developer:** AI Assistant
**Requires:** Manual Implementation
**Estimated Time:** 2-3 hours untuk implementasi lengkap
