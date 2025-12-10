<?php
/**
 * 404 Error Page Template
 * Displayed when a page is not found
 */
get_header();
?>

<div class="content">
    
    <div class="error-404-container">
        
        <!-- 404 Illustration -->
        <div class="error-404-illustration">
            <svg width="200" height="200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M16 16s-1.5-2-4-2-4 2-4 2"></path>
                <line x1="9" y1="9" x2="9.01" y2="9"></line>
                <line x1="15" y1="9" x2="15.01" y2="9"></line>
            </svg>
            <div class="error-404-number">404</div>
        </div>
        
        <!-- Error Message -->
        <h1 class="error-404-title">Oops! Page Not Found</h1>
        <p class="error-404-description">
            The page you're looking for seems to have wandered off. 
            It might have been moved, deleted, or maybe it never existed.
        </p>
        
        <!-- Action Buttons -->
        <div class="error-404-actions">
            <a href="<?php echo home_url(); ?>" class="btn-home-404">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                Back to Home
            </a>
            <button class="btn-search-404" onclick="document.querySelector('.search-toggle').click()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                Search Site
            </button>
        </div>
        
        <!-- Helpful Links -->
        <div class="error-404-links">
            <h3>You might find these helpful:</h3>
            <div class="helpful-links-grid">
                
                <!-- Recent Posts -->
                <div class="helpful-link-card">
                    <div class="helpful-link-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                    </div>
                    <h4>Recent Posts</h4>
                    <ul class="recent-posts-list">
                        <?php
                        $recent_posts = wp_get_recent_posts(array(
                            'numberposts' => 3,
                            'post_status' => 'publish'
                        ));
                        foreach ($recent_posts as $post) :
                        ?>
                            <li>
                                <a href="<?php echo get_permalink($post['ID']); ?>">
                                    <?php echo esc_html($post['post_title']); ?>
                                </a>
                            </li>
                        <?php endforeach; wp_reset_query(); ?>
                    </ul>
                </div>
                
                <!-- Categories -->
                <div class="helpful-link-card">
                    <div class="helpful-link-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                        </svg>
                    </div>
                    <h4>Categories</h4>
                    <div class="categories-list">
                        <?php
                        $categories = get_categories(array(
                            'orderby' => 'count',
                            'order' => 'DESC',
                            'number' => 5
                        ));
                        foreach ($categories as $category) :
                        ?>
                            <a href="<?php echo get_category_link($category->term_id); ?>" class="category-badge-404">
                                <?php echo esc_html($category->name); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                
            </div>
        </div>
        
    </div>
    
</div>

<?php get_footer(); ?>
