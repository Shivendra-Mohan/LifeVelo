<?php
/**
 * Shivendra's Blog - Theme Functions
 *
 * This is the main theme functions file that loads all modular components.
 * The theme follows a modular architecture for better organization and maintainability.
 *
 * @package Shivendra_Blog
 * @author Shivendra Mohan
 * @version 1.0.0
 *
 * =============================================================================
 * AI-ASSISTED DEVELOPMENT CREDITS
 * =============================================================================
 *
 * PROJECT MANAGER & AGENTIC COORDINATOR: Shivendra Mohan
 * - Directed the entire development process
 * - Provided requirements and design vision
 * - Tested and validated all features
 * - Made all final decisions on architecture and functionality
 *
 * AI DEVELOPMENT TEAM:
 *
 * 1. Claude Sonnet 4.5 (Primary Developer - Anthropic)
 *    - Implemented majority of theme functionality
 *    - Created modular architecture (12 modules in inc/ directory)
 *    - Developed SEO meta tags system
 *    - Implemented Schema.org structured data
 *    - Built newsletter dual-save system (WordPress DB + Brevo API)
 *    - Created newsletter admin management interface
 *    - Developed custom login URL security
 *    - Implemented security hardening features
 *    - Created responsive design and styling fixes
 *    - Maintained code quality and WordPress standards
 *    - Extensive testing and bug fixes
 *
 * 2. Grok AI (xAI)
 *    - Created initial base files and theme structure
 *    - Developed foundational code architecture
 *    - Established basic WordPress theme setup
 *
 * 3. ChatGPT (OpenAI) & Gemini (Google)
 *    - Contributed code snippets and functionality
 *    - Assisted in testing various features
 *    - Provided alternative solutions and optimizations
 *
 * DEVELOPMENT APPROACH:
 * This theme was built using an agentic AI-assisted development approach,
 * where Shivendra Mohan acted as the project manager, coordinating with
 * multiple AI assistants to create a production-ready WordPress theme.
 *
 * All code was human-reviewed, tested, and approved by Shivendra Mohan.
 *
 * =============================================================================
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// =============================================================================
// THEME CONSTANTS
// =============================================================================

define('SHIVENDRA_THEME_VERSION', '1.0.0');
define('SHIVENDRA_THEME_DIR', get_template_directory());
define('SHIVENDRA_THEME_URI', get_template_directory_uri());

// =============================================================================
// LOAD CONFIGURATION (if exists)
// =============================================================================

/**
 * Load config.php for production settings
 * This file contains custom login slug, API keys, etc.
 * Only loads if file exists (not required for development)
 */
$config_file = SHIVENDRA_THEME_DIR . '/config.php';
if (file_exists($config_file)) {
    require_once $config_file;
}

// =============================================================================
// LOAD MODULAR COMPONENTS
// =============================================================================

/**
 * Core theme setup
 * Registers theme support, menus, and image sizes
 */
require_once SHIVENDRA_THEME_DIR . '/inc/theme-setup.php';

/**
 * WordPress Customizer settings
 * Profile info, social links, contact info, Brevo API
 */
require_once SHIVENDRA_THEME_DIR . '/inc/customizer.php';

/**
 * Enqueue styles and scripts
 * Loads CSS and JavaScript files with conditional loading
 */
require_once SHIVENDRA_THEME_DIR . '/inc/enqueue.php';

/**
 * Newsletter subscription system
 * Database table, dual-save (local + Brevo), export functionality
 */
require_once SHIVENDRA_THEME_DIR . '/inc/newsletter.php';

/**
 * AJAX handlers
 * Live search functionality
 */
require_once SHIVENDRA_THEME_DIR . '/inc/ajax-handlers.php';

/**
 * Helper functions
 * Reading time calculator, custom comments, admin notices
 */
require_once SHIVENDRA_THEME_DIR . '/inc/helpers.php';

/**
 * Security hardening
 * XML-RPC disable, version hiding, user enumeration blocking
 */
require_once SHIVENDRA_THEME_DIR . '/inc/security.php';

/**
 * SEO meta tags
 * Open Graph, Twitter Cards, canonical URLs
 */
require_once SHIVENDRA_THEME_DIR . '/inc/seo.php';

/**
 * Schema.org structured data
 * JSON-LD for rich snippets and better SEO
 */
require_once SHIVENDRA_THEME_DIR . '/inc/schema.php';

/**
 * Export functionality
 * CSV export for newsletter subscribers (admin only)
 */
require_once SHIVENDRA_THEME_DIR . '/inc/export.php';

/**
 * Newsletter Admin Management
 * Admin page to view, manage, and delete newsletter subscribers
 */
require_once SHIVENDRA_THEME_DIR . '/inc/newsletter-admin.php';

/**
 * Google Analytics Integration
 * GA4 tracking with customizer settings and privacy features
 */
require_once SHIVENDRA_THEME_DIR . '/inc/analytics.php';

/**
 * XML Sitemap Generator
 * Dynamic sitemap generation for search engines
 */
require_once SHIVENDRA_THEME_DIR . '/inc/sitemap.php';

/**
 * Transients API Caching System
 * Smart caching for expensive queries (works alongside LiteSpeed Cache)
 */
require_once SHIVENDRA_THEME_DIR . '/inc/cache.php';

/**
 * Custom Login URL Security
 * Changes wp-login.php to custom URL for enhanced security
 * NOTE: Only active when config.php exists with CUSTOM_LOGIN_SLUG defined
 */
require_once SHIVENDRA_THEME_DIR . '/inc/custom-login.php';

/**
 * Discord Webhook Integration
 * Automatically sends new blog posts to Discord channel
 */
require_once SHIVENDRA_THEME_DIR . '/inc/discord-webhook.php';

// =============================================================================
// THEME INITIALIZATION COMPLETE
// =============================================================================

/**
 * All theme modules loaded successfully!
 *
 * Module Organization:
 * - theme-setup.php    : Core WordPress theme features
 * - customizer.php     : Customizer options and settings
 * - enqueue.php        : CSS/JS asset management
 * - newsletter.php     : Newsletter subscription system
 * - ajax-handlers.php  : AJAX request handlers
 * - helpers.php        : Utility and helper functions
 * - security.php       : Security enhancements
 * - seo.php           : SEO meta tags generation
 * - schema.php        : Schema.org structured data
 * - export.php        : Data export functionality
 * - cache.php         : Transients API caching system
 * - discord-webhook.php: Discord integration for new posts
 */
