<?php
declare(strict_types=1);

/**
 * @package CompassPlugin
 */

namespace CompassPlugin\Base;

/**
 *
 */
class TaxController extends BaseController
{
    public $settings;

    /**
     * @return void
     */
    public function register(): void
    {

        if (true):

            if (true):


                //PRO Feature: use types
                if (get_option('cbn_enable_marker_types')) {

                    // Taxonomy: type
                    add_action('init', [$this, 'type_tax']);
                    add_action('cbn-type_add_form_fields', [$this, 'type_tax_add_custom_fields']);
                    add_action('cbn-type_edit_form_fields', [$this, 'type_tax_edit_custom_fields'], 10, 2);
                    add_action('edited_cbn-type', [$this, 'type_tax_save']);
                    add_action('create_cbn-type', [$this, 'type_tax_save']);
                }

            endif;
        endif;

        if (get_option('cbn_enable_regions')) {

            // Taxonomy: region
            add_action('init', [$this, 'region_tax']);
            add_action('cbn-region_add_form_fields', [$this, 'region_tax_add_custom_fields']);
            add_action('cbn-region_edit_form_fields', [$this, 'region_tax_edit_custom_fields'], 10, 2);
            add_action('edited_cbn-region', [$this, 'region_tax_save']);
            add_action('create_cbn-region', [$this, 'region_tax_save']);
            add_action('manage_edit-cbn-region_columns', [$this, 'set_custom_region_columns']);
            add_action('manage_cbn-region_custom_column', [$this, 'set_custom_region_columns_data'], 10, 3); // this method has 3 attributes

        }
    }

    /**
     * Taxonomy: cbn-type
     */

    public static function type_tax(): void
    {
        $labels = [
            'name' => __('Marker Categories', 'Compass'),
            'singular_name' => __('Marker Category', 'Compass'),
            'menu_name' => __('Marker Categories', 'Compass'),
            'all_items' => __('All Marker Categories', 'Compass'),
            'edit_item' => __('Edit Marker Category', 'Compass'),
            'view_item' => __('Show Marker Category', 'Compass'),
            'update_item' => __('Update Marker Category', 'Compass'),
            'add_new_item' => __('Add new Marker Category', 'Compass'),
            'new_item_name' => __('New Type name', 'Compass'),
            'search_items' => __('Search Marker Categories', 'Compass'),
            'choose_from_most_used' => __('Choose from the most used Marker Categories', 'Compass'),
            'popular_items' => __('Popular Marker Categories', 'Compass'),
            'add_or_remove_items' => __('Add or remove Marker Categories', 'Compass'),
            'separate_items_with_commas' => __('Separate Marker Categories with commas', 'Compass'),
            'back_to_items' => __('Back to Marker Categories', 'Compass'),
        ];

        $args = [
            'labels' => $labels,
            'public' => false,
            'show_ui' => true,
            'exclude_from_search' => true,
            'show_in_nav_menus' => false,
            'show_admin_column' => true,
            'show_in_quick_edit' => true,
            'hierarchical' => false,
            'show_in_rest' => true,
        ];

        register_taxonomy('cbn-type', 'cbn-location', $args);
    }

    /**
     * @return void
     */
    public function type_tax_add_custom_fields(): void
    {
        wp_nonce_field('cbn_location', 'cbn_location_nonce');

        // render view
        require_once cbn_get_template('page-backend-add-type.php');

        wp_enqueue_script('cbn_backend_type_js', $this->plugin_url . 'src/js/backend-type.js', ['wp-polyfill'], $this->plugin_version);
    }

    /**
     * @return void
     */
    public function type_tax_edit_custom_fields(): void
    {
        wp_nonce_field('cbn_location', 'cbn_location_nonce');

        // render view
        require_once cbn_get_template('page-backend-edit-type.php');

        wp_enqueue_script('cbn_backend_type_js', $this->plugin_url . 'src/js/backend-type.js', ['wp-polyfill'], $this->plugin_version);

    }

    /**
     * @param $term_id
     * @return mixed
     */
    public function type_tax_save($term_id): mixed
    {
        // Dont save without nonce
        if (!isset($_POST['cbn_location_nonce'])) {
            return $term_id;
        }

        // Dont save if nonce is incorrect
        $nonce = $_POST['cbn_location_nonce'];
        if (!wp_verify_nonce($nonce, 'cbn_location')) {
            return $term_id;
        }

        // Dont save if wordpress just auto-saves
        if (defined('DOING AUTOSAVE') && DOING_AUTOSAVE) {
            return $term_id;
        }

        // Save Taxonomy Icon
        if (isset($_POST['cbn_marker_icon'])) {
            // Validation
            $cbn_marker_icon_validated = sanitize_text_field($_POST['cbn_marker_icon']);
            if (!$cbn_marker_icon_validated) {
                $cbn_marker_icon_validated = '';
            }

            if ($cbn_marker_icon_validated) {
                update_term_meta($term_id, 'cbn_marker_icon', $cbn_marker_icon_validated);
            }
        }

        // Save Custom Image Icon
        if (isset($_POST['cbn_marker_user_icon'])) {
            // Validation
            $cbn_marker_user_icon_validated = sanitize_text_field($_POST['cbn_marker_user_icon']);
            if (!$cbn_marker_user_icon_validated) {
                $cbn_marker_user_icon_validated = '';
            }

            if ($cbn_marker_user_icon_validated) {
                update_term_meta($term_id, 'cbn_marker_user_icon', $cbn_marker_user_icon_validated);
            }
        }
        return $term_id;
    }


    /**
     * Taxonomy: cbn-region
     */

    public static function region_tax(): void
    {
        $labels = [
            'name' => __('Regions', 'Compass'),
            'singular_name' => __('Region', 'Compass'),
            'menu_name' => __('Regions', 'Compass'),
            'all_items' => __('All Regions', 'Compass'),
            'edit_item' => __('Edit Region', 'Compass'),
            'view_item' => __('Show Region', 'Compass'),
            'update_item' => __('Update Region', 'Compass'),
            'add_new_item' => __('Add new Region', 'Compass'),
            'new_item_name' => __('New Type name', 'Compass'),
            'search_items' => __('Search Regions', 'Compass'),
            'choose_from_most_used' => __('Choose from the most used Regions', 'Compass'),
            'popular_items' => __('Popular Regions', 'Compass'),
            'add_or_remove_items' => __('Add or remove Regions', 'Compass'),
            'separate_items_with_commas' => __('Separate Regions with commas', 'Compass'),
            'back_to_items' => __('Back to Regions', 'Compass'),
        ];

        $args = [
            'labels' => $labels,
            'public' => false,
            'show_ui' => true,
            'exclude_from_search' => true,
            'show_in_nav_menus' => false,
            'show_admin_column' => false,
            'show_in_quick_edit' => false,
            'meta_box_cb' => false,
            'hierarchical' => false,
            'show_in_rest' => false,
        ];

        register_taxonomy('cbn-region', 'cbn-location', $args);
    }

    /**
     * @return void
     */
    public function region_tax_add_custom_fields(): void
    {
        wp_nonce_field('cbn_location', 'cbn_location_nonce');

        // render view
        require_once cbn_get_template('page-backend-add-region.php');


    }

    /**
     * @return void
     */
    public function region_tax_edit_custom_fields(): void
    {
        wp_nonce_field('cbn_location', 'cbn_location_nonce');

        // render view
        require_once cbn_get_template('page-backend-edit-region.php');


    }

    /**
     * @param $term_id
     * @return mixed
     */
    public function region_tax_save($term_id): mixed
    {
        // Dont save without nonce
        if (!isset($_POST['cbn_location_nonce'])) {
            return $term_id;
        }

        // Dont save if nonce is incorrect
        $nonce = $_POST['cbn_location_nonce'];
        if (!wp_verify_nonce($nonce, 'cbn_location')) {
            return $term_id;
        }

        // Dont save if wordpress just auto-saves
        if (defined('DOING AUTOSAVE') && DOING_AUTOSAVE) {
            return $term_id;
        }

        if (isset($_POST['cbn_lat'])) {
            // Validation
            $cbn_lat_validated = floatval(str_replace(',', '.', sanitize_text_field($_POST['cbn_lat'])));
            if (!$cbn_lat_validated) {
                $cbn_lat_validated = '';
            }

            if ($cbn_lat_validated) {
                update_term_meta($term_id, 'cbn_lat', $cbn_lat_validated);
            }
        }

        if (isset($_POST['cbn_lng'])) {
            // Validation
            $cbn_lng_validated = floatval(str_replace(',', '.', sanitize_text_field($_POST['cbn_lng'])));
            if (!$cbn_lng_validated) {
                $cbn_lng_validated = '';
            }

            if ($cbn_lng_validated) {
                update_term_meta($term_id, 'cbn_lng', $cbn_lng_validated);
            }
        }

        if (isset($_POST['cbn_zoom'])) {
            // Validation
            $cbn_zoom_validated = floatval(str_replace(',', '.', sanitize_text_field($_POST['cbn_zoom'])));
            if (!$cbn_zoom_validated) {
                $cbn_zoom_validated = '';
            }

            if ($cbn_zoom_validated) {
                update_term_meta($term_id, 'cbn_zoom', $cbn_zoom_validated);
            }
        }
        return $term_id;
    }

    /**
     * @param $columns
     * @return mixed
     */
    public static function set_custom_region_columns($columns): mixed
    {
        // preserve default columns
        $name = $columns['name'];
        unset($columns['description'], $columns['slug'], $columns['posts']);

        $columns['name'] = $name;
        $columns['geocoordinates'] = __('Coordinates', 'Compass');
        $columns['zoom'] = __('Zoom', 'Compass');

        return $columns;
    }

    /**
     * @param $column
     * @param $term_id
     * @return void
     */
    public static function set_custom_region_columns_data($column, $term_id): void
    {
        $data = get_term_meta($term_id);

        $lat = $data['cbn_lat'][0] ?? '';
        $lng = $data['cbn_lng'][0] ?? '';
        $zoom = $data['cbn_zoom'][0] ?? '';

        switch ($column) {
            case 'geocoordinates':
                echo esc_attr($lat) . ', ' . esc_attr($lng);
                break;
            case 'zoom':
                echo esc_attr($zoom);
                break;
            default:
                break;
        }
    }
}
