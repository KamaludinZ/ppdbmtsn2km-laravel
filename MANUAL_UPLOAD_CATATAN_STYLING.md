# MANUAL UPLOAD - FIX CATATAN STYLING

## 🚨 Status

**SSH Connection Issue:** The production server (103.30.244.77) is immediately closing SSH connections. This needs to be resolved before deployment can proceed.

**Server Status:**
- ✅ Server is reachable (ping successful)
- ❌ SSH connection immediately closed
- ❌ SCP upload failed

## 🔍 Possible Causes

1. **SSH Server Temporarily Down/Restarting**
2. **IP Blocking:** Server may be blocking connections due to too many attempts
3. **Authentication Issue:** SSH key or credentials may have changed
4. **Firewall Rule Change:** New security policy may be blocking connections
5. **Server Maintenance:** Temporary maintenance window

## 🛠️ Troubleshooting Steps

### Step 1: Check SSH Service Status
From the server console or control panel:
```bash
systemctl status sshd
# or
service ssh status
```

### Step 2: Check SSH Logs
```bash
tail -100 /var/log/auth.log
# or
tail -100 /var/log/secure
```

### Step 3: Verify SSH Configuration
```bash
cat /etc/ssh/sshd_config | grep -i "allowusers\|denyusers\|permitrootlogin"
```

### Step 4: Check Fail2Ban or IP Blocking
```bash
fail2ban-client status sshd
iptables -L -n | grep 103.30.244.77
```

## 📦 Files to Upload (When SSH is Available)

### File 1: detail-pendaftar.blade.php
**Local Path:**
```
C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\detail-pendaftar.blade.php
```

**Remote Path:**
```
/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
```

**SCP Command:**
```bash
scp "C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\detail-pendaftar.blade.php" mtsnkot4@103.30.244.77:/home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/
```

## 🎨 Changes Made

### Catatan Box Styling

**BEFORE (Not Visible):**
```css
- Background: bg-white bg-opacity-20 (semi-transparent white)
- Text: text-white (white on white = invisible!)
- Border: border-white border-opacity-30 (faint)
- Button: bg-white bg-opacity-30 (faint)
- Max Width: max-w-xs (320px)
```

**AFTER (High Contrast):**
```css
- Background: bg-yellow-50 (solid light yellow)
- Text: text-gray-800 (dark gray - clearly visible!)
- Border: border-2 border-yellow-400 (solid 2px yellow)
- Button: bg-gradient-to-r from-blue-500 to-blue-600 (solid blue gradient)
- Max Width: max-w-md (448px)
- Icons: Warning icon (yellow) for filled, Info icon (gray) for empty
```

### Visual Improvements:
1. ✅ **High Contrast:** Yellow background + dark text = excellent readability
2. ✅ **Clear Icons:** Warning icon for catatan, info icon for empty state
3. ✅ **Prominent Button:** Solid blue gradient with hover effect
4. ✅ **Defined Border:** 2px solid yellow for emphasis
5. ✅ **Better Layout:** Wider max-width for better readability

## 📋 Post-Upload Commands

After successfully uploading the file, run:

```bash
ssh mtsnkot4@103.30.244.77 "cd /home/mtsnkot4/pmbm.mtsn2kotamalang.sch.id && php artisan view:clear"
```

## 🧪 Testing After Upload

1. Login as admin: `/admin/login`
2. Navigate to: `/admin/pendaftar/{id}`
3. Set status to: `perbaiki_data`, `lengkapi_data`, or `data_ditolak`
4. Verify catatan box appearance:
   - ✅ Yellow background is clearly visible
   - ✅ Dark text is easy to read
   - ✅ Yellow border is prominent
   - ✅ Blue button is solid and clear
   - ✅ Warning/info icon is appropriate
5. Click "Edit Catatan" button
6. Verify modal opens and saves correctly

## 🔄 Alternative Upload Methods

### Method 1: cPanel File Manager
1. Login to cPanel
2. Navigate to File Manager
3. Go to: `pmbm.mtsn2kotamalang.sch.id/resources/views/livewire/admin/`
4. Upload `detail-pendaftar.blade.php`
5. Overwrite existing file
6. Run clear cache via terminal or PHP script

### Method 2: FTP/SFTP Client (FileZilla)
1. Open FileZilla
2. Connect to: `103.30.244.77` with credentials
3. Navigate to remote path
4. Upload file
5. Clear cache via SSH or cPanel terminal

### Method 3: Web-based SSH Terminal
1. Login to hosting control panel
2. Open web-based terminal/SSH
3. Use `scp` or `wget` to transfer file
4. Clear cache

## 📝 Code Changes Summary

**Location:** `resources/views/livewire/admin/detail-pendaftar.blade.php`
**Lines:** 119-160

**Key Changes:**
```blade
<!-- OLD -->
<div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg px-4 py-3 border border-white border-opacity-30 shadow-md max-w-xs">
    <p class="text-white font-medium leading-relaxed mb-2">{{ $catatan_verifikasi }}</p>
    <button class="w-full bg-white bg-opacity-30 hover:bg-opacity-40 text-white...">

<!-- NEW -->
<div class="bg-yellow-50 rounded-lg px-4 py-3 border-2 border-yellow-400 shadow-lg">
    <div class="mb-3">
        <div class="flex items-start gap-2">
            <svg class="w-5 h-5 text-yellow-600..."><!-- Warning icon --></svg>
            <p class="text-gray-800 text-sm font-medium leading-relaxed flex-1">{{ $catatan_verifikasi }}</p>
        </div>
    </div>
    <button class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white...">
```

## ⏭️ Next Steps

1. **Wait 5-10 minutes** and retry SSH connection (server may be temporarily busy)
2. **Contact hosting provider** if issue persists:
   - Check if SSH service is running
   - Verify no IP blocking is in place
   - Confirm SSH access is enabled
3. **Use alternative upload method** (cPanel/FTP) if SSH unavailable
4. **Clear cache** after successful upload
5. **Test the changes** on production

## 📞 Contact Points

- Hosting Provider: Check if they're performing maintenance
- Server Administrator: Verify SSH access permissions
- Network Admin: Check firewall rules

---
**Created:** 6 Februari 2026
**Issue:** SSH connection closed by server
**Fix Status:** Ready for upload when SSH is available
**Impact:** Visual improvement for catatan display
