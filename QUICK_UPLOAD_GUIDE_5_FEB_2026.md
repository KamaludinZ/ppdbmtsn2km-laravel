# QUICK UPLOAD GUIDE - 5 FEBRUARI 2026

## 🚀 CARA CEPAT UPLOAD

### Opsi 1: AUTO UPLOAD (RECOMMENDED)

```bash
upload_all_fixes_5_feb_2026.bat
```

**Keterangan**:
- Upload 13 files sekaligus
- Run migration otomatis
- Clear cache otomatis
- Total waktu: ~10-15 menit

---

### Opsi 2: MANUAL UPLOAD (Step by Step)

#### 1️⃣ Backup Database
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
pg_dump -U mtsnkot4 -d mtsnkot4_ppdb > backup_5feb2026.sql
```

#### 2️⃣ Upload Files
```bash
# Models (1)
scp app/Models/MessageQueue.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/

# Services (1)
scp app/Services/FileNamingService.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Services/

# Controllers (6)
scp app/Livewire/Admin/Pendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
scp app/Livewire/Panitia/Pendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/
scp app/Livewire/Admin/PengaturanPesan.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
scp app/Livewire/Admin/Settings.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
scp app/Livewire/Admin/DetailPendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/
scp app/Livewire/Panitia/DetailPendaftar.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/

# Views (7)
scp resources/views/livewire/admin/pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
scp resources/views/livewire/panitia/pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/
scp resources/views/livewire/admin/detail-pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
scp resources/views/livewire/panitia/detail-pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/panitia/
scp resources/views/livewire/admin/partials/history-email-tab.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/
scp resources/views/livewire/admin/partials/history-whatsapp-tab.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/partials/
scp resources/views/livewire/pendaftar/formulir.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/

# Templates (1)
scp resources/views/tab_templates/tab-info-akun.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/tab_templates/

# Migrations (1)
scp database/migrations/2026_02_03_210000_create_message_queue_table.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/
```

#### 3️⃣ Run Migration & Clear Cache
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Run migration
php artisan migrate --force

# Clear cache
php artisan optimize:clear
```

---

## 📊 RINGKASAN FILE

| Kategori | Jumlah | Files |
|----------|--------|-------|
| Models | 1 | MessageQueue.php |
| Services | 1 | FileNamingService.php |
| Controllers | 6 | Pendaftar (Admin/Panitia), Settings, PengaturanPesan, DetailPendaftar (Admin/Panitia) |
| Views | 7 | Admin/Panitia pendaftar & detail-pendaftar, history tabs, formulir |
| Templates | 1 | tab-info-akun.blade.php |
| Migrations | 1 | 2026_02_03_210000_create_message_queue_table.php |
| **TOTAL** | **17** | |

**Setelah deduplikasi**: **13 files unik**

---

## ✅ FITUR YANG DI-UPLOAD

### 1. **History Pesan & WhatsApp Queue** (3 Feb)
- ✅ Kolom "Isi Pesan" dengan modal viewer
- ✅ Kolom "Waktu Tersisa" dengan countdown badge
- ✅ 5 kartu statistik (Total, Pending, Terkirim, Gagal, Hari Ini)
- ✅ Filter EMIS Completeness
- ✅ Bug fix pagination error

### 2. **Test Email/WA History** (4 Feb)
- ✅ Test dari `/admin/settings` sekarang menyimpan history
- ✅ Status: pending → terkirim/gagal

### 3. **File Timestamp Display** (5 Feb)
- ✅ Nama file dengan timestamp
- ✅ Format: `rapor-PPDB2024001-Ahmad_Fulan-20260205_143052.pdf`
- ✅ Menggunakan FileNamingService

### 4. **Tab Info Akun**
- ✅ Tab baru di detail pendaftar (Admin & Panitia)
- ✅ Data dari registration Step 3
- ✅ Field WhatsApp orang tua
- ✅ Editable

### 5. **Tab Survey Movement**
- ✅ Tab Survey pindah ke kanan tab Tahfidz
- ✅ Enhanced info box

---

## 🧪 QUICK TEST

**After upload, test ini:**

```
1. /admin/pendaftar → Filter EMIS works?
2. /admin/settings → Test Email → Check history
3. /admin/settings → Test WhatsApp → Check history
4. /admin/pendaftar/[ID]?activeTab=dokumen → Upload file → Timestamp muncul?
5. /admin/pendaftar/[ID]?activeTab=infoakun → Tab Info Akun ada?
6. /pendaftar/formulir → Tab Survey posisi benar?
```

---

## ⚠️ TROUBLESHOOTING

### Error: Migration sudah jalan
```bash
# Skip migration, hanya clear cache
php artisan optimize:clear
```

### Error: File tidak update
```bash
# Clear view cache
rm -rf storage/framework/views/*.php
php artisan view:clear
php artisan optimize:clear
```

### Error: 500 di halaman tertentu
```bash
# Cek log
tail -f storage/logs/laravel.log

# Cek migration status
php artisan migrate:status
```

---

## 📞 CONTACT

**Jika ada masalah:**
1. Cek `RANGKUMAN_UPLOAD_LENGKAP_5_FEB_2026.md` untuk detail lengkap
2. Cek `FIX_FILE_TIMESTAMP_DISPLAY.md` untuk dokumentasi timestamp
3. Cek `UPLOAD_TO_PRODUCTION_HISTORY_PESAN_3_FEB_2026.md` untuk history pesan

---

**Created**: 5 Feb 2026
**Ready**: ✅ YES
**Risk**: 🟢 LOW (tested locally)
