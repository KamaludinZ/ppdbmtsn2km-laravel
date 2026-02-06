# CHECKLIST VERIFIKASI FILE TIMESTAMP

## ✅ PRE-UPLOAD CHECKLIST

- [ ] File lokal sudah benar dan berfungsi
- [ ] Sudah test upload file di lokal
- [ ] Timestamp sudah muncul di lokal
- [ ] Koneksi SSH ke production siap
- [ ] Backup file lama di production (optional)

## 📤 UPLOAD CHECKLIST

Jalankan: `upload_fix_file_timestamp.bat`

Atau manual:

- [ ] Upload `app/Livewire/Admin/DetailPendaftar.php`
- [ ] Upload `app/Livewire/Panitia/DetailPendaftar.php`
- [ ] Upload `app/Services/FileNamingService.php`
- [ ] Upload `resources/views/livewire/admin/detail-pendaftar.blade.php`
- [ ] Upload `resources/views/livewire/panitia/detail-pendaftar.blade.php`
- [ ] Clear cache production: `php artisan optimize:clear`

## 🧪 POST-UPLOAD TESTING

### Test 1: Admin Upload Dokumen

**URL**: `https://pmbm.mtsn2kotamalang.sch.id/admin/pendaftar/29?activeTab=dokumen`

1. Login sebagai Admin
   - [ ] Berhasil login
   - [ ] Halaman detail pendaftar terbuka
   - [ ] Tab Dokumen aktif

2. Upload File Baru
   - [ ] Pilih file (max 2MB, PDF)
   - [ ] Klik upload
   - [ ] Upload berhasil (muncul toast notification)
   - [ ] File muncul di list

3. Cek Nama File
   - [ ] Nama file menampilkan timestamp
   - [ ] Format: `{jenis}-{nomor}-{nama}-{timestamp}.{ext}`
   - [ ] Contoh: `rapor-PPDB2024029-Ahmad_Fulan-20260205_143052.pdf`
   - [ ] Tanggal upload muncul
   - [ ] Ukuran file muncul

4. Replace File
   - [ ] Upload file baru untuk mengganti
   - [ ] File lama terhapus
   - [ ] File baru muncul dengan timestamp berbeda
   - [ ] Timestamp lebih baru dari sebelumnya

### Test 2: Panitia Upload Dokumen

**URL**: `https://pmbm.mtsn2kotamalang.sch.id/panitia-pendaftaran/pendaftar/29?activeTab=dokumen`

1. Login sebagai Panitia
   - [ ] Berhasil login
   - [ ] Halaman detail pendaftar terbuka
   - [ ] Tab Dokumen aktif

2. Upload File Baru
   - [ ] Pilih file (max 2MB, PDF)
   - [ ] Klik upload
   - [ ] Upload berhasil
   - [ ] File muncul di list

3. Cek Nama File
   - [ ] Nama file menampilkan timestamp
   - [ ] Format benar
   - [ ] Tanggal upload muncul
   - [ ] Ukuran file muncul

### Test 3: Multi-Dokumen

Test upload beberapa jenis dokumen:

- [ ] **Rapor**: `rapor-[NOMOR]-[NAMA]-[TIMESTAMP].pdf`
- [ ] **Akta**: `akta_kelahiran-[NOMOR]-[NAMA]-[TIMESTAMP].pdf`
- [ ] **KK**: `kartu_keluarga-[NOMOR]-[NAMA]-[TIMESTAMP].pdf`
- [ ] **NISN**: `nisn-[NOMOR]-[NAMA]-[TIMESTAMP].pdf`
- [ ] **Ijazah**: `ijazah-[NOMOR]-[NAMA]-[TIMESTAMP].pdf`
- [ ] **Sertifikat**: `sertifikat_prestasi-[NOMOR]-[NAMA]-[TIMESTAMP].pdf`
- [ ] **KIP**: `kip-[NOMOR]-[NAMA]-[TIMESTAMP].pdf` (jika ada nomor KIP)
- [ ] **PKH**: `pkh-[NOMOR]-[NAMA]-[TIMESTAMP].pdf` (jika ada nomor PKH)
- [ ] **KKS**: `kks-[NOMOR]-[NAMA]-[TIMESTAMP].pdf` (jika ada nomor KKS)

### Test 4: Backward Compatibility

Test dengan file lama yang sudah ada:

1. File Legacy (sebelum update)
   - [ ] File lama tetap ditampilkan
   - [ ] Tidak ada error
   - [ ] Bisa di-download
   - [ ] Ada label "Legacy file" jika masih menggunakan old field

2. Replace File Legacy
   - [ ] Upload file baru untuk mengganti file legacy
   - [ ] File legacy terhapus
   - [ ] File baru dengan timestamp muncul
   - [ ] Tidak ada error

### Test 5: Status & Verifikasi

1. Status Upload
   - [ ] Status berubah ke "Menunggu Verifikasi" setelah upload
   - [ ] Warna badge berubah (kuning)

2. Admin/Panitia Verifikasi
   - [ ] Bisa ubah status ke "Disetujui"
   - [ ] Bisa ubah status ke "Tidak Sesuai"
   - [ ] Bisa ubah status ke "Perlu Perbaikan"
   - [ ] Bisa tambahkan catatan

3. Re-upload setelah Perlu Perbaikan
   - [ ] Pendaftar bisa upload ulang
   - [ ] File baru mendapat timestamp baru
   - [ ] Status kembali ke "Menunggu Verifikasi"

## 🔍 ERROR CHECKING

### Common Errors

1. **File tidak muncul**
   - [ ] Cek storage permissions
   - [ ] Cek apakah file tersimpan di disk 'private'
   - [ ] Cek database tabel `dokumen`

2. **Timestamp tidak muncul**
   - [ ] Cek apakah FileNamingService ter-upload
   - [ ] Cek cache sudah di-clear
   - [ ] Cek kolom `nama_file` di database

3. **Error 404 saat view file**
   - [ ] Cek route `dokumen.view`
   - [ ] Cek SecureFileController
   - [ ] Cek file exists di storage

4. **Upload gagal**
   - [ ] Cek size limit (max 2MB)
   - [ ] Cek MIME type (harus PDF)
   - [ ] Cek storage disk permissions
   - [ ] Cek folder `dokumen/{pendaftar_id}` exists

## 📊 VERIFICATION REPORT

**Tanggal Testing**: ___________________
**Tester**: ___________________

### Results Summary

| Test Case | Status | Notes |
|-----------|--------|-------|
| Admin Upload | ⬜ Pass / ⬜ Fail | |
| Panitia Upload | ⬜ Pass / ⬜ Fail | |
| Multi-Dokumen | ⬜ Pass / ⬜ Fail | |
| Backward Compatibility | ⬜ Pass / ⬜ Fail | |
| Status & Verifikasi | ⬜ Pass / ⬜ Fail | |

### Issues Found (if any)

1. _______________________________________________________________
2. _______________________________________________________________
3. _______________________________________________________________

### Screenshot Evidence

- [ ] Screenshot nama file dengan timestamp (Admin)
- [ ] Screenshot nama file dengan timestamp (Panitia)
- [ ] Screenshot multi-dokumen dengan timestamp berbeda
- [ ] Screenshot replace file (timestamp berubah)

## ✅ SIGN-OFF

- [ ] Semua test case PASS
- [ ] Tidak ada critical bugs
- [ ] Screenshot evidence terlampir
- [ ] Dokumentasi sudah diupdate
- [ ] **PRODUCTION READY** ✅

---

**Approved by**: ___________________
**Date**: ___________________
**Signature**: ___________________
