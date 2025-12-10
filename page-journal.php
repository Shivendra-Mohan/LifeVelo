<?php
/**
 * Template Name: Journal Page
 * Description: Custom template for Journal page
 */

get_header();

// Get current page for pagination
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

// Get the Journal category ID
$journal_category = get_category_by_slug('journal');

// Query journal posts (only from Journal category)
$journal_query_args = array(
    'category_name' => 'journal', // Load only posts from "Journal" category
    'posts_per_page' => 10,
    'paged' => $paged,
    'post_status' => 'publish'
);

$journal_query = new WP_Query($journal_query_args);
?>

<!-- Journal Page Container -->
<div class="journal-alignment">

    <!-- ========================================
         SECTION 1: JOURNAL HEADER
         ======================================== -->
    <section class="journal-header">
        <div class="journal-header-content">
            <h1 class="journal-main-title"><?php the_title(); ?></h1>
            <p class="journal-description">
                Tracing the thoughts and moments that teach me, shape me, and stay with me..
            </p>
        </div>
    </section>

    <!-- ========================================
         SECTION 2: JOURNAL POSTS
         ======================================== -->
    <section class="journal-posts">
        <!-- Posts List -->
        <div class="posts-list">
            <?php
            if ($journal_query->have_posts()) :
                $post_counter = 0;
                while ($journal_query->have_posts()) : $journal_query->the_post();
                    $post_counter++;
                    // Alternate between post-horizontal and post-horizontal-flipped
                    $layout_class = ($post_counter % 2 == 0) ? 'post-horizontal-flipped' : 'post-horizontal';
            ?>

            <article class="blog-post <?php echo $layout_class; ?>">
                <!-- Post Thumbnail -->
                <div class="post-thumbnail">
                    <a href="<?php the_permalink(); ?>">
                        <?php
                        if (has_post_thumbnail()) {
                            the_post_thumbnail('large', array('class' => 'post-featured-img'));
                        }
                        ?>
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

                <!-- Post Content -->
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
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>
                </div>
            </article>

            <?php
                endwhile;
            else :
            ?>
                <p class="no-posts-message">No journal entries found.</p>
            <?php
            endif;
            ?>
        </div>
    </section>

    <!-- ========================================
         SECTION 3: PAGINATION
         ======================================== -->
    <section class="journal-pagination">
        <?php if ($journal_query->max_num_pages > 1) : ?>
        <div class="pagination">
            <?php
            echo paginate_links(array(
                'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                'format' => '?paged=%#%',
                'current' => max(1, $paged),
                'total' => $journal_query->max_num_pages,
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

<?php
wp_reset_postdata();
get_footer();
?>
