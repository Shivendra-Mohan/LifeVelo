<?php
/**
 * Helper Functions
 *
 * Various utility and helper functions used throughout the theme.
 *
 * @package Shivendra_Blog
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Calculate Reading Time
 *
 * Calculates estimated reading time for a post based on word count.
 * Assumes average reading speed of 200 words per minute.
 *
 * @since 1.0.0
 * @return int Reading time in minutes
 */
function shivendra_reading_time() {
    $content = get_post_field('post_content', get_the_ID());
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // Average reading speed: 200 words per minute
    return $reading_time;
}

/**
 * Custom Comment Display Template
 *
 * Custom callback function for displaying comments with enhanced styling.
 * Used in wp_list_comments() for custom comment layout.
 *
 * @since 1.0.0
 *
 * @param object $comment The comment object
 * @param array $args Comment display arguments
 * @param int $depth Depth of comment in thread
 */
function shivendra_custom_comment($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
?>
    <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
        <article class="comment-body">
            <div class="comment-content-wrapper">
                <div class="comment-meta">
                    <span class="comment-author-name">
                        <?php echo get_comment_author_link(); ?>
                    </span>
                    <span class="comment-date">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <?php echo get_comment_date('M j, Y'); ?> at <?php echo get_comment_time(); ?>
                    </span>
                </div>

                <div class="comment-text">
                    <?php comment_text(); ?>
                </div>

                <?php if ($comment->comment_approved == '0') : ?>
                    <p class="comment-awaiting-moderation">Your comment is awaiting moderation.</p>
                <?php endif; ?>

                <div class="comment-reply">
                    <?php
                    comment_reply_link(array_merge($args, array(
                        'depth'     => $depth,
                        'max_depth' => $args['max_depth'],
                        'reply_text' => 'Reply'
                    )));
                    ?>
                </div>
            </div>
        </article>
<?php
}

/**
 * Featured Image Recommendation Notice (Block Editor)
 *
 * Displays a helpful notice in the WordPress block editor recommending
 * the ideal featured image size (16:9 aspect ratio).
 *
 * @since 1.0.0
 */
function shivendra_featured_image_notice_block_editor() {
    $screen = get_current_screen();

    // Only show on post edit screens
    if ($screen && $screen->post_type === 'post' && $screen->is_block_editor) {
        ?>
        <script type="text/javascript">
        (function() {
            // Wait for the editor to be ready
            var checkInterval = setInterval(function() {
                // Target the featured image panel in block editor
                var featuredImagePanel = document.querySelector('.editor-post-featured-image');

                if (featuredImagePanel && !document.getElementById('featured-image-notice')) {
                    clearInterval(checkInterval);

                    var noticeDiv = document.createElement('div');
                    noticeDiv.id = 'featured-image-notice';
                    noticeDiv.style.cssText = 'background: #fff3cd; border: 1px solid #ffc107; border-radius: 4px; padding: 12px; margin: 16px 0; color: #856404; font-size: 13px; line-height: 1.6;';
                    noticeDiv.innerHTML = '<strong style="font-size: 14px;">Recommended Image Size:</strong><br>' +
                        'Use images with <strong>16:9 aspect ratio</strong> (e.g., 1920x1080px or 1600x900px) for best results.<br>' +
                        '<span style="font-size: 12px; opacity: 0.8;">Images will be automatically cropped to fit this ratio on the website.</span>';

                    // Insert the notice at the top of the featured image panel
                    featuredImagePanel.insertBefore(noticeDiv, featuredImagePanel.firstChild);
                }
            }, 500);

            // Clear interval after 10 seconds to avoid infinite checking
            setTimeout(function() {
                clearInterval(checkInterval);
            }, 10000);
        })();
        </script>
        <?php
    }
}
add_action('admin_footer', 'shivendra_featured_image_notice_block_editor');
