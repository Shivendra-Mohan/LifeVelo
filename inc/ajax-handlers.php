<?php
/**
 * AJAX Handlers
 *
 * Handles AJAX requests for live search and other dynamic features.
 *
 * @package Shivendra_Blog
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Live Search AJAX Handler
 *
 * Returns search results as JSON for the live search feature.
 * Searches through published posts and returns up to 5 results.
 *
 * @since 1.0.0
 */
function shivendras_blog_live_search() {
    $search_query = sanitize_text_field($_GET['s']);

    if (empty($search_query)) {
        wp_send_json_success(array());
    }

    // Use cached search results (1 hour cache)
    $results = shivendra_get_cached_search_results($search_query);

    wp_send_json_success($results);
}

// Register AJAX handlers for live search
add_action('wp_ajax_live_search', 'shivendras_blog_live_search');
add_action('wp_ajax_nopriv_live_search', 'shivendras_blog_live_search');
