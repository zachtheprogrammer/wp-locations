<?php

/**
Plugin Name: Demo Locations Plugin
 **/


// Create locations custom post type
function locations_post_type()
{
    register_post_type(
        'locations',
        array(
            'labels' => array(
                'name' => __('Locations'),
                'singular_name' => __('Location')
            ),
            'public' => true,
            'show_in_rest' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
            'has_archive' => true,
            'rewrite'   => array('slug' => 'wp-locations'),
            'menu_position' => 5,
            'menu_icon' => 'dashicons-building',
            'taxonomies' => array('location_categories')
        )
    );

    flush_rewrite_rules();
}
add_action('init', 'locations_post_type');

// Add locations taxonomy
function create_locations_taxonomy()
{
    register_taxonomy('location_categories', 'locations', array(
        'hierarchical' => false,
        'labels' => array(
            'name' => _x('Location Cagtegories', 'taxonomy general name'),
            'singular_name' => _x('Location Category', 'taxonomy singular name'),
            'menu_name' => __('Location Categories'),
            'all_items' => __('All Categories'),
            'edit_item' => __('Edit Category'),
            'update_item' => __('Update Category'),
            'add_new_item' => __('Add Category'),
            'new_item_name' => __('New Category'),
        ),
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
    ));
}
add_action('init', 'create_locations_taxonomy', 0);

//Load custom locations templates
function load_locations_templates($template)
{

    global $post;
    if (isset($post->post_type) && $post->post_type === 'locations') {
        if (is_single()) {
            if (locate_template(array('single-location.php')) !== $template) {
                return plugin_dir_path(__FILE__) . 'single-location.php';
            }
        } else {
            if (locate_template(array('locations.php')) !== $template) {
                // Bring in bootstrap
                return plugin_dir_path(__FILE__) . 'locations.php';
            }
        }
    }

    return $template;
}
add_filter('template_include', 'load_locations_templates');

//Load styles/scripts if locations page
function locations_assets()
{
    global $post;
    if (isset($post->post_type) && $post->post_type == 'locations' && !is_single()) {

        $args = array(
            'post_type' => 'locations',
            'post_status' => 'publish',
            'nopaging' => true
        );

        $query = new WP_Query($args);
        $posts = $query->get_posts();

        $location_data = array();
        foreach ($posts as $post) {
            $post_meta = get_post_meta($post->ID);
            $location_data[] = array(
                'LocationName' => $post->post_title,
                'LocationPermalink' => get_the_permalink($post->ID),
                'LocationImage' => (has_post_thumbnail($post->ID)) ? get_the_post_thumbnail_url($post->ID) : 'https://dummyimage.com/300',
                'LocationAddress' => $post_meta['location_address'][0],
                'LocationCity' => $post_meta['location_city'][0],
                'LocationState' => $post_meta['location_state'][0],
                'LocationPostal' => $post_meta['location_postal'][0],
                'LocationLatitude' => $post_meta['location_latitude'][0],
                'LocationLongitude' => $post_meta['location_longitude'][0],
                'LocationSize' => $post_meta['location_size'][0],
            );
        }

        //Loop array and filter from search form
        $return_data = array();
        foreach ($location_data as $data) {
            $return_location = true;
            if (isset($_GET['location_search']) && $_GET['location_search'] != '') {
                //Remove all but alphanumeric and space of submitted location and lowercase it
                $location_filter = strtolower(preg_replace("/[^[:alnum:][:space:]]/u", '', $_GET['location_search']));

                // Create location string with address city state for filter and lowercase
                $location = strtolower($data['LocationAddress'] . ' ' . $data['LocationCity'] . ' ' . $data['LocationState'] . ' ' . $data['LocationPostal']);

                if (strpos($location, $location_filter) !== false) {
                } else {
                    $return_location = false;
                }
            }

            if (isset($_GET['state_search']) && $_GET['state_search'] != '') {
                if ($_GET['state_search'] != $data['LocationState']) {
                    $return_location = false;
                }
            }

            if (isset($_GET['min_size_search']) && $_GET['min_size_search'] != '') {
                if ($_GET['min_size_search'] > $data['LocationSize']) {
                    $return_location = false;
                }
            }

            if (isset($_GET['max_size_search']) && $_GET['max_size_search'] != '') {
                if ($_GET['max_size_search'] < $data['LocationSize']) {
                    $return_location = false;
                }
            }

            if ($return_location) {
                $return_data[] = $data;
            }
        }

        wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css');


        wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js');

        wp_enqueue_script('locations-js', plugins_url('/public/js/app.js', __FILE__), array(), 1.0, true);

        wp_localize_script('locations-js', 'wp_locations_data', array('locations' => $return_data));

        wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . get_option('location_api_key') . '&callback=initMap&v=weekly', array('locations-js'), '', true);
    }
}

add_action('wp_enqueue_scripts', 'locations_assets');


if (is_admin()) {
    // Load admin features
    require_once __DIR__ . '/admin/wp-locations-admin.php';
}
