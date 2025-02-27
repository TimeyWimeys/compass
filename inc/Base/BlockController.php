<?php
declare(strict_types=1);

/**
 * @package CompassPlugin
 */

namespace CompassPlugin\Base;

use Elementor_cbn_Addon\Plugin;

/**
 *
 */
class BlockController extends BaseController
{

    /**
     * @return void
     */
    public function register(): void
    {
        // Gutenberg Blocks
        add_action('init', [$this, 'set_gutenberg_blocks']);

        // Elementor Widgets
        add_action('plugins_loaded', [$this, 'set_elementor_widgets']);
    }

    /**
     * Setup Gutenberg Blocks
     */
    public function set_gutenberg_blocks(): void
    {
        // Register Block
        register_block_type(
            $this->plugin_path . 'blocks',
            [
                'render_callback' => is_admin() ? null : [$this, 'render_block_map'],
            ]
        );

        // add JS translation for Gutenberg Blocks script
        /*
        Pay Attention:
        - currently doesnt work with wordpress.org translation --> use local translation file
        - Translation file needs to be called "Compass-de_DE-cbn_blocks_script.json"
        - Howto: https://developer.wordpress.org/block-editor/how-to-guides/internationalization/
         */
        // wp_set_script_translations(
        //     'cbn_blocks_script',
        //     'compass',
        //     $this->plugin_path . 'languages'
        // );
    }

    /**
     * Setup Elementor Widgets
     */
    public function set_elementor_widgets(): void
    {
        require_once "$this->plugin_path/elementor/includes/plugin.php";

        // Run the plugin
        Plugin::instance();
    }
}
