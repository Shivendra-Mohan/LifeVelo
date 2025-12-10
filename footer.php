    <!-- ========================================
         SITE FOOTER
         Test deployment: Updated via Git workflow
         ======================================== -->
    <footer class="site-footer">
        <div class="footer-content">

            <!-- Left Column: Follow + Social Icons -->
            <div class="footer-left">
                <h3 class="footer-follow-title">Follow</h3>
                <div class="footer-social">
                    <?php if (get_theme_mod('social_youtube')) : ?>
                    <a href="<?php echo esc_url(get_theme_mod('social_youtube')); ?>" target="_blank" rel="noopener" aria-label="YouTube" class="social-link">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/youtube.svg" alt="YouTube">
                    </a>
                    <?php endif; ?>
                    
                    <?php if (get_theme_mod('social_instagram')) : ?>
                    <a href="<?php echo esc_url(get_theme_mod('social_instagram')); ?>" target="_blank" rel="noopener" aria-label="Instagram" class="social-link">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/instagram.svg" alt="Instagram">
                    </a>
                    <?php endif; ?>
                    
                    <?php if (get_theme_mod('social_linkedin')) : ?>
                    <a href="<?php echo esc_url(get_theme_mod('social_linkedin')); ?>" target="_blank" rel="noopener" aria-label="LinkedIn" class="social-link">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/linkedin.svg" alt="LinkedIn">
                    </a>
                    <?php endif; ?>
                    
                    <?php if (get_theme_mod('social_imdb')) : ?>
                    <a href="<?php echo esc_url(get_theme_mod('social_imdb')); ?>" target="_blank" rel="noopener" aria-label="IMDb" class="social-link">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/imdb.svg" alt="IMDb">
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Center Column: Navigation Links + Copyright -->
            <div class="footer-center">
                <nav class="footer-nav">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer',
                        'container'      => false,
                        'menu_class'     => 'footer-menu', // Ensure you style this class if needed, or keep existing structure
                        'items_wrap'     => '%3$s', // Output only list items or links depending on structure. 
                                                    // The original had direct <a> tags. wp_nav_menu outputs <ul><li><a> by default.
                                                    // To match original styling exactly without changing CSS, we might need a walker or just accept <ul> structure and update CSS.
                                                    // For now, let's use a simple fallback to Home if no menu assigned, or just standard output.
                        'fallback_cb'    => false,
                    ));
                    ?>
                </nav>
                <p class="footer-copyright">
                    &copy; <?php echo date('Y'); ?> <strong><?php bloginfo('name'); ?></strong>. All rights reserved.
                </p>
            </div>

            <!-- Right Column: Newsletter -->
            <div class="footer-right">
                <div class="footer-newsletter">
                    <form id="footer-newsletter-form" class="newsletter-form-footer">
                        <?php wp_nonce_field('newsletter_nonce', 'footer_newsletter_nonce_field'); ?>

                        <button type="submit" class="newsletter-button-footer">
                            Subscribe
                        </button>

                        <div class="newsletter-form-group">
                            <input
                                type="text"
                                name="name"
                                id="footer-newsletter-name"
                                placeholder="Your name"
                                class="newsletter-input-footer"
                            >
                        </div>

                        <div class="newsletter-form-group">
                            <input
                                type="email"
                                name="email"
                                id="footer-newsletter-email"
                                placeholder="Your email"
                                required
                                class="newsletter-input-footer"
                            >
                        </div>

                        <div id="footer-newsletter-message" class="newsletter-message"></div>
                    </form>
                </div>
            </div>

        </div>
    </footer>

    <!-- ========================================
         EXIT INTENT NEWSLETTER POPUP
         ======================================== -->
    <div id="exit-intent-popup" class="exit-popup-overlay">
        <div class="exit-popup-container">
            <button class="exit-popup-close" aria-label="Close popup">&times;</button>

            <div class="exit-popup-content">
                <h2 class="exit-popup-title">
                    Wait! Don't Miss Out 
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: bottom; margin-left: 8px;">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                </h2>
                <p class="exit-popup-description">
                    Get the latest stories, insights, and updates delivered straight to your inbox.
                </p>

                <form id="exit-popup-newsletter-form" class="exit-popup-form">
                    <?php wp_nonce_field('newsletter_nonce', 'exit_popup_nonce_field'); ?>

                    <input
                        type="text"
                        name="name"
                        id="exit-popup-name"
                        placeholder="Your name"
                        class="exit-popup-input"
                    >

                    <input
                        type="email"
                        name="email"
                        id="exit-popup-email"
                        placeholder="Your email address"
                        required
                        class="exit-popup-input"
                    >

                    <button type="submit" class="exit-popup-button">
                        Subscribe Now
                    </button>

                    <p class="exit-popup-note">No spam, unsubscribe anytime.</p>

                    <div id="exit-popup-message" class="exit-popup-message"></div>
                </form>
            </div>
        </div>
    </div>

<?php wp_footer(); ?> <!-- Loads scripts before </body> -->

<!--
=================================================================
AI-ASSISTED DEVELOPMENT CREDITS
=================================================================

This WordPress theme was built using an agentic AI-assisted approach.

PROJECT MANAGER & AGENTIC COORDINATOR:
Shivendra Mohan
- Directed entire development process
- Provided requirements and design vision
- Tested and validated all features
- Made all architectural decisions

AI DEVELOPMENT TEAM:

1. CLAUDE SONNET 4.5 (Primary Developer - Anthropic)
   Role: Lead AI Developer
   Contributions:
   - Implemented majority of theme functionality
   - Created modular architecture (12 modules)
   - Developed SEO meta tags system
   - Implemented Schema.org structured data
   - Built newsletter dual-save system
   - Created newsletter admin interface
   - Developed custom login security
   - Implemented security hardening
   - Responsive design and bug fixes
   - Maintained WordPress standards

2. GROK AI (xAI)
   Role: Foundation Developer
   Contributions:
   - Created initial base files
   - Developed foundational code structure
   - Established WordPress theme setup

3. CHATGPT (OpenAI) & GEMINI (Google)
   Role: Contributing Developers
   Contributions:
   - Code snippets and functionality
   - Feature testing
   - Alternative solutions

DEVELOPMENT METHODOLOGY:
This theme showcases the power of human-AI collaboration in
modern web development. Shivendra Mohan coordinated multiple
AI assistants to create a production-ready WordPress theme.

All code was human-reviewed, tested, and approved.

Learn more: https://shivendramohan.com/blog/building-with-ai
=================================================================
-->

</body>
</html>
