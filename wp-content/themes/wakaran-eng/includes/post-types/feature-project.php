<?php
/**
 * Feature Project Custom Post Type
 * 
 * Creates a custom post type for managing feature projects
 * with date, title, and featured image support
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Feature Project Custom Post Type
 */
function register_feature_project_post_type() {
    $labels = array(
        'name'                  => 'Feature Projects',
        'singular_name'         => 'Feature Project',
        'menu_name'             => 'Feature Projects',
        'name_admin_bar'        => 'Feature Project',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New Feature Project',
        'new_item'              => 'New Feature Project',
        'edit_item'             => 'Edit Feature Project',
        'view_item'             => 'View Feature Project',
        'all_items'             => 'All Feature Projects',
        'search_items'          => 'Search Feature Projects',
        'parent_item_colon'     => 'Parent Feature Projects:',
        'not_found'             => 'No feature projects found.',
        'not_found_in_trash'    => 'No feature projects found in Trash.',
    );

    $args = array(
        'labels'                => $labels,
        'description'           => 'A custom post type for feature projects',
        'public'                => false,
        'publicly_queryable'    => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_nav_menus'     => false,
        'show_in_admin_bar'     => true,
        'query_var'             => false,
        'rewrite'               => false,
        'capability_type'       => 'post',
        'has_archive'           => false,
        'hierarchical'          => false,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-portfolio',
        'supports'              => array(
            'title',
            'thumbnail',  // Only title and featured image
        ),
        // Enable in REST API for headless WordPress
        'show_in_rest'          => true,
        'rest_base'             => 'feature-projects',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
    );

    register_post_type('feature_project', $args);
}

// Hook into the 'init' action
add_action('init', 'register_feature_project_post_type');

/**
 * Flush rewrite rules when theme is activated
 */
function flush_feature_project_rewrite_rules() {
    register_feature_project_post_type();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'flush_feature_project_rewrite_rules');

/**
 * Add custom meta fields to Feature Project
 */
function add_feature_project_meta_fields() {
    add_meta_box(
        'feature_project_date',
        'Project Date',
        'feature_project_meta_box_callback',
        'feature_project',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'add_feature_project_meta_fields');

/**
 * Meta box callback function
 */
function feature_project_meta_box_callback($post) {
    // Add nonce for security
    wp_nonce_field('feature_project_meta_box', 'feature_project_meta_nonce');
    
    // Get current values
    $project_date = get_post_meta($post->ID, '_project_date', true);
    
    echo '<input type="date" id="project_date" name="project_date" value="' . esc_attr($project_date) . '" style="width: 100%;" />';
}

/**
 * Save custom meta fields
 */
function save_feature_project_meta_fields($post_id) {
    // Check if nonce is valid
    if (!isset($_POST['feature_project_meta_nonce']) || 
        !wp_verify_nonce($_POST['feature_project_meta_nonce'], 'feature_project_meta_box')) {
        return;
    }

    // Check if user has permission to edit
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save project date
    if (isset($_POST['project_date'])) {
        update_post_meta($post_id, '_project_date', sanitize_text_field($_POST['project_date']));
    }
}
add_action('save_post', 'save_feature_project_meta_fields');

/**
 * Remove slug metabox for Feature Project
 */
function remove_feature_project_slug_metabox() {
    remove_meta_box('slugdiv', 'feature_project', 'normal');
}
add_action('admin_menu', 'remove_feature_project_slug_metabox');