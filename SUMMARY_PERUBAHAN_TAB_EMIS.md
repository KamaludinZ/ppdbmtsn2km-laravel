# SUMMARY PERUBAHAN STRUKTUR TAB EMIS

## STATUS PEKERJAAN

### ✅ SELESAI:
1. **Backup file**: Kedua file sudah dibackup dengan extension `.backup2`
2. **Tab Navigation**: Sudah diubah dari 9 tab lama menjadi 8 tab baru di `detail-pendaftar.blade.php`
   - Tab baru: Data Siswa, Data Orang Tua, Data Alamat, Prestasi Siswa, Beasiswa & Bantuan, Pendidikan Lain, Verifikasi Dokumen, Daftar Ulang
3. **Template Tab Data Siswa**: Sudah dibuat lengkap di `tab_templates/tab-data-siswa.blade.php`

### ⏳ BELUM SELESAI:
File ini menjadi PANDUAN LENGKAP untuk menyelesaikan transformasi. Karena kedua file sangat besar (1621 dan 1412 baris), transformasi memerlukan:

1. **Membuat template lengkap untuk 5 tab yang tersisa** (Data Orang Tua, Data Alamat, Prestasi, Beasiswa, Pendidikan Lain)
2. **Menggabungkan semua template ke dalam file utama**
3. **Melakukan hal yang sama untuk file `formulir.blade.php`**
4. **Update Controller methods** jika diperlukan

---

## STRUKTUR FILE DETAIL-PENDAFTAR.BLADE.PHP

### File Original (1621 baris):
```
Baris 1-143: Header + Tab Navigation (✅ SUDAH DIUBAH)
Baris 144-1149: Tab content lama (❌ PERLU DIGANTI)
Baris 1150-1473: Tab Daftar Ulang (✅ PERTAHANKAN)
Baris 1474-1621: Tab Dokumen + Footer (✅ PERTAHANKAN)
```

### Langkah Peng-injeksian Tab Baru:
**LANGKAH 1:** Hapus baris 144-1149 (tab content lama)
**LANGKAH 2:** Insert 6 tab baru di posisi tersebut:
1. Tab Data Siswa (sudah ada di `tab_templates/tab-data-siswa.blade.php`)
2. Tab Data Orang Tua (perlu dibuat)
3. Tab Data Alamat (perlu dibuat)
4. Tab Prestasi Siswa (perlu dibuat)
5. Tab Beasiswa & Bantuan (perlu dibuat)
6. Tab Pendidikan Lain (perlu dibuat)
**LANGKAH 3:** Tab Daftar Ulang dan Dokumen akan otomatis ada setelahnya

---

## DETAIL FIELD PER TAB

### 1. TAB DATA SISWA (✅ Template Sudah Ada)
**Fields yang tercakup:**
- **Data Pribadi**: nama_lengkap, nama_panggilan, nisn, nik, tempat_lahir, tanggal_lahir, jenis_kelamin, agama
- **Data Keluarga**: kewarganegaraan, negara_asal, nomor_kk, anak_ke, jumlah_saudara_kandung
- **Kontak**: email, nomor_hp
- **Tempat Tinggal & Transportasi**: jenis_tinggal, alat_transportasi, jarak_tempat_tinggal, waktu_tempuh
- **Minat & Bakat**: hobi, cita_cita
- **Berkebutuhan Khusus**: berkebutuhan_khusus (checkbox array)
- **Upload**: foto

### 2. TAB DATA ORANG TUA (❌ Perlu Dibuat)
**Fields yang perlu dicakup:**
- **Data Ayah**:
  - nama_ayah, ayah_nik, ayah_tahun_lahir
  - pekerjaan_ayah, ayah_pendidikan, ayah_penghasilan
  - ayah_no_hp, ayah_berkebutuhan_khusus

- **Data Ibu**:
  - nama_ibu, ibu_nik, ibu_tahun_lahir
  - pekerjaan_ibu, ibu_pendidikan, ibu_penghasilan
  - ibu_no_hp, ibu_berkebutuhan_khusus

- **Data Wali** (conditional, if memiliki_wali = true):
  - wali_nama, wali_nik, wali_tahun_lahir
  - wali_pekerjaan, wali_pendidikan, wali_penghasilan
  - wali_no_hp

**UI Structure:**
```
Form Edit Mode:
- Section Ayah (dengan border/card hijau)
- Section Ibu (dengan border/card biru)
- Checkbox "Memiliki Wali"
- Section Wali (conditional, dengan border/card ungu)

View Mode:
- 3 cards terpisah untuk Ayah, Ibu, dan Wali (jika ada)
```

### 3. TAB DATA ALAMAT (❌ Perlu Dibuat)
**Fields yang perlu dicakup:**
- **Alamat Siswa** (primary):
  - alamat (textarea), rt, rw, dusun
  - desa, kecamatan, kabupaten, provinsi
  - kode_pos, latitude, longitude

- **Alamat Orang Tua**:
  - alamat_ayah (textarea, dengan note: "Kosongkan jika sama dengan alamat siswa")
  - alamat_ibu (textarea, dengan note: "Kosongkan jika sama dengan alamat siswa")

- **Alamat Wali** (conditional):
  - alamat_wali (textarea)

**UI Structure:**
```
Form Edit Mode:
- Section "Alamat Siswa" dengan grid 2 kolom untuk field detail
- Map preview untuk koordinat (optional)
- Section "Alamat Ayah"
- Section "Alamat Ibu"
- Section "Alamat Wali" (conditional)

View Mode:
- Cards terpisah dengan warna berbeda untuk setiap alamat
```

### 4. TAB PRESTASI SISWA (❌ Perlu Dibuat)
**Fields yang perlu dicakup:**
- prestasi (textarea, bisa berisi list prestasi)

**Optional Enhancement:**
Jika ada tabel relasi `prestasi`:
- Tampilkan list prestasi dari relasi
- Setiap item: tingkat, nama_prestasi, tahun, penyelenggara

**UI Structure:**
```
Form Edit Mode:
- Textarea besar untuk input prestasi
- (Optional) Form dinamis untuk add/remove prestasi jika ada relasi

View Mode:
- List prestasi dalam card
- Jika kosong: tampilkan "Belum ada data prestasi"
```

### 5. TAB BEASISWA & BANTUAN (❌ Perlu Dibuat)
**Fields yang perlu dicakup:**
- **KPS**:
  - penerima_kps (boolean/checkbox)
  - nomor_kps (conditional, jika penerima_kps = true)

- **KIP**:
  - penerima_kip (boolean/checkbox)
  - nomor_kip (conditional, jika penerima_kip = true)

- **PIP**:
  - layak_pip (boolean/checkbox)
  - alasan_layak_pip (textarea, conditional)

- **Upload Files**:
  - file_kartu_keluarga
  - file_akta_kelahiran

**UI Structure:**
```
Form Edit Mode:
- Section KPS dengan checkbox + input nomor (conditional)
- Section KIP dengan checkbox + input nomor (conditional)
- Section PIP dengan checkbox + textarea alasan (conditional)
- Section Upload dengan file input + preview

View Mode:
- 3-4 cards terpisah dengan status badge (Ya/Tidak)
- Show nomor jika ada
- Link download untuk file
```

### 6. TAB PENDIDIKAN LAIN (❌ Perlu Dibuat)
**Fields yang perlu dicakup:**
- **TK/PAUD**:
  - tk_nama, tk_npsn, tk_tahun_lulus

- **SD/MI**:
  - sd_nama, sd_npsn, sd_tahun_lulus
  - sd_nomor_peserta_ujian, sd_nomor_seri_ijazah, sd_nomor_skhun

- **Nilai SD** (untuk perhitungan ranking jika diperlukan):
  - sd_nilai_matematika, sd_nilai_ipa, sd_nilai_ips
  - sd_nilai_bahasa_indonesia, sd_nilai_bahasa_inggris

- **Upload Files**:
  - file_ijazah_sd
  - file_skhun_sd

**UI Structure:**
```
Form Edit Mode:
- Section "TK/PAUD" dengan 3 input (grid 3 kolom)
- Section "SD/MI - Data Sekolah" dengan 6 input (grid 2 kolom)
- Section "SD/MI - Nilai" dengan 5 input untuk nilai (grid 3 kolom)
- Section "Upload Dokumen SD/MI" dengan 2 file input + preview

View Mode:
- Card "Riwayat TK/PAUD"
- Card "Riwayat SD/MI" dengan sub-section data dan nilai
- Card "Dokumen SD/MI" dengan link download
```

---

## STRUKTUR FILE FORMULIR.BLADE.PHP

### File Original (1412 baris):
File ini untuk PENDAFTAR (bukan admin), jadi:
- Tidak ada tab "Verifikasi Dokumen"
- Tidak ada tab "Daftar Ulang"
- Hanya 6 tab: Data Siswa, Data Orang Tua, Data Alamat, Prestasi, Beasiswa, Pendidikan Lain

### Perbedaan dengan detail-pendaftar.blade.php:
1. Default active tab: `'siswa'` (bukan 'rapor')
2. Setiap tab memiliki edit mode dengan toggle button
3. Upload file bisa langsung dilakukan
4. Tab navigation: hanya 6 tab (tanpa Dokumen dan Daftar Ulang)

---

## CONTROLLER METHODS YANG DIPERLUKAN

### File: `app/Livewire/Admin/DetailPendaftar.php`

**Methods yang perlu ada/diupdate:**
```php
// Toggle edit mode
public function toggleEditMode()
{
    $this->editMode = !$this->editMode;
    // Load data ke properties untuk editing
}

// Save methods per tab
public function saveDataSiswa()
{
    $this->validate([
        'nama_lengkap' => 'required|string|max:255',
        'nisn' => 'required|string|max:10',
        'jenis_kelamin' => 'required|in:L,P',
        'agama' => 'required|string',
        // ... validasi lainnya
    ]);

    $this->pendaftar->update([
        'nama_lengkap' => $this->nama_lengkap,
        'nama_panggilan' => $this->nama_panggilan,
        'nisn' => $this->nisn,
        'nik' => $this->nik,
        'tempat_lahir' => $this->tempat_lahir,
        'tanggal_lahir' => $this->tanggal_lahir,
        'jenis_kelamin' => $this->jenis_kelamin,
        'agama' => $this->agama,
        'kewarganegaraan' => $this->kewarganegaraan,
        'negara_asal' => $this->negara_asal,
        'nomor_kk' => $this->nomor_kk,
        'anak_ke' => $this->anak_ke,
        'jumlah_saudara_kandung' => $this->jumlah_saudara_kandung,
        'email' => $this->email,
        'nomor_hp' => $this->nomor_hp,
        'jenis_tinggal' => $this->jenis_tinggal,
        'alat_transportasi' => $this->alat_transportasi,
        'jarak_tempat_tinggal' => $this->jarak_tempat_tinggal,
        'waktu_tempuh' => $this->waktu_tempuh,
        'hobi' => $this->hobi,
        'cita_cita' => $this->cita_cita,
        'berkebutuhan_khusus' => $this->berkebutuhan_khusus,
    ]);

    // Handle foto upload
    if ($this->newFoto) {
        $path = $this->newFoto->store('foto-pendaftar', 'public');
        $this->pendaftar->update(['foto' => $path]);
    }

    $this->editMode = false;
    session()->flash('message', 'Data siswa berhasil diperbarui!');
}

public function saveDataOrangTua()
{
    // Validasi dan simpan data orang tua
}

public function saveDataAlamat()
{
    // Validasi dan simpan data alamat
}

public function saveDataPrestasi()
{
    // Validasi dan simpan data prestasi
}

public function saveDataBeasiswa()
{
    // Validasi dan simpan data beasiswa & bantuan
    // Handle file upload untuk KK dan Akta
}

public function saveDataPendidikan()
{
    // Validasi dan simpan data pendidikan lain (TK & SD)
    // Handle file upload untuk ijazah dan SKHUN
}
```

### File: `app/Livewire/Pendaftar/Formulir.php`

**Methods yang perlu ada/diupdate:**
```php
// Multiple edit mode toggles (per tab)
public $editModePribadi = false;
public $editModeOrangTua = false;
public $editModeAlamat = false;
public $editModePrestasi = false;
public $editModeBeasiswa = false;
public $editModePendidikan = false;

public function toggleEdit($section)
{
    $property = 'editMode' . ucfirst($section);
    $this->$property = !$this->$property;
    // Load data to properties
}

// Save methods (sama seperti di admin, tapi untuk current auth user)
public function saveDataPribadi() { }
public function saveDataOrangTua() { }
public function saveDataAlamat() { }
public function saveDataPrestasi() { }
public function saveDataBeasiswa() { }
public function saveDataPendidikan() { }
```

---

## LANGKAH IMPLEMENTASI SELANJUTNYA

### PRIORITAS 1: Selesaikan detail-pendaftar.blade.php
1. ✅ Buat template Tab Data Siswa (DONE)
2. ❌ Buat template Tab Data Orang Tua
3. ❌ Buat template Tab Data Alamat
4. ❌ Buat template Tab Prestasi Siswa
5. ❌ Buat template Tab Beasiswa & Bantuan
6. ❌ Buat template Tab Pendidikan Lain
7. ❌ Gabungkan semua template ke file utama (replace baris 144-1149)
8. ❌ Test file dengan Laravel serve

### PRIORITAS 2: Selesaikan formulir.blade.php
1. ❌ Ubah tab navigation (6 tab)
2. ❌ Buat 6 tab content (bisa copy dari detail-pendaftar dengan modifikasi edit mode)
3. ❌ Test dengan user pendaftar

### PRIORITAS 3: Update Controllers
1. ❌ Update `DetailPendaftar.php` dengan save methods
2. ❌ Update `Formulir.php` dengan save methods
3. ❌ Add validation rules
4. ❌ Test semua CRUD operations

---

## CATATAN PENTING

### UI/UX Guidelines:
- **Warna Primary**: Emerald-600 (#059669)
- **Tab Active**: border-emerald-500, text-emerald-600, bg-emerald-50
- **Tab Inactive**: border-transparent, text-gray-500
- **Cards**: Gunakan warna berbeda per section (emerald, blue, purple, yellow, pink, indigo, dll)
- **Icons**: Setiap section gunakan SVG icon yang sesuai dari Heroicons
- **Grid Layout**: md:grid-cols-2 atau md:grid-cols-3 untuk form fields
- **Spacing**: space-y-4 untuk vertical, gap-4 untuk grid
- **Required Fields**: Tandai dengan asterisk (*) pada label

### Validation Guidelines:
- **Required**: nama_lengkap, nisn, jenis_kelamin, agama
- **Format Email**: untuk field email
- **Format Nomor**: untuk NISN, NIK, Nomor KK (numeric/string)
- **Min/Max**: untuk field angka (anak_ke, jumlah_saudara, nilai)
- **File Upload**: max 2MB untuk foto, max 5MB untuk dokumen

### Conditional Rendering:
- **Negara Asal**: hanya tampil jika kewarganegaraan = WNA
- **Nomor KPS**: hanya tampil jika penerima_kps = true
- **Nomor KIP**: hanya tampil jika penerima_kip = true
- **Alasan Layak PIP**: hanya tampil jika layak_pip = true
- **Data Wali**: hanya tampil jika memiliki_wali = true atau ada checkbox yang dicentang
- **Alamat Wali**: hanya tampil jika memiliki wali

---

## FILE YANG TELAH DIBUAT

1. ✅ `resources/views/livewire/admin/detail-pendaftar.blade.php.backup2` - Backup file original
2. ✅ `resources/views/livewire/pendaftar/formulir.blade.php.backup2` - Backup file original
3. ✅ `resources/views/livewire/admin/detail-pendaftar.blade.php` - File utama (tab navigation sudah diubah)
4. ✅ `tab_templates/tab-data-siswa.blade.php` - Template lengkap Tab Data Siswa
5. ✅ `generate_blade_file.py` - Helper script (optional)
6. ✅ `build_new_detail_pendaftar.py` - Builder script (optional)
7. ✅ `SUMMARY_PERUBAHAN_TAB_EMIS.md` - Dokumen ini

---

## NEXT STEPS RECOMMENDATIONS

Karena kompleksitas tugas dan ukuran file yang sangat besar, saya rekomendasikan:

1. **Lanjutkan secara bertahap**: Buat template untuk 5 tab yang tersisa satu per satu
2. **Test per tab**: Setelah setiap tab dibuat, test terlebih dahulu sebelum lanjut ke tab berikutnya
3. **Gunakan Git**: Commit setelah setiap tab selesai untuk memudahkan rollback jika ada masalah
4. **Automated approach**: Buat script Python untuk generate dan inject semua tab sekaligus (lebih cepat tapi lebih riskan)
5. **Manual approach**: Copy-paste setiap template secara manual (lebih lambat tapi lebih aman)

## ESTIMASI WAKTU
- **Membuat 5 template tab tersisa**: ~3-4 jam
- **Inject ke file utama**: ~30 menit
- **Update controller methods**: ~2 jam
- **Testing & debugging**: ~2-3 jam
- **Total**: ~8-10 jam

## KONTAK & SUPPORT
Jika memerlukan bantuan lebih lanjut atau ingin saya lanjutkan pembuatan template untuk tab yang tersisa, silakan request kembali.

---

**Generated by**: Claude Code Assistant
**Date**: 2026-01-13
**Version**: 1.0
