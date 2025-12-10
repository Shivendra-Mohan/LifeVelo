<?php
/**
 * Theme Configuration Sample
 *
 * INSTRUCTIONS FOR DEPLOYMENT:
 * 1. Copy this file to 'config.php' on your server
 * 2. Fill in your actual values
 * 3. Never commit 'config.php' to Git (it's in .gitignore)
 *
 * @package Shivendra_Blog
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * CUSTOM LOGIN CONFIGURATION
 *
 * IMPORTANT: Change this to your own custom login slug
 * This prevents brute force attacks on wp-login.php
 *
 * Default: 'shivendra-admin-login'
 * Change to something unique and hard to guess
 *
 * Examples: 'my-secret-portal-2024', 'admin-xyz-login', etc.
 */
if (!defined('CUSTOM_LOGIN_SLUG')) {
    define('CUSTOM_LOGIN_SLUG', 'your-custom-login-slug-here');
}

/**
 * IP WHITELIST FOR WP-LOGIN.PHP ACCESS
 *
 * Allow specific IPs to access wp-login.php directly
 * Useful for Hostinger dashboard "WordPress Admin" button
 *
 * Format Options:
 * 1. Array: array('123.45.67.89', '98.76.54.32')
 * 2. String: '123.45.67.89,98.76.54.32'
 *
 * How to find your IP: Visit https://whatismyipaddress.com/
 *
 * Leave empty to disable IP whitelisting (more secure but Hostinger button won't work)
 */
if (!defined('WHITELISTED_IPS')) {
    // Option 1: Array format (recommended)
    define('WHITELISTED_IPS', array(
        // 'YOUR_HOME_IP_HERE',     // Your home IP
        // 'YOUR_OFFICE_IP_HERE',   // Your office IP
    ));

    // Option 2: String format (alternative)
    // define('WHITELISTED_IPS', 'YOUR_IP_1,YOUR_IP_2');

    // Option 3: Disable whitelist (most secure, but Hostinger button won't work)
    // define('WHITELISTED_IPS', '');
}

/**
 * GOOGLE ANALYTICS CONFIGURATION
 *
 * Add your GA4 Measurement ID here
 * Format: G-XXXXXXXXXX
 *
 * Get from: https://analytics.google.com
 */
if (!defined('GA_MEASUREMENT_ID')) {
    define('GA_MEASUREMENT_ID', '');
}

/**
 * BREVO (SENDINBLUE) API CONFIGURATION
 *
 * Newsletter API settings
 * Get from: https://app.brevo.com/settings/keys/api
 */
if (!defined('BREVO_API_KEY')) {
    define('BREVO_API_KEY', '');
}

if (!defined('BREVO_LIST_ID')) {
    define('BREVO_LIST_ID', '');
}

/**
 * ENVIRONMENT
 *
 * Set to 'production' on live server
 * Set to 'development' on local/staging
 */
if (!defined('WP_ENVIRONMENT')) {
    define('WP_ENVIRONMENT', 'production');
}

/**
 * DEBUG MODE
 *
 * IMPORTANT: Set to false on production!
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
