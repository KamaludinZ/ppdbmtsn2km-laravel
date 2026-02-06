# Quick Fix: 404 Bukti Tes Psikologi

**Status:** ✅ FIXED

---

## 🔴 Masalah

URL bukti tes psikologi error 404 saat diklik di admin/panitia:
```
https://pmbm.mtsn2kotamalang.sch.id/storage/bukti_bayar/27/bukti_psikologi-JPAK-26020006-Muhammad_Sadira_Sukma_Nugroho-20260202.jpg
```

---

## ✅ Solusi

File tetap disimpan di **private storage** untuk keamanan, tapi diakses via **secure route** dengan authorization check.

---

## 🚀 Upload ke Production

### Quick Command:

```bash
upload_fix_404_bukti_psikologi.bat
```

### Manual Upload:

```bash
scp app/Http/Controllers/SecureFileController.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Http/Controllers/
scp routes/web.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/routes/
scp resources/views/livewire/admin/partials/tes-psikologi-data.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/
scp resources/views/livewire/pendaftar/tes-psikologi.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/

ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

---

## 🧪 Test

1. **Admin:** Buka Tes Psikologi → Klik "Lihat" → Harus muncul gambar ✅
2. **Panitia:** Buka Tes Psikologi → Klik "Lihat" → Harus muncul gambar ✅
3. **Pendaftar:** Buka Tes Psikologi → Gambar muncul di halaman ✅

---

## 📊 What Changed

| Before | After |
|--------|-------|
| URL: `/storage/bukti_bayar/27/...` | URL: `/view/bukti-bayar/27/psikologi` |
| Error: 404 Not Found | Success: 200 OK |
| File di private, tapi tidak bisa diakses | File di private, diakses via secure route |
| Tidak ada authorization | Authorization check ✅ |

---

## 🔒 Security

- ✅ Admin/Panitia: Bisa akses semua file
- ✅ Pendaftar: Hanya bisa akses file mereka sendiri
- ✅ Guest: Redirect ke login
- ✅ File tetap aman di private storage

---

**Dokumentasi Lengkap:** `FIX_404_BUKTI_PSIKOLOGI_COMPLETE.md`
