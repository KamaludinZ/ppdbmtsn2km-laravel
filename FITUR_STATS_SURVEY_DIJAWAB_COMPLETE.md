# Fitur: Stats Card Survey Dijawab - COMPLETE ✅

## Tanggal
29 Januari 2026

## Ringkasan
Menambahkan stats card baru di halaman admin dan panitia tes psikologi untuk menampilkan jumlah pendaftar yang sudah mengisi survey tes psikologi.

---

## 1. Fitur yang Ditambahkan

### Card "Survey Dijawab"
- **Lokasi**: Stats overview di halaman tes psikologi
- **Icon**: Clipboard dengan checkmark (indigo)
- **Warna**: Indigo (bg-indigo-100, text-indigo-600)
- **Data**: Jumlah pendaftar yang sudah mengisi survey tes psikologi
- **Scope**: Hanya menghitung survey dengan context 'tes_psikologi'

### Stats Cards (Total: 6 Cards)
1. Total Pendaftar (Blue)
2. Menunggu Verifikasi (Yellow)
3. Disetujui (Green)
4. Ditolak (Red)
5. Perlu Perbaikan (Orange)
6. **Survey Dijawab (Indigo)** ← NEW!

---

## 2. File yang Dimodifikasi

### File 1: `app/Livewire/Admin/TesPsikologi.php`

#### Tambahkan Stats Survey Dijawab di render() (Lines 896-900)
```php
public function render()
{
    // Get statistics
    $stats = [
        'total' => TesPsikologiPendaftar::count(),
        'menunggu' => TesPsikologiPendaftar::menungguVerifikasi()->count(),
        'disetujui' => TesPsikologiPendaftar::disetujui()->count(),
        'ditolak' => TesPsikologiPendaftar::ditolak()->count(),
        'perbaiki' => TesPsikologiPendaftar::where('status_verifikasi', 'perbaiki')->count(),
        'survey_dijawab' => SurveyAnswer::whereHas('question', function($q) {
                $q->tesPsikologi();
            })
            ->distinct('pendaftar_id')
            ->count('pendaftar_id'),
    ];
    // ...
}
```

**Penjelasan Query:**
```php
SurveyAnswer::whereHas('question', function($q) {
    $q->tesPsikologi(); // Filter hanya survey tes psikologi
})
->distinct('pendaftar_id') // Hitung unique pendaftar
->count('pendaftar_id')    // Count berdasarkan pendaftar_id
```

- `whereHas('question', ...)` - Filter jawaban yang pertanyaannya adalah survey tes psikologi
- `tesPsikologi()` - Scope di SurveyQuestion: `where('context', 'tes_psikologi')`
- `distinct('pendaftar_id')` - Hitung satu pendaftar hanya sekali (meski jawab banyak pertanyaan)
- `count('pendaftar_id')` - Hitung total pendaftar unik

---

### File 2: `app/Livewire/Panitia/TesPsikologi.php`

#### Update loadStats() Method (Lines 50-71)
```php
public function loadStats()
{
    $total = TesPsikologiPendaftar::count();
    $menunggu = TesPsikologiPendaftar::where('status_verifikasi', 'menunggu')->count();
    $disetujui = TesPsikologiPendaftar::where('status_verifikasi', 'disetujui')->count();
    $ditolak = TesPsikologiPendaftar::where('status_verifikasi', 'ditolak')->count();
    $perbaiki = TesPsikologiPendaftar::where('status_verifikasi', 'perbaiki')->count();
    $surveyDijawab = SurveyAnswer::whereHas('question', function($q) {
            $q->tesPsikologi();
        })
        ->distinct('pendaftar_id')
        ->count('pendaftar_id');

    $this->stats = [
        'total' => $total,
        'menunggu' => $menunggu,
        'disetujui' => $disetujui,
        'ditolak' => $ditolak,
        'perbaiki' => $perbaiki,
        'survey_dijawab' => $surveyDijawab,
    ];
}
```

**Catatan:**
- Method `loadStats()` dipanggil di `mount()` dan `changeStatus()`
- Memastikan stats selalu ter-update setelah ada perubahan

---

### File 3: `resources/views/livewire/admin/tes-psikologi.blade.php`

#### Update Grid dan Tambah Card (Lines 14-104)

**Before:**
```blade
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
    <!-- 5 cards -->
</div>
```

**After:**
```blade
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mb-8">
    <!-- 6 cards -->
</div>
```

**Responsive Grid Breakdown:**
- `grid-cols-1` - Mobile: 1 column
- `md:grid-cols-2` - Tablet: 2 columns
- `lg:grid-cols-3` - Laptop: 3 columns
- `xl:grid-cols-6` - Desktop: 6 columns

#### Survey Dijawab Card (Lines 90-103)
```blade
<!-- Survey Dijawab Card -->
<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
    <div class="flex items-center">
        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Survey Dijawab</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['survey_dijawab'] }}</p>
        </div>
    </div>
</div>
```

**Icon SVG:** Clipboard with checkmark
- Represents: Survey completion / form filled
- Color scheme: Indigo (professional, informational)

---

### File 4: `resources/views/livewire/panitia/tes-psikologi.blade.php`

#### Update yang Sama (Lines 14-104)
- Grid responsive: `lg:grid-cols-3 xl:grid-cols-6`
- Card Survey Dijawab ditambahkan di posisi ke-6

**Identical implementation dengan admin view.**

---

## 3. Query Logic Explained

### Mengapa Menggunakan distinct() dan count()?

**Skenario:**
- Pendaftar A menjawab 3 pertanyaan survey tes psikologi
- Pendaftar B menjawab 2 pertanyaan survey tes psikologi
- Pendaftar C belum menjawab sama sekali

**Query Tanpa distinct():**
```php
SurveyAnswer::whereHas('question', function($q) {
    $q->tesPsikologi();
})->count(); // Result: 5 (total jawaban)
```
❌ **Salah!** Kita tidak ingin menghitung jawaban, tapi pendaftar.

**Query Dengan distinct():**
```php
SurveyAnswer::whereHas('question', function($q) {
    $q->tesPsikologi();
})
->distinct('pendaftar_id')
->count('pendaftar_id'); // Result: 2 (A dan B)
```
✅ **Benar!** Menghitung pendaftar unik yang sudah menjawab.

### Alternatif Query (Sama Hasilnya)

**Menggunakan GROUP BY:**
```php
SurveyAnswer::whereHas('question', function($q) {
    $q->tesPsikologi();
})
->groupBy('pendaftar_id')
->get()
->count();
```

**Menggunakan DB Raw:**
```php
DB::table('survey_answers')
    ->join('survey_questions', 'survey_answers.survey_question_id', '=', 'survey_questions.id')
    ->where('survey_questions.context', 'tes_psikologi')
    ->distinct('survey_answers.pendaftar_id')
    ->count('survey_answers.pendaftar_id');
```

**Query yang digunakan (Eloquent) lebih readable dan maintainable.**

---

## 4. Visual Design

### Color Scheme
```
Blue (Total)         → #3B82F6
Yellow (Menunggu)    → #F59E0B
Green (Disetujui)    → #10B981
Red (Ditolak)        → #EF4444
Orange (Perbaiki)    → #F97316
Indigo (Survey)      → #6366F1 ← NEW!
```

### Card Layout
```
┌─────────────────────────────────┐
│  ┌────┐                         │
│  │ 📋 │  Survey Dijawab         │
│  └────┘  15                     │
│                                 │
└─────────────────────────────────┘
```

### Responsive Layout

**Mobile (1 column):**
```
┌──────────┐
│ Total    │
├──────────┤
│ Menunggu │
├──────────┤
│ Disetujui│
├──────────┤
│ Ditolak  │
├──────────┤
│ Perbaiki │
├──────────┤
│ Survey   │
└──────────┘
```

**Desktop XL (6 columns):**
```
┌───────┬───────┬────────┬────────┬────────┬────────┐
│ Total │Menungg│Disetuju│Ditolak │Perbaiki│ Survey │
└───────┴───────┴────────┴────────┴────────┴────────┘
```

---

## 5. Use Cases

### Use Case 1: Monitoring Survey Participation
**Goal:** Admin/Panitia ingin tahu berapa pendaftar yang sudah mengisi survey.

**Flow:**
1. Buka `/admin/tes-psikologi?mainTab=data`
2. Lihat stats card "Survey Dijawab"
3. Bandingkan dengan "Total Pendaftar"
4. Hitung persentase: (Survey Dijawab / Total) × 100%

**Example:**
- Total: 50 pendaftar
- Survey Dijawab: 35
- Persentase: 70% participation rate

### Use Case 2: Identifying Non-Respondents
**Goal:** Mengetahui siapa yang belum mengisi survey.

**Flow:**
1. Lihat kolom "Status Survey" di table
2. Filter pendaftar dengan badge "Belum Dijawab"
3. Kirim reminder via Kirim Pesan

### Use Case 3: Survey Completion Analysis
**Goal:** Analisis tingkat partisipasi survey berdasarkan status verifikasi.

**Metrics:**
- Total: 50
- Disetujui: 30 (berapa yang sudah isi survey?)
- Survey Dijawab: 35 (dari status mana saja?)

---

## 6. Testing Checklist

### Admin Dashboard
- [ ] Buka `/admin/tes-psikologi?mainTab=data`
- [ ] Stats card "Survey Dijawab" muncul di posisi ke-6
- [ ] Grid responsive di mobile (1 column)
- [ ] Grid responsive di tablet (2 columns)
- [ ] Grid responsive di laptop (3 columns)
- [ ] Grid responsive di desktop XL (6 columns)
- [ ] Angka survey_dijawab sesuai dengan data di tabel
- [ ] Icon clipboard dengan checkmark muncul
- [ ] Warna indigo diterapkan dengan benar

### Panitia Dashboard
- [ ] Buka `/panitia-pendaftaran/tes-psikologi`
- [ ] Stats card "Survey Dijawab" muncul
- [ ] Grid responsive sama dengan admin
- [ ] Angka sama dengan yang di admin dashboard

### Data Accuracy
- [ ] Test dengan 0 survey dijawab → Angka = 0
- [ ] Test dengan 1 pendaftar menjawab 1 pertanyaan → Angka = 1
- [ ] Test dengan 1 pendaftar menjawab 5 pertanyaan → Angka = 1 (tidak dobel)
- [ ] Test dengan 3 pendaftar menjawab → Angka = 3
- [ ] Test survey umum (context='pendaftar') tidak ikut terhitung

### Performance
- [ ] Query tidak lambat (< 100ms)
- [ ] Tidak ada N+1 query problem
- [ ] Cache berfungsi jika ada

---

## 7. Database Impact

### Query Complexity: LOW
- Single query dengan JOIN
- Menggunakan index pada `pendaftar_id` dan `survey_question_id`
- COUNT dengan DISTINCT efisien

### Index Recommendations
```sql
-- survey_answers table
CREATE INDEX idx_survey_answers_pendaftar_id ON survey_answers(pendaftar_id);
CREATE INDEX idx_survey_answers_question_id ON survey_answers(survey_question_id);

-- survey_questions table
CREATE INDEX idx_survey_questions_context ON survey_questions(context);
```

---

## 8. Future Enhancements

### Phase 2: Survey Analytics
1. **Percentage Display**
   ```blade
   <p class="text-sm text-gray-500">70% participation</p>
   ```

2. **Clickable Card**
   - Click card → Filter table hanya yang sudah/belum isi survey

3. **Survey Completion Rate by Status**
   ```
   Disetujui: 90% completed survey
   Menunggu: 50% completed survey
   Ditolak: 30% completed survey
   ```

4. **Progress Bar**
   ```blade
   <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
       <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ ($stats['survey_dijawab'] / $stats['total']) * 100 }}%"></div>
   </div>
   ```

5. **Time-based Tracking**
   - Survey completed today/this week/this month

---

## 9. Troubleshooting

### Issue 1: Angka Survey Dijawab = 0 (Padahal Ada Data)
**Penyebab:**
- Survey question context bukan 'tes_psikologi'
- Survey answers belum ada relasi ke questions

**Fix:**
```bash
php check_survey_aktif.php
```

**Manual Check:**
```sql
SELECT COUNT(DISTINCT sa.pendaftar_id)
FROM survey_answers sa
JOIN survey_questions sq ON sa.survey_question_id = sq.id
WHERE sq.context = 'tes_psikologi';
```

### Issue 2: Angka Survey Dijawab > Total Pendaftar
**Penyebab:**
- Data integrity issue (pendaftar_id tidak valid)
- Foreign key constraint tidak aktif

**Fix:**
```sql
-- Cek invalid data
SELECT sa.pendaftar_id, COUNT(*)
FROM survey_answers sa
LEFT JOIN pendaftars p ON sa.pendaftar_id = p.id
WHERE p.id IS NULL
GROUP BY sa.pendaftar_id;
```

### Issue 3: Query Lambat
**Penyebab:**
- Missing index
- Too many rows

**Fix:**
```sql
-- Add indexes
CREATE INDEX idx_survey_answers_pendaftar_id ON survey_answers(pendaftar_id);
CREATE INDEX idx_survey_questions_context ON survey_questions(context);

-- Analyze query
EXPLAIN SELECT COUNT(DISTINCT sa.pendaftar_id)
FROM survey_answers sa
JOIN survey_questions sq ON sa.survey_question_id = sq.id
WHERE sq.context = 'tes_psikologi';
```

---

## 10. Documentation Links

### Related Features
- [FIX_SEARCH_AND_SURVEY_STATUS_COMPLETE.md](FIX_SEARCH_AND_SURVEY_STATUS_COMPLETE.md) - Search & kolom status survey
- [FITUR_SURVEY_TES_PSIKOLOGI_DOKUMENTASI.md](FITUR_SURVEY_TES_PSIKOLOGI_DOKUMENTASI.md) - Survey system overview

### Model References
- `App\Models\SurveyAnswer` - Survey answers model
- `App\Models\SurveyQuestion` - Survey questions dengan scope tesPsikologi()
- `App\Models\TesPsikologiPendaftar` - Tes psikologi pendaftar

---

## Status: ✅ COMPLETE

Semua fitur telah diimplementasikan dengan sukses:
- ✅ Stats card "Survey Dijawab" di admin
- ✅ Stats card "Survey Dijawab" di panitia
- ✅ Query dengan distinct untuk count unique pendaftar
- ✅ Filter hanya survey tes psikologi (scope)
- ✅ Responsive grid (1-2-3-6 columns)
- ✅ Consistent UI dengan cards lainnya
- ✅ Icon dan color scheme indigo
- ✅ Dokumentasi lengkap

**Ready for testing! 🚀**

## URL Akses
- Admin: `/admin/tes-psikologi?mainTab=data`
- Panitia: `/panitia-pendaftaran/tes-psikologi`
