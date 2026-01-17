# Panduan Setup WhatsApp Gateway - PPDB MTsN 2 Kota Malang

## Overview

Sistem PPDB ini mendukung integrasi dengan berbagai penyedia WhatsApp Gateway untuk mengirim notifikasi otomatis kepada pendaftar. Sistem akan otomatis mendeteksi provider berdasarkan URL yang dikonfigurasi.

## Supported Providers

1. **MTsN 2 Kota Malang Custom Gateway** ⭐ RECOMMENDED
2. Fonnte
3. Wablas
4. WA API
5. Meta WhatsApp Business API
6. Custom Gateway (format standar)

---

## 1. Setup MTsN 2 Malang Custom Gateway (RECOMMENDED)

### 📋 Informasi Gateway

- **URL**: `https://wgw.mtsn2kotamalang.sch.id`
- **Dokumentasi**: https://wgw.mtsn2kotamalang.sch.id/api-docs
- **Endpoint**: `/send-message`
- **Method**: POST atau GET

### ✅ Kelebihan

- ✅ Server lokal - response lebih cepat
- ✅ Tidak ada biaya tambahan
- ✅ Full control
- ✅ Support 24/7 dari IT MTsN 2

### 🔧 Konfigurasi

#### Melalui .env File:

```env
WHATSAPP_GATEWAY_URL=https://wgw.mtsn2kotamalang.sch.id
WHATSAPP_ACCESS_TOKEN=YOUR_API_KEY_HERE
WHATSAPP_SENDER_NUMBER=628xxx
```

> ⚠️ **PENTING**: Isi URL base saja (`https://wgw.mtsn2kotamalang.sch.id`), JANGAN tambahkan `/send-message` di akhir!

#### Melalui Admin Dashboard:

1. Login sebagai Admin
2. Menu **Settings** → Tab **WhatsApp**
3. Isi form:
   - **WhatsApp API URL**: `https://wgw.mtsn2kotamalang.sch.id` ← **TANPA /send-message**
   - **WhatsApp API Key**: API Key yang didapat dari IT
   - **Nomor Pengirim**: Nomor WhatsApp device Anda (format: 628xxx)
   - **Enable WhatsApp**: ☑️ Centang
4. Klik **Test Connection** untuk test koneksi
5. Klik **Send Test Message** untuk kirim pesan test
6. Jika berhasil, klik **Save Settings**

### 📝 Format API Request

**POST Request:**
```json
{
  "api_key": "YOUR_API_KEY",
  "sender": "628888xxxx",
  "number": "628888xxxx",
  "message": "Hello World"
}
```

**GET Request:**
```
https://wgw.mtsn2kotamalang.sch.id/send-message?api_key=xxx&sender=628xxx&number=628xxx&message=Hello
```

### 🆘 Troubleshooting

**Error: "Sender number belum dikonfigurasi"**
- Pastikan field **Nomor Pengirim** sudah diisi
- Format harus: `628xxx` (dengan 62, tanpa +, tanpa spasi)

**Error: "API Key tidak valid"**
- Periksa API Key Anda
- Hubungi IT untuk mendapatkan API Key baru

**Error: "Endpoint tidak ditemukan"**
- Pastikan URL: `https://wgw.mtsn2kotamalang.sch.id` (tanpa trailing slash)
- Jangan tambahkan `/send-message` di URL settings

---

## 2. Setup Fonnte

### 🔧 Konfigurasi

```env
WHATSAPP_GATEWAY_URL=https://api.fonnte.com
WHATSAPP_ACCESS_TOKEN=your-fonnte-token-here
```

### 📝 Cara Mendapatkan Token

1. Daftar di https://fonnte.com
2. Scan QR Code dengan WhatsApp
3. Copy token dari dashboard
4. Paste ke WHATSAPP_ACCESS_TOKEN

---

## 3. Setup Wablas

### 🔧 Konfigurasi

```env
WHATSAPP_GATEWAY_URL=https://solo.wablas.com
WHATSAPP_ACCESS_TOKEN=your-wablas-token-here
```

### 📝 Cara Mendapatkan Token

1. Daftar di https://wablas.com
2. Pilih paket yang sesuai
3. Hubungkan WhatsApp device
4. Copy token dari dashboard

---

## 4. Setup WA API

### 🔧 Konfigurasi

```env
WHATSAPP_GATEWAY_URL=https://waapi.app/api/v1/instances/YOUR_INSTANCE_ID
WHATSAPP_ACCESS_TOKEN=your-waapi-token-here
```

---

## 5. Setup Meta WhatsApp Business API

### 🔧 Konfigurasi

```env
WHATSAPP_GATEWAY_URL=https://graph.facebook.com/v18.0/YOUR_PHONE_ID/messages
WHATSAPP_ACCESS_TOKEN=your-meta-access-token-here
```

---

## Testing Konfigurasi

### 1. Test via Admin Dashboard

1. Login sebagai Admin
2. Menu **Settings** → Tab **WhatsApp**
3. Isi semua field yang required
4. Klik **Test Connection** (opsional)
5. Isi **Nomor Test** dengan nomor WhatsApp Anda
6. Klik **Send Test Message**
7. Check WhatsApp Anda, seharusnya menerima pesan

### 2. Test via Artisan Command (Advanced)

```bash
php artisan tinker

# Test send message
$service = new \App\Services\WhatsAppGatewayService();
$result = $service->sendMessage('628xxx', 'Test message');
dd($result);
```

---

## Notifikasi Otomatis

Sistem akan otomatis mengirim WhatsApp untuk event berikut:

### ✅ Untuk Pendaftar

1. **Pendaftaran Selesai**
   - Trigger: Setelah submit formulir pendaftaran
   - Isi: Nomor pendaftaran, jalur, info selanjutnya

2. **Verifikasi Berkas**
   - Trigger: Admin verifikasi dokumen
   - Isi: Status verifikasi (diterima/ditolak + alasan)

3. **Pengumuman Kelulusan**
   - Trigger: Admin update status kelulusan
   - Isi: Status lulus/tidak lulus, info daftar ulang

4. **Reminder Daftar Ulang**
   - Trigger: Manual atau scheduled
   - Isi: Deadline, syarat, lokasi

### 🔔 Untuk Admin/Panitia

1. **Pendaftar Baru**
   - Trigger: Ada pendaftar baru
   - Isi: Nama, jalur, waktu daftar

2. **Upload Dokumen Baru**
   - Trigger: Pendaftar upload dokumen
   - Isi: Nama pendaftar, jenis dokumen

---

## Disable WhatsApp Notifications

Jika ingin menonaktifkan notifikasi WhatsApp sementara:

### Via Admin Dashboard:
1. Menu **Settings** → Tab **WhatsApp**
2. Uncheck **Enable WhatsApp**
3. Save Settings

### Via .env:
```env
WHATSAPP_GATEWAY_URL=
WHATSAPP_ACCESS_TOKEN=
```

---

## Error Messages & Solutions

### Error 404 - Endpoint tidak ditemukan
**Penyebab**: URL API salah atau endpoint tidak tersedia
**Solusi**:
- Periksa URL API (jangan ada typo)
- Pastikan tidak ada trailing slash di akhir URL
- Untuk MTsN 2: gunakan `https://wgw.mtsn2kotamalang.sch.id`

### Error 401/403 - Unauthorized
**Penyebab**: API Key/Token tidak valid
**Solusi**:
- Periksa API Key sudah benar
- Pastikan API Key belum expired
- Regenerate API Key dari provider

### Error 400 - Bad Request
**Penyebab**: Parameter tidak lengkap atau format salah
**Solusi**:
- Untuk MTsN 2: pastikan Nomor Pengirim sudah diisi
- Check format nomor: 628xxx (tanpa +, tanpa spasi, tanpa -)

### Connection Timeout
**Penyebab**: Koneksi internet lambat atau server gateway down
**Solusi**:
- Periksa koneksi internet
- Coba lagi beberapa saat
- Hubungi provider gateway

---

## Monitoring & Logs

### View Logs

Semua aktivitas WhatsApp dicatat di log file:

```bash
# View latest logs
tail -f storage/logs/laravel.log | grep WhatsApp

# Search specific date
grep "2026-01-11" storage/logs/laravel-2026-01-11.log | grep WhatsApp
```

### Log Format

```
[2026-01-11 14:00:00] local.INFO: WhatsApp Message Sent: {
    "provider": "mtsn2malang",
    "to": "628888xxxx",
    "status": true
}
```

---

## Best Practices

1. **Selalu Test Dulu**
   - Test dengan 1-2 nomor dulu sebelum produksi
   - Gunakan fitur "Send Test Message"

2. **Monitor Logs**
   - Cek logs secara berkala
   - Setup alert untuk error

3. **Backup Config**
   - Simpan API Key di tempat aman
   - Backup .env file

4. **Rate Limiting**
   - Jangan spam - beri jeda antar pesan
   - Gunakan queue untuk bulk message

5. **Content**
   - Pesan harus jelas dan informatif
   - Sertakan link jika perlu
   - Jangan terlalu panjang

---

## FAQ

**Q: Apakah bisa pakai multiple gateway?**
A: Tidak, sistem hanya support 1 gateway aktif. Pilih yang paling reliable.

**Q: Berapa biaya menggunakan MTsN 2 Gateway?**
A: Gratis untuk aplikasi internal MTsN 2.

**Q: Apakah WhatsApp wajib diaktifkan?**
A: Tidak wajib. Sistem tetap berjalan normal tanpa WhatsApp.

**Q: Bagaimana jika gateway down?**
A: Sistem akan log error tapi tidak menghentikan proses utama. Notifikasi akan gagal terkirim.

**Q: Bisa kirim gambar/file?**
A: Saat ini hanya support text message. Fitur media akan ditambahkan di versi mendatang.

---

## Support

Untuk bantuan setup WhatsApp Gateway:

- **IT MTsN 2 Kota Malang**: it@mtsn2kotamalang.sch.id
- **Developer**: support-ppdb@mtsn2kotamalang.sch.id
- **Documentation**: https://github.com/mtsn2malang/ppdb-docs

---

**Terakhir Update**: Januari 2026
**Version**: 1.0
