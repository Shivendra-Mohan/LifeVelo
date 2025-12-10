<?php
/**
 * Comments Template
 * Modern styled comments section
 */

// Don't load directly
if (!defined('ABSPATH')) {
    exit;
}

// Password protected posts
if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">
    
    <?php if (have_comments()) : ?>
        <h2 class="comments-title">
            <?php
            $comment_count = get_comments_number();
            if ($comment_count === 1) {
                echo '1 Comment';
            } else {
                printf('%s Comments', number_format_i18n($comment_count));
            }
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 0,
                'callback'    => 'shivendra_custom_comment'
            ));
            ?>
        </ol>

        <?php
        // Comment pagination
        if (get_comment_pages_count() > 1 && get_option('page_comments')) :
        ?>
            <nav class="comment-navigation">
                <div class="nav-previous"><?php previous_comments_link('← Older Comments'); ?></div>
                <div class="nav-next"><?php next_comments_link('Newer Comments →'); ?></div>
            </nav>
        <?php endif; ?>

    <?php endif; ?>

    <?php if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>
        <p class="no-comments">Comments are closed.</p>
    <?php endif; ?>

    <?php
    // Custom comment form
    comment_form(array(
        'title_reply'          => 'Leave a Comment',
        'title_reply_to'       => 'Reply to %s',
        'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title">',
        'title_reply_after'    => '</h3>',
        'cancel_reply_link'    => 'Cancel Reply',
        'label_submit'         => 'Post Comment',
        'submit_button'        => '<button type="submit" class="submit-button">%4$s<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg></button>',
        'class_submit'         => 'submit-button',
        'comment_field'        => '<p class="comment-form-comment"><label for="comment">Comment <span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" required></textarea></p>',
        'fields'               => array(
            'author' => '<p class="comment-form-author"><label for="author">Name <span class="required">*</span></label><input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" maxlength="245" required placeholder="Your name" /></p>',
            'email'  => '<p class="comment-form-email"><label for="email">Email <span class="required">*</span></label><input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" maxlength="100" aria-describedby="email-notes" required placeholder="your@email.com" /></p>',
            'cookies' => '',
        ),
    ));
    ?>

</div>
