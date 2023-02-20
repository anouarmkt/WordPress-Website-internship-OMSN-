<?php

namespace Etn\Traits;

defined( 'ABSPATH' ) || exit;

/**
 * Singleton trait
 * get instance
 *
 * @since 1.0.0
 */
trait Singleton {

	private static $instance;

	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new static();
		}
		return self::$instance;
	}
}
