<?php

/**
 * @package CompassPlugin
 */

namespace CompassPlugin\Base;

use CompassPlugin\Base\BaseController;

class Enqueue extends BaseController
{
    public function register()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin'));

        //Enqueue the Dashicons script
        add_action('wp_enqueue_scripts', array($this, 'load_dashicons_front_end'));
    }

    public function enqueue_admin()
    {
        // enqueue admin styles
        wp_enqueue_style('cbn_style', $this->plugin_url . 'assets/style.css', array(), $this->plugin_version);
        wp_enqueue_style('wp-color-picker');

        // add media API (media uploader)
        if (!did_action('wp_enqueue_media')) {
            wp_enqueue_media();
        }

        // enqueue admin scripts
        wp_enqueue_script(
            'cbn_script',
            $this->plugin_url . 'src/js/backend.js',
            array('wp-i18n', 'jquery', 'wp-color-picker'),
            $this->plugin_version,
            true
        );

        wp_localize_script('cbn_script', 'cbn_ajax', array(
            'cbn_location_nonce' => wp_create_nonce('cbn_location')
        ));

        // add JS translation for admin scripts
        wp_set_script_translations(
            'cbn_script',
            'Compass',
            $this->plugin_path . 'languages'
        );
    }

    public function load_dashicons_front_end()
    {
        wp_enqueue_style('dashicons');
    }
}
