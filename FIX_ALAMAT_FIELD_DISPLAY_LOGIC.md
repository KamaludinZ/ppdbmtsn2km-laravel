# FIX ALAMAT FIELD DISPLAY LOGIC - 7 FEB 2026

## 📋 Masalah

**User Report:**
> "di /pendaftar/formulir?activeTab=alamat meskipun Alamat ayah tidak perlu diisi karena status ayah: Meninggal/tidak diketahui sudah benar muncul info seperti itu tapi field isiannya **jangan di sembunyikan tetap munculkan saja tapi tidak wajib di isi** baik di pendaftar, admin, panitia."

**Masalah:**
- ✅ Info box "Alamat tidak perlu diisi" sudah muncul (BENAR)
- ❌ Field alamat TERSEMBUNYI saat status bukan "masih_hidup" (SALAH - seharusnya tetap muncul tapi optional)

**File:** `resources/views/tab_templates/tab-data-alamat.blade.php`

---

## 🎯 Solusi

### **Perubahan Logic:**

**SEBELUM (SALAH):**
```html
<!-- Field TERSEMBUNYI jika status bukan masih_hidup -->
<div x-show="ayah_luar_negeri && ayah_status === 'masih_hidup'" x-transition>
    <label>Status Kepemilikan Rumah <span class="text-red-500">*</span></label>
    <select wire:model="ayah_status_kepemilikan_rumah" required>...</select>
</div>
```

**SESUDAH (BENAR):**
```html
<!-- Info box muncul HANYA jika status bukan masih_hidup -->
<div x-show="ayah_luar_negeri && ayah_status !== 'masih_hidup'" x-transition class="bg-gray-100 rounded-lg p-4 mb-4">
    <p class="text-sm text-gray-600 flex items-center">
        <svg>...</svg>
        Alamat ayah tidak perlu diisi karena status ayah: <strong>{{ status }}</strong>
    </p>
</div>

<!-- Field TETAP MUNCUL, tanda wajib (*) conditional -->
<div x-show="ayah_luar_negeri" x-transition>
    <label>
        Status Kepemilikan Rumah
        <span x-show="ayah_status === 'masih_hidup'" class="text-red-500">*</span>
    </label>
    <select wire:model="ayah_status_kepemilikan_rumah">...</select>  <!-- NO required attribute -->
</div>
```

---

## 📝 Perubahan Detail

### **Section: AYAH KANDUNG**

#### **1. Luar Negeri - Ayah**

**Perubahan:**
- ❌ Hapus: `x-show="ayah_luar_negeri && ayah_status === 'masih_hidup'"`
- ✅ Ubah jadi: `x-show="ayah_luar_negeri"`
- ✅ Pindahkan info box ke ATAS field (sebelum field muncul)
- ✅ Required mark jadi conditional: `<span x-show="ayah_status === 'masih_hidup'" class="text-red-500">*</span>`
- ✅ Hapus attribute `required` dari input/select

**Field yang diubah:**
- Status Kepemilikan Rumah
- Alamat Lengkap

#### **2. Dalam Negeri - Ayah**

**Perubahan yang sama:**
- ❌ Hapus: `x-show="!ayah_luar_negeri && ayah_status === 'masih_hidup'"`
- ✅ Ubah jadi: `x-show="!ayah_luar_negeri"`
- ✅ Pindahkan info box ke ATAS
- ✅ Semua required mark jadi conditional
- ✅ Hapus semua `required` attribute

**Field yang diubah:**
- Status Kepemilikan Rumah
- Provinsi
- Kabupaten/Kota
- Kecamatan
- Kelurahan/Desa
- RT
- RW
- Kode Pos
- Alamat Lengkap

---

### **Section: IBU KANDUNG**

**TODO:** Perubahan yang SAMA PERSIS dengan Ayah untuk:
- Luar Negeri - Ibu
- Dalam Negeri - Ibu

---

### **Section: WALI**

**TODO:** Perubahan yang SAMA PERSIS dengan Ayah untuk:
- Luar Negeri - Wali
- Dalam Negeri - Wali

---

## ✅ Hasil Setelah Fix

**Behavior:**

| Status Orangtua | Info Box | Field Alamat | Required Mark (*) | Validation |
|----------------|----------|--------------|-------------------|------------|
| **Masih Hidup** | ❌ Tidak muncul | ✅ Muncul | ✅ Ada (*) | ✅ Required |
| **Meninggal** | ✅ Muncul | ✅ Muncul | ❌ Tidak ada | ❌ Optional |
| **Tidak Diketahui** | ✅ Muncul | ✅ Muncul | ❌ Tidak ada | ❌ Optional |

**Validation Backend:**
- Sudah conditional dari fix sebelumnya ✅
- `app/Livewire/Pendaftar/Formulir.php` - sudah benar
- `app/Livewire/Admin/DetailPendaftar.php` - sudah benar
- `app/Livewire/Panitia/DetailPendaftar.php` - sudah benar

---

## 🚀 Testing

### **Test di Pendaftar**
1. Buka `/pendaftar/formulir?activeTab=orangtua`
2. Ubah **Status Ayah** → **Meninggal**
3. Save
4. Pindah ke tab **Alamat**
5. ✅ **Info box muncul**: "Alamat ayah tidak perlu diisi karena status ayah: Meninggal"
6. ✅ **Field alamat tetap MUNCUL** (tidak disembunyikan)
7. ✅ **Tanda (*) HILANG** dari semua field ayah
8. ✅ **Field bisa diisi atau dikosongkan** (optional)
9. Klik Simpan
10. ✅ **Data berhasil tersimpan TANPA error**

### **Test di Admin/Panitia**
1. Login sebagai Admin atau Panitia
2. Detail Pendaftar → Tab Alamat → Edit
3. Cek pendaftar yang status ayah/ibu **Meninggal** atau **Tidak Diketahui**
4. ✅ **Field tetap muncul** (tidak tersembunyi)
5. ✅ **Info box muncul**
6. ✅ **Tanda (*) tidak ada**
7. Klik Simpan
8. ✅ **Berhasil tersimpan**

---

## 📦 Status Deployment

**File yang perlu diupload:**
- ✅ `resources/views/tab_templates/tab-data-alamat.blade.php`

**Note:**
- Section **AYAH** sudah diperbaiki ✅
- Section **IBU dan WALI** masih perlu perbaikan yang SAMA ⚠️
- Upload file dan test section Ayah dulu
- Jika berhasil, baru perbaiki Ibu dan Wali dengan pola yang sama

---

**Status:** ✅ **Section AYAH FIXED, IBU & WALI PENDING**
**Impact:** 🟢 Low (hanya UI display, validation backend sudah benar)
**Risk:** 🟢 Low (tidak mengubah logic, hanya display)
