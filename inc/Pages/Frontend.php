<?php

/**
 * @package CompassPlugin
 */

namespace CompassPlugin\Pages;

use CompassPlugin\Base\BaseController;
use Elementor_cbn_Addon\Plugin;

class Frontend extends BaseController
{
    public function register()
    {
        // Shortcodes
        add_action('init', array($this, 'set_shortcodes'));




        //Add user location within registration
        if(get_option('cbn_enable_add_user_location')):
            add_action('register_form', array($this, 'render_block_add_user_location'));
            add_action('user_register', array($this, 'add_user_location'));
        endif;
    }

    /**
     * Setup Shortcodes
     */
    public function set_shortcodes()
    {
        // EXIT if inside Elementor Backend
        // Check if Elementor installed and activated
        if (did_action('elementor/loaded')) {

            if(Plugin::is_elementor_backend()) {
                error_log('OUM: prevented shortcode rendering inside Elementor');
                return;
            }

        }

        // Render Map
        add_shortcode('Compass', array($this, 'render_block_map'));

        //Render Image Gallery (Shortcode)
        add_shortcode('Compass-gallery', array($this, 'render_block_gallery'));

        //Render Location Value (Shortcode)
        add_shortcode('Compass-location', array($this, 'render_block_location'));

        //Render Locations List  (Shortcode)
        add_shortcode('Compass-list', array($this, 'render_block_list'));

        // Whitelisting OUM scripts for Complianz plugin
        add_filter('script_loader_tag', function ($tag, $handle, $source) {

            if (stristr($handle, 'cbn')) {
                $tag = '<script src="' . $source . '" data-category="functional" class="cmplz-native" id="' . $handle . '-js"></script>';
            }

            return $tag;
        }, 10, 3);

        // Prevent shortcode parsing by All In One SEO plugin
        add_filter('aioseo_disable_shortcode_parsing', '__return_true');

        // Prevent shortcode parsing by Slim SEO plugin
        add_filter('slim_seo_skipped_shortcodes', function ($shortcodes) {
            $shortcodes[] = 'Compass';
            $shortcodes[] = 'Compass-location';
            $shortcodes[] = 'Compass-gallery';
            $shortcodes[] = 'Compass-list';
            return $shortcodes;
        });

        // Prevent block parsing by Slim SEO plugin
        add_filter('slim_seo_skipped_blocks', function ($blocks) {
            $blocks[] = 'Compass/map';
            return $blocks;
        });

    }
}
