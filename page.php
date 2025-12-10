<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * @package Shivendra's Blog
 */

get_header(); ?>

<div class="page-content-section">
    <div class="content-container">
        <div class="page-content">
            <?php
            while (have_posts()) : the_post(); ?>
                
                <article id="page-<?php the_ID(); ?>" <?php post_class('page'); ?>>
                    
                    <!-- Page Title -->
                    <header class="page-header">
                        <h1 class="page-title"><?php the_title(); ?></h1>
                    </header>
                    
                    <!-- Page Content -->
                    <div class="content-wrapper">
                        <?php the_content(); ?>
                        
                        <?php
                        // If comments are open or we have at least one comment, load up the comment template.
                        if (comments_open() || get_comments_number()) :
                            comments_template();
                        endif;
                        ?>
                    </div>
                    
                </article>
                
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
