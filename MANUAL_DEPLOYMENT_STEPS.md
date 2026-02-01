# Manual Deployment Steps - Role Fix

**Date**: 30 Januari 2026
**Issue**: Fix CREATE and EDIT role issues
**Method**: Manual step-by-step

---

## 📋 Step-by-Step Manual Deployment

### Step 1: Upload File Panitia.php

Buka **Command Prompt** atau **PowerShell**, lalu jalankan:

```bash
scp app\Livewire\Admin\Panitia.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/Panitia.php
```

**Expected Output**:
```
Panitia.php                           100%   6KB   150.2KB/s   00:00
```

---

### Step 2: Upload Script Fix (Optional tapi Recommended)

```bash
scp fix_wrong_role_panitia.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/
```

```bash
scp check_hidden_panitia.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/
```

---

### Step 3: Clear Cache di Production

```bash
ssh mtsnkot4@103.30.244.77
```

Setelah masuk ke server, jalankan:

```bash
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

**Expected Output** untuk setiap command:
```
INFO  Configuration cache cleared successfully.
INFO  Application cache cleared successfully.
INFO  Compiled views cleared successfully.
```

---

### Step 4: Fix Data yang Salah (Masih di Server)

Jalankan script fix:

```bash
php fix_wrong_role_panitia.php
```

**Interactive Prompts**:

Script akan tampilkan semua user dengan role salah, lalu tanya:

```
Options:
1. Convert to panitia_pendaftaran (default panitia role)
2. Convert to panitia_daftarulang
3. Convert to panitia_tes_psikologi
4. Convert to panitia_seragam
5. Convert to admin
6. Skip - do nothing

Enter option (1-6) [default: 1]:
```

**Ketik**: `1` lalu Enter (untuk panitia_pendaftaran)

Atau pilih nomor lain sesuai role yang seharusnya.

Script akan fix semua data dan tampilkan konfirmasi.

---

### Step 5: Verify Fix di Server (Optional)

Check apakah data sudah benar:

```bash
php check_hidden_panitia.php
```

Atau via tinker:

```bash
php artisan tinker
```

Di tinker, jalankan:

```php
User::whereIn('role', ['panitia_pendaftaran', 'panitia_daftarulang', 'panitia_tes_psikologi', 'panitia_seragam'])->get(['id', 'name', 'email', 'role']);
```

Ketik `exit` untuk keluar dari tinker.

---

### Step 6: Keluar dari Server

```bash
exit
```

---

## 🌐 Test di Browser

### Test 1: Lihat Daftar Panitia

1. Buka browser: `https://pmbm.mtsn2kotamalang.sch.id/admin/panitia`
2. Clear all filters (pilih "Semua Role")
3. Semua panitia seharusnya muncul dengan role yang benar ✅

### Test 2: Tambah Panitia Baru

1. Click "Tambah Panitia Baru"
2. Fill form:
   - Name: `Test Role Panitia Pendaftaran`
   - Email: `test1@example.com`
   - Password: `password123`
   - Role: **Panitia Pendaftaran**
3. Click "Simpan"
4. **Verify**: Data muncul di list ✅
5. **Check di phpPgAdmin**: Role = `panitia_pendaftaran` ✅

### Test 3: Tambah dengan Role Berbeda

Ulangi Test 2 dengan role lain:

- Test dengan role: **Panitia Daftar Ulang**
  - Check DB: `role = 'panitia_daftarulang'` ✅

- Test dengan role: **Panitia Tes Psikologi**
  - Check DB: `role = 'panitia_tes_psikologi'` ✅

- Test dengan role: **Panitia Seragam**
  - Check DB: `role = 'panitia_seragam'` ✅

### Test 4: Edit/Ubah Role

1. Click "Edit" pada salah satu panitia
2. Ubah role dari "Panitia Pendaftaran" ke "Panitia Daftar Ulang"
3. Click "Simpan"
4. **Verify**: Role berubah di list ✅
5. **Check di phpPgAdmin**: Role = `panitia_daftarulang` ✅

---

## 🔍 Verification di phpPgAdmin

### Check Data Panitia:

1. Login ke phpPgAdmin
2. Select database: `mtsnkot4_dbpmbm`
3. Browse table: `users`
4. Run SQL query:

```sql
SELECT id, name, email, role, aktif, created_at
FROM users
WHERE role IN ('admin', 'panitia_pendaftaran', 'panitia_daftarulang', 'panitia_tes_psikologi', 'panitia_seragam')
ORDER BY created_at DESC;
```

**Expected**:
- Semua panitia dengan role yang benar
- Tidak ada role 'pendaftar' untuk panitia

### Check Tidak Ada Data Salah:

```sql
SELECT u.id, u.name, u.email, u.role
FROM users u
LEFT JOIN pendaftars p ON p.user_id = u.id
WHERE u.role = 'pendaftar' AND p.id IS NULL;
```

**Expected**: 0 rows (tidak ada data salah)

---

## 📝 Summary Commands

**Quick Copy-Paste** (jalankan satu per satu):

```bash
# 1. Upload file
scp app\Livewire\Admin\Panitia.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/Panitia.php

# 2. Upload script fix
scp fix_wrong_role_panitia.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/

# 3. SSH ke server
ssh mtsnkot4@103.30.244.77

# 4. (Di server) Masuk ke folder aplikasi
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# 5. (Di server) Clear cache
php artisan config:clear && php artisan cache:clear && php artisan view:clear

# 6. (Di server) Fix data yang salah
php fix_wrong_role_panitia.php

# 7. (Di server) Keluar
exit
```

---

**Date**: 30 Januari 2026
**Status**: Manual deployment ready
**Next**: Follow steps above
