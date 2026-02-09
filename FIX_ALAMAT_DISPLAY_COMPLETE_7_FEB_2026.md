# FIX ALAMAT FIELD DISPLAY LOGIC COMPLETE - 7 FEB 2026

## 📋 Masalah

**User Request:**
> "di /pendaftar/formulir?activeTab=alamat meskipun Alamat ayah tidak perlu diisi karena status ayah: Meninggal/tidak diketahui sudah benar muncul info seperti itu tapi field isiannya **jangan di sembunyikan tetap munculkan saja tapi tidak wajib di isi** baik di pendaftar, admin, panitia."

**Masalah:**
- ✅ Info box "Alamat tidak perlu diisi" sudah muncul (BENAR)
- ❌ Field alamat TERSEMBUNYI saat status bukan "masih_hidup" (SALAH - seharusnya tetap muncul tapi optional)

**File:** `resources/views/tab_templates/tab-data-alamat.blade.php`

---

## 🎯 Solusi Yang Diterapkan

### **Pattern Fix:**

**SEBELUM (SALAH):**
```html
<!-- Field TERSEMBUNYI jika status bukan masih_hidup -->
<div x-show="ayah_luar_negeri && ayah_status === 'masih_hidup'" x-transition>
    <label>Status Kepemilikan Rumah <span class="text-red-500">*</span></label>
    <select wire:model="ayah_status_kepemilikan_rumah" required>...</select>
</div>

<!-- Info box di BAWAH -->
<div x-show="ayah_luar_negeri && ayah_status !== 'masih_hidup'">
    Info: Alamat tidak perlu diisi
</div>
```

**SESUDAH (BENAR):**
```html
<!-- Info box PINDAH KE ATAS -->
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

### **1. SECTION AYAH KANDUNG ✅ COMPLETED**

#### **A. Luar Negeri - Ayah**

**Perubahan:**
1. ❌ Hapus kondisi: `ayah_status === 'masih_hidup'` dari `x-show`
2. ✅ Info box pindah ke ATAS (sebelum field muncul)
3. ✅ Required mark jadi conditional:
   - SEBELUM: `<span class="text-red-500">*</span>`
   - SESUDAH: `<span x-show="ayah_status === 'masih_hidup'" class="text-red-500">*</span>`
4. ✅ Hapus attribute `required` dari input/select

**Field yang diubah (Line 62-110):**
- Status Kepemilikan Rumah
- Alamat Lengkap

#### **B. Dalam Negeri - Ayah**

**Perubahan yang sama:**
1. ❌ Ubah `x-show="!ayah_luar_negeri && ayah_status === 'masih_hidup'"` → `x-show="!ayah_luar_negeri"`
2. ✅ Info box pindah ke ATAS
3. ✅ Semua required mark jadi conditional
4. ✅ Hapus semua `required` attribute

**Field yang diubah (Line 114-225):**
- Status Kepemilikan Rumah (Line 131-143)
- Provinsi (Line 145-157)
- Kabupaten/Kota (Line 159-172)
- Kecamatan (Line 174-186)
- Kelurahan/Desa (Line 189-198)
- RT (Line 200-208)
- RW (Line 210-218)
- Kode Pos (Line 221-228)
- Alamat Lengkap (Line 231-238)

---

### **2. SECTION IBU KANDUNG ✅ COMPLETED**

#### **A. Luar Negeri - Ibu**

**Perubahan (Line 258-295):**
1. ❌ Ubah `x-show="ibu_luar_negeri && ibu_status === 'masih_hidup'"` → `x-show="ibu_luar_negeri"`
2. ✅ Info box pindah ke ATAS (Line 259-266)
3. ✅ Required mark conditional (Line 273, 290)
4. ✅ Hapus `required` attribute

**Field yang diubah:**
- Status Kepemilikan Rumah
- Alamat Lengkap

#### **B. Dalam Negeri - Ibu**

**Perubahan (Line 297-424):**
1. ❌ Info box pindah dari Line 416-424 ke Line 297-305 (SEBELUM fields)
2. ✅ Ubah `x-show="!ibu_luar_negeri && ibu_status === 'masih_hidup'"` → `x-show="!ibu_luar_negeri"` (Line 308)
3. ✅ Required mark jadi **DUA kondisi** (karena ada checkbox "Sama dengan Ayah"):
   - SEBELUM: `<span x-show="!ibu_sama_ayah" class="text-red-500">*</span>`
   - SESUDAH: `<span x-show="!ibu_sama_ayah && ibu_status === 'masih_hidup'" class="text-red-500">*</span>`
4. ✅ Hapus semua `:required="!ibu_sama_ayah"` attribute

**Field yang diubah:**
- Status Kepemilikan Rumah (Line 312)
- Provinsi (Line 329)
- Kabupaten/Kota (Line 343)
- Kecamatan (Line 358)
- Kelurahan/Desa (Line 372)
- RT (Line 388)
- RW (Line 398)
- Kode Pos (Line 408)
- Alamat Lengkap (Line 419)

---

### **3. SECTION WALI - TIDAK PERLU FIX ℹ️**

**Alasan:**
- Wali TIDAK punya field status seperti Ayah/Ibu (masih_hidup, meninggal, tidak_diketahui)
- Wali hanya punya `wali_status_wali`: sama_dengan_ayah, sama_dengan_ibu, lainnya
- Required marks sudah benar: `x-show="wali_status === 'lainnya' && !wali_sama_ayah"`
- Tidak ada konsep "status wali: Meninggal/Tidak Diketahui"

**Kesimpulan:** Section WALI sudah benar, tidak perlu perubahan.

---

## ✅ Hasil Setelah Fix

### **Behavior Baru:**

| Status Orangtua | Info Box | Field Alamat | Required Mark (*) | Validation |
|----------------|----------|--------------|-------------------|------------|
| **Masih Hidup** | ❌ Tidak muncul | ✅ Muncul | ✅ Ada (*) | ✅ Required |
| **Meninggal** | ✅ Muncul | ✅ Muncul | ❌ Tidak ada | ❌ Optional |
| **Tidak Diketahui** | ✅ Muncul | ✅ Muncul | ❌ Tidak ada | ❌ Optional |

### **Behavior untuk IBU (dengan checkbox "Sama dengan Ayah"):**

| Status Ibu | Sama dengan Ayah | Info Box | Field | Required (*) | Validation |
|-----------|------------------|----------|-------|--------------|------------|
| Masih Hidup | ❌ Tidak | ❌ Tidak | ✅ Muncul | ✅ Ada | ✅ Required |
| Masih Hidup | ✅ Ya | ❌ Tidak | 🔒 Disabled | ❌ Tidak | ❌ Optional |
| Meninggal | ❌ Tidak | ✅ Muncul | ✅ Muncul | ❌ Tidak | ❌ Optional |
| Tidak Diketahui | ❌ Tidak | ✅ Muncul | ✅ Muncul | ❌ Tidak | ❌ Optional |

---

## 🔧 Validation Backend

**Sudah benar dari fix sebelumnya:**
- `app/Livewire/Pendaftar/Formulir.php` - sudah conditional ✅
- `app/Livewire/Admin/DetailPendaftar.php` - sudah conditional ✅
- `app/Livewire/Panitia/DetailPendaftar.php` - sudah conditional ✅

**Logic backend:**
```php
// Contoh di Pendaftar/Formulir.php
protected function rules()
{
    $rules = [];

    // Alamat Ayah hanya required jika status masih hidup
    if ($this->ayah_status === 'masih_hidup') {
        $rules['ayah_status_kepemilikan_rumah'] = 'required';
        $rules['ayah_provinsi'] = 'required';
        // ... dst
    }

    return $rules;
}
```

**Kesimpulan:** Frontend sekarang MATCH dengan backend validation ✅

---

## 🚀 Testing

### **Test di Pendaftar**

1. Login sebagai Pendaftar
2. Buka `/pendaftar/formulir?activeTab=orangtua`
3. Ubah **Status Ayah** → **Meninggal**
4. Klik Simpan
5. Pindah ke tab **Alamat** (`/pendaftar/formulir?activeTab=alamat`)

**Expected Result:**
- ✅ **Info box muncul**: "Alamat ayah tidak perlu diisi karena status ayah: Meninggal"
- ✅ **Field alamat TETAP MUNCUL** (tidak disembunyikan)
- ✅ **Tanda (*) HILANG** dari semua field ayah
- ✅ **Field bisa diisi atau dikosongkan** (optional)
- ✅ Klik Simpan → **Berhasil tanpa error**

**Test untuk Ibu:**
6. Kembali ke tab Orang Tua
7. Ubah **Status Ibu** → **Tidak Diketahui**
8. Klik Simpan, pindah ke tab Alamat
9. Cek section Ibu:
   - ✅ Info box muncul
   - ✅ Field tetap muncul
   - ✅ Tanda (*) hilang
   - ✅ Simpan berhasil

### **Test di Admin**

1. Login sebagai Admin
2. Menu **Pendaftar** → Pilih pendaftar yang status ayah **Meninggal**
3. Tab **Alamat** → Klik **Edit**

**Expected Result:**
- ✅ Info box muncul: "Alamat ayah tidak perlu diisi..."
- ✅ Field alamat ayah tetap muncul (tidak tersembunyi)
- ✅ Tanda (*) tidak ada
- ✅ Bisa edit atau kosongkan field
- ✅ Klik Simpan → Berhasil

4. Test juga untuk pendaftar yang status ibu **Tidak Diketahui**
5. Cek section Ibu - behavior sama seperti di atas

### **Test di Panitia**

1. Login sebagai Panitia-Pendaftaran
2. Ulangi test yang sama seperti Admin
3. Expected result sama ✅

---

## 📦 Deployment

### **File yang Diubah:**
- ✅ `resources/views/tab_templates/tab-data-alamat.blade.php`

### **Upload ke Production:**

**Menggunakan script:**
```bash
./upload_fix_alamat_display_complete.bat
```

**Atau manual:**
```bash
# Upload file
scp resources/views/tab_templates/tab-data-alamat.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/tab_templates/

# Clear cache
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear"
```

---

## 📊 Summary

### **Total Perubahan:**

| Section | Luar Negeri | Dalam Negeri | Total Fields |
|---------|-------------|--------------|--------------|
| **AYAH** | ✅ Fixed (2 fields) | ✅ Fixed (9 fields) | **11 fields** |
| **IBU** | ✅ Fixed (2 fields) | ✅ Fixed (9 fields) | **11 fields** |
| **WALI** | ℹ️ Tidak perlu | ℹ️ Tidak perlu | **0 fields** |
| **TOTAL** | **4 fields** | **18 fields** | **22 fields** |

### **Pattern Applied:**

**3 Perubahan Utama:**
1. **Info Box**: Pindah ke ATAS sebelum fields (agar muncul duluan)
2. **Field Display**: `x-show` tidak lagi conditional pada status (selalu muncul)
3. **Required Mark**: `*` jadi conditional dengan `x-show="status === 'masih_hidup'"`

**Untuk IBU (special case):**
- Required mark punya DUA kondisi: `x-show="!ibu_sama_ayah && ibu_status === 'masih_hidup'"`
- Karena ada checkbox "Sama dengan Ayah" yang juga mempengaruhi required/optional

---

## ✅ Status

**Status:** ✅ **COMPLETE - READY FOR PRODUCTION**

**Files Modified:**
- `resources/views/tab_templates/tab-data-alamat.blade.php`

**Impact:**
- 🟢 Low (hanya UI display, tidak mengubah logic)
- User-facing: Field yang tadinya tersembunyi sekarang tetap muncul (lebih jelas)

**Risk:**
- 🟢 Very Low (backend validation sudah benar dari sebelumnya)
- Tidak ada perubahan database
- Tidak ada perubahan backend logic

**Testing Required:**
- ✅ Pendaftar: Tab Alamat untuk Ayah/Ibu dengan status Meninggal/Tidak Diketahui
- ✅ Admin: Edit alamat pendaftar
- ✅ Panitia: Edit alamat pendaftar

**User Feedback:**
- Section AYAH sudah di-test oleh user: **"Biak sudah saya tes dan berhasil. lanjutkan"** ✅
- Section IBU dan WALI menggunakan pattern yang sama

---

**Generated:** 7 Feb 2026
**Developer:** Claude Code
**Session:** FIX_ALAMAT_DISPLAY_COMPLETE_7_FEB_2026
