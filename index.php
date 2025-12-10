<?php get_header(); ?>
<a class="skip-link screen-reader-text" href="#posts">Skip to content</a>
<div class="main-wrapper" role="main">

<!-- ========================================
     HERO SECTION - Two Column Layout
     ======================================== -->
<section class="hero-section">
    <div class="hero-container">

        <!-- Left Column: Profile Card -->
        <?php get_template_part('template-parts/profile-card'); ?>

        <!-- Right Column: Featured Posts Carousel -->
        <div class="hero-featured-carousel">
            <?php
            // Get cached carousel posts (6 hour cache)
            $carousel_query = shivendra_get_cached_carousel_posts();

            if ($carousel_query && $carousel_query->have_posts()) :
            ?>

            <div class="carousel-wrapper" role="region" aria-label="Featured posts carousel">
                <div class="carousel-slides" role="list">
                    <?php
                    $slide_index = 0;
                    while ($carousel_query->have_posts()) : $carousel_query->the_post();
                        $slide_index++;
                    ?>

                    <div id="slide-<?php echo $slide_index; ?>" class="carousel-slide <?php echo $slide_index === 1 ? 'active' : ''; ?>" role="listitem">
                        <a href="<?php echo esc_url( get_permalink() ); ?>" class="featured-post-link">
                            <div class="featured-post-image">
                                <?php if ( has_post_thumbnail() ) :
                                    $thumb_id = get_post_thumbnail_id();
                                    echo wp_get_attachment_image( $thumb_id, 'large', false, array(
                                        'class'    => 'featured-image',
                                        'loading'  => $slide_index === 1 ? 'eager' : 'lazy',
                                        'decoding' => 'async',
                                        'alt'      => esc_attr( get_the_title() ),
                                    ) );
                                else : ?>
                                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/default-post.svg' ); ?>"
                                         alt="<?php echo esc_attr( get_the_title() ); ?>"
                                         class="featured-image"
                                         loading="<?php echo $slide_index === 1 ? 'eager' : 'lazy'; ?>">
                                <?php endif; ?>

                                <div class="featured-post-overlay">
                                    <?php
                                    $categories = get_the_category();
                                    if ( ! empty( $categories ) ) :
                                    ?>
                                    <span class="featured-category"><?php echo esc_html( $categories[0]->name ); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="featured-post-content">
                                <h2 class="featured-post-title"><?php echo esc_html( get_the_title() ); ?></h2>
                                <p class="featured-post-excerpt">
                                    <?php echo esc_html( wp_trim_words( get_the_excerpt(), 25, '...' ) ); ?>
                                </p>
                                <span class="featured-read-more">
                                    Read More
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M5 12h14M12 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            </div>
                        </a>
                    </div>

                    <?php endwhile; ?>
                </div>

                <!-- Navigation Arrows -->
                <button class="carousel-prev" aria-label="Previous slide">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M15 18l-6-6 6-6"/>
                    </svg>
                </button>
                <button class="carousel-next" aria-label="Next slide">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </button>

                <!-- Dots Indicator -->
                <div class="carousel-dots" role="tablist" aria-label="Carousel slides">
                    <?php for ( $i = 1; $i <= $slide_index; $i++ ) : ?>
                        <button
                            class="carousel-dot <?php echo $i === 1 ? 'active' : ''; ?>"
                            data-slide="<?php echo $i; ?>"
                            aria-label="<?php echo esc_attr( 'Go to slide ' . $i ); ?>"
                            aria-pressed="<?php echo $i === 1 ? 'true' : 'false'; ?>"
                            role="tab"
                            aria-controls="slide-<?php echo $i; ?>"
                        ></button>
                    <?php endfor; ?>
                </div>
            </div>

            <?php
                wp_reset_postdata();
            endif;
            ?>
        </div>

    </div>
</section>

<!-- ========================================
     BLOG POSTS SECTION
     ========================================= -->
<section class="posts-section" id="posts" role="region" aria-labelledby="posts-title">
    <div class="content">
        
        <!-- Section Heading -->
        <div class="section-header">
            <h2 id="posts-title" class="section-title">Latest Stories</h2>
            <p class="section-subtitle">Cycling adventures, book summaries, fitness experiments, and more</p>
        </div>
        
        <?php if (have_posts()) : ?>
            <div class="posts-list">
                <?php
                $post_counter = 0;
                while (have_posts()) : the_post();
                    $post_counter++;

                    // Add divider before grid section (before post 3, 11, 19...)
                    if ($post_counter % 8 == 3) {
                        echo '<div class="grid-divider"></div>';
                    }

                    // Determine post layout class
                    $position_in_cycle = $post_counter % 8;
                    $layout_class = '';

                    if ($position_in_cycle == 2) {
                        $layout_class = 'post-horizontal-flipped';
                    } elseif ($position_in_cycle >= 3 || $position_in_cycle == 0) {
                        $layout_class = 'post-vertical';
                    } else {
                        $layout_class = 'post-horizontal';
                    }

                    // Set query var for template part
                    set_query_var('layout_class', $layout_class);
                    get_template_part('template-parts/content', 'post');

                    // Add divider after grid section (after post 8, 16, 24...)
                    if ($post_counter % 8 == 0) {
                        echo '<div class="grid-divider"></div>';
                    }
                endwhile;
                ?>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <?php the_posts_pagination( array(
                    'mid_size' => 2,
                    'prev_text' => '← Prev',
                    'next_text' => 'Next →',
                    'screen_reader_text' => 'Posts navigation',
                ) ); ?>
            </div>

        <?php else : ?>
            <div class="no-posts">
                <p>No posts yet! Check back soon for stories about cycling, books, fitness, and more.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

</div> <!-- .main-wrapper -->
<?php get_footer(); ?>
