<?php
/**
 * Google Analytics Integration
 *
 * Adds Google Analytics 4 (GA4) tracking code to the website.
 * Configurable via WordPress Customizer.
 *
 * @package Shivendra_Blog
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Google Analytics Customizer Settings
 */
function shivendra_analytics_customizer($wp_customize) {
    // Add Analytics Section
    $wp_customize->add_section('shivendra_analytics_section', array(
        'title'    => __('Google Analytics', 'shivendras-blog'),
        'priority' => 200,
    ));

    // GA4 Measurement ID Setting
    $wp_customize->add_setting('google_analytics_id', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('google_analytics_id', array(
        'label'       => __('GA4 Measurement ID', 'shivendras-blog'),
        'description' => __('Enter your Google Analytics 4 Measurement ID (e.g., G-XXXXXXXXXX)', 'shivendras-blog'),
        'section'     => 'shivendra_analytics_section',
        'type'        => 'text',
        'placeholder' => 'G-XXXXXXXXXX',
    ));

    // Enable/Disable Analytics
    $wp_customize->add_setting('enable_analytics', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('enable_analytics', array(
        'label'       => __('Enable Analytics', 'shivendras-blog'),
        'description' => __('Turn on/off Google Analytics tracking', 'shivendras-blog'),
        'section'     => 'shivendra_analytics_section',
        'type'        => 'checkbox',
    ));

    // Anonymize IP Setting
    $wp_customize->add_setting('analytics_anonymize_ip', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('analytics_anonymize_ip', array(
        'label'       => __('Anonymize IP Addresses', 'shivendras-blog'),
        'description' => __('Enable IP anonymization for GDPR compliance', 'shivendras-blog'),
        'section'     => 'shivendra_analytics_section',
        'type'        => 'checkbox',
    ));

    // Exclude Logged-in Users
    $wp_customize->add_setting('analytics_exclude_admins', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('analytics_exclude_admins', array(
        'label'       => __('Exclude Logged-in Users', 'shivendras-blog'),
        'description' => __('Don\'t track logged-in administrators and editors', 'shivendras-blog'),
        'section'     => 'shivendra_analytics_section',
        'type'        => 'checkbox',
    ));
}
add_action('customize_register', 'shivendra_analytics_customizer');

/**
 * Output Google Analytics tracking code in <head>
 */
function shivendra_google_analytics_code() {
    // Get settings
    $ga_id = get_theme_mod('google_analytics_id', '');
    $enabled = get_theme_mod('enable_analytics', true);
    $anonymize_ip = get_theme_mod('analytics_anonymize_ip', true);
    $exclude_admins = get_theme_mod('analytics_exclude_admins', true);

    // Don't output if:
    // 1. Analytics is disabled
    // 2. No GA ID is set
    // 3. User is logged in and exclude_admins is true
    if (!$enabled || empty($ga_id)) {
        return;
    }

    if ($exclude_admins && is_user_logged_in()) {
        return;
    }

    // Validate GA4 format (G-XXXXXXXXXX)
    if (!preg_match('/^G-[A-Z0-9]+$/', $ga_id)) {
        // Only show warning to admins
        if (current_user_can('manage_options')) {
            echo '<!-- Google Analytics: Invalid Measurement ID format. Please use G-XXXXXXXXXX format. -->' . "\n";
        }
        return;
    }

    // Output GA4 Global Site Tag (gtag.js)
    ?>
    <!-- Google Analytics 4 (GA4) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($ga_id); ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '<?php echo esc_js($ga_id); ?>', {
            <?php if ($anonymize_ip): ?>
            'anonymize_ip': true,
            <?php endif; ?>
            'cookie_flags': 'SameSite=None;Secure'
        });
    </script>
    <!-- End Google Analytics 4 -->
    <?php
}
add_action('wp_head', 'shivendra_google_analytics_code', 10);

/**
 * Add admin notice if GA ID is not set
 * Shows only to administrators on the dashboard
 */
function shivendra_analytics_admin_notice() {
    $screen = get_current_screen();

    // Show only on dashboard
    if ($screen->id !== 'dashboard') {
        return;
    }

    // Show only to administrators
    if (!current_user_can('manage_options')) {
        return;
    }

    // Check if GA ID is set
    $ga_id = get_theme_mod('google_analytics_id', '');
    $enabled = get_theme_mod('enable_analytics', true);

    if ($enabled && empty($ga_id)) {
        ?>
        <div class="notice notice-warning is-dismissible">
            <p>
                <strong>📊 Google Analytics Not Configured</strong><br>
                Go to <strong>Appearance → Customize → Google Analytics</strong> to add your GA4 Measurement ID.
            </p>
        </div>
        <?php
    }
}
add_action('admin_notices', 'shivendra_analytics_admin_notice');

/**
 * HOW TO USE:
 *
 * 1. Get your GA4 Measurement ID:
 *    - Go to https://analytics.google.com
 *    - Create or select a property
 *    - Go to Admin → Data Streams
 *    - Copy your Measurement ID (format: G-XXXXXXXXXX)
 *
 * 2. Add to WordPress:
 *    - Go to WordPress Admin → Appearance → Customize
 *    - Open "Google Analytics" section
 *    - Paste your Measurement ID
 *    - Configure privacy settings
 *    - Click "Publish"
 *
 * 3. Verify Installation:
 *    - Go to Google Analytics → Reports → Realtime
 *    - Visit your website in another tab
 *    - Check if you appear in Realtime report
 *
 * PRIVACY FEATURES:
 * - IP Anonymization for GDPR compliance
 * - Exclude logged-in users from tracking
 * - Easy enable/disable toggle
 * - Cookie compliance settings
 *
 * BENEFITS OVER PLUGINS:
 * - No plugin overhead
 * - Faster page load
 * - Better control
 * - No external dependencies
 * - Clean, minimal code
 */
