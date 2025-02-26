<?php
declare(strict_types=1);

namespace CompassPlugin\Base;

/**
 *
 */
class Deactivate {

	/**
	 * @return void
	 */
	public static function deactivate(): void {
		flush_rewrite_rules();
	}
}
