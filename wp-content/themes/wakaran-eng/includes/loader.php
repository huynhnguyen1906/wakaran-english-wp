<?php
/**
 * Theme Includes Auto-loader
 * 
 * Automatically loads all PHP files from includes directory
 * for better code organization
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Auto-load all files from includes directory
 */
function wakaran_eng_load_includes() {
    $includes_dir = get_template_directory() . '/includes/';
    
    // Load core functions first (like post-views.php)
    $other_includes = array(
        'post-views.php',  // View counter functions - MUST LOAD FIRST
        // Add more include files here as needed
        // 'custom-fields.php',
        // 'theme-options.php',
    );
    
    foreach ($other_includes as $file) {
        $file_path = $includes_dir . $file;
        if (file_exists($file_path)) {
            require_once $file_path;
        }
    }
    
    // Load all files from post-types directory
    $post_types_dir = $includes_dir . 'post-types/';
    if (is_dir($post_types_dir)) {
        foreach (glob($post_types_dir . '*.php') as $file) {
            require_once $file;
        }
    }
    
    // Load all files from api directory LAST
    $api_dir = $includes_dir . 'api/';
    if (is_dir($api_dir)) {
        foreach (glob($api_dir . '*.php') as $file) {
            require_once $file;
        }
    }
}

// Load includes during init (after WordPress core is loaded)
add_action('init', 'wakaran_eng_load_includes', 5);