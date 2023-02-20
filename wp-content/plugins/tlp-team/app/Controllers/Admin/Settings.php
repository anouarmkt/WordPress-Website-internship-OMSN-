<?php
/**
 * Admin Settings Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers\Admin;

use RT\Team\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Admin Settings Class.
 */
class Settings {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'init', [ $this, 'image_size' ] );
		add_action( 'plugins_loaded', [ $this, 'text_domain' ] );
		add_action( 'admin_menu', [ $this, 'tlp_menu_register' ], 15 );

		add_filter( 'plugin_action_links_' . plugin_basename( TLP_TEAM_PLUGIN_ACTIVE_FILE_NAME ), [ $this, 'marketing' ] );
	}

	/**
	 * Add image size.
	 *
	 * @return void
	 */
	public function image_size() {
		$settings = get_option( rttlp_team()->options['settings'] );
		$width    = ( isset( $settings['feature_img']['width'] ) ? ( $settings['feature_img']['width'] ? (int) $settings['feature_img']['width'] : 400 ) : 400 );
		$height   = ( isset( $settings['feature_img']['height'] ) ? ( $settings['feature_img']['height'] ? (int) $settings['feature_img']['height'] : 400 ) : 400 );
		add_image_size( rttlp_team()->options['feature_img_size'], $width, $height, true );
	}

	/**
	 * Load plugin text domain.
	 *
	 * @return void
	 */
	public function text_domain() {
		load_plugin_textdomain( 'tlp-team', false, TLP_TEAM_LANGUAGE_PATH );
	}

	/**
	 * Admin menu.
	 *
	 * @return void
	 */
	public function tlp_menu_register() {
		add_submenu_page(
			'edit.php?post_type=' . rttlp_team()->post_type,
			esc_html__( 'TLP TEAM Settings', 'tlp-team' ),
			esc_html__( 'Settings', 'tlp-team' ),
			'administrator',
			'tlp_team_settings',
			[
				$this,
				'render_settings_page',
			]
		);

		add_submenu_page(
			'edit.php?post_type=' . rttlp_team()->post_type,
			esc_html__( 'TLP TEAM GET HELP', 'tlp-team' ),
			esc_html__( 'Get Help', 'tlp-team' ),
			'administrator',
			'tlp_team_get_help',
			[
				$this,
				'render_help_page',
			]
		);
	}

	/**
	 * Render Settings.
	 *
	 * @return void|string
	 */
	public function render_settings_page() {
		Fns::render_view( 'settings' );
	}

	/**
	 * Render Help.
	 *
	 * @return void|string
	 */
	public function render_help_page() {
		Fns::render_view( 'get-help' );
	}

	/**
	 * Render Import/Export.
	 *
	 * @return void|string
	 */

	/**
	 * Action link.
	 *
	 * @param array $links Links.
	 * @return array
	 */
	public function marketing( $links ) {
		$links[] = '<a target="_blank" href="' . esc_url( 'https://www.radiustheme.com/demo/plugins/team/layout-1' ) . '">Demo</a>';
		$links[] = '<a target="_blank" href="' . esc_url( rttlp_team()->documentation_link() ) . '">Documentation</a>';

		if ( ! function_exists( 'rttmp' ) ) {
			$links[] = '<a target="_blank" style="color: #39b54a;font-weight: 700;" href="' . esc_url( rttlp_team()->pro_version_link() ) . '">Get Pro</a>';
		}

		return $links;
	}
}
