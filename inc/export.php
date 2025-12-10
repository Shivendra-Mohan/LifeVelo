<?php
/**
 * Newsletter Subscribers Export
 *
 * Provides admin functionality to export newsletter subscribers to CSV.
 * Only accessible to users with 'manage_options' capability (administrators).
 *
 * @package Shivendra_Blog
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Export Newsletter Subscribers to CSV
 *
 * Allows administrators to export all newsletter subscribers from the local database.
 *
 * Usage: Add ?export_subscribers=1 to any admin URL
 * Example: yoursite.com/wp-admin/?export_subscribers=1
 *
 * The CSV file will be automatically downloaded with format:
 * - Email
 * - Subscribed Date
 *
 * @since 1.0.0
 */
function shivendra_export_subscribers() {
    // Only allow admins to export
    if (!current_user_can('manage_options')) {
        return;
    }

    // Check if export is requested
    if (!isset($_GET['export_subscribers'])) {
        return;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'newsletter_subscribers';

    // Get all subscribers
    $subscribers = $wpdb->get_results(
        "SELECT email, subscribed_date FROM $table_name ORDER BY subscribed_date DESC"
    );

    if (empty($subscribers)) {
        wp_die('No subscribers found to export.');
    }

    // Set headers for CSV download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=newsletter-subscribers-' . date('Y-m-d') . '.csv');

    // Create output stream
    $output = fopen('php://output', 'w');

    // Add CSV headers
    fputcsv($output, array('Email', 'Subscribed Date'));

    // Add subscriber data
    foreach ($subscribers as $subscriber) {
        fputcsv($output, array(
            $subscriber->email,
            $subscriber->subscribed_date
        ));
    }

    fclose($output);
    exit;
}
add_action('admin_init', 'shivendra_export_subscribers');
