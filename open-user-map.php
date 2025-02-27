<?php
declare(strict_types=1);

/**
 * @package OpenUserMapPlugin
 */
/*
Plugin Name: Compass
Plugin URI: https://nomty.life
Description: Engage your visitors with an interactive map – let them add markers instantly or create a custom map showcasing your favorite spots.
Author: Nomty
Version: 1.0.0
Author URI: https://nomty.life
License: GPLv3 or later
Text Domain: nomty
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

Copyright 2025 Nomty
*/
defined('ABSPATH') or die('Direct access is not allowed.');

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
    // Define the paths to the template locations
    $theme_template = get_stylesheet_directory() . '/open-user-map/' . $template_name;
    $plugin_template = plugin_dir_path(__FILE__) . 'templates/' . $template_name;
    // Check if the template exists in the theme directory
    if (file_exists($theme_template)) {
        return $theme_template;
    } else {
        return $plugin_template;
    }
}