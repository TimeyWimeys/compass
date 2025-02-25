<?php

namespace CompassPlugin\Base;

class Deactivate {
    public static function deactivate(): void
    {
        flush_rewrite_rules();
    }
}