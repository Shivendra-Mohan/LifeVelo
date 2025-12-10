<?php
/**
 * The template for displaying search results pages
 */

get_header();
?>

<div class="content">
    
    <!-- Search Header -->
    <div class="archive-header">
        <div class="archive-header-content">
            
            <h1 class="archive-title">
                <?php printf( esc_html__( 'Search Results for: %s', 'shivendra-blog' ), '<span>' . get_search_query() . '</span>' ); ?>
            </h1>
            
            <!-- Result Count -->
            <?php
            global $wp_query;
            $total_posts = $wp_query->found_posts;
            ?>
            <div class="archive-meta">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <span><?php echo $total_posts; ?> <?php echo ($total_posts == 1) ? 'Result' : 'Results'; ?> found</span>
            </div>
        </div>
    </div>
    
    <!-- Posts Grid -->
    <div class="posts-list">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <?php get_template_part( 'template-parts/content', 'post' ); ?>
            <?php endwhile; ?>
        <?php else : ?>
            <div class="no-posts">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <h3>No results found</h3>
                <p>Sorry, but nothing matched your search terms. Please try again with some different keywords.</p>
                
                <!-- Search Form -->
                <div class="search-box-404" style="max-width: 500px; margin: 0 auto;">
                    <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <div class="search-input-wrapper" style="margin-bottom: 0;">
                            <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <input type="search" class="search-modal-input" placeholder="<?php echo esc_attr_x( 'Search again...', 'placeholder', 'shivendra-blog' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                        </div>
                    </form>
                </div>
                
                <div style="margin-top: 30px;">
                    <a href="<?php echo home_url(); ?>" class="btn-home">
                        Back to Home
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Pagination -->
    <?php if ( have_posts() ) : ?>
        <div class="pagination">
            <?php
            the_posts_pagination( array(
                'mid_size'  => 2,
                'prev_text' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg> Previous',
                'next_text' => 'Next <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>',
            ) );
            ?>
        </div>
    <?php endif; ?>
    
</div>

<?php get_footer(); ?>
