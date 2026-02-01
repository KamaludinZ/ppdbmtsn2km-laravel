# ✅ FITUR ROLE PANITIA TES PSIKOLOGI - SELESAI

## 🎯 Ringkasan Fitur

Fitur ini menambahkan role baru **"Panitia Tes Psikologi"** yang memiliki akses terbatas hanya ke 2 menu:
- 📊 **Dashboard** - Melihat statistik pendaftaran
- 📝 **Tes Psikologi** - Mengelola verifikasi tes psikologi

Role ini cocok untuk panitia yang hanya bertugas memverifikasi bukti transfer tes psikologi tanpa akses ke data pendaftar lainnya.

---

## 📋 Perubahan yang Dilakukan

### 1. Database Migration

**File**: `database/migrations/2026_01_27_151550_add_panitia_tes_psikologi_role_to_users_table.php`

```php
public function up(): void
{
    // Drop existing constraint
    DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check");

    // Add new constraint with panitia_tes_psikologi role
    DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role::text = ANY (ARRAY['admin'::character varying, 'pendaftar'::character varying, 'panitia_pendaftaran'::character varying, 'panitia_daftarulang'::character varying, 'panitia_tes_psikologi'::character varying]::text[]))");
}
```

**Cara Menjalankan**:
```bash
php artisan migrate
```

---

### 2. Routes Configuration

**File**: `routes/web.php` (Lines 314-336)

```php
// Panitia Routes - with granular access control
Route::prefix('panitia')->middleware(['auth', 'role:panitia_pendaftaran,panitia_daftarulang,panitia_tes_psikologi'])->group(function () {
    // Dashboard - accessible by all panitia roles
    Route::get('/dashboard', \App\Livewire\Panitia\Dashboard::class)->name('panitia.dashboard');

    // Tes Psikologi - accessible by panitia_pendaftaran and panitia_tes_psikologi
    Route::get('/tes-psikologi', \App\Livewire\Panitia\TesPsikologi::class)
        ->middleware('role:panitia_pendaftaran,panitia_tes_psikologi')
        ->name('panitia.tes-psikologi');

    // Other routes - only accessible by panitia_pendaftaran and panitia_daftarulang
    Route::middleware('role:panitia_pendaftaran,panitia_daftarulang')->group(function () {
        Route::get('/pendaftar', \App\Livewire\Panitia\Pendaftar::class)->name('panitia.pendaftar');
        // ... other routes
    });
});
```

**Penjelasan**:
- Semua role panitia bisa akses dashboard
- `panitia_tes_psikologi` hanya bisa akses dashboard dan tes psikologi
- Route lain (pendaftar, kelulusan, dll) tidak bisa diakses oleh `panitia_tes_psikologi`

---

### 3. Sidebar Menu

**File**: `resources/views/components/layouts/dashboard.blade.php` (Lines 418-432)

```blade
@elseif($role === 'panitia_tes_psikologi')
    <!-- Panitia Tes Psikologi Menu -->
    <a href="/panitia/dashboard" class="flex items-center px-4 py-3 text-sm rounded-lg transition {{ request()->is('panitia/dashboard') ? $activeClass : $inactiveClass }}">
        <svg class="w-5 h-5 mr-3 {{ request()->is('panitia/dashboard') ? 'text-emerald-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
        Dashboard
    </a>
    <a href="/panitia/tes-psikologi" class="flex items-center px-4 py-3 text-sm rounded-lg transition {{ request()->is('panitia/tes-psikologi*') ? $activeClass : $inactiveClass }}">
        <svg class="w-5 h-5 mr-3 {{ request()->is('panitia/tes-psikologi*') ? 'text-emerald-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        Tes Psikologi
    </a>
@endif
```

**Hasil**:
- Sidebar hanya menampilkan 2 menu (Dashboard dan Tes Psikologi)
- Menu lain seperti Pendaftar, Kelulusan, dll TIDAK muncul

---

### 4. Livewire Component Authorization

#### A. Panitia\Dashboard.php

**Perubahan Line 28**:
```php
if (!$user || !in_array($user->role, ['panitia_pendaftaran', 'panitia_daftarulang', 'panitia_tes_psikologi', 'panitia'])) {
    return redirect()->to('/login/admin');
}
```

**Perubahan Line 53**:
```php
$panitiaAktif = User::whereIn('role', ['panitia', 'panitia_pendaftaran', 'panitia_daftarulang', 'panitia_tes_psikologi'])->count();
```

**Perubahan Line 176-181**:
```php
$roleLabel = match($user->role) {
    'panitia_pendaftaran' => 'Panitia Pendaftaran',
    'panitia_daftarulang' => 'Panitia Daftar Ulang',
    'panitia_tes_psikologi' => 'Panitia Tes Psikologi',
    default => 'Panitia'
};
```

#### B. Panitia\TesPsikologi.php

**Perubahan Line 6** (Import Model):
```php
use App\Models\TesPsikologiSetting;
```

**Perubahan Line 423-430** (Pass $user->role):
```php
$user = \Illuminate\Support\Facades\Auth::user();

// Get setting for info box
$setting = TesPsikologiSetting::getSetting();

return view('livewire.panitia.tes-psikologi', [
    'data' => $data,
    'setting' => $setting
])->layout('components.layouts.dashboard', [
    'role' => $user->role,
    'pageTitle' => 'Data Tes Psikologi',
    'pageDescription' => 'Verifikasi bukti transfer tes psikologi pendaftar'
]);
```

---

## 🔐 Hak Akses Role Panitia Tes Psikologi

| Menu/Fitur | Panitia Pendaftaran | Panitia Daftar Ulang | Panitia Tes Psikologi |
|-----------|---------------------|----------------------|-----------------------|
| Dashboard | ✅ | ✅ | ✅ |
| Pendaftar | ✅ | ✅ | ❌ |
| Kelulusan | ✅ | ✅ | ❌ |
| Tes Psikologi | ✅ | ❌ | ✅ |
| Daftar Ulang | ✅ | ✅ | ❌ |
| Jalur | ✅ | ✅ | ❌ |
| Jadwal | ✅ | ✅ | ❌ |
| Data Asal Sekolah | ✅ | ✅ | ❌ |
| Pengumuman | ✅ | ✅ | ❌ |

---

## 👤 Cara Membuat User Panitia Tes Psikologi

### Via Admin Panel
1. Login sebagai **Admin**
2. Buka menu **Panitia**
3. Klik **Tambah Panitia**
4. Isi form:
   - Nama: `[Nama Panitia]`
   - Email: `[email@domain.com]`
   - Password: `[password]`
   - **Role**: Pilih **"Panitia Tes Psikologi"**
5. Klik **Simpan**

### Via Database (PostgreSQL)
```sql
INSERT INTO users (name, email, password, role, email_verified_at, created_at, updated_at)
VALUES (
    'Panitia Tes Psikologi',
    'panitia.tes@mtsn2kotamalang.sch.id',
    '$2y$12$[hash_password]', -- gunakan Hash::make('password')
    'panitia_tes_psikologi',
    NOW(),
    NOW(),
    NOW()
);
```

### Via Tinker
```bash
php artisan tinker
```

```php
\App\Models\User::create([
    'name' => 'Panitia Tes Psikologi',
    'email' => 'panitia.tes@mtsn2kotamalang.sch.id',
    'password' => Hash::make('password123'),
    'role' => 'panitia_tes_psikologi',
    'email_verified_at' => now(),
]);
```

---

## 🧪 Testing Checklist

### 1. Test Login
- [ ] Login dengan user `panitia_tes_psikologi`
- [ ] Pastikan redirect ke `/panitia/dashboard`

### 2. Test Sidebar
- [ ] Sidebar hanya menampilkan 2 menu:
  - [ ] Dashboard
  - [ ] Tes Psikologi
- [ ] Menu lain TIDAK muncul

### 3. Test Access Dashboard
- [ ] Akses `/panitia/dashboard` - Harus berhasil ✅
- [ ] Lihat statistik pendaftaran
- [ ] Page title: "Dashboard Panitia Tes Psikologi"

### 4. Test Access Tes Psikologi
- [ ] Akses `/panitia/tes-psikologi` - Harus berhasil ✅
- [ ] Lihat data tes psikologi pendaftar
- [ ] Test fitur verifikasi (Setujui/Tolak)
- [ ] Test kirim pesan ke pendaftar

### 5. Test Restricted Access
- [ ] Akses `/panitia/pendaftar` - Harus **403 Forbidden** ❌
- [ ] Akses `/panitia/kelulusan` - Harus **403 Forbidden** ❌
- [ ] Akses `/panitia/daftar-ulang` - Harus **403 Forbidden** ❌
- [ ] Akses `/panitia/jalur` - Harus **403 Forbidden** ❌

### 6. Test Authorization
- [ ] Pastikan tidak ada error "Unauthorized" atau "Access Denied"
- [ ] Role label di dashboard muncul: "Dashboard Panitia Tes Psikologi"

---

## 📝 Files yang Diubah/Dibuat

### Migrations
- ✅ `database/migrations/2026_01_27_151550_add_panitia_tes_psikologi_role_to_users_table.php`

### Routes
- ✅ `routes/web.php` (Lines 314-336)

### Views
- ✅ `resources/views/components/layouts/dashboard.blade.php` (Lines 418-432)

### Livewire Components
- ✅ `app/Livewire/Panitia/Dashboard.php` (Lines 28, 53, 176-181)
- ✅ `app/Livewire/Panitia/TesPsikologi.php` (Lines 6, 423-430)

---

## 🚀 Deployment ke Production

### 1. Upload Files
Upload semua file yang diubah ke server production sesuai struktur direktori.

### 2. Jalankan Migration
```bash
ssh user@server
cd /path/to/production
php artisan migrate
```

### 3. Clear Cache
```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
```

### 4. Test di Production
- Login dengan user panitia_tes_psikologi
- Verifikasi akses dan sidebar

---

## ⚠️ Catatan Penting

1. **PostgreSQL Enum Constraint**
   - Migration menggunakan raw SQL untuk modify enum constraint
   - Rollback akan mengubah user dengan role `panitia_tes_psikologi` menjadi `panitia_pendaftaran`

2. **Middleware Caching**
   - Jangan lupa `php artisan route:cache` setelah update routes
   - Clear cache jika ada error middleware

3. **Existing Users**
   - User yang sudah ada tidak terpengaruh
   - Hanya user baru yang bisa diberi role `panitia_tes_psikologi`

4. **Security**
   - Role checking dilakukan di 3 layer:
     - Routes (middleware)
     - Livewire Components (mount/authorization)
     - Sidebar (conditional rendering)

---

## 🎉 Status

**✅ FITUR LENGKAP DAN SIAP DIGUNAKAN**

Role **Panitia Tes Psikologi** sudah berhasil ditambahkan dengan akses terbatas hanya ke Dashboard dan Tes Psikologi.

---

**Dibuat**: 27 Januari 2026
**Developer**: Claude Code
**Versi**: 1.0.0
