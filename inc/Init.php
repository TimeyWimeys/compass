<?php

/**
 * @package CompassPlugin
 */

namespace CompassPlugin;

final class Init
{
    public static function get_services(): array
    {
        error_log('register_services() wordt uitgevoerd');
        $classes = array(
            Pages\Settings::class,
            Base\Enqueue::class,
            Base\SettingsLinks::class,
            Base\LocationControl::class,
            Base\BlockController::class,
            Base\TaxController::class,
        );
        error_log("Services geregistreerd...");

        if (!is_admin()) {
            array_unshift($classes, Pages\Frontend::class);
        }

        return $classes;
    }

    public static function register_services(): void
    {
        foreach (self::get_services() as $class) {
            $service = self::instantiate($class);

            if (method_exists($service, 'register')) {
                $service->register();
            }
        }
    }

    private static function instantiate($class)
    {
        return new $class();
    }
}
