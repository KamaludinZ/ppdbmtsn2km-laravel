# Quick Test Guide - URL Tabs Feature

## 🧪 Testing di Lokal (WAJIB sebelum upload!)

### Test 1: URL berubah saat klik tab
1. Buka: http://localhost:8000/admin/pendaftar/13
2. Klik tab "Data Orang Tua"
3. **EXPECTED:** URL berubah jadi `?activeTab=orangtua`
4. Klik tab "Data Alamat"
5. **EXPECTED:** URL berubah jadi `?activeTab=alamat`

✅ **PASS:** URL update otomatis saat pindah tab
❌ **FAIL:** URL tetap sama → Check x-data @entangle

### Test 2: Direct URL access
1. Tutup browser
2. Buka: http://localhost:8000/admin/pendaftar/13?activeTab=orangtua
3. **EXPECTED:** Langsung ke tab "Data Orang Tua" (bukan tab Siswa)

✅ **PASS:** Tab Orangtua langsung aktif saat load
❌ **FAIL:** Masih di tab Siswa → Check #[Url] attribute di controller

### Test 3: Refresh browser
1. Buka: http://localhost:8000/admin/pendaftar/13
2. Klik tab "Data Alamat"
3. Refresh browser (F5)
4. **EXPECTED:** Tetap di tab "Data Alamat"

✅ **PASS:** Tab tetap di posisi yang sama setelah refresh
❌ **FAIL:** Kembali ke tab Siswa → URL tidak persistent

### Test 4: Independent Edit Mode
1. Buka: http://localhost:8000/admin/pendaftar/13?activeTab=siswa
2. Klik "Edit Data"
3. **EXPECTED:** Form di tab Siswa jadi editable
4. Pindah ke tab "Data Orang Tua" (JANGAN save dulu)
5. **EXPECTED:** Form di tab Orangtua tetap read-only (belum di-edit)
6. Klik "Edit Data" di tab Orangtua
7. **EXPECTED:** Form di tab Orangtua jadi editable
8. Kembali ke tab "Data Siswa"
9. **EXPECTED:** Form di tab Siswa MASIH editable (tetap dalam edit mode)

✅ **PASS:** Setiap tab punya edit mode sendiri yang tidak saling mengganggu
❌ **FAIL:** Edit mode hilang saat pindah tab → Check editModeSiswa, editModeOrangtua properties

---

## 🔍 Troubleshooting

### Issue: URL tidak berubah saat klik tab

**Possible Causes:**
1. `@entangle('activeTab')` tidak terpasang di x-data
2. Livewire tidak loaded dengan benar

**Check:**
```html
<!-- View harus punya ini: -->
<div x-data="{ activeTab: @entangle('activeTab') }" ...>
```

**Fix:**
- Pastikan line 134 di view sudah update
- Clear browser cache (Ctrl+Shift+R)

---

### Issue: Direct URL tidak bekerja (selalu ke tab Siswa)

**Possible Causes:**
1. Property activeTab tidak punya #[Url] attribute
2. Import Livewire\Attributes\Url tidak ada

**Check:**
```php
// Controller harus punya ini:
use Livewire\Attributes\Url;

class DetailPendaftar extends Component {
    #[Url]
    public $activeTab = 'siswa';
}
```

**Fix:**
- Pastikan line 7 (import) dan line 38-39 (property) sudah update
- Run: `php artisan optimize:clear`

---

### Issue: Edit mode conflict antar tab

**Possible Causes:**
1. Section-specific editMode properties tidak ada
2. toggleEdit($section) tidak menggunakan property yang benar

**Check:**
```php
// Controller harus punya ini:
public $editModeSiswa = false;
public $editModeOrangtua = false;
public $editModeAlamat = false;
// ... dst

public function toggleEdit($section) {
    $property = 'editMode' . ucfirst($section);
    if (property_exists($this, $property)) {
        $this->$property = !$this->$property;
        // ...
    }
}
```

**Fix:**
- Ini sudah fixed di perbaikan sebelumnya
- Pastikan semua properties ada

---

## 📋 Checklist Before Upload

- [ ] Test 1: URL berubah saat klik tab ✓
- [ ] Test 2: Direct URL access bekerja ✓
- [ ] Test 3: Refresh browser tetap di tab yang sama ✓
- [ ] Test 4: Independent edit mode bekerja ✓
- [ ] No console errors di browser ✓
- [ ] No PHP errors di laravel.log ✓

**Jika semua test PASS**, silakan upload ke production dengan:
```cmd
upload_all_fixes_complete.bat
```

**Jika ada test FAIL**, check Troubleshooting di atas atau tanya untuk bantuan.

---

## 🎯 Expected Behavior Summary

### URL Format:
```
Admin tanpa query:    /admin/pendaftar/13
Admin dengan query:   /admin/pendaftar/13?activeTab=orangtua

Panitia tanpa query:  /panitia-pendaftaran/pendaftar/13
Panitia dengan query: /panitia-pendaftaran/pendaftar/13?activeTab=alamat
```

### Tab Values:
- `siswa` - Tab Data Siswa
- `orangtua` - Tab Data Orang Tua
- `alamat` - Tab Data Alamat
- `prestasi` - Tab Data Prestasi
- `keahlian` - Tab Keahlian
- `tahfidz` - Tab Tahfidz
- `beasiswa` - Tab Beasiswa & Bantuan
- `pendidikan` - Tab Pendidikan Lain
- `survey` - Tab Survey
- `dokumen` - Tab Verifikasi Dokumen
- `daftarulang` - Tab Daftar Ulang

### Default Behavior:
- Tanpa query parameter → Default ke tab `siswa`
- Invalid query parameter → Default ke tab `siswa`
- Query parameter valid → Langsung ke tab tersebut

---

Tanggal: 3 Februari 2026 - 08:15 WIB
Status: ✅ READY FOR LOCAL TESTING
