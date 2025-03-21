<?php /** @noinspection PhpUndefinedMethodInspection */

/**
 * @package OpenUserMapPlugin
 */
/*
Plugin Name: Open User Map (Premium)
Plugin URI: https://wordpress.org/plugins/open-user-map/
Description: Engage your visitors with an interactive map – let them add markers instantly or create a custom map showcasing your favorite spots.
Author: 100plugins
Version: 1.4.3
Update URI: https://api.freemius.com
Author URI: https://www.open-user-map.com/
License: GPLv3 or later
Text Domain: open-user-map
*/
/*
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.

Copyright 2025 100plugins
*/
defined('ABSPATH') or die('Direct access is not allowed.');

// Special uninstall routine (zonder Freemius)
/**
 * @return void
 */
function oum_uninstall_cleanup(): void
{
    global $wpdb;
    // Verwijder OUM locaties
    $wpdb->query("DELETE FROM " . $wpdb->prefix . "posts WHERE post_type='oum-location'");
    // Verwijder postmeta
    $wpdb->query("DELETE FROM " . $wpdb->prefix . "postmeta WHERE meta_key LIKE '%oum_%'");
    // Verwijder opties
    $wpdb->query("DELETE FROM " . $wpdb->prefix . "options WHERE option_name LIKE 'oum_%'");
}

// Voeg de uninstall routine toe zonder afhankelijkheid van Freemius
register_uninstall_hook(__FILE__, 'oum_uninstall_cleanup');
// ... Your plugin's main file logic ...
// Require once the composer autoload
if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}
/**
 * The code that runs during plugin activation
 */
function oum_activate_plugin(): void
{
    OpenUserMapPlugin\Base\Activate::activate();
}

register_activation_hook(__FILE__, 'oum_activate_plugin');
/**
 * The code that runs during plugin deactivation
 */
function oum_deactivate_plugin(): void
{
    OpenUserMapPlugin\Base\Deactivate::deactivate();
}

register_deactivation_hook(__FILE__, 'oum_deactivate_plugin');
/**
 * Initialize all the core classes of the plugin
 */
if (class_exists('OpenUserMapPlugin\\Init')) {
    // OpenUserMapPlugin\Init::register_services();
    try {
        OpenUserMapPlugin\Init::register_services();
    } catch (\Error $e) {
        error_log($e->getMessage() . ' (' . $e->getFile() . ' Line: ' . $e->getLine() . ')');
        return 'An error has occurred. Please look in the settings under Open User Map > Help > Debug Info.';
    }
}
/**
 * Get a value from a location (public function)
 *
 * possible attributes:
 * - title
 * - image
 * - audio
 * - video
 * - type
 * - map
 * - address
 * - lat
 * - lng
 * - route
 * - text
 * - notification
 * - author_name
 * - author_email
 * - wp_author_id
 * - CUSTOM FIELD LABEL
 */
function oum_get_location_value($attr, $post_id, $raw = false)
{
    $location_controller = new OpenUserMapPlugin\Base\LocationController();
    return $location_controller->get_location_value($attr, $post_id, $raw);
}

/**
 * Allow to get the template from the theme directory (template override)
 *
 * Just add a folder "open-user-map" in your theme directory and copy the template file you want to override.
 * Be aware that new features may then not be available or even break the functionality!
 */
function oum_get_template($template_name): string
{
    // Pad naar de template in het thema
    $theme_template = get_stylesheet_directory() . '/open-user-map/' . $template_name;

    // Pad naar de template in de plugin
    $plugin_template = plugin_dir_path(__FILE__) . 'templates/' . $template_name;

    // Controleer of de template in het thema staat, anders gebruik de plugin-template
    if (file_exists($theme_template)) {
        return $theme_template;
    }
    return $plugin_template;
}