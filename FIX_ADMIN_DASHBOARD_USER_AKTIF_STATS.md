# Fix: Admin Dashboard - Stats "Panitia Aktif" menjadi "User Aktif" ✅

**Tanggal:** 29 Januari 2026
**Status:** ✅ SELESAI

---

## 📋 Ringkasan Perubahan

Mengubah stats card **"Panitia Aktif"** di Admin Dashboard menjadi **"User Aktif"** yang menampilkan jumlah user dari semua role yang **sedang aktif login** di aplikasi (online).

### Before (❌):
- **Label:** "Panitia Aktif"
- **Data:** Count user dengan role panitia (panitia, panitia_pendaftaran, panitia_daftarulang)
- **Subtitle:** "Tim PMBM 2025"
- **Issue:** Hanya menghitung jumlah user panitia, bukan yang sedang online

### After (✅):
- **Label:** "User Aktif"
- **Data:** Count user dari **semua role** yang sedang login (online dalam 15 menit terakhir)
- **Subtitle:** "Sedang Online"
- **Benefit:** Real-time monitoring user yang aktif menggunakan aplikasi

---

## 🔧 Files Modified

### 1. `app/Livewire/Admin/Dashboard.php`

#### Method: `loadStats()` (Line 54-59, 83)

**Before:**
```php
// Panitia aktif
$panitiaAktif = User::whereIn('role', ['panitia', 'panitia_pendaftaran', 'panitia_daftarulang'])->count();

// ...

$this->stats = [
    // ...
    'panitia_aktif' => $panitiaAktif,
];
```

**After:**
```php
// User aktif (sedang login dalam 15 menit terakhir)
$userAktif = DB::table('sessions')
    ->whereNotNull('user_id')
    ->where('last_activity', '>=', now()->subMinutes(15)->timestamp)
    ->distinct()
    ->count('user_id');

// ...

$this->stats = [
    // ...
    'user_aktif' => $userAktif,
];
```

**Perubahan:**
- ✅ Query dari `users` table → `sessions` table
- ✅ Filter by `user_id IS NOT NULL` (user yang authenticated)
- ✅ Filter by `last_activity >= 15 minutes ago` (masih online)
- ✅ Count `distinct user_id` (unique users)
- ✅ Variable name: `$panitiaAktif` → `$userAktif`

---

### 2. `resources/views/livewire/admin/dashboard.blade.php`

#### Section: Stats Overview Card (Line 86-100)

**Before:**
```blade
<!-- Panitia Aktif -->
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-600">Panitia Aktif</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['panitia_aktif']) }}</p>
            <p class="text-sm text-gray-600 mt-2">Tim {{ $appAcronym }} 2025</p>
        </div>
        <div class="w-14 h-14 bg-purple-100 rounded-lg flex items-center justify-center">
            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
        </div>
    </div>
</div>
```

**After:**
```blade
<!-- User Aktif -->
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-600">User Aktif</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['user_aktif']) }}</p>
            <p class="text-sm text-gray-600 mt-2">Sedang Online</p>
        </div>
        <div class="w-14 h-14 bg-purple-100 rounded-lg flex items-center justify-center">
            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728m-9.9-2.829a5 5 0 010-7.07m7.072 0a5 5 0 010 7.07M13 12a1 1 0 11-2 0 1 1 0 012 0z"></path>
            </svg>
        </div>
    </div>
</div>
```

**Perubahan:**
- ✅ Title: "Panitia Aktif" → "User Aktif"
- ✅ Data: `$stats['panitia_aktif']` → `$stats['user_aktif']`
- ✅ Subtitle: "Tim PMBM 2025" → "Sedang Online"
- ✅ Icon: Team icon → Radio/Signal icon (online indicator)

---

## 📊 Technical Details

### How It Works

#### Query Explanation:
```php
$userAktif = DB::table('sessions')
    ->whereNotNull('user_id')  // (1)
    ->where('last_activity', '>=', now()->subMinutes(15)->timestamp)  // (2)
    ->distinct()  // (3)
    ->count('user_id');  // (4)
```

**Breakdown:**

1. **`whereNotNull('user_id')`**
   - Filter hanya session yang authenticated (ada user_id)
   - Skip guest sessions (user_id = null)

2. **`where('last_activity', '>=', now()->subMinutes(15)->timestamp)`**
   - `now()->subMinutes(15)` = 15 menit yang lalu
   - `->timestamp` = convert ke Unix timestamp
   - Filter session yang last_activity masih dalam 15 menit terakhir
   - **Definisi "Online":** User yang aktif dalam 15 menit terakhir

3. **`distinct()`**
   - Ensure unique user_id only
   - Satu user bisa punya multiple sessions (multiple devices/tabs)
   - Kita hanya count unique user

4. **`count('user_id')`**
   - Count total unique user_id

---

### Session Table Structure

Laravel session table (dengan `SESSION_DRIVER=database`):

| Column | Type | Description |
|--------|------|-------------|
| `id` | string | Session ID (primary key) |
| `user_id` | bigint | User ID (nullable, null for guests) |
| `ip_address` | string | IP address user |
| `user_agent` | text | Browser user agent |
| `payload` | longtext | Session data (encrypted) |
| `last_activity` | integer | Unix timestamp last activity |

**Query targets:**
- `user_id IS NOT NULL` → Authenticated users only
- `last_activity >= (now - 15 min)` → Online users only

---

### Why 15 Minutes?

**Rationale:**
- ✅ **Laravel default session lifetime:** 120 minutes (2 hours)
- ✅ **Active usage indicator:** 15 minutes reasonable untuk "sedang online"
- ✅ **Not too strict:** Tidak logout user yang idle sebentar (misal baca konten)
- ✅ **Not too loose:** Tidak count user yang sudah idle terlalu lama

**Alternative thresholds:**
- **5 minutes:** Very strict, hanya user yang benar-benar aktif
- **15 minutes (current):** Balanced, recommended
- **30 minutes:** Loose, count user yang mungkin sudah idle

**Customization:**
Jika ingin ubah threshold, edit line 57:
```php
->where('last_activity', '>=', now()->subMinutes(5)->timestamp)  // 5 menit
->where('last_activity', '>=', now()->subMinutes(30)->timestamp)  // 30 menit
```

---

## 📊 Before vs After Comparison

| Aspek | Before | After |
|-------|--------|-------|
| **Label** | Panitia Aktif | User Aktif |
| **Data Source** | `users` table | `sessions` table |
| **Filter** | Role IN (panitia, ...) | user_id NOT NULL AND last_activity recent |
| **Scope** | Only panitia roles | All roles (admin, panitia, pendaftar) |
| **Real-time** | ❌ Static count | ✅ Dynamic (real-time online) |
| **Useful for** | ❌ Team size info (not changing) | ✅ Activity monitoring |
| **Icon** | Team icon | Online/Signal icon |
| **Subtitle** | "Tim PMBM 2025" | "Sedang Online" |

---

## ✅ Cache Cleared

```bash
php artisan view:clear
php artisan optimize:clear
```

✅ All caches cleared successfully!

---

## 🧪 Cara Testing

### Test 1: Verify User Aktif Count

1. **Login sebagai Admin**
   - Akses: `/admin/dashboard`
   - Lihat stats card **"User Aktif"**
   - **Expected:** Minimal count = 1 (admin yang sedang login)

2. **Open Multiple Sessions**
   - Login sebagai Admin di browser 1
   - Login sebagai Panitia di browser 2 (private/incognito)
   - Login sebagai Pendaftar di browser 3
   - Refresh dashboard admin
   - **Expected:** User Aktif = 3

3. **Test Idle Timeout**
   - Login sebagai user A
   - Idle (tidak klik apa-apa) selama 16 menit
   - User B refresh dashboard
   - **Expected:** User A tidak dihitung lagi (sudah > 15 menit idle)

---

### Test 2: Verify Query Performance

```sql
-- Manual check di database
SELECT COUNT(DISTINCT user_id) as user_aktif
FROM sessions
WHERE user_id IS NOT NULL
  AND last_activity >= UNIX_TIMESTAMP(NOW() - INTERVAL 15 MINUTE);
```

**Expected:**
- Query execution time < 100ms
- Same result dengan stats card

---

### Test 3: Verify All Roles Counted

**Scenario:** Login dengan berbagai role

| Role | Browser | Expected Counted? |
|------|---------|-------------------|
| admin | Chrome | ✅ Yes |
| panitia_pendaftaran | Firefox | ✅ Yes |
| panitia_daftarulang | Edge | ✅ Yes |
| panitia_tes_psikologi | Safari | ✅ Yes |
| panitia_seragam | Opera | ✅ Yes |
| pendaftar | Brave | ✅ Yes |
| Guest (not logged in) | - | ❌ No (user_id IS NULL) |

---

### Test 4: Visual & UX

1. Akses `/admin/dashboard`
2. **Expected card layout:**
   - Title: "User Aktif" (gray text)
   - Count: Large number (e.g., "5")
   - Subtitle: "Sedang Online" (gray text)
   - Icon: Purple signal/radio icon
3. **Expected behavior:**
   - Auto-refresh: Angka update ketika refresh page
   - Real-time: Count berubah sesuai user yang login/logout

---

## 🎯 Benefits dari Perubahan Ini

### 1. Real-Time Monitoring
- ✅ Admin bisa lihat berapa user yang sedang aktif
- ✅ Indikator sistem load dan usage
- ✅ Monitoring aktivitas aplikasi real-time

### 2. All Roles Included
- ✅ Tidak hanya panitia, tapi semua user (admin, panitia, pendaftar)
- ✅ Complete picture of active users
- ✅ Better system monitoring

### 3. More Relevant Data
- ✅ "User Aktif" lebih informatif daripada "Panitia Aktif"
- ✅ Dynamic data vs static role count
- ✅ Actionable information (bisa lihat peak hours)

### 4. Better Icon & Label
- ✅ Signal/Radio icon lebih represent "online"
- ✅ "Sedang Online" jelas indicate real-time
- ✅ Consistent with modern dashboard patterns

---

## 🚀 Possible Enhancements

### 1. Add Role Breakdown
Show distribution by role:
```blade
<p class="text-xs text-gray-500 mt-1">
    Admin: 1 | Panitia: 3 | Pendaftar: 12
</p>
```

### 2. Add Peak Hour Indicator
```blade
<p class="text-xs text-green-600 mt-1">
    📈 Peak: 45 user (14:00 WIB)
</p>
```

### 3. Add Auto-Refresh
Use Livewire polling:
```php
// In component
use Livewire\Attributes\On;

#[On('refresh-stats')]
public function refreshStats()
{
    $this->loadStats();
}
```

```blade
<div wire:poll.30s="refreshStats">
    <!-- Stats card -->
</div>
```

### 4. Add Online User List
Modal showing who's online:
```blade
<button wire:click="$toggle('showOnlineUsers')" class="text-xs text-blue-600 hover:underline">
    Lihat Detail
</button>
```

### 5. Add Activity Timeline
Show login/logout timeline in last hour.

---

## 📌 Important Notes

### Session Driver Requirement
**Required:** `SESSION_DRIVER=database` in `.env`

**Why?**
- Query depends on `sessions` table
- File/cookie sessions tidak bisa di-query
- Redis/memcached bisa, tapi perlu custom implementation

**Verify:**
```bash
# Check .env
grep SESSION_DRIVER .env

# Expected output
SESSION_DRIVER=database
```

---

### Performance Considerations

**Query Performance:**
- Sessions table bisa grow besar over time
- Recommended: Add index pada columns yang di-query

**Recommended Index:**
```sql
CREATE INDEX idx_sessions_user_activity
ON sessions(user_id, last_activity);
```

**Session Cleanup:**
Laravel automatically cleanup expired sessions via:
- `php artisan session:gc` (garbage collection)
- Runs automatically on percentage basis

---

### Multi-Device Sessions

**Scenario:** User login dari multiple devices

**Example:**
- User A login dari laptop → Session 1
- User A login dari phone → Session 2
- Query result: Count = 1 (distinct user_id)

**Why distinct()?**
- We count unique users, not sessions
- One user with 3 devices still counted as 1 user
- More accurate representation of "active users"

---

## 📝 Files Modified Summary

### PHP Files:
1. ✅ `app/Livewire/Admin/Dashboard.php` - Updated loadStats() method

### Blade Files:
2. ✅ `resources/views/livewire/admin/dashboard.blade.php` - Updated stats card

### Total Files Modified: 2

---

## 🔍 SQL Equivalent

For manual verification:

```sql
-- Count user aktif (online dalam 15 menit terakhir)
SELECT COUNT(DISTINCT user_id) as user_aktif
FROM sessions
WHERE user_id IS NOT NULL
  AND last_activity >= UNIX_TIMESTAMP(NOW() - INTERVAL 15 MINUTE);

-- Detail user aktif (dengan info)
SELECT DISTINCT s.user_id, u.name, u.email, u.role,
       FROM_UNIXTIME(s.last_activity) as last_seen
FROM sessions s
JOIN users u ON s.user_id = u.id
WHERE s.user_id IS NOT NULL
  AND s.last_activity >= UNIX_TIMESTAMP(NOW() - INTERVAL 15 MINUTE)
ORDER BY s.last_activity DESC;
```

---

**Selesai! Stats "Panitia Aktif" berhasil diubah menjadi "User Aktif" dengan real-time monitoring!** 🎉

**End of Documentation**
