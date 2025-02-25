<?php

/**
 * @package CompassPlugin
 */
/*
Plugin Name: Compass
Plugin URI: https://nomty.life
Description: Our tailored map plugin
Author: Nomty
Version: 1.1.2
Author URI: https://nomty.life
License: GPLv3 or later
Text Domain: nomty
*/

defined('ABSPATH') or die('Direct access is not allowed.');

/**
 * Dynamisch alle bestanden inladen uit specifieke mappen
 */
function cbn_include_files($directory): void
{
    foreach (glob(plugin_dir_path(__FILE__) . "$directory/*.php") as $file) {
        require_once $file;
    }
}

// Laden van alle bestanden in "Base" en "Pages"
cbn_include_files('inc/Base');
cbn_include_files('inc/Pages');

if (class_exists('CompassPlugin\Base\Deactivate')) {
    register_deactivation_hook(__FILE__, ['CompassPlugin\Base\Deactivate', 'deactivate']);
}
/**
 * Functie om de instellingen te laden
 */
function cbn_load_settings(): void
{
    if (class_exists('CompassPlugin\Pages\Settings')) {
        $settings = new CompassPlugin\Pages\Settings();
        $settings->register();
    }
}

// Zorg ervoor dat instellingen geladen worden
add_action('plugins_loaded', 'cbn_load_settings');

/**
 * Ophalen van locatiegegevens
 */
function cbn_get_location_value($attr, $post_id, $raw = false) {
    $location_controller = new CompassPlugin\Base\LocationControl();
    return $location_controller->get_location_value($attr, $post_id, $raw);
}

/**
 * Zoek een template in de thema-map, of val terug op de plugin-map
 */
function cbn_get_template($template_name): string {
    $theme_template = get_stylesheet_directory() . '/Compass/' . $template_name;
    $plugin_template = plugin_dir_path(__FILE__) . 'templates/' . $template_name;

    return file_exists($theme_template) ? $theme_template : $plugin_template;
}