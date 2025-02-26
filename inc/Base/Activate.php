<?php
declare(strict_types=1);

namespace CompassPlugin\Base;

/**
 *
 */
class Activate {

	/**
	 * @return void
	 */
	public static function activate(): void {
		flush_rewrite_rules();
	}
}
