<?php
/**
 * Transients API Caching System
 *
 * Implements smart caching for expensive database queries using WordPress Transients API.
 * This works alongside LiteSpeed Cache without conflicts:
 * - LiteSpeed Cache: Handles full page HTML caching (server-level)
 * - This Module: Caches expensive queries and data (application-level)
 *
 * @package Shivendra_Blog
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get cached carousel posts for homepage
 *
 * Caches 5 random posts for the featured carousel.
 * Cache expires after 6 hours.
 *
 * @return WP_Query|false Query object or false on failure
 */
function shivendra_get_cached_carousel_posts() {
    $cache_key = 'shivendra_carousel_posts';
    $cached = get_transient($cache_key);

    if (false === $cached) {
        $carousel_query = new WP_Query(array(
            'posts_per_page' => 5,
            'post_status' => 'publish',
            'orderby' => 'rand'
        ));

        // Cache for 6 hours
        set_transient($cache_key, $carousel_query, 6 * HOUR_IN_SECONDS);
        return $carousel_query;
    }

    return $cached;
}

/**
 * Get cached related posts for single post page
 *
 * Caches related posts by category or recent posts.
 * Cache expires after 12 hours.
 *
 * @param int $post_id Current post ID
 * @return WP_Query|false Query object or false on failure
 */
function shivendra_get_cached_related_posts($post_id) {
    $cache_key = 'shivendra_related_posts_' . $post_id;
    $cached = get_transient($cache_key);

    if (false === $cached) {
        $categories = get_the_category($post_id);

        if ($categories) {
            $category_ids = array();
            foreach ($categories as $category) {
                $category_ids[] = $category->term_id;
            }

            $args = array(
                'category__in' => $category_ids,
                'post__not_in' => array($post_id),
                'posts_per_page' => 3,
                'orderby' => 'rand'
            );

            $related_posts = new WP_Query($args);

            // If not enough related posts, fill with recent posts
            if ($related_posts->post_count < 3) {
                $args = array(
                    'post__not_in' => array($post_id),
                    'posts_per_page' => 3,
                    'orderby' => 'date',
                    'order' => 'DESC'
                );
                $related_posts = new WP_Query($args);
            }
        } else {
            // No categories, just show recent posts
            $args = array(
                'post__not_in' => array($post_id),
                'posts_per_page' => 3,
                'orderby' => 'date',
                'order' => 'DESC'
            );
            $related_posts = new WP_Query($args);
        }

        // Cache for 12 hours
        set_transient($cache_key, $related_posts, 12 * HOUR_IN_SECONDS);
        return $related_posts;
    }

    return $cached;
}

/**
 * Cache live search results
 *
 * Caches search results for frequently searched terms.
 * Cache expires after 1 hour.
 *
 * @param string $search_query Search term
 * @return array Search results
 */
function shivendra_get_cached_search_results($search_query) {
    // Sanitize and create unique cache key
    $sanitized_query = sanitize_text_field($search_query);
    $cache_key = 'shivendra_search_' . md5($sanitized_query);
    $cached = get_transient($cache_key);

    if (false === $cached) {
        $args = array(
            's' => $sanitized_query,
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 5,
            'orderby' => 'relevance'
        );

        $search = new WP_Query($args);
        $results = array();

        if ($search->have_posts()) {
            while ($search->have_posts()) {
                $search->the_post();

                $categories = get_the_category();
                $category = !empty($categories) ? $categories[0]->name : '';

                $results[] = array(
                    'title' => get_the_title(),
                    'url' => get_permalink(),
                    'excerpt' => wp_trim_words(get_the_excerpt(), 20),
                    'date' => get_the_date('M j, Y'),
                    'category' => $category
                );
            }
            wp_reset_postdata();
        }

        // Cache for 1 hour (search results change more frequently)
        set_transient($cache_key, $results, HOUR_IN_SECONDS);
        return $results;
    }

    return $cached;
}

/**
 * Clear all theme caches when a post is published/updated/deleted
 *
 * Ensures fresh content after changes.
 *
 * @param int $post_id Post ID
 */
function shivendra_clear_theme_caches($post_id) {
    // Only clear cache for published posts
    if (get_post_status($post_id) !== 'publish') {
        return;
    }

    // Clear carousel cache
    delete_transient('shivendra_carousel_posts');

    // Clear related posts cache for this post
    delete_transient('shivendra_related_posts_' . $post_id);

    // Clear search cache (delete all search transients)
    global $wpdb;
    $wpdb->query(
        "DELETE FROM {$wpdb->options}
         WHERE option_name LIKE '_transient_shivendra_search_%'
         OR option_name LIKE '_transient_timeout_shivendra_search_%'"
    );
}

// Hook cache clearing to post updates
add_action('save_post', 'shivendra_clear_theme_caches');
add_action('delete_post', 'shivendra_clear_theme_caches');

/**
 * Manual cache clear function (for debugging or admin use)
 *
 * Clears all theme transient caches.
 */
function shivendra_clear_all_caches() {
    global $wpdb;

    // Clear all theme-specific transients
    $wpdb->query(
        "DELETE FROM {$wpdb->options}
         WHERE option_name LIKE '_transient_shivendra_%'
         OR option_name LIKE '_transient_timeout_shivendra_%'"
    );
}

// Optional: Add admin bar button to clear cache (useful for development)
if (current_user_can('manage_options')) {
    add_action('admin_bar_menu', function($wp_admin_bar) {
        $wp_admin_bar->add_node(array(
            'id'    => 'clear_theme_cache',
            'title' => 'Clear Theme Cache',
            'href'  => add_query_arg('clear_theme_cache', '1'),
        ));
    }, 100);

    add_action('init', function() {
        if (isset($_GET['clear_theme_cache']) && current_user_can('manage_options')) {
            shivendra_clear_all_caches();
            wp_redirect(remove_query_arg('clear_theme_cache'));
            exit;
        }
    });
}
