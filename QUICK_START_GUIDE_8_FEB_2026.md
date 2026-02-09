# Quick Start Guide - Upload Seragam Changes

**Tanggal:** 8 Februari 2026
**Status:** ✅ Siap Upload ke Production

---

## 🎯 Yang Berubah (4 Fitur)

1. ✅ **Redesign Paket Seragam** - Dependent dropdown (Paket → Opsi)
2. ✅ **Hapus Duplikat Section** - Removed old "Paket Seragam" di atas Step 1
3. ✅ **Perbaiki Upload Bukti** - UI lebih jelas (sama dengan Madrasah)
4. ✅ **Tambah Warning Boxes** - Green (disetujui) & Yellow (menunggu)

---

## 🚀 Cara Upload (3 Langkah)

### Langkah 1: Upload Files
```batch
# Jalankan di Windows (local)
upload_redesign_paket_seragam_8_feb_2026.bat
```

**File yang akan diupload:**
- Database migration (opsi_seragam column)
- Model Seragam
- Livewire Admin & Pendaftar
- Views Admin & Pendaftar

**Waktu upload:** ~2-3 menit

---

### Langkah 2: SSH & Run Migration
```bash
# SSH ke server
ssh mtsnkot4@103.30.244.77

# Masuk ke directory
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Run migration
php artisan migrate

# Clear cache
php artisan optimize:clear
```

**Waktu proses:** ~1 menit

---

### Langkah 3: Testing
```
# Admin
https://pmbm.mtsn2kotamalang.sch.id/admin/daftar-ulang?activeTab=settings
→ Buat 1 paket dengan 2-3 opsi
→ Simpan dan reload
→ Verify data tersimpan

# Pendaftar
https://pmbm.mtsn2kotamalang.sch.id/pendaftar/daftarulang?activeTab=seragam
→ Pilih paket → opsi muncul
→ Pilih opsi → konfirmasi nominal terisi otomatis
→ Upload bukti → lihat tampilan baru
→ Submit dan verify
```

**Waktu testing:** ~5 menit

---

## ✅ Checklist Cepat

### Pre-Upload
- [ ] Backup database: `pg_dump mtsnkot4_ppdb > backup_8_feb_2026.sql`
- [ ] Backup files yang akan diubah

### Upload
- [ ] Run `upload_redesign_paket_seragam_8_feb_2026.bat`
- [ ] Verify semua file berhasil (6/6)

### Post-Upload
- [ ] SSH ke server
- [ ] Run `php artisan migrate` → check "migrated successfully"
- [ ] Run `php artisan optimize:clear`
- [ ] Test Admin: Buat paket baru
- [ ] Test Pendaftar: Pilih paket & submit
- [ ] Verify database: Check `opsi_seragam` column

---

## 🐛 Troubleshooting

### Migration Error
```bash
# Check if column already exists
psql -U mtsnkot4 -d mtsnkot4_ppdb
\d seragam

# If exists, skip migration manually
```

### View Not Updating
```bash
# Clear view cache
php artisan view:clear

# Check file uploaded
ls -la resources/views/livewire/pendaftar/daftar-ulang.blade.php
```

### Upload Failed
```bash
# Manual upload specific file
scp "resources/views/livewire/pendaftar/daftar-ulang.blade.php" \
    mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/
```

---

## 📚 Dokumentasi Lengkap

Jika butuh detail lebih lanjut, buka:

1. **FINAL_SUMMARY_ALL_SERAGAM_CHANGES_8_FEB_2026.md** - Rangkuman semua perubahan
2. **CHECKLIST_UPLOAD_FINAL_8_FEB_2026.md** - Testing checklist lengkap
3. **REDESIGN_PAKET_SERAGAM_DEPENDENT_DROPDOWN_8_FEB_2026.md** - Spesifikasi design
4. **PERBAIKAN_TAMPILAN_SERAGAM_8_FEB_2026.md** - UI improvements
5. **ADD_WARNING_SERAGAM_TAB_8_FEB_2026.md** - Warning boxes

---

## 🎯 Quick Test Flow

### Admin (2 menit)
```
1. Login admin
2. Settings → Daftar Ulang → Tab Settings
3. Scroll ke "Daftar Ulang Seragam"
4. Klik "+ Tambah Paket"
5. Isi: Nama "Paket Test", Deskripsi "Test"
6. Klik "+ Tambah Opsi" di dalam paket
7. Isi: Nama "Putra", Nominal 1500000
8. Klik "+ Tambah Opsi" lagi
9. Isi: Nama "Putri", Nominal 1450000
10. Klik "Simpan Pengaturan"
11. Reload → Verify data masih ada ✅
```

### Pendaftar (3 menit)
```
1. Login pendaftar
2. Daftar Ulang → Tab Seragam
3. Check: TIDAK ada section "Paket Seragam" lama ✅
4. Check: Form mulai dari "Step 1" ✅
5. Pilih paket "Paket Test" → dropdown opsi muncul ✅
6. Pilih opsi "Putri" → konfirmasi nominal = 1.450.000 ✅
7. Pilih opsi pembayaran "Lunas"
8. Pilih bank
9. Isi "Nama Pengirim" → field baru ✅
10. Upload bukti → lihat tampilan baru dengan icon besar ✅
11. Klik "Ganti File" → bisa ganti ✅
12. Klik "Simpan Data Seragam"
13. Reload → Verify data tersimpan ✅
```

### Database (1 menit)
```sql
-- Check migration
SELECT * FROM migrations WHERE migration LIKE '%opsi_seragam%';

-- Check data
SELECT
    paket_seragam,
    opsi_seragam,
    nominal_seragam,
    nama_pengirim_seragam
FROM seragam
WHERE pendaftar_id = (SELECT id FROM users WHERE email = 'test@example.com')
LIMIT 1;

-- Expected: opsi_seragam contains JSON with selected option ✅
```

---

## ⏱️ Estimasi Total Waktu

| Tahap | Waktu |
|-------|-------|
| Upload files | 2-3 menit |
| Run migration & cache clear | 1 menit |
| Testing admin | 2 menit |
| Testing pendaftar | 3 menit |
| Verify database | 1 menit |
| **TOTAL** | **9-10 menit** |

---

## 🎉 Success Indicators

Deployment berhasil jika:
- ✅ Migration ran without errors
- ✅ Admin dapat membuat paket hierarkis
- ✅ Pendaftar melihat dependent dropdown
- ✅ Tidak ada duplikat "Paket Seragam"
- ✅ Upload bukti menampilkan file dengan jelas
- ✅ Warning boxes muncul (jika status disetujui/menunggu)
- ✅ Data tersimpan ke database dengan benar
- ✅ No console errors di browser

---

## 📞 Contact

Jika ada masalah:
1. Check documentation files di folder project
2. Check error logs: `storage/logs/laravel.log`
3. Rollback jika critical: `php artisan migrate:rollback --step=1`

---

**Ready to Deploy!** ✅
**Total Changes:** 4 fitur | 6 files | ~800 lines | 1 new column
**Risk Level:** Low (backward compatible)
**Rollback Available:** Yes (migration can be rolled back)
