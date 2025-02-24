<?php

/**
 * @package CompassPlugin
 */

namespace CompassPlugin\Base;

class SettingsLinks extends BaseController
{
    public function register()
    {
        add_filter('plugin_action_links_' . $this->plugin, array($this, 'settings_link'));
    }

    public function settings_link($links)
    {
        /** @noinspection HtmlUnknownTarget */
        $settings_link = '<a href="edit.php?post_type=cbn-location&page=Compass-settings">Settings</a>';
        $links[] = $settings_link;

        return $links;
    }
}
