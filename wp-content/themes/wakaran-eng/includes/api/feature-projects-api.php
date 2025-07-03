<?php
/**
 * Feature Projects REST API
 * 
 * Custom REST API endpoint for Feature Projects
 * Provides clean JSON structure for headless frontend
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register custom REST API endpoints
 */
function register_feature_projects_api() {
    // Register route: GET /wp-json/api/v1/feature-projects
    register_rest_route('api/v1', '/feature-projects', array(
        'methods' => 'GET',
        'callback' => 'get_feature_projects_api',
        'permission_callback' => '__return_true', // Public endpoint
    ));
}
add_action('rest_api_init', 'register_feature_projects_api');

/**
 * Get all Feature Projects
 * 
 * @param WP_REST_Request $request
 * @return WP_REST_Response|WP_Error
 */
function get_feature_projects_api($request) {
    // Get query parameters - không bị giới hạn bởi WordPress Reading Settings
    $per_page = $request->get_param('per_page') ? intval($request->get_param('per_page')) : -1; // -1 = get all
    $page = $request->get_param('page') ? intval($request->get_param('page')) : 1;
    
    // Query feature projects
    $args = array(
        'post_type' => 'feature_project',
        'post_status' => 'publish',
        'posts_per_page' => $per_page, // Sẽ lấy tất cả nếu per_page không được set
        'paged' => $page,
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    $query = new WP_Query($args);
    $projects = array();
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $projects[] = format_feature_project_data(get_post());
        }
        wp_reset_postdata();
    }
    
    // Prepare response
    $response_data = array(
        'projects' => $projects
    );
    
    return new WP_REST_Response($response_data, 200);
}

/**
 * Format Feature Project data for API response
 * 
 * @param WP_Post $post
 * @return array
 */
function format_feature_project_data($post) {
    // Get custom meta
    $project_date = get_post_meta($post->ID, '_project_date', true);
    
    // Get featured image
    $featured_image = null;
    if (has_post_thumbnail($post->ID)) {
        $thumbnail_id = get_post_thumbnail_id($post->ID);
        $featured_image = array(
            'id' => $thumbnail_id,
            'url' => get_the_post_thumbnail_url($post->ID, 'full'),
            'thumbnail' => get_the_post_thumbnail_url($post->ID, 'thumbnail'),
            'medium' => get_the_post_thumbnail_url($post->ID, 'medium'),
            'large' => get_the_post_thumbnail_url($post->ID, 'large'),
            'alt' => get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true)
        );
    }
    
    return array(
        'id' => $post->ID,
        'title' => get_the_title($post->ID),
        'date' => array(
            'project_date' => $project_date ? date('c', strtotime($project_date)) : null,
            'project_date_formatted' => $project_date ? date('F j, Y', strtotime($project_date)) : null
        ),
        'featured_image' => $featured_image
    );
}