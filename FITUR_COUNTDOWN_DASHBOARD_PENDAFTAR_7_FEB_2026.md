# FITUR COUNTDOWN TIMER DASHBOARD PENDAFTAR - 7 FEB 2026

## 📋 Overview

**Fitur Baru:** Countdown Timer di Dashboard Pendaftar untuk menghitung mundur waktu pendaftaran yang tersisa.

**User Request:**
> "saya ingin countdown time seperti di tampilan public tapi ini sekarang saya ingin munculnya ada di /pendaftar/dashboard untuk waktu pendaftaran tersisa yang akan berakhir (bisa di munculkan, bisa disembunyikan) lalu pengaturan waktunya ada di /admin/settings di bawah pengaturan umum dan bisa ubah label fase dan daskripsi fase seperti di pengaturan umum yang ada itu."

---

## 🎯 Fitur Yang Dibuat

### **1. Countdown Timer di Dashboard Pendaftar**
- Menampilkan hitung mundur waktu hingga **Tanggal Selesai Pendaftaran**
- Tampilan sama persis dengan countdown di public (registration-phase-banner)
- Format: **Hari : Jam : Menit : Detik**
- Real-time update setiap detik
- Responsive design (mobile & desktop)

### **2. Pengaturan di Admin Settings**
- **Toggle ON/OFF**: Aktifkan/nonaktifkan countdown
- **Label Countdown**: Teks judul countdown (default: "Waktu Pendaftaran Tersisa")
- **Deskripsi Countdown**: Pesan di bawah judul (default: "Segera lengkapi data pendaftaran Anda sebelum waktu berakhir.")
- Countdown menggunakan **Tanggal Selesai** dari Pengaturan Umum

---

## 📝 File Yang Diubah

### **1. Backend - Admin Settings**

#### **File:** `app/Livewire/Admin/Settings.php`

**Perubahan:**

```php
// 1. Tambah Properties (Line 40-43)
// Countdown Timer Settings (Dashboard Pendaftar)
public $countdown_pendaftar_enabled = false;
public $countdown_pendaftar_label;
public $countdown_pendaftar_description;

// 2. Load Settings (Line 109-112)
// Load Countdown Timer Settings
$this->countdown_pendaftar_enabled = Setting::get('countdown_pendaftar_enabled', false);
$this->countdown_pendaftar_label = Setting::get('countdown_pendaftar_label', 'Waktu Pendaftaran Tersisa');
$this->countdown_pendaftar_description = Setting::get('countdown_pendaftar_description', 'Segera lengkapi data pendaftaran Anda sebelum waktu berakhir.');

// 3. Validation (Line 564-565)
'countdown_pendaftar_label' => 'nullable|string',
'countdown_pendaftar_description' => 'nullable|string',

// 4. Save Settings (Line 574-577)
Setting::set('countdown_pendaftar_enabled', $this->countdown_pendaftar_enabled, 'boolean', 'Countdown Timer Pendaftar Aktif');
Setting::set('countdown_pendaftar_label', $this->countdown_pendaftar_label, 'string', 'Label Countdown Pendaftar');
Setting::set('countdown_pendaftar_description', $this->countdown_pendaftar_description, 'string', 'Deskripsi Countdown Pendaftar');
```

**Settings Database:**
- `countdown_pendaftar_enabled` (boolean) - Default: false
- `countdown_pendaftar_label` (string) - Default: "Waktu Pendaftaran Tersisa"
- `countdown_pendaftar_description` (string) - Default: "Segera lengkapi data pendaftaran Anda sebelum waktu berakhir."

---

### **2. Frontend - Admin Settings Form**

#### **File:** `resources/views/livewire/admin/settings.blade.php`

**Perubahan (Line 75-120):**

```html
<!-- Divider -->
<div class="md:col-span-2 my-4">
    <div class="border-t-2 border-gray-200"></div>
</div>

<!-- Countdown Timer Settings -->
<div class="md:col-span-2">
    <div class="bg-emerald-50 border-2 border-emerald-200 rounded-xl p-4">
        <!-- Toggle Switch -->
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
                <svg>...</svg>
                <div>
                    <h4>Countdown Timer Dashboard Pendaftar</h4>
                    <p>Tampilkan hitung mundur waktu pendaftaran di dashboard pendaftar</p>
                </div>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" wire:model.live="countdown_pendaftar_enabled">
                <div class="toggle-switch"></div>
            </label>
        </div>

        <!-- Form Fields (muncul jika toggle ON) -->
        <div x-show="$wire.countdown_pendaftar_enabled" x-transition>
            <!-- Label Countdown -->
            <input type="text" wire:model="countdown_pendaftar_label" placeholder="Waktu Pendaftaran Tersisa">

            <!-- Deskripsi Countdown -->
            <textarea wire:model="countdown_pendaftar_description" placeholder="Segera lengkapi data pendaftaran Anda..."></textarea>

            <!-- Info Box -->
            <div class="info-box">
                Countdown akan menghitung mundur waktu hingga Tanggal Selesai di atas.
            </div>
        </div>
    </div>
</div>
```

**UI Features:**
- Toggle switch dengan animasi
- Form fields muncul hanya jika toggle ON (Alpine.js `x-show`)
- Info box menjelaskan bahwa countdown menggunakan Tanggal Selesai
- Color scheme: Emerald green (matching theme)

---

### **3. Component - Countdown Timer**

#### **File:** `resources/views/components/countdown-dashboard-pendaftar.blade.php` (NEW)

**Konten:**

```blade
@php
    $countdownEnabled = \App\Models\Setting::get('countdown_pendaftar_enabled', false);
    $countdownLabel = \App\Models\Setting::get('countdown_pendaftar_label', 'Waktu Pendaftaran Tersisa');
    $countdownDescription = \App\Models\Setting::get('countdown_pendaftar_description', 'Segera lengkapi...');
    $endDate = \App\Models\Setting::get('registration_end_date');
@endphp

@if($countdownEnabled && $endDate)
<div class="bg-gradient-to-r from-emerald-50 to-green-50 border-2 border-emerald-200 rounded-xl p-6">
    <div class="flex flex-col md:flex-row items-center gap-6">
        <!-- Icon -->
        <div class="icon-circle">
            <svg>⏰</svg>
        </div>

        <!-- Text Content -->
        <div class="flex-1">
            <h3>{{ $countdownLabel }}</h3>
            <p>{{ $countdownDescription }}</p>
        </div>

        <!-- Countdown Timer -->
        <div x-data="countdown('{{ $endDate->format('Y-m-d H:i:s') }}')" x-init="init()">
            <p>Berakhir Dalam:</p>
            <div class="countdown-boxes">
                <div>
                    <span x-text="days">00</span>
                    <label>Hari</label>
                </div>
                :
                <div>
                    <span x-text="hours">00</span>
                    <label>Jam</label>
                </div>
                :
                <div>
                    <span x-text="minutes">00</span>
                    <label>Menit</label>
                </div>
                :
                <div>
                    <span x-text="seconds">00</span>
                    <label>Detik</label>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
```

**Features:**
- Component hanya muncul jika `countdown_pendaftar_enabled = true` DAN `registration_end_date` ada
- Menggunakan Alpine.js function `countdown()`
- Responsive layout (vertikal di mobile, horizontal di desktop)
- Countdown boxes dengan background emerald-600 dan text white
- Auto-update setiap 1 detik

---

### **4. Dashboard Layout - Countdown Function**

#### **File:** `resources/views/components/layouts/dashboard.blade.php`

**Perubahan (Line 696-730):**

```html
<!-- Countdown Timer Function -->
<script>
    function countdown(targetDate) {
        return {
            days: '00',
            hours: '00',
            minutes: '00',
            seconds: '00',
            init() {
                this.updateCountdown();
                setInterval(() => {
                    this.updateCountdown();
                }, 1000);
            },
            updateCountdown() {
                const now = new Date().getTime();
                const target = new Date(targetDate).getTime();
                const distance = target - now;

                if (distance < 0) {
                    this.days = '00';
                    this.hours = '00';
                    this.minutes = '00';
                    this.seconds = '00';
                    return;
                }

                this.days = Math.floor(distance / (1000 * 60 * 60 * 24)).toString().padStart(2, '0');
                this.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).toString().padStart(2, '0');
                this.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)).toString().padStart(2, '0');
                this.seconds = Math.floor((distance % (1000 * 60)) / 1000).toString().padStart(2, '0');
            }
        };
    }
</script>
```

**Function Logic:**
- Alpine.js component function
- Input: `targetDate` (Y-m-d H:i:s format)
- Output: Reactive days, hours, minutes, seconds
- Update interval: 1000ms (1 detik)
- Countdown berhenti di 00:00:00:00 jika waktu sudah lewat

---

### **5. Dashboard Pendaftar View**

#### **File:** `resources/views/livewire/pendaftar/dashboard.blade.php`

**Perubahan (Line 38-41):**

```html
<!-- Countdown Timer -->
<div class="mb-6">
    <x-countdown-dashboard-pendaftar />
</div>
```

**Posisi:**
- SETELAH: Notification toast
- SEBELUM: Status Pendaftaran (box hijau dengan foto)
- Margin bottom: 6 (24px)

---

## 🎨 Tampilan UI

### **Admin Settings - Pengaturan Umum**

```
┌─────────────────────────────────────────────────────────┐
│ PENGATURAN UMUM                                         │
├─────────────────────────────────────────────────────────┤
│                                                         │
│ [Fase Pendaftaran] [Label Fase]                        │
│ [Deskripsi Fase...........................]             │
│ [Tanggal Mulai] [Tanggal Selesai]                      │
│                                                         │
│ ─────────────────────────────────────────────────────  │
│                                                         │
│ ┌───────────────────────────────────────────────────┐  │
│ │ 🕐 COUNTDOWN TIMER DASHBOARD PENDAFTAR    [●──○] │  │
│ │ Tampilkan hitung mundur waktu pendaftaran         │  │
│ │                                                   │  │
│ │ [Label Countdown.................................] │  │
│ │ Label yang ditampilkan di atas countdown timer   │  │
│ │                                                   │  │
│ │ [Deskripsi Countdown...........................]  │  │
│ │ [.............................................]  │  │
│ │ Pesan yang ditampilkan di bawah label countdown  │  │
│ │                                                   │  │
│ │ ℹ️ Countdown akan menghitung mundur waktu hingga  │  │
│ │   Tanggal Selesai di atas.                       │  │
│ └───────────────────────────────────────────────────┘  │
│                                                         │
│                           [💾 SIMPAN PENGATURAN UMUM]  │
└─────────────────────────────────────────────────────────┘
```

### **Dashboard Pendaftar**

```
┌─────────────────────────────────────────────────────────┐
│                                                         │
│ ┌───────────────────────────────────────────────────┐  │
│ │ 🕐  WAKTU PENDAFTARAN TERSISA                      │  │
│ │     Segera lengkapi data pendaftaran Anda         │  │
│ │     sebelum waktu berakhir.                       │  │
│ │                                                   │  │
│ │                            Berakhir Dalam:        │  │
│ │                       ┌──┐  ┌──┐  ┌──┐  ┌──┐     │  │
│ │                       │15│ :│23│ :│45│ :│12│     │  │
│ │                       └──┘  └──┘  └──┘  └──┘     │  │
│ │                       Hari  Jam  Menit Detik     │  │
│ └───────────────────────────────────────────────────┘  │
│                                                         │
│ ┌───────────────────────────────────────────────────┐  │
│ │ STATUS PENDAFTARAN                                 │  │
│ │ [Photo] Nama: Ahmad Rizki                         │  │
│ │         Status: Data Lengkap ✓                    │  │
│ └───────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────┘
```

---

## ✅ Testing Checklist

### **1. Test di Admin Settings**

- [ ] Login sebagai Admin
- [ ] Buka `/admin/settings`
- [ ] Scroll ke "Pengaturan Umum"
- [ ] Cek section "Countdown Timer Dashboard Pendaftar" muncul
- [ ] Toggle OFF → form fields TERSEMBUNYI
- [ ] Toggle ON → form fields MUNCUL
- [ ] Isi Label Countdown: "Pendaftaran Segera Berakhir!"
- [ ] Isi Deskripsi: "Jangan sampai terlewat!"
- [ ] Klik "Simpan Pengaturan Umum"
- [ ] ✅ Notifikasi sukses muncul

### **2. Test di Dashboard Pendaftar (Toggle ON)**

- [ ] Login sebagai Pendaftar
- [ ] Buka `/pendaftar/dashboard`
- [ ] ✅ Countdown timer MUNCUL di atas box status pendaftaran
- [ ] ✅ Label countdown sesuai dengan yang diset di admin
- [ ] ✅ Deskripsi countdown sesuai dengan yang diset di admin
- [ ] ✅ Countdown menampilkan angka (Hari, Jam, Menit, Detik)
- [ ] ✅ Angka countdown UPDATE setiap 1 detik
- [ ] ✅ Tampilan responsive di mobile & desktop

### **3. Test di Dashboard Pendaftar (Toggle OFF)**

- [ ] Kembali ke Admin Settings
- [ ] Toggle countdown menjadi OFF
- [ ] Simpan
- [ ] Refresh dashboard pendaftar
- [ ] ✅ Countdown timer TIDAK MUNCUL

### **4. Test Edge Cases**

- [ ] Set Tanggal Selesai = hari ini + 1 hari
- [ ] Cek countdown menampilkan "01 : XX : XX : XX"
- [ ] Set Tanggal Selesai = hari ini + 3 jam
- [ ] Cek countdown menampilkan "00 : 03 : XX : XX"
- [ ] Tunggu hingga countdown mencapai 00:00:00:00
- [ ] ✅ Countdown berhenti di 00:00:00:00 (tidak minus)

---

## 📦 Deployment

### **File Yang Diupload:**

```
1. app/Livewire/Admin/Settings.php
2. resources/views/livewire/admin/settings.blade.php
3. resources/views/components/countdown-dashboard-pendaftar.blade.php (NEW)
4. resources/views/components/layouts/dashboard.blade.php
5. resources/views/livewire/pendaftar/dashboard.blade.php
```

### **Upload Script:**

```bash
./upload_fitur_countdown_pendaftar.bat
```

**Script akan:**
1. Upload 5 files ke production
2. Clear cache (view + config)
3. Tampilkan checklist testing

### **Manual Upload:**

```bash
# 1. Upload backend
scp app/Livewire/Admin/Settings.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Admin/

# 2. Upload admin view
scp resources/views/livewire/admin/settings.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/

# 3. Upload countdown component (NEW)
scp resources/views/components/countdown-dashboard-pendaftar.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/components/

# 4. Upload dashboard layout
scp resources/views/components/layouts/dashboard.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/components/layouts/

# 5. Upload pendaftar dashboard
scp resources/views/livewire/pendaftar/dashboard.blade.php mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/pendaftar/

# 6. Clear cache
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear && php artisan config:clear"
```

---

## 📊 Summary

### **Fitur Yang Dibuat:**

| Fitur | Keterangan | Status |
|-------|------------|--------|
| **Toggle ON/OFF** | Aktifkan/nonaktifkan countdown | ✅ Complete |
| **Custom Label** | Edit label countdown | ✅ Complete |
| **Custom Deskripsi** | Edit deskripsi countdown | ✅ Complete |
| **Real-time Countdown** | Update setiap 1 detik | ✅ Complete |
| **Responsive Design** | Mobile & Desktop | ✅ Complete |
| **Conditional Display** | Hanya muncul jika enabled + ada end_date | ✅ Complete |

### **Database Settings:**

- `countdown_pendaftar_enabled` → Boolean (default: false)
- `countdown_pendaftar_label` → String (default: "Waktu Pendaftaran Tersisa")
- `countdown_pendaftar_description` → String (default: "Segera lengkapi...")

### **Total Files:**
- **Modified:** 4 files
- **New:** 1 file (countdown component)
- **Total:** 5 files

---

## ✅ Status

**Status:** ✅ **COMPLETE - READY FOR PRODUCTION**

**Impact:**
- 🟢 Low (hanya menambah fitur tampilan, tidak mengubah logic existing)
- User experience lebih baik (pendaftar tahu deadline)

**Risk:**
- 🟢 Very Low (countdown hanya visual, tidak ada database operation)
- Jika countdown error, tidak mempengaruhi fungsi lain

**Dependencies:**
- Alpine.js (sudah ada di dashboard layout)
- Settings model (sudah ada)
- registration_end_date (sudah ada di Pengaturan Umum)

---

**Generated:** 7 Feb 2026
**Developer:** Claude Code
**Session:** FITUR_COUNTDOWN_DASHBOARD_PENDAFTAR_7_FEB_2026
