<?php

/**
 * @package CompassPlugin
 */

namespace CompassPlugin\Base;

class BaseController
{
    public $plugin_path;
    public $plugin_url;
    public $plugin_version;
    public $plugin;
    public $post_status;
    public $cbn_title_label_default;
    public $cbn_map_label_default;
    public $cbn_address_label_default;
    public $cbn_description_label_default;
    public $cbn_upload_media_label_default;
    public $cbn_marker_types_label_default;
    public $cbn_searchmarkers_label_default;
    public $cbn_searchmarkers_zoom_default;
    public $cbn_searchaddress_label_default;
    public $cbn_user_notification_label_default;

    public $map_styles = array(
        //"Stamen.TonerLite" => "TonerLite (Stamen)",
        //"Stadia.StamenTonerLite" => "Stadia TonerLite",
        "Esri.WorldStreetMap" => "Esri WorldStreetMap",
        "OpenStreetMap.Mapnik" => "OpenStreetMap",
        "OpenStreetMap.DE" => "OpenStreetMap (Germany)",
        "CartoDB.DarkMatter" => "CartoDB DarkMatter",
        "CartoDB.Positron" => "CartoDB Positron",
        "Esri.WorldImagery" => "Esri WorldImagery",
    );

    public $custom_map_styles = array(
        "Custom1" => "Light with big labels",
        "Custom2" => "Purple Glow with big labels",
        "Custom3" => "Blue with big labels",
    );

    public $commercial_map_styles = array(
        "MapBox.streets" => "MapBox Streets",
        "MapBox.outdoors" => "MapBox Outdoors",
        "MapBox.light" => "MapBox Light",
        "MapBox.dark" => "MapBox Dark",
        "MapBox.satellite" => "MapBox Satellite",
        "MapBox.satellite-streets" => "MapBox Satellite Streets",
    );

    public $marker_icons = array(
        "default", "custom1", "custom2", "custom3", "custom4", "custom5", "custom6", "custom7", "custom8", "custom9", "custom10"
    );

    public $cbn_map_sizes = array(
        "default" => "Content width",
        "fullwidth" => "Full width"
    );

    public $pro_marker_icons = array(
        "user1"
    );

    public $cbn_ui_color_default = '#e02aaf';

    public $cbn_custom_field_fieldtypes = array(
        "text" => "Text"
    );

    public $pro_cbn_custom_field_fieldtypes = array(
        "link" => "Link",
        "email" => "Email",
        "checkbox" => "Checkbox",
        "radio" => "Radio",
        "select" => "Select",
        "html" => "HTML"
    );

    public $cbn_title_required_default = true;

    public $cbn_geosearch_provider = array(
        "osm" => "Open Street Map"
    );

    public $pro_cbn_geosearch_provider = array(
        "geoapify" => "Geoapify",
        "here" => "Here",
        "mapbox" => "MapBox"
    );

    public $cbn_searchbar_types = array(
        "address" => "Search for Address (Geosearch)",
        "markers" => "Search for Location Marker",
    );

    public $pro_cbn_searchbar_types = array(
        "live_filter" => "Live Filter Markers"
    );

    public $cbn_regions_layout_styles = array(
        "layout-1" => "Top",
        "layout-2" => "Sidebar"
    );

    public $cbn_incompatible_3rd_party_scripts = array(
        //"gsap", //Bug: Avada scrolltrigger overwrites L
        //"mappress-leaflet" //Bug: globally serves old leaflet.js library (overwrites L)
    );

    public function __construct()
    {
        $this->plugin_path = plugin_dir_path(dirname(dirname(__FILE__)));
        $this->plugin_url = plugin_dir_url(dirname(dirname(__FILE__)));
        $this->plugin_version = get_file_data(dirname(dirname(dirname(__FILE__))) . '/Compass.php', array('Version' => 'Version'))['Version'];
        $this->plugin = plugin_basename(dirname(dirname(dirname(__FILE__)))) . '/Compass.php';

        //Default labels
        $this->cbn_title_label_default = __('Title', 'Compass');
        $this->cbn_map_label_default = __('Click on the map to set a marker', 'Compass');
        $this->cbn_description_label_default = __('Description', 'Compass');
        $this->cbn_upload_media_label_default = __('Upload media', 'Compass');
        $this->cbn_address_label_default = __('Subtitle', 'Compass');
        $this->cbn_marker_types_label_default = __('Type', 'Compass');
        $this->cbn_searchaddress_label_default = __('Search for address', 'Compass');
        $this->cbn_searchmarkers_label_default = __('Find marker', 'Compass');
        $this->cbn_searchmarkers_zoom_default = 8;
        $this->cbn_user_notification_label_default = __('Notify me when it is published', 'Compass');
    }

    public function cbn_init()
    {
        $this->post_status = 'pending';




        if(get_option('cbn_enable_user_restriction')):
            if(is_user_logged_in()):

                add_action('wp_ajax_nopriv_cbn_add_location_from_frontend', array($this, 'ajax_add_location_from_frontend'));
                add_action('wp_ajax_cbn_add_location_from_frontend', array($this, 'ajax_add_location_from_frontend'));

            endif;
        else:
            // Default: Allow Frontend Adding for everyone
            add_action('wp_ajax_nopriv_cbn_add_location_from_frontend', array($this, 'ajax_add_location_from_frontend'));
            add_action('wp_ajax_cbn_add_location_from_frontend', array($this, 'ajax_add_location_from_frontend'));

        endif;

        // Auto-Publish for registered users
        if(get_option('cbn_enable_auto_publish') && current_user_can('edit_cbn-locations')):
            $this->post_status = 'publish';
        endif;

        // Auto-Publish for unregistered users (USE WITH CAUTION!)
        if(get_option('cbn_enable_auto_publish_for_everyone')):
            $this->post_status = 'publish';
        endif;
    }

    /**
     * Render all necessary base scripts for the map
     */
    public function include_map_scripts()
    {
        // Unregister incompatible 3rd party scripts
        $this->remove_incompatible_3rd_party_scripts();

        // enqueue Leaflet css
        wp_enqueue_style('cbn_leaflet_css', $this->plugin_url . 'src/leaflet/leaflet.css', array(), $this->plugin_version);
        wp_enqueue_style('cbn_leaflet_gesture_css', $this->plugin_url . 'src/leaflet/leaflet-gesture-handling.min.css', array(), $this->plugin_version);
        wp_enqueue_style('cbn_leaflet_markercluster_css', $this->plugin_url . 'src/leaflet/leaflet-markercluster.css', array(), $this->plugin_version);
        wp_enqueue_style('cbn_leaflet_markercluster_default_css', $this->plugin_url . 'src/leaflet/leaflet-markercluster.default.css', array(), $this->plugin_version);
        wp_enqueue_style('cbn_leaflet_geosearch_css', $this->plugin_url . 'src/leaflet/geosearch.css', array(), $this->plugin_version);
        wp_enqueue_style('cbn_leaflet_fullscreen_css', $this->plugin_url . 'src/leaflet/control.fullscreen.css', array(), $this->plugin_version);
        wp_enqueue_style('cbn_leaflet_locate_css', $this->plugin_url . 'src/leaflet/leaflet-locate.min.css', array(), $this->plugin_version);
        wp_enqueue_style('cbn_leaflet_search_css', $this->plugin_url . 'src/leaflet/leaflet-search.css', array(), $this->plugin_version);
        wp_enqueue_style('cbn_leaflet_responsivepopup_css', $this->plugin_url . 'src/leaflet/leaflet-responsive-popup.css', array(), $this->plugin_version);

        // Add map loader script first (before any other scripts)
        wp_enqueue_script('cbn_map_loader_js', $this->plugin_url . 'src/js/frontend-map-loader.js', array(), $this->plugin_version, true);

        // enqueue Leaflet javascripts
        wp_enqueue_script('cbn_leaflet_polyfill_unfetch_js', $this->plugin_url . 'src/js/polyfills/unfetch.js', array(), $this->plugin_version, true);
        wp_enqueue_script('cbn_leaflet_js', $this->plugin_url . 'src/leaflet/leaflet.js', array('cbn_leaflet_polyfill_unfetch_js'), $this->plugin_version, true);
        wp_enqueue_script('cbn_leaflet_providers_js', $this->plugin_url . 'src/leaflet/leaflet-providers.js', array('cbn_leaflet_js'), $this->plugin_version, true);
        wp_enqueue_script('cbn_leaflet_markercluster_js', $this->plugin_url . 'src/leaflet/leaflet-markercluster.js', array('cbn_leaflet_js'), $this->plugin_version, true);
        wp_enqueue_script('cbn_leaflet_subgroups_js', $this->plugin_url . 'src/leaflet/leaflet.featuregroup.subgroup.js', array('cbn_leaflet_js', 'cbn_leaflet_markercluster_js'), $this->plugin_version, true);
        wp_enqueue_script('cbn_leaflet_geosearch_js', $this->plugin_url . 'src/leaflet/geosearch.js', array('cbn_leaflet_js'), $this->plugin_version, true);
        wp_enqueue_script('cbn_leaflet_locate_js', $this->plugin_url . 'src/leaflet/leaflet-locate.min.js', array('cbn_leaflet_js'), $this->plugin_version, true);
        wp_enqueue_script('cbn_leaflet_fullscreen_js', $this->plugin_url . 'src/leaflet/control.fullscreen.js', array('cbn_leaflet_js'), $this->plugin_version, true);
        wp_enqueue_script('cbn_leaflet_search_js', $this->plugin_url . 'src/leaflet/leaflet-search.js', array('cbn_leaflet_js'), $this->plugin_version, true);
        wp_enqueue_script('cbn_leaflet_gesture_js', $this->plugin_url . 'src/leaflet/leaflet-gesture-handling.min.js', array('cbn_leaflet_js'), $this->plugin_version, true);
        wp_enqueue_script('cbn_leaflet_responsivepopup_js', $this->plugin_url . 'src/leaflet/leaflet-responsive-popup.js', array('cbn_leaflet_js'), $this->plugin_version, true);

        // Capture the fully extended L object after all Leaflet add-ons are loaded
        wp_enqueue_script('cbn_global_leaflet_js', $this->plugin_url . 'src/leaflet/cbn-global-leaflet.js', array('cbn_leaflet_js'), $this->plugin_version, true);

        // enqueue WordPress i18n (for translations inside JS)
        wp_enqueue_script('wp-i18n');
    }

    /**
     * Unregister incompatible 3rd party scripts
     */
    public function remove_incompatible_3rd_party_scripts()
    {
        foreach($this->cbn_incompatible_3rd_party_scripts as $item) {
            wp_deregister_script($item);
        }
    }

    /**
     * Render the map
     */
    public function render_block_map($block_attributes, $content)
    {
        wp_enqueue_style('cbn_frontend_css', $this->plugin_url . 'assets/frontend.css', array(), $this->plugin_version);

        // load map base scripts
        $this->include_map_scripts();

        wp_enqueue_script('cbn_frontend_block_map_js', $this->plugin_url . 'src/js/frontend-block-map.js', array('cbn_leaflet_providers_js', 'cbn_leaflet_markercluster_js', 'cbn_leaflet_subgroups_js', 'cbn_leaflet_geosearch_js', 'cbn_leaflet_locate_js', 'cbn_leaflet_fullscreen_js', 'cbn_leaflet_search_js', 'cbn_leaflet_gesture_js', 'wp-i18n', 'cbn_global_leaflet_js'), $this->plugin_version, true);

        // add custom js to frontend-block-map.js
        wp_localize_script('cbn_frontend_block_map_js', 'custom_js', array(
            'snippet' => get_option('cbn_custom_js'),
        ));

        wp_enqueue_script('cbn_frontend_ajax_js', $this->plugin_url . 'src/js/frontend-ajax.js', array('jquery', 'cbn_frontend_block_map_js'), $this->plugin_version, true);
        wp_localize_script('cbn_frontend_ajax_js', 'cbn_ajax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
        ));

        // Enqueue carousel script
        wp_enqueue_script('cbn_frontend_carousel_js', $this->plugin_url . 'src/js/frontend-carousel.js', array(), $this->plugin_version, true);

        ob_start();
        require cbn_get_template('block-map.php');
        return ob_get_clean();
    }

    /**
     * Add location from frontend (AJAX)
     */
    public function ajax_add_location_from_frontend()
    {
        if (!empty($_POST['action']) && $_POST['action'] == 'cbn_add_location_from_frontend') {

            // Initialize error handling
            $error = new \WP_Error();

            // Dont save without nonce
            if (!isset($_POST['cbn_location_nonce'])) {
                $error->add('000', 'Security error (no nonce povided)');
                wp_send_json_error($error);
                die();
            }

            // Dont save if nonce is incorrect
            $nonce = $_POST['cbn_location_nonce'];
            if (!wp_verify_nonce($nonce, 'cbn_location')) {
                $error->add('000', 'Security error (incorrect nonce)');
                wp_send_json_error($error);
                die();
            }

            $data['cbn_location_title'] = (isset($_POST['cbn_location_title']) && $_POST['cbn_location_title'] != '') ? sanitize_text_field(wp_strip_all_tags($_POST['cbn_location_title'])) : time();
            $data['cbn_location_lat'] = sanitize_text_field(wp_strip_all_tags($_POST['cbn_location_lat']));
            $data['cbn_location_lng'] = sanitize_text_field(wp_strip_all_tags($_POST['cbn_location_lng']));
            $data['cbn_location_address'] = isset($_POST['cbn_location_address']) ? sanitize_text_field(wp_strip_all_tags($_POST['cbn_location_address'])) : '';
            $data['cbn_location_text'] = isset($_POST['cbn_location_text']) ? wp_kses_post($_POST['cbn_location_text']) : '';
            $data['cbn_location_notification'] = isset($_POST['cbn_location_notification']) ? $_POST['cbn_location_notification'] : '';
            $data['cbn_location_author_name'] = isset($_POST['cbn_location_notification']) ? sanitize_text_field(wp_strip_all_tags($_POST['cbn_location_author_name'])) : '';
            $data['cbn_location_author_email'] = isset($_POST['cbn_location_notification']) ? sanitize_email(wp_strip_all_tags($_POST['cbn_location_author_email'])) : '';
            $data['cbn_location_video'] = isset($_POST['cbn_location_video']) ? sanitize_url(wp_strip_all_tags($_POST['cbn_location_video'])) : '';

            if(isset($_POST['cbn_marker_icon'])) {
                $data['cbn_marker_icon'] = array();

                foreach($_POST['cbn_marker_icon'] as $index => $val) {
                    $data['cbn_marker_icon'][$index] = (int)sanitize_text_field(wp_strip_all_tags($val));
                }
            } else {
                $data['cbn_marker_icon'] = '';
            }

            if(isset($_POST['cbn_location_custom_fields']) && is_array($_POST['cbn_location_custom_fields'])) {
                $data['cbn_location_custom_fields'] = array();

                foreach($_POST['cbn_location_custom_fields'] as $index => $val) {
                    if(is_array($val)) {
                        //multiple values
                        $arr_vals = array();
                        foreach($val as $el) {
                            $arr_vals[] = sanitize_text_field(wp_strip_all_tags($el));
                        }
                        $data['cbn_location_custom_fields'][$index] = $arr_vals;
                    } else {
                        //single value
                        $data['cbn_location_custom_fields'][$index] = sanitize_text_field(wp_strip_all_tags($val));
                    }
                }
            }

            if (isset($_POST['cbn_post_id']) && $_POST['cbn_post_id'] != '') {

                $data['cbn_post_id'] = intval($_POST['cbn_post_id']);

                // Does the post exist?
                if (get_post_status($data['cbn_post_id']) === false) {
                    $error->add('008', 'The provided Post ID does not exist.');
                }

                // Is the current user allowed to edit this post?
                $has_general_permission = current_user_can('edit_cbn-locations');
                $is_author = (get_current_user_id() == get_post_field('post_author', $data['cbn_post_id']));
                $can_edit_specific_post = current_user_can('edit_post', $data['cbn_post_id']);
                $allow_edit = ($has_general_permission && ($is_author || $can_edit_specific_post)) ? true : false;
                if (!$allow_edit) {
                    $error->add('009', 'You are not allowed to edit this location.');
                }

                // Should the location be deleted?
                if(isset($_POST['cbn_delete_location']) && $_POST['cbn_delete_location'] == 'true') {
                    $data['cbn_delete_location'] = $_POST['cbn_delete_location'];
                }

            }


            if(!$data['cbn_location_title']) {
                $error->add('001', 'Missing or incorrect Title value.');
            }

            if(!$data['cbn_location_lat'] || !$data['cbn_location_lng']) {
                $error->add('002', 'Missing or incorrect location. Click on the map to set a marker.');
            }

            if(isset($_FILES['cbn_location_audio']['name']) && $_FILES['cbn_location_audio']['name'] != '') {
                $valid_extensions = array('mp3', 'wav', 'mp4', 'm4a'); // valid extensions

                $img = sanitize_file_name($_FILES['cbn_location_audio']['name']);
                $tmp = sanitize_text_field($_FILES['cbn_location_audio']['tmp_name']);

                // get uploaded file's extension
                $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
                //error_log(print_r($_FILES, true));

                // check internal upload handling
                if($tmp == '') {
                    $error->add('003', 'Something went wrong with file upload. Use a valid audio file.');
                }

                // check valid format
                if(in_array($ext, $valid_extensions)) {
                    $data['cbn_location_audio_src'] = $tmp;
                    $data['cbn_location_audio_ext'] = $ext;
                } else {
                    $error->add('004', 'Invalid audio file extension. Please use .mp3, .wav, .mp4 or .m4a.');
                }

                // check maximum filesize
                // default 10MB
                $cbn_max_audio_filesize = get_option('cbn_max_audio_filesize') ? get_option('cbn_max_audio_filesize') : 10;
                $max_filesize = (int)$cbn_max_audio_filesize * 1048576;

                if ($_FILES['cbn_location_audio']['size'] > $max_filesize) {
                    $error->add('005', 'The audio file exceeds maximum size of ' . $cbn_max_audio_filesize . 'MB.');
                }
            }

            if(isset($data['cbn_location_notification']) && $data['cbn_location_notification'] != '') {
                if(!$data['cbn_location_author_name']) {
                    $error->add('006', 'Missing author name.');
                }

                if(!$data['cbn_location_author_email']) {
                    $error->add('007', 'Missing author email.');
                }
            }

            if($error->has_errors()) {

                wp_send_json_error($error);

            } else {

                $new_post = array(
                    'post_title' => $data['cbn_location_title'],
                    'post_type' => 'cbn-location',
                    'post_status' => $this->post_status,
                    'comment_status' => 'closed'
                );

                if(get_option('cbn_enable_single_page')) {
                    $new_post['comment_status'] = 'open';
                };



                // DELETE, UPDATE or INSERT the location
                if (isset($data['cbn_delete_location']) && $data['cbn_delete_location'] == 'true') {

                    // DELETE (Move to trash)
                    wp_trash_post($data['cbn_post_id']);



                    // Trigger webhook for DELETE event
                    $this->trigger_webhook($data['cbn_post_id'], 'deleted');

                    wp_send_json_success(array(
                        'message' => 'Ok, the location has been removed.',
                        'post_id' => $data['cbn_post_id']
                    ));

                } else {

                    // INSERT or UPDATE the location based on 'cbn_post_id'
                    $is_update = isset($data['cbn_post_id']) && get_post_status($data['cbn_post_id']) !== false;
                    $post_id = $is_update
                        ? wp_update_post(array_merge($new_post, ['ID' => $data['cbn_post_id']]))
                        : wp_insert_post($new_post);

                    if($post_id) {
                        // Handle multiple images
                        $final_image_urls = array();
                        $image_order = isset($_POST['image_order']) ? json_decode(stripslashes($_POST['image_order']), true) : array();
                        $new_image_mapping = array(); // Store mapping of original filename to new URL

                        // First, handle new uploaded images to create the mapping
                        if(isset($_FILES['cbn_location_images'])) {
                            $valid_extensions = array('jpeg', 'jpg', 'png', 'webp');
                            $cbn_max_image_filesize = get_option('cbn_max_image_filesize') ? get_option('cbn_max_image_filesize') : 10;
                            $max_filesize = (int)$cbn_max_image_filesize * 1048576;

                            if(is_array($_FILES['cbn_location_images']['name'])) {
                                foreach($_FILES['cbn_location_images']['name'] as $key => $name) {
                                    if(empty($name)) {
                                        continue;
                                    }

                                    $tmp = $_FILES['cbn_location_images']['tmp_name'][$key];
                                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                                    $size = $_FILES['cbn_location_images']['size'][$key];

                                    // Validate file
                                    if($tmp == '' || !is_uploaded_file($tmp)) {
                                        error_log("File $key: Invalid upload");
                                        continue;
                                    }

                                    if(!in_array($ext, $valid_extensions)) {
                                        error_log("File $key: Invalid extension");
                                        continue;
                                    }

                                    if($size > $max_filesize) {
                                        error_log("File $key: File too large");
                                        $error->add('005', sprintf(__('Image "%s" is too large. Maximum file size is %d MB.', 'Compass'), $name, $cbn_max_image_filesize));
                                        continue;
                                    }

                                    // Process the upload
                                    $uploads_dir = trailingslashit(wp_upload_dir()['basedir']) . 'cbn-useruploads/';
                                    wp_mkdir_p($uploads_dir);

                                    $unique_filename = uniqid() . '.' . $ext;
                                    $file_fullpath = $uploads_dir . $unique_filename;

                                    if(move_uploaded_file($tmp, $file_fullpath)) {
                                        // Store relative path in the mapping with original filename as key
                                        $relative_url = '/wp-content/uploads/cbn-useruploads/' . $unique_filename;
                                        $new_image_mapping[$name] = $relative_url;
                                    }
                                }
                            }
                        }

                        // Now build the final array based on image_order
                        if(!empty($image_order)) {
                            foreach($image_order as $item) {
                                list($type, $identifier) = explode(':', $item);

                                if($type === 'existing') {
                                    // Convert absolute URL to relative if needed
                                    $relative_url = str_replace(site_url(), '', $identifier);
                                    $final_image_urls[] = $relative_url;
                                } else {
                                    // For new images, get URL from our mapping
                                    if(isset($new_image_mapping[$identifier])) {
                                        $final_image_urls[] = $new_image_mapping[$identifier];
                                    }
                                }
                            }
                        }

                        // Save the final list of images
                        if(!empty($final_image_urls)) {
                            update_post_meta($post_id, '_cbn_location_image', implode('|', $final_image_urls));
                        }

                        // update meta
                        $lat_validated = floatval(str_replace(',', '.', $data['cbn_location_lat']));
                        if(!$lat_validated) {
                            $lat_validated = '';
                        }

                        $lng_validated = floatval(str_replace(',', '.', $data['cbn_location_lng']));
                        if(!$lng_validated) {
                            $lng_validated = '';
                        }

                        $data_meta = array(
                            'address' => $data['cbn_location_address'],
                            'lat' => $lat_validated,
                            'lng' => $lng_validated,
                            'text' => $data['cbn_location_text'],
                            'video' => $data['cbn_location_video'],
                        );

                        if(isset($data['cbn_location_notification']) && isset($data['cbn_location_author_name']) && isset($data['cbn_location_author_email'])) {
                            $data_meta['notification'] = $data['cbn_location_notification'];
                            $data_meta['author_name'] = $data['cbn_location_author_name'];
                            $data_meta['author_email'] = $data['cbn_location_author_email'];
                        }

                        if(isset($data['cbn_location_custom_fields']) && is_array($data['cbn_location_custom_fields'])) {
                            $data_meta['custom_fields'] = $data['cbn_location_custom_fields'];
                        }

                        update_post_meta($post_id, '_cbn_location_key', $data_meta);

                        // IMAGE

                        // remove the existing image
                        if (isset($_POST['cbn_remove_existing_image']) && $_POST['cbn_remove_existing_image'] == '1') {
                            delete_post_meta($post_id, '_cbn_location_image');
                        }

                        // AUDIO

                        // remove the existing audio
                        if (isset($_POST['cbn_remove_existing_audio']) && $_POST['cbn_remove_existing_audio'] == '1') {
                            delete_post_meta($post_id, '_cbn_location_audio');
                        }

                        if(isset($data['cbn_location_audio_src']) && isset($data['cbn_location_audio_ext'])) {
                            //set uploads dir
                            $uploads_dir = trailingslashit(wp_upload_dir()['basedir']) . 'cbn-useruploads/';
                            wp_mkdir_p($uploads_dir);

                            $file_name = $post_id.'.'.$data['cbn_location_audio_ext'];
                            $file_fullpath = $uploads_dir.$file_name;

                            // save file to wp-content/uploads/cbn-useruploads/
                            if(move_uploaded_file($data['cbn_location_audio_src'], $file_fullpath)) {
                                $cbn_location_audio_url = wp_upload_dir()['baseurl'] . '/cbn-useruploads/' . $file_name;
                                $data_audio = esc_url_raw($cbn_location_audio_url);
                                update_post_meta($post_id, '_cbn_location_audio', $data_audio);
                            };
                        }




                        // PRO: update cbn-type taxonomy
                        if(get_option('cbn_enable_marker_types')) {
                            if($data['cbn_marker_icon']) {
                                $cbn_marker_types = is_array($data['cbn_marker_icon']) ? $data['cbn_marker_icon'] : [(int)$data['cbn_marker_icon']];
                                wp_set_object_terms(
                                    $post_id,
                                    $cbn_marker_types,
                                    'cbn-type'
                                );
                            }
                        }

                        // PRO: notify author instantly if auto-publish is active
                        if(get_option('cbn_enable_auto_publish')) {
                            $this->notify_author__premium_only('publish', 'new', get_post($post_id));
                        }

                        // Trigger webhook for INSERT or UPDATE event
                        $event_type = $is_update ? 'updated' : 'added';
                        $this->trigger_webhook($post_id, $event_type, $data_meta);
                    }

                    wp_send_json_success(array(
                        'message' => 'Ok, the location is now pending review.',
                        'post_id' => $post_id
                    ));
                }
            }
        }

        die(); //necessary for correct ajax return in WordPress plugins
    }

    /**
     * PRO: Trigger webhook notification
     */
    public function trigger_webhook($post_id, $event_type, $data_meta = null)
    {
        // Check if webhook notifications are enabled
        if (!get_option('cbn_enable_webhook_notification')) {
            error_log("Webhook notifications are disabled. Skipping trigger for Post ID: $post_id");
            return;
        }

        // Get the webhook URL from settings
        $webhook_url = get_option('cbn_webhook_notification_url');
        if (!$webhook_url) {
            error_log("No webhook URL configured for Post ID: $post_id");
            return;
        }

        // Prepare webhook payload
        $webhook_data = array(
            'title' => get_the_title($post_id),
            'content' => get_post_field('post_content', $post_id),
            'website_url' => get_site_url(),
            'website_name' => get_bloginfo('name'),
            'edit_location_url' => get_edit_post_link($post_id),
            'taxonomy_terms' => wp_get_post_terms($post_id, 'cbn-type', array('fields' => 'names')),
            'meta_data' => $data_meta ?? get_post_meta($post_id, '_cbn_location_key', true),
            'event' => $event_type,
            'timestamp' => current_time('mysql'),
        );

        // Send webhook
        $response = wp_remote_post($webhook_url, array(
            'body' => json_encode($webhook_data),
            'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        ));

        // Handle response
        if (is_wp_error($response)) {
            error_log('Webhook error: ' . $response->get_error_message());
        } else {
            error_log("Webhook successfully triggered for Post ID: $post_id - Event: $event_type");
        }
    }


    public function correctImageOrientation($filename, $img)
    {
        if(!function_exists('exif_read_data')) {
            //exit, if EXIF PHP Library is not available
            return $img;
        }

        $exif = @exif_read_data($filename);
        if ($exif && isset($exif['Orientation'])) {
            $orientation = $exif['Orientation'];
            if ($orientation != 1) {
                $deg = 0;
                switch ($orientation) {
                    case 3:
                        $deg = 180;
                        break;
                    case 6:
                        $deg = 270;
                        break;
                    case 8:
                        $deg = 90;
                        break;
                }
                if ($deg) {
                    $img = imagerotate($img, $deg, 0);
                }
            }
        }

        return $img;
    }

    /**
     * PRO: Notify author on publish
     */
    public function notify_author__premium_only($new_status, $old_status, $post)
    {
        if('publish' === $new_status && 'publish' !== $old_status && $post->post_type === 'cbn-location') {
            $data = get_post_meta($post->ID, '_cbn_location_key', true);

            //exit if meta data is not available yet (post is still about to be created from the frontend)
            if(!$data) {
                return;
            }

            //exit if notification has been send before
            $notified = get_post_meta($post->ID, '_cbn_location_notified', true);
            if($notified) {
                return;
            }

            if(get_option('cbn_enable_user_notification') && isset($data['notification']) && $data['notification'] == 'on' && isset($data['author_name']) && isset($data['author_email'])) {
                //get subject
                $subject = get_option('cbn_user_notification_subject');
                $subject = str_replace('%name%', $data['author_name'], $subject);
                $subject = str_replace('%website_url%', get_site_url(), $subject);
                $subject = str_replace('%website_name%', get_bloginfo('name'), $subject);

                //get message
                $message = get_option('cbn_user_notification_message');
                $message = str_replace('%name%', $data['author_name'], $message);
                $message = str_replace('%website_url%', get_site_url(), $message);
                $message = str_replace('%website_name%', get_bloginfo('name'), $message);

                //mailto author
                $send_notification = wp_mail($data['author_email'], $subject, $message);

                if($send_notification) {
                    //mark as notified (email sending has been successfull)
                    update_post_meta($post->ID, '_cbn_location_notified', time());
                }
            }
        }
    }

    /**
     * PRO: Notify admin on new post
     */
    public function notify_admin__premium_only($post_id, $post, $update)
    {
        if($post->post_type == 'cbn-location' && !$update && empty(get_post_meta($post_id, '_cbn_admin_notified'))) {

            if(get_option('cbn_enable_admin_notification') && get_option('cbn_admin_notification_email')) {
                // Get user information
                $user_name = '';
                $user_email = '';

                if ($post->post_author) {
                    // Get info from WordPress user
                    $user = get_userdata($post->post_author);
                    if ($user) {
                        $user_name = $user->display_name;
                        $user_email = $user->user_email;
                    }
                }

                // If no WordPress user, try to get from location meta
                if (empty($user_name) || empty($user_email)) {
                    $location_meta = get_post_meta($post_id, '_cbn_location_key', true);
                    if (!empty($location_meta['author_name'])) {
                        $user_name = $location_meta['author_name'];
                    }
                    if (!empty($location_meta['author_email'])) {
                        $user_email = $location_meta['author_email'];
                    }
                }

                //get subject
                $subject = get_option('cbn_admin_notification_subject');
                $subject = str_replace('%title%', get_the_title($post_id), $subject);
                $subject = str_replace('%website_url%', get_site_url(), $subject);
                $subject = str_replace('%website_name%', get_bloginfo('name'), $subject);
                $subject = str_replace('%edit_location_url%', get_edit_post_link($post_id), $subject);
                $subject = str_replace('%user_name%', $user_name, $subject);
                $subject = str_replace('%user_email%', $user_email, $subject);

                //get message
                $message = get_option('cbn_admin_notification_message');
                $message = str_replace('%title%', get_the_title($post_id), $message);
                $message = str_replace('%website_url%', get_site_url(), $message);
                $message = str_replace('%website_name%', get_bloginfo('name'), $message);
                $message = str_replace('%edit_location_url%', get_edit_post_link($post_id), $message);
                $message = str_replace('%user_name%', $user_name, $message);
                $message = str_replace('%user_email%', $user_email, $message);

                //mailto admin
                $send_notification = wp_mail(get_option('cbn_admin_notification_email'), $subject, $message);

                if($send_notification) {
                    //mark as notified (email sending has been successfull)
                    update_post_meta($post_id, '_cbn_admin_notified', time());
                }
            }
        }
    }


    /**
     * PRO: Add user location within registration
     */
    public function render_block_add_user_location__premium_only()
    {
        wp_enqueue_style('cbn_frontend_css', $this->plugin_url . 'assets/frontend.css', array(), $this->plugin_version);

        // load map base scripts
        $this->include_map_scripts();

        require_once cbn_get_template('block-add-user-location.php');

        wp_enqueue_script('cbn_frontend_block_map_js', $this->plugin_url . 'src/js/frontend-block-add-user-location.js', array('cbn_leaflet_providers_js', 'cbn_leaflet_markercluster_js', 'cbn_leaflet_subgroups_js', 'cbn_leaflet_geosearch_js', 'cbn_leaflet_locate_js', 'cbn_leaflet_fullscreen_js', 'cbn_leaflet_search_js', 'cbn_leaflet_gesture_js', 'wp-i18n', 'cbn_global_leaflet_js'), $this->plugin_version, true);
    }

    public function add_user_location__premium_only($userid)
    {
        if ($userid && !empty($_POST['cbn_location_lat']) && !empty($_POST['cbn_location_lng'])) {
            $data['cbn_location_lat'] = sanitize_text_field(wp_strip_all_tags($_POST['cbn_location_lat']));
            $data['cbn_location_lng'] = sanitize_text_field(wp_strip_all_tags($_POST['cbn_location_lng']));

            $newuser = get_userdata($userid);

            $new_post = array(
                'post_title' => $newuser->user_login,
                'post_type' => 'cbn-location',
                'post_author' => $userid,
                'post_status' => $this->post_status,
                'comment_status' => 'closed'
            );

            $post_id = wp_insert_post($new_post);

            if($post_id) {
                // update meta

                // Validation
                $lat_validated = floatval(str_replace(',', '.', $data['cbn_location_lat']));
                if(!$lat_validated) {
                    $lat_validated = '';
                }

                $lng_validated = floatval(str_replace(',', '.', $data['cbn_location_lng']));
                if(!$lng_validated) {
                    $lng_validated = '';
                }

                $data_meta = array(
                    'address' => '',
                    'lat' => $lat_validated,
                    'lng' => $lng_validated,
                    'text' => '',
                );

                update_post_meta($post_id, '_cbn_location_key', $data_meta);

            }
        }
    }

    /**
     * PRO: Render Image Gallery
     */
    public function render_block_gallery__premium_only($block_attributes, $content)
    {
        wp_enqueue_style('cbn_frontend_css', $this->plugin_url . 'assets/frontend.css', array(), $this->plugin_version);

        // Enqueue WordPress core scripts
        wp_enqueue_script('jquery');
        wp_enqueue_script('masonry');
        wp_enqueue_script('imagesloaded');

        // Enqueue our custom gallery script with dependencies
        wp_enqueue_script('cbn_gallery_js', $this->plugin_url . 'src/js/frontend-gallery.js', array('jquery', 'masonry', 'imagesloaded'), $this->plugin_version, true);

        ob_start();
        require cbn_get_template('block-image-gallery.php');
        return ob_get_clean();
    }

    /**
     * PRO: Render Location Value
     */
    public function render_block_location__premium_only($block_attributes, $content)
    {
        wp_enqueue_style('cbn_frontend_css', $this->plugin_url . 'assets/frontend.css', array(), $this->plugin_version);

        if(isset($block_attributes['value']) && $block_attributes['value'] == 'map') {

            // load map base scripts
            $this->include_map_scripts();

            wp_enqueue_script('cbn_frontend_block_location_js', $this->plugin_url . 'src/js/frontend-block-location.js', array('cbn_leaflet_providers_js', 'cbn_leaflet_markercluster_js', 'cbn_leaflet_subgroups_js', 'cbn_leaflet_geosearch_js', 'cbn_leaflet_locate_js', 'cbn_leaflet_fullscreen_js', 'cbn_leaflet_search_js', 'cbn_leaflet_gesture_js', 'wp-i18n', 'cbn_global_leaflet_js'), $this->plugin_version, true);
        }

        ob_start();
        require "$this->plugin_path/templates/block-location.php";
        return ob_get_clean();
    }

    /**
     * PRO: Render Locations List
     */
    public function render_block_list__premium_only($block_attributes, $content)
    {
        wp_enqueue_style('cbn_frontend_css', $this->plugin_url . 'assets/frontend.css', array(), $this->plugin_version);

        // Enqueue carousel script
        wp_enqueue_script('cbn_frontend_carousel_js', $this->plugin_url . 'src/js/frontend-carousel.js', array(), $this->plugin_version, true);

        ob_start();
        require cbn_get_template('block-locations-list.php');
        return ob_get_clean();
    }
}
