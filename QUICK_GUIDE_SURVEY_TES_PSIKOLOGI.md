# 🚀 QUICK GUIDE: Survey Tes Psikologi

## 📋 Ringkasan Fitur
Survey Tes Psikologi adalah fitur kuesioner yang **wajib diisi** pendaftar sebelum upload bukti pembayaran tes psikologi.

---

## 👨‍💼 UNTUK ADMIN/PANITIA

### ⚡ Quick Start (5 Langkah)

#### 1. Aktifkan Survey
```
Admin → Tes Psikologi → Tab "Pertanyaan Survey"
Toggle "Survey Aktif" → ON (hijau)
Klik "Simpan Pengaturan"
```

#### 2. Buat Pertanyaan (Text/Textarea)
```
Isi form:
- Pertanyaan: "Apa motivasi Anda?"
- Tipe Input: Text
- Wajib Diisi: ✓
- Urutan: 1
- Aktif: ✓

Klik "Simpan Pertanyaan"
```

#### 3. Buat Pertanyaan (Radio/Checkbox/Select)
```
Isi form:
- Pertanyaan: "Bagaimana Anda tahu PPDB?"
- Tipe Input: Radio
- Wajib Diisi: ✓
- Urutan: 2
- Aktif: ✓

Klik "Simpan Pertanyaan"

Form Opsi akan muncul:
Opsi 1: Label "Media Sosial", Value "medsos", Urutan 1
Opsi 2: Label "Website", Value "website", Urutan 2
Opsi 3: Label "Teman", Value "teman", Urutan 3

Klik "Tutup Form Opsi"
```

#### 4. Lihat Jawaban Pendaftar
```
Admin → Tes Psikologi → Tab "Data Psikologi"
Kolom "Jawaban Survey" → Klik tombol "Lihat (N)"
Modal akan tampilkan semua jawaban
```

#### 5. Export ke Excel
```
Admin → Laporan
Klik "Export Data Pendaftar Lengkap"
Kolom survey otomatis include dengan prefix "SURVEI:"
```

---

### 📝 Tipe Input yang Tersedia

| Tipe | Deskripsi | Use Case |
|------|-----------|----------|
| **Text** | Input teks pendek | Nama, nomor, jawaban singkat |
| **Textarea** | Input teks panjang | Motivasi, essay, penjelasan |
| **Radio** | Pilihan tunggal | Jenis kelamin, ya/tidak |
| **Checkbox** | Pilihan ganda | Hobi, minat, ekstrakurikuler |
| **Select** | Dropdown pilihan | Pilih kategori, list panjang |

---

### 🔧 Operasi Lanjutan

**Edit Pertanyaan:**
```
Daftar Pertanyaan → Klik "Edit" (ikon pensil)
Edit data → Simpan
```

**Edit Opsi:**
```
Daftar Pertanyaan → Klik "Kelola Opsi"
Edit opsi → Klik "Tutup Form Opsi"
```

**Hapus Pertanyaan:**
```
Daftar Pertanyaan → Klik "Hapus" (ikon trash)
Konfirmasi → Pertanyaan & jawaban terhapus
```

**Non-aktifkan Survey:**
```
Toggle "Survey Aktif" → OFF (abu-abu)
Simpan → Survey hilang dari halaman pendaftar
```

---

## 👨‍🎓 UNTUK PENDAFTAR

### ⚡ Cara Isi Survey (3 Langkah)

#### 1. Buka Halaman Tes Psikologi
```
Login → Menu "Tes Psikologi"
Pastikan status: "Data Disetujui"
```

#### 2. Isi Survey
```
Section "Pertanyaan Survey" akan muncul
Badge status: "Belum Diisi" (kuning)
Warning: "Survey wajib diisi sebelum upload bukti pembayaran!"

Jawab semua pertanyaan:
- Tanda bintang merah (*) = wajib diisi
- Pilih/isi sesuai tipe input

Klik "Simpan Jawaban Survey"
```

#### 3. Upload Bukti Pembayaran
```
Badge berubah: "Sudah Diisi" (hijau)
Warning hilang

Scroll ke "Upload Bukti Pembayaran"
Isi nominal & pilih file
Klik "Upload Bukti Pembayaran"
```

---

### ⚠️ Penting untuk Pendaftar

✅ **BOLEH:**
- Edit jawaban kapan saja sebelum verifikasi
- Skip pertanyaan yang tidak wajib (tanpa tanda *)
- Simpan berkali-kali

❌ **TIDAK BOLEH:**
- Upload bukti tanpa isi survey
- Skip pertanyaan wajib (dengan tanda *)

---

## 🐛 Troubleshooting

### Problem: Survey tidak muncul di halaman pendaftar
**Solusi:**
1. Pastikan survey sudah diaktifkan admin
2. Pastikan ada minimal 1 pertanyaan aktif
3. Clear browser cache (Ctrl + F5)

### Problem: Upload bukti diblokir
**Solusi:**
1. Cek badge status: harus "Sudah Diisi"
2. Pastikan semua pertanyaan wajib (*) sudah dijawab
3. Klik "Simpan Jawaban Survey" sekali lagi

### Problem: Error saat simpan jawaban
**Solusi:**
1. Cek koneksi internet
2. Refresh halaman (F5)
3. Isi ulang dan simpan lagi

### Problem: Jawaban checkbox tidak tersimpan
**Solusi:**
1. Pastikan minimal 1 checkbox dicentang (jika wajib)
2. Klik "Simpan Jawaban Survey"
3. Refresh halaman untuk verify

---

## 📊 FAQ

**Q: Berapa maksimal pertanyaan yang bisa dibuat?**
A: Tidak ada limit, tapi disarankan 10-15 pertanyaan untuk UX yang baik.

**Q: Apakah jawaban pendaftar bisa diedit setelah upload bukti?**
A: Ya, pendaftar bisa edit jawaban kapan saja sebelum verifikasi final.

**Q: Apakah admin bisa edit jawaban pendaftar?**
A: Tidak, admin hanya bisa melihat. Pendaftar yang punya kontrol penuh.

**Q: Apa yang terjadi jika pertanyaan dihapus setelah dijawab?**
A: Semua jawaban untuk pertanyaan tersebut akan terhapus dari database.

**Q: Apakah survey bisa diaktifkan tengah-tengah periode PPDB?**
A: Ya, tapi pendaftar yang sudah upload bukti tidak wajib isi survey lagi.

**Q: Format jawaban checkbox di Excel?**
A: Comma-separated, contoh: "pramuka, olahraga, seni"

**Q: Apakah survey mempengaruhi kelulusan?**
A: Tidak, survey hanya untuk data tambahan. Tidak ada scoring otomatis.

---

## 💡 Tips & Best Practices

### Untuk Admin:
1. ✅ Buat pertanyaan yang jelas dan tidak ambigu
2. ✅ Test survey sebelum aktivasi dengan dummy account
3. ✅ Limit pertanyaan wajib (maksimal 50% dari total)
4. ✅ Gunakan "Deskripsi" untuk instruksi tambahan
5. ✅ Export data survey secara berkala untuk backup

### Untuk Pendaftar:
1. ✅ Isi survey dengan jujur dan lengkap
2. ✅ Baca deskripsi pertanyaan dengan teliti
3. ✅ Simpan jawaban sebelum menutup browser
4. ✅ Screenshot bukti "Sudah Diisi" untuk dokumentasi

---

## 📞 Butuh Bantuan?

**Admin/Panitia:**
- Baca dokumentasi lengkap: `FITUR_SURVEY_TES_PSIKOLOGI_DOKUMENTASI.md`
- Cek file: `app/Livewire/Admin/TesPsikologi.php`

**Pendaftar:**
- Hubungi admin/panitia melalui menu "Kontak"
- Screenshot error untuk mempermudah troubleshooting

---

**✨ Selamat menggunakan fitur Survey Tes Psikologi! ✨**

Updated: 28 Januari 2026
