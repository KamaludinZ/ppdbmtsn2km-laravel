# Session Fixes - 31 Januari 2026 (Part 2)

## Ringkasan Perbaikan

Melanjutkan dari session sebelumnya, memperbaiki issue terakhir yaitu header public page yang masih menampilkan tombol login meskipun user sudah login.

---

## Fix Header Public - Avatar vs Login Buttons

**Masalah:**
- Ketika sudah login dan mengakses halaman public (homepage, informasi, dll)
- Header masih menampilkan tombol "Login Pendaftar" dan "Login Panitia"
- Seharusnya menampilkan avatar profil user yang sedang login

**Root Cause:**
- Layout `resources/views/components/layouts/public.blade.php` tidak mengecek status autentikasi
- Navigation items selalu tampil tanpa conditional `@auth/@guest`
- Tidak ada avatar dropdown untuk user yang sudah login

**Solusi:**

### 1. Desktop Navigation (Lines 122-207)

**SEBELUM:**
```blade
<!-- Right Side -->
<div class="flex items-center space-x-3">
    <a href="/login" class="hidden lg:inline-flex...">
        Login Pendaftar
    </a>
    <a href="/login/admin" class="hidden lg:inline-flex...">
        Login Panitia
    </a>
    @if(\App\Models\Setting::get('registration_phase') === 'dibuka')
    <a href="/daftar" class="inline-flex...">
        Daftar Sekarang
    </a>
    @endif
```

**SETELAH:**
```blade
<!-- Right Side -->
<div class="flex items-center space-x-3">
    @auth
        <!-- User Avatar Dropdown (Alpine.js) -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" @click.away="open = false"
                    class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                <!-- Avatar Circle with First Letter -->
                <div class="w-8 h-8 bg-emerald-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <span class="hidden lg:block text-sm font-medium text-gray-700">
                    {{ auth()->user()->name }}
                </span>
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" x-transition
                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 border border-gray-200 z-50">

                <!-- Dashboard Link (Role-based) -->
                @if(auth()->user()->isPendaftar())
                    <a href="/pendaftar/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Dashboard
                    </a>
                @elseif(auth()->user()->isAdmin())
                    <a href="/admin/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Dashboard Admin
                    </a>
                @elseif(auth()->user()->role === 'panitia_pendaftaran')
                    <a href="/panitia-pendaftaran/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Dashboard Panitia
                    </a>
                @elseif(auth()->user()->role === 'panitia_daftarulang')
                    <a href="/panitia-daftarulang/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Dashboard Panitia DU
                    </a>
                @elseif(auth()->user()->role === 'panitia_tes_psikologi')
                    <a href="/panitia-psikologi/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Dashboard Panitia Psikologi
                    </a>
                @elseif(auth()->user()->role === 'panitia_seragam')
                    <a href="/panitia-seragam/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Dashboard Panitia Seragam
                    </a>
                @endif

                <hr class="my-1">

                <!-- Logout -->
                <form action="/logout" method="POST">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    @else
        <!-- Login Buttons (Guest Only) -->
        <a href="/login" class="hidden lg:inline-flex items-center px-4 py-2 border border-emerald-600 text-sm font-medium rounded-lg text-emerald-600 hover:bg-emerald-50 transition">
            Login Pendaftar
        </a>
        <a href="/login/admin" class="hidden lg:inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition shadow-sm">
            Login Panitia
        </a>
        @if(\App\Models\Setting::get('registration_phase') === 'dibuka')
        <a href="/daftar" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 transition shadow-sm">
            Daftar Sekarang
        </a>
        @endif
    @endauth
```

### 2. Mobile Navigation (Lines 281-335)

**SEBELUM:**
```blade
<div class="px-4 py-3 border-t border-gray-200 space-y-2">
    <a href="/login" class="block w-full...">Login Pendaftar</a>
    <a href="/login/admin" class="block w-full...">Login Panitia</a>
</div>
```

**SETELAH:**
```blade
<div class="px-4 py-3 border-t border-gray-200 space-y-2">
    @auth
        <!-- User Profile Section (Mobile) -->
        <div class="bg-gray-50 rounded-lg p-4 mb-2">
            <div class="flex items-center mb-3">
                <div class="w-10 h-10 bg-emerald-600 rounded-full flex items-center justify-center text-white font-semibold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                </div>
            </div>

            <!-- Dashboard Button (Role-based) -->
            @if(auth()->user()->isPendaftar())
                <a href="/pendaftar/dashboard" class="block w-full px-4 py-2 text-center text-sm font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 transition mb-2">
                    Ke Dashboard
                </a>
            @elseif(auth()->user()->isAdmin())
                <a href="/admin/dashboard" class="block w-full px-4 py-2 text-center text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition mb-2">
                    Ke Dashboard Admin
                </a>
            @elseif(auth()->user()->role === 'panitia_pendaftaran')
                <a href="/panitia-pendaftaran/dashboard" class="block w-full px-4 py-2 text-center text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition mb-2">
                    Ke Dashboard Panitia
                </a>
            @elseif(auth()->user()->role === 'panitia_daftarulang')
                <a href="/panitia-daftarulang/dashboard" class="block w-full px-4 py-2 text-center text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition mb-2">
                    Ke Dashboard Panitia DU
                </a>
            @elseif(auth()->user()->role === 'panitia_tes_psikologi')
                <a href="/panitia-psikologi/dashboard" class="block w-full px-4 py-2 text-center text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition mb-2">
                    Ke Dashboard Panitia Psikologi
                </a>
            @elseif(auth()->user()->role === 'panitia_seragam')
                <a href="/panitia-seragam/dashboard" class="block w-full px-4 py-2 text-center text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition mb-2">
                    Ke Dashboard Panitia Seragam
                </a>
            @endif

            <!-- Logout Button -->
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="block w-full px-4 py-2 text-center text-sm font-medium rounded-lg text-red-600 border border-red-600 hover:bg-red-50 transition">
                    Logout
                </button>
            </form>
        </div>
    @else
        <!-- Login Buttons (Guest Only - Mobile) -->
        <a href="/login" class="block w-full px-4 py-2 text-center border border-emerald-600 text-sm font-medium rounded-lg text-emerald-600 hover:bg-emerald-50 transition mb-2">
            Login Pendaftar
        </a>
        <a href="/login/admin" class="block w-full px-4 py-2 text-center border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition">
            Login Panitia
        </a>
    @endauth
</div>
```

**Files Modified:**
- ✅ `resources/views/components/layouts/public.blade.php`

**Fitur yang Ditambahkan:**

1. **Avatar Circle dengan Initial**
   - Lingkaran hijau dengan huruf pertama nama user
   - Menggunakan `strtoupper(substr(auth()->user()->name, 0, 1))`

2. **Dropdown Menu (Alpine.js)**
   - Click to open/close dengan `x-data="{ open: false }"`
   - Auto-close saat click di luar dengan `@click.away="open = false"`
   - Smooth transition dengan `x-transition`

3. **Role-Based Dashboard Link**
   - Pendaftar → `/pendaftar/dashboard`
   - Admin → `/admin/dashboard`
   - Panitia Pendaftaran → `/panitia-pendaftaran/dashboard`
   - Panitia Daftar Ulang → `/panitia-daftarulang/dashboard`
   - Panitia Tes Psikologi → `/panitia-psikologi/dashboard`
   - Panitia Seragam → `/panitia-seragam/dashboard`

4. **Logout Button**
   - Form POST ke `/logout` dengan CSRF token
   - Styling merah untuk tombol logout

5. **Responsive Design**
   - Desktop: Avatar dropdown di kanan atas
   - Mobile: Profile card dengan nama dan email lengkap

**Status:** ✅ **CODE COMPLETE - READY FOR UPLOAD**

---

## Upload Manual ke Production

Karena koneksi SSH terputus, file perlu diupload secara manual:

### File yang Perlu Diupload:

```
resources/views/components/layouts/public.blade.php
```

### Lokasi di Production:

```
/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/components/layouts/public.blade.php
```

### Cara Upload:

**Opsi 1: SCP Command**
```bash
scp "C:\ppdbmtsn2km-laravel\resources\views\components\layouts\public.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/components/layouts/public.blade.php
```

**Opsi 2: FTP/SFTP Client**
- Gunakan FileZilla, WinSCP, atau client FTP lainnya
- Upload file ke path yang sama

### Setelah Upload:

```bash
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id

# Clear view cache
php artisan view:clear
php artisan cache:clear

exit
```

---

## Testing Checklist

### Desktop Navigation:
- [ ] Login sebagai **Pendaftar**, akses homepage → Avatar muncul di kanan atas
- [ ] Klik avatar → Dropdown muncul dengan link "Dashboard"
- [ ] Klik Dashboard → Redirect ke `/pendaftar/dashboard`
- [ ] Klik Logout → User logout dan kembali ke homepage dengan tombol login

- [ ] Login sebagai **Admin**, akses homepage → Avatar muncul
- [ ] Klik avatar → Link "Dashboard Admin" tampil
- [ ] Klik Dashboard Admin → Redirect ke `/admin/dashboard`

- [ ] Login sebagai **Panitia Pendaftaran** → Link ke `/panitia-pendaftaran/dashboard`
- [ ] Login sebagai **Panitia Daftar Ulang** → Link ke `/panitia-daftarulang/dashboard`
- [ ] Login sebagai **Panitia Tes Psikologi** → Link ke `/panitia-psikologi/dashboard`
- [ ] Login sebagai **Panitia Seragam** → Link ke `/panitia-seragam/dashboard`

### Mobile Navigation:
- [ ] Login sebagai Pendaftar, buka menu mobile → Profile card muncul
- [ ] Profile card menampilkan nama dan email user
- [ ] Tombol "Ke Dashboard" berfungsi
- [ ] Tombol "Logout" berfungsi

### Guest (Belum Login):
- [ ] Akses homepage tanpa login → Tombol "Login Pendaftar" dan "Login Panitia" tampil
- [ ] Tidak ada avatar atau dropdown
- [ ] Desktop: 2 tombol login di kanan atas
- [ ] Mobile: 2 tombol login di menu mobile

---

## Technical Details

### Alpine.js Usage:
```blade
<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" @click.away="open = false">
        ...
    </button>
    <div x-show="open" x-transition>
        ...
    </div>
</div>
```

### Authentication Check:
```blade
@auth
    <!-- Konten untuk user yang sudah login -->
    {{ auth()->user()->name }}
    {{ auth()->user()->email }}
@else
    <!-- Konten untuk guest -->
@endauth
```

### Role Detection:
```blade
@if(auth()->user()->isPendaftar())
    <!-- Pendaftar logic -->
@elseif(auth()->user()->isAdmin())
    <!-- Admin logic -->
@elseif(auth()->user()->role === 'panitia_pendaftaran')
    <!-- Panitia Pendaftaran logic -->
@endif
```

---

## Summary Session Complete

**Total Issues Fixed dalam Session Ini:**
1. ✅ Toggle Survey tidak tampil bagus → Fixed dengan custom CSS
2. ✅ Tombol Edit/Hapus tidak responsif → Fixed duplikasi @if
3. ✅ Pagination filter tidak menampilkan 100 item → Fixed dengan updatingPerPage()
4. ✅ Header public masih tampilkan login meskipun sudah login → Fixed dengan @auth/@guest

**Total Files Modified:**
1. `public/css/toggle-switch.css` (NEW)
2. `resources/views/components/asset-loader.blade.php`
3. `resources/views/livewire/admin/partials/tes-psikologi-survey.blade.php`
4. `resources/views/livewire/panitia/partials/tes-psikologi-survey.blade.php`
5. `resources/views/livewire/admin/jadwal.blade.php`
6. `app/Livewire/Admin/Jadwal.php`
7. `app/Livewire/Panitia/Jadwal.php`
8. `app/Livewire/Admin/Pendaftar.php`
9. `app/Livewire/Panitia/Pendaftar.php`
10. `resources/views/components/layouts/public.blade.php`

**Production Status:**
- Files 1-9: ✅ **ALREADY UPLOADED** (dari session sebelumnya)
- File 10: ⏳ **PENDING UPLOAD** (koneksi SSH terputus)

**Production URL:** https://pmbm.mtsn2kotamalang.sch.id

---

## Notes

- Alpine.js sudah ada di layout, jadi dropdown langsung berfungsi
- Avatar menggunakan initial huruf pertama nama untuk simplicity
- Warna avatar menggunakan emerald (hijau) yang konsisten dengan branding
- Logout menggunakan POST method dengan CSRF protection
- Dropdown auto-close saat click di luar area (Alpine.js @click.away)
