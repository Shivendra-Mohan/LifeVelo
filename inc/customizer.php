<?php
/**
 * Theme Customizer Settings
 *
 * Registers all WordPress Customizer options including:
 * - Profile information
 * - Social media links
 * - Contact information for various pages
 * - Brevo Newsletter API settings
 *
 * @package Shivendra_Blog
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register customizer settings
 *
 * @param WP_Customize_Manager $wp_customize WordPress Customizer object
 */
function shivendras_blog_customize_register($wp_customize) {

    // 0. Site Logo/Branding Section
    $wp_customize->add_section('shivendra_logo_section', array(
        'title'    => __('Site Logo & Branding', 'shivendras-blog'),
        'priority' => 25,
        'description' => __('Choose between an image logo or text logo for your header', 'shivendras-blog'),
    ));

    // Logo Type: Image or Text
    $wp_customize->add_setting('logo_type', array(
        'default'   => 'image',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('logo_type', array(
        'label'    => __('Logo Type', 'shivendras-blog'),
        'section'  => 'shivendra_logo_section',
        'type'     => 'radio',
        'choices'  => array(
            'image' => __('Image/Icon Logo', 'shivendras-blog'),
            'text'  => __('Text Logo', 'shivendras-blog'),
        ),
    ));

    // Logo Image Upload
    $wp_customize->add_setting('site_logo', array(
        'default'   => get_template_directory_uri() . '/assets/images/profile.webp',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'site_logo', array(
        'label'    => __('Logo Image/Icon', 'shivendras-blog'),
        'description' => __('Upload your site logo or icon (PNG, SVG, or WebP recommended)', 'shivendras-blog'),
        'section'  => 'shivendra_logo_section',
        'settings' => 'site_logo',
        'active_callback' => function() {
            return get_theme_mod('logo_type', 'image') === 'image';
        },
    )));

    // Text Logo Content
    $wp_customize->add_setting('text_logo', array(
        'default'   => 'LifeVelo',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('text_logo', array(
        'label'    => __('Text Logo', 'shivendras-blog'),
        'description' => __('Enter text for your logo (e.g., site name or initials)', 'shivendras-blog'),
        'section'  => 'shivendra_logo_section',
        'type'     => 'text',
        'active_callback' => function() {
            return get_theme_mod('logo_type', 'image') === 'text';
        },
    ));

    // Logo Size (for image logos)
    $wp_customize->add_setting('logo_size', array(
        'default'   => '40',
        'transport' => 'refresh',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('logo_size', array(
        'label'       => __('Logo Size (px)', 'shivendras-blog'),
        'description' => __('Size of the logo in pixels (default: 40px)', 'shivendras-blog'),
        'section'     => 'shivendra_logo_section',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 20,
            'max'  => 100,
            'step' => 5,
        ),
        'active_callback' => function() {
            return get_theme_mod('logo_type', 'image') === 'image';
        },
    ));

    // Text Logo Font Size
    $wp_customize->add_setting('text_logo_size', array(
        'default'   => '24',
        'transport' => 'refresh',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('text_logo_size', array(
        'label'       => __('Text Logo Font Size (px)', 'shivendras-blog'),
        'description' => __('Font size of the text logo (default: 24px)', 'shivendras-blog'),
        'section'     => 'shivendra_logo_section',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 16,
            'max'  => 48,
            'step' => 2,
        ),
        'active_callback' => function() {
            return get_theme_mod('logo_type', 'image') === 'text';
        },
    ));

    // Text Logo Font Weight
    $wp_customize->add_setting('text_logo_weight', array(
        'default'   => '700',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('text_logo_weight', array(
        'label'    => __('Text Logo Font Weight', 'shivendras-blog'),
        'section'  => 'shivendra_logo_section',
        'type'     => 'select',
        'choices'  => array(
            '400' => __('Normal', 'shivendras-blog'),
            '500' => __('Medium', 'shivendras-blog'),
            '600' => __('Semi-Bold', 'shivendras-blog'),
            '700' => __('Bold', 'shivendras-blog'),
            '800' => __('Extra Bold', 'shivendras-blog'),
            '900' => __('Black', 'shivendras-blog'),
        ),
        'active_callback' => function() {
            return get_theme_mod('logo_type', 'image') === 'text';
        },
    ));

    // 1. Profile Section
    $wp_customize->add_section('shivendra_profile_section', array(
        'title'    => __('Profile Info', 'shivendras-blog'),
        'priority' => 30,
    ));

    // Profile Image
    $wp_customize->add_setting('profile_image', array(
        'default'   => get_template_directory_uri() . '/assets/images/profile.webp',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'profile_image', array(
        'label'    => __('Profile Image', 'shivendras-blog'),
        'section'  => 'shivendra_profile_section',
        'settings' => 'profile_image',
        'priority' => 5,
    )));

    // Profile Name
    $wp_customize->add_setting('profile_name', array(
        'default'   => get_bloginfo('name'),
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('profile_name', array(
        'label'    => __('Profile Name', 'shivendras-blog'),
        'section'  => 'shivendra_profile_section',
        'type'     => 'text',
    ));

    // Tagline
    $wp_customize->add_setting('profile_tagline', array(
        'default'   => 'Video Editor & Storyteller',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('profile_tagline', array(
        'label'    => __('Primary Tagline', 'shivendras-blog'),
        'section'  => 'shivendra_profile_section',
        'type'     => 'text',
    ));

    // Secondary Tagline
    $wp_customize->add_setting('profile_tagline_secondary', array(
        'default'   => 'Documenting My Journey',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('profile_tagline_secondary', array(
        'label'    => __('Secondary Tagline', 'shivendras-blog'),
        'section'  => 'shivendra_profile_section',
        'type'     => 'text',
    ));

    // Location
    $wp_customize->add_setting('profile_location', array(
        'default'   => 'Mumbai, India',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('profile_location', array(
        'label'    => __('Location', 'shivendras-blog'),
        'section'  => 'shivendra_profile_section',
        'type'     => 'text',
    ));

    // Email Address
    $wp_customize->add_setting('profile_email', array(
        'default'   => get_option('admin_email'),
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('profile_email', array(
        'label'    => __('Contact Email', 'shivendras-blog'),
        'section'  => 'shivendra_profile_section',
        'type'     => 'email',
    ));

    // 2. Social Media Section
    $wp_customize->add_section('shivendra_social_section', array(
        'title'    => __('Social Media Links', 'shivendras-blog'),
        'priority' => 31,
        'description' => 'Leave URL empty to hide the icon.',
    ));

    $social_networks = array(
        'youtube'   => 'YouTube URL',
        'instagram' => 'Instagram URL',
        'linkedin'  => 'LinkedIn URL',
        'imdb'      => 'IMDb URL',
    );

    foreach ($social_networks as $id => $label) {
        $wp_customize->add_setting("social_$id", array(
            'default'   => '',
            'transport' => 'refresh',
        ));
        $wp_customize->add_control("social_$id", array(
            'label'    => __($label, 'shivendras-blog'),
            'section'  => 'shivendra_social_section',
            'type'     => 'url',
        ));
    }

    // 3. Privacy Policy Contact Section
    $wp_customize->add_section('privacy_policy_contact', array(
        'title'       => __('Privacy Policy Contact', 'shivendras-blog'),
        'description' => __('Contact information displayed on the Privacy Policy page', 'shivendras-blog'),
        'priority'    => 130,
    ));

    $wp_customize->add_setting('privacy_contact_email', array(
        'default'           => 'your-email@example.com',
        'sanitize_callback' => 'sanitize_email',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('privacy_contact_email', array(
        'label'       => __('Contact Email', 'shivendras-blog'),
        'description' => __('Email address for privacy-related inquiries', 'shivendras-blog'),
        'section'     => 'privacy_policy_contact',
        'type'        => 'email',
        'priority'    => 10,
    ));

    $wp_customize->add_setting('privacy_discord_link', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('privacy_discord_link', array(
        'label'       => __('Discord Link', 'shivendras-blog'),
        'description' => __('Discord server invitation link (optional)', 'shivendras-blog'),
        'section'     => 'privacy_policy_contact',
        'type'        => 'url',
        'priority'    => 20,
    ));

    // 4. Terms of Use Contact Section
    $wp_customize->add_section('terms_of_use_contact', array(
        'title'       => __('Terms of Use Contact', 'shivendras-blog'),
        'description' => __('Contact information displayed on the Terms of Use page', 'shivendras-blog'),
        'priority'    => 131,
    ));

    $wp_customize->add_setting('terms_contact_email', array(
        'default'           => 'your-email@example.com',
        'sanitize_callback' => 'sanitize_email',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('terms_contact_email', array(
        'label'       => __('Contact Email', 'shivendras-blog'),
        'description' => __('Email address for terms-related inquiries', 'shivendras-blog'),
        'section'     => 'terms_of_use_contact',
        'type'        => 'email',
        'priority'    => 10,
    ));

    $wp_customize->add_setting('terms_discord_link', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('terms_discord_link', array(
        'label'       => __('Discord Link', 'shivendras-blog'),
        'description' => __('Discord server invitation link (optional)', 'shivendras-blog'),
        'section'     => 'terms_of_use_contact',
        'type'        => 'url',
        'priority'    => 20,
    ));

    // 5. About Page Contact Section
    $wp_customize->add_section('about_page_contact', array(
        'title'       => __('About Page Contact', 'shivendras-blog'),
        'description' => __('Contact information displayed on the About page', 'shivendras-blog'),
        'priority'    => 132,
    ));

    $wp_customize->add_setting('about_contact_email', array(
        'default'           => 'your-email@example.com',
        'sanitize_callback' => 'sanitize_email',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('about_contact_email', array(
        'label'       => __('Contact Email', 'shivendras-blog'),
        'description' => __('Email address for general inquiries', 'shivendras-blog'),
        'section'     => 'about_page_contact',
        'type'        => 'email',
        'priority'    => 10,
    ));

    $wp_customize->add_setting('about_discord_link', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('about_discord_link', array(
        'label'       => __('Discord Link', 'shivendras-blog'),
        'description' => __('Discord server invitation link (optional)', 'shivendras-blog'),
        'section'     => 'about_page_contact',
        'type'        => 'url',
        'priority'    => 20,
    ));

    // 6. Brevo Newsletter API Section
    $wp_customize->add_section('brevo_api_settings', array(
        'title'       => __('Brevo Newsletter API', 'shivendras-blog'),
        'description' => __('Configure Brevo (Sendinblue) API for newsletter subscriptions. Get your API key from: https://app.brevo.com/settings/keys/api', 'shivendras-blog'),
        'priority'    => 140,
    ));

    $wp_customize->add_setting('brevo_api_key', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('brevo_api_key', array(
        'label'       => __('Brevo API Key', 'shivendras-blog'),
        'description' => __('Your Brevo API key (starts with xkeysib-)', 'shivendras-blog'),
        'section'     => 'brevo_api_settings',
        'type'        => 'text',
        'priority'    => 10,
    ));

    $wp_customize->add_setting('brevo_list_id', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('brevo_list_id', array(
        'label'       => __('Brevo List ID', 'shivendras-blog'),
        'description' => __('The ID of your Brevo contact list (numeric value)', 'shivendras-blog'),
        'section'     => 'brevo_api_settings',
        'type'        => 'number',
        'priority'    => 20,
    ));
}
add_action('customize_register', 'shivendras_blog_customize_register');
