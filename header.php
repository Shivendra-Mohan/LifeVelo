<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title(); ?> - <?php bloginfo('name'); ?></title>

    <!-- AI Development Credits - Visible to Search Engines -->
    <meta name="developer" content="Shivendra Mohan">
    <meta name="development-method" content="AI-Assisted Development">
    <meta name="ai-primary-developer" content="Claude Sonnet 4.5 by Anthropic">
    <meta name="ai-contributors" content="Grok AI (xAI), ChatGPT (OpenAI), Gemini (Google)">
    <meta name="project-manager" content="Shivendra Mohan">

    <!-- SEO Meta Tags -->
    <?php shivendra_seo_meta_tags(); ?>

    <!-- Schema.org Structured Data -->
    <?php shivendra_schema_markup(); ?>

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php
// ---------------------------------------------------------------------
//  FLOATING THREE-BOX HEADER
// ---------------------------------------------------------------------
?>
<header class="floating-header">
    <div class="header-inner">

        <!-- Mobile Menu Toggle (Hamburger) - Left -->
        <button class="mobile-menu-toggle" aria-label="Toggle mobile menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <!-- Logo - Center -->
        <div class="header-box logo-box">
            <a href="<?php echo home_url(); ?>" class="site-logo-link">
                <?php
                $logo_type = get_theme_mod('logo_type', 'image');

                if ($logo_type === 'text') {
                    // Text Logo
                    $text_logo = get_theme_mod('text_logo', 'LifeVelo');
                    $text_size = get_theme_mod('text_logo_size', '24');
                    $text_weight = get_theme_mod('text_logo_weight', '700');
                    ?>
                    <span class="site-logo-text" style="font-size: <?php echo esc_attr($text_size); ?>px; font-weight: <?php echo esc_attr($text_weight); ?>;">
                        <?php echo esc_html($text_logo); ?>
                    </span>
                    <?php
                } else {
                    // Image Logo
                    $logo_url = get_theme_mod('site_logo', get_template_directory_uri() . '/assets/images/profile.webp');
                    $logo_size = get_theme_mod('logo_size', '40');
                    ?>
                    <img src="<?php echo esc_url($logo_url); ?>"
                         alt="<?php bloginfo('name'); ?>"
                         class="site-logo"
                         style="width: <?php echo esc_attr($logo_size); ?>px; height: <?php echo esc_attr($logo_size); ?>px;">
                    <?php
                }
                ?>
            </a>
        </div>

        <!-- Navigation -->
        <div class="header-box nav-box">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'menu_class'    => 'nav-menu',
                'container'     => false
            ));
            ?>
        </div>

        <!-- Search - Right -->
        <div class="header-box search-box">
            <button class="search-toggle" aria-label="Toggle search">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </button>
        </div>
    </div>
</header>

<!-- ========================================
     SEARCH MODAL OVERLAY
     ======================================== -->
<div id="search-modal" class="search-modal">
    <div class="search-modal-overlay"></div>
    <div class="search-modal-content">
        
        <!-- Close Button -->
        <button class="search-close" aria-label="Close search">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
        
        <!-- Search Input -->
        <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
            <div class="search-input-wrapper">
                <svg class="search-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input 
                    type="search" 
                    id="search-input" 
                    class="search-modal-input" 
                    placeholder="Search posts..."
                    autocomplete="off"
                    value="<?php echo get_search_query(); ?>" 
                    name="s"
                >
            </div>
        </form>
        
        <!-- Search Results -->
        <div id="search-results" class="search-results">
            <div class="search-hint">
                <p>Start typing to search posts...</p>
            </div>
        </div>
        
    </div>
</div>