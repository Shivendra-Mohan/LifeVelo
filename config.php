<?php
/**
 * Theme Configuration - Local Development
 *
 * This is for LOCAL TESTING only
 * On production, create a fresh config.php with real values
 *
 * @package Shivendra_Blog
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * CUSTOM LOGIN CONFIGURATION
 */
if (!defined('CUSTOM_LOGIN_SLUG')) {
    define('CUSTOM_LOGIN_SLUG', 'shivendra-test-login'); // Local test slug
}

/**
 * IP WHITELIST FOR WP-LOGIN.PHP ACCESS
 *
 * For local testing, we'll allow localhost IPs
 */
if (!defined('WHITELISTED_IPS')) {
    define('WHITELISTED_IPS', array(
        '127.0.0.1',        // Localhost IPv4
        '::1',              // Localhost IPv6
        'localhost',        // Localhost name
    ));
}

/**
 * GOOGLE ANALYTICS - Leave empty for local
 */
if (!defined('GA_MEASUREMENT_ID')) {
    define('GA_MEASUREMENT_ID', '');
}

/**
 * BREVO API - Use your test credentials if available
 */
if (!defined('BREVO_API_KEY')) {
    define('BREVO_API_KEY', '');
}

if (!defined('BREVO_LIST_ID')) {
    define('BREVO_LIST_ID', '');
}

/**
 * ENVIRONMENT
 */
if (!defined('WP_ENVIRONMENT')) {
    define('WP_ENVIRONMENT', 'development');
}

/**
 * DEBUG MODE - Disable to hide warnings
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
