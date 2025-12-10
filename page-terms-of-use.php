<?php
/**
 * Template Name: Terms of Use
 * Template for Terms of Use page
 */
get_header();
?>

<!-- Terms of Use Container -->
<div class="terms-of-use-alignment">

    <!-- Terms of Use Content -->
    <div class="terms-of-use-content">
        <?php while (have_posts()) : the_post(); ?>

            <article id="page-<?php the_ID(); ?>" <?php post_class('terms-of-use-article'); ?>>

                <!-- Page Title -->
                <header class="terms-of-use-header">
                    <h1 class="terms-of-use-title"><?php the_title(); ?></h1>
                </header>

                <!-- Page Content -->
                <div class="terms-of-use-wrapper">
                    <?php the_content(); ?>
                </div>

            </article>

        <?php endwhile; ?>
    </div>

    <!-- ========================================
         CONTACT SECTION
         ======================================== -->
    <section class="terms-of-use-contact">
        <h2 class="terms-of-use-contact-title">Questions About Terms</h2>

        <p class="terms-of-use-contact-intro">
            If you have any questions or concerns about these terms, you can reach me at:
        </p>

        <div class="terms-of-use-contact-info">
            <?php
            // Get contact info from customizer
            $contact_email = get_theme_mod('terms_contact_email', 'your-email@example.com');
            $discord_link = get_theme_mod('terms_discord_link', '');
            ?>

            <!-- Email -->
            <div class="terms-of-use-contact-item">
                <svg class="contact-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
                <a href="mailto:<?php echo esc_attr($contact_email); ?>" class="contact-link">
                    <?php echo esc_html($contact_email); ?>
                </a>
            </div>

            <!-- Discord Link (only show if set) -->
            <?php if (!empty($discord_link)) : ?>
                <div class="terms-of-use-contact-item">
                    <svg class="contact-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515a.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0a12.64 12.64 0 0 0-.617-1.25a.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057a19.9 19.9 0 0 0 5.993 3.03a.078.078 0 0 0 .084-.028a14.09 14.09 0 0 0 1.226-1.994a.076.076 0 0 0-.041-.106a13.107 13.107 0 0 1-1.872-.892a.077.077 0 0 1-.008-.128a10.2 10.2 0 0 0 .372-.292a.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127a12.299 12.299 0 0 1-1.873.892a.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028a19.839 19.839 0 0 0 6.002-3.03a.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419c0-1.333.956-2.419 2.157-2.419c1.21 0 2.176 1.096 2.157 2.42c0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419c0-1.333.955-2.419 2.157-2.419c1.21 0 2.176 1.096 2.157 2.42c0 1.333-.946 2.418-2.157 2.418z"/>
                    </svg>
                    <a href="<?php echo esc_url($discord_link); ?>" class="contact-link" target="_blank" rel="noopener noreferrer">
                        Discord for terms related question.
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>

</div>

<?php get_footer(); ?>
