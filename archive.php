<?php
/**
 * Archive Template (for date-based archives, tags, etc.)
 * Falls back for any archive that doesn't have its own template
 */
get_header();
?>

<div class="content">
    
    <!-- Archive Header -->
    <div class="archive-header">
        <div class="archive-header-content">
            
            <!-- Archive Title -->
            <h1 class="archive-title"><?php the_archive_title(); ?></h1>
            
            <!-- Archive Description -->
            <?php if (get_the_archive_description()) : ?>
                <div class="archive-description"><?php the_archive_description(); ?></div>
            <?php endif; ?>
            
            <!-- Post Count -->
            <?php
            global $wp_query;
            $total_posts = $wp_query->found_posts;
            ?>
            <div class="archive-meta">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                <span><?php echo $total_posts; ?> <?php echo ($total_posts == 1) ? 'Post' : 'Posts'; ?></span>
            </div>
        </div>
    </div>
    
    <!-- Posts Grid -->
    <div class="posts-list">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <?php get_template_part('template-parts/content', 'post'); ?>
            <?php endwhile; ?>
        <?php else : ?>
            <div class="no-posts">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <h3>No posts found</h3>
                <p>Check back later for new content!</p>
                <a href="<?php echo home_url(); ?>" class="btn-home">
                    Back to Home
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Pagination -->
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
    
</div>

<?php get_footer(); ?>
