<?php
declare(strict_types=1);
/**
 * @package OpenUserMapPlugin
 */

namespace OpenUserMapPlugin\Pages;

use Elementor_OUM_Addon\Plugin;
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
        add_action('init', [$this, 'set_shortcodes']);

        if (oum_fs()->is__premium_only()):
            if (oum_fs()->can_use_premium_code()):

                //PRO: Add user location within registration
                if (get_option('oum_enable_add_user_location')):
                    add_action('register_form', [$this, 'render_block_add_user_location__premium_only']);
                    add_action('user_register', [$this, 'add_user_location__premium_only']);
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

            if (Plugin::is_elementor_backend()) {
                error_log('OUM: prevented shortcode rendering inside Elementor');
                return;
            }

        }

        // Render Map
        add_shortcode('open-user-map', [$this, 'render_block_map']);

        //PRO: Render Image Gallery (Shortcode)
        if (oum_fs()->is__premium_only()):
            if (oum_fs()->can_use_premium_code()):

                add_shortcode('open-user-map-gallery', [$this, 'render_block_gallery__premium_only']);

            endif;
        endif;

        //PRO: Render Location Value (Shortcode)
        if (oum_fs()->is__premium_only()):
            if (oum_fs()->can_use_premium_code()):

                add_shortcode('open-user-map-location', [$this, 'render_block_location__premium_only']);

            endif;
        endif;

        //PRO: Render Locations List  (Shortcode)
        if (oum_fs()->is__premium_only()):
            if (oum_fs()->can_use_premium_code()):

                add_shortcode('open-user-map-list', [$this, 'render_block_list__premium_only']);

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
