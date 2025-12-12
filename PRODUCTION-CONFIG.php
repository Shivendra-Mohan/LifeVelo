<?php
/**
 * Production Configuration for LifeVelo.com
 *
 * DEPLOYMENT INSTRUCTIONS:
 * 1. Upload this to your Hostinger server
 * 2. Rename it to 'config.php'
 * 3. Update the values below with your actual data
 * 4. Delete this file from local machine after deployment
 *
 * SECURITY: This file should NEVER be committed to Git
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * CUSTOM LOGIN CONFIGURATION
 *
 * ⚠️ CRITICAL SECURITY SETTING
 *
 * This is your secret login URL. It will replace wp-login.php
 *
 * Suggested Strong Login Slugs (choose one or create your own):
 * - 'gateway-rx47k'
 * - 'portal-m9dx3'
 * - 'access-vk82n'
 * - 'secure-tj64p'
 * - 'admin-zx29w'
 *
 * Your login URL will be: https://lifevelo.com/YOUR-SLUG-HERE
 *
 * RECOMMENDATION: Use a combination of:
 * - Common word (gateway, portal, access, secure, admin)
 * - Random letters/numbers (rx47k, m9dx3, vk82n)
 */
if (!defined('CUSTOM_LOGIN_SLUG')) {
    define('CUSTOM_LOGIN_SLUG', 'gateway-rx47k');  // ⬅️ CHANGE THIS!
}

/**
 * IP WHITELIST FOR HOSTINGER BUTTON
 *
 * Find your IP address: https://whatismyipaddress.com/
 *
 * Add IPs that should be able to use wp-login.php directly
 * (Mainly for Hostinger dashboard "WordPress Admin" button)
 *
 * STEPS TO ADD YOUR IP:
 * 1. Visit https://whatismyipaddress.com/
 * 2. Copy your IPv4 address
 * 3. Add it below (uncomment and replace)
 */
if (!defined('WHITELISTED_IPS')) {
    define('WHITELISTED_IPS', array(
        // 'YOUR_HOME_IP_HERE',        // Example: '203.45.67.89'
        // 'YOUR_OFFICE_IP_HERE',      // Example: '198.76.54.32'
        // 'HOSTINGER_SERVER_IP',      // Optional: Hostinger's IP for admin button
    ));

    // ⚠️ SECURITY OPTIONS:
    //
    // Option 1: Whitelist your IPs (recommended if you need Hostinger button)
    //           Uncomment lines above and add your IPs
    //
    // Option 2: Maximum Security (disable whitelist completely)
    //           Keep array empty like above
    //           Note: Hostinger's "WordPress Admin" button won't work
}

/**
 * GOOGLE ANALYTICS GA4
 *
 * Get from: https://analytics.google.com
 * Format: G-XXXXXXXXXX
 */
if (!defined('GA_MEASUREMENT_ID')) {
    define('GA_MEASUREMENT_ID', '');  // ⬅️ ADD YOUR GA4 ID
}

/**
 * BREVO (SENDINBLUE) NEWSLETTER API
 *
 * Get API credentials from: https://app.brevo.com/settings/keys/api
 */
if (!defined('BREVO_API_KEY')) {
    define('BREVO_API_KEY', '');  // ⬅️ ADD YOUR BREVO API KEY
}

if (!defined('BREVO_LIST_ID')) {
    define('BREVO_LIST_ID', '');  // ⬅️ ADD YOUR BREVO LIST ID
}

/**
 * ENVIRONMENT SETTINGS
 */
if (!defined('WP_ENVIRONMENT')) {
    define('WP_ENVIRONMENT', 'production');
}

/**
 * DEBUG MODE
 *
 * ⚠️ KEEP FALSE ON PRODUCTION!
 */
if (!defined('WP_DEBUG')) {
    define('WP_DEBUG', false);
}

if (!defined('WP_DEBUG_LOG')) {
    define('WP_DEBUG_LOG', false);
}

if (!defined('WP_DEBUG_DISPLAY')) {
    define('WP_DEBUG_DISPLAY', false);
}

/**
 * SECURITY NOTES:
 *
 * 1. After deployment, your login URL will be:
 *    https://lifevelo.com/gateway-rx47k (or whatever you set above)
 *
 * 2. Anyone trying to access:
 *    https://lifevelo.com/wp-login.php
 *    Will get 404 error (unless their IP is whitelisted)
 *
 * 3. SAVE YOUR LOGIN URL SECURELY:
 *    - Bookmark it in browser (private bookmark)
 *    - Save in password manager
 *    - DO NOT share publicly
 *
 * 4. TESTING BEFORE GOING LIVE:
 *    - Test custom login URL works
 *    - Verify wp-login.php is blocked
 *    - Test IP whitelist (if enabled)
 *    - Clear permalinks: Settings → Permalinks → Save
 */
