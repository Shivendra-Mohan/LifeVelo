# Theme Modules Documentation

This directory contains modular components for Shivendra's Blog WordPress theme.

## Directory Structure

```
inc/
├── README.md             (This file - documentation)
├── ajax-handlers.php     (AJAX request handlers)
├── custom-login.php      (Custom login URL security)
├── customizer.php        (WordPress Customizer settings)
├── enqueue.php           (CSS/JS asset management)
├── export.php            (CSV export functionality)
├── helpers.php           (Utility and helper functions)
├── newsletter.php        (Newsletter subscription system)
├── newsletter-admin.php  (Newsletter admin management page)
├── schema.php            (Schema.org structured data)
├── security.php          (Security hardening)
├── seo.php              (SEO meta tags)
└── theme-setup.php      (Core WordPress theme setup)
```

## Module Descriptions

### 1. **theme-setup.php** (1.7 KB)
**Purpose:** Core WordPress theme features and support

**Functions:**
- `shivendras_blog_setup()` - Main theme setup function

**Features:**
- Post thumbnails support
- Navigation menus (Primary & Footer)
- Custom image sizes (blog-medium: 600x400, blog-large: 1200x600)
- HTML5 support (search, comments, gallery, captions)
- Classic editor support
- Emoji script removal (performance)

**Hook:** `after_setup_theme`

---

### 2. **customizer.php** (9.4 KB)
**Purpose:** WordPress Customizer options and settings

**Functions:**
- `shivendras_blog_customize_register($wp_customize)` - Register all customizer settings

**Sections:**
1. Profile Info (name, tagline, location, email, image)
2. Social Media Links (YouTube, Instagram, LinkedIn, IMDb)
3. Privacy Policy Contact (email, Discord link)
4. Terms of Use Contact (email, Discord link)
5. About Page Contact (email, Discord link)
6. Brevo Newsletter API (API key, List ID)

**Hook:** `customize_register`

---

### 3. **enqueue.php** (3.0 KB)
**Purpose:** Load CSS and JavaScript files

**Functions:**
- `shivendras_blog_styles()` - Enqueue styles and scripts

**Features:**
- Main theme stylesheet
- Conditional CSS loading:
  - `categories.css` (Categories page)
  - `journal.css` (Journal page)
  - `category-archive.css` (Category archives)
  - `privacy-policy.css` (Privacy Policy page)
  - `terms-of-use.css` (Terms of Use page)
  - `about.css` (About page)
- Main JavaScript file with AJAX localization
- Custom dropdown menu walker

**Hook:** `wp_enqueue_scripts`

---

### 4. **newsletter.php** (6.5 KB)
**Purpose:** Newsletter subscription system with dual-save approach

**Functions:**
- `shivendras_blog_create_newsletter_table()` - Create subscribers database table
- `shivendras_blog_newsletter_subscribe()` - Handle subscription requests
- `shivendras_blog_add_to_brevo($email, $api_key, $list_id, $name)` - Sync to Brevo API

**Features:**
- **Dual Save:** Saves to both local WordPress database AND Brevo (if configured)
- Database table: `wp_newsletter_subscribers` (id, email, subscribed_date)
- AJAX handlers for form submissions
- Nonce security verification
- Email validation
- Duplicate checking
- Error logging for Brevo API failures

**Hooks:**
- `after_switch_theme` (create table)
- `wp_ajax_newsletter_subscribe` (logged-in users)
- `wp_ajax_nopriv_newsletter_subscribe` (guests)

---

### 5. **ajax-handlers.php** (1.6 KB)
**Purpose:** Handle AJAX requests

**Functions:**
- `shivendras_blog_live_search()` - Live search handler

**Features:**
- Real-time search with keyword sanitization
- Returns top 5 relevant posts
- JSON response with title, URL, excerpt, date, category
- Registered for both logged-in and guest users

**Hooks:**
- `wp_ajax_live_search`
- `wp_ajax_nopriv_live_search`

---

### 6. **helpers.php** (4.8 KB)
**Purpose:** Utility and helper functions

**Functions:**
- `shivendra_reading_time($post_id)` - Calculate reading time based on word count
- `shivendra_custom_comment($comment, $args, $depth)` - Custom comment display template
- `shivendra_featured_image_notice_block_editor()` - Show recommended image size in editor

**Features:**
- Reading time: 200 words per minute calculation
- Custom comment layout with author name, date, reply link
- Block editor notice for featured images (recommended: 1200x600px)

**Hook:** `admin_footer` (featured image notice)

---

### 7. **security.php** (1.7 KB)
**Purpose:** Security hardening and attack prevention

**Functions:**
- `shivendra_disable_x_pingback($headers)` - Remove X-Pingback header
- `shivendra_remove_version()` - Hide WordPress version
- `shivendra_block_user_enumeration()` - Block username enumeration via `?author=N`

**Features:**
- XML-RPC disabled (prevents brute force attacks)
- Version information hidden (prevents targeted attacks)
- User enumeration blocked (redirects to homepage)

**Hooks:**
- `xmlrpc_enabled` (disable filter)
- `wp_headers` (remove pingback)
- `the_generator` (hide version)
- `template_redirect` (block enumeration)

---

### 8. **seo.php** (5.0 KB)
**Purpose:** Generate SEO meta tags for better search visibility

**Functions:**
- `shivendra_seo_meta_tags()` - Generate dynamic SEO meta tags

**Features:**
- **Basic SEO:** description, author, canonical URL
- **Open Graph:** Facebook, WhatsApp, LinkedIn preview
- **Twitter Cards:** Large image cards for Twitter
- **Context-aware:** Different tags for:
  - Single posts (article type, dates, categories)
  - Pages (excerpt or content snippet)
  - Category archives (category description)
  - Search results (query-specific)
  - 404 pages (helpful message)
  - Homepage (site tagline)

**Called:** Directly in [header.php:9](../header.php#L9)

---

### 9. **schema.php** (7.0 KB)
**Purpose:** Generate Schema.org JSON-LD structured data for Google Rich Results

**Functions:**
- `shivendra_schema_markup()` - Generate structured data

**Schema Types:**
1. **WebSite** (all pages): Site info, search action
2. **Person** (all pages): Author info, social profiles
3. **BlogPosting** (blog posts): Article info, images, word count, dates
4. **BreadcrumbList** (blog posts): Navigation breadcrumbs
5. **CollectionPage** (categories): Category collection info

**Benefits:**
- Rich snippets in Google search
- Featured image previews
- Author information display
- Publication dates
- Breadcrumb navigation
- Knowledge panel eligibility

**Called:** Directly in [header.php:12](../header.php#L12)

---

### 10. **export.php** (1.9 KB)
**Purpose:** Export newsletter subscribers to CSV

**Functions:**
- `shivendra_export_subscribers()` - Export subscribers to CSV file

**Features:**
- Admin-only access (requires 'manage_options' capability)
- CSV format: Email, Subscribed Date
- Filename: `newsletter-subscribers-YYYY-MM-DD.csv`
- Ordered by newest subscribers first
- **Usage:** Visit `yoursite.com/wp-admin/?export_subscribers=1`

**Hook:** `admin_init`

---

### 11. **newsletter-admin.php** (6.2 KB)
**Purpose:** Admin dashboard page to manage newsletter subscribers

**Functions:**
- `shivendra_add_newsletter_admin_menu()` - Register admin menu
- `shivendra_handle_newsletter_deletions()` - Process delete actions
- `shivendra_newsletter_admin_page()` - Render admin interface

**Features:**
- Admin menu item: "Newsletter" with email icon
- View all subscribers in table format
- Individual delete (with nonce verification)
- Bulk delete (select multiple subscribers)
- Delete all subscribers (with confirmation)
- Total subscriber count display
- Success/error message notifications
- Link to CSV export functionality
- **Usage:** WordPress Admin → Newsletter menu

**Hooks:**
- `admin_menu` (register menu)
- `admin_init` (handle deletions)

---

### 12. **custom-login.php** (5.8 KB)
**Purpose:** Custom login URL security to hide wp-login.php

**Functions:**
- `shivendra_custom_login_url()` - Handle custom login URL
- `shivendra_block_default_login()` - Block direct wp-login.php access
- `shivendra_block_wp_admin()` - Block wp-admin for non-logged users
- `shivendra_login_url()` - Filter to change login URLs
- `shivendra_logout_redirect()` - Redirect after logout
- `shivendra_custom_login_rewrite()` - Add rewrite rule
- `shivendra_activate_custom_login()` - Flush rules on activation
- `shivendra_custom_login_admin_notice()` - Show login URL reminder

**Features:**
- Custom login slug: `shivendra-admin-login` (configurable)
- Blocks direct access to wp-login.php (redirects to 404)
- Blocks wp-admin for non-logged-in users
- Allows logout and password reset to work normally
- Shows admin notice with custom login URL
- Easy to enable/disable via functions.php
- **Custom Login URL:** `yoursite.com/shivendra-admin-login`
- **To Change:** Edit `SHIVENDRA_CUSTOM_LOGIN_SLUG` constant (line 24)

**Hooks:**
- `init` (custom URL handler, rewrites, blocking)
- `admin_init` (block wp-admin)
- `login_url` (filter login URLs)
- `wp_logout` (logout redirect)
- `after_switch_theme` (flush rewrites)
- `admin_notices` (show reminder)

**Security Benefits:**
- Prevents brute force attacks on default wp-login.php
- Hides admin area from bots and scanners
- Difficult-to-guess custom URL
- No plugin required (code-based solution)

---

## Module Loading Order

Modules are loaded in [functions.php](../functions.php) in this order:

1. **theme-setup.php** - Core features first
2. **customizer.php** - Settings registration
3. **enqueue.php** - Asset loading
4. **newsletter.php** - Newsletter system
5. **ajax-handlers.php** - AJAX handlers
6. **helpers.php** - Utility functions
7. **security.php** - Security hardening
8. **seo.php** - SEO meta tags
9. **schema.php** - Structured data
10. **export.php** - Export functionality
11. **newsletter-admin.php** - Newsletter management page
12. **custom-login.php** - Custom login URL security

## Benefits of Modular Architecture

### ✅ **Organization**
- Each file has a single, clear purpose
- Easy to locate specific functionality
- Clean separation of concerns

### ✅ **Maintainability**
- Edit specific features without touching others
- Reduce risk of breaking unrelated code
- Easier to debug (errors show specific file)

### ✅ **Scalability**
- Add new features by creating new modules
- Remove features by commenting out one line in functions.php
- Easy to enable/disable features for testing

### ✅ **Collaboration**
- Multiple developers can work on different modules
- Reduced merge conflicts in version control
- Clear ownership of features

### ✅ **Professional**
- Industry-standard approach
- WordPress core uses modular structure
- Premium themes follow this pattern

## How to Edit Modules

### Example: Update Newsletter API
```
1. Open: inc/newsletter.php
2. Edit the relevant function
3. Save
4. Test
```

### Example: Add New SEO Tag
```
1. Open: inc/seo.php
2. Find shivendra_seo_meta_tags()
3. Add new meta tag in OUTPUT section
4. Save
```

### Example: Disable a Feature
```
// In functions.php, comment out the module:
// require_once SHIVENDRA_THEME_DIR . '/inc/export.php';
```

### Example: Change Custom Login URL
```
1. Open: inc/custom-login.php
2. Find line 24: define('SHIVENDRA_CUSTOM_LOGIN_SLUG', 'shivendra-admin-login');
3. Change 'shivendra-admin-login' to your desired slug
4. Save and visit any page to flush rewrite rules
```

## Backup Information

Original functions.php backup: `functions.php.backup`

To restore original:
```bash
cp functions.php.backup functions.php
```

## File Sizes

| Module | Size | Lines (approx) |
|--------|------|----------------|
| theme-setup.php | 1.7 KB | 65 |
| customizer.php | 9.4 KB | 278 |
| enqueue.php | 3.0 KB | 120 |
| newsletter.php | 6.5 KB | 180 |
| newsletter-admin.php | 6.2 KB | 200 |
| ajax-handlers.php | 1.6 KB | 54 |
| helpers.php | 4.8 KB | 165 |
| security.php | 1.7 KB | 60 |
| seo.php | 5.0 KB | 130 |
| schema.php | 7.0 KB | 195 |
| export.php | 1.9 KB | 58 |
| custom-login.php | 5.8 KB | 175 |
| **Total** | **54.6 KB** | **~1680** |

Compare to original functions.php: **90 KB, 1137 lines** (before modularization)

## Testing Checklist

After modularization, test these features:

- [ ] Homepage loads without errors
- [ ] WordPress Customizer opens and shows all sections
- [ ] Newsletter subscription works (footer, exit popup, about page)
- [ ] Newsletter admin page works (view, delete, bulk delete)
- [ ] Live search functions
- [ ] Blog posts display correctly
- [ ] Comments system works
- [ ] SEO meta tags appear in page source
- [ ] Schema.org JSON-LD appears in page source
- [ ] CSV export works (admin only)
- [ ] Custom login URL works (test logout and login)
- [ ] Default wp-login.php is blocked (redirects to 404)
- [ ] No PHP errors in debug log

## Support

For issues or questions about this modular structure:
1. Check debug.log: `/wp-content/debug.log`
2. Verify all module files exist in `inc/` directory
3. Check functions.php has all `require_once` statements
4. Restore backup if needed: `cp functions.php.backup functions.php`

---

**Version:** 1.0.0
**Last Updated:** December 8, 2024
**Author:** Shivendra
