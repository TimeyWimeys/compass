<?php
declare(strict_types=1);

/**
 * @package CompassPlugin
 */

namespace CompassPlugin\Base;

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
        // Admin CSS & JS
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin']);

        // Frontend CSS & JS
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend']);

        // Dashicons inladen voor frontend (indien nodig)
        add_action('wp_enqueue_scripts', [$this, 'load_dashicons_front_end']);
    }

    /**
     * Laadt CSS en JS voor de WordPress admin
     */
    public function enqueue_admin(): void
    {
        // Admin styles
        wp_enqueue_style('cbn_admin_style', $this->plugin_url . 'assets/css/style.css', [], time());
        wp_enqueue_style('wp-color-picker');

        // Admin scripts
        wp_enqueue_script(
            'cbn_script',
            $this->plugin_url . 'src/js/backend.js',
            ['wp-i18n', 'jquery', 'wp-color-picker'],
            $this->plugin_version,
            true
        );

        /**
         * @return void
         */
        function admin_enqueue_scripts(): void
        {
            wp_enqueue_script(
                'custom-backend-js',
                plugin_dir_url(dirname(__FILE__, 3)) . 'templates/backend.js', // 🔹 Correcte URL genereren
                ['jquery'],
                '1.0',
                true
            );

            // Maak ajaxurl beschikbaar in JavaScript
            wp_localize_script('custom-backend-js', 'ajax_object', [
                'ajaxurl' => admin_url('admin-ajax.php')
            ]);
        }

        add_action('admin_enqueue_scripts', 'admin_enqueue_scripts');

        add_action('admin_enqueue_scripts', 'my_plugin_enqueue_scripts');
        wp_localize_script('cbn_script', 'cbn_ajax', [
            'cbn_location_nonce' => wp_create_nonce('cbn_location')
        ]);

        // JS vertalingen
        wp_set_script_translations(
            'cbn_script',
            'Compass',
            $this->plugin_path . 'languages'
        );
    }

    /**
     * Laadt CSS en JS voor de frontend
     */
    public function enqueue_frontend(): void
    {
        // Frontend styles
        wp_enqueue_style('cbn_frontend_style', $this->plugin_url . 'assets/css/frontend.css', [], time());
    }

    /**
     * Laadt Dashicons op de frontend (indien nodig)
     */
    public function load_dashicons_front_end(): void
    {
        wp_enqueue_style('dashicons');
    }
}
