<?php
/**
 * Posts REST API v1
 * 
 * Custom REST API endpoint for WordPress Posts with view counter
 * Provides clean JSON structure for headless frontend
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register custom REST API endpoints for Posts
 */
function register_posts_v1_api() {
    // 1. GET All Posts
    register_rest_route('api/v1', '/posts', array(
        'methods' => 'GET',
        'callback' => 'wakaran_get_all_posts_api',
        'permission_callback' => '__return_true',
    ));

    // 2. GET Post by ID
    register_rest_route('api/v1', '/posts/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'wakaran_get_post_by_id_api',
        'permission_callback' => '__return_true',
        'args' => array(
            'id' => array(
                'validate_callback' => function($param, $request, $key) {
                    return is_numeric($param);
                }
            ),
        ),
    ));

    // 3. GET Post by Slug
    register_rest_route('api/v1', '/posts/slug/(?P<slug>.+)', array(
        'methods' => 'GET',
        'callback' => 'wakaran_get_post_by_slug_api',
        'permission_callback' => '__return_true',
    ));

    // 4. GET Top 5 Posts by Views
    register_rest_route('api/v1', '/posts/popular', array(
        'methods' => 'GET',
        'callback' => 'wakaran_get_popular_posts_api',
        'permission_callback' => '__return_true',
    ));

    // 5. GET Recommended Posts by Post ID
    register_rest_route('api/v1', '/posts/(?P<id>\d+)/recommend', array(
        'methods' => 'GET',
        'callback' => 'wakaran_get_recommend_posts_by_id_api',
        'permission_callback' => '__return_true',
        'args' => array(
            'id' => array(
                'validate_callback' => function($param, $request, $key) {
                    return is_numeric($param);
                }
            ),
        ),
    ));

    // 6. GET Recommended Posts by Slug
    register_rest_route('api/v1', '/posts/slug/(?P<slug>.+)/recommend', array(
        'methods' => 'GET',
        'callback' => 'wakaran_get_recommend_posts_by_slug_api',
        'permission_callback' => '__return_true',
    ));
}
add_action('rest_api_init', 'register_posts_v1_api');

/**
 * 1. Get all posts
 */
function wakaran_get_all_posts_api($request) {
    try {
        $per_page = $request->get_param('per_page') ? intval($request->get_param('per_page')) : -1;
        
        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $per_page,
            'orderby' => 'date',
            'order' => 'DESC'
        );
        
        $query = new WP_Query($args);
        $posts = array();
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $post = get_post();
                $posts[] = wakaran_format_post_data($post, false); // false = không track view
            }
            wp_reset_postdata();
        }
        
        return new WP_REST_Response(array('posts' => $posts), 200);
    } catch (Exception $e) {
        return new WP_Error('server_error', 'Error: ' . $e->getMessage(), array('status' => 500));
    }
}

/**
 * 2. Get post by ID
 */
function wakaran_get_post_by_id_api($request) {
    try {
        $post_id = intval($request['id']);
        
        $post = get_post($post_id);
        
        if (!$post || $post->post_type !== 'post' || $post->post_status !== 'publish') {
            return new WP_Error('not_found', 'Post not found', array('status' => 404));
        }
        
        // Track view khi get single post
        if (function_exists('track_post_view')) {
            track_post_view($post_id);
        } else {
            // Fallback: increment view manually
            $view_count = get_post_meta($post_id, '_post_views', true);
            $view_count = $view_count ? intval($view_count) : 0;
            $view_count++;
            update_post_meta($post_id, '_post_views', $view_count);
        }
        
        $post_data = wakaran_format_post_data($post, true); // true = đã track view
        
        return new WP_REST_Response($post_data, 200);
    } catch (Exception $e) {
        return new WP_Error('server_error', 'Error: ' . $e->getMessage(), array('status' => 500));
    }
}

/**
 * 3. Get post by slug
 */
function wakaran_get_post_by_slug_api($request) {
    try {
        $slug = $request['slug'];
        
        // Decode URL-encoded slug (for Japanese characters, etc.)
        $decoded_slug = urldecode($slug);
        
        // Sanitize the decoded slug
        $sanitized_slug = sanitize_text_field($decoded_slug);
        
        // Try to find post by slug - Method 1: get_page_by_path
        $post = get_page_by_path($sanitized_slug, OBJECT, 'post');
        
        // If not found with decoded slug, try with original slug as fallback
        if (!$post) {
            $post = get_page_by_path($slug, OBJECT, 'post');
        }
        
        // Method 2: WP_Query with decoded slug
        if (!$post) {
            $query = new WP_Query(array(
                'name' => $sanitized_slug,
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => 1
            ));
            
            if ($query->have_posts()) {
                $post = $query->posts[0];
            }
            wp_reset_postdata();
        }
        
        // Method 3: WP_Query with original slug
        if (!$post) {
            $query = new WP_Query(array(
                'name' => $slug,
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => 1
            ));
            
            if ($query->have_posts()) {
                $post = $query->posts[0];
            }
            wp_reset_postdata();
        }
        
        // Method 4: Search by post_name directly in database
        if (!$post) {
            global $wpdb;
            $post_id = $wpdb->get_var($wpdb->prepare(
                "SELECT ID FROM {$wpdb->posts} WHERE post_name = %s AND post_type = 'post' AND post_status = 'publish'",
                $sanitized_slug
            ));
            
            if ($post_id) {
                $post = get_post($post_id);
            }
        }
        
        // Method 5: Search by original slug in database
        if (!$post) {
            global $wpdb;
            $post_id = $wpdb->get_var($wpdb->prepare(
                "SELECT ID FROM {$wpdb->posts} WHERE post_name = %s AND post_type = 'post' AND post_status = 'publish'",
                $slug
            ));
            
            if ($post_id) {
                $post = get_post($post_id);
            }
        }
        
        if (!$post || $post->post_status !== 'publish') {
            return new WP_Error('not_found', 'Post not found', array('status' => 404));
        }
        
        // Track view khi get single post
        if (function_exists('track_post_view')) {
            track_post_view($post->ID);
        } else {
            // Fallback: increment view manually
            $view_count = get_post_meta($post->ID, '_post_views', true);
            $view_count = $view_count ? intval($view_count) : 0;
            $view_count++;
            update_post_meta($post->ID, '_post_views', $view_count);
        }
        
        $post_data = wakaran_format_post_data($post, true); // true = đã track view
        
        return new WP_REST_Response($post_data, 200);
    } catch (Exception $e) {
        return new WP_Error('server_error', 'Error: ' . $e->getMessage(), array('status' => 500));
    }
}

/**
 * 4. Get top 5 posts by views
 */
function wakaran_get_popular_posts_api($request) {
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 5,
        'meta_key' => '_post_views',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'meta_query' => array(
            array(
                'key' => '_post_views',
                'value' => 0,
                'compare' => '>'
            )
        )
    );
    
    $query = new WP_Query($args);
    $posts = array();
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post = get_post();
            $posts[] = wakaran_format_post_data($post, false); // false = không track view
        }
        wp_reset_postdata();
    }
    
    return new WP_REST_Response(array('posts' => $posts), 200);
}

/**
 * Format post data for API response
 */
function wakaran_format_post_data($post, $track_view = false) {
    // Get view count safely
    $view_count = 0;
    if (function_exists('get_post_view_count')) {
        $view_count = get_post_view_count($post->ID);
    } else {
        $view_count = get_post_meta($post->ID, '_post_views', true);
        $view_count = $view_count ? intval($view_count) : 0;
    }
    
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
        'content' => apply_filters('the_content', $post->post_content),
        'excerpt' => get_the_excerpt($post->ID),
        'slug' => $post->post_name,
        'date' => array(
            'published' => get_the_date('c', $post->ID),
            'published_formatted' => get_the_date('F j, Y', $post->ID)
        ),
        'featured_image' => $featured_image,
        'views' => $view_count,
        'author' => array(
            'id' => $post->post_author,
            'name' => get_the_author_meta('display_name', $post->post_author)
        )
    );
}

/**
 * 5. Get recommended posts by post ID
 */
function wakaran_get_recommend_posts_by_id_api($request) {
    try {
        $post_id = intval($request['id']);
        
        $post = get_post($post_id);
        
        if (!$post || $post->post_type !== 'post' || $post->post_status !== 'publish') {
            return new WP_Error('not_found', 'Post not found', array('status' => 404));
        }
        
        $recommended_posts = wakaran_get_recommended_posts($post_id);
        
        return new WP_REST_Response(array('recommended_posts' => $recommended_posts), 200);
    } catch (Exception $e) {
        return new WP_Error('server_error', 'Error: ' . $e->getMessage(), array('status' => 500));
    }
}

/**
 * 6. Get recommended posts by slug
 */
function wakaran_get_recommend_posts_by_slug_api($request) {
    try {
        $slug = $request['slug'];
        
        // Decode URL-encoded slug (for Japanese characters, etc.)
        $decoded_slug = urldecode($slug);
        
        // Sanitize the decoded slug
        $sanitized_slug = sanitize_text_field($decoded_slug);
        
        // Try to find post by slug - Method 1: get_page_by_path
        $post = get_page_by_path($sanitized_slug, OBJECT, 'post');
        
        // If not found with decoded slug, try with original slug as fallback
        if (!$post) {
            $post = get_page_by_path($slug, OBJECT, 'post');
        }
        
        // Method 2: WP_Query with decoded slug
        if (!$post) {
            $query = new WP_Query(array(
                'name' => $sanitized_slug,
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => 1
            ));
            
            if ($query->have_posts()) {
                $post = $query->posts[0];
            }
            wp_reset_postdata();
        }
        
        // Method 3: WP_Query with original slug
        if (!$post) {
            $query = new WP_Query(array(
                'name' => $slug,
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => 1
            ));
            
            if ($query->have_posts()) {
                $post = $query->posts[0];
            }
            wp_reset_postdata();
        }
        
        // Method 4: Search by post_name directly in database
        if (!$post) {
            global $wpdb;
            $post_id = $wpdb->get_var($wpdb->prepare(
                "SELECT ID FROM {$wpdb->posts} WHERE post_name = %s AND post_type = 'post' AND post_status = 'publish'",
                $sanitized_slug
            ));
            
            if ($post_id) {
                $post = get_post($post_id);
            }
        }
        
        // Method 5: Search by original slug in database
        if (!$post) {
            global $wpdb;
            $post_id = $wpdb->get_var($wpdb->prepare(
                "SELECT ID FROM {$wpdb->posts} WHERE post_name = %s AND post_type = 'post' AND post_status = 'publish'",
                $slug
            ));
            
            if ($post_id) {
                $post = get_post($post_id);
            }
        }
        
        if (!$post || $post->post_status !== 'publish') {
            return new WP_Error('not_found', 'Post not found', array('status' => 404));
        }
        
        $recommended_posts = wakaran_get_recommended_posts($post->ID);
        
        return new WP_REST_Response(array('recommended_posts' => $recommended_posts), 200);
    } catch (Exception $e) {
        return new WP_Error('server_error', 'Error: ' . $e->getMessage(), array('status' => 500));
    }
}

/**
 * Helper function to get recommended posts
 * Logic: Get posts from same categories first, then fill with random posts if needed
 */
function wakaran_get_recommended_posts($post_id) {
    $recommended_posts = array();
    $max_posts = 5;
    
    // Get categories of the current post
    $categories = get_the_category($post_id);
    $category_ids = array();
    
    if (!empty($categories)) {
        foreach ($categories as $category) {
            $category_ids[] = $category->term_id;
        }
    }
    
    // First: Try to get posts from same categories
    if (!empty($category_ids)) {
        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $max_posts,
            'post__not_in' => array($post_id), // Exclude current post
            'category__in' => $category_ids,
            'orderby' => 'rand', // Random order for variety
        );
        
        $query = new WP_Query($args);
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $post = get_post();
                $recommended_posts[] = wakaran_format_post_data($post, false);
            }
            wp_reset_postdata();
        }
    }
    
    // If we don't have enough posts, fill with random posts
    if (count($recommended_posts) < $max_posts) {
        $remaining_posts = $max_posts - count($recommended_posts);
        
        // Get IDs of posts already selected
        $exclude_ids = array($post_id);
        foreach ($recommended_posts as $rec_post) {
            $exclude_ids[] = $rec_post['id'];
        }
        
        $random_args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $remaining_posts,
            'post__not_in' => $exclude_ids,
            'orderby' => 'rand',
        );
        
        $random_query = new WP_Query($random_args);
        
        if ($random_query->have_posts()) {
            while ($random_query->have_posts()) {
                $random_query->the_post();
                $post = get_post();
                $recommended_posts[] = wakaran_format_post_data($post, false);
            }
            wp_reset_postdata();
        }
    }
    
    return $recommended_posts;
}