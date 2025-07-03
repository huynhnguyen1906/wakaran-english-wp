<?php
/**
 * Posts View Counter
 * 
 * Simple view counter for WordPress posts
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Track post view when post is accessed via API
 */
function track_post_view($post_id) {
    if (!$post_id) return;
    
    // Get current view count
    $view_count = get_post_meta($post_id, '_post_views', true);
    $view_count = $view_count ? intval($view_count) : 0;
    
    // Increment view count
    $view_count++;
    
    // Update view count
    update_post_meta($post_id, '_post_views', $view_count);
    
    return $view_count;
}

/**
 * Get post view count
 */
function get_post_view_count($post_id) {
    $view_count = get_post_meta($post_id, '_post_views', true);
    return $view_count ? intval($view_count) : 0;
}