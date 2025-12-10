# Deploy WordPress Theme to Hostinger via Git

## Overview

Deploy your theme from GitHub to Hostinger using different methods based on your hosting plan.

---

## Method 1: SSH + Git Clone (Most Professional)

**Requirements:** SSH access (available on most Hostinger plans)

### Step 1: Enable SSH Access in Hostinger

1. Login to Hostinger dashboard
2. Go to **Advanced → SSH Access**
3. Enable SSH
4. Note down:
   - Host: `ssh.hostinger.com` (or your specific host)
   - Port: `65002` (default) or your assigned port
   - Username: Your SSH username
   - Password: Your SSH password

### Step 2: Connect via SSH

**On Mac/Linux:**
```bash
ssh username@ssh.hostinger.com -p 65002
```

**On Windows:**
- Use PuTTY or Windows Terminal
- Host: `ssh.hostinger.com`
- Port: `65002`

### Step 3: Navigate to Themes Directory

```bash
cd domains/yourdomain.com/public_html/wp-content/themes/
```

Or:
```bash
cd public_html/wp-content/themes/
```

### Step 4: Clone Your Repository

```bash
# Remove old theme folder if exists
rm -rf shivendra-blog

# Clone from GitHub
git clone https://github.com/Shivendra-Mohan/LifeVelo.git shivendra-blog

# Enter the directory
cd shivendra-blog
```

### Step 5: Create Production config.php

```bash
# Create config.php from sample
cp config-sample.php config.php

# Edit config.php with your settings
nano config.php
```

**Add your production settings:**
```php
// Custom login slug
define('CUSTOM_LOGIN_SLUG', 'your-secret-slug-2024');

// Your IP (find at: https://whatismyipaddress.com/)
define('WHITELISTED_IPS', array(
    'YOUR_REAL_IP_HERE',
));

// Brevo API
define('BREVO_API_KEY', 'your-actual-key');
define('BREVO_LIST_ID', 'your-actual-list-id');

// Google Analytics
define('GA_MEASUREMENT_ID', 'G-XXXXXXXXXX');
```

**Save:** Press `Ctrl+X`, then `Y`, then `Enter`

### Step 6: Set Correct Permissions

```bash
# Set file permissions
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;

# Make sure config.php is readable
chmod 644 config.php
```

### Step 7: Activate Theme

1. Go to WordPress Admin: `yourdomain.com/wp-admin`
2. **Appearance → Themes**
3. Activate "Shivendra Blog"

### Step 8: Enable Custom Login

**Edit functions.php via SSH or File Manager:**

```bash
nano functions.php
```

**Uncomment line 163:**
```php
require_once SHIVENDRA_THEME_DIR . '/inc/custom-login.php';
```

### Step 9: Flush Permalinks

1. Login to WordPress admin
2. **Settings → Permalinks**
3. Click **Save Changes**

### Step 10: Test Everything

Test checklist in Step 6 of PRODUCTION-DEPLOYMENT.md

---

## Method 2: GitHub + Manual Download (Simple, No SSH)

**For those without SSH access**

### Step 1: Download from GitHub

**Option A: Download ZIP**
1. Go to: https://github.com/Shivendra-Mohan/LifeVelo
2. Click green **"Code"** button
3. Select **"Download ZIP"**
4. Extract on your computer

**Option B: Download via Git (if you have Git locally)**
```bash
cd ~/Desktop
git clone https://github.com/Shivendra-Mohan/LifeVelo.git shivendra-blog
cd shivendra-blog
```

### Step 2: Create config.php

1. Copy `config-sample.php` → `config.php`
2. Edit `config.php` with your production settings
3. Save the file

### Step 3: Upload to Hostinger

**Via Hostinger File Manager:**

1. Login to Hostinger
2. Go to **File Manager**
3. Navigate to: `public_html/wp-content/themes/`
4. Delete old `shivendra-blog` folder (if exists)
5. Click **Upload**
6. Upload the entire `shivendra-blog` folder
   - Or upload as ZIP and extract

**Via FTP (FileZilla, Cyberduck, etc.):**
```
Host: ftp.yourdomain.com
Username: Your FTP username
Password: Your FTP password
Port: 21

Upload to: /public_html/wp-content/themes/
```

### Step 4: Enable Custom Login

Via File Manager, edit `functions.php` line 163:
```php
require_once SHIVENDRA_THEME_DIR . '/inc/custom-login.php';
```

### Step 5: Activate & Test

Same as Method 1, Steps 7-10

---

## Method 3: Git Pull to Update (After Initial Deployment)

**Once deployed via Method 1, use this for updates:**

### When You Make Changes Locally:

```bash
# On your local machine
cd /Users/shivendra/Local\ Sites/shivendra-blog/app/public/wp-content/themes/shivendra-blog

git add .
git commit -m "Update: describe your changes"
git push origin main
```

### Deploy Updates to Hostinger:

```bash
# SSH into Hostinger
ssh username@ssh.hostinger.com -p 65002

# Go to theme directory
cd public_html/wp-content/themes/shivendra-blog

# Pull latest changes
git pull origin main

# Done! Changes are live
```

---

## Method 4: Automated Deployment with GitHub Actions (Advanced)

**Set up auto-deployment on every push to main**

### Step 1: Create GitHub Action

In your repository, create: `.github/workflows/deploy.yml`

```yaml
name: Deploy to Hostinger

on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest

    steps:
    - name: Deploy via SSH
      uses: appleboy/ssh-action@master
      with:
        host: ${{ secrets.HOST }}
        username: ${{ secrets.USERNAME }}
        password: ${{ secrets.PASSWORD }}
        port: ${{ secrets.PORT }}
        script: |
          cd public_html/wp-content/themes/shivendra-blog
          git pull origin main
          echo "Deployment complete!"
```

### Step 2: Add GitHub Secrets

1. Go to: Repository → Settings → Secrets and variables → Actions
2. Add secrets:
   - `HOST`: `ssh.hostinger.com`
   - `USERNAME`: Your SSH username
   - `PASSWORD`: Your SSH password
   - `PORT`: `65002` (or your port)

### Step 3: Push and Auto-Deploy

Now whenever you push to `main`, it auto-deploys to Hostinger! 🚀

---

## Quick Comparison

| Method | Difficulty | Speed | Best For |
|--------|-----------|-------|----------|
| SSH + Git Clone | Medium | Fast | Developers, regular updates |
| Manual Download | Easy | Slow | One-time setup, no SSH |
| Git Pull Updates | Easy | Very Fast | After initial setup |
| GitHub Actions | Hard | Automated | Advanced users, CI/CD |

---

## Recommended Approach

**For You (First Time):**

1. **Initial Deployment:** Use **Method 2** (Manual Download)
   - Simple, no SSH needed
   - Good for testing

2. **After Testing:** Switch to **Method 1** (SSH + Git)
   - Enables easy updates
   - Professional workflow

3. **For Regular Updates:** Use **Method 3** (Git Pull)
   - Quick updates
   - No re-upload needed

---

## Troubleshooting

### Problem: Permission Denied (SSH)

**Fix:**
```bash
# On server, fix permissions
chmod 644 config.php
chmod 755 public_html/wp-content/themes/shivendra-blog
```

### Problem: config.php Not Found

**Fix:**
- Create it from config-sample.php
- Make sure it's in theme root directory

### Problem: Git Command Not Found (SSH)

**Solution:**
- Hostinger may not have Git on all plans
- Use Method 2 (Manual Upload) instead

### Problem: Theme Not Showing After Upload

**Fix:**
1. Check folder name: Must be `shivendra-blog`
2. Check location: Must be in `wp-content/themes/`
3. Refresh WordPress: Appearance → Themes

---

## Security Checklist After Deployment

- [ ] config.php created with real values
- [ ] Custom login slug is strong
- [ ] IP whitelist configured
- [ ] WP_DEBUG set to false
- [ ] Permalinks flushed
- [ ] Custom login tested
- [ ] Hostinger button tested
- [ ] SSL (HTTPS) enabled

---

## Post-Deployment Commands

**Check Git status on server:**
```bash
cd public_html/wp-content/themes/shivendra-blog
git status
git log --oneline -5
```

**Pull latest changes:**
```bash
git pull origin main
```

**Reset to specific commit:**
```bash
git log --oneline
git reset --hard COMMIT_HASH
```

**See what changed:**
```bash
git diff HEAD~1
```

---

## Need Help?

See also:
- [PRODUCTION-DEPLOYMENT.md](PRODUCTION-DEPLOYMENT.md) - Complete setup guide
- [CUSTOM-LOGIN-GUIDE.md](CUSTOM-LOGIN-GUIDE.md) - Login setup details
- [CACHING-README.md](CACHING-README.md) - Caching information

---

**Choose the method that works best for you! Method 2 is easiest for beginners. 👍**
