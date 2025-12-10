# Deployment Guide - LifeVelo WordPress Theme

## 🚀 Hostinger Deployment via Git

### Prerequisites
- Hostinger account with SSH/Git access
- GitHub account (public or private repo)
- SSH key setup on Hostinger

---

## 📋 Step-by-Step Deployment

### **Step 1: Prepare GitHub Repository**

1. **Create GitHub Repo:**
   ```bash
   # On your local machine
   cd /path/to/theme/shivendra-blog
   git init
   git add .
   git commit -m "Initial commit - WordPress theme"
   ```

2. **Create GitHub Repository:**
   - Go to https://github.com/new
   - Name: `lifevelo-wp-theme` (or any name)
   - Visibility: **Private** (recommended for security)
   - Don't initialize with README (you already have files)

3. **Push to GitHub:**
   ```bash
   git remote add origin https://github.com/YOUR-USERNAME/lifevelo-wp-theme.git
   git branch -M main
   git push -u origin main
   ```

---

### **Step 2: Hostinger Setup**

1. **Enable SSH Access:**
   - Login to Hostinger
   - Go to **Advanced → SSH Access**
   - Enable SSH
   - Note down SSH credentials

2. **Connect via SSH:**
   ```bash
   ssh username@your-domain.com -p PORT
   # Enter password when prompted
   ```

3. **Navigate to WordPress Theme Directory:**
   ```bash
   cd public_html/wp-content/themes/
   ```

---

### **Step 3: Clone Theme from GitHub**

1. **Clone Repository:**
   ```bash
   # If public repo:
   git clone https://github.com/YOUR-USERNAME/lifevelo-wp-theme.git lifevelo

   # If private repo (requires personal access token):
   git clone https://<TOKEN>@github.com/YOUR-USERNAME/lifevelo-wp-theme.git lifevelo
   ```

2. **Set Permissions:**
   ```bash
   chmod -R 755 lifevelo/
   ```

---

### **Step 4: Configure Production Settings**

1. **Create config.php (NOT in Git):**
   ```bash
   cd lifevelo
   cp config-sample.php config.php
   nano config.php  # or use vim, File Manager, etc.
   ```

2. **Edit config.php with Production Values:**
   ```php
   <?php
   // Custom Login Slug - KEEP THIS SECRET!
   define('CUSTOM_LOGIN_SLUG', 'your-secret-login-2024');

   // Google Analytics
   define('GA_MEASUREMENT_ID', 'G-XXXXXXXXXX');

   // Brevo API (if using)
   define('BREVO_API_KEY', 'your-api-key');
   define('BREVO_LIST_ID', 'your-list-id');

   // Environment
   define('WP_ENVIRONMENT', 'production');
   define('WP_DEBUG', false);
   ?>
   ```

3. **Load config.php in functions.php:**
   ```bash
   # Add at the top of functions.php (after ABSPATH check)
   nano inc/theme-setup.php
   ```

   Add this line:
   ```php
   // Load config if exists
   if (file_exists(SHIVENDRA_THEME_DIR . '/config.php')) {
       require_once SHIVENDRA_THEME_DIR . '/config.php';
   }
   ```

---

### **Step 5: Enable Custom Login (Production)**

1. **Uncomment custom-login.php:**
   ```bash
   nano functions.php
   ```

2. **Find line ~113 and uncomment:**
   ```php
   // Before:
   // require_once SHIVENDRA_THEME_DIR . '/inc/custom-login.php';

   // After:
   require_once SHIVENDRA_THEME_DIR . '/inc/custom-login.php';
   ```

3. **Test Custom Login:**
   ```
   Visit: https://lifevelo.com/your-secret-login-2024
   ```

---

### **Step 6: Activate Theme**

1. **Login to WordPress Admin:**
   ```
   https://lifevelo.com/wp-admin
   (or your custom login URL)
   ```

2. **Activate Theme:**
   - Go to **Appearance → Themes**
   - Find "Shivendra's Blog" (or "LifeVelo")
   - Click **Activate**

3. **Configure Customizer:**
   - **Appearance → Customize**
   - **Site Logo & Branding** → Upload logo or set text
   - **Profile Info** → Update details
   - **Social Media Links** → Add URLs
   - **Google Analytics** → Add GA4 ID
   - **XML Sitemap** → Enable
   - Click **Publish**

---

### **Step 7: Upload Media Files (if any)**

Media files are not in Git (too large). Upload via:

**Option A: Hostinger File Manager:**
1. Go to **Files → File Manager**
2. Navigate to `public_html/wp-content/uploads/`
3. Upload your local `uploads/` folder

**Option B: FTP/SFTP:**
```bash
# From local machine
scp -r -P PORT uploads/* username@domain.com:~/public_html/wp-content/uploads/
```

---

### **Step 8: Security Checklist**

- [ ] Custom login slug set and tested
- [ ] wp-login.php blocked (test by visiting it directly)
- [ ] WP_DEBUG set to false
- [ ] config.php created (not in Git)
- [ ] Strong admin password
- [ ] SSL certificate enabled (HTTPS)
- [ ] File permissions correct (755 for directories, 644 for files)
- [ ] Remove any test/demo content

---

### **Step 9: Submit Sitemap to Search Engines**

1. **Google Search Console:**
   - https://search.google.com/search-console
   - Add property: https://lifevelo.com
   - Submit sitemap: https://lifevelo.com/sitemap.xml

2. **Bing Webmaster Tools:**
   - https://www.bing.com/webmasters
   - Add site
   - Submit sitemap

---

## 🔄 Future Updates

### **Deploying Updates:**

```bash
# On local machine
git add .
git commit -m "Update: description of changes"
git push origin main

# On Hostinger server (via SSH)
cd public_html/wp-content/themes/lifevelo
git pull origin main
```

---

## 🔒 Security Best Practices

### **What's NOT in Git (Protected):**
✅ config.php (contains secrets)
✅ Custom login slug (defined in config.php)
✅ API keys (defined in config.php)
✅ .env files
✅ Backup files
✅ User uploads (media files)

### **What's in Git (Safe):**
✅ Theme code
✅ CSS/JS files
✅ Template files
✅ Default images (theme assets)
✅ config-sample.php (template only)

---

## 🆘 Troubleshooting

### **Issue: Can't access custom login URL**
```bash
# SSH into server
cd public_html
wp rewrite flush --allow-root
# Or visit: yourdomain.com/?p=1 to trigger rewrite flush
```

### **Issue: Theme not showing in WordPress**
```bash
# Check folder name and location
ls -la public_html/wp-content/themes/
# Should see 'lifevelo' folder
```

### **Issue: Git pull fails**
```bash
# Reset to remote state
git fetch origin
git reset --hard origin/main
```

---

## 📝 Important URLs to Save

- **Admin Dashboard:** https://lifevelo.com/wp-admin
- **Custom Login:** https://lifevelo.com/YOUR-SECRET-SLUG
- **Sitemap:** https://lifevelo.com/sitemap.xml
- **Newsletter Admin:** https://lifevelo.com/wp-admin/admin.php?page=newsletter-subscribers

---

## 🎯 Post-Deployment Tasks

1. Test all features:
   - [ ] Homepage loads
   - [ ] Blog posts display
   - [ ] Newsletter subscription works
   - [ ] Live search functions
   - [ ] Contact forms work
   - [ ] All pages accessible

2. Performance check:
   - [ ] Run Google PageSpeed Insights
   - [ ] Check mobile responsiveness
   - [ ] Test loading speed

3. SEO verification:
   - [ ] Check meta tags in page source
   - [ ] Verify Schema.org markup
   - [ ] Test sitemap accessibility
   - [ ] Submit to search engines

---

**Deployment completed!** 🎉

For support or questions, refer to the theme documentation in `/inc/README.md`.
