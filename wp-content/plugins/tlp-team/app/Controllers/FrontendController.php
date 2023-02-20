<?php
/**
 * Public Controller Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers;

use RT\Team\Widgets as Widgets;
use RT\Team\Abstracts\Controller;
use RT\Team\Controllers\Hooks\FilterHooks;
use RT\Team\Controllers\Frontend as Frontend;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Admin Controller Class.
 */
class FrontendController extends Controller {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Classes to include.
	 *
	 * @return array
	 */
	public function classes() {
		$classes  = [];
		$settings = get_option( rttlp_team()->options['settings'] );
		$settings = isset( $settings['tlp_team_block_type'] ) ? esc_html( $settings['tlp_team_block_type'] ) : 'default';

		$classes[] = FilterHooks::class;
		$classes[] = Widgets\Vc\VcAddon::class;
		$classes[] = Frontend\CustomCSS::class;
		$classes[] = Frontend\Shortcode::class;
		$classes[] = Frontend\Template::class;
		$classes[] = Frontend\Ajax\LoadMore::class;

		if ( in_array( $settings, [ 'default', 'elementor' ], true ) ) {
			$classes[] = Frontend\ElementorAddons::class;
		}

		return $classes;
	}
}
