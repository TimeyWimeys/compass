<?php
declare(strict_types=1);

/**
 * @package CompassPlugin
 */

namespace CompassPlugin;

/**
 *
 */
final class Init
{
    /**
     * @return string[]
     */
    public static function get_services(): array
    {
        error_log('register_services() wordt uitgevoerd');
        $classes = [
            Pages\Settings::class,
            Base\Enqueue::class,
            Base\SettingsLinks::class,
            Base\LocationControl::class,
            Base\BlockController::class,
            Base\TaxController::class,
        ];
        error_log('Services geregistreerd...');

        if (!is_admin()) {
            array_unshift($classes, Pages\Frontend::class);
        }

        return $classes;
    }

    /**
     * @return void
     */
    public static function register_services(): void
    {
        foreach (self::get_services() as $class) {
            $service = self::instantiate($class);

            if (method_exists($service, 'register')) {
                $service->register();
            }
        }
    }

    /**
     * @param $class
     * @return mixed
     */
    private static function instantiate($class): mixed
    {
        return new $class();
    }
}

/**
 * @param $links
 * @return mixed
 */
function cbn_add_settings_link($links): mixed
{
    $settings_link = '<a href="admin.php?page=cbn-settings">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'cbn_add_settings_link');