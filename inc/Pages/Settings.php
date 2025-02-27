<?php
declare(strict_types=1);
/**
 * @package OpenUserMapPlugin
 */

namespace OpenUserMapPlugin\Pages;

use OpenUserMapPlugin\Base\BaseController;
use OpenUserMapPlugin\Base\LocationController;
use WP_Error;

/**
 *
 */
class Settings extends BaseController
{
    /**
     * @return void
     */
    public function register(): void
    {
        add_action('init', [$this, 'migrate_deprecated_settings']);
        add_action('admin_menu', [$this, 'add_admin_pages']);
        add_action('admin_init', [$this, 'add_plugin_settings']);
        add_action('admin_init', [$this, 'add_oum_wizard']);
        add_action('admin_notices', [$this, 'show_getting_started_notice']);
        add_action('wp_ajax_oum_dismiss_getting_started_notice', [$this, 'getting_started_dismiss_notice']);
        add_action('wp_ajax_oum_csv_export', [$this, 'csv_export']);
        add_action('wp_ajax_oum_csv_import', [$this, 'csv_import']);
        add_action('update_option', [$this, 'add_settings_updated_message'], 10, 3);
    }

    /**
     * @return void
     */
    public function add_admin_pages(): void
    {
        //add_options_page('Open User Map', 'Open User Map', 'manage_options', 'open_user_map', array($this, 'admin_index'));
        add_submenu_page('edit.php?post_type=oum-location', 'Settings', 'Settings', 'manage_options', 'open-user-map-settings', [$this, 'admin_index']);
    }

    /**
     * @return void
     */
    public function add_plugin_settings(): void
    {
        register_setting('open-user-map-settings-getting-started-notice', 'oum_getting_started_notice_dismissed');
        register_setting('open-user-map-settings-group', 'oum_map_style', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_tile_provider_mapbox_key', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_marker_icon', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_marker_user_icon', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_map_size', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_map_height', ['sanitize_callback' => [$this, 'validate_size']]);
        register_setting('open-user-map-settings-group', 'oum_map_height_mobile', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_start_lat', ['sanitize_callback' => [$this, 'validate_geocoordinate']]);
        register_setting('open-user-map-settings-group', 'oum_start_lng', ['sanitize_callback' => [$this, 'validate_geocoordinate']]);
        register_setting('open-user-map-settings-group', 'oum_start_zoom', ['sanitize_callback' => [$this, 'validate_zoom']]);
        register_setting('open-user-map-settings-group', 'oum_enable_fixed_map_bounds', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_enable_title', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_title_required', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_title_maxlength', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_title_label', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_map_label', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_hide_address', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_enable_address', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_geosearch_provider', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_geosearch_provider_geoapify_key', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_geosearch_provider_here_key', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_geosearch_provider_mapbox_key', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_enable_searchbar', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_searchbar_type', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_enable_searchaddress_button', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_searchaddress_label', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_enable_searchmarkers_button', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_searchmarkers_label', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_searchmarkers_zoom', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_enable_gmaps_link', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_address_label', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_enable_description', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_description_required', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_description_label', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_upload_media_label', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_enable_image', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_image_required', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_enable_audio', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_audio_required', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_enable_video', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_video_required', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_custom_fields', ['sanitize_callback' => [$this, 'validate_array']]);
        register_setting('open-user-map-settings-group', 'oum_enable_scrollwheel_zoom_map', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_enable_cluster', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_enable_fullscreen', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_enable_currentlocation', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_disable_oum_attribution', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_max_image_filesize', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_max_audio_filesize', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_action_after_submit', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_thankyou_redirect', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_thankyou_headline', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_thankyou_text', ['sanitize_callback' => 'wp_kses_post']);
        register_setting('open-user-map-settings-group', 'oum_plus_button_label', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_submit_button_label', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_form_headline', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_enable_user_notification', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_user_notification_label', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_user_notification_subject', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_enable_admin_notification', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_admin_notification_email', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_admin_notification_subject', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_enable_webhook_notification', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_webhook_notification_url', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_enable_user_restriction', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_enable_redirect_to_registration', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_enable_auto_publish', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_enable_auto_publish_for_everyone', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_enable_add_user_location', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_enable_marker_types', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_enable_empty_marker_type', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_enable_multiple_marker_types', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_collapse_filter', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_marker_types_label', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_ui_color', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_enable_add_location', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_enable_single_page', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_enable_location_date', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_location_date_type', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_enable_regions', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_regions_layout_style', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group', 'oum_custom_js', ['sanitize_callback' => 'wp_kses_post']);
        register_setting('open-user-map-settings-group-wizard-1', 'oum_wizard_usecase', ['sanitize_callback' => [$this, 'process_wizard_usecase']]);
        register_setting('open-user-map-settings-group-wizard-1', 'oum_wizard_usecase_done', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('open-user-map-settings-group-wizard-2', 'oum_wizard_finish_done', ['sanitize_callback' => 'sanitize_text_field']);


        if (oum_fs()->is__premium_only()) {
            if (oum_fs()->can_use_premium_code()) {
                // Premium settings, because wp_kses_post can not be null in free version
                register_setting('open-user-map-settings-group', 'oum_user_notification_message', ['sanitize_callback' => 'wp_kses_post']);
                register_setting('open-user-map-settings-group', 'oum_admin_notification_message', ['sanitize_callback' => 'wp_kses_post']);
            }
        }
    }

    /**
     * @return void
     */
    public function migrate_deprecated_settings(): void
    {
        // Variant 1: invert old settings

        $options = [
            'oum_disable_add_location' => 'oum_enable_add_location',
            'oum_disable_title' => 'oum_enable_title',
            'oum_disable_address' => 'oum_enable_address',
            'oum_disable_gmaps_link' => 'oum_enable_gmaps_link',
            'oum_disable_description' => 'oum_enable_description',
            'oum_disable_image' => 'oum_enable_image',
            'oum_disable_audio' => 'oum_enable_audio',
            'oum_disable_cluster' => 'oum_enable_cluster',
            'oum_disable_fullscreen' => 'oum_enable_fullscreen',
            'oum_disable_searchaddress' => 'oum_enable_searchaddress_button',
        ];

        foreach ($options as $old_option => $new_option) {
            $old_setting = get_option($old_option);

            // do nothing if old option doesnt exist
            if ($old_setting === false) {
                //error_log('Open User Map: Deprecated option ' . $old_option . ' does not exist. Nothing to do.');
                continue;
            }

            if (empty($old_setting)) {
                $new_setting = 'on';
            } else {
                $new_setting = '';
            }

            //update (or create) new
            update_option($new_option, $new_setting);
            error_log('Open User Map: Update new option ' . $new_option . ' from old option ' . $old_option . '. New Value: ' . $new_setting);

            //delete old
            delete_option($old_option);
            error_log('Open User Map: Deleting old option ' . $new_option . '.');
        }


        // Variant 2: rename settings (keep value)

        $options = [
            'oum_enable_searchaddress' => 'oum_enable_searchbar',
        ];

        foreach ($options as $old_option => $new_option) {
            $old_setting = get_option($old_option);

            // do nothing if old option doesnt exist
            if ($old_setting === false) {
                //error_log('Open User Map: Deprecated option ' . $old_option . ' does not exist. Nothing to do.');
                continue;
            }

            //update (or create) new
            update_option($new_option, $old_setting);
            error_log('Open User Map: Update new option ' . $new_option . ' from old option ' . $old_option . '. New Value: ' . $old_setting);

            //delete old
            delete_option($old_option);
            error_log('Open User Map: Deleting old option ' . $new_option . '.');
        }


        // Variant 3: change value of a setting
        if (get_option('oum_map_style') == 'Stamen.TonerLite') {
            update_option('oum_map_style', 'CartoDB.Positron');
        }

        if (get_option('oum_map_style') == 'Stadia.StamenTonerLite') {
            update_option('oum_map_style', 'CartoDB.Positron');
        }
    }

    /**
     * @return void
     */
    public function add_oum_wizard(): void
    {
        if ((get_option('oum_enable_add_location') !== 'on' && get_option('oum_enable_add_location') !== '') || (get_option('oum_wizard_usecase_done') && !get_option('oum_wizard_finish_done'))) {

            add_action('admin_body_class', function ($class) {
                $class .= ' oum-settings-wizard';

                return $class;
            });
        }
    }

    /**
     * @return void
     */
    public function admin_index(): void
    {
        require_once oum_get_template('page-backend-settings.php');
    }

    /**
     * @param $input
     * @return float|string
     */
    public static function validate_geocoordinate($input): float|string
    {
        // Validation
        $geocoordinate_validated = floatval(str_replace(',', '.', sanitize_text_field($input)));
        if (!$geocoordinate_validated && $geocoordinate_validated != '0') {
            $geocoordinate_validated = '';
        }

        return $geocoordinate_validated;
    }

    /**
     * @param $input
     * @return float|string
     */
    public static function validate_zoom($input): float|string
    {
        // Validation
        $zoom_validated = floatval(str_replace(',', '.', sanitize_text_field($input)));
        if (!$zoom_validated) {
            $zoom_validated = '';
        }

        return $zoom_validated;
    }

    /**
     * @param $input
     * @return string
     */
    public static function validate_size($input): string
    {
        // Add px if it's missing
        $size_validated = (is_numeric($input)) ? $input . 'px' : sanitize_text_field($input);

        return $size_validated;
    }

    /**
     * @param $array
     * @return array|string
     */
    public function validate_array($array): array|string
    {

        // if not an array
        if (!is_array($array)) return '';

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

    /**
     * @return void
     */
    public static function show_getting_started_notice(): void
    {
        // return if already dismissed
        if (get_option('oum_getting_started_notice_dismissed')) {
            return;
        }

        $screen = get_current_screen();
        //error_log(print_r($screen, true));


        // Only render this notice on a Open User Map page.
        if (!$screen || 'edit.php?post_type=oum-location' !== $screen->parent_file) {
            return;
        }

        // Render the notice's HTML.
        echo '<div class="notice oum-getting-started-notice notice-success is-dismissible">';
        echo sprintf(__('<h3>🚀 Getting started with Open User Map</h3><ol><li>Use the page editor or Elementor to insert the <b>"Open User Map"</b> block onto a page. Alternatively, you can use the shortcode <code>[open-user-map]</code></li><li>You can <a href="%s">manage Locations</a> under <i>Open User Map > All Locations</i></li><li><a href="%s">Customize</a> styles and features under <i>Open User Map > Settings</i></li></ol>', 'open-user-map'), 'edit.php?post_type=oum-location', 'edit.php?post_type=oum-location&page=open-user-map-settings');
        echo '</div>';
    }

    /**
     * @return void
     */
    public static function getting_started_dismiss_notice(): void
    {
        update_option('oum_getting_started_notice_dismissed', 1);
    }

    /**
     * @param $input
     * @return mixed
     */
    public function process_wizard_usecase($input): mixed
    {

        // Adjust OUM settings based on the wizard
        if ($input == 1) {

            // everybody
            update_option('oum_enable_add_location', 'on');

        } elseif ($input == 2) {

            //just me
            update_option('oum_enable_add_location', '');

            //disable fullscreen button
            update_option('oum_enable_fullscreen', '');

            //disable searchbar
            update_option('oum_enable_searchbar', '');

            //disable search address button
            update_option('oum_enable_searchaddress_button', '');

            //disable search markers button
            update_option('oum_enable_searchmarkers_button', '');

            //disable current location button
            update_option('oum_enable_currentlocation', '');

            //disable location date
            update_option('oum_enable_location_date', '');

        }


        return $input;
    }

    /**
     * @return void
     */
    public function csv_export(): void
    {
        if (isset($_POST['action']) && $_POST['action'] == 'oum_csv_export') {

            // Initialize error handling
            $error = new WP_Error;

            // TODO: Exit if no nonce

            if ($error->has_errors()) {

                // Return errors
                wp_send_json_error($error);

            } else {

                // EXPORT
                $all_oum_locations = get_posts([
                    'post_type' => 'oum-location',
                    'posts_per_page' => -1,
                    'fields' => 'ids',
                ]);

                $locations_list = [];

                foreach ($all_oum_locations as $post_id) {

                    // get fields
                    $location = [
                        'post_id' => $post_id,
                        'wp_author_id' => oum_get_location_value('wp_author_id', $post_id),
                        'title' => oum_get_location_value('title', $post_id),
                        'image' => oum_get_location_value('image', $post_id, true),
                        'audio' => oum_get_location_value('audio', $post_id, true),
                        'type' => oum_get_location_value('type', $post_id),
                        'address' => oum_get_location_value('address', $post_id),
                        'lat' => oum_get_location_value('lat', $post_id),
                        'lng' => oum_get_location_value('lng', $post_id),
                        'text' => oum_get_location_value('text', $post_id),
                        'notification' => oum_get_location_value('notification', $post_id),
                        'author_name' => oum_get_location_value('author_name', $post_id),
                        'author_email' => oum_get_location_value('author_email', $post_id),
                    ];

                    //get custom fields
                    $location_customfields = [];
                    $available_custom_fields = get_option('oum_custom_fields', []); // all available custom fields

                    foreach ($available_custom_fields as $custom_field_id => $custom_field) {
                        $value = oum_get_location_value($custom_field['label'], $post_id, true);

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
                $response = [
                    'locations' => $locations_list,
                    'datetime' => $datetime
                ];

                wp_send_json_success($response);
            }
        }
    }

    /**
     * @param $csvFile
     * @return false|int|string
     */
    public function detectDelimiter($csvFile): false|int|string
    {
        $delimiters = [
            ';' => 0,
            ',' => 0,
            "\t" => 0
        ];

        $handle = fopen($csvFile, 'r');
        $firstLine = fgets($handle);
        fclose($handle);
        foreach ($delimiters as $delimiter => &$count) {
            $count = count(str_getcsv($firstLine, $delimiter));
        }

        return array_search(max($delimiters), $delimiters);
    }

    /**
     * @return void
     */
    public function csv_import(): void
    {

        if (isset($_POST['action']) && $_POST['action'] == 'oum_csv_import') {

            // Initialize error handling
            $error = new WP_Error;

            // Dont save without nonce
            if (!isset($_POST['oum_location_nonce'])) {
                $error->add('002', 'Not allowed');
            }

            // Dont save if nonce is incorrect
            $nonce = $_POST['oum_location_nonce'];
            if (!wp_verify_nonce($nonce, 'oum_location')) {
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
                        error_log('Open User Map: an empty line or a row not of type array detected and skipped');
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

                    $insert_post = wp_insert_post([
                        'ID' => $location['post_id'],
                        'post_author' => $wp_author_id,
                        'post_type' => 'oum-location',
                        'post_title' => $location['title'],
                        'post_name' => sanitize_title($location['title']),
                        'tax_input' => [
                            'oum-type' => $types
                        ]
                    ]);

                    if ($insert_post) {

                        // Add fields

                        $fields = [
                            'oum_location_nonce' => $nonce,
                            'oum_location_image' => $location['image'],
                            'oum_location_audio' => $location['audio'],
                            'oum_location_address' => $location['address'],
                            'oum_location_lat' => $location['lat'],
                            'oum_location_lng' => $location['lng'],
                            'oum_location_text' => $location['text'],
                            'oum_location_notification' => $location['notification'],
                            'oum_location_author_name' => $location['author_name'],
                            'oum_location_author_email' => $location['author_email'],
                        ];


                        // Add custom fields

                        $customfields = array_filter($location, function ($val, $key) {
                            return str_starts_with($key, 'CUSTOMFIELD');
                        }, ARRAY_FILTER_USE_BOTH);

                        foreach ($customfields as $key => $val) {
                            $id = explode('_', $key)[1];

                            // transform pipe-separated string to array
                            if ($val && str_contains($val, '|')) {
                                $val = explode('|', $val);
                            }

                            $fields['oum_location_custom_fields'][$id] = $val;
                        }

                        // Validate and Save
                        LocationController::save_fields($insert_post, $fields);

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
    public function add_settings_updated_message($option, $old_value, $value): void
    {
        // Only add message for our plugin settings and only if no message exists yet
        if (str_starts_with($option, 'oum_')) {
            global $wp_settings_errors;

            // Check if we already added our message
            if (!empty($wp_settings_errors)) {
                foreach ($wp_settings_errors as $error) {
                    if ($error['setting'] === 'oum_messages' && $error['code'] === 'oum_message') {
                        return; // Message already exists, don't add another one
                    }
                }
            }

            add_settings_error(
                'oum_messages',
                'oum_message',
                __('Settings Saved', 'open-user-map'),
                'updated'
            );
        }
    }
}
