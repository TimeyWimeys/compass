<?php

/**
 * @package CompassPlugin
 */
/*
Plugin Name: Compass
Plugin URI: https://nomty.life
Description: Our tailored map plugin
Author: Nomty
Version: 1.1.0
Author URI: https://nomty.life
License: GPLv3 or later
Text Domain: nomty
*/

defined('ABSPATH') or die('Direct access is not allowed.');

// Load plugin files
if (class_exists('CompassPlugin\\Init')) {
    try {
        CompassPlugin\Init::register_services();
    } catch (Error $e) {
        error_log('Compass Plugin Error: ' . $e->getMessage() . ' (' . $e->getFile() . ' Line: ' . $e->getLine() . ')');
        return 'An error has occurred. Please check Compass > Help > Debug Info.';
    }
}

// Function to load settings
function cbn_load_settings()
{
    if (class_exists('CompassPlugin\Pages\Settings')) {
        $settings = new CompassPlugin\Pages\Settings();
        $settings->register();
    }
}
add_action('plugins_loaded', 'cbn_load_settings');

/**
 * Get a value from a location
 */
function cbn_get_location_value($attr, $post_id, $raw = false)
{
    $location_controller = new CompassPlugin\Base\LocationController();
    return $location_controller->get_location_value($attr, $post_id, $raw);
}

/**
 * Allow overriding templates in the theme directory
 */
function cbn_get_template($template_name): string
{
    $theme_template = get_stylesheet_directory() . '/Compass/' . $template_name;
    $plugin_template = plugin_dir_path(__FILE__) . 'templates/' . $template_name;

    return file_exists($theme_template) ? $theme_template : $plugin_template;
}
require_once plugin_dir_path(__FILE__) . 'inc/Pages/Settings.php';
