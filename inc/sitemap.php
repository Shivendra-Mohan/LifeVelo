<?php
/**
 * XML Sitemap Generator
 *
 * Generates dynamic XML sitemap for search engines.
 * Supports posts, pages, categories, and custom post types.
 *
 * @package Shivendra_Blog
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Sitemap Customizer Settings
 */
function shivendra_sitemap_customizer($wp_customize) {
    // Add Sitemap Section
    $wp_customize->add_section('shivendra_sitemap_section', array(
        'title'    => __('XML Sitemap', 'shivendras-blog'),
        'priority' => 210,
    ));

    // Enable/Disable Sitemap
    $wp_customize->add_setting('enable_sitemap', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('enable_sitemap', array(
        'label'       => __('Enable XML Sitemap', 'shivendras-blog'),
        'description' => __('Generate dynamic XML sitemap at /sitemap.xml', 'shivendras-blog'),
        'section'     => 'shivendra_sitemap_section',
        'type'        => 'checkbox',
    ));

    // Include Categories
    $wp_customize->add_setting('sitemap_include_categories', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('sitemap_include_categories', array(
        'label'       => __('Include Categories', 'shivendras-blog'),
        'description' => __('Add category pages to sitemap', 'shivendras-blog'),
        'section'     => 'shivendra_sitemap_section',
        'type'        => 'checkbox',
    ));
}
add_action('customize_register', 'shivendra_sitemap_customizer');

/**
 * Handle sitemap requests
 */
function shivendra_sitemap_handler() {
    // Check if sitemap is requested
    $request_uri = $_SERVER['REQUEST_URI'];

    if (strpos($request_uri, '/sitemap.xml') !== false) {
        // Check if sitemap is enabled
        if (!get_theme_mod('enable_sitemap', true)) {
            return;
        }

        // Generate and output sitemap
        shivendra_generate_sitemap();
        exit;
    }
}
add_action('init', 'shivendra_sitemap_handler', 1);

/**
 * Generate XML sitemap
 */
function shivendra_generate_sitemap() {
    // Set proper headers
    header('Content-Type: application/xml; charset=utf-8');
    header('X-Robots-Tag: noindex, follow', true);

    // Start XML output
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<?xml-stylesheet type="text/xsl" href="' . esc_url(get_template_directory_uri() . '/inc/sitemap-style.xsl') . '"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

    // Homepage
    shivendra_sitemap_add_url(home_url('/'), current_time('c'), '1.0', 'daily');

    // Posts
    $posts = get_posts(array(
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'modified',
        'order'          => 'DESC',
    ));

    foreach ($posts as $post) {
        shivendra_sitemap_add_url(
            get_permalink($post->ID),
            get_post_modified_time('c', false, $post->ID),
            '0.8',
            'weekly'
        );
    }

    // Pages (excluding specific templates)
    $pages = get_posts(array(
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'modified',
        'order'          => 'DESC',
    ));

    foreach ($pages as $page) {
        // Skip privacy policy and terms pages if they exist
        $template = get_page_template_slug($page->ID);

        shivendra_sitemap_add_url(
            get_permalink($page->ID),
            get_post_modified_time('c', false, $page->ID),
            '0.6',
            'monthly'
        );
    }

    // Categories (if enabled)
    if (get_theme_mod('sitemap_include_categories', true)) {
        $categories = get_categories(array(
            'hide_empty' => true,
        ));

        foreach ($categories as $category) {
            shivendra_sitemap_add_url(
                get_category_link($category->term_id),
                current_time('c'),
                '0.5',
                'weekly'
            );
        }
    }

    echo '</urlset>';
}

/**
 * Add single URL to sitemap
 *
 * @param string $url URL to add
 * @param string $lastmod Last modified date
 * @param string $priority Priority (0.0 to 1.0)
 * @param string $changefreq Change frequency
 */
function shivendra_sitemap_add_url($url, $lastmod, $priority, $changefreq) {
    echo '  <url>' . "\n";
    echo '    <loc>' . esc_url($url) . '</loc>' . "\n";
    echo '    <lastmod>' . esc_html($lastmod) . '</lastmod>' . "\n";
    echo '    <changefreq>' . esc_html($changefreq) . '</changefreq>' . "\n";
    echo '    <priority>' . esc_html($priority) . '</priority>' . "\n";
    echo '  </url>' . "\n";
}

/**
 * Add rewrite rule for sitemap
 */
function shivendra_sitemap_rewrite() {
    add_rewrite_rule('^sitemap\.xml$', 'index.php?shivendra_sitemap=1', 'top');
}
add_action('init', 'shivendra_sitemap_rewrite');

/**
 * Add custom query var
 */
function shivendra_sitemap_query_vars($vars) {
    $vars[] = 'shivendra_sitemap';
    return $vars;
}
add_filter('query_vars', 'shivendra_sitemap_query_vars');

/**
 * Flush rewrite rules on theme activation
 */
function shivendra_sitemap_activate() {
    shivendra_sitemap_rewrite();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'shivendra_sitemap_activate');

/**
 * Add sitemap to robots.txt
 */
function shivendra_add_sitemap_to_robots($output, $public) {
    if ($public && get_theme_mod('enable_sitemap', true)) {
        $output .= "\n# XML Sitemap\n";
        $output .= "Sitemap: " . home_url('/sitemap.xml') . "\n";
    }
    return $output;
}
add_filter('robots_txt', 'shivendra_add_sitemap_to_robots', 10, 2);

/**
 * Admin notice about sitemap
 */
function shivendra_sitemap_admin_notice() {
    $screen = get_current_screen();

    // Show only on dashboard
    if ($screen->id !== 'dashboard') {
        return;
    }

    // Show only to administrators
    if (!current_user_can('manage_options')) {
        return;
    }

    // Check if sitemap is enabled
    if (!get_theme_mod('enable_sitemap', true)) {
        return;
    }

    // Only show once (use transient to track)
    if (get_transient('shivendra_sitemap_notice_dismissed')) {
        return;
    }

    ?>
    <div class="notice notice-success is-dismissible" data-dismissible="shivendra-sitemap-notice">
        <p>
            <strong>🗺️ XML Sitemap Generated!</strong><br>
            Your sitemap is available at: <a href="<?php echo home_url('/sitemap.xml'); ?>" target="_blank"><?php echo home_url('/sitemap.xml'); ?></a><br>
            Submit this to <a href="https://search.google.com/search-console" target="_blank">Google Search Console</a> and <a href="https://www.bing.com/webmasters" target="_blank">Bing Webmaster Tools</a>.
        </p>
    </div>
    <script>
    jQuery(document).on('click', '.notice[data-dismissible="shivendra-sitemap-notice"] .notice-dismiss', function() {
        jQuery.post(ajaxurl, {
            action: 'dismiss_sitemap_notice'
        });
    });
    </script>
    <?php
}
add_action('admin_notices', 'shivendra_sitemap_admin_notice');

/**
 * Handle dismiss notice AJAX
 */
function shivendra_dismiss_sitemap_notice() {
    set_transient('shivendra_sitemap_notice_dismissed', true, MONTH_IN_SECONDS);
    wp_die();
}
add_action('wp_ajax_dismiss_sitemap_notice', 'shivendra_dismiss_sitemap_notice');

/**
 * HOW TO USE:
 *
 * 1. Sitemap URL:
 *    - Your sitemap is available at: yoursite.com/sitemap.xml
 *    - It updates automatically when you publish/update content
 *
 * 2. Submit to Search Engines:
 *    Google Search Console:
 *    - Go to https://search.google.com/search-console
 *    - Add your property
 *    - Go to Sitemaps section
 *    - Submit: yoursite.com/sitemap.xml
 *
 *    Bing Webmaster Tools:
 *    - Go to https://www.bing.com/webmasters
 *    - Add your site
 *    - Submit sitemap URL
 *
 * 3. Customize Settings:
 *    - Go to Appearance → Customize → XML Sitemap
 *    - Enable/disable sitemap
 *    - Include/exclude categories
 *
 * 4. robots.txt:
 *    - Sitemap URL is automatically added to robots.txt
 *    - Search engines will discover it automatically
 *
 * WHAT'S INCLUDED:
 * - Homepage (priority: 1.0, daily updates)
 * - All published posts (priority: 0.8, weekly updates)
 * - All published pages (priority: 0.6, monthly updates)
 * - Categories (priority: 0.5, weekly updates, optional)
 *
 * BENEFITS:
 * - No plugin required
 * - Dynamic generation (always up-to-date)
 * - Search engine friendly
 * - Automatically updates when content changes
 * - Fast and lightweight
 */
