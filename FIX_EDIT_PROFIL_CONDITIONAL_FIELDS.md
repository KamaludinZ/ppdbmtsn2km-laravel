# Perbaikan Edit Profil - Conditional Fields per Role

## 📋 Ringkasan Perubahan

Field "No. WhatsApp Orang Tua/Wali" sekarang **hanya tampil untuk role Pendaftar**, tidak untuk Admin dan Panitia.

## ✅ File yang Diubah

### 1. **View** - `resources/views/livewire/edit-profil.blade.php`

#### Before:
```blade
<!-- Nomor WhatsApp Orang Tua/Wali -->
<div>
    <label>No. WhatsApp Orang Tua/Wali</label>
    <input wire:model="telepon_ortu" ... />
</div>
```

#### After:
```blade
<!-- Nomor WhatsApp Orang Tua/Wali - Hanya untuk Pendaftar -->
@if(Auth::user()->role === 'pendaftar')
    <div>
        <label>No. WhatsApp Orang Tua/Wali</label>
        <input wire:model="telepon_ortu" ... />
    </div>
@endif
```

### 2. **Controller** - `app/Livewire/EditProfil.php`

#### A. Conditional Validation Rules
```php
protected function rules()
{
    $user = Auth::user();
    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . Auth::id(),
        'current_password' => 'nullable|required_with:new_password|string',
        'new_password' => 'nullable|required_with:current_password|string|min:6|confirmed',
    ];

    // ✅ Hanya validasi telepon_ortu untuk role pendaftar
    if ($user->role === 'pendaftar') {
        $rules['telepon_ortu'] = 'nullable|string|max:20|regex:/^62[0-9]{9,13}$/';
    }

    return $rules;
}
```

#### B. Conditional Mount/Load
```php
public function mount()
{
    $user = Auth::user();
    $this->name = $user->name;
    $this->email = $user->email;

    // ✅ Hanya load telepon_ortu untuk role pendaftar
    if ($user->role === 'pendaftar' && $user->pendaftar) {
        $this->telepon_ortu = $user->pendaftar->telepon_ortu ?? null;
    }
}

public function openModal()
{
    $this->showModal = true;
    $user = Auth::user();
    $this->name = $user->name;
    $this->email = $user->email;

    // ✅ Hanya load telepon_ortu untuk role pendaftar
    if ($user->role === 'pendaftar' && $user->pendaftar) {
        $this->telepon_ortu = $user->pendaftar->telepon_ortu ?? null;
    }

    $this->resetPasswordFields();
}
```

#### C. Conditional Save
```php
public function updateProfil()
{
    $this->validate();
    $user = Auth::user();

    // ... validasi password ...

    $user->name = $this->name;

    // Update email hanya jika admin
    if ($user->role === 'admin') {
        $user->email = $this->email;
    }

    // ✅ Update nomor WhatsApp - hanya untuk role pendaftar
    if ($user->role === 'pendaftar' && $user->pendaftar) {
        $user->pendaftar->telepon_ortu = $this->telepon_ortu;
        $user->pendaftar->save();
    }

    // ... update password ...

    $user->save();
    session()->flash('message', 'Profil berhasil diperbarui.');
}
```

## 📊 Field yang Tampil per Role

| Field | Admin | Panitia | Pendaftar |
|-------|-------|---------|-----------|
| Nama Lengkap | ✅ | ✅ | ✅ |
| Email | ✅ Editable | ❌ Disabled | ❌ Disabled |
| No. WhatsApp Orang Tua/Wali | ❌ Hidden | ❌ Hidden | ✅ Show |
| Password Lama | ✅ | ✅ | ✅ |
| Password Baru | ✅ | ✅ | ✅ |
| Konfirmasi Password | ✅ | ✅ | ✅ |

## 🧪 Testing Checklist

### Test sebagai **ADMIN**
- [ ] Buka Edit Profil
- [ ] ✅ Field "Nama Lengkap" tampil
- [ ] ✅ Field "Email" tampil dan **dapat diedit**
- [ ] ❌ Field "No. WhatsApp" **TIDAK tampil**
- [ ] ✅ Field "Password" tampil
- [ ] Ubah nama → Simpan → Berhasil
- [ ] Ubah email → Simpan → Berhasil
- [ ] Ubah password → Simpan → Berhasil

### Test sebagai **PANITIA** (Pendaftaran atau Daftar Ulang)
- [ ] Buka Edit Profil
- [ ] ✅ Field "Nama Lengkap" tampil
- [ ] ✅ Field "Email" tampil tapi **disabled** (tidak bisa edit)
- [ ] ❌ Field "No. WhatsApp" **TIDAK tampil**
- [ ] ✅ Field "Password" tampil
- [ ] Ubah nama → Simpan → Berhasil
- [ ] Coba ubah email → **Tidak bisa** (field disabled)
- [ ] Ubah password → Simpan → Berhasil

### Test sebagai **PENDAFTAR**
- [ ] Buka Edit Profil
- [ ] ✅ Field "Nama Lengkap" tampil
- [ ] ✅ Field "Email" tampil tapi **disabled** (tidak bisa edit)
- [ ] ✅ Field "No. WhatsApp Orang Tua/Wali" **TAMPIL**
- [ ] ✅ Field "Password" tampil
- [ ] Ubah nama → Simpan → Berhasil
- [ ] Ubah nomor WhatsApp (08xxx → auto jadi 62xxx) → Simpan → Berhasil
- [ ] Ubah password → Simpan → Berhasil

## 🔍 Verifikasi di Browser Console

### Test Field Visibility
```javascript
// Login sebagai Admin atau Panitia
// Buka Edit Profil, lalu test di console:

// Field WhatsApp seharusnya TIDAK ada
document.getElementById('telepon_edit')
// Expected untuk Admin/Panitia: null

// Login sebagai Pendaftar
// Buka Edit Profil, lalu test di console:

// Field WhatsApp seharusnya ADA
document.getElementById('telepon_edit')
// Expected untuk Pendaftar: <input id="telepon_edit" ...>
```

### Test Validation
```javascript
// Login sebagai Pendaftar
// Isi nomor WhatsApp dengan format salah: "12345"
// Submit form

// Expected: Error validation muncul
// "Nomor WhatsApp harus dalam format 62xxx"

// Login sebagai Admin/Panitia
// Field WhatsApp tidak ada, jadi tidak ada validation error
```

## 🚀 Deployment

### 1. Upload File yang Diubah
```
resources/views/livewire/edit-profil.blade.php
app/Livewire/EditProfil.php
```

### 2. Clear Cache di Server
```bash
php artisan view:clear
php artisan cache:clear
php artisan config:clear
```

### 3. Test di Production
```
1. Login sebagai Admin → Test edit profil
2. Login sebagai Panitia → Test edit profil
3. Login sebagai Pendaftar → Test edit profil
```

## 💡 Keuntungan Perubahan Ini

### 1. **UX yang Lebih Baik**
   - Admin dan Panitia tidak bingung dengan field yang tidak relevan
   - Form lebih clean dan fokus

### 2. **Data Integrity**
   - Hanya pendaftar yang punya data telepon_ortu di tabel pendaftar
   - Admin dan Panitia tidak punya relasi ke tabel pendaftar

### 3. **Validation yang Tepat**
   - Validation rules hanya dijalankan untuk role yang relevan
   - Menghindari error saat save untuk Admin/Panitia

### 4. **Performance**
   - Tidak load data yang tidak diperlukan
   - Query lebih efisien

## 🐛 Troubleshooting

### Field WhatsApp Masih Tampil untuk Admin/Panitia

**Solusi:**
```bash
# Clear view cache
php artisan view:clear

# Clear browser cache
Ctrl + Shift + R (hard reload)
```

### Error saat Save untuk Admin/Panitia

**Check:**
1. Pastikan conditional `if ($user->role === 'pendaftar')` ada di `updateProfil()`
2. Clear cache: `php artisan cache:clear`
3. Periksa browser console untuk error

### Nomor WhatsApp Tidak Tersimpan untuk Pendaftar

**Check:**
```php
// Di EditProfil.php, pastikan ada:
if ($user->role === 'pendaftar' && $user->pendaftar) {
    $user->pendaftar->telepon_ortu = $this->telepon_ortu;
    $user->pendaftar->save();
}
```

## 📝 Logic Flow

```
┌─────────────────────────────────────────┐
│         User Klik "Edit Profil"         │
└────────────────┬────────────────────────┘
                 │
                 v
         ┌───────────────┐
         │  openModal()  │
         └───────┬───────┘
                 │
      ┌──────────┴──────────┐
      │   Load User Data    │
      └──────────┬──────────┘
                 │
         ┌───────v────────┐
         │ Check Role     │
         └───────┬────────┘
                 │
    ┌────────────┼────────────┐
    │            │            │
    v            v            v
┌───────┐  ┌─────────┐  ┌──────────┐
│ Admin │  │ Panitia │  │Pendaftar │
└───┬───┘  └────┬────┘  └─────┬────┘
    │           │             │
    v           v             v
Show:       Show:         Show:
- Nama      - Nama        - Nama
- Email*    - Email       - Email
- Password  - Password    - WhatsApp
                          - Password

* Editable hanya untuk Admin
```

## ✨ Future Improvements

### 1. **Role-Specific Fields**
Tambah field khusus untuk setiap role:
- Admin: Tambah field "Department"
- Panitia: Tambah field "Tugas/Divisi"
- Pendaftar: Sudah ada WhatsApp

### 2. **Profile Picture Upload**
Tambah upload foto profil untuk semua role

### 3. **Two-Factor Authentication**
Tambah opsi 2FA khusus untuk Admin

## 📞 Support

Jika ada masalah:
1. Check dokumentasi ini
2. Clear all caches
3. Test di browser incognito
4. Periksa browser console untuk error

---

**Status:** ✅ **READY FOR PRODUCTION**
**Last Updated:** 21 Januari 2026
**Changed Files:** 2 files (View + Controller)
