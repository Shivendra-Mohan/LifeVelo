<?php
/**
 * SEO Meta Tags Generator
 *
 * Generates dynamic SEO meta tags for different page types.
 * Includes Open Graph (Facebook, WhatsApp), Twitter Cards, and basic SEO tags.
 *
 * @package Shivendra_Blog
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Generate Dynamic SEO Meta Tags
 *
 * Outputs meta tags in the <head> section for:
 * - Basic SEO (title, description, author)
 * - Open Graph (Facebook, WhatsApp, LinkedIn)
 * - Twitter Cards
 * - Canonical URLs
 *
 * Supports different page types: single posts, pages, categories, search, 404, homepage.
 *
 * @since 1.0.0
 */
function shivendra_seo_meta_tags() {
    // Site information
    $site_name = get_bloginfo('name');
    $site_url = home_url('/');

    // Default values
    $title = '';
    $description = '';
    $image = get_template_directory_uri() . '/assets/images/profile.webp'; // Default image
    $url = '';
    $type = 'website';
    $author = get_theme_mod('profile_name', $site_name);

    // ====================================
    // DETERMINE CONTEXT & SET META DATA
    // ====================================

    if (is_single()) {
        // Single Post Page
        global $post;
        $title = get_the_title();
        $description = get_the_excerpt();

        // Get featured image
        if (has_post_thumbnail()) {
            $image = get_the_post_thumbnail_url($post->ID, 'full');
        }

        $url = get_permalink();
        $type = 'article';

    } elseif (is_page()) {
        // Single Page
        $title = get_the_title();

        // Try to get excerpt, fallback to content snippet
        $excerpt = get_the_excerpt();
        if (empty($excerpt)) {
            $content = get_the_content();
            $description = wp_trim_words(strip_tags($content), 30, '...');
        } else {
            $description = $excerpt;
        }

        if (has_post_thumbnail()) {
            $image = get_the_post_thumbnail_url(get_the_ID(), 'full');
        }

        $url = get_permalink();

    } elseif (is_category()) {
        // Category Archive
        $category = get_queried_object();
        $title = 'Category: ' . $category->name;
        $description = $category->description ? $category->description : 'Posts in ' . $category->name . ' category';
        $url = get_category_link($category->term_id);

    } elseif (is_search()) {
        // Search Results
        $title = 'Search Results for: ' . get_search_query();
        $description = 'Search results for "' . get_search_query() . '" on ' . $site_name;
        $url = get_search_link();

    } elseif (is_404()) {
        // 404 Page
        $title = '404 - Page Not Found';
        $description = 'The page you are looking for could not be found on ' . $site_name;
        $url = $site_url;

    } else {
        // Homepage or other
        $title = $site_name;
        $description = get_bloginfo('description') ? get_bloginfo('description') : 'A journal by Shivendra Mohan';
        $url = $site_url;
        // Use profile logo for WhatsApp/social media (1200x1200)
        $image = get_template_directory_uri() . '/assets/images/Profile Logo.png';
    }

    // Clean up description
    $description = wp_strip_all_tags($description);
    $description = str_replace(["\r", "\n"], ' ', $description);
    $description = trim($description);

    // ====================================
    // OUTPUT META TAGS
    // ====================================
    ?>

    <!-- Basic SEO Meta Tags -->
    <meta name="description" content="<?php echo esc_attr($description); ?>">
    <meta name="author" content="<?php echo esc_attr($author); ?>">

    <!-- Open Graph Meta Tags (Facebook, WhatsApp, LinkedIn) -->
    <meta property="og:title" content="<?php echo esc_attr($title); ?>">
    <meta property="og:description" content="<?php echo esc_attr($description); ?>">
    <meta property="og:image" content="<?php echo esc_url($image); ?>">
    <meta property="og:url" content="<?php echo esc_url($url); ?>">
    <meta property="og:type" content="<?php echo esc_attr($type); ?>">
    <meta property="og:site_name" content="<?php echo esc_attr($site_name); ?>">

    <?php if ($type === 'article' && is_single()): ?>
        <meta property="article:published_time" content="<?php echo get_the_date('c'); ?>">
        <meta property="article:modified_time" content="<?php echo get_the_modified_date('c'); ?>">
        <meta property="article:author" content="<?php echo esc_attr($author); ?>">
        <?php
        // Article categories
        $categories = get_the_category();
        foreach ($categories as $cat) {
            echo '<meta property="article:section" content="' . esc_attr($cat->name) . '">' . "\n    ";
        }
        ?>
    <?php endif; ?>

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo esc_attr($title); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr($description); ?>">
    <meta name="twitter:image" content="<?php echo esc_url($image); ?>">

    <!-- Additional SEO Tags -->
    <link rel="canonical" href="<?php echo esc_url($url); ?>">

    <?php
}
