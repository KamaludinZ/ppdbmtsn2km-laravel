# Update: Invoice dengan Kolom Detail yang Lengkap

**Tanggal**: 31 Januari 2026
**Status**: ✅ **COMPLETE**

---

## 🎯 Summary

Berhasil menambahkan **kolom detail yang lebih lengkap** di invoice Daftar Ulang Madrasah dan Seragam untuk menampilkan:
1. **Keterangan** - Nama biaya/paket
2. **Nominal Biaya** - Dari field "Pilih Nominal Biaya" (nominal_biaya_awal)
3. **Konfirmasi Pembayaran** - Dari field "Konfirmasi Nominal yang Dibayarkan" (nominal_biaya)
4. **Status Pembayaran** - LUNAS/KEMBALIAN/KEKURANGAN (otomatis dihitung)
5. **Opsi Pembayaran** - Lunas/Angsuran/Potongan (dari field opsi_pembayaran)

---

## 📋 Structure Perubahan

### Before (2 Kolom):
| Keterangan | Jumlah |
|------------|--------|
| Nama Biaya | Rp XXX |
| **Total** | **Rp XXX** |

### After (3 Kolom + Status):
| Keterangan | Nominal Biaya | Konfirmasi Pembayaran |
|------------|---------------|----------------------|
| Nama Biaya | Rp XXX | Rp YYY |
| **Status Pembayaran** | colspan=2 | **LUNAS/KEMBALIAN/KEKURANGAN** |

**Plus**: Tambahan baris "Opsi Pembayaran" di bagian Informasi Pembayaran

---

## 🔄 Logika Status Pembayaran

```php
$selisih = $jumlahKonfirmasi - $jumlahAwal;

if ($selisih == 0) {
    // Nominal sama
    $statusPembayaran = 'LUNAS';
} elseif ($selisih > 0) {
    // Konfirmasi lebih besar
    $statusPembayaran = 'KEMBALIAN: Rp ' . number_format($selisih, 0, ',', '.');
} else {
    // Konfirmasi lebih kecil
    $statusPembayaran = 'KEKURANGAN: Rp ' . number_format(abs($selisih), 0, ',', '.');
}
```

**Contoh**:
- Nominal Biaya: Rp 600.000, Konfirmasi: Rp 600.000 → **LUNAS**
- Nominal Biaya: Rp 600.000, Konfirmasi: Rp 650.000 → **KEMBALIAN: Rp 50.000**
- Nominal Biaya: Rp 600.000, Konfirmasi: Rp 550.000 → **KEKURANGAN: Rp 50.000**

---

## 📄 Files Modified

### 1. Invoice Daftar Ulang Madrasah PDF ✅

**File**: `resources/views/pdf/invoice-daftar-ulang-madrasah.blade.php`

#### A. Tambah Opsi Pembayaran di Informasi Pembayaran (Lines 228-234):
```php
<tr>
    <td class="label" style="width: 30%;">Opsi Pembayaran</td>
    <td class="separator">:</td>
    <td class="value" style="text-transform: uppercase;">
        {{ $daftarUlang->opsi_pembayaran ? strtoupper($daftarUlang->opsi_pembayaran) : 'LUNAS' }}
    </td>
</tr>
```

#### B. Update Tabel Detail Biaya (Lines 236-277):
**Before**:
```php
<thead>
    <tr>
        <th class="col-keterangan">Keterangan</th>
        <th class="col-jumlah text-right">Jumlah</th>
    </tr>
</thead>
```

**After**:
```php
<thead>
    <tr>
        <th style="width: 40%;">Keterangan</th>
        <th class="text-right" style="width: 30%;">Nominal Biaya</th>
        <th class="text-right" style="width: 30%;">Konfirmasi Pembayaran</th>
    </tr>
</thead>
<tbody>
    @php
        // Nominal Biaya Awal (yang dipilih di "Pilih Nominal Biaya")
        $nominalBiayaAwal = $daftarUlang->nominal_biaya_awal ?? [];
        $keterangan = $nominalBiayaAwal['name'] ?? 'Biaya Daftar Ulang Madrasah';
        $jumlahAwal = $nominalBiayaAwal['amount'] ?? 0;

        // Nominal Biaya Konfirmasi (yang dipilih di "Konfirmasi Nominal yang Dibayarkan")
        $nominalBiayaKonfirmasi = $daftarUlang->nominal_biaya ?? [];
        $jumlahKonfirmasi = $nominalBiayaKonfirmasi['amount'] ?? 0;

        // Hitung selisih
        $selisih = $jumlahKonfirmasi - $jumlahAwal;
        $statusPembayaran = '';
        if ($selisih == 0) {
            $statusPembayaran = 'LUNAS';
        } elseif ($selisih > 0) {
            $statusPembayaran = 'KEMBALIAN: Rp ' . number_format($selisih, 0, ',', '.');
        } else {
            $statusPembayaran = 'KEKURANGAN: Rp ' . number_format(abs($selisih), 0, ',', '.');
        }
    @endphp
    <tr>
        <td>{{ $keterangan }}</td>
        <td class="text-right">Rp {{ number_format($jumlahAwal, 0, ',', '.') }}</td>
        <td class="text-right">Rp {{ number_format($jumlahKonfirmasi, 0, ',', '.') }}</td>
    </tr>
    <tr class="bg-gray">
        <td style="font-weight: bold;">Status Pembayaran</td>
        <td colspan="2" class="text-right" style="font-weight: bold;">{{ $statusPembayaran }}</td>
    </tr>
</tbody>
```

---

### 2. Invoice Daftar Ulang Seragam PDF ✅

**File**: `resources/views/pdf/invoice-daftar-ulang-seragam.blade.php`

#### A. Tambah Opsi Pembayaran di Informasi Pembayaran (Lines 234-240):
```php
<tr>
    <td class="label" style="width: 30%;">Opsi Pembayaran</td>
    <td class="separator">:</td>
    <td class="value" style="text-transform: uppercase;">
        {{ $seragam->opsi_pembayaran_seragam ? strtoupper($seragam->opsi_pembayaran_seragam) : 'LUNAS' }}
    </td>
</tr>
```

#### B. Update Tabel Detail Biaya (Lines 263-304):
**Structure sama dengan Madrasah, menggunakan**:
- `$seragam->nominal_seragam_awal` (Nominal Biaya)
- `$seragam->nominal_seragam` (Konfirmasi Pembayaran)
- `$seragam->opsi_pembayaran_seragam` (Opsi Pembayaran)

---

### 3. Preview Invoice Madrasah (Admin Settings) ✅

**File**: `resources/views/livewire/admin/pengaturan-kartu.blade.php`

#### A. Tambah Opsi Pembayaran (Lines 857-860):
```html
<tr>
    <td class="text-gray-700">Opsi Pembayaran</td>
    <td class="font-semibold text-gray-900">: LUNAS</td>
</tr>
```

#### B. Update Tabel Detail Biaya (Lines 868-901):
```html
<thead class="bg-gray-100">
    <tr>
        <th class="border border-gray-300 px-2 py-1 text-left" style="width: 40%;">Keterangan</th>
        <th class="border border-gray-300 px-2 py-1 text-right" style="width: 30%;">Nominal Biaya</th>
        <th class="border border-gray-300 px-2 py-1 text-right" style="width: 30%;">Konfirmasi Pembayaran</th>
    </tr>
</thead>
<tbody>
    <tr>
        <td class="border border-gray-300 px-2 py-1">
            <span class="text-xs text-gray-700">Biaya Daftar Ulang Full</span>
        </td>
        <td class="border border-gray-300 px-2 py-1 text-right">
            <span class="text-xs">Rp 600.000</span>
        </td>
        <td class="border border-gray-300 px-2 py-1 text-right">
            <span class="text-xs">Rp 550.000</span>
        </td>
    </tr>
    <tr class="bg-gray-50 font-semibold">
        <td class="border border-gray-300 px-2 py-1">Status Pembayaran</td>
        <td colspan="2" class="border border-gray-300 px-2 py-1 text-right">
            <span class="text-xs text-orange-600">KEKURANGAN: Rp 50.000</span>
        </td>
    </tr>
</tbody>
```

**Info Box**:
```html
<div class="mt-2 bg-blue-50 border border-blue-200 rounded px-2 py-1" style="font-size: 7px;">
    <strong>ℹ️ Info:</strong> Invoice menampilkan:<br>
    • <strong>Nominal Biaya</strong>: dari "Pilih Nominal Biaya"<br>
    • <strong>Konfirmasi Pembayaran</strong>: dari "Konfirmasi Nominal yang Dibayarkan"<br>
    • <strong>Status</strong>: LUNAS (sama), KEMBALIAN (lebih), atau KEKURANGAN (kurang)
</div>
```

---

### 4. Preview Invoice Seragam (Admin Settings) ✅

**File**: `resources/views/livewire/admin/pengaturan-kartu.blade.php`

#### A. Tambah Opsi Pembayaran (Lines 1029-1032):
```html
<tr>
    <td class="text-gray-700">Opsi Pembayaran</td>
    <td class="font-semibold text-gray-900">: ANGSURAN</td>
</tr>
```

#### B. Update Tabel Detail Biaya (Lines 1061-1094):
Struktur sama dengan Preview Madrasah, dengan contoh:
- Keterangan: "Paket Seragam Lengkap"
- Nominal Biaya: Rp 750.000
- Konfirmasi Pembayaran: Rp 750.000
- Status: LUNAS (hijau)

---

## 📊 Files Modified Summary

| No | File Path | Lines Modified | Description |
|----|-----------|----------------|-------------|
| 1 | `resources/views/pdf/invoice-daftar-ulang-madrasah.blade.php` | 228-277 | Tambah Opsi Pembayaran & 3 kolom detail |
| 2 | `resources/views/pdf/invoice-daftar-ulang-seragam.blade.php` | 234-304 | Tambah Opsi Pembayaran & 3 kolom detail |
| 3 | `resources/views/livewire/admin/pengaturan-kartu.blade.php` | 857-901 | Preview Madrasah updated |
| 4 | `resources/views/livewire/admin/pengaturan-kartu.blade.php` | 1029-1094 | Preview Seragam updated |

**Total**: 4 sections modified across 3 files

---

## 💾 Database Fields Used

### DaftarUlang Model:
```php
'nominal_biaya_awal' => 'array',  // Pilih Nominal Biaya (awal)
'nominal_biaya' => 'array',       // Konfirmasi Nominal yang Dibayarkan
'opsi_pembayaran' => 'string',    // lunas / angsuran / potongan
```

### Seragam Model:
```php
'nominal_seragam_awal' => 'array',       // Pilih Paket Seragam (awal)
'nominal_seragam' => 'array',            // Konfirmasi Nominal Seragam yang Dibayarkan
'opsi_pembayaran_seragam' => 'string',   // lunas / angsuran / potongan
```

---

## 🔍 Visual Comparison

### Invoice Madrasah

#### Before:
```
Detail Biaya:
+---------------------------+---------------+
| Keterangan                | Jumlah        |
+---------------------------+---------------+
| Biaya Daftar Ulang Full   | Rp 600.000    |
+---------------------------+---------------+
| Total                     | Rp 600.000    |
+---------------------------+---------------+
```

#### After:
```
Detail Biaya:
+---------------------------+---------------+----------------------+
| Keterangan                | Nominal Biaya | Konfirmasi Pembayaran|
+---------------------------+---------------+----------------------+
| Biaya Daftar Ulang Full   | Rp 600.000    | Rp 550.000          |
+---------------------------+---------------+----------------------+
| Status Pembayaran         | KEKURANGAN: Rp 50.000              |
+---------------------------+------------------------------------+

Informasi Pembayaran:
Bank Dari         : BCA
Waktu Transfer    : 15/01/2024 14:30 WIB
Status            : LUNAS / DISETUJUI
Opsi Pembayaran   : ANGSURAN          ← NEW!
```

---

## ✅ Testing Checklist

### Skenario 1: Pembayaran Lunas (Sama)
- [ ] Pendaftar pilih: Biaya Rp 600.000
- [ ] Pendaftar konfirmasi: Rp 600.000
- [ ] Invoice menampilkan: Status "LUNAS" (hijau)
- [ ] Opsi Pembayaran: "LUNAS"

### Skenario 2: Pembayaran Lebih (Kembalian)
- [ ] Pendaftar pilih: Biaya Rp 600.000
- [ ] Pendaftar konfirmasi: Rp 650.000
- [ ] Invoice menampilkan: Status "KEMBALIAN: Rp 50.000"
- [ ] Opsi Pembayaran: "LUNAS"

### Skenario 3: Pembayaran Kurang (Kekurangan)
- [ ] Pendaftar pilih: Biaya Rp 600.000
- [ ] Pendaftar konfirmasi: Rp 550.000
- [ ] Invoice menampilkan: Status "KEKURANGAN: Rp 50.000" (orange/red)
- [ ] Opsi Pembayaran: "ANGSURAN"

### Skenario 4: Opsi Lainnya
- [ ] Pendaftar pilih: "Opsi Lainnya" Rp 450.000
- [ ] Pendaftar konfirmasi: "Opsi Lainnya" Rp 400.000
- [ ] Invoice: Keterangan "Opsi Lainnya"
- [ ] Invoice: Nominal Biaya Rp 450.000
- [ ] Invoice: Konfirmasi Rp 400.000
- [ ] Status: "KEKURANGAN: Rp 50.000"

### Admin Preview:
- [ ] Akses `/admin/pengaturan-kartu?activeTab=invoice-madrasah`
- [ ] Preview menampilkan 3 kolom dengan contoh
- [ ] Info box menjelaskan logika status pembayaran
- [ ] Opsi Pembayaran terlihat di Informasi Pembayaran
- [ ] Akses `/admin/pengaturan-kartu?activeTab=invoice-seragam`
- [ ] Struktur sama dengan invoice madrasah

---

## 📝 Technical Notes

### 1. Width Kolom Tabel

**Header**:
- Keterangan: 40%
- Nominal Biaya: 30%
- Konfirmasi Pembayaran: 30%

**Status Pembayaran Row**:
- Kolom 1: "Status Pembayaran"
- Kolom 2-3: colspan=2, value status

### 2. Color Coding (Recommended)

Warna untuk Status Pembayaran:
- **LUNAS**: `color: green;` atau `text-emerald-600`
- **KEMBALIAN**: `color: blue;` atau `text-blue-600`
- **KEKURANGAN**: `color: orange;` atau `text-orange-600`

### 3. Fallback Values

Jika data null/kosong:
- **Keterangan**: Default "Biaya Daftar Ulang Madrasah" / "Biaya Seragam & Atribut"
- **Nominal**: Default 0
- **Opsi Pembayaran**: Default "LUNAS"

### 4. Text Transform

Opsi Pembayaran selalu ditampilkan UPPERCASE:
```php
{{ $daftarUlang->opsi_pembayaran ? strtoupper($daftarUlang->opsi_pembayaran) : 'LUNAS' }}
```

---

## 🎯 Impact

**User Experience**:
- ✅ Invoice lebih transparan dan jelas
- ✅ Pendaftar dapat melihat nominal awal vs konfirmasi
- ✅ Status pembayaran otomatis terhitung (LUNAS/KEMBALIAN/KEKURANGAN)
- ✅ Opsi pembayaran tercantum jelas (Lunas/Angsuran/Potongan)

**Admin**:
- ✅ Lebih mudah verifikasi pembayaran
- ✅ Dapat melihat selisih pembayaran dengan jelas
- ✅ Opsi pembayaran membantu tracking pembayaran bertahap

**Financial Tracking**:
- ✅ Audit trail lebih jelas
- ✅ Kembalian atau kekurangan tercatat otomatis
- ✅ Memudahkan rekapitulasi pembayaran

---

## 🔄 Related Features

**Feature ini melengkapi**:
1. **Konfirmasi Nominal Pembayaran** - Form pilihan nominal di halaman Daftar Ulang
2. **Opsi Lainnya** - Fitur untuk input nominal custom
3. **Verifikasi Pembayaran** - Admin melihat detail lengkap di invoice
4. **Opsi Pembayaran** - Tracking pembayaran Lunas/Angsuran/Potongan

---

**Completion Date**: 31 Januari 2026
**Status**: ✅ **COMPLETE - Ready for testing**
**Next Step**: Testing dengan berbagai skenario pembayaran (lunas, kembalian, kekurangan, angsuran)
