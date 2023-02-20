<?php
/**
 * Settings Ajax Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers\Admin\Ajax;

use RT\Team\Helpers\Fns;
use RT\Team\Helpers\Options;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Settings Ajax Class.
 */
class Settings {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'wp_ajax_tlpTeamSettings', [ $this, 'response' ] );
	}

	/**
	 * Ajax Response.
	 *
	 * @return void
	 */
	public function response() {
		$error    = true;
		$settings = [];
		if ( Fns::verifyNonce() ) {
			$_REQUEST['team-slug'] = isset( $_REQUEST['team-slug'] ) ? sanitize_title_with_dashes( $_REQUEST['team-slug'] ) : 'team';
			$options               = Options::getAllSettingOptions();
			if ( ! empty( $options ) ) {
				foreach ( $options as $optionId => $option ) {
					if ( isset( $_REQUEST[ $optionId ] ) ) {
						$settings[ $optionId ] = Fns::sanitize( $option, $_REQUEST[ $optionId ] );
					}
				}
			}
			update_option( rttlp_team()->options['settings'], $settings );
			flush_rewrite_rules();
			$error = false;
			$msg   = esc_html__( 'Settings successfully updated', 'tlp-team' );
		} else {
			$msg = esc_html__( 'Security Error !!', 'tlp-team' );
		}
		wp_send_json(
			[
				'error' => $error,
				'msg'   => $msg,
			]
		);
	}
}
