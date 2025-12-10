<?php
/**
 * Schema.org Structured Data
 *
 * Generates JSON-LD structured data for Google Rich Results.
 * Supports: Blog Posts, Person (Author), Website, Organization, Breadcrumbs.
 *
 * @package Shivendra_Blog
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Generate Schema.org JSON-LD Structured Data
 *
 * Outputs JSON-LD structured data in the <head> section for:
 * - Website schema (all pages)
 * - Person schema (author/creator)
 * - BlogPosting schema (blog posts)
 * - Breadcrumb schema (blog posts)
 * - CollectionPage schema (category pages)
 *
 * @since 1.0.0
 */
function shivendra_schema_markup() {
    $schema_data = array();

    // ====================================
    // 1. WEBSITE SCHEMA (All Pages)
    // ====================================
    $website_schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => get_bloginfo('name'),
        'url' => home_url('/'),
        'description' => get_bloginfo('description'),
        'potentialAction' => array(
            '@type' => 'SearchAction',
            'target' => array(
                '@type' => 'EntryPoint',
                'urlTemplate' => home_url('/?s={search_term_string}')
            ),
            'query-input' => 'required name=search_term_string'
        )
    );
    $schema_data[] = $website_schema;

    // ====================================
    // 2. PERSON SCHEMA (Author/Creator)
    // ====================================
    $profile_name = get_theme_mod('profile_name', get_bloginfo('name'));
    $profile_image = get_theme_mod('profile_image', get_template_directory_uri() . '/assets/images/profile.webp');
    $profile_tagline = get_theme_mod('profile_tagline', 'Video Editor & Storyteller');

    $person_schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Person',
        'name' => $profile_name,
        'url' => home_url('/'),
        'image' => $profile_image,
        'description' => $profile_tagline,
        'jobTitle' => $profile_tagline,
        'sameAs' => array_filter(array(
            get_theme_mod('social_youtube'),
            get_theme_mod('social_instagram'),
            get_theme_mod('social_linkedin'),
            get_theme_mod('social_imdb')
        ))
    );
    $schema_data[] = $person_schema;

    // ====================================
    // 3. BLOG POST ARTICLE SCHEMA
    // ====================================
    if (is_single() && get_post_type() === 'post') {
        global $post;

        $article_schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => get_the_title(),
            'description' => get_the_excerpt() ? wp_strip_all_tags(get_the_excerpt()) : wp_trim_words(wp_strip_all_tags(get_the_content()), 30),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'author' => array(
                '@type' => 'Person',
                'name' => $profile_name,
                'url' => home_url('/')
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'logo' => array(
                    '@type' => 'ImageObject',
                    'url' => get_template_directory_uri() . '/assets/images/site-logo.png'
                )
            ),
            'mainEntityOfPage' => array(
                '@type' => 'WebPage',
                '@id' => get_permalink()
            )
        );

        // Add featured image if available
        if (has_post_thumbnail()) {
            $image_url = get_the_post_thumbnail_url($post->ID, 'full');
            $image_id = get_post_thumbnail_id($post->ID);
            $image_meta = wp_get_attachment_metadata($image_id);

            $article_schema['image'] = array(
                '@type' => 'ImageObject',
                'url' => $image_url,
                'width' => isset($image_meta['width']) ? $image_meta['width'] : 1200,
                'height' => isset($image_meta['height']) ? $image_meta['height'] : 630
            );
        }

        // Add article categories/sections
        $categories = get_the_category();
        if (!empty($categories)) {
            $article_schema['articleSection'] = array();
            foreach ($categories as $category) {
                $article_schema['articleSection'][] = $category->name;
            }
        }

        // Add word count
        $content = get_post_field('post_content', $post->ID);
        $word_count = str_word_count(strip_tags($content));
        $article_schema['wordCount'] = $word_count;

        $schema_data[] = $article_schema;

        // ====================================
        // 4. BREADCRUMB SCHEMA (Blog Posts)
        // ====================================
        $breadcrumb_schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => array(
                array(
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Home',
                    'item' => home_url('/')
                )
            )
        );

        // Add category to breadcrumb
        if (!empty($categories)) {
            $primary_category = $categories[0];
            $breadcrumb_schema['itemListElement'][] = array(
                '@type' => 'ListItem',
                'position' => 2,
                'name' => $primary_category->name,
                'item' => get_category_link($primary_category->term_id)
            );

            $breadcrumb_schema['itemListElement'][] = array(
                '@type' => 'ListItem',
                'position' => 3,
                'name' => get_the_title(),
                'item' => get_permalink()
            );
        } else {
            $breadcrumb_schema['itemListElement'][] = array(
                '@type' => 'ListItem',
                'position' => 2,
                'name' => get_the_title(),
                'item' => get_permalink()
            );
        }

        $schema_data[] = $breadcrumb_schema;
    }

    // ====================================
    // 5. CATEGORY PAGE SCHEMA
    // ====================================
    if (is_category()) {
        $category = get_queried_object();

        $collection_schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $category->name,
            'description' => $category->description ? $category->description : 'Posts in ' . $category->name . ' category',
            'url' => get_category_link($category->term_id)
        );

        $schema_data[] = $collection_schema;
    }

    // ====================================
    // OUTPUT JSON-LD SCRIPT
    // ====================================
    if (!empty($schema_data)) {
        echo '<script type="application/ld+json">' . "\n";
        echo wp_json_encode($schema_data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        echo "\n" . '</script>' . "\n";
    }
}
