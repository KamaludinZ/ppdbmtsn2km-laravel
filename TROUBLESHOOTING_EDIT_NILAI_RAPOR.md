# Troubleshooting Edit Nilai Rapor Production Error

## Status Masalah

### ❌ Error 1: Panitia - Error 500 saat klik "Edit Data"
**Error Log:**
```
Public method [toggleEdit] not found on component
```

**Penyebab:**  
File `app/Livewire/Panitia/DetailPendaftar.php` di production **BELUM** memiliki method `toggleEdit()`

**File Lokal (BENAR):**
- ✅ Line 307: `public function toggleEdit($section = null)`
- ✅ Line 875: `public function saveNilaiRapor()`
- ✅ Total: 929 baris

**File Production (SALAH/LAMA):**
- ❌ Method `toggleEdit()` tidak ada
- ❌ File belum terupdate

---

### ❌ Error 2: Admin - Button "Simpan" tidak ada respon (tanpa error console)
**Penyebab:**  
File di production kemungkinan:
1. Belum terupdate dengan method `saveNilaiRapor()` yang benar
2. Cache belum di-clear dengan sempurna
3. Session/view cache masih menyimpan versi lama

**File Lokal (BENAR):**
- ✅ Line 2520: `public function saveNilaiRapor()`
- ✅ Line 795: `public function toggleEdit($section = null)`
- ✅ Total: 2577 baris
- ✅ Modal dengan `wire:submit.prevent="saveNilaiRapor"` (line 1016)
- ✅ Submit button `type="submit"` (line 1164)

---

## Solusi Upload

### 1. Jalankan Script Upload Baru
```cmd
cd C:\ppdbmtsn2km-laravel
upload_fix_edit_nilai_rapor.bat
```

Script ini akan:
1. ✅ Upload 4 file (2 controllers + 2 views)
2. ✅ Verifikasi file terupload dengan benar
3. ✅ Clear SEMUA cache (cache, config, route, view, optimize)

### 2. Verifikasi Manual di Production (Opsional)
Jika masih error, jalankan command ini via SSH:

```bash
# Cek jumlah baris file
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
wc -l app/Livewire/Panitia/DetailPendaftar.php
wc -l app/Livewire/Admin/DetailPendaftar.php

# Harus menunjukkan:
# 929 app/Livewire/Panitia/DetailPendaftar.php
# 2577 app/Livewire/Admin/DetailPendaftar.php

# Cek method toggleEdit di Panitia
grep -n "public function toggleEdit" app/Livewire/Panitia/DetailPendaftar.php
# Harus ada output: 307:    public function toggleEdit($section = null)

# Cek method saveNilaiRapor
grep -n "public function saveNilaiRapor" app/Livewire/Panitia/DetailPendaftar.php
grep -n "public function saveNilaiRapor" app/Livewire/Admin/DetailPendaftar.php
```

### 3. Clear Cache Production (Manual)
```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
php artisan optimize
```

### 4. Restart Services (Jika Diperlukan)
Jika masih belum work, restart PHP-FPM atau Apache:
```bash
# Untuk PHP-FPM
sudo systemctl restart php8.2-fpm

# Atau untuk Apache
sudo systemctl restart apache2
```

---

## Checklist Setelah Upload

### ✅ Test di Panitia
1. Login sebagai panitia
2. Buka `/panitia/pendaftar/[id]`
3. **Tidak boleh ada error 500** ✓
4. Klik tombol "Edit Data" → Harus berfungsi
5. Tab "Verifikasi Dokumen" → Scroll ke "Nilai Rapor yang Diinputkan"
6. Klik tombol "Edit Nilai" → Modal muncul
7. Edit nilai → Klik "Simpan Perubahan" → **Harus muncul notifikasi sukses**
8. Data tersimpan dan halaman terupdate

### ✅ Test di Admin
1. Login sebagai admin
2. Buka `/admin/pendaftar/[id]`
3. Tab "Verifikasi Dokumen" → Scroll ke "Nilai Rapor yang Diinputkan"
4. Klik tombol "Edit Nilai" → Modal muncul
5. Edit nilai → Klik "Simpan Perubahan" → **Harus muncul notifikasi sukses**
6. Data tersimpan dan halaman terupdate
7. Cek database → Nilai harus berubah

---

## File yang Diupload

| File | Lokasi | Perubahan |
|------|--------|-----------|
| Panitia Controller | `app/Livewire/Panitia/DetailPendaftar.php` | + method `toggleEdit()` (line 307)<br>+ method `saveNilaiRapor()` (line 875) |
| Admin Controller | `app/Livewire/Admin/DetailPendaftar.php` | + method `toggleEdit()` (line 795)<br>+ method `saveNilaiRapor()` (line 2520) |
| Panitia View | `resources/views/livewire/panitia/detail-pendaftar.blade.php` | + Tombol "Edit Nilai"<br>+ Modal 3 kolom dengan rata-rata |
| Admin View | `resources/views/livewire/admin/detail-pendaftar.blade.php` | + Tombol "Edit Nilai"<br>+ Modal 3 kolom dengan rata-rata |

---

## Debugging Jika Masih Error

### Jika Panitia masih error 500:
1. Cek log production:
   ```bash
   ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && tail -50 storage/logs/laravel.log | grep toggleEdit"
   ```
2. Pastikan file terupload:
   ```bash
   ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && ls -lh app/Livewire/Panitia/DetailPendaftar.php"
   ```
3. Cek timestamp file (harus baru):
   ```bash
   ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && stat app/Livewire/Panitia/DetailPendaftar.php | grep Modify"
   ```

### Jika Admin tidak bisa simpan:
1. Buka browser Console (F12) → Network tab
2. Klik "Simpan Perubahan"
3. Lihat request ke `/livewire/update`
4. Cek response error (jika ada)
5. Screenshot dan share error message

---

## Catatan Penting

⚠️ **Upload file dalam urutan yang benar:**
1. Controller files dulu (Panitia & Admin)
2. View files (Panitia & Admin)
3. Clear cache terakhir

⚠️ **Jangan lupa clear cache setelah upload:**
- Cache application
- Config cache
- Route cache  
- View cache
- Optimize cache

⚠️ **Test dengan user berbeda:**
- Test dengan akun panitia yang berbeda
- Test dengan akun admin yang berbeda
- Pastikan tidak ada cached session issue

---

Tanggal: 3 Februari 2026 - 05:30 WIB
