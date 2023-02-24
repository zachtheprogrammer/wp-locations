<?php
// Add location date and time to edit page
function admin_locations_custom()
{
    add_meta_box('location_meta', "Location Information", 'location_meta', 'locations', 'side', 'low');
}
add_action('admin_init', 'admin_locations_custom');

// Callback function for location_date_time_meta
function location_meta()
{
    global $post;
    $custom = get_post_custom($post->ID);
    $location_address = isset($custom['location_address']) ? $custom['location_address'][0] : '';
    $location_city = isset($custom['location_city']) ? $custom['location_city'][0] : '';
    $location_state = isset($custom['location_state']) ? $custom['location_state'][0] : '';
    $location_postal = isset($custom['location_postal']) ? $custom['location_postal'][0] : '';
    $location_latitude = isset($custom['location_latitude']) ? $custom['location_latitude'][0] : '';
    $location_longitude = isset($custom['location_longitude']) ? $custom['location_longitude'][0] : '';
    $location_size = isset($custom['location_size']) ? $custom['location_size'][0] : '';
?>
    <label>Location Address</label><br />
    <input type="address" name="location_address" value="<?php echo $location_address; ?>" /><br />
    <label>Location City</label><br />
    <input type="text" name="location_city" value="<?php echo $location_city; ?>" /><br />
    <label>Location State</label><br />
    <input type="text" name="location_state" value="<?php echo $location_state; ?>" /><br />
    <label>Location Postal</label><br />
    <input type="text" name="location_postal" value="<?php echo $location_postal; ?>" /><br />
    <label>Location Latitude</label><br />
    <input type="text" name="location_latitude" value="<?php echo $location_latitude; ?>" /><br />
    <label>Location Longitude</label><br />
    <input type="text" name="location_longitude" value="<?php echo $location_longitude; ?>" /><br />
    <label>Location Sq. Ft.</label><br />
    <input type="text" name="location_size" value="<?php echo $location_size; ?>" /><br />
<?php
}

// Save location
function save_locations()
{
    global $post;
    if (isset($_POST['location_address'])) {
        update_post_meta($post->ID, "location_address", $_POST['location_address']);
    }
    if (isset($_POST['location_city'])) {
        update_post_meta($post->ID, "location_city", $_POST['location_city']);
    }
    if (isset($_POST['location_state'])) {
        update_post_meta($post->ID, "location_state", $_POST['location_state']);
    }
    if (isset($_POST['location_postal'])) {
        update_post_meta($post->ID, "location_postal", $_POST['location_postal']);
    }
    if (isset($_POST['location_latitude'])) {
        update_post_meta($post->ID, "location_latitude", $_POST['location_latitude']);
    }
    if (isset($_POST['location_longitude'])) {
        update_post_meta($post->ID, "location_longitude", $_POST['location_longitude']);
    }
    if (isset($_POST['location_size'])) {
        update_post_meta($post->ID, "location_size", $_POST['location_size']);
    }
}
add_action('save_post', 'save_locations');

// Update locations view to include location date and time

function location_edit_columns($columns)
{
    $columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "title" => "Location Name",
        "location_address" => "Address",
        "location_city" => "City",
        "location_state" => "State",
        "location_postal" => "Postal",
        "location_latitude" => "Latitude",
        "location_longitude" => "Longitude",
        "location_size" => "Location Sq. Ft.",
        "location_categories" => "Location Categories",
    );

    return $columns;
}

function location_custom_columns($column)
{
    global $post;

    switch ($column) {
        case "location_address":
            $custom = get_post_custom();
            $location_address = isset($custom['location_address']) ? $custom['location_address'][0] : '';
            echo $location_address;
            break;
        case "location_city":
            $custom = get_post_custom();
            $location_city = isset($custom['location_city']) ? $custom['location_city'][0] : '';
            echo $location_city;
            break;
        case "location_state":
            $custom = get_post_custom();
            $location_state = isset($custom['location_state']) ? $custom['location_state'][0] : '';
            echo $location_state;
            break;
        case "location_postal":
            $custom = get_post_custom();
            $location_postal = isset($custom['location_postal']) ? $custom['location_postal'][0] : '';
            echo $location_postal;
            break;
        case "location_latitude":
            $custom = get_post_custom();
            $location_latitude = isset($custom['location_latitude']) ? $custom['location_latitude'][0] : '';
            echo $location_latitude;
            break;
        case "location_longitude":
            $custom = get_post_custom();
            $location_longitude = isset($custom['location_longitude']) ? $custom['location_longitude'][0] : '';
            echo $location_longitude;
            break;
        case "location_size":
            $custom = get_post_custom();
            $location_size = isset($custom['location_size']) ? $custom['location_size'][0] : '';
            echo $location_size;
            break;
        case "location_categories":
            echo get_the_term_list($post->ID, 'location_categories', '', ', ', '');
            break;
    }
}
add_filter("manage_edit-locations_columns", "location_edit_columns");
add_action("manage_posts_custom_column",  "location_custom_columns");

// Add settings page for maps API
function locations_register_settings()
{
    add_option('locations_api_key', '');
    register_setting('locations_options_group', 'location_api_key', '');
}
add_action('admin_init', 'locations_register_settings');

function locations_register_options_page()
{
    add_options_page('Location Plugin Settings', 'Location Plugin Settings', 'manage_options', 'locations', 'locations_options_page');
}
add_action('admin_menu', 'locations_register_options_page');

function locations_options_page()
{
?>
    <div>
        <h2>Location Plugin Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('locations_options_group'); ?>
            <table>
                <tr valign="top">
                    <th scope="row"><label for="location_api_key">API Key</label></th>
                    <td><input type="text" id="location_api_key" name="location_api_key" value="<?php echo get_option('location_api_key'); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
<?php
} ?>