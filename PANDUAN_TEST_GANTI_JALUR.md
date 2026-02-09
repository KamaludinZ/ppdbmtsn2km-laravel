# Panduan Test - Fitur Ganti Jalur

## ✅ Yang Sudah Ditambahkan

Fitur untuk mengganti jalur pendaftaran seorang pendaftar dan otomatis generate nomor pendaftaran baru.

**Lokasi:** `/admin/pendaftar` (kolom paling kanan setelah "Riwayat Pesan")

---

## 🧪 Cara Test

### Test 1: Lihat Kolom Baru

**1. Login sebagai Admin:**
```
Role: admin
URL: http://localhost/admin/pendaftar
```

**2. Cek Kolom "Ganti Jalur":**
- Scroll ke kanan sampai kolom paling akhir
- Setelah kolom "Riwayat Pesan", ada kolom baru "Ganti Jalur"
- Setiap row pendaftar punya:
  - **Dropdown:** Menampilkan jalur yang tersedia
  - **Tombol Generate:** Dengan icon refresh
  - **Info:** Nomor pendaftaran saat ini

---

### Test 2: Ganti Jalur Pendaftar

**1. Pilih Pendaftar:**
- Pilih satu pendaftar di tabel
- Lihat kolom "Ganti Jalur"
- Perhatikan jalur saat ini (misalnya: "Jalur Reguler")
- Perhatikan nomor pendaftaran (misalnya: "REG-26020045")

**2. Pilih Jalur Baru:**
- Klik dropdown di kolom "Ganti Jalur"
- Pilih jalur berbeda (misalnya: "Jalur Prestasi")
- Tombol "Generate" berubah dari abu-abu ke biru

**3. Generate Nomor Baru:**
- Klik tombol "Generate" (yang biru)
- Muncul konfirmasi: "Yakin ingin mengganti jalur pendaftaran? Nomor pendaftaran akan di-generate ulang."
- Klik "OK"

**4. Verifikasi Hasil:**
- Muncul notifikasi hijau: "Jalur berhasil diganti dari 'Jalur Reguler' ke 'Jalur Prestasi'. Nomor pendaftaran baru: PRE-26020012"
- Halaman refresh otomatis
- Cek kolom "Jalur": Sekarang "Jalur Prestasi"
- Cek kolom "Nomor Pendaftaran": Sekarang "PRE-26020012"
- Cek dropdown di kolom "Ganti Jalur": Sekarang menunjukkan "Jalur Prestasi" sebagai selected

---

### Test 3: Tombol Disabled

**1. Pilih Jalur yang Sama:**
- Di dropdown, pilih jalur yang sama dengan saat ini
- Tombol "Generate" tetap abu-abu (disabled)
- Tidak bisa diklik

**2. Cancel Konfirmasi:**
- Pilih jalur berbeda
- Klik tombol "Generate"
- Muncul konfirmasi
- Klik "Cancel"
- Tidak ada perubahan pada data

---

### Test 4: Format Nomor Pendaftaran

**Format:** `[PREFIX]-[YY][MM][0001-9999]`

**Contoh:**
- REG-26020001 (Reguler, Februari 2026, urutan 0001)
- PRE-26020012 (Prestasi, Februari 2026, urutan 0012)
- TAH-26020003 (Tahfidz, Februari 2026, urutan 0003)

**Test:**
1. Ganti beberapa pendaftar ke jalur "Prestasi"
2. Nomor harus sequential: PRE-26020001, PRE-26020002, PRE-26020003, dst.
3. Prefix berubah sesuai jalur
4. Bulan dan tahun sesuai waktu sekarang

---

### Test 5: Multiple Change

**1. Ganti Jalur Beberapa Kali:**
- Pendaftar A: Reguler → Prestasi (nomor: PRE-26020001)
- Pendaftar A: Prestasi → Tahfidz (nomor: TAH-26020001)
- Pendaftar A: Tahfidz → Reguler (nomor: REG-26020046)

**2. Verifikasi:**
- Setiap kali ganti, dapat nomor baru
- Nomor lama tidak dipakai lagi
- Nomor sequential per jalur tetap konsisten

---

## 📊 Checklist Test Lengkap

### Visual Check
- [ ] Kolom "Ganti Jalur" muncul setelah "Riwayat Pesan"
- [ ] Dropdown menampilkan semua jalur yang ada
- [ ] Dropdown menunjukkan jalur saat ini sebagai selected
- [ ] Tombol "Generate" ada icon refresh
- [ ] Nomor pendaftaran saat ini tampil di bawah tombol

### Functional Check
- [ ] Pilih jalur berbeda → Tombol jadi biru (enabled)
- [ ] Pilih jalur sama → Tombol tetap abu-abu (disabled)
- [ ] Klik tombol → Muncul konfirmasi
- [ ] Klik OK → Jalur dan nomor berubah
- [ ] Klik Cancel → Tidak ada perubahan
- [ ] Notifikasi sukses muncul dengan detail perubahan

### Data Integrity
- [ ] Jalur terupdate di database (tabel: pendaftar, kolom: jalur_pendaftaran_id)
- [ ] Nomor terupdate di database (tabel: pendaftar, kolom: nomor_pendaftaran)
- [ ] Field diperbarui_oleh terisi dengan ID admin
- [ ] Field updated_at otomatis terupdate
- [ ] Tidak ada duplikasi nomor pendaftaran

### Format Nomor
- [ ] Format: PREFIX-YYMM#### (contoh: REG-26020045)
- [ ] Prefix sesuai jalur
- [ ] YY = tahun (2 digit)
- [ ] MM = bulan (2 digit)
- [ ] #### = urutan (4 digit, dengan leading zero)
- [ ] Sequential per jalur per bulan

### Error Handling
- [ ] Jika error → Muncul notifikasi merah dengan pesan error
- [ ] Jika error → Data tidak berubah (rollback)
- [ ] Network error → Notifikasi error
- [ ] Jalur tidak ada → Error 404

---

## ⚠️ Jika Ada Masalah

### Kolom tidak muncul:
1. Hard refresh browser: `Ctrl + Shift + F5`
2. Clear cache Laravel: `php artisan optimize:clear`
3. Cek file sudah terupload ke server

### Tombol tidak berfungsi:
1. Cek browser console (F12) untuk error JavaScript
2. Pastikan Alpine.js terload
3. Cek Laravel logs: `storage/logs/laravel.log`

### Nomor duplikat:
1. Seharusnya tidak terjadi (ada locking mechanism)
2. Jika terjadi, report ke developer
3. Cek Laravel logs untuk detail error

### Format nomor salah:
1. Cek prefix di database (tabel: jalur_pendaftaran, kolom: prefix_nomor)
2. Cek method generateNomorPendaftaran() di model JalurPendaftaran

---

## 🎯 Skenario Test Real

### Scenario: Pendaftar Salah Pilih Jalur

**Situasi:**
- Pendaftar bernama "Budi" mendaftar di "Jalur Reguler"
- Nomor pendaftaran: REG-26020045
- Setelah dicek, seharusnya masuk "Jalur Prestasi" (punya prestasi)

**Solusi:**
1. Admin login ke `/admin/pendaftar`
2. Cari pendaftar "Budi" (gunakan search)
3. Scroll ke kolom "Ganti Jalur"
4. Dropdown saat ini: "Jalur Reguler"
5. Pilih: "Jalur Prestasi"
6. Klik "Generate"
7. Konfirmasi "OK"
8. Budi sekarang:
   - Jalur: Jalur Prestasi
   - Nomor: PRE-26020012 (nomor baru)

**Verifikasi:**
- Budi bisa login dengan nomor baru
- Semua data Budi tetap utuh (tidak hilang)
- History tidak hilang
- Relasi ke tabel lain (daftar_ulang, seragam) masih valid

---

## 📝 Catatan Penting

### Hal yang Berubah:
- ✅ `jalur_pendaftaran_id` → ID jalur baru
- ✅ `nomor_pendaftaran` → Nomor baru
- ✅ `diperbarui_oleh` → ID admin
- ✅ `updated_at` → Timestamp update

### Hal yang TIDAK Berubah:
- ✅ Semua data pendaftar lain (nama, NISN, dll.)
- ✅ History daftar ulang
- ✅ History pembayaran
- ✅ Berkas yang sudah diupload
- ✅ Status verifikasi
- ✅ Relasi ke tabel lain

---

**Date:** 9 Feb 2026
**Status:** Complete & Ready to Test
**Feature:** Ganti Jalur Pendaftaran dengan Auto-Generate Nomor Baru

**Jika semua checklist ✅ → Siap production!**
