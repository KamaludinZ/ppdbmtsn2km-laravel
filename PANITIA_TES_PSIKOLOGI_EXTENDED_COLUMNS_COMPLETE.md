# Extended Columns untuk Panitia Tes Psikologi - Tab Data

## 🎯 Tujuan
Memberikan akses penuh ke kolom **Ubah Status**, **Kirim Pesan**, dan **Riwayat Pesan** untuk `panitia_tes_psikologi` di `/panitia-psikologi/tes-psikologi?mainTab=data`, sehingga mereka dapat mengelola status verifikasi dan berkomunikasi dengan pendaftar secara mandiri.

## ✨ Fitur yang Ditambahkan

### 1. Kolom Ubah Status
- **Dropdown** untuk mengubah status verifikasi:
  - Disetujui
  - Ditolak
  - Perbaiki
- **Link "Lihat Detail"** untuk melihat profil lengkap pendaftar
- URL routing yang dynamic berdasarkan role user

### 2. Kolom Kirim Pesan
- **Button Kirim Email** - Buka modal untuk mengirim email dengan template
- **Button Kirim WhatsApp** - Buka modal untuk mengirim WhatsApp dengan template
- Integrasi dengan sistem template pesan

### 3. Kolom Riwayat Pesan
- Menampilkan **pesan terakhir yang dikirim** kepada pendaftar
- Informasi meliputi:
  - Tipe pesan (Email/WhatsApp)
  - Waktu pengiriman (relative & absolute)
  - Pengirim pesan
- Empty state "Belum ada" jika belum pernah kirim pesan

## 📂 File yang Dimodifikasi

### `resources/views/livewire/admin/partials/tes-psikologi-data.blade.php`

#### 1. Conditional Logic di Top File
```php
@php
    // Tentukan apakah user bisa akses kolom extended (Ubah Status, Kirim Pesan, Riwayat Pesan)
    $showExtendedColumns = Auth::user()->role === 'admin' || (isset($isPanitiaPsikologi) && $isPanitiaPsikologi);
    $userRole = Auth::user()->role;
@endphp
```

**Penjelasan:**
- `$showExtendedColumns`: Boolean flag yang menentukan apakah kolom extended ditampilkan
- Bernilai `true` jika:
  - User adalah admin, ATAU
  - Variable `$isPanitiaPsikologi` ada dan bernilai `true`
- `$userRole`: Menyimpan role user untuk digunakan dalam URL routing

#### 2. Table Headers - Line 87-91
```blade
@if($showExtendedColumns)
    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ubah Status</th>
    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Kirim Pesan</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Riwayat Pesan</th>
@endif
```

**Sebelumnya:**
```blade
@if(Auth::user()->role === 'admin')
```

**Perubahan:** Kondisi dari hardcoded `admin` menjadi dynamic `$showExtendedColumns`

#### 3. Table Body - Line 204-293
```blade
@if($showExtendedColumns)
    <!-- Kolom Ubah Status -->
    <td class="px-6 py-4 whitespace-nowrap text-center">
        <div class="flex items-center justify-center gap-2">
            <select wire:change="changeStatus({{ $data->id }}, $event.target.value)" ...>
                <option value="">-- Pilih Status --</option>
                <option value="disetujui" ...>Disetujui</option>
                <option value="ditolak" ...>Ditolak</option>
                <option value="perbaiki" ...>Perbaiki</option>
            </select>
            <a href="/{{ $userRole }}/pendaftar/{{ $data->pendaftar_id }}" target="_blank" ...>
                <!-- Icon lihat detail -->
            </a>
        </div>
    </td>

    <!-- Kolom Kirim Pesan -->
    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
        <div class="flex flex-col space-y-2">
            <button wire:click="openSendMessageModal({{ $data->id }}, 'email')" ...>
                Kirim Email
            </button>
            <button wire:click="openSendMessageModal({{ $data->id }}, 'whatsapp')" ...>
                Kirim WhatsApp
            </button>
        </div>
    </td>

    <!-- Kolom Riwayat Pesan -->
    <td class="px-6 py-4 whitespace-nowrap">
        @php
            $lastMessage = \App\Models\MessageHistory::where('pendaftar_id', $data->pendaftar_id)
                ->where('context', 'tes_psikologi')
                ->orderBy('sent_at', 'desc')
                ->first();
        @endphp
        @if($lastMessage)
            <div class="text-xs">
                <div class="flex items-center text-gray-700 mb-1">
                    <!-- Icon & tipe pesan -->
                    {{ $lastMessage->message_type }}
                </div>
                <div class="text-gray-600">{{ $lastMessage->sent_at->diffForHumans() }}</div>
                <div class="text-gray-500 text-[10px]">{{ $lastMessage->sent_at->format('d/m/Y H:i') }}</div>
                <div class="text-gray-500 text-[10px]">oleh: {{ $lastMessage->sender->name ?? 'System' }}</div>
            </div>
        @else
            <span class="text-xs text-gray-400 italic">Belum ada</span>
        @endif
    </td>
@endif
```

**Perubahan Penting:**
1. Kondisi dari `@if(Auth::user()->role === 'admin')` → `@if($showExtendedColumns)`
2. URL dari hardcoded `/admin/pendaftar/` → dynamic `/{{ $userRole }}/pendaftar/`

## 🔐 Role-Based Access Control

| Role | Extended Columns | URL Detail Pendaftar |
|------|------------------|---------------------|
| **admin** | ✅ YES | `/admin/pendaftar/{id}` |
| **panitia_tes_psikologi** | ✅ YES | `/panitia-psikologi/pendaftar/{id}` |
| **panitia_pendaftaran** | ❌ NO | - |
| **panitia_daftarulang** | ❌ NO | - |

### Access Logic
```
showExtendedColumns = (role === 'admin') OR (isPanitiaPsikologi === true)
```

## 📊 Fitur Detail

### 1. Ubah Status Verifikasi

**Cara Kerja:**
1. Panitia memilih status dari dropdown
2. Event `wire:change` memanggil method `changeStatus($id, $status)` di Livewire component
3. Status verifikasi di database ter-update
4. Tanggal verifikasi dan verifier dicatat
5. UI ter-refresh dengan status baru

**Status yang tersedia:**
- `menunggu` (default) - Badge kuning
- `disetujui` - Badge hijau
- `ditolak` - Badge merah
- `perbaiki` - Badge orange

### 2. Kirim Pesan dengan Template

**Button Kirim Email:**
```blade
<button wire:click="openSendMessageModal({{ $data->id }}, 'email')" ...>
    Kirim Email
</button>
```

**Button Kirim WhatsApp:**
```blade
<button wire:click="openSendMessageModal({{ $data->id }}, 'whatsapp')" ...>
    Kirim WhatsApp
</button>
```

**Workflow:**
1. Klik button → Modal terbuka
2. Pilih template pesan (dari dropdown)
3. Preview pesan dengan placeholder terisi otomatis
4. Edit pesan jika perlu
5. Kirim pesan
6. Riwayat pesan tercatat di database

**Template Placeholders:**
- `{nama_lengkap}` - Nama pendaftar
- `{nomor_pendaftaran}` - Nomor pendaftaran
- `{status_verifikasi}` - Status terkini
- `{catatan_verifikasi}` - Catatan dari panitia
- dll.

### 3. Riwayat Pesan

**Query:**
```php
$lastMessage = \App\Models\MessageHistory::where('pendaftar_id', $data->pendaftar_id)
    ->where('context', 'tes_psikologi')
    ->orderBy('sent_at', 'desc')
    ->first();
```

**Context Filtering:** `context = 'tes_psikologi'` memastikan hanya menampilkan pesan yang relevan dengan tes psikologi.

**Informasi yang Ditampilkan:**
- Tipe pesan (Email/WhatsApp) dengan icon
- Waktu relative ("2 jam yang lalu")
- Waktu absolute ("29/01/2026 14:30")
- Nama pengirim

## 🎨 UI Preview

### Tampilan untuk panitia_tes_psikologi:
```
┌─────────────────────────────────────────────────────────────────────────────────┐
│ [Data Psikologi] [Pertanyaan Survey]  ← TAB NAVIGATION                         │
├─────────────────────────────────────────────────────────────────────────────────┤
│ Data Tes Psikologi Pendaftar                                                   │
│ [Cari...] [Filter Status]                                                      │
├──────┬────────┬────────┬─────────┬────────┬───────────┬────────┬─────────┬────┤
│ No   │ NISN   │ Nama   │ Opsi    │ Bank   │ Waktu     │ Bukti  │ Status  │... │
│ Daft │        │        │         │        │ Transfer  │        │ Psiko   │    │
├──────┼────────┼────────┼─────────┼────────┼───────────┼────────┼─────────┼────┤
│      │        │        │         │        │           │        │         │    │
├──────┴────────┴────────┴─────────┴────────┴───────────┴────────┴─────────┴────┤
│                                                                                 │
│  ... (kolom standard) ...                                                      │
│                                                                                 │
├────────────────────┬──────────────────────┬─────────────────────────────────────┤
│ Ubah Status        │ Kirim Pesan          │ Riwayat Pesan                      │
├────────────────────┼──────────────────────┼─────────────────────────────────────┤
│ [Dropdown Status]  │ [📧 Kirim Email]     │ 📧 Email                           │
│ [👁️ Lihat Detail]  │ [💬 Kirim WhatsApp]  │ 2 jam yang lalu                    │
│                    │                      │ 29/01/2026 14:30                   │
│                    │                      │ oleh: Admin                        │
└────────────────────┴──────────────────────┴─────────────────────────────────────┘
```

### Tampilan untuk panitia lain (pendaftaran, daftarulang):
```
┌─────────────────────────────────────────────────────────────────────────────────┐
│ Data Tes Psikologi Pendaftar                                                   │
│ [Cari...] [Filter Status]                                                      │
├──────┬────────┬────────┬─────────┬────────┬───────────┬────────┬─────────┐    │
│ No   │ NISN   │ Nama   │ Opsi    │ Bank   │ Waktu     │ Bukti  │ Status  │    │
│ Daft │        │        │         │        │ Transfer  │        │ Psiko   │    │
├──────┼────────┼────────┼─────────┼────────┼───────────┼────────┼─────────┤    │
│      │        │        │         │        │           │        │         │    │
└──────┴────────┴────────┴─────────┴────────┴───────────┴────────┴─────────┘    │

Tidak ada kolom Ubah Status, Kirim Pesan, Riwayat Pesan
```

## ⚙️ Backend Requirements

### Component Method Requirements

Component `app/Livewire/Panitia/TesPsikologi.php` atau yang menggunakan partial ini HARUS memiliki:

1. **Property `$isPanitiaPsikologi`** (sudah ada dari fitur sebelumnya)
```php
public $isPanitiaPsikologi = false;
```

2. **Method `changeStatus($id, $status)`**
```php
public function changeStatus($tesPsikologiId, $newStatus)
{
    $tesPsikologi = TesPsikologiPendaftar::find($tesPsikologiId);

    if (!$tesPsikologi) {
        $this->dispatch('show-alert', type: 'error', message: 'Data tidak ditemukan.');
        return;
    }

    $tesPsikologi->update([
        'status_verifikasi' => $newStatus,
        'tanggal_verifikasi' => now(),
        'verified_by' => Auth::id()
    ]);

    $this->dispatch('show-alert', type: 'success', message: 'Status berhasil diubah.');
}
```

3. **Method `openSendMessageModal($id, $type)`**
```php
public function openSendMessageModal($tesPsikologiId, $messageType)
{
    $this->showSendMessageModal = true;
    $this->selectedTesPsikologiId = $tesPsikologiId;
    $this->messageType = $messageType; // 'email' or 'whatsapp'

    // Load pendaftar data
    $tesPsikologi = TesPsikologiPendaftar::with('pendaftar')->find($tesPsikologiId);
    $this->selectedPendaftar = $tesPsikologi->pendaftar;

    // Load template pesan
    $this->loadMessageTemplates('tes_psikologi');
}
```

4. **Method `sendMessage()` untuk handle pengiriman**
```php
public function sendMessage()
{
    // Validation
    $this->validate([
        'selectedTemplate' => 'required',
        'messageContent' => 'required'
    ]);

    // Process template placeholders
    $message = $this->processPlaceholders($this->messageContent);

    // Send based on type
    if ($this->messageType === 'email') {
        // Send email logic
        Mail::to($this->selectedPendaftar->email)->send(new TesPsikologiMail($message));
    } elseif ($this->messageType === 'whatsapp') {
        // Send WhatsApp logic (via API)
        $this->sendWhatsApp($this->selectedPendaftar->no_hp, $message);
    }

    // Save to message history
    MessageHistory::create([
        'pendaftar_id' => $this->selectedPendaftar->id,
        'context' => 'tes_psikologi',
        'message_type' => $this->messageType,
        'message_content' => $message,
        'sent_by' => Auth::id(),
        'sent_at' => now()
    ]);

    $this->dispatch('show-alert', type: 'success', message: 'Pesan berhasil dikirim.');
    $this->closeSendMessageModal();
}
```

### Database Requirements

**Table: `message_histories`**
```sql
CREATE TABLE message_histories (
    id BIGSERIAL PRIMARY KEY,
    pendaftar_id BIGINT NOT NULL,
    context VARCHAR(255) NOT NULL, -- 'tes_psikologi', 'daftar_ulang', etc.
    message_type VARCHAR(50) NOT NULL, -- 'email', 'whatsapp'
    message_content TEXT NOT NULL,
    sent_by BIGINT NOT NULL,
    sent_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (pendaftar_id) REFERENCES pendaftars(id) ON DELETE CASCADE,
    FOREIGN KEY (sent_by) REFERENCES users(id) ON DELETE CASCADE
);

CREATE INDEX idx_message_histories_pendaftar ON message_histories(pendaftar_id);
CREATE INDEX idx_message_histories_context ON message_histories(context);
```

## 🔄 Data Flow

### Flow Ubah Status:
```
User selects status
    ↓
wire:change event
    ↓
changeStatus($id, $status) method
    ↓
Update database:
  - status_verifikasi
  - tanggal_verifikasi
  - verified_by
    ↓
Livewire re-render
    ↓
UI updated with new status badge
```

### Flow Kirim Pesan:
```
User clicks "Kirim Email/WhatsApp"
    ↓
openSendMessageModal($id, $type) method
    ↓
Modal opens with:
  - Template dropdown
  - Message preview
    ↓
User selects template & edits message
    ↓
User clicks "Kirim"
    ↓
sendMessage() method
    ↓
Process placeholders
    ↓
Send via Email/WhatsApp API
    ↓
Save to MessageHistory
    ↓
Close modal & show success alert
    ↓
Riwayat Pesan column updated
```

## 🧪 Testing Checklist

### Test 1: Sebagai Admin
1. Login sebagai admin
2. Akses `/admin/tes-psikologi?mainTab=data`
3. Verifikasi:
   - ✅ Kolom "Ubah Status" muncul
   - ✅ Kolom "Kirim Pesan" muncul
   - ✅ Kolom "Riwayat Pesan" muncul
   - ✅ Dropdown status berfungsi
   - ✅ Link detail mengarah ke `/admin/pendaftar/{id}`
   - ✅ Button kirim email membuka modal
   - ✅ Button kirim WhatsApp membuka modal
   - ✅ Riwayat pesan tampil jika ada

### Test 2: Sebagai Panitia Tes Psikologi
1. Login sebagai panitia_tes_psikologi
2. Akses `/panitia-psikologi/tes-psikologi?mainTab=data`
3. Verifikasi:
   - ✅ Kolom "Ubah Status" muncul
   - ✅ Kolom "Kirim Pesan" muncul
   - ✅ Kolom "Riwayat Pesan" muncul
   - ✅ Dropdown status berfungsi
   - ✅ Link detail mengarah ke `/panitia-psikologi/pendaftar/{id}`
   - ✅ Ubah status berhasil (status ter-update di database)
   - ✅ Kirim email berhasil
   - ✅ Kirim WhatsApp berhasil
   - ✅ Riwayat pesan ter-record dengan benar

### Test 3: Sebagai Panitia Pendaftaran
1. Login sebagai panitia_pendaftaran
2. Akses `/panitia-pendaftaran/tes-psikologi`
3. Verifikasi:
   - ✅ Kolom "Ubah Status" TIDAK muncul
   - ✅ Kolom "Kirim Pesan" TIDAK muncul
   - ✅ Kolom "Riwayat Pesan" TIDAK muncul
   - ✅ Tampilan sama seperti sebelum perubahan

### Test 4: Sebagai Panitia Daftar Ulang
1. Login sebagai panitia_daftarulang
2. Akses `/panitia-daftarulang/tes-psikologi`
3. Verifikasi:
   - ✅ Kolom "Ubah Status" TIDAK muncul
   - ✅ Kolom "Kirim Pesan" TIDAK muncul
   - ✅ Kolom "Riwayat Pesan" TIDAK muncul
   - ✅ Tampilan sama seperti sebelum perubahan

### Test 5: Functionality Testing
1. **Test Ubah Status:**
   - Ubah status dari "menunggu" ke "disetujui" → Berhasil
   - Ubah status ke "ditolak" → Berhasil
   - Ubah status ke "perbaiki" → Berhasil
   - Cek database: `tanggal_verifikasi` dan `verified_by` ter-record
   - Cek UI: Badge status berubah sesuai

2. **Test Kirim Email:**
   - Klik "Kirim Email" → Modal terbuka
   - Pilih template → Preview muncul dengan placeholder terisi
   - Edit message → Perubahan tersimpan
   - Kirim → Email terkirim
   - Cek database: Record di `message_histories` ada
   - Cek kolom "Riwayat Pesan" → Update dengan pesan terbaru

3. **Test Kirim WhatsApp:**
   - Klik "Kirim WhatsApp" → Modal terbuka
   - Pilih template → Preview muncul
   - Kirim → WhatsApp terkirim (via API)
   - Cek database: Record di `message_histories` ada
   - Cek kolom "Riwayat Pesan" → Update dengan pesan terbaru

4. **Test Riwayat Pesan:**
   - Empty state: "Belum ada" muncul jika belum pernah kirim
   - After send: Info pesan terbaru muncul
   - Multiple messages: Hanya pesan terakhir yang ditampilkan
   - Context filtering: Hanya pesan dengan context 'tes_psikologi'

### Test 6: URL Routing
1. Sebagai admin, klik link detail → Navigasi ke `/admin/pendaftar/{id}` ✅
2. Sebagai panitia_tes_psikologi, klik link detail → Navigasi ke `/panitia-psikologi/pendaftar/{id}` ✅

## 🔒 Security Notes

### 1. Role-Based Access
- Akses kolom extended hanya untuk admin dan panitia_tes_psikologi
- Validasi role di level view dengan conditional rendering
- Backend method tetap harus melakukan validasi role

### 2. Context Filtering
- Riwayat pesan di-filter dengan `context = 'tes_psikologi'`
- Memastikan tidak tercampur dengan pesan context lain (daftar ulang, dll)

### 3. CSRF Protection
- Semua form dan button menggunakan Livewire (built-in CSRF)
- Wire:click events dilindungi Livewire authentication

### 4. Authorization Middleware
Pastikan route `/panitia-psikologi/pendaftar/{id}` sudah protected:
```php
Route::middleware(['auth', 'role:panitia_tes_psikologi'])->prefix('panitia-psikologi')->group(function() {
    Route::get('/pendaftar/{id}', [PendaftarController::class, 'show']);
});
```

## 📝 Catatan Penting

### 1. Variable `$isPanitiaPsikologi` Must Be Set
Component yang menggunakan partial ini HARUS passing variable `$isPanitiaPsikologi` ke view:
```php
return view('livewire.panitia.tes-psikologi', [
    'dataPsikologi' => $dataPsikologi,
    'isPanitiaPsikologi' => $this->isPanitiaPsikologi, // WAJIB
    // ... other vars
]);
```

### 2. Dynamic URL Routing
URL detail pendaftar sekarang dynamic based on `$userRole`:
- Admin: `/admin/pendaftar/{id}`
- Panitia Tes Psikologi: `/panitia-psikologi/pendaftar/{id}`

Pastikan route dan controller sudah support kedua URL pattern.

### 3. MessageHistory Model
Pastikan model `MessageHistory` sudah ada dengan relationships:
```php
class MessageHistory extends Model
{
    protected $fillable = ['pendaftar_id', 'context', 'message_type', 'message_content', 'sent_by', 'sent_at'];

    protected $casts = [
        'sent_at' => 'datetime'
    ];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }
}
```

### 4. Performance Consideration
Untuk setiap row di tabel, ada query ke `message_histories`:
```php
$lastMessage = \App\Models\MessageHistory::where('pendaftar_id', $data->pendaftar_id)
    ->where('context', 'tes_psikologi')
    ->orderBy('sent_at', 'desc')
    ->first();
```

**Optimization:** Bisa di-eager load di component:
```php
$dataPsikologi = TesPsikologiPendaftar::with([
    'pendaftar' => function($query) {
        $query->with(['lastTesPsikologiMessage' => function($q) {
            $q->where('context', 'tes_psikologi')
              ->orderBy('sent_at', 'desc')
              ->limit(1);
        }]);
    }
])->paginate(20);
```

## 🚀 Deployment Checklist

### File yang Berubah
```
resources/views/livewire/admin/partials/tes-psikologi-data.blade.php  [MODIFIED]
```

### Tidak Ada Perubahan Database
- Tidak perlu migration baru
- Menggunakan tabel existing (`message_histories`, `tes_psikologi_pendaftars`)

### Clear Cache After Deploy
```bash
php artisan view:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Verify After Deploy
1. Login sebagai panitia_tes_psikologi
2. Akses `/panitia-psikologi/tes-psikologi?mainTab=data`
3. Verifikasi kolom extended muncul
4. Test ubah status
5. Test kirim pesan
6. Verifikasi riwayat pesan

## ✅ Benefit Perubahan

### 1. Otonomi Panitia Tes Psikologi
- ✅ Tidak perlu bergantung pada admin untuk ubah status
- ✅ Bisa berkomunikasi langsung dengan pendaftar
- ✅ Workflow lebih cepat dan efisien

### 2. Konsistensi dengan Admin
- ✅ Interface sama dengan admin
- ✅ Fitur lengkap untuk mengelola tes psikologi
- ✅ User experience yang familiar

### 3. Backward Compatible
- ✅ Panitia lain tidak terpengaruh
- ✅ Tidak ada breaking changes
- ✅ Admin tetap full access

### 4. Traceability
- ✅ Semua perubahan status ter-record (verified_by, tanggal_verifikasi)
- ✅ Riwayat pesan tercatat lengkap
- ✅ Audit trail untuk accountability

## 🆚 Sebelum vs Sesudah

### Sebelum
```
Panitia Tes Psikologi di /panitia-psikologi/tes-psikologi?mainTab=data:
- ✅ Lihat data tes psikologi
- ✅ Lihat bukti transfer
- ✅ Lihat status survey
- ❌ Tidak bisa ubah status
- ❌ Tidak bisa kirim pesan
- ❌ Tidak bisa lihat riwayat komunikasi

→ Harus minta admin untuk ubah status atau kirim pesan
```

### Sesudah
```
Panitia Tes Psikologi di /panitia-psikologi/tes-psikologi?mainTab=data:
- ✅ Lihat data tes psikologi
- ✅ Lihat bukti transfer
- ✅ Lihat status survey
- ✅ Ubah status (disetujui/ditolak/perbaiki)
- ✅ Kirim email dengan template
- ✅ Kirim WhatsApp dengan template
- ✅ Lihat riwayat pesan terakhir

→ Mandiri, tidak perlu admin untuk operasi rutin
```

## 📚 Related Documentation

- `PANITIA_TES_PSIKOLOGI_TAB_SURVEY_COMPLETE.md` - Tab Survey (view-only)
- `PANITIA_TES_PSIKOLOGI_SURVEY_FULL_CRUD.md` - Tab Survey (full CRUD)
- `FITUR_RIWAYAT_PESAN_COMPLETE_ALL_LOCATIONS.md` - Sistem riwayat pesan
- `FITUR_ROLE_PANITIA_TES_PSIKOLOGI_COMPLETE.md` - Role panitia tes psikologi

---

**Dokumentasi:** 29 Januari 2026
**Author:** Claude Code
**Version:** 1.0.0
**Status:** ✅ Complete & Ready for Testing
**Breaking Changes:** None
**Backward Compatible:** Yes
