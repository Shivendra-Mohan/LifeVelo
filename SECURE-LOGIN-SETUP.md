# 🔐 Secure Custom Login Setup for LifeVelo.com

## 📋 Overview

This guide will help you disable `wp-login.php` and set up a secure custom login URL on your production site.

---

## 🎯 What This Does

**Before:**
- Login URL: `https://lifevelo.com/wp-login.php` (everyone knows this)
- Vulnerable to brute force attacks
- Bots constantly trying to hack

**After:**
- Login URL: `https://lifevelo.com/gateway-rx47k` (secret, unpredictable)
- `wp-login.php` returns 404 error
- Brute force attacks blocked ✅

---

## 🚀 Step-by-Step Setup

### Step 1: Choose Your Secret Login Slug

Pick one of these **strong, unpredictable** login slugs (or create your own):

**Recommended Options:**
```
gateway-rx47k    ← Strong (recommended)
portal-m9dx3     ← Strong
access-vk82n     ← Strong
secure-tj64p     ← Strong
admin-zx29w      ← Strong
```

**❌ Avoid These (too predictable):**
```
shivendra-admin  ← Contains your name
lifevelo-login   ← Contains site name
admin            ← Too common
login            ← Too common
wp-admin         ← WordPress default
```

**💡 Pro Tip:** Use a random password generator to create your slug:
```
Example: https://1password.com/password-generator/
Generate 8-12 characters: lowercase + numbers
```

---

### Step 2: Get Your IP Address (Optional)

If you want Hostinger's "WordPress Admin" button to work:

1. Visit: https://whatismyipaddress.com/
2. Copy your **IPv4 Address** (example: `203.45.67.89`)
3. Save it for Step 4

**⚠️ Note:** If you skip this, Hostinger's dashboard button won't work (but more secure).

---

### Step 3: Edit Production Config File

1. Open the file: `PRODUCTION-CONFIG.php`
2. Find line 38:
   ```php
   define('CUSTOM_LOGIN_SLUG', 'gateway-rx47k');
   ```
3. Replace `gateway-rx47k` with your chosen slug:
   ```php
   define('CUSTOM_LOGIN_SLUG', 'portal-m9dx3');  // Your secret slug
   ```

---

### Step 4: Add Your IP (Optional - For Hostinger Button)

In the same file, find line 52:
```php
define('WHITELISTED_IPS', array(
    // 'YOUR_HOME_IP_HERE',
));
```

**Option A: Whitelist Your IP** (Hostinger button will work)
```php
define('WHITELISTED_IPS', array(
    '203.45.67.89',     // Your home IP from Step 2
));
```

**Option B: Maximum Security** (Hostinger button won't work)
```php
define('WHITELISTED_IPS', array());  // Empty = no whitelist
```

---

### Step 5: Deploy to Hostinger

#### Method 1: SSH (Recommended)

```bash
# 1. SSH into Hostinger
ssh u123456789@your-server-ip

# 2. Navigate to theme directory
cd domains/lifevelo.com/public_html/wp-content/themes/shivendra-blog

# 3. Upload the config file (use SFTP or SCP)
# Or create it directly:
nano config.php

# 4. Paste the content from PRODUCTION-CONFIG.php
# (with your custom login slug)

# 5. Save and exit (Ctrl+X, Y, Enter)

# 6. Set correct permissions
chmod 600 config.php
```

#### Method 2: Hostinger File Manager

1. Login to Hostinger dashboard
2. Go to **File Manager**
3. Navigate to: `public_html/wp-content/themes/shivendra-blog/`
4. Click **Upload**
5. Upload `PRODUCTION-CONFIG.php`
6. Rename it to `config.php`
7. Right-click → **Permissions** → Set to `600`

---

### Step 6: Enable Custom Login in Theme

The custom login is controlled by `functions.php` line 163.

**On Hostinger, ensure this line is UNCOMMENTED:**

```php
// In functions.php line 163:
require_once SHIVENDRA_THEME_DIR . '/inc/custom-login.php';  // ✅ Active
```

**Push this change via Git:**

```bash
# On local machine:
cd /Users/shivendra/Local\ Sites/shivendra-blog/app/public/wp-content/themes/shivendra-blog

# Edit functions.php (uncomment line 163)
# Then:
git add functions.php
git commit -m "Enable custom login for production"
git push origin main

# Webhook will auto-deploy to Hostinger
```

---

### Step 7: Flush Permalinks

**Important:** After deployment, flush WordPress permalinks:

1. Login to your site (using OLD wp-login.php for now)
2. Go to: **Settings → Permalinks**
3. Click **Save Changes** (don't change anything, just save)
4. This registers the new login URL

---

### Step 8: Test Your New Login

1. **Logout** from WordPress
2. Try your new login URL:
   ```
   https://lifevelo.com/gateway-rx47k
   ```
   (Replace with your chosen slug)

3. You should see the WordPress login page ✅

4. **Test that wp-login.php is blocked:**
   ```
   https://lifevelo.com/wp-login.php
   ```
   Should show **404 Page Not Found** ✅
   (Unless your IP is whitelisted)

---

## 🔖 Save Your Login URL

**⚠️ CRITICAL:** Bookmark your new login URL securely:

1. **Browser Bookmark:**
   - Add bookmark: `https://lifevelo.com/gateway-rx47k`
   - Name it something generic like "Dashboard"
   - Store in private/encrypted bookmarks folder

2. **Password Manager:**
   - Add to 1Password/Bitwarden/LastPass
   - Store as: "LifeVelo Admin Login"
   - Include: URL, username, password

3. **Backup Location:**
   - Write it down on paper (stored safely)
   - Or save in encrypted note

---

## 🛡️ Security Best Practices

### DO ✅
- Use strong, unpredictable login slug
- Bookmark your login URL securely
- Enable two-factor authentication (2FA plugin)
- Use strong password for WordPress admin
- Keep WordPress and plugins updated
- Monitor login attempts

### DON'T ❌
- Share your login URL publicly
- Use predictable slugs (admin, login, etc.)
- Include your name or site name in slug
- Store login URL in plain text files
- Forget to flush permalinks after setup

---

## 🔧 Troubleshooting

### Issue 1: "404 Not Found" on Custom Login URL

**Solution:**
```
Go to: Settings → Permalinks → Save Changes
This flushes the rewrite rules.
```

### Issue 2: Redirect Loop

**Solution:**
```
1. Disable custom login temporarily
2. Check config.php is properly formatted
3. Clear browser cache
4. Try incognito mode
```

### Issue 3: Can't Login Anywhere

**Emergency Access:**
1. SSH into server
2. Edit `functions.php` line 163:
   ```php
   // require_once SHIVENDRA_THEME_DIR . '/inc/custom-login.php';  // Disabled
   ```
3. Use wp-login.php to access
4. Fix the issue
5. Re-enable custom login

### Issue 4: Forgot Login URL

**Solution:**
```
1. SSH into server
2. View config.php:
   cat wp-content/themes/shivendra-blog/config.php | grep CUSTOM_LOGIN_SLUG
3. You'll see your slug
```

---

## 📝 Example Full Setup

**Your chosen settings:**
```php
// config.php
define('CUSTOM_LOGIN_SLUG', 'portal-m9dx3');
define('WHITELISTED_IPS', array('203.45.67.89'));
```

**Your new login URL:**
```
https://lifevelo.com/portal-m9dx3
```

**Blocked URL:**
```
https://lifevelo.com/wp-login.php  → 404 Error
(Unless you access from IP: 203.45.67.89)
```

---

## 🎯 Next Steps After Setup

1. ✅ Test login with new URL
2. ✅ Verify wp-login.php is blocked
3. ✅ Test Hostinger button (if whitelisted)
4. ✅ Bookmark new login URL securely
5. ✅ Install 2FA plugin (recommended: Wordfence)
6. ✅ Monitor failed login attempts
7. ✅ Delete PRODUCTION-CONFIG.php from local machine

---

## 📞 Support

If you encounter issues:
1. Check troubleshooting section above
2. Review CUSTOM-LOGIN-GUIDE.md
3. Check server error logs in Hostinger

---

**🔒 Remember:** Your custom login URL is your first line of defense. Keep it secret, keep it safe!
