# Cara Test Warning Boxes di Tab Seragam

**Tanggal:** 8 Februari 2026

---

## ⚠️ PENTING: Kapan Warning Muncul?

Warning boxes **HANYA muncul** dalam kondisi tertentu:

| Status Seragam | Warning Box | Warna | Kapan Muncul |
|----------------|-------------|-------|--------------|
| `'disetujui'` | ✅ YA | 🟢 HIJAU | Data sudah disetujui admin |
| `'menunggu'` | ✅ YA | 🟡 KUNING | Data sedang diverifikasi |
| `NULL` (kosong) | ❌ TIDAK | - | Belum pernah submit |
| `'ditolak'` | ❌ TIDAK | - | Ditolak, bisa edit lagi |
| `'perbaiki'` | ❌ TIDAK | - | Perlu perbaikan, bisa edit |

**Jadi jika Anda tidak melihat warning boxes, kemungkinan:**
- Anda belum pernah submit data seragam
- Status seragam bukan 'disetujui' atau 'menunggu'

---

## 🧪 Cara Test (3 Skenario)

### Persiapan

1. **Login sebagai pendaftar** di browser
2. **Buka tab baru** untuk PostgreSQL
3. **Cari email Anda:**

```sql
-- Cek email yang sedang login
SELECT u.email, u.name
FROM users u
JOIN pendaftar p ON u.id = p.user_id
WHERE u.email LIKE '%YOUR_EMAIL%';
```

---

### Test 1: Warning HIJAU (Disetujui) ✅

#### Step 1: Set Status di Database
```sql
-- GANTI 'test@example.com' dengan email Anda!
UPDATE seragam
SET status_seragam = 'disetujui'
WHERE pendaftar_id IN (
    SELECT p.id FROM pendaftar p
    JOIN users u ON p.user_id = u.id
    WHERE u.email = 'test@example.com'
);
```

#### Step 2: Reload Browser
```
https://localhost:8000/pendaftar/daftarulang?activeTab=seragam
```
atau
```
http://127.0.0.1:8000/pendaftar/daftarulang?activeTab=seragam
```

Tekan **Ctrl + Shift + R** (hard reload)

#### Step 3: Verify
**HARUS MUNCUL:**
- 🟢 **Green box** di atas "Step 1: Pilih Paket Seragam & Nominal"
- ✅ Icon checkmark hijau
- 📝 Text: **"Data Daftar Ulang Seragam Sudah Disetujui"**
- 📝 Subtext: "Data daftar ulang seragam tidak dapat diubah..."
- 🔒 Form fields ter-disable (tidak bisa diisi)

**Screenshot untuk perbandingan:**
```
┌─────────────────────────────────────────────────┐
│ 🟢 ✓ Data Daftar Ulang Seragam Sudah Disetujui │
│    Data daftar ulang seragam tidak dapat        │
│    diubah karena sudah disetujui oleh panitia.  │
└─────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────┐
│ 📘 Step 1: Pilih Paket Seragam & Nominal       │
│                                                  │
│ [Dropdown disabled]                             │
└─────────────────────────────────────────────────┘
```

---

### Test 2: Warning KUNING (Menunggu) ⏳

#### Step 1: Set Status di Database
```sql
-- GANTI 'test@example.com' dengan email Anda!
UPDATE seragam
SET status_seragam = 'menunggu'
WHERE pendaftar_id IN (
    SELECT p.id FROM pendaftar p
    JOIN users u ON p.user_id = u.id
    WHERE u.email = 'test@example.com'
);
```

#### Step 2: Reload Browser
Tekan **Ctrl + Shift + R**

#### Step 3: Verify
**HARUS MUNCUL:**
- 🟡 **Yellow box** di atas "Step 1: Pilih Paket Seragam & Nominal"
- ⏰ Icon clock kuning
- 📝 Text: **"Data Daftar Ulang Seragam Sedang Menunggu Persetujuan"**
- 📝 Subtext: "Data daftar ulang seragam yang Anda kirim sedang dalam proses verifikasi..."
- 🔒 Form fields ter-disable

**Screenshot untuk perbandingan:**
```
┌──────────────────────────────────────────────────────┐
│ 🟡 ⏰ Data Daftar Ulang Seragam Sedang Menunggu     │
│       Persetujuan                                    │
│    Data daftar ulang seragam yang Anda kirim sedang │
│    dalam proses verifikasi oleh panitia. Form tidak │
│    dapat diubah saat menunggu persetujuan.          │
└──────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────┐
│ 📘 Step 1: Pilih Paket Seragam & Nominal            │
│                                                       │
│ [Dropdown disabled]                                  │
└──────────────────────────────────────────────────────┘
```

---

### Test 3: TIDAK ADA Warning (Normal) ✅

#### Step 1: Set Status di Database
```sql
-- GANTI 'test@example.com' dengan email Anda!
UPDATE seragam
SET status_seragam = NULL
WHERE pendaftar_id IN (
    SELECT p.id FROM pendaftar p
    JOIN users u ON p.user_id = u.id
    WHERE u.email = 'test@example.com'
);
```

#### Step 2: Reload Browser
Tekan **Ctrl + Shift + R**

#### Step 3: Verify
**HARUS:**
- ❌ **TIDAK ADA** warning box (hijau atau kuning)
- ✅ Form langsung mulai dari "Step 1: Pilih Paket Seragam & Nominal"
- ✅ Form fields bisa diisi (tidak disabled)

**Screenshot untuk perbandingan:**
```
┌──────────────────────────────────────────────────────┐
│ 📘 Step 1: Pilih Paket Seragam & Nominal            │
│                                                       │
│ Pilih Paket Seragam *                               │
│ [Dropdown enabled - bisa dipilih]                   │
└──────────────────────────────────────────────────────┘
```

---

## 🔍 Troubleshooting

### Masalah: Warning Tidak Muncul Setelah Set Status

**Solusi 1: Clear Cache**
```bash
php artisan view:clear
php artisan cache:clear
php artisan config:clear
```

**Solusi 2: Hard Reload Browser**
- Windows/Linux: **Ctrl + Shift + R**
- Mac: **Cmd + Shift + R**

**Solusi 3: Clear Browser Cache**
- Chrome: Buka DevTools (F12) → Network tab → Centang "Disable cache"
- Atau: Settings → Privacy → Clear browsing data

**Solusi 4: Verify Status di Database**
```sql
SELECT
    s.status_seragam,
    u.email,
    u.name
FROM seragam s
JOIN pendaftar p ON s.pendaftar_id = p.id
JOIN users u ON p.user_id = u.id
WHERE u.email = 'test@example.com';
```

**Expected Output:**
```
status_seragam | email              | name
---------------|--------------------|-----------
disetujui      | test@example.com   | Test User
```

---

### Masalah: Tidak Ada Data Seragam

**Cek apakah ada data:**
```sql
SELECT COUNT(*) FROM seragam
WHERE pendaftar_id IN (
    SELECT p.id FROM pendaftar p
    JOIN users u ON p.user_id = u.id
    WHERE u.email = 'test@example.com'
);
```

**Jika hasil = 0 (tidak ada data):**
```sql
-- Buat data seragam dummy
INSERT INTO seragam (
    pendaftar_id,
    status_seragam,
    paket_seragam,
    opsi_pembayaran_seragam,
    created_at,
    updated_at
)
SELECT
    p.id,
    'disetujui',
    '{"id":"pkg_1","name":"Paket Test"}',
    'Lunas',
    NOW(),
    NOW()
FROM pendaftar p
JOIN users u ON p.user_id = u.id
WHERE u.email = 'test@example.com'
AND NOT EXISTS (
    SELECT 1 FROM seragam s WHERE s.pendaftar_id = p.id
);
```

---

### Masalah: Warning Muncul Tapi Tidak Sesuai

**Verify kode di file:**
```bash
# Cek apakah perubahan sudah ada di file
grep -n "isSeragamWaiting" resources/views/livewire/pendaftar/daftar-ulang.blade.php

# Harus muncul di 2 tempat:
# Line 1207: Variable definition
# Line 1346: Warning box condition
```

**Expected Output:**
```
1207:        $isSeragamWaiting = $seragam && $seragam->status_seragam === 'menunggu';
1346:    @if($isSeragamWaiting)
```

---

## 📊 Quick Reference

### Command Cheat Sheet
```bash
# Clear all cache
php artisan optimize:clear

# Check specific file
grep "isSeragamApproved\|isSeragamWaiting" resources/views/livewire/pendaftar/daftar-ulang.blade.php | head -5

# Verify database
psql -U your_user -d your_database -c "SELECT status_seragam, COUNT(*) FROM seragam GROUP BY status_seragam;"
```

### SQL Cheat Sheet
```sql
-- Set disetujui
UPDATE seragam SET status_seragam = 'disetujui' WHERE pendaftar_id = 1;

-- Set menunggu
UPDATE seragam SET status_seragam = 'menunggu' WHERE pendaftar_id = 1;

-- Reset ke normal
UPDATE seragam SET status_seragam = NULL WHERE pendaftar_id = 1;

-- Check status
SELECT status_seragam FROM seragam WHERE pendaftar_id = 1;
```

---

## ✅ Checklist Testing

- [ ] Test 1: Set status = 'disetujui' → Green box muncul ✅
- [ ] Test 2: Set status = 'menunggu' → Yellow box muncul ✅
- [ ] Test 3: Set status = NULL → Tidak ada box ✅
- [ ] Hard reload browser (Ctrl + Shift + R) ✅
- [ ] Cache cleared (php artisan optimize:clear) ✅
- [ ] Form disabled saat approved/menunggu ✅
- [ ] Form enabled saat NULL/ditolak/perbaiki ✅
- [ ] Icon dan warna sesuai (green checkmark, yellow clock) ✅
- [ ] Text sesuai dengan spesifikasi ✅

---

## 🎯 Expected Final Result

Setelah semua test, Anda harus bisa melihat:

1. **Saat status = 'disetujui':** 🟢 Green warning box
2. **Saat status = 'menunggu':** 🟡 Yellow warning box
3. **Saat status = NULL/ditolak/perbaiki:** ❌ No warning box

Sama persis seperti di tab **Ukuran** (`/pendaftar/daftarulang?activeTab=ukuran`)!

---

**Happy Testing!** 🚀

Jika masih tidak muncul setelah semua langkah di atas, hubungi untuk troubleshooting lebih lanjut.
