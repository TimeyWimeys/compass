<?php

/**
 * @package CompassPlugin
 */

namespace CompassPlugin\Pages;

require_once plugin_dir_path(__FILE__) . '../Base/BaseController.php';


use CompassPlugin\Base\BaseController;

class Settings extends BaseController
{
    public function register()
    {
        add_action('init', array($this, 'migrate_deprecated_settings'));
        add_action('admin_menu', array($this, 'add_admin_pages'));
        add_action('admin_init', array($this, 'add_plugin_settings'));
        add_action('admin_init', array($this, 'add_cbn_wizard'));
        add_action('admin_notices', array($this, 'show_getting_started_notice'));
        add_action('wp_ajax_cbn_dismiss_getting_started_notice', array($this, 'getting_started_dismiss_notice'));
        add_action('wp_ajax_cbn_csv_export', array($this, 'csv_export'));
        add_action('wp_ajax_cbn_csv_import', array($this, 'csv_import'));
        add_action('update_option', array($this, 'add_settings_updated_message'), 10, 3);
    }


    public function add_admin_pages()
    {
        //add_options_page('Compass', 'Compass', 'manage_options', 'Compass', array($this, 'admin_index'));
        add_options_page('Compass', 'Compass', 'manage_options', 'Compass-settings', array($this, 'admin_index'));
    }

    public function add_plugin_settings()
    {
        register_setting('Compass-settings-getting-started-notice', 'cbn_getting_started_notice_dismissed');
        register_setting('Compass-settings-group', 'cbn_map_style', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_tile_provider_mapbox_key', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_marker_icon', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_marker_user_icon', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_map_size', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_map_height', array('sanitize_callback' => array($this, 'validate_size')));
        register_setting('Compass-settings-group', 'cbn_map_height_mobile', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_start_lat', array('sanitize_callback' => array($this, 'validate_geocoordinate')));
        register_setting('Compass-settings-group', 'cbn_start_lng', array('sanitize_callback' => array($this, 'validate_geocoordinate')));
        register_setting('Compass-settings-group', 'cbn_start_zoom', array('sanitize_callback' => array($this, 'validate_zoom')));
        register_setting('Compass-settings-group', 'cbn_enable_fixed_map_bounds', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_enable_title', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_title_required', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_title_maxlength', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_title_label', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_map_label', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_hide_address', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_enable_address', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_geosearch_provider', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_geosearch_provider_geoapify_key', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_geosearch_provider_here_key', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_geosearch_provider_mapbox_key', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_enable_searchbar', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_searchbar_type', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_enable_searchaddress_button', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_searchaddress_label', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_enable_searchmarkers_button', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_searchmarkers_label', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_searchmarkers_zoom', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_enable_gmaps_link', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_address_label', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_enable_description', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_description_required', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_description_label', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_upload_media_label', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_enable_image', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_image_required', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_enable_audio', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_audio_required', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_enable_video', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_video_required', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_custom_fields', array('sanitize_callback' => array($this, 'validate_array')));
        register_setting('Compass-settings-group', 'cbn_enable_scrollwheel_zoom_map', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_enable_cluster', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_enable_fullscreen', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_enable_currentlocation', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_disable_cbn_attribution', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_max_image_filesize', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_max_audio_filesize', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_action_after_submit', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_thankyou_redirect', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_thankyou_headline', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_thankyou_text', array('sanitize_callback' => 'wp_kses_post'));
        register_setting('Compass-settings-group', 'cbn_plus_button_label', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_submit_button_label', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_form_headline', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_enable_user_notification', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_user_notification_label', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_user_notification_subject', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_enable_admin_notification', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_admin_notification_email', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_admin_notification_subject', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_enable_webhook_notification', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_webhook_notification_url', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_enable_user_restriction', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_enable_redirect_to_registration', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_enable_auto_publish', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_enable_auto_publish_for_everyone', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_enable_add_user_location', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_enable_marker_types', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_enable_empty_marker_type', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_enable_multiple_marker_types', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_collapse_filter', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_marker_types_label', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_ui_color', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_enable_add_location', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_enable_single_page', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_enable_location_date', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_location_date_type', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_enable_regions', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_regions_layout_style', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group', 'cbn_custom_js', array('sanitize_callback' => 'wp_kses_post'));
        register_setting('Compass-settings-group-wizard-1', 'cbn_wizard_usecase', array('sanitize_callback' => array($this, 'process_wizard_usecase')));
        register_setting('Compass-settings-group-wizard-1', 'cbn_wizard_usecase_done', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('Compass-settings-group-wizard-2', 'cbn_wizard_finish_done', array('sanitize_callback' => 'sanitize_text_field'));


        if (cbn_fs()->true()) {
            if (cbn_fs()->true()) {
                // Premium settings, because wp_kses_post can not be null in free version
                register_setting('Compass-settings-group', 'cbn_user_notification_message', array('sanitize_callback' => 'wp_kses_post'));
                register_setting('Compass-settings-group', 'cbn_admin_notification_message', array('sanitize_callback' => 'wp_kses_post'));
            }
        }
    }

    public function migrate_deprecated_settings()
    {
        // Variant 1: invert old settings

        $options = array(
            'cbn_disable_add_location' => 'cbn_enable_add_location',
            'cbn_disable_title' => 'cbn_enable_title',
            'cbn_disable_address' => 'cbn_enable_address',
            'cbn_disable_gmaps_link' => 'cbn_enable_gmaps_link',
            'cbn_disable_description' => 'cbn_enable_description',
            'cbn_disable_image' => 'cbn_enable_image',
            'cbn_disable_audio' => 'cbn_enable_audio',
            'cbn_disable_cluster' => 'cbn_enable_cluster',
            'cbn_disable_fullscreen' => 'cbn_enable_fullscreen',
            'cbn_disable_searchaddress' => 'cbn_enable_searchaddress_button',
        );

        foreach ($options as $old_option => $new_option) {
            $old_setting = get_option($old_option);

            // do nothing if old option doesnt exist
            if ($old_setting === false) {
                //error_log('Compass: Deprecated option ' . $old_option . ' does not exist. Nothing to do.');
                continue;
            }

            if (empty($old_setting)) {
                $new_setting = 'on';
            } else {
                $new_setting = '';
            }

            //update (or create) new
            update_option($new_option, $new_setting);
            error_log('Compass: Update new option ' . $new_option . ' from old option ' . $old_option . '. New Value: ' . $new_setting);

            //delete old
            delete_option($old_option);
            error_log('Compass: Deleting old option ' . $new_option . '.');
        }


        // Variant 2: rename settings (keep value)

        $options = array(
            'cbn_enable_searchaddress' => 'cbn_enable_searchbar',
        );

        foreach ($options as $old_option => $new_option) {
            $old_setting = get_option($old_option);

            // do nothing if old option doesnt exist
            if ($old_setting === false) {
                //error_log('Compass: Deprecated option ' . $old_option . ' does not exist. Nothing to do.');
                continue;
            }

            //update (or create) new
            update_option($new_option, $old_setting);
            error_log('Compass: Update new option ' . $new_option . ' from old option ' . $old_option . '. New Value: ' . $old_setting);

            //delete old
            delete_option($old_option);
            error_log('Compass: Deleting old option ' . $new_option . '.');
        }


        // Variant 3: change value of a setting
        if (get_option('cbn_map_style') == 'Stamen.TonerLite') {
            update_option('cbn_map_style', 'CartoDB.Positron');
        }

        if (get_option('cbn_map_style') == 'Stadia.StamenTonerLite') {
            update_option('cbn_map_style', 'CartoDB.Positron');
        }
    }

    public function add_cbn_wizard()
    {
        if ((get_option('cbn_enable_add_location') !== 'on' && get_option('cbn_enable_add_location') !== '') || (get_option('cbn_wizard_usecase_done') && !get_option('cbn_wizard_finish_done'))) {

            add_action('admin_body_class', function ($class) {
                $class .= ' cbn-settings-wizard';

                return $class;
            });
        }
    }

    public function admin_index()
    {
        require_once cbn_get_template('page-backend-settings.php');
    }

    public static function validate_geocoordinate($input)
    {
        // Validation
        $geocoordinate_validated = floatval(str_replace(',', '.', sanitize_text_field($input)));
        if (!$geocoordinate_validated && $geocoordinate_validated != '0') {
            $geocoordinate_validated = '';
        }

        return $geocoordinate_validated;
    }

    public static function validate_zoom($input)
    {
        // Validation
        $zoom_validated = floatval(str_replace(',', '.', sanitize_text_field($input)));
        if (!$zoom_validated) {
            $zoom_validated = '';
        }

        return $zoom_validated;
    }

    public static function validate_size($input)
    {
        // Add px if it's missing
        $size_validated = (is_numeric($input)) ? $input . 'px' : sanitize_text_field($input);

        return $size_validated;
    }

    public function validate_array($array)
    {

        // if not an array
        if (!is_array($array)) {
            return '';
        }

        foreach ($array as &$value) {

            if (!is_array($value)) {
                // sanitize if value is not an array
                $value = sanitize_text_field($value);
            } else {
                // go inside this function again
                $this->validate_array($value);
            }

        }

        return $array;

    }

    public static function show_getting_started_notice()
    {
        // return if already dismissed
        if (get_option('cbn_getting_started_notice_dismissed')) {
            return;
        }

        $screen = get_current_screen();
        //error_log(print_r($screen, true));


        // Only render this notice on a Compass page.
        if (! $screen || 'edit.php?post_type=cbn-location' !== $screen->parent_file) {
            return;
        }

        // Render the notice's HTML.
        echo '<div class="notice cbn-getting-started-notice notice-success is-dismissible">';
        echo sprintf(__('<h3>🚀 Getting started with Compass</h3><ol><li>Use the page editor or Elementor to insert the <b>"Compass"</b> block onto a page. Alternatively, you can use the shortcode <code>[Compass]</code></li><li>You can <a href="%s">manage Locations</a> under <i>Compass > All Locations</i></li><li><a href="%s">Customize</a> styles and features under <i>Compass > Settings</i></li></ol>', 'Compass'), 'edit.php?post_type=cbn-location', 'edit.php?post_type=cbn-location&page=Compass-settings');
        echo '</div>';
    }

    public static function getting_started_dismiss_notice()
    {
        update_option('cbn_getting_started_notice_dismissed', 1);
    }

    public function process_wizard_usecase($input)
    {

        // Adjust OUM settings based on the wizard
        if ($input == 1) {

            // everybody
            update_option('cbn_enable_add_location', 'on');

        } elseif ($input == 2) {

            //just me
            update_option('cbn_enable_add_location', '');

            //disable fullscreen button
            update_option('cbn_enable_fullscreen', '');

            //disable searchbar
            update_option('cbn_enable_searchbar', '');

            //disable search address button
            update_option('cbn_enable_searchaddress_button', '');

            //disable search markers button
            update_option('cbn_enable_searchmarkers_button', '');

            //disable current location button
            update_option('cbn_enable_currentlocation', '');

            //disable location date
            update_option('cbn_enable_location_date', '');

        }


        return $input;
    }

    public function csv_export()
    {
        if (isset($_POST['action']) && $_POST['action'] == 'cbn_csv_export') {

            // Initialize error handling
            $error = new \WP_Error();

            // TODO: Exit if no nonce

            if ($error->has_errors()) {

                // Return errors
                wp_send_json_error($error);

            } else {

                // EXPORT
                $all_cbn_locations = get_posts(array(
                    'post_type' => 'cbn-location',
                    'posts_per_page' => -1,
                    'fields' => 'ids',
                ));

                $locations_list = array();

                foreach ($all_cbn_locations as $post_id) {

                    // get fields
                    $location = array(
                        'post_id' => $post_id,
                        'wp_author_id' => cbn_get_location_value('wp_author_id', $post_id),
                        'title' => cbn_get_location_value('title', $post_id),
                        'image' => cbn_get_location_value('image', $post_id, true),
                        'audio' => cbn_get_location_value('audio', $post_id, true),
                        'type' => cbn_get_location_value('type', $post_id),
                        'address' => cbn_get_location_value('address', $post_id),
                        'lat' => cbn_get_location_value('lat', $post_id),
                        'lng' => cbn_get_location_value('lng', $post_id),
                        'text' => cbn_get_location_value('text', $post_id),
                        'notification' => cbn_get_location_value('notification', $post_id),
                        'author_name' => cbn_get_location_value('author_name', $post_id),
                        'author_email' => cbn_get_location_value('author_email', $post_id),
                    );

                    //get custom fields
                    $location_customfields = array();
                    $available_custom_fields = get_option('cbn_custom_fields', array()); // all available custom fields

                    foreach ($available_custom_fields as $custom_field_id => $custom_field) {
                        $value = cbn_get_location_value($custom_field['label'], $post_id, true);

                        // transform array to pipe-separated string (also empty array)
                        if (is_array($value)) {
                            $value = implode('|', $value);
                        }

                        $location_customfields['CUSTOMFIELD_' . $custom_field_id . '_' . $custom_field['label']] = $value;
                    }

                    $location_data = array_merge($location, $location_customfields);

                    $locations_list[] = $location_data;
                }

                //preparing values for CSV
                foreach ($locations_list as $i => $row) {
                    foreach ($row as $j => $val) {
                        //escape "
                        $locations_list[$i][$j] = str_replace('"', '""', $val);
                    }
                }

                $datetime = date('d-m-Y_His'); // Format: YYYY-MM-DD_HHMMSS
                $response = array(
                    'locations' => $locations_list,
                    'datetime' => $datetime
                );

                wp_send_json_success($response);
            }
        }
    }

    public function detectDelimiter($csvFile)
    {
        $delimiters = array(
            ';' => 0,
            ',' => 0,
            "\t" => 0
        );

        $handle = fopen($csvFile, "r");
        $firstLine = fgets($handle);
        fclose($handle);
        foreach ($delimiters as $delimiter => &$count) {
            $count = count(str_getcsv($firstLine, $delimiter));
        }

        return array_search(max($delimiters), $delimiters);
    }

    public function csv_import()
    {

        if (isset($_POST['action']) && $_POST['action'] == 'cbn_csv_import') {

            // Initialize error handling
            $error = new \WP_Error();

            // Dont save without nonce
            if (!isset($_POST['cbn_location_nonce'])) {
                $error->add('002', 'Not allowed');
            }

            // Dont save if nonce is incorrect
            $nonce = $_POST['cbn_location_nonce'];
            if (!wp_verify_nonce($nonce, 'cbn_location')) {
                $error->add('002', 'Not allowed');
            }

            // Exit if no file
            if (!isset($_POST['url'])) {
                $error->add('001', 'File upload failed.');
            }

            // TODO: Exit if no CSV filetype


            if ($error->has_errors()) {

                // Return errors
                wp_send_json_error($error);

            } else {

                // IMPORT
                $path_1 = wp_get_upload_dir()['basedir'];
                $path_2 = explode('/uploads/', $_POST['url'])['1'];
                $csv_file = $path_1 . '/' . $path_2;
                $delimiter = $this->detectDelimiter($csv_file);

                // parse csv file to array
                $file_to_read = fopen($csv_file, 'r');
                while (!feof($file_to_read)) {
                    $rows[] = fgetcsv($file_to_read, 99999, $delimiter);
                }
                fclose($file_to_read);

                // build assoziative array
                array_walk($rows, function (&$a) use ($rows) {
                    // Check if the line is empty or not an array
                    if (is_array($a) && !empty(array_filter($a, 'strlen'))) {
                        $a = array_combine($rows[0], $a);
                    } else {
                        error_log('Compass: an empty line or a row not of type array detected and skipped');
                    }
                });
                array_shift($rows); # remove column header
                $locations = $rows;


                // Create or Update the posts

                $cnt_imported_locations = 0;

                foreach ($locations as $location) {

                    // Marker categories
                    $types = $location['type'];
                    if ($types) {
                        $types = explode('|', $types);
                    }

                    // update or insert post
                    if ($location['post_id'] == '') {
                        $location['post_id'] = 0;
                    }

                    // author
                    $wp_author_id = (isset($location['wp_author_id']) && $location['wp_author_id'] != '') ? $location['wp_author_id'] : get_current_user_id();

                    $insert_post = wp_insert_post(array(
                        'ID' => $location['post_id'],
                        'post_author' => $wp_author_id,
                        'post_type' => 'cbn-location',
                        'post_title' => $location['title'],
                        'post_name' => sanitize_title($location['title']),
                        'tax_input' => array(
                            'cbn-type' => $types
                        )
                    ));

                    if ($insert_post) {

                        // Add fields

                        $fields = array(
                            'cbn_location_nonce' => $nonce,
                            'cbn_location_image' => $location['image'],
                            'cbn_location_audio' => $location['audio'],
                            'cbn_location_address' => $location['address'],
                            'cbn_location_lat' => $location['lat'],
                            'cbn_location_lng' => $location['lng'],
                            'cbn_location_text' => $location['text'],
                            'cbn_location_notification' => $location['notification'],
                            'cbn_location_author_name' => $location['author_name'],
                            'cbn_location_author_email' => $location['author_email'],
                        );


                        // Add custom fields

                        $customfields = array_filter($location, function ($val, $key) {
                            return strpos($key, 'CUSTOMFIELD') === 0;
                        }, ARRAY_FILTER_USE_BOTH);

                        foreach ($customfields as $key => $val) {
                            $id = explode('_', $key)[1];

                            // transform pipe-separated string to array
                            if ($val && strpos($val, '|') !== false) {
                                $val = explode('|', $val);
                            }

                            $fields['cbn_location_custom_fields'][$id] = $val;
                        }

                        // Validate and Save
                        \CompassPlugin\Base\LocationController::save_fields($insert_post, $fields);

                        $cnt_imported_locations++;
                    }

                }

                // return success message
                wp_send_json_success($cnt_imported_locations . ' Locations have been imported successfully.');
            }
        }

    }

    /**
     * Add settings updated message
     */
    public function add_settings_updated_message($option, $old_value, $value)
    {
        // Only add message for our plugin settings and only if no message exists yet
        if (strpos($option, 'cbn_') === 0) {
            global $wp_settings_errors;

            // Check if we already added our message
            if (!empty($wp_settings_errors)) {
                foreach ($wp_settings_errors as $error) {
                    if ($error['setting'] === 'cbn_messages' && $error['code'] === 'cbn_message') {
                        return; // Message already exists, don't add another one
                    }
                }
            }

            add_settings_error(
                'cbn_messages',
                'cbn_message',
                __('Settings Saved', 'Compass'),
                'updated'
            );
        }
    }
}
