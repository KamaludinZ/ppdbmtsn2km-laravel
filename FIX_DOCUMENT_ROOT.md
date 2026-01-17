# 🚨 FIX MASALAH DOCUMENT ROOT SUBDOMAIN

## MASALAH
Website menampilkan **"Index of /"** karena document root subdomain mengarah ke **root aplikasi**, bukan ke folder **`/public`**.

## SOLUSI (Pilih Salah Satu)

---

## ✅ SOLUSI 1: Ubah Document Root di cPanel (RECOMMENDED)

### Langkah-langkah:

1. **Login ke cPanel**

2. **Masuk ke: Domains → Subdomains**

3. **Cari subdomain `pmbm.mtsn2kotamalang.sch.id`**

4. **Klik "Manage" atau "Edit"**

5. **Ubah Document Root dari:**
   ```
   /home/username/pmbm.mtsn2kotamalang.sch.id
   ```

   **Menjadi:**
   ```
   /home/username/pmbm.mtsn2kotamalang.sch.id/public
   ```

6. **Save Changes**

7. **Test website** - Seharusnya langsung jalan!

---

## ✅ SOLUSI 2: Buat .htaccess di Root (Jika Tidak Bisa Edit Subdomain)

### Langkah-langkah:

1. **Via File Manager cPanel**

2. **Navigate ke root subdomain:**
   ```
   /home/username/pmbm.mtsn2kotamalang.sch.id/
   ```

3. **Create New File: `.htaccess`**

4. **Isi dengan kode berikut:**

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On

    # Force HTTPS
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}/$1 [R=301,L]

    # Redirect everything to public folder
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteRule ^(.*)$ /public/$1 [L]

    # Redirect root specifically
    RewriteCond %{REQUEST_URI} ^/$
    RewriteRule ^$ /public/ [L]
</IfModule>

# Security - Prevent directory listing
Options -Indexes

# Deny access to sensitive files
<FilesMatch "^\.env">
    Order allow,deny
    Deny from all
</FilesMatch>

<FilesMatch "\.(json|lock|md|yml|yaml)$">
    Order allow,deny
    Deny from all
</FilesMatch>

# Deny access to folders
<DirectoryMatch "^(app|bootstrap|config|database|resources|routes|storage|vendor)">
    Order allow,deny
    Deny from all
</DirectoryMatch>
```

5. **Save file**

6. **Test website**

---

## ✅ SOLUSI 3: Alternative .htaccess (Lebih Simple)

Jika solusi 2 tidak work, coba yang ini:

**File: `.htaccess` di root**

```apache
RewriteEngine On

# Redirect all requests to public folder
RewriteRule ^(.*)$ public/$1 [L]
```

**Dan pastikan ada `.htaccess` di folder `/public`:**

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

---

## ⚠️ JIKA HOSTING TIDAK SUPPORT .htaccess

### Buat `index.php` di Root:

1. **Create file `index.php` di root subdomain**

2. **Isi dengan:**

```php
<?php
/**
 * Laravel - A PHP Framework For Web Artisans
 * @package  Laravel
 */

// Get the Laravel bootstrap file
require __DIR__.'/public/index.php';
```

---

## 📁 STRUKTUR YANG BENAR

```
/home/username/pmbm.mtsn2kotamalang.sch.id/
├── .htaccess         ← Redirect ke public (jika perlu)
├── app/
├── bootstrap/
├── config/
├── database/
├── public/           ← DOCUMENT ROOT HARUSNYA DI SINI
│   ├── .htaccess     ← Laravel htaccess
│   ├── index.php     ← Laravel entry point
│   ├── css/
│   │   └── app.css
│   └── js/
│       └── app.js
├── resources/
├── routes/
├── storage/
├── vendor/
└── .env
```

---

## ✅ QUICK FIX STEPS

### CARA TERCEPAT:

1. **Login cPanel**
2. **Domains → Subdomains**
3. **Edit `pmbm.mtsn2kotamalang.sch.id`**
4. **Tambahkan `/public` di akhir Document Root**
5. **Save**
6. **DONE! Website langsung jalan**

### JIKA TIDAK BISA:

1. **Create `.htaccess` di root**
2. **Copy kode dari Solusi 2 di atas**
3. **Save**
4. **Test website**

---

## 🔍 CARA CEK DOCUMENT ROOT SEKARANG

Via Terminal/SSH:
```bash
# Check current directory structure
ls -la /home/username/pmbm.mtsn2kotamalang.sch.id/

# Check if public folder exists
ls -la /home/username/pmbm.mtsn2kotamalang.sch.id/public/
```

Via cPanel:
- File Manager → Navigate ke subdomain folder
- Pastikan ada folder `public/` dengan `index.php` di dalamnya

---

## ⚡ TEST SETELAH FIX

1. Buka: https://pmbm.mtsn2kotamalang.sch.id
2. Seharusnya muncul aplikasi, bukan directory listing
3. Jika masih error 500, check:
   - Permission folders (storage, bootstrap/cache)
   - File .env exists
   - Database connection

---

## 📞 JIKA MASIH BERMASALAH

1. **Hubungi hosting support**
   - Minta bantuan ubah document root subdomain
   - Atau minta enable mod_rewrite jika .htaccess tidak work

2. **Alternative hosting panel:**
   - DirectAdmin: Domain Setup → Document Root
   - Plesk: Websites & Domains → Hosting Settings
   - CyberPanel: Websites → Manage → vHost Conf

---

**PENTING:** Document root HARUS mengarah ke folder `/public`, BUKAN root aplikasi. Ini standar security Laravel!