<?php
declare(strict_types=1);
/**
 * @package OpenUserMapPlugin
 */

namespace OpenUserMapPlugin\Base;

use OpenUserMapPlugin\Base\BaseController;

/**
 *
 */
class SettingsLinks extends BaseController
{

    /**
     * @return void
     */
    public function register(): void
    {
        add_filter('plugin_action_links_' . $this->plugin, [$this, 'settings_link']);
    }

    /**
     * @param $links
     * @return mixed
     */
    public function settings_link($links): mixed
    {
        $settings_link = '<a href="edit.php?post_type=oum-location&page=open-user-map-settings">Settings</a>';
        $links[] = $settings_link;

        return $links;
    }
}
