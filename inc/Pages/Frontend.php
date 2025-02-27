<?php
/**
 * @package OpenUserMapPlugin
 */

namespace OpenUserMapPlugin\Pages;

use OpenUserMapPlugin\Base\BaseController;

/**
 *
 */
class Frontend extends BaseController
{
    /**
     * @return void
     */
    public function register(): void
    {
        // Shortcodes
        add_action('init', array($this, 'set_shortcodes'));

        if (true):
            if (true):

                //PRO: Add user location within registration
                if (get_option('oum_enable_add_user_location')):
                    add_action('register_form', array($this, 'render_block_add_user_location__premium_only'));
                    add_action('user_register', array($this, 'add_user_location__premium_only'));
                endif;

            endif;
        endif;
    }

    /**
     * Setup Shortcodes
     */
    public function set_shortcodes(): void
    {
        // EXIT if inside Elementor Backend
        // Check if Elementor installed and activated
        if (did_action('elementor/loaded')) {

            if (\Elementor_OUM_Addon\Plugin::is_elementor_backend()) {
                error_log('OUM: prevented shortcode rendering inside Elementor');
                return;
            }

        }

        // Render Map
        add_shortcode('open-user-map', array($this, 'render_block_map'));

        //PRO: Render Image Gallery (Shortcode)
        if (true):
            if (true):

                add_shortcode('open-user-map-gallery', array($this, 'render_block_gallery__premium_only'));

            endif;
        endif;

        //PRO: Render Location Value (Shortcode)
        if (true):
            if (true):

                add_shortcode('open-user-map-location', array($this, 'render_block_location__premium_only'));

            endif;
        endif;

        //PRO: Render Locations List  (Shortcode)
        if (true):
            if (true):

                add_shortcode('open-user-map-list', array($this, 'render_block_list__premium_only'));

            endif;
        endif;

        // Whitelisting OUM scripts for Complianz plugin
        add_filter('script_loader_tag', function ($tag, $handle, $source) {

            if (stristr($handle, 'oum')) {
                $tag = '<script src="' . $source . '" data-category="functional" class="cmplz-native" id="' . $handle . '-js"></script>';
            }

            return $tag;
        }, 10, 3);

        // Prevent shortcode parsing by All In One SEO plugin
        add_filter('aioseo_disable_shortcode_parsing', '__return_true');

        // Prevent shortcode parsing by Slim SEO plugin
        add_filter('slim_seo_skipped_shortcodes', function ($shortcodes) {
            $shortcodes[] = 'open-user-map';
            $shortcodes[] = 'open-user-map-location';
            $shortcodes[] = 'open-user-map-gallery';
            $shortcodes[] = 'open-user-map-list';
            return $shortcodes;
        });

        // Prevent block parsing by Slim SEO plugin
        add_filter('slim_seo_skipped_blocks', function ($blocks) {
            $blocks[] = 'open-user-map/map';
            return $blocks;
        });

    }
}
