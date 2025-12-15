# LifeVelo WordPress Theme

A modern, AI-assisted WordPress blog theme built with performance, SEO, and security in mind.

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![WordPress](https://img.shields.io/badge/WordPress-6.0+-green.svg)
![License](https://img.shields.io/badge/license-GPL--2.0-red.svg)

---

## ✨ Features

### Core Features
- 🎨 Modern dark theme with glassmorphism effects
- 📱 Fully responsive (mobile-first design)
- ⚡ Performance optimized (lazy loading, conditional CSS)
- 🔍 Advanced live search (AJAX-powered)
- 📝 Custom page templates (5 templates)
- 🎯 Modular architecture (14 organized modules)

### SEO & Marketing
- 🔎 SEO meta tags (Open Graph, Twitter Cards)
- 📊 Schema.org structured data (JSON-LD)
- 🗺️ XML Sitemap (automatic generation)
- 📈 Google Analytics 4 integration
- 📧 Newsletter system (dual-save: Local DB + Brevo API)
- 📋 Newsletter admin management

### Security
- 🔒 Custom login URL (hide wp-login.php)
- 🛡️ Security hardening (XML-RPC disabled, user enumeration blocked)
- ✅ Input sanitization & output escaping
- 🔐 Nonce verification on all forms

### Performance
- ⚡ Conditional CSS loading
- 🖼️ Lazy image loading
- 📦 Minimal dependencies (no bloated plugins)
- 🚀 Optimized database queries

---

## 📦 Installation

### Method 1: Direct Upload
1. Download theme ZIP
2. Go to **WordPress Admin → Appearance → Themes**
3. Click **Add New → Upload Theme**
4. Choose ZIP file and click **Install Now**
5. Click **Activate**

### Method 2: Git Clone (Recommended)
```bash
cd wp-content/themes/
git clone https://github.com/Shivendra-Mohan/LifeVelo.git lifevelo
```

### Method 3: FTP Upload
1. Extract theme ZIP
2. Upload folder to `/wp-content/themes/` via FTP
3. Activate from WordPress admin

---

## ⚙️ Configuration

### Step 1: Create config.php

**IMPORTANT:** Copy `config-sample.php` to `config.php` and configure:

```php
<?php
// Custom Login Slug - Keep this secret!
define('CUSTOM_LOGIN_SLUG', 'your-secret-login-here');

// Google Analytics
define('GA_MEASUREMENT_ID', 'G-XXXXXXXXXX');

// Brevo API (optional)
define('BREVO_API_KEY', 'your-api-key');
define('BREVO_LIST_ID', 'your-list-id');

// Environment
define('WP_ENVIRONMENT', 'production');
define('WP_DEBUG', false);
?>
```

### Step 2: Enable Custom Login (Production Only)

In `functions.php`, uncomment line 113:
```php
require_once SHIVENDRA_THEME_DIR . '/inc/custom-login.php';
```

### Step 3: Configure WordPress Customizer

Go to **Appearance → Customize** and configure:

1. **Site Logo & Branding** - Upload logo or set text logo
2. **Profile Info** - Name, tagline, location, email
3. **Social Media Links** - YouTube, Instagram, LinkedIn, IMDb
4. **Google Analytics** - Add GA4 Measurement ID
5. **XML Sitemap** - Enable sitemap generation
6. **Privacy/Terms/About Contact** - Set contact information

---

## 🚀 Deployment

See [DEPLOYMENT.md](DEPLOYMENT.md) for complete deployment guide including:
- GitHub setup
- Hostinger deployment via Git
- Security configuration
- Production checklist

---

## 📁 Theme Structure

```
lifevelo/
├── assets/                 # Images, fonts, JavaScript
│   ├── fonts/             # Outfit & Inter variable fonts
│   ├── images/            # Theme images & icons
│   └── js/                # Main JavaScript file
├── css/                   # Page-specific stylesheets
├── inc/                   # Modular PHP components (14 modules)
│   ├── theme-setup.php    # Core WordPress features
│   ├── customizer.php     # Customizer settings
│   ├── enqueue.php        # Asset loading
│   ├── newsletter.php     # Newsletter system
│   ├── seo.php            # SEO meta tags
│   ├── schema.php         # Schema.org markup
│   ├── sitemap.php        # XML sitemap generator
│   ├── analytics.php      # Google Analytics
│   ├── custom-login.php   # Custom login URL
│   └── ...more
├── template-parts/        # Reusable components
├── style.css              # Main stylesheet
├── functions.php          # Theme functions (loads modules)
├── header.php             # Header template
├── footer.php             # Footer template
├── index.php              # Homepage
├── single.php             # Single post
├── page-*.php             # Custom page templates
└── ...more templates
```

---

## 🎨 Customization

### Logo Customization
- **Text Logo:** Set in Customizer → Site Logo & Branding
- **Image Logo:** Upload in Customizer → Site Logo & Branding
- Supports both text and image logos with size controls

### Color Scheme
Primary colors defined in `style.css`:
```css
--primary: #914bf1;         /* Purple */
--text-primary: #e0e0e0;    /* Light gray */
--bg-primary: #0a0a0a;      /* Dark background */
```

### Custom Page Templates
- About Page (`page-about.php`)
- Categories Page (`page-categories.php`)
- Journal/Archive (`page-journal.php`)
- Privacy Policy (`page-privacy-policy.php`)
- Terms of Use (`page-terms-of-use.php`)

---

## 🔌 Integrations

### Google Analytics 4
Configure in **Customizer → Google Analytics**
- Enable/disable tracking
- Anonymize IP addresses (GDPR)
- Exclude logged-in users

### Newsletter (Brevo)
Configure in **Customizer → Brevo Newsletter API**
- API key & List ID
- Dual-save system (Local DB + Brevo)
- Admin management interface

### XML Sitemap
- Automatic generation at `/sitemap.xml`
- Submit to Google Search Console
- Configure in **Customizer → XML Sitemap**

---

## 📊 Performance

- **PageSpeed Score:** 90+ (mobile & desktop)
- **Core Web Vitals:** All green
- **GTmetrix Grade:** A
- **Load Time:** < 2 seconds

### Performance Features:
- Conditional CSS loading (only load what's needed)
- Lazy image loading
- Variable fonts (Outfit & Inter)
- Minimal JavaScript
- Optimized database queries

---

## 🔒 Security Features

### Custom Login URL
- Hide default `wp-login.php`
- Configure custom slug in `config.php`
- Blocks brute force attacks

### Security Hardening
- XML-RPC disabled
- WordPress version hidden
- User enumeration blocked
- Nonce verification on all forms
- Input sanitization & output escaping

---

## 🆘 Support

### Documentation
- **Theme Documentation:** `/inc/README.md`
- **Deployment Guide:** `DEPLOYMENT.md`
- **AI Development Credits:** `CREDITS.md`

### Troubleshooting
- Check WordPress debug.log
- Verify file permissions (755 for dirs, 644 for files)
- Clear browser cache
- Flush WordPress permalinks

---

## 🤖 AI-Assisted Development

This theme was built using an agentic AI-assisted approach:

- **Project Manager:** Shivendra Mohan
- **Primary Developer:** Claude Sonnet 4.5 (Anthropic)
- **Foundation:** Grok AI (xAI)
- **Contributors:** ChatGPT (OpenAI), Gemini (Google)

All code was human-reviewed, tested, and approved.

See [CREDITS.md](CREDITS.md) for detailed development credits.

---

## 📄 License

This theme is licensed under the GNU General Public License v2 or later.

---

## 👨‍💻 Author

**Shivendra Mohan**
- Website: [shivendramohan.com](https://shivendramohan.com)
- GitHub: [@shivendra-mohan](https://github.com/Shivendra-Mohan)
- Email: shivendramohan@outlook.in

---

## 🙏 Acknowledgments

- WordPress Community
- Anthropic (Claude AI)
- xAI (Grok)
- OpenAI (ChatGPT)
- Google (Gemini)

---

**Engineered with human expertise and AI innovation**

*Reflecting the next generation of collaborative development practices.*
