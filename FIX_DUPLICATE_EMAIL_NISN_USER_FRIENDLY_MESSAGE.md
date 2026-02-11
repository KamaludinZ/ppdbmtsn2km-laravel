# FIX: Pesan User-Friendly untuk Email dan NISN Duplikat

**Tanggal:** 10 Februari 2026
**Status:** ✅ FIXED
**Priority:** HIGH - UX Issue

---

## 🐛 MASALAH

Di halaman `/daftar`, ketika user mencoba mendaftar dengan **email** atau **NISN** yang sudah terdaftar, muncul pesan error teknis yang membingungkan:

### Error Sebelumnya:
```
Pendaftaran gagal: SQLSTATE[23505]: Unique violation: 7 ERROR:
duplicate key value violates unique constraint "users_email_unique"
```

```
Registration failed {"error":"SQLSTATE[23505]: Unique violation: 7 ERROR:
duplicate key value violates unique constraint "pendaftar_nisn_unique"
```

❌ **Dampak:**
- User bingung dengan pesan error teknis
- UX yang buruk
- User tidak tahu apa yang harus dilakukan

---

## ✅ SOLUSI

### 1. **Perbaikan Error Handling di Register.php**

File: `app/Livewire/Auth/Register.php` (baris 530-576)

**Perubahan:**
- Menambahkan deteksi yang lebih **robust** untuk berbagai format error message
- Mengecek variasi constraint name: `pendaftar_nisn_unique`, `pendaftars_nisn`, dll
- Menambahkan `$this->addError()` untuk highlight field yang bermasalah
- Pesan error lebih **user-friendly** dan actionable

### 2. **Pesan Error Baru**

#### Untuk Email Duplikat:
```
Email ini sudah terdaftar di sistem kami. Silahkan gunakan email lain atau
login jika Anda sudah memiliki akun. Jika lupa password, gunakan fitur reset password.
```
✅ **Field email** juga akan di-highlight merah dengan pesan inline

#### Untuk NISN Duplikat:
```
NISN ini sudah terdaftar di sistem kami. Silahkan cek email atau WhatsApp Anda
untuk informasi login. Jika lupa password, gunakan fitur reset password atau hubungi panitia.
```
✅ **Field NISN** juga akan di-highlight merah dengan pesan inline

---

## 📝 PERUBAHAN KODE

### Before:
```php
// Hanya mengecek satu constraint name
if (str_contains($e->getMessage(), 'users_email_unique')) {
    // ...
}
elseif (str_contains($e->getMessage(), 'pendaftars_nisn')) {
    // ❌ Nama constraint salah!
}
```

### After:
```php
$errorMessage = $e->getMessage();

// Cek berbagai variasi untuk email
if (str_contains($errorMessage, 'users_email_unique') ||
    str_contains($errorMessage, 'duplicate key') && str_contains($errorMessage, 'email')) {

    session()->flash('error', 'Email ini sudah terdaftar...');
    $this->addError('email', 'Email ini sudah terdaftar. Silahkan gunakan email lain.');
}
// Cek berbagai variasi untuk NISN
elseif (str_contains($errorMessage, 'pendaftar_nisn_unique') ||
        str_contains($errorMessage, 'pendaftars_nisn') ||
        (str_contains($errorMessage, 'duplicate key') && str_contains($errorMessage, 'nisn'))) {

    session()->flash('error', 'NISN ini sudah terdaftar...');
    $this->addError('nisn', 'NISN ini sudah terdaftar. Silahkan hubungi panitia jika ada masalah.');
}
```

---

## 🚀 CARA DEPLOY

### 1. Upload ke Production
```bash
# Jalankan bat file
upload_fix_duplicate_email_nisn_friendly_message.bat

# Atau manual via SCP
scp app/Livewire/Auth/Register.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Auth/
```

### 2. Clear Cache di Production
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan optimize:clear
```

---

## ✅ TESTING CHECKLIST

### Test Case 1: Email Duplikat
- [ ] 1. Buka halaman `/daftar`
- [ ] 2. Isi semua data dengan benar
- [ ] 3. Gunakan **email yang sudah terdaftar**
- [ ] 4. Klik "Daftar Sekarang"
- [ ] 5. **Expected:** Muncul pesan user-friendly:
  ```
  Email ini sudah terdaftar di sistem kami. Silahkan gunakan email lain
  atau login jika Anda sudah memiliki akun. Jika lupa password, gunakan fitur reset password.
  ```
- [ ] 6. **Expected:** Field email di-highlight merah
- [ ] 7. **Expected:** TIDAK ada pesan error teknis "SQLSTATE[23505]"

### Test Case 2: NISN Duplikat
- [ ] 1. Buka halaman `/daftar`
- [ ] 2. Isi semua data dengan benar
- [ ] 3. Gunakan **NISN yang sudah terdaftar**
- [ ] 4. Klik "Daftar Sekarang"
- [ ] 5. **Expected:** Muncul pesan user-friendly:
  ```
  NISN ini sudah terdaftar di sistem kami. Silahkan cek email atau WhatsApp Anda
  untuk informasi login. Jika lupa password, gunakan fitur reset password atau hubungi panitia.
  ```
- [ ] 6. **Expected:** Field NISN di-highlight merah
- [ ] 7. **Expected:** TIDAK ada pesan error teknis "SQLSTATE[23505]"

### Test Case 3: Data Valid (Normal Registration)
- [ ] 1. Daftar dengan email dan NISN yang **belum terdaftar**
- [ ] 2. **Expected:** Pendaftaran berhasil
- [ ] 3. **Expected:** Redirect ke `/login`
- [ ] 4. **Expected:** Pesan sukses muncul

---

## 📊 DAMPAK PERBAIKAN

### UX Improvement:
✅ User mendapat pesan error yang jelas dan mudah dipahami
✅ User tahu apa yang harus dilakukan (gunakan email lain, reset password, dll)
✅ Field yang bermasalah di-highlight
✅ Tidak ada lagi pesan error teknis yang membingungkan

### Technical Improvement:
✅ Error handling lebih robust dengan multiple condition checks
✅ Logging lebih baik untuk debugging
✅ Consistent error message format

---

## 📌 FILES CHANGED

```
app/Livewire/Auth/Register.php (baris 530-576)
```

---

## 🔗 RELATED ISSUES

- Session flash message sudah ada di blade: `resources/views/livewire/auth/register.blade.php` (baris 83-92)
- Validation error inline sudah ada di blade: `@error('email')`, `@error('nisn')`

---

## 💡 NOTES

1. **PostgreSQL Constraint Names:**
   - Email: `users_email_unique`
   - NISN: `pendaftar_nisn_unique` atau `pendaftars_nisn`

2. **Why Multiple Checks?**
   - Different PostgreSQL versions might format error messages differently
   - Migration scripts might create constraints with different names
   - Better to be safe and check multiple variations

3. **User Journey:**
   - Jika email/NISN duplikat → User diminta gunakan email lain atau reset password
   - Jika lupa akun → User bisa contact panitia
   - Clear next steps untuk user

---

**Author:** Claude Code
**Last Updated:** 10 Februari 2026
