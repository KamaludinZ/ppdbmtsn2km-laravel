# FIX DATABASE ERROR - PAKET_SERAGAM VARCHAR(255) TOO SHORT
**Tanggal:** 21 Februari 2026
**Error:** `SQLSTATE[22001]: String data, right truncated: 7 ERROR: value too long for type character varying(255)`

## 🔍 ROOT CAUSE

### Error Log:
```
SQLSTATE[22001]: String data, right truncated: 7 ERROR:  value too long for type character varying(255)
Location: app/Livewire/Pendaftar/DaftarUlang.php:664
Method: saveSeragam()
```

### Problem:
Kolom `paket_seragam` di tabel `seragam` masih **VARCHAR(255)**, tapi kita menyimpan **full package JSON object** yang bisa sangat panjang ketika paket punya banyak opsi.

### Example JSON yang Melebihi 255 Characters:
```json
{
  "name": "Paket Lengkap Seragam Full + Atribut + Buku + Kerudung + Sepatu",
  "options": [
    {"id":"opt1","name":"Paket A Lengkap","amount":1500000},
    {"id":"opt2","name":"Paket B Lengkap","amount":2000000},
    {"id":"opt3","name":"Paket C Lengkap","amount":2500000}
  ],
  "selection_mode":"checkbox"
}
```

Total: **300+ characters** → Melebihi VARCHAR(255)!

## 🔧 SOLUTION

### Migration File Created:
`database/migrations/2026_02_21_100000_change_paket_seragam_to_text.php`

### Changes:
```php
Schema::table('seragam', function (Blueprint $table) {
    // Change paket_seragam from VARCHAR(255) to TEXT
    $table->text('paket_seragam')->change();
});
```

### Why TEXT?
- VARCHAR(255) = Max 255 characters ❌
- TEXT = Unlimited characters (up to ~1GB in PostgreSQL) ✅
- Consistent with other JSON columns: `opsi_seragam` (TEXT), `nominal_seragam_awal` (JSON)

## 📊 BEFORE vs AFTER

| Aspect | Before | After |
|--------|--------|-------|
| **Column Type** | VARCHAR(255) | TEXT |
| **Max Length** | 255 characters | ~1GB (unlimited) |
| **Error on Save** | Yes (if > 255) ❌ | No ✅ |
| **Package JSON** | Truncated ❌ | Full data ✅ |
| **Multi-select** | Fails ❌ | Works ✅ |

## 🚀 DEPLOYMENT

### Local:
```bash
# Already applied via direct ALTER
ALTER TABLE seragam ALTER COLUMN paket_seragam TYPE TEXT;
```

### Production:
```bash
# Upload migration file
scp database/migrations/2026_02_21_100000_change_paket_seragam_to_text.php production:/path/

# Run migration
ssh production
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan migrate
```

## ✅ TESTING

### Test Scenario:
1. Login sebagai Pendaftar
2. Go to `/pendaftar/daftarulang?activeTab=seragam`
3. Pilih paket seragam dengan mode checkbox
4. Pilih 3+ opsi
5. Fill semua field
6. Upload bukti transfer
7. Klik "Simpan Daftar Ulang Seragam"

### Expected Result:
- ✅ **BEFORE FIX:** Error 500 (value too long for type character varying(255))
- ✅ **AFTER FIX:** Sukses simpan, redirect ke halaman status "menunggu"

## 🔗 RELATED FILES

### 1. Migration File (NEW):
`database/migrations/2026_02_21_100000_change_paket_seragam_to_text.php`

### 2. Component Using paket_seragam:
- `app/Livewire/Pendaftar/DaftarUlang.php` - Line 635: `'paket_seragam' => $this->paketSeragam`
- `app/Livewire/Admin/DaftarUlang.php`
- `app/Livewire/PanitiaSeragam/DaftarUlang.php`

### 3. View Files:
- `resources/views/livewire/pendaftar/daftar-ulang.blade.php` (dropdown paket)
- `resources/views/livewire/admin/daftar-ulang.blade.php` (settings)

## 📋 IMPACT

### Who is Affected?
- **Pendaftar** dengan paket seragam checkbox mode ❌ (Error 500 saat save)
- **Admin** yang buat paket dengan nama panjang ❌ (Data terpotong)
- **Panitia Seragam** ❌ (Tidak bisa lihat data lengkap)

### After Fix:
- ✅ Pendaftar bisa save tanpa error
- ✅ Admin bisa buat paket dengan nama panjang
- ✅ Data JSON lengkap tersimpan
- ✅ Multi-select checkbox mode bekerja sempurna

## 🎉 SUMMARY

**Root Cause:** VARCHAR(255) terlalu pendek untuk menyimpan package JSON yang lengkap dengan banyak opsi.

**Fix:** Change column type dari VARCHAR(255) ke TEXT.

**Files Changed:**
- 1 migration file (NEW)

**Testing:** Berhasil save seragam dengan checkbox mode tanpa error 500.

**Ready for Production!** ✅
