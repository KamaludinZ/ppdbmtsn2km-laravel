# Fix: Pindahkan Posisi Tab Keahlian dan Tahfidz

## 🎯 Tujuan
Memindahkan tab **Keahlian** dan **Tahfidz** agar berada di sebelah kanan tab **Prestasi**, sesuai permintaan user.

## 📍 Urutan Tab Sebelum Fix

```
1. Data Siswa
2. Orangtua
3. Alamat
4. Prestasi
5. Beasiswa
6. Pendidikan Lain
7. Keahlian          ← Posisi lama
8. Tahfidz           ← Posisi lama
9. Dokumen
10. Daftar Ulang
11. Survey
```

## ✅ Urutan Tab Setelah Fix

```
1. Data Siswa
2. Orangtua
3. Alamat
4. Prestasi
5. Keahlian          ← Dipindah ke sini (setelah Prestasi)
6. Tahfidz           ← Dipindah ke sini
7. Beasiswa
8. Pendidikan Lain
9. Dokumen
10. Daftar Ulang
11. Survey
```

## 🔧 Changes Applied

### 1. Admin DetailPendaftar View
**File**: `resources/views/livewire/admin/detail-pendaftar.blade.php`

#### A. Pindahkan Tab Buttons (Line 150-169)
**Sebelum**: Urutan Prestasi → Beasiswa → Pendidikan → Keahlian → Tahfidz

**Setelah**: Urutan Prestasi → Keahlian → Tahfidz → Beasiswa → Pendidikan

```blade
<!-- Urutan baru -->
<button @click="activeTab = 'prestasi'" ...>Prestasi Siswa</button>
<button @click="activeTab = 'keahlian'" ...>Keahlian</button>        ← DIPINDAH
<button @click="activeTab = 'tahfidz'" ...>Tahfidz</button>          ← DIPINDAH
<button @click="activeTab = 'beasiswa'" ...>Beasiswa & Bantuan</button>
<button @click="activeTab = 'pendidikan'" ...>Pendidikan Lain</button>
```

#### B. Pindahkan Tab Content (Line 202-225)
**Sebelum**: Urutan Prestasi → Beasiswa → Pendidikan → Keahlian → Tahfidz

**Setelah**: Urutan Prestasi → Keahlian → Tahfidz → Beasiswa → Pendidikan

```blade
<!-- Urutan baru -->
<div x-show="activeTab === 'prestasi'" x-transition>
    @include('tab_templates.tab-prestasi')
</div>

<div x-show="activeTab === 'keahlian'" x-transition>    ← DIPINDAH
    @include('tab_templates.tab-keahlian')
</div>

<div x-show="activeTab === 'tahfidz'" x-transition>    ← DIPINDAH
    @include('tab_templates.tab-tahfidz')
</div>

<div x-show="activeTab === 'beasiswa'" x-transition>
    @include('tab_templates.tab-beasiswa')
</div>

<div x-show="activeTab === 'pendidikan'" x-transition>
    @include('tab_templates.tab-pendidikan-lain')
</div>
```

---

### 2. Panitia DetailPendaftar View
**File**: `resources/views/livewire/panitia/detail-pendaftar.blade.php`

#### A. Pindahkan Tab Buttons (Line 150-169)
Perubahan yang sama seperti Admin.

#### B. Pindahkan Tab Content (Line 202-225)
Perubahan yang sama seperti Admin.

---

## 📊 Visual Comparison

### Tab Navigation Bar - Sebelum Fix
```
┌─────────────────────────────────────────────────────────────────┐
│ [Siswa] [Orangtua] [Alamat] [Prestasi] [Beasiswa] [Pendidikan] │
│ [Keahlian] [Tahfidz] [Dokumen] [Daftar Ulang] [Survey]         │
└─────────────────────────────────────────────────────────────────┘
```

### Tab Navigation Bar - Setelah Fix
```
┌─────────────────────────────────────────────────────────────────┐
│ [Siswa] [Orangtua] [Alamat] [Prestasi] [Keahlian] [Tahfidz]    │
│ [Beasiswa] [Pendidikan] [Dokumen] [Daftar Ulang] [Survey]      │
└─────────────────────────────────────────────────────────────────┘
```

**Keuntungan**: Tab Keahlian dan Tahfidz sekarang lebih logis karena bersebelahan dengan Prestasi (semua terkait prestasi/achievement siswa).

---

## 🧪 Testing Checklist

### Admin Dashboard
- [ ] Login sebagai admin
- [ ] Buka `/admin/pendaftar/29`
- [ ] Verify: Tab navigation menampilkan urutan: Prestasi → Keahlian → Tahfidz → Beasiswa
- [ ] Klik tab Prestasi → verify content benar
- [ ] Klik tab Keahlian → verify content benar
- [ ] Klik tab Tahfidz → verify content benar
- [ ] Klik tab Beasiswa → verify content benar

### Panitia Dashboard
- [ ] Login sebagai panitia
- [ ] Buka `/panitia-pendaftaran/pendaftar/29`
- [ ] Verify: Tab navigation menampilkan urutan: Prestasi → Keahlian → Tahfidz → Beasiswa
- [ ] Klik tab Prestasi → verify content benar
- [ ] Klik tab Keahlian → verify content benar
- [ ] Klik tab Tahfidz → verify content benar
- [ ] Klik tab Beasiswa → verify content benar

---

## 📝 Files Modified

### Admin
1. ✅ `resources/views/livewire/admin/detail-pendaftar.blade.php`
   - Line 150-169: Reorder tab buttons
   - Line 202-225: Reorder tab content

### Panitia
2. ✅ `resources/views/livewire/panitia/detail-pendaftar.blade.php`
   - Line 150-169: Reorder tab buttons
   - Line 202-225: Reorder tab content

**No Backend Changes**: Tidak ada perubahan di PHP/Livewire component karena hanya reorder UI.

---

## 💡 Reasoning

### Mengapa Keahlian dan Tahfidz dipindah ke setelah Prestasi?

**Grouping Logis**:
- **Prestasi** = Achievement/pencapaian siswa
- **Keahlian** = Skills/kemampuan siswa (achievement juga)
- **Tahfidz** = Hafalan Al-Qur'an (achievement juga)

Ketiga tab ini terkait dengan **kemampuan dan pencapaian siswa**, jadi lebih logis jika dikelompokkan berdekatan.

**Beasiswa** dan **Pendidikan Lain** lebih terkait dengan **latar belakang dan dukungan**, jadi dipisahkan.

---

**Tanggal**: 31 Januari 2026
**Status**: ✅ Complete
**Impact**: Tab order lebih logis dan sesuai permintaan user
