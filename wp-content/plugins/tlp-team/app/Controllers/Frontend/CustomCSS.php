<?php
/**
 * Frontend Custom CSS Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers\Frontend;

use RT\Team\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Frontend Custom CSS Class.
 */
class CustomCSS {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'wp_head', [ $this, 'custom_css' ] );
	}

	/**
	 * Custom CSS.
	 *
	 * @return void
	 */
	public function custom_css() {
		$settings = get_option( rttlp_team()->options['settings'] );
		$output   = null;

		if ( ! empty( $settings['custom_css'] ) ) {
			$output .= "<style>{$settings['custom_css']}</style>";
		}

		Fns::print_html( $output, true );
	}
}
