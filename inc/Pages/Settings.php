<?php
declare(strict_types=1);

/**
 * @package CompassPlugin
 */

namespace CompassPlugin\Pages;

require_once plugin_dir_path(__FILE__) . '../Base/BaseController.php';


use CompassPlugin\Base\BaseController;
use CompassPlugin\Base\LocationControl;
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
        add_action('admin_init', [$this, 'add_cbn_wizard']);
        add_action('admin_notices', [$this, 'show_getting_started_notice']);
        add_action('wp_ajax_cbn_dismiss_getting_started_notice', [$this, 'getting_started_dismiss_notice']);
        add_action('wp_ajax_cbn_csv_export', [$this, 'csv_export']);
        add_action('wp_ajax_cbn_csv_import', [$this, 'csv_import']);
        add_action('update_option', [$this, 'add_settings_updated_message'], 10, 3);
    }

    /**
     * @return void
     */
    public function add_admin_pages(): void
    {
        // Hoofdmenu
        add_menu_page(
            'Compass Plugin',
            'Compass',
            'manage_options',
            'cbn-dashboard',
            [$this, 'dashboard_page'],
            'dashicons-location-alt',
            25
        );
        // Submenu's
        add_submenu_page('cbn-dashboard', 'Dashboard', 'Dashboard', 'manage_options', 'cbn-dashboard', [$this, 'dashboard_page']);
        add_submenu_page('cbn-dashboard', 'Instellingen', 'Instellingen', 'manage_options', 'cbn-settings', [$this, 'settings_page']);
        add_submenu_page('cbn-dashboard', 'Locaties', 'Locaties', 'manage_options', 'cbn-location', [$this, 'location_page']);
        add_submenu_page('cbn-dashboard', 'Regio Toevoegen', 'Regio Toevoegen', 'manage_options', 'cbn-add-region', [$this, 'add_region_page']);
        add_submenu_page('cbn-dashboard', 'Regio Bewerken', 'Regio Bewerken', 'manage_options', 'cbn-edit-region', [$this, 'edit_region_page']);
        add_submenu_page('cbn-dashboard', 'Type Toevoegen', 'Type Toevoegen', 'manage_options', 'cbn-add-type', [$this, 'add_type_page']);
        add_submenu_page('cbn-dashboard', 'Type Bewerken', 'Type Bewerken', 'manage_options', 'cbn-edit-type', [$this, 'edit_type_page']);


    }

    // Dashboard pagina

    /**
     * @return void
     */
    public function dashboard_page(): void
    {
        include plugin_dir_path(__FILE__) . '../../templates/page-backend-settings.php';
    }

    /**
     * @return void
     */
    public function settings_page(): void
    {
        include plugin_dir_path(__FILE__) . '../../templates/page-backend-settings.php';
    }

    /**
     * @return void
     */
    public function location_page(): void
    {
        include plugin_dir_path(__FILE__) . '../../templates/page-backend-location.php';
    }

    /**
     * @return void
     */
    public function add_region_page(): void
    {
        include plugin_dir_path(__FILE__) . '../../templates/page-backend-add-region.php';
    }

    /**
     * @return void
     */
    public function add_type_page(): void
    {
        include plugin_dir_path(__FILE__) . '../../templates/page-backend-add-type.php';
    }

    /**
     * @return void
     */
    public function edit_region_page(): void
    {
        include plugin_dir_path(__FILE__) . '../../templates/page-backend-edit-region.php';
    }

    /**
     * @return void
     */
    public function edit_type_page(): void
    {
        include plugin_dir_path(__FILE__) . '../../templates/page-backend-edit-type.php';
    }


    /**
     * @return void
     */

    public function add_plugin_settings(): void
    {
        register_setting('cbn-settings-getting-started-notice', 'cbn_getting_started_notice_dismissed');
        register_setting('cbn-settings-group', 'cbn_map_style', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_tile_provider_mapbox_key', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_marker_icon', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_marker_user_icon', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_map_size', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_map_height', ['sanitize_callback' => [$this, 'validate_size']]);
        register_setting('cbn-settings-group', 'cbn_map_height_mobile', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_start_lat', ['sanitize_callback' => [$this, 'validate_geocoordinate']]);
        register_setting('cbn-settings-group', 'cbn_start_lng', ['sanitize_callback' => [$this, 'validate_geocoordinate']]);
        register_setting('cbn-settings-group', 'cbn_start_zoom', ['sanitize_callback' => [$this, 'validate_zoom']]);
        register_setting('cbn-settings-group', 'cbn_enable_fixed_map_bounds', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_enable_title', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_title_required', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_title_maxlength', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_title_label', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_map_label', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_hide_address', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_enable_address', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_geosearch_provider', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_geosearch_provider_geoapify_key', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_geosearch_provider_here_key', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_geosearch_provider_mapbox_key', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_enable_searchbar', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_searchbar_type', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_enable_searchaddress_button', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_searchaddress_label', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_enable_searchmarkers_button', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_searchmarkers_label', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_searchmarkers_zoom', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_enable_gmaps_link', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_address_label', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_enable_description', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_description_required', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_description_label', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_upload_media_label', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_enable_image', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_image_required', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_enable_audio', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_audio_required', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_enable_video', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_video_required', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_custom_fields', ['sanitize_callback' => [$this, 'validate_array']]);
        register_setting('cbn-settings-group', 'cbn_enable_scrollwheel_zoom_map', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_enable_cluster', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_enable_fullscreen', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_enable_currentlocation', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_disable_cbn_attribution', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_max_image_filesize', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_max_audio_filesize', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_action_after_submit', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_thankyou_redirect', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_thankyou_headline', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_thankyou_text', ['sanitize_callback' => 'wp_kses_post']);
        register_setting('cbn-settings-group', 'cbn_plus_button_label', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_submit_button_label', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_form_headline', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_enable_user_notification', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_user_notification_label', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_user_notification_subject', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_enable_admin_notification', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_admin_notification_email', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_admin_notification_subject', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_enable_webhook_notification', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_webhook_notification_url', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_enable_user_restriction', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_enable_redirect_to_registration', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_enable_auto_publish', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_enable_auto_publish_for_everyone', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_enable_add_user_location', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_enable_marker_types', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_enable_empty_marker_type', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_enable_multiple_marker_types', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_collapse_filter', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_marker_types_label', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_ui_color', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_enable_add_location', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_enable_single_page', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_enable_location_date', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_location_date_type', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_enable_regions', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_regions_layout_style', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group', 'cbn_custom_js', ['sanitize_callback' => 'wp_kses_post']);
        register_setting('cbn-settings-group-wizard-1', 'cbn_wizard_usecase', ['sanitize_callback' => [$this, 'process_wizard_usecase']]);
        register_setting('cbn-settings-group-wizard-1', 'cbn_wizard_usecase_done', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('cbn-settings-group-wizard-2', 'cbn_wizard_finish_done', ['sanitize_callback' => 'sanitize_text_field']);


        if (true) {
            if (true) {
                // Premium settings, because wp_kses_post can not be null in free version
                register_setting('cbn-settings-group', 'cbn_user_notification_message', ['sanitize_callback' => 'wp_kses_post']);
                register_setting('cbn-settings-group', 'cbn_admin_notification_message', ['sanitize_callback' => 'wp_kses_post']);
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
        ];

        foreach ($options as $old_option => $new_option) {
            $old_setting = get_option($old_option);

            // do nothing if old option doesn't exist
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

        $options = [
            'cbn_enable_searchaddress' => 'cbn_enable_searchbar',
        ];

        foreach ($options as $old_option => $new_option) {
            $old_setting = get_option($old_option);

            // do nothing if old option doesn't exist
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

    /**
     * @return void
     */
    public function add_cbn_wizard(): void
    {
        if ((get_option('cbn_enable_add_location') !== 'on' && get_option('cbn_enable_add_location') !== '') || (get_option('cbn_wizard_usecase_done') && !get_option('cbn_wizard_finish_done'))) {

            add_action('admin_body_class', function ($class) {
                $class .= ' cbn-settings-wizard';

                return $class;
            });
        }
    }

    /**
     * @return void
     */
    public function admin_index(): void
    {
        require_once cbn_get_template('page-backend-settings.php');
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
        return (is_numeric($input)) ? $input . 'px' : sanitize_text_field($input);
    }

    /**
     * @param $array
     * @return array|string
     */
    public function validate_array($array): array|string
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

    /**
     * @return void
     */
    public static function show_getting_started_notice(): void
    {
        // return if already dismissed
        if (get_option('cbn_getting_started_notice_dismissed')) {
            return;
        }

        $screen = get_current_screen();
        //error_log(print_r($screen, true));


        // Only render this notice on a Compass page.
        if (!$screen || 'edit.php?post_type=cbn-location' !== $screen->parent_file) {
            return;
        }

        // Render the notice's HTML.
        echo '<div class="notice cbn-getting-started-notice notice-success is-dismissible">';
        echo sprintf(__('<!--suppress ALL -->
<h3>🚀 Getting started with Compass</h3><ol><li>Use the page editor or Elementor to insert the <b>"Compass"</b> block onto a page. Alternatively, you can use the shortcode <code>[Compass]</code></li><li>You can <a href="%s">manage Locations</a> under <i>Compass > All Locations</i></li><li><a href="%s">Customize</a> styles and features under <i>Compass > Settings</i></li></ol>', 'Compass'), 'edit.php?post_type=cbn-location', 'edit.php?post_type=cbn-location&page=cbn-settings');
        echo '</div>';
    }

    /**
     * @return void
     */
    public static function getting_started_dismiss_notice(): void
    {
        update_option('cbn_getting_started_notice_dismissed', 1);
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

    /**
     * @return void
     */
    public function csv_export(): void
    {
        if (isset($_POST['action']) && $_POST['action'] == 'cbn_csv_export') {

            // Initialize error handling
            $error = new WP_Error();

            // TODO: Exit if no nonce

            if ($error->has_errors()) {

                // Return errors
                wp_send_json_error($error);

            } else {

                // EXPORT
                $all_cbn_locations = get_posts([
                    'post_type' => 'cbn-location',
                    'posts_per_page' => -1,
                    'fields' => 'ids',
                ]);

                $locations_list = [];

                foreach ($all_cbn_locations as $post_id) {

                    // get fields
                    $location = [
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
                    ];

                    //get custom fields
                    $location_customfields = [];
                    $available_custom_fields = get_option('cbn_custom_fields', []); // all available custom fields

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

        if (isset($_POST['action']) && $_POST['action'] == 'cbn_csv_import') {

            // Initialize error handling
            $error = new WP_Error();

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

                    $insert_post = wp_insert_post([
                        'ID' => $location['post_id'],
                        'post_author' => $wp_author_id,
                        'post_type' => 'cbn-location',
                        'post_title' => $location['title'],
                        'post_name' => sanitize_title($location['title']),
                        'tax_input' => [
                            'cbn-type' => $types
                        ]
                    ]);

                    if ($insert_post) {

                        // Add fields

                        $fields = [
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

                            $fields['cbn_location_custom_fields'][$id] = $val;
                        }

                        // Validate and Save
                        LocationControl::save_fields($insert_post, $fields);

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
    public function add_settings_updated_message($option): void
    {
        // Only add message for our plugin settings and only if no message exists yet
        if (str_starts_with($option, 'cbn_')) {
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
