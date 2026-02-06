# Quick Guide: Debug WhatsApp Tidak Terkirim

**Masalah:** Notifikasi "Berhasil" tapi WhatsApp tidak sampai ke nomor orang tua.

---

## 🔍 Cek Log Error (LANGKAH PERTAMA!)

```bash
# SSH ke server
ssh mtsnkot4@103.30.244.77

# Cek log WhatsApp
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
tail -100 storage/logs/laravel.log | grep -A 10 "WhatsApp\|Gateway"
```

---

## ✅ Checklist Debugging

### 1. Cek Konfigurasi WhatsApp Gateway

**Menu:** Admin → Settings → WhatsApp

**Yang Harus Diisi:**
- ✅ WhatsApp API URL: `https://wgw.mtsn2kotamalang.sch.id`
- ✅ WhatsApp API Key/Token: `(API Key dari gateway)`
- ✅ Nomor Pengirim: `081234567890` (untuk gateway MTsN 2)

**Test Konfigurasi:**
```bash
php artisan tinker
> \App\Models\Setting::where('key', 'like', 'whatsapp%')->get(['key', 'value']);
```

---

### 2. Cek Device WhatsApp Tersambung

**Dashboard Gateway:**
```
https://wgw.mtsn2kotamalang.sch.id/dashboard
```

**Verifikasi:**
- ✅ Status device: **Connected** (hijau)
- ✅ QR Code sudah di-scan
- ✅ WhatsApp di HP tidak logout

**Jika Disconnected:**
- Scan ulang QR Code di dashboard gateway
- Pastikan HP dengan WhatsApp Business aktif dan online

---

### 3. Cek Format Nomor Orang Tua

**Menu:** Admin → Data Pendaftar → Aktifkan kolom "Ortu WhatsApp"

**Format yang Benar:**
- ✅ `081234567890`
- ✅ `+6281234567890`
- ✅ `6281234567890`

**Format yang Salah:**
- ❌ `0812-3456-7890` (ada dash)
- ❌ `(0812) 345678` (ada tanda kurung)
- ❌ Kosong atau `-`

**Edit Nomor:**
Klik icon **pensil** di kolom Ortu WhatsApp untuk edit.

---

### 4. Cek Kuota/Limit Gateway

**Dashboard Gateway:**
- Cek sisa kuota pesan
- Cek limit harian

**Jika Kuota Habis:**
- Hubungi penyedia gateway untuk isi ulang
- Atau upgrade paket gateway

---

### 5. Test Kirim Manual dengan Monitoring

**Step-by-step:**

1. **Buka terminal untuk monitoring log:**
   ```bash
   ssh mtsnkot4@103.30.244.77
   cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
   tail -f storage/logs/laravel.log | grep --line-buffered "WhatsApp"
   ```

2. **Di browser:**
   - Login Admin
   - Data Pendaftar
   - Pilih pendaftar dengan nomor valid
   - Klik "Kirim WhatsApp"
   - Isi pesan singkat: "Test"
   - Kirim

3. **Lihat output di terminal**
   - Jika sukses: `"status":true`
   - Jika gagal: Lihat error message

---

## 🔴 Error Messages dan Solusinya

| Error | Penyebab | Solusi |
|-------|----------|--------|
| `Device not connected` | HP WhatsApp offline | Login ke dashboard gateway, scan QR |
| `Invalid API Key` | API Key salah | Update API Key di Settings |
| `Failed to send` | Device logout | Scan ulang QR Code |
| `Nomor tidak ditemukan` | Field kosong | Edit nomor ortu |
| `400 Bad Request` | Parameter salah | Cek format nomor |
| `404 Not Found` | URL salah | Jangan tambahkan `/send-message` di URL |
| `Kuota habis` | Limit tercapai | Hubungi provider gateway |

---

## 🧪 Test Manual via Tinker

```bash
php artisan tinker

# Test kirim WhatsApp langsung
$service = new \App\Services\WhatsAppGatewayService();
$result = $service->sendMessage('081234567890', 'Test pesan');

# Lihat hasilnya
print_r($result);

# Jika sukses: ["success" => true, "data" => [...]]
# Jika gagal: ["success" => false, "message" => "error..."]
```

---

## 📞 Hubungi Tim Jika:

- ❌ Device selalu disconnect
- ❌ API Key tidak valid terus
- ❌ Semua checklist sudah OK tapi tetap gagal
- ❌ Log menunjukkan error yang tidak dipahami

**Kirim ke Tim:**
- Screenshot dashboard gateway
- Screenshot Settings WhatsApp di admin
- Copy error message dari log

---

## ✅ Verifikasi Berhasil

**Tanda WhatsApp Berhasil Terkirim:**
- ✅ Notifikasi "Berhasil" muncul di admin
- ✅ Log menunjukkan `"status":true`
- ✅ WhatsApp diterima di HP orang tua
- ✅ Riwayat pesan tersimpan dengan status "terkirim"

**Cek Riwayat Pesan:**
- Menu: Admin → Data Pendaftar
- Klik "Lihat Detail" pada pendaftar
- Tab "Riwayat Pesan"

---

**Dibuat:** 2 Februari 2026
**Update terakhir:** 2 Februari 2026
