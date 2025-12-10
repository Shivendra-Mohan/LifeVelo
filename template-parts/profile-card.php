<!-- Profile Card Component -->
<div class="hero-profile-card">

    <!-- Profile Image -->
    <div class="profile-image-wrapper">
        <img src="<?php echo esc_url(get_theme_mod('profile_image', get_template_directory_uri() . '/assets/images/profile.webp')); ?>"
             alt="<?php bloginfo('name'); ?>"
             class="profile-image">
    </div>

    <!-- Profile Text -->
    <div class="profile-text-group">
        <h1 class="profile-name"><?php echo esc_html(get_theme_mod('profile_name', get_bloginfo('name'))); ?></h1>
        <p class="profile-tagline"><?php echo esc_html(get_theme_mod('profile_tagline', 'Video Editor & Storyteller')); ?></p>
        <p class="profile-location"><?php echo esc_html(get_theme_mod('profile_location', 'Mumbai, India')); ?></p>
        <p class="profile-tagline-secondary"><?php echo esc_html(get_theme_mod('profile_tagline_secondary', 'Documenting My Journey')); ?></p>
    </div>

    <!-- Social Links -->
    <div class="profile-social">
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
