<?php
/**
 * Template part for displaying posts in horizontal layout
 * Used by category.php and other archive templates
 *
 * Expects 'layout_class' query var to be set (post-horizontal or post-horizontal-flipped)
 */

// Get layout class from query var (set by parent template)
$layout_class = get_query_var('layout_class', 'post-horizontal');
?>

<article class="blog-post <?php echo esc_attr($layout_class); ?>">

    <!-- Post Thumbnail -->
    <?php if (has_post_thumbnail()) : ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('large', array('class' => 'post-featured-img')); ?>
            </a>

            <!-- Category Badge -->
            <?php
            $categories = get_the_category();
            if (!empty($categories)) :
                $category = $categories[0]; // Show first category only
            ?>
                <div class="post-categories">
                    <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="category-badge">
                        <?php echo esc_html($category->name); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Post Content -->
    <div class="post-content-wrapper">

        <!-- Post Title -->
        <h2 class="post-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>

        <!-- Post Meta (Date & Read Time) -->
        <div class="post-meta">
            <span class="post-date">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <?php echo get_the_date('M j, Y'); ?>
            </span>
            <span class="post-reading-time">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                </svg>
                <?php echo ceil(str_word_count(get_the_content()) / 200); ?> min read
            </span>
        </div>

        <!-- Excerpt -->
        <div class="post-excerpt">
            <?php echo wp_trim_words(get_the_excerpt(), 25, '...'); ?>
        </div>

        <!-- Read More Link -->
        <a href="<?php the_permalink(); ?>" class="read-more-link">
            Read Full Story
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
        </a>

    </div>

</article>
