<?php
/**
 * @package OpenUserMapPlugin
 */

namespace OpenUserMapPlugin\Base;

use OpenUserMapPlugin\Base\BaseController;

/**
 *
 */
class Enqueue extends BaseController
{
    /**
     * @return void
     */
    public function register(): void
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin'));

        //Enqueue the Dashicons script
        add_action('wp_enqueue_scripts', array($this, 'load_dashicons_front_end'));
    }

    /**
     * @return void
     */
    public function enqueue_admin(): void
    {
        // enqueue admin styles
        wp_enqueue_style('oum_style', $this->plugin_url . 'assets/style.css', array(), $this->plugin_version);
        wp_enqueue_style('wp-color-picker');

        // Enqueue wpColorPickerAlpha
        wp_enqueue_style('wp-color-picker');
        wp_register_script('wp-color-picker-alpha', $this->plugin_url . 'src/js/wp-color-picker-alpha.js', array('wp-color-picker'), $current_version, $in_footer);
        wp_add_inline_script(
            'wp-color-picker-alpha',
            'jQuery( function() { jQuery( ".color-picker" ).wpColorPicker(); } );'
        );

        // add media API (media uploader)
        if (!did_action('wp_enqueue_media')) {
            wp_enqueue_media();
        }

        // enqueue admin scripts
        wp_enqueue_script(
            'oum_script',
            $this->plugin_url . 'src/js/backend.js',
            array('wp-i18n', 'jquery', 'wp-color-picker', 'wp-color-picker-alpha'),
            $this->plugin_version,
            true
        );

        wp_localize_script('oum_script', 'oum_ajax', array(
            'oum_location_nonce' => wp_create_nonce('oum_location')
        ));

        // add JS translation for admin scripts
        wp_set_script_translations(
            'oum_script',
            'open-user-map',
            $this->plugin_path . 'languages'
        );
    }

    /**
     * @return void
     */
    public function load_dashicons_front_end(): void
    {
        wp_enqueue_style('dashicons');
    }
}
