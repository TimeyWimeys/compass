<?php
declare(strict_types=1);

/**
 * @package CompassPlugin
 */

namespace CompassPlugin\Base;

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
        /** @noinspection HtmlUnknownTarget */
        $settings_link = '<a href="edit.php?post_type=cbn-location&page=compass-settings">Settings</a>';
        $links[] = $settings_link;

        return $links;
    }
}
