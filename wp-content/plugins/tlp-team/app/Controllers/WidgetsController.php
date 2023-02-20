<?php
/**
 * Widget Controller Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers;

use RT\Team\Widgets as Widgets;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Widget Controller Class.
 */
class WidgetsController {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'widgets_init', [ $this, 'load_widgets' ] );
	}

	/**
	 * Load widgets.
	 *
	 * @return void
	 */
	public function load_widgets() {
		$widgets = [
			Widgets\TeamWidget::class,
			Widgets\TeamCarousel::class,
			Widgets\TeamShortcodeWidget::class,
		];

		foreach ( $widgets as $widget ) {
			register_widget( $widget );
		}
	}
}
