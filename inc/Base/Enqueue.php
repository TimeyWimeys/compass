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
        wp_enqueue_style('cbn_admin_style', $this->plugin_url . 'assets/css/style.css', [], $this->plugin_version);
        wp_enqueue_style('wp-color-picker');

        // Admin scripts
        wp_enqueue_script(
            'cbn_script',
            $this->plugin_url . 'src/js/backend.js',
            ['wp-i18n', 'jquery', 'wp-color-picker'],
            $this->plugin_version,
            true
        );

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
        wp_enqueue_style('cbn_frontend_style', $this->plugin_url . 'assets/css/frontend.css', [], $this->plugin_version);
    }

    /**
     * Laadt Dashicons op de frontend (indien nodig)
     */
    public function load_dashicons_front_end(): void
    {
        wp_enqueue_style('dashicons');
    }
}
