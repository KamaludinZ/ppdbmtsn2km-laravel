# FIX STATS OVERVIEW - HISTORY WHATSAPP

## Tanggal
4 Februari 2026

## Masalah
Stats overview di `/admin/pengaturan-pesan?activeTab=history-whatsapp` tidak berfungsi dengan benar atau menampilkan angka 0 semua.

## Root Cause
Fungsi `getQueueStatistics()` di `PengaturanPesan.php` menggunakan `WhatsAppQueueService` yang tidak menghitung dengan benar dari database.

### Kode Lama (SALAH)
```php
public function getQueueStatistics()
{
    $service = app(WhatsAppQueueService::class);
    return $service->getStatistics(); // ❌ Tidak menghitung dari database
}
```

## Solusi yang Diterapkan

### Update fungsi getQueueStatistics()
Hitung stats langsung dari tabel `riwayat_pesan` dan `message_queue`:

```php
public function getQueueStatistics()
{
    // Count from RiwayatPesan (history)
    $sent = RiwayatPesan::where('jenis_pesan', 'whatsapp')
        ->where('status', 'terkirim')
        ->count();

    $failed = RiwayatPesan::where('jenis_pesan', 'whatsapp')
        ->where('status', 'gagal')
        ->count();

    $pending = RiwayatPesan::where('jenis_pesan', 'whatsapp')
        ->where('status', 'pending')
        ->count();

    // Count from MessageQueue (queue system)
    $queued = MessageQueue::where('jenis_pesan', 'whatsapp')
        ->whereIn('status', ['queued', 'processing'])
        ->count();

    $today = RiwayatPesan::where('jenis_pesan', 'whatsapp')
        ->where('status', 'terkirim')
        ->whereDate('sent_at', today())
        ->count();

    return [
        'total' => $sent + $failed + $pending,
        'queued' => $queued,
        'sent' => $sent,
        'failed' => $failed,
        'today' => $today,
    ];
}
```

## Stats yang Akan Ditampilkan

### 1. Total Pesan
**Warna**: Biru
**Menghitung**: Semua pesan WhatsApp di tabel `riwayat_pesan` (terkirim + gagal + pending)
**Query**: `$sent + $failed + $pending`

### 2. Antrian
**Warna**: Kuning
**Menghitung**: Pesan yang masih dalam queue di tabel `message_queue`
**Query**: `MessageQueue` dengan status `queued` atau `processing`
**Note**: Untuk sistem queue WhatsApp (fitur terjadwal)

### 3. Terkirim
**Warna**: Hijau
**Menghitung**: Pesan yang berhasil terkirim dari `riwayat_pesan`
**Query**: Status `terkirim`

### 4. Gagal
**Warna**: Merah
**Menghitung**: Pesan yang gagal terkirim dari `riwayat_pesan`
**Query**: Status `gagal`

### 5. Hari Ini
**Warna**: Biru
**Menghitung**: Pesan yang terkirim hari ini dari `riwayat_pesan`
**Query**: Status `terkirim` dengan `sent_at` = today

## File yang Diupdate
✅ `app/Livewire/Admin/PengaturanPesan.php`

## Cara Upload ke Production

### Opsi 1: Via Batch File
```bash
upload_fix_stats_whatsapp.bat
```

### Opsi 2: Manual via SCP
```bash
scp app/Livewire/Admin/PengaturanPesan.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
```

### Opsi 3: Manual via FTP/cPanel
Upload file ke: `/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/PengaturanPesan.php`

## Langkah Setelah Upload

### 1. Clear Cache
```bash
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
```

### 2. Set Permission (Optional)
```bash
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && chmod 644 app/Livewire/Admin/PengaturanPesan.php"
```

## Testing

### Test 1: Stats Overview Tampil dengan Benar
1. Login sebagai Admin
2. Buka `/admin/pengaturan-pesan?activeTab=history-whatsapp`
3. **Stats overview harus menampilkan angka yang benar**:
   - **Total Pesan**: Harus menampilkan jumlah semua history WhatsApp
   - **Antrian**: Jumlah pesan di queue (biasanya 0 jika tidak ada queue)
   - **Terkirim**: Jumlah pesan yang berhasil terkirim ✅
   - **Gagal**: Jumlah pesan yang gagal ❌
   - **Hari Ini**: Jumlah terkirim hari ini

### Test 2: Stats Update Setelah Kirim Pesan
1. Catat angka stats saat ini
2. Kirim test WhatsApp dari `/admin/settings`
3. Refresh tab history-whatsapp
4. **Stats harus terupdate**:
   - **Total Pesan**: +1
   - **Terkirim**: +1 (jika berhasil) ATAU **Gagal**: +1 (jika gagal)
   - **Hari Ini**: +1 (jika terkirim hari ini)

### Test 3: Stats Konsisten dengan Tabel
1. Hitung manual jumlah WhatsApp terkirim di tabel history
2. Bandingkan dengan angka di stats overview
3. Harus sama ✅

## Contoh Tampilan Stats

### Sebelum Fix (SALAH)
```
┌─────────────┬─────────┬──────────┬────────┬──────────┐
│ Total Pesan │ Antrian │ Terkirim │ Gagal  │ Hari Ini │
├─────────────┼─────────┼──────────┼────────┼──────────┤
│      0      │    0    │     0    │   0    │     0    │
└─────────────┴─────────┴──────────┴────────┴──────────┘
❌ Semua 0 padahal ada history WhatsApp
```

### Setelah Fix (BENAR)
```
┌─────────────┬─────────┬──────────┬────────┬──────────┐
│ Total Pesan │ Antrian │ Terkirim │ Gagal  │ Hari Ini │
├─────────────┼─────────┼──────────┼────────┼──────────┤
│     25      │    3    │    20    │   5    │    12    │
└─────────────┴─────────┴──────────┴────────┴──────────┘
✅ Menampilkan data real dari database
```

**Penjelasan**:
- **Total**: 25 (20 terkirim + 5 gagal)
- **Antrian**: 3 pesan masih di queue
- **Terkirim**: 20 pesan berhasil
- **Gagal**: 5 pesan gagal
- **Hari Ini**: 12 pesan terkirim hari ini

## Perbedaan dengan Stats Email

| Aspek | Email Stats | WhatsApp Stats |
|-------|-------------|----------------|
| Sumber Data | `riwayat_pesan` saja | `riwayat_pesan` + `message_queue` |
| Antrian | Tidak ada | Ada (sistem queue) |
| Real-time | Ya | Ya |

**Note**: WhatsApp memiliki sistem antrian (queue) untuk pesan terjadwal, sedangkan email langsung kirim tanpa queue.

## Troubleshooting

### Jika Stats Masih 0 Semua

1. **Cek tabel riwayat_pesan**:
   ```sql
   SELECT COUNT(*) FROM riwayat_pesan WHERE jenis_pesan = 'whatsapp';
   ```
   Jika 0, berarti belum ada history WhatsApp.

2. **Kirim test WhatsApp**:
   - Buka `/admin/settings`
   - Kirim test WhatsApp
   - Cek history muncul

3. **Clear cache lagi**:
   ```bash
   php artisan optimize:clear
   ```

### Jika Stats Tidak Update

1. **Refresh halaman** dengan Ctrl+F5 (hard refresh)
2. **Clear browser cache**
3. **Cek file terupload**:
   ```bash
   grep "Count from RiwayatPesan" app/Livewire/Admin/PengaturanPesan.php
   ```
   Harus muncul baris komentar tersebut

### Jika Antrian Selalu 0

Ini normal jika Anda tidak menggunakan sistem queue untuk WhatsApp. Antrian hanya akan terisi jika ada pesan yang dijadwalkan untuk dikirim nanti.

## Kesimpulan

**Stats overview di tab history-whatsapp sudah diperbaiki** untuk menghitung langsung dari database:
- ✅ Total Pesan: dari `riwayat_pesan`
- ✅ Antrian: dari `message_queue`
- ✅ Terkirim: dari `riwayat_pesan` (status terkirim)
- ✅ Gagal: dari `riwayat_pesan` (status gagal)
- ✅ Hari Ini: dari `riwayat_pesan` (terkirim hari ini)

Setelah upload dan clear cache, stats overview akan menampilkan angka yang akurat! 📊✅
