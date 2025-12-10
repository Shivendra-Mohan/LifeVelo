<?php
/**
 * Newsletter Admin Management Page
 *
 * Provides admin interface to view and manage newsletter subscribers
 *
 * @package Shivendra_Blog
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add admin menu for Newsletter Subscribers
 */
function shivendra_add_newsletter_admin_menu() {
    add_menu_page(
        'Newsletter Subscribers',           // Page title
        'Newsletter',                        // Menu title
        'manage_options',                    // Capability
        'newsletter-subscribers',            // Menu slug
        'shivendra_newsletter_admin_page',   // Callback function
        'dashicons-email-alt',               // Icon
        30                                   // Position
    );
}
add_action('admin_menu', 'shivendra_add_newsletter_admin_menu');

/**
 * Handle delete actions
 */
function shivendra_handle_newsletter_deletions() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'newsletter_subscribers';

    // Check if user has permission
    if (!current_user_can('manage_options')) {
        return;
    }

    // Handle individual subscriber deletion
    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['subscriber_id'])) {
        // Verify nonce
        if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'delete_subscriber_' . $_GET['subscriber_id'])) {
            wp_die('Security check failed');
        }

        $subscriber_id = intval($_GET['subscriber_id']);
        $wpdb->delete($table_name, array('id' => $subscriber_id), array('%d'));

        // Redirect with success message
        wp_redirect(add_query_arg(array('page' => 'newsletter-subscribers', 'deleted' => '1'), admin_url('admin.php')));
        exit;
    }

    // Handle delete all subscribers
    if (isset($_POST['delete_all_subscribers']) && isset($_POST['confirm_delete_all'])) {
        // Verify nonce
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'delete_all_subscribers')) {
            wp_die('Security check failed');
        }

        $wpdb->query("TRUNCATE TABLE $table_name");

        // Redirect with success message
        wp_redirect(add_query_arg(array('page' => 'newsletter-subscribers', 'deleted_all' => '1'), admin_url('admin.php')));
        exit;
    }

    // Handle bulk delete
    if (isset($_POST['delete_selected']) && isset($_POST['subscriber_ids']) && is_array($_POST['subscriber_ids'])) {
        // Verify nonce
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'bulk_delete_subscribers')) {
            wp_die('Security check failed');
        }

        $ids = array_map('intval', $_POST['subscriber_ids']);
        $ids_placeholder = implode(',', array_fill(0, count($ids), '%d'));

        $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id IN ($ids_placeholder)", $ids));

        // Redirect with success message
        wp_redirect(add_query_arg(array('page' => 'newsletter-subscribers', 'bulk_deleted' => count($ids)), admin_url('admin.php')));
        exit;
    }
}
add_action('admin_init', 'shivendra_handle_newsletter_deletions');

/**
 * Render the admin page
 */
function shivendra_newsletter_admin_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'newsletter_subscribers';

    // Get all subscribers ordered by newest first
    $subscribers = $wpdb->get_results("SELECT * FROM $table_name ORDER BY subscribed_date DESC");
    $total_count = count($subscribers);

    ?>
    <div class="wrap">
        <h1>Newsletter Subscribers Management</h1>

        <?php
        // Show success messages
        if (isset($_GET['deleted'])) {
            echo '<div class="notice notice-success is-dismissible"><p>Subscriber deleted successfully!</p></div>';
        }
        if (isset($_GET['deleted_all'])) {
            echo '<div class="notice notice-success is-dismissible"><p>All subscribers deleted successfully!</p></div>';
        }
        if (isset($_GET['bulk_deleted'])) {
            echo '<div class="notice notice-success is-dismissible"><p>' . intval($_GET['bulk_deleted']) . ' subscribers deleted successfully!</p></div>';
        }
        ?>

        <div class="tablenav top">
            <div class="alignleft actions">
                <p><strong>Total Subscribers: <?php echo $total_count; ?></strong></p>
            </div>
        </div>

        <?php if ($total_count > 0): ?>
            <form method="post">
                <?php wp_nonce_field('bulk_delete_subscribers', '_wpnonce'); ?>

                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <td class="manage-column column-cb check-column">
                                <input type="checkbox" id="select-all" />
                            </td>
                            <th style="width: 50px;">ID</th>
                            <th>Email Address</th>
                            <th>Subscribed Date</th>
                            <th style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($subscribers as $subscriber): ?>
                            <tr>
                                <th scope="row" class="check-column">
                                    <input type="checkbox" name="subscriber_ids[]" value="<?php echo esc_attr($subscriber->id); ?>" />
                                </th>
                                <td><?php echo esc_html($subscriber->id); ?></td>
                                <td><?php echo esc_html($subscriber->email); ?></td>
                                <td><?php echo esc_html($subscriber->subscribed_date); ?></td>
                                <td>
                                    <a href="<?php echo wp_nonce_url(add_query_arg(array('action' => 'delete', 'subscriber_id' => $subscriber->id)), 'delete_subscriber_' . $subscriber->id); ?>"
                                       class="button button-small"
                                       onclick="return confirm('Are you sure you want to delete this subscriber?');">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="tablenav bottom" style="margin-top: 20px;">
                    <button type="submit" name="delete_selected" class="button button-secondary" onclick="return confirm('Are you sure you want to delete the selected subscribers?');">
                        Delete Selected
                    </button>
                </div>
            </form>

            <!-- Delete All Section -->
            <div style="margin-top: 40px; padding: 20px; background: #fff; border-left: 4px solid #dc3232;">
                <h2 style="margin-top: 0; color: #dc3232;">⚠️ Danger Zone</h2>
                <p>Delete all subscribers from the database. This action cannot be undone!</p>

                <form method="post" onsubmit="return confirm('⚠️ WARNING: This will permanently delete ALL subscribers!\n\nTotal subscribers to be deleted: <?php echo $total_count; ?>\n\nAre you absolutely sure you want to continue?');">
                    <?php wp_nonce_field('delete_all_subscribers', '_wpnonce'); ?>
                    <label>
                        <input type="checkbox" name="confirm_delete_all" required />
                        I understand this will permanently delete all <?php echo $total_count; ?> subscribers
                    </label>
                    <br><br>
                    <button type="submit" name="delete_all_subscribers" class="button button-primary" style="background: #dc3232; border-color: #dc3232;">
                        🗑️ Delete All Subscribers
                    </button>
                </form>
            </div>

        <?php else: ?>
            <div class="notice notice-info">
                <p>No subscribers found in the database.</p>
            </div>
        <?php endif; ?>

        <!-- Instructions Section -->
        <div style="margin-top: 30px; padding: 15px; background: #f0f0f1; border-left: 4px solid #2271b1;">
            <h3>📖 Instructions</h3>
            <ul>
                <li><strong>Individual Delete:</strong> Click "Delete" button next to any subscriber</li>
                <li><strong>Bulk Delete:</strong> Check multiple subscribers and click "Delete Selected"</li>
                <li><strong>Delete All:</strong> Use the Danger Zone section to remove all subscribers at once</li>
                <li><strong>Export:</strong> Visit <code><?php echo home_url('/wp-admin/?export_subscribers=1'); ?></code> to download CSV</li>
            </ul>
        </div>
    </div>

    <script>
        // Select all checkbox functionality
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[name="subscriber_ids[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });
    </script>

    <style>
        .wp-list-table th,
        .wp-list-table td {
            padding: 12px 10px;
        }
        .wp-list-table tbody tr:hover {
            background: #f6f7f7;
        }
    </style>
    <?php
}
