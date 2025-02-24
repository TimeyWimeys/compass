<?php

// Settings
$cbn_enable_scrollwheel_zoom_map = (get_option('cbn_enable_scrollwheel_zoom_map') === 'on') ? 'true' : 'false';
$cbn_enable_regions = get_option('cbn_enable_regions');
$cbn_regions_layout_style = get_option('cbn_regions_layout_style', 'layout-1');
$cbn_enable_cluster = (get_option('cbn_enable_cluster', 'on') === 'on') ? 'true' : 'false';
$cbn_enable_fullscreen = (get_option('cbn_enable_fullscreen', 'on') === 'on') ? 'true' : 'false';
$cbn_enable_gmaps_link = get_option('cbn_enable_gmaps_link', 'on');
$cbn_max_image_filesize = !empty(get_option('cbn_max_image_filesize')) ? get_option('cbn_max_image_filesize') : 10;
$map_style = get_option('cbn_map_style') ? get_option('cbn_map_style') : 'Esri.WorldStreetMap';
$cbn_tile_provider_mapbox_key = get_option('cbn_tile_provider_mapbox_key', '');
$marker_icon = get_option('cbn_marker_icon') ? get_option('cbn_marker_icon') : 'default';
$marker_user_icon = get_option('cbn_marker_user_icon');
$map_size = get_option('cbn_map_size');
$cbn_map_height = get_option('cbn_map_height');
$cbn_map_height_mobile = get_option('cbn_map_height_mobile');
$cbn_action_after_submit = get_option('cbn_action_after_submit');
$thankyou_headline = get_option('cbn_thankyou_headline');
$thankyou_text = get_option('cbn_thankyou_text');
$thankyou_redirect = get_option('cbn_thankyou_redirect');
$cbn_enable_add_location = get_option('cbn_enable_add_location', 'on');
$cbn_enable_user_notification = get_option('cbn_enable_user_notification');
$text_notify_me_on_publish_label = get_option('cbn_user_notification_label') ? get_option('cbn_user_notification_label') : $this->cbn_user_notification_label_default;
$text_notify_me_on_publish_name = __('Your name', 'Compass');
$text_notify_me_on_publish_email = __('Your email', 'Compass');
$cbn_enable_currentlocation = get_option('cbn_enable_currentlocation') ? 'true' : 'false';
$cbn_disable_cbn_attribution = get_option('cbn_disable_cbn_attribution');
$cbn_collapse_filter = get_option('cbn_collapse_filter') ? 'use-collapse' : 'active';
$cbn_ui_color = get_option('cbn_ui_color') ? get_option('cbn_ui_color') : $this->cbn_ui_color_default;
$cbn_plus_button_label = get_option('cbn_plus_button_label') ? get_option('cbn_plus_button_label') : __('Add location', 'Compass');
$cbn_marker_types_label = get_option('cbn_marker_types_label') ? get_option('cbn_marker_types_label') : $this->cbn_marker_types_label_default;
$cbn_title_label = get_option('cbn_title_label') ? get_option('cbn_title_label') : $this->cbn_title_label_default;
$cbn_map_label = get_option('cbn_map_label') ? get_option('cbn_map_label') : $this->cbn_map_label_default;
$cbn_address_label = get_option('cbn_address_label') ? get_option('cbn_address_label') : $this->cbn_address_label_default;
$cbn_description_label = get_option('cbn_description_label') ? get_option('cbn_description_label') : $this->cbn_description_label_default;
$cbn_upload_media_label = get_option('cbn_upload_media_label') ? get_option('cbn_upload_media_label') : $this->cbn_upload_media_label_default;
$cbn_enable_fixed_map_bounds = get_option('cbn_enable_fixed_map_bounds');
$cbn_enable_multiple_marker_types = get_option('cbn_enable_multiple_marker_types', false) ? 'true' : 'false';

$cbn_enable_searchbar = (get_option('cbn_enable_searchbar', 'on') === 'on') ? 'true' : 'false';
$cbn_searchbar_type = get_option('cbn_searchbar_type') ? get_option('cbn_searchbar_type') : 'address';

$cbn_enable_searchmarkers_button = (get_option('cbn_enable_searchmarkers_button', 'on') === 'on') ? 'true' : 'false';
$cbn_searchmarkers_label = get_option('cbn_searchmarkers_label') ? get_option('cbn_searchmarkers_label') : $this->cbn_searchmarkers_label_default;

$cbn_searchmarkers_zoom = get_option('cbn_searchmarkers_zoom') ? get_option('cbn_searchmarkers_zoom') : $this->cbn_searchmarkers_zoom_default;

$cbn_geosearch_provider = get_option('cbn_geosearch_provider') ? get_option('cbn_geosearch_provider') : 'osm';
$cbn_geosearch_provider_geoapify_key = get_option('cbn_geosearch_provider_geoapify_key', '');
$cbn_geosearch_provider_here_key = get_option('cbn_geosearch_provider_here_key', '');
$cbn_geosearch_provider_mapbox_key = get_option('cbn_geosearch_provider_mapbox_key', '');

$cbn_enable_searchaddress_button = (get_option('cbn_enable_searchaddress_button', 'on') === 'on') ? 'true' : 'false';
$cbn_searchaddress_label = get_option('cbn_searchaddress_label') ? get_option('cbn_searchaddress_label') : $this->cbn_searchaddress_label_default;

$cbn_custom_js = get_option('cbn_custom_js');

$cbn_location_date_type = get_option('cbn_location_date_type', 'modified');

// Custom Attribute: Map Size
if (isset($block_attributes['size']) && $block_attributes['size'] != '') {
    $map_size = $block_attributes['size'];
}

// Custom Attribute: Map Style
if (isset($block_attributes['map_style']) && $block_attributes['map_style'] != '') {
    $map_style = $block_attributes['map_style'];
}

// Custom Attribute: Height
if (isset($block_attributes['height']) && $block_attributes['height'] != '') {
    $cbn_map_height = $block_attributes['height'];
}

// Custom Attribute: Height (Mobile)
if (isset($block_attributes['height_mobile']) && $block_attributes['height_mobile'] != '') {
    $cbn_map_height_mobile = $block_attributes['height_mobile'];
}

// Custom Attribute: Clustering (true|false)
if (isset($block_attributes['enable_cluster']) && $block_attributes['enable_cluster'] != '') {
    $cbn_enable_cluster = $block_attributes['enable_cluster'];
}

// Custom Attribute: Map Type (interactive|simple)
if (isset($block_attributes['map_type']) && $block_attributes['map_type'] != '') {
    $cbn_enable_add_location = ($block_attributes['map_type'] == 'interactive') ? 'on' : '';
}

// Custom Attribute: Fullscreen (true|false)
if (isset($block_attributes['enable_fullscreen']) && $block_attributes['enable_fullscreen'] != '') {
    $cbn_enable_fullscreen = $block_attributes['enable_fullscreen'];
}

// Custom Attribute: Searchbar (true|false)
if (isset($block_attributes['enable_searchbar']) && $block_attributes['enable_searchbar'] != '') {
    $cbn_enable_searchbar = $block_attributes['enable_searchbar'];
}

// Custom Attribute: Search Address Button (true|false)
if (isset($block_attributes['enable_searchaddress_button']) && $block_attributes['enable_searchaddress_button'] != '') {
    $cbn_enable_searchaddress_button = $block_attributes['enable_searchaddress_button'];
}

// Custom Attribute: Search Markers Button (true|false)
if (isset($block_attributes['enable_searchmarkers_button']) && $block_attributes['enable_searchmarkers_button'] != '') {
    $cbn_enable_searchmarkers_button = $block_attributes['enable_searchmarkers_button'];
}

// Custom Attribute: Current Location Button (true|false)
if (isset($block_attributes['enable_currentlocation']) && $block_attributes['enable_currentlocation'] != '') {
    $cbn_enable_currentlocation = $block_attributes['enable_currentlocation'];
}

// Custom Attribute: Disable Regions (true|false)
if (isset($block_attributes['disable_regions']) && $block_attributes['disable_regions'] != '') {
    $cbn_enable_regions = ($block_attributes['disable_regions'] == 'true') ? '' : $cbn_enable_regions;
}

if ($cbn_enable_regions == 'on') {
    // Taxonomy: Regions
    $regions = get_terms(array(
      'taxonomy' => 'cbn-region',
      'hide_empty' => false,
      'meta_query' => array(
        'relation' => 'AND',
        array(
          'key'       => 'cbn_lat',
          'compare'   => 'EXISTS'
        ),
        array(
          'key'       => 'cbn_lng',
          'compare'   => 'EXISTS'
        ),
        array(
          'key'       => 'cbn_zoom',
          'compare'   => 'EXISTS'
        )
      )
    ));
}

// Taxonomy: Types (Marker Categories)
$types = get_terms(array(
  'taxonomy' => 'cbn-type',
  'hide_empty' => false
));

if (is_wp_error($types) || empty($types)) {
    $types = false;
}

$query = array(
  'post_type' => 'cbn-location',
  'posts_per_page' => -1,
  'suppress_filters' => false,
);

// Custom Attribute: Filter for types
if (isset($block_attributes['types']) && $block_attributes['types'] != '') {
    $selected_types_slugs = explode('|', $block_attributes['types']);
    $query['tax_query'] = array(
      array(
        'taxonomy' => 'cbn-type',
        'field'    => 'slug',
        'terms'    => $selected_types_slugs,      // provide the term slugs
      ),
    );

    //overwrite types with filtered types
    $types = [];
    foreach ($selected_types_slugs as $slug) {
        $types[] = get_term_by('slug', $slug, 'cbn-type');
    }
};

// Custom Attribute: Filter for ids
if (isset($block_attributes['ids']) && $block_attributes['ids'] != '') {
    $selected_ids = explode('|', $block_attributes['ids']);
    $query['post__in'] = $selected_ids;
};

// Custom Attribute: Pre-select region
if (isset($regions) && isset($block_attributes['region']) && $block_attributes['region'] != '') {
    $cbn_start_region_name = $block_attributes['region'];

    $regions_filtered = array_filter($regions, function ($obj) use ($cbn_start_region_name) {
        return $obj->name == $cbn_start_region_name;
    });

    if (!empty($regions_filtered)) {
        $cbn_start_region = current($regions_filtered);
    }
}

// Instead of get_posts(), use WP_Query to get all post data at once
$locations_query = new WP_Query($query);
$posts = $locations_query->posts;

// Get all post meta in a single query
$post_ids = wp_list_pluck($posts, 'ID');

global $wpdb;
$all_meta = $wpdb->get_results($wpdb->prepare(
    "SELECT post_id, meta_key, meta_value 
    FROM $wpdb->postmeta 
    WHERE post_id IN (" . implode(',', array_fill(0, count($post_ids), '%d')) . ")
    AND meta_key IN ('_cbn_location_key', '_cbn_location_image', '_cbn_location_audio')",
    $post_ids
));

// Index meta values by post_id and meta_key for faster lookup
$indexed_meta = array();
foreach ($all_meta as $meta) {
    if (!isset($indexed_meta[$meta->post_id])) {
        $indexed_meta[$meta->post_id] = array();
    }
    $indexed_meta[$meta->post_id][$meta->meta_key] = $meta->meta_value;
}

// Move this outside the loop - get active custom fields once
$active_custom_fields = get_option('cbn_custom_fields');

$locations_list = array();
foreach ($posts as $post) {
    $post_id = $post->ID;

    // Get all meta values for this post from our indexed array
    $location_meta = isset($indexed_meta[$post_id]['_cbn_location_key']) ?
        maybe_unserialize($indexed_meta[$post_id]['_cbn_location_key']) : array();

    if (!isset($location_meta['lat']) || !isset($location_meta['lng'])) {
        continue;
    }

    $name = str_replace("'", "\'", strip_tags($post->post_title));
    $address = isset($location_meta['address']) ?
        str_replace("'", "\'", (preg_replace('/\r|\n/', '', $location_meta['address']))) : '';
    $text = isset($location_meta["text"]) ?
        str_replace("'", "\'", str_replace(array("\r\n", "\r", "\n"), "<br>", $location_meta["text"])) : '';
    $video = isset($location_meta["video"]) ? $location_meta["video"] : '';

    $image = isset($indexed_meta[$post_id]['_cbn_location_image']) ?
        $indexed_meta[$post_id]['_cbn_location_image'] : '';
    $image_thumb = null;

    // Handle multiple images
    $images = array();
    if ($image) {
        $image_urls = explode('|', $image);
        foreach ($image_urls as $url) {
            if (stristr($url, 'cbn-useruploads')) {
                // Handle user uploads - always use original image
                $images[] = $url;
            } else {
                // Handle media library images
                $image_id = attachment_url_to_postid($url);
                if ($image_id > 0) {
                    $thumb = wp_get_attachment_image_url($image_id, 'medium');
                    $images[] = $thumb ? $thumb : $url;
                } else {
                    $images[] = $url;
                }
            }
        }
    }

    // Make image URLs relative
    $site_url = 'http://';
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        $site_url = 'https://';
    }
    $site_url .= $_SERVER['SERVER_NAME'];

    $images = array_map(function ($url) use ($site_url) {
        return str_replace($site_url, '', $url);
    }, $images);

    $audio = isset($indexed_meta[$post_id]['_cbn_location_audio']) ?
        $indexed_meta[$post_id]['_cbn_location_audio'] : '';

    // Optimized custom fields processing
    $custom_fields = [];
    $meta_custom_fields = isset($location_meta['custom_fields']) ? $location_meta['custom_fields'] : false;

    if (is_array($meta_custom_fields) && is_array($active_custom_fields)) {
        // Iterate over active_custom_fields to maintain order
        foreach ($active_custom_fields as $index => $custom_field) {
            // Skip if field is marked as private
            if (isset($custom_field['private'])) {
                continue;
            }

            // Skip if no value exists for this field
            if (!isset($meta_custom_fields[$index])) {
                continue;
            }

            $custom_fields[] = [
                'index' => $index,
                'label' => $custom_field['label'],
                'val' => $meta_custom_fields[$index],
                'fieldtype' => isset($custom_field['fieldtype']) ? $custom_field['fieldtype'] : 'text',
                'uselabelastextoption' => isset($custom_field['uselabelastextoption']) ?
                    $custom_field['uselabelastextoption'] : false,
            ];
        }
    }




    //PRO Feature: use types
    $location_types = (get_the_terms($post_id, 'cbn-type') && !is_wp_error(get_the_terms($post_id, 'cbn-type'))) ? get_the_terms($post_id, 'cbn-type') : false;

    if (isset($location_types) && is_array($location_types) && count($location_types) == 1 && !get_option('cbn_enable_multiple_marker_types')) {
        $type = $location_types[0];
        if ($type->term_id && get_term_meta($type->term_id, 'cbn_marker_icon', true)) {
            //get current location icon from cbn-type taxonomy
            $current_marker_icon = get_term_meta($type->term_id, 'cbn_marker_icon', true);
            $current_marker_user_icon = get_term_meta($type->term_id, 'cbn_marker_user_icon', true);
        } else {
            //get current location icon from settings
            $current_marker_icon = $marker_icon;
            $current_marker_user_icon = $marker_user_icon;
        }
    } else {
        //get current location icon from settings
        $current_marker_icon = $marker_icon;
        $current_marker_user_icon = $marker_user_icon;
    }

    if ($current_marker_icon == 'user1' && $current_marker_user_icon) {
        $icon = esc_url($current_marker_user_icon);
    } else {
        $icon = esc_url($this->plugin_url) . 'src/leaflet/images/marker-icon_' . esc_attr($current_marker_icon) .'-2x.png';
    }

    // Date: modified or published
    if ($cbn_location_date_type == 'created') {
        $date = get_the_date('', $post_id);
    } else {
        $date = get_the_modified_date('', $post_id);
    }

    // collect locations for JS use
    $location = array(
      'post_id' => $post_id,
      'date' => $date,
      'name' => $name,
      'address' => $address,
      'lat' => $location_meta['lat'],
      'lng' => $location_meta['lng'],
      'text' => $text,
      'images' => $images,
      'image' => !empty($images) ? $images[0] : '',
      'audio' => $audio,
      'video' => $video,
      'icon' => $icon,
      'custom_fields' => $custom_fields,
      'author_id' => get_post_field('post_author', $post_id), // Added author_id here
    );

    if (isset($location_types) && is_array($location_types) && count($location_types) > 0) {
        foreach ($location_types as $term) {
            $location['types'][] = (string) $term->term_taxonomy_id;
        }
    }

    $locations_list[] = $location;
}

$cbn_use_settings_start_location = 'false';

// Set focus for map init
if (isset($block_attributes['lat']) && $block_attributes['lat'] != '' && isset($block_attributes['long']) && $block_attributes['long'] != '' && isset($block_attributes['zoom']) && $block_attributes['zoom'] != '') {
    //get lat, long, zoom from shortcode attributes
    $start_lat = str_replace(',', '.', $block_attributes['lat']);
    $start_lng = str_replace(',', '.', $block_attributes['long']);
    $start_zoom = str_replace(',', '.', $block_attributes['zoom']);
} elseif (isset($cbn_start_region) && $cbn_start_region != '') {
    //get region from shortcode attribute
    $start_lat = get_term_meta($cbn_start_region->term_id, 'cbn_lat', true);
    $start_lng = get_term_meta($cbn_start_region->term_id, 'cbn_lng', true);
    $start_zoom = get_term_meta($cbn_start_region->term_id, 'cbn_zoom', true);
} elseif (get_option('cbn_start_lat') && get_option('cbn_start_lng') && get_option('cbn_start_zoom')) {
    //get from settings
    $cbn_use_settings_start_location = 'true';
    $start_lat = get_option('cbn_start_lat');
    $start_lng = get_option('cbn_start_lng');
    $start_zoom = get_option('cbn_start_zoom');
} elseif (count($locations_list) == 1) {
    //get from single location
    $start_lat = $locations_list[0]['lat'];
    $start_lng = $locations_list[0]['lng'];
    $start_zoom = '8';
} else {
    //default worldview
    $start_lat = '28';
    $start_lng = '0';
    $start_zoom = '1';
}

$i = 0;

// BUGFIX: resolves issue with non-unique ids when caching inline js with 3rd party plugins
// todo: allow multiple maps/shortcodes on same site

//$unique_id = uniqid();
$unique_id = 20210929;
