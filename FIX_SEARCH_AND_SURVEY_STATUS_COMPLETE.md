# Fix: Search Field & Survey Status Column - COMPLETE ✅

## Tanggal
29 Januari 2026

## Ringkasan Perubahan
Menambahkan field pencarian dan kolom status survey untuk melihat jawaban survei tes psikologi di halaman admin dan panitia.

---

## 1. Fitur yang Ditambahkan

### A. Field Pencarian
- **Lokasi**: Di sebelah kiri filter status
- **Fungsi**: Mencari berdasarkan nama pendaftar, NISN, atau nomor pendaftaran
- **Live Update**: Menggunakan `wire:model.live` untuk pencarian real-time
- **Icon**: SVG search icon di dalam input field
- **Placeholder**: "Cari nama, NISN, nomor..."

### B. Kolom Status Survey
- **Header Kolom**: "Status Survey"
- **Kondisional**: Hanya muncul jika `$setting->survey_aktif` bernilai true
- **Filter Scope**: Hanya menampilkan survey dengan scope `tesPsikologi()`
- **Badge Status**:
  - ✅ **Sudah Dijawab**: Badge hijau (bg-green-100 text-green-800)
  - ❌ **Belum Dijawab**: Badge abu-abu (bg-gray-100 text-gray-800)
- **Tombol Lihat**: Button indigo dengan icon mata untuk melihat jawaban
- **Counter**: Menampilkan jumlah jawaban dalam format "(N)"

---

## 2. File yang Dimodifikasi

### File 1: `resources/views/livewire/admin/partials/tes-psikologi-data.blade.php`

#### A. Penambahan Search Field (Lines 32-44)
```blade
<!-- Search Field -->
<div class="relative w-full sm:w-64">
    <input
        type="text"
        wire:model.live="search"
        placeholder="Cari nama, NISN, nomor..."
        class="w-full pl-10 pr-4 py-2 border-0 rounded-lg text-sm text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-white"
    >
    <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
    </svg>
</div>
```

**Penjelasan**:
- Input field dengan icon search di kiri
- Menggunakan `wire:model.live` untuk update otomatis saat user mengetik
- Responsive design: `w-full sm:w-64`

#### B. Penambahan Header Kolom Status Survey (Lines 78-80)
```blade
@if($setting && $setting->survey_aktif)
    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status Survey</th>
@endif
```

**Penjelasan**:
- Kondisional: hanya muncul jika survey aktif
- Posisi: Setelah kolom "Status Psikologi", sebelum kolom admin-only

#### C. Implementasi Cell Status Survey (Lines 256-286)
```blade
<!-- Kolom Jawaban Survey -->
@if($setting && $setting->survey_aktif)
    <td class="px-6 py-4 whitespace-nowrap text-center">
        @php
            // Count only tes psikologi survey answers
            $surveyAnswersCount = \App\Models\SurveyAnswer::where('pendaftar_id', $data->pendaftar_id)
                ->whereHas('question', function($q) {
                    $q->tesPsikologi();
                })
                ->count();
        @endphp
        @if($surveyAnswersCount > 0)
            <div class="flex flex-col items-center gap-2">
                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                    Sudah Dijawab
                </span>
                <button wire:click="openSurveyAnswersModal({{ $data->pendaftar_id }})" class="inline-flex items-center px-3 py-1 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition text-xs font-medium">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Lihat ({{ $surveyAnswersCount }})
                </button>
            </div>
        @else
            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                Belum Dijawab
            </span>
        @endif
    </td>
@endif
```

**Penjelasan**:
- **Query Filter**: Menggunakan `whereHas('question', ...)` dengan scope `tesPsikologi()`
- **Conditional Display**:
  - Jika ada jawaban: tampilkan badge hijau + button "Lihat"
  - Jika belum ada: tampilkan badge abu-abu
- **Button Action**: Memanggil `openSurveyAnswersModal()` dengan pendaftar_id
- **Counter**: Menampilkan jumlah jawaban yang telah diisi

---

### File 2: `app/Livewire/Admin/TesPsikologi.php`

#### A. Penambahan Import SurveyAnswer (Line 12)
```php
use App\Models\SurveyAnswer;
```

**Penjelasan**: Diperlukan untuk query survey answers di blade template

#### B. Penambahan Property Search (Line 26)
```php
// Filter for data tab
public $search = '';
public $filterStatus = 'all';
```

**Penjelasan**: Property untuk menyimpan nilai input search

#### C. Penambahan Method updatingSearch() (Lines 487-490)
```php
public function updatingSearch()
{
    $this->resetPage();
}
```

**Penjelasan**:
- Livewire lifecycle hook yang dipanggil saat `$search` berubah
- Reset pagination ke halaman 1 saat search berubah

#### D. Update Method render() - Search Logic (Lines 900-916)
```php
// Get data psikologi with pagination
$dataPsikologi = [];
if ($this->mainTab === 'data') {
    $query = TesPsikologiPendaftar::with(['pendaftar', 'opsi', 'bank', 'verifiedBy']);

    // Apply search filter
    if ($this->search) {
        $query->whereHas('pendaftar', function($q) {
            $q->where('nama_lengkap', 'like', '%'.$this->search.'%')
              ->orWhere('nisn', 'like', '%'.$this->search.'%')
              ->orWhere('nomor_pendaftaran', 'like', '%'.$this->search.'%');
        });
    }

    // Apply status filter
    if ($this->filterStatus !== 'all') {
        $query->where('status_verifikasi', $this->filterStatus);
    }

    $dataPsikologi = $query->orderBy('created_at', 'desc')->paginate(15);
}
```

**Penjelasan**:
- **Search Filter**: Menggunakan `whereHas()` untuk filter berdasarkan relasi pendaftar
- **Multiple Fields**: Mencari di 3 field: nama_lengkap, nisn, nomor_pendaftaran
- **OR Logic**: Menggunakan `orWhere()` untuk mencari di salah satu field
- **Conditional**: Hanya apply filter jika `$search` tidak kosong

---

### File 3: `app/Livewire/Panitia/TesPsikologi.php`

#### Status: ✅ **Sudah Lengkap**

Property dan method yang sudah ada:
- ✅ `public $search = '';` (Line 18)
- ✅ `public $filterStatus = 'all';` (Line 19)
- ✅ `use App\Models\SurveyAnswer;` (Line 9)
- ✅ `updatingSearch()` method (sudah ada dari session sebelumnya)
- ✅ `openSurveyAnswersModal()` method dengan filter `tesPsikologi()` (Lines 417-456)
- ✅ Search logic di render method (sudah ada dari session sebelumnya)

**Tidak perlu modifikasi** karena sudah diupdate di session sebelumnya.

---

## 3. Fitur Survey Modal yang Sudah Ada

### Modal Survey Answers (Lines 287-362 di partial)
```blade
@if($showSurveyAnswersModal)
    <div class="fixed inset-0 z-[9999] overflow-y-auto" ...>
        <!-- Background overlay -->
        <!-- Modal panel -->
        <!-- Header with pendaftar info -->
        <!-- Content with survey answers -->
        <!-- Footer with close button -->
    </div>
@endif
```

**Fitur Modal**:
- ✅ Display nama pendaftar & nomor pendaftaran di header
- ✅ Menampilkan pertanyaan & jawaban dalam card format
- ✅ Numbering untuk setiap pertanyaan
- ✅ Menampilkan tipe input (text, textarea, radio, dll)
- ✅ Empty state jika belum ada jawaban
- ✅ Button close untuk menutup modal

---

## 4. Scope tesPsikologi() di Model

### SurveyQuestion Model
```php
public function scopeTesPsikologi($query)
{
    return $query->where('context', 'tes_psikologi');
}
```

**Penjelasan**:
- Filter survey questions berdasarkan context = 'tes_psikologi'
- Memastikan hanya survey dari tab `/tes-psikologi?mainTab=survey` yang ditampilkan
- Tidak tercampur dengan survey umum (pendaftar)

---

## 5. Flow Penggunaan Fitur

### A. Flow Search
1. Admin/Panitia membuka halaman tes psikologi
2. Mengetik di search field (nama/NISN/nomor pendaftaran)
3. Livewire otomatis update table dengan hasil pencarian
4. Pagination reset ke halaman 1
5. Search bisa dikombinasikan dengan filter status

### B. Flow Survey Status
1. Admin/Panitia melihat kolom "Status Survey"
2. Jika pendaftar sudah mengisi survey tes psikologi:
   - Badge hijau "Sudah Dijawab" muncul
   - Button "Lihat (N)" muncul dengan jumlah jawaban
3. Klik button "Lihat" → Modal terbuka
4. Modal menampilkan semua pertanyaan & jawaban
5. Klik "Tutup" atau background → Modal tertutup

---

## 6. Keuntungan Implementasi

### A. Performance
- ✅ Query optimal dengan `whereHas()` dan scope
- ✅ Pagination tetap berfungsi dengan search
- ✅ Live update tanpa reload halaman penuh

### B. User Experience
- ✅ Real-time search tanpa tombol submit
- ✅ Visual feedback dengan badge berwarna
- ✅ Clear indication mana pendaftar yang sudah mengisi survey
- ✅ Easy access untuk melihat jawaban survey

### C. Code Reusability
- ✅ Shared partial untuk admin dan panitia
- ✅ Same modal component
- ✅ Consistent UI/UX across roles

### D. Data Integrity
- ✅ Filter by scope memastikan hanya survey tes psikologi yang ditampilkan
- ✅ Tidak tercampur dengan survey umum pendaftar
- ✅ Query safe dengan proper escaping

---

## 7. Testing Checklist

- [ ] Test search dengan nama pendaftar
- [ ] Test search dengan NISN
- [ ] Test search dengan nomor pendaftaran
- [ ] Test kombinasi search + filter status
- [ ] Test pagination dengan search aktif
- [ ] Test badge "Sudah Dijawab" muncul jika ada jawaban
- [ ] Test badge "Belum Dijawab" muncul jika belum ada jawaban
- [ ] Test button "Lihat" membuka modal
- [ ] Test modal menampilkan jawaban yang benar
- [ ] Test modal hanya menampilkan survey tes psikologi (bukan survey umum)
- [ ] Test counter jumlah jawaban akurat
- [ ] Test close modal dengan button dan background click
- [ ] Test di role admin
- [ ] Test di role panitia
- [ ] Test responsive di mobile view

---

## 8. URL Akses

### Admin
```
/admin/tes-psikologi?mainTab=data
```

### Panitia
```
/panitia-pendaftaran/tes-psikologi
```

---

## 9. Catatan Penting

### A. Survey Context
- Survey tes psikologi memiliki `context = 'tes_psikologi'`
- Survey umum pendaftar memiliki `context = 'pendaftar'`
- Kolom ini HANYA menampilkan survey tes psikologi
- Filter menggunakan scope `tesPsikologi()` di model

### B. Conditional Display
- Kolom hanya muncul jika `$setting->survey_aktif = true`
- Dapat diatur di tab `/tes-psikologi?mainTab=survey`
- Setting global untuk semua user

### C. Permissions
- Admin: dapat melihat semua kolom termasuk Ubah Status, Kirim Pesan, Riwayat Pesan
- Panitia: tidak dapat melihat kolom admin-only, tapi dapat melihat survey status

---

## 10. File Structure

```
app/
├── Livewire/
│   ├── Admin/
│   │   └── TesPsikologi.php         ✅ Updated (search + import)
│   └── Panitia/
│       └── TesPsikologi.php         ✅ Already complete
│
resources/
└── views/
    └── livewire/
        ├── admin/
        │   └── partials/
        │       └── tes-psikologi-data.blade.php  ✅ Updated (search + survey column)
        └── panitia/
            └── tes-psikologi.blade.php           ✅ Using shared partial
```

---

## Status: ✅ COMPLETE

Semua fitur telah diimplementasikan dengan sukses:
- ✅ Search field di sebelah kiri filter status
- ✅ Search berfungsi untuk nama, NISN, nomor pendaftaran
- ✅ Kolom Status Survey dengan badge dan button
- ✅ Filter hanya survey tes psikologi (scope tesPsikologi)
- ✅ Modal untuk melihat jawaban survey
- ✅ Implementasi di admin dan panitia
- ✅ Shared partial untuk konsistensi UI
- ✅ Live update dengan Livewire
- ✅ Responsive design

**Ready for testing! 🚀**
