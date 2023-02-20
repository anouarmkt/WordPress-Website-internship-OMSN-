<?php
/**
 * Plugin Name: Team
 * Plugin URI: https://radiustheme.com/tlp-team-for-wordpress/
 * Description: Team is a fully responsive and mobile friendly team member profile display plugin.
 * Author: RadiusTheme
 * Version: 4.1.5
 * Author URI: www.radiustheme.com
 * Text Domain: tlp-team
 * Domain Path: /languages
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Defining Constants.
 */
define( 'TLP_TEAM_NAME', 'Team' );
define( 'TLP_TEAM_VERSION', '4.1.5' );
define( 'TLP_TEAM_AUTHOR', 'RadiusTheme' );
define( 'EDD_TLP_TEAM_STORE_URL', 'https://www.radiustheme.com' );
define( 'EDD_TLP_TEAM_ITEM_ID', 523 );
define( 'TLP_TEAM_PLUGIN_PATH', dirname( __FILE__ ) );
define( 'TLP_TEAM_DOWNLOAD_PATH', dirname( __FILE__ ) . '/temp/' );
define( 'TLP_TEAM_PLUGIN_ACTIVE_FILE_NAME', __FILE__ );
define( 'TLP_TEAM_PLUGIN_URL', plugins_url( '', __FILE__ ) );
define( 'TLP_TEAM_LANGUAGE_PATH', dirname( plugin_basename( __FILE__ ) ) . '/languages' );

/**
 * App Init.
 */
if ( ! class_exists( 'RttlpTeam' ) ) {
	require_once 'app/RttlpTeam.php';
}

register_activation_hook( __FILE__, 'activate_rttlp_team' );
/**
 * Plugin activation action.
 *
 * Plugin activation will not work after "plugins_loaded" hook
 * that's why activation hooks run here.
 */
function activate_rttlp_team() {
	\RT\Team\Helpers\Install::activate();
}

register_deactivation_hook( __FILE__, 'deactivate_rttlp_team' );
/**
 * Plugin deactivation action.
 *
 * Plugin deactivation will not work after "plugins_loaded" hook
 * that's why deactivation hooks run here.
 */
function deactivate_rttlp_team() {
	\RT\Team\Helpers\Install::deactivate();
}

/**
 * Support for deprecated methods with the previous
 * version of PRO plugin (v3).
 */
if ( ! class_exists( TLPTeam::class ) ) {
	require_once 'app/Helpers/Deprecated.php';

	/**
	 * Returns Deprecated.
	 *
	 * @return Deprecated
	 */
	function TLPTeam() {
		return new \RT\Team\Helpers\Deprecated();
	}
}
