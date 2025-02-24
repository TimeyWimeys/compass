<?php

/**
 * @package CompassPlugin
 */

namespace CompassPlugin\Base;

class LocationController extends BaseController
{
    public $settings;

    public function register()
    {
        // CPT: Location
        add_action('init', array($this, 'location_cpt'));
        add_action('admin_init', array($this, 'cbn_capabilities'));
        add_action('add_meta_boxes', array($this, 'add_meta_box'));
        add_action('save_post', array($this, 'save_fields'));
        add_action('manage_cbn-location_posts_columns', array($this, 'set_custom_location_columns'));
        add_action('manage_cbn-location_posts_custom_column', array($this, 'set_custom_location_columns_data'), 10, 2); // this method has 2 attributes
        add_action('pre_get_posts', array($this, 'custom_search_cbn_location'));
        add_action('admin_menu', array($this, 'add_pending_counter_to_menu'));

        add_filter('post_thumbnail_html', array($this, 'default_location_header'), 10, 5);
        add_filter('the_content', array($this, 'default_location_content'));
    }

    /**
     * CPT: Location
     */

    public static function location_cpt()
    {
        $labels = array(
            'name' => __('Locations', 'Compass'),
            'singular_name' => __('Location', 'Compass'),
            'add_new' => __('Add new Location', 'Compass'),
            'add_new_item' => __('Add new Location', 'Compass'),
            'edit_item' => __('Edit Location', 'Compass'),
            'new_item' => __('New Location', 'Compass'),
            'all_items' => __('All Locations', 'Compass'),
            'view_item' => __('View Location', 'Compass'),
            'search_items' => __('Search Locations', 'Compass'),
            'not_found' => __('No Locations found', 'Compass'),
            'not_found_in_trash' => __('No Location in trash', 'Compass'),
            'parent_item_colon' => '',
            'menu_name' => __('Compass', 'Compass'),
        );
        $args = array(
            'labels' => $labels,
            'capability_type' => 'cbn-location',
            'map_meta_cap' => true,
            'description' => __('Location', 'Compass'),
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'exclude_from_search' => true,
            'show_in_nav_menus' => false,
            'has_archive' => false,
            'menu_position' => 20,
            'menu_icon' => 'dashicons-location-alt',
            'supports' => array('title', 'author', 'thumbnail', 'excerpt', 'revisions', 'trash'),
        );




        //enable single pages
        if (get_option('cbn_enable_single_page')) {
            $args['public'] = true;
            $args['publicly_queryable'] = true;
            $args['show_in_nav_menus'] = true; //needs to be true to show up in Elementor template builder
            $args['supports'] = array('title', 'author', 'editor', 'comments', 'thumbnail', 'excerpt', 'revisions', 'trash');
            $args['has_archive'] = true;
            $args['show_in_rest'] = true;
        }

        register_post_type('cbn-location', $args);
    }

    /**
     * Assign default capabilities to default user roles (same as 'post')
     */
    public function cbn_capabilities()
    {
        // Administrator, Editor
        $roles = array('editor','administrator');
        foreach ($roles as $the_role) {
            $role = get_role($the_role);

            if (!is_null($role)) {
                $role->add_cap('read_cbn-location');
                $role->add_cap('read_private_cbn-locations');
                $role->add_cap('edit_cbn-location');
                $role->add_cap('edit_cbn-locations');
                $role->add_cap('edit_others_cbn-locations');
                $role->add_cap('edit_published_cbn-locations');
                $role->add_cap('edit_private_cbn-locations');
                $role->add_cap('publish_cbn-locations');
                $role->add_cap('delete_cbn-locations');
                $role->add_cap('delete_others_cbn-locations');
                $role->add_cap('delete_private_cbn-locations');
                $role->add_cap('delete_published_cbn-locations');
            }
        }

        // Author
        $role = get_role('author');
        if (!is_null($role)) {
            $role->add_cap('edit_cbn-locations');
            $role->add_cap('edit_published_cbn-locations');
            $role->add_cap('publish_cbn-locations');
            $role->add_cap('delete_cbn-locations');
            $role->add_cap('delete_published_cbn-locations');
        }

        // Contributor
        $role = get_role('contributor');
        if (!is_null($role)) {
            $role->add_cap('edit_cbn-locations');
            $role->add_cap('delete_cbn-locations');
        }

        // Subscriber
        $role = get_role('subscriber');
        if (!is_null($role)) {
            $role->add_cap('edit_cbn-locations');
            $role->add_cap('delete_cbn-locations');
        }
    }

    public function add_meta_box()
    {
        add_meta_box(
            'location_customfields',
            __('Compass Location Settings', 'Compass'),
            array($this, 'render_customfields_box'),
            'cbn-location',
            'normal',
            'high'
        );
    }

    public function render_customfields_box($post)
    {
        wp_nonce_field('cbn_location', 'cbn_location_nonce');

        $data = get_post_meta($post->ID, '_cbn_location_key', true);

        //error_log(print_r($data, true));


        $address = $data['address'] ?? '';
        $lat = $data['lat'] ?? '';
        $lng = $data['lng'] ?? '';
        $text = $data['text'] ?? '';
        $video = $data['video'] ?? '';
        $has_video = (isset($video) && $video != '') ? 'has-video' : '';
        $video_tag = ($has_video) ? apply_filters('the_content', esc_attr($video)) : '';

        $image = get_post_meta($post->ID, '_cbn_location_image', true);
        $has_image = (isset($image) && $image != '') ? 'has-image' : '';
        $image_tag = ($has_image) ? '<img src="'.esc_attr($image).'" style="width: 100%;">' : '';

        $audio = get_post_meta($post->ID, '_cbn_location_audio', true);
        $has_audio = (isset($audio) && $audio != '') ? 'has-audio' : '';
        $audio_tag = ($has_audio) ? '<audio controls="controls" style="width:100%"><source type="audio/mp4" src="'.esc_attr($audio).'"><source type="audio/mpeg" src="'.esc_attr($audio).'"><source type="audio/wav" src="'.esc_attr($audio).'"></audio>' : '';

        $notification = $data['notification'] ?? '';
        $author_name = $data['author_name'] ?? '';
        $author_email = $data['author_email'] ?? '';
        $text_notify_me_on_publish_label = get_option('cbn_user_notification_label') ? get_option('cbn_user_notification_label') : $this->cbn_user_notification_label_default;
        $text_notify_me_on_publish_name = __('Your name', 'Compass');
        $text_notify_me_on_publish_email = __('Your email', 'Compass');
        $notified = get_post_meta($post->ID, '_cbn_location_notified', true);
        $notified_tag = (isset($notified) && $notified != '') ? '<p>User has been notified on ' . date("Y-m-d H:i:s", $notified) . '</p>' : '';


        // Set map style
        $map_style = get_option('cbn_map_style') ? get_option('cbn_map_style') : 'Esri.WorldStreetMap';
        $cbn_tile_provider_mapbox_key = get_option('cbn_tile_provider_mapbox_key', '');

        $marker_icon = get_option('cbn_marker_icon') ? get_option('cbn_marker_icon') : 'default';
        $marker_user_icon = get_option('cbn_marker_user_icon');

        $meta_custom_fields = $data['custom_fields'] ?? false;
        $active_custom_fields = get_option('cbn_custom_fields');

        // render view
        require_once cbn_get_template('page-backend-location.php');
    }

    /**
     * Save Location Fields (Backend)
     */
    public static function save_fields($post_id, $fields = array())
    {
        $location_data = $_REQUEST;

        // Set data source ($_REQUEST or $fields)
        if (!empty($fields)) {
            $location_data = $fields;
            $location_data['post_type'] = 'cbn-location';
        }

        // Dont save if not a location
        if (!isset($location_data['post_type']) || $location_data['post_type'] != 'cbn-location') {
            return $post_id;
        }

        // Handle image uploads and updates
        if (isset($location_data['cbn_location_image'])) {
            $images = explode('|', $location_data['cbn_location_image']);

            // Validate image URLs
            $valid_images = array();
            foreach ($images as $image_url) {
                if (!empty($image_url) && strpos($image_url, '|') === false) {
                    $valid_images[] = esc_url_raw($image_url);
                }
            }

            // Store images as pipe-separated string
            update_post_meta($post_id, '_cbn_location_image', implode('|', $valid_images));

            // Set first image as featured image if available
            if (!empty($valid_images[0])) {
                // Download image from URL and set as featured image
                $upload = media_sideload_image($valid_images[0], $post_id, null, 'src');

                if (!is_wp_error($upload)) {
                    $attachment_id = attachment_url_to_postid($upload);
                    if ($attachment_id) {
                        set_post_thumbnail($post_id, $attachment_id);
                    }
                }
            }
        }

        // Set post thumbnail and excerpt when saving inline (Quick Edit) and exit
        if (isset($location_data['action']) && in_array($location_data['action'], array('edit', 'inline-save'))) {

            // Dont save if wordpress just auto-saves
            if (defined('DOING AUTOSAVE') && DOING_AUTOSAVE) {
                return $post_id;
            }

            // Dont save if user is not allowed to do
            $has_general_permission = current_user_can('edit_cbn-locations');
            $is_author = (get_current_user_id() == get_post_field('post_author', $post_id));
            $can_edit_specific_post = current_user_can('edit_post', $post_id);
            $allow_edit = $has_general_permission && ($is_author || $can_edit_specific_post);
            if (!$allow_edit) {
                return $post_id;
            }

            // Set excerpt if not set
            if (get_the_excerpt($post_id) == '') {

                // Set location text as post excerpt
                $max_length = 400;
                $post_text = cbn_get_location_value('text', $post_id, true);
                $text = wp_strip_all_tags($post_text);

                if ($text) {
                    if (strlen($text) > $max_length) {
                        $text = substr($text, 0, $max_length);
                        $last_space = strrpos($text, ' ');
                        if ($last_space !== false) {
                            $text = substr($text, 0, $last_space);
                        }

                        $text .= '...';
                    }

                    $post = array(
                        'ID' => $post_id,
                        'post_excerpt' => sanitize_text_field($text)
                    );
                    wp_update_post($post);
                }
            }

            return $post_id;
        }

        // Dont save without nonce
        if (!isset($location_data['cbn_location_nonce'])) {
            return $post_id;
        }

        // Dont save if nonce is incorrect
        $nonce = $location_data['cbn_location_nonce'];
        if (!wp_verify_nonce($nonce, 'cbn_location')) {
            return $post_id;
        }

        // Dont save if wordpress just auto-saves
        if (defined('DOING AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        // Dont save if user is not allowed to do
        $has_general_permission = current_user_can('edit_cbn-locations');
        $is_author = (get_current_user_id() == get_post_field('post_author', $post_id));
        $can_edit_specific_post = current_user_can('edit_post', $post_id);
        $allow_edit = $has_general_permission && ($is_author || $can_edit_specific_post);
        if (!$allow_edit) {
            return $post_id;
        }

        // Validation
        $lat_validated = isset($location_data['cbn_location_lat']) ? floatval(str_replace(',', '.', sanitize_text_field($location_data['cbn_location_lat']))) : '';

        $lng_validated = isset($location_data['cbn_location_lng']) ? floatval(str_replace(',', '.', sanitize_text_field($location_data['cbn_location_lng']))) : '';


        $data = array(
            'address' => isset($location_data['cbn_location_address']) ? sanitize_text_field($location_data['cbn_location_address']) : '',
            'lat' => $lat_validated,
            'lng' => $lng_validated,
            'text' => isset($location_data['cbn_location_text']) ? wp_kses_post($location_data['cbn_location_text']) : '',
            'author_name' => isset($location_data['cbn_location_author_name']) ? sanitize_text_field($location_data['cbn_location_author_name']) : '',
            'author_email' => isset($location_data['cbn_location_author_email']) ? sanitize_text_field($location_data['cbn_location_author_email']) : '',
            'video' => isset($location_data['cbn_location_video']) ? sanitize_text_field($location_data['cbn_location_video']) : '',
        );

        if (isset($location_data['cbn_location_notification'])) {
            $data['notification'] = sanitize_text_field($location_data['cbn_location_notification']);
        }

        if (isset($location_data['cbn_location_custom_fields']) && is_array($location_data['cbn_location_custom_fields'])) {
            $data['custom_fields'] = $location_data['cbn_location_custom_fields'];
        }


        update_post_meta($post_id, '_cbn_location_key', $data);

        if (isset($location_data['cbn_location_audio'])) {
            // validate & store audio seperately (to avoid serialized URLs [bad for search & replace due to domain change])
            $data_audio = esc_url_raw($location_data['cbn_location_audio']);
            update_post_meta($post_id, '_cbn_location_audio', $data_audio);
        }

        // Set excerpt if not set
        if (get_the_excerpt($post_id) == '') {

            // Set location text as post excerpt
            $max_length = 400;
            $post_text = cbn_get_location_value('text', $post_id, true);
            $text = wp_strip_all_tags($post_text);

            if ($text) {
                if (strlen($text) > $max_length) {
                    $text = substr($text, 0, $max_length);
                    $last_space = strrpos($text, ' ');
                    if ($last_space !== false) {
                        $text = substr($text, 0, $last_space);
                    }

                    $text .= '...';
                }

                $post = array(
                    'ID' => $post_id,
                    'post_excerpt' => sanitize_text_field($text)
                );
                wp_update_post($post);
            }
        }
        return $post_id;
    }

    public function set_custom_location_columns($columns): array
    {
        // Get all default columns we want to preserve
        $cb = $columns['cb'] ?? '';
        $title = $columns['title'] ?? '';
        $author = $columns['author'] ?? '';
        $categories = $columns['taxonomy-cbn-type'] ?? '';
        $comments = $columns['comments'] ?? '';
        $date = $columns['date'] ?? '';

        // Remove all columns
        $columns = array();

        // Add columns in desired order
        if ($cb) {
            $columns['cb'] = $cb;
        }
        $columns['post_id'] = 'ID';
        $columns['title'] = $title;
        $columns['address'] = __('Subtitle', 'Compass');
        if ($categories) {
            $columns['taxonomy-cbn-type'] = $categories;
        }
        $columns['text'] = __('Text', 'Compass');
        $columns['geocoordinates'] = __('Coordinates', 'Compass');

        if ($comments) {
            $columns['comments'] = $comments;
        }
        if ($author) {
            $columns['author'] = $author;
        }
        if ($date) {
            $columns['date'] = $date;
        }

        return $columns;
    }

    public function set_custom_location_columns_data($column, $post_id)
    {
        $data = get_post_meta($post_id, '_cbn_location_key', true);

        $text = $data['text'] ?? '';
        $address = $data['address'] ?? '';
        $lat = $data['lat'] ?? '';
        $lng = $data['lng'] ?? '';

        switch ($column) {
            case 'post_id':
                echo esc_html($post_id);
                break;
            case 'text':
                echo esc_html($text);
                break;
            case 'address':
                echo esc_html($address);
                break;
            case 'geocoordinates':
                echo esc_attr($lat) . ', ' . esc_attr($lng);
                break;
            default:
                break;
        }
    }

    /**
     * Custom search (backend) for locations (inlcuding meta and author)
     */
    public function custom_search_cbn_location($query)
    {
        // Ensure we're in the WordPress admin, it's a search query, and the right post type
        if ($query->is_search() && is_admin() && $query->is_main_query() && isset($_GET['post_type']) && $_GET['post_type'] === 'cbn-location') {

            // Get the search term
            $search_term = $query->query_vars['s'];

            // Clear the default search query
            $query->set('s', '');

            // Join wp_users and wp_postmeta tables for author and meta field searches
            add_filter('posts_join', function ($join) {
                global $wpdb;

                // Join wp_users for author search
                if (strpos($join, "$wpdb->users") === false) {
                    $join .= " LEFT JOIN $wpdb->users AS u ON $wpdb->posts.post_author = u.ID ";
                }

                // Join wp_postmeta for meta field search
                if (strpos($join, "$wpdb->postmeta") === false) {
                    $join .= " LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id ";
                }

                return $join;
            });

            // Modify the search query to include user_login, user_email, post_title, and post_content search
            add_filter('posts_search', function ($search) use ($search_term) {
                global $wpdb;

                if ($search_term) {
                    $escaped_term = '%' . $wpdb->esc_like($search_term) . '%';

                    // Combine search conditions for post title, content, and author fields
                    $search .= " AND ($wpdb->posts.post_title LIKE '$escaped_term' 
                                    OR $wpdb->posts.post_content LIKE '$escaped_term' 
                                    OR u.user_login LIKE '$escaped_term' 
                                    OR u.user_email LIKE '$escaped_term') ";
                }

                return $search;
            }, 10, 2);

            // Modify the WHERE clause to include the meta query for '_cbn_location_key'
            add_filter('posts_where', function ($where) use ($search_term) {
                global $wpdb;

                // Search in the _cbn_location_key meta field
                $escaped_meta_value = '%' . $wpdb->esc_like($search_term) . '%';
                $where .= $wpdb->prepare(" OR ($wpdb->postmeta.meta_key = '_cbn_location_key' AND $wpdb->postmeta.meta_value LIKE %s)", $escaped_meta_value);

                return $where;
            });

            // Group results by post ID to avoid duplicates from multiple meta entries
            add_filter('posts_groupby', function ($groupby) {
                global $wpdb;
                if (!$groupby) {
                    $groupby = "$wpdb->posts.ID";  // Group by post ID to ensure unique results
                }
                return $groupby;
            });
        }
    }


    public function add_pending_counter_to_menu()
    {
        global $menu;
        $count = count(get_posts(array(
            'post_type' => 'cbn-location',
            'post_status' => 'pending',
            'posts_per_page' => -1,
            'fields' => 'ids'
        )));

        $menu_item = wp_list_filter(
            $menu,
            array( 2 => 'edit.php?post_type=cbn-location' ) // 2 is the position of an array item which contains URL, it will always be 2!
        );

        if (! empty($menu_item)  && $count >= 1) {
            $menu_item_position = key($menu_item); // get the array key (position) of the element
            $menu[ $menu_item_position ][0] .= ' <span class="awaiting-mod">' . $count . '</span>';
        }
    }


    /**
     * Get a value from a location
     */
    public function get_location_value($attr, $post_id, $raw = false)
    {
        $location = get_post_meta($post_id, '_cbn_location_key', true);
        $custom_field_ids = get_option('cbn_custom_fields', array()); // get all available custom fields
        get_terms(array(
            'taxonomy' => 'cbn-type',
            'hide_empty' => false
        )); // get all available types
        $value = '';

        if ($attr == 'title') {

            // GET TITLE
            $value = get_the_title($post_id);

        } elseif ($attr == 'image') {

            // GET IMAGE
            $image = get_post_meta($post_id, '_cbn_location_image', true);
            $has_image = (isset($image) && $image != '') ? 'has-image' : '';

            if ($has_image) {
                $images = explode('|', $image);

                if (count($images) > 1 && !$raw) {
                    // Enqueue carousel script and styles
                    wp_enqueue_style('cbn_frontend_css', plugin_dir_url(dirname(__FILE__, 2)) . 'assets/frontend.css', array(), $this->plugin_version);
                    wp_enqueue_script('cbn_frontend_carousel_js', plugin_dir_url(dirname(__FILE__, 2)) . 'src/js/frontend-carousel.js', array(), $this->plugin_version);

                    // Multiple images - use carousel
                    $value = '<div class="cbn-carousel">';
                    $value .= '<div class="cbn-carousel-inner">';

                    foreach ($images as $index => $image_url) {
                        if (!empty($image_url)) {
                            $active_class = ($index === 0) ? ' active' : '';
                            $value .= '<div class="cbn-carousel-item' . $active_class . '">';
                            $value .= '<img class="skip-lazy" src="' . esc_url_raw($image_url) . '">';
                            $value .= '</div>';
                        }
                    }

                    $value .= '</div>';
                    $value .= '</div>';
                } else {
                    // Single image or raw output
                    if (!$raw) {
                        $value = '<img src="' . esc_attr($images[0]) . '">';
                    } else {
                        $value = esc_attr($image);
                    }
                }
            } else {
                $value = '';
            }

        } elseif ($attr == 'audio') {

            // GET AUDIO
            $audio = get_post_meta($post_id, '_cbn_location_audio', true);
            $has_audio = (isset($audio) && $audio != '') ? 'has-audio' : '';

            if (!$raw) {
                $value = ($has_audio) ? '<audio controls="controls" style="width:100%"><source type="audio/mp4" src="'.esc_attr($audio).'"><source type="audio/mpeg" src="'.esc_attr($audio).'"><source type="audio/wav" src="'.esc_attr($audio).'"></audio>' : '';
            } else {
                $value = ($has_audio) ? esc_attr($audio) : '';
            }

        } elseif ($attr == 'video') {

            // GET VIDEO
            $video = $location['video'];
            $has_video = (isset($video) && $video != '') ? 'has-video' : '';
            $video_tag = ($has_video) ? apply_filters('the_content', esc_attr($video)) : '';

            if (!$raw) {
                $value = ($has_video) ? $video_tag : '';
            } else {
                $value = ($has_video) ? esc_attr($video) : '';
            }

        } elseif ($attr == 'type') {

            // GET TYPE
            $location_types = (get_the_terms($post_id, 'cbn-type') && !is_wp_error(get_the_terms($post_id, 'cbn-type'))) ? get_the_terms($post_id, 'cbn-type') : false;

            if (isset($location_types) && is_array($location_types) && count($location_types) == 1 && !get_option('cbn_enable_multiple_marker_types')) {
                $value = $location_types[0]->name;
            } else {
                $value = '';
                if (isset($location_types) && is_array($location_types)) {
                    $value = implode('|', wp_list_pluck($location_types, 'name'));
                }
            }

        } elseif ($attr == 'map') {

            // GET MAP
            $plugin_url = plugin_dir_url(dirname(__FILE__, 2));
            $map_style = get_option('cbn_map_style') ? get_option('cbn_map_style') : 'Esri.WorldStreetMap';
            $cbn_tile_provider_mapbox_key = get_option('cbn_tile_provider_mapbox_key', '');
            $lat = $location['lat'];
            $lng = $location['lng'];
            $zoom = '13';
            $marker_icon = get_option('cbn_marker_icon') ? get_option('cbn_marker_icon') : 'default';
            $marker_user_icon = get_option('cbn_marker_user_icon');
            $marker_icon_url = ($marker_icon == 'user1' && $marker_user_icon) ? esc_url($marker_user_icon) : esc_url($plugin_url).'src/leaflet/images/marker-icon_'.esc_attr($marker_icon).'-2x.png';
            $marker_shadow_url = esc_url($plugin_url) . 'src/leaflet/images/marker-shadow.png';

            $value = '<div id="mapRenderLocation" data-lat="' . $lat . '" data-lng="' . $lng . '" data-zoom="' . $zoom . '" data-mapstyle="' . $map_style . '" data-tile_provider_mapbox_key="' . $cbn_tile_provider_mapbox_key . '" data-marker_icon_url="' . $marker_icon_url . '" data-marker_shadow_url="' . $marker_shadow_url . '" class="Compass-location-map leaflet-map map-style_' . $map_style . '"></div>';

        } elseif ($attr == 'route') {

            // GET GOOGLE ROUTE LINK
            $lat = esc_attr($location['lat']);
            $lng = esc_attr($location['lng']);
            $text = $location['address'] ?: __('Route on Google Maps', 'Compass');
            $value = '<a title="' . __('go to Google Maps', 'Compass') . '" href="https://www.google.com/maps/search/?api=1&amp;query=' . $lat . '%2C' . $lng . '" target="_blank">' . $text . '</a>';

        } elseif ($attr == 'wp_author_id') {

            // GET AUTHOR ID
            $value = get_post_field('post_author', $post_id);

        } elseif (isset($location[$attr])) {

            // GET DEFAULT FIELD
            $value = $location[$attr];

        } else {

            // GET CUSTOM FIELD
            foreach ($custom_field_ids as $custom_field_id => $custom_field) {

                if ((strtolower($custom_field['label']) == strtolower($attr)) && isset($location['custom_fields'][$custom_field_id])) {

                    $value = $location['custom_fields'][$custom_field_id];
                    break;

                }
            }
        }

        if (!$raw) {
            //change array to list
            if (is_array($value)) {
                $value = implode(', ', $value);
            }
        }

        return $value;
    }

    // Add a custom header to the location single page
    public function default_location_header($featured_image_html, $post_id)
    {
        if (is_singular('cbn-location') && in_the_loop() && is_main_query()) {

            $location = get_post_meta($post_id, '_cbn_location_key', true);

            if (isset($location['video']) && $location['video'] != '') {
                $featured_image_html = '<div class="Compass-single-default-template-media has-video">' . apply_filters('the_content', esc_attr($location['video'])) . '</div>';
            } else {
                $featured_image_html = '<div class="Compass-single-default-template-media">' . $featured_image_html . '</div>';
            }

        }
        return $featured_image_html;
    }

    // Add custom content to the location single page
    public function default_location_content($content): string
    {
        // Check if we're inside the main loop in a single Post of type 'custom_post_type'.
        if (is_singular('cbn-location') && in_the_loop() && is_main_query()) {
            // Check if the content is empty
            if (empty(trim($content))) {
                // Custom content to display if the original content is empty
                return '
                <!-- wp:group {"className":"Compass-single-default-template","layout":{"type":"default"}} -->
                <div class="wp-block-group Compass-single-default-template">
                
                    <!-- wp:columns -->
                    <div class="wp-block-columns">
                    
                        <!-- wp:column {"width":"66.66%"} -->
                        <div class="wp-block-column" style="flex-basis:66.66%">

                            <!-- wp:shortcode -->
                            [Compass-location value="image"]
                            <!-- /wp:shortcode -->

                            <!-- wp:shortcode -->
                            [Compass-location value="text"]
                            <!-- /wp:shortcode -->
                        
                        </div>

                        <!-- /wp:column -->

                        <!-- wp:column {"width":"33.33%"} -->
                        <div class="wp-block-column" style="flex-basis:33.33%">

                            <!-- wp:shortcode -->
                            [Compass-location value="map"]
                            <!-- /wp:shortcode -->

                            <!-- wp:shortcode -->
                            [Compass-location value="route"]
                            <!-- /wp:shortcode -->

                            <!-- wp:shortcode -->
                            [Compass-location value="type"]
                            <!-- /wp:shortcode -->

                        </div>
                        <!-- /wp:column -->
                    </div>
                    <!-- /wp:columns -->
                </div>
                <!-- /wp:group -->
                ';
            }
        }

        // Return the original content if it's not empty or if the conditions are not met
        return $content;
    }
}
