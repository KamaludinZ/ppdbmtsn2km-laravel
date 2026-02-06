# PANDUAN LENGKAP: Setup SSH untuk Shared Hosting ke Claude Code

**Tujuan**: Agar Claude Code bisa jalankan command SSH langsung ke production server untuk debugging

---

## 🎯 OVERVIEW

Untuk Claude Code bisa SSH ke server, perlu:
1. ✅ SSH service aktif di server
2. ✅ SSH key authentication (tanpa password prompt)
3. ✅ Firewall allow connection dari IP Anda
4. ✅ SSH config di Windows sudah setup

---

## STEP 1: CEK APAKAH SHARED HOSTING SUPPORT SSH

### Via cPanel:

1. **Login cPanel** → `https://103.30.244.77:2083` (atau domain cPanel Anda)
2. **Cari "Terminal" atau "SSH Access"** di cPanel

**Jika ADA menu "Terminal":**
✅ SSH supported! Lanjut ke Step 2.

**Jika TIDAK ADA menu "Terminal":**
❌ SSH mungkin disabled oleh hosting provider.

**Action**: Hubungi hosting provider dan tanya:
- "Apakah SSH access tersedia untuk akun saya?"
- "Port berapa untuk SSH?" (biasanya 22 atau 2222)
- "Apakah perlu aktivasi SSH key?"

---

## STEP 2: AKTIVASI SSH ACCESS (Jika Belum Aktif)

### A. Via cPanel → SSH Access:

1. **cPanel** → **Security** → **SSH Access**
2. Klik **"Manage SSH Keys"**
3. Jika ada, klik **"Generate a New Key"**:
   - Key Name: `claude-code-key`
   - Key Password: (kosongkan atau isi password)
   - Key Type: RSA
   - Key Size: 4096
   - Klik **"Generate Key"**

4. **Import Public Key**:
   - Setelah generate, klik **"Manage"** pada key yang baru dibuat
   - Klik **"Authorize"** untuk mengaktifkan key

5. **Download Private Key**:
   - Klik **"View/Download"** pada private key
   - Save as: `claude-code-key` (tanpa extension)

---

### B. Via Hosting Provider Support:

Jika cPanel tidak ada menu SSH, hubungi support:

**Template Email/Ticket:**
```
Subject: Request SSH Access Activation

Hi Support,

Saya memerlukan SSH access untuk server account:
- Username: mtsnkot4
- Domain: pmbm.mtsn2kotamalang.sch.id
- Server IP: 103.30.244.77

Mohon aktivasi SSH access dengan:
1. Port SSH (default 22 atau custom?)
2. SSH key authentication (recommended)
3. Allow access dari IP: [IP Anda - cek di whatismyip.com]

Terima kasih.
```

---

## STEP 3: SETUP SSH KEY DI KOMPUTER WINDOWS

### A. Check Apakah OpenSSH Sudah Terinstall:

Buka **PowerShell** atau **CMD**, jalankan:
```cmd
ssh -V
```

**Jika muncul versi** (misal: `OpenSSH_8.x`):
✅ Sudah terinstall, lanjut ke Step 3B.

**Jika error "command not found"**:
❌ Perlu install OpenSSH.

**Install OpenSSH di Windows 10/11:**
1. **Settings** → **Apps** → **Optional Features**
2. **Add a feature** → Cari **"OpenSSH Client"**
3. Install → Restart PC

---

### B. Generate SSH Key Pair (Di Windows):

```cmd
cd %USERPROFILE%\.ssh
ssh-keygen -t rsa -b 4096 -C "claude-code-ssh" -f id_rsa_ppdb
```

**Prompt yang muncul:**
```
Enter passphrase (empty for no passphrase):
```
→ **Tekan ENTER** (kosongkan password agar Claude Code bisa auto-connect)

**Output:**
```
Your identification has been saved in id_rsa_ppdb
Your public key has been saved in id_rsa_ppdb.pub
```

**Location:**
- Private key: `C:\Users\[YourUsername]\.ssh\id_rsa_ppdb`
- Public key: `C:\Users\[YourUsername]\.ssh\id_rsa_ppdb.pub`

---

### C. Copy Public Key ke Server:

#### **Method 1: Via cPanel File Manager**

1. Buka file: `C:\Users\[YourUsername]\.ssh\id_rsa_ppdb.pub`
2. **Copy semua isi file** (Ctrl+A, Ctrl+C)
3. Login **cPanel** → **File Manager**
4. Navigate ke: `/home/mtsnkot4/.ssh/`
   - Jika folder `.ssh` tidak ada, buat folder baru (klik New Folder)
5. Buka file: `authorized_keys` (atau buat baru jika tidak ada)
6. **Paste** public key di baris baru
7. **Save** file

#### **Method 2: Via cPanel Terminal**

Jika cPanel punya Terminal:
```bash
cd ~/.ssh
nano authorized_keys
```
Paste public key, tekan **Ctrl+X** → **Y** → **Enter** untuk save.

#### **Method 3: Via SSH Copy (Jika SSH Sudah Bisa Pakai Password)**

```cmd
type C:\Users\[YourUsername]\.ssh\id_rsa_ppdb.pub | ssh mtsnkot4@103.30.244.77 "mkdir -p ~/.ssh && cat >> ~/.ssh/authorized_keys"
```

---

### D. Set Permission File `authorized_keys` di Server:

**PENTING!** File permission harus correct agar SSH key bisa berfungsi.

Via cPanel Terminal:
```bash
chmod 700 ~/.ssh
chmod 600 ~/.ssh/authorized_keys
```

Via cPanel File Manager:
- Klik kanan file `.ssh` → **Change Permissions** → Set ke `700`
- Klik kanan file `authorized_keys` → **Change Permissions** → Set ke `600`

---

## STEP 4: SETUP SSH CONFIG DI WINDOWS

Buat file config untuk memudahkan connection.

### A. Create SSH Config File:

```cmd
notepad %USERPROFILE%\.ssh\config
```

### B. Isi Config File:

```
Host ppdb-production
    HostName 103.30.244.77
    User mtsnkot4
    Port 22
    IdentityFile ~/.ssh/id_rsa_ppdb
    StrictHostKeyChecking no
    UserKnownHostsFile /dev/null
    ServerAliveInterval 60
    ServerAliveCountMax 3
```

**Jika SSH Port Bukan 22**, ganti:
```
Port 2222
```
(Atau port yang diberikan hosting provider)

**Save** file (Ctrl+S).

---

## STEP 5: TEST SSH CONNECTION

### A. Test Manual Connection:

```cmd
ssh ppdb-production
```

**Jika berhasil:**
✅ Anda akan masuk ke server shell tanpa diminta password!
```bash
[mtsnkot4@server ~]$
```

**Jika gagal:**

#### **Error: "Permission denied (publickey)"**
→ Public key belum ter-copy dengan benar ke server
→ Ulangi Step 3C (copy public key)
→ Check permission file (Step 3D)

#### **Error: "Connection refused"**
→ SSH service tidak running atau port blocked
→ Hubungi hosting provider

#### **Error: "Connection timed out"**
→ Firewall block IP Anda
→ Hubungi hosting provider untuk whitelist IP

---

### B. Test Command Execution:

```cmd
ssh ppdb-production "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && ls -la"
```

**Jika berhasil:**
✅ Akan tampil list files di folder production
✅ Claude Code sekarang bisa jalankan command!

---

## STEP 6: VERIFY CLAUDE CODE BISA CONNECT

Setelah setup selesai, saya (Claude Code) bisa jalankan:

```bash
ssh ppdb-production "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan --version"
```

Atau upload file:
```bash
scp app/Models/SurveyAnswer.php ppdb-production:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/app/Models/
```

---

## TROUBLESHOOTING

### Issue 1: SSH Port 22 Tidak Tersedia

**Symptom**: `Connection refused` pada port 22

**Check Port SSH Server:**
Hubungi hosting provider atau cek via cPanel → **SSH Access** → lihat port SSH.

**Common Ports:**
- Port 22 (default)
- Port 2222 (shared hosting alternative)
- Port 2200

**Update SSH Config:**
```
Port 2222
```

**Test:**
```cmd
ssh -p 2222 mtsnkot4@103.30.244.77
```

---

### Issue 2: Firewall Block IP

**Symptom**: `Connection timed out`

**Solution:**
1. Cek IP Anda: https://whatismyip.com/
2. Login **cPanel** → **IP Blocker** atau **ConfigServer Security & Firewall**
3. **Whitelist IP** Anda
4. Atau hubungi hosting support untuk whitelist IP

---

### Issue 3: SSH Key Tidak Terdeteksi

**Symptom**: Masih diminta password

**Check:**
```bash
# Di server (via cPanel Terminal):
ls -la ~/.ssh/
cat ~/.ssh/authorized_keys
```

**Verify:**
- File `authorized_keys` harus ada
- Isi file harus match dengan public key Anda
- Permission harus benar: `chmod 600 ~/.ssh/authorized_keys`

---

### Issue 4: Host Key Verification Failed

**Symptom**: `Host key verification failed`

**Quick Fix:**
```cmd
ssh-keygen -R 103.30.244.77
```

Atau tambahkan di SSH config:
```
StrictHostKeyChecking no
```

---

## ALTERNATIVE: Gunakan FTP/SFTP Jika SSH Tidak Tersedia

Jika hosting provider **TIDAK SUPPORT SSH**, alternative:

### Via FileZilla (SFTP):
- Host: `sftp://103.30.244.77`
- Username: `mtsnkot4`
- Port: 22 (atau 21 untuk FTP)
- Logon Type: Key file
- Key file: `C:\Users\[YourUsername]\.ssh\id_rsa_ppdb`

### Via WinSCP (SFTP):
- File protocol: SFTP
- Host: `103.30.244.77`
- Username: `mtsnkot4`
- Advanced → SSH → Authentication → Private key file

---

## COMMON HOSTING PROVIDERS - SSH SUPPORT

### ✅ Support SSH:
- DigitalOcean
- Linode
- AWS Lightsail
- Vultr
- VPS/Dedicated servers
- cPanel with WHM

### ❌ Limited/No SSH:
- Shared hosting murah (<$5/month)
- Hostinger (shared plan)
- Niagahoster (shared plan)
- GoDaddy (shared plan)

**Jika shared hosting Anda tidak support SSH:**
- Upgrade ke VPS plan, ATAU
- Gunakan manual upload via cPanel

---

## VERIFICATION CHECKLIST

Setelah setup, verify dengan checklist ini:

```
[ ] 1. OpenSSH client terinstall di Windows (ssh -V)
[ ] 2. SSH key pair generated (id_rsa_ppdb + id_rsa_ppdb.pub)
[ ] 3. Public key ter-copy ke server (~/.ssh/authorized_keys)
[ ] 4. Permission file correct (600 untuk authorized_keys)
[ ] 5. SSH config file dibuat (~/.ssh/config)
[ ] 6. Test manual connection berhasil (ssh ppdb-production)
[ ] 7. Test command execution berhasil (ssh ppdb-production "ls")
[ ] 8. Claude Code bisa connect (saya test dari sini)
```

---

## QUICK TEST COMMANDS

Setelah setup, test dengan commands ini:

```cmd
# Test 1: Basic connection
ssh ppdb-production "echo 'SSH Connected!'"

# Test 2: Check PHP version
ssh ppdb-production "php -v"

# Test 3: Check Laravel version
ssh ppdb-production "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan --version"

# Test 4: List files
ssh ppdb-production "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && ls -la app/Models/"

# Test 5: Check logs
ssh ppdb-production "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && tail -20 storage/logs/laravel.log"
```

**Jika semua test berhasil:**
✅ SSH setup COMPLETE!
✅ Claude Code bisa jalankan debug commands!

---

## SETELAH SSH BERHASIL

Kirim konfirmasi ke saya:
```
"SSH sudah berhasil! Semua test command berjalan."
```

Saya akan langsung:
1. ✅ Verify files ter-upload dengan benar
2. ✅ Check production logs
3. ✅ Debug survey overwrite bug
4. ✅ Clear cache
5. ✅ Test survey dan verify database

Semua bisa dilakukan otomatis! 🚀

---

## CONTACTS

Jika perlu bantuan hosting provider:
- **Cek email** order/welcome email dari provider
- **Portal Client**: Biasanya ada di WHMCS atau client portal
- **Support**: Email support@[provider].com atau live chat

Info yang perlu siap:
- Username: `mtsnkot4`
- Domain: `pmbm.mtsn2kotamalang.sch.id`
- Server IP: `103.30.244.77`

---

**Dibuat**: 6 Februari 2026, 20:00 WIB
**Estimasi Setup**: 15-30 menit (jika SSH supported)
**Benefit**: Claude Code bisa auto-debug production! 🎯
