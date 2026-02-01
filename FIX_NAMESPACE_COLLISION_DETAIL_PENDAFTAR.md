# ✅ FIX NAMESPACE COLLISION - DETAIL PENDAFTAR

## 📋 Status: SELESAI

**Tanggal:** 27 Januari 2026
**Error:** BadMethodCallException - Method App\Livewire\Panitia\Pendaftar::find does not exist
**Route:** GET `/panitia/pendaftar/{id}`

---

## 🐛 Root Cause

### Error Message
```
BadMethodCallException
Method App\Livewire\Panitia\Pendaftar::find does not exist.

at app\Livewire\Panitia\DetailPendaftar.php:160
```

### Root Cause: Namespace Collision

**File:** `app/Livewire/Panitia/DetailPendaftar.php` (Line 160)

**Kode Bermasalah:**
```php
public function loadDokumen()
{
    // ❌ WRONG: Refers to Livewire component, not Model
    $pendaftar = Pendaftar::find($this->pendaftarId);
}
```

**Mengapa Error?**

File `DetailPendaftar.php` berada di namespace `App\Livewire\Panitia`.

Di namespace yang sama, ada 2 class bernama `Pendaftar`:
1. ✅ `App\Models\Pendaftar` - Model (yang kita butuhkan)
2. ❌ `App\Livewire\Panitia\Pendaftar` - Livewire component

Ketika kode menulis `Pendaftar::find()`:
- PHP mencari class `Pendaftar` di **current namespace** (`App\Livewire\Panitia`) dulu
- Menemukan `App\Livewire\Panitia\Pendaftar` (Livewire component)
- Mencoba call method `find()` di Livewire component
- **ERROR!** Livewire component tidak punya method `find()`

**Ini adalah Namespace Collision!** 🔥

---

## ✅ Solusi

### Fix: Use Alias

**File sudah memiliki import yang benar di line 7:**
```php
use App\Models\Pendaftar as PendaftarModel;
```

**Tapi di line 160, tidak menggunakan alias:**

**BEFORE (Line 160):**
```php
// ❌ WRONG: Menggunakan Pendaftar (merujuk ke Livewire component)
$pendaftar = Pendaftar::find($this->pendaftarId);
```

**AFTER (Line 160):**
```php
// ✅ CORRECT: Menggunakan PendaftarModel (merujuk ke Model)
$pendaftar = PendaftarModel::find($this->pendaftarId);
```

**Sekarang:**
- Code explicitly menggunakan alias `PendaftarModel`
- PHP tahu harus menggunakan `App\Models\Pendaftar` (Model)
- Method `find()` exists di Model ✅
- No error! 🎉

---

## 📁 File yang Diubah

| File | Line | Perubahan |
|------|------|-----------|
| `app/Livewire/Panitia/DetailPendaftar.php` | 160 | `Pendaftar::find()` → `PendaftarModel::find()` |

**Total:** 1 file, 1 line modified

---

## 🧪 Testing Checklist

### ✅ Scenario 1: Akses Detail Pendaftar

1. **Test:**
   - Login sebagai panitia
   - Akses `/panitia/pendaftar`
   - Klik salah satu pendaftar untuk lihat detail
   - URL: `/panitia/pendaftar/27`

2. **Expected:**
   - Halaman detail pendaftar load normal
   - Tidak ada error BadMethodCallException
   - Data pendaftar tampil lengkap

3. **Verified:** ✅

### ✅ Scenario 2: Load Dokumen

1. **Test:**
   - Di halaman detail pendaftar
   - Navigate ke tab Dokumen

2. **Expected:**
   - Method `loadDokumen()` dipanggil
   - Legacy rapor data (if exists) migrated ke Dokumen model
   - No error

3. **Verified:** ✅

### ✅ Scenario 3: Multiple Detail Pages

1. **Test:**
   - Akses detail pendaftar dengan ID berbeda
   - Test `/panitia/pendaftar/1`
   - Test `/panitia/pendaftar/50`
   - Test `/panitia/pendaftar/100`

2. **Expected:**
   - Semua halaman load normal
   - No namespace collision errors

3. **Verified:** ✅

---

## 🎯 Technical Details

### Namespace Resolution in PHP

**How PHP Resolves Class Names:**

1. **Fully Qualified Name (FQN)**
   ```php
   $user = \App\Models\User::find(1); // ✅ Always works
   ```

2. **Imported with Alias**
   ```php
   use App\Models\User as UserModel;
   $user = UserModel::find(1); // ✅ Works
   ```

3. **Imported without Alias**
   ```php
   use App\Models\User;
   $user = User::find(1); // ⚠️ May conflict if same name exists in current namespace
   ```

4. **Unqualified Name (Relative to Current Namespace)**
   ```php
   // In namespace App\Livewire\Panitia
   $user = User::find(1);
   // ❌ PHP looks for App\Livewire\Panitia\User first!
   ```

### Best Practice: Always Use Aliases for Models

**When in doubt, use aliases:**

```php
namespace App\Livewire\Panitia;

// ✅ GOOD: Use aliases to avoid conflicts
use App\Models\Pendaftar as PendaftarModel;
use App\Models\User as UserModel;
use App\Models\Jalur as JalurModel;

class DetailPendaftar extends Component
{
    public function mount($id)
    {
        // ✅ Clear and unambiguous
        $this->pendaftar = PendaftarModel::find($id);
        $this->user = UserModel::find(1);
        $this->jalur = JalurModel::all();
    }
}
```

**Avoid:**

```php
namespace App\Livewire\Panitia;

// ❌ BAD: Can cause namespace collisions
use App\Models\Pendaftar;
use App\Models\User;

class DetailPendaftar extends Component
{
    public function mount($id)
    {
        // ❌ Which Pendaftar? Model or Livewire component?
        $this->pendaftar = Pendaftar::find($id);
    }
}
```

### Common Namespace Collision Scenarios

| Location | Class Name | Namespace | Type |
|----------|-----------|-----------|------|
| Livewire | `Pendaftar` | `App\Livewire\Panitia\Pendaftar` | Component |
| Livewire | `User` | `App\Livewire\Admin\User` | Component |
| Model | `Pendaftar` | `App\Models\Pendaftar` | Eloquent Model |
| Model | `User` | `App\Models\User` | Eloquent Model |

**If both exist in related namespaces → Collision risk!**

---

## 🔍 How to Prevent This Issue

### 1. Code Review Checklist

When reviewing Livewire components:
- [ ] Check all static method calls (Model::find(), Model::where(), etc.)
- [ ] Ensure they use imported aliases or FQN
- [ ] Look for potential namespace collisions

### 2. IDE Configuration

**PHPStorm / VS Code:**
- Enable "Undefined method" warnings
- Enable "Namespace import" suggestions
- Use strict PSR-4 autoloading

### 3. Naming Convention

**Avoid naming Livewire components same as Models:**

✅ **Good:**
```
App\Livewire\Panitia\PendaftarList.php  (Component)
App\Livewire\Panitia\PendaftarDetail.php (Component)
App\Models\Pendaftar.php                 (Model)
```

❌ **Bad:**
```
App\Livewire\Panitia\Pendaftar.php  (Component) ← Conflict!
App\Models\Pendaftar.php            (Model)     ← Conflict!
```

### 4. Static Analysis

Use tools like:
- **PHPStan** - Catch undefined method calls
- **Psalm** - Detect namespace issues
- **Laravel IDE Helper** - Generate accurate IDE autocomplete

```bash
# Install PHPStan
composer require --dev phpstan/phpstan

# Run analysis
vendor/bin/phpstan analyse app/Livewire
```

---

## 📊 Impact

### Before Fix

- ❌ Error: BadMethodCallException
- ❌ Cannot access detail pendaftar page
- ❌ Route broken: `/panitia/pendaftar/{id}`

### After Fix

- ✅ No error
- ✅ Detail pendaftar page works
- ✅ All routes functional

---

## 🚀 Production Deployment

### Checklist

- [x] Fix applied
- [x] Cache cleared
- [x] Tested locally
- [x] Ready for upload

### Upload Files

```bash
# Upload to production
scp app/Livewire/Panitia/DetailPendaftar.php \
    mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Livewire/Panitia/

# SSH and clear cache
ssh mtsnkot4@103.30.244.77
cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id
php artisan optimize:clear

# Test
curl -I https://pmbm.mtsn2kotamalang.sch.id/panitia/pendaftar/27
# Should return 200 OK
```

---

## 📝 Lessons Learned

1. **Always use aliases for Models in Livewire components**
   - Prevents namespace collision
   - Makes code more explicit
   - Easier to debug

2. **Be aware of namespace context**
   - Current namespace matters for class resolution
   - Use FQN when in doubt

3. **Naming matters**
   - Avoid naming conflicts between Components and Models
   - Use descriptive component names (e.g., `PendaftarList` not `Pendaftar`)

4. **Static analysis is your friend**
   - Tools like PHPStan can catch these errors before runtime
   - IDE hints can prevent mistakes

---

## ✅ Kesimpulan

Namespace collision error di `/panitia/pendaftar/{id}` telah **berhasil diperbaiki** dengan menggunakan alias yang sudah didefinisikan.

**Root Cause:**
- Code menggunakan `Pendaftar::find()`
- PHP resolve ke `App\Livewire\Panitia\Pendaftar` (Livewire component)
- Component tidak punya method `find()` → Error!

**Solution:**
- Gunakan alias `PendaftarModel::find()`
- Explicitly reference `App\Models\Pendaftar` (Model)
- Model punya method `find()` → Works!

**Result:**
- ✅ No more BadMethodCallException
- ✅ Detail pendaftar page functional
- ✅ All routes working

**Status:** PRODUCTION READY 🚀

---

**Dibuat:** 27 Januari 2026
**Developer:** Claude Code AI
**Project:** PPDB MTsN 2 Kota Malang
