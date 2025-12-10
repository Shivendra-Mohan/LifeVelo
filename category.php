<?php
/**
 * Category Archive Template
 * Displays all posts from a specific category
 */
get_header();

// Get current category
$category = get_queried_object();

// Pagination
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
?>

<!-- Category Archive Container -->
<div class="category-archive-alignment">

    <!-- ========================================
         SECTION 1: CATEGORY HEADER
         ======================================== -->
    <section class="category-archive-header">
        <div class="category-archive-header-content">
            <h1 class="category-archive-title"><?php echo esc_html($category->name); ?></h1>

            <p class="category-archive-description">
                <?php
                if ($category->description) {
                    echo esc_html($category->description);
                } else {
                    // Fallback description
                    echo 'Exploring ' . esc_html($category->name) . ' - stories, insights, and adventures.';
                }
                ?>
            </p>
        </div>
    </section>

    <!-- ========================================
         SECTION 2: CATEGORY POSTS
         ======================================== -->
    <section class="category-archive-posts">
        <!-- Posts list -->
        <div class="posts-list">
            <?php if (have_posts()) : ?>
                <?php
                $post_counter = 0;
                while (have_posts()) : the_post();
                    $post_counter++;
                    // Alternate between post-horizontal and post-horizontal-flipped
                    $layout_class = ($post_counter % 2 == 0) ? 'post-horizontal-flipped' : 'post-horizontal';
                    set_query_var('layout_class', $layout_class);

                    get_template_part('template-parts/content', 'post');
                endwhile;
                ?>
            <?php else : ?>
                <div class="category-archive-no-posts">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <h3>No posts found in this category</h3>
                    <p>Check back later for new content!</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- ========================================
         SECTION 3: PAGINATION
         ======================================== -->
    <section class="category-archive-pagination">
        <?php if (have_posts()) : ?>
        <div class="pagination">
            <?php
            the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg> Previous',
                'next_text' => 'Next <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>',
            ));
            ?>
        </div>
        <?php endif; ?>
    </section>

</div>

<?php get_footer(); ?>
