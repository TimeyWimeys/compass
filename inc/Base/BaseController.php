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

    public $cbn_incompatible_3rd_party_scripts = array(
        //"gsap", //Bug: Avada scrolltrigger overwrites L
        //"mappress-leaflet" //Bug: globally serves old leaflet.js library (overwrites L)
    );

    public function __construct()
    {
        $this->plugin_path = plugin_dir_path(dirname(__FILE__, 2));
        $this->plugin_url = plugin_dir_url(dirname(__FILE__, 2));
        $this->plugin_version = get_file_data(dirname(__FILE__, 3) . '/Compass.php', array('Version' => 'Version'))['Version'];
        $this->plugin = plugin_basename(dirname(__FILE__, 3)) . '/Compass.php';

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
    public function render_block_map()
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
    public function render_block_gallery__premium_only()
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
    public function render_block_location__premium_only($block_attributes)
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
    public function render_block_list__premium_only()
    {
        wp_enqueue_style('cbn_frontend_css', $this->plugin_url . 'assets/frontend.css', array(), $this->plugin_version);

        // Enqueue carousel script
        wp_enqueue_script('cbn_frontend_carousel_js', $this->plugin_url . 'src/js/frontend-carousel.js', array(), $this->plugin_version, true);

        ob_start();
        require cbn_get_template('block-locations-list.php');
        return ob_get_clean();
    }
}
