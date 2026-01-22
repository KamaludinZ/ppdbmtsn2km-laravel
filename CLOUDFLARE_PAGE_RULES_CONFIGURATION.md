# CLOUDFLARE PAGE RULES CONFIGURATION
## How to Set Up Cloudflare Page Rules for Your Application

### 🌐 ACCESSING CLOUDFLARE DASHBOARD:

1. Go to [https://dash.cloudflare.com](https://dash.cloudflare.com)
2. Log in to your Cloudflare account
3. Select your domain: `pmbm.mtsn2kotamalang.sch.id`
4. Click on "Rules" in the left sidebar
5. Click on "Page Rules" (under "Create a Custom Rule" section)

### 📝 PAGE RULES TO CREATE:

Click "Create Page Rule" and add these rules in order (most specific first):

#### **Rule 1: Bypass Cache for API Routes**
- **URL Pattern:** `pmbm.mtsn2kotamalang.sch.id/api/*`
- **Settings:**
  - Cache Level: Bypass

#### **Rule 2: Bypass Cache for Livewire Routes** 
- **URL Pattern:** `pmbm.mtsn2kotamalang.sch.id/livewire/*`
- **Settings:**
  - Cache Level: Bypass

#### **Rule 3: Bypass Cache for Login Routes**
- **URL Pattern:** `pmbm.mtsn2kotamalang.sch.id/login*`
- **Settings:**
  - Cache Level: Bypass

#### **Rule 4: Bypass Cache for Admin Routes**
- **URL Pattern:** `pmbm.mtsn2kotamalang.sch.id/admin/*`
- **Settings:**
  - Cache Level: Bypass

#### **Rule 5: Bypass Cache for Panitia Routes**
- **URL Pattern:** `pmbm.mtsn2kotamang.sch.id/panitia/*`
- **Settings:**
  - Cache Level: Bypass

#### **Rule 6: Bypass Cache for Pendaftar Routes**
- **URL Pattern:** `pmbm.mtsn2kotamalang.sch.id/pendaftar/*`
- **Settings:**
  - Cache Level: Bypass

#### **Rule 7: Bypass Cache for Daftar Ulang Routes**
- **URL Pattern:** `pmbm.mtsn2kotamalang.sch.id/daftarulang/*`
- **Settings:**
  - Cache Level: Bypass

#### **Rule 8: Bypass Cache for Logout**
- **URL Pattern:** `pmbm.mtsn2kotamalang.sch.id/logout`
- **Settings:**
  - Cache Level: Bypass

#### **Rule 9: Cache Static CSS Files**
- **URL Pattern:** `pmbm.mtsn2kotamalang.sch.id/*.css`
- **Settings:**
  - Cache Level: Cache Everything
  - Browser Cache TTL: 1 Month

#### **Rule 10: Cache Static JS Files**
- **URL Pattern:** `pmbm.mtsn2kotamalang.sch.id/*.js`
- **Settings:**
  - Cache Level: Cache Everything
  - Browser Cache TTL: 1 Month

#### **Rule 11: Cache Static Image Files**
- **URL Pattern:** `pmbm.mtsn2kotamalang.sch.id/*.(jpg|jpeg|png|gif|webp|svg)`
- **Settings:**
  - Cache Level: Cache Everything
  - Browser Cache TTL: 1 Month

#### **Rule 12: Cache Static Font Files**
- **URL Pattern:** `pmbm.mtsn2kotamalang.sch.id/*.(woff|woff2|ttf|eot)`
- **Settings:**
  - Cache Level: Cache Everything
  - Browser Cache TTL: 1 Month

#### **Rule 13: Cache Static Asset Files**
- **URL Pattern:** `pmbm.mtsn2kotamalang.sch.id/*.(ico|pdf|txt|xml|json|zip|rar|tar|gz)`
- **Settings:**
  - Cache Level: Cache Everything
  - Browser Cache TTL: 1 Month

### 📋 STEP-BY-STEP INSTRUCTIONS:

#### **For Each Rule:**
1. Click "Create Page Rule"
2. Enter the URL Pattern exactly as shown above
3. Click "Add a Setting"
4. Select "Cache Level" from the dropdown
5. Select the appropriate option (either "Bypass" or "Cache Everything")
6. If applicable, add "Browser Cache TTL" setting
7. Click "Save and Deploy"

### 🔄 ORDER MATTERS:
- More specific rules (like API, Livewire, login) should come first
- General static file rules should come last
- The order ensures dynamic content bypasses cache while static assets are cached

### 🧪 TESTING YOUR CONFIGURATION:

After setting up the rules:

1. **Wait 5-10 minutes** for the rules to propagate
2. **Test dynamic routes** (login, admin, etc.) - they should work normally
3. **Check browser network tab** - static files should have CF-Cache-Status: HIT
4. **Verify no 503 errors** occur
5. **Check that CSP violations are resolved**

### ⚠️ TROUBLESHOOTING:

- **If dynamic routes still cache:** Double-check rule order and patterns
- **If static files don't cache:** Verify the static file patterns are correct
- **If issues persist:** Temporarily pause the rules and test individually

### 📊 MONITORING:

- Check Cloudflare Analytics to see cache hit rates
- Monitor your application for any performance improvements
- Watch for any unexpected caching issues

This configuration ensures dynamic content bypasses cache while static assets are properly cached for optimal performance!