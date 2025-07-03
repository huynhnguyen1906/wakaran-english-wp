<?php
/**
 * Wakaran Engineering Theme Functions
 * 
 * Main theme functions file - organized with auto-loaded includes
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme setup
 */
function nanto_theme_setup() {
    add_theme_support('post-thumbnails'); // アイキャッチ画像
    add_theme_support('title-tag');       // タイトルタグを自動で出力
    add_theme_support('custom-logo');     // カスタムロゴ対応
    add_theme_support('menus');           // メニュー機能
}
add_action('after_setup_theme', 'nanto_theme_setup');

/**
 * Load organized includes
 */
require_once get_template_directory() . '/includes/loader.php';

/**
 * HEADLESS WORDPRESS: Block all frontend access
 * Allow only admin, API, and login pages
 */
function block_frontend_for_headless() {
    // Skip if it's admin, ajax, REST API, or login
    if (is_admin() || 
        wp_doing_ajax() || 
        (defined('REST_REQUEST') && REST_REQUEST) ||
        $GLOBALS['pagenow'] === 'wp-login.php') {
        return;
    }
    
    // Check if it's a REST API request via URL
    if (strpos($_SERVER['REQUEST_URI'], '/wp-json/') !== false) {
        return;
    }
    
    // Block all frontend access
    wp_die(
        '<h1>403 - Access Forbidden</h1>
        <p>This is a headless WordPress installation.</p>
        <p>Frontend access is disabled for SEO protection.</p>',
        'Access Forbidden', 
        array('response' => 403)
    );
}
add_action('template_redirect', 'block_frontend_for_headless', 1);

/**
 * Remove unnecessary admin menu items for headless setup
 */
function remove_unnecessary_admin_menus() {
    // Remove Pages menu
    remove_menu_page('edit.php?post_type=page');
    
    // Remove Comments menu
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'remove_unnecessary_admin_menus', 999);