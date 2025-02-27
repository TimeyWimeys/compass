<?php
/**
 * @package OpenUserMapPlugin
 */

namespace OpenUserMapPlugin\Base;

/**
 *
 */
class Activate
{
    /**
     * @return void
     */
    public static function activate(): void
    {
        flush_rewrite_rules();
    }
}
