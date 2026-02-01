# Fitur Tab Survey untuk Panitia Tes Psikologi

## Ringkasan Perubahan
Halaman `/panitia-psikologi/tes-psikologi` telah ditingkatkan dengan menambahkan **Tab Navigation** dan **Tab Survey (View-Only)** khusus untuk role `panitia_tes_psikologi`. Panitia lain (pendaftaran, daftar ulang) tetap melihat tampilan lama tanpa tab navigation.

## 🎯 Tujuan
1. **Panitia Tes Psikologi** dapat melihat pertanyaan survey yang aktif untuk tes psikologi
2. Memiliki akses view-only untuk melihat konfigurasi survey
3. Tampilan lengkap seperti admin tetapi **tanpa aksi edit/delete/tambah**
4. Role panitia lain tidak terpengaruh (backward compatible)

## ✨ Fitur Baru untuk Panitia Tes Psikologi

### 1. Tab Navigation
- **Tab Data**: Tabel data tes psikologi (existing)
- **Tab Survey**: Daftar pertanyaan survey (NEW - view only)

### 2. Tab Survey Content
- Status survey (Aktif/Nonaktif)
- Informasi untuk pendaftar
- Daftar pertanyaan dengan detail lengkap:
  - Pertanyaan dan deskripsi
  - Tipe input (text, textarea, radio, checkbox, select)
  - Status wajib diisi
  - Opsi jawaban (untuk radio/checkbox/select)
  - Urutan pertanyaan
- Statistik survey (total, aktif, wajib)

## 📂 File yang Dimodifikasi

### 1. Backend: `app/Livewire/Panitia/TesPsikologi.php`

#### Property Baru
```php
public $isPanitiaPsikologi = false; // Flag untuk panitia tes psikologi
```

#### Modifikasi mount()
```php
public function mount()
{
    // Detect panitia tes psikologi role
    $user = \Illuminate\Support\Facades\Auth::user();
    $this->isPanitiaPsikologi = ($user->role === 'panitia_tes_psikologi');

    $this->loadStats();

    // Load survey hanya untuk panitia_tes_psikologi
    if ($this->isPanitiaPsikologi) {
        $this->loadSurvey();
    }
}
```

#### Method Baru: loadSurvey()
```php
public function loadSurvey()
{
    // Load survey questions dengan context 'tes_psikologi' (view-only untuk panitia)
    $this->surveyQuestions = SurveyQuestion::tesPsikologi()
        ->with('options')
        ->aktif()
        ->ordered()
        ->get();
}
```

#### Modifikasi render()
```php
public function render()
{
    // Conditionally load data based on mainTab
    $dataPsikologi = collect();
    if ($this->mainTab === 'data') {
        // Load data psikologi query...
        $dataPsikologi = $query->latest()->paginate(20);
    }

    $user = \Illuminate\Support\Facades\Auth::user();
    $setting = TesPsikologiSetting::getSetting();

    return view('livewire.panitia.tes-psikologi', [
        'dataPsikologi' => $dataPsikologi,
        'setting' => $setting,
        'surveyQuestions' => $this->surveyQuestions,
        'isPanitiaPsikologi' => $this->isPanitiaPsikologi
    ])->layout('components.layouts.dashboard', [
        'role' => $user->role,
        'pageTitle' => 'Data Tes Psikologi',
        'pageDescription' => 'Verifikasi bukti transfer tes psikologi pendaftar'
    ]);
}
```

### 2. Frontend: `resources/views/livewire/panitia/tes-psikologi.blade.php`

#### Conditional Tab Navigation
```blade
@if($isPanitiaPsikologi)
    <!-- Tab Navigation - HANYA untuk Panitia Tes Psikologi -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <a href="?mainTab=data" wire:navigate class="...">
                    Data Psikologi
                </a>
                <a href="?mainTab=survey" wire:navigate class="...">
                    Pertanyaan Survey
                </a>
            </nav>
        </div>
    </div>

    <!-- Tab Content untuk Panitia Tes Psikologi -->
    @if($mainTab === 'data')
        @include('livewire.admin.partials.tes-psikologi-data')
    @elseif($mainTab === 'survey')
        @include('livewire.panitia.partials.tes-psikologi-survey')
    @endif
@else
    <!-- Panitia Lain - Tampilan Lama Tanpa Tab -->
    @include('livewire.admin.partials.tes-psikologi-data')
@endif
```

### 3. New Partial: `resources/views/livewire/panitia/partials/tes-psikologi-survey.blade.php`

File baru yang berisi tampilan view-only untuk survey questions. Fitur:
- Info box "Mode Hanya Lihat"
- Status survey (Aktif/Nonaktif)
- Informasi untuk pendaftar
- Daftar pertanyaan dengan detail lengkap
- Statistik ringkasan

**TIDAK ADA** aksi edit/delete/tambah - pure view only!

## 🔍 Perbedaan Tampilan

### Untuk `panitia_tes_psikologi` di `/panitia-psikologi/tes-psikologi`

```
┌──────────────────────────────────────────────────────────┐
│  📊 Statistics Cards (6 cards)                           │
├──────────────────────────────────────────────────────────┤
│                                                          │
│  [Data Psikologi]  [Pertanyaan Survey]  ← TAB NAV       │
│                                                          │
├──────────────────────────────────────────────────────────┤
│                                                          │
│  Tab Data: Data Table dengan filter & actions           │
│  Tab Survey: View pertanyaan survey (READ ONLY)         │
│                                                          │
└──────────────────────────────────────────────────────────┘
```

### Untuk `panitia_pendaftaran` atau `panitia_daftarulang`

```
┌──────────────────────────────────────────────────────────┐
│  📊 Statistics Cards (6 cards)                           │
├──────────────────────────────────────────────────────────┤
│                                                          │
│  Data Table (Tidak ada tab navigation)                  │
│                                                          │
└──────────────────────────────────────────────────────────┘
```

## 📊 Role-Based Access Matrix

| Role | URL | Tab Navigation | Tab Data | Tab Survey |
|------|-----|----------------|----------|------------|
| **panitia_tes_psikologi** | `/panitia-psikologi/tes-psikologi` | ✅ YES | ✅ YES (Full Access) | ✅ YES (View Only) |
| **panitia_pendaftaran** | `/panitia-pendaftaran/tes-psikologi` | ❌ NO | ✅ YES (Full Access) | ❌ NO |
| **panitia_daftarulang** | `/panitia-daftarulang/tes-psikologi` | ❌ NO | ✅ YES (Full Access) | ❌ NO |
| **admin** | `/admin/tes-psikologi` | ✅ YES | ✅ YES (Full Access) | ✅ YES (Full CRUD) |

## ✅ Benefit Perubahan

### 1. Akses Informasi Survey
- ✅ Panitia tes psikologi bisa melihat pertanyaan yang akan dijawab pendaftar
- ✅ Memahami konteks survey untuk memberikan support yang lebih baik
- ✅ Validasi bahwa survey sudah dikonfigurasi dengan benar

### 2. Konsistensi UI dengan Admin
- ✅ Interface mirip dengan admin (tab navigation)
- ✅ Informasi lengkap tentang survey
- ✅ Statistik yang membantu monitoring

### 3. Security & Permission
- ✅ View-only access - tidak bisa edit/delete/tambah
- ✅ Role-based rendering - panitia lain tidak terpengaruh
- ✅ Backward compatible - tidak ada breaking changes

### 4. User Experience
- ✅ Navigasi intuitif dengan tab
- ✅ URL state persistent dengan `?mainTab=data` atau `?mainTab=survey`
- ✅ Tidak perlu bolak-balik ke halaman admin untuk cek survey

## 🧪 Testing Checklist

### Test 1: Sebagai Panitia Tes Psikologi
1. Login dengan role `panitia_tes_psikologi`
2. Akses `/panitia-psikologi/tes-psikologi`
3. Verifikasi:
   - ✅ Tab navigation muncul (Data & Survey)
   - ✅ Default tab = Data
   - ✅ Tabel data psikologi tampil dengan filter & actions
   - ✅ Klik tab Survey
   - ✅ Info box "Mode Hanya Lihat" muncul
   - ✅ Status survey tampil (Aktif/Nonaktif)
   - ✅ Daftar pertanyaan tampil dengan detail
   - ✅ Opsi jawaban tampil untuk radio/checkbox/select
   - ✅ Statistik survey tampil (total, aktif, wajib)
   - ✅ TIDAK ADA tombol edit/delete/tambah
   - ✅ URL berubah ke `?mainTab=survey`

### Test 2: URL Query Parameter
1. Sebagai panitia tes psikologi
2. Akses langsung: `/panitia-psikologi/tes-psikologi?mainTab=survey`
3. Verifikasi:
   - ✅ Langsung menampilkan tab survey
   - ✅ Tab survey aktif (border purple)

### Test 3: Sebagai Panitia Pendaftaran
1. Login dengan role `panitia_pendaftaran`
2. Akses `/panitia-pendaftaran/tes-psikologi`
3. Verifikasi:
   - ✅ Tab navigation TIDAK muncul
   - ✅ Langsung menampilkan data table
   - ✅ Tampilan sama seperti sebelumnya (tidak ada perubahan)

### Test 4: Sebagai Panitia Daftar Ulang
1. Login dengan role `panitia_daftarulang`
2. Akses `/panitia-daftarulang/tes-psikologi`
3. Verifikasi:
   - ✅ Tab navigation TIDAK muncul
   - ✅ Langsung menampilkan data table
   - ✅ Tampilan sama seperti sebelumnya (tidak ada perubahan)

### Test 5: Survey Empty State
1. Sebagai panitia tes psikologi
2. Pastikan belum ada survey (atau disable semua)
3. Akses tab survey
4. Verifikasi:
   - ✅ Empty state message muncul
   - ✅ Icon dan teks informatif
   - ✅ Tidak ada error

### Test 6: Survey dengan Options
1. Sebagai admin, buat pertanyaan dengan tipe radio/checkbox
2. Tambahkan beberapa opsi jawaban
3. Sebagai panitia tes psikologi, akses tab survey
4. Verifikasi:
   - ✅ Opsi jawaban tampil dalam box terpisah
   - ✅ Urutan opsi sesuai dengan urutan yang diset
   - ✅ Label opsi tampil dengan benar

## 🔒 Security Notes

### Role Checking
- Role detection dilakukan di `mount()` method
- Property `$isPanitiaPsikologi` digunakan untuk conditional rendering
- Survey hanya di-load untuk panitia_tes_psikologi

### View-Only Access
- Tidak ada wire:click untuk edit/delete/tambah
- Tidak ada form input untuk modifikasi
- Pure display/read-only component

### URL Manipulation
- URL parameter `mainTab` di-handle oleh Livewire `#[Url]` attribute
- Meskipun panitia lain ubah URL ke `?mainTab=survey`, tetap tidak akan tampil karena conditional di view

## 📝 Data Consistency

- Data survey yang ditampilkan sama dengan yang di admin
- Filter: `context = 'tes_psikologi'` memastikan hanya survey tes psikologi
- Urutan pertanyaan sesuai dengan field `urutan`
- Status aktif/nonaktif ditampilkan dengan jelas

## 🚀 Backward Compatibility

✅ **Tidak ada breaking changes:**
- Panitia pendaftaran & daftar ulang tetap melihat tampilan lama
- Admin tetap memiliki full CRUD access
- URL routing tidak berubah
- Database tidak ada perubahan
- Model relationships tetap sama

## 📌 Catatan Penting

### 1. Context Survey
Survey memiliki 2 context:
- `umum`: Survey untuk pendaftaran umum
- `tes_psikologi`: Survey khusus tes psikologi

Filter `SurveyQuestion::tesPsikologi()` memastikan hanya menampilkan survey tes psikologi.

### 2. loadSurvey() Method
Method `loadSurvey()` di component panitia tidak melakukan CRUD, hanya load data untuk display.

### 3. View-Only Philosophy
Panitia tes psikologi adalah role operasional yang fokus pada verifikasi bukti transfer. Mereka perlu melihat survey untuk konteks, tetapi tidak perlu mengubahnya.

### 4. Performance
- Survey hanya di-load saat `isPanitiaPsikologi = true`
- Data table hanya di-load saat `mainTab = 'data'`
- Conditional loading meningkatkan performance

## 🐛 Troubleshooting

### Q: Panitia tes psikologi tidak melihat tab navigation?
**A:**
1. Clear browser cache
2. Clear Laravel cache: `php artisan view:clear`
3. Pastikan role di database = `panitia_tes_psikologi` (bukan `panitia_pendaftaran`)
4. Check console browser untuk JavaScript errors

### Q: Tab survey kosong meskipun sudah ada pertanyaan?
**A:**
1. Pastikan pertanyaan memiliki `context = 'tes_psikologi'`
2. Pastikan pertanyaan `aktif = true`
3. Check method `SurveyQuestion::tesPsikologi()` di model
4. Verify di database: `SELECT * FROM survey_questions WHERE context = 'tes_psikologi'`

### Q: Panitia lain melihat tab navigation?
**A:**
1. Shouldn't happen jika role checking correct
2. Check `$isPanitiaPsikologi` flag di component
3. Verify conditional `@if($isPanitiaPsikologi)` di view
4. Check session/auth - pastikan user logged in dengan role yang benar

### Q: Error "surveyQuestions undefined"?
**A:**
1. Pastikan property `public $surveyQuestions = []` ada di component
2. Pastikan `loadSurvey()` dipanggil di mount()
3. Pastikan variable di-pass ke view di render()
4. Check `with('options')` relationship di load

## 📦 Dependencies

- Laravel 12.47.0
- Livewire v4.0.1
- PostgreSQL
- SurveyQuestion Model dengan scope `tesPsikologi()`
- TesPsikologiSetting Model

## 🔗 Related Documentation

- `FITUR_SURVEY_TES_PSIKOLOGI_SUMMARY.md` - Sistem survey tes psikologi
- `PANITIA_PSIKOLOGI_LAPORAN_SIMPLIFIKASI.md` - Simplifikasi halaman laporan
- `FITUR_ROLE_PANITIA_TES_PSIKOLOGI_COMPLETE.md` - Role panitia tes psikologi

---

**Dokumentasi:** 29 Januari 2026
**Author:** Claude Code
**Version:** 1.0.0
**Status:** ✅ Complete
**Tested:** ⏳ Pending Manual Test
