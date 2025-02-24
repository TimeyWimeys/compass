<?php

/**
 * @package CompassPlugin
 */

namespace CompassPlugin\Base;

class Activate
{
    public static function activate()
    {
        flush_rewrite_rules();
    }
}
