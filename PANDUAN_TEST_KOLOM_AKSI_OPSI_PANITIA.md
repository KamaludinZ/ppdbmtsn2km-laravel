# Panduan Test - Kolom Aksi Opsi Panitia

## ✅ Yang Sudah Ditambahkan

Kolom "Aksi Opsi" (dengan 2 toggle per pendaftar) sudah ditambahkan ke:
1. **Panitia Daftar Ulang** → Tab Madrasah
2. **Panitia Seragam** → Tab Seragam & Tab Ukuran

---

## 🧪 Cara Test

### Test 1: Panitia Daftar Ulang

**1. Login sebagai Panitia:**
```
Role: panitia_daftarulang
URL: http://localhost/panitia/daftar-ulang
```

**2. Cek Kolom Baru:**
- Tab: Madrasah
- Lihat kolom "Aksi Opsi" (sebelum kolom "Ubah Status")
- Setiap row ada 2 toggle:
  - **Toggle Biru:** Dropdown Opsi Lainnya
  - **Toggle Hijau:** Konfirmasi Opsi Lainnya

**3. Test Toggle:**
- Klik toggle biru untuk pendaftar pertama → Toggle menyala (warna biru)
- Text berubah: "Aktif untuk orang ini"
- Muncul notifikasi: "Opsi Lainnya Dropdown (Madrasah) untuk pendaftar ini berhasil diaktifkan"
- Refresh halaman (F5) → Toggle masih menyala

**4. Test di Pendaftar:**
- Login sebagai pendaftar yang tadi di-toggle
- Buka: `/pendaftar/daftar-ulang?activeTab=madrasah`
- Cek dropdown "Pilih Paket Daftar Ulang Madrasah"
- **Harus muncul:** "Opsi Lainnya" di dropdown
- Coba pilih "Opsi Lainnya" → Bisa input nominal custom

---

### Test 2: Panitia Seragam - Tab Seragam

**1. Login sebagai Panitia Seragam:**
```
Role: panitia_seragam
URL: http://localhost/panitia-seragam/daftar-ulang?activeTab=seragam
```

**2. Cek Kolom Baru:**
- Tab: Seragam (tab pertama)
- Lihat kolom "Aksi Opsi" (sebelum kolom "Ubah Status")
- Setiap row ada 2 toggle

**3. Test Toggle:**
- Klik toggle biru untuk pendaftar pertama → Toggle menyala
- Notifikasi: "Opsi Lainnya Dropdown (Seragam) untuk pendaftar ini berhasil diaktifkan"
- Refresh halaman → Toggle masih menyala

**4. Test di Pendaftar:**
- Login sebagai pendaftar yang tadi di-toggle
- Buka: `/pendaftar/daftar-ulang?activeTab=seragam`
- Cek dropdown "Pilih Paket Daftar Ulang Seragam"
- **Harus muncul:** "Opsi Lainnya" di dropdown

---

### Test 3: Panitia Seragam - Tab Ukuran

**1. Pindah ke Tab Ukuran:**
```
URL: http://localhost/panitia-seragam/daftar-ulang?activeTab=ukuran
```

**2. Cek Kolom Masih Ada:**
- Tab: Ukuran (tab kedua)
- Kolom "Aksi Opsi" masih muncul
- 2 toggle per row tetap ada

**3. Test Toggle:**
- Klik toggle hijau (Konfirmasi) untuk pendaftar pertama → Toggle menyala
- Notifikasi: "Opsi Lainnya Konfirmasi (Ukuran) untuk pendaftar ini berhasil diaktifkan"
- Refresh halaman → Toggle masih menyala

**4. Catatan:**
- Ukuran sebenarnya tidak punya individual toggle di desain awal
- Tapi toggle tetap ada untuk konsistensi UI
- Data disimpan di tabel `seragam` (sama dengan tab Seragam)

---

## 📊 Ringkasan Fitur

| Role | Tab | Toggle Ada? | Model |
|------|-----|------------|-------|
| **Admin** | Madrasah | ✅ Ya | DaftarUlang |
| **Admin** | Seragam | ✅ Ya | Seragam |
| **Panitia Daftar Ulang** | Madrasah | ✅ Ya (NEW!) | DaftarUlang |
| **Panitia Seragam** | Seragam | ✅ Ya (NEW!) | Seragam |
| **Panitia Seragam** | Ukuran | ✅ Ya (NEW!) | Seragam |

---

## 🎯 Apa yang Harus Bekerja

**Toggle ON:**
1. ✅ Warna berubah (biru atau hijau)
2. ✅ Text berubah: "Aktif untuk orang ini"
3. ✅ Muncul notifikasi sukses
4. ✅ Refresh halaman → Toggle tetap ON
5. ✅ Database tersimpan (nilai = true)

**Pendaftar Melihat:**
1. ✅ Login sebagai pendaftar yang di-toggle
2. ✅ Buka halaman daftar ulang (Madrasah/Seragam)
3. ✅ "Opsi Lainnya" muncul di dropdown
4. ✅ Bisa pilih dan input nominal custom

**Pendaftar Lain Tidak Melihat:**
1. ✅ Login sebagai pendaftar yang TIDAK di-toggle
2. ✅ Buka halaman yang sama
3. ✅ "Opsi Lainnya" TIDAK muncul
4. ✅ Hanya lihat paket predefined

---

## ⚠️ Jika Ada Masalah

### Toggle tidak menyimpan:
1. Cek apakah migration sudah dijalankan
2. Cek Laravel logs: `storage/logs/laravel.log`
3. Hard refresh browser: `Ctrl + Shift + F5`

### "Opsi Lainnya" tidak muncul di Pendaftar:
1. Pastikan toggle sudah ON (warna biru/hijau)
2. Hard refresh browser pendaftar: `Ctrl + Shift + F5`
3. Cek database: Nilai `opsi_lainnya_dropdown_individual` harus `true`
4. Clear cache Laravel:
   ```bash
   php artisan optimize:clear
   ```

### Error saat klik toggle:
1. Periksa error di browser console (F12)
2. Periksa Laravel logs
3. Pastikan migration sudah run (kolom database ada)

---

## 📝 Perbedaan dengan Admin

**Sama:**
- 2 toggle per pendaftar
- Toggle biru (dropdown) dan hijau (konfirmasi)
- Null-safe (tidak error kalau migration belum run)
- Notifikasi sukses
- State persist setelah refresh

**Beda:**
- **Admin:** Punya tab Madrasah & Seragam
- **Panitia Daftar Ulang:** Hanya tab Madrasah
- **Panitia Seragam:** Punya tab Seragam & Ukuran

---

## 🚀 Siap Upload ke Production

**Files yang diubah (4 files):**
1. `app/Livewire/Panitia/DaftarUlang.php`
2. `resources/views/livewire/panitia/daftar-ulang.blade.php`
3. `app/Livewire/PanitiaSeragam/DaftarUlang.php`
4. `resources/views/livewire/panitia-seragam/daftar-ulang.blade.php`

**Cache:** Sudah di-clear ✅

**Test:** Siap ditest di local sekarang!

---

## ✅ Checklist Test

- [ ] Login sebagai Panitia Daftar Ulang
- [ ] Cek kolom "Aksi Opsi" muncul di tab Madrasah
- [ ] Test toggle biru dan hijau
- [ ] Refresh halaman, toggle tetap ON
- [ ] Login sebagai pendaftar yang di-toggle
- [ ] Cek "Opsi Lainnya" muncul di dropdown
- [ ] Login sebagai Panitia Seragam
- [ ] Cek kolom "Aksi Opsi" muncul di tab Seragam
- [ ] Test toggle di tab Seragam
- [ ] Pindah ke tab Ukuran
- [ ] Cek kolom "Aksi Opsi" masih muncul
- [ ] Test toggle di tab Ukuran
- [ ] Login sebagai pendaftar yang di-toggle (tab Seragam)
- [ ] Cek "Opsi Lainnya" muncul di dropdown seragam

**Jika semua checklist ✅ → Siap upload!**

---

**Date:** 9 Feb 2026
**Status:** Complete & Ready to Test
**Feature:** Kolom Aksi Opsi untuk Panitia Daftar Ulang & Panitia Seragam

