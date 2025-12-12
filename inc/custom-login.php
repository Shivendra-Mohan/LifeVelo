<?php
/**
 * Custom Login URL Security
 *
 * Changes the default wp-login.php URL to a custom URL for enhanced security.
 * Blocks direct access to wp-login.php and wp-admin for non-logged-in users.
 *
 * @package Shivendra_Blog
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * CONFIGURATION
 * Change this to your desired custom login slug
 *
 * Example: If you set 'my-secret-login', your login URL will be:
 * yoursite.com/my-secret-login
 *
 * IMPORTANT: Remember this URL - you'll need it to login!
 *
 * For production: Set this in config.php (not tracked in Git)
 * For development: Default value below
 */
if (!defined('CUSTOM_LOGIN_SLUG')) {
    define('CUSTOM_LOGIN_SLUG', 'shivendra-admin-login'); // Development default
}
define('SHIVENDRA_CUSTOM_LOGIN_SLUG', CUSTOM_LOGIN_SLUG);

/**
 * Handle custom login URL
 */
function shivendra_custom_login_url() {
    // Don't run in admin area
    if (is_admin()) {
        return;
    }

    $custom_slug = SHIVENDRA_CUSTOM_LOGIN_SLUG;

    // Get the current request URI
    if (!isset($_SERVER['REQUEST_URI'])) {
        return;
    }

    $request_uri = $_SERVER['REQUEST_URI'];
    $parsed_request = parse_url($request_uri, PHP_URL_PATH);
    $parsed_request = trim($parsed_request, '/');

    // Check if user is accessing the custom login URL
    if ($parsed_request === $custom_slug) {
        // Set the custom login flag
        $_GET['shivendra_custom_login'] = '1';

        // Include the default wp-login.php
        if (file_exists(ABSPATH . 'wp-login.php')) {
            require_once ABSPATH . 'wp-login.php';
            exit;
        }
    }
}
add_action('init', 'shivendra_custom_login_url', 1);

/**
 * Block direct access to wp-login.php
 * Redirect unauthorized access to 404 page
 */
function shivendra_block_default_login() {
    // Only run on wp-login.php
    if (!isset($GLOBALS['pagenow']) || $GLOBALS['pagenow'] !== 'wp-login.php') {
        return;
    }

    // Allow if custom login flag is set
    if (isset($_GET['shivendra_custom_login'])) {
        return;
    }

    // Allow if user is already logged in
    if (is_user_logged_in()) {
        return;
    }

    // Allow logout action
    if (isset($_GET['action']) && $_GET['action'] === 'logout') {
        return;
    }

    // Allow password reset
    if (isset($_GET['action']) && in_array($_GET['action'], array('rp', 'resetpass', 'lostpassword'), true)) {
        return;
    }

    // Allow access from whitelisted IPs (for Hostinger dashboard button)
    // Get this from config.php in production
    $whitelisted_ips = array();
    if (defined('WHITELISTED_IPS') && !empty(WHITELISTED_IPS)) {
        $whitelisted_ips = is_array(WHITELISTED_IPS) ? WHITELISTED_IPS : explode(',', WHITELISTED_IPS);
        $whitelisted_ips = array_map('trim', $whitelisted_ips);
    }

    if (!empty($whitelisted_ips)) {
        $user_ip = '';
        if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            // If using Cloudflare
            $user_ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // If behind a proxy
            $user_ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            // Standard IP
            $user_ip = $_SERVER['REMOTE_ADDR'];
        }

        $user_ip = trim($user_ip);

        // Allow if IP is whitelisted
        if (in_array($user_ip, $whitelisted_ips, true)) {
            return;
        }
    }

    // Block all other direct access - redirect to 404
    wp_safe_redirect(home_url('/404'));
    exit;
}
add_action('login_init', 'shivendra_block_default_login', 1);

/**
 * Block wp-admin access for non-logged-in users
 */
function shivendra_block_wp_admin() {
    // Allow AJAX requests
    if (defined('DOING_AJAX') && DOING_AJAX) {
        return;
    }

    // Block admin access if not logged in - return 404 instead of redirect
    if (!is_user_logged_in()) {
        // Send 404 error (no redirect, more secure)
        wp_safe_redirect(home_url('/404'));
        exit;
    }
}
add_action('admin_init', 'shivendra_block_wp_admin', 1);

/**
 * Change login URL in WordPress
 * This updates all login URLs throughout WordPress
 */
function shivendra_login_url($login_url, $redirect = '', $force_reauth = false) {
    $custom_slug = SHIVENDRA_CUSTOM_LOGIN_SLUG;

    // Build custom login URL
    $custom_login_url = home_url('/' . $custom_slug);

    // Add redirect parameter if provided
    if (!empty($redirect)) {
        $custom_login_url = add_query_arg('redirect_to', urlencode($redirect), $custom_login_url);
    }

    // Add reauth parameter if needed
    if ($force_reauth) {
        $custom_login_url = add_query_arg('reauth', '1', $custom_login_url);
    }

    return $custom_login_url;
}
add_filter('login_url', 'shivendra_login_url', 10, 3);

/**
 * Redirect after logout to homepage (not custom login page)
 */
function shivendra_logout_redirect() {
    wp_redirect(home_url('/'));
    exit;
}
add_action('wp_logout', 'shivendra_logout_redirect');

/**
 * Add custom rewrite rule for login page
 * This makes the custom URL work properly
 */
function shivendra_custom_login_rewrite() {
    $custom_slug = SHIVENDRA_CUSTOM_LOGIN_SLUG;
    add_rewrite_rule('^' . $custom_slug . '/?$', 'index.php?shivendra_login=1', 'top');
}
add_action('init', 'shivendra_custom_login_rewrite');

/**
 * Add custom query var
 */
function shivendra_login_query_vars($vars) {
    $vars[] = 'shivendra_login';
    return $vars;
}
add_filter('query_vars', 'shivendra_login_query_vars');

/**
 * Flush rewrite rules on theme activation
 * This ensures the custom URL works immediately
 */
function shivendra_activate_custom_login() {
    shivendra_custom_login_rewrite();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'shivendra_activate_custom_login');

/**
 * Admin notice to remind about custom login URL
 * Shows only to admins on dashboard
 */
function shivendra_custom_login_admin_notice() {
    $screen = get_current_screen();

    // Show only on dashboard
    if ($screen->id !== 'dashboard') {
        return;
    }

    // Show only to administrators
    if (!current_user_can('manage_options')) {
        return;
    }

    $custom_slug = SHIVENDRA_CUSTOM_LOGIN_SLUG;
    $custom_login_url = home_url('/' . $custom_slug);

    ?>
    <div class="notice notice-info">
        <p>
            <strong>🔒 Custom Login Security Active</strong><br>
            Your login URL has been changed for security.<br>
            <strong>Login URL:</strong> <code><?php echo esc_html($custom_login_url); ?></code><br>
            <small>Save this URL! The default wp-login.php is now blocked.</small>
        </p>
    </div>
    <?php
}
add_action('admin_notices', 'shivendra_custom_login_admin_notice');

/**
 * HOW TO USE:
 *
 * 1. Your new login URL is: yoursite.com/shivendra-admin-login
 * 2. The old wp-login.php is now blocked
 * 3. To change the login slug, edit line 24 (SHIVENDRA_CUSTOM_LOGIN_SLUG)
 * 4. After changing, visit any page on your site to flush rewrite rules
 *
 * SECURITY FEATURES:
 * - Blocks direct access to wp-login.php
 * - Blocks wp-admin for non-logged-in users
 * - Redirects unauthorized access to 404 page
 * - Allows logout and password reset to work normally
 * - Custom URL is difficult to guess
 *
 * TO DISABLE:
 * Simply comment out the require_once line in functions.php
 */
