# RANGKUMAN FINAL & PETUNJUK DEPLOY MANUAL - 21 FEBRUARI 2026

## 📋 EXECUTIVE SUMMARY

Hari ini dilakukan **2 MAJOR UPDATES** pada sistem PPDB dengan total **10 files** yang dimodifikasi dan **3 database migrations**.

### Fitur yang Ditambahkan:

1. **Fix Paket Lainnya** (Tab Madrasah & Seragam + Excel Export)
2. **Link Grup WhatsApp Daftar Ulang** (Admin Settings + Tombol Pendaftar)

---

## 📊 STATISTICS

| Item | Count | Details |
|------|-------|---------|
| **Total Files Modified** | 10 files | 7 PHP + 3 Migrations |
| **Database Migrations** | 3 files | MUST RUN! |
| **Features Added** | 2 major features | Paket Lainnya + Link Grup |
| **Roles Affected** | 3 roles | Admin, Panitia, Pendaftar |
| **Lines Added** | ~700+ lines | Across all files |

---

## 🗂️ DAFTAR LENGKAP FILES YANG DIMODIFIKASI

### A. PAKET LAINNYA (5 Files)

#### 1. `resources/views/livewire/pendaftar/daftar-ulang.blade.php`
**Role**: Pendaftar
**Changes**:
- Tab Madrasah: Section konfirmasi nominal untuk paket lainnya
- Tab Seragam: Section konfirmasi nominal untuk paket lainnya
- Tab Madrasah: Tombol "Bergabung ke Grup WhatsApp" (DI ATAS Surat Pernyataan)

**Impact**: Pendaftar bisa pilih dan save paket custom dengan nama sendiri

---

#### 2. `app/Livewire/Pendaftar/DaftarUlang.php`
**Role**: Pendaftar (Backend)
**Changes**:
- Logic display konfirmasi paket lainnya
- Parse dan save nama paket custom
- Validation untuk paket lainnya

**Impact**: Backend support untuk paket lainnya

---

#### 3. `app/Exports/DaftarUlangMadrasahSheet.php`
**Role**: Admin & Panitia (Excel Export)
**Changes**:
- Add kolom "Opsi yang Dipilih"
- Add kolom "Nominal Opsi"
- Add kolom "Konfirmasi Nominal"
- Add kolom "Nominal yang Dibayarkan"
- Parse JSON nominal untuk display

**New Columns**:
```
| Opsi yang Dipilih | Nominal Opsi | Konfirmasi Nominal | Nominal yang Dibayarkan |
```

**Impact**: Excel export lengkap dengan data paket lainnya

---

#### 4. `app/Exports/DaftarUlangSeragamSheet.php`
**Role**: Admin & Panitia (Excel Export)
**Changes**:
- Parse `paket_seragam` JSON → readable text
- Handle multi-select dengan " + " separator
- Add kolom "Opsi yang Dipilih"
- Add kolom "Nominal Opsi"
- Label paket lainnya: "Paket Lainnya (Manual)"

**Before**: `{"name":"Paket A","amount":500000}` (RAW JSON)
**After**: `Paket A` (READABLE)

**Multi-select Before**: `[{"name":"Item 1"},{"name":"Item 2"}]`
**Multi-select After**: `Item 1 + Item 2`

**Impact**: Export readable, tidak ada JSON mentah

---

#### 5. `app/Exports/DaftarUlangUkuranSheet.php`
**Role**: Admin & Panitia (Excel Export)
**Changes**:
- Parse `paket_seragam` JSON → readable
- Consistent dengan sheet Seragam

**Impact**: Export consistent di semua sheet

---

### B. DATABASE MIGRATIONS (3 Files) ⚠️ CRITICAL

#### 6. `database/migrations/2026_02_09_100000_add_individual_opsi_lainnya_to_daftar_ulang_and_seragam.php`

**Purpose**: Track paket lainnya usage

**Adds to `daftar_ulang` table**:
```sql
opsi_lainnya_dropdown_individual (boolean, default: false)
opsi_lainnya_konfirmasi_individual (boolean, default: false)
```

**Adds to `seragam` table**:
```sql
opsi_seragam_lainnya_dropdown_individual (boolean, default: false)
opsi_seragam_lainnya_konfirmasi_individual (boolean, default: false)
```

**Safe**: Adds columns only, no data modification

---

#### 7. `database/migrations/2026_02_21_100000_change_paket_seragam_to_text.php`

**Purpose**: Support long JSON for multi-select checkbox

**Changes**:
```sql
ALTER TABLE seragam
ALTER COLUMN paket_seragam TYPE TEXT;
```

**Before**: VARCHAR(255) - terpotong jika JSON panjang
**After**: TEXT - unlimited length

**Safe**: Expands column, preserves existing data

---

#### 8. `database/migrations/2026_02_21_210000_add_belum_diisi_to_seragam_status_enum.php`

**Purpose**: Add default status for new entries

**Changes**:
```sql
-- Add to status_ukuran enum
ALTER TABLE seragam DROP CONSTRAINT IF EXISTS seragam_status_ukuran_check;
ALTER TABLE seragam ADD CONSTRAINT seragam_status_ukuran_check
  CHECK (status_ukuran IN ('menunggu', 'disetujui', 'ditolak', 'belum_diisi'));

-- Add to status_seragam enum
ALTER TABLE seragam DROP CONSTRAINT IF EXISTS seragam_status_seragam_check;
ALTER TABLE seragam ADD CONSTRAINT seragam_status_seragam_check
  CHECK (status_seragam IN ('menunggu', 'disetujui', 'ditolak', 'belum_diisi'));
```

**Safe**: Expands enum, no data changes

---

### C. LINK GRUP DAFTAR ULANG (3 Files, 1 shared)

#### 9. `app/Livewire/Admin/PengaturanKartu.php`

**Role**: Admin (Backend Settings)

**Changes**:
```php
// New properties (line 59-61)
public $link_grup_daftar_ulang_url = '';
public $link_grup_daftar_ulang_enabled = false;

// Updated mount() - Load settings (line 86-88)
$this->link_grup_daftar_ulang_url = Setting::get('link_grup_daftar_ulang_url', '');
$this->link_grup_daftar_ulang_enabled = Setting::get('link_grup_daftar_ulang_enabled', false);

// New method saveLinkGrupDaftarUlang() (line 116-129)
public function saveLinkGrupDaftarUlang() {
    $this->validate([...]);
    Setting::set('link_grup_daftar_ulang_url', $this->link_grup_daftar_ulang_url);
    Setting::set('link_grup_daftar_ulang_enabled', $this->link_grup_daftar_ulang_enabled);
    session()->flash('message', 'Link grup daftar ulang berhasil disimpan.');
}
```

**Impact**: Admin bisa manage link grup daftar ulang

---

#### 10. `resources/views/livewire/admin/pengaturan-kartu.blade.php`

**Role**: Admin (UI Settings)

**Location**: `/admin/pengaturan-kartu?activeTab=link-grup`

**Changes** (line 443-558):
- Section baru "Link Grup WhatsApp/Telegram - Khusus Daftar Ulang"
- Toggle enable/disable (Alpine.js)
- Input URL field
- Preview tombol emerald
- Info box kuning (perbedaan dengan link grup kelulusan)

**UI Elements**:
```
┌─────────────────────────────────────────┐
│ Link Grup WhatsApp/Telegram            │ (Existing)
│ ✓ Aktifkan Link Grup                   │
│ [https://chat.whatsapp.com/...]        │
│ [Simpan Link Grup]                      │
└─────────────────────────────────────────┘

─────────────────────────────────────────── (Separator)

┌─────────────────────────────────────────┐
│ Link Grup - Khusus Daftar Ulang  ⭐NEW  │
│ ✓ Aktifkan Link Grup Daftar Ulang      │
│ [https://chat.whatsapp.com/...]        │
│ [Simpan Link Grup Daftar Ulang]        │
│                                          │
│ [Preview Tombol] (Emerald Green)        │
└─────────────────────────────────────────┘
```

**Impact**: Admin punya UI untuk manage 2 link grup berbeda

---

#### 11. `resources/views/livewire/pendaftar/daftar-ulang.blade.php` (SHARED)

**Role**: Pendaftar (UI)

**Location**: `/pendaftar/daftarulang?activeTab=madrasah`

**Changes for Link Grup** (line 578-613):
- Tombol "Segera Bergabung ke Grup Daftar Ulang!"
- Posisi: **DI ATAS Surat Pernyataan**
- Conditional: Toggle enabled + URL ada

**Display Logic**:
```blade
@else  {{-- Form section (bukan status approved) --}}

    @if($linkGrupDaftarUlangEnabled && !empty($linkGrupDaftarUlangUrl))
        <!-- Tombol Emerald Bergabung Grup -->
        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600...">
            <h4>Segera Bergabung ke Grup Daftar Ulang!</h4>
            <p>Bergabunglah ke grup WhatsApp khusus...</p>
            <a href="{{$linkGrupDaftarUlangUrl}}">Bergabung ke Grup WhatsApp</a>
            <p class="italic">* Silakan bergabung sebelum melanjutkan...</p>
        </div>
    @endif

    @if($showPernyataan)
        <!-- Surat Pernyataan -->
    @endif
```

**Impact**: Pendaftar bisa langsung bergabung grup setelah setuju daftar ulang

---

## 🔍 PERUBAHAN PER ROLE

### 👨‍💼 ADMIN

#### A. Pengaturan Kartu (`/admin/pengaturan-kartu?activeTab=link-grup`)

**Before**:
- 1 section: Link Grup (untuk kelulusan)

**After**:
- 2 sections:
  1. Link Grup (untuk kelulusan) - existing
  2. **Link Grup Daftar Ulang** (untuk yang daftar ulang) - NEW ⭐

**New Features**:
- Toggle enable/disable link grup daftar ulang
- Input URL grup daftar ulang
- Preview tombol (emerald green)
- Validation: URL must be valid

**How to Use**:
1. Enable toggle "Aktifkan Link Grup Daftar Ulang"
2. Input URL: `https://chat.whatsapp.com/ABC123`
3. Click "Simpan Link Grup Daftar Ulang"
4. Preview tombol akan muncul

---

#### B. Excel Export Daftar Ulang

**Sheet 1: Daftar Ulang Madrasah**

**New Columns**:
- Opsi yang Dipilih (Bayar Penuh/Cicilan)
- Nominal Opsi (amount dari opsi)
- Konfirmasi Nominal (opsi final yang dipilih)
- Nominal yang Dibayarkan (amount final)

**Data Paket Lainnya**:
- Nama paket custom ditampilkan readable
- Nominal sesuai input user

---

**Sheet 2: Daftar Ulang Seragam**

**New Columns**:
- Opsi yang Dipilih (multi-select dengan " + ")
- Nominal Opsi (total dari semua item)

**Paket Seragam Column**:
- Before: `{"name":"Paket A","amount":500000}`
- After: `Paket A` atau `Paket Lainnya (Manual)`

**Multi-select**:
- Before: `[{"name":"Item 1"},{"name":"Item 2"}]`
- After: `Item 1 + Item 2`

---

**Sheet 3: Ukuran Seragam & Atribut**

**Paket Seragam Column**:
- Consistent dengan Sheet 2 (readable)

---

### 👥 PANITIA

**Same as Admin** untuk Excel Export (akses yang sama).

**No changes** untuk menu/UI lainnya.

---

### 🎓 PENDAFTAR

#### A. Tab Madrasah (`/pendaftar/daftarulang?activeTab=madrasah`)

**New: Tombol Bergabung Grup** ⭐

**Posisi**: DI ATAS Surat Pernyataan

**Kriteria Muncul**:
1. User sudah setuju daftar ulang (bisa akses halaman)
2. Admin enable toggle link grup daftar ulang
3. URL grup sudah diisi

**Timing**: **SEGERA** setelah setuju daftar ulang (tidak tunggu approval panitia)

**UI**:
```
┌───────────────────────────────────────────┐
│  💬 Segera Bergabung ke Grup Daftar Ulang!│
│                                            │
│  Bergabunglah ke grup WhatsApp khusus...  │
│  Dapatkan informasi terbaru dan...        │
│                                            │
│  [ Bergabung ke Grup WhatsApp ]           │ ← White button
│                                            │
│  * Silakan bergabung sebelum melanjutkan  │
└───────────────────────────────────────────┘
```

---

**Paket Lainnya - Tab Madrasah**

**Before**:
- Pilih "Paket Lainnya" → Section konfirmasi **TIDAK MUNCUL**
- Data tidak tersimpan

**After**:
- Pilih "Paket Lainnya (Input Manual)"
- Isi "Nama Paket Custom": [input text]
- Isi "Nominal Biaya (Rp)": [input number]
- Section "Konfirmasi Nominal yang Dibayarkan" **MUNCUL** ✓
- Opsi "Bayar Penuh" menampilkan nama dan nominal custom ✓
- Data tersimpan ke database ✓

---

**Paket Lainnya - Tab Seragam**

**Before**: Same issue (section konfirmasi tidak muncul)

**After**: Same fix (section muncul, data tersimpan)

---

#### B. Tab Seragam (`/pendaftar/daftarulang?activeTab=seragam`)

**No tombol grup** (hanya di Tab Madrasah)

**Paket Lainnya**: Fix sama seperti Tab Madrasah

---

#### C. Tab Ukuran (`/pendaftar/daftarulang?activeTab=ukuran`)

**No tombol grup** (hanya di Tab Madrasah)

**No changes** untuk konten lainnya

---

## 🗄️ DATABASE CHANGES

### Tables Modified: 2 tables

#### 1. Table `daftar_ulang`

**New Columns** (Migration #6):
```sql
opsi_lainnya_dropdown_individual      BOOLEAN DEFAULT FALSE
opsi_lainnya_konfirmasi_individual    BOOLEAN DEFAULT FALSE
```

**Purpose**: Track apakah user gunakan paket lainnya

---

#### 2. Table `seragam`

**New Columns** (Migration #6):
```sql
opsi_seragam_lainnya_dropdown_individual    BOOLEAN DEFAULT FALSE
opsi_seragam_lainnya_konfirmasi_individual  BOOLEAN DEFAULT FALSE
```

**Modified Columns** (Migration #7):
```sql
paket_seragam   VARCHAR(255) → TEXT
```

**Modified Constraints** (Migration #8):
```sql
status_ukuran   enum(..., 'belum_diisi')  -- Added
status_seragam  enum(..., 'belum_diisi')  -- Added
```

---

### Settings Table (No Migration)

**New Keys** (for Link Grup Daftar Ulang):
```
link_grup_daftar_ulang_url       => 'https://...'
link_grup_daftar_ulang_enabled   => true/false
```

**Method**: Using `Setting::get()` and `Setting::set()` (existing system)

---

## 📤 PETUNJUK DEPLOY MANUAL KE PRODUCTION

### 🔴 STEP 1: BACKUP DATABASE (RECOMMENDED)

```bash
# SSH to server
ssh mtsnkot4@103.30.244.77

# Navigate to project
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Backup database
pg_dump -U mtsnkot4 -d mtsnkot4_ppdb > backup_21feb2026_before_deploy.sql

# Verify backup
ls -lh backup_21feb2026_before_deploy.sql

# Exit SSH
exit
```

---

### 🟠 STEP 2: UPLOAD FILES (10 FILES)

#### A. Upload PHP Files (7 files)

```bash
# Windows Command Prompt

# File 1: Pendaftar - Daftar Ulang View (PAKET LAINNYA + LINK GRUP)
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\pendaftar\daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/

# File 2: Pendaftar - Daftar Ulang Controller (PAKET LAINNYA)
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Pendaftar\DaftarUlang.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Pendaftar/

# File 3: Excel Export - Madrasah Sheet (PAKET LAINNYA)
scp "C:\ppdbmtsn2km-laravel\app\Exports\DaftarUlangMadrasahSheet.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/

# File 4: Excel Export - Seragam Sheet (PAKET LAINNYA)
scp "C:\ppdbmtsn2km-laravel\app\Exports\DaftarUlangSeragamSheet.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/

# File 5: Excel Export - Ukuran Sheet (PAKET LAINNYA)
scp "C:\ppdbmtsn2km-laravel\app\Exports\DaftarUlangUkuranSheet.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/

# File 6: Admin - Pengaturan Kartu Controller (LINK GRUP)
scp "C:\ppdbmtsn2km-laravel\app\Livewire\Admin\PengaturanKartu.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/

# File 7: Admin - Pengaturan Kartu View (LINK GRUP)
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\pengaturan-kartu.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
```

**Verify Upload**:
```bash
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && ls -lh resources/views/livewire/pendaftar/daftar-ulang.blade.php app/Livewire/Pendaftar/DaftarUlang.php app/Exports/DaftarUlang*.php app/Livewire/Admin/PengaturanKartu.php resources/views/livewire/admin/pengaturan-kartu.blade.php"
```

---

#### B. Upload Migration Files (3 files) ⚠️

```bash
# Migration 1: Add opsi_lainnya tracking columns
scp "C:\ppdbmtsn2km-laravel\database\migrations\2026_02_09_100000_add_individual_opsi_lainnya_to_daftar_ulang_and_seragam.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/

# Migration 2: Change paket_seragam to TEXT
scp "C:\ppdbmtsn2km-laravel\database\migrations\2026_02_21_100000_change_paket_seragam_to_text.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/

# Migration 3: Add belum_diisi status
scp "C:\ppdbmtsn2km-laravel\database\migrations\2026_02_21_210000_add_belum_diisi_to_seragam_status_enum.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/database/migrations/
```

**Verify Upload**:
```bash
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && ls -lh database/migrations/2026_02_*"
```

---

### 🟡 STEP 3: RUN MIGRATIONS ⚠️ CRITICAL

```bash
# SSH to server
ssh mtsnkot4@103.30.244.77

# Navigate to project
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Check migration status BEFORE
php artisan migrate:status | grep "2026_02"
# Expected: All show "Pending"

# Run migrations
php artisan migrate

# Expected output:
# Running: 2026_02_09_100000_add_individual_opsi_lainnya_to_daftar_ulang_and_seragam
# Migrated: 2026_02_09_100000_add_individual_opsi_lainnya_to_daftar_ulang_and_seragam (XX ms)
# Running: 2026_02_21_100000_change_paket_seragam_to_text
# Migrated: 2026_02_21_100000_change_paket_seragam_to_text (XX ms)
# Running: 2026_02_21_210000_add_belum_diisi_to_seragam_status_enum
# Migrated: 2026_02_21_210000_add_belum_diisi_to_seragam_status_enum (XX ms)

# Check migration status AFTER
php artisan migrate:status | grep "2026_02"
# Expected: All show "Ran"
```

**If Migration Fails**:
```bash
# Check error
tail -100 storage/logs/laravel.log

# DO NOT run rollback in production!
# Contact developer or create new migration to fix
```

---

### 🟢 STEP 4: VERIFY DATABASE CHANGES

```bash
# Still in SSH session
php artisan tinker
```

```php
// Check opsi_lainnya columns in daftar_ulang
>>> \DB::select("SELECT column_name FROM information_schema.columns WHERE table_name = 'daftar_ulang' AND column_name LIKE '%opsi_lainnya%'");
// Expected: 2 rows

// Check opsi_lainnya columns in seragam
>>> \DB::select("SELECT column_name FROM information_schema.columns WHERE table_name = 'seragam' AND column_name LIKE '%opsi_lainnya%'");
// Expected: 2 rows

// Check paket_seragam is TEXT
>>> \DB::select("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'seragam' AND column_name = 'paket_seragam'");
// Expected: data_type = "text"

// Check status enum values
>>> \DB::select("SELECT constraint_name, check_clause FROM information_schema.check_constraints WHERE constraint_name LIKE '%status%' AND constraint_schema = 'public'");
// Expected: Should include 'belum_diisi'

>>> exit
```

---

### 🔵 STEP 5: CLEAR CACHE

```bash
# Still in SSH session
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Clear all cache
php artisan optimize:clear

# Clear view cache
php artisan view:clear

# Clear config cache
php artisan config:clear

# Clear application cache
php artisan cache:clear

# Verify views cleared
ls storage/framework/views/ | wc -l
# Expected: 0 or very few files

# Exit SSH
exit
```

---

### 🟣 STEP 6: TESTING

#### A. Test Admin - Link Grup Settings

1. **Login** sebagai Admin
2. **URL**: `https://pmbm.mtsn2kotamalang.sch.id/admin/pengaturan-kartu?activeTab=link-grup`
3. **Scroll** ke bagian "Link Grup WhatsApp/Telegram - Khusus Daftar Ulang"
4. **Enable** toggle
5. **Input** URL: `https://chat.whatsapp.com/[your-group-id]`
6. **Click** "Simpan Link Grup Daftar Ulang"
7. **Verify**: Flash message "Link grup daftar ulang berhasil disimpan."
8. **Verify**: Preview tombol emerald muncul

---

#### B. Test Pendaftar - Tombol Bergabung Grup

1. **Login** sebagai Pendaftar yang sudah setuju daftar ulang
2. **URL**: `https://pmbm.mtsn2kotamalang.sch.id/pendaftar/daftarulang?activeTab=madrasah`
3. **Verify**: Tombol "Segera Bergabung ke Grup Daftar Ulang!" muncul
4. **Verify**: Tombol berada **DI ATAS** bagian "Surat Pernyataan"
5. **Verify**: Text "* Silakan bergabung sebelum melanjutkan..." terlihat
6. **Click** tombol "Bergabung ke Grup WhatsApp"
7. **Verify**: Link buka di tab baru
8. **Verify**: URL sesuai dengan yang diset admin

**Check Tab Lain**:
9. **URL**: `?activeTab=seragam` → Tombol **TIDAK MUNCUL** ✓
10. **URL**: `?activeTab=ukuran` → Tombol **TIDAK MUNCUL** ✓

---

#### C. Test Paket Lainnya - Tab Madrasah

1. **Login** sebagai Pendaftar
2. **URL**: `/pendaftar/daftarulang?activeTab=madrasah`
3. **Pilih** dropdown "Pilih Paket Madrasah" → "Paket Lainnya (Input Manual)"
4. **Isi** "Nama Paket Custom": `Test Custom Madrasah`
5. **Isi** "Nominal Biaya (Rp)": `1500000`
6. **Verify**: Section "Konfirmasi Nominal yang Dibayarkan" **MUNCUL** ✓
7. **Verify**: Opsi "Bayar Penuh" menampilkan:
   - Nama: `Test Custom Madrasah`
   - Nominal: `Rp 1.500.000`
8. **Pilih** "Bayar Penuh"
9. **Lengkapi** form (bank, bukti transfer, nama pengirim)
10. **Click** "Simpan dan Lanjutkan"
11. **Verify**: Redirect berhasil, no error

---

#### D. Test Paket Lainnya - Tab Seragam

1. **URL**: `/pendaftar/daftarulang?activeTab=seragam`
2. **Pilih** "Paket Lainnya (Input Manual)"
3. **Isi** "Nama Paket Custom": `Test Custom Seragam`
4. **Isi** "Nominal Biaya (Rp)": `2500000`
5. **Verify**: Section konfirmasi **MUNCUL** ✓
6. **Verify**: Bayar Penuh menampilkan nama dan nominal ✓
7. **Simpan**
8. **Verify**: Berhasil, no error

---

#### E. Test Excel Export - Madrasah

1. **Login** Admin/Panitia
2. **URL**: `/admin/daftarulang` (tab: Daftar Ulang Madrasah)
3. **Click** "Export Excel"
4. **Download** file
5. **Buka** Excel
6. **Verify** kolom baru ada:
   - "Opsi yang Dipilih"
   - "Nominal Opsi"
   - "Konfirmasi Nominal"
   - "Nominal yang Dibayarkan"
7. **Verify** data paket lainnya:
   - Nama custom readable (bukan JSON)
   - Nominal sesuai input user

---

#### F. Test Excel Export - Seragam

1. **URL**: `/admin/daftarulang` (tab: Daftar Ulang Seragam)
2. **Click** "Export Excel"
3. **Buka** Excel
4. **Verify**:
   - Kolom "Paket Seragam" → readable (bukan JSON)
   - Paket lainnya → "Paket Lainnya (Manual)"
   - Multi-select → dengan " + " separator
   - Ada kolom "Opsi yang Dipilih"
   - Ada kolom "Nominal Opsi"

---

#### G. Test Excel Export - Ukuran

1. **URL**: `/admin/daftarulang` (tab: Ukuran Seragam & Atribut)
2. **Click** "Export Excel"
3. **Buka** Excel
4. **Verify**: Kolom "Paket Seragam" readable (consistent dengan sheet Seragam)

---

### ⚫ STEP 7: MONITOR LOGS

```bash
# SSH to server
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Monitor Laravel log in real-time
tail -f storage/logs/laravel.log

# Press Ctrl+C to stop
# Exit SSH
exit
```

**Look for**:
- ❌ Errors
- ⚠️ Warnings
- ✅ No issues = deployment successful!

---

## 🚨 TROUBLESHOOTING

### Issue 1: Migration Error - "column already exists"

**Cause**: Migration already ran before

**Solution**:
```bash
# Check migration status
php artisan migrate:status | grep "2026_02"

# If all show "Ran" → skip, already applied
```

---

### Issue 2: Tombol Link Grup Tidak Muncul

**Check**:
1. Admin toggle enabled? → `/admin/pengaturan-kartu?activeTab=link-grup`
2. URL diisi? → Same page
3. User bisa akses halaman? → status_pendaftaran = 'data_disetujui'
4. Cache cleared? → `php artisan view:clear`

**Debug**:
```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Check settings
php artisan tinker
>>> \App\Models\Setting::get('link_grup_daftar_ulang_enabled');
>>> \App\Models\Setting::get('link_grup_daftar_ulang_url');
>>> exit
```

---

### Issue 3: Section Konfirmasi Paket Lainnya Tidak Muncul

**Check**:
1. File ter-upload? → `ls -lh resources/views/livewire/pendaftar/daftar-ulang.blade.php`
2. Cache cleared? → `php artisan view:clear`
3. Browser cache? → Hard refresh Ctrl+F5

**Fix**:
```bash
# Re-upload file
scp "resources/views/livewire/pendaftar/daftar-ulang.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/

# Clear cache
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear && php artisan cache:clear"
```

---

### Issue 4: Excel Export Kosong/Error

**Check**:
1. Files ter-upload? → `ls -lh app/Exports/DaftarUlang*.php`
2. Cache cleared? → `php artisan optimize:clear`

**Fix**:
```bash
# Re-upload export files
scp "app/Exports/DaftarUlangMadrasahSheet.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/
scp "app/Exports/DaftarUlangSeragamSheet.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/
scp "app/Exports/DaftarUlangUkuranSheet.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Exports/

# Clear cache
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear"
```

---

## ✅ POST-DEPLOYMENT CHECKLIST

- [ ] **Backup database** created
- [ ] **7 PHP files** uploaded successfully
- [ ] **3 migrations** uploaded successfully
- [ ] **Migrations ran** successfully (all show "Ran")
- [ ] **Database columns** verified (opsi_lainnya exist, paket_seragam is TEXT)
- [ ] **Cache cleared** (all 4 types)
- [ ] **Admin settings** tested (toggle + URL save)
- [ ] **Pendaftar tombol grup** tested (muncul di atas Surat Pernyataan)
- [ ] **Paket lainnya Tab Madrasah** tested (section konfirmasi muncul)
- [ ] **Paket lainnya Tab Seragam** tested (section konfirmasi muncul)
- [ ] **Excel export Madrasah** tested (kolom baru ada)
- [ ] **Excel export Seragam** tested (paket readable, multi-select OK)
- [ ] **Excel export Ukuran** tested (paket consistent)
- [ ] **Laravel log** checked (no errors)
- [ ] **Browser console** checked (no JS errors)

---

## 📚 DOKUMENTASI TERKAIT

### Dokumentasi Lengkap:
1. **RANGKUMAN_FINAL_DEPLOY_MANUAL_21_FEB_2026.md** ⭐ (This file)
2. **FINAL_SUMMARY_COMPLETE_21_FEB_2026.md** - Summary semua fitur
3. **ADD_LINK_GRUP_DAFTAR_ULANG_21_FEB_2026.md** - Detail link grup feature
4. **UPDATE_POSISI_LINK_GRUP_21_FEB_2026.md** - Perubahan posisi tombol

### Quick Reference:
5. **QUICK_REFERENCE_COMPLETE_21_FEB_2026.txt** - Commands cepat
6. **CHECKLIST_UPLOAD_21_FEB_2026.md** - Checklist tick-box

### Feature-Specific:
7. **FIX_PAKET_LAINNYA_TAB_MADRASAH_21_FEB_2026.md**
8. **FIX_PAKET_LAINNYA_TAB_SERAGAM_21_FEB_2026.md**
9. **FIX_EXCEL_EXPORT_DAFTAR_ULANG_21_FEB_2026.md**

---

## 🎯 QUICK COMMAND SUMMARY

```bash
# === UPLOAD FILES ===
# (Run all 10 scp commands from STEP 2)

# === RUN MIGRATIONS ===
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan migrate"

# === CLEAR CACHE ===
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan optimize:clear && php artisan view:clear && php artisan config:clear && php artisan cache:clear"

# === VERIFY ===
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan migrate:status | grep '2026_02'"

# === MONITOR ===
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && tail -f storage/logs/laravel.log"
```

---

## 📊 SUMMARY

**Date**: 21 Februari 2026
**Total Files**: 10 unique files (7 PHP + 3 Migrations)
**Total Features**: 2 major features
**Roles Affected**: Admin, Panitia, Pendaftar
**Database Migrations**: 3 (MUST RUN!)
**Breaking Changes**: None
**Risk Level**: LOW (safe migrations, backward compatible)

**Status**: ✅ **READY FOR PRODUCTION DEPLOYMENT**

---

**PENTING**:
1. ⚠️ **WAJIB RUN MIGRATIONS** - jangan skip!
2. ✅ Backup database sebelum deploy (recommended)
3. 🔄 Clear cache setelah upload
4. 🧪 Test semua fitur setelah deploy
5. 📊 Monitor logs untuk error

---

END OF DEPLOYMENT GUIDE

**Deployment Time Estimate**: 15-20 menit
**Recommended Deploy Window**: Non-peak hours

Good luck! 🚀
