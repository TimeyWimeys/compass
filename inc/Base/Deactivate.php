<?php
/**
 * @package OpenUserMapPlugin
 */

namespace OpenUserMapPlugin\Base;

/**
 *
 */
class Deactivate
{
    /**
     * @return void
     */
    public static function deactivate(): void
    {
        flush_rewrite_rules();
    }
}
