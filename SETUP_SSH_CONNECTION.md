# Setup SSH Connection untuk Production Server

## CARA 1: Test SSH Connection Sederhana

### Via Command Prompt (CMD):
```cmd
ssh mtsnkot4@103.30.244.77
```

Atau dengan password langsung (jika diminta):
```cmd
ssh mtsnkot4@103.30.244.77 -p 22
```

---

## CARA 2: Setup SSH Config di Windows

### Step 1: Check apakah SSH sudah terinstall
```cmd
ssh -V
```

Jika muncul versi (misal: `OpenSSH_8.x`), berarti sudah terinstall.

### Step 2: Buat SSH Config File

Lokasi: `C:\Users\[Username]\.ssh\config`

```
Host ppdb-production
    HostName 103.30.244.77
    User mtsnkot4
    Port 22
    IdentityFile ~/.ssh/id_rsa
```

Setelah itu, koneksi bisa lebih mudah:
```cmd
ssh ppdb-production
```

---

## CARA 3: Setup SSH Key (Recommended)

### Step 1: Generate SSH Key (jika belum punya)
```cmd
ssh-keygen -t rsa -b 4096 -C "your_email@example.com"
```

Tekan Enter untuk default location: `C:\Users\[Username]\.ssh\id_rsa`

### Step 2: Copy Public Key ke Server
```cmd
type C:\Users\[Username]\.ssh\id_rsa.pub | ssh mtsnkot4@103.30.244.77 "cat >> ~/.ssh/authorized_keys"
```

Atau manual:
1. Buka file: `C:\Users\[Username]\.ssh\id_rsa.pub`
2. Copy isinya
3. Login ke server via cPanel Terminal
4. Paste ke file: `~/.ssh/authorized_keys`

---

## TROUBLESHOOTING: Connection Refused / Timeout

### Issue 1: Port 22 Blocked oleh Firewall

**Check apakah port 22 terbuka:**
```cmd
telnet 103.30.244.77 22
```

Jika gagal, mungkin:
- Firewall server block port 22
- Server SSH service tidak running
- ISP block port 22

**Solusi**: Gunakan port alternatif (jika server support), misal port 2222:
```cmd
ssh mtsnkot4@103.30.244.77 -p 2222
```

---

### Issue 2: SSH Service di Server Tidak Running

**Check via cPanel:**
1. Login cPanel
2. Terminal → jalankan:
   ```bash
   service ssh status
   # atau
   systemctl status sshd
   ```

Jika tidak running:
```bash
sudo service ssh start
# atau
sudo systemctl start sshd
```

---

### Issue 3: Server Firewall Block IP Anda

Jika server punya CSF/Firewall, mungkin IP Anda di-block.

**Solusi**:
1. Login cPanel
2. ConfigServer Security & Firewall (CSF)
3. Whitelist IP Anda

Atau hubungi hosting provider untuk unblock IP.

---

## CARA 4: Alternative - Gunakan PuTTY

Download PuTTY: https://www.putty.org/

**Setup:**
1. Host Name: `103.30.244.77`
2. Port: `22`
3. Connection type: `SSH`
4. Click "Open"
5. Login: `mtsnkot4`
6. Password: [masukkan password]

---

## CARA 5: Alternative - Gunakan Git Bash

Jika sudah install Git for Windows, bisa gunakan Git Bash:

```bash
# Buka Git Bash
ssh mtsnkot4@103.30.244.77
```

---

## CARA 6: Via Windows Subsystem for Linux (WSL)

Jika punya WSL:
```bash
wsl
ssh mtsnkot4@103.30.244.77
```

---

## QUICK TEST: Apakah SSH Connection Bisa?

Jalankan command ini di CMD/PowerShell:
```cmd
ping 103.30.244.77
```

**Jika ping berhasil:**
✅ Server online, tapi SSH port mungkin blocked

**Jika ping gagal:**
❌ Server down atau IP salah

---

## UNTUK CLAUDE CODE: Test SSH Connection

Setelah SSH bisa connect, saya bisa jalankan command seperti ini:
```bash
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && ls -la"
```

Tapi perlu setup SSH key dulu agar tidak perlu input password setiap kali.
