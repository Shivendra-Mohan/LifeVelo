<?php
/**
 * Enqueue Styles and Scripts
 *
 * Handles loading of CSS and JavaScript files with conditional loading
 * for specific page templates.
 *
 * @package Shivendra_Blog
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue theme styles and scripts
 */
function shivendras_blog_styles() {
    // Main stylesheet
    wp_enqueue_style(
        'shivendras-blog-style',
        get_stylesheet_uri(),
        array(),
        '1.0.2' // Version bump for logo customization update
    );

    // Conditionally load categories page CSS
    if (is_page_template('page-categories.php')) {
        wp_enqueue_style(
            'categories-style',
            get_template_directory_uri() . '/css/categories.css',
            array('shivendras-blog-style'),
            '1.0'
        );
    }

    // Conditionally load journal page CSS
    if (is_page_template('page-journal.php')) {
        wp_enqueue_style(
            'journal-style',
            get_template_directory_uri() . '/css/journal.css',
            array('shivendras-blog-style'),
            '1.0'
        );
    }

    // Conditionally load category archive CSS
    if (is_category()) {
        wp_enqueue_style(
            'category-archive-style',
            get_template_directory_uri() . '/css/category-archive.css',
            array('shivendras-blog-style'),
            '1.0'
        );
    }

    // Conditionally load privacy policy page CSS
    if (is_page_template('page-privacy-policy.php')) {
        wp_enqueue_style(
            'privacy-policy-style',
            get_template_directory_uri() . '/css/privacy-policy.css',
            array('shivendras-blog-style'),
            '1.0'
        );
    }

    // Conditionally load terms of use page CSS
    if (is_page_template('page-terms-of-use.php')) {
        wp_enqueue_style(
            'terms-of-use-style',
            get_template_directory_uri() . '/css/terms-of-use.css',
            array('shivendras-blog-style'),
            '1.0'
        );
    }

    // Conditionally load about page CSS
    if (is_page_template('page-about.php')) {
        wp_enqueue_style(
            'about-style',
            get_template_directory_uri() . '/css/about.css',
            array('shivendras-blog-style'),
            '1.0.1' // Version bump for selection fix
        );
    }

    // Enqueue main JavaScript
    wp_enqueue_script(
        'shivendras-blog-js',
        get_template_directory_uri() . '/assets/js/main.js',
        array(),
        '1.0',
        true
    );

    // Localize script for AJAX
    wp_localize_script('shivendras-blog-js', 'newsletter_ajax_obj', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'shivendras_blog_styles');

/**
 * Custom Walker for dropdown menus
 */
class Shivendra_Dropdown_Walker extends Walker_Nav_Menu {
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"dropdown-menu\">\n";
    }
    function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
}
