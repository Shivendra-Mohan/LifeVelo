<?php
/**
 * Security Hardening
 *
 * Implements security best practices to protect the WordPress site
 * from common vulnerabilities and attacks.
 *
 * @package Shivendra_Blog
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Disable XML-RPC
 *
 * XML-RPC is a common target for brute force attacks and DDoS.
 * Unless you are using the WordPress Mobile App or Jetpack, it is safe to disable.
 *
 * @since 1.0.0
 */
add_filter('xmlrpc_enabled', '__return_false');

/**
 * Disable X-Pingback Header
 *
 * Removes the X-Pingback header to prevent pingback attacks.
 *
 * @since 1.0.0
 *
 * @param array $headers HTTP headers
 * @return array Modified headers
 */
function shivendra_disable_x_pingback($headers) {
    unset($headers['X-Pingback']);
    return $headers;
}
add_filter('wp_headers', 'shivendra_disable_x_pingback');

/**
 * Hide WordPress Version
 *
 * Prevents attackers from easily identifying the specific WordPress version you are running.
 * Removes version number from HTML meta tags.
 *
 * @since 1.0.0
 *
 * @return string Empty string
 */
function shivendra_remove_version() {
    return '';
}
add_filter('the_generator', 'shivendra_remove_version');

/**
 * Block User Enumeration
 *
 * Prevents hackers from fishing for usernames by trying ?author=1, ?author=2, etc.
 * Redirects such requests to the home page.
 *
 * @since 1.0.0
 */
function shivendra_block_user_enumeration() {
    if (is_admin()) return;

    $author_by_id = (isset($_GET['author']) && is_numeric($_GET['author']));

    if ($author_by_id) {
        wp_redirect(home_url());
        exit;
    }
}
add_action('template_redirect', 'shivendra_block_user_enumeration');
