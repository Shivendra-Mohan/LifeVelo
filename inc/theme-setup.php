<?php
/**
 * Theme Setup
 *
 * Sets up essential WordPress features for the theme including:
 * - Post thumbnails and custom image sizes
 * - Navigation menus
 * - HTML5 support
 * - Performance optimizations
 *
 * @package Shivendra_Blog
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme setup function
 * Runs on after_setup_theme hook
 */
function shivendras_blog_setup() {

    // 1. Enable Featured Images (Post Thumbnails)
    add_theme_support('post-thumbnails');

    // 2. Enable Navigation Menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'shivendras-blog'),
        'footer'  => __('Footer Menu', 'shivendras-blog'),
    ));

    // 3. Add Custom Image Sizes
    add_image_size('blog-medium', 600, 400, true);
    add_image_size('blog-large', 1200, 600, true);

    // 4. Add HTML5 Support
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));

    // 5. Declare Classic Theme
    add_theme_support('classic-editor');

    // 6. Disable WordPress Emojis (Performance Optimization & Fix for Hanging Requests)
    // Prevents wp-emoji-release.min.js from trying to load SVGs from external servers
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}
add_action('after_setup_theme', 'shivendras_blog_setup');
