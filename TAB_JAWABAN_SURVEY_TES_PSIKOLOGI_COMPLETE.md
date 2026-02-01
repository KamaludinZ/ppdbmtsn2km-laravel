# Tab Jawaban Survey Tes Psikologi

## 🎯 Tujuan
Memberikan akses untuk melihat dan menganalisis jawaban survey dari pendaftar tes psikologi dalam format tabel yang mudah dibaca, dengan kolom dinamis berdasarkan pertanyaan survey yang ada.

## ✨ Fitur yang Ditambahkan

### 1. Tab Baru: "Jawaban Survey"
Tab ketiga yang menampilkan data jawaban survey pendaftar, tersedia di:
- `/admin/tes-psikologi?mainTab=jawaban-survey` - Untuk admin
- `/panitia-psikologi/tes-psikologi?mainTab=jawaban-survey` - Untuk panitia_tes_psikologi

### 2. Kolom Tabel

#### Kolom Fixed:
1. **Nomor Pendaftaran** - Sticky column (fixed saat scroll horizontal)
2. **NISN** - Nomor Induk Siswa Nasional
3. **Nama Pendaftar** - Nama lengkap pendaftar
4. **Status Survey** - Badge "Sudah Dijawab" atau "Belum Dijawab"

#### Kolom Dinamis:
- **Satu kolom untuk setiap pertanyaan survey aktif**
- Header menampilkan pertanyaan (maksimal 50 karakter)
- Sub-header menampilkan deskripsi (maksimal 60 karakter)
- Kolom minimal width 200px untuk readability

### 3. Fitur Interaktif

#### Expand/Collapse untuk Jawaban Panjang:
- Jawaban > 100 karakter di-truncate
- Tombol "Lihat Selengkapnya" untuk expand
- Tombol "Sembunyikan" untuk collapse kembali

#### Search Functionality:
- Search by nama pendaftar
- Search by NISN
- Search by nomor pendaftaran
- Real-time filtering dengan `wire:model.live`

#### Pagination:
- 15 entries per page
- Pagination links di bawah tabel

## 📂 File yang Dimodifikasi/Dibuat

### Backend Files

#### 1. `app/Livewire/Admin/TesPsikologi.php`

**Property Updated:**
```php
#[Url(keep: true)]
public $mainTab = 'data'; // Added 'jawaban-survey' as valid value
```

**render() Method - Added (lines 925-983):**
```php
// Get survey answers data for jawaban-survey tab
$surveyAnswersData = [];
$surveyQuestions = [];
if ($this->mainTab === 'jawaban-survey') {
    // Get all active survey questions for tes psikologi
    $surveyQuestions = SurveyQuestion::tesPsikologi()
        ->aktif()
        ->ordered()
        ->get();

    // Get all pendaftar who registered for tes psikologi
    $query = TesPsikologiPendaftar::with(['pendaftar'])
        ->whereHas('pendaftar');

    // Apply search filter
    if ($this->search) {
        $query->whereHas('pendaftar', function($q) {
            $q->where('nama_lengkap', 'like', '%'.$this->search.'%')
              ->orWhere('nisn', 'like', '%'.$this->search.'%')
              ->orWhere('nomor_pendaftaran', 'like', '%'.$this->search.'%');
        });
    }

    $tesPsikologiData = $query->orderBy('created_at', 'desc')->paginate(15);

    // For each pendaftar, get their survey answers
    foreach ($tesPsikologiData as $data) {
        $pendaftar = $data->pendaftar;

        // Check if pendaftar has answered survey
        $answersCount = SurveyAnswer::where('pendaftar_id', $pendaftar->id)
            ->whereHas('question', function($q) {
                $q->tesPsikologi();
            })
            ->count();

        $surveyStatus = $answersCount > 0 ? 'Sudah Dijawab' : 'Belum Dijawab';

        // Get answers for each question
        $answers = [];
        foreach ($surveyQuestions as $question) {
            $answer = SurveyAnswer::where('pendaftar_id', $pendaftar->id)
                ->where('survey_question_id', $question->id)
                ->first();

            $answers[$question->id] = $answer ? $answer->jawaban : '-';
        }

        $surveyAnswersData[] = [
            'nomor_pendaftaran' => $pendaftar->nomor_pendaftaran,
            'nisn' => $pendaftar->nisn ?? '-',
            'nama_lengkap' => $pendaftar->nama_lengkap,
            'survey_status' => $surveyStatus,
            'answers' => $answers
        ];
    }

    $dataPsikologi = $tesPsikologiData; // For pagination links
}

// Pass to view
return view('livewire.admin.tes-psikologi', compact('stats', 'dataPsikologi', 'setting', 'surveyAnswersData', 'surveyQuestions'))
```

**Data Structure Passed to View:**
```php
$surveyAnswersData = [
    [
        'nomor_pendaftaran' => 'P2026-001',
        'nisn' => '0012345678',
        'nama_lengkap' => 'Ahmad Budi',
        'survey_status' => 'Sudah Dijawab',
        'answers' => [
            1 => 'Instagram',
            2 => 'Saya ingin belajar agama lebih dalam...',
            3 => 'Ya',
            // ... answers for each question ID
        ]
    ],
    // ... more pendaftar
];

$surveyQuestions = [
    SurveyQuestion {
        id: 1,
        pertanyaan: 'Bagaimana Anda mengetahui PPDB ini?',
        deskripsi: 'Pilih salah satu',
        tipe_input: 'radio',
        urutan: 1,
        // ...
    },
    // ... more questions
];
```

#### 2. `app/Livewire/Panitia/TesPsikologi.php`

**Property Updated:**
```php
#[Url(keep: true)]
public $mainTab = 'data'; // Added 'jawaban-survey' as valid value
```

**render() Method - Added (lines 728-786):**
Similar logic to admin, but with conditional check:
```php
// Get survey answers data for jawaban-survey tab (only for panitia_tes_psikologi)
$surveyAnswersData = [];
$surveyQuestionsForAnswers = [];
if ($this->mainTab === 'jawaban-survey' && $this->isPanitiaPsikologi) {
    // ... same logic as admin
}

// Pass to view
return view('livewire.panitia.tes-psikologi', [
    'dataPsikologi' => $dataPsikologi,
    'setting' => $setting,
    'surveyQuestions' => $this->surveyQuestions,
    'isPanitiaPsikologi' => $this->isPanitiaPsikologi,
    'surveyAnswersData' => $surveyAnswersData,
    'surveyQuestionsForAnswers' => $surveyQuestionsForAnswers
]);
```

**Note:** Variable name different: `surveyQuestionsForAnswers` instead of `surveyQuestions` to avoid conflict with existing `$this->surveyQuestions`.

### Frontend Files

#### 3. `resources/views/livewire/admin/tes-psikologi.blade.php`

**Tab Navigation Updated (lines 106-140):**
```blade
<nav class="flex -mb-px">
    <!-- Data Psikologi Tab -->
    <button wire:click="setMainTab('data')" class="...">
        Data Psikologi
    </button>

    <!-- Jawaban Survey Tab - NEW -->
    <button wire:click="setMainTab('jawaban-survey')" class="py-4 px-8 text-center border-b-2 font-medium text-sm transition focus:outline-none {{ $mainTab === 'jawaban-survey' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
        </svg>
        Jawaban Survey
    </button>

    <!-- Pertanyaan Survey Tab -->
    <button wire:click="setMainTab('survey')" class="...">
        Pertanyaan Survey
    </button>

    <!-- Pengaturan Tab -->
    <button wire:click="setMainTab('settings')" class="...">
        Pengaturan
    </button>
</nav>
```

**Tab Content Updated (lines 142-157):**
```blade
@if(Auth::user()->role === 'admin')
    @if($mainTab === 'data')
        @include('livewire.admin.partials.tes-psikologi-data')
    @elseif($mainTab === 'jawaban-survey')
        @include('livewire.admin.partials.tes-psikologi-jawaban-survey')
    @elseif($mainTab === 'survey')
        @include('livewire.admin.partials.tes-psikologi-survey')
    @else
        @include('livewire.admin.partials.tes-psikologi-settings')
    @endif
@else
    @include('livewire.admin.partials.tes-psikologi-data')
@endif
```

#### 4. `resources/views/livewire/panitia/tes-psikologi.blade.php`

**Tab Navigation Updated (lines 106-134):**
```blade
@if($isPanitiaPsikologi)
    <nav class="flex -mb-px">
        <!-- Data Psikologi Tab -->
        <a href="?mainTab=data" wire:navigate class="...">
            Data Psikologi
        </a>

        <!-- Jawaban Survey Tab - NEW -->
        <a href="?mainTab=jawaban-survey" wire:navigate class="py-4 px-8 text-center border-b-2 font-medium text-sm transition focus:outline-none {{ $mainTab === 'jawaban-survey' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
            Jawaban Survey
        </a>

        <!-- Pertanyaan Survey Tab -->
        <a href="?mainTab=survey" wire:navigate class="...">
            Pertanyaan Survey
        </a>
    </nav>
@endif
```

**Tab Content Updated (lines 136-150):**
```blade
@if($isPanitiaPsikologi)
    @if($mainTab === 'data')
        @include('livewire.admin.partials.tes-psikologi-data')
    @elseif($mainTab === 'jawaban-survey')
        @include('livewire.panitia.partials.tes-psikologi-jawaban-survey')
    @elseif($mainTab === 'survey')
        @include('livewire.panitia.partials.tes-psikologi-survey')
    @endif
@else
    @include('livewire.admin.partials.tes-psikologi-data')
@endif
```

#### 5. `resources/views/livewire/admin/partials/tes-psikologi-jawaban-survey.blade.php` - CREATED

Partial baru yang menampilkan tabel jawaban survey untuk admin.

**Features:**
- Header dengan background indigo-600
- Search field integrated
- Empty state untuk 2 kondisi:
  - Belum ada data pendaftar
  - Belum ada pertanyaan survey aktif
- Sticky first column (Nomor Pendaftaran)
- Dynamic columns based on survey questions
- Expand/collapse untuk jawaban panjang (>100 karakter)
- Status badge (Sudah/Belum Dijawab)
- Pagination

#### 6. `resources/views/livewire/panitia/partials/tes-psikologi-jawaban-survey.blade.php` - CREATED

Partial baru yang menampilkan tabel jawaban survey untuk panitia_tes_psikologi.

**Same features as admin partial**, dengan satu perbedaan variable name:
- Admin: `$surveyQuestions`
- Panitia: `$surveyQuestionsForAnswers`

## 🎨 UI/UX Details

### Tab Navigation
```
┌──────────────────────────────────────────────────────────────────────┐
│ [Data Psikologi] [Jawaban Survey] [Pertanyaan Survey] [Pengaturan]  │ ← Admin
└──────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────┐
│ [Data Psikologi] [Jawaban Survey] [Pertanyaan Survey]                │ ← Panitia Psikologi
└──────────────────────────────────────────────────────────────────────┘
```

### Tabel Jawaban Survey
```
┌─────────────┬──────┬──────────────┬────────────┬──────────────────┬──────────────────┐
│ No Daftar   │ NISN │ Nama         │ Status     │ Pertanyaan 1     │ Pertanyaan 2     │
│ (Sticky)    │      │ Pendaftar    │ Survey     │                  │                  │
├─────────────┼──────┼──────────────┼────────────┼──────────────────┼──────────────────┤
│ P2026-001   │ 0012 │ Ahmad Budi   │ Sudah      │ Instagram        │ Saya ingin...    │
│             │      │              │ Dijawab    │                  │ [Lihat Seleng..] │
├─────────────┼──────┼──────────────┼────────────┼──────────────────┼──────────────────┤
│ P2026-002   │ 0013 │ Siti Aisyah  │ Belum      │ -                │ -                │
│             │      │              │ Dijawab    │                  │                  │
└─────────────┴──────┴──────────────┴────────────┴──────────────────┴──────────────────┘
```

### Empty States

**No Data:**
```
┌────────────────────────────────────────────┐
│                                            │
│           📄 Icon                          │
│                                            │
│    Belum ada data jawaban survey           │
│                                            │
│ Data akan muncul setelah pendaftar         │
│ mengisi survey tes psikologi               │
│                                            │
└────────────────────────────────────────────┘
```

**No Questions:**
```
┌────────────────────────────────────────────┐
│                                            │
│           ⚠️ Icon                          │
│                                            │
│    Belum ada pertanyaan survey aktif       │
│                                            │
│ Silakan buat pertanyaan survey terlebih    │
│ dahulu di tab Pertanyaan Survey            │
│                                            │
└────────────────────────────────────────────┘
```

## 🔄 Data Flow

### Load Survey Answers:
```
User navigates to ?mainTab=jawaban-survey
    ↓
Component render() method
    ↓
Load active survey questions (context = 'tes_psikologi')
    ↓
Load all tes psikologi pendaftar with search filter
    ↓
For each pendaftar:
  - Check if has answered survey (count answers)
  - Determine survey status (Sudah/Belum)
  - Get answer for each question
    ↓
Build surveyAnswersData array
    ↓
Pass to view with pagination
    ↓
View renders table with dynamic columns
```

### Search Flow:
```
User types in search field
    ↓
wire:model.live triggers
    ↓
Component re-renders
    ↓
Query filtered by:
  - nama_lengkap LIKE %search%
  - OR nisn LIKE %search%
  - OR nomor_pendaftaran LIKE %search%
    ↓
Filtered results displayed
```

## 📊 Role-Based Access Matrix

| Role | URL | Tab "Jawaban Survey" | Access |
|------|-----|----------------------|--------|
| **admin** | `/admin/tes-psikologi?mainTab=jawaban-survey` | ✅ YES | Full access |
| **panitia_tes_psikologi** | `/panitia-psikologi/tes-psikologi?mainTab=jawaban-survey` | ✅ YES | Full access |
| **panitia_pendaftaran** | `/panitia-pendaftaran/tes-psikologi` | ❌ NO | No tab navigation |
| **panitia_daftarulang** | `/panitia-daftarulang/tes-psikologi` | ❌ NO | No tab navigation |

## 🧪 Testing Checklist

### Test 1: Sebagai Admin
1. Login sebagai admin
2. Akses `/admin/tes-psikologi`
3. Verifikasi:
   - ✅ Tab "Jawaban Survey" muncul setelah "Data Psikologi"
   - ✅ Klik tab "Jawaban Survey"
   - ✅ Tabel muncul dengan kolom fixed (No Daftar, NISN, Nama, Status)
   - ✅ Kolom dinamis muncul sesuai jumlah pertanyaan survey
   - ✅ Data pendaftar ter-load dengan paginasi
   - ✅ Search field berfungsi
   - ✅ Jawaban panjang ada tombol expand/collapse
   - ✅ Status survey badge muncul (Sudah/Belum Dijawab)

### Test 2: Sebagai Panitia Tes Psikologi
1. Login sebagai panitia_tes_psikologi
2. Akses `/panitia-psikologi/tes-psikologi`
3. Verifikasi:
   - ✅ Tab navigation muncul (3 tabs)
   - ✅ Tab "Jawaban Survey" ada di tengah
   - ✅ Klik tab "Jawaban Survey"
   - ✅ Tabel muncul dengan semua fitur sama seperti admin
   - ✅ Data ter-load dengan benar
   - ✅ Search & pagination berfungsi

### Test 3: Empty State - No Data
1. Login sebagai admin/panitia_tes_psikologi
2. Akses tab "Jawaban Survey" saat belum ada pendaftar
3. Verifikasi:
   - ✅ Empty state muncul
   - ✅ Icon dan pesan "Belum ada data jawaban survey"
   - ✅ Keterangan informatif ditampilkan

### Test 4: Empty State - No Questions
1. Login sebagai admin/panitia_tes_psikologi
2. Nonaktifkan semua pertanyaan survey atau belum ada pertanyaan
3. Akses tab "Jawaban Survey"
4. Verifikasi:
   - ✅ Empty state muncul
   - ✅ Icon warning dan pesan "Belum ada pertanyaan survey aktif"
   - ✅ Link ke tab "Pertanyaan Survey" ada dan berfungsi

### Test 5: Dynamic Columns
1. Buat 3 pertanyaan survey (text, textarea, radio)
2. Beberapa pendaftar jawab survey
3. Akses tab "Jawaban Survey"
4. Verifikasi:
   - ✅ 3 kolom tambahan muncul (sesuai jumlah pertanyaan)
   - ✅ Header menampilkan pertanyaan dengan truncate jika panjang
   - ✅ Sub-header menampilkan deskripsi jika ada
   - ✅ Jawaban ditampilkan sesuai dengan pertanyaan

### Test 6: Expand/Collapse
1. Pendaftar jawab pertanyaan dengan jawaban >100 karakter
2. Akses tab "Jawaban Survey"
3. Verifikasi:
   - ✅ Jawaban di-truncate ke 100 karakter
   - ✅ Tombol "Lihat Selengkapnya" muncul
   - ✅ Klik tombol → Jawaban full expand
   - ✅ Tombol berubah jadi "Sembunyikan"
   - ✅ Klik "Sembunyikan" → Jawaban collapse kembali

### Test 7: Search Functionality
1. Akses tab "Jawaban Survey" dengan data pendaftar
2. Test search dengan:
   - Nama pendaftar (sebagian) → ✅ Filter bekerja
   - NISN lengkap → ✅ Filter bekerja
   - Nomor pendaftaran → ✅ Filter bekerja
   - String yang tidak ada → ✅ Empty result
3. Clear search → ✅ Semua data muncul kembali

### Test 8: Pagination
1. Ada lebih dari 15 pendaftar
2. Akses tab "Jawaban Survey"
3. Verifikasi:
   - ✅ Hanya 15 entries ditampilkan per halaman
   - ✅ Pagination links muncul di bawah
   - ✅ Klik halaman 2 → Data halaman 2 muncul
   - ✅ URL updated dengan `?page=2`

### Test 9: Sticky Column
1. Akses tab dengan banyak pertanyaan survey (>5)
2. Scroll horizontal ke kanan
3. Verifikasi:
   - ✅ Kolom "Nomor Pendaftaran" tetap fixed di kiri
   - ✅ Kolom lain scroll normal
   - ✅ Sticky column memiliki background putih (tidak transparan)

### Test 10: Panitia Lain
1. Login sebagai panitia_pendaftaran
2. Akses `/panitia-pendaftaran/tes-psikologi`
3. Verifikasi:
   - ✅ Tab navigation TIDAK muncul
   - ✅ Langsung menampilkan data table
   - ✅ Tidak ada akses ke tab "Jawaban Survey"

## 🔒 Security Notes

### 1. Role-Based Access
- Tab hanya muncul untuk admin dan panitia_tes_psikologi
- Conditional check di backend: `if ($this->mainTab === 'jawaban-survey' && $this->isPanitiaPsikologi)`
- URL manipulation tidak akan menampilkan data jika role tidak sesuai

### 2. Context Filtering
- Hanya survey dengan `context = 'tes_psikologi'` yang di-load
- Method `SurveyQuestion::tesPsikologi()` memastikan filtering tepat
- Tidak tercampur dengan survey context lain

### 3. Data Privacy
- Hanya pendaftar yang mendaftar tes psikologi yang ditampilkan
- Tidak ada link/action untuk edit jawaban (view-only)
- Jawaban sensitive di-truncate dengan opsi expand manual

## 📝 Catatan Penting

### 1. Performance Consideration
Untuk setiap pendaftar, ada loop untuk setiap pertanyaan:
```php
foreach ($tesPsikologiData as $data) {
    foreach ($surveyQuestions as $question) {
        // Query answer
    }
}
```

**Optimization Potential:** Bisa di-eager load dengan:
```php
$allAnswers = SurveyAnswer::whereIn('pendaftar_id', $pendaftarIds)
    ->whereHas('question', function($q) {
        $q->tesPsikologi();
    })
    ->get()
    ->groupBy('pendaftar_id');
```

### 2. Variable Name Difference
- **Admin:** `$surveyQuestions`
- **Panitia:** `$surveyQuestionsForAnswers`

Ini untuk menghindari konflik dengan existing `$this->surveyQuestions` di panitia component.

### 3. Sticky Column
Menggunakan CSS class `sticky left-0` dengan `z-10` untuk memastikan kolom pertama tetap visible saat scroll horizontal.

### 4. Alpine.js Dependency
Fitur expand/collapse menggunakan Alpine.js `x-data` dan `x-show`. Pastikan Alpine.js sudah di-load di layout.

## 🚀 Deployment Checklist

### Files Changed/Created
```
MODIFIED:
- app/Livewire/Admin/TesPsikologi.php
- app/Livewire/Panitia/TesPsikologi.php
- resources/views/livewire/admin/tes-psikologi.blade.php
- resources/views/livewire/panitia/tes-psikologi.blade.php

CREATED:
- resources/views/livewire/admin/partials/tes-psikologi-jawaban-survey.blade.php
- resources/views/livewire/panitia/partials/tes-psikologi-jawaban-survey.blade.php
```

### No Database Changes
- Tidak ada migration baru
- Menggunakan tabel existing
- Hanya perubahan logic dan view

### Clear Cache After Deploy
```bash
php artisan view:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Verify After Deploy
1. Login sebagai admin → Test tab "Jawaban Survey"
2. Login sebagai panitia_tes_psikologi → Test tab "Jawaban Survey"
3. Login sebagai panitia lain → Verify no access
4. Test search functionality
5. Test pagination
6. Test expand/collapse

## ✅ Benefit Fitur

### 1. Analisis Survey Lebih Mudah
- ✅ Semua jawaban dalam satu tabel
- ✅ Kolom dinamis menyesuaikan pertanyaan
- ✅ Easy comparison antar pendaftar

### 2. Export-Ready Data
- ✅ Format tabel cocok untuk screenshot/export
- ✅ Data terstruktur rapi
- ✅ Dapat dikembangkan untuk export Excel/PDF

### 3. Efficient Workflow
- ✅ Tidak perlu buka detail satu-satu
- ✅ Search & filter terintegrasi
- ✅ Pagination untuk handle data banyak

### 4. Role-Based Transparency
- ✅ Admin dan panitia_tes_psikologi punya akses sama
- ✅ Monitoring survey completion rate
- ✅ Identify pendaftar yang belum jawab

## 📚 Related Documentation

- `PANITIA_TES_PSIKOLOGI_SURVEY_FULL_CRUD.md` - CRUD akses untuk pertanyaan survey
- `PANITIA_TES_PSIKOLOGI_TAB_SURVEY_COMPLETE.md` - Tab survey (view-only version)
- `PANITIA_TES_PSIKOLOGI_EXTENDED_COLUMNS_COMPLETE.md` - Extended columns di tab data
- `FITUR_SURVEY_TES_PSIKOLOGI_SUMMARY.md` - Sistem survey tes psikologi

---

**Dokumentasi:** 29 Januari 2026
**Author:** Claude Code
**Version:** 1.0.0
**Status:** ✅ Complete & Ready for Testing
**Breaking Changes:** None
**Backward Compatible:** Yes
