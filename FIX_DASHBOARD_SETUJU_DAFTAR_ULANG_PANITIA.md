# Fix Dashboard - Card "Setuju Daftar Ulang" untuk Panitia Daftar Ulang & Panitia Seragam

## 🎯 Tujuan

Mengubah stats overview "Pendaftar Hari Ini" menjadi "Setuju Daftar Ulang" dengan list jalur aktif dan jumlah pendaftar yang setuju daftar ulang per jalur, khusus untuk:
- `/panitia-daftarulang/dashboard` (Panitia Daftar Ulang)
- `/panitia-seragam/dashboard` (Panitia Seragam)

## 🐛 Masalah Sebelumnya

Di dashboard panitia daftar ulang dan panitia seragam, card "Pendaftar Hari Ini" kurang relevan karena:
- Panitia daftar ulang fokus pada proses daftar ulang, bukan pendaftaran baru
- Panitia seragam fokus pada seragam siswa yang sudah lulus dan setuju daftar ulang
- Informasi yang lebih berguna adalah jumlah siswa yang setuju daftar ulang per jalur

## 📋 Perubahan

### Before:
**Card "Pendaftar Hari Ini":**
```
┌─────────────────────────────────────┐
│ Pendaftar Hari Ini                  │
│                                      │
│ 0                                    │
│ Kamis, 29 Januari 2026              │
└─────────────────────────────────────┘
```

### After:
**Card "Setuju Daftar Ulang" (dengan list jalur):**
```
┌─────────────────────────────────────┐
│ ✅ Setuju Daftar Ulang              │
│ 275 Total                            │
│                                      │
│ ● Jalur Prestasi .............. 45  │
│ ● Jalur Reguler .............. 120  │
│ ● Jalur Tahfidz ............... 30  │
│ ● Jalur Zonasi ................ 80  │
└─────────────────────────────────────┘
```

## 📁 Files Modified

### 1. `app/Livewire/Panitia/Dashboard.php`

**Purpose:** Backend untuk dashboard panitia (shared untuk panitia_pendaftaran, panitia_daftarulang, panitia_tes_psikologi)

#### Added Properties (Lines 23-24)

```php
public $isPanitiaDaftarUlang = false; // Flag untuk panitia daftar ulang
public $jalurSetujuDaftarUlang = []; // Data jalur dengan count setuju daftar ulang
```

#### Modified mount() (Lines 38-48)

**BEFORE:**
```php
// Detect panitia tes psikologi role
$this->isPanitiaPsikologi = ($user->role === 'panitia_tes_psikologi');

$this->loadStats();
$this->loadRecentPendaftar();
$this->loadJalurStats();
```

**AFTER:**
```php
// Detect panitia tes psikologi role
$this->isPanitiaPsikologi = ($user->role === 'panitia_tes_psikologi');

// Detect panitia daftar ulang role
$this->isPanitiaDaftarUlang = ($user->role === 'panitia_daftarulang');

$this->loadStats();
$this->loadRecentPendaftar();
$this->loadJalurStats();

// Load jalur setuju daftar ulang untuk panitia daftar ulang
if ($this->isPanitiaDaftarUlang) {
    $this->loadJalurSetujuDaftarUlang();
}
```

#### Added Method loadJalurSetujuDaftarUlang() (Lines 145-169)

```php
public function loadJalurSetujuDaftarUlang()
{
    // Load jalur aktif dengan count pendaftar yang setuju daftar ulang
    $jalurAktif = JalurPendaftaran::where('aktif', true)
        ->orderBy('urutan', 'asc')
        ->get();

    $this->jalurSetujuDaftarUlang = $jalurAktif->map(function ($jalur) {
        // Count pendaftar yang lulus DAN setuju daftar ulang di jalur ini
        $count = $jalur->pendaftar()
            ->where('status_pendaftaran', 'lulus')
            ->where('status_persetujuan_daftar_ulang', 'setuju')
            ->count();

        return [
            'nama' => $jalur->nama,
            'count' => $count,
            'warna' => $jalur->warna,
        ];
    });

    // Hitung total setuju daftar ulang
    $totalSetuju = $this->jalurSetujuDaftarUlang->sum('count');
    $this->stats['total_setuju_daftar_ulang'] = $totalSetuju;
}
```

### 2. `resources/views/livewire/panitia/dashboard.blade.php`

**Purpose:** View untuk dashboard panitia

#### Replaced Card "Pendaftar Hari Ini" (Lines 86-135)

**BEFORE:** Single card showing today's registrations

**AFTER:** Conditional card - berbeda untuk panitia daftar ulang vs role lain

```blade
<!-- Pendaftar Hari Ini / Setuju Daftar Ulang -->
@if($isPanitiaDaftarUlang)
<!-- Setuju Daftar Ulang (Panitia Daftar Ulang) -->
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Setuju Daftar Ulang</p>
                <p class="text-xl font-bold text-gray-900">{{ number_format($stats['total_setuju_daftar_ulang'] ?? 0) }} Total</p>
            </div>
        </div>
    </div>

    <!-- List Jalur -->
    <div class="space-y-2 mt-3">
        @forelse($jalurSetujuDaftarUlang as $jalur)
        <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
            <div class="flex items-center">
                <span class="inline-block w-3 h-3 rounded-full mr-2" style="background-color: {{ $jalur['warna'] ?? '#6B7280' }}"></span>
                <span class="text-sm text-gray-700">{{ $jalur['nama'] }}</span>
            </div>
            <span class="text-sm font-semibold text-gray-900">{{ number_format($jalur['count']) }}</span>
        </div>
        @empty
        <p class="text-sm text-gray-500 text-center py-3">Belum ada yang setuju daftar ulang</p>
        @endforelse
    </div>
</div>
@else
<!-- Pendaftar Hari Ini (Role Lain) -->
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-600">Pendaftar Hari Ini</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['pendaftar_hari_ini']) }}</p>
            <p class="text-sm text-gray-600 mt-2">{{ \Carbon\Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
        </div>
        <div class="w-14 h-14 bg-purple-100 rounded-lg flex items-center justify-center">
            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
    </div>
</div>
@endif
```

### 3. `app/Livewire/PanitiaSeragam/Dashboard.php`

**Purpose:** Backend untuk dashboard panitia seragam (dedicated component)

#### Added Property (Line 17)

```php
public $jalurSetujuDaftarUlang = []; // Data jalur dengan count setuju daftar ulang
```

#### Modified mount() (Lines 29)

**BEFORE:**
```php
$this->loadStats();
$this->loadRecentPendaftar();
$this->loadJalurStats();
```

**AFTER:**
```php
$this->loadStats();
$this->loadRecentPendaftar();
$this->loadJalurStats();
$this->loadJalurSetujuDaftarUlang();
```

#### Added Method loadJalurSetujuDaftarUlang() (Lines 128-152)

```php
public function loadJalurSetujuDaftarUlang()
{
    // Load jalur aktif dengan count pendaftar yang setuju daftar ulang
    $jalurAktif = JalurPendaftaran::where('aktif', true)
        ->orderBy('urutan', 'asc')
        ->get();

    $this->jalurSetujuDaftarUlang = $jalurAktif->map(function ($jalur) {
        // Count pendaftar yang lulus DAN setuju daftar ulang di jalur ini
        $count = $jalur->pendaftar()
            ->where('status_pendaftaran', 'lulus')
            ->where('status_persetujuan_daftar_ulang', 'setuju')
            ->count();

        return [
            'nama' => $jalur->nama,
            'count' => $count,
            'warna' => $jalur->warna,
        ];
    });

    // Hitung total setuju daftar ulang
    $totalSetuju = $this->jalurSetujuDaftarUlang->sum('count');
    $this->stats['total_setuju_daftar_ulang'] = $totalSetuju;
}
```

### 4. `resources/views/livewire/panitia-seragam/dashboard.blade.php`

**Purpose:** View untuk dashboard panitia seragam

#### Added Card "Setuju Daftar Ulang" (Lines 73-103)

**Location:** After stats cards grid, before recent pendaftar section

```blade
{{-- Setuju Daftar Ulang Card --}}
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Setuju Daftar Ulang</p>
                <p class="text-xl font-bold text-gray-900">{{ number_format($stats['total_setuju_daftar_ulang'] ?? 0) }} Total</p>
            </div>
        </div>
    </div>

    {{-- List Jalur --}}
    <div class="space-y-2 mt-3">
        @forelse($jalurSetujuDaftarUlang as $jalur)
        <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
            <div class="flex items-center">
                <span class="inline-block w-3 h-3 rounded-full mr-2" style="background-color: {{ $jalur['warna'] ?? '#6B7280' }}"></span>
                <span class="text-sm text-gray-700">{{ $jalur['nama'] }}</span>
            </div>
            <span class="text-sm font-semibold text-gray-900">{{ number_format($jalur['count']) }}</span>
        </div>
        @empty
        <p class="text-sm text-gray-500 text-center py-3">Belum ada yang setuju daftar ulang</p>
        @endforelse
    </div>
</div>
```

## 🎨 UI Design Details

### Card "Setuju Daftar Ulang"

**Header:**
- Icon: Green checkmark circle (`bg-green-100`, `text-green-600`)
- Title: "Setuju Daftar Ulang"
- Total count: Bold, large font dengan format ribuan

**List Jalur:**
- Setiap jalur tampil dalam row dengan:
  - Color dot (● ) sesuai warna jalur
  - Nama jalur di kiri
  - Count di kanan (bold, format ribuan)
- Border bottom pada setiap row kecuali row terakhir
- Empty state jika belum ada yang setuju

**Example Display:**
```
✅ Setuju Daftar Ulang
   275 Total

● Jalur Prestasi ................... 45
● Jalur Reguler ................... 120
● Jalur Tahfidz .................... 30
● Jalur Zonasi ..................... 80
```

## 🔍 Data Logic

### Query untuk Count Setuju Daftar Ulang

**Kondisi:**
1. `status_pendaftaran` = `'lulus'` (hanya yang lulus)
2. `status_persetujuan_daftar_ulang` = `'setuju'` (hanya yang setuju)
3. Grouped by `jalur_pendaftaran_id`

**Query:**
```php
$count = $jalur->pendaftar()
    ->where('status_pendaftaran', 'lulus')
    ->where('status_persetujuan_daftar_ulang', 'setuju')
    ->count();
```

### Fields Used

**From Table: `pendaftars`**
- `status_pendaftaran` (enum: 'lulus', 'tidak_lulus', etc.)
- `status_persetujuan_daftar_ulang` (enum: 'setuju', 'tidak_setuju', 'belum')
- `jalur_pendaftaran_id` (FK to jalur_pendaftaran)

**From Table: `jalur_pendaftaran`**
- `nama` (Jalur name)
- `warna` (Jalur color for display)
- `aktif` (boolean - only show active jalur)
- `urutan` (integer - for ordering)

## ✅ Testing Checklist

### Test 1: Panitia Daftar Ulang - Card Display
1. Login sebagai `panitia_daftarulang`
2. Akses `/panitia-daftarulang/dashboard`
3. Verifikasi:
   - ✅ Card "Setuju Daftar Ulang" tampil (bukan "Pendaftar Hari Ini")
   - ✅ Total count tampil dengan benar
   - ✅ List jalur tampil di bawah total

### Test 2: Panitia Seragam - Card Display
1. Login sebagai `panitia_seragam`
2. Akses `/panitia-seragam/dashboard`
3. Verifikasi:
   - ✅ Card "Setuju Daftar Ulang" tampil
   - ✅ Card berada setelah 4 stats cards
   - ✅ Card sebelum section "Pendaftar Terbaru"

### Test 3: Data Accuracy - Count per Jalur
1. Query manual di database:
   ```sql
   SELECT jp.nama, COUNT(*)
   FROM pendaftars p
   JOIN jalur_pendaftaran jp ON p.jalur_pendaftaran_id = jp.id
   WHERE p.status_pendaftaran = 'lulus'
   AND p.status_persetujuan_daftar_ulang = 'setuju'
   AND jp.aktif = true
   GROUP BY jp.nama, jp.urutan
   ORDER BY jp.urutan;
   ```
2. Bandingkan hasil query dengan yang tampil di dashboard
3. Verifikasi:
   - ✅ Count per jalur sesuai
   - ✅ Total sesuai sum dari semua jalur
   - ✅ Urutan jalur sesuai field `urutan`

### Test 4: Jalur Color Display
1. Check setiap jalur di dashboard
2. Verifikasi:
   - ✅ Color dot tampil sesuai warna jalur di database
   - ✅ Jika jalur tidak ada warna, fallback ke gray (#6B7280)

### Test 5: Empty State
1. Set semua pendaftar `status_persetujuan_daftar_ulang` = 'tidak_setuju' atau 'belum'
2. Refresh dashboard
3. Verifikasi:
   - ✅ Total = 0
   - ✅ Empty state message tampil: "Belum ada yang setuju daftar ulang"

### Test 6: Panitia Pendaftaran - No Change
1. Login sebagai `panitia_pendaftaran`
2. Akses `/panitia-pendaftaran/dashboard`
3. Verifikasi:
   - ✅ Card "Pendaftar Hari Ini" masih tampil (TIDAK berubah)
   - ✅ Tidak ada card "Setuju Daftar Ulang"

### Test 7: Panitia Tes Psikologi - No Change
1. Login sebagai `panitia_tes_psikologi`
2. Akses `/panitia-tes-psikologi/dashboard`
3. Verifikasi:
   - ✅ Card "Pendaftar Hari Ini" masih tampil (TIDAK berubah)

### Test 8: Jalur Aktif vs Nonaktif
1. Set salah satu jalur `aktif` = false di database
2. Refresh dashboard
3. Verifikasi:
   - ✅ Jalur nonaktif tidak tampil di list
   - ✅ Count jalur nonaktif tidak termasuk di total

### Test 9: Responsive Design
1. Resize browser ke mobile view
2. Verifikasi:
   - ✅ Card tidak overflow
   - ✅ List jalur tetap readable
   - ✅ Number format tidak break

### Test 10: Real-time Update
1. Buka dashboard di tab 1
2. Di tab 2, update pendaftar: ubah `status_persetujuan_daftar_ulang` dari 'belum' ke 'setuju'
3. Refresh tab 1
4. Verifikasi:
   - ✅ Count bertambah
   - ✅ Total updated

## 🚀 Deployment

### Files to Upload:

```
MODIFIED:
- app/Livewire/Panitia/Dashboard.php
- app/Livewire/PanitiaSeragam/Dashboard.php
- resources/views/livewire/panitia/dashboard.blade.php
- resources/views/livewire/panitia-seragam/dashboard.blade.php
```

### No Database Changes:
- ✅ No migration needed
- ✅ Existing fields used: `status_persetujuan_daftar_ulang`, `status_pendaftaran`
- ✅ No model changes

### Clear Cache After Deploy:

```bash
php artisan view:clear
php artisan cache:clear
php artisan config:clear
```

### Verify After Deploy:

1. Login sebagai panitia_daftarulang → Check dashboard
2. Login sebagai panitia_seragam → Check dashboard
3. Login sebagai panitia_pendaftaran → Verify unchanged
4. Verify data accuracy with database query
5. Test empty state scenario

## 💡 Benefits

### Before Fix:
- ❌ Card "Pendaftar Hari Ini" tidak relevan untuk panitia daftar ulang dan seragam
- ❌ Tidak ada visibility untuk count setuju daftar ulang per jalur
- ❌ Panitia harus buka halaman lain untuk lihat breakdown per jalur
- ❌ Tidak ada quick overview distribusi setuju daftar ulang

### After Fix:
- ✅ Card relevan dengan tugas panitia daftar ulang dan seragam
- ✅ Quick overview total setuju daftar ulang
- ✅ Breakdown per jalur langsung visible di dashboard
- ✅ Visual color coding per jalur untuk quick identification
- ✅ Real-time count update saat data berubah
- ✅ Easy monitoring distribusi per jalur untuk planning
- ✅ Role lain (panitia_pendaftaran, panitia_tes_psikologi) tidak terpengaruh

## 📊 Use Cases

### For Panitia Daftar Ulang:

**1. Daily Monitoring**
- Quick check berapa siswa yang sudah setuju daftar ulang hari ini
- Identify jalur mana yang response rate rendah
- Plan follow-up untuk jalur dengan count rendah

**2. Resource Planning**
- Jalur dengan count tinggi butuh resource lebih banyak
- Coordinate dengan tim lain untuk handle volume per jalur
- Schedule verifikasi berkas based on jalur distribution

**3. Target Tracking**
- Set target setuju per jalur
- Monitor progress real-time
- Alert jika jalur tertentu jauh di bawah target

### For Panitia Seragam:

**1. Inventory Planning**
- Count setuju = potential seragam orders
- Plan inventory per jalur distribution
- Coordinate dengan supplier based on count per jalur

**2. Production Scheduling**
- Prioritize jalur dengan count tinggi
- Schedule pengukuran seragam per jalur
- Batch processing based on jalur grouping

**3. Budget Forecast**
- Estimate budget based on setuju count
- Plan payment collection per jalur
- Track actual vs forecast

## 🔧 Technical Details

### Query Performance:

**Optimization:**
- Query menggunakan relationship `pendaftar()` dari JalurPendaftaran model
- Filter `where('aktif', true)` reduce unnecessary jalur
- `orderBy('urutan')` ensure consistent display order
- Collection `map()` untuk transform data efficiently

**Index Recommendations:**
```sql
-- Improve query performance
CREATE INDEX idx_pendaftars_status ON pendaftars(status_pendaftaran, status_persetujuan_daftar_ulang);
CREATE INDEX idx_jalur_aktif_urutan ON jalur_pendaftaran(aktif, urutan);
```

### Data Flow:

```
1. User access dashboard
   ↓
2. Component mount() called
   ↓
3. Detect role (panitia_daftarulang or panitia_seragam)
   ↓
4. loadJalurSetujuDaftarUlang() executed
   ↓
5. Query jalur aktif, order by urutan
   ↓
6. For each jalur: count pendaftar with status lulus + setuju
   ↓
7. Map to array with nama, count, warna
   ↓
8. Calculate total setuju
   ↓
9. Pass to view via $jalurSetujuDaftarUlang and $stats
   ↓
10. View render list dengan @forelse loop
```

## 📝 Notes

### Why Different Implementation for Panitia vs PanitiaSeragam?

**Panitia (Shared Component):**
- Digunakan untuk multiple roles: panitia_pendaftaran, panitia_daftarulang, panitia_tes_psikologi
- Perlu conditional `@if($isPanitiaDaftarUlang)` untuk only show ke panitia_daftarulang
- Card "Pendaftar Hari Ini" tetap relevan untuk panitia_pendaftaran

**PanitiaSeragam (Dedicated Component):**
- Hanya untuk role panitia_seragam
- Tidak ada role lain menggunakan component ini
- Bisa directly add card tanpa conditional
- Tidak ada conflict dengan role lain

### Why Check status_pendaftaran = 'lulus'?

- Status persetujuan daftar ulang hanya relevan untuk yang lulus
- Pendaftar yang tidak lulus tidak bisa daftar ulang
- Query lebih efficient dengan double filter

### Why Use Collection map() vs Query groupBy()?

**Collection map():**
- ✅ Flexible: Bisa access full relationship
- ✅ Easy to add more fields (nama, warna, etc.)
- ✅ Readable code
- ⚠️ Load all jalur first (OK karena jalur count kecil)

**Query groupBy():**
- ✅ More efficient for large dataset
- ❌ Need raw SQL atau complex query builder
- ❌ Harder to maintain

**Conclusion:** Collection map() dipilih karena:
- Jalur count typically < 10 (small dataset)
- Code readability > micro-optimization
- Easy to extend dengan additional fields

### Field status_persetujuan_daftar_ulang Values:

**Enum Values:**
- `'setuju'` - Siswa setuju daftar ulang (COUNT THIS)
- `'tidak_setuju'` - Siswa tidak setuju daftar ulang
- `'belum'` - Siswa belum memilih (default)
- `NULL` - Legacy data (treated as 'belum')

**Count Logic:** Only count `'setuju'`, ignore lainnya

## ✅ Status

**Status:** ✅ **COMPLETE & READY FOR TESTING**

**Fixed On:** 29 Januari 2026
**Fixed By:** Claude Code
**Breaking Changes:** None
**Backward Compatible:** Yes
**Impact:** Hanya panitia_daftarulang dan panitia_seragam, role lain tidak terpengaruh

---

**Dokumentasi:** 29 Januari 2026
**Version:** 1.0.0
