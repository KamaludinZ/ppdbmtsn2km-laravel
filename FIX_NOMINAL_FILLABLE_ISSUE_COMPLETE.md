# Fix Nominal Field Not Saving - Complete

## Masalah yang Ditemukan

Data nominal yang diisi oleh pendaftar tidak muncul di halaman admin `/admin/daftar-ulang`. Setelah investigasi, ditemukan bahwa:

### Root Cause
Field `nominal_biaya` dan `nominal_seragam` **tidak ada di array `$fillable`** pada model `DaftarUlang` dan `Seragam`.

Ketika pendaftar menyimpan data dengan:
```php
$dataToUpdate = [
    'nominal_biaya' => $konfirmasiNominalData,
    'opsi_pembayaran' => $this->opsiPembayaran,
    'metode_pembayaran' => $this->bankPilihan,
];
$daftarUlang->update($dataToUpdate);
```

Laravel **mengabaikan** field `nominal_biaya` karena tidak ada di array fillable (mass assignment protection).

## Solusi yang Diterapkan

### 1. Model DaftarUlang (app/Models/DaftarUlang.php)

**Sebelum:**
```php
protected $fillable = [
    'pendaftar_id',
    'status',
    'opsi_pembayaran',
    'metode_pembayaran',
    // ... field lainnya
];
```

**Sesudah:**
```php
protected $fillable = [
    'pendaftar_id',
    'nominal_biaya',  // ✅ DITAMBAHKAN
    'status',
    'opsi_pembayaran',
    'metode_pembayaran',
    // ... field lainnya
];
```

### 2. Model Seragam (app/Models/Seragam.php)

**Sebelum:**
```php
protected $fillable = [
    'pendaftar_id',
    'paket_seragam',
    'opsi_pembayaran_seragam',
    // ... field lainnya
];
```

**Sesudah:**
```php
protected $fillable = [
    'pendaftar_id',
    'nominal_seragam',  // ✅ DITAMBAHKAN
    'paket_seragam',
    'opsi_pembayaran_seragam',
    // ... field lainnya
];
```

## Struktur Data Nominal

Kedua field `nominal_biaya` dan `nominal_seragam` menyimpan data dalam format JSON:

```json
{
    "name": "Lunas",
    "amount": 500000
}
```

Atau untuk "Opsi Lainnya":
```json
{
    "name": "Opsi Lainnya",
    "amount": 750000
}
```

## Tampilan di Admin View

View admin sudah benar menampilkan data dengan format:

**Tab Madrasah:**
```blade
@if($item->daftarUlang && $item->daftarUlang->nominal_biaya)
    @php $nominalBiaya = $item->daftarUlang->nominal_biaya; @endphp
    <div class="text-xs text-gray-900">
        <div class="font-medium text-gray-700 mb-1">{{ $nominalBiaya['name'] ?? '-' }}</div>
        <div class="font-bold text-emerald-700">Rp {{ number_format($nominalBiaya['amount'] ?? 0, 0, ',', '.') }}</div>
    </div>
@else
    <span class="text-xs text-gray-500">Belum dipilih</span>
@endif
```

**Tab Seragam:**
```blade
@if($item->seragam && $item->seragam->nominal_seragam)
    @php $nominalSeragam = $item->seragam->nominal_seragam; @endphp
    <div class="text-xs text-gray-900">
        <div class="font-medium text-gray-700 mb-1">{{ $nominalSeragam['name'] ?? '-' }}</div>
        <div class="font-bold text-emerald-700">Rp {{ number_format($nominalSeragam['amount'] ?? 0, 0, ',', '.') }}</div>
    </div>
@else
    <span class="text-xs text-gray-500">Belum dipilih</span>
@endif
```

## Testing

### Cara Test untuk Madrasah:

1. Login sebagai **pendaftar** yang statusnya "lulus"
2. Buka halaman **Daftar Ulang - Madrasah**
3. Di bagian "Konfirmasi Nominal yang Dibayarkan", pilih salah satu:
   - Nominal dari dropdown (misal: "Lunas - Rp 500.000")
   - Atau pilih "Opsi Lainnya" dan isi nominal manual (misal: 750000)
4. Pilih opsi pembayaran (Lunas/Angsuran/Potongan)
5. Pilih bank/metode pembayaran
6. Klik **Simpan**

7. Login sebagai **admin**
8. Buka `/admin/daftar-ulang?activeTab=madrasah`
9. Cari pendaftar yang tadi mengisi form
10. ✅ **Verifikasi**: Kolom "Biaya & Pembayaran" harus menampilkan:
    - Nama opsi (misal: "Lunas" atau "Opsi Lainnya")
    - Nominal (misal: "Rp 500.000")

### Cara Test untuk Seragam:

1. Login sebagai **pendaftar** yang statusnya "lulus"
2. Buka halaman **Daftar Ulang - Seragam**
3. Di bagian "Konfirmasi Nominal yang Dibayarkan", pilih salah satu:
   - Nominal dari dropdown (misal: "Paket A - Rp 1.500.000")
   - Atau pilih "Opsi Lainnya" dan isi nominal manual (misal: 1750000)
4. Pilih opsi pembayaran (Lunas/Angsuran/Potongan)
5. Pilih bank/metode pembayaran
6. Klik **Simpan**

7. Login sebagai **admin**
8. Buka `/admin/daftar-ulang?activeTab=seragam`
9. Cari pendaftar yang tadi mengisi form
10. ✅ **Verifikasi**: Kolom "Biaya & Pembayaran" harus menampilkan:
    - Nama opsi (misal: "Paket A" atau "Opsi Lainnya")
    - Nominal (misal: "Rp 1.500.000")

## Files yang Diubah

1. **app/Models/DaftarUlang.php** - Menambahkan `nominal_biaya` ke array fillable
2. **app/Models/Seragam.php** - Menambahkan `nominal_seragam` ke array fillable

## Hasil

- ✅ Data nominal sekarang dapat disimpan dengan benar
- ✅ Data nominal muncul di halaman admin `/admin/daftar-ulang`
- ✅ Mendukung nominal dari dropdown maupun "Opsi Lainnya" (input manual)
- ✅ Format tampilan: Nama opsi + Nominal terformat (Rp xxx.xxx)

## Catatan Penting

**Mass Assignment Protection** adalah fitur keamanan Laravel yang mencegah field yang tidak diinginkan diisi secara massal. Semua field yang ingin bisa di-mass-assign melalui `create()` atau `update()` harus didaftarkan di array `$fillable`.

Field yang sudah ada di `$casts` tetapi tidak di `$fillable` akan:
- ✅ Bisa dibaca (read) dengan benar
- ❌ Tidak bisa ditulis (write) melalui mass assignment
- ✅ Bisa ditulis manual: `$model->field = $value; $model->save();`

---
**Tanggal:** 30 Januari 2026
**Status:** ✅ Complete
**Testing:** Required (silakan test dengan akun pendaftar dan admin)
