<?php
/**
 * Activation & Deactivation actions.
 *
 * @package RT/Team
 */

namespace RT\Team\Helpers;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Activation & Deactivation actions.
 */
class Install {
	/**
	 * Activation actions.
	 *
	 * @return void
	 */
	public static function activate() {
		$activation = strtotime( 'now' );

		add_option( 'rtteam_plugin_activation_time', $activation );
		update_option( 'rtteam_activation_redirect', true );

		\flush_rewrite_rules();
	}

	/**
	 * Deactivation actions.
	 *
	 * @return void
	 */
	public static function deactivate() {
		\flush_rewrite_rules();
	}
}
