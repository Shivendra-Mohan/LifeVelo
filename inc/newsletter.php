<?php
/**
 * Newsletter Subscription Handler
 *
 * Handles newsletter subscriptions with dual-save approach:
 * - Saves to local WordPress database (master/backup)
 * - Optionally syncs to Brevo (Sendinblue) API if configured
 *
 * @package Shivendra_Blog
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Create newsletter subscribers database table
 *
 * Creates a custom table to store newsletter subscribers locally.
 * This runs automatically when the theme is activated.
 *
 * @since 1.0.0
 */
function shivendras_blog_create_newsletter_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'newsletter_subscribers';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        email varchar(100) NOT NULL,
        subscribed_date datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id),
        UNIQUE KEY email (email)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
add_action('after_switch_theme', 'shivendras_blog_create_newsletter_table');

/**
 * Handle newsletter subscription requests
 *
 * AJAX handler for newsletter subscriptions.
 * Implements dual-save approach:
 * 1. Always saves to local database (primary storage)
 * 2. Optionally syncs to Brevo API if configured (bonus feature)
 *
 * @since 1.0.0
 */
function shivendras_blog_newsletter_subscribe() {
    // Security check
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'newsletter_nonce')) {
        wp_send_json_error(array('message' => 'Security check failed'));
    }

    // Validate email
    $email = sanitize_email($_POST['email']);
    if (!is_email($email)) {
        wp_send_json_error(array('message' => 'Please enter a valid email address'));
    }

    // Get optional name field
    $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';

    // =================================================================
    // DUAL SAVE APPROACH: Save to BOTH Local DB + Brevo
    // =================================================================

    // STEP 1: Always save to Local Database (Master/Backup)
    global $wpdb;
    $table_name = $wpdb->prefix . 'newsletter_subscribers';

    // Check if already subscribed in local DB
    $existing = $wpdb->get_var($wpdb->prepare(
        "SELECT email FROM $table_name WHERE email = %s",
        $email
    ));

    if ($existing) {
        wp_send_json_error(array('message' => 'You are already subscribed!'));
    }

    // Insert into local database
    $local_result = $wpdb->insert(
        $table_name,
        array('email' => $email),
        array('%s')
    );

    // If local DB insert fails, stop here
    if (!$local_result) {
        wp_send_json_error(array('message' => 'Something went wrong. Please try again.'));
    }

    // STEP 2: Also send to Brevo (if configured)
    $brevo_api_key = get_theme_mod('brevo_api_key');
    $brevo_list_id = get_theme_mod('brevo_list_id');
    $brevo_success = false;
    $brevo_message = '';

    if (!empty($brevo_api_key) && !empty($brevo_list_id)) {
        // Send to Brevo API (non-blocking - don't fail if Brevo is down)
        $brevo_response = shivendras_blog_add_to_brevo($email, $brevo_api_key, $brevo_list_id, $name);
        $brevo_success = $brevo_response['success'];
        $brevo_message = $brevo_response['message'];

        // Log Brevo failures (optional - for debugging)
        if (!$brevo_success) {
            error_log('Brevo API failed for ' . $email . ': ' . $brevo_message);
        }
    }

    // STEP 3: Send success response
    // Local DB saved successfully, Brevo is bonus
    wp_send_json_success(array(
        'message' => 'Thanks for subscribing!',
        'saved_locally' => true,
        'saved_to_brevo' => $brevo_success
    ));
}

/**
 * Add subscriber to Brevo contact list
 *
 * Sends a contact to Brevo (Sendinblue) via their API.
 * This is optional and only runs if Brevo API credentials are configured.
 *
 * @since 1.0.0
 *
 * @param string $email Email address to subscribe
 * @param string $api_key Brevo API key
 * @param int $list_id Brevo list ID
 * @param string $name Optional name of subscriber
 * @return array Success status and message
 */
function shivendras_blog_add_to_brevo($email, $api_key, $list_id, $name = '') {
    $url = 'https://api.brevo.com/v3/contacts';

    $data = array(
        'email' => $email,
        'listIds' => array((int)$list_id),
        'updateEnabled' => true // Update if contact already exists
    );

    // Add name attribute if provided
    if (!empty($name)) {
        $data['attributes'] = array(
            'FIRSTNAME' => sanitize_text_field($name)
        );
    }

    $args = array(
        'method' => 'POST',
        'headers' => array(
            'accept' => 'application/json',
            'api-key' => $api_key,
            'content-type' => 'application/json'
        ),
        'body' => json_encode($data),
        'timeout' => 15
    );

    $response = wp_remote_post($url, $args);

    if (is_wp_error($response)) {
        return array(
            'success' => false,
            'message' => 'Connection error. Please try again.'
        );
    }

    $status_code = wp_remote_retrieve_response_code($response);
    $body = json_decode(wp_remote_retrieve_body($response), true);

    // Status 201: New contact created
    if ($status_code === 201) {
        return array(
            'success' => true,
            'message' => 'Thanks for subscribing!'
        );
    }

    // Status 204: Contact already exists and was updated
    if ($status_code === 204) {
        return array(
            'success' => false,
            'message' => 'You are already subscribed!'
        );
    }

    // Handle specific error cases
    if ($status_code === 400 && isset($body['code']) && $body['code'] === 'duplicate_parameter') {
        return array(
            'success' => false,
            'message' => 'You are already subscribed!'
        );
    }

    if ($status_code === 401) {
        return array(
            'success' => false,
            'message' => 'API configuration error. Please contact site administrator.'
        );
    }

    // Generic error
    return array(
        'success' => false,
        'message' => 'Something went wrong. Please try again later.'
    );
}

// Register AJAX handlers for newsletter subscription
add_action('wp_ajax_newsletter_subscribe', 'shivendras_blog_newsletter_subscribe');
add_action('wp_ajax_nopriv_newsletter_subscribe', 'shivendras_blog_newsletter_subscribe');
