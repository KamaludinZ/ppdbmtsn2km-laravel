# Quick Debug Guide - Modal Tidak Muncul di Production

## 🚨 Quick Steps

### 1. Upload Files
```bash
upload_fix_modal_livewire.bat
```

### 2. Buka Console Browser (F12)
URL: https://pmbm.mtsn2kotamalang.sch.id/daftar

### 3. Cek Console Logs
**Saat halaman load, harus muncul:**
```
✅ Page loaded
✅ Livewire available: true
✅ Alpine available: true
✅ Button found: <button>
```

**Saat klik tombol "Daftar Sekarang":**
```
✅ handleConfirmSubmit called
✅ Livewire loaded, attempting to call confirmSubmit
✅ Button clicked (via addEventListener)
```

### 4. Cek Server Logs
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
tail -100 storage/logs/laravel.log | grep -i confirmsubmit
```

**Expected:**
```
✅ confirmSubmit called {"nisn":"...","nama_lengkap":"..."}
✅ Modal shown successfully
```

## 🔧 Troubleshooting

### ❌ Problem: "Livewire not loaded" di console
**Cause**: Livewire scripts tidak loaded

**Quick Fix:**
```bash
# Cek apakah @livewireScripts ada di layout
grep -r "livewireScripts" resources/views/components/layouts/

# Pastikan ada di public.blade.php
```

### ❌ Problem: Console error "u is not a function"
**Cause**: Alpine.js error

**Quick Fix**: Bisa diabaikan jika modal sudah muncul. Jika modal tidak muncul, coba:
```bash
# Restart Livewire
php artisan livewire:discover
php artisan view:clear
```

### ❌ Problem: No server logs
**Cause**: Request tidak sampai ke server

**Quick Fix:**
```bash
# 1. Cek CSRF token
tail storage/logs/laravel.log | grep -i csrf

# 2. Cek route
php artisan route:list | grep livewire

# 3. Test Livewire connection
# Buka console browser dan run:
window.Livewire
# Harus return object, bukan undefined
```

## ⚡ Alternative Solutions (Jika Masih Gagal)

### Option A: Simplify Modal
Edit `app/Livewire/Auth/Register.php`:
```php
public function showModal()
{
    $this->showConfirmationModal = true;
}
```

Edit button di view:
```blade
<button wire:click="showModal">Daftar Sekarang</button>
```

### Option B: JavaScript Confirm
```blade
<button onclick="if(confirm('Yakin ingin mendaftar?')) @this.call('register')">
    Daftar Sekarang
</button>
```

### Option C: Direct Submit
```blade
<button wire:click="register">Daftar Sekarang</button>
```

## 📞 Contact for Help

Jika semua solusi gagal, kumpulkan informasi berikut:

1. **Console logs** (screenshot)
2. **Server logs**:
   ```bash
   tail -200 storage/logs/laravel.log > debug_logs.txt
   ```
3. **Network tab** (F12 → Network → XHR → Copy as cURL)
4. **Browser info** (Chrome/Firefox/Safari, version)

## ✅ Success Checklist

- [ ] Files uploaded
- [ ] Cache cleared
- [ ] Console logs muncul tanpa error
- [ ] Server logs tercatat
- [ ] Modal muncul saat klik tombol
- [ ] Proses pendaftaran berjalan normal

---
**Last Updated**: 1 Februari 2026
