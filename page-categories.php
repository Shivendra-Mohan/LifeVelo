<?php
/**
 * Template Name: Categories Page
 * Description: Custom template for Categories page - displays all blog categories
 */

get_header();
?>

<div class="category-alignment">

    <section class="all-category">
        <div class="category-pills-container">
            <?php
            // Get current selected category from URL
            $selected_cat = isset($_GET['cat']) ? intval($_GET['cat']) : 0;

            // "All Categories" pill
            $all_active = ($selected_cat == 0) ? 'active' : '';
            $all_posts_count = wp_count_posts()->publish;
            ?>
            <a href="<?php echo get_permalink(); ?>" class="category-pill <?php echo $all_active; ?>">
                All Categories
                <span class="pill-count"><?php echo $all_posts_count; ?></span>
            </a>

            <?php
            // Get all categories (including Uncategorized)
            $categories = get_categories(array(
                'orderby' => 'name',
                'order'   => 'ASC',
                'hide_empty' => true
            ));

            if (!empty($categories)) :
                foreach ($categories as $category) :
                    $is_active = ($selected_cat == $category->term_id) ? 'active' : '';
            ?>
                <a href="<?php echo add_query_arg('cat', $category->term_id, get_permalink()); ?>"
                   class="category-pill <?php echo $is_active; ?>">
                    <?php echo esc_html($category->name); ?>
                    <span class="pill-count"><?php echo $category->count; ?></span>
                </a>
            <?php
                endforeach;
            endif;
            ?>
        </div>
    </section>

    <section class="category-posts">
        <?php
        // Get selected category from URL
        $selected_cat = isset($_GET['cat']) ? intval($_GET['cat']) : 0;
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        // Query args based on selected category
        if ($selected_cat) {
            // Specific category selected
            $query_args = array(
                'cat' => $selected_cat,
                'posts_per_page' => 10,
                'paged' => $paged,
                'post_status' => 'publish'
            );
        } else {
            // All Categories selected (no category filter)
            $query_args = array(
                'posts_per_page' => 10,
                'paged' => $paged,
                'post_status' => 'publish'
            );
        }

        $cat_query = new WP_Query($query_args);

        if ($cat_query->have_posts()) :
        ?>
            <div class="grid-divider"></div>
            <div class="posts-list">
                <?php
                $post_counter = 0;
                while ($cat_query->have_posts()) : $cat_query->the_post();
                    $post_counter++;
                    // Alternate between post-horizontal and post-horizontal-flipped
                    $layout_class = ($post_counter % 2 == 0) ? 'post-horizontal-flipped' : 'post-horizontal';
                ?>
                    <article class="blog-post <?php echo $layout_class; ?>">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('large', array('class' => 'post-featured-img')); ?>
                                </a>
                                <!-- Category Badge -->
                                <?php
                                $categories = get_the_category();
                                if (!empty($categories)) {
                                    ?>
                                    <div class="post-categories">
                                        <?php
                                        $category = $categories[0];
                                        ?>
                                        <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="category-badge">
                                            <?php echo esc_html($category->name); ?>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php endif; ?>
                        <div class="post-content-wrapper">
                            <h2 class="post-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
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
                            <div class="post-excerpt">
                                <?php echo wp_trim_words(get_the_excerpt(), 25, '...'); ?>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="read-more-link">
                                Read Full Story
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        <?php
        else :
            echo '<p class="no-posts-message">No posts found.</p>';
        endif;
        wp_reset_postdata();
        ?>
    </section>

    <section class="category-pagination">
        <?php if (isset($cat_query) && $cat_query->max_num_pages > 1) : ?>
        <div class="pagination">
            <?php
            echo paginate_links(array(
                'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                'format' => '?paged=%#%',
                'current' => max(1, $paged),
                'total' => $cat_query->max_num_pages,
                'prev_text' => '← Prev',
                'next_text' => 'Next →',
                'type' => 'list',
                'mid_size' => 2,
            ));
            ?>
        </div>
        <?php endif; ?>
    </section>

</div>

<?php get_footer(); ?>
