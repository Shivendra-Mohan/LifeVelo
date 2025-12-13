<?php
/**
 * Discord Webhook Integration
 *
 * Automatically sends new blog posts to Discord channel via webhook
 * when posts are published.
 *
 * @package Shivendra_Blog
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Send new post notification to Discord
 *
 * Triggers when a new post is published (not drafts, not updates)
 * Sends rich embed with post details to Discord channel
 *
 * @param string $new_status New post status
 * @param string $old_status Old post status
 * @param WP_Post $post Post object
 */
function shivendra_discord_notify_new_post($new_status, $old_status, $post) {
    // Only trigger for posts (not pages or other post types)
    if ($post->post_type !== 'post') {
        return;
    }

    // Only send when post is newly published (not updates)
    if ($new_status !== 'publish' || $old_status === 'publish') {
        return;
    }

    // Get Discord webhook URL from customizer
    $webhook_url = get_theme_mod('discord_webhook_url', '');
    $webhook_enabled = get_theme_mod('discord_webhook_enabled', false);

    // Exit if webhook is not enabled or URL is empty
    if (!$webhook_enabled || empty($webhook_url)) {
        return;
    }

    // Prepare post data
    $post_title = get_the_title($post->ID);
    $post_url = get_permalink($post->ID);
    $post_excerpt = get_the_excerpt($post->ID);
    $author_name = get_the_author_meta('display_name', $post->post_author);

    // Get featured image
    $thumbnail_url = '';
    if (has_post_thumbnail($post->ID)) {
        $thumbnail_url = get_the_post_thumbnail_url($post->ID, 'large');
    }

    // Get categories
    $categories = get_the_category($post->ID);
    $category_names = array();
    foreach ($categories as $category) {
        $category_names[] = $category->name;
    }
    $categories_text = !empty($category_names) ? implode(', ', $category_names) : 'Uncategorized';

    // Prepare Discord embed message
    $embed_color = 7506394; // Purple color (hex: #7289DA converted to decimal)

    $embed = array(
        'title' => $post_title,
        'description' => $post_excerpt ? wp_trim_words($post_excerpt, 30, '...') : '',
        'url' => $post_url,
        'color' => $embed_color,
        'timestamp' => get_the_date('c', $post->ID),
        'footer' => array(
            'text' => 'LifeVelo • New Post'
        ),
        'author' => array(
            'name' => $author_name,
            'icon_url' => get_avatar_url($post->post_author)
        ),
        'fields' => array(
            array(
                'name' => '📁 Categories',
                'value' => $categories_text,
                'inline' => true
            ),
            array(
                'name' => '🔗 Read More',
                'value' => '[Click here to read](' . $post_url . ')',
                'inline' => true
            )
        )
    );

    // Add thumbnail if available
    if (!empty($thumbnail_url)) {
        $embed['image'] = array(
            'url' => $thumbnail_url
        );
    }

    // Prepare webhook payload
    $payload = json_encode(array(
        'content' => '📝 **New blog post published!**',
        'embeds' => array($embed)
    ));

    // Send to Discord
    $response = wp_remote_post($webhook_url, array(
        'method' => 'POST',
        'timeout' => 15,
        'headers' => array(
            'Content-Type' => 'application/json'
        ),
        'body' => $payload
    ));

    // Log errors (optional - for debugging)
    if (is_wp_error($response)) {
        error_log('Discord Webhook Error: ' . $response->get_error_message());
    }
}
add_action('transition_post_status', 'shivendra_discord_notify_new_post', 10, 3);

/**
 * Test Discord webhook (for admin testing)
 *
 * Sends a test message to verify webhook is working
 */
function shivendra_discord_test_webhook() {
    // Check nonce and permissions
    if (!isset($_POST['discord_test_nonce']) ||
        !wp_verify_nonce($_POST['discord_test_nonce'], 'discord_test_action') ||
        !current_user_can('manage_options')) {
        wp_die('Unauthorized access');
    }

    $webhook_url = get_theme_mod('discord_webhook_url', '');

    if (empty($webhook_url)) {
        wp_redirect(add_query_arg('discord_test', 'no_url', wp_get_referer()));
        exit;
    }

    // Send test message
    $embed = array(
        'title' => '✅ Discord Webhook Test',
        'description' => 'Your Discord webhook is working correctly! New posts will be automatically shared to this channel.',
        'color' => 3066993, // Green color
        'timestamp' => current_time('c'),
        'footer' => array(
            'text' => 'LifeVelo • Test Message'
        )
    );

    $payload = json_encode(array(
        'content' => '🧪 **Test message from LifeVelo**',
        'embeds' => array($embed)
    ));

    $response = wp_remote_post($webhook_url, array(
        'method' => 'POST',
        'timeout' => 15,
        'headers' => array(
            'Content-Type' => 'application/json'
        ),
        'body' => $payload
    ));

    if (is_wp_error($response)) {
        wp_redirect(add_query_arg('discord_test', 'error', wp_get_referer()));
    } else {
        wp_redirect(add_query_arg('discord_test', 'success', wp_get_referer()));
    }
    exit;
}
add_action('admin_post_discord_test_webhook', 'shivendra_discord_test_webhook');

/**
 * Display admin notices for test results
 */
function shivendra_discord_test_notices() {
    if (!isset($_GET['discord_test'])) {
        return;
    }

    $status = $_GET['discord_test'];

    if ($status === 'success') {
        echo '<div class="notice notice-success is-dismissible"><p><strong>Success!</strong> Discord webhook test message sent successfully.</p></div>';
    } elseif ($status === 'error') {
        echo '<div class="notice notice-error is-dismissible"><p><strong>Error!</strong> Failed to send Discord webhook. Please check your webhook URL.</p></div>';
    } elseif ($status === 'no_url') {
        echo '<div class="notice notice-warning is-dismissible"><p><strong>Warning!</strong> Please enter a Discord webhook URL first.</p></div>';
    }
}
add_action('admin_notices', 'shivendra_discord_test_notices');
